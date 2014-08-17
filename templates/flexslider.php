<div class="flex-placeholder" data-flexoptions='{"directionNav":false, "animation":"<?php echo
 $this->plugin->get_option('effect_cmb_slider', 'cmb_slider_settings')
  ?>","animationLoop": true,"slideshowSpeed":<?php echo $this->plugin->get_option('img_interval__slide', 'cmb_slider_settings') ?>}'>
	<ul class="slides">
									
		<?php foreach($this->plugin->get_attachment_ids()  as $attr_id) :  			
			
		   $attachment = wp_get_attachment( $attr_id );
		    if ($src_attr): ?>			  
				<li>
					<?php echo	wp_get_attachment_image( $attr_id ,$this->plugin->get_option('img_size_cmb_slide_large', 'cmb_slider_settings')); ?>
					<?php if( $this->plugin->get_option('caption_cmb_slider', 'cmb_slider_settings') == 'yes'  ): ?>
						<div class="flex-caption">
							<h3><?php echo  $attachment->title ?></h3>
							<p>	<?php echo  $attachment->caption ?></p>
					</div>
			    	<?php endif; ?>
				</li>
		
		<?php 
			endif;
		    endforeach;
	    ?>

	</ul>

</div>