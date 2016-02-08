jQuery('.nutrition_type:eq(0)').removeProp('checked');

/*--------------------Read localstorage data-------------*/
//console.log(localStorage.getItem("UpfitUserData"))
readlocal();
readHome();
$act=50;
function get_sec1_values_ByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    
        results = regex.exec(location.search);
        
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function readlocal(){

	var current_weight = localStorage.getItem("myvalcheck");
	var desired_weight = localStorage.getItem("myvalcheck_sec");
	
	if(jQuery.trim(current_weight) !== ''){
		
		jQuery('#myvalcheck').val(current_weight);
	}
	if(jQuery.trim(desired_weight) !== ''){
		jQuery('#myvalcheck_sec').val(desired_weight);
	}
	if(jQuery('#myvalcheck').val() !== '' && 	jQuery('#myvalcheck_sec').val() !== '')
	{
		 jQuery("html, body").animate({
				 scrollTop: jQuery('#section-2').offset().top
			}, 500);
			jQuery('.page-nav li:first-child').addClass('done');
	}
		$userData=JSON.parse(localStorage.getItem("UpfitUserData"));
		if($userData){
			var cw1=jQuery('#myvalcheck').val();
			var dw1=jQuery('#myvalcheck_sec').val();

			if(cw1 === undefined && $userData.cur_weight!=''){
				
				jQuery('#myvalcheck').val($userData.cur_weight)
			}
			if(dw1 === undefined && $userData.desired_weight!=''){
				jQuery('#myvalcheck_sec').val($userData.desired_weight);
			}
			if($userData.allergies!=''){
				var algs=$userData.allergies;
				
				var algs_name=algs.split(',');
				for(var i=0;i<countProperties(algs_name);i++){
				//	console.log(algs_name[i])
					jQuery('input[value='+algs_name[i]+']').click()
				}
			}
			if($userData.age!=''){jQuery('#age').val($userData.age)}
			if($userData.daily_activity!=''){$act=$userData.daily_activity}
			if($userData.fruit!=''){
				var fts=$userData.fruit;
				var fts_name=fts.split(',');
				for(var i=0;i<countProperties(fts_name);i++){
					jQuery('input[value='+fts_name[i]+']').click()
				}
			}
			if($userData.gender!=''){
				if($userData.gender==='m'){
					jQuery('input[value=m]').click()
				}else{
					jQuery('input[value=f]').click()
				}
			}
			if($userData.height!=''){jQuery('#height').val($userData.height)}
			
			if($userData.is_time_to_cook!=''){jQuery('input[value='+$userData.is_time_to_cook+']').click()}
			if($userData.most_buy!=''){jQuery('input[value='+$userData.most_buy+']').click()}
			if($userData.nutrition_type!=''){
				var ntts=$userData.nutrition_type;
				var ntts_name=ntts.split(',');
				for(var i=0;i<countProperties(ntts_name);i++){

					jQuery('input[value='+ntts_name[i]+']').click();					
				}
			}
			if($userData.nuts!=''){
				var nts=$userData.nuts;
				var nts_name=nts.split(',');
				for(var i=0;i<countProperties(nts_name);i++){
					jQuery('input[value='+nts_name[i]+']').click()
				}
			}
			if($userData.sweet_tooth!=''){jQuery('input[value='+$userData.sweet_tooth+']').click()}
			if($userData.where_food_buy!=''){jQuery('input[value='+$userData.where_food_buy+']').click()}
		}
 }

//jQuery("html, body").animate({ scrollTop: 0 }, "fast");
/*----------------Tipso Tool tip-----------*/
jQuery(window).ready(function($){
 //jQuery("html, body").animate({ scrollTop: 0 }, "fast");
	/*---------------DropDown--------*/
	jQuery(document).on('click','.sn',function(){
		var st=100;
		var t=0;
		if(jQuery(this).children('.snuts').hasClass('active')){
			 st=300;
			 t=1;
		}
		else{
			 st=100;
		}
		jQuery(this).children('.snuts').toggleClass('active');
		
		jQuery('.nuts').each(function(){
			jQuery(this).parents('.inp-s3').parents('li').fadeToggle(st);
			if(t===0){st=st+100}
			;
		});

	});
	jQuery(document).on('click','.sf',function(){
		var st=100;
		var t=0;
		if(jQuery(this).children('.sfruits').hasClass('active')){
			 st=300;
			 t=1;
		}
		else{
			 st=100;
		}
		jQuery(this).children('.sfruits').toggleClass('active');
		jQuery('.fruit').each(function(){
			jQuery(this).parents('.inp-s3').parents('li').fadeToggle(st);
			if(t===0){st=st+100}
		});

	});
	
	/*----------------Remove Dot on Mobile View-------*/
	function checkdot(){
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			jQuery('ul.page-nav').hide();
		}
		else
		{
			jQuery('ul.page-nav').show();
		}
	}
checkdot();
});
jQuery('.nuts,.fruit').each(function(){
	jQuery(this).parents('.inp-s3').parents('li').hide();
});
var errors=false;
if(err_msg===undefined || err_msg.length<2 )
{
	err_msg=['', "Bitte gib Dein aktuelles Gewicht und Dein Wunschgewicht ein."  ,  
"Dein Wunschgewicht muss mindestens 1 kg kleiner sein, als Dein aktuelles Gewicht."  ,  
"Bitte gib Dein aktuelles Gewicht ein."  ,  
"Bitte gib Dein Wunschgewicht ein."  ,  
"Bitte gib Dein Geschlecht an."  , "Bitte gib Dein Alter an."  ,"Bitte gib Deine KÃ¶rpergrÃ¶ÃŸe an."  ,  
"Sehr gering"  ,  
"Gering xxx"  ,  
"Mittel xxx"  ,  
"ErhÃ¶ht xxx"  ,  
"Hoch xxx"  ,  
"Sehr hoch xxx"  ,  
"Der kleinste Punkt der Skala entspricht einer sehr geringen AlltagsaktivitÃ¤t, geprÃ¤gt durch kaum und sehr passive Bewegung, Ã¼berwiegend liegendes oder sitzendes Verweilen."  ,  
"Der hÃ¶chste Punkt der Skala entspricht einem sehr aktivem Alltagsverhalten, geprÃ¤gt durch viel und intensive Bewegung, (nahezu) tÃ¤gliches Sporttreiben oder kÃ¶rperlich fordernde Arbeit wie z.B. Fahrradkurrier."  ,  
"Pescetarier verzichten auf den Verzehr des Fleisches gleichwarmer Tiere, nicht jedoch auf Fisch."  ,  
"Flexitarier verzichten nicht komplett, sondern zeitweise bzw. gelegentlich auf Fleischkonsum."  ,  
"paleo (auch SteinzeiternÃ¤hrung) ist eine ErnÃ¤hrungsform, die sich an der vermuteten ErnÃ¤hrung der Altsteinzeit orientiert, in der Ackerbau und Viehzucht vermehrt betrieben wurden. FÃ¼r alle Details siehe: www.paleo360.de"  ,  
"Sorry. Nach diesen EinschrÃ¤nkungen wÃ¤re leider nicht mehr genÃ¼gend Essensvielfalt in Deinem ErnÃ¤hrungsplan gegeben. Versuche es gern bald wieder, denn wir fÃ¼gen tÃ¤glich neue Gerichte hinzu und hoffen Dir zeitnah einen fÃ¼r Dich passenden ErnÃ¤hrungsplan zur VerfÃ¼gung stellen zu kÃ¶nnen. Wenn Du dringend loslegen musst, dann versuche einige EinschrÃ¤nkungen, auf die Du verzichten kannst, zu entfernen."  ,  
"Bitte sage uns, ob und wie du naschst."  ,  
"Bitte sage uns, wieviel Zeit/Lust Du zum Kochen pro Tag hast."  ,  
"Bitte sage uns, wo Du Lebensmittel kaufst."  ,  
"Bitte sage uns, worauf Du beim Kauf besonders achtest."  ,  
"Tooltip"  ,"Bitte gib Deinen Vornamen an."  ,  
"Bitte gib Deinen Nachnamen an."  ,  
"Bitte gib eine (gÃ¼ltige) E-Mail-Adresse an."  ,  
"Bitte gib eine (gÃ¼ltige) Adresse an."  ,  
"Bitte gib eine (gÃ¼ltige) Postleitzahl an."  ,  
"Bitte gib Deinen Ort an."  ,  
"Bitte akzeptiere unsere Allgemeine GeschÃ¤ftsbedingungen, um die Bestellung abzuschlieÃŸen."  ,  
"Vielen Dank fÃ¼r Deinen Einkauf"  ,  
"Sorry, etwas ist schief gelaufen"  ,  
"Deine Bestellung ist bei uns eingegangen."  ,  
"Sorry, Deine Bestellung konnte technisch leider nicht ausgefÃ¼hrt werden. Bitte versuche es noch einmal."  ,  
"Deine Zahlung ist bei uns eingegangen."  ,  
"Du bekommst zudem noch eine Email mit dem Download-Link, so dass Du jederzeit Zugriff auf Deinen ErnÃ¤hrungsplan hast." ];

}
else{

}
/*--------Remove place holder text-----------*/
jQuery('input[placeholder]').on('focus',function(){
var $this = jQuery(this);
$this.data('placeholder',$this.prop('placeholder'));
$this.removeAttr('placeholder')
}).on('blur',function(){
var $this = jQuery(this);
$this.prop('placeholder',$this.data('placeholder'));
});
/*--------End Remove place holder text-----------*/

