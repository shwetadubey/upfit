<?php 
/*
 * Template Name: PDF2 (Das UpFit Ernährungskonzept)
 */
?>
<!DOCTYPE html>
<html lang="en">
	 <head><style>
  .price_tag2{
background: #feda46;
    border-radius: 50%;
    height: 120px;
    width: 120px;
    text-align: center;
    position: absolute;
	float:right;
    
    top: 0px;
    z-index: 999;
    }
    </style>
  </head>
  <?php 
   $order_id= $_GET['order_id']; 

	$order=new WC_Order($order_id);
	$items = $order->get_items();
			global $wpdb, $shopkeeper_theme_options;
	$item_data=$items [array_keys($items)[0]];
	// echo '<pre>	';
	$plan_id=$item_data['item_meta']['_product_id'][0];
	// echo $plan_id;
	$plan_details=get_post($plan_id);
	$plan_period=get_post_meta($plan_id,'plan_period',true);
	$regular_price=get_post_meta($plan_id,'_regular_price',true);
	$price=get_post_meta($plan_id,'_price',true);
	//$best=get_post_meta($plan_id,'choose_besteller',true);
	$sale_price=get_post_meta($plan_id,'_sale_price',true);
	$weight_loss=get_post_meta($plan_id,'range_of_weight_loss_meta',true);
	$reason_desc=get_post_meta($plan_id,'reason_description_meta',true);
	$extras=get_post_meta($plan_id,'extras',true);
	
	if(isset($sale_price) && !empty($sale_price))
	{
		$pries_line= 'Nur '.$sale_price.get_woocommerce_currency_symbol()."&nbsp;<span style='text-decoration:line-through'>".$regular_price.get_woocommerce_currency_symbol()."</span> (Nur ".number_format($sale_price/($plan_period*7),2,',','').get_woocommerce_currency_symbol() . " pro Tag)";
	}
	else{
			$pries_line=  'Nur '.$regular_price.get_woocommerce_currency_symbol()." (Nur ".number_format($regular_price/($plan_period*7),2,',','').get_woocommerce_currency_symbol() . " pro Tag)";
	  }

	$plan_name=$item_data['name'];
	$site_logo = $shopkeeper_theme_options['site_logo']['url'];
	$searchArray = array("##%%plan_name%%##", "##%%plan_period%%##","##%%pries_line%%##","##%%weight_loss%%##","##%%reason_desc%%##","##%%extras%%##","##%%site_logo%%##","##%%product_content%%##");
 
	$replaceArray = array($plan_name, $plan_period,$pries_line,$weight_loss,$reason_desc,$extras,$site_logo,$plan_details->post_content);
  ?>
 
  <body  class="white">
	<div style="background:#fff; padding:15px;">
      <div style="background:#f0eeef url(<?php echo get_template_directory_uri(); ?>-child/images/pdf_page2.png) no-repeat top right;background-size:33%; margin:0 auto;height:176mm;padding:50px 70px; outline:none;">  
		<?php 
			$page=get_post(46);
			//$page= get_page_by_path( 'the-upfit-nutrition-concept', $output, 'pdf' ); 
			$content= ($page->post_content); 
			echo str_replace($searchArray, $replaceArray, $content);		           
      ?>
            
        </div>

      </div>
   
  </body>
</html>
