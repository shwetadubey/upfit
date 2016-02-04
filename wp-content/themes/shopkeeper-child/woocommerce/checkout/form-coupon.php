<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<h1 style="color: #162c5d; font-size: 36px; text-align: center;" class="main-hedding">Bestelldetails</h1>
<?php
if ( ! WC()->cart->coupons_enabled() )
	return;

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( '<h3 style="width:100%; color: #71848e;font-size: 16px; text-align: center;" class="sub_headding">Hast du einen Gutschein?','woocommerce' ) );
$info_message .= ' <a href="#" class="showcoupon">' . __( 'Klicke hier um deinen Gutschein-Code einzugeben.', 'woocommerce' ) . '</a></h3>';

?>


<div class="checkout_coupon_box">
		
	<?php 
	if ( ! WC()->cart->is_empty() ) {
		wc_print_notice( $info_message, 'notice' ); 
	?>

	<div class="row">
		<div class="xlarge-9 large-11 xlarge-centered large-centered text-center columns coupon-codes">
			
			<form class="checkout_coupon" method="post" style="display:none">
				<div class="checkout_coupon_inner">
					<input type="text" name="coupon_code" class="input-text conpon-input" placeholder="<?php _e( 'Gutschein-Code eingeben...', 'woocommerce' ); ?>" id="coupon_code" value="" />
					<input type="submit" class="button coupon_button" name="apply_coupon" value="<?php _e( 'Gutschein einlösen', 'woocommerce' ); ?>" /><i class="btn_arrow_right"></i>
					<div class="clear"></div>
				</div>
			</form>
		</div><!-- .large-8-->
	</div><!-- .row-->
	<?php
}
	?>
</div><!-- .checkout_coupon_box-->
