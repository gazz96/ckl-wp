# CKL Booking System Architecture Documentation

## Overview

The CKL website implements a **hybrid booking system** that leverages multiple WooCommerce booking solutions to handle different types of reservations:

- **Accommodations/Hotels** → WooCommerce Accommodation Bookings
- **Car Rentals** → Custom System built on WooCommerce Bookings

This document provides a comprehensive overview of the architecture, components, and integration patterns used.

---

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                      WOOCOMMERCE CORE                            │
│                   (E-commerce Foundation)                        │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │
         ┌───────────────────┴───────────────────┐
         │                                       │
         ▼                                       ▼
┌─────────────────────┐           ┌─────────────────────────────┐
│  WC Bookings        │           │  WC Accommodation           │
│  (Base Plugin v3.0.2)│           │  Bookings v1.3.7           │
│                     │           │                             │
│ - Day-based         │           │ - Night-based               │
│ - Hour-based        │           │ - Check-in/out              │
│ - Staff/resources   │           │ - Range availability         │
│ - General services  │           │ - Minimum/Maximum stay      │
└─────────┬───────────┘           └──────────┬──────────────────┘
          │                                   │
          │                                   │
          │              ┌────────────────────┴────────────┐
          │              │                                 │
          ▼              ▼                                 ▼
    ┌──────────┐  ┌───────────┐               ┌───────────────────┐
    │  Custom  │  │  Vehicle  │               │  Accommodation     │
    │  CKL     │  │  CPT      │               │  Booking Products  │
    │  Car     │  │  (synced  │               │  (hotel rooms,     │
    │  Rental  │  │   to WC   │               │   villas, etc.)    │
    │          │  │   Booking)│               │                    │
    └──────────┘  └───────────┘               └───────────────────┘
```

---

## Plugin Components

### 1. WooCommerce Bookings (v3.0.2)

**Location:** `/web/app/plugins/woocommerce-bookings/`

**Main File:** `woocommerce-bookings.php`

**Core Classes:**
- `WC_Bookings` - Main plugin class
- `WC_Product_Booking` - Booking product type (line 13 in `/includes/data-objects/class-wc-product-booking.php`)
- `WC_Booking` - Booking data object
- `WC_Booking_Cart_Manager` - Cart management
- `WC_Booking_Order_Manager` - Order management

**Features:**
- Day-based and hour-based booking durations
- Resource and staff management
- Availability management
- Pricing rules
- Google Calendar integration

### 2. WooCommerce Accommodation Bookings (v1.3.7)

**Location:** `/web/app/plugins/woocommerce-accommodation-bookings/`

**Main File:** `woocommerce-accommodation-bookings.php`

**Core Classes:**
- `WC_Accommodation_Bookings_Plugin` - Main plugin class
- `WC_Product_Accommodation_Booking` - Accommodation booking product type
- `WC_Accommodation_Booking` - Accommodation booking data object
- `WC_Accommodation_Booking_Cart_Manager` - Cart management for accommodations
- `WC_Accommodation_Booking_Order_Manager` - Order management for accommodations

**Features:**
- Night-based durations (not day-based)
- Check-in/check-out date ranges
- Minimum stay requirements
- Maximum stay limits
- Check-in day restrictions
- Seasonal pricing

### 3. CKL Car Rental (v1.0.0)

**Location:** `/web/app/plugins/ckl-car-rental/`

**Main File:** `ckl-car-rental.php`

**Dependencies (lines 92-102):**
- Requires WooCommerce
- Requires WooCommerce Bookings

**Core Classes:**
- `CKL_Car_Rental` - Main plugin class
- `CKL_Booking_Manager` - Booking management for vehicles
- `CKL_Late_Fees` - Late fee calculation
- `CKL_Reviews` - Vehicle review system
- `CKL_Calendar_Sync` - Calendar synchronization
- `CKL_Analytics` - Booking analytics
- `CKL_Dynamic_Pricing` - Dynamic pricing rules
- `CKL_Block_Dates` - Date blocking for maintenance
- `CKL_Location_System` - Pickup/return locations
- `CKL_User_Roles` - Custom user roles
- `CKL_My_Account_Endpoints` - Customer dashboard

---

## Custom Post Types

### Vehicle CPT

**Location:** `/web/app/mu-plugins/custom-post-types.php`

**Registration (lines 7-53):**
- Post Type: `vehicle`
- Public: true
- Menu Icon: `dashicons-car`
- Supports: title, editor, thumbnail
- Rewrite Slug: `vehicle`

**Meta Fields (lines 75-181):**
- `_vehicle_type` - Vehicle type (sedan, compact, mpv, luxury_mpv, suv, 4x4, motorcycle)
- `_vehicle_passenger_capacity` - Number of passengers
- `_vehicle_doors` - Number of doors
- `_vehicle_luggage` - Luggage capacity
- `_vehicle_has_air_conditioning` - Air conditioning (checkbox)
- `_vehicle_transmission` - Transmission type (automatic/manual)
- `_vehicle_fuel_type` - Fuel type
- `_vehicle_plate_number` - Vehicle plate number
- `_vehicle_units_available` - Available units
- `_vehicle_price_per_day` - Daily rental price
- `_vehicle_late_fee_per_hour` - Late fee per hour
- `_vehicle_grace_period_minutes` - Grace period before late fees

**WooCommerce Sync (lines 232-299):**
The Vehicle CPT automatically syncs with WooCommerce Bookings:
1. Creates a `WC_Product_Booking` product when vehicle is created
2. Sets duration unit to `day`
3. Sets minimum duration to 1 day
4. Sets maximum duration to 30 days
5. Syncs price and availability

**Sync Meta Fields:**
- `_vehicle_woocommerce_product_id` - Linked WooCommerce product ID
- `_vehicle_sync_status` - Sync status
- `_vehicle_last_synced` - Last sync timestamp

---

## Booking Flow Comparison

### Accommodation Booking Flow

```
1. User selects dates (check-in & check-out)
   ↓
