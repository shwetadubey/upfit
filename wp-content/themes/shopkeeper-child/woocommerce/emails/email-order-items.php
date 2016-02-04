<?php
/**
 * Email Order Items
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.1.2
 */
global $wpdb;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

foreach ( $items as $item_id => $item ) :
	$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
	$item_meta    = new WC_Order_Item_Meta( $item, $_product );
	//echo "<pre>";print_r($order);
	
	if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		?>
		<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
	
			<td style="color:#96a7b0; font-size:16px;line-height:30px; font-weight: normal; background-color:#fff;height:72px;padding:20px 0 20px 30px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC;">
				
				<?php
					
				// Show title/image etc
				if ( $show_image ) {
					echo apply_filters( 'woocommerce_order_item_thumbnail', '<div style="margin-bottom: 5px"><img src="' . ( $_product->get_image_id() ? current( wp_get_attachment_image_src( $_product->get_image_id(), 'thumbnail') ) : wc_placeholder_img_src() ) .'" alt="' . esc_attr__( 'Product Image', 'woocommerce' ) . '" height="' . esc_attr( $image_size[1] ) . '" width="' . esc_attr( $image_size[0] ) . '" style="vertical-align:middle; margin-right: 10px;" /></div>', $item );
				}

				// Product name
				echo $item['name'];
				//echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item, false );
				//echo "status:".$order->post_status;
				
				if($order->post_status == 'wc-completed'){
					$site_id=get_current_blog_id();
					$results = $wpdb->get_results( 'SELECT * FROM up_user_nutrition_plans WHERE order_id ='.$order->id.' AND site_id='.$site_id);
					//echo "<pre>";print_r($results);
					if(!empty($results[0]->re_pdf_path) && !empty($results[0]->re_pdf_name)){
						$pdf_name=$results[0]->re_pdf_name;
						$pdf_path=$results[0]->re_pdf_path;
					
					}
					else{
						$pdf_name=$results[0]->pdf_name;
						$pdf_path=$results[0]->pdf_path;
						
					}
					if(isset($pdf_name) && !empty($pdf_path))
					{
						$plan_name=get_post($results[0]->plan_id)->post_title;
					
					?>
						<div style="cursor: pointer;">
							<a class="pdf_link_dow" style="color:#162c5d;cursor: pointer;text-transform:capitalize;" href="<?php echo $pdf_path; ?>" target="_blank"><?php echo $plan_name.' download';?></a>
						</div>
					<?php
					}
				
				}
				// SKU
				if ( $show_sku && is_object( $_product ) && $_product->get_sku() ) {
					echo ' (#' . $_product->get_sku() . ')';
				}

				// allow other plugins to add additional product information here
				do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

				// Variation
				if ( ! empty( $item_meta->meta ) ) {
				//	echo '<br/><small>' . nl2br( $item_meta->display( true, true, '_', "\n" ) ) . '</small>';
				}

				// File URLs
				if ( $show_download_links ) {
					//$order->display_item_downloads( $item );
				}

				// allow other plugins to add additional product information here
				do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
				
			?>
			
			</td>
			<td style="color:#96a7b0;text-align:left; font-size:16px;line-height: 16px; font-weight: normal;padding:0 0px 0 30px; background-color:#fff;height:72px;border-right:1px solid #BDC9CC;border-bottom:1px solid #BDC9CC;"><?php echo apply_filters( 'woocommerce_email_order_item_quantity', $item['qty'], $item ); ?></td>
			<td style="color:#96a7b0;text-align:right; font-size:16px;line-height: 16px; font-weight: normal; background-color:#fff;height:72px;padding:0px 30px 0px 0px;border-bottom:1px solid #BDC9CC;"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td>
		</tr>
		<?php
	}

	if ( $show_purchase_note && is_object( $_product ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) : ?>
		<tr>
			<td colspan="3" style="text-align:left; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
		</tr>
	<?php endif; ?>
	
<?php

 endforeach;?>
