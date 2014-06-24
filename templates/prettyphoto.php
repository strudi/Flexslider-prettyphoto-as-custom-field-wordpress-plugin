<?php foreach($this->plugin->get_attachment_ids()  as $attr_id) : 

 	$attachment = wp_get_attachment( $attr_id);
    
	$src_attr = wp_get_attachment_image_src( $attr_id, $this->plugin->get_option('img_size_cmb_pretty_thumb', 'cmb_gallery_settings') );
	
	$src_large = wp_get_attachment_image_src( $attr_id, $this->plugin->get_option('img_size_cmb_pretty_large', 'cmb_gallery_settings'));
    
     if ($src_attr): ?>  

		<a href="<?php echo $src_large[0] ?>" rel="prettyPhoto[medo_gal]" title="<?php echo $attachment->title ?>"><img  class="img-thumbnail" alt="<?php echo $attachment->title ?>" src="<?php echo $src_attr[0] ?>" alt="" /></a>
					
	<?php
   endif;
   endforeach; ?>

 <script>
	jQuery(function($) {
	  $("a[rel^='prettyPhoto']").prettyPhoto({slideshow:5000,deeplinking: false, autoplay_slideshow:false});
	});
 </script>
