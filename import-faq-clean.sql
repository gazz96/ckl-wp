-- ================================================
-- CK Langkawi FAQ Import Script (COMPLETE)
-- ================================================
-- This script:
-- 1. DELETES all existing FAQ posts and categories
-- 2. INSERTS 6 correct FAQ categories
-- 3. INSERTS 17 FAQs with complete answers from JSON
--
-- RUN THIS IN phpMyAdmin or via MySQL command line
-- ================================================

-- Set variables
SET @table_prefix = 'wp_';
SET @user_id = 1;

-- ================================================
-- STEP 1: DELETE EXISTING FAQ DATA
-- ================================================

-- Delete all FAQ posts
DELETE FROM wp_postmeta WHERE post_id IN (
    SELECT ID FROM wp_posts WHERE post_type = 'faq'
);

DELETE FROM wp_term_relationships WHERE object_id IN (
    SELECT ID FROM wp_posts WHERE post_type = 'faq'
);

DELETE FROM wp_posts WHERE post_type = 'faq';

-- Delete all FAQ categories
DELETE FROM wp_term_taxonomy WHERE taxonomy = 'faq_category';
DELETE FROM wp_terms WHERE term_id IN (
    SELECT term_id FROM wp_term_taxonomy WHERE taxonomy = 'faq_category'
);

SELECT '✓ Cleaned up existing FAQ data' AS '';


-- ================================================
-- STEP 2: INSERT FAQ CATEGORIES
-- ================================================

-- Category 1: Eligibility & Documents
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(201, 'Eligibility & Documents', 'eligibility-documents', 0);

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(201, 201, 'faq_category', '', 0, 3);

-- Category 2: Booking & Payment
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(202, 'Booking & Payment', 'booking-payment', 0);

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(202, 202, 'faq_category', '', 0, 5);

-- Category 3: Insurance & Liability
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(203, 'Insurance & Liability', 'insurance-liability', 0);

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(203, 203, 'faq_category', '', 0, 2);

-- Category 4: During Rental
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(204, 'During Rental', 'during-rental', 0);

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(204, 204, 'faq_category', '', 0, 3);

-- Category 5: Pick-up & Return
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(205, 'Pick-up & Return', 'pick-up-return', 0);

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(205, 205, 'faq_category', '', 0, 2);

-- Category 6: Other Fees
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(206, 'Other Fees', 'other-fees', 0);

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(206, 206, 'faq_category', '', 0, 2);

SELECT '✓ Created 6 FAQ categories' AS '';


-- ================================================
-- STEP 3: INSERT FAQ POSTS
-- ================================================

-- ============================================
-- Category: Eligibility & Documents (3 FAQs)
-- ============================================

-- FAQ 1: Malaysian citizens documents
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2001, @user_id, NOW(), UTC_TIMESTAMP(), '<p>You need your original NRIC (MyKad) and a valid Original Physical Malaysian Driving License. Minimum age is usually 21 (or 23 for certain luxury/larger vehicles). Must be a full, not probationary, license.</p>', 'What documents do Malaysian citizens need to rent a car?', '', 'publish', 'closed', 'closed', '', 'what-documents-do-malaysian-citizens-need-to-rent-a-car', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 1, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2001, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2001, 201);

-- FAQ 2: International visitors documents
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2002, @user_id, NOW(), UTC_TIMESTAMP(), '<p>You need your original Passport, a valid Original Physical Foreign Driving License, and an International Driving Permit (IDP). An IDP is mandatory if your national license is not in English or Malay. Foreign licenses in English/Malay are generally valid for up to 3 months of use in Malaysia.</p>', 'What documents do international visitors need to rent a car?', '', 'publish', 'closed', 'closed', '', 'what-documents-do-international-visitors-need-to-rent-a-car', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 2, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2002, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2002, 201);

-- FAQ 3: Minimum age
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2003, @user_id, NOW(), UTC_TIMESTAMP(), '<p>The minimum age is 21 years old for most standard car categories. Age limits for luxury or larger MPVs/SUVs may be higher (e.g., 23 or 25).</p>', 'What is the minimum age to rent a car?', '', 'publish', 'closed', 'closed', '', 'what-is-the-minimum-age-to-rent-a-car', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 3, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2003, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2003, 201);


-- ============================================
-- Category: Booking & Payment (5 FAQs)
-- ============================================