2. System calculates nights (not days)
   ↓
3. Shows availability for range
   ↓
4. User selects room/unit
   ↓
5. Add to cart with range pricing
   ↓
6. Checkout
   ↓
7. Booking created with check-in/out times
```

### Car Rental Booking Flow

```
1. User searches vehicles (location, dates)
   ↓
2. System checks availability (day-based)
   ↓
3. User selects vehicle
   ↓
4. Calculates pricing (daily rate × days)
   ↓
5. User adds pickup/return location
   ↓
6. Add to cart
   ↓
7. Checkout
   ↓
8. Booking created with pickup/return times
   ↓
9. Late fees applied if return is late
```

---

## Key Differences

| Feature | WC Bookings | WC Accommodation Bookings | CKL Car Rental |
|---------|-------------|---------------------------|----------------|
| Duration Unit | Day/Hour | Night | Day |
| Date Selection | Single date or range | Range (check-in to check-out) | Range (pickup to return) |
| Pricing | Per day/hour | Per night | Per day |
| Minimum Duration | Configurable | Minimum nights | 1 day |
| Maximum Duration | Configurable | Maximum nights | 30 days |
| Check-in/Out | No | Yes (with times) | Yes (with locations) |
| Product Type | `booking` | `accommodation-booking` | `booking` (custom) |
| Custom CPT | No | No | Yes (`vehicle`) |

---

## Database Schema

### WooCommerce Bookings Tables

```sql
-- Main bookings table
wp_wc_bookings
├── id
├── product_id
├── start_date
├── end_date
├── status
├── order_id
├── customer_id
├── created_by

-- Availability meta
wp_wc_bookings_availabilitymeta
├── meta_id
├── availability_id
├── meta_key
├── meta_value
```

### Vehicle CPT Meta

```sql
-- Vehicle post meta
wp_postmeta (for vehicle posts)
├── _vehicle_type
├── _vehicle_passenger_capacity
├── _vehicle_price_per_day
├── _vehicle_late_fee_per_hour
├── _vehicle_woocommerce_product_id
└── ... (other vehicle fields)

