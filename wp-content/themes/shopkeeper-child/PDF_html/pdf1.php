<?php 
/*
 * Template Name: PDF1(Cover)
 */
?>
<!DOCTYPE html>
<html lang="en">
  
  <body class="white">
	  <?php
	//get_header();
	 global $wpdb, $shopkeeper_theme_options;
	  $order_id= $_GET['order_id']; 
	//  $week_no= $_GET['week_no']; 

	 $order=new WC_Order($order_id);
	  $items = $order->get_items();
		 
	 $item_data=$items [array_keys($items)[0]];
	// echo '<pre>	';
	 $plan_id=$item_data['item_meta']['_product_id'][0];
	// echo $plan_id;
		$plan_details=get_post($plan_id);
		$plan_period='('.get_post_meta($plan_id,'plan_period',true).' Wochen)';
		$billing_first_name = get_post_meta($order_id,'_billing_first_name',true);
		$plan_name='Dein Ernährungsplan: '.$item_data['name'];
	 $site_logo = $shopkeeper_theme_options['site_logo']['url'];
		$searchArray = array("##%%plan_name%%##", "##%%plan_period%%##", "##%%product_content%%##",'##%%site_logo%%##','##%%user_first_name%%##');
 
		$replaceArray = array($plan_name, $plan_period, $plan_details->post_content,$site_logo,$billing_first_name);

	
	  ?>
	<div style="background:#fff; padding:15px;">
      <div style="background:#f0eeef url(<?php echo get_template_directory_uri();?>-child/images/main_input_bg.png) no-repeat 85% bottom;margin:0 auto;padding:50px 70px;height:176mm;outline:none;">
			<?php
				$page=get_post(28);
			 //$page= get_page_by_path( 'cover', $output, 'pdf' ); 
				  $content= ($page->post_content); 
					echo str_replace($searchArray, $replaceArray, $content);
			
			?>

      </div>
   </div>
  </body>
</html>
