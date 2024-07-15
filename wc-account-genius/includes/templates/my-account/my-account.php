<?php

/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package MeuMouse.com
 * @version 2.1.0
 */

use Account_Genius\Init\Init;

defined('ABSPATH') || exit; ?>

<div class="wc-account-genius-my-account container pb-5">
	<div class="row">
		<div class="col-lg-4 mb-4 mb-lg-0">
			<div class="bg-light rounded-3 shadow-lg p-sm-3">
				<div class="px-4 py-4 mb-1 text-center">
					<div class="user-avatar">
						<?php echo get_avatar( get_current_user_id(), 150, '', esc_html( $current_user->display_name ), [ 'class' => 'd-block rounded-circle mx-auto my-2' ] ); ?>
					</div>
						<?php
						$user_id = get_current_user_id();
    					$picture_id = get_user_meta( $user_id, 'profile_pic', true );

						if ( Init::get_setting('enable_upload_avatar') === 'yes' ) : ?>
							<div class="container-actions-avatar">
								<?php if ( trim( $picture_id ) !== '' ) : ?>
									<a id="remove-avatar" class="btn btn-outline-danger btn-icon rounded-circle" href="<?php echo wc_customer_edit_account_url() . '?action=wc-account-genius-delete-avatar'; ?>">
										<i class='bx bx-trash-alt' ></i>
									</a>
								<?php endif; ?>

								<button id="upload-avatar" class="btn btn-primary rounded-circle" <?php echo trim( $picture_id ) === '' ? 'style="margin-right: -5rem;"' : ''; ?>>
									<i class='bx bx-camera'></i>
								</button>
							</div>
							<div id="upload-avatar-container">
								<div id="upload-avatar-content">
									<div id="upload-avatar-header">
										<button id="close-popup-avatar" class="btn-close fs-lg" aria-label="Fechar"></button>
									</div>
									<?php echo do_shortcode('[wc_account_genius_avatar_upload]'); ?>
								</div>
							</div>
					<?php endif; ?>
					
					<h6 class="mb-0 pt-1"><?php echo esc_html( $current_user->display_name ); ?></h6>
					<span class="text-muted fs-sm"><?php echo esc_html( $current_user->user_email ); ?></span>
				</div>

				<div class="d-lg-none px-4 pb-4 text-center mb-3">
					<a class="btn btn-primary px-5 mb-2" href="#account-menu" data-bs-toggle="collapse">
						<i class="bx bx-menu me-2"></i><?php echo esc_html__( 'Menu da conta', 'wc-account-genius');?>
					</a>
				</div>
				
				<?php do_action('woocommerce_account_navigation'); ?>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="d-flex flex-column h-100 bg-light rounded-3 shadow-lg p-4">
				<div class="py-2 p-md-3">
					<div class="d-sm-flex align-items-center justify-content-between pb-2">
	                  	<h1 class="h3 mb-3 text-center endpoint-titles"><?php wc_account_genius_endpoint_titles(); ?></h1>
	                </div>

					<div class="w-100">
						<?php
						/**
						 * My Account content.
						 *
						 * @since 1.0.0
						 */
						do_action('woocommerce_account_content'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>