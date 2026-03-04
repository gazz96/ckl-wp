/**
 * Toolbar Button Component
 *
 * @package QuickSwap\Block_Manager
 */

import { Button } from '@wordpress/components';
import { starFilled } from '@wordpress/icons';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Toolbar Button Component
 */
const ToolbarButton = () => {
	const { openPanel } = useDispatch('quickswap/block-manager');

	return (
		<Button
			icon={starFilled}
			label={__('Quick Insert Panel', 'quickswap')}
			onClick={openPanel}
			isPrimary
		>
			{__('Quick Insert', 'quickswap')}
		</Button>
	);
};

export default ToolbarButton;
