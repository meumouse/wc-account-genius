<?php

// Exit if accessed directly.
defined('ABSPATH') || exit; ?>

<div id="about" class="nav-content">
  <table class="form-table">
	<tr>
		<td class="d-grid">
			<h3 class="mb-4"><?php esc_html_e( 'Informações sobre a licença:', 'wc-account-genius' ); ?></h3>
			<span class="mb-2"><?php echo esc_html__( 'Status da licença:', 'wc-account-genius' ) ?>
				<?php if ( self::license_valid() ) : ?>
					<span class="badge bg-translucent-success rounded-pill"><?php _e(  'Válida', 'wc-account-genius' );?></span>
				<?php elseif ( empty( get_option('wc_account_genius_license_key') ) ) : ?>
					<span class="fs-sm"><?php _e(  'Nenhuma licença informada', 'wc-account-genius' );?></span>
				<?php else : ?>
					<span class="badge bg-translucent-danger rounded-pill"><?php _e(  'Inválida', 'wc-account-genius' );?></span>
				<?php endif; ?>
			</span>

			<span class="mb-2"><?php echo esc_html__( 'Recursos:', 'wc-account-genius' ) ?>
				<?php if ( self::license_valid() ) : ?>
					<span class="badge bg-translucent-primary rounded-pill"><?php _e(  'Pro', 'wc-account-genius' );?></span>
				<?php endif; ?>
			</span>

			<?php if ( self::license_valid() ) : ?>
				<span class="mb-2"><?php echo sprintf( esc_html__( 'Tipo da licença: %s', 'wc-account-genius' ), self::license_title() ) ?></span>
				<span class="mb-2"><?php echo sprintf( esc_html__( 'Licença expira em: %s', 'wc-account-genius' ), self::license_expire() ) ?></span>
				
				<span class="mb-2"><?php echo esc_html__( 'Sua chave de licença:', 'wc-account-genius' ) ?>
					<?php if ( isset( $this->responseObj->license_key ) ) :
						echo esc_attr( substr( $this->responseObj->license_key, 0, 9 ) . "XXXXXXXX-XXXXXXXX" . substr( $this->responseObj->license_key, -9 ) );
					else :
						echo __(  'Não disponível', 'wc-account-genius' );
					endif; ?>
				</span>
			<?php endif; ?>
		</td>
	</tr>

	<?php if ( self::license_valid() ) : ?>
		<tr>
			<td>
				<button id="wc_account_genius_deactive_license" name="wc_account_genius_deactive_license" class="btn btn-sm btn-primary button-loading" type="submit">
					<span><?php esc_attr_e( 'Desativar licença', 'wc-account-genius' ); ?></span>
				</button>
			</td>
		</tr>
	<?php else : ?>
		<tr>
			<td class="d-grid">
				<a class="btn btn-primary my-4 d-inline-flex w-fit" href="https://meumouse.com/plugins/account-genius/?utm_source=wordpress&utm_medium=about-section_&utm_campaign=account_genius" target="_blank">
					<svg class="flexify-license-key-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke-width="0"/><g stroke-linecap="round" stroke-linejoin="round"/><g> <path d="M12.3212 10.6852L4 19L6 21M7 16L9 18M20 7.5C20 9.98528 17.9853 12 15.5 12C13.0147 12 11 9.98528 11 7.5C11 5.01472 13.0147 3 15.5 3C17.9853 3 20 5.01472 20 7.5Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g></svg>
					<span><?php _e(  'Comprar licença', 'wc-account-genius' );?></span>
				</a>
				<span class="bg-translucent-success fw-medium rounded-2 px-3 py-2 mb-4 d-flex align-items-center w-fit">
					<svg class="icon icon-success me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M11 11h2v6h-2zm0-4h2v2h-2z"></path></svg>
					<?php echo esc_html__( 'Informe sua licença abaixo para desbloquear todos os recursos.', 'wc-account-genius' ) ?>
				</span>
				<span class="form-label d-block mt-2"><?php echo esc_html__( 'Código da licença', 'wc-account-genius' ) ?></span>
				<div class="input-group" style="width: 550px;">
					<input class="form-control" type="text" placeholder="XXXXXXXX-XXXXXXXX-XXXXXXXX-XXXXXXXX" id="wc_account_genius_license_key" name="wc_account_genius_license_key" size="50" value="<?php echo get_option( 'wc_account_genius_license_key' ) ?>" />
					<button id="wc_account_genius_active_license" name="wc_account_genius_active_license" class="btn btn-primary button-loading" type="submit">
						<span class="span-inside-button-loader"><?php esc_attr_e( 'Ativar licença', 'wc-account-genius' ); ?></span>
					</button>
				</div>
			</td>
		</tr>
	<?php endif; ?>
	
	<tr class="container-separator"></tr>

	<tr class="w-75 mt-5">
		<td>
			<h3 class="h2 mt-0"><?php esc_html_e( 'Status do sistema:', 'wc-account-genius' ); ?></h3>
			<h4 class="mt-4"><?php esc_html_e( 'WordPress', 'wc-account-genius' ); ?></h4>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'Versão do WordPress:', 'wc-account-genius' ); ?></span>
				<span class="ms-2"><?php echo esc_html( get_bloginfo( 'version' ) ); ?></span>
			</div>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'WordPress Multisite:', 'wc-account-genius' ); ?></span>
				<span class="ms-2"><?php echo is_multisite() ? esc_html__( 'Sim', 'wc-account-genius' ) : esc_html__( 'Não', 'wc-account-genius' ); ?></span>
			</div>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'Modo de depuração do WordPress:', 'wc-account-genius' ); ?></span>
				<span class="ms-2"><?php echo defined( 'WP_DEBUG' ) && WP_DEBUG ? esc_html__( 'Ativo', 'wc-account-genius' ) : esc_html__( 'Desativado', 'wc-account-genius' ); ?></span>
			</div>

			<h4 class="mt-4"><?php esc_html_e( 'WooCommerce', 'wc-account-genius' ); ?></h4>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'Versão do WooCommerce:', 'wc-account-genius' ); ?></span>
				<span class="ms-2">
					<?php if( version_compare( WC_VERSION, '6.0', '<' ) ) : ?>
						<span class="badge bg-translucent-danger">
							<span>
								<?php echo esc_html( WC_VERSION ); ?>
							</span>
							<span>
								<?php esc_html_e( 'A versão mínima exigida do WooCommerce é 6.0', 'wc-account-genius' ); ?>
							</span>
						</span>
					<?php else : ?>
						<span class="badge bg-translucent-success">
							<?php echo esc_html( WC_VERSION ); ?>
						</span>
					<?php endif; ?>
				</span>
			</div>

			<h4 class="mt-4"><?php esc_html_e( 'Servidor', 'wc-account-genius' ); ?></h4>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'Versão do PHP:', 'wc-account-genius' ); ?></span>
				<span class="ms-2">
					<?php if ( version_compare( PHP_VERSION, '7.2', '<' ) ) : ?>
						<span class="badge bg-translucent-danger">
							<span>
								<?php echo esc_html( PHP_VERSION ); ?>
							</span>
							<span>
								<?php esc_html_e( 'A versão mínima exigida do PHP é 7.2', 'wc-account-genius' ); ?>
							</span>
						</span>
					<?php else : ?>
						<span class="badge bg-translucent-success">
							<?php echo esc_html( PHP_VERSION ); ?>
						</span>
					<?php endif; ?>
				</span>
			</div>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'DOMDocument:', 'wc-account-genius' ); ?></span>
				<span class="ms-2">
					<span>
						<?php if ( ! class_exists( 'DOMDocument' ) ) : ?>
							<span class="badge bg-translucent-danger">
								<?php esc_html_e( 'Não', 'wc-account-genius' ); ?>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php esc_html_e( 'Sim', 'wc-account-genius' ); ?>
							</span>
						<?php endif; ?>
					</span>
				</span>
			</div>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'Extensão cURL:', 'wc-account-genius' ); ?></span>
				<span class="ms-2">
					<span>
						<?php if ( ! extension_loaded('curl') ) : ?>
							<span class="badge bg-translucent-danger">
								<?php esc_html_e( 'Não', 'wc-account-genius' ); ?>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php esc_html_e( 'Sim', 'wc-account-genius' ); ?>
							</span>
							<span>
								<?php echo sprintf( __( 'Versão %s', 'wc-account-genius' ), curl_version()['version'] ) ?>
							</span>
						<?php endif; ?>
					</span>
				</span>
			</div>
			<div class="d-flex mb-2">
				<span><?php esc_html_e( 'Extensão OpenSSL:', 'wc-account-genius' ); ?></span>
				<span class="ms-2">
					<span>
						<?php if ( ! extension_loaded('openssl') ) : ?>
							<span class="badge bg-translucent-danger">
								<?php esc_html_e( 'Não', 'wc-account-genius' ); ?>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php esc_html_e( 'Sim', 'wc-account-genius' ); ?>
							</span>
							<span>
								<?php echo OPENSSL_VERSION_TEXT ?>
							</span>
						<?php endif; ?>
					</span>
				</span>
			</div>

			<?php if ( function_exists('ini_get') ) : ?>
				<div class="d-flex mb-2">
					<span>
						<?php $post_max_size = ini_get( 'post_max_size' ); ?>

						<?php esc_html_e( 'Tamanho máximo da postagem do PHP:', 'wc-account-genius' ); ?>
					</span>
					<span class="ms-2">
						<?php if ( wp_convert_hr_to_bytes( $post_max_size ) < 64000000 ) : ?>
							<span>
								<span class="badge bg-translucent-danger">
									<?php echo esc_html( $post_max_size ); ?>
								</span>
								<span>
									<?php esc_html_e( 'Valor mínimo recomendado é 64M', 'wc-account-genius' ); ?>
								</span>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php echo esc_html( $post_max_size ); ?>
							</span>
						<?php endif; ?>
					</span>
				</div>
				<div class="d-flex mb-2">
					<span>
						<?php $max_execution_time = ini_get( 'max_execution_time' ); ?>
						<?php esc_html_e( 'Limite de tempo do PHP:', 'wc-account-genius' ); ?>
					</span>
					<span class="ms-2">
						<?php if ( $max_execution_time < 180 ) : ?>
							<span>
								<span class="badge bg-translucent-danger">
									<?php echo esc_html( $max_execution_time ); ?>
								</span>
								<span>
									<?php esc_html_e( 'Valor mínimo recomendado é 180', 'wc-account-genius' ); ?>
								</span>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php echo esc_html( $max_execution_time ); ?>
							</span>
						<?php endif; ?>
					</span>
				</div>
				<div class="d-flex mb-2">
					<span>
						<?php $max_input_vars = ini_get( 'max_input_vars' ); ?>
						<?php esc_html_e( 'Variáveis máximas de entrada do PHP:', 'wc-account-genius' ); ?>
					</span>
					<span class="ms-2">
						<?php if ( $max_input_vars < 10000 ) : ?>
							<span>
								<span class="badge bg-translucent-danger">
									<?php echo esc_html( $max_input_vars ); ?>
								</span>
								<span>
									<?php esc_html_e( 'Valor mínimo recomendado é 10000', 'wc-account-genius' ); ?>
								</span>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php echo esc_html( $max_input_vars ); ?>
							</span>
						<?php endif; ?>
					</span>
				</div>
				<div class="d-flex mb-2">
					<span>
						<?php $memory_limit = ini_get( 'memory_limit' ); ?>
						<?php esc_html_e( 'Limite de memória do PHP:', 'wc-account-genius' ); ?>
					</span>
					<span class="ms-2">
						<?php if ( wp_convert_hr_to_bytes( $memory_limit ) < 128000000 ) : ?>
							<span>
								<span class="badge bg-translucent-danger">
									<?php echo esc_html( $memory_limit ); ?>
								</span>
								<span>
									<?php esc_html_e( 'Valor mínimo recomendado é 128M', 'wc-account-genius' ); ?>
								</span>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php echo esc_html( $memory_limit ); ?>
							</span>
						<?php endif; ?>
					</span>
				</div>
				<div class="d-flex mb-2">
					<span>
						<?php $upload_max_filesize = ini_get( 'upload_max_filesize' ); ?>
						<?php esc_html_e( 'Tamanho máximo de envio do PHP:', 'wc-account-genius' ); ?>
					</span>
					<span class="ms-2">
						<?php if ( wp_convert_hr_to_bytes( $upload_max_filesize ) < 64000000 ) : ?>
							<span>
								<span class="badge bg-translucent-danger">
									<?php echo esc_html( $upload_max_filesize ); ?>
								</span>
								<span>
									<?php esc_html_e( 'Valor mínimo recomendado é 64M', 'wc-account-genius' ); ?>
								</span>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php echo esc_html( $upload_max_filesize ); ?>
							</span>
						<?php endif; ?>
					</span>
				</div>
				<div class="d-flex mb-2">
					<span><?php esc_html_e( 'Função PHP "file_get_content":', 'wc-account-genius' ); ?></span>
					<span class="ms-2">
						<?php if ( ! ini_get( 'allow_url_fopen' ) ) : ?>
							<span class="badge bg-translucent-danger">
								<?php esc_html_e( 'Desligado', 'wc-account-genius' ); ?>
							</span>
						<?php else : ?>
							<span class="badge bg-translucent-success">
								<?php esc_html_e( 'Ligado', 'wc-account-genius' ); ?>
							</span>
						<?php endif; ?>
					</span>
				</div>
			<?php endif; ?>
		</td>
		<tr>
			<td>
				<a class="btn btn-sm btn-outline-danger" target="_blank" href="https://meumouse.com/reportar-problemas/"><?php echo esc_html__( 'Reportar problemas', 'wc-account-genius' ); ?></a>

				<?php if ( get_option('account_genius_alternative_license_activation') !== 'yes' ) : ?>
					<button class="btn btn-sm btn-outline-primary ms-2 button-loading" name="account_genius_clear_activation_cache"><?php echo esc_html__(  'Limpar cache de ativação', 'wc-account-genius' ); ?></button>
				<?php endif;?>
			</td>
		</tr>
	</tr>
  </table>
</div>