/**
 * Collections List Component
 *
 * @package QuickSwap\Block_Manager
 */

import { TabPanel } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Collections List Component
 */
const CollectionsList = () => {
	const { collections, activeTab } = useSelect((select) => ({
		collections: select('quickswap/block-manager').getCollections(),
		activeTab: select('quickswap/block-manager').getActiveTab(),
	}));

	const { setActiveTab, setSelectedCollection } = useDispatch('quickswap/block-manager');

	const tabs = [
		{
			name: 'all',
			title: __('All Blocks', 'quickswap'),
			className: 'quickswap-block-manager__tab-all',
		},
		{
			name: 'favorites',
			title: __('Favorites', 'quickswap'),
			className: 'quickswap-block-manager__tab-favorites',
		},
		{
			name: 'recent',
			title: __('Recent', 'quickswap'),
			className: 'quickswap-block-manager__tab-recent',
		},
	];

	// Add collections as tabs
	collections.forEach((collection) => {
		tabs.push({
			name: `collection-${collection.id}`,
			title: collection.name,
			className: 'quickswap-block-manager__tab-collection',
			collectionId: collection.id,
		});
	});

	const handleTabSelect = (tabName) => {
		if (tabName.startsWith('collection-')) {
			const collectionId = tabName.replace('collection-', '');
			setActiveTab('collections');
			setSelectedCollection(collectionId);
		} else {
			setActiveTab(tabName);
			setSelectedCollection(null);
		}
	};

	return (
		<TabPanel
			tabs={tabs}
			activeClass="is-active"
			onSelect={handleTabSelect}
			className="quickswap-block-manager__tabs"
			initialTabName={activeTab}
		>
			{(tab) => (
				<div className="quickswap-block-manager__tab-description">
					{tab.name === 'all' && <p>{__('Browse all available blocks', 'quickswap')}</p>}
					{tab.name === 'favorites' && (
						<p>{__('Your favorite blocks', 'quickswap')}</p>
					)}
					{tab.name === 'recent' && <p>{__('Recently used blocks', 'quickswap')}</p>}
					{tab.name.startsWith('collection-') && (
						<p>
							{collections.find((c) => c.id === tab.collectionId)?.description ||
								__('Custom collection', 'quickswap')}
						</p>
					)}
				</div>
			)}
		</TabPanel>
	);
};

export default CollectionsList;
