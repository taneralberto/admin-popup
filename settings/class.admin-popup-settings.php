<?php

if ( ! class_exists( 'Admin_Popup_Settings' ) ) {
	class Admin_Popup_Settings {

		public static $options;

		public function __construct() {

			self::$options = get_option( 'admin_popup_options' );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

        public function admin_init() {

            //register_setting( 'admin_popup_group', 'admin_popup_options', array( $this, 'settings_when_registered' ) );

			register_setting(
                'admin_popup_group',
                'admin_popup_options',
                array(
                    'type' => 'array',
                    'description' => 'Array with all the options data',
                    'sanitize_callback' => array( $this, 'settings_when_registered' ),
                )
            );

            add_settings_section(
                'admin_popup_general_section',
                esc_html__( 'Gerenal Settings', 'admin-popup' ),
                null,
                'admin_popup_general_page'
            );

            add_settings_field(
                'admin_popup_display',
                esc_html__( 'What Popup show?','admin-popup' ),
                array( $this, 'field_callback_what_popup_display' ),
                'admin_popup_general_page',
                'admin_popup_general_section',
                array(
                    'label_for' => 'popup_id'
                )
            );

            add_settings_field(
                'admin_popup_show',
                esc_html__( 'Show Popup?', 'admin-popup' ),
                array( $this, 'field_callback_display_popup' ),
                'admin_popup_general_page',
                'admin_popup_general_section',
                array(
                    'label_for' => 'popup_display'
                )
            );
        }

        public function field_callback_what_popup_display() {

            $value = isset( self::$options['popup_id'] ) ? esc_html( self::$options['popup_id'] ) : '';

            ?>
            <input type="text" name="admin_popup_options[popup_id]" id="popup_id"
            value="<?php echo $value; ?>"
            />
            <?php
        }

        public function field_callback_display_popup() {
            ?>
            <input type="checkbox"
            name="admin_popup_options[popup_display]"
            id="popup_display"
            value="1"
            <?php if( isset( self::$options['popup_display'] ) ) {
                checked( '1', self::$options['popup_display'], true );
            } ?>
            />
            <?php
        }

        public function settings_when_registered( $input ) {

            $new_input = array();

            foreach( $input as $key => $value ) {
                if( empty( $value ) ) {
                    add_settings_error( 'admin_popup_options', 'admin_popup_setting_message', esc_html__( 'There\'re fields that cannot be left empty.' ), 'error' );
                }

                $new_input[$key] = sanitize_text_field( $value );
            }

            return $new_input;
        }
	}
}