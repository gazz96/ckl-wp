# CKL Booking System - Data Flow Diagrams

## Mermaid Diagrams

### System Architecture Overview

```mermaid
graph TB
    WC[WooCommerce Core]
    WCB[WooCommerce Bookings v3.0.2]
    WCAB[WooCommerce Accommodation Bookings v1.3.7]
    CKL[CKL Car Rental v1.0.0]
    VEHICLE[Vehicle CPT]
    ACCOMM[Accommodation Products]

    WC --> WCB
    WC --> WCAB
    WCB --> CKL
    WCB --> VEHICLE
    WCAB --> ACCOMM

    style WC fill:#e1f5ff
    style WCB fill:#ffe1e1
    style WCAB fill:#e1ffe1
    style CKL fill:#fff5e1
    style VEHICLE fill:#f5e1ff
    style ACCOMM fill:#e1f5ff
```

### Accommodation Booking Flow

```mermaid
sequenceDiagram
    participant User
    participant Calendar
    participant WCAB as Accommodation Bookings
    participant Cart
    participant Order

    User->>Calendar: Select check-in date
    User->>Calendar: Select check-out date
    Calendar->>WCAB: Calculate nights
    WCAB->>Calendar: Show availability
    User->>Calendar: Select room type
    User->>Cart: Add to cart
    Cart->>WCAB: Calculate total (nights × rate)
    User->>Order: Complete checkout
    Order->>WCAB: Create booking
    WCAB->>User: Send confirmation
```

### Car Rental Booking Flow

```mermaid
sequenceDiagram
    participant User
    participant CKL as CKL Car Rental
    participant WCB as WooCommerce Bookings
    participant Cart
    participant Order

    User->>CKL: Search vehicles
    CKL->>User: Show available vehicles
    User->>CKL: Select vehicle & dates
    CKL->>WCB: Check availability
    WCB->>CKL: Return availability status
    User->>CKL: Select pickup/return location
    User->>Cart: Add to cart
    Cart->>CKL: Calculate total (days × rate)
    User->>Order: Complete checkout
    Order->>WCB: Create booking
    WCB->>CKL: Sync booking data
    CKL->>User: Send confirmation
```

### Vehicle CPT to WooCommerce Sync

```mermaid
graph LR
    VEHICLE[Vehicle CPT] -->|Save Post| SYNC[sync_vehicle_to_woocommerce_product]
    SYNC -->|Product exists?| CHECK{Check}
    CHECK -->|No| CREATE[Create WC_Product_Booking]
    CHECK -->|Yes| UPDATE[Update WC_Product_Booking]
    CREATE -->|Save| META1[Store product_id in vehicle meta]
    UPDATE -->|Save| META2[Update product data]
    META1 --> WC_PRODUCT[WooCommerce Booking Product]
    META2 --> WC_PRODUCT
    WC_PRODUCT -->|Store| VEHICLE_ID[Link back to Vehicle ID]

    style VEHICLE fill:#e1f5ff
    style WC_PRODUCT fill:#ffe1e1
```

### Late Fee Calculation Flow

```mermaid
graph TD
    START[Vehicle Returned] --> GET_TIMES[Get scheduled & actual times]
    GET_TIMES --> CALC_DIFF[Calculate time difference]
    CALC_DIFF --> OVERDUE{Overdue?}
    OVERDUE -->|No| NO_FEE[No late fee]
    OVERDUE -->|Yes| GET_GRACE[Get grace period]
    GET_GRACE --> SUB_GRACE[Subtract grace period]
    SUB_GRACE --> CALC_HOURS[Calculate late hours]
    CALC_HOURS --> GET_RATE[Get late fee rate]
    GET_RATE --> CALC_FEE[Calculate fee: hours × rate]
    CALC_FEE --> ADD_TOTAL[Add to booking total]
    ADD_TOTAL --> NOTIFY[Notify customer]

    style NO_FEE fill:#e1ffe1
    style CALC_FEE fill:#ffe1e1
    style NOTIFY fill:#fff5e1
```

### Database Schema Relationships

```mermaid
erDiagram
    VEHICLE ||--o{ VEHICLE_META : has
    PRODUCT ||--o{ PRODUCT_META : has
    BOOKING ||--o{ BOOKING_META : has
    VEHICLE ||--|| PRODUCT : syncs_to
    PRODUCT ||--o{ BOOKING : creates

    VEHICLE {
        int ID PK
        string post_title
        string post_status
        string post_type
    }

    VEHICLE_META {
        int meta_id PK
        int post_id FK
        string meta_key
        string meta_value
    }

    PRODUCT {
        int ID PK
        string post_title
        string post_status
        string post_type
    }

    PRODUCT_META {
        int meta_id PK
        int post_id FK
        string meta_key
        string meta_value
    }

    BOOKING {
        int id PK
        int product_id FK
        datetime start_date
        datetime end_date
        string status
    }

    BOOKING_META {
        int meta_id PK
        int booking_id FK
        string meta_key
        string meta_value
    }
```

---

## ASCII Diagrams

### Class Hierarchy

