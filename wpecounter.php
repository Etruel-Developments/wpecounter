<?php
/**
 * Plugin Name:  WP Views Counter
 * Plugin URI:   https://etruel.com/downloads/wpecounter
 * Description:  Counts visits on post lists, pages and/or custom post types. It also displays them in posts, pages or text widget content, shortcode [WPeCounter].
 * Version:		 2.0.2
 * Author:		 Etruel Developments LLC
 * Author URI:	 https://etruel.com
 * Text Domain:  wpecounter
 * Domain Path: /languages/
 */
// Exit if accessed directly
if (!function_exists('add_filter'))
	exit;
// Plugin version
if (!defined('WPECOUNTER_VERSION'))
	define('WPECOUNTER_VERSION', '2.0.2');

if (!class_exists('WPeCounter')) :

	class WPeCounter {

		private static $instance = null;

		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		function __construct() {
			$this->setupGlobals();
			$this->includes();
			$this->load_textdomain();
			$this->hooks();
		}

		private function includes() {
			require_once WPECOUNTER_PLUGIN_DIR . 'includes/plugin-utils.php';
			require_once WPECOUNTER_PLUGIN_DIR . 'includes/functions.php';
			require_once WPECOUNTER_PLUGIN_DIR . 'includes/settings.php';
			require_once WPECOUNTER_PLUGIN_DIR . 'includes/version.php';
			require_once WPECOUNTER_PLUGIN_DIR . 'includes/widget.php';
			require_once WPECOUNTER_PLUGIN_DIR . 'includes/class-views.php';

			do_action('wpecounter_include_files');
		}

		private function setupGlobals() {

			// Plugin Folder Path
			if (!defined('WPECOUNTER_PLUGIN_DIR')) {
				define('WPECOUNTER_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}

			// Plugin Folder URL
			if (!defined('WPECOUNTER_PLUGIN_URL')) {
				define('WPECOUNTER_PLUGIN_URL', plugin_dir_url(__FILE__));
			}

			// Plugin Root File
			if (!defined('WPECOUNTER_PLUGIN_FILE')) {
				define('WPECOUNTER_PLUGIN_FILE', __FILE__);
			}
		}

		private function hooks() {

			// Always load translations.
			add_action('plugins_loaded', array($this, 'load_textdomain'));
			add_action('widgets_init', array($this, 'register_widgets'), 10);

			#register_activation_hook( plugin_basename( __FILE__ ), array( 'WPeCounterPluginUtils', 'activate' ) );
			#register_deactivation_hook( plugin_basename( __FILE__ ), array( 'WPeCounterPluginUtils', 'deactivate' ) );
			register_uninstall_hook(plugin_basename(__FILE__), array('WPeCounterPluginUtils', 'uninstall'));
		}

		/**
		 * Registers the plugin's widgets.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function register_widgets() {

			register_widget('WPeCounter_Widget');
			do_action('wpecounter_register_widgets');
		}

		public function load_textdomain() {
			// Set filter for plugin's languages directory
			$lang_dir	 = dirname(plugin_basename(__FILE__)) . '/languages/';
			$lang_dir	 = apply_filters('wpecounter_languages_directory', $lang_dir);
			load_plugin_textdomain('wpecounter', false, $lang_dir);
		}

	}

	endif; // End if class_exists check

$wpecounter = null;

function getClassWPeCounter() {
	global $wpecounter;
	if (is_null($wpecounter)) {
		$wpecounter = wpecounter::getInstance();
	}
	return $wpecounter;
}

getClassWPeCounter();
