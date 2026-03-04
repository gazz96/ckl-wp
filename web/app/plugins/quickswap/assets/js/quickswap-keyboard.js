/**
 * QuickSwap Keyboard Handler
 *
 * Handles keyboard shortcuts and navigation
 *
 * @package QuickSwap
 * @since 1.0.0
 */

(function() {
    'use strict';

    const QuickSwapKeyboard = {
        /**
         * Current keyboard state
         */
        state: {
            isMac: false,
            modifierPressed: false,
        },

        /**
         * Key codes
         */
        keys: {
            ESCAPE: 'Escape',
            ENTER: 'Enter',
            TAB: 'Tab',
            ARROW_UP: 'ArrowUp',
            ARROW_DOWN: 'ArrowDown',
            ARROW_LEFT: 'ArrowLeft',
            ARROW_RIGHT: 'ArrowRight',
            KEY_K: 'k',
            SPACE: ' ',
        },

        /**
         * Initialize keyboard handler
         */
        init() {
            this.detectPlatform();
            this.bindEvents();
        },

        /**
         * Detect current platform
         */
        detectPlatform() {
            this.state.isMac = /Mac|iPod|iPhone|iPad/.test(navigator.platform);
        },

        /**
         * Bind keyboard events
         */
        bindEvents() {
            document.addEventListener('keydown', (e) => this.handleKeydown(e));
            document.addEventListener('keyup', (e) => this.handleKeyup(e));
        },

        /**
         * Handle keydown events
         */
        handleKeydown(e) {
            // Track modifier keys
            if (e.metaKey || e.ctrlKey) {
                this.state.modifierPressed = true;
            }

            // Check for QuickSwap shortcut (Cmd/Ctrl + K)
            if (this.isQuickSwapShortcut(e)) {
                e.preventDefault();
                this.toggleQuickSwap();
                return;
            }
        },

        /**
         * Handle keyup events
         */
        handleKeyup(e) {
            // Track modifier keys
            if (!e.metaKey && !e.ctrlKey) {
                this.state.modifierPressed = false;
            }
        },

        /**
         * Check if key combination matches QuickSwap shortcut
         */
        isQuickSwapShortcut(e) {
            // Must have Cmd/Ctrl modifier
            if (!e.metaKey && !e.ctrlKey) return false;

            // Check for K key (case insensitive)
            if (e.key.toLowerCase() !== this.keys.KEY_K.toLowerCase()) return false;

            // Check for other modifiers (should not have Shift or Alt)
            if (e.shiftKey || e.altKey) return false;

            // Not in an input/textarea (unless specifically focused)
            const tag = e.target.tagName.toLowerCase();
            if (tag === 'input' || tag === 'textarea' || e.target.isContentEditable) {
                // Check if it's the QuickSwap input itself
                if (e.target.id === 'quickswap-input') {
                    return false; // Let QuickSwap handle it
                }
            }

            return true;
        },

        /**
         * Toggle QuickSwap modal
         */
        toggleQuickSwap() {
            if (window.QuickSwap) {
                window.QuickSwap.toggle();
            }
        },

        /**
         * Format keyboard shortcut for display
         */
        formatShortcut(shortcut) {
            const parts = shortcut.split('+');

            return parts.map(key => {
                switch (key.toLowerCase()) {
                    case 'cmd':
                    case 'meta':
                        return this.state.isMac ? '⌘' : 'Ctrl';
                    case 'ctrl':
                        return 'Ctrl';
                    case 'shift':
                        return this.state.isMac ? '⇧' : 'Shift';
                    case 'alt':
                    case 'option':
                        return this.state.isMac ? '⌥' : 'Alt';
                    case 'space':
                        return '␣';
                    default:
                        return key.charAt(0).toUpperCase() + key.slice(1);
                }
            }).join(this.state.isMac ? '' : '+');
        },

        /**
         * Get modifier key name
         */
        getModifierKeyName() {
            return this.state.isMac ? '⌘' : 'Ctrl';
        },

        /**
         * Check if key is a navigation key
         */
        isNavigationKey(key) {
            const navKeys = [
                this.keys.ARROW_UP,
                this.keys.ARROW_DOWN,
                this.keys.ARROW_LEFT,
                this.keys.ARROW_RIGHT,
                this.keys.TAB,
            ];

            return navKeys.includes(key);
        },

        /**
         * Check if key is an action key
         */
        isActionKey(key) {
            const actionKeys = [
                this.keys.ENTER,
                this.keys.ESCAPE,
            ];

            return actionKeys.includes(key);
        },

        /**
         * Get keyboard hints for display
         */
        getKeyboardHints() {
            const modifier = this.getModifierKeyName();

            return [
                {
                    keys: ['↑', '↓'],
                    action: 'Navigate',
                },
                {
                    keys: ['Enter'],
                    action: 'Open',
                },
                {
                    keys: [modifier, 'Enter'],
                    action: 'Edit',
                },
                {
                    keys: ['Esc'],
                    action: 'Close',
                },
            ];
        },

        /**
         * Format keyboard hint for display
         */
        formatKeyboardHint(keys) {
            return keys.map(key => {
                // If it's a multi-key hint like "⌘ Enter"
                if (key.includes(' ')) {
                    const parts = key.split(' ');
                    return parts.map(k => `<kbd>${k}</kbd>`).join(' + ');
                }
                return `<kbd>${key}</kbd>`;
            }).join(' ');
        },

        /**
         * Simulate key press
         */
        simulateKeyPress(keyCode, options = {}) {
            const event = new KeyboardEvent('keydown', {
                key: keyCode,
                code: keyCode,
                keyCode: this.getKeyCode(keyCode),
                which: this.getKeyCode(keyCode),
                bubbles: true,
                cancelable: true,
                ...options,
            });

            document.dispatchEvent(event);
        },

        /**
         * Get key code from key name
         */
        getKeyCode(keyName) {
            const keyCodes = {
                'Enter': 13,
                'Escape': 27,
                'Tab': 9,
                'ArrowUp': 38,
                'ArrowDown': 40,
                'ArrowLeft': 37,
                'ArrowRight': 39,
                'k': 75,
                'K': 75,
            };

            return keyCodes[keyName] || 0;
        },

        /**
         * Check if event has modifier
         */
        hasModifier(e) {
            return e.metaKey || e.ctrlKey || e.altKey || e.shiftKey;
        },

        /**
         * Prevent default if needed
         */
        preventIfNeeded(e) {
            // Prevent default for special keys that might trigger browser actions
            const preventKeys = [
                this.keys.SPACE,
                this.keys.ARROW_UP,
                this.keys.ARROW_DOWN,
            ];

            if (preventKeys.includes(e.key)) {
                e.preventDefault();
                return true;
            }

            return false;
        },
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => QuickSwapKeyboard.init());
    } else {
        QuickSwapKeyboard.init();
    }

    // Export
    window.QuickSwapKeyboard = QuickSwapKeyboard;

})();
