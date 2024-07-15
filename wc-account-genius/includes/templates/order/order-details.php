<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
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

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited

if ( ! $order ) {
	return;
}

$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads = $order->get_downloadable_items();
$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<div class="card order-details">
	<div class="card-header">
		<h2 class="woocommerce-order-details__title h6 mb-0"><?php esc_html_e( 'Detalhes do pedido', 'wc-account-genius' ); ?></h2>
	</div>
	<div class="card-body">
		<div class="row mx-n2">
			<div class="col-md-7">
				<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

				<div class="widget widget_products">
					<ul class="product_list_widget list-unstyled">
						<?php do_action( 'woocommerce_order_details_before_order_table_items', $order );

						foreach ( $order_items as $item_id => $item ) :
							$product = $item->get_product();

							wc_get_template( 'order/order-details-item.php',
								array(
									'order' => $order,
									'item_id' => $item_id,
									'item' => $item,
									'show_purchase_note' => $show_purchase_note,
									'purchase_note' => $product ? $product->get_purchase_note() : '',
									'product' => $product,
								)
							);
						endforeach;

						do_action( 'woocommerce_order_details_after_order_table_items', $order ); ?>
					</ul>
				</div>

				<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
			</div>
			<div class="col-md-5 pt-4 pt-md-0">
				<div class="bg-secondary rounded-3 p-4 h-100">
					<?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
						<div class="d-flex justify-content-between align-items-center fs-md mb-2 pb-1">
							<span class="me-2"><?php echo esc_html( $total['label'] ); ?></span>
							<span class="text-right"><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</div>
					<?php endforeach; ?>
					<?php if ( $order->get_customer_note() ) : ?>
						<div class="pt-3">
							<h6 class="fs-base"><?php esc_html_e( 'Observação:', 'wc-account-genius' ); ?></h6>
							<p class="mb-0 fs-sm"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}

/**
 * Action hook fired after the order details.
 *
 * @since 1.0.0
 * @param WC_Order $order Order data
 */
do_action( 'woocommerce_after_order_details', $order );