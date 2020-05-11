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