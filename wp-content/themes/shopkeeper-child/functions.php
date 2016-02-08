<?php
require_once('pdf_functions.php');
require_once('add_meta_boxes.php');
require_once('plan_listing.php');
require_once('show_plan_overview.php');
//require_once('why_upfit.php');
require_once('wooproduct_widget.php');
require_once('add_theme_options.php');
require_once('how_upfit_works.php');
require_once('add_custom_posts.php');
require_once('woo_product_shortcode.php');
include_once('inc/custom-styles/custom-styles.php'); // Load Custom Styles
/*
function add_css() {
	wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	echo get_stylesheet_uri();
}

add_action( 'wp_enqueue_style', 'add_css' );*/
     

/*add_filter('site_url',  'wpadmin_filter', 10, 3);
 function wpadmin_filter( $url, $path, $orig_scheme ) {
  
  $old  = array( "/(wp-admin)/");
  $admin_dir = WP_ADMIN_DIR;
  $new  = array($admin_dir);
  
  return preg_replace( $old, $new, $url, 1);
}*/
/*add_filter('auth_cookie_expiration', 'my_expiration_filter', 99, 3);
function my_expiration_filter($seconds, $user_id, $remember){

    //if "remember me" is checked;
    /*if ( $remember ) {
        //WP defaults to 2 weeks;
        $expiration = 14*24*60*60; //UPDATE HERE;
/*    } else {
        //WP defaults to 48 hrs/2 days;
        $expiration = 2*24*60*60; //UPDATE HERE;
    }

    //http://en.wikipedia.org/wiki/Year_2038_problem
    if ( PHP_INT_MAX - time() < $expiration ) {
        //Fix to a little bit earlier!
        $expiration =  PHP_INT_MAX - time() - 5;
    }

    //return $expiration;
}*/


add_action('wp_footer','placeholder_script');
function placeholder_script() {
	?>
<style>
.scrollToTop{
	width:100px; 
	height:130px;
	padding:10px; 
	text-align:center; 
	font-weight: bold;
	color: green;
	text-decoration: none;
	position:fixed;
	bottom:25px;
	right:25px;
	z-index:999;
	
}
.scrollToTop:hover{
	text-decoration:none;
}
</style>
 <a href="#" class="scrollToTop" style="display:none;">Scroll To Top</a>
<script type="text/javascript">	
	
	jQuery(document).ready(function(){
		jQuery('.scrollToTop').bind('touchmove', function(e) { 
			jQuery('.scrollToTop').css('display','block');
			if(jQuery(window).width()<767){
					jQuery('.scrollToTop').fadeIn();
				}
			//console.log($(this).scrollTop()); // Replace this with your code.
			//alert('touchmove');
		});
		jQuery(window).scroll(function(){
			//jQuery('.scrollToTop').css('display','block');
			if (jQuery(this).scrollTop() > 100) {
				if(jQuery(window).width()<767){
					jQuery('.scrollToTop').fadeIn();
				}
				
			}else {
				
				jQuery('.scrollToTop').removeClass('active');
				jQuery('.scrollToTop').fadeOut();
			}
		});
		jQuery('.scrollToTop').click(function(){
			jQuery(this).addClass('active');
			jQuery('html, body').animate({scrollTop : 0},1500);
			return false;
		});
	});
	jQuery('input').on('focus',function(){
		var $this = jQuery(this);
		jQuery(this).data('placeholder',jQuery(this).prop('placeholder'));
	//	jQuery(this).removeAttr('placeholder')
	}).on('blur',function(){
		//var $this = jQuery(this);
		jQuery(this).prop('placeholder',jQuery(this).data('placeholder'));
	});
	jQuery('textarea').focus(function($){
   jQuery(this).data('placeholder',jQuery(this).attr('placeholder'))
          .attr('placeholder','');
}).blur(function($){
   jQuery(this).attr('placeholder',jQuery(this).data('placeholder'));
});
</script>
	<?php 
	}
