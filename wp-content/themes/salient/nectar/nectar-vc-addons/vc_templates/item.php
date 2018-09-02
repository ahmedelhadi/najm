<?php 


if($GLOBALS['nectar-carousel-script'] == 'carouFredSel') {
	echo '<li class="col span_4">' . do_shortcode($content) . '</li>';
} else if($GLOBALS['nectar-carousel-script'] == 'owl_carousel') {
	echo '<div class="carousel-item">' . do_shortcode($content) . '</div>';
} else if($GLOBALS['nectar-carousel-script'] == 'flickity') {
	$column_bg_markup = (!empty($GLOBALS['nectar_carousel_column_color'])) ? 'style=" background-color: ' . sanitize_text_field($GLOBALS['nectar_carousel_column_color']) . ';"': '';
	echo '<div class="cell"><div class="inner-wrap-outer"><div class="inner-wrap" '.$column_bg_markup.'>' . do_shortcode($content) . '</div></div></div>';
}

?>