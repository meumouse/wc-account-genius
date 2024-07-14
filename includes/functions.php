<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Get downloads count
 * 
 * @since 1.0.0
 * @return void
 */
if ( ! function_exists( 'wc_account_genius_downloads_count' ) ) {
    function wc_account_genius_downloads_count() {
        if ( WC()->customer ) {
            $downloads = WC()->customer->get_downloadable_products();

            echo count( $downloads );
        }
    }
}

/**
 * Get orders count
 * 
 * @since 1.0.0
 * @return void
 */
if ( ! function_exists( 'wc_account_genius_orders_count' ) ) {
    function wc_account_genius_orders_count() {
        $orders = wc_get_orders( apply_filters( 'wc_account_genius_orders_count_args', [
            'status' => [ 'pending', 'processing', 'completed', 'on-hold', 'failed' ],
            'customer' => get_current_user_id(),
            'return' => 'ids',
            'limit' => - 1,
            'paginate' => false,
        ] ) );

        echo count( $orders );
    }
}

/**
 * Display endpoint titles in My account page
 * 
 * @since 1.0.0
 * @return string
 */
if ( ! function_exists( 'wc_account_genius_endpoint_titles' ) ) {
    function wc_account_genius_endpoint_titles() {
        global $wp;

        $endpoints = wc_get_account_menu_items();
        $title = esc_html_x( 'Minha conta', 'front-end', 'wc-account-genius' );

        foreach ( $endpoints as $endpoint => $label ) {
            if ( isset( $wp->query_vars[ $endpoint ] ) ) {
                $title = $label;
            } elseif ( isset( $wp->query_vars['orders'] ) ) {
                $title = esc_html_x( 'Histórico de pedidos', 'front-end', 'wc-account-genius' );
                break;
            } elseif ( isset( $wp->query_vars['payment-methods'] ) ) {
                $title = esc_html_x( 'Métodos de pagamento', 'front-end', 'wc-account-genius' );
                break;
            } elseif ( isset( $wp->query_vars['edit-address'] ) ) {
                $title = esc_html_x( 'Meus endereços', 'front-end', 'wc-account-genius' );
                break;
            } elseif ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) {
                // Dashboard is not an endpoint, so needs a custom check.
                $title = esc_html_x( 'Painel', 'front-end', 'wc-account-genius' );
                break;
            }
        }

        echo apply_filters( 'wc_account_genius_endpoint_titles', $title );
    }
}


/**
 * Get posts per page in WooCommerce My account
 * 
 * @since 1.0.0
 * @return object
 */
if ( ! function_exists( 'wc_account_genius_my_account_orders_limit' ) ) {
    function wc_account_genius_my_account_orders_limit( $args ) {
        // Set the posts per page
        $args['posts_per_page'] = wc_account_genius_orders_limit();

        return $args;
    }
}

if ( ! function_exists( 'wc_account_genius_orders_limit' ) ) {
    function wc_account_genius_orders_limit() {
        return apply_filters( 'wc_account_genius_orders_limit', 5 );
    }
}

/**
 * Adds new WooCommerce account tab and corresponding rendering function
 * 
 * @since 1.8.0
 * @param string $endpoint | Endpoint for new tab
 * @param string $label | Label for new tab
 * @param string $content_callback | Callback function to render tab content
 * @param string $icon | (Optional) Icon for new tab
 * @return void
 */
function add_new_account_tab( $endpoint, $label, $content_callback, $icon = '' ) {
    add_filter('woocommerce_account_menu_items', function( $items ) use ( $endpoint, $label, $icon ) {
        $new_item = array(
            $endpoint => $label,
        );

        // display icon if different of empty
        if ( ! empty( $icon ) ) {
            $new_item[$endpoint] = '<i class="' . $icon . '"></i> ' . $label;
        }

        $items = array_slice( $items, 0, 1, true ) + $new_item + array_slice( $items, 1, count( $items ) - 1, true );

        return $items;
    });

    // add action to corresponding render function
    $key = 'woocommerce_account_' . $endpoint . '_endpoint';

    // render tab content
    add_action( $key, $content_callback );

    // add rewrite endpoint
    add_rewrite_endpoint( $endpoint, EP_ROOT | EP_PAGES );

    // add rewrite rules for new endpoint
    add_rewrite_rule(
        '^' . $endpoint . '/?$',
        'index.php?pagename=' . $endpoint,
        'top'
    );

    // update permalinks
    flush_rewrite_rules();
}

