<?php

namespace Account_Genius\Compat;

/**
 * Autoloader classes for compatibility with themes and plugins
 * 
 * @since 2.1.0
 * @version 2.1.0
 * @package MeuMouse.com
 */
class Autoloader {

    /**
     * Directory to scan for compatibility classes
     * 
     * @since 2.1.0
     * @var string
     */
    private $directory;

    /**
     * Constructor
     * 
     * @since 2.1.0
     * @param string $directory The directory to scan for compatibility classes.
     */
    public function __construct( $directory ) {
        $this->directory = $directory;
    }
    

    /**
     * Load and run all compatibility classes
     * 
     * @since 2.1.0
     * @return void
     */
    public function load_and_run() {
        if ( ! is_dir( $this->directory ) ) {
            return;
        }

        // iterate for each compat class on directory
        foreach ( glob( $this->directory . '/class-compat-*.php' ) as $file ) {
            include_once $file;
        }
    }
}

$autoloader = new Autoloader( ACCOUNT_GENIUS_INC_DIR . 'classes/compat/' );
$autoloader->load_and_run();