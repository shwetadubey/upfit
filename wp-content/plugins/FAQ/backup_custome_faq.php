<?php


register_activation_hook(__FILE__, 'faq_plugin_activate');

register_deactivation_hook(__FILE__, 'faq_plugin_deactivate');

function faq_plugin_activate()
{
	my_custom_post_FAQ();
	flush_rewrite_rules();	
}


function faq_plugin_deactivate()
{
	flush_rewrite_rules();
}

function my_custom_post_FAQ() {
  $labels = array(
    'name'               => _x( 'FAQ', 'post type general name' ),
    'singular_name'      => _x( 'FAQ', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'FAQ' ),
    'add_new_item'       => __( 'Add New FAQ' ),
    'edit_item'          => __( 'Edit FAQ' ),
    'new_item'           => __( 'New FAQ' ),
    'all_items'          => __( 'All FAQ' ),
    'view_item'          => __( 'View FAQ' ),
    'search_items'       => __( 'Search FAQ' ),
    'not_found'          => __( 'No FAQ found' ),
    'not_found_in_trash' => __( 'No FAQ found in the Trash' ), 
    'parent_item_colon'  => '',
    'parent' => __( 'Parent FAQ' ),
    'menu_name'          => 'FAQ',
    
    
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Add new question and manage frequently asked questions',
    'public'        => true,
    'menu_position' => 7,
    'supports'      => array( 'title', 'editor', 'thumbnail','author','excerpt'),
    //'taxonomies' 	=> array('Company'),
    'menu_icon'			 => 'dashicons-admin-customizer',
    'has_archive'   => true,
  );
  register_post_type( 'faq', $args ); 
}
add_action( 'init', 'my_custom_post_FAQ' );


add_shortcode('display_faq','show_faq');

function show_faq($atts)
{
	
	  $a = shortcode_atts( array(
        'limit' => -1,
        'offset' => 0,
    ), $atts );
   
    
    $args = array(
	'posts_per_page'   => $a['limit'],
	'offset'           => $a['offset'],
	'orderby'          => 'date',
	'order'            => 'DESC',
	/*'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',*/
	'post_type'        => 'faq',
	/*'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',*/
	'post_status'      => 'publish',
	'suppress_filters' => true 
);

$the_query = new WP_Query( $args );
?>
	<div class="faq_show" id="faq">
			
			
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div>
			<a href="javascript:void(0);">FAQ</a>
			<h3><?php the_title();?></h3>
			
			<div>
			<div><?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                    the_post_thumbnail('thumbnail'); 
                  }
                  ?>
				  </div>
				  <?php 
				  $content = get_the_content();
				  echo substr($content, 0, 310);
				  //the_content(); ?></div>
				  <hr/>
				  
		</div> 
		<?php 
		endwhile;
		wp_reset_postdata();?>
		
			
		</div>
		
<?php
}
	


add_shortcode('load_more_faq','show_more_faq');

function show_more_faq($atts)
{
	
	  $a = shortcode_atts( array(
        'limit' => -1,
        'offset' => 0,
    ), $atts );
   
    
    $args = array(
	'posts_per_page'   => $a['limit'],
	'offset'           => $a['offset'],
	'orderby'          => 'date',
	'order'            => 'DESC',
	/*'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',*/
	'post_type'        => 'faq',
	/*'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',*/
	'post_status'      => 'publish',
	'suppress_filters' => true 
);

$the_query = new WP_Query( $args );
//print_r($the_query);
?>
	
			
			
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div>
			<a href="javascript:void(0);">FAQ</a>
			<h3><?php the_title();?></h3>
			
			<div>
			<div><?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                    the_post_thumbnail('thumbnail'); 
                  }
                  ?>
				  </div>
				  <?php 
				  $content = get_the_content();
				  echo substr($content, 0, 310);
				  //the_content(); ?></div>
				  <hr/>
				  
		</div> 
		<?php 
		endwhile;
		wp_reset_postdata();?>
		
			
		
		
<?php
}
	
?>
