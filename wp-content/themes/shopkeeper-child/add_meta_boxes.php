<?php

add_action( 'add_meta_boxes', 'add_meta_box_to_product' );
function add_meta_box_to_product()
{  
    add_meta_box( 'plan_period', 'Period of plan', 'plan_period', 'product', 'normal', 'high' );
    add_meta_box( 'bestseller', 'Bestseller', 'bestseller_plan', 'product', 'normal', 'high' );
    add_meta_box('plan_description','Plan description','plan_description','product','normal', 'high');
    add_meta_box('tooltip_id','Tooltip ID','tooltip_id','tooltip','normal', 'high');
    add_meta_box('testimonial_meta_boxes','Testimonial Notice','testimonial_meta_boxes','testimonial','normal', 'high');
    add_meta_box('faq_meta_boxes','FAQ Button Redirect','faq_meta_boxes','faq','normal', 'high');
    add_meta_box('fatburner_drink_boxes','Drink Details','fatburner_drink_boxes','fatburner_drink','normal', 'high');
    add_meta_box( 'nutrition_details', 'Ernährungsdetails', 'nutrition_details', 'shop_order', 'normal', 'high' );
    add_meta_box( 'downloadable_pdf_plan', 'Downloadable Nutrition Plan ', 'downloadable_pdf_plan', 'shop_order', 'normal', 'high' );
    if(get_the_ID()==160)
    add_meta_box( 'nav_header_fields', 'Nav Header Fields ', 'nav_header_fields', 'page', 'normal', 'high' );
   
}
//------------------------Period of Plan Meta box---------------------------//
function plan_period()
{
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
 
    $val = isset($values['plan_period']) ? $values['plan_period'] : '';
  $v=(isset($val[0]) && !empty($val[0])) ? $val[0] :  '1';
  
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'plan_period_nonce', 'meta_box_nonce' );
    ?>
   <label>Period of Plan</label>
     <input type="number" name="plan_period" required value="<?php echo $v; ?>" step="integer" min="1"/>
   
    <?php        
}
 

//-------------------------Bestseller Metabox------------------------

function bestseller_plan(){
   global $post;
    $values = get_post_custom( $post->ID );
   // print_r($values);
    $val = isset($values['choose_besteller']) ? $values['choose_besteller'] : '';
  
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'choose_besteller_nonce', 'meta_box_nonce1' );
    ?>
  <label>Bestseller&nbsp;</label>
    <input type="radio" name="choose_besteller" value="yes"  <?php if($val[0]=='yes') echo 'checked'; ?> >Yes</input>
    <input type="radio" name="choose_besteller" value="no"  <?php if(($val[0]=='no')) echo 'checked'; ?>  >No</input>
   <?php
}

//------------------------Plan Description--------------------------
function plan_description(){
   global $post;
  wp_nonce_field( basename( __FILE__ ), 'shopkeeper' );
  $val = get_post_meta( $post->ID );
    //$val = isset($values['price_info']) ? $values['price_info'] : '';
   
  //  wp_nonce_field( 'price_info_nonce', 'meta_box_nonce2' );
    wp_nonce_field( 'range_of_weight_loss_nonce', 'meta_box_nonce3' );
    wp_nonce_field( 'reason_description_nonce', 'meta_box_nonce4' );
    wp_nonce_field( 'extras_nonce', 'meta_box_nonce5' );
    wp_nonce_field( 'plan_button_nonce', 'plan_button_meta_nonce' );
 //print_r($val);
?>
<p>
        <label for="plan_button_text"><?php  _e( 'Choose Button Text(for homepage)', 'shopkeeper' )?></label>
        <input type="text" name="plan_button_text" id="plan_button_text" value="<?php if ( isset ( $val['plan_button_text'] ) ) echo $val['plan_button_text'][0]; ?>"/>
    </p>
  <p>
        <label for="price_info"><?php  _e( 'Text1:', 'shopkeeper' )?></label>
         <input type="text" placeholder='Label' name="price_info_label" id="price_info_label" value="<?php if ( isset ( $val['price_info_label'] ) ) echo $val['price_info_label'][0]; ?>"/>
        <label>It will be changed automatically when you change or update price of product. </label>
       <!-- <input type="text" name="price_info" id="price_info" value="<?php// if ( isset ( $val['price_info'] ) ) echo $val['price_info'][0]; ?>"/>-->
    </p>
     
    
    <p>
        <label for="range_of_weight_loss"><?php  _e( 'Text2', 'shopkeeper' )?></label>
        <input type="text" placeholder='Label' name="range_of_weight_label" id="range_of_weight_label" value="<?php if ( isset ( $val['range_of_weight_label'] ) ) echo $val['range_of_weight_label'][0]; ?>"/>
        <input type="text" name="range_of_weight_loss_meta" id="range_of_weight_loss" value="<?php if ( isset ( $val['range_of_weight_loss_meta'] ) ) echo $val['range_of_weight_loss_meta'][0]; ?>"/>
    </p>
    
    <p>
        <label for="reason_description"><?php  _e( 'Text3', 'shopkeeper' )?></label>
        <input type="text" placeholder='Label' name="reason_description_label" id="reason_description_label" value="<?php if ( isset ( $val['reason_description_label'] ) ) echo $val['reason_description_label'][0]; ?>"/>
        <input type="text" name="reason_description_meta" id="reason_description" value="<?php if ( isset ( $val['reason_description_meta'] ) ) echo $val['reason_description_meta'][0]; ?>"/>
    </p>
    
    <p>
        <label for="extras" style="vertical-align:top;"><?php  _e( 'Text4', 'shopkeeper' )?></label>
        <input type="text" style="vertical-align:top;" placeholder='Label' name="extras_label" id="extras_label" value="<?php if ( isset ( $val['extras_label'] ) ) echo $val['extras_label'][0]; ?>"/>
        <textarea name="extras" id="extras" rows="4"><?php if ( isset ( $val['extras'] ) ) echo $val['extras'][0]; ?></textarea>
    </p>
<?php
}