jQuery(document).ready(function ($) {
	$('.ui.dropdown').dropdown();

	$('.ui.buttons .dropdown.button').dropdown({
		action: 'combo'
	});

jQuery('input[name=q1],input[name=q2],input[name=q3],input[name=q4],input[name=group3]').click(function(){
	var cn=jQuery(this).attr('name');
	jQuery('input[name='+cn+']').each(function(){
		jQuery(this).parents('label.toggle-btn').removeClass('success');
	});
	if(cn==='group3'){jQuery('.error_sex').parents('li.error_color').removeClass('error_color');
					jQuery('.error_sex').empty();
					}
	
	jQuery(this).parents('label.toggle-btn').parents('.toggle-btn-grp.joint-toggle').parents('li.error_color').children('.text-info').empty();
	jQuery(this).parents('label.toggle-btn').parents('.toggle-btn-grp.joint-toggle').parents('li.error_color').removeClass('error_color');
	jQuery(this).parents('label.toggle-btn').addClass('success');
});
var k=0;
jQuery('label.toggle-btn').one('touchend', function(e) {
        e.preventDefault();
      jQuery(this).children('input[type="radio"]').click();
    });
/*jQuery(document).on('click','.toggle-btn',function(){
	jQuery(this).children('input[type="radio"]').click();
});*/
jQuery( ".tooltip" ).tooltip({	position: {
	my: "center bottom-20",
	at: "center top",
	using: function( position, feedback ) {
		$( this ).css( position );
		$( "<div>" )
		.addClass( "arrow" )
		.addClass( feedback.vertical )
		.addClass( feedback.horizontal )
		.appendTo( this );
	}
}});

jQuery(window).resize(function(){


})

/*-----------------------------TRACKER------------------*/
var tooltip = $('<div id="tooltip" class="drtooltip " />').css({
    position: 'absolute',
    top: -25,
    left: -10
}).hide();
if(jQuery(window).width()<767){
    		$b='<br><br>';
    	}
    	else{
    		$b='<br>';
    	}
var tooltipmb=$('<div  id="tooltipbm" />').css({ position: 'absolute',top: -25,left: -10}).hide();
$("#slider").slider({
    value: $act,
    min: 0,
    max: 100,
    step: 1,
    slide: function(event, ui) {
    	
    	uival=jQuery('#slider').slider("option", "value")
			if(uival<19.99){	msg=err_msg[8].replace('xxx', uival+$b);}
			else if(uival>=20 && uival<39.99 ){	msg=err_msg[9].replace('xxx', uival+$b);	}
			else if(uival>=40 && uival<59.99){msg=err_msg[10].replace('xxx', uival+$b);}
			else if(uival>=60 && uival<79.99){msg=err_msg[11].replace('xxx', uival+$b);}
			else if(uival>=80 && uival<89.99){msg=err_msg[12].replace('xxx', uival+$b);}
			else if(uival>=90 && uival<=100){msg=err_msg[13].replace('xxx', uival+$b);}
        tooltip.html('<span class="content-text">'+msg+'</span><div class="arrow_bottom"></div>');
         tooltipmb.html('<span class="content-text">'+msg+'</span>');
        if(jQuery(window).width()>640){
        	 tooltip.show();	
        }
        else{
        	tooltipmb.show();	
        }
       
    },stop: function(event, ui) {
                   tooltip.hide();;
                   tooltipmb.hide();	

            },
    change: function(event, ui) {}
}).find(".ui-slider-handle").append('<img class="svg" src="https://upfit.de/wp-content/themes/shopkeeper-child/images/slider_radio_btn.svg">').append(tooltip).hover(function() {
	uival=jQuery('#slider').slider("option", "value")
			if(uival<19.99){	msg=err_msg[8].replace('xxx', uival+$b);}
			else if(uival>=20 && uival<39.99 ){	msg=err_msg[9].replace('xxx', uival+$b);	}
			else if(uival>=40 && uival<59.99){msg=err_msg[10].replace('xxx', uival+$b);}
			else if(uival>=60 && uival<79.99){msg=err_msg[11].replace('xxx', uival+$b);}
			else if(uival>=80 && uival<89.99){msg=err_msg[12].replace('xxx', uival+$b);}
			else if(uival>=90 && uival<=100){msg=err_msg[13].replace('xxx', uival+$b);}
        tooltip.html('<span class="content-text">'+msg+'</span><div class="arrow_bottom"></div>');
        tooltipmb.html('<span class="content-text">'+msg+'</span>');
         if(jQuery(window).width()>640){
        	 tooltip.show();	
        }
        else{
        	tooltipmb.show();	
        }
}, function() {
    tooltip.hide();
    tooltipmb.hide();	
});
jQuery('.slider_wrp').append(tooltipmb);
$(function(){
    jQuery('img.svg').each(function(){
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');
    
        jQuery.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');
    
            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }
    
            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');
            
            // Check if the viewport is set, else we gonna set it if we can.
            if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
            }
    
            // Replace image with new SVG
            $img.replaceWith($svg);
    
        }, 'xml');
    
    });
});
var error_msg = ['', '', 'Dein Wunschgewicht muss mindestens 1 kg kleiner sein, als dein aktuelles Gewicht.', 'Bitte gib Dein aktuelles Gewicht ein.', 'Bitte gib Dein Wunschgewicht ein.', 'nur numerische Zeichen sind erlaubt'];
var err_msg1 = ['', 'Bitte gib Dein aktuelles Gewicht und Dein Wunschgewicht ein.',
'Dein Wunschgewicht muss mindestens 1 kg kleiner sein, als Dein aktuelles Gewicht.',
'Bitte gib Dein aktuelles Gewicht ein.', 'Bitte gib Dein Wunschgewicht ein.',
'Bitte gib Dein Geschlecht an.', 'Bitte gib Dein Alter an.', 'Bitte gib Deine KÃ¶rpergrÃ¶ÃŸe an.', '', ''];



