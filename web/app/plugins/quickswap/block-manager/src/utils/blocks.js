/**
 * Block Utilities
 *
 * @package QuickSwap\Block_Manager
 */

/**
 * Get all blocks from WordPress block registry
 *
 * @return {Array} Array of block objects
 */
export const getAllBlocks = () => {
	const blocksRegistry = select('core/blocks').getBlockTypes();

	return blocksRegistry.map((block) => ({
		name: block.name,
		title: block.title,
		description: block.description,
		icon: block.icon?.src || block.icon,
		category: block.category,
		keywords: block.keywords || [],
		supports: block.supports,
	}));
};

/**
 * Insert a block into the editor
 *
 * @param {string} blockName - The block name
 * @param {Object} attributes - Block attributes
 * @return {boolean} Success status
 */
export const insertBlock = (blockName, attributes = {}) => {
	try {
		const { createBlock } = window.wp.blocks;
		const { dispatch } = window.wp.data;

		const block = createBlock(blockName, attributes);
		const { getSelectedBlockClientId } = select('core/block-editor');
		const selectedBlockId = getSelectedBlockClientId();

		if (selectedBlockId) {
			dispatch('core/block-editor').insertBlocks(block, undefined, selectedBlockId);
		} else {
			dispatch('core/block-editor').insertBlocks(block);
		}

		return true;
	} catch (error) {
		console.error('Error inserting block:', error);
		return false;
	}
};

/**
 * Get block by name
 *
 * @param {string} blockName - The block name
 * @return {Object|null} Block object or null
 */
export const getBlockByName = (blockName) => {
	const { getBlockType } = select('core/blocks');
	return getBlockType(blockName);
};

/**
 * Get blocks by category
 *
 * @param {string} category - The category slug
 * @return {Array} Array of blocks in the category
 */
export const getBlocksByCategory = (category) => {
	const blocks = getAllBlocks();
	return blocks.filter((block) => block.category === category);
};

/**
 * Group blocks by category
 *
 * @return {Object} Object with category slugs as keys and block arrays as values
 */
export const groupBlocksByCategory = () => {
	const blocks = getAllBlocks();
	const grouped = {};

	blocks.forEach((block) => {
		if (!grouped[block.category]) {
			grouped[block.category] = [];
		}
		grouped[block.category].push(block);
	});

	return grouped;
};

/**
 * Search blocks
 *
 * @param {string} query - Search query
 * @return {Array} Array of matching blocks
 */
export const searchBlocks = (query) => {
	const blocks = getAllBlocks();
	const lowerQuery = query.toLowerCase();

	return blocks.filter((block) => {
		return (
			block.title?.toLowerCase().includes(lowerQuery) ||
			block.description?.toLowerCase().includes(lowerQuery) ||
			block.keywords?.some((keyword) => keyword.toLowerCase().includes(lowerQuery)) ||
			block.name?.toLowerCase().includes(lowerQuery)
		);
	});
};

/**
 * Get core blocks only
 *
 * @return {Array} Array of core blocks
 */
export const getCoreBlocks = () => {
	const blocks = getAllBlocks();
	return blocks.filter((block) => block.name.startsWith('core/'));
};
