<?php

if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}



if (!class_exists('WPeCounterPluginUtils')) {

	class WPeCounterPluginUtils {

		// Constructor: Hooks into WordPress actions and filters
		function __construct() {
			// Register the block during init
			add_action('init', array(__CLASS__, 'mvpb_register_block'));

			// Enqueue editor assets for the block
			add_action('enqueue_block_editor_assets', array(__CLASS__, 'wpecounter_enqueue_block_editor_assets'));

			// Add a custom block category to the block editor
			add_filter('block_categories_all', function ($categories, $post) {
				$custom_category = [
					[
						'slug'  => 'wpecounter',
						'title' => __('WP Views Counter', 'wpecounter'),
						'icon'  => 'visibility', // Optional icon
					],
				];
				// Add the custom category at the beginning of the list
				return array_merge($custom_category, $categories);
			}, 10, 2);

			// Add custom links in the plugin list
			add_filter('plugin_row_meta', array(__CLASS__, 'init_row_meta'), 10, 2);
			add_filter('plugin_action_links_' . plugin_basename(WPECOUNTER_PLUGIN_FILE), array(__CLASS__, 'init_action_links'));
		}

		// Register the dynamic block and link the render callback
		public static function mvpb_register_block() {
			register_block_type(WPECOUNTER_PLUGIN_DIR, array(
				'render_callback' => array(__CLASS__, 'mvpb_render_most_viewed'),
			));
		}

		// Render callback for the dynamic block (frontend output)
		public static function mvpb_render_most_viewed($attributes){
			// Set default values for attributes
			if (! isset($attributes['postType'])) {
				$attributes['postType'] = 'post';
			}
			if (! isset($attributes['limit'])) {
				$attributes['limit'] = 5;
			}
			if (! isset($attributes['order'])) {
				$attributes['order'] = 'DESC';
			}
			if (! isset($attributes['title'])) {
				$attributes['title'] = __('Most Popular', 'text-domain');
			}

			// Instantiate the views counter object if not already set
			if (!isset($WPeCounterViews)) {
				$WPeCounterViews = new WPeCounterViews();
			}

			// Query arguments to retrieve the most viewed posts
			$args = array(
				'post_type'           => sanitize_text_field($attributes['postType']),
				'posts_per_page'      => intval($attributes['limit']),
				'post_status'         => 'publish',
				'meta_key'            => $WPeCounterViews->wpecounter_views_meta_key(),
				'orderby'             => 'meta_value_num',
				'order'               => in_array(strtoupper($attributes['order']), ['ASC', 'DESC']) ? strtoupper($attributes['order']) : 'DESC',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			);

			$posts = get_posts($args);

			// If no posts found, return fallback message
			if (empty($posts)) {
				return '<p>' . esc_html__('No popular posts found.', 'text-domain') . '</p>';
			}

			// Wrapper with dynamic block attributes (e.g., className, etc.)
			$wrapper = get_block_wrapper_attributes();

			// Start building HTML output
			$output  = "<div {$wrapper}>";
			$output .= '<div class="mvpb-block">';
			$output .= '<h3 class="mvpb-block-title">' . esc_html($attributes['title']) . '</h3>';
			$output .= '<ul class="mvpb-post-list">';

			// Loop through the posts and create a list item for each
			foreach ($posts as $post) {
				$title = esc_html(get_the_title($post->ID));
				$url   = esc_url(get_permalink($post->ID));
				$views = $WPeCounterViews->get_post_views_count($post->ID);
				$output .= "<li class='mvpb-post-item'><a href='{$url}'>{$title}</a> ({$views})</li>";
			}

			$output .= '</ul>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

		// Enqueue JavaScript and pass data to the block editor
		public static function wpecounter_enqueue_block_editor_assets(){
			wp_enqueue_script(
				'wpecounter-block-editor', WPECOUNTER_PLUGIN_DIR . 'build/index.js',
			);

			// Get plugin options
			$options = get_option('WPeCounter_Options');
			$cpostypes = isset($options['cpostypes']) ? (array) $options['cpostypes'] : [];
			
			// Get all public post types
			$args = array('public' => true);
			$post_types = get_post_types($args, 'names');

			// List of post types to exclude
			$exclude = array('attachment', 'revision', 'nav_menu_item');

			// Filter post types based on user settings and exclusion list
			$post_types_filtered = array_filter($post_types, function ($pt) use ($exclude, $cpostypes) {
				return !in_array($pt, $exclude, true) && isset($cpostypes[$pt]) && $cpostypes[$pt] === '1';
			});

			// Format the post types for Select dropdown in block editor
			$select_options = [];
			foreach ($post_types_filtered as $pt) {
				$select_options[] = [
					'label' => ucfirst($pt),
					'value' => $pt,
				];
			}

			// Pass the data to the block editor script
			wp_localize_script(
				'wpecounter-block-editor',
				'wpecounterData',
				array(
					'postTypes' => $select_options,
				)
			);
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
			delete_option('WPeCounter_Options');
		}

	}

}

$WPeCounterPluginUtils = new WPeCounterPluginUtils();
