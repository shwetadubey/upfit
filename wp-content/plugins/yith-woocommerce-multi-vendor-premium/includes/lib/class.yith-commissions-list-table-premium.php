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
    exit( 'Direct access forbidden.' );
}


if ( ! class_exists( 'YITH_Commissions_List_Table_Premium' ) ) {
    /**
     *
     *
     * @class class.yith-commissions-list-table
     * @package    Yithemes
     * @since      Version 1.0.0
     * @author     Your Inspiration Themes
     *
     */
    class YITH_Commissions_List_Table_Premium extends YITH_Commissions_List_Table {

        /**
         * Months Dropdown value
         *
         * @var array
         * @since 1.0
         */
        protected $_months_dropdown = array();

        /**
         * Construct
         */
        public function __construct(){
            parent::__construct();

            // Months dropdown
            $this->_months_dropdown = $this->months_dropdown_results( 'commissions' );
            add_filter( 'months_dropdown_results', array( $this, 'get_months_dropdown' ) );
            add_action( 'yith_wpv_after_order_column', array( $this, 'get_order_status' ), 10, 1 );
            add_action( 'wc_order_statuses', array( $this, 'custom_order_status' ) );

            //Order column
            add_filter( 'yith_wcmv_commissions_order_column', array( $this, 'commissions_order_column' ), 10, 2 );
        }

        /**
         * Sets bulk actions for table
         *
         * @return array Array of available actions
         * @since 1.0.0
         */
        public function get_bulk_actions() {
            $actions = array();

            if( $this->_vendor->is_super_user() ){
                foreach( YITH_Commissions()->get_status() as $action => $label ){
                    $actions[ $action ] = __( 'Change to', 'yith_wc_product_vendors' ) . ' ' . $label;
                }
                $actions = array_merge( array( 'pay' => __( 'Pay', 'yith_wc_product_vendors' ) ), $actions );
            }

            return  $actions;
        }

        /**
         * Extra controls to be displayed between bulk actions and pagination
         *
         * @since 3.1.0
         * @access protected
         */
        protected function get_views() {

            $views = array( 'all' => __( 'All', 'yith_wc_product_vendors' ) ) + YITH_Commissions()->get_status();
            $current_view = $this->get_current_view();
            $args = array( 'status' => 0 );

            if( $this->_vendor->is_valid() && $this->_vendor->has_limited_access() && $this->_vendor->is_owner() ){
                $args[ 'user_id' ] = get_current_user_id();
            }

	        // merge Unpaid with Processing
	        $views['unpaid'] .= '/' . $views['processing'];
	        unset( $views['processing'] );

            foreach ( $views as $id => $view ) {
                $href           = esc_url( add_query_arg( 'status', $id ) );
                $class          = $id == $current_view ? 'current' : '';
                $args['status'] = 'unpaid' == $id ? array( $id, 'processing' ) : $id;
                $count          = YITH_Commissions()->count_commissions( $args );
                $views[$id]     = sprintf( "<a href='%s' class='%s'>%s <span class='count'>(%d)</span></a>", $href, $class, $view, $count );
            }

            return $views;
        }

        /**
         * Returns columns available in table
         *
         * @return array Array of columns of the table
         * @since 1.0.0
         */
        public function get_columns() {
            $old_columns    = parent::get_columns();
            $before_columns = array( 'cb' => '<input type="checkbox" />' );
            $vendor_index   = array_search( 'amount', array_keys( $old_columns ) );

            $old_columns = array_merge(
                array_slice( $old_columns, 0, $vendor_index ),
                array( 'shipping_address' => __( 'Ship to', 'woocommerce' ) ),
                array_slice( $old_columns, $vendor_index, count( $old_columns ) )
            );

            return array_merge( $before_columns, $old_columns );
        }

        /**
         * Extra controls to be displayed between bulk actions and pagination
         *
         * @since  1.0.0
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         *
         * @return string The view name
         */
        public function get_current_view() {
            return empty( $_GET['status'] ) ? get_option( 'yith_commissions_default_table_view', 'unpaid' ) : $_GET['status'];
        }

        /**
         * Extra controls to be displayed between bulk actions and pagination
         *
         * @since 3.1.0
         * @access protected
         *
         * @param string $which
         */
        protected function extra_tablenav( $which ) {
            if ( 'top' == $which ) {
                $this->months_dropdown( 'commissions' );

                $this->product_dropdown();
            }
        }

        /**
         * Add the product dropdown
         *
         * @return void
         * @since 1.0
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function product_dropdown(){
            $product_id         = ! empty( $_REQUEST['product_id'] ) ? $_REQUEST['product_id'] : '';
            $product            = ! empty( $product_id ) ? wc_get_product( $product_id ) : false;
            $product_display    = ! empty( $product ) ? $product->post_title . '(#' . $product->id . ' - ' . $product->post->post_title . ')' : '';
            $reset_button       = esc_url( add_query_arg( array( 'page' => $_REQUEST['page'] ), admin_url( 'admin.php' ) ) );
            ?>
            <div id="product_data_search" class="panel woocommerce_options_panel yith-wpv-commissions">
				<div class="options_group">
                    <input type="hidden"
                       class="wc-product-search"
                       style="width: 50%;"
                       id="product_id"
                       name="product_id"
                       data-placeholder="<?php _e( 'Search for a product&hellip;', 'yith_wc_product_vendors' ); ?>"
                       data-action="woocommerce_json_search_products"
                       data-multiple="false"
                       data-selected="<?php echo $product_display ?>"
                       value="<?php echo $product_id ?>" />
                    <?php submit_button( __( 'Filter', 'yith_wc_product_vendors' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) ); ?>
                    <a href="<?php echo $reset_button ?>" class="button-primary" style="margin: 1px 8px 0 0;"><?php _e( 'Reset', 'yith_wc_product_vendors' )?></a>
				</div>
			</div>
        <?php
        }

	    /**
	     * Month Dropdown filter
	     *
	     * @since 3.1.0
	     * @access protected
	     *
	     * @return mixed
	     */
        public function months_dropdown_results() {
            global $wpdb;

            $current_view = $this->get_current_view();
            $where        = 'WHERE 1=1 ';

            if ( 'all' != $current_view ) {
                $where .= 'AND status=%s';
            }

            $months = $wpdb->get_results( $wpdb->prepare( "
                SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
                FROM $wpdb->posts
                WHERE post_type = %s
                AND ID IN (
                    SELECT DISTINCT order_id
                    FROM $wpdb->commissions $where
                    )
                ORDER BY post_date DESC
            ", 'shop_order', $current_view ) );

            return $months;
        }

        /**
         * Get month dropdown protected var
         *
         * @since 1.0.0
         * @access protected
         *
         * @param string $which
         */
        public function get_months_dropdown() {
          return $this->_months_dropdown;
        }

        /**
         * Display the search box.
         *
         * @since 3.1.0
         * @access public
         *
         * @param string $text     The search button text
         * @param string $input_id The search input id
         */
        public function add_search_box( $text, $input_id ) {
            parent::search_box( $text, $input_id );
        }

	    /**
	     * Display the search box.
	     *
	     * @since 3.1.0
	     * @access public
	     *
	     * @param $rec YITH_Commission
	     */
        public function column_user_actions( $rec ) {
            printf( '<a class="button tips view" href="%1$s" data-tip="%2$s">%2$s</a>', $rec->get_view_url( 'admin' ), __( 'View', 'yith_wc_product_vendors' ) );

            if ( $this->_vendor->is_super_user() ) {

	            if ( $rec->has_status( 'unpaid' ) ) {
                    printf( '<a class="button tips pay" href="%1$s" data-tip="%2$s">%2$s</a>',
                        esc_url( wp_nonce_url( add_query_arg( array( 'action' => 'pay_commission', 'commission_id' => $rec->id ), admin_url( 'admin.php' ) ), 'yith-vendors-pay-commission' ) ),
                            __( 'Pay', 'yith_wc_product_vendors' )
                    );
	            }

                $all_status = YITH_Commissions()->get_status();
                $action_url = admin_url( 'admin.php' );
                $args       = array(
                    'action'        => 'yith_commission_table_actions',
                    'commission_id' => $rec->id
                );
                foreach ( $all_status as $status => $display ) {
                    if ( $status == 'processing' || ! YITH_Commissions()->is_status_changing_permitted( $status, $rec->status ) ) {
                        continue;
                    }
                    $args['new_status'] = $status;
                    $action_url         = add_query_arg( $args, $action_url );
                    printf( '<a class="action-link" href="%1$s"><mark data-tip="%2$s" class="%3$s tips">%2$s</mark></a>', $action_url, __( 'Change status to', 'yith_wc_product_vendors' ) . ' ' . $display, $status );
                }
            }
        }

        public function column_shipping_address( $rec ) {
            /** @var WC_Order $order */
            $order = wc_get_order( $rec->order_id );

            if( ! $order ){
                echo '<small class="meta">-</small>';
                return false;
            }

            if ( $address = $order->get_formatted_shipping_address() ) {
                echo '<a target="_blank" href="' . esc_url( $order->get_shipping_address_map_url() ) . '">' . esc_html( preg_replace( '#<br\s*/?>#i', ', ', $address ) ) . '</a>';
            }
            else {
                echo '&ndash;';
            }

            if ( $order->get_shipping_method() ) {
                echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $order->get_shipping_method() ) . '</small>';
            }
        }

         /**
	     * Get order status by order item
	     *
	     * @since 3.1.0
	     * @access public
	     *
	     * @param $order WC_Order
	     */
        public function get_order_status( $order ){
            $wc_order_status = wc_get_order_statuses();
            echo '<small>(' .  __( 'Order status:' ) . ' ' . $wc_order_status[ $order->post_status ] . ')</small>';
        }

        /**
         * Add WooCommerce Order Custom Status
         *
         * @return array Array of columns of the table
         * @since 1.0.0
         */
        public function custom_order_status( $status ){
            $status['trash'] = _x( 'Trashed', 'Order status', 'yith_wc_product_vendors' );
            return $status;
        }

        /**
         * Filter the order link, if vendor has access
         *
         * @param $value    The string to output
         * @param $order_id The order id
         *
         * @return string The output string
         * @since 1.6
         */
        public function commissions_order_column( $value, $order_id ){
            $vendor = yith_get_vendor( 'current', 'user' );
            if( $vendor->is_valid() && $vendor->has_limited_access() && wp_get_post_parent_id( $order_id )&& in_array( $order_id, $vendor->get_orders() ) ){
                $value = '<a href="' . admin_url( 'post.php?post=' . absint( $order_id ) . '&action=edit' ) . '">' . $value . '</a>';;
            }

            return $value;
        }
    }
}

