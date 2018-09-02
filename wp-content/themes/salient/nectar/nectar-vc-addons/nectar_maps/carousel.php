<?php 


$tab_id_1 = time().'-1-'.rand(0, 100);
	$tab_id_2 = time().'-2-'.rand(0, 100);
	$tab_id_3 = time().'-3-'.rand(0, 100);

	$vc_is_wp_version_3_6_more = version_compare(preg_replace('/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo('version')), '3.6') >= 0;

return array(
	  "name"  => __("Carousel", "js_composer"),
	  "base" => "carousel",
	  "show_settings_on_create" => true,
	  "is_container" => true,
	  "icon" => "icon-wpb-carousel",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('A simple carousel for any content', 'js_composer'),
	  "params" => array(
	  array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "Carousel Script",
			'save_always' => true,
			"param_name" => "script",
			"value" => array(
				"carouFredSel" => "carouFredSel",
				"Owl Carousel" => "owl_carousel",
				"Flickity" => "flickity"
			),
			"description" => __("Owl Carousel and Flickity are reccomended over carouFredSel - however carouFredSel is still available for legacy users who prefer it." , "js_composer")
		),
		array(
		 "type" => "dropdown",
		 "class" => "",
		 "heading" => "Style",
		 'save_always' => true,
		 "param_name" => "flickity_formatting",
		 "value" => array(
			 "Default" => "default",
			 "Fixed Text Content Fullwidth" => "fixed_text_content_fullwidth",
		 ),
		 "dependency" => array('element' => "script", 'value' => 'flickity'),
		 "description" => __("Select the formatting of your carousel. <br/>When using the \"Fixed Text Content Fullwidth\" format, the carousel should be the only element in your row and inside of a full (1/1) column." , "js_composer")
	 ),
	 array(
			 "type" => "textarea",
			 "holder" => "div",
			 "heading" => __("Text Content", "js_composer"),
			 "param_name" => "flickity_fixed_content",
			 "value" => __("", "js_composer"),
			 "dependency" => array('element' => "flickity_formatting", 'value' => array('fixed_text_content_fullwidth')),
			 "description" => __("Enter any text/content you would like to be shown prominently in your carousel", "js_composer"),
			 "admin_label" => false
		 ),
		 
		 array(
				"type" => "textfield",
				"heading" => __("CTA Button Text", "js_composer"),
				"param_name" => "cta_button_text",
				"description" => __("Enter your CTA text here" , "js_composer"),
				"dependency" => array('element' => "flickity_formatting", 'value' => array('fixed_text_content_fullwidth'))
			),

		array(
				"type" => "textfield",
				"heading" => __("CTA Button Link URL", "js_composer"),
				"param_name" => "cta_button_url",
				"description" => __("Enter your URL here" , "js_composer"),
				"dependency" => array('element' => "flickity_formatting", 'value' => array('fixed_text_content_fullwidth'))
			),

		 array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("CTA Button Open in New Tab", "js_composer"),
				"param_name" => "cta_button_open_new_tab",
			"value" => Array(__("Yes", "js_composer") => 'true'),
			"description" => "",
			"dependency" => array('element' => "flickity_formatting", 'value' => array('fixed_text_content_fullwidth'))
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			'save_always' => true,
			"heading" => "CTA Button Color",
			"param_name" => "button_color",
			"value" => array(
				"Accent Color" => "Accent-Color",
				"Extra Color 1" => "Extra-Color-1",
				"Extra Color 2" => "Extra-Color-2",	
				"Extra Color 3" => "Extra-Color-3",
				"Color Gradient 1" => "extra-color-gradient-1",
				"Color Gradient 2" => "extra-color-gradient-2"
			),
			"dependency" => array('element' => "flickity_formatting", 'value' => array('fixed_text_content_fullwidth')),
			'description' => __( 'Choose a color from your <a target="_blank" href="'. admin_url() .'?page=Salient&tab=6">globally defined color scheme</a>', 'js_composer' ),
		),
		
	   array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "Columns <span>Desktop</span>",
			'save_always' => true,
			"param_name" => "desktop_cols",
			"value" => array(
				"Default (4)" => "4",
				"1" => "1",
				"2" => "2",
				"3" => "3",
				"4" => "4",
				"5" => "5",
				"6" => "6",
				"7" => "7",
				"8" => "8",
			),
			"edit_field_class" => "col-md-2 vc_column",
			"dependency" => array('element' => "script", 'value' => array('owl_carousel')),
			"description" => __("" , "js_composer")
		),
	   array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "<span>Desktop Small</span>",
			'save_always' => true,
			"param_name" => "desktop_small_cols",
			"value" => array(
				"Default (3)" => "3",
				"1" => "1",
				"2" => "2",
				"3" => "3",
				"4" => "4",
				"5" => "5",
				"6" => "6",
				"7" => "7",
				"8" => "8",
			),
			"edit_field_class" => "col-md-2 vc_column",
			"dependency" => array('element' => "script", 'value' => array('owl_carousel')),
			"description" => __("" , "js_composer")
		),
	    array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "<span>Tablet</span>",
			'save_always' => true,
			"param_name" => "tablet_cols",
			"value" => array(
				"Default (2)" => "2",
				"1" => "1",
				"2" => "2",
				"3" => "3",
				"4" => "4",
				"5" => "5",
				"6" => "6",
			),
			"edit_field_class" => "col-md-2 vc_column",
			"dependency" => array('element' => "script", 'value' => array('owl_carousel')),
			"description" => __("" , "js_composer")
		),
	    array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "<span>Mobile</span>",
			'save_always' => true,
			"param_name" => "mobile_cols",
			"value" => array(
				"Default (1)" => "1",
				"1" => "1",
				"2" => "2",
				"3" => "3",
				"4" => "4",
			),
			"dependency" => array('element' => "script", 'value' => array('owl_carousel')),
			"edit_field_class" => "col-md-2 vc_column",
			"description" => __("" , "js_composer")
		),
		
		array(
		 "type" => "dropdown",
		 "class" => "",
		 "heading" => "Columns <span>Desktop</span>",
		 'save_always' => true,
		 "param_name" => "desktop_cols_flickity",
		 "value" => array(
			 "Default (3)" => "3",
			 "1" => "1",
			 "2" => "2",
			 "3" => "3",
			 "4" => "4",
			 "5" => "5",
			 "6" => "6",
		 ),
		 "edit_field_class" => "col-md-2 vc_column",
		 "dependency" => array('element' => "script", 'value' => array('flickity')),
		 "description" => __("" , "js_composer")
	 ),
		array(
		 "type" => "dropdown",
		 "class" => "",
		 "heading" => "<span>Desktop Small</span>",
		 'save_always' => true,
		 "param_name" => "desktop_small_cols_flickity",
		 "value" => array(
			 "Default (3)" => "3",
			 "1" => "1",
			 "2" => "2",
			 "3" => "3",
			 "4" => "4",
			 "5" => "5",
			 "6" => "6",
		 ),
		 "edit_field_class" => "col-md-2 vc_column",
		 "dependency" => array('element' => "script", 'value' => array('flickity')),
		 "description" => __("" , "js_composer")
	 ),
		 array(
		 "type" => "dropdown",
		 "class" => "",
		 "heading" => "<span>Tablet</span>",
		 'save_always' => true,
		 "param_name" => "tablet_cols_flickity",
		 "value" => array(
			 "Default (2)" => "2",
			 "1" => "1",
			 "2" => "2",
			 "3" => "3"
		 ),
		 "edit_field_class" => "col-md-2 vc_column",
		 "dependency" => array('element' => "script", 'value' => array('flickity')),
		 "description" => __("" , "js_composer")
	 ),
	 
	   array(
	      "type" => "textfield",
	      "heading" => __("Carousel Title", "js_composer"),
	      "param_name" => "carousel_title",
	      "dependency" => array('element' => "script", 'value' => array('carouFredSel')),
	      "description" => __("Enter the title you would like at the top of your carousel (optional)" , "js_composer")
	    ),
	   array(
	     "type" => "dropdown",
			"class" => "",
			"heading" => "Column Padding",
			'save_always' => true,
			"param_name" => "column_padding",
			"value" => array(
				"None" => "0",
				"5px" => "5px",
				"10px" => "10px",
				"15px" => "15px",
				"20px" => "20px",
				"30px" => "30px",
				"40px" => "40px",
				"50px" => "50px"
			),
			"dependency" => array('element' => "script", 'value' => array('owl_carousel','flickity')),
			"description" => __("Please select your desired column padding " , "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Transition Scroll Speed", "js_composer"),
	      "param_name" => "scroll_speed",
	      "dependency" => array('element' => "script", 'value' => array('carouFredSel')),
	      "description" => __("Enter in milliseconds (default is 700)" , "js_composer")
	    ),
			array(
	 		 "type" => "checkbox",
	 		 "class" => "",
	 		 "heading" => __("Loop?", "js_composer"),
	 			 "param_name" => "loop",
	 		 "value" => Array(__("Yes", "js_composer") => 'true'),
			 "dependency" => array('element' => "script", 'value' => array('owl_carousel')),
	 		 "description" => ""
	 	 ),
	    array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Autorotate?", "js_composer"),
	     	"param_name" => "autorotate",
			"value" => Array(__("Yes", "js_composer") => 'true'),
			"description" => ""
		),
		array(
	      "type" => "textfield",
	      "heading" => __("Autorotation Speed", "js_composer"),
	      "param_name" => "autorotation_speed",
	      "dependency" => array('element' => "script", 'value' => array('owl_carousel','flickity')),
	      "description" => __("Enter in milliseconds (default is 5000)" , "js_composer")
	    ),
			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Carousel Column Color",
				"param_name" => "column_color",
				"value" => "",
				"dependency" => array('element' => "script", 'value' => array('flickity')),
				"description" => ""
			),
			array(
					"type" => "dropdown",
					"heading" => __("Border Radius", "js_composer"),
					'save_always' => true,
					"param_name" => "border_radius",
					"dependency" => array('element' => "script", 'value' => array('flickity')),
					"value" => array(
						__("0px", "js_composer") => "none",
						__("3px", "js_composer") => "3px",
						__("5px", "js_composer") => "5px", 
						__("10px", "js_composer") => "10px", 
						__("15px", "js_composer") => "15px", 
						__("20px", "js_composer") => "20px"),
				),	
				array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Column Border",
				"value" => array("Enable?" => "true" ),
				"param_name" => "enable_column_border",
				"dependency" => array('element' => "script", 'value' => array('flickity')),
				"description" => "This add a subtle border to your columns"
			),
	    array(
			"type" => "checkbox",
			"class" => "",
			"heading" => "Enable Animation",
			"value" => array("Enable Animation?" => "true" ),
			"param_name" => "enable_animation",
			"dependency" => array('element' => "script", 'value' => array('owl_carousel')),
			"description" => "This will cause your list items to animate in one by one"
		),

		array(
			"type" => "textfield",
			"class" => "",
			"heading" => "Animation Delay",
			"param_name" => "delay",
			"admin_label" => false,
			"description" => "",
			"dependency" => Array('element' => "enable_animation", 'not_empty' => true)
		),

	    array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"admin_label" => false,
			"heading" => "Easing",
			"param_name" => "easing",
			'save_always' => true,
			"dependency" => array('element' => "script", 'value' => array('carouFredSel')),
			"value" => array(
				'linear'=>'linear',
				'swing'=>'swing',
				'easeInQuad'=>'easeInQuad',
				'easeOutQuad' => 'easeOutQuad',
				'easeInOutQuad'=>'easeInOutQuad',
				'easeInCubic'=>'easeInCubic',
				'easeOutCubic'=>'easeOutCubic',
				'easeInOutCubic'=>'easeInOutCubic',
				'easeInQuart'=>'easeInQuart',
				'easeOutQuart'=>'easeOutQuart',
				'easeInOutQuart'=>'easeInOutQuart',
				'easeInQuint'=>'easeInQuint',
				'easeOutQuint'=>'easeOutQuint',
				'easeInOutQuint'=>'easeInOutQuint',
				'easeInExpo'=>'easeInExpo',
				'easeOutExpo'=>'easeOutExpo',
				'easeInOutExpo'=>'easeInOutExpo',
				'easeInSine'=>'easeInSine',
				'easeOutSine'=>'easeOutSine',
				'easeInOutSine'=>'easeInOutSine',
				'easeInCirc'=>'easeInCirc',
				'easeOutCirc'=>'easeOutCirc',
				'easeInOutCirc'=>'easeInOutCirc',
				'easeInElastic'=>'easeInElastic',
				'easeOutElastic'=>'easeOutElastic',
				'easeInOutElastic'=>'easeInOutElastic',
				'easeInBack'=>'easeInBack',
				'easeOutBack'=>'easeOutBack',
				'easeInOutBack'=>'easeInOutBack',
				'easeInBounce'=>'easeInBounce',
				'easeOutBounce'=>'easeOutBounce',
				'easeInOutBounce'=>'easeInOutBounce',
			),
			"description" => "Select the animation easing you would like for slide transitions <a href=\"http://jqueryui.com/resources/demos/effect/easing.html\" target=\"_blank\"> Click here </a> to see examples of these."
		)
	  ),
	  "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  %content%
	  </div>'
	  ,
	  'default_content' => '
	  [item id="'.$tab_id_1.'"] Add Content Here [/item]
	  [item id="'.$tab_id_2.'"] Add Content Here [/item]
	  [item id="'.$tab_id_3.'"] Add Content Here [/item]
	  ',
	  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
	);

?>