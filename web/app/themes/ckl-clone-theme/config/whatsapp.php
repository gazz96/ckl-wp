<?php
/**
 * WhatsApp Configuration
 * 
 * Centralized settings for WhatsApp floating button
 * Modify these values to change phone number and default message
 *
 * @package CKL_Car_Rental
 */

return [
    // WhatsApp phone number (format: country code + number, no spaces or +)
    // Examples: 
    // Malaysia: 60194428040
    // Indonesia: 6281234567890
    // Singapore: 6512345678
    'phone' => '60194428040',
    
    // Default pre-filled message when user clicks WhatsApp button
    // This message will be URL encoded automatically
    'message' => 'Hi, I\'m interested in renting a car from CK Langkawi. Can you help me?',
    
    // Button tooltip text (shown on hover)
    'tooltip' => 'Chat with us!',
    
    // Button position (bottom-right, bottom-left, top-right, top-left)
    'position' => 'bottom-right',
    
    // Enable/disable WhatsApp button globally
    'enabled' => true,
];