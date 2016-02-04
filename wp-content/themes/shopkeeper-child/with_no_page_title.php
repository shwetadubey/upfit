<?php

/*
 * Template Name: Without Page Title
 */
	
	global $shopkeeper_theme_options;
	
	$page_id = "";
	if ( is_single() || is_page() ) {
		$page_id = get_the_ID();
	} else if ( is_home() ) {
		$page_id = get_option('page_for_posts');		
	}

    $page_header_src = "";

    if (has_post_thumbnail()) $page_header_src = wp_get_attachment_url( get_post_thumbnail_id( $page_id ) );

    $page_title_option = "on";
	
	if (get_post_meta( $page_id, 'page_title_meta_box_check', true )) {
		$page_title_option = get_post_meta( $page_id, 'page_title_meta_box_check', true );
	}
    
?>

<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/css/layout.css">
	<div id="primary" class="content-area">
       
        <div id="content" class="site-content" role="main">
        
       		

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'content', 'page' ); ?>
                    
                <?php   if (function_exists('is_cart') && is_cart()) : ?>
                <?php else: ?>    
                <div class="clearfix"></div>
                <footer class="entry-meta">   
                    <?php // edit_post_link( __( 'Edit', 'shopkeeper' ), '<div class="edit-link"><i class="fa fa-pencil"></i> ', '</div>' );  ?>
                </footer><!-- .entry-meta -->
                <?php endif; ?>

                <?php
                    
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || '0' != get_comments_number() ) comments_template();
                    
                ?>

            <?php endwhile; // end of the loop. ?>

        </div><!-- #content -->           
        
    </div><!-- #primary -->
<script type="text/javascript">
	
	var page = 1; // What page we are on.
	var ppp = 2;
	jQuery('.load_more').live('click',function(){
		var ajaxUrl = "<?php echo admin_url('admin-ajax.php')?>";
		
		//alert('1');
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxUrl,
			data:{"action": "load_more_faq_post",
					offset: (page * ppp),
					ppp: ppp,
					
				},
			beforeSend: function(){		
				//jQuery(".load_more").html('<img src="<?php echo get_template_directory_uri();?>/images/ajax-loader.gif"/>');
			},				
			error:function(ds,dd,ff){
				//console.log(ff);
				}
		}).done(function(data){
				page++;
				//jQuery('#faq').html(data);
				if(jQuery.trim(data)===undefined ||jQuery.trim(data)==="" || data == 0){
					//alert('1');
					
					jQuery('<div class="faq_details"><h3>No More FAQ</h3></div>').appendTo('#faq').hide().fadeIn(1500);
					jQuery('.load_more').hide();
				}
				else
				{
					jQuery(data).appendTo('#faq').hide().fadeIn(2300);
				}
		});
	});
</script>

<?php 


get_footer(); ?>
<!--
 <script src="<?php //echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/validation.js"></script>  
-->
 <script src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/simple-expand.js"></script>  
 
 
