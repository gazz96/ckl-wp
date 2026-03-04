/**
 * QuickSwap Block Manager - Data Store
 *
 * @package QuickSwap\Block_Manager
 */

import { registerStore } from '@wordpress/data';

/**
 * Default state
 */
const DEFAULT_STATE = {
	isPanelOpen: false,
	searchQuery: '',
	activeTab: 'all', // all, favorites, recent, collections
	selectedBlock: null,
	selectedCollection: null,
	collections: [],
	favorites: [],
	recentBlocks: [],
	allBlocks: [],
	isLoading: false,
};

/**
 * Actions
 */
const actions = {
	togglePanel: () => ({ type: 'TOGGLE_PANEL' }),
	openPanel: () => ({ type: 'OPEN_PANEL' }),
	closePanel: () => ({ type: 'CLOSE_PANEL' }),
	setSearchQuery: (query) => ({ type: 'SET_SEARCH_QUERY', query }),
	setActiveTab: (tab) => ({ type: 'SET_ACTIVE_TAB', tab }),
	setSelectedCollection: (collectionId) => ({ type: 'SET_SELECTED_COLLECTION', collectionId }),
	setSelectedBlock: (blockName) => ({ type: 'SET_SELECTED_BLOCK', blockName }),
	setLoading: (isLoading) => ({ type: 'SET_LOADING', isLoading }),
	setCollections: (collections) => ({ type: 'SET_COLLECTIONS', collections }),
	setFavorites: (favorites) => ({ type: 'SET_FAVORITES', favorites }),
	setRecentBlocks: (recentBlocks) => ({ type: 'SET_RECENT_BLOCKS', recentBlocks }),
	setAllBlocks: (allBlocks) => ({ type: 'SET_ALL_BLOCKS', blocks: allBlocks }),

	/**
	 * Toggle favorite status for a block
	 */
	toggleFavorite: (blockName) => async ({ dispatch, select }) => {
		const favorites = select.getFavorites();
		const isFavorite = favorites.includes(blockName);

		// Optimistic update
		const newFavorites = isFavorite
			? favorites.filter((b) => b !== blockName)
			: [...favorites, blockName];

		dispatch.setFavorites(newFavorites);

		// Sync with server
		try {
			const response = await wp.apiFetch({
				path: '/quickswap-block-manager/v1/favorites',
				method: 'POST',
				data: { block: blockName },
			});
			dispatch.setFavorites(response.favorites || newFavorites);
		} catch (error) {
			console.error('Error toggling favorite:', error);
			// Revert on error
			dispatch.setFavorites(favorites);
		}
	},

	/**
	 * Fetch all blocks from API
	 */
	fetchAllBlocks: () => async ({ dispatch }) => {
		dispatch.setLoading(true);
		try {
			const response = await wp.apiFetch({
				path: '/quickswap-block-manager/v1/blocks',
				method: 'GET',
			});
			dispatch.setAllBlocks(response);
		} catch (error) {
			console.error('Error fetching blocks:', error);
		} finally {
			dispatch.setLoading(false);
		}
	},

	/**
	 * Fetch collections from API
	 */
	fetchCollections: () => async ({ dispatch }) => {
		try {
			const response = await wp.apiFetch({
				path: '/quickswap-block-manager/v1/collections',
				method: 'GET',
			});
			dispatch.setCollections(response);
		} catch (error) {
			console.error('Error fetching collections:', error);
		}
	},

	/**
	 * Fetch favorites from API
	 */
	fetchFavorites: () => async ({ dispatch }) => {
		try {
			const response = await wp.apiFetch({
				path: '/quickswap-block-manager/v1/favorites',
				method: 'GET',
			});
			dispatch.setFavorites(response.map((b) => b.name));
		} catch (error) {
			console.error('Error fetching favorites:', error);
		}
	},

	/**
	 * Fetch recent blocks from API
	 */
	fetchRecentBlocks: () => async ({ dispatch }) => {
		try {
			const response = await wp.apiFetch({
				path: '/quickswap-block-manager/v1/recent',
				method: 'GET',
			});
			dispatch.setRecentBlocks(response.map((b) => b.name));
		} catch (error) {
			console.error('Error fetching recent blocks:', error);
		}
	},
};

/**
 * Reducer
 */
const reducer = (state = DEFAULT_STATE, action) => {
	switch (action.type) {
		case 'TOGGLE_PANEL':
			return {
				...state,
				isPanelOpen: !state.isPanelOpen,
			};

		case 'OPEN_PANEL':
			return {
				...state,
				isPanelOpen: true,
			};

		case 'CLOSE_PANEL':
			return {
				...state,
				isPanelOpen: false,
				searchQuery: '',
				selectedBlock: null,
			};

		case 'SET_SEARCH_QUERY':
			return {
				...state,
				searchQuery: action.query,
			};

		case 'SET_ACTIVE_TAB':
			return {
				...state,
				activeTab: action.tab,
				selectedCollection: null,
			};

		case 'SET_SELECTED_COLLECTION':
			return {
				...state,
				selectedCollection: action.collectionId,
			};

		case 'SET_SELECTED_BLOCK':
			return {
				...state,
				selectedBlock: action.blockName,
			};

		case 'SET_LOADING':
			return {
				...state,
				isLoading: action.isLoading,
			};

		case 'SET_COLLECTIONS':
			return {
				...state,
				collections: action.collections,
			};

		case 'SET_FAVORITES':
			return {
				...state,
				favorites: action.favorites,
			};

		case 'SET_RECENT_BLOCKS':
			return {
				...state,
				recentBlocks: action.recentBlocks,
			};

		case 'SET_ALL_BLOCKS':
			return {
				...state,
				allBlocks: action.blocks,
			};

		default:
			return state;
	}
};

/**
 * Selectors
 */
const selectors = {
	isPanelOpen: (state) => state.isPanelOpen,
	getSearchQuery: (state) => state.searchQuery,
	getActiveTab: (state) => state.activeTab,
	getSelectedCollection: (state) => state.selectedCollection,
	getSelectedBlock: (state) => state.selectedBlock,
	isLoading: (state) => state.isLoading,
	getCollections: (state) => state.collections,
	getFavorites: (state) => state.favorites,
	getRecentBlocks: (state) => state.recentBlocks,
	getAllBlocks: (state) => state.allBlocks,

	/**
	 * Get filtered blocks based on search query
	 */
	getFilteredBlocks: (state) => {
		const { allBlocks, searchQuery } = state;

		if (!searchQuery) {
			return allBlocks;
		}

		const query = searchQuery.toLowerCase();

		return allBlocks.filter((block) => {
			return (
				block.title?.toLowerCase().includes(query) ||
				block.description?.toLowerCase().includes(query) ||
				block.keywords?.some((keyword) => keyword.toLowerCase().includes(query)) ||
				block.name?.toLowerCase().includes(query)
			);
		});
	},

	/**
	 * Get blocks for current tab
	 */
	getBlocksForTab: (state) => {
		const { allBlocks, favorites, recentBlocks, activeTab, selectedCollection, collections } = state;

		switch (activeTab) {
			case 'favorites':
				return allBlocks.filter((block) => favorites.includes(block.name));

			case 'recent':
				return allBlocks.filter((block) => recentBlocks.includes(block.name));

			case 'collections':
				if (!selectedCollection) return [];
				const collection = collections.find((c) => c.id === selectedCollection);
				if (!collection) return [];
				return allBlocks.filter((block) => collection.blocks?.includes(block.name));

			default:
				return allBlocks;
		}
	},
};

/**
 * Register the store
 */
registerStore('quickswap/block-manager', {
	reducer,
	actions,
	selectors,
});