/*---------Section-1 Weight & Aim ----------------*/

var first_in = '', sec_in = '';
var patr = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
var age_pattr = /^[0-9]{2}$/;
var height_pattr = /^[0-9]{1,3}$/;
section1();
var pr=0;
function sec_1_error_check(){
	er = false;
	if (jQuery('#myvalcheck').val().trim() == '')
	{
		$('.current-weight').addClass('error_color');
		jQuery('.first_err').html('<strong>' + err_msg[3] + '</strong>');
		if (er === false)	{			er = true;		}
	}
	if (jQuery('#myvalcheck_sec').val().trim() == '')
	{
		$('.weight-loss').addClass('error_color');
		jQuery('.sec_err').html('<strong>' + err_msg[4] + '</strong>');
		if (er === false)	{			er = true;		}
	}
	if (er === true)
	{
		errors=true;
	}
	else 
	{
		var text1 = jQuery('#myvalcheck').val();
		var text2 = jQuery('#myvalcheck_sec').val();
		text1 = parseInt(text1.replace(',', '.'));
		text2 = parseInt(text2.replace(',', '.'));
		if (text2 >= text1) {
			jQuery('.sec_err').html('<strong>' + error_msg[2] + '</strong>');
			jQuery('.weight-loss').addClass('error_color');
                //jQuery('.info-tooltip').removeClass('hidden');
                errors=true;
        }
        else {
        	jQuery('.sec_err').empty();
        	 errors=false;
            //jQuery('.info-tooltip').addClass('hidden');
        }
    }
    return errors;
}

jQuery('.btnext').click(function (e) {
	e.preventDefault();
	sec_1_error_check();
	if(errors===false){
		plan_section();
	}
	
});
jQuery('.inp').focusin(function () {
	jQuery('.info-tooltip').addClass('hidden');
});
jQuery('#myvalcheck.inp').focusin(function () {
	jQuery('.first_err').empty();
	$('.current-weight').removeClass('error_color');
});
jQuery('#myvalcheck_sec.inp').focusin(function () {
	jQuery('.sec_err').empty();
	$('.weight-loss').removeClass('error_color');
});

function section1() {

	jQuery('#myvalcheck').blur(function () {
			var v = jQuery(this).val().trim();

            //alert(v);
            var vv = v.split(",");
            if (vv[0].length <= 2)
            {
            	patr = /^[0-9]{1,2}[,]*[0-9]{1}$/;
            }
            else
            {
            	patr = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
            }

			if (v.match(patr) && vv[0].length <= 3)
			{
				first_in = jQuery('#myvalcheck').val();
                //alert('Great, you entered an E-Mail-address'+first_in);
                $('.current-weight').removeClass('error_color');
                $('.first_err').empty();
            }
            else
            {
                //alert('not');
                $('.current-weight').addClass('error_color');
                if (v.length == 0)
                {
                	jQuery('.first_err').html('<strong>' + err_msg[3] + '</strong>');
                }
                else {
                	jQuery('.first_err').html('<strong>' + err_msg[3] + '</strong>');
                }
            }

    });
	jQuery('#myvalcheck_sec').blur(function () {
		var v = jQuery(this).val().trim();
            var vv = v.split(",");
            if (vv[0].length <= 2)
            {
            	patr = /^[0-9]{1,2}[,]*[0-9]{1}$/;
            }
            else
            {
            	patr = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
            }
            if (v.match(patr) && vv[0].length <= 3)
            {
            	sec_in = jQuery('#myvalcheck_sec').val();
                //alert('Great, you entered an E-Mail-address');
                $('.weight-loss').removeClass('error_color');
                $('.sec_err').empty();
				errors=false;
            }
            else
            {
				errors=true;
            	
                //alert('hi');

                if (v.length == 0)
                {
					$('.weight-loss').addClass('error_color');
                	jQuery('.sec_err').html('<strong>' + err_msg[4] + '</strong>');
                }
                else if(parseInt(v)===NaN){
					$('.weight-loss').addClass('error_color');
                	jQuery('.sec_err').html('<strong>'+ err_msg[5]+'</strong>');
                }
                else if (sec_in.length > first_in.length)
                {
			$('.weight-loss').addClass('error_color');
                	jQuery('.sec_err').html('<strong>' + err_msg[2] + '</strong>');
                }
		else if(!v.match(patr)){
			$('.weight-loss').addClass('error_color');
                	jQuery('.sec_err').html('<strong>' + err_msg[4] + '</strong>');
		}
                
                errors=true;
            }
        });
}




/*-----Section 2----------*/
function show_error_age(){
	var st=jQuery('<strong/>').append(err_msg[6]);;
	jQuery('.error_age').empty().append(st);
	jQuery('#age').parents('li').addClass('error_color');
	errors=true;
	
};

function hide_error_age(){
	jQuery('.error_age').text('');
	jQuery('#age').parents('li').removeClass('error_color');
	if(!errors)	errors=false;
	
};
function show_error_height(){
	var st=jQuery('<strong/>').append(err_msg[7]);;
	jQuery('.error_height').empty().append(st);
	jQuery('#height').parents('li').addClass('error_color');
	errors=true;
};

function hide_error_height(){
	jQuery('.error_height').text('');
	jQuery('#height').parents('li').removeClass('error_color');
	if(!errors)errors=false;	
};