/* add scripts */	
function theme_name_scripts() {
	wp_enqueue_style( 'tipso', get_template_directory_uri().'-child/tipso/tipso.min.css' );
	wp_enqueue_style( 'layout', get_template_directory_uri().'-child/css/layout.css' );
	
	wp_enqueue_script( 'tipso-js', get_template_directory_uri() . '-child/tipso/tipso.min.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
function load_more_faq_post()
{
	$offset = $_POST["offset"];
    $limit = $_POST["ppp"];
	//echo $limit."AND".$offset;
	echo do_shortcode('[load_more_faq limit='.$limit.' offset='.$offset.']');
	
	
	wp_die();
}
add_action('wp_ajax_nopriv_load_more_faq_post', 'load_more_faq_post'); 
add_action('wp_ajax_load_more_faq_post', 'load_more_faq_post');
function test1(){
	
	global $wpdb;
	$res=$wpdb->get_results("select DISTINCT (synonym_of) as synonym_of  from up_foods");
	$t=0;
	foreach($res as $n){
		$ar=array();
		$ar['id']=$t;
		$ar['text']=$n->synonym_of ;
		$names[]= $ar;
		$t++;
	}
	//print_r("select DISTINCT LEFT(shopping_list_name,LOCATE(' ',shopping_list_name) - 1) as shopping_list_name  from ".$wpdb->prefix."foods");exit;
	 $path = wp_upload_dir();
	 if(!file_exists($path['basedir'] . '/foodies/')){mkdir($path['basedir'] . '/foodies/',0777, true);}
	  $uppath = $path['basedir'] . '/foodies/';
	file_put_contents($uppath.'foods.json',json_encode($names));
}
add_action( 'wp_ajax_get_food_list', 'get_food_list' );
add_action( 'wp_ajax_nopriv_get_food_list', 'get_food_list' );
function get_food_list(){
	echo 'test';
}

add_action( 'after_setup_theme', 'upfit_post_image_size_setup' );
function upfit_post_image_size_setup() {
    add_image_size( 'custom-thumbnail', 275,250,false ); // 300 pixels wide (and unlimited height)  
}

function dotooltip($atts){

	$tooltip=get_post($atts['id']);
	echo $tooltip->post_content;

}
add_shortcode('dotooltip', 'dotooltip');

/*function wpgyan_widgets_init() {

	register_sidebar( array(
		'name' => 'Footer Sidebar',
		'id' => 'Footer_sidebar',
		'before_widget' => '<div id="wpgyan-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'wpgyan_widgets_init' );*/
/*------------------------------------------------------------*/
function form_data_to_cart_script(){
?>
<script>
 var ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';
</script>
<?php
}
add_action('wp_footer','form_data_to_cart_script');

add_action('wp_ajax_wdm_add_user_custom_data_options_callback', 'wdm_add_user_custom_data_options_callback');
add_action('wp_ajax_nopriv_wdm_add_user_custom_data_options_callback', 'wdm_add_user_custom_data_options_callback');

function wdm_add_user_custom_data_options_callback()
{
      //Custom data - Sent Via AJAX post method
      $product_id = $_POST['id']; //This is product ID
   
     
      $user_custom_data_values =  $_POST['user_data']; //This is User custom value sent via AJAX
      session_start();
      $_SESSION['wdm_user_custom_data'] = $user_custom_data_values;
     // print_r($_SESSION['wdm_user_custom_data']);
    //  exit;
      wp_die();
}
add_filter('woocommerce_add_cart_item_data','wdm_add_item_data',1,2);
    function wdm_add_item_data($cart_item_data,$product_id)
    {
        /*Here, We are adding item in WooCommerce session with, wdm_user_custom_data_value name*/
        global $woocommerce;
        session_start();  
        $woocommerce->cart->empty_cart();  
       // print_r($_SESSION['wdm_user_custom_data']); 
       // exit;
        if (isset($_SESSION['wdm_user_custom_data'])) {
			
            $option = $_SESSION['wdm_user_custom_data'];       
            $new_value = array('wdm_user_custom_data_value' => $option);
        }
        if(empty($option))
            return $cart_item_data;
        else
        {    
            if(empty($cart_item_data))
                return $new_value;
            else
                return array_merge($cart_item_data,$new_value);
        }
       
        unset($_SESSION['wdm_user_custom_data']); 
        //Unset our custom session variable, as it is no longer needed.
    }
add_filter('woocommerce_get_cart_item_from_session', 'wdm_get_cart_items_from_session', 1, 3 );

    function wdm_get_cart_items_from_session($item,$values,$key)
    {
        if (array_key_exists( 'wdm_user_custom_data_value', $values ) )
        {
        $item['wdm_user_custom_data_value'] = $values['wdm_user_custom_data_value'];
        }       
        return $item;
    }
add_filter('woocommerce_checkout_cart_item_quantity','wdm_add_user_custom_option_from_session_into_cart',1,3);  
add_filter('woocommerce_cart_item_price','wdm_add_user_custom_option_from_session_into_cart',1,3);
if(!function_exists('wdm_add_user_custom_option_from_session_into_cart'))
{
 function wdm_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key )
    {
        /*code to add custom data on Cart & checkout Page*/ 
        if(count($values['wdm_user_custom_data_value']) > 0)
        {
            $return_string = $product_name . "</a><dl class='variation'>";
            $return_string .= "<table class='wdm_options_table' id='" . $values['product_id'] . "'>";
          //  $return_string .= "<tr><td>" . print_r($values['wdm_user_custom_data_value']) . "</td></tr>";
            $return_string .= "</table></dl>"; 
            //return $return_string;
        }
        else
        {
            return $product_name;
        }
    }
}
add_action('woocommerce_add_order_item_meta','wdm_add_values_to_order_item_meta',1,2);
if(!function_exists('wdm_add_values_to_order_item_meta'))
{
  function wdm_add_values_to_order_item_meta($item_id, $values)
  {
        global $woocommerce,$wpdb;
        $user_custom_values = $values['wdm_user_custom_data_value'];
        $pdf_processing_status=0;
        $pdf_processing_description='Waiting for execution of cron-job';
        if(!empty($user_custom_values))
        {
				woocommerce_add_order_item_meta($item_id,'wdm_user_custom_data',$user_custom_values);
				woocommerce_add_order_item_meta($item_id,'pdf_processing_status',$pdf_processing_status);    
				woocommerce_add_order_item_meta($item_id,'pdf_processing_description',$pdf_processing_description);    
        }
        
        
  }
}

/*------------------Tooltip Json----------------*/
function tooltipjson(){
   // echo 'dsds';exit;
    $args=array(
  'post_type' => 'tooltip',
  'post_status' => 'publish',
  'posts_per_page'=>-1);
    $my_query = null;
    $my_query = new WP_Query($args);
    $a=json_decode(json_encode($my_query->posts),true);
    $av=array_map("addmeta",$a);
    usort($av, function($a, $b) {  return $a['tooltip_id'] - $b['tooltip_id'];});
    $tooltip_msg = wp_list_pluck( $av, 'post_content', 'tooltip_id' ); 
    //print_r($tooltip_msg);
    $path = wp_upload_dir();
     if(!file_exists($path['basedir'] . '/foodies')){mkdir($path['basedir'] . '/foodies/',0777, true);}
    $uppath = $path['basedir'] . '/foodies/';
  file_put_contents($uppath.'msg.json',json_encode($tooltip_msg));

}
add_action( 'save_post_tooltip', 'tooltipjson' );
add_action( 'init', 'tooltipjson' );

function addmeta($v)
{
$v['tooltip_id']=$key_1_value = get_post_meta( $v['ID'], 'tooltip_id', true );
return $v;
}

/*----------------Chekout Custom Fields-----------------*/
add_filter( 'woocommerce_checkout_fields' , 'custom_wc_checkout_fields' );
function custom_wc_checkout_fields( $fields ) {
	//-----Salutation Heading
		
	$fields['billing']['billing_title']['options'] = array('Frau','Herr');
	$fields['billing']['billing_title']['default'] = 'Frau';
	$fields['billing']['billing_title']['label'] = 'Anrede';
	$fields['billing']['billing_title']['required'] = false;
	$fields['billing']['billing_title']['class'] = array('form-row-first','checkout_select');
	//----Company
	$fields['billing']['billing_company']['label'] = 'Firmenname';
	$fields['billing']['billing_company']['placeholder'] = 'z.B. Musterfirma';
	$fields['billing']['billing_company']['class'] = array('checkout_input_small','form-row-last');
	//----First Name
	$fields['billing']['billing_first_name']['label'] = 'Vorname';
	$fields['billing']['billing_first_name']['placeholder'] = 'z.B. Max';
	$fields['billing']['billing_first_name']['class'] = array('form-row-first','checkout_input');
	//----Last Name
	$fields['billing']['billing_last_name']['label'] = 'Nachname';
	$fields['billing']['billing_last_name']['placeholder'] = 'z.B. Mustermann';
	$fields['billing']['billing_last_name']['class'] = array('form-row-last','checkout_input');
	//-----Email
	$fields['billing']['billing_email']['label'] = 'Email';
	$fields['billing']['billing_email']['placeholder'] = 'z.B. max@mustermann.de';
	$fields['billing']['billing_email']['class'] =array('form-row-first','checkout_input');
	//----Phone
	$fields['billing']['billing_phone']['label'] = 'Telefon';
	$fields['billing']['billing_phone']['required'] = false;
	$fields['billing']['billing_phone']['class'] = array('form-row-last','checkout_input');
	//----country
	$fields['billing']['billing_country']['label'] = 'Land';
	$fields['billing']['billing_country']['class'] = array('checkout_input');
	//----Address
	$fields['billing']['billing_address_1']['label'] = 'Adresse';
	$fields['billing']['billing_address_1']['placeholder'] = 'z.B. Musterstraße 9';
	$fields['billing']['billing_address_1']['class'] = array('checkout_input');
	$fields['billing']['billing_address_2']['placeholder'] = 'z.B. Adresszusatz';
	$fields['billing']['billing_address_2']['class'] = array('checkout_input');
	//----Order
	$fields['order']['order_comments']['label'] = false;
	$fields['order']['order_comments']['placeholder'] = 'Zusätzliche Anmerkungen zur Bestellung';
	//$fields['billing']['order_comments']['class'] = array('salutation');
	
 $order = array(
		"billing_title",
		"billing_company", 
        "billing_first_name", 
        "billing_last_name", 
        "billing_email", 
        "billing_phone",
        "billing_country", 
        "billing_address_1", 
        "billing_address_2", 
        "billing_postcode", 
		"billing_city",
    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields["billing"][$field];
    }
	//print_r($fields['billing']['billing_city']);
    $fields["billing"] = $ordered_fields;
   
return $fields;
}
add_filter( 'woocommerce_default_address_fields' , 'wpse_120741_wc_def_state_label' );
function wpse_120741_wc_def_state_label( $address_fields ) {
	//------------City-----------------//
     $address_fields['city']['label'] = 'Ort';
     $address_fields['city']['placeholder'] = 'z.B. Hamburg';
     $address_fields['city']['class'] =  array('form-row-last','checkout_input');
     $address_fields['city']['clear'] = false;
     //------postcode------
	  $address_fields['postcode']['label'] = 'Postleitzahl';
	  $address_fields['postcode']['placeholder'] = 'z.B. 22303	';
	  $address_fields['postcode']['class'] =  array('form-row-first','checkout_input');
	  $address_fields['postcode']['clear'] = false;
     return $address_fields;
}
add_action( 'woocommerce_after_order_notes', 'add_required_field_label' );
function add_required_field_label(){
	echo '<span class="required_labelnew">* Pflichtfelder</span>'; 
}
add_filter( 'woocommerce_order_button_text', 'change_name_of_place_order');
function change_name_of_place_order(){
	
	return strtoupper("JETZT KAUFEn");
}

//add_action( 'template_redirect', 'global_posts_redirect' );

function global_posts_redirect() {
    global $wp_query;

    if ( $wp_query->is_404() && !is_main_site() ) {
        //switch to primary site for checking post/page
        switch_to_blog( 1 );
		
        $slug = $wp_query->query_vars[ 'name' ];
		echo $slug;
		exit;
        $args = array(
            'name'           => $slug,
            'post_type'      => 'any',
            'post_status'    => 'publish'
        );

        $my_posts = get_posts( $args );

        if ( $my_posts ) {
            //if post/page exists - change header code to 200
            status_header( 200 );

            $post        = $my_posts[ 0 ];
            $post_type   = get_post_type( $post );

            //emulate wp_query
            $wp_query->queried_object_id = $post->post_id;
            if ( $post_type == 'page' ) {
                $wp_query->query[ 'pagename' ]       = $post->name;
                unset( $wp_query->query[ 'name' ] );
                $wp_query->query_vars[ 'pagename' ]  = $post->name;
            }
            $wp_query->query_vars[ 'name' ]  = $post->name;
            $wp_query->queried_object        = $post;
            $wp_query->post_count            = 1;
            $wp_query->current_post          = -1;
            $wp_query->posts                 = array( $post );
            $wp_query->post                  = $post;
            $wp_query->found_posts           = 1;
            if ( $post_type == 'page' ) {
                $wp_query->is_page = 1;
            } else if ( $post_type == 'post' ) {
                $wp_query->is_single = 1;
            }
            $wp_query->is_404        = false;
            $wp_query->is_singular   = 1;

            restore_current_blog();
        }
    }
}
// add the filter for questionnaire tab
/*add_filter( 'woocommerce_admin_reports', 'filter_woocommerce_admin_reports', 10, 1 );
// define the woocommerce_admin_reports callback
function filter_woocommerce_admin_reports( $reports ) 
{
	$reports['questionnaire'] = array(
				'title'  => __( 'Questionnaire', 'woocommerce' ),
				'reports' => array(
					"questionnaire_by_code" => array(
						'title'       => __( 'Questionnaire by name', 'woocommerce' ),
						'description' => '',
						'hide_title'  => true,
						'callback'    => array( __CLASS__, 'get_report' )
					),
					"questionnaire_by_date" => array(
						'title'       => __( 'Questionnaire by date', 'woocommerce' ),
						'description' => '',
						'hide_title'  => true,
						'callback'    => array( __CLASS__, 'get_report' )
					),
				)
			);
    // make filter magic happen here...

    return $reports;
};*/




add_filter( 'woocommerce_cart_totals_coupon_label', 'change_coupon_label' );
function change_coupon_label(){
	 $coupon= WC()->cart->get_coupons();
   return $coupon[array_keys($coupon)[0]]->coupon_amount.'&#37; Rabatt';
}
add_filter( 'woocommerce_cart_totals_order_total_html', 'wc_cart_totals_order_total_html_update', 10, 1 );	
function wc_cart_totals_order_total_html_update (){
  $value = '<strong>' . WC()->cart->get_total() . '</strong> ';

  return $value;
}


add_filter( 'gettext', 'custom_paypal_button_text', 20, 3 );
function custom_paypal_button_text( $translated_text, $text, $domain ) {
	switch ( $translated_text ) {
		case 'Proceed to PayPal' :
			$translated_text = __( 'JETZT KAUFEn', 'woocommerce' );
			break;
	}
	return $translated_text;
}

function arrange_tooltip_id_column($defaults) {  

    $new = array();
    $tags = $defaults['tooltip_id'];  // save the tags column
    unset($defaults['tooltip_id']);   // remove it from the columns list
	
    foreach($defaults as $key=>$value) {
        if($key=='title') {  // when we find the date column
           $new['tooltip_id'] = $tags;  // put the tags column before it
        }    
        $new[$key]=$value;
    }  

    return $new;  
} 
add_filter('manage_tooltip_posts_columns', 'arrange_tooltip_id_column');  

add_shortcode('tooltip_id','get_tooltip_content_using_id');
function get_tooltip_content_using_id($atts){
	
	$tooltip=get_posts(array('post_type'=>'tooltip',
							'posts_per_page'    => 1,
							'order'     => 'ASC',
							 'meta_key'=>'tooltip_id',
							 'meta_value'=>$atts['id'],
							)
					);
				//	print_r($tooltip);
	return $tooltip[0]->post_content;	
}


function price_column_register( $columns ) {
    $columns['tooltip_id'] = __( 'Tooltip ID', 'tooltip' );

    return $columns;
}
add_filter( 'manage_edit-tooltip_columns', 'price_column_register' );
function price_column_display( $column_name, $post_id ) {
    if ( 'tooltip_id' != $column_name )
        return;

    $price = get_post_meta($post_id, 'tooltip_id', true);
    if ( !$price )
        $price = '<em>' . __( 'undefined', 'tooltip' ) . '</em>';

    echo $price;
}
add_action( 'manage_tooltip_posts_custom_column', 'price_column_display', 10, 2 );
function price_column_register_sortable( $columns ) {
    $columns['tooltip_id'] = 'tooltip_id';

    return $columns;
}
add_filter( 'manage_edit-tooltip_sortable_columns', 'price_column_register_sortable' );
function price_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'tooltip_id' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'tooltip_id',
            'orderby' => 'meta_value_num'
        ) );
    }
 
    return $vars;
}
add_filter( 'request', 'price_column_orderby' );

