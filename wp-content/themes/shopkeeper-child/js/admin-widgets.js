jQuery(document).ready(function($){
 
    function wds_reset_footer_transient() {
 
        // Run our AJAX call to delete our site transient
        $.ajax({
            type : 'post',
            dataType : 'json',
            url : ajaxurl,
            data : {
                'action' : 'wds-reset-transient',
                'wds-widget-nonce' : wds_AJAX.wds_widget_nonce
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                console.log( thrownError );
                console.log( ajaxOptions );
            }
        });
    }
 
    // If one of our update buttons is clicked on a single widget
    $( '.widgets-holder-wrap' ).on( 'click', '.widget-control-remove, .widget-control-close, .widget-control-save', function() {
 
        // Get our parent, or sidebar, ID
        var widget_parent_div = $(this).parents().eq(5).attr( 'id' );
 
        // And if our parent div ID, or our sidebar ID, is one of the following
        if ( widget_parent_div == 'sidebar-footer-1' || widget_parent_div == 'sidebar-footer-2' || widget_parent_div == 'sidebar-footer-3' ) {
 
            // Run our function
            wds_reset_footer_transient();
        }
    });
});