/**
 * Renders tab content dynamically
 * 
 * @since 1.8.0
 * @return void
 */
function render_new_tab_content() {
    if ( is_user_logged_in() ) {
        $account_tabs = get_option('account_genius_get_tabs_options', array());
        $account_tabs = maybe_unserialize( $account_tabs );
        $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        foreach ( $account_tabs as $index => $tab ) {
            // ignore if is native tab
            if ( isset( $tab['native'] ) && $tab['native'] === 'yes' ) {
                continue;
            }

            // check if current tab not is redirect link and is same endpoint with current URL
            if ( isset( $tab['link'] ) && $tab['link'] !== 'yes' && false !== strpos( $url, $tab['endpoint'] ) ) {
                // check content
                if ( preg_match( '/\[[a-zA-Z0-9_]+/', $tab['content'] ) ) {
                    // if is shortcode, then display
                    echo do_shortcode( $tab['content'] );
                    break;
                } else {
                    // If not a shortcode, display as HTML content
                    echo $tab['content'];
                    break;
                }
            }
        }
    }
}

if ( ! function_exists('is_account_genius_admin_panel') ) {
    /**
     * Check if is admin panel
     * 
     * @since 2.1.0
     * @return bool
     */
    function is_account_genius_admin_panel() {
        $current_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $admin_page_url = admin_url('admin.php?page=wc-account-genius');
        
        if ( $current_url === $admin_page_url || strpos( $current_url, 'admin.php?page=wc-account-genius' ) !== false ) {
            return true;
        }
        
        return false;
    }
}

if ( ! function_exists('is_account_genius_account_page') ) {
    /**
     * Check if current page is account page
     * 
     * @since 2.1.0
     * @return bool
     */
    function is_account_genius_account_page() {
        $current_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $account_page_url = wc_get_page_permalink('myaccount');
        
        if ( $current_url === $account_page_url || strpos( $current_url, $account_page_url ) !== false ) {
            return true;
        }
        
        return false;
    }
}

if ( ! function_exists('is_theme_active') ) {
    /**
     * Check if a specific theme is active.
     *
     * @since 2.1.0
     * @param string $theme_name | The name of the theme to check.
     * @return bool True if the theme is active, false otherwise.
     */
    function is_theme_active( $theme_name ) {
        $current_theme = wp_get_theme();

        return ( $current_theme->get('Name') === $theme_name );
    }
}

if ( ! function_exists('account_genius_boxicons_lib') ) {
    /**
     * Create array with Boxicons classes
     * 
     * @since 2.1.0
     * @link https://boxicons.com
     * @return array | Return 1634 icons
     */
    function account_genius_boxicons_lib() {
        // check if icon classes is in cache
        $icon_classes = get_transient('boxicons_icon_classes');

        // If not cached, read the CSS file and extract the classes
        if ( false === $icon_classes ) {
            $file_path = ACCOUNT_GENIUS_ASSETS . 'vendor/boxicons/css/boxicons.min.css';
            
            // Array to store the icon classes
            $icon_classes = array();

            // Read the contents of the CSS file
            $css_content = file_get_contents( $file_path );

            // Regex to find classes with :before pseudo-elements
            $pattern = '/\.([^\s:]+):before/';

            // Find all classes with :before pseudo-elements
            if ( preg_match_all( $pattern, $css_content, $matches ) ) {
                // Add the found classes to the array
                $icon_classes = $matches[1];
            }

            // Cache icon class array for 30 days
            set_transient( 'boxicons_icon_classes', $icon_classes, 30 * DAY_IN_SECONDS );
        }

        return $icon_classes;
    }
}