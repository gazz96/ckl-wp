# CKL Car Rental Theme - Quick Start Guide

## 🚀 First-Time Setup

### Step 1: Run the Migration
1. Go to **WordPress Admin > CKL Settings > Migrate Vehicle Types**
2. Review the migration information
3. Check "Remove old meta fields" if you want to clean up
4. Click **Run Migration**
5. Wait for confirmation message

### Step 2: Configure Homepage
1. Go to **WordPress Admin > CKL Settings**
2. **Homepage Tab**: Enable/disable sections and set order
3. **Hero Tab**: Set title, subtitle, upload background images
4. **Vehicles Tab**: Set number of vehicles, columns, sorting
5. Save changes

### Step 3: Configure Pricing
1. Go to **CKL Settings > Pricing** tab
2. Set default hourly rate
3. Configure daily rate multiplier
4. Set weekly/monthly discounts
5. Adjust vehicle type multipliers
6. Add seasonal pricing if needed
7. Save changes

### Step 4: Set Up Amenities
1. Go to **CKL Settings > Amenities** tab
2. Add/edit/delete amenities
3. Assign Dashicons (e.g., `dashicons-format-audio`)
4. Set order and enable/disable
5. Save changes

### Step 5: Add Reviews
1. Go to **CKL Settings > Reviews** tab
2. Click **Add Review**
3. Fill in reviewer details
4. Select vehicle (optional)
5. Set rating (1-5 stars)
6. Check "Featured" to show on homepage
7. Set display order
8. Save changes

---

## 📝 Editing Vehicles

### New Vehicle
1. Go to **Vehicles > Add New**
2. Enter vehicle title and description
3. Use **tabbed interface** to configure:
   - **Basic Info**: Category, passengers, doors, etc.
   - **Pricing**: Rates, minimum booking, special offers
   - **Inventory**: Units available, WooCommerce sync
   - **Amenities**: Select from global list
   - **Availability**: View/edit calendar, bulk updates
4. Set featured image
5. Publish

### Existing Vehicle
1. Go to **Vehicles > All Vehicles**
2. Click on vehicle title
3. Use tabs to navigate sections
4. Make changes
5. Update

---

## 🎨 Customizing Homepage

### Change Hero Background
1. Go to **CKL Settings > Hero** tab
2. Under "Background Images", click **Add Background Image**
3. Upload multiple images for slideshow effect
4. Adjust overlay opacity (0-100%)
5. Save changes

### Reorder Sections
1. Go to **CKL Settings > Homepage** tab
2. Change the "Order" number for each section
3. Lower numbers appear first
4. Save changes

### Disable Sections
1. Go to **CKL Settings > Homepage** tab
2. Uncheck "Enable" for unwanted sections
3. Save changes

### Configure Vehicle Grid
1. Go to **CKL Settings > Vehicles** tab
2. Set "Number of Vehicles" to display
3. Choose "Grid Columns" (2-5)
4. Enable/disable "Show Category Tabs"
5. Select sort order
6. Save changes

---

## 💰 Pricing Configuration

### Vehicle Type Multipliers
The multiplier is applied to the base hourly rate:

**Example:**
- Base hourly rate: RM 15
- Sedan multiplier: 1.0 = RM 15/hour
- MPV multiplier: 1.3 = RM 19.50/hour
- Luxury MPV multiplier: 1.8 = RM 27/hour

### Seasonal Pricing
1. Go to **CKL Settings > Pricing** tab
2. Scroll to "Seasonal Pricing"
3. Click **Add Seasonal Pricing**
4. Set name (e.g., "School Holidays")
5. Set date range
6. Set multiplier (e.g., 1.5 for 50% increase)
7. Save

### Special Pricing (Per Vehicle)
1. Edit vehicle
2. Go to **Pricing** tab
3. Scroll to "Special Pricing Offers"
4. Click **+ Add Pricing Offer**
5. Set dates and special price
6. Save

---

## 🗓️ Availability Management

### View Calendar
1. Edit vehicle
2. Go to **Availability** tab
3. Calendar shows current month
4. Green = Available, Red = Booked, Gray = Past

### Update Single Date
- Click on calendar day (future feature - use bulk update for now)

### Bulk Update Dates
1. Edit vehicle
2. Go to **Availability** tab
3. Under "Bulk Update Availability"
4. Select date range
5. Select status (Available or Fully Booked)
6. Click **Update Availability**

---

