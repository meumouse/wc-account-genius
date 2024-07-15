<?php

/**
 * Plugin Name: 			Account Genius para WooCommerce
 * Description: 			Extensão que altera o modelo de página minha conta padrão do WooCommerce por um modelo profissional e responsivo.
 * Plugin URI: 				https://meumouse.com/plugins/account-genius/
 * Author: 					MeuMouse.com
 * Author URI: 				https://meumouse.com/
 * Version: 				2.1.2
 * WC requires at least: 	6.0.0
 * WC tested up to: 		9.0.2
 * Requires PHP: 			7.4
 * Tested up to:      		6.5.5
 * Text Domain: 			wc-account-genius
 * Domain Path: 			/languages
 * License: 				GPL2
 */

namespace Account_Genius;
use Account_Genius\Init\Init;

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! class_exists('Wc_Account_Genius') ) {
  
	/**
	 * Main Wc_Account_Genius Class
	 *
	 * @since 1.0.0
	 * @version 1.8.5
	 * @package MeuMouse.com
	 */
	class Wc_Account_Genius {

		/**
		 * Wc_Account_Genius The single instance of Wc_Account_Genius.
		 *
		 * @var object
		 * @since 1.0.0
		 */
		private static $instance = null;

		/**
		 * The slug
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public static $slug = 'wc-account-genius';

		/**
		 * The version number
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public static $version = '2.1.2';

		/**
		 * Constructor function
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			$this->define_constants();
			
			add_action( 'init', array( __CLASS__, 'load_plugin_textdomain' ), -1 );
			add_action( 'plugins_loaded', array( $this, 'load_checker' ), 5 );
		}
		

		/**
		 * Check requeriments on load plugin
		 * 
		 * @since 1.0.0
		 * @version 1.8.5
		 * @return void
		 */
		public function load_checker() {
			// Display notice if PHP version is bottom 7.4
			if ( version_compare( phpversion(), '7.4', '<' ) ) {
				add_action( 'admin_notices', array( $this, 'wc_account_genius_php_version_notice' ) );

				return;
			}

			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			// check if WooCommerce is active
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && version_compare( WC_VERSION, '6.0', '>' ) ) {
				add_action( 'before_woocommerce_init', array( $this, 'setup_hpos_compatibility' ) );
				add_action( 'plugins_loaded', array( $this, 'setup_includes' ), 10 );
				add_filter( 'plugin_action_links_' . ACCOUNT_GENIUS_BASENAME, array( $this, 'wc_account_genius_plugin_links' ), 10, 4 );
				add_filter( 'plugin_row_meta', array( $this, 'account_genius_row_meta_links' ), 10, 4 );
			} else {
				add_action( 'admin_notices', array( $this, 'wc_account_genius_wc_version_notice' ) );
				deactivate_plugins( 'wc-account-genius/wc-account-genius.php' );
				add_action( 'admin_notices', array( $this, 'wc_account_genius_wc_deactivate_notice' ) );
			}
		}


		/**
		 * Setp compatibility with HPOS/Custom order table feature of WooCommerce.
		 *
		 * @since 1.6.0
		 * @return void
		 */
		public static function setup_hpos_compatibility() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', ACCOUNT_GENIUS_PLUGIN_FILE, true );
			}
		}
		

		/**
		 * Main Wc_Account_Genius Instance
		 *
		 * Ensures only one instance of Wc_Account_Genius is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @see Wc_Account_Genius()
		 * @return Main Wc_Account_Genius instance
		 */
		public static function run() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Setup plugin constants
		 *
		 * @since 1.0.0
		 * @version 2.1.0
		 * @return void
		 */
		private function define_constants() {
			$this->define( 'ACCOUNT_GENIUS_BASENAME', plugin_basename( __FILE__ ) );
			$this->define( 'ACCOUNT_GENIUS_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'ACCOUNT_GENIUS_INC_DIR', ACCOUNT_GENIUS_DIR . '/includes/' );
			$this->define( 'ACCOUNT_GENIUS_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'ACCOUNT_GENIUS_ASSETS', ACCOUNT_GENIUS_URL . 'assets/' );
			$this->define( 'ACCOUNT_GENIUS_FILE', __FILE__ );
			$this->define( 'ACCOUNT_GENIUS_ABSPATH', dirname( ACCOUNT_GENIUS_FILE ) . '/' );
			$this->define( 'ACCOUNT_GENIUS_PLUGIN_FILE', __FILE__ );
			$this->define( 'ACCOUNT_GENIUS_VERSION', self::$version );
			$this->define( 'ACCOUNT_GENIUS_SLUG', self::$slug );
			$this->define( 'ACCOUNT_GENIUS_ADMIN_EMAIL', get_option('admin_email') );
			$this->define( 'ACCOUNT_GENIUS_DOCS_LINK', 'https://meumouse.com/docs/account-genius-para-woocommerce/' );
		}


		/**
		 * Define constant if not already set.
		 *
		 * @param string $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}


		/**
		 * Include required files
		 *
		 * @since 1.0.0
		 * @version 2.1.0
		 * @return void
		 */
		public function setup_includes() {

			/**
			 * Plugin functions
			 * 
			 * @since 1.0.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'functions.php';

			/**
			 * Class init plugin
			 * 
			 * @since 1.0.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'class-init.php';

			/**
			 * Admin options
			 * 
			 * @since 1.0.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'admin/class-admin-options.php';

			/**
			 * Load assets
			 * 
			 * @since 1.8.5
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'classes/class-assets.php';

			/**
			 * Frontend class
			 * 
			 * @since 1.0.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'classes/class-core.php';

			/**
			 * Custom colors
			 * 
			 * @since 1.0.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'classes/class-custom-colors.php';

			/**
			 * Connect licenses API
			 * 
			 * @since 1.6.0
			 * @version 2.1.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'classes/class-license.php';

			/**
			 * Load avatar class
			 * 
			 * @since 1.0.0
			 */
			if ( Init::get_setting('enable_upload_avatar') === 'yes' ) {
				include_once ACCOUNT_GENIUS_INC_DIR . 'classes/class-avatar.php';
			}

			/**
			 * Update checker
			 * 
			 * @since 1.5.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'classes/class-updater.php';

			/**
			 * Compatibility autoloader class
			 * 
			 * @since 2.1.0
			 */
			include_once ACCOUNT_GENIUS_INC_DIR . 'classes/class-compat-autoloader.php';
		}


		/**
		 * PHP version notice
		 * 
		 * @since 1.0.0
		 * @version 2.1.0
		 * @return void
		 */
		public function wc_account_genius_php_version_notice() {
			$class = 'notice notice-error is-dismissible';
			$message = esc_html__( '<strong>Account Genius para WooCommerce</strong> requer a versão do PHP 7.4 ou maior. Contate o suporte da sua hospedagem para realizar a atualização.', 'wc-account-genius' );

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
		}


		/**
		 * WooCommerce version notice
		 * 
		 * @since 1.0.0
		 * @version 2.1.0
		 * @return void
		 */
		public function wc_account_genius_wc_version_notice() {
			$class = 'notice notice-error is-dismissible';
			$message = esc_html__( '<strong>Account Genius para WooCommerce</strong> requer a versão do WooCommerce 6.0 ou maior. Faça a atualização do plugin WooCommerce.', 'wc-account-genius' );

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
		}


		/**
		 * Notice if WooCommerce is deactivate
		 * 
		 * @since 1.0.0
		 * @version 2.1.0
		 * @return void
		 */
		public function wc_account_genius_wc_deactivate_notice() {
			if ( ! current_user_can('install_plugins') ) {
				return;
			}

			$class = 'notice notice-error is-dismissible';
			$message = esc_html__( '<strong>Account Genius para WooCommerce</strong> requer que <strong>WooCommerce</strong> esteja instalado e ativado.', 'wc-account-genius' );

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
		}


		/**
		 * Plugin action links
		 * 
		 * @since 1.0.0
		 * @version 1.8.5
		 * @param array $action_links
		 * @return array
		 */
		public function wc_account_genius_plugin_links( $action_links ) {
			$plugins_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-account-genius' ) . '">'. __( 'Configurar', 'wc-account-genius' ) .'</a>',
			);

			return array_merge( $plugins_links, $action_links );
		}


		/**
		 * Add meta links on plugin
		 * 
		 * @since 1.8.5
		 * @param string $plugin_meta | An array of the plugin’s metadata, including the version, author, author URI, and plugin URI
		 * @param string $plugin_file | Path to the plugin file relative to the plugins directory
		 * @param array $plugin_data | An array of plugin data
		 * @param string $status | Status filter currently applied to the plugin list
		 * @return string
		 */
		public function account_genius_row_meta_links( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( strpos( $plugin_file, ACCOUNT_GENIUS_BASENAME ) !== false ) {
				$new_links = array(
					'docs' => '<a href="'. ACCOUNT_GENIUS_DOCS_LINK .'" target="_blank">'. __( 'Documentação', 'wc-account-genius' ) .'</a>',
				);
				
				$plugin_meta = array_merge( $plugin_meta, $new_links );
			}
		
			return $plugin_meta;
		}


		/**
		 * Load the plugin text domain for translation.
		 * 
		 * @since 1.0.0
		 * @return void
		 */
		public static function load_plugin_textdomain() {
			load_plugin_textdomain( 'wc-account-genius', false, dirname( ACCOUNT_GENIUS_BASENAME ) . '/languages/' );
		}


		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Trapaceando?', 'wc-account-genius' ), '1.0.0' );
		}


		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Trapaceando?', 'wc-account-genius' ), '1.0.0' );
		}
	}
}

/**
 * Initialise the plugin
 */
Wc_Account_Genius::run();