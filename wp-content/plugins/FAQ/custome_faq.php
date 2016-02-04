<?php

/**
 * Plugin Name: FAQ Plugin
 * Plugin URI: 
 * Description: This is very simple faq plugin
 * Version: 1.0
 * 
 */

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

add_action( 'init', 'create_topics_hierarchical_taxonomy', 0 );

//create a custom taxonomy name it topics for your posts

function create_topics_hierarchical_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'FAQ Kategorien', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Kategorien' ),
    'all_items' => __( 'All Kategorien' ),
    'parent_item' => __( 'Parent Kategorien' ),
    'parent_item_colon' => __( 'Parent Kategorien:' ),
    'edit_item' => __( 'Edit Kategorien' ), 
    'update_item' => __( 'Update Kategorien' ),
    'add_new_item' => __( 'Add New Kategorien' ),
    'new_item_name' => __( 'New Kategorien Name' ),
    'menu_name' => __( 'FAQ Kategorien' ),
  ); 	

// Now register the taxonomy

  register_taxonomy('Faq_topic',array('FAQ'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'topic' ),
  ));

}

 


function filter_post_type_link($link, $post)
{
    if ($post->post_type != 'FAQ')
        return $link;

    if ($cats = get_the_terms($post->ID, 'Faq_topic'))
        $link = str_replace('%Faq_topic%', array_pop($cats)->slug, $link);
    return $link;
}
add_filter('post_type_link', 'filter_post_type_link', 10, 2);

function my_custom_post_FAQ() {
  $labels = array(
    'name'               => _x( 'FAQs', 'post type general name' ),
    'singular_name'      => _x( 'FAQ', 'post type singular name' ),
    'add_new'            => _x( 'Erstellen', 'FAQ' ),
    'add_new_item'       => __( 'Erstellen FAQ' ),
    'edit_item'          => __( 'Edit FAQ' ),
    'new_item'           => __( 'Erstellen FAQ' ),
    'all_items'          => __( 'Alle FAQs' ),
    'view_item'          => __( 'View FAQ' ),
    'search_items'       => __( 'Search FAQ' ),
    'not_found'          => __( 'No FAQ found' ),
    'not_found_in_trash' => __( 'No FAQ found in the Trash' ), 
    'parent_item_colon'  => '',
    'parent' => __( 'Parent FAQ' ),
    'menu_name'          => 'FAQs',
    
    
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Add new question and manage frequently asked questions',
    'public'        => true,
    'menu_position' => 7,
    'supports'      => array( 'title', 'editor', 'thumbnail','author','excerpt','page-attributes'),
    'taxonomies' => array('Faq_topic'),
    'menu_icon'			 => 'dashicons-admin-customizer',
    'has_archive'   => true,
    'hierarchical'       => true,
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
	'orderby'          => 'menu_order',
	'order'            => 'ASC',
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
 $taxonomy = 'Faq_topic';
/*echo '<pre>';
print_r($the_query->posts);
echo '</pre>';*/
//$my_id = get_ID_by_slug('all-faq');
?>
	<div class="faq_show" id="faq">
			
			
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
					$tax=get_the_terms(get_the_ID(),$taxonomy);
				//	print_r($tax);
		?>
		

			<div class="faq_details">
			<a href="<?php echo get_permalink( get_page_by_path( 'all-faq' ) )?>" target="_self"><?php echo $tax[0]->name; ?></a>
			<!--<a href="javascript:void(0);">FAQ</a>-->
			<h3><?php the_title();?></h3>
			
			
			<?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                    the_post_thumbnail('thumbnail'); 
                  }
                  ?>
			
				  <p class="content">
				  <?php 
				  $content = get_the_content();
				  echo $content;
				  //the_content(); ?>
				 </p> 
				  
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
   
    


$taxonomy = 'Faq_topic';
$tax_terms = get_terms($taxonomy);
//print_r($tax_terms);
?>
<div class="row">
	<div class="xxlarge-9 xlarge-10 large-12 columns large-centered faq_container">
		<div class="row">
		<div class="large-4 columns">
			<ul class="faq_list faq_left_pane">
				<li><a href="javascript:void(0)" class="go_back"><i></i> Zurück</a></li>
			<?php
			$t=1;
			foreach ($tax_terms as $tax_term) {
				echo '<li>'.'<a  href="javascript:void(0);" sl-class="faq_'.$t++.'" title="' . sprintf( __( "View all posts in %s" ), $tax_term->name ) . '">' . $tax_term->name.'</a>'.'</li>';
			//echo '<li>' . '<a href="' . esc_attr(get_term_link($tax_term, $taxonomy)) . '" title="' . sprintf( __( "View all posts in %s" ), $tax_term->name ) . '" ' . '>' . $tax_term->name.'</a></li>';
			}
			?>
		</ul>
		</div>
		<div class="large-8 columns">
			<div class="faq_show faq_main_page">
			<?php 
			
			$v=0;
			
			foreach ($tax_terms as $tax_term) {
				
				$args = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'Faq_topic',
							'field' => 'slug',
							'terms' => $tax_term->slug
						)
					),
					'orderby'          => 'menu_order',
					'order'            => 'ASC',
					'post_type'        => 'faq',
					'post_status'      => 'publish',
					'suppress_filters' => true 
			);
			
			$the_query = new WP_Query( $args ); $v++;
			/*echo '<pre>';
			print_r($the_query->posts);
			echo '</pre>';*/
			?>
		<div class="topic">
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); $id = get_the_ID();
				
			?>
			
			
		<div class="faq_details faq_detail_wrapper <?php echo 'faq_'.$v;?>">
			
			<a class="text-uppercase" href="javascript:void(0);"><?php echo $tax_term->name;?></a>
			
			<h3 class="expander" Id="<?php the_ID();?>"><?php the_title();?></h3>
			<div class="content">
				  <p id="<?php the_ID();?>" >
				  <?php 
				  $content = get_the_content();
				  echo $content;
				  //the_content();
				  ?>
				 </p>
				<div class="welter_btn pr">

					<a class="btnext" href="<?php echo get_permalink(get_post_meta($id,'faq_button_link',true));?>" target="_self"><?php echo get_post_meta($id,'faq_button_text',true);?></a>

				</div>
			</div>		  
		</div> 
		<?php 
		endwhile;
		wp_reset_postdata();
			echo "</div>";
		}?>
		
			</div>
		</div>
	</div>
	</div>
