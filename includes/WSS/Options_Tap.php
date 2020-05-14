<?php

namespace WSS;
defined( 'ABSPATH' ) || exit; //prevent direct file access.

class Options_Tap {
	protected $tab_name;

	public function __construct() {
		$this->tab_name = 'wss_settings';
	}

	public function load() {
		add_action( 'woocommerce_settings_tabs_' . $this->tab_name, array( $this, 'setting_tab_content' ) );
		add_action( 'woocommerce_update_options_' . $this->tab_name, array( $this, 'update_settings' ) );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_setting_tab' ), 30, 1 );
	}

	public function add_setting_tab( $settings_tabs ) {
		$settings_tabs[ $this->tab_name ] = __( 'Single State', 'wss' );

		return $settings_tabs;
	}

	private function get_states() {
		$countries_obj   = new \WC_Countries();
		$default_country = $countries_obj->get_base_country();

		return $countries_obj->get_states( $default_country );
	}

	private function setting_fields() {
		$settings = array(
			array(
				'name' => __( 'Single State settings', 'wss' ),
				'type' => 'title',
				'id'   => 'wc_settings_tab_demo_section_title'
			),
			array(
				'title'   => __( 'Enable', 'wss' ),
				'type'    => 'checkbox',
				'default' => 'Default value for the option',
				'label'   => __( 'Enable', 'wss' ),
				'id'      => 'wss_state_enable',
				'options' => $this->get_states()
			),
			array(
				'title'   => __( 'Select state', 'wss' ),
				'type'    => 'multiselect',
				'default' => 'Default value for the option',
				'id'      => 'wss_state',
				'css' => 'height: 200px',
				'options' => $this->get_states()
			),
			array(
				'type' => 'sectionend',
				'id'   => 'wc_settings_tab_demo_section_end'
			)
		);

		return apply_filters( 'wc_settings_' . $this->tab_name, $settings );
	}

	public function setting_tab_content() {
		woocommerce_admin_fields( apply_filters( 'wc_settings_' . $this->tab_name, $this->setting_fields() ) );
	}

	public function update_settings() {
		woocommerce_update_options( $this->setting_fields() );
	}
}

