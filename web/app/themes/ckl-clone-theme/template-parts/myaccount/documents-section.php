<?php
/**
 * My Account Documents Section
 *
 * Displays required documents, downloads, and upload form
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var string $license_url URL to uploaded license
 * @var int $license_document_id License attachment ID
 * @var string $id_url URL to uploaded ID
 * @var int $id_document_id ID attachment ID
 */

defined('ABSPATH') || exit;

if (!isset($license_url)) {
    $license_url = '';
}
if (!isset($license_document_id)) {
    $license_document_id = 0;
}
if (!isset($id_url)) {
    $id_url = '';
}
if (!isset($id_document_id)) {
    $id_document_id = 0;
}
?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Required Documents -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <?php esc_html_e('Required Documents', 'ckl-car-rental'); ?>
        </h2>

        <div class="space-y-4">
            <!-- Driver's License -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">
                            <?php esc_html_e('Valid Driver\'s License', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <?php esc_html_e('Must be valid for the duration of your rental.', 'ckl-car-rental'); ?>
                        </p>
                        <?php if ($license_url) : ?>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <?php esc_html_e('Uploaded', 'ckl-car-rental'); ?>
                                </span>
                                <a href="<?php echo esc_url($license_url); ?>" target="_blank" class="text-sm text-[#cc2e28] hover:underline">
                                    <?php esc_html_e('View', 'ckl-car-rental'); ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <p class="mt-2 text-sm text-yellow-600">
                                <?php esc_html_e('Not uploaded yet', 'ckl-car-rental'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ID/Passport -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">
                            <?php esc_html_e('ID Card or Passport', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <?php esc_html_e('For identity verification.', 'ckl-car-rental'); ?>
                        </p>
                        <?php if ($id_url) : ?>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <?php esc_html_e('Uploaded', 'ckl-car-rental'); ?>
                                </span>
                                <a href="<?php echo esc_url($id_url); ?>" target="_blank" class="text-sm text-[#cc2e28] hover:underline">
                                    <?php esc_html_e('View', 'ckl-car-rental'); ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <p class="mt-2 text-sm text-yellow-600">
                                <?php esc_html_e('Not uploaded yet', 'ckl-car-rental'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Credit Card -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">
                            <?php esc_html_e('Credit Card', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <?php esc_html_e('Required for security deposit.', 'ckl-car-rental'); ?>
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            <?php esc_html_e('Bring your credit card when picking up the vehicle.', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Documents -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            <?php esc_html_e('Upload Documents', 'ckl-car-rental'); ?>
        </h2>

        <form method="post" enctype="multipart/form-data" class="space-y-6" id="ckl-document-upload-form">
            <?php wp_nonce_field('ckl_upload_document', 'ckl_document_nonce'); ?>

            <!-- License Upload -->
            <div>
                <label for="license_upload" class="block text-sm font-medium text-gray-700 mb-2">
                    <?php esc_html_e('Driver\'s License', 'ckl-car-rental'); ?>
                </label>
                <div class="flex items-center gap-4">
                    <input type="file" id="license_upload" name="license_upload" accept="image/*,.pdf"
                           class="flex-1 block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-[#cc2e28] file:text-white
                                  hover:file:bg-[#a8241f]">
                    <input type="hidden" name="document_type" value="license">
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    <?php esc_html_e('Accepted formats: JPG, PNG, PDF (Max 5MB)', 'ckl-car-rental'); ?>
                </p>
                <?php if ($license_url) : ?>
                    <p class="text-sm text-green-600 mt-2">
                        <?php esc_html_e('Document uploaded successfully.', 'ckl-car-rental'); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- ID Upload -->
            <div>
                <label for="id_upload" class="block text-sm font-medium text-gray-700 mb-2">
                    <?php esc_html_e('ID Card / Passport', 'ckl-car-rental'); ?>
                </label>
                <div class="flex items-center gap-4">
                    <input type="file" id="id_upload" name="id_upload" accept="image/*,.pdf"
                           class="flex-1 block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-[#cc2e28] file:text-white
                                  hover:file:bg-[#a8241f]">
                    <input type="hidden" name="document_type" value="id">
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    <?php esc_html_e('Accepted formats: JPG, PNG, PDF (Max 5MB)', 'ckl-car-rental'); ?>
                </p>
                <?php if ($id_url) : ?>
                    <p class="text-sm text-green-600 mt-2">
                        <?php esc_html_e('Document uploaded successfully.', 'ckl-car-rental'); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Upload Button -->
            <button type="submit" name="ckl_upload_document" value="1"
                    class="w-full px-6 py-3 bg-[#cc2e28] text-white rounded-lg font-semibold hover:bg-[#a8241f] transition-colors">
                <?php esc_html_e('Upload Documents', 'ckl-car-rental'); ?>
            </button>

        </form>
    </div>

</div>

<!-- Downloadable Documents -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <?php esc_html_e('Downloadable Documents', 'ckl-car-rental'); ?>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Rental Terms -->
        <a href="#" class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-[#cc2e28] hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900">
                    <?php esc_html_e('Rental Terms & Conditions', 'ckl-car-rental'); ?>
                </h3>
                <p class="text-sm text-gray-600">
                    <?php esc_html_e('PDF', 'ckl-car-rental'); ?>
                </p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
        </a>

        <!-- Insurance Policy -->
        <a href="#" class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-[#cc2e28] hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900">
                    <?php esc_html_e('Insurance Policy Details', 'ckl-car-rental'); ?>
                </h3>
                <p class="text-sm text-gray-600">
                    <?php esc_html_e('PDF', 'ckl-car-rental'); ?>
                </p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
        </a>

        <!-- Fuel Policy -->
        <a href="#" class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-[#cc2e28] hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900">
                    <?php esc_html_e('Fuel Policy', 'ckl-car-rental'); ?>
                </h3>
                <p class="text-sm text-gray-600">
                    <?php esc_html_e('PDF', 'ckl-car-rental'); ?>
                </p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
        </a>

        <!-- Traffic Rules -->
        <a href="#" class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-[#cc2e28] hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900">
                    <?php esc_html_e('Traffic Rules in Langkawi', 'ckl-car-rental'); ?>
                </h3>
                <p class="text-sm text-gray-600">
                    <?php esc_html_e('PDF', 'ckl-car-rental'); ?>
                </p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
        </a>
    </div>
</div>
