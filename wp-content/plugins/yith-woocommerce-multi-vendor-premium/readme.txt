=== YITH WooCommerce Multi Vendor Premium ===

Contributors: yithemes
Tags: product vendors, vendors, vendor, multi store, multi vendor, multi seller, woocommerce product vendors, woocommerce multi vendor, commission rate, seller, shops, vendor shop, vendor system, woo vendors, wc vendors, e-commerce, yit, yith, yithemes
Requires at least: 4.0
Tested up to: 4.4.1
Stable tag: 1.8.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Multi vendor e-commerce is a new idea of e-commerce platform that is becoming more and more popular in the web.

== Changelog ==

= 1.8.4 =

* Fixed: Product variations in order with latest WooCommerce

= 1.8.3 =

* Added: Advanced text editor for vendor description
* Fixed: Store header wrapper stylesheet error, no margin bottom
* Fixed: Vendor table column style
* Fixed: Vendor with no order can see all shop orders
* Fixed: yith_vendors not defined in vendor taxonomy page
* Fixed: User with vendor role but without store can edit products, order, coupons, ecc.
* Fixed: add to cart button disappears in Nielsen Theme with vacation module enabled

= 1.8.2 =

* Added: Hide customer section in order details page for vendor
* Added: Calculate commission include tax

= 1.8.1 =

* Fixed: Vendor lost translated product if edit by website admin
* Fixed: Support to WPML in vendor store page (frontend)
* Fixed: Can't create vendor sidebar in YITH Thmemes with WordPress 4.4
* Fixed: WooCommerce Report can't show correct information

= 1.8.0 =

* Added: Support to WordPress 4.4
* Added: Disabled vendor logo (gravatar) image in each vendor store page
* Added: Change vendor logo (gravatar) image size
* Added: Support to YITH WooCommerce Waiting List (vendor can manage waiting list)
* Added: Support to YITH WooCommerce Order Tracking (vendor can manage tracking code)
* Added: Support to YITH WooCommerce Membership (vendor can manage membership plans)
* Added: Support to YITH WooCommerce Subscription (vendor can manage subscription)
* Added: Support to YITH WooCommerce Badge Management (vendor can manage product badges)
* Added: Support to YITH WooCommerce Survey (vendor can manage survey)
* Added: Support to YITH WooCommerce Coupon Email System (vendor can send coupon by mail)
* Added: yith_wcmv_vendor_taxonomy_args hook tyo change taxonomy rewrite rules
* Added: Change vendor store taxonomy rewrite slug option
* Added: Antispam filter for vendor registration form
* Added: Antispam filter for become a vendor form
* Tweak: Flush rewrite rules to prevent 404 not found page after plugin update in vendor store page
* Tweak: Vendor taxonomy menu management
* Fixed: Vendor can't see admin dashboard and vendor rules after plugin update
* Fixed: Undefined suborder_id when add inline item to parent order
* Fixed: Admin and Vendor can't view trashed orders
* Fixed: Issue with YITH WooCommerce Gift Card in checkout page
* Fixed: Lost products after edit vendor slug
* Fixed: New vendor without user role
* Fixed: Vendor information validation on become a vendor page
* Fixed: WPML issue vendor can edit her/his products in other languages

= 1.7.3 =

* Tweak: Performance increase use php construct instanceof instead of is_a function
* Tweak: Order management (added order version in DB)
* Fixed: Vendor can't add or upload a store image
* Fixed: Store Name and Gravatar issue
* Fixed: Can't see product variation in vendor order details
* Fixed: Website admin can't assigne products to a specific vendor

= 1.7.2 =

* Updated: Language files
* Fixed: Shop manager can't edit vendors profile
* Fixed: Customer can't register if VAT/SSN fields is set to required

= 1.7.1 =

* Added: Support to YITH Product Size Charts for WooCommerce Premium
* Added: Support to YITH WooCommerce Name Your Price Premium

= 1.7.0 =

* Added: Refund management
* Added: New user role "Vendor" (Dashboard->Users)
* Added: yit_wcmv_plugin_options_capability hook for admin panel capabilities
* Added: VAT/SSN field in vendor registration
* Added: yith_wcmv_vendor_capabilities hook
* Added: Store description in vendor page
* Updated: Languages file
* Tweak: User capabilities
* Tweak: Performance improved with new plugin core 2.0
* Fixed: Delete user capabilities after deactive or remove plugin
* Fixed: Fields "Commission id" in commission table doesn't display correctly
* Fixed: Unable to create new vendor account in front-end
* Fixed: Wrong user capabilities after delete vendor account
* Fixed: Add order link in dashboard menu
* Fixed: Issue with Date filter in Vendor sales report

= 1.6.5. =

* Updated: Italian translation 
* Fixed: Product amount limit doesn't calculate correct vendor products

= 1.6.4 =

* Fixed: Vendor disabled sales after save option
* Fixed: Become a vendor page doesn't show for not logged in users

= 1.6.3 =

* Added: Become a vendor registration form
* Added: Support to YITH Live Chat Premium
* Added: Disable user gravatar in vendor's store page
* Tweak: Support to YITH Nielsen theme
* Tweak: Custom post type capabilities
* Updated: Language pot file
* Fixed: Option deps doesn't work
* Fixed: Can't translate string localized by esc_attr__ and esc_attr_e function
* Fixed: Print wrong commission rate value after insert new vendor by admin

= 1.6.2 =

* Added: Auto enable vendor account after registration
* Added: Seller vacation module
* Updated: Language pot file
* Fixed: Order email issue
* Removed: Old Product -> Vendors admin menu link

= 1.6.1 =

* Updated: Italian translation
* Updated: pot language file
* Fixed: checkout abort if no store owner set

