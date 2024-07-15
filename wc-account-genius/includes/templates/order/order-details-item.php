<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
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

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}

/** @var WC_Product $product */

$is_visible = $product && $product->is_visible();
$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink() : '', $item, $order );

$qty = $item->get_quantity();
$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

if ( $refunded_qty ) {
	$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
} else {
	$qty_display = esc_html( $qty );
}

?>
<li class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">
	<div class="media align-items-center">
		<?php if ( $product_permalink ) : ?>
			<a href="<?php echo esc_url( $product_permalink ); ?>" class="widget-product-thumb">
				<?php echo apply_filters( 'woocommerce_order_item_thumbnail', $product->get_image(), $item, $order ); ?>
			</a>
		<?php else: ?>
			<div class="widget-product-thumb">
				<?php echo apply_filters( 'woocommerce_order_item_thumbnail', $product->get_image(), $item, $order ); ?>
			</div>
		<?php endif; ?>
		<div class="media-body">
			<h6 class="widget-product-title nav-heading">
				<?php echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ) ); ?>
			</h6>
			<div class="widget-product-meta">
				<span class="text-accent me-1"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="text-muted"><?php echo apply_filters( 'woocommerce_order_item_quantity_html', sprintf( '&times; %s', $qty_display ), $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
		</div>
	</div>
	<?php if ( $show_purchase_note && $purchase_note ) : ?>
		<div class="woocommerce-table__product-purchase-note product-purchase-note font-size-ms text-muted pt-1">
			<?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
</li>
