<?php
/*
 * Template Name: Demo Cron JOB
 */
//ini_set("display_errors", "1");
 // error_reporting(E_ALL);
global $wpdb;
$prefix="up_";
$blog_id = get_current_blog_id();
$date1 = date('Y-m-d H:i:s');

function file_get_contents_curl($url) {
		echo "</br>".$url."</br>";
	//file_put_contents('cl.txt', 'tete');
		$ch = curl_init();
		$username="admin";
		$password="upfit@123";
        //$timeout = 5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
         $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        echo $data;
		return $data;
}


$blog_list = get_blog_list( 0, 'all' );
/*
foreach ($blog_list AS $blog) {
    echo 'Blog '.$blog['blog_id'].': '.$blog['domain'].$blog['path'].'<br />';
    $main_blog_prefix = $wpdb->get_blog_prefix($blog['blog_id']);
    echo $main_blog_prefix;
}*/
//$order_file=@file_get_contents(home_url().'/order_data.txt');
//echo $wpdb->prefix;

$sql="SELECT order_id,upwom.order_item_id FROM ".$wpdb->prefix."woocommerce_order_items upwo,".$wpdb->prefix."woocommerce_order_itemmeta upwom  
	WHERE upwom.order_item_id=upwo.order_item_id and meta_key = 'pdf_processing_status' and meta_value=0";
$result_order=$wpdb->get_results($sql);
//echo $sql;
$logic_page=get_permalink( get_page_by_path( 'nutrition-plan-cron' ) );
//echo "orders:<pre>";print_r($result_order);


if(!empty($result_order))
{
	foreach($result_order as $order_value)
	{
		//$order_file.="order id:".$order_value->order_id." pdf_processing_status : 0 Logic not started</br>";
		//echo "order id:".$order_value->order_id." pdf_processing_status : 0 Logic not started<br/>";
		$pdf_processing_description='Execution of logic is started';
		$sql1="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value = 1 WHERE order_item_id=".$order_value->order_item_id." and meta_key = 'pdf_processing_status'";
		$sql2="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value ='".$pdf_processing_description."' WHERE order_item_id=".$order_value->order_item_id." and meta_key = 'pdf_processing_description'";
		//echo "order id:".$order_value->order_id." pdf_processing_status : 1 <br/>";
		//$order_file.="order id:".$order_value->order_id." pdf_processing_status : 1</br>";
		$wpdb->query($sql1);
		$wpdb->query($sql2);
		
		$cron_sql="insert into ".$prefix."cron_status (order_id,description,pdf_processing_status,description_done_datetime,site_id) values ('".$order_value->order_id."','cron has been started','1','".$date1."','".$blog_id."')";
		//echo "1st:".$cron_sql."</br>";
		//$cron=$wpdb->query($cron_sql);
		
	}
}


if(!empty($result_order)){
	foreach($result_order as $order_value)
	{
			$order_id=$order_value->order_id;
			
			$msg=file_get_contents_curl($logic_page."?order_id=".$order_id);
			$get_check="select count(id) from up_user_nutrition_plans where order_id=".$order_id;
			$order_count=$wpdb->get_var($get_check);
			if($order_count > 0)
			{
				$pdf_processing_description='Logic is complete, PDF is generating';
				$sql1="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value = 2 WHERE order_item_id=".$order_value->order_item_id." and meta_key = 'pdf_processing_status'";
				$sql2="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value ='".$pdf_processing_description."' WHERE order_item_id=".$order_value->order_item_id." and meta_key = 'pdf_processing_description'";
				$cron_sql="insert into ".$prefix."cron_status (order_id,description,pdf_processing_status,description_done_datetime,site_id) values ('".$order_id."','logic has been successfully run','2','".$date1."','".$blog_id."')";
				//echo "2nd:".$cron_sql."</br>";
				
				//echo $sql;
				$a=$wpdb->query($sql1);
				$a=$wpdb->query($sql2);
				//$cron=$wpdb->query($cron_sql);
				echo do_shortcode('[create_pdf order_id='.$order_id.' order_item_id='.$order_value->order_item_id.']');
			}
			/*if(strcasecmp(sanitize_text_field($msg),'success')==0)
			{
				//echo "if";
				
				$sql="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value = 2 WHERE order_item_id=".$order_value->order_item_id." and meta_key = 'pdf_processing_status'";
				$cron_sql="insert into ".$prefix."cron_status (order_id,description,pdf_processing_status,description_done_datetime,site_id) values ('".$order_id."','logic has been successfully run','2','".$date1."','".$blog_id."')";
				//echo "2nd:".$cron_sql."</br>";
				
				//echo $sql;
				$a=$wpdb->query($sql);
				$cron=$wpdb->query($cron_sql);
				echo do_shortcode('[create_pdf order_id='.$order_id.' order_item_id='.$order_value->order_item_id.']');
				//$order_file.="Pdf is generated pdf_processing_status : 3 <br/>";
			}*/
			
			
	}
}

//echo $logic_page;


//header('Location: '.$logic_page);

?>
