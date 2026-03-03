<?php
/**
 * My Account Profile
 *
 * Profile management page for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get user meta
$first_name = get_user_meta($customer_id, 'first_name', true);
$last_name = get_user_meta($customer_id, 'last_name', true);
$phone = get_user_meta($customer_id, 'billing_phone', true);
$date_of_birth = get_user_meta($customer_id, 'date_of_birth', true);

// Address fields
$address_1 = get_user_meta($customer_id, 'billing_address_1', true);
$address_2 = get_user_meta($customer_id, 'billing_address_2', true);
$city = get_user_meta($customer_id, 'billing_city', true);
$postcode = get_user_meta($customer_id, 'billing_postcode', true);
$country = get_user_meta($customer_id, 'billing_country', true);
$state = get_user_meta($customer_id, 'billing_state', true);

// Driver's license
$license_number = get_user_meta($customer_id, 'driving_license', true);
$license_expiry = get_user_meta($customer_id, 'license_expiry', true);

// Emergency contact
$emergency_name = get_user_meta($customer_id, 'emergency_contact_name', true);
$emergency_relationship = get_user_meta($customer_id, 'emergency_contact_relationship', true);
$emergency_phone = get_user_meta($customer_id, 'emergency_contact_phone', true);

// Success/error messages
$success_message = '';
$error_message = '';

// Check for form submission
if (isset($_GET['profile_updated']) && $_GET['profile_updated'] === 'true') {
    $success_message = __('Profile updated successfully.', 'ckl-car-rental');
}

do_action('woocommerce_account_profile_before', $customer_id);
?>

<!-- Page Header -->
<div class="ckl-page-header mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
        <?php esc_html_e('My Profile', 'ckl-car-rental'); ?>
    </h1>
    <p class="text-gray-600">
        <?php esc_html_e('Manage your personal information and preferences.', 'ckl-car-rental'); ?>
    </p>
</div>

<?php if ($success_message) : ?>
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        <?php echo esc_html($success_message); ?>
    </div>
<?php endif; ?>

<?php do_action('woocommerce_account_profile_content_before', $customer_id); ?>

<?php
/**
 * Profile form
 */
wc_get_template_part('template-parts/myaccount/profile-form', '', array(
    'first_name' => $first_name,
    'last_name' => $last_name,
    'phone' => $phone,
    'date_of_birth' => $date_of_birth,
    'address_1' => $address_1,
    'address_2' => $address_2,
    'city' => $city,
    'postcode' => $postcode,
    'country' => $country,
    'state' => $state,
    'license_number' => $license_number,
    'license_expiry' => $license_expiry,
    'emergency_name' => $emergency_name,
    'emergency_relationship' => $emergency_relationship,
    'emergency_phone' => $emergency_phone,
));
?>

<?php do_action('woocommerce_account_profile_content_after', $customer_id); ?>

<?php do_action('woocommerce_account_profile_after', $customer_id); ?>
