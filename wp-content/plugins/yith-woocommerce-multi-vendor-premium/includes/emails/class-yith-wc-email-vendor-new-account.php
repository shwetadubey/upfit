<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YITH_WC_Email_Vendor_New_Account' ) ) :

/**
 * Customer New Account
 *
 * An email sent to the customer when they create an account.
 *
 * @class 		WC_Email_Customer_New_Account
 * @version		2.3.0
 * @package		WooCommerce/Classes/Emails
 * @author 		WooThemes
 * @extends 	WC_Email
 */
class YITH_WC_Email_Vendor_New_Account extends WC_Email {

	public $user_login;
	public $user_email;
	public $user_pass;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {

		$this->id 				= 'vendor_new_account';
		$this->title 			= __( "Vendor's new account approvation", 'yith_wc_product_vendors' );
		$this->description		= __( 'Emails are sent to the vendor as soon as the admin approves his/her account', 'yith_wc_product_vendors' );

		$this->template_html 	= 'emails/vendor-new-account.php';
		$this->template_plain 	= 'emails/plain/vendor-new-account.php';

		$this->subject 			= __( 'Your vendor account on the website {site_title} has been approved.', 'yith_wc_product_vendors');
		$this->heading      	= __( 'Welcome to {site_title}', 'yith_wc_product_vendors');

		// Call parent constuctor
		parent::__construct();

        // Triggers for this email
		add_action( 'yith_vendors_account_approved_notification', array( $this, 'trigger' ), 10, 1 );
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $user_id ) {

		if ( $user_id ) {
			$this->object 		= new WP_User( $user_id );
			$this->user_email         = stripslashes( $this->object->user_email );
			$this->recipient          = $this->user_email;
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() )
			return;

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		yith_wcpv_get_template( $this->template_html, array(
			'email_heading'      => $this->get_heading(),
			'blogname'           => $this->get_blogname(),
			'sent_to_admin' => false,
			'plain_text'    => false
		), '' );
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		yith_wcpv_get_template( $this->template_plain, array(
			'email_heading'      => $this->get_heading(),
			'blogname'           => $this->get_blogname(),
			'sent_to_admin' => false,
			'plain_text'    => true
		), '' );
		return ob_get_clean();
	}
}

endif;

return new YITH_WC_Email_Vendor_New_Account();
