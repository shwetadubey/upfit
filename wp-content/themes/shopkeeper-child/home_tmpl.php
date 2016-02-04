<?php

/*
 * Template Name: Home Template 
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
	
	if (get_post_meta( $page_id, 'page_title_meta_box_check', true )) {
		$page_title_option = get_post_meta( $page_id, 'page_title_meta_box_check', true );
	} else {
		$page_title_option = "on";
	}	
 $path = wp_upload_dir();
   // print_r($path);
	$uppath = $path['baseurl'] . '/foodies/';	
 get_header(); ?>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/tooltipster/tooltipster.css">

 <style type="text/css">

.tooltipster-error {
	border-radius: 20px; 
	padding: 30px; 
	background: rgb(236, 65, 45)  !important;
	border: solid 1px rgb(236, 65, 45) !important;
	color: #ffffff;
}
.tooltipster-error .tooltipster-content {
	text-transform: inherit;
text-align: left;
font-family: 'Conv_AvenirLTStd-Medium' sans-serif;
font-size: 14px;
line-height: 26px;
font-weight: normal;
letter-spacing: 0.5px;
}
</style>

	<div class="full-width-page <?php echo ( (isset($page_title_option)) && ($page_title_option == "on") ) ? 'page-title-shown':'page-title-hidden';?>">
    
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
    <script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/jquery.cookie.js"></script>
<script type="text/javascript">
var v=jQuery('#main_sec div.left_images').clone();
jQuery(window).resize(function(){
if( jQuery(window).width()<768 ) {

jQuery('.left_images').remove();
jQuery('#main_sec').append(v);

jQuery('#myvalcheck').attr('placeholder','Aktuelles Gewicht');
jQuery('#myvalcheck_sec').attr('placeholder','Wunschgewicht');

}else{
	jQuery('#myvalcheck').attr('placeholder','z.B. 105');
	jQuery('#myvalcheck_sec').attr('placeholder','z.B. 80');
}
	
})
var np1=jQuery('#myvalcheck').attr('placeholder');
var np2=jQuery('#myvalcheck_sec').attr('placeholder');
if( jQuery(window).width()<768 ) {

jQuery('.left_images').remove();
jQuery('#main_sec').append(v);

jQuery('#myvalcheck').attr('placeholder',jQuery('.current-weight .input_kg_left').text());
jQuery('#myvalcheck_sec').attr('placeholder',jQuery('.weight-loss .input_kg_left').text());

}else{
	jQuery('#myvalcheck').attr('placeholder',np1);
	jQuery('#myvalcheck_sec').attr('placeholder',np2);
}
function countProperties(obj) {
var prop;
var propCount = 0;

for (prop in obj) {
propCount++;
}
return propCount;
}
var error_msg=new Array();
jQuery.getJSON("<?php echo $uppath.'msg.json'?>", function( data ) {
		//console.log('<?php echo $uppath; ?>')
		var lan=countProperties(data);
		for(var i=1;i<=lan;i++){
			error_msg[i]= data[i];
		}
});
/*var vl=jQuery('<ul class="dt"/>');

jQuery('#testimonial_rotator_504  div.slide ').each(function(){
	vl.append('<li>1</li>')
})
jQuery('#testimonial_rotator_wrap_504').append(vl);
jQuery(document).on( 'cycletwo-update-view', function( e, opts, slideOpts, currSlide ) {
	I=jQuery('.cycletwo-slide-active').index();
	if(I==0 || I==1){
		I=1;
	}
	I=I-1;
	jQuery('ul.dt li').removeClass('active');
	jQuery('ul.dt li:eq('+I+')').addClass('active');
	
})*/

jQuery(document).ready(function(){
	var w=jQuery(window).width();
	v11 = jQuery('#pricing .sub_headding').text();
	//console.log(w);
//	console.log(v);
	if(w>767 && w<=1080)
	{
		ar = v11.split('. (');
		jQuery('#pricing .sub_headding ').html(ar.join('.<br/>('));
	}
	else{
			jQuery('#pricing .sub_headding ').text(v11);
	}
});
jQuery(window).resize(function(){
	/*	if(jQuery(window).width()>767 && jQuery(window).width()<980)
		{
			v = jQuery('.vc_custom_1450265567399').text();
			ar = v.split('. (');
			jQuery('.vc_custom_1450265567399').html(ar.join('<br/> ('));
		}*/
		v11 = jQuery('#pricing .sub_headding').text();
		if(jQuery(window).width()>767 && jQuery(window).width()<=1080)
		{
		//	alert('if');
			ar = v11.split('. (');
			jQuery('#pricing .sub_headding').html(ar.join('.<br/>('));
		}
		else{
		//alert('else');
		jQuery('#pricing .sub_headding').html(v11);
		}
	});

</script>

<?php get_footer(); ?>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 <script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/tooltipster/jquery.tooltipster.min.js"></script>


<script src="<?php echo get_template_directory_uri();?>-child/js/redirect.js"></script>  
