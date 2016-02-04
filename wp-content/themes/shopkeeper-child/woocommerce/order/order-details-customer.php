<?php
/**
 * Order Customer Details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="order-detail-box">
<h2 class="dark-blue-color box-heading"><?php _e( 'Kundendetails', 'woocommerce' ); ?></h2>

<table class="shop_table shop_table_responsive customer_details shop_table order_details">
	<?php if ( $order->customer_note ) : ?>
		<tr>
			<th class="dark-blue-color text-light"><?php _e( 'Notiz', 'woocommerce' ); ?></th>
			<td class="light-gray-text text-light text-right"><?php echo wptexturize( $order->customer_note ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->billing_email ) : ?>
		<tr>
			<th class="dark-blue-color text-light"><?php _e( 'Email', 'woocommerce' ); ?></th>
			<td class="light-gray-text text-light text-right"><?php echo esc_html( $order->billing_email ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->billing_phone ) : ?>
		<tr>
			<th class="dark-blue-color text-light"><?php _e( 'Telefon', 'woocommerce' ); ?></th>
			<td class="light-gray-text text-light text-right"><?php echo esc_html( $order->billing_phone ); ?></td>
		</tr>
	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
</table>
</div>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>

<div class="col2-set addresses">
	<div class="col-1">

<?php endif; ?>
<div class="order-detail-box">
<h2 class="dark-blue-color box-heading"><?php _e( 'Rechnungsadresse', 'woocommerce' ); ?></h2>
<address class="light-gray-text text-light">
	<?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
</address>
</div>
<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>

	</div><!-- /.col-1 -->
	<div class="col-2">
	<div class="order-detail-box">
		<header class="title">
			<h3 class="text-uppercase text-light"><?php _e( 'Lieferanschrift', 'woocommerce' ); ?></h3>
		</header>
		<address>
			<?php echo ( $address = $order->get_formatted_shipping_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
		</address>
		</div>
	</div><!-- /.col-2 -->
</div><!-- /.col2-set -->

<?php endif; ?>
