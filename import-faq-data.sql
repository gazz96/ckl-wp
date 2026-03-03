-- ================================================
-- CK Langkawi FAQ Import Script
-- ================================================
-- This script imports FAQ data directly into WordPress database
-- Run this in phpMyAdmin or via MySQL command line
--
-- IMPORTANT:
-- 1. Backup your database before running this!
-- 2. Replace 'wp_' with your table prefix if different
-- 3. Check the term_taxonomy_id values don't conflict
-- ================================================

-- Set variables for easier customization
SET @table_prefix = 'wp_';
SET @user_id = 1; -- Change this to the admin user ID

-- ================================================
-- STEP 1: Insert FAQ Categories
-- ================================================

-- Category 1: Langkawi Attractions
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(101, 'Langkawi Attractions', 'langkawi-attractions', 0);

INSERT INTO `wp_term_taxonomy` (`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(101, 'faq_category', '', 0, 10);

-- Category 2: Driving & Travel
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(102, 'Driving & Travel', 'driving-travel', 0);

INSERT INTO `wp_term_taxonomy` (`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(102, 'faq_category', '', 0, 9);

-- Category 3: Vehicle Rental
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(103, 'Vehicle Rental', 'vehicle-rental', 0);

INSERT INTO `wp_term_taxonomy` (`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(103, 'faq_category', '', 0, 5);

-- Category 4: Booking & Payment
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(104, 'Booking & Payment', 'booking-payment', 0);

INSERT INTO `wp_term_taxonomy` (`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(104, 'faq_category', '', 0, 3);

-- Category 5: Eligibility & Documents
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(105, 'Eligibility & Documents', 'eligibility-documents', 0);

INSERT INTO `wp_term_taxonomy` (`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(105, 'faq_category', '', 0, 3);

-- Category 6: Insurance & Liability
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(106, 'Insurance & Liability', 'insurance-liability', 0);

INSERT INTO `wp_term_taxonomy` (`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(106, 'faq_category', '', 0, 2);


-- ================================================
-- STEP 2: Insert FAQ Posts
-- ================================================

-- ============================================
-- Category: Langkawi Attractions (10 FAQs)
-- ============================================

-- FAQ 1
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1001, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Some must-visit attractions include Langkawi Sky Bridge, Langkawi Cable Car, Kilim Geoforest Park, Pantai Cenang, and Tanjung Rhu Beach.</p>', 'What are the top attractions to visit in Langkawi?', '', 'publish', 'closed', 'closed', '', 'what-are-the-top-attractions-to-visit-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 1, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1001, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1001, 101);

-- FAQ 2
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1002, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, the Sky Bridge is safe for all ages. However, some walking and stairs are involved, so we recommend wearing comfortable shoes.</p>', 'Is the Langkawi Sky Bridge safe for children and elderly visitors?', '', 'publish', 'closed', 'closed', '', 'is-the-langkawi-sky-bridge-safe-for-children-and-elderly-visitors', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 2, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1002, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1002, 101);

-- FAQ 3
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1003, @user_id, NOW(), UTC_TIMESTAMP(), '<p>The best time is during the dry season, from November to April, when the weather is sunny and ideal for sightseeing.</p>', 'What is the best time to visit Langkawi attractions?', '', 'publish', 'closed', 'closed', '', 'what-is-the-best-time-to-visit-langkawi-attractions', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 3, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1003, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1003, 101);

-- FAQ 4
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1004, @user_id, NOW(), UTC_TIMESTAMP(), '<p>You can reach Kilim Geoforest Park by car or motorbike, and boat tours start from the Kilim Jetty. Renting a car with CK Langkawi makes the trip more flexible.</p>', 'How do I get to Kilim Geoforest Park?', '', 'publish', 'closed', 'closed', '', 'how-do-i-get-to-kilim-geoforest-park', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 4, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1004, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1004, 101);

-- FAQ 5
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1005, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, most beaches like Pantai Cenang and Pantai Tengah are public and free to access.</p>', 'Are Langkawi beaches open to the public?', '', 'publish', 'closed', 'closed', '', 'are-langkawi-beaches-open-to-the-public', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 5, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1005, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1005, 101);

-- FAQ 6
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1006, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Absolutely. Langkawi is easy to navigate, and renting a car or motorbike gives you the freedom to explore at your own pace.</p>', 'Is it easy to drive around Langkawi to see the attractions?', '', 'publish', 'closed', 'closed', '', 'is-it-easy-to-drive-around-langkawi-to-see-the-attractions', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 6, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1006, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1006, 101);

-- FAQ 7
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1007, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, check out Telaga Tujuh (Seven Wells) Waterfall and Durian Perangin Waterfall for nature and hiking experiences.</p>', 'Are there any waterfalls worth visiting in Langkawi?', '', 'publish', 'closed', 'closed', '', 'are-there-any-waterfalls-worth-visiting-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 7, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1007, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1007, 101);

-- FAQ 8
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1008, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, you can visit Mahsuri\'s Tomb, Langkawi Craft Complex, and the Atma Alam Batik Village to explore local culture and history.</p>', 'Are there any cultural attractions in Langkawi?', '', 'publish', 'closed', 'closed', '', 'are-there-any-cultural-attractions-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 8, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1008, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1008, 101);

