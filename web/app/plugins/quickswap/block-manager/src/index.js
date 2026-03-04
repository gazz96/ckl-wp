/**
 * QuickSwap Block Manager - Main Entry Point
 *
 * @package QuickSwap\Block_Manager
 */

import { registerPlugin } from '@wordpress/plugins';
import { Icon, starFilled } from '@wordpress/icons';
import { PluginMoreMenuItem } from '@wordpress/edit-post';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

// Import styles
import './style.scss';

// Import components
import QuickInsertPanel from './quick-insert-panel';

// Register store
import './store';

/**
 * Main Block Manager Plugin Component
 */
const BlockManagerPlugin = () => {
	const { openPanel } = useDispatch('quickswap/block-manager');

	return (
		<>
			<PluginMoreMenuItem
				icon={starFilled}
				onClick={openPanel}
			>
				{__('Quick Insert Block', 'quickswap')}
			</PluginMoreMenuItem>
			<QuickInsertPanel />
		</>
	);
};

// Register keyboard shortcut for opening panel
wp.domReady(() => {
	const { dispatch } = window.wp.data;
	const { registerShortcut } = dispatch('core/keyboard-shortcuts');

	registerShortcut({
		name: 'quickswap/block-manager/open-panel',
		category: 'global',
		description: __('Open QuickSwap Block Manager panel', 'quickswap'),
		keyCombination: {
			modifier: 'primary',
			character: 'b',
		},
		callback: () => {
			const { openPanel } = dispatch('quickswap/block-manager');
			openPanel();
		},
	});
});

/**
 * Register the plugin
 */
registerPlugin('quickswap-block-manager', {
	icon: <Icon icon={starFilled} />,
	render: BlockManagerPlugin,
});

// Export components for external use if needed
export { QuickInsertPanel };
