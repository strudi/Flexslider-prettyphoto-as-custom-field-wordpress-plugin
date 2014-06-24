<?php
class PrettyPhoto_Template implements iTemplate
{
    private $plugin = null;

    public function __construct($plugin) 
    { 
        $this->plugin = $plugin;
    }
    public function enque_css(){
            wp_enqueue_style( $this->plugin->get_plugin_slug() . '-plugin-jqueryprettyPhotoCss', plugins_url( '../assets/prettyphoto/css/prettyPhoto.css', __FILE__ ), array(), 3 );
    
    }
    public function enque_js(){
         wp_enqueue_script( $this->plugin->get_plugin_slug() . '-plugin-jqueryprettyPhotoJs', plugins_url( '../assets/prettyphoto/js/jquery.prettyPhoto.js', __FILE__ ), array( 'jquery' ),3 );
    
    }
    public function render(){
         $template_loader = new  CMB_Slider_Template_Loader;
         $template =  $template_loader->locate_template('prettyphoto.php');

        include( $template );
    }
}