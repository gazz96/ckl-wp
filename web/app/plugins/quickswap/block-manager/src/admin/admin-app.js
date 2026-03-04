/**
 * Admin App Component
 *
 * @package QuickSwap\Block_Manager
 */

import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Modal, TextControl, TextareaControl, SelectControl } from '@wordpress/components';
import { plus, trash, edit, download, upload } from '@wordpress/icons';

/**
 * Admin App Component
 */
const AdminApp = () => {
	const [collections, setCollections] = useState([]);
	const [isLoading, setIsLoading] = useState(true);
	const [isModalOpen, setIsModalOpen] = useState(false);
	const [editingCollection, setEditingCollection] = useState(null);
	const [formData, setFormData] = useState({
		name: '',
		description: '',
		icon: 'star-filled',
		blocks: [],
	});

	// Available block icons
	const icons = [
		{ value: 'star-filled', label: __('Star', 'quickswap') },
		{ value: 'editor-bold', label: __('Bold', 'quickswap') },
		{ value: 'editor-italic', label: __('Italic', 'quickswap') },
		{ value: 'editor-ul', label: __('List', 'quickswap') },
		{ value: 'editor-quote', label: __('Quote', 'quickswap') },
		{ value: 'format-image', label: __('Image', 'quickswap') },
		{ value: 'media-video', label: __('Video', 'quickswap') },
		{ value: 'media-audio', label: __('Audio', 'quickswap') },
		{ value: 'layout', label: __('Layout', 'quickswap') },
		{ value: 'shortcode', label: __('Shortcode', 'quickswap') },
	];

	// Fetch collections on mount
	useEffect(() => {
		fetchCollections();
	}, []);

	/**
	 * Fetch collections from API
	 */
	const fetchCollections = async () => {
		setIsLoading(true);
		try {
			const response = await wp.apiFetch({
				path: '/quickswap-block-manager/v1/collections',
				method: 'GET',
			});
			setCollections(response);
		} catch (error) {
			console.error('Error fetching collections:', error);
		} finally {
			setIsLoading(false);
		}
	};

	/**
	 * Handle form submit
	 */
	const handleSubmit = async (e) => {
		e.preventDefault();

		try {
			if (editingCollection) {
				// Update existing collection
				await wp.apiFetch({
					path: `/quickswap-block-manager/v1/collections/${editingCollection.id}`,
					method: 'PUT',
					data: formData,
				});
			} else {
				// Create new collection
				await wp.apiFetch({
					path: '/quickswap-block-manager/v1/collections',
					method: 'POST',
					data: formData,
				});
			}

			// Refresh collections and close modal
			await fetchCollections();
			setIsModalOpen(false);
			setEditingCollection(null);
			setFormData({ name: '', description: '', icon: 'star-filled', blocks: [] });
		} catch (error) {
			console.error('Error saving collection:', error);
		}
	};

	/**
	 * Handle edit
	 */
	const handleEdit = (collection) => {
		setEditingCollection(collection);
		setFormData({
			name: collection.name,
			description: collection.description,
			icon: collection.icon,
			blocks: collection.blocks || [],
		});
		setIsModalOpen(true);
	};

	/**
	 * Handle delete
	 */
	const handleDelete = async (collectionId) => {
		if (!confirm(__('Are you sure you want to delete this collection?', 'quickswap'))) {
			return;
		}

		try {
			await wp.apiFetch({
				path: `/quickswap-block-manager/v1/collections/${collectionId}`,
				method: 'DELETE',
			});
			await fetchCollections();
		} catch (error) {
			console.error('Error deleting collection:', error);
		}
	};

	/**
	 * Handle export
	 */
	const handleExport = async () => {
		try {
			const data = {
				version: '1.0',
				exported_at: new Date().toISOString(),
				collections,
			};

			const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
			const url = URL.createObjectURL(blob);
			const a = document.createElement('a');
			a.href = url;
			a.download = `quickswap-collections-${Date.now()}.json`;
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			URL.revokeObjectURL(url);
		} catch (error) {
			console.error('Error exporting collections:', error);
		}
	};

	/**
	 * Handle import
	 */
	const handleImport = () => {
		const input = document.createElement('input');
		input.type = 'file';
		input.accept = 'application/json';

		input.onchange = async (e) => {
			const file = e.target.files[0];
			if (!file) return;

			try {
				const text = await file.text();
				const data = JSON.parse(text);

				if (!data.collections || !Array.isArray(data.collections)) {
					alert(__('Invalid file format', 'quickswap'));
					return;
				}

				// Import each collection
				for (const collection of data.collections) {
					await wp.apiFetch({
						path: '/quickswap-block-manager/v1/collections',
						method: 'POST',
						data: {
							name: collection.name,
							description: collection.description || '',
							blocks: collection.blocks || [],
							icon: collection.icon || 'star-filled',
						},
					});
				}

				await fetchCollections();
				alert(__('Collections imported successfully!', 'quickswap'));
			} catch (error) {
				console.error('Error importing collections:', error);
				alert(__('Error importing collections', 'quickswap'));
			}
		};

		input.click();
	};

	return (
		<div className="quickswap-block-admin">
			<div className="quickswap-block-admin__header">
				<h2>{__('Block Collections', 'quickswap')}</h2>
				<div className="quickswap-block-admin__actions">
					<Button
						icon={plus}
						onClick={() => {
							setEditingCollection(null);
							setFormData({ name: '', description: '', icon: 'star-filled', blocks: [] });
							setIsModalOpen(true);
						}}
						isPrimary
					>
						{__('Add New Collection', 'quickswap')}
					</Button>
					<Button icon={download} onClick={handleExport}>
						{__('Export', 'quickswap')}
					</Button>
					<Button icon={upload} onClick={handleImport}>
						{__('Import', 'quickswap')}
					</Button>
				</div>
			</div>

			{isLoading ? (
				<div className="quickswap-block-admin__loading">
					{__('Loading collections...', 'quickswap')}
				</div>
			) : collections.length === 0 ? (
				<div className="quickswap-block-admin__empty">
					<p>{__('No collections found. Create your first collection to get started.', 'quickswap')}</p>
				</div>
			) : (
				<div className="quickswap-block-admin__list">
					{collections.map((collection) => (
						<div key={collection.id} className="quickswap-block-admin__item">
							<div className="quickswap-block-admin__item-icon">
								<span className={`dashicons dashicons-${collection.icon || 'star-filled'}`} />
							</div>
							<div className="quickswap-block-admin__item-content">
								<h3 className="quickswap-block-admin__item-title">{collection.name}</h3>
								{collection.description && (
									<p className="quickswap-block-admin__item-description">
										{collection.description}
									</p>
								)}
								<p className="quickswap-block-admin__item-count">
									{sprintf(__('%d blocks', 'quickswap'), collection.blocks?.length || 0)}
								</p>
							</div>
							<div className="quickswap-block-admin__item-actions">
								<Button
									icon={edit}
									onClick={() => handleEdit(collection)}
									label={__('Edit', 'quickswap')}
									isSmall
								/>
								<Button
									icon={trash}
									onClick={() => handleDelete(collection.id)}
									label={__('Delete', 'quickswap')}
									isDestructive
									isSmall
								/>
							</div>
						</div>
					))}
				</div>
			)}

			{isModalOpen && (
				<Modal
					title={editingCollection ? __('Edit Collection', 'quickswap') : __('Add New Collection', 'quickswap')}
					onRequestClose={() => {
						setIsModalOpen(false);
						setEditingCollection(null);
					}}
					className="quickswap-block-admin__modal"
				>
					<form onSubmit={handleSubmit}>
						<TextControl
							label={__('Name', 'quickswap')}
							value={formData.name}
							onChange={(name) => setFormData({ ...formData, name })}
							required
						/>

						<TextareaControl
							label={__('Description', 'quickswap')}
							value={formData.description}
							onChange={(description) => setFormData({ ...formData, description })}
							rows={3}
						/>

						<SelectControl
							label={__('Icon', 'quickswap')}
							value={formData.icon}
							onChange={(icon) => setFormData({ ...formData, icon })}
							options={icons}
						/>

						<div className="quickswap-block-admin__modal-actions">
							<Button onClick={() => setIsModalOpen(false)}>
								{__('Cancel', 'quickswap')}
							</Button>
							<Button type="submit" isPrimary>
								{editingCollection ? __('Update Collection', 'quickswap') : __('Create Collection', 'quickswap')}
							</Button>
						</div>
					</form>
				</Modal>
			)}
		</div>
	);
};

export default AdminApp;
