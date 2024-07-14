<?php

namespace Account_Genius\Compat\Astra;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Astra theme compatibility
 * 
 * @since 2.1.0
 * @version 2.1.0
 * @package MeuMouse.com
 */
class Compat_Astra {
    
    /**
     * Instance function
     * 
     * @since 2.1.0
     * @return void
     */
    public function __construct() {
        if ( function_exists('is_theme_active') && is_theme_active('Astra') ) {
            add_action( 'init', array( __CLASS__, 'compat_astra' ), 20 );
        }
    }


    /**
     * Astra compatibility in the init hook
     * 
     * @since 2.1.0
     * @return void
     */
    public static function compat_astra() {
        if ( ! class_exists('Astra_Woocommerce') || ! is_account_genius_account_page() ) {
			return;
		}

        $astra = \Astra_Woocommerce::get_instance();
    
        remove_action( 'wp_enqueue_scripts', array( $astra, 'add_scripts_styles' ) );
        remove_filter( 'woocommerce_enqueue_styles', array( $astra, 'woo_filter_style' ) );
    }
}

new Compat_Astra();