<?php

namespace Account_Genius\Core;
use Account_Genius\Init\Init;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Frontend actions class
 * 
 * @since 2.0.0
 * @package MeuMouse.com
 */
class Core {

    /**
     * Construct function
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct() {
        // Add action to render account tabs
        add_action( 'init', array( $this, 'render_account_tabs' ) );

        // display slanted background on account page
        add_action( 'wp_head', array( $this, 'wc_account_genius_slanted_bg' ) );

        // order item name in WooCommerce orders
        add_filter( 'woocommerce_order_item_name', array( $this, 'wc_account_genius_order_item_name' ), 10, 2 );
    }


    /**
     * Renders account tabs and corresponding content
     * 
     * @since 1.8.0
     * @version 2.0.0
     * @return void
     */
    public function render_account_tabs() {
        $account_tabs = get_option('account_genius_get_tabs_options', array());
        $account_tabs = maybe_unserialize( $account_tabs );

        foreach ( $account_tabs as $index => $tab ) {
            if ( isset( $tab['native'] ) && $tab['native'] === 'no' ) {
                add_new_account_tab( $tab['endpoint'], $tab['label'], 'render_new_tab_content', $tab['icon'], );
            }
        }
    }


    /**
     * Display slanted background in My account page
     * 
     * @since 1.0.0
     * @version 2.0.0
     * @return void
     */
    public function wc_account_genius_slanted_bg() {
        if ( is_account_page() && Init::get_setting('display_background_slanted') === 'yes' ) {
            $css = '.wc-account-genius-my-account::before,
                    .wc-account-genius-form-login::before,
                    .wc-account-genius-form-last-password::before {
                content: "";
                position: absolute;
                width: 100%;
                height: 50vh;
                top: 0;
                right: 0;
                z-index: -1;
                background-color: #008aff;
            }';

            $css .= '.wc-account-genius-my-account::after,
                    .wc-account-genius-form-login::after,
                    .wc-account-genius-form-last-password::after {
                content: "";
                position: absolute;
                width: 100%;
                height: 20vh;
                top: 40vh;
                right: 0;
                z-index: -1;
                background-color: #008aff;
                transform: skewY(-8deg);
                -webkit-transform: skewY(-8deg);
            }';

            $css .= '@media screen and (min-width: 992px) {
                    .wc-account-genius-my-account::before,
                    .wc-account-genius-form-login::before,
                    .wc-account-genius-form-last-password::before {
                        height: 300px;
                    }

                .wc-account-genius-my-account::after,
                .wc-account-genius-form-login::after,
                .wc-account-genius-form-last-password::after {
                    height: 350px;
                    top: 20vh;
                    transform: skewY(-5deg);
                    -webkit-transform: skewY(-5deg);
                }
            }';

            ?>
            <style type="text/css">
                <?php echo $css; ?>
            </style> <?php
        }
    }


    /**
     * Order item name in WooCommerce orders
     * 
     * @since 1.0.0
     * @version 2.0.0
     * @param string $name | Product name
     * @param object|array $item | Product item
     * @return string
     */
    public function wc_account_genius_order_item_name( $name, $item ){
        $variation_id = $item['variation_id'];

        if ( $variation_id > 0 ) {
            $product_id = $item['product_id'];
            $_product = wc_get_product( $product_id );
            $product_name = $_product->get_title();
            $_name = $product_name;
            $variation_name = str_replace( $product_name . ' -', '', $item->get_name() );
            $_name .= '<span class="text-muted d-block mt-1 fw-normal">'. $variation_name .'</span>';
            $updated_name = str_replace( $item->get_name(), $_name, $name );
            $name = $updated_name;
        }
        
        return $name;
    }
}

new Core();