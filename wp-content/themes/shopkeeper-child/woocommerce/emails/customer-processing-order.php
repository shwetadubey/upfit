<?php
/**
 * Customer processing order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$order_id=$order->id;
 do_action('woocommerce_email_header', $email_heading,$order_id); ?>

<p style="font-size:16px;color:#96a7b0; line-height: 28px;margin:0px 0px 20px 0px;"><?php _e( "Deine Bestellung ist bei uns eingegangen und wird nun bearbeitet. Deine Bestelldetails werden unten zur Kontrolle angezeigt:", 'woocommerce' ); ?></p>
<?php
if (get_post_meta($order->id, '_payment_method', true) == 'bacs') {
	$bacs = new WC_Gateway_BACS(); ?>
<p style="font-size:16px;color:#96a7b0;line-height: 28px;margin:20px 0px 40px;"><?php _e( "Überweise direkt an unsere Bankverbindung. Bitte nutze die Bestellnummer als Verwendungszweck. Deine Bestellung wird direkt nach Geldeingang auf unserem Konto versandt.", 'woocommerce' ); ?></p>
<?php
// do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2 style="color:#162c5d; font-size:24px;line-height: 24px; border-bottom:10px solid #fff; margin:0 auto;font-weight: normal;"><?php echo _e( 'Unsere Bankverbindung', 'woocommerce' ); ?></h2>
<h4 style="color:#96a7b0; font-size:16px;line-height: 24px; border-bottom:10px solid #fff; margin:0 auto;font-weight: normal;"><?php printf( __( ' Bestellnummer: %s', 'woocommerce' ), $order->get_order_number() ); ?></h4>
<div class="row">
	<div class="xlarge-12 xlarge-centered large-8 large-centered columns">

		<ul class="no-margin no-padding no-padding-left" style="list-style:none;">
			<li style="margin:0 !important;line-height:26px">
				<label class="pull-left green-text text-light" style="font-family:arial,​sans-serif">Kontoinhaber:</label>
				<strong class="pull-left green-text text-light" style="font-family:arial,​sans-serif"><?php echo $bacs->account_details[0]['account_name']; ?></strong>
			</li>
			<li style="margin:0 !important;line-height:30px">
				<label class="pull-left green-text text-light" style="font-family:arial,​sans-serif">IBAN:</label>
				<strong class="pull-left green-text text-light" style="font-family:arial,​sans-serif"><?php echo $bacs->account_details[0]['iban']; ?></strong>
			</li>
			<li style="margin:0 !important;line-height:30px">
				<label class="pull-left green-text text-light" style="font-family:arial,​sans-serif">BIC:</label>
				<strong class="pull-left green-text text-light" style="font-family:arial,​sans-serif"><?php echo $bacs->account_details[0]['bic']; ?></strong>
			</li>
			<li style="margin:0 !important;line-height:30px">
				<label class="pull-left green-text text-light" style="font-family:arial,​sans-serif">Verwendungszweck:</label>
				<strong class="pull-left green-text text-light" style="font-family:arial,​sans-serif">Bestellnummer 	<?php echo $order->get_order_number(); ?></strong>
			</li>
		</ul>

	</div>
</div>
<?php }
else{
?>
<h2 style="color:#162c5d; font-size:24px;line-height: 24px; border-bottom:10px solid #fff; margin:0 auto;font-weight: normal;"><?php printf( __( ' Bestellnummer: %s', 'woocommerce' ), $order->get_order_number() ); ?></h2>
     <?php }?>                  
<table cellspacing="0" cellpadding="0" style="width:100%;border:1px solid #BDC9CC;width:100%;color:#96a7b0;font-family:Helvetica, Arial;" >
	<thead>
		<tr>
			<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC;font-family:Helvetica, Arial; "><?php _e( 'Produkt', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC;font-family:Helvetica, Arial; "><?php _e( 'Anzahl', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:right;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 30px 0 0px;;height:72px;border-bottom:1px solid #BDC9CC;font-family:Helvetica, Arial; "><?php _e( 'Preis', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( $order->is_download_permitted(), true, $order->has_status( 'processing' ) ); ?>
	</tbody>
	<tfoot data-id='from-child'>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				global $woocommerce;
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
				$tax_label='<span  class="inc-tax" style="color:#96a7b0;">(inkl.&nbsp;'.$tax_array['tax_rate']['tax']->rate.'&nbsp;'.$tax_array['tax_rate']['tax']->label.')</span>';;
		
		foreach($order->get_order_item_totals() as $key => $value){
			$exp_key = explode('_', $key);
			if($exp_key[0] == 'tax'){
				 $tax_label1[] = $value;
			}
		}
		//print_r($tax_label);
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
								<th scope="row" colspan="2" style="font-weight: normal; padding: 0px 0px 0px 30px;color:#2cab50;background-color: #ededed; text-align:left;padding-left:30px; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo 'Gesamtsumme '.$tax_label?>
								<?php /*if(isset($tax_label1[0]['label'])){?>
									<span class="inc-tax" style="color:#96a7b0;">
										(<?php echo $tax_label1[0]['label']?>)
									</span>
								<?php } */?>
								</th>
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
								<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
							</tr>
				<?php
						}
					}
				}
			}
			}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>
