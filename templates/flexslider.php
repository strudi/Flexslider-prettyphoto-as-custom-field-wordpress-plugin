<div class="flex-placeholder" data-flexoptions='{"directionNav":false, "animation":"slide","animationLoop": true,"slideshowSpeed":9000}'>
	<ul class="slides">
									
		<?php foreach($this->plugin->get_attachment_ids()  as $attr_id) :  
			
			$src_attr = wp_get_attachment_image_src( $attr_id, $this->plugin->get_option('img_size_cmb_slide_large', 'cmb_slider_settings'));
		   
		    if ($src_attr): ?>			  
				<li>
					<img src="<?php echo $src_attr[0] ?>" alt="" />
				</li>
		
		<?php 
			endif;
		    endforeach;
	    ?>

	</ul>

</div>