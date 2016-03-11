<?php 
/*
 * Template Name: Email HTML Template for new order
 * 
 */
get_header();
$order_id=$_GET['order_id'];

$order = new WC_Order( $order_id );

?>
<div class="row">
	<div class="xlarge-6 xlarge-centered large-6 large-centered columns">
		<p class="padding_bottom_add" style="font-size:16px; font-family:'Conv_AvenirLTStd-Medium', sans-serif; color:#96a7b0; line-height: 28px;margin:0 auto;padding:0 0 40px 0;"><?php printf( __( 'You have received an order from %s. The order is as follows:', 'woocommerce' ), $order->get_formatted_billing_full_name() ); ?></p>

		<?php do_action( 'woocommerce_email_before_order_table', $order, true, false ); ?>

		<h2 style="color:#162c5d; font-size:24px;line-height: 28px; margin:0 auto 20px;font-weight: normal;"><a style="color:#162c5d; text-decoration:none;" class="link" href="<?php echo admin_url( 'post.php?post=' . $order->id . '&action=edit' ); ?>"><?php printf( __( 'Bestellnummer: %s', 'woocommerce'), $order->get_order_number() ); ?></a> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>
		<div class="row">
			<div class="xlarge-12 xlarge-centered large-8 large-centered columns payment_details">
				<table cellspacing="0" cellpadding="0" style="width:100%;border:1px solid #BDC9CC;width:100%;color:#96a7b0;font-family:Helvetica, Arial;" >
					<thead>
						<tr>
							<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC;font-family:Helvetica, Arial; "><?php _e( 'Produkt', 'woocommerce' ); ?></th>
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
							
								$coupon=$order->get_used_coupons();
								
								$f=new WC_Coupon($coupon[0]);
								
								$discount = get_post_meta($f->id,'coupon_amount',true);
								$type = get_post_meta($f->id,'discount_type',true);
							
								$tax = $order->get_order_item_totals()['tax_MwSt.'];
								$order_total = $order->get_order_item_totals()['order_total'];

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
													<th scope="row" colspan="2" style="font-weight: normal; padding: 0px 0px 0px 30px;color:#2cab50;background-color: #ededed; text-align:left;padding-left:30px;"><?php echo 'Gesamtsumme <span class="inc-tax" style="color:#96a7b0;">('.$tax_label1[0]['label'].')</span>'; ?></th>
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
			</div>
		</div>
	</div>
</div>