add_action("template_redirect", 'redirection_function');
function redirection_function(){
    global $woocommerce;
     $order_id    = absint( $_GET['order_id'] );
		$order   = wc_get_order( $order_id );
		//print_r($order );
    if( is_cart() && WC()->cart->cart_contents_count == 0){
        wp_safe_redirect( home_url());
    }
    else if( $order->post_status == 'wc-cancelled' && $_GET['cancel_order']=='true') {
		echo 'true';
		wp_safe_redirect($woocommerce->cart->get_checkout_url().'order-cancelled/?cancel_order=true');
		}
}
add_action( 'woocommerce_cancelled_order','redirect_after_cancelled_order' );
function redirect_after_cancelled_order(){
	  wp_safe_redirect( home_url());
}

function remove_product_from_admin_menu() {
     if(! is_admin()){
		  remove_menu_page( 'edit.php?post_type=product' );  
		 }
     
}
add_action( 'admin_menu', 'remove_product_from_admin_menu' );

function custom_menu_order($menu_ord) {
       if (!$menu_ord) return true;
      //return array('index.php', 'edit.php','edit-comments.php');
      return array('index.php', 'admin.php','edit.php','profile.php');
}
add_filter('custom_menu_order', 'custom_menu_order');
add_filter('menu_order', 'custom_menu_order');


