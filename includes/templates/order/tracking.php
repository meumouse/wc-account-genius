<?php
/**
 * Order tracking
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/tracking.php.
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

$notes = $order->get_customer_order_notes();
?>

<h1 class="mb-3 pb-4">Rastrear pedido: <span class="font-weight-normal"><?php echo esc_html( $order->get_order_number() ); ?></span></h1>

<div class="row mb-4">
	<div class="col-md-4 mb-2">
		<div class="bg-secondary p-4 text-center rounded-lg h-100">
			<span class="font-weight-medium text-dark mr-2"><?php echo esc_html_x( 'Order ID:', 'front-end', 'epicjungle' ); ?></span>
			<?php echo esc_html( $order->get_order_number() ); ?>
		</div>
	</div>
	<div class="col-md-4 mb-2">
		<div class="bg-secondary p-4 text-center rounded-lg h-100">
			<span class="font-weight-medium text-dark mr-2"><?php echo esc_html_x( 'Placed on:', 'front-end', 'epicjungle' ); ?></span>
			<?php echo wc_format_datetime( $order->get_date_created() ); ?>
		</div>
	</div>
	<div class="col-md-4 mb-2">
		<div class="bg-secondary p-4 text-center rounded-lg h-100">
			<span class="font-weight-medium text-dark mr-2"><?php echo esc_html_x( 'Order status:', 'front-end', 'epicjungle' ); ?></span>
			<span class="badge badge-<?php echo esc_attr( $order->get_status() ); ?> font-size-ms"><?php echo wc_get_order_status_name( $order->get_status() ); ?></span>
		</div>
	</div>
</div>


<?php if ( $notes ) : ?>
	<h2 class="h5 pb-2"><?php esc_html_e( 'Order updates', 'epicjungle' ); ?></h2>
	<div class="woocommerce-OrderUpdates">
		<?php foreach ( $notes as $note ) : ?>
			<div class="woocommerce-OrderUpdate card mb-3">
				<div class="card-body">
					<div class="font-size-md"><?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div class="font-size-sm text-muted">
						<i class="fe-clock align-middle mr-1 mt-n1"></i>
						<?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'epicjungle' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order->get_id() ); ?>