-- FAQ 4: How far in advance
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2004, @user_id, NOW(), UTC_TIMESTAMP(), '<p>It is highly recommended to book at least 2-4 weeks in advance, especially during peak season (e.g., school holidays, major festivals, December to February). Availability and rates are better for early bookings. Last-minute bookings are subject to availability.</p>', 'How far in advance should I book?', '', 'publish', 'closed', 'closed', '', 'how-far-in-advance-should-i-book', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 4, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2004, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2004, 202);

-- FAQ 5: 2 adults 4 kids in 5 seater
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2005, @user_id, NOW(), UTC_TIMESTAMP(), '<p>No; for safety and legal reasons, our 5 seater vehicles have a maximum capacity of 5 occupants, as there are only enough seatbelts and designated space to securely accommodate that number.</p>', 'Can I fit 2 adults and 4 kids in a 5 seater vehicle?', '', 'publish', 'closed', 'closed', '', 'can-i-fit-2-adults-and-4-kids-in-a-5-seater-vehicle', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 5, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2005, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2005, 202);

-- FAQ 6: Book for 1 day
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2006, @user_id, NOW(), UTC_TIMESTAMP(), '<p>No, our minimum booking time is 48 hours.</p>', 'Can I book a car for 1 day?', '', 'publish', 'closed', 'closed', '', 'can-i-book-a-car-for-1-day', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 6, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2006, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2006, 202);

-- FAQ 7: Payment methods
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2007, @user_id, NOW(), UTC_TIMESTAMP(), '<p>We accept major credit/debit cards (Visa, MasterCard) and local Malaysian bank transfers (FPX). Cash payment is typically only accepted for security deposit. All online payments are processed in Malaysian Ringgit (MYR).</p>', 'What payment methods do you accept?', '', 'publish', 'closed', 'closed', '', 'what-payment-methods-do-you-accept', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 7, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2007, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2007, 202);

-- FAQ 8: Security deposit
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2008, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, a refundable Security Deposit is required to cover potential fines, minor damages, or refuelling charges. The amount typically ranges from RM100 to RM500 depending on the vehicle class. This is usually refunded within 3-7 working days after return.</p>', 'Is a security deposit required?', '', 'publish', 'closed', 'closed', '', 'is-a-security-deposit-required', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 8, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2008, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2008, 202);


-- ============================================
-- Category: Insurance & Liability (2 FAQs)
-- ============================================

-- FAQ 9: Insurance included
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2009, @user_id, NOW(), UTC_TIMESTAMP(), '<p>All our rental rates include Standard Vehicle Insurance (Third-Party Liability and Own Damage Coverage), subject to an Excess Clause. The standard excess liability is typically between RM2,000 and RM5,000 depending on the car group.</p>', 'What insurance is included in the rental price?', '', 'publish', 'closed', 'closed', '', 'what-insurance-is-included-in-the-rental-price', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 9, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2009, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2009, 203);

-- FAQ 10: Reduce excess
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2010, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, you can purchase an optional Collision Damage Waiver (CDW) at the counter to significantly reduce your excess liability (e.g., down to RM200-RM500). CDW is highly recommended for peace of mind, especially for international visitors.</p>', 'Can I reduce my financial liability (Excess)?', '', 'publish', 'closed', 'closed', '', 'can-i-reduce-my-financial-liability-excess', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 10, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2010, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2010, 203);


-- ============================================
-- Category: During Rental (3 FAQs)
-- ============================================

-- FAQ 11: Fuel policy
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2011, @user_id, NOW(), UTC_TIMESTAMP(), '<p>All vehicles are rented with a Half Tank of fuel and must be returned with a Half Tank. If the vehicle is returned with less than a half tank, a refuelling charge will be applied to the security deposit depending on the vehicle type.</p>', 'What is your fuel policy?', '', 'publish', 'closed', 'closed', '', 'what-is-your-fuel-policy', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 11, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2011, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2011, 204);

-- FAQ 12: Off island
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2012, @user_id, NOW(), UTC_TIMESTAMP(), '<p>No. Our rental agreements strictly prohibit taking the vehicle off Langkawi island (this includes taking it on the ferry to the mainland). Any violation of this term will immediately void the insurance coverage.</p>', 'Can I take the rental vehicle off Langkawi island?', '', 'publish', 'closed', 'closed', '', 'can-i-take-the-rental-vehicle-off-langkawi-island', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 12, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2012, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2012, 204);

