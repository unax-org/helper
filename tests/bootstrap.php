<?php
/**
 * PHPUnit bootstrap file
 */

// Define ABSPATH if not already defined
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', true );
}

// Load Composer autoloader
require_once dirname( __DIR__ ) . '/vendor/autoload.php';
