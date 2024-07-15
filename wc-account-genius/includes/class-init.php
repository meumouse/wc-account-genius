<?php

namespace Account_Genius\Init;
use Account_Genius\License\License;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Init class plugin
 * 
 * @version 1.0.0
 * @version 2.1.2
 * @package MeuMouse.com
 */
class Init {

  /**
   * Construct function
   * 
   * @since 1.0.0
   * @version 2.1.2
   * @return void
   */
  public function __construct() {
    // set default options
    add_action( 'admin_init', array( $this, 'set_default_options' ) );

    // set default tabs
    add_action( 'admin_init', array( $this, 'set_default_tabs_options' ) );

    // load templates if is active
    if ( self::get_setting('replace_default_template_my_account') === 'yes' ) {
      add_filter( 'woocommerce_locate_template', array( $this, 'replace_my_account_templates' ), 10, 3 );
    }

    // plugin WooCommerce Subscriptions
    if ( class_exists('WC_Subscriptions') ) {
      add_filter( 'account_genius_myaccount_navigation_tabs', array( $this, 'add_subscription_tab' ), 10, 1 );
    }
  }


  /**
   * Set default options
   * 
   * @since 1.0.0
   * @version 1.8.5
   * @return array
   */
  public function set_default_data_options() {
    $options = array(
      'replace_default_template_my_account' => 'yes',
      'replace_default_notices' => 'yes',
      'primary_main_color' => '#008aff',
      'enable_icons' => 'yes',
      'display_background_slanted' => 'yes',
      'enable_upload_avatar' => 'yes',
    );

    return apply_filters( 'wc_set_default_options', $options );
  }


  /**
   * Gets the items from the array and inserts them into the option if it is empty,
   * or adds new items with default value to the option
   * 
   * @since 1.0.0
   * @return void
   */
  public function set_default_options() {
    $get_options = $this->set_default_data_options();
    $default_options = get_option('wc-account-genius-setting', array());

    if ( empty( $default_options ) ) {
        $options = $get_options;
        update_option('wc-account-genius-setting', $options);
    } else {
        $options = $default_options;

        foreach ( $get_options as $key => $value ) {
            if ( ! isset( $options[$key] ) ) {
                $options[$key] = $value;
            }
        }

        update_option('wc-account-genius-setting', $options);
    }
  }


