<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>
<div class="form-field yith-choosen">
    <label for="yith_vendor_owner"><?php _e( 'Vendor Shop Owner', 'yith_wc_product_vendors' ); ?></label>
    <select name="yith_vendor_data[owner]" id="yith_vendor_owner" class="ajax_chosen_select_customer" style="width:95%;" data-placeholder="Search for users">
        <option></option>
    </select>
    <br />
    <span class="description"><?php _e( 'User that can manage products in this vendor shop and view sale reports.', 'yith_wc_product_vendors' ); ?></span>
</div>

<div class="form-field">
    <label for="yith_vendor_paypal_email"><?php _e( 'PayPal email address', 'yith_wc_product_vendors' ); ?></label>
    <input type="text" class="regular-text" name="yith_vendor_data[paypal_email]" id="yith_vendor_paypal_email" value="" /><br />
    <span class="description"><?php _e( 'Vendor\'s PayPal email address where profits will be delivered.', 'yith_wc_product_vendors' ); ?></span>
</div>

<div class="form-field">
    <label for="yith_vendor_vat">
        <?php _e( 'VAT/SSN', 'yith_wc_product_vendors' ); ?>
    </label>
    <input type="text" class="regular-text" name="yith_vendor_data[vat]" id="yith_vendor_vat" value="" /><br />
    <span class="description"><?php _e( 'Vendor\'s VAT/SSN.', 'yith_wc_product_vendors' ); ?></span>
</div>

<div class="form-field">
    <label class="yith_vendor_enable_selling_label" for="yith_vendor_enable_selling"><?php _e( 'Enable sales', 'yith_wc_product_vendors' ); ?></label>
    <input type="checkbox" name="yith_vendor_data[enable_selling]" id="yith_vendor_enable_selling" value="yes" checked /><br />
    <span class="description"><?php _e( 'Enable or disable product sales.', 'yith_wc_product_vendors' ); ?></span>
</div>

<div class="form-field">
    <?php $skip_review = get_option( 'yith_wpv_vendors_option_skip_review' ); ?>
    <label class="yith_vendor_skip_revision_label" for="yith_vendor_skip_revision"><?php _e( 'Skip admin review', 'yith_wc_product_vendors' ); ?></label>
    <input type="checkbox" name="yith_vendor_data[skip_review]" id="yith_vendor_enable_selling" value="yes" <?php checked( 'yes', $skip_review ) ?>/><br />
    <span class="description"><?php _e( 'Allow vendors to add products without admin review', 'yith_wc_product_vendors' ); ?></span>
</div>

<div class="form-field">
    <div class="yith-vendor-commission">
        <label class="yith_vendor_commission_label" for="yith_vendor_commission"><?php _e( 'Commission', 'yith_wc_product_vendors' ); ?></label>
        <input type="number" class="regular-text" name="yith_vendor_data[commission]" id="yith_vendor_commission" value="<?php echo esc_attr( $commission * 100 ); ?>" min="0" max="100" step="0.1" /> %<br/>
    </div>
    <span class="description"><?php _e( 'Percentage of the total sale price that this vendor receives', 'yith_wc_product_vendors' ); ?></span>
</div>