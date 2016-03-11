<?php
/*
 * Template Name: Nutrition plan overview full width Template
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
    //------------------- belboon session variable-------------------//
	if(empty($_SESSION['belboon'])) { // If $_SESSION['belboon'] not set

	// example: http://www.abc.de?belboon=0001cb000205005e720005f2,2022994 

		$_SESSION['belboon'] = $_GET['belboon']; // value of $_GET['belboon'] will be saved in server session 

	}
     get_header(); ?>
     <style type="text/css">
         #menu{text-align: center;  background: #EBEAEA; width: 100%; height: 50px;}
        ul#page-nav {
            display: inline-block;
            margin: 0;
            max-width: 940px;
            width: 940px;
        }
        ul#page-nav li{float: left; padding: 12px 30px 11px;text-transform: capitalize;}
        ul#page-nav li:first-of-type { padding-left: 0;}
        ul#page-nav li a{color: #71848e; font-family: 'Conv_AvenirLTStd-Medium';}
    	nav.nav ul#page-nav{display:none;}
        .sticky {position: fixed;top: 0; z-index: 9999; }
       .nav-header nav {    overflow-x: auto; /* 1 */
          -webkit-overflow-scrolling: touch; /* 2 */
        }

       .nav-heade nav ul {
          text-align: justify; /* 3 */
          width: 30em; /* 4 */
        }


       .nav-heade nav ul:after { /* 5 */
          content: '';
          display: inline-block;
          width: 100%;
        }

       .nav-heade nav ul li {
          display: inline-block; /* 6 */
        }
        .nav-heade nav {
            max-height: 0;
            transition: .6s ease-in-out;
        }

        .show {
            max-height: 15em;
        }
        @media (min-width: 31.25em) {
          .nav-heade nav {
            max-height: none; /* reset the max-height */
            overflow: hidden !important; /* this prevents the scroll bar showing on large devices */
          }

          .nav-heade nav  ul {
            width: 100%;
          }

          .nav-toggle {
            display: none;
          }
        }
		nav.navigation{overflow-x:hidden !important;}
		.nicescroll-cursors{opacity:0 !important;}
     </style>
	 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	 
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>-child/css/jquery.fullPage.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>-child/css/jquery.sidr.light.css">

    <div class="full-width-page <?php  echo ( (isset($page_title_option)) && ($page_title_option == "on") ) ? 'page-title-shown':'page-title-hidden';?>">
    <div id="menu">
        <!-------------------Mobile Menu---------------------->
         <!--<div id="mobile-header"><a id="responsive-menu-button" href="#sidr-main">Menu </a></div>
        <div id="navigation"><nav class="nav"></nav></div>
        <!-------------------End Mobile Menu---------------------->
    </div>
        <div id="primary" class="content-area">
           
            <div id="content" class="site-content nutrition-plans nutrition-overview" role="main">
                
                    
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
    
<?php get_footer(); 
	$nav_header_5_label = get_post_meta(get_the_ID(),'nav_header_5_label',true);
    $nav_header_5_link = get_post_meta(get_the_ID(),'nav_header_5_link',true);
    $nav_header_6_label = get_post_meta(get_the_ID(),'nav_header_6_label',true);
    $nav_header_6_link = get_post_meta(get_the_ID(),'nav_header_6_link',true);
?>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.6.0/jquery.nicescroll.min.js"></script>
<script type="text/javascript">
/*-----------------------------------------*/
// jQuery version
    jQuery(document).on('click','.nav-toggle', function (e) {
      jQuery("nav").toggleClass("show");
      e.preventDefault();
    });
var hadmenu=jQuery('<ul id="page-nav" />');
//var na=jQuery('<nav class="nav"></nav>');

    jQuery('.plan_overview').each(function(){
        //alert(this.id)
        var i=this.id;
        var li=jQuery('<li class="'+i+'_nav"><a href="#'+i+'">'+i+'</a></li>');
        //na.append(jQuery('<li><a href="#'+i+'">'+i+'</a></li>'));
        hadmenu.append(li);
    });
    hadmenu.append('<li class="plan_listing_nav"><a href="<?php echo $nav_header_5_link; ?>"><?php echo $nav_header_5_label; ?></a></li>');
    //na.append('<li><a href="#plan_listing">Gesamtübersicht</a></li>');
    hadmenu.append('<li class="why_upfit_nav"><a href="<?php echo $nav_header_6_link; ?>"><?php echo $nav_header_6_label; ?></a></li>');
    //na.append('<li><a href="#why_upfit">Warum Upfit</a></li>');

  /*  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

        jQuery('#page-nav').hide();
        //jQuery('#mobile-header').show();
       // hadmenu.prepend('<li class="mhide"><a href="javascript:void(0);" class="mhide">Menu</a></li>');
      /*  jQuery('nav.nav').append(hadmenu);
    	/*------------For mobile responsive menu-------------*/
       /* jQuery('#responsive-menu-button').sidr({
              name: 'sidr-main',
              source: '#navigation'
            });*/
       /* jQuery(document).on('click','.sidr-class-mhide',function(){
            jQuery('#responsive-menu-button').click();
        })*/
   /* }
    else
    {*/
    	//jQuery('#mobile-header').hide();;
        var n=jQuery("<nav role='navigation'>");
        var h=jQuery('<header class="nav-header" />');
        n.append(hadmenu);
        h.append(n);
       // h.append('<a href="#" class="nav-toggle">Menu</a>');
    	jQuery('#menu').append(h);
        var distance = jQuery('#menu').offset().top,
            $window = jQuery(window);
            $window.scroll(function() {
                if ( $window.scrollTop() >= distance-50 ) {
                    // Your div has reached the top
                    jQuery('#menu').addClass('sticky');
                }
                else{
                    jQuery('#menu').removeClass('sticky');
                }

            });