//add_filter( 'woocommerce_email_customer_details_fields',  'customer_details' ,1,3);
function customer_details( $order, $sent_to_admin = false, $plain_text = false ) {
		 global $woocommerce;
		
		$order_id= $order->id;
		$fields['billing_email'] = array(
				'label' => __( '', 'woocommerce' ),
				'value' =>  wptexturize( get_post_meta($order_id,'_billing_email',true) )
			);
	   
			$fields['billing_phone'] = array(
				'label' => __( 'Telefon:', 'woocommerce' ),
				'value' => wptexturize(get_post_meta($order_id,'_billing_phone',true))
			);
		//	print_r($order);
			exit;
		return $fields;

}
add_filter( 'woocommerce_email_custom_details_header', 'change_email_custom_details_header');
function change_email_custom_details_header(){
	return 'Deine Details';
}

function enter_title_here_filter($label){
 $screen = get_current_screen();

    if($screen->post_type == 'fatburner_drink')
        $label= 'Enter Drink&#39;s Name';
 
    return $label;
}
add_filter('enter_title_here', 'enter_title_here_filter');

add_filter('default_content','smallenvelop_default_content',10,2);
function smallenvelop_default_content( $content, $post ){
$screen = get_current_screen();

    if($screen->post_type == 'fatburner_drink')
 $content ='Enter Drink&#39;s Preparation Method Here...';
return $content;
}



