<?php

if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/* function boilerplate_license_menu() {
  add_submenu_page(
  'edit.php?post_type=wpematico',
  'Boilerplate Settings',
  'BoilerPlate <span class="dashicons-before dashicons-admin-plugins"></span>',
  'manage_options',
  'boilerplate_license',
  'boilerplate_license_page'
  );
  //add_plugins_page( 'Plugin License', 'Plugin License', 'manage_options', 'boilerplate_license', 'boilerplate_license_page' );
  }
  add_action('admin_menu', 'boilerplate_license_menu');
 */



if (!class_exists('WPeCounterPluginUtils')) {

	class WPeCounterPluginUtils {

		function __construct() {
//			add_filter('admin_init', array(__CLASS__, 'init'), 10, 2);
//		}
//
//		public static function init() {
			//Additional links on the plugin page
			add_filter('plugin_row_meta', array(__CLASS__, 'init_row_meta'), 10, 2);
			add_filter('plugin_action_links_' . plugin_basename(WPECOUNTER_PLUGIN_FILE), array(__CLASS__, 'init_action_links'));
		}

		/**
		 * Actions-Links del Plugin
		 *
		 * @param   array   $data  Original Links
		 * @return  array   $data  modified Links
		 */
		public static function init_action_links($data) {
			if (!current_user_can('manage_options')) {
				return $data;
			}
			return array_merge(
					$data,
					array(
						'<a href="' . admin_url('options-general.php?page=WPeCounter') . '" title="' . __('Go to WP Views Counter Settings Page', 'wpecounter') . '">' . __('Settings', 'wpecounter') . '</a>',
					)
			);
		}

		/**
		 * Meta-Links del Plugin
		 *
		 * @param   array   $data  Original Links
		 * @param   string  $page  plugin actual
		 * @return  array   $data  modified Links
		 */
		public static function init_row_meta($data, $page) {
			if (basename($page) != basename(WPECOUNTER_PLUGIN_FILE)) {
				return $data;
			}
			return array_merge(
					$data,
					array(
						'<a href="http://etruel.com/my-account/support/" target="_blank">' . __('Technical Support') . '</a>',
						'<a href="http://etruel.com/downloads/premium-support/" target="_blank">' . __('Premium Support') . '</a>',
						'<a href="https://wordpress.org/support/view/plugin-reviews/wpecounter?filter=5&rate=5#postform" target="_Blank" title="Rate 5 stars on Wordpress.org">' . __('Rate Plugin', 'wpecounter') . '</a>',
						'<a href="#" onclick="javascript:window.open(\'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7267TH4PT3GSW\');return false;">' . __('Donate', 'wpecounter') . '</a>',
					)
			);
		}

		/**
		 * activation
		 *
		 * @access public
		 * @static
		 * @return void
		 */
		public static function activate() {
			
		}

		/**
		 * deactivation
		 *
		 * @access public
		 * @static
		 * @return void
		 */
		public static function deactivate() {
			
		}

		/**
		 * uninstallation
		 *
		 * @access public
		 * @static
		 * @global $wpdb, $blog_id
		 * @return void
		 */
		public static function uninstall() {
			global $wpdb, $blog_id;
			if (is_network_admin()) {
				if (isset($wpdb->blogs)) {
					$blogs = $wpdb->get_results(
							$wpdb->prepare(
									'SELECT blog_id ' .
									'FROM ' . $wpdb->blogs . ' ' .
									"WHERE blog_id <> '%s'",
									$blog_id
							)
					);
					foreach ($blogs as $blog) {
						delete_blog_option($blog->blog_id, 'WPeCounter_Options');
					}
				}
			}
			delete_option(self :: OPTION_KEY);
		}

	}

}

$WPeCounterPluginUtils = new WPeCounterPluginUtils();
