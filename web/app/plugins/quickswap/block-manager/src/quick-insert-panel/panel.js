/**
 * Quick Insert Panel Component
 *
 * @package QuickSwap\Block_Manager
 */

import { Modal, Spinner } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import BlockSearch from './block-search';
import BlockList from './block-list';
import CollectionsList from './collections-list';

/**
 * Quick Insert Panel Component
 */
const QuickInsertPanel = () => {
	const { isPanelOpen, isLoading } = useSelect((select) => ({
		isPanelOpen: select('quickswap/block-manager').isPanelOpen(),
		isLoading: select('quickswap/block-manager').isLoading(),
	}));

	const { closePanel } = useDispatch('quickswap/block-manager');

	// Fetch data when panel opens
	useEffect(() => {
		if (isPanelOpen) {
			// Fetch all data using dispatch
			dispatch('quickswap/block-manager').fetchAllBlocks();
			dispatch('quickswap/block-manager').fetchCollections();
			dispatch('quickswap/block-manager').fetchFavorites();
			dispatch('quickswap/block-manager').fetchRecentBlocks();
		}
	}, [isPanelOpen, dispatch]);

	// Handle escape key
	useEffect(() => {
		const handleEscape = (e) => {
			if (e.key === 'Escape' && isPanelOpen) {
				closePanel();
			}
		};

		document.addEventListener('keydown', handleEscape);
		return () => document.removeEventListener('keydown', handleEscape);
	}, [isPanelOpen, closePanel]);

	// Trap focus within modal when open
	useEffect(() => {
		if (!isPanelOpen) return;

		// Focus management will be handled by the Modal component
		const focusableElements = 'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';

		const handleTab = (e) => {
			if (e.key !== 'Tab') return;

			const modal = document.querySelector('.quickswap-block-manager-panel');
			if (!modal) return;

			const focusable = modal.querySelectorAll(focusableElements);
			const firstFocusable = focusable[0];
			const lastFocusable = focusable[focusable.length - 1];

			if (e.shiftKey && document.activeElement === firstFocusable) {
				e.preventDefault();
				lastFocusable.focus();
			} else if (!e.shiftKey && document.activeElement === lastFocusable) {
				e.preventDefault();
				firstFocusable.focus();
			}
		};

		document.addEventListener('keydown', handleTab);
		return () => document.removeEventListener('keydown', handleTab);
	}, [isPanelOpen]);

	if (!isPanelOpen) {
		return null;
	}

	return (
		<Modal
			title={__('Quick Insert Block', 'quickswap')}
			onRequestClose={closePanel}
			className="quickswap-block-manager-panel"
			isDismissible
			shouldCloseOnEsc
			shouldCloseOnClickOutside
			focusOnMount
			role="dialog"
			aria-label={__('Quick Insert Block Panel', 'quickswap')}
		>
			<div className="quickswap-block-manager-panel__content">
				{isLoading && (
					<div className="quickswap-block-manager__loading-overlay">
						<Spinner />
						<span>{__('Loading blocks...', 'quickswap')}</span>
					</div>
				)}
				<BlockSearch />
				<CollectionsList />
				<BlockList />
			</div>
		</Modal>
	);
};

export default QuickInsertPanel;