function check_error_age(){
	var age =jQuery('#age').val();
	if(age==='')
	{
		show_error_age();

	}else{
		if (jQuery.trim(age) === '') {
			show_error_age();
		}
		else if (!age.match(age_pattr)) {
			show_error_age();
		}
		else{hide_error_age();}
	}
}
function check_error_height(){
	var height = jQuery('#height').val();
	if(height===''){
		show_error_height();
	}else{
		//height = parseInt(jQuery('#height').val());
		if (jQuery.trim(height) === '') {
			show_error_height();
		}
		else if (!height.match(height_pattr) || parseInt(height) >= 300) {
			show_error_height();
		}
		else{
			hide_error_height();
		}
	}
}
function sec_2_error_check(){
		if ($('input[name=group3]:checked').length <= 0)
		{
			var st=jQuery('<strong/>').append(err_msg[5]);;
			jQuery('.error_sex').empty().append(st);
			jQuery('.error_sex').parents('li.animated.zoomIn').addClass('error_color');;
			errors=true;

		}
		else
		{
			jQuery('.error_sex').text('');
			jQuery('.error_sex').parents('li.error_color').removeClass('error_color');
			errors=false;
		}
		check_error_age();
		check_error_height();
			if(errors===true){sec=1;}else{sec=0;}
		return errors;
		
}
function section2() {

	jQuery('#age').blur(function(){
		check_error_age();
	})
	jQuery('#height').blur(function(){
		check_error_height();
	})
	
	jQuery('#section2').click(function () {
		sec_1_error_check();
		sec_2_error_check();
	});

}
section2();
sec=0;
/*----------------------Section 3-----------------------------*/
function sec_3_error_check(){
	if(!jQuery('.nutrition_type').is(':checked')){
       // var st=jQuery('<strong>').text('Bitte wählen Sie Ihren Ernährungstyp')
        var st=jQuery('<strong>').text(err_msg[45])
        jQuery('.error_ntype').addClass('diet_error');
        jQuery('.diet_error').empty().append(st);
        errors=true;
        if(sec===0){sec=3;}
       
    }
	else{
	 	//jQuery('.error_ntype').empty();
	 	errors=false;
	}
	return errors;

}
function section3() { 
	var ntype_checkes=true;
	jQuery('.nutrition_type').change(function(){

			if(jQuery(this)[0].checked) {
				var v=jQuery(this).val();
				if(jQuery(this).val()==='nospecial'){
					jQuery('.nutrition_type').not(".nutrition_type[value='"+v+"']").removeAttr('checked')
				}
				if(jQuery(this).val()==='vegetarian'){
					jQuery('.nutrition_type').not(".nutrition_type[value='"+v+"']").removeAttr('checked')
				}
				if(jQuery(this).val()==='vegan'){
					jQuery('.nutrition_type').not(".nutrition_type[value='"+v+"']").removeAttr('checked')
				}
				if(jQuery(this).val()==='flexitarian' || jQuery(this).val()==='pescetarian'){
					jQuery(".nutrition_type[value='nospecial'],.nutrition_type[value='vegetarian'],.nutrition_type[value='vegan']").removeAttr('checked')
				}
				if(jQuery(this).val()==='paleo'){
					jQuery(".nutrition_type[value='nospecial'],.nutrition_type[value='vegetarian'],.nutrition_type[value='vegan']").removeAttr('checked')
				}
			}
		jQuery('.error_ntype').empty();
		jQuery('.error_ntype').removeClass('diet_error');
		jQuery('.nutrition_type').each(function(){

			if(jQuery(this).is(':checked')) {
				ntype_checkes=true;  
				//jQuery(this).val(1);
			}
			else{
				//jQuery(this).val(0);
			}//
		}); 
	});

	jQuery('#section3').click(function () {
		sec_1_error_check();
		sec_2_error_check();
		sec_3_error_check();
	})
};
section3();

/*--------------Section 4------------------------*/
jQuery(".welter_btn a[href='#section-5']").click(function(){
		sec_1_error_check();
		sec_2_error_check();
		sec_3_error_check();
})





/*--------------Section 5------------------------*/

function sec_5_error_check(){
/*--------Questino 1------*/
	if ($('input[name=q1]:checked').length <= 0)
	{
		var st=jQuery('<strong/>').append(err_msg[20]);;
		jQuery('.q1_error').empty().append(st);
		jQuery('.q1_error').parents('li').addClass('error_color');;
		errors=true;

	}
	else
	{
		jQuery('.q1_error').text('');
		jQuery('.q1_error').parents('li').removeClass('error_color');
		if(errors!==true){errors=false;}
	}

	/*--------Questino 2------*/
	if ($('input[name=q2]:checked').length <= 0)
	{
		var st=jQuery('<strong/>').append(err_msg[21]);;
		jQuery('.q2_error').empty().append(st);
		jQuery('.q2_error').parents('li').addClass('error_color');;
		errors=true;

	}
	else
	{
		jQuery('.q2_error').text('');
		jQuery('.q2_error').parents('li').removeClass('error_color');
		if(errors!==true){errors=false;}
	}

	/*--------Questino 3------*/
	if ($('input[name=q3]:checked').length <= 0)
	{
		var st=jQuery('<strong/>').append(err_msg[22]);;
		jQuery('.q3_error').empty().append(st);
		jQuery('.q3_error').parents('li').addClass('error_color');;
		errors=true;

	}
	else
	{
		jQuery('.q3_error').text('');
		jQuery('.q3_error').parents('li').removeClass('error_color');
		if(errors!==true){errors=false;}
	}
	/*--------Questino 4------*/
	if ($('input[name=q4]:checked').length <= 0)
	{
		var st=jQuery('<strong/>').append(err_msg[23]);;
		jQuery('.q4_error').empty().append(st);
		jQuery('.q4_error').parents('li').addClass('error_color');;
		errors=true;

	}
	else
	{
		jQuery('.q4_error').text('');
		jQuery('.q4_error').parents('li').removeClass('error_color');
		if(errors!==true){errors=false;}
	}
	if(errors===false){
		jQuery('.pricing_table').children('div').each(function(){
				jQuery(this).children('div').each(function(){
					
					if(jQuery(this).hasClass('active')){
						setTimeout(function(){
							jQuery('html,body').animate({scrollTop: jQuery('.price_table.active').offset().top-34}, 1000);
						},1000)
						
					}
				});
			});
	}
	return errors;
}
function section5(){
	jQuery(".welter_btn a[href='#section-6']").click(function(){

		sec_1_error_check();
		sec_2_error_check();
		sec_3_error_check();
		sec_5_error_check();
		
		
	})
}
section5();