/*-------------------------------------*/
function faq_column_register_faq( $columns ) {
    $columns['faq_order'] = __( 'FAQ Order', 'faq' );

    return $columns;
}
add_filter( 'manage_edit-faq_columns', 'faq_column_register_faq' );
function faq_column_display_faq( $column_name, $post_id ) {
    if ( 'faq_order' != $column_name )
        return;

    //$price = get_post_meta($post_id, 'menu_order', true);
    $thispost = get_post($post_id);
$price = $thispost->menu_order;
    if ( !$price )
        $price = '<em>' . __( '0', 'faq' ) . '</em>';

    echo $price;
}
add_action( 'manage_faq_posts_custom_column', 'faq_column_display_faq', 10, 2 );
function faq_column_register_sortable_faq( $columns ) {
    $columns['faq_order'] = 'menu_order';
    return $columns;
}
add_filter( 'manage_edit-faq_sortable_columns', 'faq_column_register_sortable_faq' );
function faq_column_orderby_faq( $vars ) {
    if ( isset( $vars['orderby'] ) && 'faq_order' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'menu_order',
            'orderby' => 'meta_value_num'
        ) );
    }
 
    return $vars;
}
add_filter( 'request', 'faq_column_orderby_faq' );

function arrange_faq_order_column($defaults) {  

    $new = array();
    $tags = $defaults['faq_order'];  // save the tags column
    unset($defaults['faq_order']);   // remove it from the columns list
	
    foreach($defaults as $key=>$value) {
        if($key=='title') {  // when we find the date column
           $new['faq_order'] = $tags;  // put the tags column before it
        }    
        $new[$key]=$value;
    }  

    return $new;  
} 
add_filter('manage_faq_posts_columns', 'arrange_faq_order_column');  

//add_action( 'woocommerce_email_order_meta_fields', 'order_meta_fields' , 10, 1 );
 function order_meta_fields( $sent_to_admin = false ) {
		$fields = array();

		if ( $order->customer_note ) {
			$fields['customer_note'] = array(
				'label' => __( 'Notiz', 'woocommerce' ),
				'value' => wptexturize( $order->customer_note )
			);
		}
}

