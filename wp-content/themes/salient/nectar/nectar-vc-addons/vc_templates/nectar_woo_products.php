<?php 


global $woocommerce_loop;

extract( shortcode_atts( array(
	'product_type' => 'all',
	'per_page' 	=> '12',
	'columns' 	=> '4',
	'carousel' 	=> 'false',
	'category' 	=> 'all',
	'controls_on_hover' => 'false'
), $atts ) );

//incase only all was selected
if($category == 'all') {
	$category = null;
}


if($product_type == 'all') {

	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' 		=> $per_page,
		'product_cat'           => $category,
		'meta_query'          => WC()->query->get_meta_query(),
		'tax_query'           => WC()->query->get_tax_query(),
	);

} else if($product_type == 'sale') {


	$args = array(
		'posts_per_page'	=> $per_page,
		'no_found_rows'  => 1,
		'post_status'    => 'publish',
		'post_type'      => 'product',
		'product_cat'           => $category,
		'meta_query'     => WC()->query->get_meta_query(),
		'tax_query'      => WC()->query->get_tax_query(),
		'post__in'       => array_merge( array( 0 ), wc_get_product_ids_on_sale() ),
	);


} else if($product_type == 'featured') {


	$meta_query  = WC()->query->get_meta_query();
	$tax_query   = WC()->query->get_tax_query();
	$tax_query[] = array(
		'taxonomy' => 'product_visibility',
		'field'    => 'name',
		'terms'    => 'featured',
		'operator' => 'IN',
	);

	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'product_cat'         => $category,
		'posts_per_page' 	  => $per_page,
		'meta_query'          => $meta_query,
		'tax_query'           => $tax_query,
	);


} else if($product_type == 'best_selling') {


	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'product_cat'           => $category,
		'posts_per_page'		=> $per_page,
		'meta_key'            => 'total_sales',
		'orderby'             => 'meta_value_num',
		'meta_query'          => WC()->query->get_meta_query(),
		'tax_query'           => WC()->query->get_tax_query(),
	);


}






ob_start();

$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

$woocommerce_loop['columns'] = $columns;

if ( $products->have_posts() ) : ?>
	
	<?php if($carousel == '1') { ?> <div class="carousel-wrap products-carousel" data-controls="<?php echo $controls_on_hover ?>"> <?php } ?>

	<?php wc_get_template( 'loop/loop-start.php' ); ?>

		<?php while ( $products->have_posts() ) : $products->the_post(); ?>

			<?php wc_get_template_part( 'content', 'product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php  wc_get_template( 'loop/loop-end.php' ); ?>

	<?php if($carousel == '1') { ?> </div> <?php } ?>

<?php endif;

wp_reset_postdata();

echo '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
	

?>