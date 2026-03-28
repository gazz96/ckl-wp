# WhatsApp Button Configuration Guide

## Settings Location
All WhatsApp button settings are now centralized in:
```
config/whatsapp.php
```

## How to Change Settings

### 1. Change Phone Number
Edit `'phone'` value in `config/whatsapp.php`:

```php
'phone' => '60194428040',  // Current Malaysian number
```

**Format**: Country code + number (no spaces, no + symbol)

**Examples**:
- Malaysia: `'60194428040'`
- Indonesia: `'6281234567890'`
- Singapore: `'6512345678'`
- Thailand: `'66123456789'`

### 2. Change Default Message
Edit `'message'` value in `config/whatsapp.php`:

```php
'message' => 'Hi, I\'m interested in renting a car from CK Langkawi. Can you help me?',
```

**Note**: Use `\'` for apostrophes, the system will automatically URL-encode the message.

### 3. Change Tooltip Text
Edit `'tooltip'` value:

```php
'tooltip' => 'Chat with us!',
```

### 4. Change Button Position
Edit `'position'` value:

```php
'position' => 'bottom-right',  // Options: bottom-right, bottom-left, top-right, top-left
```

### 5. Enable/Disable Button
Edit `'enabled'` value:

```php
'enabled' => true,   // Show WhatsApp button
'enabled' => false,  // Hide WhatsApp button
```

## Complete Configuration Example

```php
return [
    'phone' => '60194428040',
    'message' => 'Hello, I want to make a car rental inquiry.',
    'tooltip' => 'Chat with us!',
    'position' => 'bottom-right',
    'enabled' => true,
];
```

## Advanced Customization

### Custom Position
To add custom positions, modify the switch statement in `footer.php`:

```php
case 'custom-position':
    $position_classes .= ' bottom-10 right-10';  // Your custom classes
    break;
```

### Multiple WhatsApp Numbers
For different numbers on different pages, you can modify the config loading in `footer.php`:

```php
// Example: Different numbers for different pages
if (is_page('contact')) {
    $whatsapp_config['phone'] = '60123456789';
} elseif (is_page('vehicles')) {
    $whatsapp_config['phone'] = '60987654321';
}
```

## Troubleshooting

### Button Not Showing
1. Check `'enabled' => true` in config
2. Clear browser cache
3. Check PHP error logs

### Wrong Number Format
- No spaces: `60194428040` ✅
- No + symbol: `+60194428040` ❌
- No dashes: `60-19-442-8040` ❌

### Message Not Appearing
1. Check for proper apostrophe escaping: `\'`
2. Ensure message is not empty
3. Test URL encoding with online tools

## File Locations
- **Config**: `web/app/themes/ckl-clone-theme/config/whatsapp.php`
- **Template**: `web/app/themes/ckl-clone-theme/footer.php`
- **Documentation**: `web/app/themes/ckl-clone-theme/WHATSAPP-CONFIG.md`

## Support
For issues or questions, check the WordPress admin panel or contact development team.