add_action('wp_ajax_check_plan_validation', 'check_plan_validation');
add_action( 'wp_ajax_nopriv_check_plan_validation', 'check_plan_validation' );
function check_plan_validation() {
	global $wpdb;
	//$prefix=$wpdb->prefix;
	$prefix="up_";
	//echo "in action";
	$type= $_POST['nutrition_type'];
	$allergies=$_POST['allergies'];
	
	$nuts=$_POST['nuts'];
	$fruit=$_POST['fruit'];
	$exclude=$_POST['exclude'];
	$preparation_time=$_POST['preparation_time'];
	//echo $type;

	$nutrition_types_array = array(
    'nospecial' => '',
    'pescetarian' => 'cw_pescetarian',
    'vegetarian' => 'cw_vegetarian',
    'flexitarian' => 'cw_flexitarian',
    'vegan' => 'cw_vegan',
    'paleo' => 'cw_paleo');

	$intolerance_array = array('lactose' => 'cw_lactose_intolerance',
    'fructose' => 'cw_fructose_intolerance',
    'histamine' => 'cw_histamine_intolerance',
    'gluten' => 'cw_gluten_intolerance',
    'glutamat' => 'cw_glutamat_intolerance',
    'sucrose' => 'cw_sucrose_intolerance',
    'peanut' => 'cw_peanut_intolerance',
    'almond' => 'cw_almond_intolerance',
    'hazelnut' => 'cw_hazelnut_intolerance',
    'walnut' => 'cw_walnut_intolerance',
    'cashew' => 'cw_cashew_intolerance',
    'pecan_nut' => 'cw_pecan_nut_intolerance',
    'brazil_nut' => 'cw_brazil_nut_intolerance',
    'pistachio' => 'cw_pistachio_intolerance',
    'banana' => 'cw_banana_intolerance',
    'avocade' => 'cw_avocade_intolerance',
    'apple' => 'cw_apple_intolerance',
    'kiwi' => 'cw_kiwi_fruit_intolerance',
    'melon' => 'cw_melon_intolerance',
    'papaya' => 'cw_papaya_intolerance',
    'peach' => 'cw_peach_intolerance',
    'strawberry' => 'cw_strawberry_intolerance');
	
	/*echo "type :".$type."</br>";
	echo "allergies :".$allergies."</br>";
	echo "nuts :".$nuts."</br>";
	echo "fruit :".$fruit."</br>";
	echo "exclude :".$exclude."</br>";*/
	
	$available_time_array = array('little' => ' AND mins.preparation_time <= 45 ', 'normal' => '', 'much' => '');

$available_time_condi = $available_time_array[$preparation_time];
	
	$nutrition_type = explode(',', $type);
	
	$alergies1 = $nuts1 = $fruit1 = array();
	if (!empty($allergies)) {
        $alergies1 = explode(',', $allergies);
	}
	if (!empty($nuts)) {
			$nuts1 = explode(',', $nuts);
	}
	
	$alergies_nuts = array_merge($alergies1, $nuts1);
	if (!empty($fruit)) {
			$fruit1 = explode(',', $fruit);
	}
	$intolerance = array_merge($alergies_nuts, $fruit1);
	
	$res_nut_typeval = $res_nut_typeval1 = $nut_type_cond = $nut_type_cond1 = $f1nut_type_cond = $f1nut_type_cond1 = '';
	if (!empty($nutrition_type)) {

        foreach ($nutrition_type as $n) {
            if ($n != 'nospecial' && $n != 'flexitarian') {
                $val = $nutrition_types_array[$n];
                $nut_typeval[] = 'f.' . $val . ' <> 0 ';
                $nut_typeval1[] = 'f1.' . $val . ' <> 0 ';
            }
        }

        if (!empty($nut_typeval)) {
                $res_nut_typeval = implode('AND ', $nut_typeval);
                $res_nut_typeval1 = implode('AND ', $nut_typeval1);

                $res_nut_typeval = " AND (" . $res_nut_typeval . ")";
                $res_nut_typeval1 = " AND (" . $res_nut_typeval1 . ")";
        }
	}	
	//echo "intolerance: <pre>";print_r($intolerance);
	$res_int_type = $res_int_type1 = $res_int_typeval = $res_int_typeval1 = '';
	if (!empty($intolerance)) {
		//echo "not empty";
        foreach ($intolerance as $i) {
				//echo "i ".$i;
                $intal = $intolerance_array[$i];
                //echo $intal;
                $int_typeval[] = 'f.' . $intal . ' <> 0 ';
                $int_typeval1[] = 'f1.' . $intal . ' <> 0 ';
                
               // echo "<pre>";print_r($int_typeval);
        }

        if (!empty($int_typeval)) {
				
                $res_int_typeval = implode('AND ', $int_typeval);
                $res_int_typeval1 = implode('AND ', $int_typeval1);

				//echo "</br> intolerance".$res_int_typeval;
                $res_int_typeval = " AND (" . $res_int_typeval . ")";
                $res_int_typeval1 = " AND (" . $res_int_typeval1 . ")";
                
                //echo "</br> intolerance".$res_int_typeval;
        }

        //$select_field_int_typeval = implode(',', $res_int_type);
        //$select_field_int_typeval1 = implode(',', $res_int_type1);
}
$synonyms_exclude = $exclude;

$query1 = "SELECT meal_id, COUNT( * ) AS total_ingredient
FROM " . $prefix . "meal_ingredients
GROUP BY meal_id";

$res1 = $wpdb->get_results($query1, OBJECT_K);

$synonyms_que = $synonyms_que1 = '';
if (!empty($nutrion_plan_detail['exclude'])) {
    $synonyms_que = ' AND NOT FIND_IN_SET( f.synonym_of,  "' . $nutrion_plan_detail['exclude'] . '" ) ';
    $synonyms_que1 = ' AND (NOT FIND_IN_SET(f1.synonym_of, "' . $nutrion_plan_detail['exclude'] . '") OR NOT FIND_IN_SET(f.synonym_of, "' . $nutrion_plan_detail['exclude'] . '"))';
}

/* check main ingeredient compatible */
$query3 = "select um.meal_id as meal_id,group_concat(replace(um.name,',','')) as ing_names,group_concat(f.id) as compatible_ingredients,count(*) as main_count from " . $prefix . "meal_ingredients um, " . $prefix . "foods f "
        . ", " . $prefix . "meal_instructions mins where um.name=f.name  " . $res_nut_typeval . $res_int_typeval . $synonyms_que . " AND mins.meal_id = um.meal_id " . $available_time_condi . "
group by um.meal_id";

//echo $query3 . "<br><br>";
$res3 = $wpdb->get_results($query3, OBJECT_K);
//echo "<pre>";print_r($res3);
/* exchangable ingeredient check */

$q2= "SELECT mi.meal_id ,replace(mi.NAME,',','') AS ingredient_name,replace(f1.exchangeable_with, ' ','') as exchangeable_with,mi.is_ommitable, group_concat(f.name) AS compatible_food_name, group_concat(f.nid) AS compatible_food_id ".
" FROM up_meal_ingredients mi INNER JOIN up_foods f1 ON mi.name = f1.name LEFT JOIN up_foods f ON FIND_IN_SET(f.nid, replace(f1.exchangeable_with, ' ','')) ".
" LEFT JOIN up_meal_instructions mins on mi.id = mins.meal_id WHERE (mi.is_ommitable = 1 or ((f1.exchangeable_with is not null and f1.exchangeable_with <> '' and f1.exchangeable_with <> '0') "
.$available_time_condi.$synonyms_que1.$res_nut_typeval.$res_int_typeval.")) GROUP BY mi.meal_id,mi.NAME";

//echo $q2."<br><br>";
$res2 = $wpdb->get_results($q2);
//echo "<pre>";print_r($res2);

$count=$wpdb->get_var("select count(id) from ".$prefix."order_meals where order_id=".$order_id);
if($count!=0){
	$query_del="DELETE from ".$prefix."order_meals where order_id=".$order_id;
//	$wpdb->query($query_del);
}
$res_meals = $exchangable_foods = $prioritise_meals =$up_order_meals = array();
if (!empty($res3)) {
    foreach ($res1 as $k1 => $r1) {
        $r3 = $res3[$r1->meal_id];
        if (!is_null($r3) && !empty($r3)) {
            if ($r1->total_ingredient > $r3->main_count) {
                $temp = 0;
                $temp_meal_id="";
                foreach ($res2 as $r) {
                    if ($r->meal_id == $r1->meal_id) {
            			if(!in_array($r->ingredient_name, explode(',',$r3->ing_names))){
            				$exchangables = explode(',',$r->exchangeable_with);
            				if(!empty($exchangables) && $r->exchangeable_with != '0' && $exchangables[0] != '') {
            					foreach($exchangables as $ei) {
            						if(!empty($r->compatible_food_id)) {
            						    $compatible = explode(',',$r->compatible_food_id);
            							if(in_array($ei, $compatible)){
            							    //$ei = $wpdb->get_var("select id from up_foods where name='".$ename."' LIMIT 1;");
                                            //if (!is_null($ei) && !empty($ei)) {
                							    $temp++;
                								$temp_meal_id .= ",".$ei;
                								break;
                                            //}
            							}
            						}
            					}
            				} else if($r->is_ommitable) {
							    $temp++;
            				}
            			}
                    }
                }

                $final_count = $r3->main_count + $temp;

				//echo "final count:".$final_count."temp:".$temp."</br>";
                if ($final_count == $r1->total_ingredient) {
                    $res_meals[] = $r1->meal_id;
                    $up_order_meals['order_id']=$order_id;
            		$up_order_meals['meal_id']=$r3->meal_id;
            		$up_order_meals['ingredient_ids']=$r3->compatible_ingredients.$temp_meal_id;
            		$count=$wpdb->get_var("select count(id) from up_order_meals where order_id=".$order_id." AND meal_id=".$up_order_meals['meal_id']);
            		if($count==0){
            			$query_insert="insert into up_order_meals (order_id,meal_id,ingredient_ids,exchangble) values(".$order_id.",".$up_order_meals['meal_id'].",'".$up_order_meals['ingredient_ids']."',1)";
            		//	$wpdb->query($query_insert);
            		}
                }
            } else if ($r1->total_ingredient <= $r3->main_count) {
                $final_count = $r3->main_count;
                $prioritise_meals[] = $r1->meal_id;
                $up_order_meals['order_id']=$order_id;
                $up_order_meals['meal_id']=$r3->meal_id;
                $up_order_meals['ingredient_ids']=$r3->compatible_ingredients;

              //  $count=$wpdb->get_var("select count(id) from up_order_meals where order_id=".$order_id." AND meal_id=".$up_order_meals['meal_id']);

                if($count==0){
					$query_insert="insert into up_order_meals (order_id,meal_id,ingredient_ids,exchangble) values(".$order_id.",".$up_order_meals['meal_id'].",'".$up_order_meals['ingredient_ids']."',0)";
				//	$wpdb->query($query_insert);
                }
            }
        }
    }
}

$final_meal = array_merge($prioritise_meals, $res_meals);
/*echo '<br/>count'.count($final_meal);
$final_meal = array_merge($prioritise_meals, $res_meals);*/
echo count($final_meal);
$final_meal = implode(',', $final_meal);
//echo "final meals:".$final_meal;
//echo "count".count($final_meal);
wp_die();
}
//add_action( 'woocommerce_review_order_after_payment', 'woocommerce_gzd_template_checkout_legal', wc_gzd_get_hook_priority( 'checkout_legal' ) );
function woocommerce_gzd_template_checkout_legal(){
	echo '<p class="form-row legal terms">' . ( get_option( 'woocommerce_gzd_display_checkout_legal_no_checkbox' ) == 'no' ? '<span class="checkbox-custom"><input type="checkbox" class="input-checkbox" name="legal" id="legal" /><span class="checkout_checkbox"></span></span><label class="checkbox" for="legal">' : '' ) . ' ' . wc_gzd_get_legal_text() . '</label></p>';
	$checkbox_label=get_option('mc4wp_lite_checkbox');
	if($checkbox_label['precheck']==1){
		$checked='checked="checked"';
		}
		else{
			$checked='';
			}
	echo '<p class="form-row form-row " id="_mc4wp_subscribe_woocommerce_checkout_field"><span class="checkbox-custom">
			<input type="hidden" name="mc4wp-subscribe" value="1" />			
			<input type="checkbox" class="input-checkbox " name="mc4wp-subscribe" id="mc4wp-subscribe" '.$checked.'><span class="checkout_checkbox"></span></span><label class="checkbox ">'.$checkbox_label['label'].'</label></p>';
}

