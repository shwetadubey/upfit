<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$action='&order_action=complete_order';
$order_id=$order->id;
 do_action( 'woocommerce_email_header', $email_heading,$order_id.$action ); ?>

<p class="padding_bottom_add" style="font-size:16px;font-family:'Conv_AvenirLTStd-Medium', sans-serif;color:#96a7b0; line-height: 28px;margin:0 auto;padding:0 0 20px 0;"><?php printf( __( "Deine Bestellung ist bei uns eingegangen und wird nun bearbeitet. Deine Bestelldetails werden unten zur Kontrolle angezeigt:", 'woocommerce' ), get_option( 'blogname' ) ); ?></p>

<?php //do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2 style="color:#162c5d; font-size:24px;line-height: 28px; border-bottom:10px solid #fff; margin:0 auto;font-weight: normal;"><?php printf( __( 'Bestellnummer: %s', 'woocommerce' ), $order->get_order_number() ); ?></h2>

<table class="email_box_border" cellspacing="0" cellpadding="6" style="width:100%; border:1px solid #BDC9CC;font-family: 'Conv_AvenirLTStd-Medium', sans-serif;">
	<thead>
		<tr>
			<th class="padding_left_odlist border-bottom" scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0 0 30px;height:72px;border-right:1px solid #BDC9CC; border-bottom:1px solid #BDC9CC"><?php _e( 'Produkt', 'woocommerce' ); ?></th>
			<th class="padding_left_odlist border-bottom" scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC; border-bottom:1px solid #BDC9CC;"><?php _e( 'Anzahl', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:right;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 30px 0 0px;;height:72px;border-bottom:1px solid #BDC9CC; "><?php _e( 'Preis', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( true, false, true ); ?>
	</tbody>
	
	<tfoot data-id='from-child'>
		<?php
		if ( $totals = $order->get_order_item_totals() ) {
				global $woocommerce,$wpdb;
				$i = 0;
			/*echo '<pre>';
			print_r($order);
			echo '</pre>';*/
			$coupon=$order->get_used_coupons();
	
			$f=new WC_Coupon($coupon[0]);
			
			$discount = get_post_meta($f->id,'coupon_amount',true);
			$type = get_post_meta($f->id,'discount_type',true);
		
			$tax = $order->get_order_item_totals()['tax_MwSt.'];
			$order_total = $order->get_order_item_totals()['order_total'];
			/*echo '<pre>';
			print_r($tax);
			print_r($order->get_order_item_totals());
			echo '</pre>';*/
			foreach ( $order->get_tax_totals() as $code => $tax ) {
				$tax->rate = WC_Tax::get_rate_percent( $tax->rate_id );
				
				if ( ! isset( $tax_array[ 'tax_rate'] ) )
					$tax_array[ 'tax_rate' ] = array( 'tax' => $tax, 'amount' => $tax->amount, 'contains' => array( $tax ) );
				else {
					array_push( $tax_array[ 'tax_rate' ][ 'contains' ], $tax );
					$tax_array[ 'tax_rate' ][ 'amount' ] += $tax->amount;
				}
			
			}
			if(isset($tax_array['tax_rate']['tax']->rate))
				$tax_label='<span  class="inc-tax" style="color:#96a7b0;">(inkl.&nbsp;'.$tax_array['tax_rate']['tax']->rate.'&nbsp;'.$tax_array['tax_rate']['tax']->label.')</span>';

	//	echo  'SELECT * FROM up_user_nutrition_plans WHERE order_id ='.$order->id;
		$results = $wpdb->get_results( 'SELECT * FROM up_user_nutrition_plans WHERE order_id ='.$order->id );
		
/*		
		foreach($order->get_order_item_totals() as $key => $value){
			
			$exp_key = explode('_', $key);
			if($exp_key[0] == 'tax'){
				 $tax_label1[] = $value;
			}
			
		}
*/		//print_r($tax_label);
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				//echo $order->get_shipping_method().'   '.$order->get_total_discount().'  '.$discount;
				$i++;
				if($key=='cart_subtotal')
				{
					
				}
				else
				{
					
					if($key=='discount' && $type=='percent')
					{
						?>
						<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
							<th scope="row" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $discount.'&#37; Rabatt'; ?></th>
							<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
						</tr>
						<?php
					}
					else if($key=='discount' && $type!='percent')
					{
						?>
						<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
							<th scope="row" colspan="2" style="font-weight: normal; padding: 0px 0px 0px 30px;color:#96a7b0;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; text-align:left;padding-left:30px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo 'Rabatt'; ?></th>
							<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
						</tr>
						<?php
					}
					else
					{
						
						if($key=='order_total')
						{
							
							if($order->get_total_discount()>0)
							{
								?>
								<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
									<th scope="row" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $discount.'&#37; Rabatt'; ?></th>
									<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo '-'.$order->get_discount_to_display(); ?></td>
								</tr>
								<?php
							}
							
							?>
							<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
									<th scope="row" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo 'Versandkosten'; ?></th>
									<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo '0 '.get_woocommerce_currency_symbol(); ?></td>
								</tr>
							<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
								<th scope="row" colspan="2" style="font-weight: normal; padding: 0px 0px 0px 30px;color:#2cab50;background-color: #ededed; text-align:left;padding-left:30px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo 'Gesamtsumme ',$tax_label; ?>
								<?php/* if(isset($tax_label1[0]['label'])){?>
									<span class="inc-tax" style="color:#96a7b0;">
										(<?php echo $tax_label1[0]['label']?>)
									</span>
								<?php }*/ ?></th>
								<td style="color:#96a7b0;padding: 0px 30px 0px 0px;color:#2cab50;font-weight: normal;background-color: #ededed; text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $order_total['value']; ?></td>
							</tr>
							<?php
						}
						else if(strpos($key,'tax')>=0)
						{
						}
						else
						{
				?>
							<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
								<th class="order_total" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
								<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><a><?php echo $total['value']; ?></td>
							</tr>
				<?php
						}
					}
				}
			}
			}
			//exit;
	//	wp_mail('lanetteam.brijesh@gmail.com','test','test msg');
		?>
		
	</tfoot>

</table>

<?php  do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php  do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php  do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>

