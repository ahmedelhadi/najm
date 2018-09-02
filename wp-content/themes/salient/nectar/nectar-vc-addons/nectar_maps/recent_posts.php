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
		  "name" => __("Recent Posts", "js_composer"),
		  "base" => "recent_posts",
		  "weight" => 8,
		  "icon" => "icon-wpb-recent-posts",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Display your recent blog posts', 'js_composer'),
		  "params" => array(
		  	array(
			  "type" => "dropdown",
			  "heading" => __("Style", "js_composer"),
			  "param_name" => "style",
			  "admin_label" => true,
			  "value" => array(	
				    'Default' => 'default',
				    'Minimal' => 'minimal',
				    'Minimal - Title Only' => 'title_only',
				    'Classic Enhanced' => 'classic_enhanced',
				    'Classic Enhanced Alt' => 'classic_enhanced_alt',
						'List With Featured First Row' => 'list_featured_first_row',
						'List With Tall Featured First Row ' => 'list_featured_first_row_tall',
				    'Slider' => 'slider',
						'Slider Multiple Visible' => 'slider_multiple_visible',
						'Single Large Featured' => 'single_large_featured',
						'Multiple Large Featured' => 'multiple_large_featured'
				),
			  'save_always' => true,
			  "description" => __("Please select desired style here.", "js_composer")
			),
			array(
			  "type" => "dropdown",
			  "heading" => __("Color Scheme", "js_composer"),
			  "param_name" => "color_scheme",
			  "admin_label" => true,
			  "value" => array(	
				    'Light' => 'light',
				    'Dark' => 'dark',
				),
			  "dependency" => Array('element' => "style", 'value' => array('classic_enhanced')),
			  'save_always' => true,
			  "description" => __("Please select your desired coloring here.", "js_composer")
			),
			array(
		      "type" => "textfield",
		      "heading" => __("Slider Height", "js_composer"),
		      "param_name" => "slider_size",
		      "admin_label" => false,
		      "dependency" => Array('element' => "style", 'value' => 'slider'),
		      "description" => __("Don't include \"px\" in your string. e.g. 650", "js_composer")
		    ),
			array(
			  "type" => "dropdown_multi",
			  "heading" => __("Blog Categories", "js_composer"),
			  "param_name" => "category",
			  "admin_label" => true,
			  "value" => $blog_options,
			  'save_always' => true,
			  "description" => __("Please select the categories you would like to display in your recent posts. <br/> You can select multiple categories too (ctrl + click on PC and command + click on Mac).", "js_composer")
			),
			array(
			  "type" => "dropdown",
			  "heading" => __("Number Of Columns", "js_composer"),
			  "param_name" => "columns",
			  "admin_label" => false,
			  "value" => array(
			  	'4' => '4',
			  	'3' => '3',
			  	'2' => '2',
			  	'1' => '1'
			  ),
			  "dependency" => Array('element' => "style", 'value' => array('default','minimal','title_only','classic_enhanced', 'classic_enhanced_alt', 'list_featured_first_row', 'list_featured_first_row_tall', 'slider_multiple_visible')),
			  'save_always' => true,
			  "description" => __("Please select the number of posts you would like to display.", "js_composer")
			),
			array(
		      "type" => "textfield",
		      "heading" => __("Number Of Posts", "js_composer"),
		      "param_name" => "posts_per_page",
					"dependency" => Array('element' => "style", 'value' => array('default','minimal','title_only','classic_enhanced', 'classic_enhanced_alt','slider', 'slider_multiple_visible', 'list_featured_first_row',  'list_featured_first_row_tall')),
		      "description" => __("How many posts would you like to display? <br/> Enter as a number example \"4\"", "js_composer")
		    ),
				array(
				  "type" => "dropdown",
				  "heading" => __("Number Of Posts", "js_composer"),
				  "param_name" => "multiple_large_featured_num",
				  "admin_label" => false,
				  "value" => array(
				  	'4' => '4',
				  	'3' => '3',
				  	'2' => '2',
				  ),
				  "dependency" => Array('element' => "style", 'value' => array('multiple_large_featured')),
				  'save_always' => true,
				  "description" => __("Please select the number of posts you would like to display.", "js_composer")
				),
		    array(
		      "type" => "textfield",
		      "heading" => __("Post Offset", "js_composer"),
		      "param_name" => "post_offset",
		      "description" => __("Optioinally enter a number e.g. \"2\" to offset your posts by - useful for when you're using multiple styles of this element on the same page and would like them to no show duplicate posts", "js_composer")
		    ),
				array(
				  "type" => "dropdown",
				  "heading" => __("Auto Rotate", "js_composer"),
				  "param_name" => "auto_rotate",
				  "admin_label" => true,
				  "value" => array(	
					    'No Auto Rotate' => 'none',
							'11 Seconds' => '11000',
							'10 Seconds' => '10000',
							'9 Seconds' => '9000',
					    '8 Seconds' => '8000',
							'7 Seconds' => '7000',
							'6 Seconds' => '6000',
							'5 Seconds' => '5000',
							'4 Seconds' => '4000',
							'3 Seconds' => '3000',
					),
				  "dependency" => Array('element' => "style", 'value' => array('multiple_large_featured')),
				  'save_always' => true,
				  "description" => __("Please select your desired auto rotation timing here", "js_composer")
				),
				array(
				  "type" => "dropdown",
				  "heading" => __("Top/Bottom Padding", "js_composer"),
				  "param_name" => "large_featured_padding",
				  "admin_label" => false,
				  "value" => array(
				  	'20%' => '20%',
				  	'18%' => '18%',
				  	'16%' => '16%',
						'14%' => '14%',
						'12%' => '12%',
						'10%' => '10%',
						'8%' => '8%',
						'6%' => '6%',
				  ),
				  "dependency" => Array('element' => "style", 'value' => array('single_large_featured','multiple_large_featured')),
				  'save_always' => true,
				  "description" => __("The % value will be applied as padding to the top and bottom of your featured post(s)", "js_composer")
				),
				array(
				  "type" => "dropdown",
				  "heading" => __("Navigation Location", "js_composer"),
				  "param_name" => "mlf_navigation_location",
				  "admin_label" => false,
				  "value" => array(
				  	'On Side' => 'side',
				  	'On Bottom' => 'bottom',
				  ),
				  "dependency" => Array('element' => "style", 'value' => array('multiple_large_featured')),
				  'save_always' => true,
				  "description" => __("Please select where you would like the navigation to display", "js_composer")
				),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Title Labels", "js_composer"),
		      "param_name" => "title_labels",
		      "description" => __("These labels are defined by you in the \"Blog Options\" tab of your theme options panel.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "style", 'value' => 'default')
		    ),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Button Color', 'js_composer' ),
					'value' => array(
						"Accent Color" => "Accent-Color",
						"Extra Color 1" => "Extra-Color-1",
						"Extra Color 2" => "Extra-Color-2",	
						"Extra Color 3" => "Extra-Color-3",
						"Color Gradient 1" => "extra-color-gradient-1",
				 		"Color Gradient 2" => "extra-color-gradient-2",
					),
					'save_always' => true,
					'param_name' => 'button_color',
					"dependency" => Array('element' => "style", 'value' => array('single_large_featured','multiple_large_featured', 'slider_multiple_visible')),
					'description' => __( 'Choose a color from your <a target="_blank" href="'. admin_url() .'?page=Salient&tab=6">globally defined color scheme</a>', 'js_composer' ),
				),
				array(
				  "type" => "dropdown",
				  "heading" => __("BG Overlay", "js_composer"),
				  "param_name" => "bg_overlay",
				  "admin_label" => true,
				  "value" => array(	
					    'Solid' => 'solid_color',
					    'Diagonal Gradient' => 'diagonal_gradient',
					),
				  "dependency" => Array('element' => "style", 'value' => array('single_large_featured','multiple_large_featured')),
				  'save_always' => true,
				  "description" => __("Please select your desired BG overlay here.", "js_composer")
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