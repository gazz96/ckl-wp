<?php
/**
 * QuickSwap Admin UI Components
 *
 * Helper methods for rendering modern UI components in admin
 *
 * @package QuickSwap
 * @since 1.3.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Admin_UI_Components {

    /**
     * Render a card component
     *
     * @param string $content The card content
     * @param string $classes Additional CSS classes
     * @return string HTML output
     */
    public static function card($content, $classes = '') {
        $default_classes = 'qs-card qs-p-6 qs-mb-6';
        $final_classes = $default_classes . ($classes ? ' ' . $classes : '');

        return sprintf(
            '<div class="%s">%s</div>',
            esc_attr($final_classes),
            $content
        );
    }

    /**
     * Render a button
     *
     * @param string $text Button text
     * @param array $args Button arguments
     * @return string HTML output
     */
    public static function button($text, $args = array()) {
        $defaults = array(
            'type' => 'primary',
            'href' => '',
            'id' => '',
            'class' => '',
            'icon' => '',
            'onclick' => '',
            'disabled' => false,
            'title' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $type_classes = array(
            'primary' => 'qs-btn-primary',
            'secondary' => 'qs-btn-secondary',
            'outline' => 'qs-btn-outline',
            'danger' => 'qs-btn-danger',
            'success' => 'qs-btn-success',
        );

        $btn_class = $type_classes[$args['type']] ?? $type_classes['primary'];
        $classes = 'qs-btn ' . $btn_class . ($args['class'] ? ' ' . $args['class'] : '');

        $icon_html = $args['icon'] ? '<span class="qs-btn-icon">' . $args['icon'] . '</span>' : '';
        $attr_id = $args['id'] ? ' id="' . esc_attr($args['id']) . '"' : '';
        $attr_title = $args['title'] ? ' title="' . esc_attr($args['title']) . '"' : '';
        $attr_disabled = $args['disabled'] ? ' disabled' : '';
        $attr_onclick = $args['onclick'] ? ' onclick="' . esc_js($args['onclick']) . '"' : '';

        if ($args['href']) {
            return sprintf(
                '<a href="%s"%s%s%s class="%s">%s%s</a>',
                esc_url($args['href']),
                $attr_id,
                $attr_title,
                $args['disabled'] ? ' aria-disabled="true"' : '',
                esc_attr($classes),
                $icon_html,
                esc_html($text)
            );
        }

        return sprintf(
            '<button type="submit"%s%s%s%s class="%s">%s%s</button>',
            $attr_id,
            $attr_title,
            $attr_disabled,
            $attr_onclick,
            esc_attr($classes),
            $icon_html,
            esc_html($text)
        );
    }

    /**
     * Render a badge
     *
     * @param string $text Badge text
     * @param string $type Badge type (success, warning, error, info, default)
     * @return string HTML output
     */
    public static function badge($text, $type = 'info') {
        $types = array(
            'success' => 'qs-badge-success',
            'warning' => 'qs-badge-warning',
            'error' => 'qs-badge-error',
            'info' => 'qs-badge-info',
            'default' => 'qs-badge-default',
        );

        $class = 'qs-badge ' . ($types[$type] ?? $types['info']);

        return sprintf(
            '<span class="%s">%s</span>',
            esc_attr($class),
            esc_html($text)
        );
    }

    /**
     * Render a progress bar
     *
     * @param int|float $value Current value
     * @param int|float $max Maximum value
     * @param bool $show_label Whether to show percentage label
     * @return string HTML output
     */
    public static function progress($value, $max = 100, $show_label = true) {
        $percentage = min(100, max(0, ($max > 0 ? ($value / $max) * 100 : 0)));

        $html = '<div class="qs-flex qs-items-center">';
        $html .= '<div class="qs-progress" style="flex: 1;">';
        $html .= sprintf(
            '<div class="qs-progress-bar" style="width: %d%%"></div>',
            intval($percentage)
        );
        $html .= '</div>';

        if ($show_label) {
            $html .= sprintf(
                '<span class="qs-text-xs qs-text-gray-500 qs-ml-2">%d%%</span>',
                intval($percentage)
            );
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Render an avatar
     *
     * @param int $user_id User ID
     * @param string $size Avatar size (sm, md, lg) or pixel value
     * @param array $attrs Additional attributes
     * @return string HTML output
     */
    public static function avatar($user_id, $size = 'md', $attrs = array()) {
        $size_map = array(
            'sm' => 24,
            'md' => 40,
            'lg' => 64,
        );

        $pixel_size = $size_map[$size] ?? $size_map['md'];
        $url = get_avatar_url($user_id, array('size' => $pixel_size));

        $user = get_userdata($user_id);
        $default_attrs = array(
            'alt' => $user ? $user->display_name : '',
            'class' => 'qs-avatar' . ($size !== 'md' ? ' qs-avatar-' . $size : ''),
        );

        $attrs = wp_parse_args($attrs, $default_attrs);

        $attr_string = '';
        foreach ($attrs as $key => $value) {
            $attr_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return sprintf('<img src="%s"%s loading="lazy">', esc_url($url), $attr_string);
    }

    /**
     * Render a stats card
     *
     * @param string $label Stats label
     * @param string $value Stats value
     * @param array $args Additional arguments
     * @return string HTML output
     */
    public static function stats_card($label, $value, $args = array()) {
        $defaults = array(
            'icon' => '',
            'trend' => '',
            'trend_up' => true,
            'classes' => '',
            'href' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $html = sprintf(
            '<div class="qs-stats-card %s">',
            esc_attr($args['classes'])
        );

        if ($args['href']) {
            $html = sprintf(
                '<a href="%s" class="qs-stats-card %s" style="text-decoration: none; display: block;">',
                esc_url($args['href']),
                esc_attr($args['classes'])
            );
        }

        $html .= '<div class="qs-flex qs-justify-between qs-items-center qs-mb-4">';
        $html .= sprintf('<div class="qs-stats-value">%s</div>', esc_html($value));
        $html .= $args['icon'] ? '<div class="qs-text-primary qs-text-2xl">' . $args['icon'] . '</div>' : '';
        $html .= '</div>';

        $html .= sprintf('<div class="qs-stats-label">%s</div>', esc_html($label));

        if ($args['trend']) {
            $trend_class = $args['trend_up'] ? 'qs-text-green-600' : 'qs-text-red-600';
            $trend_icon = $args['trend_up'] ? '↑' : '↓';
            $html .= sprintf(
                '<div class="qs-text-xs %s qs-mt-2">%s %s</div>',
                esc_attr($trend_class),
                $trend_icon,
                esc_html($args['trend'])
            );
        }

        $html .= $args['href'] ? '</a>' : '';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render a table with modern styling
     *
     * @param array $headers Table headers
     * @param array $rows Table rows
     * @param array $args Table arguments
     * @return string HTML output
     */
    public static function table($headers, $rows, $args = array()) {
        $defaults = array(
            'id' => '',
            'class' => '',
            'empty_message' => __('No data available', 'quickswap'),
            'footer' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $html = sprintf(
            '<div class="qs-card qs-overflow-hidden"><table class="qs-table"%s>',
            $args['id'] ? ' id="' . esc_attr($args['id']) . '"' : ''
        );

        // Header
        $html .= '<thead><tr>';
        foreach ($headers as $header) {
            $html .= sprintf('<th>%s</th>', esc_html($header));
        }
        $html .= '</tr></thead>';

        // Body
        $html .= '<tbody>';
        if (empty($rows)) {
            $colspan = count($headers);
            $html .= sprintf(
                '<tr><td colspan="%d" class="qs-text-center qs-p-6 qs-text-gray-500">%s</td></tr>',
                intval($colspan),
                esc_html($args['empty_message'])
            );
        } else {
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= sprintf('<td>%s</td>', $cell);
                }
                $html .= '</tr>';
            }
        }
        $html .= '</tbody>';

        // Footer
        if (!empty($args['footer'])) {
            $colspan = count($headers);
            $html .= sprintf(
                '<tfoot><tr><td colspan="%d">%s</td></tr></tfoot>',
                intval($colspan),
                $args['footer']
            );
        }

        $html .= '</table></div>';

        return $html;
    }

    /**
     * Render an alert/notice component
     *
     * @param string $message Alert message
     * @param string $type Alert type (info, success, warning, error)
     * @param bool $dismissible Whether the alert can be dismissed
     * @return string HTML output
     */
    public static function alert($message, $type = 'info', $dismissible = false) {
        $types = array(
            'info' => 'qs-alert-info',
            'success' => 'qs-alert-success',
            'warning' => 'qs-alert-warning',
            'error' => 'qs-alert-error',
        );

        $class = 'qs-alert ' . ($types[$type] ?? $types['info']);
        $id = uniqid('qs-alert-');

        $html = sprintf('<div class="%s" id="%s">', esc_attr($class), esc_attr($id));

        if ($dismissible) {
            $html .= sprintf(
                '<button type="button" class="qs-ml-auto qs-bg-transparent qs-border-none qs-cursor-pointer qs-text-gray-500 hover:qs-text-gray-700" onclick="document.getElementById(\'%s\').remove()">&times;</button>',
                esc_js($id)
            );
        }

        $html .= wp_kses($message, array(
            'p' => array(),
            'strong' => array(),
            'em' => array(),
            'a' => array('href' => array(), 'class' => array()),
            'br' => array(),
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
        ));

        $html .= '</div>';

        return $html;
    }

    /**
     * Render a metabox
     *
     * @param string $title Metabox title
     * @param string $content Metabox content
     * @param array $args Additional arguments
     * @return string HTML output
     */
    public static function metabox($title, $content, $args = array()) {
        $defaults = array(
            'id' => '',
            'classes' => '',
            'actions' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $html = sprintf(
            '<div class="qs-metabox %s"%s>',
            esc_attr($args['classes']),
            $args['id'] ? ' id="' . esc_attr($args['id']) . '"' : ''
        );

        if ($title || $args['actions']) {
            $html .= '<div class="qs-metabox-header">';

            if ($title) {
                $html .= sprintf('<h3 class="qs-metabox-title">%s</h3>', esc_html($title));
            }

            if ($args['actions']) {
                $html .= '<div class="qs-metabox-actions">' . $args['actions'] . '</div>';
            }

            $html .= '</div>';
        }

        $html .= '<div class="qs-metabox-content">';
        $html .= $content;
        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

    /**
     * Render a form input
     *
     * @param string $name Input name
     * @param string $value Input value
     * @param array $args Input arguments
     * @return string HTML output
     */
    public static function input($name, $value = '', $args = array()) {
        $defaults = array(
            'type' => 'text',
            'id' => '',
            'placeholder' => '',
            'class' => '',
            'required' => false,
            'disabled' => false,
            'readonly' => false,
            'attrs' => array(),
        );

        $args = wp_parse_args($args, $defaults);

        $id = $args['id'] ? $args['id'] : $name;
        $classes = 'qs-input' . ($args['class'] ? ' ' . $args['class'] : '');

        $attrs = '';
        foreach ($args['attrs'] as $key => $val) {
            $attrs .= sprintf(' %s="%s"', esc_attr($key), esc_attr($val));
        }

        return sprintf(
            '<input type="%s" name="%s" id="%s" value="%s" placeholder="%s" class="%s"%s%s%s%s />',
            esc_attr($args['type']),
            esc_attr($name),
            esc_attr($id),
            esc_attr($value),
            esc_attr($args['placeholder']),
            esc_attr($classes),
            $args['required'] ? ' required' : '',
            $args['disabled'] ? ' disabled' : '',
            $args['readonly'] ? ' readonly' : '',
            $attrs
        );
    }

    /**
     * Render a form select dropdown
     *
     * @param string $name Select name
     * @param array $options Options array (value => label)
     * @param string $selected Currently selected value
     * @param array $args Select arguments
     * @return string HTML output
     */
    public static function select($name, $options, $selected = '', $args = array()) {
        $defaults = array(
            'id' => '',
            'class' => '',
            'placeholder' => '',
            'required' => false,
            'disabled' => false,
            'attrs' => array(),
        );

        $args = wp_parse_args($args, $defaults);

        $id = $args['id'] ? $args['id'] : $name;
        $classes = 'qs-select' . ($args['class'] ? ' ' . $args['class'] : '');

        $attrs = '';
        foreach ($args['attrs'] as $key => $val) {
            $attrs .= sprintf(' %s="%s"', esc_attr($key), esc_attr($val));
        }

        $html = sprintf(
            '<select name="%s" id="%s" class="%s"%s%s%s>',
            esc_attr($name),
            esc_attr($id),
            esc_attr($classes),
            $args['required'] ? ' required' : '',
            $args['disabled'] ? ' disabled' : '',
            $attrs
        );

        if ($args['placeholder']) {
            $html .= sprintf(
                '<option value="">%s</option>',
                esc_html($args['placeholder'])
            );
        }

        foreach ($options as $value => $label) {
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                esc_attr($value),
                selected($selected, $value, false),
                esc_html($label)
            );
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Render a grid layout
     *
     * @param array $items Grid items
     * @param string $cols Grid columns (1, 2, 3, 4)
     * @param array $args Additional arguments
     * @return string HTML output
     */
    public static function grid($items, $cols = 3, $args = array()) {
        $defaults = array(
            'gap' => '6',
            'responsive' => true,
            'classes' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $classes = sprintf('qs-grid qs-grid-cols-%d qs-gap-%s', intval($cols), $args['gap']);

        if ($args['responsive']) {
            $classes .= sprintf(' qs-sm:grid-cols-1 qs-md:grid-cols-2 qs-lg:grid-cols-%d', intval($cols));
        }

        $classes .= ' ' . $args['classes'];

        $html = sprintf('<div class="%s">', esc_attr($classes));

        foreach ($items as $item) {
            $html .= $item;
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Render a divider
     *
     * @param array $args Divider arguments
     * @return string HTML output
     */
    public static function divider($args = array()) {
        $defaults = array(
            'class' => '',
            'style' => '',
        );

        $args = wp_parse_args($args, $defaults);

        return sprintf(
            '<hr class="qs-divider %s" style="%s" />',
            esc_attr($args['class']),
            esc_attr($args['style'])
        );
    }

    /**
     * Render a spinner for loading state
     *
     * @param array $args Spinner arguments
     * @return string HTML output
     */
    public static function spinner($args = array()) {
        $defaults = array(
            'size' => 'md',
            'class' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $sizes = array(
            'sm' => '0.75rem',
            'md' => '1rem',
            'lg' => '1.5rem',
        );

        $size = $sizes[$args['size']] ?? $sizes['md'];
        $classes = 'qs-spinner' . ($args['class'] ? ' ' . $args['class'] : '');

        return sprintf(
            '<span class="%s" style="width: %s; height: %s;"></span>',
            esc_attr($classes),
            esc_attr($size),
            esc_attr($size)
        );
    }

    /**
     * Render an icon wrapper
     *
     * @param string $icon SVG icon or HTML
     * @param array $args Icon arguments
     * @return string HTML output
     */
    public static function icon($icon, $args = array()) {
        $defaults = array(
            'size' => 'md',
            'class' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $sizes = array(
            'sm' => '1rem',
            'md' => '1.25rem',
            'lg' => '1.5rem',
            'xl' => '2rem',
        );

        $size = $sizes[$args['size']] ?? $sizes['md'];
        $classes = 'qs-inline-flex qs-items-center qs-justify-center' . ($args['class'] ? ' ' . $args['class'] : '');

        return sprintf(
            '<span class="%s" style="width: %s; height: %s;">%s</span>',
            esc_attr($classes),
            esc_attr($size),
            esc_attr($size),
            $icon
        );
    }

    /**
     * Render a user chip with avatar and name
     *
     * @param int $user_id User ID
     * @param array $args Additional arguments
     * @return string HTML output
     */
    public static function user_chip($user_id, $args = array()) {
        $defaults = array(
            'show_name' => true,
            'size' => 'sm',
            'class' => '',
            'linked' => false,
        );

        $args = wp_parse_args($args, $defaults);

        $user = get_userdata($user_id);

        if (!$user) {
            return '';
        }

        $avatar = self::avatar($user_id, $args['size']);
        $name = $args['show_name'] ? esc_html($user->display_name) : '';

        $html = sprintf(
            '<div class="qs-flex qs-items-center qs-gap-2 %s">',
            esc_attr($args['class'])
        );

        if ($args['linked']) {
            $html .= sprintf(
                '<a href="%s" class="qs-flex qs-items-center qs-gap-2" style="text-decoration: none;">',
                esc_url(get_edit_user_link($user_id))
            );
        }

        $html .= $avatar;

        if ($name) {
            $html .= sprintf('<span class="qs-text-sm qs-font-medium">%s</span>', $name);
        }

        if ($args['linked']) {
            $html .= '</a>';
        }

        $html .= '</div>';

        return $html;
    }
}
