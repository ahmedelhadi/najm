<?php 

$is_admin = is_admin();

$blog_types = ($is_admin) ? get_categories() : array('All' => 'all');

		$blog_options = array("All" => "all");

		if($is_admin) {
			foreach ($blog_types as $type) {
				if(isset($type->name) && isset($type->slug))
					$blog_options[htmlspecialchars($type->name)] = htmlspecialchars($type->slug);
			}
		} else {
			$blog_options['All'] = 'all';
		}

		return array(
		  "name" => __("Blog", "js_composer"),
		  "base" => "nectar_blog",
		  "weight" => 8,
		  "icon" => "icon-wpb-blog",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Display a Blog element', 'js_composer'),
		  "params" => array(
		    array(
			  "type" => "dropdown",
			  "heading" => __("Layout", "js_composer"),
			  "param_name" => "layout",
			  "admin_label" => true,
			  "value" => array(
				    'Standard Blog W/ Sidebar' => 'std-blog-sidebar',
				    'Standard Blog No Sidebar' => 'std-blog-fullwidth',
				    'Masonry Blog W/ Sidebar' => 'masonry-blog-sidebar',
				    'Masonry Blog No Sidebar' => 'masonry-blog-fullwidth',
				    'Masonry Blog Fullwidth' => 'masonry-blog-full-screen-width'
				),
			  'save_always' => true,
			  "description" => __("Please select the layout you desire for your blog", "js_composer")
			),

			array(
			  "type" => "dropdown",
			  "heading" => __("Masonry Layout Style", "js_composer"),
			  "param_name" => "blog_masonry_style",
			  "admin_label" => false,
			  "value" => array(
			  		 __('Inherit from Theme Options', NECTAR_THEME_NAME) => 'inherit',
						 __('Material Style', NECTAR_THEME_NAME) => 'material',
				     __('Classic Style', NECTAR_THEME_NAME) => 'classic',
             __('Classic Enhanced Style', NECTAR_THEME_NAME) => 'classic_enhanced',
             __('Meta Overlaid Style', NECTAR_THEME_NAME) => 'meta_overlaid',
						 __('Auto Masonry: Meta Overlaid Spaced', NECTAR_THEME_NAME) => 'auto_meta_overlaid_spaced'
				),
			  'save_always' => true,
			  "dependency" => Array('element' => "layout", 'value' => array('masonry-blog-sidebar','masonry-blog-fullwidth','masonry-blog-full-screen-width')),
			  "description" => __("Please select the style you would like your posts to use when the masonry layout is displayed", "js_composer")
			),
			
			array(
			  "type" => "dropdown",
			  "heading" => __("Auto Masonry Spacing", "js_composer"),
			  "param_name" => "auto_masonry_spacing",
			  "admin_label" => false,
			  "value" => array(
			  	   __('4px', NECTAR_THEME_NAME) => '4px',
			  		 __('8px', NECTAR_THEME_NAME) => '8px',
             __('12px', NECTAR_THEME_NAME) => '12px',
						 __('16px', NECTAR_THEME_NAME) => '16px',
						 __('20px', NECTAR_THEME_NAME) => '20px',
				),
			  'save_always' => true,
			  "dependency" => Array('element' => "blog_masonry_style", 'value' => array('auto_meta_overlaid_spaced')),
			  "description" => __("Please select the amount of spacing you would like for your auto masonry layout", "js_composer")
			),

			array(
			  "type" => "dropdown",
			  "heading" => __("Standard Layout Style", "js_composer"),
			  "param_name" => "blog_standard_style",
			  "admin_label" => false,
			  "value" => array(
			  	   __('Inherit from Theme Options', NECTAR_THEME_NAME) => 'inherit',
			  		 __('Classic Style', NECTAR_THEME_NAME) => 'classic',
             __('Minimal Style', NECTAR_THEME_NAME) => 'minimal',
						 __('Featured Image Left Style', NECTAR_THEME_NAME) => 'featured_img_left',
				),
			  'save_always' => true,
			  "dependency" => Array('element' => "layout", 'value' => array('std-blog-sidebar','std-blog-fullwidth')),
			  "description" => __("Please select the style you would like your posts to use when the standard layout is displayed", "js_composer")
			),

			array(
			  "type" => "dropdown_multi",
			  "heading" => __("Blog Categories", "js_composer"),
			  "param_name" => "category",
			  "admin_label" => true,
			  "value" => $blog_options,
			  'save_always' => true,
			  "description" => __("Please select the categories you would like to display for your blog. <br/> You can select multiple categories too (ctrl + click on PC and command + click on Mac).", "js_composer")
			),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Sticky Sidebar", "js_composer"),
		      "param_name" => "enable_ss",
		      "description" => __("Would you like to have your sidebar follow down as your scroll in a sticky manner?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "layout", 'value' => array('std-blog-sidebar','masonry-blog-sidebar')),
		    ),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Pagination", "js_composer"),
		      "param_name" => "enable_pagination",
		      "description" => __("Would you like to enable pagination?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Pagination Type", "js_composer"),
			  "param_name" => "pagination_type",
			  "admin_label" => true,
			  "value" => array(	
				    'Default' => 'default',
				    'Infinite Scroll' => 'infinite_scroll',
				),
			  'save_always' => true,
			  "description" => __("Please select your pagination type here.", "js_composer"),
			  "dependency" => Array('element' => "enable_pagination", 'not_empty' => true)
			),
		    array(
		      "type" => "textfield",
		      "heading" => __("Posts Per Page", "js_composer"),
		      "param_name" => "posts_per_page",
		      "description" => __("How many posts would you like to display per page? <br/> If pagination is not enabled, will simply show this number of posts <br/> Enter as a number example \"10\"", "js_composer")
		    ),
				array(
		      "type" => "textfield",
		      "heading" => __("Post Offset", "js_composer"),
		      "param_name" => "post_offset",
		      "description" => __("Optioinally enter a number e.g. \"2\" to offset your posts by - useful for when you're using multiple styles of this element on the same page and would like them to no show duplicate posts", "js_composer")
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Load In Animation", "js_composer"),
			  "param_name" => "load_in_animation",
			  'save_always' => true,
			  "value" => array(
				    "None" => "none",
				    "Fade In" => "fade_in",
				    "Fade In From Bottom" => "fade_in_from_bottom",
				    "Perspective Fade In" => "perspective"
				),
			  "description" => __("Please select the loading animation you would like ", "js_composer")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order", "js_composer"),
				"param_name" => "order",
				"admin_label" => false,
				"value" => array(
					'Descending' => 'DESC',
					'Ascending' => 'ASC',
				),
				'save_always' => true,
				"description" => __("Designates the ascending or descending order - defaults to descending", "js_composer")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Orderby", "js_composer"),
				"param_name" => "orderby",
				"admin_label" => false,
				"value" => array(
					'Date' => 'date',
					'Author' => 'author',
					'Title' => 'title',
					'Last Modified' => 'modified',
					'Random' => 'rand',
					'Comment Count' => 'comment_count',
					'View Count' => 'view_count'
				),
				'save_always' => true,
				"description" => __("Sort retrieved posts by parameter - defaults to date", "js_composer")
			),
		  )
		);

?>