<?php
class Flexslider_Template implements iTemplate
{
    private $plugin = null;

    public function __construct($plugin) 
    { 
        $this->plugin = $plugin;
    }
    public function enque_css(){
           wp_enqueue_style( $this->plugin->get_plugin_slug() . '-plugin-flexsliderCss', plugins_url( '../assets/flexslider/flexslider.css', __FILE__ ), array(), 3 );
    
    }
    public function enque_js(){
         wp_enqueue_script( $this->plugin->get_plugin_slug() . '-plugin-flesliderJs', plugins_url( '../assets/flexslider/jquery.flexslider.js', __FILE__ ), array( 'jquery' ),3 );
    
    }
    public function render(){
         $template_loader = new  CMB_Slider_Template_Loader;
         $template =  $template_loader->locate_template('flexslider.php');

        include( $template );
    }
}