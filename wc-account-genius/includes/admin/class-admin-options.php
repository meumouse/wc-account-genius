<?php

namespace Account_Genius\Admin_Options;
use Account_Genius\Init\Init;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Extends main class with admin actions
 * 
 * @since 1.0.0
 * @version 2.1.0
 * @package MeuMouse.com
 */
class Admin_Options extends Init {

  /**
   * Construct function
   *
   * @since 1.0.0
   * @version 2.0.0
   * @return void
   */
  public function __construct() {
    parent::__construct();

    // add submenu on WooCommerce
    add_action( 'admin_menu', array( $this, 'wc_account_genius_admin_menu' ) );

    // get AJAX call on change settings options
    add_action( 'wp_ajax_ajax_save_options_action', array( $this, 'ajax_save_options_callback' ) );

    // remove tab action on receive AJAX call
    add_action( 'wp_ajax_remove_tab_from_options', array( $this, 'remove_tab_from_options_callback' ) );

    // get AJAX call from upload license file
    add_action( 'wp_ajax_account_genius_alternative_activation', array( $this, 'account_genius_alternative_activation_callback' ) );

    // add new tab action on receive AJAX call
    add_action( 'wp_ajax_add_new_tab_action', array( $this, 'add_new_tab_action_callback' ) );

    // set to default settings on reset
    add_action( 'admin_init', array( $this, 'reset_plugin_settings' ) );
  }


  /**
   * Function for create submenu in settings
   * 
   * @since 1.0.0
   * @return array
   */
  public function wc_account_genius_admin_menu() {
    add_submenu_page(
      'woocommerce', // parent page slug
      esc_html__( 'Account Genius para WooCommerce', 'wc-account-genius'), // page title
      esc_html__( 'Account Genius', 'wc-account-genius'), // submenu title
      'manage_woocommerce', // user capabilities
      'wc-account-genius', // page slug
      array( $this, 'load_settings_page' ), // public function for print content page
    );
  }


  /**
   * Plugin general setting page and save options
   * 
   * @since 1.0.0
   * @access public
   */
  public function load_settings_page() {
    include_once ACCOUNT_GENIUS_DIR . 'includes/admin/settings.php';
  }


  /**
   * Save options in AJAX
   * 
   * @since 1.5.0
   * @version 2.0.0
   * @return void
   */
  public function ajax_save_options_callback() {
    if ( isset( $_POST['form_data'] ) ) {
        // Convert serialized data into an array
        parse_str( $_POST['form_data'], $form_data );

        $options = get_option('wc-account-genius-setting', array());
        $options['replace_default_template_my_account'] = isset( $form_data['replace_default_template_my_account'] ) ? 'yes' : 'no';
        $options['replace_default_notices'] = isset( $form_data['replace_default_notices'] ) ? 'yes' : 'no';
        $options['enable_icons'] = isset( $form_data['enable_icons'] ) ? 'yes' : 'no';
        $options['display_background_slanted'] = isset( $form_data['display_background_slanted'] ) ? 'yes' : 'no';
        $options['enable_upload_avatar'] = isset( $form_data['enable_upload_avatar'] ) ? 'yes' : 'no';

        // update data for tabs
        if ( isset( $form_data['account_tabs'] ) && is_array( $form_data['account_tabs'] ) ) {
          $get_tabs = maybe_unserialize( get_option('account_genius_get_tabs_options', array()) );

          // Iterate through the updated data
          foreach ( $form_data['account_tabs'] as $index => $tab ) {
            if ( isset( $get_tabs[$index] ) ) {
              // Update WooCommerce endpoint option if the endpoint is changed
              if ( isset( $tab['native'] ) && $tab['native'] === 'yes' && isset( $tab['endpoint'] ) && $get_tabs[$index]['endpoint'] !== $tab['endpoint'] ) {
                update_option( $get_tabs[$index]['id'], sanitize_text_field( $tab['endpoint'] ) );

                // update permalinks
                flush_rewrite_rules();
              }

              if ( ! isset( $tab['enabled'] ) ) {
                $get_tabs[$index]['enabled'] = 'no';
              }

              if ( ! isset( $tab['native'] ) ) {
                $get_tabs[$index]['native'] = 'no';
              }

              if ( ! isset( $tab['redirect'] ) ) {
                $get_tabs[$index]['redirect'] = 'no';
              }

              // Update priority if present in the form data
              if ( isset( $tab['priority'] ) ) {
                $get_tabs[$index]['priority'] = $tab['priority'];
              }

              // Merge updated data with existing tab data
              $get_tabs[$index] = array_merge( $get_tabs[$index], $tab );

              // Update option with merged data
              update_option('account_genius_get_tabs_options', maybe_serialize( $get_tabs ));
            } else {
                // get array key from new tab
                if ( ! isset( $get_tabs[$index] ) ) {
                  $get_tabs[$index]['array_key'] = $tab['array_key'];
                }

                if ( ! isset( $tab['endpoint'] ) ) {
                  $get_tabs[$index]['endpoint'] = $tab['endpoint'];
                }

                // If the tab does not exist, add it to the array
                $get_tabs[$index] = $tab;

                // Update option with merged data
                update_option('account_genius_get_tabs_options', maybe_serialize( $get_tabs ));
            }
          }
        }

        // Merge the form data with the default options
        $updated_options = wp_parse_args( $form_data, $options );

        // Save the updated options
        update_option('wc-account-genius-setting', $updated_options);

        $response = array(
            'status' => 'success',
            'options' => $updated_options,
        );

        wp_send_json( $response ); // Send JSON response
    }
  }


