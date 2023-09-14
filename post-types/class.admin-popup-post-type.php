<?php

if ( ! class_exists( 'Admin_Popup_Post_Type' ) ) {
	class Admin_Popup_Post_Type {

		const CUSTOM_POST_TYPE_KEY = 'admin_popup';

		const META_BOXES_KEYS = array(
			'priority' => 'admin_popup_priority',
			'options' => 'admin_popup_options',
			'background' => 'admin_popup_background'
		);

		const META_KEYS = array(
			'priority' => 'admin_popup_priority',
			'style' => 'admin_popup_style',
			'button_text' => 'admin_popup_button_text',
			'background' => 'admin_popup_background'
		);

		function __construct() {

			add_action( 'init', array( $this, 'create_post_type' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			// Using Meta Box Framework Plugin to create image meta
			add_filter( 'rwmb_meta_boxes', array( $this, 'background_meta_box_view' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
			add_filter( 'manage_admin_popup_posts_columns', array( $this, 'register_columns' ) );
			add_action( 'manage_admin_popup_posts_custom_column', array( $this, 'render_columns' ), 10, 2 );
			add_filter( 'manage_edit-admin_popup_sortable_columns', array( $this, 'sortable_columns' ) );
		}

		public function create_post_type() {

			register_post_type(
				self::CUSTOM_POST_TYPE_KEY,
				array(
					'labels' => array(
						'name' => esc_html__( 'Popups', 'admin-popup' ),
						'singular_name' => esc_html__( 'Popup', 'admin-popup' ),
						'menu_name' => esc_html__( 'Popup', 'admin-popup' ),
						'all_items' => esc_html__( 'All Popups', 'admin-popup' ),
						'add_new' => esc_html__( 'Add New', 'admin-popup' ),
						'add_new_item' => esc_html__( 'Add New Popup', 'admin-popup' ),
						'edit_item' => esc_html__( 'Edit Popup', 'admin-popup' ),
						'new_item' => esc_html__( 'New Popup', 'admin-popup' ),
						'view_item' => esc_html__( 'View Popup', 'admin-popup' ),
						'search_items' => esc_html__( 'Search Popups', 'admin-popup' ),
						'not_found' => esc_html__( 'No Popups found', 'admin-popup' ),
						'not_found_in_trash' => esc_html__( 'No Popups found in Trash', 'admin-popup' ),
						'filter_items_list' => esc_html__( 'Filter popups list', 'admin-popup' ),
						'items_list_navigation' => esc_html__( 'Popup list navigation', 'admin-popup' ),
						'items_list' => esc_html__( 'Popups list', 'admin-popup' ),
					),
					'supports' => array(
						'title',
						'editor',
						'thumbnail',
						'revisions',
					),
					'public' => false,
					'publicy_queryable' => false,
					'show_ui' => true,
					'hierarchical' => false,
					'show_in_menu' => true,
					'menu_position' => 20, // below Pages.
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => false,
					'can_export' => true,
					'menu_icon' => 'dashicons-align-center', // 3.8+ dashicon option.
					'exclude_from_search' => true,
					'capability_type' => 'page',
					'map_meta_cap' => true,
					'has_archive' => false,
					'show_in_rest' => true,
					// 'register_meta_box_cb' => array( $this, 'add_meta_boxes' )
					'delete_with_user' => false,
				)
			);
		}

		public function add_meta_boxes() {

			add_meta_box(
				self::META_BOXES_KEYS['priority'],
				esc_html__( 'Priority', 'admin-popup' ),
				array( $this, 'priority_meta_box_view' ),
				self::CUSTOM_POST_TYPE_KEY,
				'normal',
				'high',
			);

			add_meta_box(
				self::META_BOXES_KEYS['options'],
				esc_html__( 'Options', 'admin-popup' ),
				array( $this, 'options_meta_box_view' ),
				self::CUSTOM_POST_TYPE_KEY,
				'normal',
				'high',
			);
		}

		public function priority_meta_box_view() {

			require_once( ADMIN_POPUP_PATH . 'views/priority_meta_box.php' );
		}

		public function options_meta_box_view() {

			require_once( ADMIN_POPUP_PATH . 'views/options_meta_box.php' );
		}

		public function background_meta_box_view( $meta_boxes ) {

			$prefix = '';

			// Data structure based in Meta Box Framework Plugin
			$meta_boxes[] = [
				'title'   => esc_html__( 'Background', 'admin-popup' ),
				'id'      => 'admin_popup_background_group',
				'post_types' => 'admin_popup',
				'context' => 'normal',
				'fields'  => [
					[
						'type' => 'image_advanced',
						'name' => esc_html__( 'Choose a image', 'admin-popup' ),
						'id'   => $prefix . 'admin_popup_background',
						'max_file_uploads' => 1,
					],
				],
			];

			return $meta_boxes;
		}

		public function save_post( $post_id ) {

			if ( isset( $_POST['admin_popup_priority_nonce'] ) ) {
				if ( ! wp_verify_nonce( $_POST['admin_popup_priority_nonce'], 'admin_popup_priority_nonce' ) ) {
					return;
				}
			}

			if ( isset( $_POST['admin_popup_options_nonce'] ) ) {
				if ( ! wp_verify_nonce( $_POST['admin_popup_options_nonce'], 'admin_popup_options_nonce' ) ) {
					return;
				}
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( isset( $_POST['post_type'] ) && $_POST['post_type'] !== self::CUSTOM_POST_TYPE_KEY ) {
				return;
			}

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( isset( $_POST['action'] ) && $_POST['action'] === 'editpost' ) {
				/**
				 * Priority metadata validation
				 */
				$old_admin_popup_priority = get_post_meta( $post_id, self::META_BOXES_KEYS['priority'], true );
				$new_admin_popup_priority = $_POST[self::META_BOXES_KEYS['priority']];

				if ( ! empty( $new_admin_popup_priority ) ) {
					update_post_meta( $post_id, self::META_BOXES_KEYS['priority'], sanitize_text_field( $new_admin_popup_priority ), $old_admin_popup_priority );
				} else {
					update_post_meta( $post_id, self::META_BOXES_KEYS['priority'], 'info' );
				}

				/**
				 * Options: style and button_text validation
				 */
				$old_admin_popup_options = get_post_meta( $post_id, self::META_BOXES_KEYS['options'], true );
				$new_admin_popup_options = isset( $_POST[self::META_BOXES_KEYS['options']] ) ? $_POST[self::META_BOXES_KEYS['options']] : array();

				if ( ! isset( $new_admin_popup_options['style'] ) || empty( $new_admin_popup_options['style'] ) ) {
					$new_admin_popup_options['style'] = 'style-1';
				} elseif ( ! isset( $new_admin_popup_options['button_text'] ) || empty( $new_admin_popup_options['button_text'] ) ) {
					$new_admin_popup_options['button_text'] = 'Ok';
				}

				update_post_meta( $post_id, self::META_BOXES_KEYS['options'], $new_admin_popup_options, $old_admin_popup_options );
			}
		}

		public function register_columns( $columns ) {

			$columns[self::META_KEYS['priority']] = esc_html__( 'Priority', 'admin-popup' );
			$columns[self::META_KEYS['style']] = esc_html__( 'Style', 'admin-popup' );
			$columns[self::META_KEYS['button_text']] = esc_html__( 'Button Text', 'admin-popup' );
			return $columns;
		}

		public function render_columns( $column, $post_id ) {

			$options = get_post_meta( $post_id, self::META_BOXES_KEYS['options'], true );

			switch( $column ) {
				case  self::META_KEYS['priority'] :
					echo esc_html( get_post_meta( $post_id, self::META_KEYS['priority'], true) );
				break;
				case self::META_KEYS['style'] :
					$value = isset( $options['style'] ) ? $options['style'] : '';
					echo esc_html( $value );
				break;
				case self::META_KEYS['button_text'] :
					$value = isset( $options['button_text'] ) ? $options['button_text'] : '';
					echo esc_html( $value );
				break;
				default :
					echo '';
				break;
			}
		}

		public function sortable_columns( $columns ) {
			$columns[self::META_KEYS['priority']] = self::META_KEYS['priority'];
			$columns[self::META_KEYS['style']] = self::META_KEYS['style'];
			$columns[self::META_KEYS['button_text']] = self::META_KEYS['button_text'];
			return $columns;
		}
	}
}