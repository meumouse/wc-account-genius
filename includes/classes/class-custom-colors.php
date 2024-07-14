<?php

namespace Account_Genius\Custom_Colors;
use Account_Genius\Init\Init;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Change colors on front-end
 *
 * @since 1.0.0
 * @version 1.8.5
 * @package MeuMouse.com
 */
class Custom_Colors {

  /**
   * Construct function
   * 
   * @since 1.0.0
   * @return void
   */
  public function __construct() {
    add_action( 'wp_head', array( $this, 'wc_account_genius_custom_primary_color' ) );
  }

  /**
   * Custom color primary
   * 
   * @since 1.0.0
   * @version 1.8.5
   * @return string
   */
  public function wc_account_genius_custom_primary_color() {
    if ( is_account_page() ) {
      $primary_color = Init::get_setting('primary_main_color');
      $hover_color = $this->generate_rgba_color( $primary_color, 80 );
      $primary_color_35_opacity = $this->generate_rgba_color( $primary_color, 35 );
  
      // link color
      $css = 'a {';
        $css .= 'color:'. $primary_color .';';
      $css .= '}';

      $css .= 'a:hover {';
        $css .= 'color:'. $hover_color .';';
      $css .= '}';

      // primary button background
      $css .= '.btn-primary {';
        $css .= 'background-color:'. $primary_color .' !important;';
        $css .= 'border-color:'. $primary_color .' !important;';
      $css .= '}';

      $css .= '.btn-primary:hover, .btn-primary:focus {';
        $css .= 'background-color:'. $hover_color .' !important;';
        $css .= 'border-color:'. $hover_color .' !important;';
      $css .= '}';

      // primary outline button
      $css .= '.btn-outline-primary {';
        $css .= 'color:'. $primary_color .' !important;';
        $css .= 'border-color:'. $primary_color .' !important;';
      $css .= '}';

      $css .= '.btn-outline-primary:hover {';
        $css .= 'background-color:'. $primary_color .' !important;';
        $css .= 'border-color:'. $primary_color .' !important;';
      $css .= '}';

      // text primary color
      $css .= '.text-primary {';
        $css .= 'color:'. $primary_color .' !important;';
      $css .= '}';

      // nav link hover or active
      $css .= '.nav-link-style:hover, .nav-link-style.is-active {';
        $css .= 'color:'. $primary_color .' !important;';
      $css .= '}';

      // background primary color
      $css .= '.bg-primary {';
        $css .= 'background-color:'. $primary_color .' !important;';
      $css .= '}';

      // input checkbox checked color
      $css .= 'input[type="checkbox"]:checked {';
        $css .= 'background-color:'. $primary_color .' !important;';
        $css .= 'border-color:'. $primary_color .' !important;';
      $css .= '}';

      // select color
      $css .= '.select2-container--default .select2-results__option--highlighted[aria-selected], .select2-container--default .select2-results__option--highlighted[data-selected] {';
        $css .= 'background-color:'. $primary_color .' !important;';
      $css .= '}';

      // file item color
      $css .= '.file-item, label.custom-file-label:hover {';
        $css .= 'color:'. $primary_color .' !important;';
      $css .= '}';

      // input focus color
      $css .= '.form-control:focus, input#billing_cpf:focus, input#billing_rg:focus, input#billing_cnpj:focus, input#billing_ie:focus, input#billing_birthdate:focus, input#billing_number:focus, input#billing_neighborhood:focus, input#billing_cellphone:focus, input#shipping_number:focus, input#shipping_neighborhood:focus, .select2-container--default .select2-search--dropdown .select2-search__field:focus {';
        $css .= 'border-color:'. $primary_color_35_opacity .' !important;';
        $css .= 'box-shadow: inset 0 0 0 transparent, 0 0.5rem 1.125rem -0.5rem'. $primary_color_35_opacity .' !important';
      $css .= '}';

      // slanted background
      $css .= '.wc-account-genius-my-account::before, .wc-account-genius-form-login::before, .wc-account-genius-form-last-password::before, .wc-account-genius-my-account::after, .wc-account-genius-form-login::after, .wc-account-genius-form-last-password::after {';
        $css .= 'background-color:'. $primary_color .' !important;';
      $css .= '}';

      // password toggle
      $css .= '.cs-password-toggle-btn .cs-password-toggle-indicator:hover {';
        $css .= 'color:'. $primary_color .' !important;';
      $css .= '}';
  
      ?>
      <style type="text/css">
        <?php echo $css; ?>
      </style> <?php
    }
  }


  /**
   * Generate RGBA color from primary color
   * 
   * @since 1.0.0
   * @param string $color | Color hexadecimal
   * @param string $opacity | Opacity
   * @return string
   */
  public function generate_rgba_color($color, $opacity) {
    // removes the "#" character if present 
    $color = str_replace("#", "", $color);

    // gets the RGB decimal value of each color component
    $red = hexdec( substr( $color, 0, 2 ) );
    $green = hexdec( substr( $color, 2, 2 ) );
    $blue = hexdec( substr( $color, 4, 2 ) );
    $opacity = $opacity / 100;

    // generates RGBA color based on foreground color and opacity
    $rgba_color = "rgba($red, $green, $blue, $opacity)";

    return $rgba_color;
  }

}

new Custom_Colors();