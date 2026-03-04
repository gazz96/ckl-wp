<?php
/**
 * QuickSwap Fuzzy Search Class
 *
 * Implements Levenshtein distance algorithm for fuzzy matching
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Fuzzy {

    /**
     * Initialize fuzzy search
     */
    public static function init() {
        // Initialization if needed
    }

    /**
     * Calculate Levenshtein distance between two strings
     *
     * @param string $str1 First string
     * @param string $str2 Second string
     * @return int Distance (lower is more similar)
     */
    public static function levenshtein_distance($str1, $str2) {
        $len1 = strlen($str1);
        $len2 = strlen($str2);

        // Edge cases
        if ($len1 === 0) {
            return $len2;
        }
        if ($len2 === 0) {
            return $len1;
        }

        // Initialize matrix
        $matrix = array();
        for ($i = 0; $i <= $len1; $i++) {
            $matrix[$i][0] = $i;
        }
        for ($j = 0; $j <= $len2; $j++) {
            $matrix[0][$j] = $j;
        }

        // Fill matrix
        for ($i = 1; $i <= $len1; $i++) {
            for ($j = 1; $j <= $len2; $j++) {
                $cost = ($str1[$i - 1] === $str2[$j - 1]) ? 0 : 1;

                $matrix[$i][$j] = min(
                    $matrix[$i - 1][$j] + 1,      // deletion
                    $matrix[$i][$j - 1] + 1,      // insertion
                    $matrix[$i - 1][$j - 1] + $cost // substitution
                );
            }
        }

        return $matrix[$len1][$len2];
    }

    /**
     * Calculate similarity percentage between two strings
     *
     * @param string $str1 First string
     * @param string $str2 Second string
     * @return float Similarity (0-100)
     */
    public static function similarity($str1, $str2) {
        $distance = self::levenshtein_distance($str1, $str2);
        $max_len = max(strlen($str1), strlen($str2));

        if ($max_len === 0) {
            return 100.0;
        }

        return (1.0 - ($distance / $max_len)) * 100;
    }

    /**
     * Check if strings match with fuzzy threshold
     *
     * @param string $haystack String to search in
     * @param string $needle String to search for
     * @param int $threshold Minimum similarity (0-100)
     * @return bool True if similar enough
     */
    public static function match($haystack, $needle, $threshold = 70) {
        $haystack_lower = strtolower($haystack);
        $needle_lower = strtolower($needle);

        // Exact match
        if (strpos($haystack_lower, $needle_lower) !== false) {
            return true;
        }

        // Fuzzy match
        $similarity = self::similarity($needle_lower, $haystack_lower);

        return $similarity >= $threshold;
    }

    /**
     * Find best match in array of strings
     *
     * @param string $needle String to search for
     * @param array $haystacks Array of strings to search in
     * @param int $threshold Minimum similarity (0-100)
     * @return array|false Best match data or false if no match
     */
    public static function find_best_match($needle, $haystacks, $threshold = 70) {
        $best_match = false;
        $best_similarity = 0;

        foreach ($haystacks as $index => $haystack) {
            $similarity = self::similarity($needle, $haystack);

            if ($similarity >= $threshold && $similarity > $best_similarity) {
                $best_match = array(
                    'index' => $index,
                    'value' => $haystack,
                    'similarity' => $similarity,
                );
                $best_similarity = $similarity;
            }
        }

        return $best_match;
    }

    /**
     * Get fuzzy search score for ranking
     *
     * @param string $haystack String to search in
     * @param string $needle String to search for
     * @return float Score (0-30)
     */
    public static function get_score($haystack, $needle) {
        $haystack_lower = strtolower($haystack);
        $needle_lower = strtolower($needle);

        // Exact match gets highest score
        if ($haystack_lower === $needle_lower) {
            return 30.0;
        }

        // Starts with query gets high score
        if (strpos($haystack_lower, $needle_lower) === 0) {
            return 25.0;
        }

        // Contains query gets medium score
        if (strpos($haystack_lower, $needle_lower) !== false) {
            return 20.0;
        }

        // Fuzzy similarity score
        $similarity = self::similarity($needle_lower, $haystack_lower);

        return max(0, $similarity * 0.3);
    }

    /**
     * Split text into tokens for better fuzzy matching
     *
     * @param string $text Text to tokenize
     * @return array Array of tokens
     */
    public static function tokenize($text) {
        // Split by spaces, commas, hyphens, underscores
        preg_match_all('/[\w-]+/', $text, $matches);

        return $matches[0] ?? array();
    }

    /**
     * Match tokenized query against tokenized text
     *
     * @param string $text Text to search in
     * @param string $query Query to search for
     * @param int $threshold Minimum similarity (0-100)
     * @return bool True if any token matches
     */
    public static function match_tokens($text, $query, $threshold = 70) {
        $text_tokens = self::tokenize($text);
        $query_tokens = self::tokenize($query);

        foreach ($query_tokens as $query_token) {
            foreach ($text_tokens as $text_token) {
                if (self::match($text_token, $query_token, $threshold)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get Damerau-Levenshtein distance (includes transpositions)
     *
     * @param string $str1 First string
     * @param string $str2 Second string
     * @return int Distance
     */
    public static function damerau_levenshtein_distance($str1, $str2) {
        $len1 = strlen($str1);
        $len2 = strlen($str2);

        if ($len1 === 0) {
            return $len2;
        }
        if ($len2 === 0) {
            return $len1;
        }

        $matrix = array();

        for ($i = 0; $i <= $len1; $i++) {
            $matrix[$i] = array();
            $matrix[$i][0] = $i;
        }

        for ($j = 0; $j <= $len2; $j++) {
            $matrix[0][$j] = $j;
        }

        for ($i = 1; $i <= $len1; $i++) {
            for ($j = 1; $j <= $len2; $j++) {
                $cost = ($str1[$i - 1] === $str2[$j - 1]) ? 0 : 1;

                $matrix[$i][$j] = min(
                    $matrix[$i - 1][$j] + 1,           // deletion
                    $matrix[$i][$j - 1] + 1,           // insertion
                    $matrix[$i - 1][$j - 1] + $cost    // substitution
                );

                // Transposition
                if ($i > 1 && $j > 1 && $str1[$i - 1] === $str2[$j - 2] && $str1[$i - 2] === $str2[$j - 1]) {
                    $matrix[$i][$j] = min(
                        $matrix[$i][$j],
                        $matrix[$i - 2][$j - 2] + $cost // transposition
                    );
                }
            }
        }

        return $matrix[$len1][$len2];
    }

    /**
     * Suggest corrections for misspelled words
     *
     * @param string $word Word to correct
     * @param array $dictionary Dictionary of valid words
     * @param int $limit Number of suggestions
     * @return array Array of suggestions
     */
    public static function suggest_corrections($word, $dictionary, $limit = 5) {
        $suggestions = array();

        foreach ($dictionary as $dict_word) {
            $similarity = self::similarity($word, $dict_word);

            if ($similarity >= 50) { // Only include reasonably similar words
                $suggestions[] = array(
                    'word' => $dict_word,
                    'similarity' => $similarity,
                );
            }
        }

        // Sort by similarity descending
        usort($suggestions, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        // Return top suggestions
        return array_slice($suggestions, 0, $limit);
    }
}
