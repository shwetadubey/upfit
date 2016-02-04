<?php
return array(
    'order-tracking'   => array(
        'name'              => 'YITH WooCommerce Order Tracking',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-order-tracking/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to manage order tracking', 'yith_wc_product_vendors' ),
        'premium'           => 'YITH_YWOT_PREMIUM',
        'installed_version' => 'YITH_YWOT_VERSION',
        'min_version'       => '1.1.9',
        'compare'           => '>='
    ),

    'subscription'    => array(
        'name'              => 'YITH WooCommerce Subscription',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-subscription/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create and manage Subscription products', 'yith_wc_product_vendors' ),
        'post_types'        => array( 'ywsbs_subscription' ),
        'capabilities'      => apply_filters( 'yith_wcmv_subscription_caps', array( 'edit_subscription' => true ) ),
        'premium'           => 'YITH_YWSBS_PREMIUM',
        'installed_version' => 'YITH_YWSBS_VERSION',
        'min_version'       => '1.0.0',
        'compare'           => '>='
    ),

    'name-your-price' => array(
        'name'              => 'YITH WooCommerce Name Your Price',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-name-your-price/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create and manage name your price products', 'yith_wc_product_vendors' ),
        'premium'           => 'YWCNP_PREMIUM',
        'installed_version' => 'YWCNP_VERSION',
        'min_version'       => '1.0.0',
        'compare'           => '>='
    ),

    'size-charts'      => array(
        'name'              => 'YITH Product Size Charts for WooCommerce',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-product-size-charts-for-woocommerce/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to add product size charts for their own products', 'yith_wc_product_vendors' ),
        'post_types'        => apply_filters( 'yith_wcpsc_vendor_allowed_post_types', array( 'yith-wcpsc-wc-chart' ) ),
        'capabilities'      => apply_filters( 'yith_wcpsc_vendor_allowed_caps', yith_wcmv_create_capabilities( array( 'size_chart', 'size_charts' ) ) ),
        'premium'           => 'YITH_WCPSC_PREMIUM',
        'installed_version' => 'YITH_WCPSC_VERSION',
        'min_version'       => '1.0.6',
        'compare'           => '>='
    ),

    'membership'      => array(
        'name'              => 'YITH WooCommerce Membership',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-membership/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create and manage memberships for their own customers', 'yith_wc_product_vendors' ),
        'post_types'        => apply_filters( 'yith_wcmbs_vendor_allowed_post_types', array( 'yith-wcmbs-plan' ) ),
        'capabilities'      => apply_filters( 'yith_wcmbs_vendor_allowed_caps', yith_wcmv_create_capabilities( array( 'plan', 'plans' ) ) ),
        'premium'           => 'YITH_WCMBS_PREMIUM',
        'installed_version' => 'YITH_WCMBS_VERSION',
        'min_version'       => '1.0.4',
        'compare'           => '>='
    ),

    'live-chat'       => array(
        'name'              => 'YITH Live Chat',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-live-chat/',
        'option_desc'       => __( 'If you enable this option, each vendor will be able to chat with their customers directly', 'yith_wc_product_vendors' ),
        'premium'           => 'YLC_PREMIUM',
        'installed_version' => 'YLC_VERSION',
        'min_version'       => '1.0.5',
        'compare'           => '>='
    ),

    'waiting-list'       => array(
        'name'              => 'YITH WooCommerce Waiting List',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-waiting-list/',
        'option_desc'       => __( 'If you enable this option, each vendor will be able to manage their waiting lists and send mail to their customers.', 'yith_wc_product_vendors' ),
        'premium'           => 'YITH_WCWTL_PREMIUM',
        'installed_version' => 'YITH_WCWTL_VERSION',
        'min_version'       => '1.0.6',
        'compare'           => '>='
    ),

    'surveys'      => array(
        'name'              => 'YITH WooCommerce Surveys',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-surveys/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create and manage surveys for their own customers', 'yith_wc_product_vendors' ),
        'post_types'        => apply_filters( 'yith_wc_surveys_vendor_allowed_post_types', array( 'yith_wc_surveys' ) ),
        'capabilities'      => apply_filters( 'yith_wc_surveys_vendor_allowed_caps', yith_wcmv_create_capabilities( array( 'survey', 'surveys' ) ) ),
        'premium'           => 'YITH_WC_SURVEYS_PREMIUM',
        'installed_version' => 'YITH_WC_SURVEYS_VERSION',
        'min_version'       => '1.0.1',
        'compare'           => '>='
    ),

    'badge-management'      => array(
        'name'              => 'YITH WooCommerce Badge Management',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-badge-management/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create and manage badges for their own products', 'yith_wc_product_vendors' ),
        'post_types'        => apply_filters( 'yith_wcbm_vendor_allowed_post_types', array( 'yith-wcbm-badge' ) ),
        'capabilities'      => apply_filters( 'yith_wcbm_vendor_allowed_caps', yith_wcmv_create_capabilities( array( 'badge', 'badges' ) ) ),
        'premium'           => 'YITH_WCBM_PREMIUM',
        'installed_version' => 'YITH_WCBM_VERSION',
        'min_version'       => '1.2.3',
        'compare'           => '>='
    ),

    'review-discounts'      => array(
        'name'              => 'YITH WooCommerce Review For Discounts',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-review-for-discounts/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create and manage discounts for their own customers', 'yith_wc_product_vendors' ),
        'post_types'        => array( 'ywrfd-discount' ),
        'capabilities'      => apply_filters( 'yith_wrfd_vendor_caps', yith_wcmv_create_capabilities( array( 'ywrfd-discount', 'ywrfd-discounts' ) ) ),
        'premium'           => 'YWRFD_PREMIUM',
        'installed_version' => 'YWRFD_VERSION',
        'min_version'       => '1.0.0',
        'compare'           => '>=',
    ),

    'coupon-email-system' => array(
        'name'              => 'YITH WooCommerce Coupon Email System',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-coupon-email-system/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create custom coupon and send it by email for their own customers', 'yith_wc_product_vendors' ),
        'premium'           => 'YWCES_PREMIUM',
        'installed_version' => 'YWCES_VERSION',
        'min_version'       => '1.0.5',
        'compare'           => '>='
    ),

    'pdf-invoice' => array(
        'name'              => 'YITH WooCommerce PDF Invoice',
        'landing_uri'       => '//yithemes.com/themes/plugins/yith-woocommerce-pdf-invoice/',
        'option_desc'       => __( 'If you enable this option, vendors will be able to create invoice for their order', 'yith_wc_product_vendors' ),
        'premium'           => 'YITH_YWPI_PREMIUM',
        'installed_version' => 'YITH_YWPI_VERSION',
        'min_version'       => '1.3.0',
        'compare'           => '>='
    ),
);