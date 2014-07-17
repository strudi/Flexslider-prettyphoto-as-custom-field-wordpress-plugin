<?php
/*--------------------------*
/* Meta Boxes
/*--------------------------*/
function cmb_slider($field, $meta) {
 
    $slider_admin = cmbSlider_Admin::get_instance();
    
    if ( $slider_admin->is_admin_required() ){
    global $post;

        wp_localize_script('cmbslider-admin-script', 'cmbSliderData', array(
            'fieldID' => $field['id'],
            'attachments' => $meta,  
            'save_button' =>  __( 'Add images', 'cmbslider' ),
            'editor_title' =>  __( 'Gallery', 'cmbslider' ),
            'ajaxurl' => admin_url('/admin-ajax.php') ,
            
        ));
        echo '<input class="cmb_gallery_button" id="_button" type="button" value="',  _e( 'Add image', 'cmbslider' ) , '" />', '<p class="cmb_metabox_description"></p>';
        echo '<ul class="attach_list_gallery" id="attach-list', $field['id'], '">';
       
    
        echo '</ul>';
        echo '<div id="cmb-attachment-edit-form" style="display:none;">
              <div  id="cmb-attachment-form"  class="media-sidebar" style="position:static;width:84%;background:transparent;border:none;">
              <label class="setting" data-setting="title">
                    <span>', _e( 'Title', 'cmbslider' ) , '</span>
                    <input id="cmb-att-title" type="text" value="">
                </label>
                <label class="setting" data-setting="caption">
                    <span>',  _e( 'Caption', 'cmbslider' ) , '</span>
                    <textarea  id="cmb-att-caption"></textarea>
                </label>
            
                <label class="setting" data-setting="alt">
                    <span>',  _e( 'Alt Text', 'cmbslider' ) , '</span>
                    <input  id="cmb-att-alt-text" type="text" value="">
                </label>
            
                <label class="setting" data-setting="description">
                    <span>',  _e( 'Description', 'cmbslider' ) , '</span>
                    <textarea  id="cmb-att-description"></textarea>
                </label>
                <div class="media-toolbar-primary">
                   <a id="cmb-save-att-button" href="#" style="color:#FFF;" class="button media-button button-primary button-large media-button-select">',  _e( 'Save', 'cmbslider' ) , '</a>
                    <input  id="cmb-att-id" type="hidden" value="">
                     <input  id="cmb-att-nonce" type="hidden" value="">
                  </div>
                </div>
                </div>';
     }
}
add_action('cmb_render_cmb_slider', 'cmb_slider', 10, 2);
add_filter('cmb_meta_boxes', 'cmb_slider_metaboxes');
function cmb_slider_metaboxes(array $meta_boxes) {
        $slider_admin = cmbSlider_Admin::get_instance();
        $pages = $slider_admin->settings_api->get_option('post_types_cmb_slider', 'cmb_slider_general');
        $type = $slider_admin->settings_api->get_option('type1_cmb_slider', 'cmb_slider_general','1');
      
        if(is_array($pages)){
         
            $meta_boxes[] = array(
                'id' => 'gallery-portfolio-meta',
                'title' => __( 'CMB Slider', 'cmbslider' ) ,
                'pages' =>  $pages ,
                'context' => 'normal',
                'priority' => 'high',
                'show_names' => true,
                'fields' => array(
                    array(
                        'name' => _('Slider items') ,
                        'desc' => '',
                        'id' =>  cmbSlider::META_FIELD_ATTACHMENTS ,
                        'type' => 'cmb_slider',
                    ) ,
                    array(
                        'name' => 'Type',
                        'id' =>  cmbSlider::META_FIELD_TYPE ,
                        'type' => 'radio',
                        'default' => $type,
                        'options' => array(
                            '1' => __('Slider', 'cmbslider') ,
                            '2' => __('Gallery', 'cmbslider') ,
                        ) ,
                    ) ,
                ) ,
            );
        }
        return $meta_boxes;
   
}
add_action('init', 'cmb_slider_initialize_cmb_meta_boxes', 9999);
/**
 * Initialize the metabox class.
 */
function cmb_slider_initialize_cmb_meta_boxes() {
    if (!class_exists('cmb_Meta_Box')) require_once 'init.php';
}
