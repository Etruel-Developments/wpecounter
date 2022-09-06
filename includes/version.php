<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WPeCounter_version {
	public static function hooks() {
		add_action('admin_init', array(__CLASS__, 'init'), 11);
	}

	public static function init() {
		$current_version = get_option('WPeCounter_db_version', 0.0);
		if (version_compare($current_version, WPECOUNTER_VERSION, '<')) {
			// Update
			update_option('WPeCounter_db_version', WPECOUNTER_VERSION);
			
			//Make the magic on update
			
			if (version_compare($current_version, 0.0, '=')) {
//				wp_redirect( sanitize_url(site_url($_POST['_wp_http_referer'])) );
			}
			
		}
	}
}
WPeCounter_version::hooks();
?>
