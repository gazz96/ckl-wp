/**
 * Block Item Component
 *
 * @package QuickSwap\Block_Manager
 */

import { Button } from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import { createBlock } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Block Item Component
 */
const BlockItem = ({ block }) => {
	const { insertBlock } = useDispatch('core/block-editor');
	const { closePanel } = useDispatch('quickswap/block-manager');
	const { toggleFavorite } = useDispatch('quickswap/block-manager');

	const { isFavorite } = useSelect((select) => ({
		isFavorite: select('quickswap/block-manager').getFavorites().includes(block.name),
	}));

	/**
	 * Handle block insertion
	 */
	const handleInsert = () => {
		const newBlock = createBlock(block.name);
		insertBlock(newBlock);
		closePanel();

		// Track usage
		wp.apiFetch({
			path: '/quickswap-block-manager/v1/track-usage',
			method: 'POST',
			data: { block: block.name },
		}).catch((error) => {
			console.error('Error tracking block usage:', error);
		});
	};

	/**
	 * Handle keyboard navigation
	 */
	const handleKeyDown = (e) => {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			handleInsert();
		}
	};

	/**
	 * Handle favorite toggle
	 */
	const handleFavoriteToggle = (e) => {
		e.stopPropagation();
		toggleFavorite(block.name);

		// Sync with server
		wp.apiFetch({
			path: '/quickswap-block-manager/v1/favorites',
			method: 'POST',
			data: { block: block.name },
		}).catch((error) => {
			console.error('Error toggling favorite:', error);
		});
	};

	return (
		<div
			className="quickswap-block-manager__block-item"
			onClick={handleInsert}
			onKeyDown={handleKeyDown}
			role="button"
			tabIndex={0}
			aria-label={sprintf(
				/* translators: %s: Block title */
				__('Insert %s block', 'quickswap'),
				block.title
			)}
		>
			<div className="quickswap-block-manager__block-icon" aria-hidden="true">
				{block.icon && typeof block.icon === 'object' ? (
					<block.icon />
				) : (
					<span className={`dashicons dashicons-${block.icon || 'block-default'}`} />
				)}
			</div>
			<div className="quickswap-block-manager__block-info">
				<div className="quickswap-block-manager__block-title">{block.title}</div>
				{block.description && (
					<div className="quickswap-block-manager__block-description">
						{block.description}
					</div>
				)}
			</div>
			<Button
				icon={isFavorite ? 'star-filled' : 'star-empty'}
				label={isFavorite ? __('Remove from favorites', 'quickswap') : __('Add to favorites', 'quickswap')}
				onClick={handleFavoriteToggle}
				className="quickswap-block-manager__block-favorite"
				isSmall
				aria-pressed={isFavorite}
			/>
		</div>
	);
};

export default BlockItem;