```
WC_Product
    │
    ├── WC_Product_Booking
    │       │
    │       ├── WC_Product_Accmodation_Booking
    │       │
    │       └── [Custom Vehicle Booking via CKL Car Rental]
    │
    └── WC_Product_Simple
            └── [Other standard products]
```

### File Structure

```
/web/app/
├── plugins/
│   ├── woocommerce/
│   │   └── [WooCommerce core files]
│   │
│   ├── woocommerce-bookings/
│   │   ├── woocommerce-bookings.php
│   │   ├── includes/
│   │   │   ├── data-objects/
│   │   │   │   ├── class-wc-product-booking.php
│   │   │   │   └── class-wc-booking.php
│   │   │   ├── class-wc-booking-cart-manager.php
│   │   │   ├── class-wc-booking-order-manager.php
│   │   │   └── ...
│   │   └── templates/
│   │
│   ├── woocommerce-accommodation-bookings/
│   │   ├── woocommerce-accommodation-bookings.php
│   │   ├── includes/
│   │   │   ├── class-wc-product-accommodation-booking.php
│   │   │   ├── class-wc-accommodation-booking-cart-manager.php
│   │   │   ├── class-wc-accommodation-booking-order-manager.php
│   │   │   └── admin/
│   │   └── templates/
│   │
│   └── ckl-car-rental/
│       ├── ckl-car-rental.php
│       ├── includes/
│       │   ├── class-booking-manager.php
│       │   ├── class-late-fees.php
│       │   ├── class-location-system.php
│       │   └── ...
│       └── assets/
│
└── mu-plugins/
    └── custom-post-types.php
        ├── Vehicle CPT registration
        ├── Vehicle meta boxes
        └── WooCommerce sync logic
```

---

## Process Flowcharts

### New Vehicle Creation Process

```
┌─────────────────┐
│ Admin creates   │
│ new Vehicle CPT │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Fill vehicle    │
│ details form    │
│ (type, price,   │
│ capacity, etc.) │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Save vehicle    │
│ post            │
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│ sync_vehicle_to_        │
│ woocommerce_product()   │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Create WC_Product_Booking   │
│ with vehicle data           │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────┐
│ Store product_id in     │
│ vehicle meta            │
└────────┬────────────────┘
         │
         ▼
┌─────────────────┐
│ Vehicle ready   │
│ for booking     │
└─────────────────┘
```

### Booking Creation Process

```
┌─────────────────┐
│ Customer        │
│ searches for    │
│ vehicle/room    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ System checks   │
│ availability    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Customer        │
│ selects dates   │
│ & options       │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Add to cart     │
│ with calculated │
│ pricing         │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Customer        │
│ completes       │
│ checkout        │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Order created   │
│ in WooCommerce  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Booking created │
│ in WC_Bookings  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Confirmation   │
│ sent to         │
│ customer        │
└─────────────────┘
```

---

## Pricing Calculation Examples

### Accommodation Example

```
Scenario: 3-night stay in a villa

Input:
- Check-in: 2026-03-01
- Check-out: 2026-03-04
- Base Rate: RM 300/night
- Guests: 4 adults

Calculation:
Nights = (2026-03-04) - (2026-03-01) = 3 nights
Base Total = 3 × RM 300 = RM 900
Person Type Fees = (4 - 2) × RM 50 = RM 100

Total = RM 900 + RM 100 = RM 1,000
```

### Car Rental Example

```
Scenario: 2-day car rental with 3-hour late return

Input:
- Pickup: 2026-03-01 10:00
- Return: 2026-03-03 15:00 (scheduled: 2026-03-03 10:00)
- Daily Rate: RM 150/day
- Late Fee Rate: RM 20/hour
- Grace Period: 30 minutes

Calculation:
Days = ceil((2026-03-03 10:00 - 2026-03-01 10:00) / 24 hours) = 2 days
Base Total = 2 × RM 150 = RM 300

Late Hours = 15:00 - 10:00 - 0:30 = 4.5 hours → 5 hours (rounded up)
Late Fee = 5 × RM 20 = RM 100

Total = RM 300 + RM 100 = RM 400
```

---

## State Diagrams

### Booking Status Flow

```
┌─────────┐
│ Pending │
└────┬────┘
     │
     ▼
┌─────────┐      ┌──────────┐
│ Confirmed│─────>│ Complete │
└────┬────┘      └──────────┘
     │
     ▼
┌─────────┐      ┌──────────┐
│ Cancelled│     │ Refunded │
└─────────┘      └──────────┘
```

### Vehicle Availability State

```
┌──────────┐
│ Available│
└────┬─────┘
     │
     ▼ (when booked)
┌──────────┐
│ Booked   │
└────┬─────┘
     │
     ├─────────────┐
     │             │
     ▼ (return)    ▼ (maintenance)
┌──────────┐  ┌──────────┐
│ Available│  │ Blocked  │
└──────────┘  └────┬─────┘
                   │
                   ▼ (complete)
              ┌──────────┐
              │ Available │
              └──────────┘
```

---

## Component Interactions

### Frontend Components