  /**
   * Get default tabs
   * 
   * @since 1.8.0
   * @version 2.0.0
   * @return array $tabs
   */
  public function get_default_tabs() {
    $tabs = array(
      'orders' => array(
          'id' => 'woocommerce_myaccount_orders_endpoint',
          'endpoint' => get_option('woocommerce_myaccount_orders_endpoint'),
          'icon' => 'bx bx-cart',
          'label' => 'Meus pedidos',
          'native' => 'yes',
          'priority' => '1',
          'enabled' => 'yes',
          'class' => '',
          'hooks' => array(
            'woocommerce_before_account_orders',
            'woocommerce_before_account_orders_pagination',
            'woocommerce_after_account_orders',
          ),
      ),
      'downloads' => array(
          'id' => 'woocommerce_myaccount_downloads_endpoint',
          'endpoint' => get_option('woocommerce_myaccount_downloads_endpoint'),
          'icon' => 'bx bx-cloud-download',
          'label' => 'Downloads',
          'native' => 'yes',
          'priority' => '2',
          'enabled' => 'yes',
          'class' => '',
          'hooks' => array(
            'woocommerce_before_account_downloads',
            'woocommerce_before_available_downloads',
            'woocommerce_after_available_downloads',
            'woocommerce_after_account_downloads',
          ),
      ),
      'edit-address' => array(
          'id' => 'woocommerce_myaccount_edit_address_endpoint',
          'endpoint' => get_option('woocommerce_myaccount_edit_address_endpoint'),
          'icon' => 'bx bx-map',
          'label' => 'Meus endereços',
          'native' => 'yes',
          'priority' => '3',
          'enabled' => 'yes',
          'class' => '',
          'hooks' => array(
            'woocommerce_before_edit_account_address_form',
            'woocommerce_after_edit_account_address_form',
          ),
      ),
      'edit-account' => array(
          'id' => 'woocommerce_myaccount_edit_account_endpoint',
          'endpoint' => get_option('woocommerce_myaccount_edit_account_endpoint'),
          'icon' => 'bx bx-user',
          'label' => 'Detalhes da conta',
          'native' => 'yes',
          'priority' => '4',
          'enabled' => 'yes',
          'class' => '',
          'hooks' => array(
            'woocommerce_before_edit_account_form',
            'woocommerce_edit_account_form_start',
            'woocommerce_edit_account_form',
            'woocommerce_edit_account_form_end',
            'woocommerce_after_edit_account_form',
          ),
      ),
      'payment-methods' => array(
          'id' => 'woocommerce_myaccount_payment_methods_endpoint',
          'endpoint' => get_option('woocommerce_myaccount_payment_methods_endpoint'),
          'icon' => 'bx bx-credit-card-alt',
          'label' => 'Formas de pagamento',
          'native' => 'yes',
          'priority' => '5',
          'enabled' => 'yes',
          'class' => '',
          'hooks' => array(
            'woocommerce_before_account_payment_methods',
            'woocommerce_after_account_payment_methods',
          ),
      ),
      'customer-logout' => array(
          'id' => 'woocommerce_logout_endpoint',
          'endpoint' => get_option('woocommerce_logout_endpoint'),
          'icon' => 'bx bx-log-out',
          'label' => 'Sair',
          'native' => 'yes',
          'priority' => '6',
          'enabled' => 'yes',
          'class' => 'border-bottom-0',
      ),
    );

    return apply_filters( 'account_genius_myaccount_navigation_tabs', $tabs );
  }


  /**
   * Init default options tab
   * 
   * @since 1.8.0
   * @version 2.1.0
   * @return void
   */
  public function set_default_tabs_options() {
    $default_tabs = $this->get_default_tabs();
    $default_option_tabs = maybe_unserialize( get_option('account_genius_get_tabs_options', array()) );
    $tabs = array();

    // Check if the default tabs are already set
    if ( empty( $default_option_tabs ) ) {
        // If not set, initialize with default tabs
        foreach ( $default_tabs as $key => $value ) {
            $tabs[$key] = $value;
        }

        // Update the option with serialized tabs
        update_option('account_genius_get_tabs_options', maybe_serialize( $tabs ) );
    } else {
        // If default tabs are already set, update only missing ones
        foreach ( $default_tabs as $key => $value ) {
            if ( ! isset( $default_option_tabs[$key] ) ) {
                $default_option_tabs[$key] = $value;
            }
        }

        // Update the option with serialized updated tabs
        update_option('account_genius_get_tabs_options', maybe_serialize( $default_option_tabs ) );
    }
  }


  /**
	 * Checks if the option exists and returns the indicated array item
	 * 
	 * @since 1.0.0
   * @version 1.8.5
   * @param $key | Array key
   * @return mixed | string or false
	 */
  public static function get_setting( $key ) {
    $default_options = get_option('wc-account-genius-setting', array());

    // check if array key exists and return key
    if ( isset( $default_options[$key] ) ) {
        return $default_options[$key];
    }

    return false;
  }