/*add_action('init','possibly_redirect');

function possibly_redirect(){
 global $pagenow;
 if( 'wp-login.php' == $pagenow ) {
  wp_redirect('partnerlogin');
  exit();
 }
}*/

function tipps_column_register( $columns ) {
    $columns['tipps_tricks_order'] = __( 'Tipps & Tricks Order', 'tipps_tricks' );

    return $columns;
}
add_filter( 'manage_edit-tipps_tricks_columns', 'tipps_column_register' );
function tipps_column_display( $column_name, $post_id ) {
    if ( 'tipps_tricks_order' != $column_name )
        return;

    $thispost = get_post($post_id);
$price = $thispost->menu_order;
    if ( !$price )
        $price = '<em>' . __( '0', 'tipps_tricks' ) . '</em>';

    echo $price;
}
add_action( 'manage_tipps_tricks_posts_custom_column', 'tipps_column_display', 10, 2 );
function tipps_column_register_sortable( $columns ) {
    $columns['tipps_tricks_order'] = 'menu_order';

    return $columns;
}
add_filter( 'manage_edit-tipps_tricks_sortable_columns', 'tipps_column_register_sortable' );
function tipps_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'tipps_tricks_order' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'menu_order',
            'orderby' => 'meta_value_num'
        ) );
    }
 
    return $vars;
}
add_filter( 'request', 'tipps_column_orderby' );
function arrange_tipps_tricks_order_column($defaults) {  

    $new = array();
    $tags = $defaults['tipps_tricks_order'];  // save the tags column
    unset($defaults['tipps_tricks_order']);   // remove it from the columns list
	
    foreach($defaults as $key=>$value) {
        if($key=='title') {  // when we find the date column
           $new['tipps_tricks_order'] = $tags;  // put the tags column before it
        }    
        $new[$key]=$value;
    }  

    return $new;  
} 
add_filter('manage_tipps_tricks_posts_columns', 'arrange_tipps_tricks_order_column');  

