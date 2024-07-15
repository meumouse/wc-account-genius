<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package MeuMouse.com
 * @version 1.3.0
 */

defined('ABSPATH') || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Endereço de cobrança', 'wc-account-genius' ) : esc_html__( 'Endereço de entrega', 'wc-account-genius' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

	<form method="post">
		<h3 class="h4 pb-3"><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></h3><?php // @codingStandardsIgnoreLine ?>

		<div class="woocommerce-address-fields">
			<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

			<div class="woocommerce-address-fields__field-wrapper row">
				<?php
					foreach ( $address as $key => $field ) {
						$field['input_class'][] = 'form-control';

						// change label for billing or shipping number in Brazilian Market on WooCommerce
						if ( $key === 'billing_number' || $key === 'shipping_number' ) {
							$field['label'] = 'Número da residência';
						}

						// remove class for billing or billing address field 2
						if ( $key === 'billing_address_2' || $key === 'shipping_address_2' ) {
							$field['label_class'] = array();
						}

						woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
					}
				?>
			</div>

			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

			<div class="button-group pt-4">
				<div class="d-flex flex-wrap justify-content-end align-items-center">
					<button type="submit" class="btn btn-primary button-loading" name="save_address" value="<?php esc_attr_e( 'Salvar endereço', 'wc-account-genius' ); ?>"><i class="bx bx-save fs-lg me-2"></i><?php esc_html_e( 'Salvar endereço', 'wc-account-genius' ); ?></button>
					<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
					<input type="hidden" name="action" value="edit_address" />
				</div>
			</div>
		</div>

	</form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>