<?php

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if (!class_exists('WPeCountersScripts')) {

	class WPeCountersScripts {

		/**
		 * Holds the instances of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance;

		function __construct() {

			add_action('admin_print_scripts', array($this, 'scripts'));
			add_action('admin_print_styles', array($this, 'styles'));
		}
		

		public static function scripts() {
			global $current_screen;
			if ($current_screen->id == "admin_page_WPeCounter" || $current_screen->id == "fakturo_page_WPeCounter") {
				wp_enqueue_script('jquery-settings', WPECOUNTER_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), WPECOUNTER_VERSION, true);

				wp_localize_script('jquery-settings', 'wpecounter',
						array('ajax_url'		 => admin_url('admin-ajax.php'),
							'loading_image'	 => admin_url('images/spinner.gif'),
							'loading_text'	 => __('Loading...', 'wpecounter'),
						)
				);
			}
		}

		public static function styles() {
			global $current_screen;

			if ($current_screen->id == "admin_page_WPeCounter") {
				wp_enqueue_style('style-settings', WPECOUNTER_PLUGIN_URL . 'assets/css/admin.css');
			}
		}


		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			if (!self::$instance)
				self::$instance = new self;

			return self::$instance;
		}

	}

}

WPeCountersScripts::get_instance();

?>