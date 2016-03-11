<?php 
/*
 * Template Name: Email HTML Template
 * 
 */

get_header();
global $product;
$order_id=$_GET['order_id'];
$action=$_GET['order_action'];
//echo $action;
$order = new WC_Order( $order_id );

$items=array_values($order->get_items());
//print_r($order);
$product_id=$items[0]['product_id'];

 $vendor =yith_get_vendor($product_id, 'product' );

//echo '<pre>';
// $order->has_status('processing')
//print_r($vendor);

?>
<style>
table.email_box_border { border:1px solid #BDC9CC;}
table thead th{
	text-transform:none;
	font-size:16px !important;
}
.payment_details ul { margin-bottom:20px;margin-left:0px}
.payment_details ul li { display:table;}
.payment_details ul li strong { margin-left:5px;    letter-spacing: 1px;
    line-height: 26px;
    font-family: "Conv_AvenirLTStd-Medium";
    font-size: 16px;
    text-transform: inherit;
    letter-spacing: 0.5px;}
.payment_details ul li label { margin-top:0;     letter-spacing: 1px;
    line-height: 26px;
    font-family: "Conv_AvenirLTStd-Medium";
    font-size: 16px;
    text-transform: inherit;
    letter-spacing: 0.5px;}
</style>
<div class="row">
	<div class="xlarge-6 xlarge-centered large-6 large-centered columns">

	<h2 style="color:#162c5d;padding-top:80px; font-size:24px;line-height: 24px; border-bottom:10px solid #fff; margin:0 auto;font-weight: normal;">
	<?php
			printf( __( 'Bestellnummer: %s', 'woocommerce' ), $order->get_order_number() ); 
			if($action == 'vendor_new_order' || $action == 'admin_new_order	'){
				 printf( ' <time datetime="%s">(%s)</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); 
			}
	?>
	</h2>
	
		<p style="font-size:16px;color:#96a7b0; line-height: 28px;margin:0px 0px 20px 0px;">
			<?php
			if($action == 'processing_order' || $action == 'complete_order'){
				_e( "Deine Bestellung ist bei uns eingegangen und wird nun bearbeitet. Deine Bestelldetails werden unten zur Kontrolle angezeigt:", 'woocommerce' ); 
			}else if($action == 'vendor_new_order'){
					_e( 'Du hust eine Bestellung erhalten. Sie lautet wie foigt:', 'woocommerce' ); 
			}
			else{
				
				printf( __( 'You have received an order from %s. The order is as follows:', 'woocommerce' ), $order->get_formatted_billing_full_name() );
			}
			?>
		</p>
		<div class="row">
			<div class="xlarge-12 xlarge-centered large-8 large-centered columns payment_details">
			<?php
			if ($action == 'processing_order' && get_post_meta($order->id, '_payment_method', true) == 'bacs') {
				$bacs = new WC_Gateway_BACS();
			?>

				<div class="row">
					<div class="xlarge-12 xlarge-centered large-6 large-centered columns">

						<ul class="" style="list-style:none;">
							<li style="line-height:26px">
								<label class="pull-left green-text text-light" style="font-family:arial,​sans-serif">Kontoinhaber:</label>
								<strong class="pull-left green-text text-light" style="font-family:arial,​sans-serif"><?php echo $bacs->account_details[0]['account_name']; ?></strong>
							</li>
							<li style="line-height:30px">
								<label class="pull-left green-text text-light" style="font-family:arial,​sans-serif">IBAN:</label>
								<strong class="pull-left green-text text-light" style="font-family:arial,​sans-serif"><?php echo $bacs->account_details[0]['iban']; ?></strong>
							</li>
							<li style="line-height:30px">
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
		<?php }?>  
				<div> 
				<?php				
					if($action == 'vendor_new_order'){?>
						<table class="email_box_border" cellspacing="0" cellpadding="0" style="width: 100%; " >
							<thead>
								<tr>
									<th scope="col"  style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 20px 0 15px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _e( 'Produkt', 'woocommerce' ); ?></th>
									<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 15px 0 15px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _e( 'Anzahl', 'yith_wc_product_vendors' ); ?></th>
									<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 15px 0 15px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _e( 'Preis', 'woocommerce' ); ?></th>
									<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 15px 0 15px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _ex( 'Gutschriften', 'Email: commission rate column', 'yith_wc_product_vendors' ); ?></th>
									<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 15px 0 15px;border-bottom:1px solid #BDC9CC;  font-family:Helvetica, Arial; height:72px;"><?php _ex( 'Verdienste', 'Email: commission amount column', 'yith_wc_product_vendors' ); ?></th>
								</tr>
							</thead>
							<?php echo $vendor->email_order_items_table( $order, false, true ); ?>
						</table>

					<?php }else{ ?>
						<table cellspacing="0" cellpadding="0" style="width:100%;border:1px solid #BDC9CC;width:100%;color:#96a7b0;" >
							<thead>
								<tr>
									<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; "><?php _e( 'Produkt', 'woocommerce' ); ?></th>
									<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC;"><?php _e( 'Anzahl', 'woocommerce' ); ?></th>
									<th scope="col" style="text-align:right;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 30px 0 0px;;height:72px;border-bottom:1px solid #BDC9CC;"><?php _e( 'Preis', 'woocommerce' ); ?></th>
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
										
										//print_r($tax);
										
										if ( ! isset( $tax_array[ 'tax_rate'] ) )
											$tax_array[ 'tax_rate' ] = array( 'tax' => $tax, 'amount' => $tax->amount, 'contains' => array( $tax ) );
										else {
											array_push( $tax_array[ 'tax_rate' ][ 'contains' ], $tax );
											$tax_array[ 'tax_rate' ][ 'amount' ] += $tax->amount;
										}
									}
							/*	echo '<pre>';
								print_r($tax_array);
								echo '</pre>';*/
								if(isset($tax_array['tax_rate']['tax']->rate))
				$tax_label='<span  class="inc-tax" style="color:#96a7b0;">(inkl.&nbsp;'.$tax_array['tax_rate']['tax']->rate.'&nbsp;'.$tax_array['tax_rate']['tax']->label.')</span>';
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
													<th scope="row" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px;"><?php echo $discount.'&#37; Rabatt'; ?></th>
													<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right;"><?php echo $total['value']; ?></td>
												</tr>
												<?php
											}
											else if($key=='discount' && $type!='percent')
											{
												?>
												<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
													<th scope="row" colspan="2" style="font-weight: normal; padding: 0px 0px 0px 30px;color:#96a7b0;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; text-align:left;padding-left:30px;"><?php echo 'Rabatt'; ?></th>
													<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; "><?php echo $total['value']; ?></td>
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
															<th scope="row" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px;"><?php echo $discount.'&#37; Rabatt'; ?></th>
															<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right;"><?php echo '-'.$order->get_discount_to_display(); ?></td>
														</tr>
														<?php
													}
													
													?>
													<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
															<th scope="row" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px; "><?php echo 'Versandkosten'; ?></th>
															<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; "><?php echo '0 '.get_woocommerce_currency_symbol(); ?></td>
														</tr>
													<tr style="font-size:16px;line-height: 16px;padding: 0px 0px 0px 30px; font-weight: normal; height:72px;">
														<th scope="row" colspan="2" style="font-weight: normal; padding: 0px 0px 0px 30px;color:#2cab50;background-color: #ededed; text-align:left;padding-left:30px;"><?php echo 'Gesamtsumme '.$tax_label?></th>
														<td style="color:#96a7b0;padding: 0px 30px 0px 0px;color:#2cab50;font-weight: normal;background-color: #ededed; text-align:right; "><?php echo $order_total['value']; ?></td>
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
														<th class="order_total" colspan="2" style="font-weight: normal;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; padding: 0px 0px 0px 30px;color:#96a7b0; text-align:left;padding-left:30px; "><?php echo $total['label']; ?></th>
														<td style="color:#96a7b0;padding: 0px 30px 0px 0px;font-weight: normal;border-bottom:1px solid #BDC9CC; text-align:right; "><?php echo $total['value']; ?></td>
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
					<?php }?>
				</div>
			</div>
		</div>
	
	</div>
</div>

	<?php 
get_footer();
?>