  /**
   * Replace WooCommerce templates in my account page
   * 
   * @since 1.0.0
   * @version 1.8.5
   * @param string $template
   * @param string $template_name
   * @param string $template_path
   * @return string $template
   */
  public function replace_my_account_templates( $template, $template_name, $template_path ) {
    // replace downloads template
    if ( $template_name === 'myaccount/downloads.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/downloads.php';
    }

    // replace form edit account template
    if ( $template_name === 'myaccount/form-edit-account.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/form-edit-account.php';
    }

    // replace form edit address template
    if ( $template_name === 'myaccount/form-edit-address.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/form-edit-address.php';
    }

    // replace form login template
    if ( is_account_page() && $template_name === 'myaccount/form-login.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/form-login.php';
    }

    // replace form lost password template
    if ( $template_name === 'myaccount/form-lost-password.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/form-lost-password.php';
    }

    // replace form lost password confirmation template
    if ( $template_name === 'myaccount/lost-password-confirmation.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/lost-password-confirmation.php';
    }

    // replace my account template
    if ( $template_name === 'myaccount/my-account.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/my-account.php';
    }

    // replace my addresses template
    if ( $template_name === 'myaccount/my-address.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/my-address.php';
    }

    // replace navigation template
    if ( $template_name === 'myaccount/navigation.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/navigation.php';
    }

    // replace orders template
    if ( $template_name === 'myaccount/orders.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/orders.php';
    }

    // replace view order template
    if ( $template_name === 'myaccount/view-order.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/view-order.php';
    }

    // replace form tracking template
    if ( $template_name === 'order/form-tracking.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/order/form-tracking.php';
    }

    // replace order again template
    if ( $template_name === 'order/order-again.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/order/order-again.php';
    }

    // replace order details template
    if ( $template_name === 'order/order-details.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/order/order-details.php';
    }

    // replace order details customer template
    if ( $template_name === 'order/order-details-customer.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/order/order-details-customer.php';
    }

    // replace order details item template
    if ( $template_name === 'order/order-details-item.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/order/order-details-item.php';
    }

    // replace order downloads template
    if ( $template_name === 'order/order-downloads.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/order/order-downloads.php';
    }

    // replace tracking template
    if ( $template_name === 'order/tracking.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/order/tracking.php';
    }

    // replace my subscriptions template  
    if ( class_exists( 'WC_Subscriptions' ) && $template_name === 'myaccount/my-subscriptions.php' ) {
      $template = ACCOUNT_GENIUS_INC_DIR . 'templates/my-account/my-subscriptions.php';
    }

    if ( is_account_page() ) {
        // replace error notice template
        if ( self::get_setting('replace_default_notices') === 'yes' && $template_name === 'notices/error.php' ) {
          $template = ACCOUNT_GENIUS_INC_DIR . 'templates/notices/error.php';
        }

        // replace success notice template
        if ( self::get_setting('replace_default_notices') === 'yes' && $template_name === 'notices/success.php' ) {
          $template = ACCOUNT_GENIUS_INC_DIR . 'templates/notices/success.php';
        }

        // replace info notice template
        if ( self::get_setting('replace_default_notices') === 'yes' && $template_name === 'notices/notice.php' ) {
          $template = ACCOUNT_GENIUS_INC_DIR . 'templates/notices/notice.php';
        }
    }

    return $template;
  }


  /**
   * Add subscription tab
   * 
   * @since 2.1.0
   * @param array $tabs | Tabs array
   * @return array
   */
  public function add_subscription_tab( $tabs ) {
    if ( ! isset( $tabs['subscription'] ) ) {
      $new_tab = array(
        'subscription' => array(
          'id' => 'woocommerce_myaccount_subscriptions_endpoint',
          'endpoint' => get_option('woocommerce_myaccount_subscriptions_endpoint'),
          'icon' => 'bx bx-basket',
          'label' => 'Minhas assinaturas',
          'native' => 'no',
          'priority' => '7',
          'enabled' => 'yes',
          'class' => '',
          'hooks' => array(
            'woocommerce_my_subscriptions_after_subscription_id',
            'woocommerce_my_subscriptions_actions',
          ),
        ),
      );

      // merge new tab with existing tabs
      $tabs = array_merge( $tabs, $new_tab );
    }

    return $tabs;
  }
}

new Init();