<?php

if ( ! class_exists( 'Admin_Popup_Settings' ) ) {
	class Admin_Popup_Settings {

		public static $options;

		public function __construct() {

			self::$options = get_option( 'admin_popup_options' );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

        public function admin_init() {

            //register_setting( 'admin_popup_options', 'Admin Popup Options', array( $this, 'settings_when_registered' ) );

			register_setting(
                'admin_popup_group',
                'admin_popup_options',
                array(
                    'type' => 'array',
                    'description' => 'Array with all the options data',
                    'sanitize_callback' => array( $this, 'settings_when_registered' ),
                    'show_in_rest' => true
                )
            );
        }

        public function settings_when_registered( $input ) {

            $new_input = array();

            foreach( $input as $key => $value ) {
                if( empty($value) ) {
                    add_settings_error( 'admin_popup_options', 'admin_popup_setting_message', esc_html__( 'There\'re fields that cannot be left empty.' ), 'error' );
                }

                $new_input[$key] = sanitize_text_field( $value );
            }

            return $new_input;
        }

		/*public static function getOptions() {
			return self::$options;
		}

        public function setOptions( $value ) {
           self::$options[] = $value;
        }*/
	}
}