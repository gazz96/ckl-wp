/**
 * Toolbar Button Plugin
 *
 * This registers a button in the Gutenberg editor toolbar
 *
 * @package QuickSwap\Block_Manager
 */

import { ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { starFilled } from '@wordpress/icons';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';

/**
 * Add toolbar button to Gutenberg editor
 */
const withQuickSwapToolbarButton = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { openPanel } = useDispatch('quickswap/block-manager');

		return (
			<Fragment>
				<BlockEdit {...props} />
				<ToolbarGroup>
					<ToolbarButton
						icon={starFilled}
						label={__('Quick Insert Block', 'quickswap')}
						onClick={openPanel}
						showTooltip
					/>
				</ToolbarGroup>
			</Fragment>
		);
	};
}, 'withQuickSwapToolbarButton');

// Add the filter to modify the block edit
addFilter(
	'editor.BlockEdit',
	'quickswap/block-manager/toolbar-button',
	withQuickSwapToolbarButton
);
