<?php
/**
 * Contact Info Cards Template Part
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="space-y-4">
    <!-- Phone Card -->
    <a href="tel:+60123456789"
       class="block bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer group"
       aria-label="Call us at +60 12-345 6789">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="font-bold text-gray-900"><?php _e('Phone', 'ckl-car-rental'); ?></h3>
                <p class="text-blue-600 group-hover:text-blue-700">+60 12-345 6789</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
    </a>

    <!-- Email Card -->
    <a href="mailto:contact@cklangkawi.com"
       class="block bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer group"
       aria-label="Send email to contact@cklangkawi.com">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="font-bold text-gray-900"><?php _e('Email', 'ckl-car-rental'); ?></h3>
                <p class="text-green-600 group-hover:text-green-700">contact@cklangkawi.com</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
    </a>

    <!-- Address Card -->
    <a href="https://maps.google.com/?q=Masjid+Al-Aman+Yooi+Langkawi"
       target="_blank"
       rel="noopener noreferrer"
       class="block bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer group"
       aria-label="View our location on Google Maps">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="font-bold text-gray-900"><?php _e('Address', 'ckl-car-rental'); ?></h3>
                <p class="text-gray-600 text-sm group-hover:text-purple-600">
                    <?php _e('Lot Kedai No.3, Masjid Al-Aman Yooi', 'ckl-car-rental'); ?>
                </p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
    </a>

    <!-- Business Hours Card -->
    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-all duration-300">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="font-bold text-gray-900"><?php _e('Business Hours', 'ckl-car-rental'); ?></h3>
                <p class="text-gray-600 text-sm"><?php _e('Daily: 8:00 AM - 10:00 PM', 'ckl-car-rental'); ?></p>
                <p class="text-gray-500 text-xs mt-1"><?php _e('24/7 Emergency Support Available', 'ckl-car-rental'); ?></p>
            </div>
        </div>
    </div>
</div>
