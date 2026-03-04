# QuickSwap Block Manager

A Gutenberg Block Manager feature for QuickSwap plugin, inspired by Kadance WordPress. Allows users to quickly insert blocks into Gutenberg editor through a toolbar-integrated Quick Insert Panel with Block Collections management.

## Features

- **Quick Insert Panel** - Fast access to insert blocks via Cmd/Ctrl+K menu
- **Block Collections** - Organize blocks into custom categories
- **Favorites** - Mark frequently used blocks as favorites
- **Recent Blocks** - Track recently used blocks
- **Search** - Real-time search across all blocks with 150ms debouncing
- **Admin Interface** - Manage collections with import/export functionality
- **Keyboard Navigation** - Full keyboard support for accessibility
- **Responsive Design** - Works on all screen sizes

## Installation

1. Copy the `block-manager` directory to `/wp-content/plugins/quickswap/`
2. Run `npm install` to install dependencies
3. Run `npm run build` to build the assets
4. The Block Manager will be automatically loaded by QuickSwap

## Development

### Build Scripts

- `npm run build` - Build both block manager and admin assets
- `npm run build:block-manager` - Build only block manager assets
- `npm run build:admin` - Build only admin assets
- `npm start` - Start development mode for block manager

### File Structure

```
block-manager/
├── src/                          # React source files
│   ├── index.js                  # Main entry point
│   ├── store/                    # Redux/Data API store
│   ├── quick-insert-panel/       # Panel components
│   ├── toolbar-button/           # Toolbar button
│   ├── admin/                    # Admin app
│   └── utils/                    # Utility functions
├── build/                        # Built assets (generated)
├── build-admin/                  # Admin built assets (generated)
├── includes/                     # PHP backend classes
├── templates/                    # PHP templates
└── block-manager.php             # Main entry point
```

## REST API Endpoints

### Collections
- `GET /wp-json/quickswap-block-manager/v1/collections` - Get all collections
- `POST /wp-json/quickswap-block-manager/v1/collections` - Create collection
- `GET /wp-json/quickswap-block-manager/v1/collections/{id}` - Get single collection
- `PUT /wp-json/quickswap-block-manager/v1/collections/{id}` - Update collection
- `DELETE /wp-json/quickswap-block-manager/v1/collections/{id}` - Delete collection

### Preferences
- `GET /wp-json/quickswap-block-manager/v1/preferences` - Get user preferences
- `POST /wp-json/quickswap-block-manager/v1/preferences` - Update preferences

### Blocks
- `GET /wp-json/quickswap-block-manager/v1/blocks` - Get all registered blocks
- `POST /wp-json/quickswap-block-manager/v1/track-usage` - Track block usage

### Favorites
- `GET /wp-json/quickswap-block-manager/v1/favorites` - Get favorite blocks
- `POST /wp-json/quickswap-block-manager/v1/favorites` - Toggle favorite

### Recent
- `GET /wp-json/quickswap-block-manager/v1/recent` - Get recent blocks

## Data Storage

### User Meta (per user)
- `quickswap_block_collections` - User's collections
- `quickswap_favorite_blocks` - User's favorite blocks
- `quickswap_recent_blocks` - User's recent blocks (last 50)
- `quickswap_block_manager_settings` - User preferences

### Site Options
- `quickswap_default_collections` - Default collections for new users

## Keyboard Shortcuts

- `Cmd/Ctrl + K` then click "Quick Insert Block" - Open Quick Insert Panel
- `Escape` - Close panel
- `Tab` - Navigate between controls
- `Enter` - Insert selected block

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Accessibility

- Full keyboard navigation
- ARIA attributes
- Screen reader support
- Focus management
- High contrast support

## Dependencies

- @wordpress/scripts ^27.0.0
- React 18
- WordPress 6.0+
- PHP 7.4+

## License

GPL-2.0-or-later

## Credits

Developed by CK Langkawi (https://cklangkawi.com)
Inspired by Kadance WordPress
