<?php
/*
 * Template Name: test mailer
 * 
 */
global $wpdb;
$dataq = $wpdb->get_results("select meta_value from up_woocommerce_order_itemmeta where order_item_id='922' and meta_key='pdf_processing_status'");
echo "select meta_value from up_woocommerce_order_itemmeta where order_item_id='922' and meta_key='pdf_processing_status'";
$dataq= json_decode(json_encode($dataq),true);
echo '<pre>';
print_r($dataq);
echo '</pre>';
 $c = wp_mail('lanetteam.milan@gmail.com','test-mail','test mail from upfit.de');
 if($c)
 {
	 echo 'success';
 }
 else
 {
	 echo 'failed';
 }
?>
