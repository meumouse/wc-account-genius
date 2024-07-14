<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
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


$wc_account_genius_forget_password_title = apply_filters( 'wc_account_genius_forget_password_title',  get_theme_mod('forget_password_title',    'Esqueceu sua senha?' ) );
$wc_account_genius_forget_password_desc = apply_filters( 'wc_account_genius_forget_password_desc',  get_theme_mod('forget_password_desc',    'Altere sua senha em três etapas fáceis. Isso ajuda a manter sua nova senha segura.' ) );

?>
<div class="wc-account-genius-form-last-password d-flex align-items-center justify-content-center">
	<div class="col-md-6 col-lg-6 py-5 py-sm-6 py-md-7 mb-5 bg-white rounded-3 shadow-lg">
		<div class="p-4">
			<?php do_action( 'woocommerce_before_lost_password_form' ); ?>
			
			<?php if ( ! empty ( $wc_account_genius_forget_password_title ) ): ?>
				<h1 class="h2 pb-3"><?php echo esc_html(  $wc_account_genius_forget_password_title ); ?></h1>
			<?php endif; ?>

			<?php if ( ! empty ( $wc_account_genius_forget_password_desc ) ): ?>
				<p class="fs-sm"><?php echo esc_html(  $wc_account_genius_forget_password_desc ); ?></p>
			<?php endif; ?>

			<?php if ( apply_filters( 'wc_account_genius_forget_password_desc', true ) ): ?>
				<ul class="list-unstyled fs-sm pb-1 mb-4">
	              	<li><span class="text-primary fw-semibold me-1">1.</span><?php echo esc_html__( 'Preencha seu e-mail abaixo.', 'wc-account-genius'); ?></li>
	              	<li><span class="text-primary fw-semibold me-1">2.</span><?php echo esc_html__( "Enviaremos um e-mail com um código temporário.", 'wc-account-genius' ); ?></li>
	              	<li><span class="text-primary fw-semibold me-1">3.</span><?php echo esc_html__( 'Use o código para alterar sua senha em nosso site seguro.','wc-account-genius'); ?></li>
	            </ul>
	        <?php endif; ?>

			<div class="bg-secondary rounded-3 px-3 py-4 p-sm-4">
				<form method="post" class="woocommerce-ResetPassword lost_reset_password">

                    <label class="form-label" for="user_login"><?php echo esc_html__('Insira seu endereço de e-mail', 'wc-account-genius');?></label>
	                <div class="input-group">
						<input class="form-control" type="text" name="user_login" id="user_login" autocomplete="username" />
						<input type="hidden" name="wc_reset_password" value="true" />
						<button type="submit" id="wc-account-genius-btn-reset-password" class="btn btn-primary button-loading" disabled value="<?php esc_attr_e( 'Redefinir senha', 'wc-account-genius' ); ?>"><?php esc_html_e( 'Redefinir senha', 'wc-account-genius' ); ?></button>
                    </div>


					<div class="clear"></div>

					<?php do_action( 'woocommerce_lostpassword_form' ); ?>

					<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

				</form>
			</div>
		</div>
	</div>
</div>
<?php
do_action( 'woocommerce_after_lost_password_form' );
