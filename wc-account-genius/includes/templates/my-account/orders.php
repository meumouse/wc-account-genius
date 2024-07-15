<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
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

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

<div class="accordion" id="orders-accordion">
	<table class="border-0 mb-0 woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr class="card d-none">
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $customer_orders->orders as $key =>  $customer_order ) {
				$uniqueid = uniqid(); 
				$order = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded(); ?>

				<tr class="card-heading px-4 py-3 collapsed d-flex flex-wrap align-items-center justify-content-between border rounded-3 mb-3 woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order<?php if ( $key !== 0 ) echo esc_attr( ' mt-3' ); ?>" data-toggle="collapse" aria-expanded="true" aria-controls="order-<?php echo esc_attr( $uniqueid );?>" data-target="#order-<?php echo esc_attr( $uniqueid );?>">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<?php 
							$text_color = '';
							$bg_color = '';

							switch ( $order->get_status() ) {
								case 'on-hold':
								case 'pending':
									$bg_color = 'bg-faded-info';
									$text_color = 'text-info';
								break;

								case 'completed':
								case 'processing':
								case 'order-shipped':
									$bg_color = 'bg-faded-success';
									$text_color = 'text-success';
								break;

								case 'cancelled':
								case 'refunded':
								case 'failed':
									$bg_color = 'bg-faded-danger';
									$text_color = 'text-danger';
								break;
							}
						?>

						<td class="p-0 border-0 woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<div class="d-flex align-items-center me-2">
									<i class="bx bx-hash fs-base mr-1"></i><span class="order-number fs-sm fw-medium text-nowrap d-inline-block align-middle"><?php echo esc_html( $order->get_order_number() ); ?></span>
								</div>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<div class="text-nowrap text-body fs-sm fw-normal my-1 me-2">
									<i class="bx bx-time-five text-muted me-1"></i><time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
								</div>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<div class="fs-xs fw-medium py-1 px-3 rounded-3 my-1 me-lg-2 <?php echo esc_attr( $bg_color );?> <?php echo esc_attr( $text_color ); ?>">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</div>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<div class="text-body fs-sm fw-medium my-1">
									<?php echo wp_kses_post( sprintf( _n( '%1$s para %2$s item', '%1$s para %2$s itens', $item_count, 'wc-account-genius' ), $order->get_formatted_order_total(), $item_count ) ); ?>
								</div>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
							<?php $actions = wc_get_account_orders_actions( $order );

							if ( ! empty( $actions ) ) {
								foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
									echo '<a href="' . esc_url( $action['url'] ) . '" class="btn btn-outline-primary btn-sm rounded-3 button-loading ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
								}
							}
							?>
						<?php endif; ?>

						</td>
						
					<?php endforeach; ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>


<nav class="d-md-flex justify-content-end pt-grid-gutter">
	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( apply_filters( ' wc_account_genius_showing_order_result_count', false ) ): ?>
		<div class="d-md-flex align-items-center w-100">
			<?php
			$per_page = function_exists( 'wc_account_genius_orders_limit' ) ? wc_account_genius_orders_limit() : 5;
			$current_page_orders = count( $customer_orders->orders );
			$total = $customer_orders->total;
			$max_num_pages = $customer_orders->max_num_pages; ?>

			<span class="fs-sm text-muted mr-md-3">
				<?php

				// phpcs:disable WordPress.Security
				if ( 1 === intval( $total ) ) {
					_e( 'Mostrando o único pedido', 'wc-account-genius' );
				} elseif ( $max_num_pages === 1 ) {
					/* translators: %d: total results */
					printf( _n( 'Mostrando todos os %d pedidos', 'Mostrando todos os %d pedidos', $total, 'wc-account-genius' ), $total );
				} else {
					$first = ( $per_page * $current_page ) - $per_page + 1;
					$last  = min( $total, $per_page * $current_page );
					if( $first === $last ) {
						/* translators: 1: first result 2: last result 3: total results */
						printf( _nx( 'Mostrando %1$d de %2$d pedido', 'Mostrando %1$d de %2$d pedido', $total, 'com primeiro e último pedido', 'wc-account-genius' ), $first, $total );
					} else {
						/* translators: 1: first result 2: last result 3: total results */
						printf( _nx( 'Mostrando %1$d&ndash;%2$d de %3$d pedido', 'Mostrando %1$d&ndash;%2$d de %3$d pedidos', $total, 'com primeiro e último pedido', 'wc-account-genius' ), $first, $last, $total );
					}
				}
				// phpcs:enable WordPress.Security
				?>
			</span>

			<?php $percentage = ( $current_page_orders / $total * 100 ); ?>
			<div class="progress w-100 my-3 mx-auto mx-md-0" style="max-width: 10rem; height: 4px;">
				<div class="progress-bar" role="progressbar" style="width: <?php echo esc_attr( $percentage ); ?>%;" aria-valuenow="<?php echo esc_attr( $percentage ); ?>" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="order-pagination woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="btn btn-outline-primary btn-sm rounded-3 button-loading woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php echo apply_filters( 'wc_account_genius_button_prev_text', esc_html__( 'Carregar pedidos anteriores', 'wc-account-genius' ) ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="btn btn-outline-primary btn-sm rounded-3 button-loading woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php echo apply_filters( 'wc_account_genius_button_next_text', esc_html__( 'Carregar mais pedidos', 'wc-account-genius' ) ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php else : ?>
		<div>
			<a class="woocommerce-Button btn btn-primary me-3 button-loading" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Ver produtos', 'wc-account-genius' ); ?></a>
			<span><?php esc_html_e( 'Nenhum pedido feito ainda.', 'wc-account-genius' ); ?></span>
		</div>
	<?php endif; ?>
	<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
</nav>
