<?php
/**
 * Enqueue and localize our scripts
 */
add_action( 'admin_enqueue_scripts', 'wds_enqueue_ajax_scripts' );
function wds_enqueue_ajax_scripts() {
    // Only register these scripts if we're on the widgets page
    if ( $current_screen->id == 'widgets' ) {
        wp_enqueue_script( 'wds_ajax_scripts', get_stylesheet_directory_uri() . '/js/admin-widgets.js', array( 'jquery' ), 1, true );
        wp_localize_script( 'wds_ajax_scripts', 'wds_AJAX', array( 'wds_widget_nonce' => wp_create_nonce( 'widget-update-nonce' ) ) );
    }
}
/**
 * Register our AJAX call
 */
add_action( 'wp_ajax_wds-reset-transient', 'wds_ajax_wds_reset_transient', 1 );
 
/**
 * AJAX Helper to delete our transient when a widget is saved
 */
function wds_ajax_wds_reset_transient() {
 
    // Delete our footer transient.  This runs when a widget is saved or updated.  Only do this if our nonce is passed.
    if ( ! empty( $_REQUEST['wds-widget-nonce'] ) )
        delete_site_transient( 'wds_footer_widgets' );
}