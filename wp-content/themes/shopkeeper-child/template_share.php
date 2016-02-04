<?php
/*
 * Template Name: Share Template Link
 */

//ini_set("display_errors", "1");
//error_reporting(E_ALL);
require_once 'lib/facebook.php';
@session_destroy();
$status=array();
$config = array();
$config['appId'] = '164866153862009';//'1513158259009174';164866153862009
$config['secret'] = '5cf8a1f894e8f690b8b758fd722f28ac';//'77de2bc36c5af01a1800f5553685b2f5';
//print_r($config);
$facebook = new Facebook($config);

$user = $facebook->getUser();

if (!$user) {
	// Get login URL
	$loginUrl = $facebook->getLoginUrl(array(
		'scope'   => 'publish_actions,user_likes, manage_pages'
		));
	header('location:'.$loginUrl);
}
else{
//echo '<pre>';
//print_r($user);//exit;
$user_profile = $facebook->api('/me');
//print_r($user_profile);
//echo $facebook->getAccessToken();
//echo '</pre>';
$custom_data=get_post_custom(1166);
$msg = do_shortcode('[tooltip_id id=47]');
$title = do_shortcode('[tooltip_id id=46]');;
$uri = home_url();
$desc = do_shortcode('[tooltip_id id=48]');
$pic =  $custom_data['img_link'][0];
$action_name = $custom_data['action_name'][0];
$action_link = $custom_data['action_link'][0];

$attachment = array(
	'access_token' => $facebook->getAccessToken(),
	'message' => $msg,
	'name' => $title,
	'link' => $uri,
	'description' => $desc,
	'picture'=>$pic,
	'actions' => json_encode(array('name' => $action_name,'link' => $action_link))
	);
$status = $facebook->api("/me/feed", "post", $attachment);
//print_r($status);
}
$page_id = "";
	if ( is_single() || is_page() ) {
		$page_id = get_the_ID();
	} else if ( is_home() ) {
		$page_id = get_option('page_for_posts');		
	}
get_header();
 
/*
if(isset($status) && isset($status['id']) && $status['id']!='' )
{	echo '<div class="blue_hover download_ebook"><a href="'.do_shortcode('[tooltip_id id=51]').'" target="_blank"  class="downloadlink">'.do_shortcode('[tooltip_id id=50]').'<i style="" class="vc_btn3-icon fa fa-angle-right" ></i></a></div>';
	echo '<span style="display:none;" class="isshare">1</span>';
}*/
//echo  $data->post_content;
?>
<style>
	.facebook_share .popupcontent{
		margin:0px auto;
		text-align: center;
		padding-top:80px ;
		padding-bottom:60px ;
		}
.facebook_share .popupcontent h4{
	color:#162c5d;
	}
</style>
	<div class="full-width-page facebook_share">
    
        <div id="primary" class="content-area">
           
            <div id="content" class="site-content" role="main">
                
                    
					
					<?php while ( have_posts() ) : the_post(); ?>
        
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div><!-- .entry-content -->
                        
                        <?php
                    
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || '0' != get_comments_number() ) comments_template();
							
						?>
        
                    <?php endwhile; // end of the loop. ?>
    
            </div><!-- #content -->           
            
        </div><!-- #primary -->
    
    </div><!-- .full-width-page -->

<?php
get_footer();
?>
