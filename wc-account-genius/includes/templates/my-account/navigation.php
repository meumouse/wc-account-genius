<?php

/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @since 1.0.0
 * @version 2.1.0
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package MeuMouse.com
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

do_action('woocommerce_before_account_navigation'); ?>

<div id="account-menu" class="d-lg-block collapse pb-2">
		<h3 class="d-block bg-secondary fs-sm fw-semibold text-muted mb-0 px-4 py-3">
			<a href="<?php echo esc_url( wc_get_account_endpoint_url('dashboard') ); ?>" class="<?php echo wc_get_account_menu_item_classes('dashboard'); ?> text-muted"><?php echo esc_html__( 'Painel', 'wc-account-genius'); ?></a>
		</h3>
		<div class="account-genius-tab-items">
			<?php
			/**
			 * Before tab items hook
			 * 
			 * @since 2.0.0
			 */
			do_action('wc_account_genius_before_tab_items');

			$account_tabs = get_option('account_genius_get_tabs_options', array());
			$account_tabs = maybe_unserialize( $account_tabs );
			$count_tabs = count( $account_tabs );

			foreach ( $account_tabs as $index => $tab ) {
				// ignore deactive tabs
				if ( isset( $tab['native'] ) && $tab['native'] === 'yes' && isset( $tab['enabled'] ) && $tab['enabled'] !== 'yes' ) {
					continue;
				}

				// display border bottom on tab if is different of last tab
				if ( intval( $tab['priority'] ) < $count_tabs ) {
					$border_bottom = 'border-bottom: 1px solid #e2e5f1;';
				} else {
					$border_bottom = '';
				}

				$class = isset( $tab['class'] ) ? $tab['class'] : ''; ?>

				<a href="<?php echo isset( $tab['redirect'] ) && $tab['redirect'] === 'yes' ? $tab['link'] : esc_url( wc_get_account_endpoint_url( $tab['endpoint'] ) ); ?>" class="<?php echo wc_get_account_menu_item_classes( $tab['endpoint'] ) . ' ' . esc_attr( $class ) ?> d-flex align-items-center nav-link-style px-4 py-3" style="order: <?php echo $tab['priority'] ?>; <?php echo $border_bottom ?>">
					<?php if ( ! empty( $tab['icon'] ) ) : ?>
						<i class="<?php echo $tab['icon'] ?> fs-lg opacity-60 me-2"></i>
					<?php endif; 

					// display label tab if is different of empty
					if ( ! empty( $tab['label'] ) ) {
						echo $tab['label'];
					}

					// display orders count for endpoint 'orders'
					if ( $index === 'orders' ) : ?>
						<span class="text-muted fs-sm fw-normal ms-auto"><?php wc_account_genius_orders_count(); ?></span>
					<?php endif; 

					// display downloads count for endpoint 'downloads'
					if ( $index === 'downloads' ) : ?>
						<span class="text-muted fs-sm fw-normal ms-auto"><?php wc_account_genius_downloads_count(); ?></span>
					<?php endif; ?>
				</a>
				<?php
			}

			/**
			 * After tab items hook
			 * 
			 * @since 2.0.0
			 */
			do_action('wc_account_genius_after_tab_items'); ?>
		</div>
</div>

<?php do_action('woocommerce_after_account_navigation'); ?>