  /**
   * Remove tab from options
   * 
   * @since 1.8.0
   * @return void
   */
  public function remove_tab_from_options_callback() {
    if ( isset( $_POST['tab_to_remove'] ) ) {
      $tab_to_remove = sanitize_text_field( $_POST['tab_to_remove'] );
 
      // Get the current tabs options
      $get_tabs = get_option('account_genius_get_tabs_options', array());
      $get_tabs = maybe_unserialize( $get_tabs );
 
      // Remove the tab with the specified index
      if ( isset( $get_tabs[$tab_to_remove] ) ) {
        unset( $get_tabs[$tab_to_remove] );
 
        // Update the tab options
        update_option('account_genius_get_tabs_options', maybe_serialize( $get_tabs ));
      }
 
      $response = array(
        'status' => 'success',
        'tab' => $tab_to_remove,
      );

      echo wp_json_encode( $response ); // Send JSON response
    }
 
    wp_die();
  }


  /**
   * Handle alternative activation license file .key
   * 
   * @since 2.0.0
   * @return void
   */
  public function account_genius_alternative_activation_callback() {
    if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'account_genius_alternative_activation' ) {
        $response = array(
          'status' => 'error',
          'message' => 'Erro ao carregar o arquivo. A ação não foi acionada corretamente.',
        );

        wp_send_json( $response );
    }

    // get received file
    $file = $_FILES['file'];

    // Checks if the file was sent
    if ( empty( $file ) ) {
        $response = array(
          'status' => 'error',
          'message' => 'Erro ao carregar o arquivo. O arquivo não foi enviado.',
        );

        wp_send_json( $response );
    }

    // Checks if it is a .key file
    if ( pathinfo( $file['name'], PATHINFO_EXTENSION ) !== 'key' ) {
        $response = array(
          'status' => 'invalid_file',
          'message' => 'Arquivo inválido. O arquivo deve ser um .crt ou .key.',
        );
        
        wp_send_json( $response );
    }

    // Read the contents of the file
    $file_content = file_get_contents( $file['tmp_name'] );

    $decrypt_keys = array(
        '3129C0D8EDD384D1', // original product key
        'B729F2659393EE27', // Clube M
    );

    $decrypted_data = $this->decrypt_with_multiple_keys( $file_content, $decrypt_keys );

    if ( $decrypted_data !== null ) {
        update_option( 'account_genius_alternative_license_decrypted', $decrypted_data );
        
        $response = array(
          'status' => 'success',
          'message' => 'Licença enviada e decriptografada com sucesso.',
        );
    } else {
        $response = array(
          'status' => 'error',
          'message' => 'Não foi possível descriptografar o arquivo de licença.',
        );
    }

