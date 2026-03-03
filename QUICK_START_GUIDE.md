# CK Langkawi - Quick Start Guide

## 🚀 Next 5 Things To Do

### 1. Create the Dashboard Page (5 minutes)
```bash
# Via WP-Admin:
# 1. Go to Pages → Add New
# 2. Title: "Dashboard"
# 3. Template: Select "User Dashboard"
# 4. Publish
```

### 2. Set Up Navigation Menu (10 minutes)
```bash
# Via WP-Admin:
# 1. Go to Appearance → Menus
# 2. Create new menu named "Primary Menu"
# 3. Add pages: Home, About Us, Contact Us, FAQs
# 4. Add custom link "Our Vehicles" with URL: /vehicles/
# 5. Add custom links as sub-items under Our Vehicles:
#    - Bike Rentals: /vehicle/?vehicle_type=motorcycle
#    - Car Rentals: /vehicle/?vehicle_type=sedan
#    - Van Rentals: /vehicle/?vehicle_type=mpv
#    - Bus Rentals: /vehicle/?vehicle_type=bus
# 6. Check "Primary" checkbox under Menu Settings
# 7. Save Menu
```

### 3. Add Locations (5 minutes)
```bash
# Via WP-Admin:
# 1. Go to Vehicles → Locations
# 2. Add New Location
#    - Name: "Langkawi International Airport"
#    - (Optional) Add coordinates to term meta: 6.3228,99.7285
# 3. Add more locations: Kuah Town, Pantai Cenang, etc.
```

### 4. Create Test Vehicle (5 minutes)
```bash
# Via WP-Admin:
# 1. Go to Vehicles → Add New
# 2. Title: "Perodua Axia"
# 3. Fill in vehicle details:
#    - Type: Compact
#    - Passenger Capacity: 4
#    - Doors: 4
#    - Luggage: 2
#    - Air Conditioning: ✅
#    - Transmission: Automatic
#    - Fuel Type: Petrol
#    - Plate Number: ABC 1234
#    - Units Available: 5
#    - Price Per Day: 80
#    - Late Fee Per Hour: 10 (optional)
#    - Grace Period: 15 (optional)
# 4. Select Featured Image
# 5. Select Locations (check all that apply)
# 6. Publish
```

### 5. Test the Booking Flow (10 minutes)
```bash
# 1. Visit /vehicles/ (should see your test vehicle)
# 2. Click on vehicle to view details
# 3. Click "Book Now" or similar button
# 4. Select dates and location
# 5. Proceed to checkout
# 6. Complete payment (in sandbox mode)
# 7. Check email for confirmation
# 8. Verify booking appears in dashboard
```

---

## 🔧 WooCommerce Configuration

### Basic Settings
```bash
# WooCommerce → Settings → General
# - Store Address: Fill in your address
# - Currency: MYR (Malaysian Ringgit)
# - Currency Position: Left
# - Thousand Separator: ,
# - Decimal Separator: .
# - Number of Decimals: 2
```

### Bookings Configuration
```bash
# WooCommerce → Bookings → Settings
# - Minimum duration: 1 day
# - Maximum duration: 30 days
# - First available date: Today
# - Require confirmation: No (optional)
```

### Payment Gateway Setup
```bash
# WooCommerce → Settings → Payments → Xendit
# 1. Enable Xendit
# 2. Test Mode: On (for testing)
# 3. Enter API keys from Xendit dashboard
# 4. Configure webhook URL
# 5. Save changes
# 6. Test with a small payment
```

---

## 👥 User Role Testing

### Create Test Users

```bash
# Via WP-Admin:
# 1. Go to Users → Add New
# 2. Create test users with different roles:

# Renter:
# Username: test_renter
# Email: [email protected]
# Role: Renter (ckl_renter)

# Owner:
# Username: test_owner
# Email: [email protected]
# Role: Vehicle Owner (ckl_owner)

# Admin:
# Username: test_admin (optional)
# Email: [email protected]
# Role: Administrator
```

### Test Each Role
```bash
# 1. Log out of admin
# 2. Log in as test_renter
# 3. Visit /dashboard/
# 4. Verify: My Bookings, My Profile, My Reviews sections visible
# 5. Try booking a vehicle
# 6. Log out, log in as test_owner
# 7. Verify: My Vehicles, Vehicle Bookings sections visible
# 8. Try adding a vehicle
```

---

## 📧 Email Testing

### Test Email Notifications
```bash
# 1. Go to WooCommerce → Settings → Emails
# 2. Enable "New Booking" email
# 3. Enable "Booking Confirmed" email
# 4. Create a test booking
# 5. Check email for notification
# 6. Verify branding and formatting
```

