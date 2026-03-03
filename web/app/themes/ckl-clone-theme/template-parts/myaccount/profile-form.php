<?php
/**
 * My Account Profile Form
 *
 * Profile edit form for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * Available variables:
 * @var string $first_name
 * @var string $last_name
 * @var string $phone
 * @var string $date_of_birth
 * @var string $address_1
 * @var string $address_2
 * @var string $city
 * @var string $postcode
 * @var string $country
 * @var string $state
 * @var string $license_number
 * @var string $license_expiry
 * @var string $emergency_name
 * @var string $emergency_relationship
 * @var string $emergency_phone
 */

defined('ABSPATH') || exit;

// Ensure all variables are set
$first_name = isset($first_name) ? $first_name : '';
$last_name = isset($last_name) ? $last_name : '';
$phone = isset($phone) ? $phone : '';
$date_of_birth = isset($date_of_birth) ? $date_of_birth : '';
$address_1 = isset($address_1) ? $address_1 : '';
$address_2 = isset($address_2) ? $address_2 : '';
$city = isset($city) ? $city : '';
$postcode = isset($postcode) ? $postcode : '';
$country = isset($country) ? $country : '';
$state = isset($state) ? $state : '';
$license_number = isset($license_number) ? $license_number : '';
$license_expiry = isset($license_expiry) ? $license_expiry : '';
$emergency_name = isset($emergency_name) ? $emergency_name : '';
$emergency_relationship = isset($emergency_relationship) ? $emergency_relationship : '';
$emergency_phone = isset($emergency_phone) ? $emergency_phone : '';

// Get current user
$current_user = wp_get_current_user();
?>

<form method="post" class="ckl-profile-form" id="ckl-profile-form">
    <?php wp_nonce_field('ckl_update_profile', 'ckl_profile_nonce'); ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <?php esc_html_e('Personal Information', 'ckl-car-rental'); ?>
            </h2>

            <div class="space-y-4">
                <!-- Email (Read-only) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Email Address', 'ckl-car-rental'); ?>
                    </label>
                    <input type="email" id="email" value="<?php echo esc_attr($current_user->user_email); ?>" disabled
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">
                        <?php esc_html_e('Contact support to change your email.', 'ckl-car-rental'); ?>
                    </p>
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('First Name', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($first_name); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Last Name', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($last_name); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Phone Number', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent"
                           placeholder="+60 12-345-6789">
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Date of Birth', 'ckl-car-rental'); ?>
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo esc_attr($date_of_birth); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <?php esc_html_e('Address', 'ckl-car-rental'); ?>
            </h2>

            <div class="space-y-4">
                <!-- Address Line 1 -->
                <div>
                    <label for="address_1" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Address Line 1', 'ckl-car-rental'); ?>
                    </label>
                    <input type="text" id="address_1" name="address_1" value="<?php echo esc_attr($address_1); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent"
                           placeholder="<?php esc_attr_e('Street address', 'ckl-car-rental'); ?>">
                </div>

                <!-- Address Line 2 -->
                <div>
                    <label for="address_2" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Address Line 2', 'ckl-car-rental'); ?>
                    </label>
                    <input type="text" id="address_2" name="address_2" value="<?php echo esc_attr($address_2); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent"
                           placeholder="<?php esc_attr_e('Apartment, suite, etc.', 'ckl-car-rental'); ?>">
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('City', 'ckl-car-rental'); ?>
                    </label>
                    <input type="text" id="city" name="city" value="<?php echo esc_attr($city); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                </div>

                <!-- Postcode -->
                <div>
                    <label for="postcode" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Postal Code', 'ckl-car-rental'); ?>
                    </label>
                    <input type="text" id="postcode" name="postcode" value="<?php echo esc_attr($postcode); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Country', 'ckl-car-rental'); ?>
                    </label>
                    <select id="country" name="country"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                        <?php
                        $countries = WC()->countries->get_countries();
                        foreach ($countries as $code => $label) : ?>
                            <option value="<?php echo esc_attr($code); ?>" <?php selected($country, $code); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('State', 'ckl-car-rental'); ?>
                    </label>
                    <input type="text" id="state" name="state" value="<?php echo esc_attr($state); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Driver's License -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <?php esc_html_e('Driver\'s License', 'ckl-car-rental'); ?>
            </h2>

            <div class="space-y-4">
                <!-- License Number -->
                <div>
                    <label for="license_number" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('License Number', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="license_number" name="license_number" value="<?php echo esc_attr($license_number); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent"
                           placeholder="<?php esc_attr_e('Enter your license number', 'ckl-car-rental'); ?>">
                </div>

                <!-- License Expiry -->
                <div>
                    <label for="license_expiry" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('License Expiry Date', 'ckl-car-rental'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="license_expiry" name="license_expiry" value="<?php echo esc_attr($license_expiry); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">
                        <?php esc_html_e('Your license must be valid for the duration of your rental.', 'ckl-car-rental'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <?php esc_html_e('Emergency Contact', 'ckl-car-rental'); ?>
            </h2>

            <div class="space-y-4">
                <!-- Contact Name -->
                <div>
                    <label for="emergency_name" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Contact Name', 'ckl-car-rental'); ?>
                    </label>
                    <input type="text" id="emergency_name" name="emergency_name" value="<?php echo esc_attr($emergency_name); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent"
                           placeholder="<?php esc_attr_e('Full name', 'ckl-car-rental'); ?>">
                </div>

                <!-- Relationship -->
                <div>
                    <label for="emergency_relationship" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Relationship', 'ckl-car-rental'); ?>
                    </label>
                    <select id="emergency_relationship" name="emergency_relationship"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent">
                        <option value=""><?php esc_html_e('Select relationship', 'ckl-car-rental'); ?></option>
                        <option value="spouse" <?php selected($emergency_relationship, 'spouse'); ?>>
                            <?php esc_html_e('Spouse', 'ckl-car-rental'); ?>
                        </option>
                        <option value="parent" <?php selected($emergency_relationship, 'parent'); ?>>
                            <?php esc_html_e('Parent', 'ckl-car-rental'); ?>
                        </option>
                        <option value="sibling" <?php selected($emergency_relationship, 'sibling'); ?>>
                            <?php esc_html_e('Sibling', 'ckl-car-rental'); ?>
                        </option>
                        <option value="friend" <?php selected($emergency_relationship, 'friend'); ?>>
                            <?php esc_html_e('Friend', 'ckl-car-rental'); ?>
                        </option>
                        <option value="other" <?php selected($emergency_relationship, 'other'); ?>>
                            <?php esc_html_e('Other', 'ckl-car-rental'); ?>
                        </option>
                    </select>
                </div>

                <!-- Contact Phone -->
                <div>
                    <label for="emergency_phone" class="block text-sm font-medium text-gray-700 mb-1">
                        <?php esc_html_e('Contact Phone', 'ckl-car-rental'); ?>
                    </label>
                    <input type="tel" id="emergency_phone" name="emergency_phone" value="<?php echo esc_attr($emergency_phone); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-transparent"
                           placeholder="+60 12-345-6789">
                </div>
            </div>
        </div>

    </div>

    <!-- Save Button -->
    <div class="mt-6 flex justify-end">
        <button type="submit" name="ckl_update_profile" value="1"
                class="px-8 py-3 bg-[#cc2e28] text-white rounded-lg font-semibold hover:bg-[#a8241f] transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <?php esc_html_e('Save Changes', 'ckl-car-rental'); ?>
        </button>
    </div>

</form>
