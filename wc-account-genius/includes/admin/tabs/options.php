<?php

// Exit if accessed directly.
defined('ABSPATH') || exit; ?>

<div id="options" class="nav-content">
   <table class="form-table">
      <tr>
        <th>
           <?php echo esc_html__( 'Ativar substituição do modelo padrão', 'wc-account-genius' ) ?>
           <span class="wc-account-genius-description"><?php echo esc_html__('Ative esta opção para substituir o modelo padrão da página de Minha conta do WooCommerce.', 'wc-account-genius' ) ?></span>
        </th>
        <td>
           <div class="form-check form-switch">
              <input type="checkbox" class="toggle-switch" id="replace_default_template_my_account" name="replace_default_template_my_account" value="yes" <?php checked( self::get_setting( 'replace_default_template_my_account' ) == 'yes' ); ?> />
           </div>
        </td>
      </tr>
      <tr>
        <th>
           <?php echo esc_html__( 'Ativar substituição do modelo padrão de avisos', 'wc-account-genius' ) ?>
           <span class="wc-account-genius-description"><?php echo esc_html__('Ative esta opção para substituir o modelo padrão de avisos do WooCommerce.', 'wc-account-genius' ) ?></span>
        </th>
        <td>
           <div class="form-check form-switch">
              <input type="checkbox" class="toggle-switch" id="replace_default_notices" name="replace_default_notices" value="yes" <?php checked( self::get_setting( 'replace_default_notices' ) == 'yes' ); ?> />
           </div>
        </td>
      </tr>
      <tr>
        <th>
           <?php echo esc_html__( 'Ativar exibição de ícones', 'wc-account-genius' ) ?>
           <span class="wc-account-genius-description"><?php echo esc_html__('Ative esta opção para que os ícones sejam carregados no front-end.', 'wc-account-genius' ) ?></span>
        </th>
        <td>
           <div class="form-check form-switch">
              <input type="checkbox" class="toggle-switch" id="enable_icons" name="enable_icons" value="yes" <?php checked( self::get_setting( 'enable_icons' ) == 'yes' ); ?> />
           </div>
        </td>
      </tr>
      <tr>
         <th>
            <?php echo esc_html__( 'Ativar o envio de imagem de perfil', 'wc-account-genius' ) ?>
            <span class="wc-account-genius-description"><?php echo esc_html__('Ative esta opção para permitir que o usuário altere sua foto de perfil.', 'wc-account-genius' ) ?></span>
         </th>
         <td>
            <div class="form-check form-switch">
               <input type="checkbox" class="toggle-switch" id="enable_upload_avatar" name="enable_upload_avatar" value="yes" <?php checked( self::get_setting( 'enable_upload_avatar' ) == 'yes' ); ?> />
            </div>
         </td>
      </tr>
   </table>
</div>