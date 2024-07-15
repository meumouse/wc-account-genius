<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
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

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details">
	<div class="row pt-4">
		<div class="col-sm-6 mb-4 mb-sm-0">
			<div class="border rounded-3 p-4 h-100">
				<h2 class="woocommerce-column__title h6"><?php esc_html_e( 'Endereço de cobrança', 'wc-account-genius' ); ?></h2>
				<ul class="fs-sm list-unstyled">
					<li class="d-flex">
						<i class="bx bx-map opacity-60 me-2 mt-1"></i>
						<div><?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'wc-account-genius' ) ) ); ?></div>
					</li>
					<?php if ( $order->get_billing_phone() ) : ?>
						<li class="d-flex">
							<i class="bx bx-phone opacity-60 me-2 mt-1"></i>
							<div><?php echo esc_html( $order->get_billing_phone() ); ?></div>
						</li>
					<?php endif; ?>
					<?php if ( $order->get_billing_email() ) : ?>
						<li class="d-flex">
							<i class="bx bx-mail-send opacity-60 me-2 mt-1"></i>
							<div><?php echo esc_html( $order->get_billing_email() ); ?></div>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		<?php if ( $show_shipping ) : ?>
			<div class="col-sm-6">
				<div class="border rounded-3 p-4 h-100">
					<h2 class="woocommerce-column__title h6"><?php esc_html_e( 'Endereço de entrega', 'wc-account-genius' ); ?></h2>
					<ul class="fs-sm list-unstyled">
						<li class="d-flex">
							<i class="bx bx-map opacity-60 me-2 mt-1"></i>
							<div><?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'wc-account-genius' ) ) ); ?></div>
						</li>
					</ul>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>
