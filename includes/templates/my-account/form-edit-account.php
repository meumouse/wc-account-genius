<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package MeuMouse.com
 * @version 1.1.0
 */

defined('ABSPATH') || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

    <div class="row">
    	<div class="col-sm-6">
            <div class="woocommerce-form-row woocommerce-form-row--first form-group">
				<label for="account_first_name" class="form-label"><?php esc_html_e( 'Nome', 'wc-account-genius' ); ?><sup class="text-danger ml-1">*</sup></label>
				<input type="text" class="form-control" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
        	</div>
        </div>

        <div class="col-sm-6">
            <div class="woocommerce-form-row woocommerce-form-row--last form-group">
                <label for="account_last_name" class="form-label"><?php esc_html_e( 'Sobrenome', 'wc-account-genius' ); ?><sup class="text-danger ml-1">*</sup></label>
                <input type="text" class="form-control" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
            </div>
        </div>
    </div>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row-wide form-group mb-3">
		<label for="account_display_name" class="form-label"><?php esc_html_e( 'Nome de exibição', 'wc-account-genius' ); ?><sup class="text-danger ml-1">*</sup></label>
		<input type="text" class="form-control" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" /> <small class="form-text text-muted"><?php esc_html_e( 'Será assim que seu nome será exibido na seção de contas e nas avaliações', 'wc-account-genius' ); ?></small>
	</div>

    <div class="woocommerce-form-row woocommerce-form-row--wide form-row-wide form-group mb-3">
		<label for="account_email" class="form-label"><?php esc_html_e( 'Endereço de e-mail', 'wc-account-genius' );?><sup class="text-danger ml-1">*</sup></label>
		<input type="email" class="form-control" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</div>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row-wide form-group mb-3">
		<label for="password_current" class="form-label">
			<?php esc_html_e( 'Senha atual (deixe em branco para não alterar)', 'wc-account-genius' ); ?>
		</label>
		<div class="cs-password-toggle w-100">
			<input type="password" class="form-control" name="password_current" id="password_current" autocomplete="off" />
			<label class="cs-password-toggle-btn">
				<input class="custom-control-input" type="checkbox">
				<i class="bx bx-show cs-password-toggle-indicator"></i>
			</label>
		</div>


	</div>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row-wide form-group mb-3">
		<label for="password_1" class="form-label"><?php esc_html_e( 'Nova senha (deixe em branco para não alterar)', 'wc-account-genius' ); ?></label>
		<div class="cs-password-toggle w-100">
			<input type="password" class="form-control" name="password_1" id="password_1" autocomplete="off" />
			<label class="cs-password-toggle-btn">
				<input class="custom-control-input" type="checkbox">
				<i class="bx bx-show cs-password-toggle-indicator"></i>
			</label>
		</div>	
	</div>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row-wide form-group mb-3">
		<label for="password_2" class="form-label"><?php esc_html_e( 'Confirmar nova senha', 'wc-account-genius' ); ?></label>
		<div class="cs-password-toggle w-100">
			<input type="password" class="form-control" name="password_2" id="password_2" autocomplete="off" />
			<label class="cs-password-toggle-btn">
				<input class="custom-control-input" type="checkbox">
				<i class="bx bx-show cs-password-toggle-indicator"></i>
			</label>
		</div>
	</div>


		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<div class="button-group pt-4">
			<div class="d-flex flex-wrap justify-content-end align-items-center">
				<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
				<button type="submit" class="btn btn-primary mt-3 mt-sm-0 button-loading" name="save_account_details" value="<?php esc_attr_e( 'Salvar alterações', 'wc-account-genius' ); ?>"><i class="fe-save font-size-lg mr-2"></i><?php esc_html_e( 'Salvar alterações', 'wc-account-genius' ); ?></button>
				<input type="hidden" name="action" value="save_account_details" />
			</div>
		</div>
			

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	        
	 </div>



</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
