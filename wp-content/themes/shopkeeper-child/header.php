<?php
	global $shopkeeper_theme_options, $woocommerce, $wp_version;
?>
<!DOCTYPE html>
<!--[if IE 9]>
<html class="ie ie9" <?php language_attributes(); ?>>
<![endif]-->
<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <?php    
    $parent_theme = wp_get_theme('shopkeeper');
    $child_theme = wp_get_theme();
    $child_theme_in_use = false;
    if ($parent_theme->name != $child_theme->name) { 
        $child_theme_in_use = TRUE;
    }    
    $vc_version = "Not activated";
    if (defined('WPB_VC_VERSION')) {
        $vc_version = "v".WPB_VC_VERSION;
    }    
    ?>
    
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    
    
    <!-- ******************************************************************** -->
    <!-- * Custom Favicon *************************************************** -->
    <!-- ******************************************************************** -->
    
    <?php
	if ( (isset($shopkeeper_theme_options['favicon']['url'])) && (trim($shopkeeper_theme_options['favicon']['url']) != "" ) ) {
        
        if (is_ssl()) {
            $favicon_image_img = str_replace("http://", "https://", $shopkeeper_theme_options['favicon']['url']);		
        } else {
            $favicon_image_img = $shopkeeper_theme_options['favicon']['url'];
        }
	?>
    
    <!-- ******************************************************************** -->
    <!-- * Favicon ********************************************************** -->
    <!-- ******************************************************************** -->
    
    <link rel="shortcut icon" href="<?php echo esc_url($favicon_image_img); ?>" />
        
    <?php } ?>
    
    <!-- ******************************************************************** -->
    <!-- * Custom Header JavaScript Code ************************************ -->
    <!-- ******************************************************************** -->
    
    <?php if ( (isset($shopkeeper_theme_options['header_js'])) && ($shopkeeper_theme_options['header_js'] != "") ) : ?>
        <?php echo $shopkeeper_theme_options['header_js']; ?>
    <?php endif; ?>

	
    <!-- ******************************************************************** -->
    <!-- * WordPress wp_head() ********************************************** -->
    <!-- ******************************************************************** -->
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager -->
<?php if(is_wc_endpoint_url('order-received')){
		$order_id=wc_get_order_id_by_order_key($_GET['key']);
		$order1=new WC_order($order_id);
		$order_items=array_values($order1->get_items());
		$net_amount=$order_items[0]['line_subtotal'];
		$belboon=$_SESSION['belboon'];
?>
		<img src="https://www1.belboon.de/adtracking/sale/000021772.gif/oc=<?php echo $order_id ?>&sale=<?php echo $net_amount; ?>&belboon=<?php echo $belboon; ?>" border="0" width="1" height="1" alt="" id="bbconv"/>
		<object class="flash" type="application/x-shockwave-flash" data="https://www1.belboon.de/adtracking/flash.swf" width="1" height="1" >
		<param name="flashvars" value="pgmid=000021772&etype=sale&tparam=sale&evalue=<?php echo $net_amount; ?>&oc=<?php echo $order_id ?>">
		<param name="movie" value="https://www1.belboon.de/adtracking/flash.swf" /></object>
		<script src="https://www1.belboon.de/adtracking/conversion.jssl"></script>
<?php }?>
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-W97B73"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W97B73');</script>
<!-- End Google Tag Manager -->

	<div id="st-container" class="st-container">

        <div class="st-pusher">
            
            <div class="st-pusher-after"></div>   
                
                <div class="st-content">

                    
                    <?php

					$header_sticky_class = "";
					$header_transparency_class = "";
					$transparency_scheme = "";
					
					if ( (isset($shopkeeper_theme_options['sticky_header'])) && ($shopkeeper_theme_options['sticky_header'] == "1" ) ) {
						$header_sticky_class = "sticky_header";
					}
					
					if ( (isset($shopkeeper_theme_options['main_header_transparency'])) && ($shopkeeper_theme_options['main_header_transparency'] == "1" ) ) {
						$header_transparency_class = "transparent_header";
					}
					
					if ( (isset($shopkeeper_theme_options['main_header_transparency_scheme'])) ) {
						$transparency_scheme = $shopkeeper_theme_options['main_header_transparency_scheme'];
					}
					
					$page_id = "";
					if ( is_single() || is_page() ) {
						$page_id = get_the_ID();
					} else if ( is_home() ) {
						$page_id = get_option('page_for_posts');		
					}
					
					if ( (get_post_meta($page_id, 'page_header_transparency', true)) && (get_post_meta($page_id, 'page_header_transparency', true) != "inherit") ) {
						$header_transparency_class = "transparent_header";
						$transparency_scheme = get_post_meta( $page_id, 'page_header_transparency', true );
					}
					
					if ( (get_post_meta($page_id, 'page_header_transparency', true)) && (get_post_meta($page_id, 'page_header_transparency', true) == "no_transparency") ) {
						$header_transparency_class = "";
						$transparency_scheme = "";
					}
					
					/*if ( is_shop() ) {
						$header_transparency_class = "";
					}*/
					
					?>
                    
                    <div id="page_wrapper" class="<?php echo $header_sticky_class; ?> <?php echo $header_transparency_class; ?> <?php echo $transparency_scheme; ?>">
                    
                        <?php do_action( 'before' ); ?>                     
                        
                        <?php
    
						$header_max_width_style = "100%";
						if ( (isset($shopkeeper_theme_options['header_width'])) && ($shopkeeper_theme_options['header_width'] == "custom") ) {
							$header_max_width_style = $shopkeeper_theme_options['header_max_width']."px";
						} else {
							$header_max_width_style = "100%";
						}
						
						?>
                        
                        <div class="top-headers-wrapper">
						
                            <?php if ( (isset($shopkeeper_theme_options['top_bar_switch'])) && ($shopkeeper_theme_options['top_bar_switch'] == "1" ) ) : ?>                        
                                <?php include_once('header-topbar.php'); ?>						
                            <?php endif; ?>
                            
                            <?php if ( isset($shopkeeper_theme_options['main_header_layout']) ) : ?>
								
								<?php if ( $shopkeeper_theme_options['main_header_layout'] == "1" ) : ?>
									<?php include_once('header-default.php'); ?>
                                <?php elseif ( $shopkeeper_theme_options['main_header_layout'] == "2" ) : ?>
                                	<?php include_once('header-centered-2menus.php'); ?>
                                <?php elseif ( $shopkeeper_theme_options['main_header_layout'] == "3" ) : ?>
                                	<?php include_once('header-centered-menu-under.php'); ?>
								<?php endif; ?>
                            <?php else : ?>
                            
                            	<?php include_once('header-default.php'); ?>
                            
                            <?php endif; ?>
							
                        </div>
						
						
