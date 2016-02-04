<?php 
/*
 * Template Name: PDF13 (Gratis E-Book  )
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>

</head>

  <body>
	  <?php 
	  $order_id=$_GET['order_id'];
	  $billing_first_name = get_post_meta($order_id,'_billing_first_name',true);
	  //echo $billing_first_name;
	//  $fb_url=home_url().'/facebook-share';
	//  $fb_url='<p class="p7-eBook_buttons"><a href="https://upfit.de/facebook-share/" target="_blank">EMPFEHLEN &amp; E-BOOK SICHERN</a></p>';
	$fb_url=home_url().'/facebook-share/';
	  $searchArray = array('##%%user_first_name%%##','##%%facebook_share_button%%##');
		$replaceArray = array($billing_first_name,$fb_url);
	   ?>
	  <!-- <div style="background:#fff; padding:15px; 	margin-bottom:0px;">
		<div style="background:#f0eeef;padding:50px 70px 0px;height:189mm;">-->
		<?php //echo 'here'.$fb_url;
				$page=get_post(55);
			//$page= get_page_by_path( 'gratis-ebook-mit-ernahrungstipps', $output, 'pdf' ); 
			  $content= ($page->post_content); 
				echo str_replace($searchArray, $replaceArray, $content);
				//echo $content;
			 ?>
		<!--</div>
		</div>-->
  </body>
</html>

