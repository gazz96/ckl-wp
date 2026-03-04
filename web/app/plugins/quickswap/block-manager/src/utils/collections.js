/**
 * Collection Utilities
 *
 * @package QuickSwap\Block_Manager
 */

/**
 * Fetch all collections from API
 *
 * @return {Promise<Array>} Array of collections
 */
export const fetchCollections = async () => {
	try {
		const response = await wp.apiFetch({
			path: '/quickswap-block-manager/v1/collections',
			method: 'GET',
		});
		return response;
	} catch (error) {
		console.error('Error fetching collections:', error);
		return [];
	}
};

/**
 * Create a new collection
 *
 * @param {Object} collection - Collection data
 * @return {Promise<Object>} Created collection
 */
export const createCollection = async (collection) => {
	try {
		const response = await wp.apiFetch({
			path: '/quickswap-block-manager/v1/collections',
			method: 'POST',
			data: collection,
		});
		return response;
	} catch (error) {
		console.error('Error creating collection:', error);
		throw error;
	}
};

/**
 * Update a collection
 *
 * @param {string} id - Collection ID
 * @param {Object} data - Updated data
 * @return {Promise<Object>} Updated collection
 */
export const updateCollection = async (id, data) => {
	try {
		const response = await wp.apiFetch({
			path: `/quickswap-block-manager/v1/collections/${id}`,
			method: 'PUT',
			data,
		});
		return response;
	} catch (error) {
		console.error('Error updating collection:', error);
		throw error;
	}
};

/**
 * Delete a collection
 *
 * @param {string} id - Collection ID
 * @return {Promise<boolean>} Success status
 */
export const deleteCollection = async (id) => {
	try {
		await wp.apiFetch({
			path: `/quickswap-block-manager/v1/collections/${id}`,
			method: 'DELETE',
		});
		return true;
	} catch (error) {
		console.error('Error deleting collection:', error);
		throw error;
	}
};

/**
 * Export collections to JSON
 *
 * @return {string} JSON string
 */
export const exportCollections = async () => {
	try {
		const collections = await fetchCollections();
		const data = {
			version: '1.0',
			exported_at: new Date().toISOString(),
			collections,
		};
		return JSON.stringify(data, null, 2);
	} catch (error) {
		console.error('Error exporting collections:', error);
		throw error;
	}
};

/**
 * Import collections from JSON
 *
 * @param {string} jsonString - JSON string
 * @return {Promise<Array>} Imported collections
 */
export const importCollections = async (jsonString) => {
	try {
		const data = JSON.parse(jsonString);

		if (!data.collections || !Array.isArray(data.collections)) {
			throw new Error('Invalid collections format');
		}

		const imported = [];

		for (const collection of data.collections) {
			const created = await createCollection({
				name: collection.name,
				description: collection.description || '',
				blocks: collection.blocks || [],
				icon: collection.icon || 'star-filled',
			});
			imported.push(created);
		}

		return imported;
	} catch (error) {
		console.error('Error importing collections:', error);
		throw error;
	}
};

/**
 * Validate collection data
 *
 * @param {Object} collection - Collection to validate
 * @return {boolean} Valid status
 */
export const validateCollection = (collection) => {
	return (
		typeof collection === 'object' &&
		typeof collection.name === 'string' &&
		collection.name.trim().length > 0 &&
		Array.isArray(collection.blocks)
	);
};