    wp_send_json( $response );
  }


  /**
   * Try to decrypt with multiple keys
   * 
   * @since 2.0.0
   * @param string $encrypted_data | Encrypted data
   * @param array $possible_keys | Array list with decryp keys
   * @return mixed Decrypted string or null
   */
  public function decrypt_with_multiple_keys( $encrypted_data, $possible_keys ) {
    foreach ( $possible_keys as $key ) {
      $decrypted_data = openssl_decrypt( $encrypted_data, 'AES-256-CBC', $key, 0, substr( $key, 0, 16 ) );

      // Checks whether decryption was successful
      if ( $decrypted_data !== false ) {
        return $decrypted_data;
      }
    }
    
    return null;
  }


  /**
   * Add new tab action in AJAX callback
   * 
   * @since 2.1.0
   * @return void
   */
  public function add_new_tab_action_callback() {
    if ( isset( $_POST['tab_name'] ) ) {
        $tab_id = sanitize_text_field( $_POST['tab_endpoint'] );

        $new_tab = array(
            'id' => $tab_id,
            'label' => sanitize_text_field( $_POST['tab_name'] ),
            'endpoint' => $tab_id,
            'icon' => isset( $_POST['tab_icon'] ) ? sanitize_text_field( $_POST['tab_icon'] ) : '',
            'native' => 'no',
            'priority' => sanitize_text_field( $_POST['tab_priority'] ),
            'enabled' => 'yes',
            'class' => isset( $_POST['tab_class'] ) ? sanitize_text_field( $_POST['tab_class'] ) : '',
            'redirect' => sanitize_text_field( $_POST['tab_redirect'] ),
            'link' => isset( $_POST['tab_redirect_link'] ) ? sanitize_text_field( $_POST['tab_redirect_link'] ) : '',
            'content' => isset( $_POST['tab_content'] ) ? sanitize_text_field( $_POST['tab_content'] ) : '',
        );

        // Get current option tabs
        $get_tabs = maybe_unserialize( get_option('account_genius_get_tabs_options', array()) );

        // Check if the tab with the same ID already exists
        if ( isset( $get_tabs[$tab_id] ) ) {
            $response = array(
              'status' => 'error',
              'error_message_header' => __( 'Ops! Ocorreu um erro ao adicionar nova guia', 'wc-account-genius' ),
              'error_message_body' => __( 'Já existe uma guia com o mesmo ID.', 'wc-account-genius' ),
            );

            wp_send_json( $response );

            return; // stop here
        }

        // Add the new tab to the array of existing tabs
        $get_tabs[$tab_id] = $new_tab;

        // Atualize a opção com as abas atualizadas
        $update_tabs = update_option('account_genius_get_tabs_options', maybe_serialize( $get_tabs ));

        // Verify that the update was successful and send a JSON response
        if ( $update_tabs ) {
            $response = array(
              'status' => 'success',
              'success_message_header' => __( 'Guia adicionada com sucesso.', 'wc-account-genius' ),
              'success_message_body' => sprintf( __( 'A nova guia %s foi adicionada com sucesso!.', 'wc-account-genius' ), sanitize_text_field( $_POST['tab_name'] ) ),
              'new_tab' => $new_tab,
              'update_tabs' => $get_tabs,
            );
            
            wp_send_json( $response );
        } else {
            $response = array(
              'status' => 'error',
              'error_message_header' => __( 'Ops! Ocorreu um erro.', 'wc-account-genius' ),
              'error_message_body' => __( 'Falha ao atualizar as guias.', 'wc-account-genius' ),
            );

            wp_send_json( $response );
        }
    }
  }


  /**
   * Reset settings to default
   * 
   * @since 2.1.2
   * @return void
   */
  public function reset_plugin_settings() {
    if ( isset( $_POST['confirm_reset_settings'] ) ) {
      delete_option('wc_account_genius_license_key');
      delete_option('wc_account_genius_temp_license_key');
      delete_option('account_genius_alternative_license');
      delete_option('account_genius_license_response_object');
      delete_transient('wc_account_genius_api_request_cache');
      delete_transient('wc_account_genius_api_response_cache');
    }
  }
}

new Admin_Options();