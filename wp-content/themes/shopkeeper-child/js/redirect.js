jQuery(document).ready(function($){
var first_in = '', sec_in = '';
var patr = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
var errors;

$('#myvalcheck').tooltipster({
        animation: 'fade',
        delay: 200,
        offsetX: 0,
        offsetY: 0,
        onlyOne: false,
        position: 'top',
        maxWidth: '300',
        speed: 200,
        timer: 0,
        theme: '.tooltipster-error',
        interactiveTolerance: 350,
        updateAnimation: true,
        trigger: 'custom',
        interactive: true,
        contentAsHTML: true,
        reposition:false
    });
    $('#myvalcheck_sec').tooltipster({
        animation: 'fade',
        delay: 200,
        offsetX: 0,
        offsetY: 0,
        onlyOne: false,
        position: 'bottom',
        maxWidth: '300',
        speed: 200,
        timer: 0,
        theme: '.tooltipster-error',
        interactiveTolerance: 350,
        updateAnimation: true,
        trigger: 'custom',
        interactive: true,
        contentAsHTML: true
    });
     $('#list_email').tooltipster({
        animation: 'fade',
        delay: 200,
        offsetX: 0,
        offsetY: 0,
        onlyOne: false,
        position: 'top',
        maxWidth: '300',
        speed: 200,
        timer: 0,
        theme: '.tooltipster-error',
        interactiveTolerance: 350,
        updateAnimation: true,
        trigger: 'custom',
        interactive: true,
        contentAsHTML: true
    });
    
 
var pr=0;
if(error_msg===undefined || error_msg.length<2 )
{
	error_msg = ['Dein Wunschgewicht muss mindestens 1 kg kleiner sein, als dein aktuelles Gewicht.', 'Bitte gib Dein aktuelles Gewicht ein.', 'Bitte gib Dein Wunschgewicht ein.', 'nur numerische Zeichen sind erlaubt'];
	
}

function sec_1_error_check(){
	//console.log('call sec 1');
	er = false;
	var v1 = jQuery('#myvalcheck').val().trim();
	var v2 = jQuery('#myvalcheck_sec').val().trim();
		var vv1 = v1.split(",");
	   
		var vv2 = v2.split(",");
		
		if (vv1[0].length <= 2)
		{
			patr1 = /^[0-9]{1,2}[,]*[0-9]{1}$/;
		}
		else
		{
					 
			patr1 =  /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
		}	
		if (vv2[0].length <= 2)
		{
			patr2 = /^[0-9]{1,2}[,]*[0-9]{1}$/;
		}
		else
		{
			patr2 = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
		}	
		if (jQuery('#myvalcheck').val().trim() == '' )
		{
			$('.current-weight').addClass('error_color');
		
			jQuery('#myvalcheck ').tooltipster('update',error_msg[1]);
				jQuery('#myvalcheck').tooltipster('show');
			//jQuery('.first_err').html('<strong>' + error_msg[3] + '</strong>');
			if (er === false)	{			er = true;		}
		}
		else if ((v1).match(patr1) && vv1[0].length <= 3)
		{
			jQuery('#myvalcheck').tooltipster('hide');
			
		}
		else{
			
			$('.current-weight').addClass('error_color');
		
			jQuery('#myvalcheck ').tooltipster('update',error_msg[3]);
			jQuery('#myvalcheck').tooltipster('show');
			//jQuery('.first_err').html('<strong>' + error_msg[3] + '</strong>');
			if (er === false)	{			er = true;		}
			}
			
		if (jQuery('#myvalcheck_sec').val().trim() == '')
		{
			$('.weight-loss').addClass('error_color');
			jQuery('#myvalcheck_sec ').tooltipster('update', error_msg[1]);
			if($('.current-weight').hasClass('error_color')){
					//jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'bottom');
				}
				jQuery('#myvalcheck_sec').tooltipster('show');
			//jQuery('.sec_err').html('<strong>' + error_msg[4] + '</strong>');
			if (er === false)	{			er = true;		}
		}
		
		else if ((v2).match(patr2) && vv2[0].length <= 3)
		{
		
			jQuery('#myvalcheck_sec').tooltipster('hide');
		}
		else{
			$('.weight-loss').addClass('error_color');
			jQuery('#myvalcheck_sec ').tooltipster('update', error_msg[2]);
			if( $('.current-weight').hasClass('error_color')){
						//jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'bottom');
					}
				jQuery('#myvalcheck_sec').tooltipster('show');
			//jQuery('.sec_err').html('<strong>' + error_msg[4] + '</strong>');
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
				//jQuery('.sec_err').html('<strong>' + error_msg[2] + '</strong>');
				jQuery('.weight-loss').addClass('error_color');
				//jQuery('#myvalcheck').addClass('tooltipster_error');
				jQuery('#myvalcheck_sec').tooltipster('update', error_msg[2]);
				jQuery('#myvalcheck_sec').tooltipster('show');
				
					//jQuery('.info-tooltip').removeClass('hidden');
					errors=true;
			}
			else {
					jQuery('.sec_err').empty();
					jQuery('#myvalcheck_sec').tooltipster('hide');
						 errors=false;
				//jQuery('.info-tooltip').addClass('hidden');
			}
		}
	}
/*---------Section-1 Weight & Aim ----------------*/
/*
	jQuery('#myvalcheck').blur(function () {
		var v = jQuery(this).val().trim();
			//jQuery(this).tooltipster('update', 'position', 'top');
			
             
            //alert(v);
            var vv = v.split(",");
        //    console.log(vv);
            if (vv[0].length <= 2)
            {
            	patr1 = /^[0-9]{1,2}[,]*[0-9]{1}$/;
            }
            else
            {
            	patr1 = /^[0-1]{1}[0-9]{1,2}[,]*[0-9]{1}$/;
            }
alert(v.match(patr1));
			if (v.match(patr1) && vv[0].length <= 3)
			{
				first_in = jQuery('#myvalcheck').val();
                //alert('Great, you entered an E-Mail-address'+first_in);
                $('.current-weight').removeClass('error_color');
                 jQuery('#myvalcheck').tooltipster('hide');
                $('.first_err').empty();
                errors=false;
            }
            else
            {
                //alert('not');
                $('.current-weight').addClass('error_color');
                 errors=true;
			
                if (v.length == 0)
                {
					jQuery('#myvalcheck').tooltipster('update', error_msg[3]);
                	//jQuery('.first_err').html('<strong>' + error_msg[3] + '</strong>');
                }
                else if(!v.match(patr1)){
					//alert(error_msg[5]);
					jQuery('#myvalcheck').tooltipster('update',error_msg[5]);
                	//jQuery('.first_err').html('<strong>' + error_msg[5] + '</strong>');
                }
                else{
					jQuery('#myvalcheck').tooltipster('update', error_msg[2]);
					}
                 errors=true;
                
                jQuery('#myvalcheck').tooltipster('show');
            }
            if(errors===true){
				// jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'bottom');
				}
			else {
				//jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'top');
				}
    });
	jQuery('#myvalcheck_sec').blur(function () {
		var v = jQuery(this).val().trim();
            var vv = v.split(",");
          //  console.log(vv);
         
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
                jQuery('#myvalcheck_sec').tooltipster('hide');
                $('.sec_err').empty();
				errors=false;
            }
            else
            {
				errors=true;
            	$('.weight-loss').addClass('error_color');
            	
               if (v.length == 0)
                {
					 jQuery('#myvalcheck_sec').tooltipster('update', error_msg[4]);
                	//jQuery('.sec_err').html('<strong>' + error_msg[4] + '</strong>');
                }
                 else if(!v.match(patr)){
					
					jQuery('#myvalcheck_sec').tooltipster('update', error_msg[5]);
                	//jQuery('.sec_err').html('<strong>'+ error_msg[5]+'</strong>');
                }
                else if(parseInt(v)===NaN){
					alert('ff');
					jQuery('#myvalcheck_sec').tooltipster('update', error_msg[5]);
                	//jQuery('.sec_err').html('<strong>'+ error_msg[5]+'</strong>');
                }
                else if (sec_in.length > first_in.length)
                {	
					jQuery('#myvalcheck_sec').tooltipster('update', error_msg[2]);
                //	jQuery('.sec_err').html('<strong>' + error_msg[2] + '</strong>');
                						
                }
                else {
					jQuery('#myvalcheck_sec').tooltipster('update', error_msg[2]);
                	//jQuery('.sec_err').html('<strong>' + error_msg[2] + '</strong>');

                }
                if( $('.current-weight').hasClass('error_color')){
				//	jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'bottom');
				}
                jQuery('#myvalcheck_sec').tooltipster('show');
                errors=true;
            }
        });
*/	
	
	
	jQuery('input[placeholder]').on('focus',function(){
		var $this = $(this);
		$this.data('placeholder',$this.prop('placeholder'));
		$this.removeAttr('placeholder')
	}).on('blur',function(){
		var $this = $(this);
		$this.prop('placeholder',$this.data('placeholder'));
	});
	jQuery('#myvalcheck_sec').mouseover(function (e) {
		 e.preventDefault();
		if(!$('.weight-loss').hasClass('error_color')){
			jQuery('#myvalcheck_sec').tooltipster('hide');
		 }
		 if(!$('.current-weight').hasClass('error_color')){
			jQuery('#myvalcheck').tooltipster('hide');
		 }
		 
	});
	jQuery('#myvalcheck, #myvalcheck_sec').mouseleave(function () {
		
		if($('.current-weight').hasClass('error_color')){
		//	jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'bottom');
		//	jQuery('#myvalcheck').tooltipster('update', 'position', 'top');
			jQuery('#myvalcheck').tooltipster('show');
		}
		else {
			//jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'top');
			}
		
		if($('.weight-loss').hasClass('error_color')){
		//	jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'bottom');
			jQuery('#myvalcheck_sec').tooltipster('show');
		}
	 
	});

	jQuery('.inp').focusin(function () {
	 //jQuery(this).attr('placeholder',"");
		 jQuery(this).tooltipster('hide');
	});
	jQuery('#myvalcheck.inp').focusin(function () {
		jQuery('.first_err').empty();
		//jQuery('#myvalcheck').tooltipster('update', 'position', 'top');
		if(jQuery('.weight-loss').hasClass('error_color')){
		//jQuery('#myvalcheck_sec').tooltipster('update', 'position', 'bottom');
		}
		$('.current-weight').removeClass('error_color');
		 jQuery('#myvalcheck').tooltipster('hide');
	});
	jQuery('#myvalcheck_sec.inp').focusin(function () {
		jQuery('.sec_err').empty();
		$('.weight-loss').removeClass('error_color');
		 jQuery('#myvalcheck_sec').tooltipster('hide');
	});

	jQuery(document).on('click','#lose_weight_btn',function(e){
		e.preventDefault();
		sec_1_error_check();
		//alert(errors);
		//return false;
		if(errors===false){
			var cw = jQuery('#myvalcheck').val();
			var dw = jQuery('#myvalcheck_sec').val();
			var user_data={	
						cur_weight:jQuery.trim(jQuery('#myvalcheck').val()),
						desired_weight:jQuery.trim(jQuery('#myvalcheck_sec').val())
					}
					var user_data={	
							cur_weight:jQuery.trim(jQuery('#myvalcheck').val()),
							desired_weight:jQuery.trim(jQuery('#myvalcheck_sec').val()),
							t:1
						}
					localStorage.setItem("myvalcheck",jQuery('#myvalcheck').val());					
					localStorage.setItem("myvalcheck_sec",jQuery('#myvalcheck_sec').val());					
					jQuery('#homepage_form').submit();

					//$.removeCookie('UpfitUserDataHome');
					//$.cookie('UpfitUserDataHome',JSON.stringify(user_data),{ expires: 1 })
			//window.location.href=window.location.href.split('?')[0]+'nutrition-plan-creation/';//?current_weight='+cw+'&desired_weight='+dw;
		}
		
		
	});
	 $("#homepage_form").submit(function(e){
                e.preventDefault();
                window.location.assign('/'+jQuery('#homepage_form').attr('action'))
            });
	jQuery(document).on('click','.price_table',function(e){
		e.preventDefault();
			window.location.href=window.location.href.split('?')[0]+'ernaehrungsplan_erstellen';
	});
	/*jQuery(document).on('click','.plan_redirect_btn',function(e){
		e.preventDefault();
			window.location.href=window.location.href.split('?')[0]+'ernaehrungsplan_erstellen';
	});*/
	/*jQuery(document).on('click','.price_table .top_price  span a',function(e){
		e.preventDefault();
		var id=jQuery(this).attr('id');
			window.location.href=window.location.href.split('?')[0]+'ernaehrungsplan_erstellen/#'+id;
	});*/
	
	
		
		 
	});
	