</div>
<script>
	
	//$("h3.jqueryheading").next('div.xde').slideToggle("slow");
	
	//$("h3.jqueryheading").next('div.xde').slideToggle("slow");
	jQuery('.faq_details:first .content').slideDown();
	//jQuery('.faq_details:first ').addClass('active');
	jQuery('.faq_left_pane li a').click(function(){
		//e.preventDefault();
		jQuery('.faq_left_pane li').removeClass('active');
		jQuery(this).parents('li').addClass('active');
		var sl=jQuery(this).attr('sl-class');
		var target=jQuery('.'+sl+':first');
		jQuery('html,body').animate({
			scrollTop: target.offset().top
		}, 1000);
	});
	//jQuery('.content').not('.content:first)').slideUp();
	jQuery(document).ready(function($){

		jQuery('.topic ').each(function(){
			
			
		jQuery(this).find('.faq_details').first().addClass('active');
		jQuery(this).find('.faq_details:not(:first)').addClass('content-up');
		
		});
		

	});
		jQuery(document).ready(function($){
			jQuery(document).on('click','.go_back',function(){
					window.history.back();
				});
			jQuery('.topic ').each(function(){
				jQuery('.content-up').find('.content').slideUp();
			});
			
		
		jQuery('.expander').click(function(){
			jQuery(this).next('.content').slideToggle();
			
		});

	 	var distance = $('.faq_left_pane').offset().top,
		$window = $(window);

		$window.scroll(function() {
		    if ( $window.scrollTop() >= distance ) {
		        // Your div has reached the top
		        jQuery('.faq_left_pane').addClass('sticky-nav-faq');
		    }
		    else{
		    	jQuery('.faq_left_pane').removeClass('sticky-nav-faq');
		    }
		  //  console.log(jQuery('#site-footer').offset().top+ '      ' +$window.scrollTop());
		    if( $window.scrollTop()>=jQuery('#site-footer').offset().top-jQuery('.faq_list').height()-70)
		    {
		    	jQuery('.faq_left_pane').removeClass('sticky-nav-faq');
		    }
		});
		       
	});
</script>		
		
		
<?php
}
	