/*----------Scroll---------------*/
function savedatacookie(){

	try {
			t=new Array();
			ex=jQuery("#exclude").select2('val'); 
			if(ex.length>0 && isNaN(ex[0])){
			for(var i=0;i<ex.length;i++){
				if(isNaN(ex[i])){
					t.push(ex[i]);
				}
			}
			}
			else{
				t=ex;
			}
			var user_data={	
							cur_weight:jQuery.trim(jQuery('#myvalcheck').val()),
							desired_weight:jQuery.trim(jQuery('#myvalcheck_sec').val()),
							gender:jQuery('input[name=group3]:checked').val(),
							age:jQuery.trim(jQuery('#age').val()),
							height:jQuery.trim(jQuery('#height').val()),
							daily_activity:jQuery('#slider').slider("option", "value"),
							nutrition_type:jQuery("input[class=nutrition_type]:checked").map(function(){ return this.value;}).get().join(","),
							allergies:jQuery("input[class=allergies]:checked").map(function(){ return this.value;}).get().join(","),
							nuts:jQuery("input[name=nuts]:checked").map(function(){ return this.value;}).get().join(","),
							fruit:jQuery("input[name=fruit]:checked").map(function(){ return this.value;}).get().join(","),
							exclude:t.join(","),
							sweet_tooth:jQuery('input[name=q1]:checked').val(),
							is_time_to_cook:jQuery('input[name=q2]:checked').val(),
							where_food_buy:jQuery('input[name=q3]:checked').val(),
							most_buy:jQuery('input[name=q4]:checked').val()
						}

						$.removeCookie('UpfitUserData');
						$.cookie('UpfitUserData',JSON.stringify(user_data),{ expires: 1 })
						localStorage.setItem("UpfitUserData",$.cookie("UpfitUserData"));

					//Object.defineProperty(document, "cookie", { get: function() { return "UpfitUserData="+JSON.stringify(user_data) } });
		} catch (er) {
			}
}
jQuery(window).bind('beforeunload', function(e){
	localStorage.removeItem('myvalcheck');
	localStorage.removeItem('myvalcheck_sec');
   savedatacookie();
});
jQuery(function() {
	
	function savedata(url){
		
	try {
		t=new Array();
		ex=jQuery("#exclude").select2('val'); 
		
		if(ex.length>0 && isNaN(ex[0])){
		
			for(var i=0;i<ex.length;i++){
				if(isNaN(ex[i])){
					t.push(ex[i]);
				}
			}
		}
		else{

			t=ex;
		}
		
		var user_data={	
						cur_weight:jQuery.trim(jQuery('#myvalcheck').val()),
						desired_weight:jQuery.trim(jQuery('#myvalcheck_sec').val()),
						gender:jQuery('input[name=group3]:checked').val(),
						age:jQuery.trim(jQuery('#age').val()),
						height:jQuery.trim(jQuery('#height').val()),
						daily_activity:jQuery('#slider').slider("option", "value"),
						nutrition_type:jQuery("input[class=nutrition_type]:checked").map(function(){ return this.value;}).get().join(","),
						allergies:jQuery("input[class=allergies]:checked").map(function(){ return this.value;}).get().join(","),
						nuts:jQuery("input[name=nuts]:checked").map(function(){ return this.value;}).get().join(","),
						fruit:jQuery("input[name=fruit]:checked").map(function(){ return this.value;}).get().join(","),
						exclude:t.join(","),
						sweet_tooth:jQuery('input[name=q1]:checked').val(),
						is_time_to_cook:jQuery('input[name=q2]:checked').val(),
						where_food_buy:jQuery('input[name=q3]:checked').val(),
						most_buy:jQuery('input[name=q4]:checked').val()
					}
				 	

					$.removeCookie('UpfitUserData');
					$.cookie('UpfitUserData',JSON.stringify(user_data),{ expires: 1 })
					Object.defineProperty(document, "cookie", { get: function() { return "UpfitUserData="+JSON.stringify(user_data) } });
				//	window.testValue = $.cookie("UpfitUserData");
				//	console.log($.cookie("UpfitUserData"));
				 	localStorage.setItem("UpfitUserData", $.cookie("UpfitUserData"));
//return;
					window.location.assign(url);
					window.ok = true;
					
			} catch (er) {
			}
			
	}
	function setdata(url){

		//$.removeCookie('UpfitUserData');
		if($.cookie("UpfitUserData")){
			
			//localStorage.removeItem('UpfitUserData');
			//localStorage.setItem("UpfitUserData", $.cookie("UpfitUserData"));
			//readlocal();
			$userData=JSON.parse($.cookie("UpfitUserData"));
			var current_weight = localStorage.getItem("myvalcheck");
			var desired_weight = localStorage.getItem("myvalcheck_sec");
			//console.log('cw='+current_weight);
			if(current_weight){
				
				jQuery('#myvalcheck').val(current_weight);
			}
			if(jQuery.trim(desired_weight) !== ''){
				jQuery('#myvalcheck_sec').val(desired_weight);
			}
			if(jQuery('#myvalcheck').val() !== '' && 	jQuery('#myvalcheck_sec').val() !== '')
			{
				 jQuery("html, body").animate({
						 scrollTop: jQuery('#section-2').offset().top
					}, 500);
					jQuery('.page-nav li:first-child').addClass('done');
			}
			var cw1=jQuery('#myvalcheck').val();
			var dw1=jQuery('#myvalcheck_sec').val();

			if(!cw1 && $userData.cur_weight){
				jQuery('#myvalcheck').val($userData.cur_weight)
			}
			if(!dw1  && $userData.desired_weight){
				jQuery('#myvalcheck_sec').val($userData.desired_weight)
			}
		
			if($userData.allergies!=''){
				var algs=$userData.allergies;
				
				var algs_name=algs.split(',');
				for(var i=0;i<countProperties(algs_name);i++){
					jQuery('input[value='+algs_name[i]+']').attr('checked','checked');
				}
			}
			if($userData.age!=''){jQuery('#age').val($userData.age)}
			if($userData.daily_activity!=''){jQuery('#slider').slider("value",$userData.daily_activity)}
			if($userData.fruit!=''){
				var fts=$userData.fruit;
				var fts_name=fts.split(',');
				for(var i=0;i<countProperties(fts_name);i++){
					jQuery('input[value='+fts_name[i]+']').attr('checked','checked');
				}
			}
			if($userData.gender!=''){
				if($userData.gender==='m'){
					jQuery('input[value=m]').click()
				}else{
					jQuery('input[value=f]').click()
				}
			}
			if($userData.height!=''){jQuery('#height').val($userData.height)}
			
			if($userData.is_time_to_cook!=''){jQuery('input[value='+$userData.is_time_to_cook+']').click()}
			if($userData.most_buy!=''){jQuery('input[value='+$userData.most_buy+']').click()}
			if($userData.nutrition_type!=''){
				var ntts=$userData.nutrition_type;
				var ntts_name=ntts.split(',');
				for(var i=0;i<countProperties(ntts_name);i++){
					jQuery('input[value='+ntts_name[i]+']').attr('checked','checked');
					
				}
			}
			if($userData.nuts!=''){
				var nts=$userData.nuts;
				var nts_name=nts.split(',');
				for(var i=0;i<countProperties(nts_name);i++){
					jQuery('input[value='+nts_name[i]+']').attr('checked','checked');
				}
			}
			if($userData.sweet_tooth!=''){jQuery('input[value='+$userData.sweet_tooth+']').click()}
			if($userData.where_food_buy!=''){jQuery('input[value='+$userData.where_food_buy+']').click()}
		}
		
	}
setdata();

	
	
	jQuery('ul.page-nav li').click(function(){
		var l=jQuery(this).children('a').attr('href')
		target=jQuery(l)
			jQuery('html,body').animate({scrollTop: target.offset().top+2 }, 1000);
		})
	jQuery('a[href*=#]:not([href=#])').click(function(e) {
	e.preventDefault();
	/*----------------Save Data----------*/
	if(jQuery(this).hasClass('plan_more_details')){
		var url=jQuery(this).attr('href');
		localStorage.removeItem('myvalcheck');
		localStorage.removeItem('myvalcheck_sec');
			savedata(url);

		//window.location.replace()
	}
	/*-------------End Save Data----------*/
		var pgurl = window.location.href;
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = jQuery(this.hash);
			target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
			jQuery('.loader_plan').remove();
			if (target.length) {
				if(!jQuery(this).data('nav')){

					if(errors===true || jQuery('.error_color').length>0){
						if(sec!==3){
							jQuery('html,body').animate({scrollTop: jQuery('.error_color').offset().top-50 }, 1000);
						}
						else{
							 jQuery('html,body').animate({scrollTop: jQuery('.pr.section3').offset().top-39 }, 1000);
						}
							//jQuery('.error_color:first input').focus();;	
					}else{
						jQuery('ul.page-nav li.active').addClass('done').removeClass('active');
							jQuery('html,body').animate({scrollTop: target.offset().top+2 }, 1000);

					}
					return false;
				}
				else{
					//jQuery('ul.page-nav li').removeClass('active');
					//jQuery(this).parents('li').addClass('active');
					jQuery('html,body').animate({scrollTop: target.offset().top+2 }, 1000);
				}
				
			}
		}
		
		
	});
});
/*---------------------*/
jQuery('input.inp').keypress(function(){
jQuery(this).parents('li.error_color').removeClass('error_color').children('div.text-info').empty();
});

/*--------Check scroll------------*/
var distance = $("div[id^='section']").offset().top,
    $window = $(window);

