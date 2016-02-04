<?php 
/*
 * Template Name: Order Cancelled Template
 * 
 */
 

?>
<?php
	
	global $shopkeeper_theme_options;
	
	$page_id = "";
	if ( is_single() || is_page() ) {
		$page_id = get_the_ID();
	} else if ( is_home() ) {
		$page_id = get_option('page_for_posts');		
	}

    $page_header_src = "";

    if (has_post_thumbnail()) $page_header_src = wp_get_attachment_url( get_post_thumbnail_id( $page_id ) );
	
	if (get_post_meta( $page_id, 'page_title_meta_box_check', true )) {
		$page_title_option = get_post_meta( $page_id, 'page_title_meta_box_check', true );
	} else {
		$page_title_option = "on";
	}	
	if(!isset($_GET['cancel_order'])|| $_GET['cancel_order']=='false') {
		wp_redirect( home_url());
	}
	else{
?>

<?php get_header(); ?>

	<div class="full-width-page <?php echo ( (isset($page_title_option)) && ($page_title_option == "on") ) ? 'page-title-shown':'page-title-hidden';?>">
    
        <div id="primary" class="content-area">
           
            <div id="content" class="site-content" role="main">
                
               	<div class="entry-content">
						
					<div class="grey">
						<div class="order_cancel_header">					
				
				   <h1 style="color:#162c5d;font-size: 36px;text-align: center;" class="vc_custom_heading main-hedding order_cancelled_heading"><?php echo do_shortcode('[tooltip_id id=33]'); ?></h1>
				   <div class="thank_you_header text-center thank_you_wrapper">
	
						
		
						<div class="thank_you_header_text">			
							<div class="row">
								<div class="xlarge-12 xlarge-centered large-8 large-centered columns">
								   
											<?php 
											$lines = explode(".",do_shortcode('[tooltip_id id=35]'));
											?>
										 <p class="text-light cancel_order_text"><?php echo $lines[0].'.';?>
										 <a href="<?php echo home_url(); ?>/nutrition-plan-creation" ><?php echo $lines[1]; ?></a>
										 </p>
										 <div class="blue_hover">
											<a href="<?php echo home_url(); ?>" class="vc_btn3" style="background-color:#2cab50; color:#ffffff;">Zurück zur Abfrage <i class="fa fa-angle-right"></i></a>
										</div>
										
										
								</div><!-- .xlarge-6-->
							</div><!--	.row	-->                
						</div>
    
					</div>
					</div>
						<!-- order_cancel_header -->
						<span class="divider"></span>
						
					
					<?php
						$page=get_post(1062);
						//print_r($page);
						echo do_shortcode($page->post_content);
					?>
					<!-- grey -->
			
					</div>
                </div><!-- .entry-content -->
                      
            </div><!-- #content -->           
            
        </div><!-- #primary -->
    
    </div><!-- .full-width-page -->
    

<?php 

get_footer();
}
?>
