<?php
/**
 * Email Styles
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg              = get_option( 'woocommerce_email_background_color' );
$body            = get_option( 'woocommerce_email_body_background_color' );
$base            = get_option( 'woocommerce_email_base_color' );
$base_text       = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text            = get_option( 'woocommerce_email_text_color' );

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
?>
.left_right_border { margin-right: 40px; margin-left: 40px; }
.logo_pdf_top { margin:0 0 0 40px;}
h2.border_bottom_sub { border-top:1px solid #bdc9cc; padding:40px 0px;}
.marging_remove_leftright { margin:20px 0px 40px;}
.no-padding-left li label.pull-left.green-text.text-light { color:#8d9da5;}
.remove_marginleft { margin-left:0;}
table.email_box_border { border:1px solid #BDC9CC;}
.padding_left_odlist { padding-left:30px;} 
.padding_bottom_add { padding-bottom:40px;}
.email_box_border th, .email_box_border td { border-color:#BDC9CC; }
.border_bottom_add { border-bottom:1px solid #BDC9CC;}
.email_box_border th.padding_left_odlist.border-bottom { border-style:solid; border-width:0 1px 1px 0;}
.marging_bottom_add { margin-bottom:40px;}

#wrapper {
    background-color: <?php echo esc_attr( $bg ); ?>;
    margin: 0 ;
    padding: 70px 0 70px 0;
    -webkit-text-size-adjust: none !important;
    width: 100%;
}
.pdf_link_dow { color:#2cab50; cursor:pointer;}
#template_container {
    box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important;
    background-color: <?php echo esc_attr( $body ); ?>;
    border: 1px solid <?php echo esc_attr( $bg_darker_10 ); ?>;
    border-radius: 3px !important;
}

#template_header {
    background-color:#ffffff; 
    border-radius: 3px 3px 0 0 !important;
    color:##162c5d;
    border-bottom: 0;
    font-weight: bold;
    line-height: 100%;
    vertical-align: middle;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1 {
    color:#162c5d;
}

#template_footer td {
    padding: 0;
    -webkit-border-radius: 6px;
}

#template_footer #credit {
    border:0;
    color: <?php echo esc_attr( $base_lighter_40 ); ?>;
    font-family: Arial;
    font-size:12px;
    line-height:125%;
    text-align:center;
    padding: 0 48px 48px 48px;
}

#body_content {
    background-color: <?php echo esc_attr( $body ); ?>;
}

ul.no-padding-left { padding:0;}
ul.no-padding-left li { list-style:none;}


#body_content p {
    margin: 0 0;
}
#body_content p strong { color:#96a7b0; font-size: 16px; line-height: 20px;font-weight: normal;}
#body_content_inner {
    color: <?php echo esc_attr( $text_lighter_20 ); ?>;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 14px;
    line-height: 150%;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.td {
    color: <?php echo esc_attr( $text_lighter_20 ); ?>;
    border: 1px solid <?php echo esc_attr( $body_darker_10 ); ?>;
}

.text {
    color: #96a7b0;
    font-family: Helvetica, Roboto, Arial, sans-serif;
    font-size: 16px;
    line-height: 28px;
    border-top: 8px solid #fff;
    margin: 0 0;
}

.link {
    color: <?php echo esc_attr( $base ); ?>;
}

#header_wrapper {
    padding: 36px 48px;
    display: block;
}

h1 {
    color: <?php echo esc_attr( $base ); ?>;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 30px;
    font-weight: 300;
    line-height: 150%;
    margin: 0;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
    -webkit-font-smoothing: antialiased;
}

h2 {
   color: #162c5d;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 24px;
    font-weight: normal;
    line-height: 32px;
    margin: 40px auto 0px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
    color: #162c5d;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 24px;
    font-weight: normal;
    line-height: 28px;
    margin: 0 auto;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    
}
.billing_address_heading { border-top:30px solid #fff;}
a {
    color: <?php echo esc_attr( $base ); ?>;
    font-weight: normal;
    text-decoration: underline;
}

img {
    border: none;
    display: inline-block;
    font-size: 0px;
    font-weight: bold;
    height: auto;
    line-height: auto;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
}
.im{
	color:#96a7b0 !important;
	}
	.email_address{
		    color: #96a7b0;
		     text-decoration: none;

    }
<?php
