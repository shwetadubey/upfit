<?php
ini_set('max_execution_time', 0);
/*if( php_sapi_name() !== 'cli' ) {
 die("Meant to be run from command line.\n");
}
*/
// Modify this based on site domain
//$_SERVER['HTTP_HOST'] = 'yoursite.com';

define( 'WP_USE_THEMES', false );
ini_set("display_errors", "1");
  
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;

	$cn=mysqli_connect('localhost','root','kUGvX6vaA1BJkCQ3','upfit');
	if($cn){
		//echo 'connected';
	}
	else{
		echo 'error';
	}

//require_once('wp-load.php');
///echo 'test';

global $wpdb;
$sql="SELECT domain, path FROM up_blogs WHERE archived='0' AND deleted ='0' LIMIT 0,300";
$res = mysqli_query($cn,$sql);
while($r=mysqli_fetch_object($res)){
	$blogs[]=$r;
}
//$blogs = $wpdb->get_results($sql);
//print_r($blogs);
/*$d=curlRequest('http://upfit.de/cron-job/?preview=true');
	echo $d;*/
//echo 'Cron job page link for all sites';
foreach($blogs as $blog) {
	$url = "https://" . $blog->domain . ($blog->path ? $blog->path : '/') . 'cron-job/?preview=true';
	echo $url . "<br>";
	$d=curlRequest($url);
	echo $d;
 //wp_remote_get( 'http://lanetteam.com:8018/html/upfit/cron-job' );
}/*
function get_order_details($url){
	//echo $url;
	$ch = curl_init();
        //$timeout = 5;

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch,CURLOPT_URL,$url);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        if($data === false)
		{
		    echo 'Curl error: ' . curl_error($ch);
		    //echo '<br>';
		}
		else
		{
		    echo'-------------'. $data;//.'Operation completed without any errors';
		    //echo '<br>';
		}

        curl_close($ch);
	}
*/
function curlRequest($url) {
	//echo $url.'\n';
	$username="admin";
	$password="upfit@123";
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	//curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	echo $httpcode;

	/*if(!curl_errno($ch))
	{
	 $info = curl_getinfo($ch);
	 print_r($info);

	 echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
	}*/

	//echo 'here';
	//print_r($ch);
	return $response;
}
?>