-- Product post meta (for linked booking products)
wp_postmeta (for product posts)
├── _booking_class (value: WC_Product_Booking)
├── _booking_duration
├── _booking_duration_unit
├── _min_booking_duration
├── _max_booking_duration
├── _vehicle_id (back-reference)
└── ... (other booking fields)
```

---

## API Endpoints

### WooCommerce Bookings REST API

```
GET /wp-json/wc-bookings/v1/products
GET /wp-json/wc-bookings/v1/products/<id>
GET /wp-json/wc-bookings/v1/bookings
POST /wp-json/wc-bookings/v1/bookings
GET /wp-json/wc-bookings/v1/bookings/<id>
PUT /wp-json/wc-bookings/v1/bookings/<id>
DELETE /wp-json/wc-bookings/v1/bookings/<id>
```

### CKL Car Rental Custom Endpoints

The CKL Car Rental plugin adds custom AJAX handlers:
- Vehicle search
- Location filtering
- Availability checking
- Late fee calculation

---

## Frontend Integration

### Booking Forms

**WooCommerce Bookings:**
- Uses `wc-bookings-booking-form` script
- Date picker component
- Duration selector
- Resource selector

**Accommodation Bookings:**
- Uses `wc-accommodation-bookings-form` script
- Extends base booking form
- Range-based date picker
- Check-in/out time selector

**CKL Car Rental:**
- Custom booking form
- Hero search component
- Location selector
- Vehicle filtering

---

## Pricing Logic

### Accommodation Pricing

```
Total = (Base Rate × Number of Nights) + Seasonal Adjustments + Person Type Fees
```

Example:
- Base Rate: RM 200/night
- Nights: 3
- Total: RM 200 × 3 = RM 600

### Car Rental Pricing

```
Total = (Daily Rate × Number of Days) + Location Fees + Insurance + Late Fees
```

Example:
- Daily Rate: RM 150/day
- Days: 2
- Late Fee: RM 20/hour × 2 hours = RM 40
- Total: (RM 150 × 2) + RM 40 = RM 340

---

## Late Fee Calculation (CKL Car Rental)

**Location:** `/web/app/plugins/ckl-car-rental/includes/class-late-fees.php`

**Logic:**
1. Get scheduled return time
2. Get actual return time
3. Calculate difference
4. Apply grace period (vehicle-specific or system default)
5. Calculate late fee per hour (vehicle-specific or system default)
6. Add to booking total

---

## Availability Management

### WooCommerce Bookings

- Global availability rules
- Product-specific availability
- Resource-based availability
- Staff-based availability

### Accommodation Bookings

- Range-based availability
- Check-in day restrictions
- Minimum/Maximum stay limits
- Seasonal availability

### CKL Car Rental

- Vehicle-specific availability
- Location-based availability
- Blocked dates for maintenance
- Sync with WooCommerce Bookings availability

---

## Customization Points

### Adding Custom Booking Types

To add a new booking type:

1. Extend `WC_Product_Booking` class
2. Register product type with `product_type_selector` filter
3. Implement custom pricing logic
4. Add custom meta boxes
5. Create custom booking forms

### Customizing Vehicle Fields

Edit `/web/app/mu-plugins/custom-post-types.php`:
1. Add field to meta box HTML (line 75)
2. Add field to save function (line 201)
3. Sync to WooCommerce if needed (line 235)

---

## Troubleshooting

### Bookings Not Syncing

**Problem:** Vehicle CPT not syncing to WooCommerce products

**Solutions:**
1. Check if WooCommerce Bookings is active
2. Check if `CKL_Booking_Manager` class exists
3. Verify `_vehicle_woocommerce_product_id` meta
4. Check sync status in `_vehicle_sync_status`

### Pricing Not Calculating

**Problem:** Incorrect pricing calculations

**Solutions:**
1. Verify duration unit (day vs night)
2. Check seasonal pricing rules
3. Verify minimum/maximum duration
4. Check for pricing conflicts

### Availability Issues

**Problem:** Availability not showing correctly

**Solutions:**
1. Clear bookings cache
2. Check global availability rules
3. Verify resource availability
4. Check for conflicting bookings

---

## File Reference

### Core Files

| File | Purpose |
|------|---------|
| `/web/app/plugins/woocommerce-bookings/woocommerce-bookings.php` | Main WC Bookings plugin |
| `/web/app/plugins/woocommerce-accommodation-bookings/woocommerce-accommodation-bookings.php` | Main Accommodation Bookings extension |
| `/web/app/plugins/ckl-car-rental/ckl-car-rental.php` | Main CKL Car Rental plugin |
| `/web/app/mu-plugins/custom-post-types.php` | Vehicle CPT registration and sync |

### Key Classes

| Class | Location | Purpose |
|-------|----------|---------|
| `WC_Bookings` | `woocommerce-bookings.php:108` | Main bookings class |
| `WC_Product_Booking` | `includes/data-objects/class-wc-product-booking.php` | Booking product |
| `WC_Accommodation_Bookings_Plugin` | `includes/class-wc-accommodation-bookings-plugin.php:16` | Accommodation plugin |
| `WC_Product_Accommodation_Booking` | `includes/class-wc-product-accommodation-booking.php` | Accommodation product |
| `CKL_Car_Rental` | `ckl-car-rental.php:31` | Car rental main class |
| `CKL_Booking_Manager` | `includes/class-booking-manager.php` | Vehicle booking manager |

---

## Future Enhancements

### Potential Improvements

1. **Unified Booking Interface**
   - Single search for both accommodations and vehicles
   - Cross-product availability checking

2. **Dynamic Pricing**
   - Demand-based pricing
   - Seasonal adjustments
   - Last-minute discounts

3. **Enhanced Analytics**
   - Revenue forecasting
   - Occupancy rate tracking
   - Customer behavior analysis

4. **Mobile App**
   - Native booking interface
   - Push notifications
   - Mobile payments

---

## Support & Maintenance

### Regular Maintenance Tasks

1. **Keep Plugins Updated**
   - WooCommerce Bookings
   - WooCommerce Accommodation Bookings
   - CKL Car Rental custom plugin

2. **Monitor Sync Status**
   - Check vehicle sync logs
   - Verify WooCommerce product links

3. **Performance Optimization**
   - Clear caches regularly
   - Optimize database queries
   - Monitor booking performance

4. **Backup Strategy**
   - Regular database backups
   - Plugin configuration backups
   - Custom code versioning

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2026-02-23 | Initial documentation |

---

## References

- [WooCommerce Bookings Documentation](https://woocommerce.com/documentation/woocommerce-bookings/)
- [WooCommerce Accommodation Bookings Documentation](https://woocommerce.com/documentation/woocommerce-accommodation-bookings/)
- [WooCommerce REST API Docs](https://woocommerce.github.io/woocommerce-rest-api-docs/)
