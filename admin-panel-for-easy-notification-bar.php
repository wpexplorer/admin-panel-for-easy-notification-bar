<?php
/*
 * Plugin Name: Admin Panel for Easy Notification Bar
 * Plugin URI: https://wordpress.org/plugins/easy-notification-bar/
 * Description: Provides an admin panel for changing the easy notification bar text, button text and button link outside of the Customizer, with Admin and Editor role access.
 * Author: WPExplorer
 * Author URI: https://www.wpexplorer.com/
 * Version: 1.0
 *
 * Text Domain: admin-panel-for-easy-notification-bar
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Admin_Panel_For_Easy_Notification_Bar' ) ) {

	final class Admin_Panel_For_Easy_Notification_Bar {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Create or retrieve the instance of Accent_Colors.
		 */
		public static function instance() {
			if ( is_null( static::$instance ) ) {
				static::$instance = new self();
			}
			return static::$instance;
		}

		/**
		 * Class constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', __CLASS__ . '::add_submenu_page' );
			add_action( 'admin_init', __CLASS__ . '::register_settings' );
		}

		/**
		 * Add new admin page.
		 */
		public static function add_submenu_page() {
			add_submenu_page(
				'options-general.php',
				'Easy Notification Bar',
				'Easy Notification Bar',
				'edit_posts', // allow editor access.
				'easy-notification-bar',
				__CLASS__ . '::create_admin_page'
			);
		}

		/**
		 * Registers the "easy_notification_bar" setting and setting fields.
		 */
		public static function register_settings() {

			register_setting(
				'easy_notification_bar',
				'easy_notification_bar',
				__CLASS__ . '::save_settings'
			);

			add_settings_section(
				'easy_notification_bar_setting_section',
				null,
				__CLASS__ . '::settings_section_callback_function',
				'easy-notification-bar'
			);

			// Message field
			add_settings_field(
				'message',
				esc_html__( 'Message', 'admin-panel-for-easy-notification-bar' ),
				__CLASS__ . '::message_field',
				'easy-notification-bar',
				'easy_notification_bar_setting_section'
			);

			// Button Text
			add_settings_field(
				'button_text',
				esc_html__( 'Button Text', 'admin-panel-for-easy-notification-bar' ),
				__CLASS__ . '::button_text_field',
				'easy-notification-bar',
				'easy_notification_bar_setting_section'
			);

			// Button URL
			add_settings_field(
				'button_link',
				esc_html__( 'Button Link', 'admin-panel-for-easy-notification-bar' ),
				__CLASS__ . '::button_link_field',
				'easy-notification-bar',
				'easy_notification_bar_setting_section'
			);

		}

		/**
		 * Message field callback function.
		 */
		public static function message_field() {
			$value = '';
			$mods = get_theme_mod( 'easy_nb' );

			if ( is_array( $mods ) && array_key_exists( 'message', $mods ) ) {
				$value = $mods['message'];
			}

			?>
			<textarea id="easy_notification_bar[message]" name="easy_notification_bar[message]"><?php echo wp_kses_post( $value ); ?></textarea>
		<?php }

		/**
		 * Button Text field callback function.
		 */
		public static function button_text_field() {
			$value = '';
			$mods = get_theme_mod( 'easy_nb' );

			if ( is_array( $mods ) && array_key_exists( 'button_text', $mods ) ) {
				$value = $mods['button_text'];
			}

			?>
			<input id="easy_notification_bar[message]" name="easy_notification_bar[button_text]" value="<?php echo esc_attr( $value ); ?>">
		<?php }

		/**
		 * Button Link field callback function.
		 */
		public static function button_link_field() {
			$value = '';
			$mods = get_theme_mod( 'easy_nb' );

			if ( is_array( $mods ) && array_key_exists( 'button_link', $mods ) ) {
				$value = $mods['button_link'];
			}

			?>
			<input id="easy_notification_bar[button_link]" name="easy_notification_bar[button_link]" value="<?php echo esc_attr( $value ); ?>">
		<?php }

		/**
		 * Settings section callback.
		 *
		 * @since 1.6.0
		 */
		public static function settings_section_callback_function() {
			// Leave blank - nothing needed here.
		}

		/**
		 * Save our settings.
		 */
		public static function save_settings( $options ) {

			if ( empty( $options ) || ! is_array( $options ) ) {
				return;
			}

			$mods = get_theme_mod( 'easy_nb' );

			if ( array_key_exists( 'message', $options ) ) {
				$mods['message'] = wp_kses_post( $options['message'] );
			}

			if ( array_key_exists( 'button_text', $options ) ) {
				$mods['button_text'] = sanitize_text_field( $options['button_text'] );
			}

			if ( array_key_exists( 'button_link', $options ) ) {
				$mods['button_link'] = sanitize_text_field( $options['button_link'] );
			}

			set_theme_mod( 'easy_nb', $mods );

			$options = ''; // reset so we don't store in the options table.

		}

		/**
		 * Create the admin page.
		 */
		public static function create_admin_page() { ?>

			<div class="wrap">
				<h1>Easy Notification Bar</h1>

				<form method="post" action="options.php">
					<?php settings_fields( 'easy_notification_bar' ); ?>
					<?php do_settings_sections( 'easy-notification-bar' ); ?>
					<?php submit_button(); ?>
				</form>

				<?php if ( current_user_can( 'edit_theme_options' ) ) { ?>
					<hr><br>
					<div class="textright">
						<a class="button button-secondary" href="<?php echo esc_url( admin_url( '/customize.php?autofocus[section]=easy_nb' ) ); ?>"><?php esc_html_e( 'All settings', 'admin-panel-for-easy-notification-bar' ); ?></a>
					</div>
				<?php } ?>

			</div><!-- .wrap -->

		<?php }

	}

	Admin_Panel_For_Easy_Notification_Bar::instance();

}