```
┌─────────────────────────────────────────────────────────┐
│                       Frontend                          │
├─────────────────────────────────────────────────────────┤
│                                                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │   Hero      │  │   Search    │  │   Results   │   │
│  │   Search    │  │   Filters   │  │   Grid      │   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
│                                                           │
│  ┌─────────────────────────────────────────────────┐   │
│  │              Booking Form                        │   │
│  │  ┌─────────┐  ┌─────────┐  ┌─────────────────┐  │   │
│  │  │ Date    │  │ Guest   │  │   Location      │  │   │
│  │  │ Picker  │  │ Count   │  │   Selector      │  │   │
│  │  └─────────┘  └─────────┘  └─────────────────┘  │   │
│  └─────────────────────────────────────────────────┘   │
│                                                           │
│  ┌─────────────────────────────────────────────────┐   │
│  │              Cart & Checkout                     │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
         │                    │                    │
         │                    │                    │
         ▼                    ▼                    ▼
    ┌─────────┐         ┌─────────┐         ┌─────────┐
    │   API   │         │   API   │         │   API   │
    └─────────┘         └─────────┘         └─────────┘
```

### Backend Components

```
┌─────────────────────────────────────────────────────────┐
│                       Backend                           │
├─────────────────────────────────────────────────────────┤
│                                                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │   Booking   │  │   Pricing   │  │ Availability│   │
│  │  Manager    │  │   Engine    │  │   Manager   │   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
│                                                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │    Late     │  │  Location   │  │   Calendar  │   │
│  │    Fees     │  │   System    │  │     Sync    │   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
│                                                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │   Reviews   │  │  Analytics  │  │   Dynamic   │   │
│  │   System    │  │             │  │   Pricing   │   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
│                                                           │
└─────────────────────────────────────────────────────────┘
         │                    │                    │
         │                    │                    │
         ▼                    ▼                    ▼
    ┌─────────┐         ┌─────────┐         ┌─────────┐
    │   DB    │         │   DB    │         │   DB    │
    └─────────┘         └─────────┘         └─────────┘
```

---

## API Request/Response Examples

### Check Vehicle Availability

**Request:**
```
GET /wp-json/ckl/v1/vehicles/availability
?vehicle_id=123
&start_date=2026-03-01
&end_date=2026-03-03
&location=airport
```

**Response:**
```json
{
  "available": true,
  "vehicle": {
    "id": 123,
    "name": "Toyota Vios",
    "type": "sedan",
    "capacity": 5,
    "price_per_day": 150
  },
  "pricing": {
    "days": 2,
    "base_rate": 150,
    "total": 300,
    "currency": "MYR"
  },
  "availability": {
    "available_units": 3
  }
}
```

### Create Booking

**Request:**
```
POST /wp-json/wc-bookings/v1/bookings
{
  "product_id": 456,
  "start_date": "2026-03-01T10:00:00",
  "end_date": "2026-03-03T10:00:00",
  "persons": 2
}
```

**Response:**
```json
{
  "id": 789,
  "product_id": 456,
  "start_date": "2026-03-01T10:00:00",
  "end_date": "2026-03-03T10:00:00",
  "status": "confirmed",
  "order_id": 101,
  "customer_id": 5
}
```

---

## Error Handling

### Common Error States

```
┌─────────────────────────────────────────────────────────┐
│                    Error States                         │
├─────────────────────────────────────────────────────────┤
│                                                           │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Availability Errors                              │   │
│  │  - No vehicles available for selected dates      │   │
│  │  - All units booked                              │   │
│  │  - Location has no available vehicles            │   │
│  └─────────────────────────────────────────────────┘   │
│                                                           │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Validation Errors                                │   │
│  │  - Invalid date range (return before pickup)     │   │
│  │  - Minimum stay not met                          │   │
│  │  - Maximum stay exceeded                         │   │
│  │  - Invalid location combination                  │   │
│  └─────────────────────────────────────────────────┘   │
│                                                           │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Payment Errors                                   │   │
│  │  - Payment gateway timeout                       │   │
│  │  - Insufficient funds                            │   │
│  │  - Payment declined                              │   │
│  └─────────────────────────────────────────────────┘   │
│                                                           │
└─────────────────────────────────────────────────────────┘
```

---

## Security Considerations

### Access Control

```
┌─────────────────────────────────────────────────────────┐
│                    Access Control                       │
├─────────────────────────────────────────────────────────┤
│                                                           │
│  Role              │  Bookings  │  Vehicles  │  Settings│
│  ─────────────────┼───────────┼────────────┼──────────│
│  Administrator     │  Full     │  Full      │  Full    │
│  Shop Manager      │  Full     │  Full      │  Limited │
│  Customer          │  Own only │  View only │  None    │
│  Guest             │  None     │  View only │  None    │
│                                                           │
└─────────────────────────────────────────────────────────┘
```

### Data Validation

All booking data must be validated:
- Date ranges must be valid
- Pickup must be before return
- Duration must be within min/max limits
- Location combinations must be valid
- Payment amounts must match calculated totals

---

This diagram file is designed to be used with Mermaid-compatible tools like GitHub, VS Code, or online Mermaid editors.
