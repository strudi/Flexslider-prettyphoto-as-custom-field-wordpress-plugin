<?php
/**
 * Plugin Name.
 *
 * @package   cmbSlider_Admin
 * @author    Primoz Krkovic <primoz@primas.si>
 * @license   GPL-2.0+
 * @link      medonet.si
 * @copyright 2014 Primoz Krkovic
 */
/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-cmbslider.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package cmbSlider_Admin
 * @author  Your Name <email@example.com>
 */
require_once (plugin_dir_path(__FILE__) . 'includes/settings-api.php');

class cmbSlider_Admin {
    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;
    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;
    public $settings_api = null;
    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {
        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
        */
        /* if( ! is_super_admin() ) {
        return;
        } */
        /*
         * Call $plugin_slug from public plugin class.
         *
         * @TODO:
         *
         * - Rename "cmbSlider" to the name of your initial plugin class
         *
        */
      
       
   


            $plugin = cmbSlider::get_instance();
            $this->plugin_slug = $plugin->get_plugin_slug();
            // Load admin style sheet and JavaScript.
            add_action('admin_enqueue_scripts', array(
                $this,
                'enqueue_admin_styles'
            ));
            add_action('admin_enqueue_scripts', array(
                $this,
                'enqueue_admin_scripts'
            ));
            // Add the options page and menu item.
            add_action('admin_menu', array(
                $this,
                'add_plugin_admin_menu'
            ));
            // Add an action link pointing to the options page.
            $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_slug . '.php');
            add_filter('plugin_action_links_' . $plugin_basename, array(
                $this,
                'add_action_links'
            ));
           $this->settings_api = new WeDevs_Settings();
           $this->slider_meta_boxes();
      
    }
    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
        */
        /* if( ! is_super_admin() ) {
        return;
        } */
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @TODO:
     *
     * - Rename "cmbSlider" to the name your plugin
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function is_admin_required(){
       global $pagenow;
     
       $screen = get_current_screen();
       $alloved_post_types =  $this->settings_api->get_option('post_types_cmb_slider', 'cmb_slider_general');
  //    print_r($screen );
      /* echo $pagenow;
       echo $screen->post_type;
     print_r(  $alloved_post_types);*/
       if (is_array($alloved_post_types) && in_array( $screen->post_type, $alloved_post_types ) && 
             ( in_array( $pagenow, array( 'page.php', 'page-new.php', 'post.php', 'post-new.php') ) ) ){
          
          return true;
       }

       return false;
    }

    public function enqueue_admin_styles() {
       
        if (!isset($this->plugin_screen_hook_suffix)) {            
            return;
        }

       
       if ( $this->is_admin_required() ){
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__) , array() , cmbSlider::VERSION);
        }
        
    }
    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @TODO:
     *
     * - Rename "cmbSlider" to the name your plugin
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {
            
             if (!isset($this->plugin_screen_hook_suffix)) {                
                return;
             }

            $screen = get_current_screen();

          if ( $this->is_admin_required() ){
            wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__) , array(
                'jquery'
            ) , cmbSlider::VERSION, true);
          }
    }
    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        $this->plugin_screen_hook_suffix = add_options_page(__('CMB slider', $this->plugin_slug) , __('CMB Slider', $this->plugin_slug) , 'manage_options', $this->plugin_slug, array(
            $this,
            'display_plugin_admin_page'
        ));
    }
    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        $this->settings_api->settings_page();
    }
    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links) {
        
        return array_merge(array(
            'settings' => '<a href="' . admin_url('options-general.php?page=' . $this->plugin_slug) . '">' . __('Settings', $this->plugin_slug) . '</a>'
        ) , $links);
    }
    /**
     * NOTE:     Actions are points in the execution of a page or process
     *           lifecycle that WordPress fires.
     *
     *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
     *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     * @since    1.0.0
     */
    public function init_cmb_slider_field() {
    }
    public function text_link($field, $meta) {
        echo '<input class="cmb_text_link" type="text" size="45" id="', $field['id'], '" name="', $field['id'], '" value="', $meta, '" />';
        echo '<input class="cmb_link_button button" type="button" value="Dodaj povezavo" />', '<p class="cmb_metabox_description">', $field['desc'], '</p>';
    }
    public function slider_meta_boxes() {
        require_once (plugin_dir_path(__FILE__) . 'includes/metabox-api/metaboxes.php');
    }
    /* NOTE:     Filters are points of execution in which WordPress modifies data
     *           before saving it or sending it to the browser.
     *
     *           Filters: http://codex.wordpress.org/Plugin_API#Filters
     *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     * @since    1.0.0
    */
    public function filter_method_name() {
        // @TODO: Define your filter hook callback here
        
    }
}
