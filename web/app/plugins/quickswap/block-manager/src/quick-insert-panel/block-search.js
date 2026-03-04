/**
 * Block Search Component
 *
 * @package QuickSwap\Block_Manager
 */

import { TextControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Block Search Component
 */
const BlockSearch = () => {
	const [query, setQuery] = useState('');
	const { setSearchQuery } = useDispatch('quickswap/block-manager');

	useEffect(() => {
		const timer = setTimeout(() => {
			setSearchQuery(query);
		}, 150); // 150ms debounce

		return () => clearTimeout(timer);
	}, [query, setSearchQuery]);

	return (
		<div className="quickswap-block-manager__search">
			<TextControl
				value={query}
				onChange={setQuery}
				placeholder={__('Search blocks...', 'quickswap')}
				autoFocus
				className="quickswap-block-manager__search-input"
			/>
		</div>
	);
};

export default BlockSearch;
