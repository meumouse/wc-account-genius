<?php

namespace Account_Genius\Assets;
use Account_Genius\Init\Init;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Load assets class
 * 
 * @since 1.8.5
 * @version 2.1.0
 * @package MeuMouse.com
 */
class Assets {

    /**
     * Construct function
     *
     * @since 1.8.5
     * @return void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'my_account_load_styles' ), 999 );

        // load box icons library
        if ( Init::get_setting('enable_icons') === 'yes' ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'box_icons_lib' ) );
        }
    
        // load popup avatar
        if ( Init::get_setting('enable_upload_avatar') === 'yes' ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_avatar_upload' ) );
        }
    }


    /**
     * Enqueue admin scripts in page settings only
     * 
     * @since 1.0.0
     * @version 2.1.0
     * @return void
     */
    public function admin_assets() {
        if ( is_account_genius_admin_panel() ) {
            wp_enqueue_media();

            wp_enqueue_style( 'account-genius-modal-styles', ACCOUNT_GENIUS_ASSETS . 'components/modal/modal.css', array(), ACCOUNT_GENIUS_VERSION );
            wp_enqueue_script( 'account-genius-modal-scripts', ACCOUNT_GENIUS_ASSETS . 'components/modal/modal.js', array('jquery'), ACCOUNT_GENIUS_VERSION );

            wp_enqueue_script( 'account-genius-controller-visibility', ACCOUNT_GENIUS_ASSETS . 'components/visibility-controller/visibility-controller.min.js', array('jquery'), ACCOUNT_GENIUS_VERSION );

            wp_enqueue_style( 'account-genius-admin-styles', ACCOUNT_GENIUS_ASSETS . 'admin/css/account-genius-admin-styles.css', array(), ACCOUNT_GENIUS_VERSION );
            wp_enqueue_script( 'account-genius-admin-scripts', ACCOUNT_GENIUS_ASSETS . 'admin/js/account-genius-admin-scripts.js', array('jquery'), ACCOUNT_GENIUS_VERSION );
            wp_enqueue_style( 'bootstrap-buttons', ACCOUNT_GENIUS_ASSETS . 'components/buttons/buttons.css', array(), ACCOUNT_GENIUS_VERSION );
            
            if ( ! class_exists('Flexify_Dashboard') ) {
                wp_enqueue_style( 'boxicons-lib', ACCOUNT_GENIUS_ASSETS . 'vendor/boxicons/css/boxicons.min.css', array(), '2.1.4' );
                wp_enqueue_style( 'bootstrap-grid', ACCOUNT_GENIUS_ASSETS . 'vendor/bootstrap/bootstrap-grid.min.css', array(), '5.3.3' );
                wp_enqueue_style( 'bootstrap-utilities', ACCOUNT_GENIUS_ASSETS . 'vendor/bootstrap/bootstrap-utilities.min.css', array(), '5.3.3' );
            }

            wp_localize_script( 'account-genius-admin-scripts', 'account_genius_admin_params', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'new_tab' => esc_html__( 'Nova guia', 'wc-account-genius' ),
                'new_tab_endpoint' => esc_html__( 'nova-guia', 'wc-account-genius' ),
                'tab_title' => esc_html__( 'Configurar guia', 'wc-account-genius' ),
                'edit_tab' => esc_html__( 'Editar guia', 'wc-account-genius' ),
                'close_popup' => esc_html__( 'Fechar', 'wc-account-genius' ),
                'tab_name' => esc_html__( 'Nome da guia', 'wc-account-genius' ),
                'tab_name_description' => esc_html__('Define o título que será exibido para esta guia.', 'wc-account-genius' ),
                'endpoint_tab' => esc_html__( 'Endpoint da guia', 'wc-account-genius' ),
                'endpoint_tab_description' => esc_html__('Define o endpoint que será usado como link permanente para esta guia.', 'wc-account-genius' ),
                'icon_tab' => esc_html__( 'Ícone da guia', 'wc-account-genius' ),
                'icon_tab_description' => esc_html__('Define o ícone que será exibido ao lado do título desta guia. Ou deixe em branco para não exibir.', 'wc-account-genius' ),
                'icon_placeholder' => esc_html__('Classe do ícone Boxicons.', 'wc-account-genius' ),
                'tab_content_title' => esc_html__( 'Conteúdo da guia', 'wc-account-genius' ),
                'tab_content_description' => esc_html__('Coloque aqui o conteúdo que você deseja exibir na guia. É permitido HTML e shortcodes. Ex.: [content id="10580"]', 'wc-account-genius' ),
                'active_redirect_tab_title' => esc_html__( 'Redirecionar para outro link', 'wc-account-genius' ),
                'active_redirect_tab_description' => esc_html__('Quando o usuário acessar esta guia, será redirecionado para outro link do site, ou externo.', 'wc-account-genius' ),
                'redirect_tab_link_title' => esc_html__( 'Link de redirecionamento', 'wc-account-genius' ),
                'redirect_tab_link_description' => esc_html__('Informe o link de destino da guia.', 'wc-account-genius' ),
                'class_css_title' => esc_html__('Classe CSS da guia', 'wc-account-genius' ),
                'class_css_description' => esc_html__('Permite definir classes CSS personalizadas para esta guia.', 'wc-account-genius' ),
                'class_css_placeholder' => esc_html__('Classe CSS personalizada.', 'wc-account-genius' ),
                'invalid_file' => esc_html__( 'O arquivo enviado não é permitido.', 'wc-account-genius' ),
                'upload_success' => esc_html__( 'Arquivo enviado com sucesso', 'wc-account-genius' ),
            ));
        }
    }


    /**
     * Load styles in My account page
     * 
     * @since 1.0.0
     * @return void
     */
    public function my_account_load_styles() {
        if ( is_account_page() && Init::get_setting('replace_default_template_my_account') === 'yes' ) {
            wp_enqueue_style( 'wc-account-genius-front-styles', ACCOUNT_GENIUS_ASSETS . 'front/css/account-genius-front-styles.css', array(), ACCOUNT_GENIUS_VERSION );
            wp_enqueue_script( 'wc-account-genius-front-scripts', ACCOUNT_GENIUS_ASSETS . 'front/js/account-genius-front-scripts.js', array('jquery'), ACCOUNT_GENIUS_VERSION );
        }
    }
    

    /**
     * Load Boxicons library
     * 
     * @since 1.0.0
     * @return void
     */
    public function box_icons_lib() {
        wp_enqueue_style( 'boxicons-lib', ACCOUNT_GENIUS_ASSETS . 'vendor/boxicons/css/boxicons.min.css', array(), '2.1.4' );
    }


    /**
     * Load upload avatar styles and scripts
     * 
     * @since 1.0.0
     * @return void
     */
    public function enqueue_avatar_upload() {
        wp_enqueue_style( 'wc-account-genius-avatar-styles', ACCOUNT_GENIUS_ASSETS . 'front/css/account-genius-popup-avatar-styles.css', array(), ACCOUNT_GENIUS_VERSION );
        wp_enqueue_script( 'wc-account-genius-avatar-scripts', ACCOUNT_GENIUS_ASSETS . 'front/js/account-genius-popup-avatar-scripts.js', array(), ACCOUNT_GENIUS_VERSION );
    }
}

new Assets();