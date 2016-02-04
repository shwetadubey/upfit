<?php
/**
 * Review order table
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<table class="shop_table woocommerce-checkout-review-order-table from-child-theme">
	<thead>
		<tr>
			<th class="product-name"><?php _e( 'Produkt', 'woocommerce' ); ?></th>
			<th class="product-total"><?php _e( 'Summe', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="product-name">
							<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
							<?php //echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
							<?php echo WC()->cart->get_item_data( $cart_item ); ?>
						</td>
						<td class="product-total">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
						</td>
					</tr>
					<?php
				}
			}
		
			do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
		
	</tbody>
	<tfoot>
	<!--
		<tr class="cart-subtotal">
			<th><?php// _e( 'Subtotal', 'woocommerce' ); ?></th>
			<td><?php// wc_cart_totals_subtotal_html(); ?></td>
		</tr>
-->
		<?php 
			
		foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<td><?php wc_cart_totals_coupon_label( $coupon ); ?></td>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>
		<tr class="shipping">
			<td><?php	_e( 'Versandkosten', 'woocommerce' ); ?></td>
			<td><span class="amount"><?php echo '0 '.get_woocommerce_currency_symbol();?></span></td>
		</tr>
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php// wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<td><?php echo esc_html( $fee->name ); ?></td>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && WC()->cart->tax_display_cart === 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<td><?php echo esc_html( $tax->label ); ?></td>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<td><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></td>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php _e( 'Gesamtsumme', 'woocommerce' ); 
			if ( wc_tax_enabled() && WC()->cart->tax_display_cart == 'incl' ) {
				$tax_string_array = array();
				if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
					$_tax = new WC_Tax();//looking for appropriate vat for specific product
					$rates = array_shift($_tax->get_rates( $_product->get_tax_class() ));
				  foreach ( WC()->cart->get_tax_totals() as $code => $tax ){
			
					$tax_string_array[] = sprintf( '%s %s',round($rates['rate']).'%', $tax->label );
					}
				} else {
				  $tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
				}
				if ( ! empty( $tax_string_array ) ) {
				  $value .= '<small class="includes_tax">' . sprintf( __( '(inkl.&nbsp;%s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) . '</small>';
				}
			  }
			 
			  echo $value;	
			?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
<script>

	jQuery('#order_review .shop_table .cart-discount td a').remove();		

</script>
