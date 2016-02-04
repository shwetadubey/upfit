<?php
/**
 * Email Header
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
        <style type="text/css">
		body { background-color: #F4F4F4; margin: 0 auto; padding: 0 40px}
      
	</style>
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
        <div style="background-color: #F4F4F4; margin: 0 auto; width:600px;min-width:320px;max-width:100%;">
        <table cellspacing="0" cellpadding="0" align="center" style="width:600px;min-width:320px;max-width:100%;font-family:Helvetica, Arial;color:#96a7b0; background:#fff;">
        <tbody style="line-height:1px;" align="center">
            <tr>
				<td>
					<div style="height:40px;">
						<img src="<?php echo get_template_directory_uri(); ?>-child/images/email_top_side.jpg" alt="top side" width="100%" style="outline:0;border:0; width:100%; " />
						
					</div>
				</td>
			</tr>
            	<tr>
                <td>
					<table style="margin:0 auto;width:100%;background-color: #fff;">
						<tr>
							<td id="template_header_image" style="float:left;margin:0px 0px 0 40px">
								<?php
									
									if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
										echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
									}
								?>
							</td>
							
							<td align="right" style="float:right;margin:10px 40px 10px 0"><span style="font-size:14px;line-height: 20px; color:#96a7b0;letter-spacing: 0.5px;">Mail nicht lesbar? <a style="color:#96a7b0;text-decoration:underline;font-family:Helvetica, Arial;" href="<?php echo home_url();?>/email-html/?order_id=<?php echo $order_id; ?>">Hier klicken.</a></span></td>
						</tr>
					</table>
						
                    	<table border="0" cellpadding="0" cellspacing="0" width="600" min-width="320px" id="template_container"  style="border:0px none; box-shadow:none">
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Header -->
                                    
                                	<table border="0" cellpadding="0" border=0 cellspacing="0" width="100%" id="template_header">
                                        <tr>
                                            <td id="header_wrapper" class="left_right_border" style="display: block; padding: 20px 0px 0;">
                                            	<h2 style="color:#162c5d;border-top:1px solid #bdc9cc;border-bottom:1px solid #bdc9cc;padding:40px 0px;margin:0px 40px 0px 40px; font-size:24px;line-height: 30px; margin:0 auto;font-weight: normal;"><?php echo $email_heading; ?></h2>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Header -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" id="template_body">
                                    	<tr>
                                            <td valign="top" id="body_content" >
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top" class="left_right_padding" style="padding:40px 40px 12px 40px;">
                                                            <div id="body_content_inner">
