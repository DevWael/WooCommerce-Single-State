<?php
/**
 * Plugin Name: Woocommerce Single State
 * Plugin URI: https://innoshop.co
 * Description: Limit checkout states to one state only.
 * Version: 1.1
 * Author: Ahmad Wael
 * Author URI: https://github.com/devwael
 * License: GPL2
 * Text Domain: wss
 */

namespace WSS;

defined( 'ABSPATH' ) || exit; //prevent direct file access.
define( 'WSS_DIR', plugin_dir_path( __FILE__ ) );
define( 'WSS_URL', plugin_dir_url( __FILE__ ) );

spl_autoload_register( 'WSS\wss_autoloader' );
function wss_autoloader( $class_name ) {
	$classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
	$class_file  = $class_name . '.php';
	$class       = $classes_dir . str_replace( '\\', '/', $class_file );
	if ( file_exists( $class ) ) {
		require_once $class;
	}

	return false;
}

class Single_State {
	public static function init() {
		( new Options_Tap() )->load();
		( new Limit_State() )->load();
	}
}

add_action( 'plugins_loaded', 'WSS\wss_init' );
function wss_init() {
	Single_State::init();
}

add_action( 'plugins_loaded', 'WSS\wss_load_textdomain' );
function wss_load_textdomain() {
	load_plugin_textdomain(
		'wss',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}