function save_product_meta( $post_id )
{ 
  //print_r($post_id);
  $d= get_post_meta( $post_id->ID );
  
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'plan_period_nonce' ) ) return;
    if( !isset( $_POST['meta_box_nonce1'] ) || !wp_verify_nonce( $_POST['meta_box_nonce1'], 'choose_besteller_nonce' ) ) return;
    if( !isset( $_POST['plan_button_meta_nonce'] ) || !wp_verify_nonce( $_POST['plan_button_meta_nonce'], 'plan_button_nonce' ) ) return;
    if( !isset( $_POST['meta_box_nonce3'] ) || !wp_verify_nonce( $_POST['meta_box_nonce3'], 'range_of_weight_loss_nonce' ) ) return;
      if( !isset( $_POST['meta_box_nonce4'] ) || !wp_verify_nonce( $_POST['meta_box_nonce4'], 'reason_description_nonce' ) ) return;
      if( !isset( $_POST['meta_box_nonce5'] ) || !wp_verify_nonce( $_POST['meta_box_nonce5'], 'extras_nonce' ) ) return;
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    /*if( isset( $_POST[ 'range_of_weight_loss' ] ) ) {
        update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'range_of_weight_loss' ] ) );
    }*/
    if( isset( $_POST['plan_period'] ) )
        update_post_meta( $post_id, 'plan_period', wp_kses( $_POST['plan_period'], $allowed ) );
   
         if( isset( $_POST['range_of_weight_loss_meta'] ) )
        update_post_meta( $post_id, 'range_of_weight_loss_meta', wp_kses( $_POST['range_of_weight_loss_meta']) );
        
         if( isset( $_POST['plan_button_text'] ) )
        update_post_meta( $post_id, 'plan_button_text', wp_kses( $_POST['plan_button_text']) );
        
         if( isset( $_POST['extras'] ) )
        update_post_meta( $post_id, 'extras', wp_kses( $_POST['extras'], $allowed ) );
        
         if( isset( $_POST['reason_description_meta'] ) )
        update_post_meta( $post_id, 'reason_description_meta', wp_kses( $_POST['reason_description_meta']) );
        
        
         if( isset( $_POST['choose_besteller'] ) )
        update_post_meta( $post_id, 'choose_besteller', wp_kses( $_POST['choose_besteller'], $allowed ) );
        
        if( isset( $_POST['price_info_label'] ) )
        update_post_meta( $post_id, 'price_info_label', wp_kses( $_POST['price_info_label']) );
         if( isset( $_POST['range_of_weight_label'] ) )
        update_post_meta( $post_id, 'range_of_weight_label', wp_kses( $_POST['range_of_weight_label']) );
        
         if( isset( $_POST['reason_description_label'] ) )
        update_post_meta( $post_id, 'reason_description_label', wp_kses( $_POST['reason_description_label']) );
        
         if( isset( $_POST['extras_label'] ) )
        update_post_meta( $post_id, 'extras_label', wp_kses( $_POST['extras_label']) );
        
        
        
  
  
      //  if( isset( $_POST['price_info'] ) )
      //  update_post_meta( $post_id, 'price_info', wp_kses( $_POST['price_info']) );
         
}
add_action( 'save_post', 'save_product_meta' ); 


/*-----------------------------add metaboxes to testimonial--------------------------*/

