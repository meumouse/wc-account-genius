<?php

namespace Account_Genius\Updater;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Class to make requests to a remote server to get plugin versions and updates
 * 
 * @since 1.5.0
 * @version 2.1.0
 * @package MeuMouse.com
 */
class Updater {

    public $plugin_slug;
    public $version;
    public $cache_key;
    public $cache_data_base_key;
    public $cache_allowed;
    public $time_cache;
    private static $instance;

    /**
     * Construct function
     * 
     * @since 1.5.0
     * @return void
     */
    public function __construct() {
        if ( defined( 'ACCOUNT_GENIUS_DEV_MODE' ) ) {
            add_filter( 'https_ssl_verify', '__return_false' );
            add_filter( 'https_local_ssl_verify', '__return_false' );
            add_filter( 'http_request_host_is_external', '__return_true' );
        }

        $this->plugin_slug = ACCOUNT_GENIUS_SLUG;
        $this->version = ACCOUNT_GENIUS_VERSION;
        $this->cache_key = 'wc_account_genius_check_updates';
        $this->cache_data_base_key = 'wc_account_genius_remote_data';
        $this->cache_allowed = true;
        $this->time_cache = DAY_IN_SECONDS;

        add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3 );
        add_filter( 'site_transient_update_plugins', array( $this, 'update_plugin' ) );
        add_action( 'upgrader_process_complete', array( $this, 'purge_cache' ), 10, 2 );
        add_filter( 'plugin_row_meta', array( $this, 'add_manual_updates_link'), 10, 2 );
        add_filter( 'all_admin_notices', array( $this, 'check_manual_updates' ) );
    }


    /**
     * Request on remote server
     * 
     * @since 1.0.0
     * @return array
     */
    public function request() {
        $cached_data = wp_cache_get( $this->cache_key );
    
        if ( false === $cached_data ) {
            $remote = get_transient( $this->cache_data_base_key );
    
            if ( false === $remote ) {
                $remote = wp_remote_get('https://raw.githubusercontent.com/meumouse/wc-account-genius/main/updater/plugin-update-checker.json', [
                    'timeout' => 10,
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);
    
                if ( !is_wp_error( $remote ) && 200 === wp_remote_retrieve_response_code( $remote ) ) {
                    $remote_data = json_decode( wp_remote_retrieve_body( $remote ) );
    
                    // set cache remote data for 1 day
                    set_transient( $this->cache_data_base_key, $remote_data, $this->time_cache );
                } else {
                    return false;
                }
            } else {
                $remote_data = $remote;
            }
    
            // set cache remote data for 1 day
            wp_cache_set( $this->cache_key, $remote_data, $this->time_cache );
        } else {
            $remote_data = $cached_data;
        }
    
        return $remote_data;
    }


    /**
     * Get plugin info
     * 
     * @since 1.0.0
     * @return array
     */
    public function plugin_info( $response, $action, $args ) {
        // do nothing if you're not getting plugin information right now
        if ( 'plugin_information' !== $action ) {
            return $response;
        }

        // do nothing if it is not our plugin
        if ( empty( $args->slug ) || $this->plugin_slug !== $args->slug ) {
            return $response;
        }

        // get updates
        $remote = $this->request();

        if ( ! $remote ) {
            return $response;
        }

        $response = new \stdClass();

        $response->name = $remote->name;
        $response->slug = $remote->slug;
        $response->version = $remote->version;
        $response->tested = $remote->tested;
        $response->requires = $remote->requires;
        $response->author = $remote->author;
        $response->author_profile = $remote->author_profile;
        $response->donate_link = $remote->donate_link;
        $response->homepage = $remote->homepage;
        $response->download_link = $remote->download_url;
        $response->trunk = $remote->download_url;
        $response->requires_php = $remote->requires_php;
        $response->last_updated = $remote->last_updated;

        $response->sections = [
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
            'changelog' => $remote->sections->changelog
        ];

        if ( ! empty( $remote->banners ) ) {
            $response->banners = [
                'low' => $remote->banners->low,
                'high' => $remote->banners->high
            ];
        }

        return $response;
    }


    /**
     * Update plugin
     * 
     * @since 1.0.0
     * @return string
     */
    public function update_plugin( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }
    
        $cached_data = $this->request();
    
        if ( $cached_data && version_compare( $this->version, $cached_data->version, '<' ) && version_compare( $cached_data->requires, get_bloginfo( 'version' ), '<=' ) && version_compare( $cached_data->requires_php, PHP_VERSION, '<' ) ) {
            $this->update_available = $cached_data;

            $response = new \stdClass();
            $response->slug = $this->plugin_slug;
            $response->plugin = "{$this->plugin_slug}/{$this->plugin_slug}.php";
            $response->new_version = $cached_data->version;
            $response->tested = $cached_data->tested;
            $response->package = $cached_data->download_url;
            $transient->response[ $response->plugin ] = $response;
        }
    
        return $transient;
    }      


    /**
     * Purge cache on update plugin
     * 
     * @since 1.0.0
     * @return void
     */
    public function purge_cache( $upgrader, $options ) {
        if ( $this->cache_allowed && 'update' === $options['action'] && 'plugin' === $options[ 'type' ] ) {
            delete_transient('wc_account_genius_api_request_cache');
            delete_transient('wc_account_genius_api_response_cache');
            delete_transient( $this->cache_key );
        }
    }


    /**
     * Add check updates link in the plugin_row_meta
     * 
     * @since 1.0.0
     * @return string
     */
    public function add_manual_updates_link( $actions, $plugin_file ) {
        if ( $plugin_file === $this->plugin_slug . '/' . $this->plugin_slug . '.php' ) {
            $check_updates_link = '<a href="' . esc_url( add_query_arg( 'wc_account_genius_check_updates', '1' ) ) . '">' . esc_html__( 'Verificar atualizações', 'wc-account-genius' ) . '</a>';
            $actions['wc_account_genius_check_updates'] = $check_updates_link;
        }
        
        return $actions;
    }
    

    /**
     * Check manual updates
     * 
     * @since 1.0.0
     * @version 2.1.0
     * @param $plugins
     * @return void
     */
    public function check_manual_updates( $plugins ) {
        if ( isset( $_GET['wc_account_genius_check_updates'] ) && $_GET['wc_account_genius_check_updates'] === '1' ) {

            // purge cache before request on server
            delete_transient('wc_account_genius_api_request_cache');
            delete_transient('wc_account_genius_api_response_cache');
            delete_transient( $this->cache_key );
            delete_transient( $this->cache_data_base_key );

            $remote_data = $this->request();
    
            if ( $remote_data ) {
                $current_version = $this->version;
                $latest_version = $remote_data->version;
    
                // if the current version is lower than that of the remote server
                if ( version_compare( $current_version, $latest_version, '<' ) ) {
                    $message = esc_html__( 'Uma nova versão do plugin Account Genius está disponível.', 'wc-account-genius' );
                    $class = 'notice is-dismissible notice-success'; ?>

                    <script type="text/javascript">
                        if ( ! sessionStorage.getItem('reload_account_genius_update') ) {
                            sessionStorage.setItem('reload_account_genius_update', 'true');
                            window.location.reload();
                        }
                    </script>
                    <?php
                } elseif ( version_compare( $current_version, $latest_version, '>=' ) ) {
                    $message = esc_html__( 'A versão do plugin Account Genius é a mais recente.', 'wc-account-genius' );
                    $class = 'notice is-dismissible notice-success';
                }
            } else {
                $message = esc_html__( 'Não foi possível verificar atualizações para o plugin Account Genius.', 'wc-account-genius' );
                $class = 'notice is-dismissible notice-error';
            }

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
        }
    
        return $plugins;
    }
    
}

new Updater();