t1=jQuery('#how_upfit_works .blue_hover a').html();
t2=jQuery('.testimonial_rotator_wrap .blue_hover a').html();
t3=jQuery('#why_upfit .blue_hover a').html();


	if(jQuery(window).width()<=767){
		jQuery('#how_upfit_works .blue_hover a').text('JETZT ABNEHMEN');
		jQuery('.testimonial_rotator_wrap .blue_hover a').text('JETZT ABNEHMEN');
		jQuery('#why_upfit .blue_hover a').text('JETZT ABNEHMEN');
	}
jQuery(window).resize(function() {
  var windowsize = jQuery(window).width();
	if(windowsize<=767){
		 	
		jQuery('#how_upfit_works .blue_hover a').text('JETZT ABNEHMEN');
		jQuery('.testimonial_rotator_wrap .blue_hover a').text('JETZT ABNEHMEN');
		jQuery('#why_upfit .blue_hover a').text('JETZT ABNEHMEN');
	}
	else{
		//alert(t1+'  '+t2+'  '+t3)
		jQuery('#how_upfit_works .blue_hover a').html(t1);
		jQuery('.testimonial_rotator_wrap .blue_hover a').html(t2);
		jQuery('#why_upfit .blue_hover a').html(t3);
		}
});
jQuery('#list_email').focusin(function(){
			jQuery('.email_err').html('');
			jQuery('#list_email').removeClass('error_color');
				jQuery('.mc4wp-form-email').removeClass('error_color');
			jQuery('#list_email').tooltipster('hide');
});

		jQuery('#register_email').click(function(e){
			var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			var email=jQuery("#list_email").val();
			e.preventDefault();
			if(email===undefined || !expr.test(email) || jQuery.trim(email)===''){	 
				//alert('Please enter valid email address');
				jQuery('#list_email').addClass('error_color');
				jQuery('.mc4wp-form-email').addClass('error_color');
				jQuery('#list_email ').tooltipster('update', 'Please enter valid email address');
			//	jQuery('.email_err').html('<strong>Please enter valid email address</strong>');
					jQuery('#list_email').tooltipster('show');
				return false;
			}
			else
			{
				jQuery('.email_err').html('');
				jQuery('.mc4wp-form-email').removeClass('error_color');
					jQuery('#list_email').tooltipster('hide');
					$form=jQuery('#mc-embedded-subscribe-form');
					submitSubscribeForm($form);
				//return true;
			}
		});
	function getAjaxSubmitUrl($form) {
			var url =$form.attr("action");
			url = url.replace("/post?u=", "/post-json?u=");
			url += "&c=?";
			return url;
		}
 function submitSubscribeForm($form) {
//	alert($form.attr("action"));
	 
            jQuery.ajax({
                type: "GET",
                 dataType: "jsonp",
                  
              //  jsonp: false, 
                url:getAjaxSubmitUrl($form),
              //  data: $form.serialize(),
                 data: $form.serialize(),
               cache: true,
               // trigger MailChimp to return a JSONP response
               
                error: function(error,error1,error3){
					console.log(error3);
                    // According to jquery docs, this is never called for cross-domain JSONP requests
                },
                success: function(data){
					console.log(data.result);
					if(data.result==='success'){
						jQuery('#mc_embed_signup').empty().append('<p class="mc_success">'+error_msg[41]+'</p>').hide().fadeIn(1500);
						}
						if(data.result==='error'){
						jQuery('#mc_embed_signup').empty().append('<p class="mc_error">'+error_msg[42]+'</p>').hide().fadeIn(1500);
						}
                   
                }
            });
        }


