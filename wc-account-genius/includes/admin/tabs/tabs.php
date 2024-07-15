<?php

// Exit if accessed directly.
defined('ABSPATH') || exit; ?>

<div id="tabs" class="nav-content px-5">
   <div id="account-tabs">
      <ul class="sortable ps-0">
         <?php

         $account_tabs = get_option('account_genius_get_tabs_options', array());
         $account_tabs = maybe_unserialize( $account_tabs );

         foreach ( $account_tabs as $index => $tab ) :
            $current_endpoint_tab = isset( $account_tabs[$index]['endpoint'] ) ? $account_tabs[$index]['endpoint'] : '';
            $current_icon_tab = isset( $account_tabs[$index]['icon'] ) ? $account_tabs[$index]['icon'] : '';
            $current_name_tab = isset( $account_tabs[$index]['label'] ) ? $account_tabs[$index]['label'] : ''; 
            $current_tab_content = isset( $account_tabs[$index]['content'] ) ? $account_tabs[$index]['content'] : ''; 
            $current_tab_link = isset( $account_tabs[$index]['link'] ) ? $account_tabs[$index]['link'] : '';
            $current_tab_class = isset( $account_tabs[$index]['class'] ) ? $account_tabs[$index]['class'] : ''; ?>
            
            <li id="<?php echo esc_html( $index ); ?>" class="tab-item <?php echo isset( $tab['enabled'] ) && $tab['enabled'] !== 'yes' && isset( $tab['native'] ) && $tab['native'] === 'yes' ? 'inactive' : '' ?>" data-native="<?php echo esc_html( $tab['native'] ) ?>" data-priority="<?php echo esc_html( $tab['priority'] ) ?>">
               <input type="hidden" class="change-priority" name="account_tabs[<?php echo $index; ?>][priority]" value="<?php echo esc_html( $tab['priority'] ) ?>">
               <input type="hidden" class="get-native-tab" name="account_tabs[<?php echo $index; ?>][native]" value="<?php echo esc_html( $tab['native'] ) ?>">
               <input type="hidden" class="get-endpoint-tab" name="account_tabs[<?php echo $index; ?>][endpoint]" value="<?php echo esc_html( $tab['native'] ) ?>">
               <input type="hidden" class="set-array-key-tab" name="account_tabs[<?php echo $index; ?>][array_key]" value="<?php echo $index; ?>">
               <input type="hidden" class="get-content-tab" name="account_tabs[<?php echo $index; ?>][content]" value="<?php echo $current_tab_content ?>">
               
               <i class="<?php echo esc_html( $tab['icon'] ) ?>"></i>
               <span class="tab-name"><?php echo esc_html( $tab['label'] ) ?></span>
               <button class="account-genius-tabs-trigger btn btn-sm btn-outline-primary ms-auto rounded-3" data-trigger="<?php echo esc_html( $index ) ?>"><?php echo esc_html__( 'Editar guia', 'wc-account-genius' ) ?></button>
               
               <div class="account-genius-tabs-container">
                  <div class="account-genius-tabs-content">
                     <div class="account-genius-tabs-header">
                        <h5 class="account-genius-tabs-popup-title"><?php echo sprintf( __('Configurar guia <strong class="tab-name">%s</strong>', 'wc-account-genius'), esc_html( $tab['label'] ) ); ?></h5>
                        <button class="account-genius-tabs-close-popup btn-close fs-lg" aria-label="<?php esc_html( 'Fechar', 'wc-account-genius' ); ?>"></button>
                     </div>

                     <div class="tab-conditions">
                        <table class="form-table">
                           <?php if ( isset( $tab['native'] ) && $tab['native'] === 'yes' ) : ?>
                              <tr>
                                 <th class="w-50">
                                    <?php echo esc_html__( 'Ativar/Desativar esta guia', 'wc-account-genius' ) ?>
                                    <span class="wc-account-genius-description"><?php echo esc_html__('Esta é uma guia nativa do WooCommerce e não pode ser removida, apenas desativada.', 'wc-account-genius' ) ?></span>
                                 </th>
                                 <td class="w-50">
                                    <div class="form-check form-switch">
                                       <input type="checkbox" class="toggle-switch toggle-active-tab" name="account_tabs[<?php echo $index; ?>][enabled]" value="yes" <?php checked( $account_tabs[$index]['enabled'] == 'yes' ); ?> />
                                    </div>
                                 </td>
                              </tr>
                           <?php endif; ?>

                           <tr>
                              <th class="w-50">
                                 <?php echo esc_html__( 'Nome da guia', 'wc-account-genius' ) ?>
                                 <span class="wc-account-genius-description"><?php echo esc_html__('Define o título que será exibido para esta guia.', 'wc-account-genius' ) ?></span>
                              </th>
                              <td class="w-50">
                                 <input type="text" class="get-name-tab form-control" name="account_tabs[<?php echo $index; ?>][label]" value="<?php echo $current_name_tab ?>"/>
                              </td>
                           </tr>

                           <tr>
                              <th class="w-50">
                                 <?php echo esc_html__( 'Endpoint da guia', 'wc-account-genius' ) ?>
                                 <span class="wc-account-genius-description"><?php echo esc_html__('Define o endpoint que será usado como link permanente para esta guia.', 'wc-account-genius' ) ?></span>
                              </th>
                              <td class="w-50">
                                 <input type="text" class="set-endpoint-tab form-control" name="account_tabs[<?php echo $index; ?>][endpoint]" value="<?php echo $current_endpoint_tab ?>"/>
                              </td>
                           </tr>

                           <tr>
                              <th class="w-50">
                                 <?php echo esc_html__( 'Ícone da guia', 'wc-account-genius' ) ?>
                                 <span class="wc-account-genius-description"><?php echo esc_html__('Define o ícone que será exibido ao lado do título desta guia. Ou deixe em branco para não exibir.', 'wc-account-genius' ) ?></span>
                              </th>
                              <td class="w-50">
                                 <div class="input-group">
                                    <button class="display-icon-tab fs-xg btn btn-outline-secondary d-flex align-items-center">
                                       <i class="<?php echo esc_attr( $tab['icon'] ) ?>"></i>
                                    </button>
                                    <input type="text" class="set-icon-tab form-control" name="account_tabs[<?php echo $index; ?>][icon]" value="<?php echo $current_icon_tab ?>" placeholder="<?php echo esc_html__('Classe do ícone Boxicons.', 'wc-account-genius' ) ?>"/>
                                 </div>
                              </td>
                           </tr>

                           <tr>
                              <th class="w-50">
                                 <?php echo esc_html__( 'Classe CSS da guia', 'wc-account-genius' ) ?>
                                 <span class="wc-account-genius-description"><?php echo esc_html__('Permite definir classes CSS personalizadas para esta guia.', 'wc-account-genius' ) ?></span>
                              </th>
                              <td class="w-50">
                                 <input type="text" class="form-control" name="account_tabs[<?php echo $index; ?>][class]" value="<?php echo $current_tab_class ?>" placeholder="<?php echo esc_html__('Classe CSS personalizada.', 'wc-account-genius' ) ?>"/>
                              </td>
                           </tr>

                           <?php if ( isset( $tab['native'] ) && $tab['native'] !== 'yes' ) : ?>
                              <tr class="redirect-link-content <?php echo isset( $account_tabs[$index]['redirect'] ) && $account_tabs[$index]['redirect'] == 'yes' ? 'd-none' : ''; ?>">
                                 <th class="w-50">
                                    <?php echo esc_html__( 'Conteúdo da guia', 'wc-account-genius' ) ?>
                                    <span class="wc-account-genius-description"><?php echo esc_html__('Coloque aqui o conteúdo que você deseja exibir na guia. É permitido HTML e shortcodes. Ex.: [content id="10580"]', 'wc-account-genius' ) ?></span>
                                 </th>
                                 <td class="w-50">
                                    <div class="form-check form-switch">
                                       <textarea class="form-control" name="account_tabs[<?php echo $index; ?>][content]"><?php echo $current_tab_content ?></textarea>
                                    </div>
                                 </td>
                              </tr>

                              <tr>
                                 <th class="w-50">
                                    <?php echo esc_html__( 'Redirecionar para outro link', 'wc-account-genius' ) ?>
                                    <span class="wc-account-genius-description"><?php echo esc_html__('Quando o usuário acessar esta guia, será redirecionado para outro link do site, ou externo.', 'wc-account-genius' ) ?></span>
                                 </th>
                                 <td class="w-50">
                                    <div class="form-check form-switch">
                                       <input type="checkbox" class="toggle-switch enable-redirect-link" name="account_tabs[<?php echo $index; ?>][redirect]" value="yes" <?php checked( isset( $account_tabs[$index]['redirect'] ) && $account_tabs[$index]['redirect'] == 'yes' ); ?> />
                                    </div>
                                 </td>
                              </tr>

                              <tr class="redirect-link <?php echo isset( $account_tabs[$index]['redirect'] ) && $account_tabs[$index]['redirect'] == 'yes' ? '' : 'd-none'; ?>">
                                 <th class="w-50">
                                    <?php echo esc_html__( 'Link de redirecionamento', 'wc-account-genius' ) ?>
                                    <span class="wc-account-genius-description"><?php echo esc_html__('Informe o link de destino da guia.', 'wc-account-genius' ) ?></span>
                                 </th>
                                 <td class="w-50">
                                    <div class="form-check form-switch">
                                       <input type="text" class="form-control" name="account_tabs[<?php echo $index; ?>][link]" value="<?php echo esc_url( $current_tab_link ); ?>" />
                                    </div>
                                 </td>
                              </tr>
                           <?php endif; ?>
                        </table>

                     </div>
                  </div>
               </div>

               <?php if ( isset( $tab['native'] ) && $tab['native'] !== 'yes' ) : ?>
                  <button class="btn btn-outline-danger btn-icon ms-3 rounded-3 exclude-tab" data-exclude="<?php echo esc_html( $index ) ?>">
                     <i class="bx bx-trash-alt fs-lg"></i>
                  </button>
               <?php endif; ?>
            </li>
         <?php endforeach; ?>
      </ul>
      
      <button id="add_new_tab_item" class="btn btn-primary d-flex align-items-center mt-5">
         <i class="bx bx-plus fs-lg me-2"></i>
         <span><?php echo esc_html__( 'Adicionar nova guia', 'wc-account-genius' ) ?></span>
      </button>

      <div id="add_new_tab_container">
         <div class="popup-content">
            <div class="popup-header justify-content-between">
               <h5 class="popup-title"><?php echo esc_html__( 'Configurar nova guia', 'wc-account-genius' ); ?></h5>
               <button id="add_new_tab_close" class="btn-close fs-lg" aria-label="<?php echo esc_html__( 'Fechar', 'wc-account-genius' ); ?>"></button>
            </div>
            <div class="popup-body">
               <table class="form-table">
                  <tr>
                     <th class="w-50">
                        <?php echo esc_html__( 'Nome da guia', 'wc-account-genius' ) ?>
                        <span class="wc-account-genius-description"><?php echo esc_html__('Informe o título que será exibido para a nova guia.', 'wc-account-genius' ) ?></span>
                     </th>
                     <td class="w-50">
                        <input type="text" class="form-control get-name-tab" id="add_new_tab_name" value=""/>
                     </td>
                  </tr>

                  <tr>
                     <th class="w-50">
                        <?php echo esc_html__( 'Endpoint da guia', 'wc-account-genius' ) ?>
                        <span class="wc-account-genius-description"><?php echo esc_html__('Informe o endpoint que será usado como link permanente para a nova guia.', 'wc-account-genius' ) ?></span>
                     </th>
                     <td class="w-50">
                        <input type="text" class="form-control set-endpoint-tab" id="add_new_tab_endpoint" value=""/>
                     </td>
                  </tr>

                  <tr>
                     <th class="w-50">
                        <?php echo esc_html__( 'Ícone da guia', 'wc-account-genius' ) ?>
                        <span class="wc-account-genius-description"><?php echo esc_html__('Define o ícone que será exibido ao lado do título desta guia. Ou deixe em branco para não exibir.', 'wc-account-genius' ) ?></span>
                     </th>
                     <td class="w-50">
                        <div class="input-group">
                           <button class="display-icon-tab fs-xg btn btn-outline-secondary d-flex align-items-center">
                              <i class="bx bx-info-circle"></i>
                           </button>
                           <input type="text" class="set-icon-tab form-control" id="add_new_tab_icon" value="bx bx-info-circle" placeholder="<?php echo esc_html__('Classe do ícone Boxicons.', 'wc-account-genius' ) ?>"/>
                        </div>
                     </td>
                  </tr>

                  <tr>
                     <th class="w-50">
                        <?php echo esc_html__( 'Classe CSS da guia (Opcional)', 'wc-account-genius' ) ?>
                        <span class="wc-account-genius-description"><?php echo esc_html__('Permite definir classes CSS personalizadas para esta guia.', 'wc-account-genius' ) ?></span>
                     </th>
                     <td class="w-50">
                        <input type="text" class="form-control" id="add_new_tab_class" value="" placeholder="<?php echo esc_html__('Classe CSS personalizada.', 'wc-account-genius' ) ?>"/>
                     </td>
                  </tr>
         
                  <tr class="redirect-link-content new-tab">
                     <th class="w-50">
                        <?php echo esc_html__( 'Conteúdo da guia', 'wc-account-genius' ) ?>
                        <span class="wc-account-genius-description"><?php echo esc_html__('Coloque aqui o conteúdo que você deseja exibir na guia. É permitido HTML e shortcodes. Ex.: [content id="10580"]', 'wc-account-genius' ) ?></span>
                     </th>
                     <td class="w-50">
                        <div class="form-check form-switch">
                           <textarea class="form-control" id="add_new_tab_content"></textarea>
                        </div>
                     </td>
                  </tr>

                  <tr>
                     <th class="w-50">
                        <?php echo esc_html__( 'Redirecionar para outro link', 'wc-account-genius' ) ?>
                        <span class="wc-account-genius-description"><?php echo esc_html__('Quando o usuário acessar esta guia, será redirecionado para outro link do site, ou externo.', 'wc-account-genius' ) ?></span>
                     </th>
                     <td class="w-50">
                        <div class="form-check form-switch">
                           <input type="checkbox" class="toggle-switch enable-redirect-link" id="add_new_tab_redirect" value="no"/>
                        </div>
                     </td>
                  </tr>

                  <tr class="redirect-link new-tab d-none">
                     <th class="w-50">
                        <?php echo esc_html__( 'Link de redirecionamento', 'wc-account-genius' ) ?>
                        <span class="wc-account-genius-description"><?php echo esc_html__('Informe o link de destino da guia.', 'wc-account-genius' ) ?></span>
                     </th>
                     <td class="w-50">
                        <div class="form-check form-switch">
                           <input type="text" class="form-control" id="add_new_tab_redirect_link" value=""/>
                        </div>
                     </td>
                  </tr>

                  <tr class="mt-3">
                     <td class="w-100 d-flex align-items justify-content-end">
                        <button id="add_new_tab_submit" class="btn btn-primary" disabled><?php echo esc_html__( 'Criar nova guia', 'wc-account-genius' ) ?></button>
                     </td>
                  </tr>
					</table>
            </div>
         </div>
      </div>

      <!-- Boxicons modal library -->
      <div id="boxicons_library_container">
         <div class="boxicons-lib-content">
               <div class="d-flex align-items-center justify-content-end close-container">
                  <button id="boxicons_library_close" class="btn-close fs-lg" aria-label="<?php echo esc_html__( 'Fechar', 'wc-account-genius' ); ?>"></button>
               </div>
               <div class="boxicons-icon-list">
                  <?php foreach( account_genius_boxicons_lib() as $icon ) : ?>
                     <button class="icon-item btn btn-icon btn-outline-secondary" data-icon="<?php echo esc_attr( $icon ) ?>">
                           <i class="bx <?php echo esc_attr( $icon ) ?>"></i>
                     </button>
                  <?php endforeach; ?>
               </div>
         </div>
      </div>
   </div>
</div>