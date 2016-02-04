<?php
/**
 * Customer note email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p style="font-size:16px;color:#96a7b0; line-height: 28px;margin:0 auto;padding:0px 0px 20px 0px;"><?php _e( "Hello, a note has just been added to your order:", 'woocommerce' ); ?></p>

<blockquote><?php echo wpautop( wptexturize( $customer_note ) ) ?></blockquote>

<p><?php _e( "For your reference, your order details are shown below.", 'woocommerce' ); ?></p>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php printf( __( 'Bestellnummer #%s', 'woocommerce' ), $order->get_order_number() ); ?></h2>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; "><?php _e( 'Produkt', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 0px 0 30px;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC; "><?php _e( 'Anzahl', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:right;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 30px 0 0px;;height:72px;border-bottom:1px solid #BDC9CC; "><?php _e( 'Preis', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( $order->is_download_permitted(), true ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th class="td" scope="row" colspan="2" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td class="td" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php //do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php //do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>
