<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */
if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
}

if ($order) :

	global $pdf_order_id;

	$pdf_order_id = $order->id;
	?>

	<?php if ($order->has_status('failed')) : ?>
			<h1 style="color:#162c5d;font-size: 36px;text-align: center;" class="vc_custom_heading main-hedding"><?php echo do_shortcode('[tooltip_id id=33]'); ?></h1>
	<?php else : ?>
			<h1 style="color:#162c5d;font-size: 36px;text-align: center;" class="vc_custom_heading main-hedding"><?php echo do_shortcode('[tooltip_id id=32]'); ?></h1>
	<?php endif; ?>

	<div class="thank_you_header text-center thank_you_wrapper">

	<?php if ($order->has_status('failed')) : ?>
				<?php $lines = explode(".", do_shortcode('[tooltip_id id=35]')); ?>
				<p>
					<?php _e($lines[0] . '.', 'woocommerce'); ?>
					<a href="<?php echo home_url(); ?>/ernaehrungsplan_erstellen/" ><?php echo $lines[1]; ?>	</a>
				</p>

				<p>
					<?php
					if (is_user_logged_in())
						_e('Bitte versuchen Kauf wieder oder gehen Sie zu Ihrer Kontoseite.', 'woocommerce');
					else
						_e('Bitte versuchen wieder Ihren Einkauf.', 'woocommerce');
					?>
				</p>

				<p>
					<a href="<?php echo esc_url($woocommerce->cart->get_checkout_url()); ?>" class="button pay"><?php _e('Pay', 'woocommerce') ?></a>
					<?php if (is_user_logged_in()) : ?>
							<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay"><?php _e('My Account', 'woocommerce'); ?></a>
					<?php endif; ?>
				</p>

	<?php else : ?>

			<div class="thank_you_header_text">			
				<div class="row">
					<div class="xlarge-12 xlarge-centered large-8 large-centered columns">
					<?php
						if (get_post_meta($order->id, '_payment_method', true) == 'bacs') {
								$bacs = new WC_Gateway_BACS();
					?>
							<p class="text-light"><?php echo apply_filters('woocommerce_thankyou_order_received_text', __(do_shortcode('[tooltip_id id=34]'), 'woocommerce'), $order); ?></p>

						<?php
						} 
						else {
							?>
							<p class="text-light"><?php echo apply_filters('woocommerce_thankyou_order_received_text', __(do_shortcode('[tooltip_id id=36]'), 'woocommerce'), $order); ?></p>										
						
							<div class="text-light light-gray-text purchure-desc-text"><?php echo __(do_shortcode('[tooltip_id id=37]'), 'woocommerce'); ?></div>
							<?php } ?>
					</div><!-- .xlarge-6-->
				</div><!--	.row	-->                
			</div>
			<div class="thank_you_description">
				<div class="row">
					<div class="text-left xlarge-12 xlarge-centered large-8 large-centered columns">
							<span class="light-gray-text desc-text line-height-normal"><?php echo $bacs->description; ?></span>
					</div><!-- .xlarge-6-->
				</div><!--  .row    -->

			</div>
			<?php if (get_post_meta($order->id, '_payment_method', true) == 'bacs') { ?>
					<div class="thank_you_bank_details">

						<div class="row">
							<div class="xlarge-12 xlarge-centered large-8 large-centered columns">

								<ul class="no-margin no-padding">
									<li>
										<label class="pull-left green-text text-light">Kontoinhaber:</label>
										<strong class="pull-left green-text text-light"><?php echo $bacs->account_details[0]['account_name']; ?></strong>
									</li>
									<li>
										<label class="pull-left green-text text-light">IBAN:</label>
										<strong class="pull-left green-text text-light"><?php echo $bacs->account_details[0]['iban']; ?></strong>
									</li>
									<li>
										<label class="pull-left green-text text-light">BIC:</label>
										<strong class="pull-left green-text text-light"><?php echo $bacs->account_details[0]['bic']; ?></strong>
									</li>
									<li>
										<label class="pull-left green-text text-light">Verwendungszweck:</label>
										<strong class="pull-left green-text text-light">Bestellnummer <?php echo $order->get_order_number(); ?></strong>
									</li>
									<li>
										<label class="pull-left green-text text-light">Betrag:</label>
										<strong class="pull-left green-text text-light"><?php echo $order->get_formatted_order_total(); ?></strong>
									</li>

								</ul>

							</div>
						</div>
				   
					</div>
			<?php } ?>
			<div class="thank_you_description">
				<div class="row">
					<div class="text-left xlarge-12 xlarge-centered large-8 large-centered columns">
						<span class="light-gray-text desc-text"><?php echo $bacs->instructions; ?></span>
					</div>
				</div>
			</div>
			<div class="clear"></div>

	<?php endif; ?>

	</div>

	<span class="divider"></span>

	<!-- .thank_you_header-->

	<?php
	$page = get_post(1062);
	//print_r($page);
	echo do_shortcode($page->post_content);
	?>
	<span class="divider"></span>

	<div class="thank_you_wrapper">
			<div class="row">
					<div class="xlarge-12 xlarge-centered large-8 large-centered columns">


							<?php //do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id );  ?>

							<div class="order_details_container">

									<h2 style="color:#162c5d;font-size: 36px;text-align: center;" class="vc_custom_heading main-hedding"><?php echo strtoupper('bestellbestätigung'); ?></h2>

									<ul class="order_details">
										<li class="order">
												<span class="title dark-blue-color"><?php _e(strtoupper('bestellnummer'), 'woocommerce'); ?></span>
												<strong class="light-gray-text text-light"><?php echo $order->get_order_number(); ?></strong>
										</li>
										<li class="date">
												<span class="title dark-blue-color"><?php _e(strtoupper('datum'), 'woocommerce'); ?></span>
												<strong class="light-gray-text text-light"><?php echo date_i18n('d.m.Y', strtotime($order->order_date)); ?></strong>
										</li>
										<li class="total">
												<span class="title dark-blue-color"><?php _e(strtoupper('gesamt'), 'woocommerce'); ?></span>
												<strong class="light-gray-text text-light"><?php echo $order->get_formatted_order_total(); ?></strong>
										</li>
										<?php if ($order->payment_method_title) : ?>
												<li class="method">
														<span class="title dark-blue-color"><?php _e(strtoupper('zahlungsart'), 'woocommerce'); ?></span>
														<strong class="light-gray-text text-light"><?php echo $order->payment_method_title; ?></strong>
												</li>
										<?php endif; ?>
									</ul>


							</div><!--.order_details_container-->
							<!-- .thank_you_bank_details-->

							<?php do_action('woocommerce_thankyou', $order->id); ?>

					</div><!-- .medium-10-->
			</div><!--	.row	-->
	</div>