$window.scroll(function() {
	$("div[id^='section']").each(function(){
		distance=jQuery(this).offset().top;
		if ( $window.scrollTop() >= distance ) {
			var p=jQuery('ul.page-nav li.active');
			var prev=jQuery('ul.page-nav li.active a').attr('href');

			if(prev==='#section-1'){
				//sec_1_error_check();
		

			}
			else if(prev==='#section-2'){
				if (jQuery('#myvalcheck').val().trim() != '' && (jQuery('#myvalcheck_sec').val().trim() != '') )
				{
					var v = jQuery('#myvalcheck').val().trim();
		            var vv = v.split(",");
					var v1 = jQuery('#myvalcheck_sec').val().trim();
		            var vv1 = v1.split(",");
		            if (vv[0].length <= 2)
		            {
		            	patr = /^[0-9]{1,2}[,]*[0-9]{1}$/;
		            }
		            else
		            {
		            	patr = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
		            }

		             if (vv1[0].length <= 2)
		            {
		            	patr1 = /^[0-9]{1,2}[,]*[0-9]{1}$/;
		            }
		            else
		            {
		            	patr1 = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
		            }

					if ((v.match(patr) && vv[0].length <= 3) && (v1.match(patr1) && vv1[0].length <= 3) )
					{
						var text1 = jQuery('#myvalcheck').val();
						var text2 = jQuery('#myvalcheck_sec').val();
						text1 = parseInt(text1.replace(',', '.'));
						text2 = parseInt(text2.replace(',', '.'));
						if (text2 >= text1) {
							jQuery('ul.page-nav li:eq(0)').removeClass('done');
				        }
				        else {
							jQuery('ul.page-nav li:eq(0)').addClass('done');
				            //jQuery('.info-tooltip').addClass('hidden');
				        }
		            }
		            else{
							jQuery('ul.page-nav li:eq(0)').removeClass('done');
		            }
			    }
			}
			else if(prev==='#section-3'){
					var age =jQuery('#age').val();
					var height = jQuery('#height').val();
					if(age!=='' && height!=='' )
					{
						if (jQuery.trim(age) !== '' && jQuery.trim(height) !== '' && jQuery('input[name=group3]:checked').length > 0) {
							if( !(!age.match(age_pattr)) &&  !(!height.match(height_pattr) || parseInt(height) >= 300)) {
								jQuery('ul.page-nav li:eq(1)').addClass('done');
							}
							else{
								jQuery('ul.page-nav li:eq(1)').removeClass('done');
							}
						}
						else{
								jQuery('ul.page-nav li:eq(1)').removeClass('done');
						}
					}
					else{
						//alert('here1')
					}
			}
			else if(prev==='#section-4'){
				if(jQuery('.nutrition_type').is(':checked')){
					jQuery('ul.page-nav li:eq(2)').addClass('done');
			    }
			    else{
					jQuery('ul.page-nav li:eq(2)').removeClass('done');
			    }
			}
			else if(prev==='#section-5'){

						jQuery('ul.page-nav li:eq(3)').addClass('done');

			}
			else if(prev==='#section-6'){
				if ($('input[name=q1]:checked').length > 0 && $('input[name=q2]:checked').length > 0 && $('input[name=q3]:checked').length > 0 && $('input[name=q4]:checked').length > 0)
				{
					jQuery('ul.page-nav li:eq(4)').addClass('done');

				}
				else
				{
					jQuery('ul.page-nav li:eq(4)').removeClass('done');
				}
			}
        jQuery('ul.page-nav li').removeClass('active');
		jQuery('a[href*=#'+this.id+']').parents('li').addClass('active');
		plan_section();
    }
	});
    
});


/*------------------Plan Selections----------------------------*/

	function plan_section(){
		jQuery('.price_table ').removeClass('active');
		if(parseInt(jQuery('#myvalcheck').val())-parseInt(jQuery('#myvalcheck_sec').val()) >=6)
		{
			if(jQuery('#slider').slider("option", "value")<=30)
			{
				recommended_plan('pl_sportmuffel');
			}
			else{
				recommended_plan('pl_dauerbrenner');
				//recommended_plan('pl_superstar');
			}
		}
		else{
			if (jQuery('input[name=q1]:checked').val()==='yes'){
				if(jQuery('#slider').slider("option", "value")<=30)
				{
					recommended_plan('pl_sportmuffel');
				}
				else{
					recommended_plan('pl_dauerbrenner');
					//recommended_plan('pl_sparfuchs');
				}
			}
			else{
				if(jQuery('input[name=q3]:checked').val()==='cheap' ||jQuery('input[name=q2]:checked').val()==='little')
				{
					recommended_plan('pl_sparfuchs');
				}
				else
				{
					recommended_plan('pl_fettkiller');
				}
			}

		}
	}
	//plan_section();
	/*jQuery('.price_table ').click(function(){
		jQuery(this).find('.plan_redirect_btn').click();
	});*/

function recommended_plan(pl){
	//return false;
	//jQuery('.price_table  h4').hide();
	//alert(pl);
	if(jQuery('.'+pl+' h4').text()!=''){
		jQuery('.'+pl+' h4').text('Empfohlen');//.show();
	}
	jQuery('.price_table ').removeClass('active');
	jQuery('.'+pl).addClass('active');
	
}
var d = new Date();
Date.prototype.getWeek = function() {
    var onejan = new Date(this.getFullYear(),0,1);
    var millisecsInDay = 86400000;
    return Math.ceil((((this - onejan) /millisecsInDay) + onejan.getDay()+1)/7);
};
/*---------------------Rename buttons-------------------------*/
//jQuery('.plan_redirect_btn').text('AuswÃ¤hlen');
/*-----------------Checkout Process---------------*/

