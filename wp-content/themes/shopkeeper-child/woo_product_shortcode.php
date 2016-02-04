<?php 
add_shortcode('woo_product_title_display','show_woo_product');
function show_woo_product($atts)
{	
$post_para = array(
	//'posts_per_page'   => 4,
	'offset'           => 0,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'product',
	'post_status'      => 'publish',
	'suppress_filters' => true ,
	 'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'plan-pricing'
            )
        )
);


$wooproduct_posts = new WP_Query( $post_para );
$page = get_page_by_path( 'ernaehrungsplan_erstellen' );
?>

<div class="textblock">
<ul>
<?php
while ( $wooproduct_posts->have_posts() ) : $wooproduct_posts->the_post();
?>
	<li>
		
		<a href="<?php echo get_permalink($page->ID); ?>" title="<?php the_title();?>" target="_blank"><?php the_title(); ?></a>
	</li>
<?php
endwhile;
wp_reset_query();

?>
</ul>
</div>
<?php
}
