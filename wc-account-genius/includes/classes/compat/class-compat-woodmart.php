<?php

namespace Account_Genius\Compat\Woodmart;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Woodmart theme compatibility
 * 
 * @since 2.1.0
 * @version 2.1.0
 * @package MeuMouse.com
 */
class Compat_Woodmart {
    
    /**
     * Instance function
     * 
     * @since 2.1.0
     * @return void
     */
    public function __construct() {
        if ( function_exists('is_theme_active') && is_theme_active('Woodmart') ) {
            add_action( 'init', array( __CLASS__, 'compat_woodmart' ), 20 );
        }
    }

    /**
     * Woodmart compatibility in the init hook
     * 
     * @since 2.1.0
     * @return void
     */
    public static function compat_woodmart() {
        if ( is_account_genius_account_page() ) {
            remove_action( 'woocommerce_account_navigation', 'woodmart_before_my_account_navigation', 5 );
            remove_action( 'woocommerce_account_dashboard', 'woodmart_my_account_links', 10 );
        }
    }
}

new Compat_Woodmart();