var meal_error="";
function checkout_tooltip(){
	if((jQuery('.error_color').length===jQuery('.diet_error').length) && jQuery('.diet_error').length===0){
	//alert('1');
	jQuery('body').append('<div class="loader_plan"><div></div> </div>');
	$c_cur_weight=jQuery.trim(jQuery('#myvalcheck').val());
	$c_desired_weight=jQuery.trim(jQuery('#myvalcheck_sec').val());
	$c_gender=jQuery('input[name=group3]:checked').val();
	$c_age=jQuery.trim(jQuery('#age').val());
	$c_height=jQuery.trim(jQuery('#height').val());
	$c_daily_activity=jQuery('#slider').slider("option", "value");

	$c_cur_weight = parseInt($c_cur_weight.replace(',', '.'));
	$c_desired_weight = parseInt($c_desired_weight.replace(',', '.'));
	$activity_level=jQuery('#slider').slider("option", "value");
	$PAL = 1.20; //Resting Metabolic Rate
	if ($activity_level >= 0 && $activity_level <= 19.99) {
	        $PAL = 1.20;
	} else if ($activity_level >= 20 && $activity_level <= 39.99) {
	        $PAL = 1.45;
	} else if ($activity_level >= 40 && $activity_level <= 59.99) {
	        $PAL = 1.65;
	} else if ($activity_level >= 60 && $activity_level <= 69.99) {
	        $PAL = 1.85;
	} else if ($activity_level >= 80 && $activity_level <= 89.99) {
	        $PAL = 2.15;
	} else if ($activity_level >= 90 && $activity_level <= 100) {
	        $PAL = 2.35;
	}
	ex=jQuery("#exclude").select2('val'); 
	nutrition_type=jQuery("input[class=nutrition_type]:checked").map(function(){ return this.value;}).get().join(",");
	allergies=jQuery("input[class=allergies]:checked").map(function(){ return this.value;}).get().join(",");
	nuts=jQuery("input[name=nuts]:checked").map(function(){ return this.value;}).get().join(",");
	fruit=jQuery("input[name=fruit]:checked").map(function(){ return this.value;}).get().join(",");
	preparation_time=jQuery('input[name=q2]:checked').val();
	
	exclude=ex.join(",");
	//alert(ajaxurl);
	jQuery.ajax({
			  url: ajaxurl,
              //url: shopkeeper_ajaxurl,
              type: 'POST',
			  async: false,
              data: "action=check_plan_validation&nutrition_type=" + nutrition_type+"&allergies=" + allergies +"&nuts=" + nuts+"&fruit="+ fruit+"&exclude="+ exclude+"&preparation_time="+preparation_time
			}).done(function(res){
				console.log(parseInt(res));
			//	console.log(parseInt(res) < 56);
				if(parseInt(res) < 56)
				{
					meal_error=err_msg[44];
					errors=true;					//return;
				}
				else
				{
					meal_error="";
				}
				if(!meal_error){
					$cweight=$c_cur_weight;
					//alert($cweight)
					$weeknum=1;
					$lossweight=0;
					for(var i=1;i<=$c_weeks;i++){
						if ($c_gender == 'f') {
					        $RMR = (655.1 + (9.6 * $cweight) + (1.8 * $c_height) - (4.7 * $c_age)); //-$PAL*(100-25/100);
						} else {
						    $RMR = (66.47 + (13.7 * $cweight) + (5 * $c_height) - (6.8 * $c_age)); //-$PAL*(100-25);
						}
						$tee = $RMR * $PAL;
						$NPES = 0; //Nutrition Plan Energy Supply
						//Adjust Total Energy Expenditure per day
						$four_week_plan = {'1_day' : -35, '2_day' : -35, '3_day' : -35, '4_day' : -35, '5_day': -35, '6_day' : -35, '0_day': 10};
						$twelve_week_plan = {'1_day': -25, '2_day' : -25, '3_day' : -10, '4_day' : -25, '5_day' : -25, '6_day' : -25, '0_day' : 10};
						$weektype = {'12_weeks': $twelve_week_plan, '4_weeks' : $four_week_plan};
						$day_value = $weektype[$c_weeks+'_weeks'][d.getDay()+'_day'];
						$plan_week = $weektype[$c_weeks+'_weeks'];
						if (i % 2 == 0) {
					        $sun_percentage = -($plan_week['0_day']) / 100;
					    } else {
					        $sun_percentage = 0;
					    }
						$NPES_perweek = $cweight - ($tee * (((-$plan_week['1_day']) / 100) + ((-$plan_week['2_day']) / 100) + ((-$plan_week['3_day']) / 100) + ((-$plan_week['4_day']) / 100) + ((-$plan_week['5_day']) / 100) + ((-$plan_week['6_day']) / 100) + ($sun_percentage)) * ((1 / 7000)));
						//$NPES_perweek = $c_cur_weight - ($tee * ((($plan_week['1_day']) / 100) + (($plan_week['2_day']) / 100) + (($plan_week['3_day']) / 100) + (($plan_week['4_day']) / 100) + (($plan_week['5_day']) / 100) + (($plan_week['6_day']) / 100) - ($plan_week['0_day'] / 100)) * ((1 / 7000)));
						$te=($cweight-$NPES_perweek.toFixed(2));//*1.75;
						$cweight=$cweight-$te;//*1.75;
						$lossweight+=$te;
					}
					$cw=$c_cur_weight-$c_desired_weight;
				
					if($lossweight*1.75<=$cw ){
						jQuery('.plan_tool_tip').remove();
						jQuery('.pricing_table').addClass('active');
						var tooltiphtml="";
						if(jQuery(window).width()>979){
							
							
								
								jQuery('.pricing_table').append('<div class="plan_tool_tip '+datap+'"><span class="plan_arrow "></span><div class="tool_cnt"><span>'+err_msg[43]+'<span></div><div class="tool_btn blue_hover"><a class="accept_plan" data-id='+planid+' href="javascript:void(0)">Ok, Los geht\'s <i class="vc_btn3-icon fa fa-angle-right"></i></a></div></div>');	
							
							
						}
						else if(jQuery(window).width()<979 && jQuery(window).width()>640){
							if(datap==="plan_1" || datap==="plan_2"){
								errors=true;
								
									jQuery('div.price_table[datap="plan_2"]').parents('.price_table_wrapper ').after('<div class="plan_tool_tip '+datap+'"><span class="plan_arrow "></span><div class="tool_cnt"><span>'+err_msg[43]+'<span></div><div class="tool_btn blue_hover"><a class="accept_plan" data-id='+planid+' href="javascript:void(0)">Ok, Los geht\'s <i class="vc_btn3-icon fa fa-angle-right"></i></a></div></div>');
								
							}else{
								
									jQuery('div.price_table[datap="plan_4"]').parents('.price_table_wrapper ').after('<div class="plan_tool_tip '+datap+'"><span class="plan_arrow "></span><div class="tool_cnt"><span>'+err_msg[43]+'<span></div><div class="tool_btn blue_hover"><a class="accept_plan" data-id='+planid+' href="javascript:void(0)">Ok, Los geht\'s <i class="vc_btn3-icon fa fa-angle-right"></i></a></div></div>');
								
							}
							errors=true;
						}
						else if(jQuery(window).width()<640){

								jQuery('div.price_table[datap="'+datap+'"]').parents('.price_table_wrapper ').after('<div class="plan_tool_tip '+datap+'"><span class="plan_arrow "></span><div class="tool_cnt"><span>'+err_msg[43]+'<span></div><div class="tool_btn blue_hover"><a class="accept_plan" data-id='+planid+' href="javascript:void(0)">Ok, Los geht\'s <i class="vc_btn3-icon fa fa-angle-right"></i></a></div></div>');
							
							errors=true;
						}
						jQuery('html,body').animate({scrollTop: jQuery('.plan_tool_tip').offset().top-jQuery(window).height()/4 }, 1000);
						
						jQuery('.loader_plan').remove();
						errors=true;
					}
					else{
						errors=false;
					redirects_checkout();
					}
				}
				else{
					jQuery('.plan_tool_tip').remove();
						jQuery('.pricing_table').addClass('active');
					if(jQuery(window).width()>979){
							
							
								jQuery('.pricing_table').append('<div class="plan_tool_tip '+datap+' tool"><span class="plan_arrow "></span><div class="tool_cnt tool_fullwidth"><span>'+meal_error+'<span></div></div>');	

						}
						else if(jQuery(window).width()<979 && jQuery(window).width()>640){
							if(datap==="plan_1" || datap==="plan_2"){
								errors=true;
								
									jQuery('div.price_table[datap="plan_2"]').parents('.price_table_wrapper ').after('<div class="plan_tool_tip '+datap+' tool"><span class="plan_arrow"></span><div class="tool_cnt tool_fullwidth"><span>'+meal_error+'<span></div></div>');	
								
							}else{
								
									jQuery('div.price_table[datap="plan_4"]').parents('.price_table_wrapper ').after('<div class="plan_tool_tip '+datap+' tool"><span class="plan_arrow "></span><div class="tool_cnt tool_fullwidth"><span>'+meal_error+'<span></div></div>');	
								
							}
							errors=true;
						}
						else if(jQuery(window).width()<640){
							
							
								jQuery('div.price_table[datap="'+datap+'"]').parents('.price_table_wrapper ').after('<div class="plan_tool_tip '+datap+' tool"><span class="plan_arrow "></span><div class="tool_cnt tool_fullwidth"><span>'+ meal_error+'<span></div></div>');	
							
							errors=true;
						}
						jQuery('html,body').animate({scrollTop: jQuery('.plan_tool_tip').offset().top-jQuery(window).height()/4 }, 1000);
						
						jQuery('.loader_plan').remove();
						errors=true;
				}
			});	
	}
	else{
		if(jQuery('.diet_error').length){
			jQuery('html,body').animate({scrollTop: jQuery('.diet_error').offset().top-50 }, 1000);
		}else{
			jQuery('html,body').animate({scrollTop: jQuery('.error_color').offset().top-50 }, 1000);
		}
	}
}
jQuery('.price_table_wrapper ').click(function(){
	jQuery('body').append('<div class="loader_plan"><div></div> </div>');
});
jQuery(document).on('click','.tool_btn .accept_plan',function(e){
	e.preventDefault();
	errors=false;
	redirects_checkout();	
});
function redirects_checkout(){
	var arr=window.location.href.split('/');
	if(errors===false){
		ex=jQuery("#exclude").select2('val'); 
		
		var user_data={	
						cur_weight:jQuery.trim(jQuery('#myvalcheck').val()),
						desired_weight:jQuery.trim(jQuery('#myvalcheck_sec').val()),
						gender:jQuery('input[name=group3]:checked').val(),
						age:jQuery.trim(jQuery('#age').val()),
						height:jQuery.trim(jQuery('#height').val()),
						daily_activity:jQuery('#slider').slider("option", "value"),
						nutrition_type:jQuery("input[class=nutrition_type]:checked").map(function(){ return this.value;}).get().join(","),
						allergies:jQuery("input[class=allergies]:checked").map(function(){ return this.value;}).get().join(","),
						nuts:jQuery("input[name=nuts]:checked").map(function(){ return this.value;}).get().join(","),
						fruit:jQuery("input[name=fruit]:checked").map(function(){ return this.value;}).get().join(","),
						exclude:ex.join(","),
						sweet_tooth:jQuery('input[name=q1]:checked').val(),
						is_time_to_cook:jQuery('input[name=q2]:checked').val(),
						where_food_buy:jQuery('input[name=q3]:checked').val(),
						most_buy:jQuery('input[name=q4]:checked').val(),
						planid:planid
					}
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{"action": "wdm_add_user_custom_data_options_callback",
					'id':planid,
					'user_data':user_data
				},
			}).done(function(data){
				setTimeout(function(){
					$.removeCookie('UpfitUserData');

					$.cookie('UpfitUserData',JSON.stringify(user_data),{ expires: 1 })

				 	localStorage.setItem("UpfitUserData", $.cookie("UpfitUserData"));

					if(errors===false &&(jQuery('.error_color').length ===jQuery('.diet_error').length)){
					//console.log(arr[0]+'//'+arr[2]+'/checkout/?add-to-cart='+planid);
					window.location.assign(arr[0]+'//'+arr[2]+'/checkout/?add-to-cart='+planid);
					}
					else{
						jQuery('.loader_plan').remove();
						if(jQuery('.diet_error').length){
							jQuery('html,body').animate({scrollTop: jQuery('.diet_error').offset().top-50 }, 1000);
						}else{
							jQuery('html,body').animate({scrollTop: jQuery('.error_color').offset().top-50 }, 1000);
						}
					}
				},2000)
				
			});		
	}
	else{
		if(jQuery('.error_color').lenght<=0){
			jQuery('html,body').animate({scrollTop: jQuery('.error_color').offset().top-50 }, 1000);
		}
		else{
			jQuery('html,body').animate({scrollTop: jQuery('.diet_error').offset().top-50 }, 1000);
		}
	}
}
var chknpes=0;
var planid=0;
jQuery(document).on('click','.price_table',function(e){
	e.preventDefault();
	var plan;
	datap=jQuery(this).attr('datap');
	
	$c_weeks=jQuery(this).children('div.center_description').children('a.plan_redirect_btn').attr('weeks');
	 planid=jQuery(this).find('.plan_redirect_btn').attr('data-id');
	var url=jQuery(this).find('.plan_redirect_btn').attr('href');;
	var plan_id=jQuery(this).find('.plan_redirect_btn').attr('data-id');
		sec_1_error_check();
		sec_2_error_check();
		sec_3_error_check();
		sec_5_error_check();
			/*---------------------------------------*/
			setTimeout(function(){
			if(errors===false && (jQuery('.diet_error').length===jQuery('.error_color').length)){
				checkout_tooltip();
			}
			else{
				jQuery('.loader_plan').remove(); 
				if(jQuery('.error_color').length){
					jQuery('html,body').animate({scrollTop: jQuery('.error_color').offset().top-50 }, 1000);
				}else{
					jQuery('html,body').animate({scrollTop: jQuery('.diet_error').offset().top-50 }, 1000);
				}//jQuery('html,body').animate({scrollTop: jQuery('.error_color').offset().top-50 }, 1000);/*jQuery('.error_color:first')*/
			}
			},1000)
	})
});

