<?php

/**
 * Plugin Name: WordPress Settings API
 * Plugin URI: http://tareq.wedevs.com/2012/06/wordpress-settings-api-php-class/
 * Description: WordPress Settings API testing
 * Author: Tareq Hasan
 * Author URI: http://tareq.weDevs.com
 * Version: 0.1
 */
require_once( plugin_dir_path( __FILE__ ) . 'class.settings-api.php' );

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('WeDevs_Settings' ) ):
class WeDevs_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
       
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function get_settings_sections() {
        $sections = array(
             array(
                'id' => 'cmb_slider_general',
                'title' => __( 'General settings', 'cmbslider' )
            ),
            array(
                'id' => 'cmb_slider_settings',
                'title' => __( 'Slider settings', 'cmbslider' )
            ),
            array(
                'id' => 'cmb_gallery_settings',
                'title' => __( 'Gallery Settings', 'cmbslider' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
      $sizes =  get_intermediate_image_sizes();
      $sizes_prepared = array();
      foreach($sizes as $key=>$value){
        $sizes_prepared[$value] = $value;
      }
        $settings_fields = array(
            'cmb_slider_general' => array(            
                array(
                    'name' => 'post_types_cmb_slider',
                    'label' => __( 'Post types', 'cmbslider' ),
                    'desc' => __( 'Where to show the gallery', 'cmbslider' ),
                    'type' => 'multicheck',
                    'default' => 'post',
                    'options' => get_post_types()
                ),
                 
            ),
             'cmb_gallery_settings' => array(            
                
                 array(
                    'name' => 'img_size_cmb_pretty_thumb',
                    'label' => __( 'Thumbnail size size', 'cmbslider' ),
                    'desc' => __( 'Image size for gallery', 'cmbslider' ),
                    'type' => 'select',
                    'default' => 'thumbnail',
                    'options' =>$sizes_prepared
                ),
                 array(
                    'name' => 'img_size_cmb_pretty_large',
                    'label' => __( 'Large size size', 'cmbslider' ),
                    'desc' => __( 'Image size for gallery', 'cmbslider' ),
                    'type' => 'select',
                    'default' => 'large',
                    'options' => $sizes_prepared
                ),
            ),
                     'cmb_slider_settings' => array(            
                
                 
                 array(
                    'name' => 'img_size_cmb_slide_large',
                    'label' => __( 'Slide image size', 'cmbslider' ),
                    'desc' => __( 'Image size for slider', 'cmbslider' ),
                    'type' => 'select',
                    'default' => 'large',
                    'options' => $sizes_prepared
                ),
                 array(
                    'name' => 'img_interval__slide',
                    'label' => __( 'Slide interval', 'cmbslider' ),
                    'desc' => __( 'Seconds between slide transition', 'cmbslider' ),
                    'type' => 'select',
                    'default' => 6,
                    'options' => array_combine(range(1,15),range(1,15))
                ),
            )
        );

        return $settings_fields;
    }

    function settings_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    function get_option( $option, $section, $default = '' ) {
 
        $options = get_option( $section );
     
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
     
        return $default;
    }
   

}
endif;

