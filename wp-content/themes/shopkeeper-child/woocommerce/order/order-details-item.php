<?php
/**
 * Order Item Details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
	<td class="product-name">
		<?php
			$is_visible = $product && $product->is_visible();
			echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity light-gray-text text-light">' . sprintf( '%s&times; ', $item['qty'] ) . '</strong>', $item );
			//echo apply_filters( 'woocommerce_order_item_name', $is_visible ? sprintf( '<a class="light-gray-text text-light" href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ) : $item['name'], $item, $is_visible );
			echo '<span class="light-gray-text">'.$item['name'].'</span>';
		//	do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );
			//$order->display_item_meta( $item );
			$order->display_item_downloads( $item );

		//	do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
		?>
	</td>
	<td class="product-total text-right light-gray-text text-light">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
	</td>
</tr>
<?php if ( $order->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $product->id, '_purchase_note', true ) ) ) : ?>
<tr class="product-purchase-note">
	<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
</tr>
<?php endif; ?>