var is_init=0;
function dragbleMenu(){
	if(jQuery(window).width()>jQuery("header.nav-header nav ul").width()){
		is_init=1;
		ulw=jQuery("header.nav-header nav ul").width();
		jQuery("header.nav-header nav ul").draggable({ 
		axis: "x",
		containment: "#nav-helper",
		stop: function() {
			var cr=ulw-jQuery(window).width();
			if(jQuery("header.nav-header nav ul").position().left > 1)
			  jQuery("header.nav-header nav ul").css("left", "0px");

			if(jQuery("header.nav-header nav ul").position().left < -cr)
			  jQuery("header.nav-header nav ul").css("left", -cr);
			}
		})
	}
};	
	

jQuery(window).resize(function(){
	/*if(is_init===1){is_init=0; 
		jQuery( "header.nav-header nav ul" ).draggable( "destroy" );
	}*/
	//dragbleMenu();
})
jQuery(document).ready(function($) {
	//dragbleMenu();
	
	jQuery('header.nav-header nav').niceScroll({
        autohidemode: 'true',     // Do not hide scrollbar when mouse out
        cursorborderradius: '0px', // Scroll cursor radius
        background: 'rgba(255,0,0,0)',     // The scrollbar rail color
        cursorwidth: '0px',       // Scroll cursor width
    });
	jQuery('header.nav-header nav').disableSelection()
	var getVar = window.location.href.split('#');
    if(getVar[1]!==undefined){
            var target = jQuery('#'+getVar[1]);
//console.log(getVar[1]);
 if(jQuery('#menu').hasClass('sticky'))
                    {
                        t1=49;
                    }else{
                        t1=99;
                    }
        jQuery('html,body').animate({
                    scrollTop: target.offset().top-t1  
        }, 1000);
        //target.parentNode.scrollTop = target.offsetTop+50;
        $('div[id$=' + getVar[1] + ']')[0].scrollIntoView();
    }

    jQuery('a[href*=#]:not([href=#])').click(function() {
        var pgurl = window.location.href;
        var t=jQuery(this);
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) 
        {
            var target = jQuery(this.hash);
            target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
            if (target.length) 
            {   
                jQuery('ul#page-nav li').removeClass('active');
                t.parents('li').addClass('active');
                if(jQuery('#menu').hasClass('sticky'))
                    {
                        t1=49;
                    }else{
                        t1=99;
                    }
                jQuery('html,body').animate({
                scrollTop: target.offset().top-t1
                }, 1000);
            return false;
            }
        }
    });
});
$window = jQuery(window);
$window.scroll(function() {
    jQuery("div.plan_overview").each(function(){
        var i=this.id
        distance1=jQuery(this).offset().top-50;
        if ( $window.scrollTop() >= distance1 ) {
            jQuery('ul#page-nav li').removeClass('active');
            jQuery('ul#page-nav li.'+i+'_nav').addClass('active');;
        }
    });
    distance1=jQuery('#plan_listing').offset().top-50;
    if ( $window.scrollTop() >= distance1 ) {
        i='plan_listing'
            jQuery('ul#page-nav li').removeClass('active');
            jQuery('ul#page-nav li.'+i+'_nav').addClass('active');
        }
    
     distance1=jQuery('#why_upfit').offset().top-50;
    if ( $window.scrollTop() >= distance1 ) {
        i='why_upfit';
            jQuery('ul#page-nav li').removeClass('active');
           // alert(i);
            jQuery('ul#page-nav li.'+i+'_nav').addClass('active');
        }
});
jQuery(window).resize(function(){
	rearrangeImg();
})
function rearrangeImg(){
	jQuery('.plan_overview  .row').each(function(){
		console.log(jQuery(this).children('.txt_box').children('.details_overview').children('h3'))
		if(jQuery(window).width()<=767){
			var imgDiv=jQuery(this).children('.img_box').detach();
			jQuery(this).children('.txt_box').children('.details_overview').children('h3').after(imgDiv);
		}
		else{
			var imgDiv=jQuery(this).children('.txt_box').children('.details_overview').children('.img_box').detach();
			jQuery(this).prepend(imgDiv);
		}
	})
	
}
rearrangeImg();
jQuery(document).on('click','.price_table',function(e){
e.preventDefault();
window.location.href='<?php echo home_url(); ?>/ernaehrungsplan_erstellen';
});	
</script>
<!--<script src="<?php //echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/validation.js"></script> 

<script src="<?php //echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/jquery.fullPage.js"></script>  -->