-- FAQ 9
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1009, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, both are located in the same area at Gunung Mat Cincang. A rental car makes it easy to plan your own schedule.</p>', 'Can I visit the Langkawi Cable Car and Sky Bridge in one trip?', '', 'publish', 'closed', 'closed', '', 'can-i-visit-the-langkawi-cable-car-and-sky-bridge-in-one-trip', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 9, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1009, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1009, 101);

-- FAQ 10
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1010, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Families love Underwater World Langkawi, Paradise 3D Museum, and Langkawi Wildlife Park, all easily reachable by car.</p>', 'What are some family-friendly attractions in Langkawi?', '', 'publish', 'closed', 'closed', '', 'what-are-some-family-friendly-attractions-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 10, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1010, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1010, 101);


-- ============================================
-- Category: Driving & Travel (9 FAQs)
-- ============================================

-- FAQ 11
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1011, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Roads in Langkawi are generally well-maintained and easy to drive on. Most attractions are connected by clear, paved roads with light traffic.</p>', 'What are the roads like in Langkawi?', '', 'publish', 'closed', 'closed', '', 'what-are-the-roads-like-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 1, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1011, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1011, 102);

-- FAQ 12
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1012, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, petrol stations are widely available in areas like Kuah, Pantai Cenang, and near the airport. Most are open daily and support self-service.</p>', 'Are petrol stations easily available in Langkawi?', '', 'publish', 'closed', 'closed', '', 'are-petrol-stations-easily-available-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 2, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1012, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1012, 102);

-- FAQ 13
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1013, @user_id, NOW(), UTC_TIMESTAMP(), '<p>You can drive in Langkawi with a valid international driving permit (IDP) or a license written in English.</p>', 'Do I need an international license to drive in Langkawi?', '', 'publish', 'closed', 'closed', '', 'do-i-need-an-international-license-to-drive-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 3, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1013, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1013, 102);

-- FAQ 14
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1014, @user_id, NOW(), UTC_TIMESTAMP(), '<p>CK Langkawi offers 2-seater motorbikes, 4- and 5-seater cars, 7- to 12-seater vans, and buses for larger groups or tours.</p>', 'What types of vehicles and seaters are available for rent?', '', 'publish', 'closed', 'closed', '', 'what-types-of-vehicles-and-seaters-are-available-for-rent', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 4, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1014, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1014, 102);

-- FAQ 15
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1015, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, parking is generally free and easy to find in most areas, including beaches, tourist attractions, and shopping centers.</p>', 'Is it easy to park in Langkawi?', '', 'publish', 'closed', 'closed', '', 'is-it-easy-to-park-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 5, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1015, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1015, 102);

-- FAQ 16
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1016, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, Langkawi is beginner-friendly with clear signage, slow-paced traffic, and low congestion. Just drive on the left side of the road.</p>', 'Are the roads safe for first-time drivers in Malaysia?', '', 'publish', 'closed', 'closed', '', 'are-the-roads-safe-for-first-time-drivers-in-malaysia', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 6, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1016, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1016, 102);

-- FAQ 17
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1017, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Absolutely. Google Maps and Waze work well in Langkawi with accurate routes and real-time traffic updates.</p>', 'Can I use Google Maps or Waze in Langkawi?', '', 'publish', 'closed', 'closed', '', 'can-i-use-google-maps-or-waze-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 7, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1017, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1017, 102);

-- FAQ 18
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1018, @user_id, NOW(), UTC_TIMESTAMP(), '<p>While not strictly enforced, child safety seats are strongly recommended for families. You can request one when booking your vehicle.</p>', 'Are child car seats required in Langkawi?', '', 'publish', 'closed', 'closed', '', 'are-child-car-seats-required-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 8, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1018, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1018, 102);

-- FAQ 19
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1019, @user_id, NOW(), UTC_TIMESTAMP(), '<p>In Langkawi (and all of Malaysia), vehicles drive on the left-hand side of the road.</p>', 'What side of the road do people drive on in Langkawi?', '', 'publish', 'closed', 'closed', '', 'what-side-of-the-road-do-people-drive-on-in-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 9, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1019, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1019, 102);


-- ============================================
-- Category: Vehicle Rental (5 FAQs)
-- ============================================

-- FAQ 20
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1020, @user_id, NOW(), UTC_TIMESTAMP(), '<p>CK Langkawi offers a wide range of vehicles including cars, motorbikes, vans, and buses to suit individual travelers, families, and large groups.</p>', 'What types of vehicles can I rent from CK Langkawi?', '', 'publish', 'closed', 'closed', '', 'what-types-of-vehicles-can-i-rent-from-ck-langkawi', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 1, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1020, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1020, 103);

-- FAQ 21
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1021, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, all our vehicles are available for both short- and long-term rental depending on your travel needs.</p>', 'Are your vehicles available for both short-term and long-term rental?', '', 'publish', 'closed', 'closed', '', 'are-your-vehicles-available-for-both-short-term-and-long-term-rental', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 2, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1021, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1021, 103);

