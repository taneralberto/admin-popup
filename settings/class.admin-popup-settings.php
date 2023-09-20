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

            // General Options
            add_settings_section(
                'admin_popup_general_section',
                esc_html__( 'Gerenal Settings', 'admin-popup' ),
                null,
                'admin_popup_general_page'
            );

            add_settings_field(
                'admin_popup_display',
                esc_html__( 'What Popup show?','admin-popup' ),
                array( $this, 'general_field_callback_what_popup_display' ),
                'admin_popup_general_page',
                'admin_popup_general_section',
                array(
                    'label_for' => 'popup_id'
                )
            );

            add_settings_field(
                'admin_popup_show',
                esc_html__( 'Show Popup?', 'admin-popup' ),
                array( $this, 'general_field_callback_display_popup' ),
                'admin_popup_general_page',
                'admin_popup_general_section',
                array(
                    'label_for' => 'popup_display'
                )
            );

            // Users Options
            add_settings_section(
                'admin_popup_users_section',
                esc_html__( 'Users Options', 'admin-popup' ),
                null,
                'admin_popup_users_page'
            );

            add_settings_field(
                'admin_popup_users',
                esc_html__( 'What users should be display?', 'admin-popup' ),
                array( $this, 'users_field_callback_users' ),
                'admin_popup_users_page',
                'admin_popup_users_section',
            );
        }

        public function general_field_callback_what_popup_display() {

            $value = isset( self::$options['popup_id'] ) ? esc_html( self::$options['popup_id'] ) : '';

            ?>
            <input type="text" name="admin_popup_options[popup_id]" id="popup_id"
            value="<?php echo $value; ?>"
            />
            <?php
        }

        public function general_field_callback_display_popup() {
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

        public function users_field_callback_users() {
            ?>
            <fieldset>
            <?php
            $users = get_users();
            //var_dump($users);
            $i = 0;

            foreach( $users as $user ) { ?>
                <label for="<?php echo esc_attr( $user->user_login ); ?>"><?php echo esc_html( $user->display_name ); ?></label>
                <input type="checkbox" name="admin_popup_options[users]" value="<?php echo esc_html( $user->user_login ); ?>" id="<?php echo esc_attr( $user->user_login ); ?>">
                <br>
            <?php $i++; } ?>
            </fieldset>
            <?php
        }

        public function settings_when_registered( $input ) {

            var_dump( $input );

            $new_input = array();

            foreach( $input as $key => $value ) {
                if( empty( $value ) ) {
                    add_settings_error( 'admin_popup_options', 'admin_popup_setting_message', esc_html__( 'There\'re fields that cannot be left empty.' ), 'error' );
                }

                $new_input[$key] = sanitize_text_field( $value );
            }

            if( isset( $input['users'] ) ) {

                foreach( $input['users'] as $key => $value ) {
                    //array_push( $new_input['users'], sanitize_text_field( $value ) );

                    $new_input['users'][$key] = sanitize_text_field( $value );
                }
            }

            return $new_input;
        }
	}
}