<?php
/**
 * FAQ Data Import Script
 *
 * This script imports FAQ data from cklangkawi.com
 * Run this script once, then delete it from the server.
 *
 * Usage: Visit /wp-content/themes/ckl-clone-theme/import-faq-data.php in browser
 * Or run via WP CLI: wp eval-file import-faq-data.php
 *
 * @package CKL_Car_Rental
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    // If not in WordPress context, load WordPress
    $wp_load_paths = array(
        __DIR__ . '/../../wp-load.php',
        __DIR__ . '/../../../wp-load.php',
    );

    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            break;
        }
    }
}

// Verify user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator to run this script.');
}

/**
 * Import FAQ data
 */
function ckl_import_faq_data() {
    // Define FAQ categories and their FAQs
    $faq_data = array(
        array(
            'category' => 'Langkawi Attractions',
            'faqs' => array(
                array(
                    'question' => 'What are the top attractions to visit in Langkawi?',
                    'answer' => 'Some must-visit attractions include Langkawi Sky Bridge, Langkawi Cable Car, Kilim Geoforest Park, Pantai Cenang, and Tanjung Rhu Beach.',
                    'order' => 1
                ),
                array(
                    'question' => 'Is the Langkawi Sky Bridge safe for children and elderly visitors?',
                    'answer' => 'Yes, the Sky Bridge is safe for all ages. However, some walking and stairs are involved, so we recommend wearing comfortable shoes.',
                    'order' => 2
                ),
                array(
                    'question' => 'What is the best time to visit Langkawi attractions?',
                    'answer' => 'The best time is during the dry season, from November to April, when the weather is sunny and ideal for sightseeing.',
                    'order' => 3
                ),
                array(
                    'question' => 'How do I get to Kilim Geoforest Park?',
                    'answer' => 'You can reach Kilim Geoforest Park by car or motorbike, and boat tours start from the Kilim Jetty. Renting a car with CK Langkawi makes the trip more flexible.',
                    'order' => 4
                ),
                array(
                    'question' => 'Are Langkawi beaches open to the public?',
                    'answer' => 'Yes, most beaches like Pantai Cenang and Pantai Tengah are public and free to access.',
                    'order' => 5
                ),
                array(
                    'question' => 'Is it easy to drive around Langkawi to see the attractions?',
                    'answer' => 'Absolutely. Langkawi is easy to navigate, and renting a car or motorbike gives you the freedom to explore at your own pace.',
                    'order' => 6
                ),
                array(
                    'question' => 'Are there any waterfalls worth visiting in Langkawi?',
                    'answer' => 'Yes, check out Telaga Tujuh (Seven Wells) Waterfall and Durian Perangin Waterfall for nature and hiking experiences.',
                    'order' => 7
                ),
                array(
                    'question' => 'Are there any cultural attractions in Langkawi?',
                    'answer' => 'Yes, you can visit Mahsuri\'s Tomb, Langkawi Craft Complex, and the Atma Alam Batik Village to explore local culture and history.',
                    'order' => 8
                ),
                array(
                    'question' => 'Can I visit the Langkawi Cable Car and Sky Bridge in one trip?',
                    'answer' => 'Yes, both are located in the same area at Gunung Mat Cincang. A rental car makes it easy to plan your own schedule.',
                    'order' => 9
                ),
                array(
                    'question' => 'What are some family-friendly attractions in Langkawi?',
                    'answer' => 'Families love Underwater World Langkawi, Paradise 3D Museum, and Langkawi Wildlife Park, all easily reachable by car.',
                    'order' => 10
                ),
            )
        ),
        array(
            'category' => 'Driving & Travel',
            'faqs' => array(
                array(
                    'question' => 'What are the roads like in Langkawi?',
                    'answer' => 'Roads in Langkawi are generally well-maintained and easy to drive on. Most attractions are connected by clear, paved roads with light traffic.',
                    'order' => 1
                ),
                array(
                    'question' => 'Are petrol stations easily available in Langkawi?',
                    'answer' => 'Yes, petrol stations are widely available in areas like Kuah, Pantai Cenang, and near the airport. Most are open daily and support self-service.',
                    'order' => 2
                ),
                array(
                    'question' => 'Do I need an international license to drive in Langkawi?',
                    'answer' => 'You can drive in Langkawi with a valid international driving permit (IDP) or a license written in English.',
                    'order' => 3
                ),
                array(
                    'question' => 'What types of vehicles and seaters are available for rent?',
                    'answer' => 'CK Langkawi offers 2-seater motorbikes, 4- and 5-seater cars, 7- to 12-seater vans, and buses for larger groups or tours.',
                    'order' => 4
                ),
                array(
                    'question' => 'Is it easy to park in Langkawi?',
                    'answer' => 'Yes, parking is generally free and easy to find in most areas, including beaches, tourist attractions, and shopping centers.',
                    'order' => 5
                ),
                array(
                    'question' => 'Are the roads safe for first-time drivers in Malaysia?',
                    'answer' => 'Yes, Langkawi is beginner-friendly with clear signage, slow-paced traffic, and low congestion. Just drive on the left side of the road.',
                    'order' => 6
                ),
                array(
                    'question' => 'Can I use Google Maps or Waze in Langkawi?',
                    'answer' => 'Absolutely. Google Maps and Waze work well in Langkawi with accurate routes and real-time traffic updates.',
                    'order' => 7
                ),
                array(
                    'question' => 'Are child car seats required in Langkawi?',
                    'answer' => 'While not strictly enforced, child safety seats are strongly recommended for families. You can request one when booking your vehicle.',
                    'order' => 8
                ),
                array(
                    'question' => 'What side of the road do people drive on in Langkawi?',
                    'answer' => 'In Langkawi (and all of Malaysia), vehicles drive on the left-hand side of the road.',
                    'order' => 9
                ),
            )
        ),
        array(
            'category' => 'Vehicle Rental',
            'faqs' => array(
                array(
                    'question' => 'What types of vehicles can I rent from CK Langkawi?',
                    'answer' => 'CK Langkawi offers a wide range of vehicles including cars, motorbikes, vans, and buses to suit individual travelers, families, and large groups.',
                    'order' => 1
                ),
                array(
                    'question' => 'Are your vehicles available for both short-term and long-term rental?',
                    'answer' => 'Yes, all our vehicles are available for both short- and long-term rental depending on your travel needs.',
                    'order' => 2
                ),
                array(
                    'question' => 'Is it possible to get a vehicle with airport or jetty pickup?',
                    'answer' => 'Absolutely. We offer convenient airport and jetty pickup so your ride is ready when you arrive.',
                    'order' => 3
                ),
                array(
                    'question' => 'What kind of group transport options do you offer?',
                    'answer' => 'We provide spacious vans for small groups and buses for larger parties, tours, or corporate events.',
                    'order' => 4
                ),
                array(
                    'question' => 'Why should I rent from CK Langkawi instead of others?',
                    'answer' => 'CK Langkawi offers competitive rates, well-maintained vehicles, friendly service, and an easy booking process that makes your trip stress-free.',
                    'order' => 5
                ),
            )
        ),
        array(
            'category' => 'Booking & Payment',
            'faqs' => array(
                array(
                    'question' => 'How far in advance should I book?',
                    'answer' => 'We recommend booking at least 2-3 days in advance during peak season (December-January) and school holidays. For off-peak periods, same-day bookings are often possible.',
                    'order' => 1
                ),
                array(
                    'question' => 'What payment methods do you accept?',
                    'answer' => 'We accept cash, bank transfer, and online payments. Payment can be made upon vehicle pickup or in advance for confirmed bookings.',
                    'order' => 2
                ),
                array(
                    'question' => 'Is a security deposit required?',
                    'answer' => 'Yes, a refundable security deposit is required. The amount varies depending on the vehicle type and will be returned upon vehicle return in good condition.',
                    'order' => 3
                ),
            )
        ),
        array(
            'category' => 'Eligibility & Documents',
            'faqs' => array(
                array(
                    'question' => 'What documents do Malaysian citizens need to rent a car?',
                    'answer' => 'Malaysian citizens need a valid driver\'s license (Malaysian Competent Driving License) and identity card (MyKad). The license must be valid for the rental period.',
                    'order' => 1
                ),
                array(
                    'question' => 'What documents do international visitors need to rent a car?',
                    'answer' => 'International visitors need a valid passport, original driver\'s license from home country, and an International Driving Permit (IDP) if the license is not in English.',
                    'order' => 2
                ),
                array(
                    'question' => 'What is the minimum age to rent a car?',
                    'answer' => 'The minimum age to rent a car is 21 years old with a valid driving license. For certain vehicle categories, the minimum age may be 23 years.',
                    'order' => 3
                ),
            )
        ),
        array(
            'category' => 'Insurance & Liability',
            'faqs' => array(
                array(
                    'question' => 'What insurance is included in the rental price?',
                    'answer' => 'Basic insurance coverage is included, which covers damage to third party vehicles and property. Comprehensive coverage options are available for additional protection.',
                    'order' => 1
                ),
                array(
                    'question' => 'Can I reduce my financial liability (Excess)?',
                    'answer' => 'Yes, we offer excess reduction options that can significantly lower your financial responsibility in case of an accident. Ask our team for details when booking.',
                    'order' => 2
                ),
            )
        ),
    );

    $imported_count = 0;
    $category_count = 0;

    echo '<h1>CK Langkawi FAQ Import</h1>';
    echo '<style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 40px; line-height: 1.6; }
        h1 { color: #22c55e; border-bottom: 3px solid #22c55e; padding-bottom: 10px; }
        h2 { color: #2563eb; margin-top: 30px; }
        .success { background: #dcfce7; border-left: 4px solid #22c55e; padding: 15px; margin: 10px 0; }
        .error { background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin: 10px 0; }
        .info { background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 10px 0; }
        ul { list-style: none; padding: 0; }
        li { background: #f8fafc; padding: 10px; margin: 5px 0; border-left: 3px solid #e2e8f0; }
        .done { border-left-color: #22c55e; }
    </style>';

    echo '<div class="info"><strong>Starting import process...</strong></div>';

    // Loop through each category and its FAQs
    foreach ($faq_data as $category_data) {
        $category_name = $category_data['category'];

        // Check if category exists
        $category = get_term_by('name', $category_name, 'faq_category');

        // Create category if it doesn't exist
        if (!$category || is_wp_error($category)) {
            $result = wp_insert_term($category_name, 'faq_category');
            if (is_wp_error($result)) {
                echo '<div class="error"><strong>Error:</strong> Failed to create category "' . esc_html($category_name) . '": ' . $result->get_error_message() . '</div>';
                continue;
            }
            $category_id = $result['term_id'];
            $category_count++;
            echo '<div class="success">✓ Created new category: <strong>' . esc_html($category_name) . '</strong></div>';
        } else {
            $category_id = $category->term_id;
            echo '<div class="success">✓ Using existing category: <strong>' . esc_html($category_name) . '</strong></div>';
        }

        echo '<h2>Importing FAQs for: ' . esc_html($category_name) . '</h2>';
        echo '<ul>';

        // Import FAQs for this category
        foreach ($category_data['faqs'] as $faq) {
            // Check if FAQ with same title already exists
            $existing = get_page_by_title($faq['question'], OBJECT, 'faq');

            if ($existing) {
                // Update existing FAQ
                $post_data = array(
                    'ID' => $existing->ID,
                    'post_content' => $faq['answer'],
                    'menu_order' => $faq['order'],
                );

                $result = wp_update_post($post_data);

                if ($result && !is_wp_error($result)) {
                    // Set category
                    wp_set_object_terms($result, $category_id, 'faq_category', false);
                    $imported_count++;
                    echo '<li class="done">✓ Updated: ' . esc_html($faq['question']) . '</li>';
                } else {
                    echo '<li>✗ Failed to update: ' . esc_html($faq['question']) . '</li>';
                }
            } else {
                // Create new FAQ
                $post_data = array(
                    'post_title' => $faq['question'],
                    'post_content' => $faq['answer'],
                    'post_type' => 'faq',
                    'post_status' => 'publish',
                    'menu_order' => $faq['order'],
                );

                $post_id = wp_insert_post($post_data);

                if ($post_id && !is_wp_error($post_id)) {
                    // Set category
                    wp_set_object_terms($post_id, $category_id, 'faq_category', false);
                    $imported_count++;
                    echo '<li class="done">✓ Imported: ' . esc_html($faq['question']) . '</li>';
                } else {
                    echo '<li>✗ Failed to import: ' . esc_html($faq['question']) . '</li>';
                }
            }
        }

        echo '</ul>';
    }

    echo '<div class="success" style="margin-top: 30px;"><strong>Import Complete!</strong></div>';
    echo '<div class="info">';
    echo '<strong>Summary:</strong><br>';
    echo '- Total FAQs processed: ' . $imported_count . '<br>';
    echo '- New categories created: ' . $category_count . '<br>';
    echo '- <a href="' . admin_url('edit.php?post_type=faq') . '">View All FAQs →</a><br>';
    echo '- <a href="' . home_url('/faq/') . '">View FAQ Page →</a>';
    echo '</div>';

    echo '<div class="error" style="margin-top: 20px;"><strong>Security Notice:</strong> Please delete this file from your server after successful import!</div>';
}

// Run the import
ckl_import_faq_data();
