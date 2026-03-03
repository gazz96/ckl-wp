# CKL Car Rental - Setup Instructions

## Quick Start Guide

### 1. Verify Installation

```bash
# Check if all plugins are active
wp plugin list --status=active

# Expected output should include:
# - ckl-car-rental
# - woocommerce
# - woocommerce-bookings
# - woo-xendit-virtual-accounts
# - yith-woocommerce-product-add-ons
# - tawkto-live-chat
# - woocommerce-store-toolkit
```

### 2. Create Your First Vehicle

1. Go to wp-admin → Vehicles → Add New
2. Fill in the vehicle details:
   - Title: "Perodua Axia"
   - Vehicle Type: Compact
   - Passenger Capacity: 4
   - Doors: 4
   - Luggage: 2
   - Air Conditioning: ✓ Check
   - Transmission: Automatic
   - Fuel Type: Petrol
   - Plate Number: ABC 1234
   - Units Available: 3
   - Price Per Day: 80
   - Late Fee Per Hour: 10 (optional)
   - Grace Period: 15 (optional)
3. Click "Publish"

**What happens automatically**:
- A WooCommerce bookable product is created
- Price and availability are synced
- The vehicle appears on the frontend

### 3. Configure Payment Gateway

1. Go to wp-admin → WooCommerce → Settings → Payments
2. Find "Xendit Virtual Accounts"
3. Click "Manage"
4. Enter your Xendit API keys:
   - For testing: Use sandbox keys from Xendit dashboard
   - For production: Use live API keys
5. Enable payment methods you want:
   - Virtual Accounts (VA)
   - Credit Card
   - E-Wallets (OVO, Dana, etc.)
6. Save changes

**Test the payment**:
1. Create a booking
2. Complete checkout
3. Use Xendit sandbox test credentials

### 4. Set Up Additional Services

1. Go to wp-admin → YITH Plugins → Product Add-Ons
2. Click "Add New"
3. Create each service:

**Baby Seat**:
- Name: Baby Seat
- Description: Safe baby seat for infants
- Price Type: Price per Day
- Price: 10
- Assign to: All vehicle products

**Navigation System**:
- Name: GPS Navigation
- Description: Never get lost with GPS
- Price Type: Price per Day
- Price: 15
- Assign to: All vehicle products

**WiFi Device**:
- Name: Portable WiFi
- Description: Stay connected on the go
- Price Type: Price per Day
- Price: 20
- Assign to: All vehicle products

**Camera**:
- Name: Action Camera
- Description: Capture your adventures
- Price Type: Price per Day
- Price: 25
- Assign to: All vehicle products

**Umbrella**:
- Name: Umbrella
- Description: For rainy days
- Price Type: Fixed Price (per booking)
- Price: 5
- Assign to: All vehicle products

### 5. Configure Google Calendar Sync

**Option 1: Using WooCommerce Bookings Built-in Sync**
1. Go to wp-admin → WooCommerce → Bookings → Google Calendar
2. Click "Connect with Google Calendar"
3. Authorize the application
4. Select the calendar to use (or use "primary")
5. Enable "Sync bookings automatically"

**Option 2: Using CKL Settings**
1. Go to wp-admin → WooCommerce → CKL Settings → Calendar Sync
2. Enable "Enable Sync"
3. Enter Calendar ID (or use "primary" for default)
4. Save settings

### 6. Set Up Live Chat

1. Go to wp-admin → Tawk.to Settings
2. If you don't have an account:
   - Click "Get Started"
   - Create a free account
   - Get your Property ID
3. Enter your Property ID
4. Customize widget appearance:
   - Position: Bottom right
   - Theme color: Match your brand
   - Widget size: Medium
5. Save settings

### 7. Configure WooCommerce Settings

1. Go to wp-admin → WooCommerce → Settings

**General Tab**:
- Selling location: Specific locations → Malaysia
- Currency: MYR (Malaysian Ringgit) ✓ Already set

**Products Tab**:
- Measurements: kg, cm
- Review ratings: Enable

**Tax Tab**:
- Enable taxes: No (or configure if needed)

**Shipping Tab**:
- Not applicable for car rental

**Accounts Tab**:
- Enable registration: Yes
- Allow customers to create accounts: Yes
- Account creation: Automatically

**Email Tab**:
- Email sender options: Set your name and email
- Verify SMTP is configured (use WP Mail SMTP plugin if needed)

### 8. Test the Booking Flow

1. Visit your homepage: http://ckl.test
2. Select dates and vehicle type
3. Click "Search Vehicles"
4. Select a vehicle
5. On the vehicle page:
   - Review specifications
   - Check availability
   - Click "Book Now"