= 1.6.0 =

* Added: Order Management
* Added: Support to YITH Live Chat
* Added: Support to WordPress 4.3
* Added: "Sold by vendor" in order details page
* Added: "Sold by vendor" in cart details page
* Added: "Sold by vendor" in checkout details page
* Added: "Sold by vendor" in My Account -> View order page
* Added: yith_wcmv_register_as_vendor_text hook for "Register as a vendor" text on frontend
* Added: yith_wcmv_store_header_class hook for vendor store header wrapper classes
* Added: yith_wcmv_header_img_class hook for vendor store header image classes
* Added: New vendor status "no-owner" in vendor taxonomy page in admin
* Added: New "Vendors" main menu item
* Added: yith_wcmv_show_vendor_name_template filter to prevent load vendor name template
* Added: YITH Essential Kit for WooCommerce #1 support
* Added: Dashboard notification for products needs to approve
* Added: New option "Send a copy to website owner" in Quick Info widget
* Updated: Italian translation
* Updated: pot language file
* Tweak: Commission rate column in commission table
* Tweak: Support to WooCommerce 2.4
* Tweak: WooCommerce option panel with the latest WC Version
* Tweak: Javascript code optimization
* Tweak: Commissions list order by descending commission ids
* Fixed: Prevent to edit other vendor reviews
* Fixed: Add new post button doesn't display
* Fixed: Unable to add Shop coupon with product amount option enabled
* Fixed: Vendor don't see shop coupon page with product amount option enabled
* Fixed: Coupon and Reviews option issue after the first installation
* Fixed: Reviews list not filter comments if vendor have no products
* Fixed: Recent comment dashboard widget in vendor administrator
* Fixed: Wrong search in Add/Edit product for Grouped product
* Fixed: Remove "Add new" post types menu from wp-admin bar
* Fixed: No default value "per_page" in yith_wcmv_list shortcodes
* Fixed: Add vendor image issue in italian language
* Fixed: Unable to translate "Edit extra info" button in admin
* Fixed: Chart GroupBy parameter doesn't exist in Vendor Reports
* Fixed: Warning on vendor reviews list in admin
* Fixed: Warning "cart item key not found" on checkout page
* Fixed: Vendors don't receive the email order
* Fixed: Auto sync commission and order status
* Fixed: Undefined index: hide_from_guests in Quick Info widget
* Fixed: Vendor description tab translation issue with qTranslateX plugin

= 1.5.2 =

* Fixed: Unable to login in vendor dashboard using particular themes

= 1.5.1 =

* Added: Support to WooCommerce 2.4
* Added: "Sold by vendor" in commission page
* Tweak: Plugin Core Framework
* Fixed: Vendor don't see product page with product amount enabled

= 1.5.0 =

* Added: New order actions: "New order" and "Cancelled order" for vendor
* Added: New order email options in WooCommerce > Settings > Emails > New order (for vendor)
* Added: Cancelled order email options in WooCommerce > Settings > Emails > New order (for vendor)
* Added: Minimum value for commission withdrawals
* Added: Featured products management option
* Added: Shortcodes for list of vendors
* Added: Item sold information in single product page
* Added: Total sales information in vendor page
* Added: yith_wcmv_header_icons_class hook to change header icons in vendor page
* Added: YITH WooCommerce Ajax Product Filter Support
* Added: Italian language file
* Added: WPML Support
* Updated: pot language file
* Fixed: Wrong order date in "Vendors Sales" report
* Fixed: Can't locate email templates
* Fixed: Prevent double instance in singleton class
* Fixed: Hide store header if vendor account is disabled
* Fixed: Variations don't show commission detail page
* Fixed: New order email notification

= 1.4.4 =

* Updated: pot language file
* Fixed: Fatal error in the commision page for deleted orders

= 1.4.3 =

* Fixed: Plugin does not recognize the languages file

= 1.4.2 =

* Fixed: Vendor can see all custom post types

= 1.4.1 =

* Added: Enable/Disable seller capabilities Bulk action
* Added: Report abuse option
* Updated: Plugin default language file
* Fixed: Quick contact info widget text area style
* Fixed: Vendors bulk action string localizzation
* Removed: Old taxonomy bulk action hook

= 1.4.0 =

* Added: Vendors can manage customer reviews on their products
* Added: Vendor can manage coupons for their products
* Added: Recent Comments dashboard widget
* Added: Recent Reviews dashboard widget
* Fixed: Store header image on Firefox and Safari
* Fixed: Wrong commission link in order page

= 1.3.0 =

* Added: Bulk Action in Vendors table
* Added: Register a new vendor from front end
* Added: yith_frontend_vendor_name_prefix hook to change the "by" prefix in loop and single product page
* Added: yith_single_product_vendor_tab_name hook to change the title of "Vendor" tab in single product page
* Added: Customize submit label in quick info widget
* Added: Option to limit the vendor product amount 
* Added: Option to hide the quick info widget from guests
* Added: yith_wpv_quick_info_button_class hook for custom css classes to quick info button
* Added: Option to hide the vendor name in Shop page
* Added: Option to hide the vendor name in Single product page
* Added: Option to hide the vendor name in Product category page
* Updated: Plugin default language file
* Fixed: Store header on mobile
* Fixed: Unable to rewrite frontend css on child theme
* Fixed: Changed "Product Vendors" label  to "Vendor" in product list table
* Fixed: Wrong default title in store location and quick info widgets
* Fixed: Widget Vendor list: option "Hide this widget on vendor page" doesn't work
* Fixed: Spelling error in Quick Info widget. Change the label "Object" to "Subject"
* Removed: Old sidebar template
* Removed: Old default.po file

= 1.2.0 =

* Initial release