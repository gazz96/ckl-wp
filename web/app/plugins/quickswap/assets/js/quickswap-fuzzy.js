/**
 * QuickSwap Fuzzy Search
 *
 * Client-side fuzzy matching algorithm
 *
 * @package QuickSwap
 * @since 1.0.0
 */

(function() {
    'use strict';

    const QuickSwapFuzzy = {
        /**
         * Calculate Levenshtein distance between two strings
         * @param {string} str1 First string
         * @param {string} str2 Second string
         * @returns {number} Distance (lower is more similar)
         */
        levenshteinDistance(str1, str2) {
            const len1 = str1.length;
            const len2 = str2.length;

            // Edge cases
            if (len1 === 0) return len2;
            if (len2 === 0) return len1;

            // Use the shorter string as the inner loop for optimization
            if (len1 < len2) {
                return this.levenshteinDistance(str2, str1);
            }

            // Initialize array
            const prevRow = new Array(len2 + 1).fill(0);
            const currRow = new Array(len2 + 1).fill(0);

            // Initialize first row
            for (let j = 0; j <= len2; j++) {
                prevRow[j] = j;
            }

            // Fill matrix
            for (let i = 1; i <= len1; i++) {
                currRow[0] = i;

                for (let j = 1; j <= len2; j++) {
                    const cost = str1[i - 1] === str2[j - 1] ? 0 : 1;
                    currRow[j] = Math.min(
                        prevRow[j] + 1,           // deletion
                        currRow[j - 1] + 1,       // insertion
                        prevRow[j - 1] + cost     // substitution
                    );
                }

                // Swap rows
                [prevRow, currRow] = [currRow, prevRow];
            }

            return prevRow[len2];
        },

        /**
         * Calculate similarity percentage between two strings
         * @param {string} str1 First string
         * @param {string} str2 Second string
         * @returns {number} Similarity (0-100)
         */
        similarity(str1, str2) {
            const distance = this.levenshteinDistance(str1, str2);
            const maxLen = Math.max(str1.length, str2.length);

            if (maxLen === 0) return 100;

            return Math.round((1 - (distance / maxLen)) * 100);
        },

        /**
         * Check if strings match with fuzzy threshold
         * @param {string} haystack String to search in
         * @param {string} needle String to search for
         * @param {number} threshold Minimum similarity (0-100)
         * @returns {boolean} True if similar enough
         */
        match(haystack, needle, threshold = 70) {
            const haystackLower = haystack.toLowerCase();
            const needleLower = needle.toLowerCase();

            // Exact match
            if (haystackLower.includes(needleLower)) {
                return true;
            }

            // Fuzzy match
            const similarity = this.similarity(needleLower, haystackLower);
            return similarity >= threshold;
        },

        /**
         * Find best match in array of strings
         * @param {string} needle String to search for
         * @param {string[]} haystacks Array of strings to search in
         * @param {number} threshold Minimum similarity (0-100)
         * @returns {object|null} Best match data or null
         */
        findBestMatch(needle, haystacks, threshold = 70) {
            let bestMatch = null;
            let bestSimilarity = 0;

            haystacks.forEach((haystack, index) => {
                const similarity = this.similarity(needle, haystack);

                if (similarity >= threshold && similarity > bestSimilarity) {
                    bestMatch = {
                        index: index,
                        value: haystack,
                        similarity: similarity,
                    };
                    bestSimilarity = similarity;
                }
            });

            return bestMatch;
        },

        /**
         * Get fuzzy search score for ranking
         * @param {string} haystack String to search in
         * @param {string} needle String to search for
         * @returns {number} Score (0-30)
         */
        getScore(haystack, needle) {
            const haystackLower = haystack.toLowerCase();
            const needleLower = needle.toLowerCase();

            // Exact match gets highest score
            if (haystackLower === needleLower) {
                return 30;
            }

            // Starts with query gets high score
            if (haystackLower.startsWith(needleLower)) {
                return 25;
            }

            // Contains query gets medium score
            if (haystackLower.includes(needleLower)) {
                return 20;
            }

            // Fuzzy similarity score
            const similarity = this.similarity(needleLower, haystackLower);
            return Math.max(0, similarity * 0.3);
        },

        /**
         * Split text into tokens for better fuzzy matching
         * @param {string} text Text to tokenize
         * @returns {string[]} Array of tokens
         */
        tokenize(text) {
            // Split by spaces, commas, hyphens, underscores
            const matches = text.match(/[\w-]+/g);
            return matches || [];
        },

        /**
         * Match tokenized query against tokenized text
         * @param {string} text Text to search in
         * @param {string} query Query to search for
         * @param {number} threshold Minimum similarity (0-100)
         * @returns {boolean} True if any token matches
         */
        matchTokens(text, query, threshold = 70) {
            const textTokens = this.tokenize(text);
            const queryTokens = this.tokenize(query);

            for (const queryToken of queryTokens) {
                for (const textToken of textTokens) {
                    if (this.match(textToken, queryToken, threshold)) {
                        return true;
                    }
                }
            }

            return false;
        },

        /**
         * Calculate Damerau-Levenshtein distance (includes transpositions)
         * @param {string} str1 First string
         * @param {string} str2 Second string
         * @returns {number} Distance
         */
        damerauLevenshteinDistance(str1, str2) {
            const len1 = str1.length;
            const len2 = str2.length;

            if (len1 === 0) return len2;
            if (len2 === 0) return len1;

            const matrix = [];

            // Initialize matrix
            for (let i = 0; i <= len1; i++) {
                matrix[i] = [];
                matrix[i][0] = i;
            }

            for (let j = 0; j <= len2; j++) {
                matrix[0][j] = j;
            }

            // Fill matrix
            for (let i = 1; i <= len1; i++) {
                for (let j = 1; j <= len2; j++) {
                    const cost = str1[i - 1] === str2[j - 1] ? 0 : 1;

                    matrix[i][j] = Math.min(
                        matrix[i - 1][j] + 1,              // deletion
                        matrix[i][j - 1] + 1,              // insertion
                        matrix[i - 1][j - 1] + cost        // substitution
                    );

                    // Transposition
                    if (i > 1 && j > 1 &&
                        str1[i - 1] === str2[j - 2] &&
                        str1[i - 2] === str2[j - 1]) {
                        matrix[i][j] = Math.min(
                            matrix[i][j],
                            matrix[i - 2][j - 2] + cost   // transposition
                        );
                    }
                }
            }

            return matrix[len1][len2];
        },

        /**
         * Suggest corrections for misspelled words
         * @param {string} word Word to correct
         * @param {string[]} dictionary Dictionary of valid words
         * @param {number} limit Number of suggestions
         * @returns {object[]} Array of suggestions
         */
        suggestCorrections(word, dictionary, limit = 5) {
            const suggestions = [];

            dictionary.forEach(dictWord => {
                const similarity = this.similarity(word, dictWord);

                if (similarity >= 50) {
                    suggestions.push({
                        word: dictWord,
                        similarity: similarity,
                    });
                }
            });

            // Sort by similarity descending
            suggestions.sort((a, b) => b.similarity - a.similarity);

            // Return top suggestions
            return suggestions.slice(0, limit);
        },

        /**
         * Batch search with fuzzy matching
         * @param {string[]} items Items to search through
         * @param {string} query Query string
         * @param {object} options Options
         * @returns {Array<{item: string, score: number}>} Ranked results
         */
        batchSearch(items, query, options = {}) {
            const defaults = {
                threshold: 50,
                maxResults: 10,
            };

            const opts = { ...defaults, ...options };
            const results = [];

            items.forEach(item => {
                const score = this.getScore(item, query);

                if (score >= opts.threshold / 30 * 100) {
                    results.push({
                        item: item,
                        score: score,
                    });
                }
            });

            // Sort by score descending
            results.sort((a, b) => b.score - a.score);

            // Return limited results
            return results.slice(0, opts.maxResults);
        },

        /**
         * Check if two strings sound similar (simple phonetic matching)
         * @param {string} str1 First string
         * @param {string} str2 Second string
         * @returns {number} Similarity score (0-100)
         */
        phoneticSimilarity(str1, str2) {
            // Simple Soundex-like comparison
            const soundex1 = this.soundex(str1);
            const soundex2 = this.soundex(str2);

            if (soundex1 === soundex2) {
                return 90;
            }

            // Compare first characters (usually same for similar sounds)
            if (soundex1[0] === soundex2[0]) {
                const score = this.similarity(soundex1, soundex2);
                return Math.min(score * 0.5, 50);
            }

            return 0;
        },

        /**
         * Simple Soundex algorithm implementation
         * @param {string} str Input string
         * @returns {string} Soundex code
         */
        soundex(str) {
            const soundexCodes = {
                'b': '1', 'f': '1', 'p': '1', 'v': '1',
                'c': '2', 'g': '2', 'j': '2', 'k': '2', 'q': '2', 's': '2', 'x': '2', 'z': '2',
                'd': '3', 't': '3',
                'l': '4',
                'm': '5', 'n': '5',
                'r': '6',
            };

            str = str.toLowerCase().replace(/[^a-z]/g, '');

            if (str.length === 0) return '';

            let code = str[0].toUpperCase();
            let lastCode = soundexCodes[str[0]] || '';

            for (let i = 1; i < str.length; i++) {
                const charCode = soundexCodes[str[i]] || '';

                if (charCode && charCode !== lastCode) {
                    code += charCode;
                    lastCode = charCode;
                }

                if (code.length === 4) break;
            }

            // Pad with zeros if needed
            while (code.length < 4) {
                code += '0';
            }

            return code;
        },
    };

    // Export
    window.QuickSwapFuzzy = QuickSwapFuzzy;

})();
