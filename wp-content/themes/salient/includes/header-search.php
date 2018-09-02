<?php $options = get_nectar_theme_options(); 

if(!empty($options['header-disable-ajax-search']) && $options['header-disable-ajax-search'] == '1') {
	$ajax_search = 'no';	
} else {
	$ajax_search = 'yes';
} ?>

<div id="search-outer" class="nectar">
		
	<div id="search">
	  	 
		<div class="container">
		  	 	
		     <div id="search-box">
		     	
		     	<div class="inner-wrap">

			     	<div class="col span_12">
				      	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="GET">
				      		<?php 

				      		$theme_skin = ( !empty($options['theme-skin']) ) ? $options['theme-skin'] : 'original'; 

				      		if($theme_skin == 'material') { ?>
				      			<input type="text" name="s" <?php if($ajax_search == 'yes') { echo 'id="s"'; } ?> value="" placeholder="<?php echo __('Search', NECTAR_THEME_NAME); ?>" />
				      		<?php } else { ?> 
				      			<input type="text" name="s" <?php if($ajax_search == 'yes') { echo 'id="s"'; } ?> value="<?php echo __('Start Typing...', NECTAR_THEME_NAME); ?>" data-placeholder="<?php echo __('Start Typing...', NECTAR_THEME_NAME); ?>" />
				      		<?php } ?>
				      	</form>
								
				      	<?php if($theme_skin == 'ascend' && $ajax_search == 'no') { echo '<span><i>'.__("Press enter to begin your search",NECTAR_THEME_NAME).'</i></span>'; }
				      		    if($theme_skin == 'material') { echo '<span>' . __('Hit enter to search or ESC to close',NECTAR_THEME_NAME) . '</span>'; }
				      	?>
			        </div><!--/span_12-->

			    </div><!--/inner-wrap-->
			      
		     </div><!--/search-box-->
		     
		     <div id="close"><a href="#">
		     	<?php if($theme_skin == 'material') {
		     		echo '<span class="close-wrap"> <span class="close-line close-line1"></span> <span class="close-line close-line2"></span> </span>';
		     	} else {
		     		echo '<span class="icon-salient-x" aria-hidden="true"></span>';
		     	} ?>
		     	</a></div>
		     
		 </div><!--/container-->
	    
	</div><!--/search-->
	  
</div><!--/search-outer-->