/**
 * Block List Component
 *
 * @package QuickSwap\Block_Manager
 */

import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import BlockItem from './block-item';

/**
 * Block List Component
 */
const BlockList = () => {
	const { getBlocksForTab, getFilteredBlocks, isLoading } = useSelect((select) => ({
		getBlocksForTab: select('quickswap/block-manager').getBlocksForTab(),
		getFilteredBlocks: select('quickswap/block-manager').getFilteredBlocks(),
		isLoading: select('quickswap/block-manager').isLoading(),
	}));

	// Combine tab filtering with search filtering
	const blocks = getBlocksForTab.filter((block) =>
		getFilteredBlocks.some((filtered) => filtered.name === block.name)
	);

	if (isLoading) {
		return (
			<div className="quickswap-block-manager__loading">
				{__('Loading blocks...', 'quickswap')}
			</div>
		);
	}

	if (blocks.length === 0) {
		return (
			<div className="quickswap-block-manager__no-results">
				{__('No blocks found', 'quickswap')}
			</div>
		);
	}

	return (
		<div className="quickswap-block-manager__block-list">
			{blocks.map((block) => (
				<BlockItem key={block.name} block={block} />
			))}
		</div>
	);
};

export default BlockList;
