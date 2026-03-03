# CKL Car Rental - Development Documentation

## Project Overview

CKL Car Rental is a WordPress-based car rental booking system built on:
- **Bedrock** - WordPress boilerplate with improved directory structure
- **WooCommerce** - E-commerce functionality for booking
- **Xendit Payment Gateway** - Payment processing for Malaysian market

## Project Structure

```
ckl/
├── web/
│   ├── app/
│   │   ├── themes/
│   │   │   └── ckl-clone-theme/          # Main theme
│   │   ├── plugins/
│   │   │   └── woo-xendit-virtual-accounts/  # Xendit payment plugin
│   │   ├── uploads/                      # Media files (gitignored)
│   │   └── mu-plugins/                   # Must-use plugins
│   ├── wp/                               # WordPress core (gitignored)
│   └── index.php
├── config/
│   ├── application.php                   # Main application config
│   └── environments/                     # Environment-specific configs
└── composer.json                         # PHP dependencies
```

## Development Environment

- **Site URL:** http://ckl.test
- **PHP Version:** (Check with `php -v`)
- **Database:** MySQL/MariaDB via Local WP
- **Dependencies:** Managed via Composer in Bedrock structure

## Development Utilities

### Xendit Payment Test Script

**Location:** `web/app/themes/ckl-clone-theme/test-xendit-payment.php`

Simulates Xendit payment webhooks for local development testing. Local sites cannot receive real webhooks from Xendit servers.

**Usage:**
```bash
# Basic usage - mark order as PAID
php web/app/themes/ckl-clone-theme/test-xendit-payment.php --order-id=123

# Specify payment status
php web/app/themes/ckl-clone-theme/test-xendit-payment.php --order-id=123 --status=SETTLED
php web/app/themes/ckl-clone-theme/test-xendit-payment.php --order-id=123 --status=FAILED

# Show help
php web/app/themes/ckl-clone-theme/test-xendit-payment.php --help
```

**Supported Statuses:**
- `PAID` - Payment successful (default)
- `SETTLED` - Payment settled
- `FAILED` - Payment failed

**Signature Verification Bypass:**
The test script uses an empty signature, so signature verification will fail. To bypass temporarily for testing:

1. Open: `web/app/plugins/woo-xendit-virtual-accounts/woocommerce-xendit-pg.php`
2. Comment out lines 277-286 (the signature verification check)
3. Run the test script
4. **IMPORTANT:** Restore signature verification after testing!

**Webhook Endpoint:**
```
http://ckl.test/?wc-api=wc_xendit_callback&xendit_mode=xendit_invoice_callback
```

## Xendit Integration Details

### External ID Format
The Xendit plugin uses the format: `woocommerce-xendit-{order_id}`

Generated in: `WC_Xendit_PG_Helper::generate_external_id()`

### Callback Handler
Located in: `web/app/plugins/woo-xendit-virtual-accounts/woocommerce-xendit-pg.php`
Function: `check_xendit_response()` (lines 253-339)

### Payment Statuses
- **PAID/SETTLED** - Order marked as processing/completed
- **FAILED** - Order marked as failed

### Advanced Order Numbers
If the "WT Advanced Order Number" plugin is active, the system handles custom order numbers via `WC_Xendit_PG_Helper::is_advanced_order_number_active()`

## Common Development Tasks

### Check WooCommerce Order Status
```bash
# Using WP-CLI
wp cli alias update @prod --ssh=production-server
wp @prod wc order list --status=processing
```

### View Order Details
```bash
# Get order details
wp wc order get 123
```

### Test Payment Flow
1. Create a test order in WooCommerce
2. Run the Xendit test script with the order ID
3. Verify order status changes
4. Check order notes for Xendit confirmation

### Check Xendit Logs
Xendit logs are stored in WooCommerce logs (if logging is enabled):
- WooCommerce > Status > Logs
- Look for logs with "xendit" in the name

## Production Deployment Cleanup Checklist

**CRITICAL:** Before deploying to production, remove these development-only files:

- [ ] `web/app/themes/ckl-clone-theme/test-xendit-payment.php` - Xendit payment test script
- [ ] Any other test scripts in theme root directory
- [ ] Verify no debug/test code remains in production
- [ ] Restore signature verification in Xendit plugin if temporarily disabled

### Pre-Deployment Verification

1. **Remove development files:**
   ```bash
   rm web/app/themes/ckl-clone-theme/test-xendit-payment.php
   ```

2. **Check for test code in theme:**
   ```bash
   grep -r "var_dump\|print_r\|error_log" web/app/themes/ckl-clone-theme/
   ```

3. **Verify Xendit plugin integrity:**
   - Ensure signature verification is enabled (lines 277-286 in main plugin file)
   - Check test mode is disabled in plugin settings

4. **Environment configuration:**
   - `WP_DEBUG` should be `false` in production
   - `WP_ENVIRONMENT_TYPE` should be `production`

## Security Notes

### Development Files Security Risks

1. **test-xendit-payment.php**
   - Bypasses normal Xendit webhook security
   - Can artificially mark orders as paid
   - Should NEVER be accessible in production

2. **Signature Verification**
   - Do NOT disable signature verification in production
   - Only disable temporarily in local development for testing
   - Always restore after testing

3. **API Keys**
   - Never commit Xendit API keys to version control
   - Use environment variables for sensitive data
   - Rotate keys if accidentally exposed

## Quick Reference

### Xendit Plugin Files

| File | Purpose |
|------|---------|
| `woocommerce-xendit-pg.php` | Main plugin file, callback handler |
| `libs/class-wc-xendit-invoice.php` | Invoice payment gateway class |
| `libs/class-wc-xendit-helper.php` | Helper functions for orders |
| `libs/helpers/class-wc-xendit-signature-verifier.php` | Signature verification |

### WordPress CLI Commands

```bash
# List orders
wp wc order list

# Get order details
wp wc order get 123

# Update order status
wp wc order update 123 --status=completed

# Get plugin status
wp plugin list

# Flush cache
wp cache flush
```

### Common Issues

**Issue:** Order not found error
**Solution:** Check if Advanced Order Number plugin is active. The script tries both regular and advanced order number lookups.

**Issue:** Invalid signature error
**Solution:** Signature verification must be temporarily disabled for testing. See "Signature Verification Bypass" above.

**Issue:** Order status doesn't change
**Solution:** Check WooCommerce > Status > Logs for Xendit errors. Verify the webhook endpoint is accessible.

---

**Last Updated:** 2026-03-03
**Maintained By:** Development Team
