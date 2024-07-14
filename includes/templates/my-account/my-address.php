<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
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

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Endereço de cobrança', 'wc-account-genius' ),
			'shipping' => __( 'Endereço de entrega', 'wc-account-genius' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Endereço de cobrança', 'wc-account-genius' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col = 1;
?>

<p><?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'Os endereços a seguir serão usados na finalização da compra por padrão.', 'wc-account-genius' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	<div class="row woocommerce-Addresses addresses">
<?php endif; ?>

<?php foreach ( $get_addresses as $name => $address_title ) : ?>
	<?php
		$address = wc_get_account_formatted_address( $name );
		$col     = $col * -1;
		$oldcol  = $oldcol * -1;
	?>

	<div class="col-sm-6 mb-4 mb-sm-0 woocommerce-Address">
		<div class="border rounded-3 p-4 h-100">
			<header class="woocommerce-Address-title title">
				<h3 class="h4"><?php echo esc_html( $address_title ); ?></h3>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="my-2 edit btn btn-outline-primary btn-sm left-0"><?php echo ! empty( $address ) ? esc_html__( 'Editar', 'wc-account-genius' ) : esc_html__( 'Adicionar', 'wc-account-genius' ); ?></a>

			</header>
			<address>
				<?php
					echo ! empty( $address ) ? wp_kses_post( $address ) : esc_html_e( 'Você ainda não configurou este tipo de endereço.', 'wc-account-genius' );
				?>
			</address>
		</div>
	</div>

<?php endforeach; ?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	</div>
	<?php
endif;