-- FAQ 13: Accident/breakdown
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2013, @user_id, NOW(), UTC_TIMESTAMP(), '<p>1. Ensure safety first. 2. Call us immediately (our 24/7 hotline is provided on the rental agreement). 3. Lodge a police report within 24 hours for any accident involving another party or significant damage. Failure to file a police report within 24 hours may void your insurance/CDW coverage.</p>', 'What should I do in case of an accident or breakdown?', '', 'publish', 'closed', 'closed', '', 'what-should-i-do-in-case-of-an-accident-or-breakdown', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 13, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2013, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2013, 204);


-- ============================================
-- Category: Pick-up & Return (2 FAQs)
-- ============================================

-- FAQ 14: Delivery services
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2014, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes, we offer free or chargeable delivery and collection to key locations like Langkawi International Airport (LGK) and Kuah Jetty. Delivery/Collection outside of standard business hours (e.g., 9:00 AM - 6:00 PM) may incur an Out-of-Hours Surcharge.</p>', 'Do you offer delivery and collection services?', '', 'publish', 'closed', 'closed', '', 'do-you-offer-delivery-and-collection-services', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 14, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2014, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2014, 205);

-- FAQ 15: Late return
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2015, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Rentals are based on a 24-hour period. There is typically a 1-hour grace period. Returning the car after the grace period will result in an hourly charge, or a full day\'s rental charge if the delay exceeds a certain limit (e.g., 3-5 hours). Please notify us immediately if you anticipate being late to arrange an extension and ensure insurance coverage is maintained.</p>', 'What if I return the car late?', '', 'publish', 'closed', 'closed', '', 'what-if-i-return-the-car-late', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 15, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2015, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2015, 205);


-- ============================================
-- Category: Other Fees (2 FAQs)
-- ============================================

-- FAQ 16: Traffic fines
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2016, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes. The renter is responsible for all traffic fines, parking fees, and road summonses incurred during the rental period. An additional administration fee (e.g., RM50) will be charged per fine to process the payment to the authorities. Ensure you pay for parking (if required) and adhere to local speed limits (Federal Roads: 90km/h; Town/City: 60-80km/h).</p>', 'Are there charges for traffic fines or summonses?', '', 'publish', 'closed', 'closed', '', 'are-there-charges-for-traffic-fines-or-summonses', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 16, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2016, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2016, 206);

-- FAQ 17: Additional driver
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2017, @user_id, NOW(), UTC_TIMESTAMP(), '<p>Yes. An additional driver must be registered and meet all age/license requirements. Only registered drivers are covered by the vehicle insurance.</p>', 'Can I add an additional driver?', '', 'publish', 'closed', 'closed', '', 'can-i-add-an-additional-driver', '', '', NOW(), UTC_TIMESTAMP(), '', 0, '', 17, 'faq', '', 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
(2017, '_wp_page_template', 'default');

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`) VALUES
(2017, 206);

SELECT '✓ Inserted 17 FAQs with complete answers' AS '';


-- ================================================
-- IMPORT COMPLETE!
-- ================================================

SELECT '========================================' AS '';
SELECT 'IMPORT COMPLETE!' AS '';
SELECT '========================================' AS '';
SELECT CONCAT('Total FAQs imported: 17') AS '';
SELECT CONCAT('Categories created: 6') AS '';
SELECT '' AS '';
SELECT 'Breakdown by category:' AS '';
SELECT '- Eligibility & Documents: 3 FAQs' AS '';
SELECT '- Booking & Payment: 5 FAQs' AS '';
SELECT '- Insurance & Liability: 2 FAQs' AS '';
SELECT '- During Rental: 3 FAQs' AS '';
SELECT '- Pick-up & Return: 2 FAQs' AS '';
SELECT '- Other Fees: 2 FAQs' AS '';
SELECT '' AS '';
SELECT 'Next steps:' AS '';
SELECT '1. Check WordPress Admin → FAQs → All FAQs' AS '';
SELECT '2. Visit your FAQ page at /faq/' AS '';
SELECT '3. Test category filters and accordion' AS '';
SELECT '4. All data from JSON imported successfully!' AS '';
SELECT '========================================' AS '';