function testimonial_meta_boxes(){
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_custom( $post->ID );
 //print_r($values);
    $weight_ch = isset($values['testimonial_weight_badge']) ? $values['testimonial_weight_badge'] : '';
    $notice = isset($values['testimonial_notice']) ? $values['testimonial_notice'] : '';
    $btntext = isset($values['testimonial_button_text']) ? $values['testimonial_button_text'] : '';
    $btnlink = isset($values['testimonial_button_link']) ? $values['testimonial_button_link'] : '';

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'testimonial_weight_badge_nonce', 'testimonial_weight_badge_meta_box_nonce' );
    wp_nonce_field( 'testimonial_notice_nonce', 'testimonial_notice_meta_box_nonce' );
    wp_nonce_field( 'testimonial_button_text_nonce', 'testimonial_button_text_meta_box_nonce' );
    wp_nonce_field( 'testimonial_button_link_nonce', 'testimonial_button_link_meta_box_nonce' );
    $pages=get_pages(array('post_type' => 'page',
  'post_status' => 'publish','sort_order' => 'asc',
  'sort_column' => 'post_title',));
    ?>
     <label><?php echo __('Weight Change:','shopkeeper') ?></label>
       <input type="number" name="testimonial_weight_badge" required value="<?php if(isset($weight_ch[0]) && !empty($weight_ch[0])) echo $weight_ch[0]; else echo ''; ?>" step="integer" min="-99" max="99"/><span>Kg</span>
   
    <hr/>
   <label><?php echo __('Notice:','shopkeeper') ?></label>
     <input type="text" name="testimonial_notice" required value="<?php 
     if(isset($notice[0]) && !empty($notice[0])) echo $notice[0];
     else echo '';  ?>" />
    <hr/>
    
    <label><?php echo __('Button Text:','shopkeeper') ?></label>
     <input type="text" name="testimonial_button_text" required value="<?php if(isset($btntext[0]) && !empty($btntext[0])) echo $btntext[0];
     else echo ''; ?>" />
     <hr/>
  <label><?php echo __('Select Page to Redirect from button:','shopkeeper') ?> </label> 
  <select name="testimonial_button_link" required>
    <option value=""><?php echo __('Select Page','shopkeeper') ?></option>
    <?php
    foreach($pages as $p){
    ?>  
      <option value='<?php echo $p->ID; ?>' <?php  if($btnlink[0]==$p->ID) echo 'selected'; else echo ''; ?> ><?php echo __($p->post_title,'shopkeeper') ?> 
       
      </option>
    <?php
    }
    ?>
  </select>

 <?php        
}
function save_testimonial_meta_boxes($post_id){

    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['testimonial_weight_badge_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['testimonial_weight_badge_meta_box_nonce'], 'testimonial_weight_badge_nonce' ) ) return;
    if( !isset( $_POST['testimonial_notice_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['testimonial_notice_meta_box_nonce'], 'testimonial_notice_nonce' ) ) return;
	if( !isset( $_POST['testimonial_button_text_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['testimonial_button_text_meta_box_nonce'], 'testimonial_button_text_nonce' ) ) return;
	if( !isset( $_POST['testimonial_button_link_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['testimonial_button_link_meta_box_nonce'], 'testimonial_button_link_nonce' ) ) return;

     if( !current_user_can( 'edit_post' ) ) return;
     $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    /*if( isset( $_POST[ 'range_of_weight_loss' ] ) ) {
        update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'range_of_weight_loss' ] ) );
    }*/
    if( isset( $_POST['testimonial_weight_badge'] ) )
        update_post_meta( $post_id, 'testimonial_weight_badge', wp_kses( $_POST['testimonial_weight_badge'], $allowed ) );
    if( isset( $_POST['testimonial_notice'] ) )
        update_post_meta( $post_id, 'testimonial_notice', wp_kses( $_POST['testimonial_notice'], $allowed ) );
	if( isset( $_POST['testimonial_button_text'] ) )
		update_post_meta( $post_id, 'testimonial_button_text', wp_kses( $_POST['testimonial_button_text']) );
	if( isset( $_POST['testimonial_button_link'] ) )
		update_post_meta( $post_id, 'testimonial_button_link', wp_kses( $_POST['testimonial_button_link']) );
   
}
add_action( 'save_post', 'save_testimonial_meta_boxes' ); 

/*---------------------Add metabox to tooltip-------------*/
function tooltip_id(){

    $values = get_post_custom( $post->ID );
    $count_posts = wp_count_posts( 'tooltip' )->publish;
  $val = isset($values['tooltip_id']) ? $values['tooltip_id'] : '';
  
  $v=(isset($val[0]) && !empty($val[0])) ? $val[0] : $count_posts+1;
  
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'tooltip_id_nonce', 'tooltip_id_meta_box_nonce' );

    ?>
  <label><?php echo __('Tooltip ID:','shopkeeper') ?></label>
    <input type="text" name="tooltip_id" required value="<?php echo $v; ?>"/>
   
    <?php        
}

function save_tooltip_id($post_id){
  
   if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     if( !isset( $_POST['tooltip_id_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['tooltip_id_meta_box_nonce'], 'tooltip_id_nonce' ) ) return;
       
       if( !current_user_can( 'edit_post' ) ) return;
     $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
      if( isset( $_POST['tooltip_id'] ) )
        update_post_meta( $post_id, 'tooltip_id', wp_kses( $_POST['tooltip_id'], $allowed ) );
     
}
add_action( 'save_post', 'save_tooltip_id' ); 

/*-----------------------------add metaboxes to FAQ--------------------------*/

function faq_meta_boxes(){
  wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_custom( $post->ID );
  //print_r($values);
    
    $btntext = isset($values['faq_button_text']) ? $values['faq_button_text'] : '';
    $btnlink = isset($values['faq_button_text']) ? $values['faq_button_link'] : '';

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'faq_button_text_nonce', 'faq_button_text_meta_box_nonce' );
    wp_nonce_field( 'faq_button_link_nonce', 'faq_button_link_meta_box_nonce' );
    $pages=get_pages(array('post_type' => 'page',
  'post_status' => 'publish','sort_order' => 'asc',
  'sort_column' => 'post_title',));
    ?>
     
    
    <label><?php echo __('Button Text:','shopkeeper') ?></label>
     <input type="text" name="faq_button_text" required value="<?php if(isset($btntext[0]) && !empty($btntext[0])) echo $btntext[0];
     else echo ''; ?>" />
     <hr/>
  <label><?php echo __('Select Page to Redirect from button:','shopkeeper') ?> </label> 
  <select name="faq_button_link" required>
    <option value=""><?php __('Select Page','shopkeeper') ?></option>
    <?php
    foreach($pages as $p){
    ?>  
      <option value='<?php echo $p->ID; ?>' <?php  if($btnlink[0]==$p->ID) echo 'selected'; else echo ''; ?> ><?php echo __($p->post_title,'shopkeeper') ?>
      </option>
    <?php
    }
    ?>
  </select>

 <?php        
}
function save_faq_meta_boxes($post_id){

    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
  if( !isset( $_POST['faq_button_text_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['faq_button_text_meta_box_nonce'], 'faq_button_text_nonce' ) ) return;
  if( !isset( $_POST['faq_button_link_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['faq_button_link_meta_box_nonce'], 'faq_button_link_nonce' ) ) return;

     if( !current_user_can( 'edit_post' ) ) return;
     $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    /*if( isset( $_POST[ 'range_of_weight_loss' ] ) ) {
        update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'range_of_weight_loss' ] ) );
    }*/
   
  if( isset( $_POST['faq_button_text'] ) )
    update_post_meta( $post_id, 'faq_button_text', wp_kses( $_POST['faq_button_text']) );
  if( isset( $_POST['faq_button_link'] ) )
    update_post_meta( $post_id, 'faq_button_link', wp_kses( $_POST['faq_button_link']) );
   
}
add_action( 'save_post', 'save_faq_meta_boxes' ); 
   
//---------------------------fatburner_drink_boxes-----------------
function fatburner_drink_boxes(){
  wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_custom( $post->ID );
    
    $drink_type=isset($values['drink_type']) ? $values['drink_type'] : '';
    $drink_short_disc = isset($values['drink_short_disc']) ? $values['drink_short_disc'] : '';
    $drink_calary = isset($values['drink_calary']) ? $values['drink_calary'] : '';
    $drink_time = isset($values['drink_time']) ? $values['drink_time'] : '';
    $drink_advantages = isset($values['drink_advantages']) ? $values['drink_advantages'] : '';
    wp_nonce_field( 'drink_type_nonce', 'drink_type_meta_box_nonce' );
    wp_nonce_field( 'drink_short_disc_nonce', 'drink_short_disc_meta_box_nonce' );
    wp_nonce_field( 'drink_calary_nonce', 'drink_calary_meta_box_nonce' );
    wp_nonce_field( 'drink_time_nonce', 'drink_time_meta_box_nonce' );
    wp_nonce_field( 'drink_advantages_nonce', 'drink_advantages_meta_box_nonce' );
    ?>
    <label><?php _e('Short Description:','shopkeeper') ?> </label>
    <input type="text" name="drink_short_disc" required value="<?php if(isset($drink_short_disc[0]) && !empty($drink_short_disc[0])) echo $drink_short_disc[0];
    else echo ''; ?>" /><br/>
    <label><?php _e('Drink Type:','shopkeeper') ?> </label>
    <?php $drink_types=array('veggie'=>'Veggie','vegan'=>'Vegan','paleo'=>'Paleo'); 
   
    ?>
    
    <select name="drink_type" required >
    <?php foreach($drink_types as $key=>$value){ ?>
        <option value="<?php echo $key ?>" <?php if($drink_type[0]==$key) echo 'selected' ?> ><?php echo $value ?></option>
  <?php }?>
  </select>
    <br/>
    <label>Kilocalorie: </label>
    <input type="text" name="drink_calary" required value="<?php if(isset($drink_calary[0]) && !empty($drink_calary[0])) echo $drink_calary[0];
    else echo ''; ?>" /><br/>
    <label>Time To Prepare: </label>
    <input type="text" name="drink_time" required value="<?php if(isset($drink_time[0]) && !empty($drink_time[0])) echo$drink_time[0];
    else echo ''; ?>" /><br/>
  
<?php
}
add_action( 'save_post', 'save_fatburner_drink_boxes' ); 
function save_fatburner_drink_boxes($post_id){
   if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
  if( !isset( $_POST['drink_type_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['drink_type_meta_box_nonce'], 'drink_type_nonce' ) ) return;
  if( !isset( $_POST['drink_short_disc_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['drink_short_disc_meta_box_nonce'], 'drink_short_disc_nonce' ) ) return;
  if( !isset( $_POST['drink_calary_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['drink_calary_meta_box_nonce'], 'drink_calary_nonce' ) ) return;
  if( !isset( $_POST['drink_time_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['drink_time_meta_box_nonce'], 'drink_time_nonce' ) ) return;
  if( !isset( $_POST['drink_advantages_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['drink_advantages_meta_box_nonce'], 'drink_advantages_nonce' ) ) return;
  

     if( !current_user_can( 'edit_post' ) ) return;
     $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    /*if( isset( $_POST[ 'range_of_weight_loss' ] ) ) {
        update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'range_of_weight_loss' ] ) );
    }*/
   
  if( isset( $_POST['drink_type'] ) )
    update_post_meta( $post_id, 'drink_type', wp_kses( $_POST['drink_type']) );
  if( isset( $_POST['drink_short_disc'] ) )
    update_post_meta( $post_id, 'drink_short_disc', wp_kses( $_POST['drink_short_disc']) );
  if( isset( $_POST['drink_calary'] ) )
    update_post_meta( $post_id, 'drink_calary', wp_kses( $_POST['drink_calary']) );
  if( isset( $_POST['drink_time'] ) )
    update_post_meta( $post_id, 'drink_time', wp_kses( $_POST['drink_time']) );
  if( isset( $_POST['drink_advantages'] ) )
    update_post_meta( $post_id, 'drink_advantages', wp_kses( $_POST['drink_advantages']) );
}


function nutrition_details()
{
  
	$orderid=$_REQUEST['post'];
	global $wpdb;

	//echo $order->id;
	$order_detail = new WC_Order($orderid);
	$items = array_values($order_detail->get_items());
	/*echo "<pre>";
	print_r($items);
	echo "</pre>";*/

	$mixed = $items[0]['wdm_user_custom_data'];
	$order_custom_data=unserialize($mixed);

	$sql="SELECT upl.* FROM up_user_nutrition_plans unp,up_plan_logs upl WHERE unp.id=upl.user_nutrition_plan_id and unp.order_id=".$orderid." and unp.regenerate=1";
	$regenerate_plan=$wpdb->get_results($sql);
		

?>
<link href="<?php echo get_template_directory_uri() . '-child/css/admin_style.css';?>"/>
<script>
  jQuery(document).ready(function () {
    jQuery('#change_nutrition_plan').on('click', function () {
      var order=jQuery('#admin_order_id').val();
      //alert(order);
      jQuery.ajax({
              url: "<?php echo admin_url('admin-ajax.php'); ?>",
              type: 'POST',
              data: "action=regenerate_pdf_plan&order_id=" + order  ,
              beforeSend: function () {
                                //console.log('sending');
          jQuery('#change_nutrition_plan').css("display", "none");
          jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
        },
        success: function (res) {
          console.log(res);
           jQuery('#change_nutrition_plan').css("display", "none");
           jQuery(".loader").css("display", "none");
          if(res=="success")
          {
            alert('your pdf is regenerated successfully.');
            jQuery('#change_nutrition_plan').css("display", "none");
            jQuery(".loader").css("display", "none");
          }
          location.reload();
        }
      })
    });
  });
</script>
<style>
.details_table tr td:first-child{
	width:40%;	
}
.details_table tr:last-child td{
	border-bottom:0px;		
}
.details_table tr td:last-child{
	border-left:1px solid #bdbdbd;		
}
.tablehead td{
	font-size:14px;
	font-weight: bold;
	border-left:0px !important;
}
.details_table{
	line-height:23px;
	width:100%;
	border-spacing:0px;
	border:1px solid #bdbdbd;
}
.details_table tr td{
	padding: 5px 15px;
	border-bottom:1px solid #bdbdbd;
}
.details_table tr:not(.tablehead) td:first-child{
	padding: 5px 32px;
}
</style>
<?php 
	$nut_types=$nuts=$fruits=$allergies=$sweet_tooth_ar=array();
		$nut_types=array(
					'nospecial'=>'Keine besondere',
					'vegetarian'=>'Vegetarisch',
					'vegan'=>'Vegan',
					'pescetarian'=>'Pescetarian',
					'flexitarian'=>'Flexitarisch',
					'paleo'=>'Paleo'
					);
		$allergies=array(
					'lactose'=>'Laktose (Milchzucker)',
					'fructose'=>'Fruktose (Fruchtzucker)',
					'histamine'=>'Histamin',
					'gluten'=>'Gluten (Zöliakie)',
					'glutamat'=>'Glutamat',
					'sucrose'=>'Saccharose (Haushaltszucker)',
					);
		$nuts_ar=array(
					'hazelnut'=>'Haselnuss',
					'almond'=>'Mandel',
					'pecan_nut'=>'Pekannuss',
					'walnut'=>'Walnuss',
					'peanut'=>'Erdnuss',
					'cashew'=>'Cashewnuss',
					'brazil_nut'=>'Paranuss',
					'pistachio'=>'Pistazie',
					);
		$fruits_ar=array(
					'apple'=>'Apfel',
					'avocade'=>'Avocado',
					'banana'=>'Banane',
					'kiwi'=>'Kiwi',
					'papaya'=>'Papaya',
					'strawberry'=>'Erdbeere',
					'melon'=>'Melone',
					'peach'=>'Pfirsich',
					);
		$alg=$fr=$nt=$nuts=array();
		//------Nutrition Type-------//
		$nt_db=explode(',',$order_custom_data['nutrition_type']);
		foreach($nt_db as $n){
			if(array_key_exists($n,$nut_types)){
				$nt[]=$nut_types[$n];
			}
		}
		$nt1=implode(',',$nt);
		
	//---------Allergies-----------//
		$alg_db=explode(',',$order_custom_data['allergies']);
		foreach($alg_db as $al){
			if(array_key_exists($al,$allergies)){
				$alg[]=$allergies[$al];
			}
		}
		$alg1=implode(',',$alg);
	//---------------Fruits------------//
		$fr_db=explode(',',$order_custom_data['fruit']);
		
		//if(!empty($fr_db)){
			foreach($fr_db as $f){
				if(array_key_exists($f,$fruits_ar)){
					$fr[]=$fruits_ar[$f];
				}
			}
				$fr1=implode(',',$fr);
	//	}
	//---------------Nuts--------------//
		$nuts_db=explode(',',$order_custom_data['nuts']);
		if(!empty($nuts_db)){
			foreach($nuts_db as $nt){
				if(array_key_exists($nt,$nuts_ar)){
					$nuts[]=$nuts_ar[$nt];
				}
			}
				$nuts1=implode(',',$nuts);
		}
	//----------Sweet Tooth--------------//
	$sweet_tooth_ar=array('yes'=>'Ja','sometime'=>'Manchmal','no'=>'Nein');
	//---------------is_time_to_cook--------------//
	$time_to_cook_ar=array('little'=>'Wenig (< 45 min)','Normal'=>'Normal (45-60 min)','much'=>'Viel (> 60 min)');
	$where_food_buy_ar=array('cheap'=>'Preiswert','Normal'=>'Normal','much'=>'Premium');
	$most_buy_ar=array('price'=>'Preis','quality'=>'Qualität','both'=>'Beides');
	
?>
	<input type="hidden" id="admin_order_id" value="<?php echo $orderid;?>"/>

	<table class="details_table">
		<tbody>
			<tr class="tablehead">
				<td colspan="2">1. Gewicht & Ziel</td>
			</tr>
			<tr>
				<td>Aktuelles Gewicht</td>
				<td><?php echo $order_custom_data['cur_weight']." kg";?></td>
			</tr>
			<tr>
				<td>Wunschgewicht</td>
				<td><?php echo  $order_custom_data['desired_weight']." kg";?></td>
			</tr>
			<tr class="tablehead">
				<td colspan="2">2. Körper & Aktivität</td>
			</tr>
			<tr>
				<td>Geschlecht</td>
				<td><?php $gender=($order_custom_data['gender']=="m")?"männlich" :  "weiblich";
					  echo $gender;?></td>
			</tr>
			<tr>
				<td>Alter</td>
				<td><?php echo $order_custom_data['age']." Jahre";?></td>
			</tr> 
			<tr>
				<td>Körpergröße</td>
				<td><?php echo $order_custom_data['height']." cm";?></td>
			</tr>
			<tr>
				<td>Aktivitätsniveau im Alltag</td>
				<td><?php echo $order_custom_data['daily_activity'];?></td>
			</tr>
			<tr class="tablehead">
				<td colspan="2">3. Ernährungsweise</td>
			</tr>
			<tr>
				<td>Ernährungsweise</td>
				<td><?php echo str_replace(',',', ',$nt1); ?></td>
			</tr>

			<tr class="tablehead">
				<td colspan="2">4. Allergien & Intoleranzen</td>
			</tr>
			<tr>
				<td>Allergien & Intoleranzen</td>
				<td>
					<?php 
						if(!empty($order_custom_data['allergies']))
							echo str_replace(',',', ',$alg1); 
						else
							echo '-';
					?>
				</td>
			</tr>
			<tr>
				<td><?php echo ('Nüsse'); ?></td>
				<td>
				<?php 
					if(!empty($order_custom_data['nuts']))  
						echo str_replace(',',', ',$nuts1); 
					else 
						echo '-';
				?>
				</td>
			</tr>
			<tr>
				<td><?php echo ('Früchte'); ?></td>
				<td>
				<?php 
					if(!empty($order_custom_data['fruit'])) 
						echo str_replace(',',', ',$fr1);
					else  
						echo '-';
				?>
				</td>
			</tr>
			<tr>
				<td>Auszuschließende Lebensmittel</td>
				<td>
				<?php
					if(!empty($order_custom_data['exclude'])) 
						echo str_replace(',',', ',$order_custom_data['exclude']); 
					else 
						echo '-';
				?>
				</td>
			</tr>
			<tr class="tablehead">
				<td colspan="2">5. Essgewohnheiten</td>
			</tr>
			<tr>
			<td>Naschst/snackst Du gern?</td>
				<td><?php echo $sweet_tooth_ar[$order_custom_data['sweet_tooth']]; ?></td>
			</tr>
			<tr>
				<td>Deine Lust/Zeit zum Kochen pro Tag?</td>
				<td><?php echo $time_to_cook_ar[$order_custom_data['is_time_to_cook']]; ?></td>
			</tr>
			<tr>
				<td>Wo kaufst Du Lebensmittel?</td>
				<td><?php echo $where_food_buy_ar[$order_custom_data['where_food_buy']]; ?></td>
			</tr>
			<tr>
				<td>Worauf achtest Du beim Kauf besonders?</td>
				<td><?php echo  $most_buy_ar[$order_custom_data['most_buy']]; ?></td>
			</tr>
			<tr class="tablehead">
				<td colspan="2">6. Plan</td>
			</tr>
			<tr>
				<td>Plan Name</td>
				<td><?php echo $items[0]['name']; ?></td>
			</tr>
			<?php if(!empty($regenerate_plan)){?>
			<tr>
				<td>Regenerate pdf</td>
				<td>
					<input type="button" name="change_nutrition_plan" id="change_nutrition_plan" value="Regenerate Plan">
					<div class="loader"></div>
				</td>
			</tr>
			<?php }?>
			  
		</tbody>
	</table>
<?php
}

add_action('wp_ajax_regenerate_pdf_plan', 'regenerate_pdf_plan');

function regenerate_pdf_plan() {
	$re_order_id=$_POST['order_id'];
	global $post,$wpdb;
	$wpdb->query('update up_user_nutrition_plans set regenerate="1" where order_id='.$re_order_id);
	echo do_shortcode('[create_pdf order_id='.$re_order_id.']');
	wp_die();
}

function downloadable_pdf_plan(){
	global $post,$wpdb;
    $site_id=get_current_blog_id();
	$results = $wpdb->get_results( 'SELECT * FROM up_user_nutrition_plans WHERE order_id ='.$post->ID.' AND site_id='.$site_id);
    //print_r($results);
	$order_id=$post->ID;
    $billing_email = get_post_meta($order_id,'_billing_email',true);
    $billing_first_name = get_post_meta($order_id,'_billing_first_name',true);
    $billing_last_name = get_post_meta($order_id,'_billing_last_name',true);
    
    ?>
    <style>
    .pdf_download_permissions .wc-metaboxes .wc-metabox .fixed strong a{
		color:#333333;
		text-decoration:none;
    }
	  
    </style>
    <div class="pdf_download_permissions wc-metaboxes-wrapper">
    <?php 
		foreach($results as $r){ 
			if(isset($r->pdf_path) && isset($r->pdf_name) && !empty($r->pdf_path)){
		?>
		  <div class="wc-metaboxes">
			<div class="wc-metabox">
			  <h3 class="fixed">
				  <?php ?>
			  <strong><a href="<?php echo $r->pdf_path ?>" target="_blank"><?php echo $r->pdf_name.'.pdf' ?></a></strong>
			  </h3>
			</div>
		  </div>
		  
	 <?php
		}
		   if(isset($r->re_pdf_name) && isset($r->re_pdf_path) && !empty($r->re_pdf_path)){
		?>
				
			<div class="wc-metaboxes">
			  <div class="wc-metabox">
				<h3 class="fixed">
			 <strong><a target="_blank" href="<?php echo $r->re_pdf_path ?>"><?php echo $r->re_pdf_name.'.pdf' ?></a></strong>

				</h3>
				 <form method="post" action="">
				  <input type="hidden" value='<?php echo $post->ID ?>' class='oid'>
				<input type="submit" class="send_mail" name="send_mail" value="An Kunde senden">
			  </form>
			  </div>
			</div>
			 
			  <?php
				  }
			   }
			   ?>
		</div>
    <script>
		jQuery('.send_mail').click(function(e){
			e.preventDefault();
			var order_id=jQuery('.oid').val();
			jQuery.ajax({
				type : "post",
				  url : "<?php echo admin_url( 'admin-ajax.php' )  ?>",
				  data:{
					  action:'send_regnerate_pdf_mail',
					  order_id:order_id
					 },
				complete:function(data){
						console.log(data);
						  location.reload();
					}
				});
		})
    </script>
    
    <?php   
}
add_action( 'wp_ajax_send_regnerate_pdf_mail', 'send_regnerate_pdf_mail' );
add_action( 'wp_ajax_nopriv_send_regnerate_pdf_mail', 'send_regnerate_pdf_mail' );
function send_regnerate_pdf_mail(){
	$order_id=$_POST['order_id'];
			
	$order = new WC_Order( $order_id );
	//print_r($order);
	$order->update_status( 'completed' );
	echo 'success mail';
	wp_die();
}

function nav_header_fields(){
	wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_custom( $post->ID );
  //print_r($values);
    
    $nav_header_5_label = isset($values['nav_header_5_label']) ? $values['nav_header_5_label'] : '';
    $nav_header_5_link = isset($values['nav_header_5_link']) ? $values['nav_header_5_link'] : '';
    $nav_header_6_label = isset($values['nav_header_6_label']) ? $values['nav_header_6_label'] : '';
    $nav_header_6_link = isset($values['nav_header_6_link']) ? $values['nav_header_6_link'] : '';
	
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'nav_header_5_label_nonce', 'nav_header_5_label_meta_box_nonce' );
    wp_nonce_field( 'nav_header_5_link_nonce', 'nav_header_5_link_meta_box_nonce' );
    wp_nonce_field( 'nav_header_6_label_nonce', 'nav_header_6_label_meta_box_nonce' );
    wp_nonce_field( 'nav_header_6_link_nonce', 'nav_header_6_link_meta_box_nonce' );
    
    ?>
    <ul>
		<li>
			<label>Nav Header 5th Label: </label>
			<input type="text" value="<?php if(isset($nav_header_5_label[0]) && !empty($nav_header_5_label[0])) echo $nav_header_5_label[0]; else echo 'Gesamtübersicht'; ?>" name="nav_header_5_label"/>
		</li>
		
		<li>
			<label>Nav Header 5th Link: </label>
			<input type="text" value="<?php if(isset($nav_header_5_link[0]) && !empty($nav_header_5_link[0])) echo $nav_header_5_link[0];  else echo '#plan_listing'; ?>" name="nav_header_5_link"/>
		</li>
		
		<li>
			<label>Nav Header 6th Label: </label>
			<input type="text" value="<?php if(isset($nav_header_6_label[0]) && !empty($nav_header_6_label[0])) echo $nav_header_6_label[0];  else echo 'Warum Upfit'; ?>" name="nav_header_6_label"/>
		</li>
		
		<li>
			<label>Nav Header 6th Link: </label>
			<input type="text" value="<?php if(isset($nav_header_6_link[0]) && !empty($nav_header_6_link[0])) echo $nav_header_6_link[0];  else echo '#why_upfit'; ?>" name="nav_header_6_link"/>
		</li>
		
    </ul>
    <?php  
}


add_action( 'save_post', 'save_nav_header_fields' ); 
function save_nav_header_fields($post_id){
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['nav_header_5_label_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['nav_header_5_label_meta_box_nonce'], 'nav_header_5_label_nonce' ) ) return;
	if( !isset( $_POST['nav_header_5_link_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['nav_header_5_link_meta_box_nonce'], 'nav_header_5_link_nonce' ) ) return;
	if( !isset( $_POST['nav_header_6_label_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['nav_header_6_label_meta_box_nonce'], 'nav_header_6_label_nonce' ) ) return;
	if( !isset( $_POST['nav_header_6_link_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['nav_header_6_link_meta_box_nonce'], 'nav_header_6_link_nonce' ) ) return;
  
	if( !current_user_can( 'edit_post' ) ) return;
		$allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
   
	if( isset( $_POST['nav_header_5_label'] ) )
		update_post_meta( $post_id, 'nav_header_5_label', wp_kses( $_POST['nav_header_5_label']) );
	if( isset( $_POST['nav_header_5_link'] ) )
		update_post_meta( $post_id, 'nav_header_5_link', wp_kses( $_POST['nav_header_5_link']) );
	if( isset( $_POST['nav_header_6_label'] ) )
		update_post_meta( $post_id, 'nav_header_6_label', wp_kses( $_POST['nav_header_6_label']) );
	if( isset( $_POST['nav_header_6_link'] ) )
		update_post_meta( $post_id, 'nav_header_6_link', wp_kses( $_POST['nav_header_6_link']) );
 
}