//add_action( 'woocommerce_thankyou', 'bryce_wc_autocomplete_order' );
function bryce_wc_autocomplete_order( $order_id ) {
	
	// Only continue if have $order_id
	if ( ! $order_id ) {
		return;
	}
    
    	// Get order
    	$order = wc_get_order( $order_id );
    	
    	// Update order to completed status
    	$order->update_status( 'processing' );
    	
}
add_filter( 'woocommerce_payment_complete_order_status', 'wc_order_processing' );
function wc_order_processing( $status, $order_id ) {
    return 'processing';
}
function mysite_woocommerce_order_status_completed( $order_id ) {
	global $wpdb;
	$sql1="update ".$wpdb->prefix."woocommerce_order_itemmeta as itemmeta join ".$wpdb->prefix."woocommerce_order_items as items on itemmeta.order_item_id=items.order_item_id set itemmeta.meta_value=4 WHERE itemmeta.meta_key='pdf_processing_status' And items.order_id=".$order_id." AND items.order_item_type='line_item'";
	//echo $sql1;
	
	$pdf_processing_description='Mail is successfully sent to user';
	$sql2="update ".$wpdb->prefix."woocommerce_order_itemmeta as itemmeta join ".$wpdb->prefix."woocommerce_order_items as items on itemmeta.order_item_id=items.order_item_id set itemmeta.meta_value='".$pdf_processing_description."' WHERE itemmeta.meta_key='pdf_processing_description' And items.order_id=".$order_id." AND items.order_item_type='line_item'";
	
	$result_order=$wpdb->get_results($sql1);
	$result_order=$wpdb->get_results($sql2);
}
add_action( 'woocommerce_order_status_completed','mysite_woocommerce_order_status_completed' );


function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}
add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

add_filter( 'woocommerce_coupon_message', 'filter_woocommerce_coupon_success_message', 10, 3 );
function filter_woocommerce_coupon_success_message( $msg, $msg_code, $instance )
{
  //  $msg = "Yeah thanks!";
  /*
	const WC_COUPON_SUCCESS                          = 200;
    const WC_COUPON_REMOVED                          = 201;
    */
   if($msg_code==200){
	 	 $msg = __(do_shortcode('[tooltip_id id=50]'), 'woocommerce' ); 
	}
	else if($msg_code==201){
		$msg = __( 'Coupon code removed successfully.', 'woocommerce' ); 
	}
    return $msg;
};

// add the filter
add_filter( 'woocommerce_coupon_error', 'filter_woocommerce_coupon_error_message', 10, 3 );
function filter_woocommerce_coupon_error_message(  $err, $err_code, $instance )
{
  //  $msg = "Yeah thanks!";
 // print_r($err_code);
/*		const E_WC_COUPON_INVALID_FILTERED               = 100;
        const E_WC_COUPON_INVALID_REMOVED                = 101;
        const E_WC_COUPON_ALREADY_APPLIED                = 103;
        const E_WC_COUPON_ALREADY_APPLIED_INDIV_USE_ONLY = 104;
        const E_WC_COUPON_NOT_EXIST                      = 105;
        const E_WC_COUPON_EXPIRED                        = 107;
        const E_WC_COUPON_PLEASE_ENTER                   = 111;
        const E_WC_COUPON_MAX_SPEND_LIMIT_MET            = 112;
       
 */
	if($err_code==100){
	 	 $err = __( do_shortcode('[tooltip_id id=51]'), 'woocommerce' ); 
	}
	else if($err_code==103){
		 $err = __( do_shortcode('[tooltip_id id=52]'), 'woocommerce' ); 
	}
	else if($err_code==105){
		$searchArray=array("##%%coupon_code%%##");
		$replaceArray=array("%s");
		$msg= str_replace($searchArray, $replaceArray, do_shortcode('[tooltip_id id=53]'));
		$err = sprintf( __( $msg, 'woocommerce' ), $instance->code ); 
	}
	else if($err_code==107){
		 $err = __( do_shortcode('[tooltip_id id=54]'), 'woocommerce' ); 
	}
	else if($err_code==111){
		 $err = __( do_shortcode('[tooltip_id id=55]'), 'woocommerce' ); 
	}
	else if($err_code==112){
		 $err = sprintf( __( do_shortcode('[tooltip_id id=56]'), 'woocommerce' ), wc_price( $instance->maximum_amount ) ); 
	}
   
    return $err;
}

// filter for direct checkout
add_filter('woocommerce_add_to_cart_redirect', 'add_to_cart_direct_checkout');
function add_to_cart_direct_checkout() {
	global $woocommerce;
	$checkout_url = $woocommerce->cart->get_checkout_url();
	return $checkout_url;
}
