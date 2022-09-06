<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
//https://www.codetab.org/tutorial/wordpress-plugin-development/admin-module-settings/settings-api/

if (!class_exists('WPeCounterSettings')) {

	class WPeCounterSettings {

		/**
		 * Holds the instances of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance;

		/** $default_options
		 * Some settings to use by default
		 */
		protected $SettingsPage		 = 'WPeCounter';
		protected $section			 = 'WPeCounter-settings';
		protected $options_key		 = 'WPeCounter_Options';
		protected $heading			 = 'WP Views Counter Options';
		protected $description		 = 'These options are applied to the selected post-types counters.';
		protected $default_options	 = array(
			'cpostypes' => array(
				'post'	 => 1,
				'page'	 => 1
			)
		);

		function __construct() {

			add_action('admin_init', array($this, 'register_settings'));
			add_action('admin_menu', array($this, 'admin_menu'));

//			add_action('admin_print_scripts', array($this, 'scripts'));
//			add_action('admin_print_styles', array($this, 'styles'));
		}

		/**
		 * Settings Page
		 *
		 * @access public
		 * @return void
		 */
		public function admin_menu() {
			$page = add_submenu_page(
					'options-general.php',
					__('WP Views Counter', 'wpecounter'),
					__('WP Views Counter', 'wpecounter'),
					'manage_options',
					$this->SettingsPage,
					array($this, 'render_settings_page') /// FALTA
			);
			add_action('admin_print_styles-' . $page, array($this, 'WPeCounter_adminfiles'));
		}

		public function WPeCounter_adminfiles() {
//			wp_enqueue_script('jquery-admin', WPECOUNTER_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), WPECOUNTER_VERSION, true);
//			wp_enqueue_style('styles-admin', WPECOUNTER_PLUGIN_URL . 'assets/css/admin.css');
//			wp_localize_script('jquery-settings', 'wpecounter',
//					array('ajax_url'		 => admin_url('admin-ajax.php'),
//						'loading_image'	 => admin_url('images/spinner.gif'),
//						'loading_text'	 => __('Loading...', 'wpecounter'),
//					)
//			);
		}

		/**
		 * register_settings
		 */
		public function register_settings() {

//			delete_option($this->options_key);
			//$section		 = $this->section;
			//$option_group	 = $this->options_key;
			// no options - create them.
			if (false == get_option($this->options_key)) {
				add_option($this->options_key, $this->default_options);
			}

			$options = get_option($this->options_key);

			/**
			 * Check is exist each option and assign by default values
			 */
			if (false == isset($options['cpostypes'])) {
				$options['cpostypes'] = $this->default_options['cpostypes'];
			}

			add_settings_section(
					$this->section, // slug
					'', // Section title
					'', // Callback function that echos out any content at the top of the section (between heading and fields).
					$this->SettingsPage
			);

			add_settings_field(
					'cpostypes',
					__('Counter on Post types:', 'wpecounter'),
					array($this, 'checkboxes_callback'),
					$this->SettingsPage,
					$this->section,
					array('id'	 => 'cpostypes',
						'name'	 => 'WPeCounter_Options[cpostypes]',
						'values' => $options['cpostypes']
					)
			);

//			add_settings_field(
//					'impofield',
//					__('CAMPO DE TEXTo:', 'wpecounter'),
//					array($this, 'textinput'),
//					$this->SettingsPage,
//					$this->section,
//					array('id'	 => 'impofield',
//						'name'	 => 'WPeCounter_Options[cpostypes]',
//						'value'	 => $options['cpostypes']
//					)
//			);
			// finally
			register_setting(
					$this->options_key,
					$this->options_key,
					array($this, 'sanitize_options')
			);
		}

		/**
		 * an admin submenu page
		 *
		 * @access public
		 * @return void
		 */
		public function render_settings_page() {

			$heading = __($this->heading, 'wpecounter');
			$desc	 = __($this->description, 'wpecounter');

//			ob_start();
			?>
			<div class="wrap">
				<h2 class="nav-tab-wrapper">
					<span id="icon-tools" class="dashicons dashicons-chart-bar"></span>
					<?php echo $heading; ?>
				</h2>
				<br />
				<div>
					<?php // echo $desc;   ?>
				</div>

				<?php //echo settings_errors($this->SettingsPage); ?>

				<div id="tab_container">
					<form method="post" action="options.php">
						<div id="normal-sortables" class="meta-box-sortables ui-sortable">
							<div class="postbox inside">						
								<div class="inside">
									<table class="form-table">
										<?php
										settings_fields($this->options_key);
										do_settings_sections($this->SettingsPage);
										//do_settings_fields($this->SettingsPage,$this->section);
										?>
									</table>
								</div>
							</div>
							<?php submit_button(); ?>
						</div>
						<div id="normal-sortables" class="meta-box-sortables ui-sortable">
							<div class="postbox inside">
								<div class="inside">
									<h3><?php _e('Import other counters to WPeCounter Views', 'wpecounter'); ?></h3>
									<p><?php _e('Be careful with This option. There are not undo.', 'wpecounter'); ?><br />
										<small><?php _e('With this option you can import the numbers of visits that another script has stored in a custom-meta-field different that of WPeCounter.', 'wpecounter'); ?><br />
											<?php _e('This function basically copy (if any) the previous meta-field of each post to meta-field "Views" used by this WP Views Counter (aka WPeCounter). Replacing the current value of "Views" if it already exists.', 'wpecounter'); ?></small></p>
									<p><label>
											<input type="checkbox" class="checkbox" name="showimpo" value="1" onclick="jQuery('#metaimpo').toggle();" /> 
											<?php _e('Show import area.', 'wpecounter'); ?></label>
									</p>
									<div id="metaimpo" style="display:none;">
										<div>
											<strong><?php _e('Type name of custom meta field from another counter to import to WPeCounter Views meta field.', 'wpecounter'); ?></strong><br />
											<div style="display: table;margin: 10px 0;">
												<div style="display: table-cell;padding-right: 10px;">
													<input type="text" class="normal-text" name="impofield" value="">
												</div>
											</div>
										</div>
										<?php submit_button(__('Proceed to Import'), 'primary', 'submit', false); ?>
									</div>

								</div>
							</div>
						</div>						
					</form>
				</div><!-- #tab_container-->
			</div><!-- .wrap -->
			<?php
//			echo ob_get_clean();
		}

		/**
		 * 
		 * @param type $args
		 * @param type $echo
		 * @return string
		 */
		function textinput($args, $echo = true) {
			$html = '<input class="text" type="text" id="' . $args['id'] .
					'" name="' . $args['name'] . '" size="30" value="' .
					$args['value'] . '"/>';
			if ($echo) {
				echo $html;
			} else {
				return $html;
			}
		}

		/**
		 * Select Callback
		 *
		 * Renders select fields.
		 *
		 * @since 2.0
		 * @param array $args Arguments passed by the setting
		 * @return void
		 */
		public static function checkboxes_callback($data, $echo = true) {
			// Currently selected post types
			$cpostypes	 = $data['values'];
			unset($cpostypes['attachment']);  // Do not allow Views counter for attachments 
			// only publics as privates doesn't have visits
			$args		 = array('public' => true);
			$post_types	 = get_post_types($args, 'names'); // names or objects

			foreach ($post_types as $post_type) {
				if ($post_type == 'attachment')
					continue;  // ignore 'attachment'
				echo '<div><input type="checkbox" class="checkbox" name="' . $data['name'] . '[' . $post_type . ']" value="1" ';
				if (!isset($cpostypes[$post_type]))
					$cpostypes[$post_type] = false;
				checked($cpostypes[$post_type], true);
				echo ' /> ' . __($post_type) . '</div>';
			}
		}

		/**
		 * Select Callback
		 *
		 * Renders select fields.
		 *
		 * @since 2.0
		 * @param array $args Arguments passed by the setting
		 * @return void
		 */
		public static function select_callback($args, $echo = true) {

			if (isset($wpedpc_options[$args['id']])) {
				$value = $wpedpc_options[$args['id']];
			} else {
				$value = isset($args['value']) ? $args['value'] : '';
			}

			if (isset($args['placeholder'])) {
				$placeholder = $args['placeholder'];
			} else {
				$placeholder = '';
			}

			if (isset($args['chosen'])) {
				$chosen = 'class="wpecounter-chosen"';
			} else {
				$chosen = '';
			}

			$html = '<select id="wpedpc_settings[' . $args['id'] . ']" name="wpedpc_settings[' . $args['id'] . ']" ' . $chosen . 'data-placeholder="' . $placeholder . '" />';

			foreach ($args['options'] as $option => $name) {
				$selected	 = selected($option, $value, false);
				$html		 .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
			}

			$html	 .= '</select>';
			$html	 .= '<label for="wpedpc_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

			if ($echo) {
				echo $html;
			} else {
				return $html;
			}
		}

		/**
		 * A custom sanitization function that will take the incoming input, and sanitize
		 * the input before handing it back to WordPress to save to the database.
		 *
		 * @since    1.0.0
		 *
		 * @param    array    $input        The address input.
		 * @return   array    $new_input    The sanitized input.
		 */
		public function sanitize_options($input) {
			global $wpdb;
			// Initialize the new array that will hold the sanitize values
			$new_input = array();

			// Loop through the input and sanitize each of the values
			foreach ($input as $key => $val) {
				$new_input[$key] = (!is_array($val) ) ? sanitize_text_field($val) : $val;
			}

			if (isset($_POST['showimpo']) && $_POST['showimpo'] == 1 && !empty($_POST['impofield'])) {
				$impoviews = $_POST['impofield'];
				unset($_POST['impofield']);
				unset($_POST['showimpo']);
				if (!empty($impoviews)) {
					/* $introws = $wpdb->query( 
					  $wpdb->prepare(
					  "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key=%s
					  ",
					  $impoviews
					  )); */
					if (!isset($WPeCounterViews))
						$WPeCounterViews = new WPeCounterViews();
					$introws		 = $wpdb->query(
							$wpdb->prepare(
									"INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value)(
						SELECT post_id, '%s', meta_value FROM $wpdb->postmeta WHERE meta_key=%s )
						",
									$WPeCounterViews->wpecounter_views_meta_key(),
									$impoviews
					));
					if ($introws > 0) {
						add_settings_error($this->SettingsPage, '', __('Import success. Posts updated: ', 'wpecounter'). $introws, 'success');
					}else{
						add_settings_error($this->SettingsPage, '', __('There was no updates.', 'wpecounter'), 'warning');
					}
				}
			}

			return apply_filters('sanitize_options_' . WPECOUNTER_PLUGIN_FILE, $new_input, $input);
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

WPeCounterSettings::get_instance();
?>