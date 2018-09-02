<?php

	return array(
	  "name" => __("Single Testimonial", "js_composer"),
	  "base" => "nectar_single_testimonial",
    "icon" => "icon-nectar-single-testimonial",
    "category" => __('Nectar Elements', 'js_composer'),
    "description" => __('Styled Quotes', 'js_composer'),
	  "params" => array(
      array(
      "type" => "dropdown",
      "heading" => __("Style", "js_composer"),
      "param_name" => "testimonial_style",
      "value" => array(
         "Small Modern" => "small_modern",
				 "Basic" => "basic",
				 "Big Bold" => "bold",
       ),
      'save_always' => true,
      'description' => __( 'Choose your desired style here.', 'js_composer' ),
      ),
      array(
       "type" => "textarea",
       "heading" => __("Quote", "js_composer"),
       "param_name" => "quote",
       "description" => __("The testimonial quote", "js_composer")
     ),
	  	array(
			"type" => "fws_image",
			"class" => "",
			"heading" => "Image",
			"value" => "",
			"param_name" => "image",
			"description" => "Add an optional image for the person/company who supplied the testimonial"
		),
    array(
      "type" => "textfield",
      "heading" => __("Name", "js_composer"),
      "param_name" => "name",
      "admin_label" => true,
      "description" => __("Name or source of the testimonial", "js_composer")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Subtitle", "js_composer"),
      "param_name" => "subtitle",
      "admin_label" => false,
      "description" => __("The optional subtitle that will follow the testimonial name", "js_composer")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Added Color", "js_composer"),
      "param_name" => "color",
      "value" => array(
       "Default (inherit from row Text Color)" => "Default",
       "Accent Color" => "Accent-Color",
       "Extra Color-1" => "Extra-Color-1",
       "Extra Color-2" => "Extra-Color-2",	
       "Extra Color-3" => "Extra-Color-3"
       ),
      'save_always' => true,
      "dependency" => array('element' => "testimonial_style", 'value' => array('small_modern','bold')),
      'description' => __( 'Choose a color from your <a target="_blank" href="'. admin_url() .'?page=Salient&tab=6">globally defined color scheme</a>', 'js_composer' ),
    ),

	  )
	);

?>