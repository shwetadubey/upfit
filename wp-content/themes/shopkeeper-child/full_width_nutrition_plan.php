<?php
/*
 * Template Name: Nutrition plan creation full width 
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
    $siteurl = site_url();
	$uppath = $siteurl.'/wp-content/uploads'.'/foodies/';
     get_header(); 
//------------------- belboon session variable-------------------//
	if(empty($_SESSION['belboon'])) { // If $_SESSION['belboon'] not set

	// example: http://www.abc.de?belboon=0001cb000205005e720005f2,2022994 

		$_SESSION['belboon'] = $_GET['belboon']; // value of $_GET['belboon'] will be saved in server session 

	}
     if(function_exists(test1)){
		test1();
	 }
     ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/css/layout.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/tooltipster/tooltipster.css">
	<!--   <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/css/jquery.fullPage.css">-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/nanoscroller/jquery.mCustomScrollbar.min.css">
<style type="text/css">/*.select2-input, .select2-search-field{width: 150px !important;}*/
.toggle-btn,input.inp,.zuruck_btn a{transition:0.5s !important;}
.tooltipster-default .tooltipster-content{padding: 10px 20px}
.tooltipster-default {border-radius: 5px;background: #162c5d;}
.tooltipster-arrow{left:2.5px;}
.tooltipster-default { height: 41px; }
.tooltipster-default .tooltipster-content {
    padding: 12px 21px;
    letter-spacing: 0.5px;
    text-transform: capitalize;
}
.inp-s3 input[type=checkbox]:not(old) + label > i{
background-image: url("<?php echo $siteurl; ?>/wp-content/themes/shopkeeper-child/images/check.png");
background-color:#fff;
}
.section4 .inp-s3 .snuts i,
.section4 .inp-s3 .sfruits i{
    background-image: url("<?php echo $siteurl; ?>/wp-content/themes/shopkeeper-child/images/Down_Arrow.png") !important;
    background-color:#BEC8CC;
}
.section4 .inp-s3 .snuts:hover i, .section4 .inp-s3 .sfruits:hover i{
	border: 1px solid #162C5D;
}
.select2-container{position: relative;}
ul.select2-choices{position: absolute !important;}
.tooltipster-base.tooltipster-default.tooltipster-fade.tooltipster-fade-show, .tooltipster-default{border-radius: 50px;background: #162C5D; border:none;}
.select2-drop-mask,.select2-drop { display:none !important ;}
.tt{display:block !important ;}</style>
    <div class="full-width-page <?php echo ( (isset($page_title_option)) && ($page_title_option == "on") ) ? 'page-title-shown':'page-title-hidden';?>">
    
        <div id="primary" class="content-area">
           
            <div id="content" class="site-content nutrition-plans nutrition-plans-creation" role="main">
                
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
    <ul class="page-nav"></ul>
    <script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/tooltipster/jquery.tooltipster.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/nanoscroller/jquery.mCustomScrollbar.concat.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/jquery.cookie.js"></script>
    <script>
    <?php    //print_r($_POST);?>
    siteurls="<?php echo $siteurl;?>";
    var tcheck=0;
    function readHome(){
      
        $userData=JSON.parse(localStorage.getItem("UpfitUserDataHome"));
            if($userData){
                if($userData.cur_weight!=''){
                    jQuery('#myvalcheck').val($userData.cur_weight)
                }
                if($userData.desired_weight!=''){jQuery('#myvalcheck_sec').val($userData.desired_weight)}
                tcheck=$userData.t;
            localStorage.removeItem('UpfitUserDataHome');
        }
    }
    jQuery(document).ready(function(){
       // readHome();

        if(navigator.userAgent.indexOf('Mac') > 0)
        jQuery('body').addClass('mac-os');
    })
    var err_msg=new Array();
	function countProperties(obj) {
          var prop;
          var propCount = 0;

          for (prop in obj) {
            propCount++;
          }
          return propCount;
       }
        /*------------------Swap Key value for foods-----------------------*/
        function swapJsonKeyValues(input) {
            var one, output = {};
            for (one in input) {
                if (input.hasOwnProperty(one)) {
                    output[input[one].text] = input[one].id;
                }
            }
            return output;
        }
    var food_json='';   
    jQuery(document).ready(function($){
        
       (function ($) {
            "use strict";

            $.fn.select2.locales['de'] = {
                formatNoMatches: function () { return "Kein Eintrag gefunden"; },
                formatInputTooShort: function (input, min) { var n = min - input.length; if (n == 1){ return "Bitte geben Sie ein einzelnes Zeichen auf der am besten"; } return n == 2 ? "Bitte geben Sie die beiden Charaktere am meisten" : "Bitte geben " + n + "höchstens"; },
                formatInputTooLong: function (input, max) { var n = input.length - max; if (n == 1){ return "Bitte geben Sie ein einzelnes Zeichen zumindest"; } return n == 2 ? "Bitte geben Sie mindestens zwei Zeichenل حرفين على الأقل" : "Bitte geben " + n + " Mindestens "; },
                formatSelectionTooBig: function (limit) { if (limit == 1){ return "Sie können nur eine Wahl entscheiden"; } return limit == 2 ? "Sie können nur zwei Möglichkeiten wählen" : "Sie können wählen " + limit + " nur Auswahl"; },
                formatLoadMore: function (pageNumber) { return "Laden Sie weitere Suchergebnisse…"; },
                formatSearching: function () { return "Lädt..."; }
            };

            $.extend($.fn.select2.defaults, $.fn.select2.locales['de']);
        })(jQuery);
    	$("div[id^='section']").each(function(){
    		jQuery('.page-nav').append(jQuery('<li class="tooltip" title="'+$('#'+this.id+' .form_heading ').text().toLowerCase()+'" />').append('<a href="#'+this.id+'" data-nav="1"></a>'));
    	});
        
        jQuery('.tipso_head').tipso({
                speed       : parseInt(100),
                background  : "#182c5f",
                color       : "#ffffff",
                position    : 'right',
                width       : parseInt(215),
               useTitle: false,
                onBeforeShow : function(element, tips){
                    element.tipso('update', 'content', 'the title is a random number');
                    element.tipso('update', 'titleContent', Math.random());
                }
               // onBeforeShow:function(){console.log('This is beforeShow');jQuery('.tipso_bubble').addClass('active');},
				//onShow:function(){jQuery('.tipso_bubble').addClass('active');}
            });;
        jQuery('.tooltip').tooltipster({position: 'right'});
        jQuery('.tipso_head, .tipso_head a').mouseenter(function(){
            //jQuery('.tipso_head').tipso('hide');
           //jQuery('.tipso_bubble').addClass('active');
           //jQuery('.tipso_head').tipso('show');
        });
         jQuery('.tipso_head, .tipso_head a').mouseout(function(){
           //jQuery('.tipso_bubble').removeClass('active');
        });
    	jQuery('ul.page-nav li:first').addClass('active');
    	
		$.getJSON( "<?php echo  $uppath.'foods.json'?>", function( data ) {
            //console.log(data);
            $(".multiple-food").empty();
            for(var i=0;i<data.length;i++){
                //console.log(data[i]);
                //$(".multiple-food").append('<option value="'+data[i]+'">'+data[i]+'</option>');
            }
        }).done(function(data){
		
          //  food_json=swapJsonKeyValues(data);
         //   console.log(food_json);
            var t =new Array();
           // console.log('template='+$.cookie("UpfitUserData"));
                if($.cookie("UpfitUserData")){
                    if($userData.exclude!=''){ 
						
                        $list=$userData.exclude.split(',');
						// console.log('list='+$list);
                        for(var i=0;i<$list.length;i++){
                        //    console.log('data'+$list[i])
                            if($list[i]!==undefined){
								t.push($list[i]);
                            }
                        }
                    }
                }
              
               // console.log( jQuery(".multiple-food").val())
                $eventSelect=jQuery(".multiple-food");
                $.extend($.fn.select2.defaults, $.fn.select2.locales['de']);
                $eventSelect.select2({ placeholder: "z.B. Apfel, Rindfleisch...",multiple: true, 
    					ajax: {
    						url: "<?php echo  $uppath.'foods.json'?>",
    						dataType: "json",
    						type: "GET",
    						data: function (params) {
    							var sq=jQuery('#s2id_autogen1').val();
    							sq=sq.toString();
    							if(sq.length>0){
                                    jQuery('.select2-drop').show();
    								jQuery('.select2-drop').addClass('tt');
    								return {
    										params: params
    									};
    								}
    								else{
                                        jQuery('.select2-drop').removeClass('tt');
    									jQuery('.select2-drop').hide()
    									//jQuery('ul.select2-results,.select2-drop.select2-drop-multi').hide()
    								}
    							
    							
    						},
    						 results: function (data) {
    							// console.log()
    							var sq=jQuery('#s2id_autogen1').val();
    							sq=sq.toString().toLowerCase();
    							var myResults = [];
    							$.each(data, function (index, item) {
    								var tx=item.text;
    								tx=tx.toString().toLowerCase();
    								//alert(sq)
    								//alert(sq.lenght());
    								jQuery(document).find('.select2-drop').css('display','block !important');
    								if(tx.indexOf(sq)> -1 && sq.length>0){
    									myResults.push({
    										'id': item.text,
    										'text': item.text
    									});
    								}
    							});
    							return {
    								results: myResults
    							};
    						}
    					},
                        initSelection: function (element, callback) {
                                callback($.map(element.val().split(','), function (id) {
                                    return { id: id, text: id };
                                }));
                                    //return t;
                            }
					}).on('select2-focus', function(){
                         jQuery('#select2-drop').addClass('call');

						//var sq=jQuery('#s2id_autogen1').val();
  					})//.on("select2-close", function() {
						/*var w=0;
						$('ul.select2-choices').width(0);
						jQuery('ul.select2-choices li').each(function(){
							w+=jQuery(this).width();
						});;
						var computedWidth = 0;
						$.each($('ul.select2-choices').children(), function(i) {
							computedWidth += $('ul.select2-choices').children().eq(i).outerWidth();
						});

						$('ul.select2-choices').width(computedWidth)*/

					//})//.on("change", function(e) {
				  // mostly used event, fired to the original element when the value changes
					
						/*var computedWidth = 0;
						$.each($('ul.select2-choices').children(), function(i) {
							computedWidth += $('ul.select2-choices').children().eq(i).outerWidth();
						});
						$('ul.select2-choices').width(computedWidth);*/
						
					
					//});
                    if(t.length>0){
                        if(jQuery(".multiple-food").select2('val',t,true)){
                             jQuery('#select2-input').attr('placeholder','');
                        }else{
                                 jQuery('#s2id_autogen1').attr('placeholder','z.B. Apfel, Rindfleisch...')
                        }
                    }
                    else{
                        jQuery('#s2id_autogen1').attr('placeholder','z.B. Apfel, Rindfleisch...')
                    }
                   
                   
                jQuery('#s2id_autogen1').bind('focus', function(event){
                             jQuery('#s2id_autogen1').attr('placeholder','')
                });//
				/*jquery('.select2-input').focus(function(){
					var vv=jQuery('ul.select2-choices').width();
                        jQuery('ul.select2-choices').removeAttr('style');
                        jQuery('ul.select2-choices').width(vv);
					
				})*/
				/*jQuery(document).on('click','.select2-search-choice-close',function(){
					/*setTimeout(function(){
							var computedWidth = 0;
						$.each($('ul.select2-choices').children(), function(i) {
							computedWidth += $('ul.select2-choices').children().eq(i).outerWidth();
						});
						$('ul.select2-choices').width(computedWidth);
					},200)*/
				/*})*/
				
               /*  jQuery('ul.select2-choices  , .select2-input').bind('keydown', function(event){/*
						var c=jQuery('ul.select2-choices li').length;
                    if(jQuery('ul.select2-choices li').hasClass('select2-search-choice-focus') && c>2)
                    {
						var p=jQuery('ul.select2-choices li.select2-search-choice-focus').position();
						console.log(p)
						var v=jQuery('ul.select2-choices li.select2-search-choice-focus').index();
                   // alert(v);
                        if(v===0){
                           $('ul.select2-choices').animate({ 'left': jQuery('ul.select2-choices').width()-jQuery('.select2-container').width() });
                           // $('ul.select2-choices').animate({ 'left': '0'});
                        }
                        else{
                            var vv=jQuery('ul.select2-choices').width()-jQuery('.select2-container').width();
                            for(i=v;i>0;i--){
                                vv-=jQuery('ul.select2-choices li:eq('+i+')').width()
                            }
							if(jQuery('ul.select2-choices li').lenght>2){
							$('ul.select2-choices').animate({ 'left': vv }) }
                           // $('ul.select2-choices').animate({ 'left': 'auto' });
                        }
                    }
                    else{
                        var vv=jQuery('ul.select2-choices').width();
                        jQuery('ul.select2-choices').removeAttr('style');
                        jQuery('ul.select2-choices').width(vv);
						var computedWidth = 0;
						$.each($('ul.select2-choices').children(), function(i) {
							computedWidth += $('ul.select2-choices').children().eq(i).outerWidth();
						});
            		$('ul.select2-choices').width(computedWidth);
                    }*/
              /*  })*/
        });
        jQuery('ul.select2-choices').focusout(function(){
            jQuery('.select2-searching').text('fgfgff');
               /* var vv=jQuery('ul.select2-choices').width();
                jQuery('ul.select2-choices').css({'left':0});
                 jQuery('ul.select2-choices').removeAttr('style');
                jQuery('ul.select2-choices').width(vv);
        	var computedWidth = 0;
               	$.each($('ul.select2-choices').children(), function(i) {
                    computedWidth += $('ul.select2-choices').children().eq(i).outerWidth();
                });
            	$('ul.select2-choices').width(computedWidth);*/
            
        })
        jQuery('.select2-input').keydown(function(){jQuery('.select2-searching').text('fgfgff');})
        jQuery('.select2-input').keyup(function(){jQuery('.select2-searching').text('fgfgff');})
        jQuery('.select2-input').focus(function(){
             jQuery('li.select2-searching').text('fgfgff');
				/*jQuery('ul.select2-choices').removeAttr('style');
				  var computedWidth = 0;
				$.each($('ul.select2-choices').children(), function(i) {
				  computedWidth += $('ul.select2-choices').children().eq(i).outerWidth();
				});
				$('ul.select2-choices').width(computedWidth);*/
            })
			jQuery('.select2-drop').css('border','none !important;');
        $.getJSON("<?php echo  $uppath.'msg.json'?>", function( data ) {
             var lan=countProperties(data);
             for(var i=1;i<=lan;i++){
               err_msg[i]= data[i];
               if(jQuery('[data-tooltip-id="'+i+'"]').text()!='' && data[i]!='' ){

                jQuery('[data-tooltip-id="'+i+'"]').addClass('tipso').attr('data-tipso',data[i]);
               }
               
             }
        }).done(function(){
            jQuery('.tipso').tipso({
                speed       : parseInt(100),
                background  : "#182c5f",
                color       : "#ffffff",
                position    : 'right',
                width       : parseInt(200),
                delay       : parseInt(100),
                useTitle    : false,
                width:			250
            });
			jQuery('.circle_info').click(function(){
				// jQuery('.tipso').tipso('show');
			})
        });

        return false;
	});

function move(e)
{
    if(window.event) // IE
    {
        ek = window.event.keyCode;

        if (ek==37)
            document.getElementById('DIV1').style.left = (document.getElementById('DIV1').style.left - 5);
        if (ek==39)
            document.getElementById('DIV1').style.left = (document.getElementById('DIV1').style.left + 5);
    }
    else // Other browsers
    {
        ek = e.which

        if (ek==37)
            document.getElementById('DIV1').style.left = (document.getElementById('DIV1').style.left - 5);
        if (ek==39)
            document.getElementById('DIV1').style.left = (document.getElementById('DIV1').style.left + 5);
    }
}

    </script>
<?php get_footer(); ?>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/validation.js"></script> 
<script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/transition.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/dropdown.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/jquery.ui.touch-punch.min.js"></script>
<!--<script src="<?php//echo get_template_directory_uri().'/../shopkeeper-child'; ?>/js/jquery.fullPage.js"></script>  -->
