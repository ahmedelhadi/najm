<?php 
global $options; 
global $post;

$masonry_size_pm = get_post_meta($post->ID, '_post_item_masonry_sizing', true); 
$masonry_item_sizing = (!empty($masonry_size_pm)) ? $masonry_size_pm : 'regular'; 
$using_masonry = null;

if(isset($GLOBALS['nectar_blog_std_style']) && $GLOBALS['nectar_blog_std_style'] != 'inherit') {
	$blog_standard_type = $GLOBALS['nectar_blog_std_style'];
} else {
	$blog_standard_type = (!empty($options['blog_standard_type'])) ? $options['blog_standard_type'] : 'classic';
}

$blog_type = $options['blog_type']; 

if(isset($GLOBALS['nectar_blog_masonry_style']) && $GLOBALS['nectar_blog_masonry_style'] != 'inherit') {
	$masonry_type = $GLOBALS['nectar_blog_masonry_style'];
} else {
	$masonry_type = (!empty($options['blog_masonry_type'])) ? $options['blog_masonry_type'] : 'classic';
}

global $layout;

if($blog_type == 'masonry-blog-sidebar' && substr( $layout, 0, 3 ) != 'std' || 
$blog_type == 'masonry-blog-fullwidth' && substr( $layout, 0, 3 ) != 'std' || 
$blog_type == 'masonry-blog-full-screen-width' && substr( $layout, 0, 3 ) != 'std' || 
$layout == 'masonry-blog-sidebar' || $layout == 'masonry-blog-fullwidth' || $layout == 'masonry-blog-full-screen-width') {
	$using_masonry = true;
} 
$use_excerpt = (!empty($options['blog_auto_excerpt']) && $options['blog_auto_excerpt'] == '1') ? 'true' : 'false'; 

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($masonry_item_sizing); ?>>
	
	<div class="inner-wrap animated">

		<div class="post-content">
			
			<?php if( !is_single() ) { ?>

					<?php 


					$extra_class = '';
					if (!has_post_thumbnail()) $extra_class = 'no-img'; ?>

					<?php if( !($using_masonry != true && $blog_standard_type == 'minimal' || $using_masonry != true && $blog_standard_type == 'featured_img_left') &&
				!($using_masonry == true && $masonry_type == 'material') && !($using_masonry == true && $masonry_type == 'auto_meta_overlaid_spaced') ) { ?>
						
					<div class="post-meta <?php echo $extra_class; ?>">
						
						
						<div class="date">
							<?php 
							if($using_masonry == true) {
								if($masonry_type != 'classic_enhanced' && $masonry_type != 'material') echo get_the_date();
							}
							else { ?>
	
									<span class="month"><?php the_time('M'); ?></span>
									<span class="day"><?php the_time('d'); ?></span>
									<?php global $options; 
									if(!empty($options['display_full_date']) && $options['display_full_date'] == 1) {
										echo '<span class="year">'. get_the_time('Y') .'</span>';
									}
								
							} ?>
							
						</div><!--/date-->
						
						<?php if(($masonry_type == 'classic_enhanced' && $using_masonry == true)) { ?> 
							<span class="meta-author"> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <i class="icon-default-style icon-salient-m-user"></i> <?php the_author(); ?></a> </span> 
							<?php if(comments_open()) { ?>
								<span class="meta-comment-count">  <a href="<?php comments_link(); ?>">
									<i class="icon-default-style steadysets-icon-chat-3"></i> <?php comments_number( '0', '1','%' ); ?></a>
								</span>
							<?php } ?>
	
						<?php } ?>
	
						<?php if($using_masonry == true && $masonry_type == 'meta_overlaid' || $using_masonry == true && $masonry_type == 'auto_meta_overlaid_spaced' || $using_masonry == true && $masonry_type == 'material' ) { } else { 
	
							if(!($using_masonry != true && $blog_standard_type == 'minimal') && !($using_masonry != true && $blog_standard_type == 'featured_img_left') ) { ?> 
							<div class="nectar-love-wrap">
								<?php if( function_exists('nectar_love') ) nectar_love(); ?>
							</div><!--/nectar-love-wrap-->	
						<?php } } ?>
						
						 </div><!--/post-meta-->
						 
					<?php } //conditional for entire post meta div ?>
			
			<?php } 

			$meta_overlaid_style = ($using_masonry == true && $masonry_type == 'meta_overlaid' || $using_masonry == true && $masonry_type == 'auto_meta_overlaid_spaced') ? true : false; ?>

			<?php 
				$img_size = ($blog_type == 'masonry-blog-sidebar' && substr( $layout, 0, 3 ) != 'std' || $blog_type == 'masonry-blog-fullwidth' && substr( $layout, 0, 3 ) != 'std' || $blog_type == 'masonry-blog-full-screen-width' && substr( $layout, 0, 3 ) != 'std' || $layout == 'masonry-blog-sidebar' || $layout == 'masonry-blog-fullwidth' || $layout == 'masonry-blog-full-screen-width') ? 'large' : 'full';
			 	if($using_masonry == true && $masonry_type == 'meta_overlaid') $img_size = (!empty($masonry_item_sizing)) ? $masonry_item_sizing : 'portfolio-thumb';
			 	if($using_masonry == true && $masonry_type == 'classic_enhanced') $img_size = (!empty($masonry_item_sizing) && $masonry_item_sizing == 'regular') ? 'portfolio-thumb' : 'full';
				
				if($using_masonry == true && $masonry_type == 'classic_enhanced' || $using_masonry == true && $masonry_type == 'material' && !is_single() ) echo'<a href="' . get_permalink() . '" class="img-link"><span class="post-featured-img">'.get_the_post_thumbnail($post->ID, $img_size, array('title' => '')) .'</span></a>'; 
			?>
			
			<?php 
			//featured image left
			if($using_masonry != true && $blog_standard_type == 'featured_img_left') {
				
				if ( has_post_thumbnail() ) {

					 global $options;
					 $hide_featrued_image = (!empty($options['blog_hide_featured_image'])) ? $options['blog_hide_featured_image'] : '0'; 
					 if(is_single() && $hide_featrued_image != '1'){
						echo '<span class="post-featured-img">'.get_the_post_thumbnail($post->ID, 'full', array('title' => '')) .'</span>';
					 }	

				 }
				 
				 if( !is_single() ) { ?> 

					 <div class="article-content-wrap">
						
						<div class="post-featured-img-wrap"> 
							
							<?php if ( has_post_thumbnail() ) {
									echo'<a href="' . get_permalink() . '"><span class="post-featured-img" style="background-image: url('.get_the_post_thumbnail_url($post->ID, 'wide_photography', array('title' => '')).');"></span></a>'; 
							} ?>
							
						</div>
						
						<div class="post-content-wrap"> 
							
							<a class="entire-meta-link" href="<?php the_permalink(); ?>"></a>
							
							<?php 
							echo '<span class="meta-category">';
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								$output = null;
							    foreach( $categories as $category ) {
							        $output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
							    }
							    echo trim( $output);
							}
						echo '</span>';  ?>
						
							<div class="post-header">

								<h3 class="title">
									<?php if( !is_single() && !($using_masonry == true && $masonry_type == 'classic_enhanced') ) { ?> 
										<a href="<?php the_permalink(); ?>"><?php } ?>
											<?php the_title(); ?>
										<?php if( !is_single() && !($using_masonry == true && $masonry_type == 'classic_enhanced') ) {?> </a> 
									<?php } ?>
								</h3>

								
							</div><!--/post-header-->
							
							<?php 
							//if no excerpt is set
							global $post;

							echo '<div class="excerpt">';
							$excerpt_length = (!empty($options['blog_excerpt_length'])) ? intval($options['blog_excerpt_length']) : 15; 
							echo nectar_excerpt($excerpt_length);
							echo '</div>';
						
							if (function_exists('get_avatar')) { 
									 echo '<div class="grav-wrap"><a href="'.get_author_posts_url($post->post_author).'">'.get_avatar( get_the_author_meta('email'), 70,  null, get_the_author() ). '</a><div class="text"><a href="'.get_author_posts_url($post->post_author).'" rel="author">' .get_the_author().'</a><span>'. get_the_date() .'</span></div></div>'; } 
							 

						?>

					</div><!--post-content-wrap-->

					</div><!--article-content-wrap-->

				<?php } //not single 
				
				
				
				if(is_single()){ ?>
					
					<div class="content-inner">
					<div class="audio-wrap">		
					 <?php 
					 
						 $audio_mp3 = get_post_meta($post->ID, '_nectar_audio_mp3', true);
							 $audio_ogg = get_post_meta($post->ID, '_nectar_audio_ogg', true); 
						 
						 if(!empty($audio_ogg) || !empty($audio_mp3)) {
									 
							 $audio_output = '[audio ';
							 
							 if(!empty($audio_mp3)) { $audio_output .= 'mp3="'. $audio_mp3 .'" '; }
							 if(!empty($audio_ogg)) { $audio_output .= 'ogg="'. $audio_ogg .'"'; }
							 
							 $audio_output .= ']';
							 
									 echo  do_shortcode($audio_output);	
								 }

						?>
				 </div><!--/audio-wrap-->
				 
				 <?php 
				
					the_content('<span class="continue-reading">'. __("Read More", NECTAR_THEME_NAME) . '</span>'); 
				} ?>
				
				<?php global $options;
					if( $options['display_tags'] == true ){
						 
						if( is_single() && has_tag() ) {
						
							echo '<div class="post-tags"><h4>'.__('Tags:').'</h4>'; 
							the_tags('','','');
							echo '<div class="clear"></div></div> ';
							
						}
					}
					
					if( is_single()){ echo '</div><!--content inner-->'; }
				
				
			} //featured img left 
			
		
			//minimal std
			else if($using_masonry != true && $blog_standard_type == 'minimal') { ?>

				<?php if( !is_single() ) { ?>
					 
					<div class="post-author">
						<?php if (function_exists('get_avatar')) { echo '<div class="grav-wrap"><a href="'.get_author_posts_url($post->post_author).'">'.get_avatar( get_the_author_meta('email'), 90,  null, get_the_author() ).'</a></div>'; } ?>
						<span class="meta-author"> <?php the_author_posts_link(); ?></span>
						
					    <?php
					  echo '<span class="meta-category">';
					  
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {

							echo '<span class="in">'. __('In', NECTAR_THEME_NAME) . ' </span>';

							$output = null;
							$cat_count = 0;
						    foreach( $categories as $category ) {
						        $output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '" >' . esc_html( $category->name ) . '</a>';
						    	if(count($categories) > 1 && ($cat_count + 1) < count($categories)) $output .= ', ';
						    	$cat_count++;
						    }
						    echo trim( $output);
						}
				      echo '</span>'; ?>
					</div>
				<?php } ?>

				<div class="content-inner">

					<?php if ( has_post_thumbnail() ) {

						 global $options;
						 $hide_featrued_image = (!empty($options['blog_hide_featured_image'])) ? $options['blog_hide_featured_image'] : '0'; 
						 if(is_single() && $hide_featrued_image != '1'){
						 	echo '<span class="post-featured-img">'.get_the_post_thumbnail($post->ID, 'full', array('title' => '')) .'</span>';
						 }	

						} ?>
						 
						 <?php if( !is_single() ) { ?> 

							 <div class="article-content-wrap">

								<div class="post-header">
									<?php $h_num = '2'; ?>
		
									<h<?php echo $h_num; ?> class="title">
										<?php if( !is_single() && !($using_masonry == true && $masonry_type == 'classic_enhanced') ) { ?> 
											<a href="<?php the_permalink(); ?>"><?php } ?>
												<?php the_title(); ?>
											<?php if( !is_single() && !($using_masonry == true && $masonry_type == 'classic_enhanced') ) {?> </a> 
										<?php } ?>
									</h<?php echo $h_num; ?>>

									
								</div><!--/post-header-->


								<?php 
			 					
			 					} //not single ?>
					 				

					 				 		
			 				 	<div class="audio-wrap">		
									<?php 
									
										$audio_mp3 = get_post_meta($post->ID, '_nectar_audio_mp3', true);
									    $audio_ogg = get_post_meta($post->ID, '_nectar_audio_ogg', true); 
										
										if(!empty($audio_ogg) || !empty($audio_mp3)) {
								        	
											$audio_output = '[audio ';
											
											if(!empty($audio_mp3)) { $audio_output .= 'mp3="'. $audio_mp3 .'" '; }
											if(!empty($audio_ogg)) { $audio_output .= 'ogg="'. $audio_ogg .'"'; }
											
											$audio_output .= ']';
											
							        		echo  do_shortcode($audio_output);	
							        	}

									 ?>
								</div><!--/audio-wrap-->

					 				 

					 		<?php if( !is_single() ) {

								//if no excerpt is set
								global $post;

								if(empty( $post->post_excerpt ) && $use_excerpt != 'true') {
									the_content('<span class="continue-reading">'. __("Continue Reading", NECTAR_THEME_NAME) . '</span>'); 
								}
								
								//excerpt
								else {
									echo '<div class="excerpt">';
									$excerpt_length = (!empty($options['blog_excerpt_length'])) ? intval($options['blog_excerpt_length']) : 15; 

									the_excerpt();

									echo '</div>';
									echo '<a class="more-link" href="' . get_permalink() . '"><span class="continue-reading">'. __("Continue Reading", NECTAR_THEME_NAME) .'</span></a>';
								}

								?>

								 

							</div><!--article-content-wrap-->

						<?php } //not single ?> 

						<?php 
						if(is_single()){
							//on the single post page display the content
							the_content('<span class="continue-reading">'. __("Read More", NECTAR_THEME_NAME) . '</span>'); 
						} ?>
						
						<?php global $options;
							if( $options['display_tags'] == true ){
								 
								if( is_single() && has_tag() ) {
								
									echo '<div class="post-tags"><h4>'.__('Tags:').'</h4>'; 
									the_tags('','','');
									echo '<div class="clear"></div></div> ';
									
								}
							}
						?>

				</div><!--/content-inner-->


			<?php }

			//other styles
			else { ?>
			
			<div class="content-inner">
				
				<?php if( !is_single() && ($using_masonry == true && $masonry_type == 'classic_enhanced') && ($using_masonry == true && $masonry_type == 'material') ) { ?> <a class="entire-meta-link" href="<?php the_permalink(); ?>"></a><?php } 


				if($meta_overlaid_style == true) {
					 if ( has_post_thumbnail() ) {
						 $img_size = ($blog_type == 'masonry-blog-sidebar' && substr( $layout, 0, 3 ) != 'std' || $blog_type == 'masonry-blog-fullwidth' && substr( $layout, 0, 3 ) != 'std' || $blog_type == 'masonry-blog-full-screen-width' && substr( $layout, 0, 3 ) != 'std' || $layout == 'masonry-blog-sidebar' || $layout == 'masonry-blog-fullwidth' || $layout == 'masonry-blog-full-screen-width') ? 'large' : 'full';
						 $img_size  = (!empty($masonry_item_sizing)) ? $masonry_item_sizing : 'portfolio-thumb';
						 if( !is_single() ) {  
								 if($using_masonry == true && $masonry_type == 'auto_meta_overlaid_spaced') {
									echo '<a href="' . get_permalink() . '"></a><span class="post-featured-img" style="background-image: url('.get_the_post_thumbnail_url($post->ID, 'medium_featured', array('title' => '')).');"></span>';
								} else {
								 echo'<a href="' . get_permalink() . '"><span class="post-featured-img">'.get_the_post_thumbnail($post->ID, $img_size, array('title' => '')) .'</span></a>';
							  }
						 }
					} else {

						//no image added
						$img_size = (!empty($masonry_item_sizing)) ? $masonry_item_sizing : 'portfolio-thumb';
						switch($img_size) {
							case 'large_featured':
								$no_image_size = 'no-blog-item-large-featured.jpg';
								break;
							case 'wide_tall':
								$no_image_size = 'no-portfolio-item-tiny.jpg';
								break;
							default:
								$no_image_size = 'no-portfolio-item-tiny.jpg';
								break;
						}
						 echo '<a href="' . get_permalink() . '"><span class="post-featured-img"><img src="'.get_template_directory_uri().'/img/'.$no_image_size.'" alt="no image added yet." /></span></a>';
				
					}
				
				} else if(!($using_masonry == true && $masonry_type == 'classic_enhanced') && !($using_masonry == true && $masonry_type == 'material') && !is_single() ) { ?>
					<div class="audio-wrap">		
						<?php 
						
							$audio_mp3 = get_post_meta($post->ID, '_nectar_audio_mp3', true);
						    $audio_ogg = get_post_meta($post->ID, '_nectar_audio_ogg', true); 
							
							if(!empty($audio_ogg) || !empty($audio_mp3)) {
					        	
								$audio_output = '[audio ';
								
								if(!empty($audio_mp3)) { $audio_output .= 'mp3="'. $audio_mp3 .'" '; }
								if(!empty($audio_ogg)) { $audio_output .= 'ogg="'. $audio_ogg .'"'; }
								
								$audio_output .= ']';
								
				        		echo  do_shortcode($audio_output);	
				        	}

						 ?>
					</div><!--/audio-wrap-->
				<?php } ?>

				<?php if( !is_single() ) { ?>

					<?php if($using_masonry == true && $masonry_type == 'classic_enhanced' || $using_masonry == true && $masonry_type == 'material') {
						echo '<span class="meta-category">';
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {
							$output = null;
						    foreach( $categories as $category ) {
						        $output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '" >' . esc_html( $category->name ) . '</a>';
						    }
						    echo trim( $output);
						}
					echo '</span>'; } ?>

					<div class="article-content-wrap">
						
						<?php if($using_masonry == true && $masonry_type == 'auto_meta_overlaid_spaced'  ) {
							echo '<span class="meta-category">';
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								$output = null;
									foreach( $categories as $category ) {
											$output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
									}
									echo trim( $output);
							}
						echo '</span>'; } ?>
						
						<div class="post-header">
							<?php 
							$h_num = '2';
							if($using_masonry == true && $masonry_type == 'classic_enhanced') {
								echo get_the_date(); 
								$h_num = '3';
							} else if($using_masonry == true && $masonry_type == 'material' || $using_masonry == true && $masonry_type == 'auto_meta_overlaid_spaced') {
								$h_num = '3';
							}  ?>	
							<h<?php echo $h_num; ?> class="title">
								<?php if( !is_single() && !($using_masonry == true && $masonry_type == 'classic_enhanced') ) { ?> 
									<a href="<?php the_permalink(); ?>"><?php } ?>
										<?php the_title(); ?>
									<?php if( !is_single() && !($using_masonry == true && $masonry_type == 'classic_enhanced') ) {?> </a> 
								<?php } ?>
							</h<?php echo $h_num; ?>>

							<?php if(!($masonry_type == 'classic_enhanced' && $using_masonry == true) && !($masonry_type == 'material' && $using_masonry == true)) { ?> 
								<span class="meta-author"><span><?php echo __('By', NECTAR_THEME_NAME); ?></span> <?php the_author_posts_link(); ?></span> <span class="meta-category">| <?php the_category(', '); ?></span> <span class="meta-comment-count">| <a href="<?php comments_link(); ?>">
								<?php comments_number( __('No Comments', NECTAR_THEME_NAME), __('One Comment ', NECTAR_THEME_NAME), __('% Comments', NECTAR_THEME_NAME) ); ?></a></span>
							<?php } ?>
						</div><!--/post-header-->
					
					<?php
					
						//if no excerpt is set
						if($meta_overlaid_style == false) {
							//if no excerpt is set
							global $post;
							if(empty( $post->post_excerpt ) && $use_excerpt != 'true' && !($using_masonry == true && $masonry_type == 'classic_enhanced') && !($using_masonry == true && $masonry_type == 'material') ) {
								the_content('<span class="continue-reading">'. __("Read More", NECTAR_THEME_NAME) . '</span>'); 
							}
							
							//excerpt
							else {
								echo '<div class="excerpt">';
								$excerpt_length = (!empty($options['blog_excerpt_length'])) ? intval($options['blog_excerpt_length']) : 15; 

								if($using_masonry == true && $masonry_type == 'classic_enhanced') {

									if($masonry_item_sizing != 'large_featured' && !empty( $post->post_excerpt )) {

										echo the_excerpt();

									} else if($masonry_item_sizing == 'large_featured') {

										echo nectar_excerpt($excerpt_length*2);
									} else {
										echo nectar_excerpt($excerpt_length);
									}

								} else if($using_masonry == true && $masonry_type == 'material') {
									echo nectar_excerpt($excerpt_length);
								} else {
									the_excerpt();
								}
								
									
								echo '</div>';
								
								if (function_exists('get_avatar') && $using_masonry == true && $masonry_type == 'material') { 
										  echo '<div class="grav-wrap"><a href="'.get_author_posts_url($post->post_author).'">'.get_avatar( get_the_author_meta('email'), 70,  null, get_the_author() ). '</a><div class="text"><a href="'.get_author_posts_url($post->post_author).'" rel="author">' .get_the_author().'</a><span>'. get_the_date() .'</span></div></div>'; } 
								 
								if(!($using_masonry == true && $masonry_type == 'material')) {  
									echo '<a class="more-link" href="' . get_permalink() . '"><span class="continue-reading">'. __("Read More", NECTAR_THEME_NAME) .'</span></a>';
								}
								
							}
						}
						?>
					</div><!--article-content-wrap-->
				<?php } ?>
				
			   
				<?php 
				if(is_single()){ ?>
					
					<div class="audio-wrap">		
						<?php 
						
							$audio_mp3 = get_post_meta($post->ID, '_nectar_audio_mp3', true);
						    $audio_ogg = get_post_meta($post->ID, '_nectar_audio_ogg', true); 
							
							if(!empty($audio_ogg) || !empty($audio_mp3)) {
					        	
								$audio_output = '[audio ';
								
								if(!empty($audio_mp3)) { $audio_output .= 'mp3="'. $audio_mp3 .'" '; }
								if(!empty($audio_ogg)) { $audio_output .= 'ogg="'. $audio_ogg .'"'; }
								
								$audio_output .= ']';
								
				        		echo  do_shortcode($audio_output);	
				        	}

						 ?>
					</div><!--/audio-wrap-->
					
					<?php 
					//on the single post page display the content
					the_content('<span class="continue-reading">'. __("Read More", NECTAR_THEME_NAME) . '</span>'); 
				} ?>
				
				<?php global $options;
					if( $options['display_tags'] == true ){
						 
						if( is_single() && has_tag() ) {
						
							echo '<div class="post-tags"><h4>'.__('Tags:').'</h4>'; 
							the_tags('','','');
							echo '<div class="clear"></div></div> ';
							
						}
					}
				?>
			
			</div><!--/content-inner-->
			
			<?php } // other styles ?>

		</div><!--/post-content-->

	</div><!--/inner-wrap-->
		
</article><!--/article-->