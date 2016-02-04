<?php
/*
if( php_sapi_name() !== 'cli' ) {
 die("Meant to be run from command line.\n");
}
*/
// Modify this based on site domain
//$_SERVER['HTTP_HOST'] = 'yoursite.com';

define( 'WP_USE_THEMES', false );
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;

require_once( dirname( __FILE__ ) . '/wp-load.php' );

if ( ! function_exists( 'wp' ) )
 die( 'Couldn\'t load WordPress :(' );
if ( ! is_multisite() )
 die( 'Multisite is not enabled.' );


global $wpdb;
$sql = $wpdb->prepare("SELECT domain, path FROM $wpdb->blogs WHERE archived='0' AND deleted ='0' LIMIT 0,300", '');

$blogs = $wpdb->get_results($sql);
print_r($blogs);
/*
foreach($blogs as $blog) {
 $command = "http://" . $blog->domain . ($blog->path ? $blog->path : '/') . 'wp-cron.php';
 //echo $command . "\n";
 wp_remote_get( $command );
}*/
?>
