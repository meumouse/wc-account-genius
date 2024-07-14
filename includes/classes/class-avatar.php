<?php

namespace Account_Genius\Avatar;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Class for manipulate avatar
 * 
 * @since 1.0.0
 * @version 2.1.0
 * @package MeuMouse.com
 */
class Avatar {

    /**
     * Construct function
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        add_action( 'init', array( $this, 'upload_woo_avatar' ) );
        add_filter( 'get_avatar', array( $this, 'change_profile_avatar' ), 1, 5 );
        add_shortcode( 'wc_account_genius_avatar_upload', array( $this, 'wc_account_genius_form_upload_avatar' ) );
    }

    
    /**
     * Change WordPress avatar
     * 
     * @since 1.0.0
     * @version 2.1.0
     * @param string $avatar | HTML for the user’s avatar
     * @param mixed $id_or_email | The avatar to retrieve
     * @param int $size | Height and width of the avatar in pixels
     * @param string $default | URL for the default image or a default type
     * @param string $alt | Alternative text to use in the avatar image tag
     * @return string
     */
    public function change_profile_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
        $user = false;
    
        if ( is_numeric( $id_or_email ) ) {
            $id = (int) $id_or_email;
            $user = get_user_by('id', $id);
        } elseif ( is_object( $id_or_email ) ) {
            if ( ! empty( $id_or_email->user_id ) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by('id', $id);
            }
        } else {
            $user = get_user_by('email', $id_or_email);
        }
    
        if ( $user && is_object( $user ) ) {
            $picture_id = get_user_meta( $user->data->ID, 'profile_pic', true );

            if ( ! empty( $picture_id ) ) {
                $avatar = wp_get_attachment_image_src( $picture_id );

                if ( $avatar ) {
                    $avatar = $avatar[0];
                    $avatar = '<img loading="lazy" alt="' . $alt . '" src="' . $avatar . '" class="d-block rounded-circle mx-auto my-2 avatar avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '">';
                }
            }
        }
    
        return $avatar;
    }
                    
                    
    /**
     * Upload actions
     * 
     * @since 1.0.0
     * @version 2.1.0
     * @return void
     */
    public function upload_woo_avatar() {
        $user_id = get_current_user_id();
    
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'wc-account-genius-delete-avatar') {
            $picture_id = get_user_meta( $user_id, 'profile_pic', true );
            delete_user_meta( $user_id, 'profile_pic' );
            wp_delete_attachment( $picture_id, true );
            wc_add_notice( __( 'Foto de perfil removida com sucesso!', 'wc-account-genius' ), 'success' );
        }
    
        if ( isset( $_FILES['profile_pic'] ) && $_FILES['profile_pic'] && trim( $_FILES['profile_pic']['name'] ) !== '' ) {
            $upload_result = $this->replace_default_gravatar( $_FILES['profile_pic'] );
    
            if ( is_int( $upload_result ) ) {
                update_user_meta( $user_id, 'profile_pic', $upload_result );
                wc_add_notice( __('Foto de perfil alterada com sucesso!', 'wc-account-genius'), 'success' );
            } else {
                wc_add_notice( $upload_result, 'error' );
            }
        }
    }


    /**
     * Insert profile photo in new path
     * 
     * @since 1.0.0
     * @version 2.1.0
     * @param array $picture | Profile picture
     * @return mixed WP_Error or string
     */
    public function replace_default_gravatar( $picture ) {
        $upload_dir = wp_upload_dir();
        $upload_path = trailingslashit( $upload_dir['basedir'] ) . 'avatar-uploads'; // folder for profile pictures
    
        if ( ! is_dir( $upload_path ) ) {
            wp_mkdir_p( $upload_path );
        }
    
        $new_file_path = $upload_path . '/' . $picture['name'];
    
        if ( empty( $picture ) ) {
            return new WP_Error('no_file', __('Nenhum arquivo selecionado.', 'wc-account-genius') );
        }

        if ( $picture['error'] ) {
            return new WP_Error('upload_error', $picture['error']);
        }
    
        // check file type
        $allowed_file_types = apply_filters( 'account_genius_avatar_allowed_mime_types', array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ));

        if ( ! in_array( $picture['type'], $allowed_file_types ) ) {
            return new WP_Error('invalid_file_type', __('Este tipo de arquivo não é permitido.', 'wc-account-genius'));
        }
    
        if ( ! move_uploaded_file( $picture['tmp_name'], $new_file_path ) ) {
            return new WP_Error('upload_failed', __('Erro ao enviar arquivo.', 'wc-account-genius'));
        }
    
        $attachment = array(
            'post_mime_type' => $picture['type'],
            'post_title' => sanitize_file_name( $picture['name'] ),
            'post_content' => '',
            'post_status' => 'inherit',
        );
    
        $attach_id = wp_insert_attachment( $attachment, $new_file_path );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $new_file_path );
        wp_update_attachment_metadata( $attach_id, $attach_data );
    
        return $attach_id;
    }    
                    
                    
    /**
     * Create a form for upload avatar in popup
     * 
     * @since 1.0.0
     * @version 1.8.5
     * @return void
     */
    public function wc_account_genius_form_upload_avatar() {
        ?>
        <div id="drop-area" class="wc-account-genius-avatar-actions">
            <div class="drag-text">
                <i class="bx bx-image"></i>
                <?php echo esc_html__('Arraste e solte a imagem aqui', 'wc-account-genius'); ?>
            </div>

            <div class="file-list"></div>

            <form enctype="multipart/form-data" action="<?php wc_customer_edit_account_url() ?>" method="POST">
                <div class="wc-account-genius-drag-and-drop-file">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="upload-file-avatar" name="profile_pic" hidden>
                        <label class="custom-file-label mb-4" for="upload-file-avatar"><?php echo esc_html__('Ou clique para procurar seu arquivo', 'wc-account-genius') ?></label>
                    </div>
                </div>
                <button class="btn btn-primary button-loading mt-3" type="submit" id="wc-account-genius-send-avatar" disabled><?php echo esc_html__('Enviar', 'wc-account-genius') ?></button>
            </form>
        </div>
    
        <div class="wc-account-genius-info-upload">
            <i class="bx bx-info-circle me-1"></i>
            <span><?php echo esc_html__('Envie uma imagem JPG, JPEG, GIF, PNG ou WebP. Tamanho máximo de 5MB.', 'wc-account-genius') ?></span>
        </div>
        <?php
    }
}

new Avatar();