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
            'upload_file' => 'Use this file',
            'remove_image' => 'Remove Image',
            'remove_file' => 'Remove',
            'file' => 'File:',
            'download' => 'Download',
            'ajaxurl' => admin_url('/admin-ajax.php') ,
            'up_arrow' => '[ ↑ ]&nbsp;',
            'down_arrow' => '&nbsp;[ ↓ ]',
        ));
        echo '<input class="cmb_gallery_button" id="_button" type="button" value="Add gallery" />', '<p class="cmb_metabox_description"></p>';
        echo '<ul class="attach_list_gallery" id="attach-list', $field['id'], '">';
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
                   <a id="cmb-save-att-button" href="#" style="color:#FFF;" class="button media-button button-primary button-large media-button-select">',  _e( 'Shrani', 'cmbslider' ) , '</a>
                    <input  id="cmb-att-id" type="hidden" value="">
                     <input  id="cmb-att-nonce" type="hidden" value="">
                  </div>
                </div>
                </div>';
      /*  if (!empty($meta)) {
            $idx = 0;
            
            foreach ($meta as $attr_id) {
                if (intval($attr_id) != 0) {
                    $att_attr = wp_get_attachment_image_src($attr_id, 'thumbnail');
                    echo '<li data-attid="' . $attr_id . '"  class="ui-state-default medo-gallery-image" >';
                    echo '<a class="cmb_remove_cmb_gallery" href="#">' . _("Remove") . '</a>';
                    echo '<img src="' . $att_attr[0] . '"/>';
                    echo '<input class="cmb_gallery" type="hidden"  id="' . $field['id'] . '_' . $attr_id . '" name="' . $field['id'] . '[]" value="', $attr_id, '" />';
                    echo '<div id="cmb-slider-thick-content-' . $field['id'] . '" style="display:none;">';
                    echo ' </div>';
                    echo '<a data-thick-content-slider="cmb-slider-thick-content-' . $field['id'] . '" href="#" class="cmbSliderThickboxLink">' . _("Caption") . '</a></li>';
                    $idx++;
                }
            }
        }*/
        echo '</ul>';
     }
}
add_action('cmb_render_cmb_slider', 'cmb_slider', 10, 2);
add_filter('cmb_meta_boxes', 'cmb_slider_metaboxes');
function cmb_slider_metaboxes(array $meta_boxes) {
        $slider_admin = cmbSlider_Admin::get_instance();
        $pages = $slider_admin->settings_api->get_option('post_types_cmb_slider', 'cmb_slider_general');

        if(is_array($pages)){
            $prefix = 'cmbslider_';
            $meta_boxes[] = array(
                'id' => 'gallery-portfolio-meta',
                'title' => 'Gallery',
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