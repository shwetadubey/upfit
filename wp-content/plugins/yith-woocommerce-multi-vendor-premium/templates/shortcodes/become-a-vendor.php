<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$firstname          = ! empty( $_POST['vendor-owner-firstname'] )   ? sanitize_text_field( $_POST['vendor-owner-firstname'] ) : '';
$lastname           = ! empty( $_POST['vendor-owner-lastname'] )    ? sanitize_text_field( $_POST['vendor-owner-lastname'] ) : '';
$store_name         = ! empty( $_POST['vendor-name'] )              ? sanitize_text_field( $_POST['vendor-name'] ) : '';
$store_location     = ! empty( $_POST['vendor-location'] )          ? sanitize_text_field( $_POST['vendor-location'] ) : '';
$store_email        = ! empty( $_POST['vendor-email'] )             ? sanitize_email( $_POST['vendor-email'] ) : '';
$store_telephone    = ! empty( $_POST['vendor-telephone'] )         ? sanitize_text_field( $_POST['vendor-telephone'] ) : '';
$vat                = ! empty( $_POST['vendor-vat'] )               ? sanitize_text_field( $_POST['vendor-vat'] ) : '';

?>

<div id="yith-become-a-vendor" class="woocommerce shortcodes">
    <?php wc_print_notices(); ?>
    <form method="post" class="register">
        <p class="form-row form-row-wide">
            <label for="vendor-name"><?php _e( 'Store name *', 'yith_wc_product_vendors' )?></label>
            <input type="text" class="input-text yith-required" name="vendor-name" id="vendor-name" value="<?php echo $store_name ?>">
        </p>

        <p class="form-row form-row-wide">
            <label for="vendor-location"><?php _e( 'Address *', 'yith_wc_product_vendors' )?></label>
            <input type="text" class="input-text yith-required" name="vendor-location" id="vendor-location" value="<?php echo $store_location ?>" placeholder="MyStore S.A. Avenue MyStore 55, 1800 Vevey, Switzerland">
        </p>

        <p class="form-row form-row-wide">
            <label for="vendor-email"><?php _e( 'Email *', 'yith_wc_product_vendors' )?></label>
            <input type="text" class="input-text yith-required" name="vendor-email" id="vendor-email" value="<?php echo $store_email ?>">
        </p>

        <p class="form-row form-row-wide">
            <label for="vendor-telephone"><?php _e( 'Telephone *', 'yith_wc_product_vendors' )?></label>
            <input type="text" class="input-text yith-required" name="vendor-telephone" id="vendor-telephone" value="<?php echo $store_telephone ?>">
        </p>

        <p class="form-row form-row-wide">
            <?php $vat_field_required =  $is_vat_require ? '*' : ''; ?>
            <label for="vendor-vat"><?php echo __( 'VAT/SSN', 'yith_wc_product_vendors' ) . ' ' . $vat_field_required ?></label>
            <input type="text" class="input-text <?php echo $is_vat_require ? 'yith-required' : '' ?>" name="vendor-vat" id="vendor-vat" value="<?php echo $vat ?>">
        </p>

        <p class="form-row">
            <?php wp_nonce_field( 'woocommerce-register' ); ?>
            <input type="button" id="yith-become-a-vendor-submit" class="<?php apply_filters( 'yith_wpv_become_a_vendor_button_class', 'button' ) ?>" name="register" value="<?php esc_attr_e( 'Become a vendor', 'yith_wc_product_vendors' ); ?>" />
            <input type="hidden" id="yith-vendor-register" name="vendor-register" value="1">
            <input type="hidden" id="vendor-antispam" name="vendor-antispam" value="">
        </p>
    </form>
</div>