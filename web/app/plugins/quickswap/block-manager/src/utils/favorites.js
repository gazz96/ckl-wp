/**
 * Favorites Utilities
 *
 * @package QuickSwap\Block_Manager
 */

/**
 * Fetch favorite blocks from API
 *
 * @return {Promise<Array>} Array of favorite block names
 */
export const fetchFavorites = async () => {
	try {
		const response = await wp.apiFetch({
			path: '/quickswap-block-manager/v1/favorites',
			method: 'GET',
		});
		return response.map((block) => block.name);
	} catch (error) {
		console.error('Error fetching favorites:', error);
		return [];
	}
};

/**
 * Toggle favorite status for a block
 *
 * @param {string} blockName - Block name
 * @return {Promise<Array>} Updated favorites array
 */
export const toggleFavorite = async (blockName) => {
	try {
		const response = await wp.apiFetch({
			path: '/quickswap-block-manager/v1/favorites',
			method: 'POST',
			data: { block: blockName },
		});
		return response.favorites || [];
	} catch (error) {
		console.error('Error toggling favorite:', error);
		throw error;
	}
};

/**
 * Check if a block is favorited
 *
 * @param {string} blockName - Block name
 * @param {Array} favorites - Favorites array
 * @return {boolean} Is favorited
 */
export const isFavorite = (blockName, favorites) => {
	return favorites.includes(blockName);
};

/**
 * Add block to favorites (client-side only, use toggleFavorite for persistence)
 *
 * @param {string} blockName - Block name
 * @param {Array} favorites - Current favorites
 * @return {Array} Updated favorites
 */
export const addFavorite = (blockName, favorites) => {
	if (favorites.includes(blockName)) {
		return favorites;
	}
	return [...favorites, blockName];
};

/**
 * Remove block from favorites (client-side only, use toggleFavorite for persistence)
 *
 * @param {string} blockName - Block name
 * @param {Array} favorites - Current favorites
 * @return {Array} Updated favorites
 */
export const removeFavorite = (blockName, favorites) => {
	return favorites.filter((name) => name !== blockName);
};
