/**
 * QuickSwap Block Manager - Admin App
 *
 * @package QuickSwap\Block_Manager
 */

// Import styles
import './admin.scss';

import { render } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import AdminApp from './admin-app';

// Render the admin app
document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('quickswap-block-collections-app');

	if (container) {
		render(<AdminApp />, container);
	}
});

export default AdminApp;
