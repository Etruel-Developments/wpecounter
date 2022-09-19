<?php

if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}


if (!class_exists('WPeCounterViews')) {

	class WPeCounterViews {

		/**
		 * Holds the instances of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance;

		/**
		 * The post ID to update the entry views for.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    int
		 */
		public $post_id = 0;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			add_action('init', array($this, 'post_type_support'), 10);

			/* Register shortcodes. */
			add_action('init', array($this, 'register_shortcodes'), 10);

			/* Registers the entry views extension scripts if we're on the correct page. */
			add_action('template_redirect', array($this, 'load'), 99);

			/* Ajax calls */
			add_action('wp_ajax_entry_views', array($this, 'update_ajax_single_views'), 10);
			add_action('wp_ajax_nopriv_entry_views', array($this, 'update_ajax_single_views'), 10);

			/* Admin filters    */
			add_action('admin_init', array($this, 'admin_init'));
		}

		/**
		 * Adds support for 'wpecounter' to the 'post', 'page', and 'attachment' post types (default WordPress 
		 * post types).  For all other post types, the theme should explicitly register support for this feature.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		function post_type_support() {
			$options = get_option('WPeCounter_Options'); // $WPeCounterSettings->options_key);

			$cpostypes	 = $options['cpostypes'];
			$args		 = array('public' => true);
			$output		 = 'names'; // names or objects
			$post_types	 = get_post_types($args, $output);
			foreach ($post_types as $post_type) {
				if (isset($cpostypes[$post_type]) && @$cpostypes[$post_type]) {
					add_post_type_support($post_type, 'wpecounter');
				}
			}
		}

		/**
		 * Registers shortcodes for the plugin.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		function register_shortcodes() {

			add_filter('widget_text', 'shortcode_unautop');
			add_filter('widget_text', 'do_shortcode');

			add_shortcode('wpecounter', array($this, 'entry_views_shortcode'));
			add_shortcode('WPeCounter', array($this, 'entry_views_shortcode'));
		}

		/**
		 * Gets the number of views a specific post has.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  array  $attr  Attributes for use in the shortcode.
		 * @return string
		 */
		function entry_views_shortcode($attr = '') {

			$defaults = array(
				'before'	 => '',
				'after'		 => '',
				'text'		 => '',
				'post_id'	 => get_the_ID()
			);

			$attr = shortcode_atts($defaults, $attr, 'wpecounter');

			return wpecounter_get_post_views($attr);
		}

		/**
		 * Checks if we're on a singular post view and if the current post type supports the 'wpecounter'
		 * extension.  If so, set the $post_id variable and load the needed JavaScript.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		function load() {

			/* Check if we're on a singular post view. */
			if (is_singular() && !is_preview()) {

				/* Get the post object. */
				$post = get_queried_object();

				/* Check if the post type supports the 'wpecounter' feature. */
				if (post_type_supports($post->post_type, 'wpecounter')) {

					/* Set the post ID for later use because we wouldn't want a custom query to change this. */
					$this->post_id = get_queried_object_id();

					/* Enqueue the jQuery library. */
					wp_enqueue_script('jquery');

					/* Load the entry views JavaScript in the footer. */
					add_action('wp_footer', array($this, 'load_single_scripts'));
				}
			}
		}

		/**
		 * Sets the post view count of specific post by adding +1 to the total count.  This function should only 
		 * be used if you want to add an addtional +1 to the count.		 
		 * 
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		function update_single_views($post_id) {
			/* If we're on a singular view of a post, calculate the number of views. */
			if (!empty($post_id)) {

				/* Allow devs to override the meta key used. By default, this is 'Views'. */
				$meta_key = $this->wpecounter_views_meta_key();

				/* Get the number of views the post currently has. */
				$old_views = get_post_meta($post_id, $meta_key, true);

				/* Add +1 to the number of current views. */
				$new_views = absint($old_views) + 1;

				/* Update the view count with the new view count. */
				update_post_meta($post_id, $meta_key, $new_views, $old_views);
			}
		}

		/**
		 * Gets the number of views a specific post has.  
		 *
		 * @since 0.1
		 * @param array $attr Attributes for use in the shortcode.
		 */
		public static function get_post_views_count($post_id) {

			/* Get the number of views the post has. */
			$views = get_post_meta($post_id, self::wpecounter_views_meta_key(), true);

			/* Return the view count and make sure it's an integer. */
			return !empty($views) ? number_format_i18n(absint($views)) : 0;
		}

		/**
		 * Allow devs to override the meta key used. By default, this is 'Views'. 
		 * 
		 * apply_filters('entry_views_meta_key', 'Views');
		 * 
		 * @return 'Views'
		 */
		public static function wpecounter_views_meta_key() {
			return apply_filters('entry_views_meta_key', 'Views');
		}

		/**
		 * Callback function hooked to 'wp_ajax_entry_views' and 'wp_ajax_nopriv_entry_views'.  It checks the
		 * AJAX nonce and passes the given $post_id to the entry views update function.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		function update_ajax_single_views() {

			/* Check the AJAX nonce to make sure this is a valid request. */
			check_ajax_referer('entry_views_ajax');

			/* If the post ID is set, set it to the $post_id variable and make sure it's an integer. */
			if (isset($_POST['post_id']))
				$post_id = absint($_POST['post_id']);

			/* If $post_id isn't empty, pass it to the $this->update_single_views() function to update the view count. */
			if (!empty($post_id))
				$this->update_single_views($post_id);
		}

		/**
		 * Displays a small script that sends an AJAX request for the page.  It passes the $post_id to the AJAX 
		 * callback function for updating the meta.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		function load_single_scripts() {

			/* Create a nonce for the AJAX request. */
			$nonce = wp_create_nonce('entry_views_ajax');

			/* Display the JavaScript needed. */
			echo '<script type="text/javascript">/* <![CDATA[ */ jQuery(document).ready( function() { jQuery.post( "' . admin_url('admin-ajax.php') . '", { action : "entry_views", _ajax_nonce : "' . $nonce . '", post_id : ' . absint($this->post_id) . ' } ); } ); /* ]]> */</script>' . "\n";
		}

		/**
		 * Init filters to display column and order by views on post types lists.
		 *
		 * @access public
		 * @return object
		 */
		public function admin_init() {
			$options	 = get_option('WPeCounter_Options'); // $WPeCounterSettings->options_key);
			$cpostypes	 = $options['cpostypes'];

			$args		 = array('public' => true);
			$output		 = 'names'; // names or objects
			$post_types	 = get_post_types($args, $output);
			foreach ($post_types as $post_type) {
				if (@$cpostypes[$post_type]) {
					//	add_filter('manage_'.$post_type.'_posts_columns', array( $this, 'posts_columns_id'), 5);
					add_filter('manage_edit-' . $post_type . '_columns', array($this, 'posts_columns_id'), 10);
					add_action('manage_' . $post_type . '_posts_custom_column', array($this, 'posts_custom_id_columns'), 10, 2);
					//Order
					add_filter('manage_edit-' . $post_type . '_sortable_columns', array($this, 'views_column_register_sortable'));
				}
			}
			add_action('pre_get_posts', array($this, 'views_column_orderby'));
//			add_action('parse_query', array($this, 'views_column_orderby'));
			add_action('admin_head', array($this, 'post_views_column_width'));
//			
//			
//			add_filter('manage_edit-' . $post_type . '_columns', array(__CLASS__, 'set_edit_wpematico_columns'));
//			add_action('manage_' . $post_type . '_posts_custom_column', array(__CLASS__, 'custom_wpematico_column'), 10, 2);
//			add_filter("manage_edit-' . $post_type . '_sortable_columns", array(__CLASS__, "sortable_columns"));
//			add_action('pre_get_posts', array(__CLASS__, 'column_orderby'));
//			
		}

		public function posts_columns_id($columns) {

			$column_post_views	 = array('post_views' => '' . __('Views', 'wpecounter') . '');
			// 5to lugar
			//$columns = array_slice( $columns, 0, 5, true ) + $column_post_views + array_slice( $columns, 5, NULL, true );
			$columns			 = array_merge($columns, $column_post_views);
			return $columns;
		}

		public function posts_custom_id_columns($column_name, $id) {
			if ($column_name === 'post_views') {
				echo '' . wpecounter_post_views(array('post_id' => $id)) . '';
			}
		}

		public function views_column_register_sortable($columns) {
			$custom = array(
				'post_views' => 'post_views'
			);
			return wp_parse_args($custom, $columns);
		}

		public function views_column_orderby($query) {
			global $pagenow, $post_type;
			$orderby = $query->get('orderby');
//			if ('edit.php' != $pagenow || empty($orderby) )
//				return;
			switch ($orderby) {
				case 'post_views':

					$query->set('meta_query', array(
						'sort_column'	 => 'post_views',
						'relation'		 => 'OR',
						'views_clause'	 => array(
							'key'	 => $this->wpecounter_views_meta_key(),
							'type'	 => 'numeric'
						),
						'noviews_clause' => array(
							'key'		 => $this->wpecounter_views_meta_key(),
							'compare'	 => 'NOT EXISTS'
						)
					));

//					$query->set('meta_key', $this->wpecounter_views_meta_key() );
//					$query->set('meta_type', 'NUMERIC');
//					$query->set('suppress_filters', false);
//					$query->set('hide_empty', false);
					$query->set('orderby', 'meta_value_num');

					break;

				default:
					break;
			}
		}

//		public function views_column_orderby() {
//			global $pagenow, $post_type;
//			if ('edit.php' != $pagenow || !isset($_GET['orderby']))
//				return;
//			if ('post_views' == $_GET['orderby']) {
//				set_query_var('meta_query', array('sort_column' => 'post_views') );
//				set_query_var('meta_key', $this->wpecounter_views_meta_key());
//				set_query_var('orderby', 'meta_value_num');
//			}
//		}

		public function post_views_column_width() {
			echo '<style type="text/css">';
			echo '.column-post_views { text-align: center !important; width:80px !important; overflow:hidden; }';
			echo '</style>';
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

//$WPeCounterViews = new WPeCounterViews();
WPeCounterViews::get_instance();
