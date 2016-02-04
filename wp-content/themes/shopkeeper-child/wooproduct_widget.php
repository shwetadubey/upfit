<?php
// Creating the widget 
class wpb_widget extends WP_Widget {
function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_widget', 

// Widget name will appear in UI
__('Woo Commerce Plan Widget', 'wpb_widget_domain'), 

// Widget description
array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), ) 
);

}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {

$catid=get_cat_ID('plan-pricing');
//echo $catid;
$post_para = array(
	//'posts_per_page'   => 4,
	'offset'           => 0,
	'orderby'          => 'date',
	'order'            => 'DESC',
	/*'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',*/
	'post_type'        => 'product',
	/*'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',*/
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
//$wooproduct_posts = get_posts( $post_para );

$wooproduct_posts = new WP_Query( $post_para );
$page = get_page_by_path( 'ernaehrungsplan_erstellen' );
$title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
//echo __( 'Hello, World!', 'wpb_widget_domain' );



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

?>
</ul>
</div>
<?php
echo $args['after_widget'];
}
	
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