## 🎯 Tips & Best Practices

### Vehicle Categories
- Always assign a vehicle to a specific category (Sedan, MPV, etc.)
- Don't assign to parent "Cars" or "Motorcycles"
- This ensures proper filtering

### Pricing
- Set competitive base rates
- Use multipliers for automatic calculations
- Create seasonal pricing for holidays
- Offer discounts for longer rentals

### Reviews
- Add diverse reviews (different vehicles, customers)
- Include country flags for international appeal
- Use real-sounding reviews
- Feature 4-5 star reviews prominently

### Images
- Use high-quality vehicle photos
- Multiple background images for hero slideshow
- Consistent image sizes
- Optimize for web (compress images)

### SEO
- Use descriptive vehicle titles
- Include vehicle specs in descriptions
- Add alt text to images
- Enable all homepage sections

---

## 🔧 Common Tasks

### Add New Vehicle Category
1. Go to **Vehicles > Vehicle Categories**
2. Add parent category (e.g., "Vans")
3. Add child categories (e.g., "Minivan", "Passenger Van")
4. Update global pricing multipliers in functions.php if needed

### Export Reviews
- Reviews are stored in `wp_options` table
- Option name: `ckl_manual_reviews`
- Use plugin like "Options Import/Export" to backup

### Reset Settings
- All settings in `wp_options` prefixed with `ckl_`
- Delete options to reset to defaults
- Next page load will recreate with defaults

### Customize Colors
- Edit `style.css` or add custom CSS
- Tailwind classes used throughout
- Blue is primary color (#2563eb)
- Gray scales for backgrounds

---

## 📱 Mobile Responsiveness

All sections are mobile-responsive:
- Hero stacks vertically
- Tabs convert to dropdown on mobile
- Vehicle grid adjusts columns
- Reviews show 1 per row
- FAQ accordion works with touch

---

## 🐛 Troubleshooting

**Tabs not working**
- Clear browser cache
- Check browser console for errors
- Verify `vehicle-tabs.js` is loaded

**Migration fails**
- Ensure `vehicle_category` taxonomy exists
- Check database permissions
- Look for errors in debug.log

**Reviews not showing**
- Mark reviews as "Featured"
- Check homepage section is enabled
- Clear WordPress cache

**Vehicles not filtering**
- Verify vehicles have categories assigned
- Check JavaScript console
- Ensure homepage.js is enqueued

**Images not uploading**
- Check WordPress upload permissions
- Verify uploads folder exists
- Increase PHP memory_limit if needed

---

## 📞 Getting Help

1. **Check the Implementation Summary** - Detailed feature documentation
2. **WordPress Codex** - General WordPress help
3. **Developer Tools** - Browser console for JS errors
4. **Debug Mode** - Enable WP_DEBUG for error logs

---

## ✨ Feature Highlights

### What's New:
- ✅ Tabbed vehicle editing interface
- ✅ Configurable homepage sections
- ✅ Vehicle category taxonomy
- ✅ Manual review system
- ✅ Dynamic amenities management
- ✅ Global pricing configuration
- ✅ Hero slideshow
- ✅ Vehicle filtering by category
- ✅ FAQ accordion
- ✅ Blog/news integration
- ✅ Migration tools

### What's Improved:
- 🚀 Faster vehicle queries (taxonomy vs meta)
- 🎨 Better admin UX (tabs vs long pages)
- 📱 Enhanced mobile responsiveness
- ⚡ JavaScript-powered interactions
- 🔒 Better security (nonces, sanitization)
- 📊 Pricing calculations
- 🗓️ Availability calendar

---

## 🎓 Learning Resources

### WordPress Functions Used:
- `register_taxonomy()` - Vehicle categories
- `get_option()` / `update_option()` - Settings storage
- `wp_localize_script()` - Pass data to JavaScript
- `add_meta_box()` - Admin interface
- `WP_Query` - Custom queries

### Taxonomy Queries:
```php
// Get all cars
$cars = get_posts([
    'post_type' => 'vehicle',
    'tax_query' => [
        [
            'taxonomy' => 'vehicle_category',
            'field' => 'slug',
            'terms' => 'cars',
        ]
    ]
]);
```

### Getting Settings:
```php
$hero = get_option('ckl_hero_settings');
$pricing = get_option('ckl_global_pricing');
$reviews = get_option('ckl_manual_reviews');
```

---

**Last Updated**: February 2025
**Version**: 1.0.0