### Customize Emails (Optional)
```bash
# Edit these files in your theme:
# - /web/app/themes/ckl-clone-theme/woocommerce/emails/email-header.php
# - /web/app/themes/ckl-clone-theme/woocommerce/emails/email-footer.php
# - /web/app/themes/ckl-clone-theme/woocommerce/emails/booking-confirmed.php
```

---

## 🎨 Customize Appearance

### Add Logo
```bash
# 1. Find or create your logo file
# 2. Save as: /web/app/themes/ckl-clone-theme/assets/images/cklangkawi_Transparent.png
# 3. Clear browser cache
# 4. Refresh site to see logo
```

### Customize Colors
```bash
# Edit: /web/app/themes/ckl-clone-theme/tailwind.config.js
# Modify theme colors to match your brand
# Then run: npm run build (or npx tailwindcss -i ./assets/css/main.css -o ./assets/dist/output.css)
```

---

## 📊 Feature Verification

### Check Each Feature

- [ ] **Vehicle CPT**: Can create/edit vehicles
- [ ] **WooCommerce Sync**: Vehicle auto-creates bookable product
- [ ] **Booking Form**: Appears on single vehicle page
- [ ] **Date Selection**: Flatpickr or similar date picker works
- [ ] **Location Selection**: Dropdown shows available locations
- [ ] **Payment**: Xendit payment processes successfully
- [ ] **Emails**: Confirmation email sends after booking
- [ ] **User Dashboard**: Shows based on user role
- [ ] **Mobile Menu**: Hamburger menu works on mobile
- [ ] **Header/Footer**: Displays correctly on all pages

---

## 🔍 Debugging

### Enable Debug Mode
```bash
# Edit: /web/app/.env
# WP_DEBUG=true
# WP_DEBUG_LOG=true
# WP_DEBUG_DISPLAY=false
```

### Check Debug Log
```bash
tail -f /web/app/debug.log
```

### Common Issues

**Issue**: Booking form not showing
```bash
# Solution:
# 1. Check if WooCommerce product was created: Edit vehicle, look for "WooCommerce Product ID" meta
# 2. If missing, re-save vehicle to trigger sync
# 3. Check for errors in debug log
```

**Issue**: Payment not processing
```bash
# Solution:
# 1. Verify Xendit API keys are correct
# 2. Check Test Mode is on if using test cards
# 3. Verify webhook URL in Xendit dashboard
# 4. Check Xendit plugin version >6.0.2
```

**Issue**: Emails not sending
```bash
# Solution:
# 1. Install WP Mail Logging plugin
# 2. Check if emails are being generated
# 3. Verify server can send emails (check with wp_mail() test)
# 4. Consider using SMTP plugin for reliable delivery
```

---

## 📈 Performance Optimization

### Install Caching Plugin
```bash
wp plugin install redis-cache --activate
# OR
wp plugin install w3-total-cache --activate
```

### Optimize Images
```bash
wp plugin install wp-smushit --activate
# Then run: Smush → Bulk Smush
```

### Enable Object Cache
```bash
# Install Redis server
# Then install Redis Cache plugin
# Configure in wp-config.php
```

---

## 🔒 Security Checklist

- [ ] SSL certificate installed and active
- [ ] Xendit plugin version >6.0.2
- [ ] All plugins updated to latest versions
- [ ] File permissions: directories 755, files 644
- [ ] WP_DEBUG disabled in production
- [ ] Regular backups configured (use plugin or WP-CLI)
- [ ] Security headers configured
- [ ] Limit login attempts plugin installed

---

## 📦 Plugin Updates

### Check for Updates
```bash
wp plugin list --update=available
```

### Update All Plugins
```bash
wp plugin update --all
```

---

## 🎯 Launch Checklist

Before going live:

- [ ] All features tested and working
- [ ] Payment gateway tested in production mode
- [ ] Email notifications sending correctly
- [ ] SSL certificate valid
- [ ] Backups running daily
- [ ] Monitoring configured (error logging)
- [ ] Google Analytics installed
- [ ] Chat widget configured
- [ ] All test data removed
- [ ] Production database backup created
- [ ] DNS configured to point to correct server
- [ ] CDN configured (optional)
- [ ] Performance optimized (caching, image compression)
- [ ] Mobile responsive test passed
- [ ] Cross-browser test passed (Chrome, Safari, Firefox, Edge)
- [ ] User acceptance testing (UAT) completed
- [ ] Rollback plan documented

---

## 📞 Support

If you encounter issues:

1. Check debug log: `/web/app/debug.log`
2. Review IMPLEMENTATION_SUMMARY.md for details
3. Check WooCommerce documentation
4. Check plugin documentation

---

**Quick Start Complete!** 🎉

You now have a fully functional car rental booking system. Follow these steps to configure and launch.