jQuery(document).ready(function ($) {

$window = $(window);
    $(window).on('scroll', function () {
    	jQuery('.loader_plan').remove();
		jQuery('.tipso_bubble').hide();
    	if($window.scrollTop()>=$('#site-footer').offset().top-500){
    		$('.page-nav').hide();
    	}
    	else{
	    		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
	    			/* || jQuery(window).width()<640 */
				jQuery('ul.page-nav').hide();
				}
				else
				{
					jQuery('ul.page-nav').show();
				}
	    	}
        
    });
    
});

/*--------Move Buttons---------------------*/
vt=0
jQuery(window).resize(function(){

	if(jQuery(window).width()<767){
		if(vt===0){
		jQuery('.form_btn').each(function(){
			var bt=jQuery(this).children('.zuruck_btn').detach();
			//jQuery(this).children('.zuruck_btn').remove();
			jQuery('.form_btn').append(bt);
			jQuery('.form_btn').children('.zuruck_btn').remove();
			jQuery('.form_btn').append(bt);
		})
		vt=1;
		}

	}
	else{
		if(vt===1){
		jQuery('.form_btn').each(function(){
			var bt=jQuery(this).children('.zuruck_btn').detach();
			//jQuery(this).children('.zuruck_btn').remove();
			jQuery('.form_btn').prepend(bt);
			jQuery('.form_btn').children('.zuruck_btn').remove();
			jQuery('.form_btn').prepend(bt);
		})
		vt=0;
		}

	}
});

if(jQuery(window).width()<640){
		if(vt===0){
		jQuery('.form_btn').each(function(){
			var bt=jQuery(this).children('.zuruck_btn').detach();
			//jQuery(this).children('.zuruck_btn').remove();
			jQuery('.form_btn').append(bt);
			jQuery('.form_btn').children('.zuruck_btn').remove();
			jQuery('.form_btn').append(bt);
		})
		vt=1;
		}

	}
	else{
		if(vt===1){
		jQuery('.form_btn').each(function(){
			var bt=jQuery(this).children('.zuruck_btn').detach();
			//jQuery(this).children('.zuruck_btn').remove();
			jQuery('.form_btn').prepend(bt);
			jQuery('.form_btn').children('.zuruck_btn').remove();
			jQuery('.form_btn').prepend(bt);
		})
		vt=0;
		}

	}


/**Add click effect on mobile if its a mobile device*/
jQuery('body:not(.tipso_style)').on('touchstart, touchend',function(){
	jQuery('.tipso_bubble').hide();
})

jQuery('body:not(.select2-choices)').click(function(){
	jQuery('.select2-drop-mask').click();
})
jQuery('input.inp[type="text"]').one('touchstart touchend', function(e) {
        e.preventDefault();
      jQuery(this).focus();
});
jQuery(document).on('click','.go_back',function(){
					window.history.back();
});

/*if (typeof(Storage) !== "undefined") {
    // Store
    localStorage.setItem("lastname", "Smith");
    // Retrieve
    console.log(localStorage.getItem("lastname"))
} */
