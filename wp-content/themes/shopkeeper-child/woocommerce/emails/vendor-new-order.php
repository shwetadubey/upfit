<?php
/**
 * Admin new order email
 *
 * @author WooThemes
 * @package WooCommerce/Templates/Emails/HTML
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/** @var $yith_wc_email YITH_WC_Email_New_Order */
$action='&order_action=vendor_new_order';
$order_id=$order->id;
 do_action('woocommerce_email_header', $email_heading,$order_id.$action);?>

<p><?php  _e( 'Du hust eine Bestellung erhalten. Sie lautet wie foigt:', 'woocommerce' ); ?></p>

<?php do_action( 'woocommerce_email_before_order_table', $order, true, false ); ?>

<h2 style="margin:20px auto 20px"><?php printf( __( 'Bestellnummer: %s', 'woocommerce'), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

<table class="email_box_border" cellspacing="0" cellpadding="0" style="width: 100%; " >
	<thead>
		<tr>
			<th scope="col"  style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 20px 0 15px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _e( 'Produkt', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 13px 0 13px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _e( 'Anzahl', 'yith_wc_product_vendors' ); ?></th>
			<th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 13px 0 13px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _e( 'Preis', 'woocommerce' ); ?></th>
            <th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 15px 0 13px;border-bottom:1px solid #BDC9CC; border-right:1px solid #BDC9CC; font-family:Helvetica, Arial; height:72px;"><?php _ex( 'Gutschriften', 'Email: commission rate column', 'yith_wc_product_vendors' ); ?></th>
            <th scope="col" style="text-align:left;color:#162c5d; font-size:16px;line-height: 16px; font-weight: normal; background-color:#EDEDED;padding:0 13px 0 13px;border-bottom:1px solid #BDC9CC;  font-family:Helvetica, Arial; height:72px;"><?php _ex( 'Verdienste', 'Email: commission amount column', 'yith_wc_product_vendors' ); ?></th>
		</tr>
	</thead>
    <?php echo $vendor->email_order_items_table( $order, false, true ); ?>
</table>

<?php// do_action( 'woocommerce_email_after_order_table', $order, true, false ); ?>

<?php// do_action( 'woocommerce_email_order_meta', $order, true, false ); ?>

<?php// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' );  ?>
