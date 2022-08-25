<?php
/*
 Plugin Name: WPeCounter
 Description: Shows a views counter on lists of posts, pages and/or custom post types. Also on posts, pages or text widget contents, shortcode [WPeCounter].
 Version: 1.2
 Author: Esteban Truelsegaard <esteban@netmdp.com>
 Author URI: http://www.netmdp.com
 */
# @charset utf-8

if ( ! function_exists( 'add_filter' ) )
	exit;

if ( ! class_exists( 'WPeCounter' ) ) {

	add_action( 'init', array( 'WPeCounter', 'init' ) );

	if ( !function_exists( 'entry_views_update' ) ) require_once( 'entry-views.php' );
	
	#register_aktivation_hook( plugin_basename( __FILE__ ), array( 'WPeCounter', 'activate' ) );
	#register_deactivation_hook( plugin_basename( __FILE__ ), array( 'WPeCounter', 'deactivate' ) );
	register_uninstall_hook( plugin_basename( __FILE__ ), array( 'WPeCounter', 'uninstall' ) );

	/* Add the [entry-views] shortcode. */
	add_shortcode( 'WPeCounter', 'entry_views_get' );

	add_filter( 'widget_text', 'shortcode_unautop');
	add_filter( 'widget_text', 'do_shortcode');

	
	class WPeCounter {

		const TEXTDOMAIN = 'wpecounter';

		//const VERSION = '1.2';

		/**		 * Option Key		 */
		const OPTION_KEY = 'WPeCounter_Options';

		/**		 * $uri
		 * absolute uri to the plugin with trailing slash
		 */
		public static $uri = '';

		/**		 * $dir
		 * filesystem path to the plugin with trailing slash
		 */
		public static $dir = '';
		public static $name = '';
		public static $version = '';

		/**		 * $default_options
		 * Some settings to use by default
		 */
		protected static $default_options = array(
			'cpostypes' => array(
				'post' => 1,
				'page' => 1
			)
		);

		/**		 * $options		 */
		protected $options = array();

		/**		 * init		 */
		public static function init() {
		
			self :: $uri = plugin_dir_url( __FILE__ );
			self :: $dir = plugin_dir_path( __FILE__ );
			self :: load_textdomain_file();
			new self( TRUE );
		}

		/**		 * constructor		 */
		public function __construct( $hook_in = FALSE ) {
			$this->load_options();
			$cpostypes = $this->options['cpostypes'];
			$args=array( 'public'   => true );
			$output = 'names'; // names or objects
			$post_types=get_post_types($args,$output); 
			foreach ($post_types  as $post_type ) {
				if( isset($cpostypes[$post_type]) && @$cpostypes[$post_type] ) {
					add_post_type_support( $post_type, 'entry-views' ) ;
				}
			}			
			if ( $hook_in ) {
				add_action( 'admin_init', array( &$this, 'admin_init' ) );
				add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			}
			//Additional links on the plugin page
			add_filter(	'plugin_row_meta',	array(	__CLASS__, 'init_row_meta'),10,2);
			add_filter(	'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__,'init_action_links'));

		}

		public static function posts_columns_id($columns){
			//$columns['post_views'] = ''.__('Views', self :: TEXTDOMAIN ) . '';
/* 			$column_post_views = array( 'post_views' => '<div style="text-align: right;width: 60px;">'.__('Views', self :: TEXTDOMAIN ) . '</div>' );
			$columns = $columns + $column_post_views ;
 */
			$column_post_views = array( 'post_views' => ''.__('Views', self :: TEXTDOMAIN ) . '' );
			// 5to lugar
			//$columns = array_slice( $columns, 0, 5, true ) + $column_post_views + array_slice( $columns, 5, NULL, true );
			$columns = array_merge( $columns, $column_post_views );
		return $columns; }

		public static function posts_custom_id_columns($column_name, $id){
			if($column_name === 'post_views'){
				if ( function_exists( 'entry_views_get' ) ) echo ''.entry_views_get( array( 'post_id' => $id ) ) . ''; 
			}
		}
		
		public static function views_column_register_sortable($columns) {
			$custom = array(
				'post_views' 	=> 'post_views'
			);
			return wp_parse_args($custom, $columns);
		}
		
		public static function views_column_orderby() {
			global $pagenow, $post_type;
			if( 'edit.php' != $pagenow || !isset( $_GET['orderby'] ) )
				return;
			if( 'post_views' == $_GET['orderby'] )  {
				$meta_group = array(
					'key' => 'Views',
					'type' => 'numeric',
				);
				set_query_var( 'meta_query', array( 'sort_column'=>'post_views', $meta_group ) );
				set_query_var( 'meta_key','Views' );
				set_query_var( 'orderby','meta_value_num' );
			}
		} 
		
		public static function post_views_column_width() {
			echo '<style type="text/css">';
			echo '.column-post_views { text-align: center !important; width:80px !important; overflow:hidden; }';
			echo '</style>';
		}
		//****** Contador de visitas
		public function admin_init() {
			$plugin_data = get_plugin_data( __FILE__ );
			self :: $name = $plugin_data['Name'];
			self :: $version = $plugin_data['Version'];

			wp_register_style( 'oplugincss', plugin_dir_url( __FILE__ ).'oplugins.css');
			wp_register_script( 'opluginjs', plugin_dir_url( __FILE__ ).'oplugins.js');
			
			$cpostypes = $this->options['cpostypes'];
			
			$args=array( 'public'   => true );
			$output = 'names'; // names or objects
			$post_types=get_post_types($args,$output); 
			foreach ($post_types  as $post_type ) {
				if( @$cpostypes[$post_type]) {
				//	add_filter('manage_'.$post_type.'_posts_columns', array( 'WPeCounter', 'posts_columns_id'), 5);
					add_filter('manage_edit-'.$post_type.'_columns', array( 'WPeCounter', 'posts_columns_id'), 10);
					add_action('manage_'.$post_type.'_posts_custom_column', array( 'WPeCounter', 'posts_custom_id_columns'), 5, 2);
					//Order
					add_filter( 'manage_edit-'.$post_type.'_sortable_columns',  array( 'WPeCounter', 'views_column_register_sortable'));
				}
			}
			add_action( 'parse_query', array( 'WPeCounter', 'views_column_orderby') );
			add_action( 'admin_head', array( 'WPeCounter', 'post_views_column_width') );

		}

		/**
		 * admin menu
		 *
		 * @access public
		 * @return void
		 */
		public function admin_menu() {
			$page= add_submenu_page(
				'options-general.php',
				__( 'WPeCounter', self :: TEXTDOMAIN ),
				__( 'WPeCounter', self :: TEXTDOMAIN ),
				'manage_options',
				'WPeCounter',
				array( &$this, 'add_admin_submenu_page' )
			);
			add_action('admin_print_styles-' . $page,  array( &$this, 'WPeCounter_adminfiles') );
		}
		
		public function WPeCounter_adminfiles () {
			wp_enqueue_style( 'oplugincss' );
			wp_enqueue_script( 'opluginjs' );
		}

		/**
		 * an admin submenu page
		 *
		 * @access public
		 * @return void
		 */
		public function add_admin_submenu_page () {
			global $wpdb;
			if ( 'POST' === $_SERVER[ 'REQUEST_METHOD' ] ) {
				if ( get_magic_quotes_gpc() ) {
					$_POST = array_map( 'stripslashes_deep', $_POST );
				}
				$impoviews = $_POST['impofield'];
				unset($_POST['impofield']);   // Borro el valor para que no lo agregue a las opciones guardadas
				if(!empty($impoviews)){
					/* $introws = $wpdb->query( 
						$wpdb->prepare(
							"SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key=%s
						",
						$impoviews 
					)); */
					$introws = $wpdb->query( 
						$wpdb->prepare(
							"INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value)(
						SELECT post_id, 'Views', meta_value FROM $wpdb->postmeta WHERE meta_key=%s )
						",
						$impoviews 
					));
					if($introws > 0){
						?><div class="updated"><p> <?php echo $introws .' '. __( 'Posts updated.', self :: TEXTDOMAIN );?></p></div><?php
					}
				}


				# evaluation goes here
				$this->options = $_POST;

				# saving
				if ( $this->update_options() ) {
					?><div class="updated"><p> <?php _e( 'Settings saved', self :: TEXTDOMAIN );?></p></div><?php
				}
			}
			
			$this->load_options();
			?>
			<div class="wrap">
				<h2><?php _e( 'WPeCounter settings', self :: TEXTDOMAIN );?></h2>
				<form method="post" action="">
				<div id="poststuff" class="metabox-holder has-right-sidebar">
					<div id="side-info-column" class="inner-sidebar">
						<?php include('myplugins.php');	?>
					</div>
					<div id="post-body">
						<div id="post-body-content">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div class="postbox inside">
									<h3><?php _e( 'WPeCounter options', self :: TEXTDOMAIN );?></h3>
									<div class="inside">
										<a style="float:right;" target="_Blank" href="http://www.netmdp.com"><img src="<?php echo self :: $uri ; ?>NetMdP.png"></a>
										<p></p>
										<div><strong><?php _e( 'Select Custom post types that must be count views.', self :: TEXTDOMAIN );?></strong><br />
											<div style="display: table;margin: 10px 0;">
											<div style="display: table-cell;padding-right: 10px;">
											<?php 
												// solo publicos porque los privados no tienen visitas
												$args=array( 'public'   => true );
												$output = 'names'; // names or objects
												$cpostypes = $this->options['cpostypes'];
												unset($cpostypes['attachment']);
												$post_types=get_post_types($args,$output); 
												foreach ($post_types  as $post_type ) {
													if ($post_type=='attachment') continue;  // ignore 'attachment'
													echo '<div><input type="checkbox" class="checkbox" name="cpostypes['.$post_type.']" value="1" '; 
													if(!isset($cpostypes[$post_type])) $cpostypes[$post_type] = false;
													checked( $cpostypes[$post_type],true);
													echo ' /> '. __( $post_type ) .'</div>';
												}
											?>
											</div>
											</div>
										</div>
										<p></p>
										
										<p><input type="submit" class="button-primary" name="submit" value="<?php _e('Save');?>" /></p>
										<p></p>									
										
									</div>
								</div>
							</div>
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div class="postbox inside">
									<h3><?php _e( 'Import other counters to WPeCounter Views', self :: TEXTDOMAIN );?></h3>
									<div class="inside">
										<p><?php _e( 'Be careful with This option. There are not undo.', self :: TEXTDOMAIN );?><br />
										<small><?php _e( 'With this option you can import the numbers of visits that another script has stored in a custom-meta-field different that of WPeCounter.', self :: TEXTDOMAIN );?><br />
										<?php _e( 'This function basically copy (if any) the previous meta-field of each post to meta-field "Views" used by WPeCounter. Replacing the current value of "Views" if it already exists.', self :: TEXTDOMAIN );?></small></p>
										<p><label>
										<input type="checkbox" class="checkbox" name="showimpo" value="1" onclick="jQuery('#metaimpo').toggle();" /> 
										<?php _e( 'Show import area.', self :: TEXTDOMAIN ); ?></label>
										</p>
										<div id="metaimpo" style="display:none;">
											<div>
												<strong><?php _e( 'Type name of custom meta field from another counter to import to WPeCounter Views meta field.', self :: TEXTDOMAIN );?></strong><br />
												<div style="display: table;margin: 10px 0;">
												<div style="display: table-cell;padding-right: 10px;">
													<input type="text" class="normal-text" name="impofield" value="">
												</div>
												</div>
											</div>
											<p></p>
											
											<p><input type="submit" class="button-primary" name="submit" value="<?php _e('Save & Import');?>" /></p>
											<p></p>									
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</form>

			</div><?php
		}
		/**
		 * load_textdomain_file
		 *
		 * @access protected
		 * @return void
		 */
		protected static function load_textdomain_file() {
			# load plugin textdomain
			load_plugin_textdomain( self :: TEXTDOMAIN, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang' );			
			//load_plugin_textdomain( self :: TEXTDOMAIN, FALSE, basename( plugin_basename( __FILE__ ) ) . '/lang' );
			# load tinyMCE localisation file
			#add_filter( 'mce_external_languages', array( &$this, 'mce_localisation' ) );
		}

		/**
		 * mce_localisation
		 *
		 * @access public
		 * @param array $mce_external_languages
		 * @return array
		 */
		public function mce_localisation( $mce_external_languages ) {

			if ( file_exists( self :: $dir . 'lang/mce_langs.php' ) )
				$mce_external_languages[ 'inpsydeOembedVideoShortcode' ] = self :: $dir . 'lang/mce-langs.php';
			return $mce_external_languages;
		}
		/**
		 * load_options
		 *
		 * @access protected
		 * @return void
		 */
		protected function load_options() {

			if ( ! get_option( self :: OPTION_KEY ) ) {
				if ( empty( self :: $default_options ) )
					return;
				$this->options = self :: $default_options;
				add_option( self :: OPTION_KEY, $this->options , '', 'yes' );
			}
			else {
				$this->options = get_option( self :: OPTION_KEY );
			}
		}

		/**
		 * update_options
		 *
		 * @access protected
		 * @return bool True, if option was changed
		 */
		public function update_options() {
			return update_option( self :: OPTION_KEY, $this->options );
		}

				/**
		* Actions-Links del Plugin
		*
		* @param   array   $data  Original Links
		* @return  array   $data  modified Links
		*/
		public static function init_action_links($data)	{
			if ( !current_user_can('manage_options') ) {
				return $data;
			}
			return array_merge(
				$data,
				array(
					'<a href="'. admin_url('options-general.php?page=WPeCounter') .'" title="' . __('Load WPeCounter Settings Page', self :: TEXTDOMAIN ) . '">' . __('Settings', self :: TEXTDOMAIN ) . '</a>',
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

		public static function init_row_meta($data, $page)	{
			if ( basename($page) != basename(__FILE__) ) {
				return $data;
			}
			return array_merge(
				$data,
				array(
				'<a href="http://etruel.com/my-account/support/" target="_blank">' . __('Technical Support') . '</a>',
				'<a href="http://etruel.com/downloads/premium-support/" target="_blank">' . __('Premium Support') . '</a>',
				'<a href="https://wordpress.org/support/view/plugin-reviews/wpecounter?filter=5&rate=5#postform" target="_Blank" title="Rate 5 stars on Wordpress.org">' . __('Rate Plugin', self :: TEXTDOMAIN ) . '</a>',
				'<a href="#" onclick="javascript:window.open(\'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7267TH4PT3GSW\');return false;">' . __('Donate', self :: TEXTDOMAIN ) . '</a>',
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
			if ( is_network_admin() ) {
				if ( isset ( $wpdb->blogs ) ) {
					$blogs = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT blog_id ' .
							'FROM ' . $wpdb->blogs . ' ' .
							"WHERE blog_id <> '%s'",
							$blog_id
						)
					);
					foreach ( $blogs as $blog ) {
						delete_blog_option( $blog->blog_id, self :: OPTION_KEY );
					}
				}
			}
			delete_option( self :: OPTION_KEY );
		}
	}
}

