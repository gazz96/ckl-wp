/**
 * QuickSwap - Main JavaScript
 *
 * Main orchestrator for QuickSwap functionality
 *
 * @package QuickSwap
 * @since 1.0.0
 */

(function() {
    'use strict';

    // QuickSwap Application
    const QuickSwap = {
        isOpen: false,
        selectedIndex: -1,
        results: [],
        searchTimeout: null,
        debounceTime: 150,

        /**
         * Initialize QuickSwap
         */
        init() {
            this.elements = this.getElements();
            this.bindEvents();
            this.checkMacintosh();
        },

        /**
         * Get DOM elements
         */
        getElements() {
            return {
                modal: document.getElementById('quickswap-modal'),
                overlay: document.querySelector('.quickswap-overlay'),
                input: document.getElementById('quickswap-input'),
                closeBtn: document.querySelector('.quickswap-close'),
                resultsContainer: document.getElementById('quickswap-results'),
            };
        },

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Keyboard shortcut
            document.addEventListener('keydown', (e) => this.handleGlobalKeydown(e));

            // Modal events
            if (this.elements.overlay) {
                this.elements.overlay.addEventListener('click', () => this.close());
            }

            if (this.elements.closeBtn) {
                this.elements.closeBtn.addEventListener('click', () => this.close());
            }

            // Input events
            if (this.elements.input) {
                this.elements.input.addEventListener('input', (e) => this.handleInput(e));
                this.elements.input.addEventListener('keydown', (e) => this.handleKeydown(e));
            }

            // Result click events
            if (this.elements.resultsContainer) {
                this.elements.resultsContainer.addEventListener('click', (e) => this.handleResultClick(e));
                this.elements.resultsContainer.addEventListener('mouseover', (e) => this.handleHover(e));
            }

            // Focus trap
            if (this.elements.modal) {
                this.elements.modal.addEventListener('focusin', (e) => this.handleFocusTrap(e));
            }
        },

        /**
         * Handle global keyboard shortcuts
         */
        handleGlobalKeydown(e) {
            // Cmd/Ctrl + K to open
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                this.toggle();
                return;
            }

            // Escape to close
            if (e.key === 'Escape' && this.isOpen) {
                e.preventDefault();
                this.close();
                return;
            }

            // Arrow keys for navigation
            if (this.isOpen) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    // Let handleKeydown handle navigation
                    return;
                }

                // Enter to open selected
                if (e.key === 'Enter' && !e.shiftKey && !e.metaKey && !e.ctrlKey) {
                    e.preventDefault();
                    this.openSelected();
                    return;
                }

                // Cmd/Ctrl + Enter to edit
                if (e.key === 'Enter' && (e.metaKey || e.ctrlKey)) {
                    e.preventDefault();
                    this.editSelected();
                    return;
                }
            }
        },

        /**
         * Handle input keydown
         */
        handleKeydown(e) {
            if (!this.isOpen) return;

            // Arrow down
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.selectNext();
                return;
            }

            // Arrow up
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.selectPrevious();
                return;
            }

            // Tab to navigate actions
            if (e.key === 'Tab') {
                e.preventDefault();
                this.navigateActions(e.shiftKey ? -1 : 1);
                return;
            }
        },

        /**
         * Handle input change
         */
        handleInput(e) {
            const query = e.target.value.trim();

            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            // Debounce search
            this.searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, this.debounceTime);

            // Reset selection
            this.selectedIndex = -1;
        },

        /**
         * Handle result click
         */
        handleResultClick(e) {
            // FIRST: Check if action button was clicked (highest priority)
            const actionBtn = e.target.closest('.quickswap-action-btn');
            if (actionBtn) {
                e.preventDefault();
                e.stopPropagation();
                this.handleActionClick(e, actionBtn);
                return;
            }

            // THEN: Check if result item was clicked
            const resultItem = e.target.closest('.quickswap-result-item');
            if (resultItem) {
                e.preventDefault();

                // Get index from data attribute
                const index = parseInt(resultItem.dataset.index, 10);
                if (!isNaN(index)) {
                    this.selectedIndex = index;
                    this.openSelected();
                }
            }
        },

        /**
         * Handle action button click
         */
        handleActionClick(e, button) {
            e.preventDefault();
            e.stopPropagation();

            const action = button.dataset.action;
            const url = button.dataset.url;
            const itemData = this.getSelectedItemData();

            // DEBUG: Log apa yang sedang diproses
            console.log('[QuickSwap] Action clicked:', {
                action,
                url,
                'data-url raw': button.getAttribute('data-url'),
                'dataset.url': button.dataset.url,
                itemData
            });

            // If it's a URL action, navigate directly
            if (url) {
                console.log('[QuickSwap] Navigating to:', url);
                window.location.href = url;
                return;
            }

            // If it's a server-side action, execute via AJAX
            if (action) {
                console.log('[QuickSwap] Executing action:', action);
                this.executeAction(action, itemData);
            }
        },

        /**
         * Handle hover for selection
         */
        handleHover(e) {
            const resultItem = e.target.closest('.quickswap-result-item');

            if (resultItem) {
                const index = parseInt(resultItem.dataset.index, 10);
                if (!isNaN(index)) {
                    this.selectItem(index);
                }
            }
        },

        /**
         * Handle focus trap within modal
         */
        handleFocusTrap(e) {
            if (e.target !== this.elements.overlay) return;

            // Trap focus back to input
            if (this.elements.input) {
                e.preventDefault();
                this.elements.input.focus();
            }
        },

        /**
         * Open the search modal
         */
        open() {
            if (!this.elements.modal) return;

            this.isOpen = true;
            this.elements.modal.classList.add('is-open');
            this.elements.modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            // Focus input
            if (this.elements.input) {
                this.elements.input.focus();
                this.elements.input.value = '';
            }

            // Clear results
            if (this.elements.resultsContainer) {
                this.elements.resultsContainer.innerHTML = '';
            }

            this.selectedIndex = -1;
            this.results = [];
        },

        /**
         * Close the search modal
         */
        close() {
            if (!this.elements.modal) return;

            this.isOpen = false;
            this.elements.modal.classList.remove('is-open');
            this.elements.modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';

            this.selectedIndex = -1;
            this.results = [];
        },

        /**
         * Toggle modal open/close
         */
        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },

        /**
         * Perform search
         */
        performSearch(query) {
            if (!query) {
                this.renderEmpty();
                return;
            }

            this.renderLoading();

            // Make AJAX request
            fetch(quickswapData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'quickswap_search',
                    nonce: quickswapData.nonce,
                    query: query,
                    limit: quickswapData.settings.maxResults,
                    enable_fuzzy: quickswapData.settings.enableFuzzy,
                    fuzzy_threshold: quickswapData.settings.fuzzyThreshold,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Validate data structure
                    if (data.data && data.data.results) {
                        this.results = this.flattenResults(data.data.results);
                        this.renderResults(data.data.results);
                    } else {
                        this.renderResults([]);
                    }
                } else {
                    // Safely extract error message
                    const errorMessage = (data.data && data.data.message) || data.message || quickswapData.i18n.error;
                    this.renderError(errorMessage);
                }
            })
            .catch(error => {
                console.error('QuickSwap search error:', error);
                this.renderError(quickswapData.i18n.error);
            });
        },

        /**
         * Flatten results array
         */
        flattenResults(groupedResults) {
            const flat = [];
            let globalIndex = 0;

            for (const provider in groupedResults) {
                const group = groupedResults[provider];
                for (const item of group.items) {
                    item._provider = provider;
                    item._globalIndex = globalIndex++;
                    flat.push(item);
                }
            }

            return flat;
        },

        /**
         * Render search results
         */
        renderResults(groupedResults) {
            if (!this.elements.resultsContainer) return;

            // Check if empty
            let hasResults = false;
            for (const provider in groupedResults) {
                if (groupedResults[provider].items.length > 0) {
                    hasResults = true;
                    break;
                }
            }

            if (!hasResults) {
                this.renderEmpty();
                return;
            }

            let html = '';
            let globalIndex = 0;

            for (const provider in groupedResults) {
                const group = groupedResults[provider];
                if (group.items.length === 0) continue;

                // Render group
                html += '<div class="quickswap-result-group">';
                html += `<div class="quickswap-group-label">${this.escapeHtml(group.label)}</div>`;

                // Render items
                for (const item of group.items) {
                    html += this.renderResultItem(item, globalIndex++);
                }

                html += '</div>';
            }

            this.elements.resultsContainer.innerHTML = html;
        },

        /**
         * Render a single result item
         */
        renderResultItem(item, index) {
            const title = this.highlightMatch(item.title);
            const subtitle = this.escapeHtml(item.subtitle || '');
            const icon = item.icon || '📄';

            let actionsHtml = '';
            if (item.actions && item.actions.length > 0) {
                actionsHtml = '<div class="quickswap-result-actions">';
                for (const action of item.actions) {
                    const actionLabel = this.escapeHtml(action.label);
                    const actionIcon = this.escapeHtml(action.icon || '');
                    const actionUrl = action.url || '';
                    // Jangan escape URLs untuk data attributes - browser handle encoding secara otomatis
                    const actionId = this.escapeHtml(action.id || '');

                    actionsHtml += `<button
                        class="quickswap-action-btn"
                        data-action="${actionId}"
                        data-url="${actionUrl}"
                        title="${actionLabel}"
                    >${actionIcon} ${actionLabel}</button>`;
                }
                actionsHtml += '</div>';
            }

            return `
                <div class="quickswap-result-item" data-index="${index}" tabindex="0">
                    <div class="quickswap-result-icon">${icon}</div>
                    <div class="quickswap-result-content">
                        <div class="quickswap-result-title">${title}</div>
                        ${subtitle ? `<div class="quickswap-result-subtitle">${subtitle}</div>` : ''}
                    </div>
                    ${actionsHtml}
                </div>
            `;
        },

        /**
         * Highlight matching text
         */
        highlightMatch(text) {
            const query = this.elements.input?.value?.trim() || '';
            if (!query) return this.escapeHtml(text);

            const regex = new RegExp(`(${this.regexEscape(query)})`, 'gi');
            return this.escapeHtml(text).replace(regex, '<em>$1</em>');
        },

        /**
         * Escape regex special characters
         */
        regexEscape(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        },

        /**
         * Render loading state
         */
        renderLoading() {
            if (!this.elements.resultsContainer) return;

            this.elements.resultsContainer.innerHTML = `
                <div class="quickswap-loading">
                    <div class="quickswap-spinner"></div>
                    <div>${quickswapData.i18n.loading}</div>
                </div>
            `;
        },

        /**
         * Render empty state
         */
        renderEmpty() {
            if (!this.elements.resultsContainer) return;

            this.elements.resultsContainer.innerHTML = `
                <div class="quickswap-empty">
                    <div class="quickswap-empty-icon">🔍</div>
                    <div class="quickswap-empty-title">${quickswapData.i18n.noResults}</div>
                </div>
            `;
        },

        /**
         * Render error state
         */
        renderError(message) {
            if (!this.elements.resultsContainer) return;

            this.elements.resultsContainer.innerHTML = `
                <div class="quickswap-error">
                    ${this.escapeHtml(message)}
                </div>
            `;
        },

        /**
         * Select next item
         */
        selectNext() {
            if (this.results.length === 0) return;

            this.selectedIndex = (this.selectedIndex + 1) % this.results.length;
            this.updateSelection();
        },

        /**
         * Select previous item
         */
        selectPrevious() {
            if (this.results.length === 0) return;

            this.selectedIndex = (this.selectedIndex - 1 + this.results.length) % this.results.length;
            this.updateSelection();
        },

        /**
         * Select item by index
         */
        selectItem(index) {
            if (index < 0 || index >= this.results.length) return;

            this.selectedIndex = index;
            this.updateSelection();
        },

        /**
         * Update visual selection
         */
        updateSelection() {
            const items = this.elements.resultsContainer?.querySelectorAll('.quickswap-result-item') || [];

            items.forEach((item, index) => {
                if (index === this.selectedIndex) {
                    item.classList.add('is-selected');
                } else {
                    item.classList.remove('is-selected');
                }
            });

            // Scroll selected into view
            if (this.selectedIndex >= 0 && items[this.selectedIndex]) {
                items[this.selectedIndex].scrollIntoView({
                    block: 'nearest',
                    behavior: 'smooth'
                });
            }
        },

        /**
         * Navigate action buttons
         */
        navigateActions(direction) {
            if (this.selectedIndex < 0) return;

            const selectedItem = this.elements.resultsContainer?.querySelector(
                `.quickswap-result-item[data-index="${this.selectedIndex}"]`
            );

            if (!selectedItem) return;

            const actions = selectedItem.querySelectorAll('.quickswap-action-btn');
            if (actions.length === 0) return;

            // Find currently focused action
            const currentFocus = document.activeElement;
            let currentIndex = -1;

            actions.forEach((btn, index) => {
                if (btn === currentFocus) {
                    currentIndex = index;
                }
            });

            // Move focus
            const nextIndex = (currentIndex + direction + actions.length) % actions.length;
            actions[nextIndex].focus();
        },

        /**
         * Open selected item
         */
        openSelected() {
            if (this.selectedIndex < 0 || this.selectedIndex >= this.results.length) return;

            const item = this.results[this.selectedIndex];
            const url = item.url || item.link;

            if (url) {
                window.location.href = url;
            }
        },

        /**
         * Edit selected item
         */
        editSelected() {
            if (this.selectedIndex < 0 || this.selectedIndex >= this.results.length) return;

            const item = this.results[this.selectedIndex];

            // Find edit action
            if (item.actions) {
                const editAction = item.actions.find(a => a.id === 'edit');
                if (editAction && editAction.url) {
                    window.location.href = editAction.url;
                    return;
                }
            }

            // Fallback to main URL
            this.openSelected();
        },

        /**
         * Get selected item data
         */
        getSelectedItemData() {
            if (this.selectedIndex < 0 || this.selectedIndex >= this.results.length) {
                return null;
            }

            return this.results[this.selectedIndex];
        },

        /**
         * Execute action via AJAX
         */
        executeAction(actionId, itemData) {
            if (!itemData) return;

            fetch(quickswapData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'quickswap_action',
                    nonce: quickswapData.nonce,
                    action_id: actionId,
                    provider: itemData._provider || '',
                    item_id: itemData.id || '',
                    item_data: JSON.stringify(itemData),
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message or redirect
                    if (data.data.redirect) {
                        window.location.href = data.data.redirect;
                    } else if (data.data.message) {
                        // Refresh search and show notification
                        this.performSearch(this.elements.input?.value || '');
                        // Could add toast notification here
                    }
                } else {
                    // Show error
                    alert(data.data.message || 'Action failed');
                }
            })
            .catch(error => {
                console.error('QuickSwap action error:', error);
                alert('Action failed');
            });
        },

        /**
         * Escape HTML
         */
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        /**
         * Check if running on Mac
         */
        checkMacintosh() {
            this.isMac = /Mac|iPod|iPhone|iPad/.test(navigator.platform);
        },
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => QuickSwap.init());
    } else {
        QuickSwap.init();
    }

    // Export to global scope
    window.QuickSwap = QuickSwap;

})();
