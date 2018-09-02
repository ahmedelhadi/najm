<?php 

extract(shortcode_atts(array("heading_tag" => "h3", 'text' => '',"btn_style" => "see-through", "link_text" => "", 'text_color' => '', 'url' => '', 'link_type' => 'regular', 'alignment' => 'left', 'class' => '' ), $atts));

$target = ($link_type == 'new_tab') ? 'target="_blank"' : null;
$style = (!empty($text_color)) ? ' style="color: '.$text_color.';"' : null;
$bg_style = (!empty($text_color)) ? ' style="background-color: '.$text_color.';"' : null;
$text_color = (!empty($text_color)) ? 'custom' : 'std';

/*material*/
if($btn_style == 'material') {
  echo '<div class="nectar-cta '.$class.'" data-style="'.$btn_style.'" data-alignment="'.$alignment.'"  data-text-color="'.$text_color.'" >';
  echo '<'.$heading_tag.'> <span class="text">'.$text.' </span>';
  echo  '<span class="link_wrap" '.$style.'><a '.$target .' class="link_text" href="'.$url.'">'.$link_text.'<span class="circle" '.$bg_style.'></span><span class="arrow"></span></a></span>'; 
  echo '</'.$heading_tag.'></div>';
} else {
  echo '<div class="nectar-cta '.$class.'" data-style="'.$btn_style.'" data-alignment="'.$alignment.'"  data-text-color="'.$text_color.'" >';
  echo '<'.$heading_tag. $style.'> <span class="text">'.$text.' </span>';
  echo  '<span class="link_wrap"><a '.$target .' class="link_text" href="'.$url.'">'.$link_text.'<span class="arrow"></span></a></span>'; 
  echo '</'.$heading_tag.'></div>';
}


?>