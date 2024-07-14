<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package MeuMouse.com
 * @version 1.2.0
 */

defined('ABSPATH') || exit;

?> 

<div class="wc-account-genius-form-last-password d-flex align-items-center justify-content-center">
	<div class="col-md-6 col-lg-6 py-5 py-sm-6 py-md-7 mb-5 bg-white rounded-3 shadow-lg">
		<div class="account-genius-reset-password-message-success p-4">
            <?php wc_print_notice( esc_html__( 'O e-mail de redefinição de senha foi enviado.', 'wc-account-genius' ) ); ?>

            <?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

            <p class="text-center text-body"><?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', esc_html__( 'Um e-mail de redefinição de senha foi enviada para o endereço de e-mail da sua conta, mas pode levar alguns minutos para aparecer na sua caixa de entrada. Aguarde pelo menos 10 minutos antes de tentar novamente ou verifique sua caixa de spam.', 'wc-account-genius' ) ) ); ?></p>

            <?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
		</div>
	</div>
</div>