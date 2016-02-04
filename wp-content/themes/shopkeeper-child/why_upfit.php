<?php

function custom_post_why_upfit() {
  $labels = array(
    'name'               => _x( 'Why upfit', 'post type general name' ),
    'singular_name'      => _x( 'Why upfit', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Why upfit' ),
    'add_new_item'       => __( 'Add New Why upfit' ),
    'edit_item'          => __( 'Edit Why upfit' ),
    'new_item'           => __( 'New Why upfit' ),
    'all_items'          => __( 'All Why upfit' ),
    'view_item'          => __( 'View Why upfit' ),
    'search_items'       => __( 'Search Why upfit' ),
    'not_found'          => __( 'No Why upfit found' ),
    'not_found_in_trash' => __( 'No Why upfit found in the Trash' ), 
    'parent_item_colon'  => '',
    'parent' => __( 'Parent upfit' ),
    'menu_name'          => 'Why Upfit',
    
    
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our products and product specific data',
    'public'        => true,
    'menu_position' => 8,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ,'author'),
    //'taxonomies' 	=> array('Company'),
    //'menu_icon'			 => 'dashicons-',
    'has_archive'   => true,
  );
  register_post_type( 'Why Upfit', $args ); 
}
add_action( 'init', 'custom_post_why_upfit' );


add_shortcode('display_why_upfit','show_why_upfit');

function show_why_upfit()
{
	//echo "hi";
	$args = array(
	'posts_per_page'   => 4,
	'category'         => '',
	'category_name'    => '',
	'offset'           => 0,
	'orderby'          => 'date',
	'order'            => 'DESC',
	/*'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',*/
	'post_type'        => 'Why Upfit',
	/*'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',*/
	'post_status'      => 'publish',
	'suppress_filters' => true 
	);

$the_query = new WP_Query( $args );
//print_r($the_query);
?>
	<div class="main_col block_warum">
		
<?php
set_post_thumbnail_size( 150, 150 );
while ( $the_query->have_posts() ) : $the_query->the_post();
?>
	<div class="col">
			<div class="img_warum">
				<?php
				if (has_post_thumbnail()) {
						the_post_thumbnail();
				} else {
						$noimg = get_template_directory_uri() . '/img/noimage.jpg';
						?>
						<img src="<?php echo $noimg; ?>"/>
						<?php
				}
				?>
			</div>
			<div class="heading_warum"><h4><?php the_title();?></h4></div>
			<div class="details_warum"><?php 
		 $content = get_the_content();
		 echo substr($content, 0, 310);
		
		?></div>
	</div>
<?php
endwhile;
?>
	</div>
	
	
<?php
wp_reset_postdata();
}