6. Fill in booking details:
   - Select dates
   - Choose add-ons (optional)
   - Proceed to checkout
7. Complete payment (sandbox mode for testing)
8. Verify:
   - Booking is created
   - Email confirmation sent
   - Calendar sync works
   - Admin can see the booking

### 9. Set Up Dynamic Pricing Rules (Optional)

1. Go to wp-admin → Vehicles → Edit a vehicle
2. Scroll to "Dynamic Pricing Rules" section
3. Click "Add Pricing Rule"
4. Create a rule:
   - Name: "Hari Raya 2026"
   - Start Date: 2026-03-30
   - End Date: 2026-04-02
   - Adjustment Type: Percentage
   - Amount: 50 (for 50% increase)
   - Recurring: Yearly
   - Priority: 10
   - Active: Yes
5. Save vehicle

**Test it**:
1. Search for vehicles during the Hari Raya period
2. Verify price shows 50% higher
3. Check that notice appears about peak pricing

### 10. Block Dates (Optional)

**Individual Vehicle Blocking**:
1. Go to wp-admin → Blocked Dates → Add New
2. Fill in:
   - Vehicle: Select vehicle
   - Start Date: 2026-03-01
   - End Date: 2026-03-05
   - Reason: Maintenance
   - Notes: "Regular service"
3. Publish

**Bulk Block Dates**:
1. Go to wp-admin → WooCommerce → CKL Settings → Bulk Block Dates
2. Select multiple vehicles
3. Set date range
4. Select reason (e.g., Holiday)
5. Add notes
6. Click "Block Dates"

**Verify**:
- Try to book the vehicle for blocked dates
- Should see error message about unavailability

### 11. Configure Analytics

Analytics are automatically tracked. To view:

1. Go to wp-admin → Dashboard
2. See "CKL Car Rental Analytics" widget
3. Or visit: wp-admin → WooCommerce → CKL Analytics

**Available Metrics**:
- Total bookings
- Total revenue
- Average booking value
- Vehicle utilization
- Popular vehicles
- Late returns rate

---

## Common Issues & Solutions

### Issue: "Booking not available" on vehicle page

**Solution**:
1. Edit the vehicle
2. Check if "WooCommerce Sync" meta box shows a linked product
3. If not, click "Create WooCommerce Product"
4. Wait for confirmation
5. Refresh vehicle page

### Issue: Late fees not calculating

**Solution**:
1. Edit vehicle
2. Verify "Late Fee Per Hour" is set
3. Verify "Grace Period" is set
4. Check booking is marked "Complete"
5. Set "Actual Return Time" in booking meta box

### Issue: Dynamic pricing not working

**Solution**:
1. Edit vehicle
2. Check dynamic pricing rule is "Active"
3. Verify dates are correct
4. Check priority (higher = more important)
5. Test with exact date range

### Issue: Google Calendar not syncing

**Solution**:
1. Verify WooCommerce Bookings is connected to Google
2. Check wp-admin → WooCommerce → Bookings → Google Calendar
3. Re-authorize if needed
4. Check CKL Settings → Calendar Sync
5. Verify Calendar ID is correct

### Issue: Reviews not showing

**Solution**:
1. Go to wp-admin → Settings → Discussion
2. Enable "Allow people to submit comments"
3. For vehicles: "Allow comments" in screen options
4. Check that reviews are approved (not spam)

---

## Daily Operations

### Check Bookings
1. wp-admin → WooCommerce → Bookings
2. View upcoming, confirmed, and completed bookings
3. Check for late returns

### Manage Vehicles
1. wp-admin → Vehicles
2. Edit prices, availability, or specifications
3. Sync changes to WooCommerce (automatic)

### View Analytics
1. wp-admin → WooCommerce → CKL Analytics
2. Select date range
3. Review metrics and trends

### Handle Late Returns
1. Edit booking in wp-admin
2. Set "Actual Return Time"
3. Late fee calculates automatically
4. Customer receives notification

---

## Maintenance Tasks

### Weekly
- Check for plugin updates
- Review analytics
- Verify calendar sync is working

### Monthly
- Review and update pricing rules
- Check vehicle availability
- Review customer feedback

### Quarterly
- Update blocked dates for holidays
- Review late fee settings
- Optimize database
- Test booking flow end-to-end

---

## Next Steps

1. **Configure Payment Gateway** (Xendit)
2. **Set Up Additional Services** (YITH)
3. **Create Location Taxonomy** (for pickup/return)
4. **Build User Dashboards** (renter, owner, admin views)
5. **Prepare for Data Migration** (from Supabase)

---

*For detailed implementation information, see IMPLEMENTATION-SUMMARY.md*
