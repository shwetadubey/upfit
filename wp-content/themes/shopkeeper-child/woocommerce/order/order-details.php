<?php
/**
 * Order details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$order = wc_get_order( $order_id );
?>
<div class="order-detail-box">
<h2 class="dark-blue-color box-heading"><?php _e( 'BestellDetails', 'woocommerce' ); ?></h2>
<table class="shop_table order_details">
	<thead>
		<tr>
			<th class="product-name text-normal text-light dark-blue-color"><?php _e( 'Produkt', 'woocommerce' ); ?></th>
			<th class="product-total text-right text-normal text-light dark-blue-color"><?php _e( 'Summe', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach( $order->get_items() as $item_id => $item ) {
				wc_get_template( 'order/order-details-item.php', array(
					'order'   => $order,
					'item_id' => $item_id,
					'item'    => $item,
					'product' => apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item )
				) );
			}
		?>
		<?php do_action( 'woocommerce_order_items_table', $order ); ?>
	</tbody>
	<tfoot>
		<?php	
		global $woocommerce;
		if ( 'itemized' == get_option( 'woocommerce_tax_total_display' ) ) {
		
		foreach ( $order->get_tax_totals() as $code => $tax ) {
			$tax->rate = WC_Tax::get_rate_percent( $tax->rate_id );
			
			if ( ! isset( $tax_array[ 'tax_rate'] ) )
				$tax_array[ 'tax_rate' ] = array( 'tax' => $tax, 'amount' => $tax->amount, 'contains' => array( $tax ) );
			else {
				array_push( $tax_array[ 'tax_rate' ][ 'contains' ], $tax );
				$tax_array[ 'tax_rate' ][ 'amount' ] += $tax->amount;
			}
		}
		//$tax_label='<span class="include_tax">(inkl.&nbsp;'.$tax_array['tax_rate']['tax']->rate.'&nbsp;'..')</span>';
	}	
			
			/*echo '<pre>';
			print_r($order);
			echo '</pre>';
			$coupon=$order->get_used_coupons();
	
			$f=new WC_Coupon($coupon[0]);
		
			$discount = get_post_meta($f->id,'coupon_amount',true);
			$type = get_post_meta($f->id,'discount_type',true);
		$order->get_order_item_totals()['shipping']['label']='Gesamtsumme';
			$tax = $order->get_order_item_totals()['tax_Mwst.'];
			$order_total = $order->get_order_item_totals()['order_total'];
			*/
			
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				
				 if($key=='tax_'.$tax_array['tax_rate']['tax']->label)
				{
				}
				else
				{
		?>
					<tr class="order-details-total">
						<th scope="row" class="light-gray-text text-light"><?php echo $total['label']; ?></th>
						<td class="text-right light-gray-text text-light"><?php echo $total['value']; ?></td>
					</tr>
		<?php
				}
					
			}
		?>
	</tfoot>
</table>
</div>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<?php wc_get_template( 'order/order-details-customer.php', array( 'order' =>  $order ) ); ?>
