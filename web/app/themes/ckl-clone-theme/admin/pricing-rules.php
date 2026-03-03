<?php
/**
 * Pricing Rules Admin Page
 *
 * Comprehensive pricing rules management with multiple tabs:
 * 1. Global Rules (Highest Priority)
 * 2. Vehicle Rules
 * 3. Rule Templates
 * 4. Bulk Actions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Pricing Rules page
 */
function ckl_pricing_rules_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'global';
    $global_rules = get_option('ckl_global_pricing_rules', array());
    $templates = get_option('ckl_pricing_rule_templates', array());

    // Get all vehicles
    $vehicles = get_posts(array(
        'post_type' => 'vehicle',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));
    ?>
    <div class="wrap ckl-pricing-rules">
        <h1>
            <span class="dashicons dashicons-list-view" style="margin-top: 4px;"></span>
            <?php _e('Pricing Rules Management', 'ckl-car-rental'); ?>
        </h1>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url('admin.php?page=ckl-pricing-rules&tab=global'); ?>"
               class="nav-tab <?php echo $active_tab === 'global' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-site"></span>
                <?php _e('Global Rules', 'ckl-car-rental'); ?>
                <span class="badge" style="background: #0073aa; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 11px;">
                    <?php echo count($global_rules); ?>
                </span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=ckl-pricing-rules&tab=vehicles'); ?>"
               class="nav-tab <?php echo $active_tab === 'vehicles' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-car"></span>
                <?php _e('Vehicle Rules', 'ckl-car-rental'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=ckl-pricing-rules&tab=templates'); ?>"
               class="nav-tab <?php echo $active_tab === 'templates' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-book"></span>
                <?php _e('Templates', 'ckl-car-rental'); ?>
                <span class="badge" style="background: #0073aa; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 11px;">
                    <?php echo count($templates); ?>
                </span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=ckl-pricing-rules&tab=bulk'); ?>"
               class="nav-tab <?php echo $active_tab === 'bulk' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-groups"></span>
                <?php _e('Bulk Actions', 'ckl-car-rental'); ?>
            </a>
        </h2>

        <div class="ckl-pricing-rules-content">
            <?php
            switch ($active_tab) {
                case 'global':
                    ckl_render_global_rules_tab($global_rules);
                    break;
                case 'vehicles':
                    ckl_render_vehicle_rules_tab($vehicles);
                    break;
                case 'templates':
                    ckl_render_templates_tab($templates);
                    break;
                case 'bulk':
                    ckl_render_bulk_actions_tab($vehicles);
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Render Global Rules tab
 */
function ckl_render_global_rules_tab($global_rules) {
    ?>
    <div class="ckl-global-rules-tab">
        <div class="ckl-tab-header" style="margin-bottom: 20px;">
            <p class="description">
                <span class="dashicons dashicons-info" style="color: #0073aa;"></span>
                <?php _e('<strong>Global rules</strong> apply to ALL vehicles and override vehicle-specific rules. Use these for holidays, special events, and system-wide pricing adjustments.', 'ckl-car-rental'); ?>
            </p>
            <button type="button" class="button button-primary" id="ckl-add-global-rule">
                <span class="dashicons dashicons-plus"></span>
                <?php _e('Add Global Rule', 'ckl-car-rental'); ?>
            </button>
        </div>

        <?php if (empty($global_rules)): ?>
            <div class="notice notice-info inline">
                <p><?php _e('No global pricing rules found. Add a rule to apply pricing adjustments across all vehicles.', 'ckl-car-rental'); ?></p>
            </div>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Priority', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Rule Name', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Date Range', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Adjustment', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Recurring', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Status', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Actions', 'ckl-car-rental'); ?></th>
                    </tr>
                </thead>
                <tbody id="ckl-global-rules-list">
                    <?php foreach ($global_rules as $rule): ?>
                        <tr data-rule-id="<?php echo esc_attr($rule['id']); ?>">
                            <td>
                                <span class="ckl-priority-badge" style="background: #0073aa; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                    <?php echo esc_html($rule['priority']); ?>
                                </span>
                            </td>
                            <td>
                                <strong><?php echo esc_html($rule['name']); ?></strong>
                            </td>
                            <td>
                                <?php echo esc_html($rule['start_date']); ?> → <?php echo esc_html($rule['end_date']); ?>
                            </td>
                            <td>
                                <?php if ($rule['adjustment_type'] === 'percentage'): ?>
                                    <span style="color: #d63638;">+<?php echo esc_html($rule['amount']); ?>%</span>
                                <?php else: ?>
                                    <span style="color: #00a32a;">+RM<?php echo esc_html($rule['amount']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $recurring_labels = array(
                                    'none' => __('One-time', 'ckl-car-rental'),
                                    'yearly' => __('Yearly', 'ckl-car-rental'),
                                    'monthly' => __('Monthly', 'ckl-car-rental'),
                                    'weekly' => __('Weekly', 'ckl-car-rental'),
                                );
                                echo esc_html($recurring_labels[$rule['recurring']] ?? $rule['recurring']);
                                ?>
                            </td>
                            <td>
                                <?php if ($rule['active']): ?>
                                    <span class="dashicons dashicons-yes-alt" style="color: #00a32a;"></span>
                                    <?php _e('Active', 'ckl-car-rental'); ?>
                                <?php else: ?>
                                    <span class="dashicons dashicons-dismiss" style="color: #646970;"></span>
                                    <?php _e('Inactive', 'ckl-car-rental'); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="button button-small ckl-edit-rule" data-rule-id="<?php echo esc_attr($rule['id']); ?>">
                                    <?php _e('Edit', 'ckl-car-rental'); ?>
                                </button>
                                <button type="button" class="button button-small ckl-toggle-rule" data-rule-id="<?php echo esc_attr($rule['id']); ?>">
                                    <?php echo $rule['active'] ? __('Disable', 'ckl-car-rental') : __('Enable', 'ckl-car-rental'); ?>
                                </button>
                                <button type="button" class="button button-small ckl-delete-rule" data-rule-id="<?php echo esc_attr($rule['id']); ?>">
                                    <?php _e('Delete', 'ckl-car-rental'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Global Rule Modal -->
    <div id="ckl-global-rule-modal" style="display: none;">
        <div class="ckl-modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 100000;"></div>
        <div class="ckl-modal-content" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 25px; width: 500px; max-width: 90%; max-height: 90vh; overflow-y: auto; z-index: 100001; box-shadow: 0 5px 15px rgba(0,0,0,0.3); border-radius: 4px;">
            <h2 id="ckl-rule-modal-title"><?php _e('Add Global Pricing Rule', 'ckl-car-rental'); ?></h2>
            <form id="ckl-global-rule-form">
                <input type="hidden" id="ckl-rule-id" value="">
                <input type="hidden" id="ckl-rule-editing" value="0">

                <table class="form-table">
                    <tr>
                        <th>
                            <label for="ckl-rule-name"><?php _e('Rule Name', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="ckl-rule-name" class="regular-text" required placeholder="<?php _e('e.g., Weekend Surcharge', 'ckl-car-rental'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-rule-start"><?php _e('Start Date', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="date" id="ckl-rule-start" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-rule-end"><?php _e('End Date', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="date" id="ckl-rule-end" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-rule-type"><?php _e('Adjustment Type', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select id="ckl-rule-type" required>
                                <option value="percentage"><?php _e('Percentage', 'ckl-car-rental'); ?></option>
                                <option value="fixed"><?php _e('Fixed Amount (RM)', 'ckl-car-rental'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-rule-amount"><?php _e('Amount', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="ckl-rule-amount" step="0.01" min="0" required>
                            <p class="description" id="ckl-rule-amount-desc"><?php _e('Percentage increase', 'ckl-car-rental'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-rule-recurring"><?php _e('Recurring', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select id="ckl-rule-recurring">
                                <option value="none"><?php _e('None (One-time)', 'ckl-car-rental'); ?></option>
                                <option value="yearly"><?php _e('Yearly', 'ckl-car-rental'); ?></option>
                                <option value="monthly"><?php _e('Monthly', 'ckl-car-rental'); ?></option>
                                <option value="weekly"><?php _e('Weekly', 'ckl-car-rental'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-rule-priority"><?php _e('Priority', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="ckl-rule-priority" value="90" min="1" max="100">
                            <p class="description"><?php _e('Higher priority rules are applied first. Global rules: 80-100, Vehicle rules: 50-79', 'ckl-car-rental'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="ckl-rule-active"><?php _e('Active', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" id="ckl-rule-active" value="1" checked>
                                <?php _e('Enable this rule', 'ckl-car-rental'); ?>
                            </label>
                        </td>
                    </tr>
                </table>

                <p class="ckl-modal-actions" style="text-align: right; margin-top: 20px;">
                    <button type="button" class="button" id="ckl-cancel-rule-modal"><?php _e('Cancel', 'ckl-car-rental'); ?></button>
                    <button type="submit" class="button button-primary"><?php _e('Save Rule', 'ckl-car-rental'); ?></button>
                </p>
            </form>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var globalRules = <?php echo json_encode($global_rules); ?>;
        var nextRuleId = <?php echo !empty($global_rules) ? max(array_column($global_rules, 'id')) + 1 : 1; ?>;

        function openRuleModal() {
            $('#ckl-global-rule-modal').show();
            $('#ckl-rule-modal-title').text('<?php _e('Add Global Pricing Rule', 'ckl-car-rental'); ?>');
            $('#ckl-rule-editing').val('0');
            $('#ckl-rule-id').val('');
        }

        function closeRuleModal() {
            $('#ckl-global-rule-modal').hide();
            $('#ckl-global-rule-form')[0].reset();
        }

        function editRule(ruleId) {
            var rule = globalRules.find(function(r) { return r.id == ruleId; });
            if (!rule) return;

            $('#ckl-rule-modal-title').text('<?php _e('Edit Global Pricing Rule', 'ckl-car-rental'); ?>');
            $('#ckl-rule-editing').val('1');
            $('#ckl-rule-id').val(rule.id);
            $('#ckl-rule-name').val(rule.name);
            $('#ckl-rule-start').val(rule.start_date);
            $('#ckl-rule-end').val(rule.end_date);
            $('#ckl-rule-type').val(rule.adjustment_type);
            $('#ckl-rule-amount').val(rule.amount);
            $('#ckl-rule-recurring').val(rule.recurring);
            $('#ckl-rule-priority').val(rule.priority);
            $('#ckl-rule-active').prop('checked', rule.active);
            $('#ckl-global-rule-modal').show();
        }

        $('#ckl-add-global-rule').on('click', openRuleModal);
        $('#ckl-cancel-rule-modal, .ckl-modal-overlay').on('click', closeRuleModal);

        $('#ckl-rule-type').on('change', function() {
            var type = $(this).val();
            $('#ckl-rule-amount-desc').text(type === 'percentage' ? '<?php _e('Percentage increase', 'ckl-car-rental'); ?>' : '<?php _e('Fixed amount in RM', 'ckl-car-rental'); ?>');
        });

        $(document).on('click', '.ckl-edit-rule', function() {
            editRule($(this).data('rule-id'));
        });

        $(document).on('click', '.ckl-toggle-rule', function() {
            var button = $(this);
            var ruleId = button.data('rule-id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ckl_toggle_global_rule',
                    nonce: '<?php echo wp_create_nonce('ckl-pricing-rules-nonce'); ?>',
                    rule_id: ruleId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });

        $(document).on('click', '.ckl-delete-rule', function() {
            if (!confirm('<?php _e('Are you sure you want to delete this rule?', 'ckl-car-rental'); ?>')) return;

            var ruleId = $(this).data('rule-id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ckl_delete_global_rule',
                    nonce: '<?php echo wp_create_nonce('ckl-pricing-rules-nonce'); ?>',
                    rule_id: ruleId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });

        $('#ckl-global-rule-form').on('submit', function(e) {
            e.preventDefault();

            var data = {
                action: 'ckl_save_global_rule',
                nonce: '<?php echo wp_create_nonce('ckl-pricing-rules-nonce'); ?>',
                editing: $('#ckl-rule-editing').val(),
                rule: {
                    id: $('#ckl-rule-id').val() || nextRuleId++,
                    name: $('#ckl-rule-name').val(),
                    start_date: $('#ckl-rule-start').val(),
                    end_date: $('#ckl-rule-end').val(),
                    adjustment_type: $('#ckl-rule-type').val(),
                    amount: $('#ckl-rule-amount').val(),
                    recurring: $('#ckl-rule-recurring').val(),
                    priority: $('#ckl-rule-priority').val(),
                    active: $('#ckl-rule-active').prop('checked') ? 1 : 0
                }
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || '<?php _e('Error saving rule', 'ckl-car-rental'); ?>');
                    }
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Render Vehicle Rules tab
 */
function ckl_render_vehicle_rules_tab($vehicles) {
    ?>
    <div class="ckl-vehicle-rules-tab">
        <div class="ckl-tab-header" style="margin-bottom: 20px;">
            <p class="description">
                <span class="dashicons dashicons-info" style="color: #0073aa;"></span>
                <?php _e('View and manage pricing rules for individual vehicles. Rules set here can be overridden by global rules and peak prices.', 'ckl-car-rental'); ?>
            </p>
        </div>

        <div class="ckl-vehicle-filters" style="margin-bottom: 15px;">
            <select id="ckl-filter-vehicle" class="regular-text">
                <option value=""><?php _e('All Vehicles', 'ckl-car-rental'); ?></option>
                <?php foreach ($vehicles as $vehicle): ?>
                    <option value="<?php echo $vehicle->ID; ?>"><?php echo esc_html($vehicle->post_title); ?></option>
                <?php endforeach; ?>
            </select>
            <select id="ckl-filter-type" class="regular-text">
                <option value=""><?php _e('All Types', 'ckl-car-rental'); ?></option>
                <option value="sedan"><?php _e('Sedan', 'ckl-car-rental'); ?></option>
                <option value="mpv"><?php _e('MPV', 'ckl-car-rental'); ?></option>
                <option value="suv"><?php _e('SUV', 'ckl-car-rental'); ?></option>
                <option value="motorcycle"><?php _e('Motorcycle', 'ckl-car-rental'); ?></option>
            </select>
            <select id="ckl-filter-status" class="regular-text">
                <option value=""><?php _e('All Status', 'ckl-car-rental'); ?></option>
                <option value="active"><?php _e('Active Only', 'ckl-car-rental'); ?></option>
                <option value="inactive"><?php _e('Inactive Only', 'ckl-car-rental'); ?></option>
            </select>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Vehicle', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Rules Count', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Active Rules', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Actions', 'ckl-car-rental'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <?php
                    $rules = get_post_meta($vehicle->ID, '_vehicle_pricing_rules', true);
                    if (!is_array($rules)) $rules = array();
                    $active_rules = array_filter($rules, function($r) { return $r['active'] === 'yes'; });
                    ?>
                    <tr data-vehicle-id="<?php echo esc_attr($vehicle->ID); ?>" data-vehicle-type="<?php echo esc_attr(get_post_meta($vehicle->ID, '_vehicle_type', true)); ?>">
                        <td>
                            <strong><?php echo esc_html($vehicle->post_title); ?></strong>
                            <br>
                            <small><?php echo esc_html(get_post_meta($vehicle->ID, '_vehicle_type', true)); ?></small>
                        </td>
                        <td><?php echo count($rules); ?></td>
                        <td><?php echo count($active_rules); ?></td>
                        <td>
                            <a href="<?php echo get_edit_post_link($vehicle->ID); ?>#vehicle_dynamic_pricing" class="button button-small">
                                <?php _e('Edit Rules', 'ckl-car-rental'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="description">
            <?php _e('To edit individual vehicle rules, click "Edit Rules" to open the vehicle edit screen where you can manage per-vehicle pricing rules.', 'ckl-car-rental'); ?>
        </p>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#ckl-filter-vehicle, #ckl-filter-type, #ckl-filter-status').on('change', function() {
            var vehicleId = $('#ckl-filter-vehicle').val();
            var vehicleType = $('#ckl-filter-type').val();
            var status = $('#ckl-filter-status').val();

            $('tbody tr').each(function() {
                var row = $(this);
                var show = true;

                if (vehicleId && row.data('vehicle-id') != vehicleId) show = false;
                if (vehicleType && row.data('vehicle-type') != vehicleType) show = false;

                row.toggle(show);
            });
        });
    });
    </script>
    <?php
}

/**
 * Render Templates tab
 */
function ckl_render_templates_tab($templates) {
    // Default templates
    $default_templates = array(
        'hari_raya' => array(
            'name' => 'Hari Raya',
            'type' => 'percentage',
            'amount' => 50,
            'recurring' => 'yearly',
            'description' => __('Common Hari Raya peak period with 50% surcharge', 'ckl-car-rental')
        ),
        'school_holidays' => array(
            'name' => 'School Holidays',
            'type' => 'percentage',
            'amount' => 25,
            'recurring' => 'yearly',
            'description' => __('School holiday period with 25% surcharge', 'ckl-car-rental')
        ),
        'weekend_surcharge' => array(
            'name' => 'Weekend Surcharge',
            'type' => 'percentage',
            'amount' => 15,
            'recurring' => 'weekly',
            'description' => __('Weekend pricing with 15% surcharge', 'ckl-car-rental')
        ),
    );
    ?>
    <div class="ckl-templates-tab">
        <div class="ckl-tab-header" style="margin-bottom: 20px;">
            <p class="description">
                <span class="dashicons dashicons-info" style="color: #0073aa;"></span>
                <?php _e('Save pricing rules as reusable templates. Quick apply templates to vehicles or use them as starting points for new rules.', 'ckl-car-rental'); ?>
            </p>
            <button type="button" class="button button-primary" id="ckl-save-new-template">
                <span class="dashicons dashicons-plus"></span>
                <?php _e('Save New Template', 'ckl-car-rental'); ?>
            </button>
        </div>

        <h3><?php _e('Default Templates', 'ckl-car-rental'); ?></h3>
        <table class="wp-list-table widefat fixed striped" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th><?php _e('Template Name', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Type', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Amount', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Recurring', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Description', 'ckl-car-rental'); ?></th>
                    <th><?php _e('Actions', 'ckl-car-rental'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($default_templates as $key => $template): ?>
                    <tr>
                        <td><strong><?php echo esc_html($template['name']); ?></strong></td>
                        <td><?php echo $template['type'] === 'percentage' ? __('Percentage', 'ckl-car-rental') : __('Fixed', 'ckl-car-rental'); ?></td>
                        <td>
                            <?php if ($template['type'] === 'percentage'): ?>
                                +<?php echo esc_html($template['amount']); ?>%
                            <?php else: ?>
                                +RM<?php echo esc_html($template['amount']); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html(ucfirst($template['recurring'])); ?></td>
                        <td><?php echo esc_html($template['description']); ?></td>
                        <td>
                            <button type="button" class="button button-small ckl-apply-default-template" data-template="<?php echo esc_attr($key); ?>">
                                <?php _e('Apply', 'ckl-car-rental'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3><?php _e('Custom Templates', 'ckl-car-rental'); ?></h3>
        <?php if (empty($templates)): ?>
            <div class="notice notice-info inline">
                <p><?php _e('No custom templates yet. Create a template by saving a pricing rule configuration.', 'ckl-car-rental'); ?></p>
            </div>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Template Name', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Type', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Amount', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Recurring', 'ckl-car-rental'); ?></th>
                        <th><?php _e('Actions', 'ckl-car-rental'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($templates as $template): ?>
                        <tr data-template-id="<?php echo esc_attr($template['id']); ?>">
                            <td><strong><?php echo esc_html($template['name']); ?></strong></td>
                            <td><?php echo $template['adjustment_type'] === 'percentage' ? __('Percentage', 'ckl-car-rental') : __('Fixed', 'ckl-car-rental'); ?></td>
                            <td>
                                <?php if ($template['adjustment_type'] === 'percentage'): ?>
                                    +<?php echo esc_html($template['amount']); ?>%
                                <?php else: ?>
                                    +RM<?php echo esc_html($template['amount']); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html(ucfirst($template['recurring'])); ?></td>
                            <td>
                                <button type="button" class="button button-small ckl-apply-template" data-template-id="<?php echo esc_attr($template['id']); ?>">
                                    <?php _e('Apply', 'ckl-car-rental'); ?>
                                </button>
                                <button type="button" class="button button-small ckl-delete-template" data-template-id="<?php echo esc_attr($template['id']); ?>">
                                    <?php _e('Delete', 'ckl-car-rental'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <p style="margin-top: 15px;">
            <button type="button" class="button" id="ckl-export-templates">
                <span class="dashicons dashicons-download"></span>
                <?php _e('Export Templates', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="button" id="ckl-import-templates">
                <span class="dashicons dashicons-upload"></span>
                <?php _e('Import Templates', 'ckl-car-rental'); ?>
            </button>
        </p>
    </div>

    <script>
    var defaultTemplates = <?php echo json_encode($default_templates); ?>;
    </script>
    <?php
}

/**
 * Render Bulk Actions tab
 */
function ckl_render_bulk_actions_tab($vehicles) {
    // Get vehicle categories (types)
    $vehicle_types = array();
    foreach ($vehicles as $vehicle) {
        $type = get_post_meta($vehicle->ID, '_vehicle_type', true);
        if ($type && !isset($vehicle_types[$type])) {
            $vehicle_types[$type] = array();
        }
        if ($type) {
            $vehicle_types[$type][] = $vehicle;
        }
    }
    ?>
    <div class="ckl-bulk-actions-tab">
        <div class="ckl-tab-header" style="margin-bottom: 20px;">
            <p class="description">
                <span class="dashicons dashicons-info" style="color: #0073aa;"></span>
                <?php _e('Apply pricing rules to multiple vehicles at once. Preview affected vehicles before applying bulk changes.', 'ckl-car-rental'); ?>
            </p>
        </div>

        <div class="ckl-bulk-actions-form" style="background: #fff; padding: 20px; border: 1px solid #ddd; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <h3><?php _e('Apply Pricing Rule to Multiple Vehicles', 'ckl-car-rental'); ?></h3>

            <table class="form-table">
                <tr>
                    <th>
                        <label><?php _e('Select Vehicles', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <div style="margin-bottom: 10px;">
                            <label>
                                <input type="radio" name="ckl-bulk-select-type" value="all" checked>
                                <?php _e('All Vehicles', 'ckl-car-rental'); ?>
                            </label>
                            <label style="margin-left: 20px;">
                                <input type="radio" name="ckl-bulk-select-type" value="type">
                                <?php _e('By Vehicle Type', 'ckl-car-rental'); ?>
                            </label>
                            <label style="margin-left: 20px;">
                                <input type="radio" name="ckl-bulk-select-type" value="manual">
                                <?php _e('Manual Selection', 'ckl-car-rental'); ?>
                            </label>
                        </div>
                        <div id="ckl-bulk-type-selector" style="display: none;">
                            <select id="ckl-bulk-vehicle-type" class="regular-text">
                                <?php foreach ($vehicle_types as $type => $type_vehicles): ?>
                                    <option value="<?php echo esc_attr($type); ?>">
                                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $type))); ?> (<?php echo count($type_vehicles); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="ckl-bulk-manual-selector" style="display: none;">
                            <select id="ckl-bulk-vehicles" multiple size="8" style="width: 400px; height: 200px;">
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?php echo $vehicle->ID; ?>"><?php echo esc_html($vehicle->post_title); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple vehicles', 'ckl-car-rental'); ?></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ckl-bulk-rule-name"><?php _e('Rule Name', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="ckl-bulk-rule-name" class="regular-text" required placeholder="<?php _e('e.g., Holiday Season 2026', 'ckl-car-rental'); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ckl-bulk-start-date"><?php _e('Start Date', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="date" id="ckl-bulk-start-date" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ckl-bulk-end-date"><?php _e('End Date', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="date" id="ckl-bulk-end-date" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ckl-bulk-adjustment-type"><?php _e('Adjustment Type', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <select id="ckl-bulk-adjustment-type" required>
                            <option value="percentage"><?php _e('Percentage', 'ckl-car-rental'); ?></option>
                            <option value="fixed"><?php _e('Fixed Amount (RM)', 'ckl-car-rental'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ckl-bulk-amount"><?php _e('Amount', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="ckl-bulk-amount" step="0.01" min="0" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ckl-bulk-recurring"><?php _e('Recurring', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <select id="ckl-bulk-recurring">
                            <option value="none"><?php _e('None (One-time)', 'ckl-car-rental'); ?></option>
                            <option value="yearly"><?php _e('Yearly', 'ckl-car-rental'); ?></option>
                            <option value="monthly"><?php _e('Monthly', 'ckl-car-rental'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>

            <p>
                <button type="button" class="button" id="ckl-preview-bulk">
                    <?php _e('Preview Affected Vehicles', 'ckl-car-rental'); ?>
                </button>
                <button type="button" class="button button-primary" id="ckl-apply-bulk">
                    <?php _e('Apply to Selected Vehicles', 'ckl-car-rental'); ?>
                </button>
            </p>
        </div>

        <div id="ckl-bulk-preview" style="margin-top: 20px; display: none;">
            <h3><?php _e('Preview - Affected Vehicles', 'ckl-car-rental'); ?></h3>
            <div id="ckl-bulk-preview-list"></div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('input[name="ckl-bulk-select-type"]').on('change', function() {
            var type = $(this).val();
            $('#ckl-bulk-type-selector, #ckl-bulk-manual-selector').hide();
            if (type === 'type') {
                $('#ckl-bulk-type-selector').show();
            } else if (type === 'manual') {
                $('#ckl-bulk-manual-selector').show();
            }
        });

        $('#ckl-preview-bulk').on('click', function() {
            var selectType = $('input[name="ckl-bulk-select-type"]:checked').val();
            var vehicleIds = [];

            if (selectType === 'all') {
                $('#ckl-bulk-vehicles option').each(function() {
                    vehicleIds.push($(this).val());
                });
            } else if (selectType === 'type') {
                var type = $('#ckl-bulk-vehicle-type').val();
                $('tbody tr[data-vehicle-type="' + type + '"]').each(function() {
                    vehicleIds.push($(this).data('vehicle-id'));
                });
            } else {
                $('#ckl-bulk-vehicles').val().forEach(function(id) {
                    vehicleIds.push(id);
                });
            }

            if (vehicleIds.length === 0) {
                alert('<?php _e('Please select at least one vehicle', 'ckl-car-rental'); ?>');
                return;
            }

            var html = '<p><strong>' + vehicleIds.length + ' <?php _e('vehicles will be affected:', 'ckl-car-rental'); ?></strong></p><ul>';
            vehicleIds.forEach(function(id) {
                var row = $('#ckl-bulk-vehicles option[value="' + id + '"]');
                html += '<li>' + row.text() + '</li>';
            });
            html += '</ul>';

            $('#ckl-bulk-preview-list').html(html);
            $('#ckl-bulk-preview').show();
        });

        $('#ckl-apply-bulk').on('click', function() {
            var selectType = $('input[name="ckl-bulk-select-type"]:checked').val();
            var vehicleIds = [];

            if (selectType === 'all') {
                $('#ckl-bulk-vehicles option').each(function() {
                    vehicleIds.push($(this).val());
                });
            } else if (selectType === 'type') {
                var type = $('#ckl-bulk-vehicle-type').val();
                $('tbody tr[data-vehicle-type="' + type + '"]').each(function() {
                    vehicleIds.push($(this).data('vehicle-id'));
                });
            } else {
                $('#ckl-bulk-vehicles').val().forEach(function(id) {
                    vehicleIds.push(id);
                });
            }

            if (vehicleIds.length === 0) {
                alert('<?php _e('Please select at least one vehicle', 'ckl-car-rental'); ?>');
                return;
            }

            if (!confirm(vehicleIds.length + ' <?php _e('vehicles will be updated. Continue?', 'ckl-car-rental'); ?>')) {
                return;
            }

            var data = {
                action: 'ckl_bulk_apply_rule',
                nonce: '<?php echo wp_create_nonce('ckl-pricing-rules-nonce'); ?>',
                vehicle_ids: vehicleIds,
                rule: {
                    name: $('#ckl-bulk-rule-name').val(),
                    start_date: $('#ckl-bulk-start-date').val(),
                    end_date: $('#ckl-bulk-end-date').val(),
                    type: $('#ckl-bulk-adjustment-type').val(),
                    amount: $('#ckl-bulk-amount').val(),
                    recurring: $('#ckl-bulk-recurring').val()
                }
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        $('#ckl-bulk-preview').hide();
                    } else {
                        alert(response.data.message || '<?php _e('Error applying rule', 'ckl-car-rental'); ?>');
                    }
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * AJAX handlers for pricing rules
 */

// Save global rule
function ckl_ajax_save_global_rule() {
    check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $editing = isset($_POST['editing']) ? intval($_POST['editing']) : 0;
    $rule = isset($_POST['rule']) ? $_POST['rule'] : array();

    if (empty($rule['name']) || empty($rule['start_date']) || empty($rule['end_date'])) {
        wp_send_json_error(array('message' => __('Missing required fields', 'ckl-car-rental')));
    }

    $global_rules = get_option('ckl_global_pricing_rules', array());

    $new_rule = array(
        'id' => intval($rule['id']),
        'name' => sanitize_text_field($rule['name']),
        'start_date' => sanitize_text_field($rule['start_date']),
        'end_date' => sanitize_text_field($rule['end_date']),
        'adjustment_type' => sanitize_text_field($rule['type']),
        'amount' => floatval($rule['amount']),
        'recurring' => sanitize_text_field($rule['recurring']),
        'priority' => intval($rule['priority']),
        'active' => boolval($rule['active']),
    );

    if ($editing) {
        $index = -1;
        foreach ($global_rules as $i => $r) {
            if ($r['id'] == $rule['id']) {
                $index = $i;
                break;
            }
        }
        if ($index >= 0) {
            $global_rules[$index] = $new_rule;
        }
    } else {
        $global_rules[] = $new_rule;
    }

    // Sort by priority
    usort($global_rules, function($a, $b) {
        return $b['priority'] - $a['priority'];
    });

    update_option('ckl_global_pricing_rules', $global_rules);

    wp_send_json_success(array('message' => __('Rule saved successfully', 'ckl-car-rental')));
}
add_action('wp_ajax_ckl_save_global_rule', 'ckl_ajax_save_global_rule');

// Toggle global rule
function ckl_ajax_toggle_global_rule() {
    check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $rule_id = isset($_POST['rule_id']) ? intval($_POST['rule_id']) : 0;

    $global_rules = get_option('ckl_global_pricing_rules', array());

    foreach ($global_rules as &$rule) {
        if ($rule['id'] == $rule_id) {
            $rule['active'] = !$rule['active'];
            break;
        }
    }

    update_option('ckl_global_pricing_rules', $global_rules);

    wp_send_json_success(array('message' => __('Rule updated', 'ckl-car-rental')));
}
add_action('wp_ajax_ckl_toggle_global_rule', 'ckl_ajax_toggle_global_rule');

// Delete global rule
function ckl_ajax_delete_global_rule() {
    check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $rule_id = isset($_POST['rule_id']) ? intval($_POST['rule_id']) : 0;

    $global_rules = get_option('ckl_global_pricing_rules', array());

    $global_rules = array_filter($global_rules, function($r) use ($rule_id) {
        return $r['id'] != $rule_id;
    });

    update_option('ckl_global_pricing_rules', array_values($global_rules));

    wp_send_json_success(array('message' => __('Rule deleted', 'ckl-car-rental')));
}
add_action('wp_ajax_ckl_delete_global_rule', 'ckl_ajax_delete_global_rule');

// Bulk apply rule
function ckl_ajax_bulk_apply_rule() {
    check_ajax_referer('ckl-pricing-rules-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $vehicle_ids = isset($_POST['vehicle_ids']) ? $_POST['vehicle_ids'] : array();
    $rule = isset($_POST['rule']) ? $_POST['rule'] : array();

    if (empty($vehicle_ids) || empty($rule['name']) || empty($rule['start_date']) || empty($rule['end_date'])) {
        wp_send_json_error(array('message' => __('Missing required data', 'ckl-car-rental')));
    }

    $new_rule = array(
        'name' => sanitize_text_field($rule['name']),
        'start_date' => sanitize_text_field($rule['start_date']),
        'end_date' => sanitize_text_field($rule['end_date']),
        'type' => sanitize_text_field($rule['type']),
        'amount' => floatval($rule['amount']),
        'recurring' => sanitize_text_field($rule['recurring']),
        'priority' => 50,
        'active' => 'yes',
    );

    $count = 0;
    foreach ($vehicle_ids as $vehicle_id) {
        $rules = get_post_meta($vehicle_id, '_vehicle_pricing_rules', true);
        if (!is_array($rules)) {
            $rules = array();
        }

        $new_rule['id'] = uniqid();
        $rules[] = $new_rule;

        // Sort by priority
        usort($rules, function($a, $b) {
            return ($b['priority'] ?? 0) - ($a['priority'] ?? 0);
        });

        update_post_meta($vehicle_id, '_vehicle_pricing_rules', $rules);
        $count++;
    }

    wp_send_json_success(array('message' => sprintf(__('Rule applied to %d vehicles', 'ckl-car-rental'), $count)));
}
add_action('wp_ajax_ckl_bulk_apply_rule', 'ckl_ajax_bulk_apply_rule');