<?php else : ?>
		<div class="row">
				<div class="medium-10 medium-centered  large-8 large-centered text-center columns thank_you_wrapper thank_you_header">
						<div class="thank_you_header_text ">
								<p class="text-light"><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Danke. Ihre Bestellung ist eingegangen.', 'woocommerce'), null); ?></p>
						</div>
				</div>
		</div>
<?php endif;
/*
global $wpdb;
 $path = wp_upload_dir();
   // print_r($path);
$uppath1 = $path['baseurl'] . '/json/';
$order_id=$order->id;
//echo '<pre>';
//print_r('hii '.$order->id);
$billing_first_name = get_post_meta($order_id,'_billing_first_name',true);
$billing_last_name = get_post_meta($order_id,'_billing_last_name',true);
$billing_email = get_post_meta($order_id,'_billing_email',true);
$order=new WC_Order($order_id);
$items = $order->get_items();
$path = wp_upload_dir(); 
$plan_name=$items [array_keys($items)[0]]['name'];
$order_details=$wpdb->get_results('select id,meals_per_day from '.$wpdb->prefix.'user_nutrition_plans where order_id='.$order_id);
if(isset($order_details) && !empty($order_details)){
$pdfname=$path['baseurl'] . '/'.'pdf/'.$plan_name.' Ernährungsplan '.$billing_first_name.' '.$billing_last_name.'_'.$order_id.'.pdf';
//echo $pdfname;
//echo '<pre>';
}
*/
  ?>
<script type="text/javascript">

      
   
  /*jQuery(document).on('click', '#download_pdf_file', function (e) {

          //  alert(shopkeeper_ajaxurl);
            jQuery.ajax({
               
                type: 'POST',
                url: shopkeeper_ajaxurl,
 
                data: {
                    'action': 'output_file',
                    
                },
                success: function (data) {
               //     console.log(data);
                    //Do something success-ish
                },
               
				

            });
            });*/
var img_btn=jQuery('a.vc_general.vc_btn3.vc_btn3-size-lg.vc_btn3-shape-round.vc_btn3-style-custom.vc_btn3-icon-right').html();
jQuery(window).resize(function(){
	
	interchangeimg();
})
interchangeimg();
function interchangeimg(){
	if(jQuery(window).width()<764)
	{
		
		var vn=jQuery('.gratis-e-book').detach();
		jQuery('.recommend-secure-e-book').append(vn);
		//jQuery('#download_nutrition_plan').html('PLAN HERUNTERLADEN');
		jQuery('a.vc_general.vc_btn3.vc_btn3-size-lg.vc_btn3-shape-round.vc_btn3-style-custom.vc_btn3-icon-right').html('WEITEREMPFEHLEN');
		
	}
	else{
		var vn=jQuery('.gratis-e-book').detach();
		jQuery('.recommend-secure-e-book').prepend(vn);
		//jQuery('#download_nutrition_plan').html(btn_pdf_text);
		jQuery('a.vc_general.vc_btn3.vc_btn3-size-lg.vc_btn3-shape-round.vc_btn3-style-custom.vc_btn3-icon-right').html(img_btn);
		
	}
}
</script>            

