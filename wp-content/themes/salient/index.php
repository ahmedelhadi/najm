<?php get_header(); ?>

<?php nectar_page_header(get_option('page_for_posts')); ?>

<div class="container-wrap">
		
	<div class="container main-content">
		
		<div class="row">
			
			<?php $options = get_nectar_theme_options(); 
			
			$blog_type = $options['blog_type'];
			if($blog_type == null) $blog_type = 'std-blog-sidebar';
			
			$masonry_class = null;
			$masonry_style = null;
			$infinite_scroll_class = null;
			$load_in_animation = (!empty($options['blog_loading_animation'])) ? $options['blog_loading_animation'] : 'none';
			$blog_standard_type = (!empty($options['blog_standard_type'])) ? $options['blog_standard_type'] : 'classic';
			$enable_ss = (!empty($options['blog_enable_ss'])) ? $options['blog_enable_ss'] : 'false';
			$auto_masonry_spacing = (!empty($options['blog_auto_masonry_spacing'])) ? $options['blog_auto_masonry_spacing'] : '4px';

			//enqueue masonry script if selected
			if($blog_type == 'masonry-blog-sidebar' || $blog_type == 'masonry-blog-fullwidth' || $blog_type == 'masonry-blog-full-screen-width') {
				$masonry_class = 'masonry';
			}
			
			if($blog_type == 'masonry-blog-full-screen-width') {
				$masonry_class = 'masonry full-width-content';
			}
			
			if(!empty($options['blog_pagination_type']) && $options['blog_pagination_type'] == 'infinite_scroll'){
				$infinite_scroll_class = ' infinite_scroll';
			}
			

			if($masonry_class != null) {
				$masonry_style = (!empty($options['blog_masonry_type'])) ? $options['blog_masonry_type']: 'classic';
			}

			if($blog_standard_type == 'minimal' && $blog_type == 'std-blog-fullwidth')
				$std_minimal_class = 'standard-minimal full-width-content';
			else if($blog_standard_type == 'minimal' && $blog_type == 'std-blog-sidebar')
				$std_minimal_class = 'standard-minimal';
			else
				$std_minimal_class = '';
				
			if($masonry_style == null && $blog_standard_type == 'featured_img_left')
				$std_minimal_class = 'featured_img_left';
			
			if($blog_type == 'std-blog-sidebar' || $blog_type == 'masonry-blog-sidebar'){
				echo '<div class="post-area col '.$std_minimal_class.' span_9 '.$masonry_class.' '.$masonry_style.' '. $infinite_scroll_class.'" data-ams="'.$auto_masonry_spacing.'"> <div class="posts-container"  data-load-animation="'.$load_in_animation.'">';
			} else {
				echo '<div class="post-area col '.$std_minimal_class.' span_12 col_last '.$masonry_class.' '.$masonry_style.' '. $infinite_scroll_class.'" data-ams="'.$auto_masonry_spacing.'"> <div class="posts-container"  data-load-animation="'.$load_in_animation.'">';
			}
	
				if(have_posts()) : while(have_posts()) : the_post(); ?>
					
					<?php 
		
					if ( floatval(get_bloginfo('version')) < "3.6" ) {
						//old post formats before they got built into the core
						 get_template_part( 'includes/post-templates-pre-3-6/entry', get_post_format() ); 
					} else {
						//WP 3.6+ post formats
						 $nectar_post_format = (get_post_format() == 'image' || get_post_format() == 'aside') ? false : get_post_format();
						 get_template_part( 'includes/post-templates/entry', $nectar_post_format ); 
					} ?>
	
				<?php endwhile; endif; ?>
				
				</div><!--/posts container-->
				
			<?php nectar_pagination(); ?>
				
			</div><!--/span_9-->
			
			<?php  if($blog_type == 'std-blog-sidebar' || $blog_type == 'masonry-blog-sidebar') { ?>
				<div id="sidebar" data-nectar-ss="<?php echo $enable_ss; ?>" class="col span_3 col_last">
					<?php get_sidebar(); ?>
				</div><!--/span_3-->
			<?php } ?>
			
		</div><!--/row-->
		
	</div><!--/container-->

</div><!--/container-wrap-->
	
<?php get_footer(); ?>