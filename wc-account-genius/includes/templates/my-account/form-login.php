<?php
/**
* Login Form
*
* This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
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
exit; // Exit if accessed directly.
}

$is_registration_enabled = false;

if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ){
	$is_registration_enabled = true;

}


$account_style = apply_filters( 'account_genius_my_account_style', get_theme_mod( 'myaccount_style', 'style-v3' ) );


$account_genius_login_form_title = apply_filters( 'account_genius_login_title', get_theme_mod('login_title', 'Entrar' ) );
$account_genius_login_form_desc = apply_filters( 'account_genius_login_description', get_theme_mod('login_desc', 'Entre em sua conta usando o e-mail e a senha fornecidos durante o registro.'));
$account_genius_register_form_title = apply_filters( 'account_genius_register_title', get_theme_mod('register_title', 'Registrar-se') );
$account_genius_register_form_desc = apply_filters( 'account_genius_register_description', get_theme_mod('register_desc', 'O registro leva menos de um minuto, mas oferece controle total sobre seus pedidos.'));
$account_genius_login_heading_alignment = apply_filters( 'account_genius_login_heading_alignment', get_theme_mod( 'login_heading_alignment', 'text-left' ) );
$account_genius_register_heading_alignment = apply_filters( 'account_genius_register_heading_alignment', get_theme_mod( 'register_heading_alignment', 'text-left' ) );
$account_genius_form_footer_alignment = apply_filters( 'account_genius_form_footer_alignment', get_theme_mod( 'form_footer_alignment', 'text-left' ) );


if ( $account_style == 'style-v3' ) {
	$container_additional_classes = 'wc-account-genius-form-login d-flex align-items-center pt-7 pb-3 pb-md-4';
	$title_class = 'h2';
	$desc_class ='fs-sm text-muted mb-4';
} elseif ( $account_style == 'style-v2' ) {
	$container_additional_classes = 'd-flex justify-content-center align-items-center pt-7 pb-4';
	$title_class = 'h2';
	$desc_class ='fs-sm text-muted mb-4';
} else {
	$container_additional_classes = 'sigin-container py-5 py-md-7';
	$title_class = 'h3 pt-1';
	$desc_class ='fs-sm text-muted';
} 

if ( $account_style == 'style-v3' && (int) get_theme_mod( 'myaccount_image') > 0 ) : ?>
	<div class="d-none d-md-block position-absolute w-50 h-100 bg-size-cover" style="top: 0; right:0; background-image: url( <?php echo( wp_get_attachment_image_url( get_theme_mod( 'myaccount_image' ), 'full' ) ); ?> );">
	</div>
<?php endif; ?>

<section class="<?php echo esc_attr( $container_additional_classes ); ?>" style="flex: 1 0 auto;">
	<?php

	if ( $account_style == 'style-v3' ) {
		?>
		<div class="w-100 pt-3">
			<div class="row justify-content-center">
			<?	if ( $account_style == 'style-v3' && (int) get_theme_mod( 'myaccount_image') == 0 ) { ?>
				
				<div class="<?php echo esc_attr( (int) get_theme_mod( 'myaccount_image') == 0  ? 'col-md-6 col-lg-6 mb-5 bg-white p-5 rounded-3 shadow-lg zindex-5' : 'col-lg-4 col-md-6 offset-lg-1' ); ?>">

				<?
			}
	}
				elseif ( $account_style == 'style-v2' ) {
					?>

					<div class="cs-signin-form mt-3 mx-auto bg-size-cover" style="bottom: 0; left: 0; background-image: url(<?php echo ( wp_get_attachment_image_url( get_theme_mod( 'myaccount_image' ), 'full' ) ); ?>);	">
						<div class="cs-signin-form-inner pb-4">

						<?php } else { ?>

							<div class="row form-login-row align-items-center pt-2">
							<?php } 

							do_action( 'woocommerce_before_customer_login_form' ); ?>
							<?php if ( $account_style == 'style-v1' ): ?>

								<div class="col-md-6 col-lg-5 mb-5 mb-md-0<?php if ( ! $is_registration_enabled ) echo esc_attr( ' mx-auto');?> ">
									<div class="bg-white px-4 py-5 p-sm-5 rounded-3 shadow-lg zindex-5">
										<?php else: ?>

											<div class="cs-view show" id="signin-view">
											<?php endif; ?>
											<?php if ( ! empty ( $account_genius_login_form_title ) || ! empty ( $account_genius_login_form_desc ) ): ?>
											<div class="form-heading <?php echo esc_attr( $account_genius_login_heading_alignment ); ?>">
												<?php if ( ! empty ( $account_genius_login_form_title ) ): ?>
													<h1 class="<?php echo esc_attr( $title_class ); ?>"><?php echo esc_html( $account_genius_login_form_title ); ?></h1>
												<?php endif; ?>

												<?php if ( ! empty ( $account_genius_login_form_desc ) ): ?>
													<p class="<?php echo esc_attr( $desc_class ); ?>"><?php echo esc_html( $account_genius_login_form_desc ); ?></p>
												<?php endif; ?>
											</div>
										<?php endif; ?>


										<form method="post">

											<?php do_action( 'woocommerce_login_form_start' ); ?>

											<div class="input-group-overlay form-group mb-3">
												<div class="input-group-prepend-overlay">
													<span class="input-group-text"><i class="bx bx-envelope"></i></span>
												</div>

												<label class="text-muted" for="username"><?php esc_html_e( 'Usuário ou e-mail', 'wc-account-genius' ); ?></label>

												<input type="text" class="account-genius-user-email-login form-control" name="username" placeholder="<?php echo esc_html_e( 'E-mail', 'wc-account-genius' ); ?>" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
											</div>

											<div class="input-group-overlay cs-password-toggle form-group mb-3">
												<div class="input-group-prepend-overlay">
													<span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
												</div>

												<label class="text-muted" for="password"><?php esc_html_e( 'Senha', 'wc-account-genius' ); ?></label>
												<input class="account-genius-password-login form-control" type="password" name="password" placeholder="<?php echo esc_html_e( 'Senha', 'wc-account-genius' ); ?>" id="password" autocomplete="current-password" />
												
												<label class="cs-password-toggle-btn">
													<input class="custom-control-input" type="checkbox">
													<i class="bx bx-show cs-password-toggle-indicator"></i>
												</label>
											</div>

											<?php do_action( 'woocommerce_login_form' ); ?>

											<div class="forget-password-row d-flex justify-content-between align-items-center form-group my-4">

												<div class="rememberme-check">
													<input class="form-check-input" name="rememberme" type="checkbox" id="keep-signed-2" value="forever">
													<label class="text-muted p-0" for="keep-signed-2"><?php echo esc_html__( 'Lembrar de mim', 'wc-account-genius' ); ?>
													</label>
												</div>
												<span class="woocommerce-LostPassword lost_password mt-3 mt-lg-0">
													<a class="nav-link-style fs-sm fw-normal" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Esqueceu sua senha?', 'wc-account-genius' ); ?></a>
												</span>
											</div>

											<div class="d-flex justify-content-between mt-2 login-actions">
												<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
												<button type="submit" class="btn btn-primary button-loading" name="login" value="<?php esc_attr_e( 'Entrar', 'wc-account-genius' ); ?>"><?php esc_html_e( 'Entrar', 'wc-account-genius' ); ?></button>

												<?php if ( $is_registration_enabled && $account_style !== 'style-v1' ) : ?>
													<p class="fs-sm pt-3 mb-0 <?php echo esc_attr( $account_genius_form_footer_alignment ); ?>"><?php esc_html_e( 'Não tem uma conta?', 'wc-account-genius' ); ?>
													<a id="register-tab" class="font-weight-medium login-register-tab-switcher" href="#" data-view="#signup-view"><?php esc_html_e( 'Registrar-se', 'wc-account-genius' ); ?></a></p>
												<?php endif; ?>
											</div>

											<?php do_action( 'woocommerce_login_form_end' ); ?>

										</form>
										<?php if ( $account_style == 'style-v1' ): ?>

										</div><!-- bg-white -->
									</div><!-- col-md-6 -->

									<?php else: ?>

									</div><!-- #signin-view -->


								<?php endif; ?>


								<?php if ( $is_registration_enabled ) : ?>
									<?php if ( $account_style == 'style-v1') : ?>
										<div class="col-md-6 offset-lg-1">
											<?php else: ?>
												<div class="cs-view" id="signup-view">
												<?php endif; ?>

												<?php if ( ! empty ( $account_genius_register_form_title ) || ! empty ( $account_genius_register_form_desc ) ): ?>
												<div class="form-heading <?php echo esc_attr( $account_genius_register_heading_alignment ); ?>">
													<?php if ( ! empty ( $account_genius_register_form_title ) ): ?>
														<h1 class="<?php echo esc_attr( $title_class ); ?>"><?php echo esc_html( $account_genius_register_form_title ); ?></h1>
													<?php endif; ?>

													<?php if ( ! empty ( $account_genius_register_form_desc ) ): ?>
														<p class="<?php echo esc_attr( $desc_class ); ?>"><?php echo esc_html( $account_genius_register_form_desc ); ?></p>
													<?php endif; ?>
												</div>
											<?php endif; ?>

											<form method="post" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

												<?php do_action( 'woocommerce_register_form_start' ); ?>
												<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

													<div class="form-group">
														<label class="text-muted" for="reg_username"><?php esc_html_e( 'Usuário', 'wc-account-genius' ); ?></label>
														<input type="text" class="account-genius-reg-username form-control" name="username" id="reg_username" placeholder="<?php echo esc_html_e( 'Nome completo', 'wc-account-genius' ); ?>" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
													</div>

												<?php endif; ?>
												<div class="form-group mb-3">
													<label class="text-muted" for="reg_email"><?php esc_html_e( 'Seu melhor e-mail', 'wc-account-genius' ); ?></label>
													<input type="email" class="account-genius-reg-email form-control" name="email" id="reg_email" autocomplete="email" placeholder="<?php echo esc_html_e( 'E-mail', 'wc-account-genius' ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
												</div>

												<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

													<div class="cs-password-toggle form-group mb-3">
														<label class="text-muted" for="reg_password"><?php esc_html_e( 'Senha', 'wc-account-genius' ); ?></label>
														<input type="password" class="account-genius-reg-password form-control" name="password" id="reg_password" placeholder="<?php echo esc_html_e( 'Senha', 'wc-account-genius' ); ?>" autocomplete="new-password" />
														
														<label class="cs-password-toggle-btn">
															<input class="custom-control-input" type="checkbox">
															<i class="bx bx-show cs-password-toggle-indicator"></i>
														</label>
													</div>

													<div class="cs-password-toggle form-group mb-3">
														<label class="form-label text-muted" for="con_password"><?php esc_html_e( 'Confirmar senha', 'wc-account-genius' ); ?></label>
														<input type="password" class="account-genius-reg-con-password form-control" name="password" id="con_password" placeholder="<?php echo esc_html_e( 'Confirmar senha', 'wc-account-genius' ); ?>" autocomplete="new-password" />
														
														<label class="cs-password-toggle-btn">
															<input class="custom-control-input" type="checkbox">
															<i class="bx bx-show cs-password-toggle-indicator"></i>
														</label>
													</div>

													<?php else : ?>

														<p><?php esc_html_e( 'Sua senha será gerada e enviada para seu e-mail.', 'wc-account-genius' ); ?></p>

													<?php endif; ?>

													<?php do_action( 'woocommerce_register_form' ); ?>

													<div class="d-flex justify-content-between mt-3 register-actions">
														<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
														<button type="submit" class="btn btn-primary button-loading" name="register" value="<?php esc_attr_e( 'Registrar-se', 'wc-account-genius' ); ?>"><?php esc_html_e( 'Registrar-se', 'wc-account-genius' ); ?></button>

														<?php if (  $account_style !== 'style-v1' ) : ?>
															<p class="fs-sm pt-3 mb-0 <?php echo esc_attr( $account_genius_form_footer_alignment ); ?>"><?php esc_html_e( 'Já tem uma conta? ', 'wc-account-genius' );?><a id="login-tab" class="font-weight-medium login-register-tab-switcher" href="#" data-view="#signin-view"><?php echo esc_html__( 'Entrar', 'wc-account-genius' ); ?></a></p>
														<?php endif; ?>
													</div>


													<?php do_action( 'woocommerce_register_form_end' ); ?>

												</form>

												<?php if ( $account_style == 'style-v1') : ?>
												</div><!-- col-md-6 offset-lg-1 -->
												<?php else: ?>
												</div><!-- #signup-view -->
											<?php endif; ?>
										<?php endif; ?>

		
									<?php if ( $account_style == 'style-v3' ){ ?>
									</div><!--.col-lg-4 col-md-6 offset-lg-1-->
								</div><!--.row-->
							</div><!--.w-100 pt-3-->
						<?php } elseif (  $account_style == 'style-v2' ) { ?>
						</div><!--.cs-signin-form-inner-->
					</div><!--.cs-signin-form-->
				<?php } else { ?>
			</div><!--.row-->
	<?php } ?>

</section>
		<?php do_action( 'woocommerce_after_customer_login_form' ); ?>