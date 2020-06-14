<?php


namespace WSS;

defined( 'ABSPATH' ) || exit; //prevent direct file access.
class Limit_State {
	protected $countries_obj;

	public function __construct() {
		$this->countries_obj = new \WC_Countries();
	}

	public function load() {
		add_filter( 'woocommerce_states', array( $this, 'states' ), 1000, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function load_assets() {
		wp_enqueue_style( 'select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css' );
		wp_enqueue_script( 'select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'select2-init-js', WSS_URL . 'assets/js/main.js', array(
			'jquery',
			'select2-js'
		), false, true );
	}

	public function admin_menu() {
		add_submenu_page( 'wsd_settings_base_id', __( 'State States', 'wss' ),
			__( 'State States', 'wss' ),
			'manage_options',
			'wss_settings',
			array( $this, 'admin_menu_page' ), 4 );
	}

	public function admin_menu_page() {
		wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=wss_settings' ) );
		exit;
	}

	private function get_country() {
		return $this->countries_obj->get_base_country();
	}

	private function get_states() {
		return $this->countries_obj->get_states( $this->get_country() );
	}

	private function is_enabled() {
		return get_option( 'wss_state_enable' ) == 'yes' ? true : false;
	}

	private function limited_states() {
		return get_option( 'wss_state' ) ?: array();
	}

	function states( $states ) {
		$result = array();
		if ( $this->is_enabled() && $this->limited_states() && ! is_admin() ) {
			foreach ( $states[ $this->get_country() ] as $state_key => $state_val ) {
				if ( in_array( $state_key, $this->limited_states() ) ) {
					$result[ $state_key ] = $state_val;
				}
			}
			$states[ $this->get_country() ] = $result;
		}

		return $states;
	}

}