-- FAQ 22
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1022, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Absolutely. We offer convenient airport and jetty pickup so your ride is ready when you arrive.</p>', 'Is it possible to get a vehicle with airport or jetty pickup?', '', 'publish', 'closed', 'closed', '', 'is-it-possible-to-get-a-vehicle-with-airport-or-jetty-pickup', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 3, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1022, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1022, 103);

-- FAQ 23
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1023, @user_id, NOW(), UTC_TIMESTAMP(), '<p>We provide spacious vans for small groups and buses for larger parties, tours, or corporate events.</p>', 'What kind of group transport options do you offer?', '', 'publish', 'closed', 'closed', '', 'what-kind-of-group-transport-options-do-you-offer', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 4, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1023, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1023, 103);

-- FAQ 24
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1024, @user_id, NOW(), UTC_TIMESTAMP(), '<p>CK Langkawi offers competitive rates, well-maintained vehicles, friendly service, and an easy booking process that makes your trip stress-free.</p>', 'Why should I rent from CK Langkawi instead of others?', '', 'publish', 'closed', 'closed', '', 'why-should-i-rent-from-ck-langkawi-instead-of-others', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 5, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1024, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1024, 103);


-- ============================================
-- Category: Booking & Payment (3 FAQs)
-- ============================================

-- FAQ 25
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1025, @user_id, NOW(), UTC_TIMESTAMP(), '<p>We recommend booking at least 2-3 days in advance during peak season (December-January) and school holidays. For off-peak periods, same-day bookings are often possible.</p>', 'How far in advance should I book?', '', 'publish', 'closed', 'closed', '', 'how-far-in-advance-should-i-book', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 1, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1025, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1025, 104);

-- FAQ 26
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1026, @user_id, NOW(), UTC_TIMESTAMP(), '<p>We accept cash, bank transfer, and online payments. Payment can be made upon vehicle pickup or in advance for confirmed bookings.</p>', 'What payment methods do you accept?', '', 'publish', 'closed', 'closed', '', 'what-payment-methods-do-you-accept', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 2, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1026, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1026, 104);

-- FAQ 27
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1027, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, a refundable security deposit is required. The amount varies depending on the vehicle type and will be returned upon vehicle return in good condition.</p>', 'Is a security deposit required?', '', 'publish', 'closed', 'closed', '', 'is-a-security-deposit-required', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 3, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1027, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1027, 104);


-- ============================================
-- Category: Eligibility & Documents (3 FAQs)
-- ============================================

-- FAQ 28
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1028, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Malaysian citizens need a valid driver\'s license (Malaysian Competent Driving License) and identity card (MyKad). The license must be valid for the rental period.</p>', 'What documents do Malaysian citizens need to rent a car?', '', 'publish', 'closed', 'closed', '', 'what-documents-do-malaysian-citizens-need-to-rent-a-car', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 1, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1028, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1028, 105);

-- FAQ 29
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1029, @user_id, NOW(), UTC_TIMESTAMP(), '<p>International visitors need a valid passport, original driver\'s license from home country, and an International Driving Permit (IDP) if the license is not in English.</p>', 'What documents do international visitors need to rent a car?', '', 'publish', 'closed', 'closed', '', 'what-documents-do-international-visitors-need-to-rent-a-car', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 2, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1029, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1029, 105);

-- FAQ 30
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1030, @user_id, NOW(), UTC_TIMESTAMP(), '<p>The minimum age to rent a car is 21 years old with a valid driving license. For certain vehicle categories, the minimum age may be 23 years.</p>', 'What is the minimum age to rent a car?', '', 'publish', 'closed', 'closed', '', 'what-is-the-minimum-age-to-rent-a-car', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 3, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1030, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1030, 105);


-- ============================================
-- Category: Insurance & Liability (2 FAQs)
-- ============================================

-- FAQ 31
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1031, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Basic insurance coverage is included, which covers damage to third party vehicles and property. Comprehensive coverage options are available for additional protection.</p>', 'What insurance is included in the rental price?', '', 'publish', 'closed', 'closed', '', 'what-insurance-is-included-in-the-rental-price', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 1, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1031, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1031, 106);

-- FAQ 32
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1032, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, we offer excess reduction options that can significantly lower your financial responsibility in case of an accident. Ask our team for details when booking.</p>', 'Can I reduce my financial liability (Excess)?', '', 'publish', 'closed', 'closed', '', 'can-i-reduce-my-financial-liability-excess', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 2, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(1032, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(1032, 106);


-- ================================================
-- COMPLETE!
-- ================================================

-- Display summary
SELECT '========================================' AS '';
SELECT 'IMPORT COMPLETE!' AS '';
SELECT '========================================' AS '';
SELECT CONCAT('Total FAQs imported: 32') AS '';
SELECT CONCAT('Categories created: 6') AS '';
SELECT '' AS '';
SELECT 'Next steps:' AS '';
SELECT '1. Check WordPress Admin → FAQs → All FAQs' AS '';
SELECT '2. Visit your FAQ page at /faq/' AS '';
SELECT '3. Verify categories and content' AS '';
SELECT '========================================' AS '';
