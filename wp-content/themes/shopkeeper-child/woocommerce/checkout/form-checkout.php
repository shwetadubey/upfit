<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

?>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<style>
	.page-title {
		margin-bottom: 24px;
	}
	
	@media only screen and (min-width: 641px) {
		.page-title {
			margin-bottom: 26px;
		}
	}

	@media only screen and (min-width: 1025px) {
		.page-title {
			margin-bottom:25px;
		}	
	}
	
	.page-title:after {
		display: none;
	}
</style>

<?php
do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>
  <style>
  .loader {
 -webkit-opacity: 1 !important;
 -moz-opacity: 1 !important;
 opacity: 1 !important;
 filter: alpha(opacity=100) !important;
 -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100) !important;
 background: rgba(255, 255, 255, 1) !important;
}
.blockUI.blockOverlay{  background: rgba(255, 255, 255, 0) !important; }
.blockUI.blockOverlay:before, .loader:before {
 -moz-animation: none !important;
 -webkit-animation: none !important;
 animation: none !important;
 background: url("https://upfit.de/wp-content/themes/shopkeeper-child/images/GRAY.gif") center center !important;
 background-size: auto !important;
 width: 5em !important;
 height: 1em !important;
 margin-top: -0.5em !important;
 background-repeat: no-repeat !important;
 margin-left: -2.5em !important;
-webkit-opacity: 1 !important;
-moz-opacity: 1 !important;
 opacity: 1 !important;
 filter: alpha(opacity=100) !important;
 -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100) !important;
}
  .shop_table.woocommerce-checkout-review-order-table.from-child-theme .blockUI {display:none !important; }
  </style>  
<div class="row">
    <div class="xxlarge-9 xlarge-10 large-12 large-centered columns mtop-warenkorb">	
	
        <form name="checkout" id="woocommerce-checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
        
            <?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
        
                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<div class="row">
					
					<div class="large-8 columns">
						<div class="checkout_left_wrapper">
			
							<div class="col2-set" id="customer_details">
					
								<div class="col-1">
					
									<?php do_action( 'woocommerce_checkout_billing' ); ?>
					
								</div>
					
								<div class="col-2">
					
									<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					
								</div>
					
							</div>
				
							<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
							
						</div><!--.checkout_left_wrapper-->
					</div><!--.large-7-->
			
					<div class="large-4 columns right-cart-box">
				<?php 
					global $shopkeeper_theme_options;
					if ( (isset($shopkeeper_theme_options['header_trust_badge3']['url'])) && (trim($shopkeeper_theme_options['header_trust_badge3']['url']) != "" ) ) {
						$img=$shopkeeper_theme_options['header_trust_badge3']['url'];
						if(isset($shopkeeper_theme_options['badge_url3'])){
							$badge_url3=$shopkeeper_theme_options['badge_url3'];
						}
						else{
							$badge_url3=home_url();
						}
						?>
						<div class="certified-image"> <img src="<?php echo $img; ?>" alt=""/> </div>
			<?php 	}?>
						<div class="certified-text">
							Zertifizierte und SSL 
							verschlüsselte Webseite
						</div>
						<div class="checkout_right_wrapper custom_border">
							<div class="order_review_wrapper">
								
								<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>
								
								<?php //do_action( 'woocommerce_checkout_before_order_review' ); ?>

								<div id="order_review" class="woocommerce-checkout-review-order">
									<?php do_action( 'woocommerce_checkout_order_review' ); ?>
								</div>

								<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
								
							</div><!--.order_review_wrapper-->
						</div><!--.checkout_right_wrapper-->
					</div><!--.large-5-->
				</div><!--.row-->
					
            <?php endif; ?>
            
        </form>
   
    </div><!-- .columns -->
</div><!-- .row -->
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
//	jQuery( "a.showcoupon" ).parent().hide();
		
/*jQuery( ".blockUI.blockOverlay" ).empty();
jQuery( ".blockUI.blockOverlay" ).addClass('loader');
jQuery( ".blockUI.blockOverlay.loader" ).append('<div class="tp-loader tp-demo spinner3"><div class="dot1" style="background-color: rgb(255, 58, 58)  ;"></div><div class="dot2" style="background-color: rgb(255, 58, 58)  ;"></div><div class="bounce1" style="background-color: rgb(255, 58, 58) ;"></div><div class="bounce2" style="background-color: rgb(255, 58, 58)  ;"></div><div class="bounce3" style="background-color: rgb(255, 58, 58)  ;"></div></div>');*/
jQuery( "#billing_title" ).selectmenu();
jQuery(window).ready(function($){
	
	jQuery("input[name='payment_method']").change(function(){
		setTimeout(function(){
			jQuery('#order_review #payment .blockUI.blockOverlay').remove();;
		},1000)
		
	})
	jQuery("#payment_method_paypal").attr('checked','checked');
	setTimeout(function(){
		jQuery('#order_review #payment .blockUI.blockOverlay').remove();;
	},1000)
	jQuery('.showcoupon').click(function(e){
		if(jQuery('tr').hasClass('cart-discount')){
		//	e.stopPropagation();
		}
		
	});
	if(jQuery('tr').hasClass('cart-discount')){
//jQuery('.woocommerce-info').empty();
	}

});
//
var v='';
jQuery(document).ready(function($){
	jQuery('form[name="checkout"]')[0].reset();
	jQuery('#woocommerce-checkout').each(function(){
			this.reset();
		});

	jQuery("#billing_country").change(function(e){
			jQuery( document.body ).trigger( 'update_checkout' );
	});
	
	if(jQuery("#billing_country").is('select')){
		v=jQuery("#billing_country").select2({ minimumResultsForSearch: Infinity});
	}
		
	jQuery('#mc4wp-subscribe').change(function(){
		if(jQuery(this).is(':checked')){
			//alert('here');
			jQuery('input[name="mc4wp-subscribe"]').val(1);
		}
		else{
		jQuery('input[name="mc4wp-subscribe"]').val(0);
		}
		
	})
	if(jQuery('#mc4wp-subscribe').is(':checked')){
		jQuery('input[name="mc4wp-subscribe"]').val(1);
	}
	else{
		jQuery('input[name="mc4wp-subscribe"]').val(0);
	}

	jQuery(window).bind('beforeunload', function(e){
	//alert('okkkk');
	e.preventDefault();
	
	//return true;
  //return 'Are you sure you want to leave?';
});


})

</script>
<style type="text/css">
  
</style>
	
