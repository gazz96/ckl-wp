<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="woo_st-about-page" class="woo_st-page wrap nosubsub">
    <div class="col xs-text-center">
        <h1 class="page-title"><?php esc_html_e( 'Getting Help', 'woocommerce-store-toolkit' ); ?></h1>
        <p><?php esc_html_e( 'We\'re here to help you get the most out of your WooCommerce store.', 'woocommerce-store-toolkit' ); ?></p>
    </div>

    <div class="help-container">
        <div class="help-card">
            <div class="card-title">
                <h3><?php esc_html_e( 'Knowledge Base', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'Access our self-service help documentation via the Knowledge Base. You\'ll find answers and solutions for a wide range of well know situations . You\'ll also find a Getting Started guide here for the plugin.', 'woocommerce-store-toolkit' ); ?></p>
                <a target="_blank" href="<?php echo esc_url( 'https://visser.com.au/support/?utm_source=store-toolkit&utm_medium=helppage&utm_campaign=helppageopenkbbutton' ); ?>" class="button button-primary button-large"><?php esc_html_e( 'Open Knowledge Base', 'woocommerce-store-toolkit' ); ?></a>
            </div>
        </div> 
        <div class="help-card">
            <div class="card-title">
                <h3><?php esc_html_e( 'Free Version WordPress.org Help Forums', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'Our support staff regularly check and help our free users at the official plugin WordPress.org help forums. Submit a post there with your question and we\'ll get back to you as soon as possible.', 'woocommerce-store-toolkit' ); ?></p>
                <a target="_blank" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/woocommerce-store-toolkit/' ); ?>" class="button button-primary button-large"><?php esc_html_e( 'Visit WordPress.org Forums', 'woocommerce-store-toolkit' ); ?></a>
            </div>
        </div>
    </div>
    
    <div class="help-container">
        <iframe src="https://visser.com.au/in-app-optin/?utm_source=store-toolkit&utm_medium=helppage&utm_campaign=helppageinappoptin" width="1200" height="500" frameborder="0"></iframe>
    </div>
</div>

<style>
    .help-container {
        max-width: 1200px;
        margin: 20px 0 30px 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 10px;
    }

    .help-card {
        background-color: white;
        box-shadow: 1px 2px 5px rgba(0,0,0,0.15);
        padding: 20px;
        border-radius: 6px;
    }
</style>
