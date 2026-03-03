<?php
/**
 * Email Header
 *
 * Override WooCommerce email header with custom branding.
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get email styling
$email_bg_color = get_option('woocommerce_email_background_color');
$email_body_bg = get_option('woocommerce_email_body_background_color');
$email_text_color = get_option('woocommerce_email_text_color');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name'); ?></title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Helvetica, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: <?php echo esc_attr($email_text_color); ?>;
            background-color: <?php echo esc_attr($email_bg_color); ?>;
        }
        #wrapper {
            background-color: <?php echo esc_attr($email_body_bg); ?>;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        #header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 30px;
        }
        #header h1 {
            margin: 0;
            color: #1f2937;
            font-size: 24px;
            font-weight: bold;
        }
        #content {
            padding: 20px 0;
        }
        #footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            margin-top: 30px;
            font-size: 14px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        h2 {
            color: #1f2937;
            font-size: 20px;
            margin-top: 0;
        }
        a {
            color: #2563eb;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <?php if (file_exists(get_template_directory() . '/assets/images/cklangkawi_Transparent.png')) : ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/cklangkawi_Transparent.png" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" style="max-height: 80px;">
            <?php else : ?>
                <h1><?php echo esc_html(get_bloginfo('name')); ?></h1>
            <?php endif; ?>
        </div>
        <div id="content">
