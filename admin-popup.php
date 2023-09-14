<?php

/**
 * Plugin Name: Admin Popup
 * Plugin URI: https://www.wordpress.org/admin-popup
 * Description: Plugin's description here.
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Taner Alberto
 * Author URI: https://www.taneralberto.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: admin-popup
 * Domain Path: /languages
 */

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see < http://www.gnu.org/licenses/ >.
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Admin_Popup' ) ) {
	class Admin_Popup {

		function __construct() {

			$this->define_constants();

			add_action( 'admin_menu', array( $this, 'add_menu' ) );

			require_once( ADMIN_POPUP_PATH . 'post-types/class.admin-popup-post-type.php' );
			$admin_popup_post_type = new Admin_Popup_Post_Type();

			require_once( ADMIN_POPUP_PATH . 'settings/class.admin-popup-settings.php' );
			$admin_popup_settings = new Admin_Popup_Settings();

			wp_reset_postdata();

			if( isset( Admin_Popup_Settings::$options['popup_display'] ) && checked( '1', Admin_Popup_Settings::$options['popup_display'] ) ) {
				$this->display_popup();
			}
		}

		public function define_constants() {

			define( 'ADMIN_POPUP_PATH', plugin_dir_path( __FILE__ ) );
			define( 'ADMIN_POPUP_URL', plugin_dir_url( __FILE__ ) );
			define( 'ADMIN_POPUP_VERSION', '1.0.0' );
		}

		public function add_menu() {

			add_submenu_page(
				'edit.php?post_type=admin_popup',
				'Settings',
				'Settings',
				'manage_options',
				'admin_popup_settings',
				array( $this, 'settings_page' ),
			);
		}

		public function settings_page() {

			if( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if( isset( $_GET['settings-updated'] ) ) {
				add_settings_error( 'admin_popup_options', 'admin_popup_setting_message', esc_html__( 'Settings Saved Successfully', 'admin-popup'), 'success' );
			}

			settings_errors( 'admin_popup_options', true );
			require_once( ADMIN_POPUP_PATH . 'views/settings-page.php' );
		}

		public function display_popup() {

			if( ! is_admin() ) {
				return;
			}

			global $pagenow;

			if( $pagenow === 'post-new.php' ) {
				return;
			} elseif( $pagenow === 'post.php' ) {
				return;
			}

			if ( ! isset( Admin_Popup_Settings::$options['popup_id'] ) && empty( Admin_Popup_Settings::$options['popup_id'] ) ) {
				return;
			}

			$popup_id = Admin_Popup_Settings::$options['popup_id'];

			if( null === get_post( $popup_id ) && Admin_Popup_Post_Type::CUSTOM_POST_TYPE_KEY !== get_post_type( $popup_id ) ) {
				return;
			}

			$options = get_post_meta( $popup_id, 'admin_popup_options', true );

			require_once( ADMIN_POPUP_PATH . 'views/popup.php' );
		}

		public static function activate() {
			update_option( 'rewrite_rules', '' );
		}

		public static function deactivate() {
			flush_rewrite_rules();
		}

		public static function uninstall() {

		}
	}
}

if ( class_exists( 'ADMIN_POPUP' ) ) {
    register_activation_hook( __FILE__, array( 'Admin_Popup', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'Admin_Popup', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'Admin_Popup', 'uninstall' ) );

	$admin_popup = new Admin_Popup();
}