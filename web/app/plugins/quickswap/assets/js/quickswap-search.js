/**
 * QuickSwap Search Logic
 *
 * Client-side search functionality with fuzzy matching
 *
 * @package QuickSwap
 * @since 1.0.0
 */

(function() {
    'use strict';

    const QuickSwapSearch = {
        /**
         * Initialize search functionality
         */
        init() {
            // Additional search initialization if needed
        },

        /**
         * Perform client-side fuzzy search
         * Used for cached results or small datasets
         */
        fuzzySearch(items, query, options = {}) {
            const defaults = {
                threshold: 70,
                keys: ['title', 'subtitle'],
                limit: 10,
            };

            const opts = { ...defaults, ...options };
            const results = [];

            for (const item of items) {
                let bestScore = 0;
                let bestMatch = '';

                // Search in all keys
                for (const key of opts.keys) {
                    const value = this.getStringValue(item, key);
                    if (!value) continue;

                    const score = this.calculateScore(value, query);
                    if (score > bestScore) {
                        bestScore = score;
                        bestMatch = value;
                    }
                }

                // Add if score meets threshold
                if (bestScore >= opts.threshold) {
                    results.push({
                        ...item,
                        _searchScore: bestScore,
                        _searchMatch: bestMatch,
                    });
                }
            }

            // Sort by score descending
            results.sort((a, b) => b._searchScore - a._searchScore);

            // Limit results
            return results.slice(0, opts.limit);
        },

        /**
         * Get string value from object by key path
         */
        getStringValue(obj, key) {
            const value = obj[key];
            return value ? String(value).toLowerCase() : '';
        },

        /**
         * Calculate search score
         */
        calculateScore(haystack, needle) {
            const h = haystack.toLowerCase();
            const n = needle.toLowerCase();

            // Exact match
            if (h === n) return 100;

            // Starts with
            if (h.startsWith(n)) return 90;

            // Contains
            if (h.includes(n)) return 70;

            // Fuzzy match using Levenshtein
            const similarity = this.levenshteinSimilarity(h, n);
            return similarity >= 50 ? similarity : 0;
        },

        /**
         * Calculate Levenshtein similarity (0-100)
         */
        levenshteinSimilarity(str1, str2) {
            const distance = this.levenshteinDistance(str1, str2);
            const maxLen = Math.max(str1.length, str2.length);

            if (maxLen === 0) return 100;

            return Math.round((1 - distance / maxLen) * 100);
        },

        /**
         * Calculate Levenshtein distance
         */
        levenshteinDistance(str1, str2) {
            const len1 = str1.length;
            const len2 = str2.length;

            if (len1 === 0) return len2;
            if (len2 === 0) return len1;

            const matrix = [];

            // Initialize first row and column
            for (let i = 0; i <= len1; i++) {
                matrix[i] = [i];
            }
            for (let j = 0; j <= len2; j++) {
                matrix[0][j] = j;
            }

            // Fill matrix
            for (let i = 1; i <= len1; i++) {
                for (let j = 1; j <= len2; j++) {
                    const cost = str1[i - 1] === str2[j - 1] ? 0 : 1;
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j] + 1,           // deletion
                        matrix[i][j - 1] + 1,           // insertion
                        matrix[i - 1][j - 1] + cost     // substitution
                    );
                }
            }

            return matrix[len1][len2];
        },

        /**
         * Parse search query for operators
         */
        parseQuery(query) {
            const parsed = {
                query: query,
                operators: {},
            };

            // Match operators like type:post, user:john, etc.
            const operatorRegex = /(\w+):([^\s]+)/g;
            let match;

            while ((match = operatorRegex.exec(query)) !== null) {
                parsed.operators[match[1]] = match[2];
                // Remove operator from main query
                parsed.query = parsed.query.replace(match[0], '');
            }

            // Clean up query
            parsed.query = parsed.query.trim().toLowerCase();

            return parsed;
        },

        /**
         * Tokenize text for better matching
         */
        tokenize(text) {
            return text.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .split(/\s+/)
                .filter(token => token.length > 0);
        },

        /**
         * Check if tokens match
         */
        tokensMatch(textTokens, queryTokens) {
            for (const queryToken of queryTokens) {
                for (const textToken of textTokens) {
                    if (textToken.includes(queryToken) || queryToken.includes(textToken)) {
                        return true;
                    }
                }
            }
            return false;
        },

        /**
         * Highlight matching text
         */
        highlightMatches(text, query) {
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
         * Escape HTML
         */
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },
    };

    // Export
    window.QuickSwapSearch = QuickSwapSearch;

})();
