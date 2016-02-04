<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WPV_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Multi_Vendor_Shortcodes
 * @package    Yithemes
 * @since      Version 2.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_Multi_Vendor_Shortcodes' ) ) {
    /**
     * Class YITH_Multi_Vendor_Shortcodes
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     */
    class YITH_Multi_Vendor_Shortcodes {

        /**
         * Add Shortcodes
         *
         * @return void
         * @since  1.7
         * @author andrea Grillo <andrea.grillo@yithemes.com>
         */
        public static function load() {
            $shortcodes = array(
                'yith_wcmv_list'            => 'YITH_Multi_Vendor_Shortcodes::vendors_list',
                'yith_wcmv_become_a_vendor' => 'YITH_Multi_Vendor_Shortcodes::become_a_vendor'
            );

            foreach ( $shortcodes as $shortcode => $callback ) {
                add_shortcode( $shortcode, $callback );
            }
        }

        /**
         * Print vendors list shortcodes
         *
         * @param array $sc_args The Shortcode args
         *
         * @return void
         * @since  1.7
         * @author andrea Grillo <andrea.grillo@yithemes.com>
         */
        public static function vendors_list( $sc_args = array() ) {
            $default = array(
                'per_page'                => - 1,
                'hide_no_products_vendor' => false
            );

            $sc_args      = wp_parse_args( $sc_args, $default );
            $vendors_args = array( 'enabled_selling' => true );
            $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
            $per_page     = absint( $sc_args['per_page'] );
            $total        = ceil( count( YITH_Vendors()->get_vendors( array( 'enabled_selling' => true, 'fields' => 'ids' ) ) ) / $per_page );

            if ( ! empty( $sc_args['per_page'] ) ) {
                $pagination_args = array(
                    'pagination' => array(
                        'offset' => ( $paged - 1 ) * absint( $sc_args['per_page'] ),
                        'number' => $per_page,
                        'type'   => 'list'
                    )
                );
                $vendors_args    = array_merge( $vendors_args, $pagination_args );
            }
            $vendors = YITH_Vendors()->get_vendors( $vendors_args );


            if ( empty( $vendors ) ) {
                return false;
            }

            $args = array(
                'vendors'          => $vendors,
                'paginate'         => array(
                    'current' => $paged,
                    'total'   => $total,
                ),
                'show_total_sales' => 'yes' == get_option( 'yith_wpv_vendor_total_sales' ) ? true : false,
                'sc_args'          => $sc_args,
                'icons'            => apply_filters( 'yith_wcmv_header_icons_class', array(
                        'rating' => 'fa fa-star-half-o',
                        'sales'  => 'fa fa-credit-card'
                    )
                ),
            );

            yith_wcpv_get_template( 'vendors-list', $args, 'shortcodes' );
        }

        /*
         * Print register vendor form
         *
         * @param array $sc_args The Shortcode args
         *
         * @return void
         * @since  1.7
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public static function become_a_vendor( $sc_args = array() ) {
            $user = wp_get_current_user();
            $vendor = yith_get_vendor( $user->ID, 'user' );
            if( ! $vendor->is_valid() && ( in_array( 'subscriber', $user->roles ) || in_array( 'customer', $user->roles ) ) ) {
                yith_wcpv_get_template( 'become-a-vendor', array( 'is_vat_require' => YITH_Vendors()->is_vat_require() ), 'shortcodes' );
            }
        }

        /*
         * check if the current user is a vendor. If yes redericet it to My Account Dashboard
         * otherwise show the "become a vendor" form
         *
         * @return void
         * @since  1.7
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public static function become_a_vendor_template_check(){
            $become_a_vendor_page   = absint( get_option( 'yith_wpv_become_a_vendor_page_id', 0 ) );
            $user                   = wp_get_current_user();
            $vendor                 = yith_get_vendor( $user->ID, 'user' );
            $become_a_vendor_page && is_page( $become_a_vendor_page ) && ( ! is_user_logged_in() || $vendor->is_valid() || $vendor->is_super_user() ) && wp_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) ) && exit;
        }
    }
}
