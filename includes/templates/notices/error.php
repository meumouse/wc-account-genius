<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/error.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package MeuMouse.com
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! $notices ) {
    return;
}

foreach ( $notices as $notice ) : ?>
  <div class="alert alert-danger d-flex align-items-center" role="alert">
    <i class="bx bx-x-circle lead me-2"></i>
    <div>
      <span <?php echo wc_get_notice_data_attr( $notice ); ?> role="alert">
        <?php echo wc_kses_notice( $notice['notice'] ); ?>
      </span>
    </div>
  </div>
<?php endforeach; ?>