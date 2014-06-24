<?php
/**
 * Plugin Name.
 *
 * @package   cmbSlider
 * @author    Primoz Krkovic <primoz@primas.si>
 * @license   GPL-2.0+
 * @link      medonet.si
 * @copyright 2014 Primoz Krkovic
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-cmbslider-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package cmbSlider
 * @author  Your Name <email@example.com>
 */

require_once( plugin_dir_path( __FILE__ ) . 'includes/interface-template.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-prettyphoto-template.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flexslider-template.php' );
class cmbSlider {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';
	/**
	 * custom meta post fields
	 */
    const META_FIELD_TYPE ="cmbslider_type";
    const META_FIELD_ATTACHMENTS = "cmbslider_attachment";
	/**
	 * @TODO - Rename "cmbslider" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $output_type = "";

	private $attachment_ids = array();

	protected $plugin_slug = 'cmbslider';
    private $template_handler = null;
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */

	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'wp', array( $this, 'setup_plugin' ) );
		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
      
      
        
		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		//add_action( '@TODO', array( $this, 'action_method_name' ) );
		//add_filter( '@TODO', array( $this, 'filter_method_name' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_attachment_ids()
	{
        return	$this->attachment_ids;
	}

	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        if(isset($this->template_handler))  {
		    $this->template_handler->enque_css();

		    wp_enqueue_style( $this->plugin_slug . '-plugin-style', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
		}
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        if(isset($this->template_handler))  { 
       
           $this->template_handler->enque_js();	
		
   			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
   		}
	}

	/**
	 * NOTE:  Actions area points in the execution of a page or process
	 *        lifecycle tahat WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */

	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

	public function get_option( $option, $section, $default = '' ) {
	 
	    $options = get_option( $section );
	 
	    if ( isset( $options[$option] ) ) {
	        return $options[$option];
	    }
	 
	    return $default;
	}

	public function setup_plugin(){
        global $post;
       
        if (isset($post))
        {
		  
			
		    $this->output_type =	 get_post_meta($post->ID, cmbSlider::META_FIELD_TYPE , true);
		    $this->attachment_ids =  get_post_meta($post->ID, cmbSlider::META_FIELD_ATTACHMENTS , true);
		   
			switch ($this->output_type) {     
			case "1":
		        $this->template_handler = new Flexslider_Template($this);
		        break;
		    case "2":
		         $this->template_handler = new PrettyPhoto_Template($this);
		        break;
		    }
	    }
	}
   // print_r(	get_post_meta($post->ID, cmbSlider::META_FIELD_ATTACHMENTS , true));
	public function render_output(){
		if(isset($this->template_handler) && !empty($this->attachment_ids))  {
			$this->template_handler->render();
	    }
	}
}

	function cmb_show_slider()
	{       
	    $plugin = cmbSlider::get_instance();
		$plugin->render_output();
		   		   
	}

	function wp_get_attachment( $attachment_id ) {

    $attachment = get_post( $attachment_id );
    return (object) array(
        'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => get_permalink( $attachment->ID ),
        'src' => $attachment->guid,
        'title' => $attachment->post_title)
    ;
}
   