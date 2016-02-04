<?php
require_once('pages/global/settings.php');
require_once('functions/importer.php');


if( ! class_exists( 'Getbowtied_Admin_Pages' ) ) {
	
	class Getbowtied_Admin_Pages {		
		
		protected $settings;

		// =============================================================================
		// Construct
		// =============================================================================

		function __construct() {	

			// var_dump(file_exists(get_template_directory().'/backend/run_once.php'));
			// die();
			if (file_exists(get_template_directory().'/backend/run_once.php'))
			{
				require_once(get_template_directory().'/backend/run_once.php');
				unlink(get_template_directory().'/backend/run_once.php');
			}

			global $getbowtied_settings;

			$this->settings = $getbowtied_settings;

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			add_action( 'register_sidebar', 		array( $this, 'getbowtied_theme_admin_init' ) );

			add_action( 'admin_menu', 				array( $this, 'getbowtied_theme_admin_menu' ) );
			add_action( 'admin_menu', 				array( $this, 'getbowtied_theme_admin_submenu_registration' ) );
			add_action( 'admin_menu', 				array( $this, 'getbowtied_theme_admin_submenu_plugins' ) );
			add_action( 'admin_menu',				array( $this, 'getbowtied_theme_admin_submenu_demos' ) );
			//add_action( 'admin_menu', 				array( $this, 'getbowtied_theme_admin_submenu_help_center' ), 30 );			
			//add_action( 'admin_menu', 				array( $this, 'getbowtied_admin_menu' ), 99 );
			add_action( 'admin_menu', 				array( $this, 'getbowtied_edit_admin_menus' ) );
			
			add_action( 'admin_init', 				array( $this, 'getbowtied_theme_update') );
			
			add_action( 'after_switch_theme', 		array( $this, 'getbowtied_activation_redirect' ) );

			add_action( 'admin_notices', 			array( $this, 'getbowtied_admin_notices' ) );
			add_action( 'admin_notices', 			array( $this, 'update_notice' ) );
			
			add_action( 'admin_enqueue_scripts', 	array( $this, 'getbowtied_theme_admin_pages' ) );
			add_action( 'admin_enqueue_scripts', 	array( $this, 'getbowtied_theme_intercom' ) );

			if (!get_option("getbowtied_".THEME_SLUG."_license_expired"))
			{
				update_option("getbowtied_".THEME_SLUG."_license_expired", 0);
			}
		}

		function settings()
		{
			return $this->settings;
		}
		
		
		// =============================================================================
		// Menus
		// =============================================================================

		function getbowtied_theme_admin_menu() {			
			$getbowtied_menu_welcome = add_menu_page(
				getbowtied_theme_name(),
				getbowtied_theme_name(),
				'administrator',
				'getbowtied_theme',
				array( $this, 'getbowtied_theme_welcome_page' ),
				'',
				3
			);
		}

		function getbowtied_theme_admin_submenu_registration() {
			$getbowtied_submenu_welcome = add_submenu_page(
				'getbowtied_theme',
				__( 'Product Activation', 'getbowtied' ),
				__( 'Product Activation', 'getbowtied' ),
				'administrator',
				'getbowtied_theme_registration',
				array( $this, 'getbowtied_theme_registration_page' )
			);
		}

		function getbowtied_theme_admin_submenu_plugins() {	
			$getbowtied_submenu_plugins = add_submenu_page(
				'getbowtied_theme',
				__( 'Plugins', 'getbowtied' ),
				__( 'Plugins', 'getbowtied' ),
				'administrator',
				'getbowtied_theme_plugins',
				array( $this, 'getbowtied_theme_plugins_page' )
			);
		}

		function getbowtied_theme_admin_submenu_demos() {				
			$getbowtied_submenu_demos = add_submenu_page(
				'getbowtied_theme',
				__( 'Demo', 'getbowtied' ),
				__( 'Demo', 'getbowtied' ),
				'administrator',
				'getbowtied_theme_demos',
				array( $this, 'getbowtied_theme_demos_page' )
			);
		}

		function getbowtied_theme_admin_submenu_help_center() {					
			$getbowtied_submenu_help_center = add_submenu_page(
				'getbowtied_theme',
				__( 'Help Center', 'getbowtied' ),
				__( 'Help Center', 'getbowtied' ),
				'administrator',
				'getbowtied_theme_help_center',
				array( $this, 'getbowtied_theme_help_center_page' )
			);
		}

		function getbowtied_admin_menu() {						
			$getbowtied_welcome = add_submenu_page(
				'getbowtied_theme',
				__( 'Get Bowtied', 'getbowtied' ),
				__( 'Get Bowtied', 'getbowtied' ),
				'administrator',
				'getbowtied',
				array( $this, 'getbowtied_welcome_page' )
			);
		}


		// =============================================================================
		// Pages
		// =============================================================================

		function getbowtied_theme_welcome_page() {
			require_once( 'pages/welcome_theme.php' );
		}

		function getbowtied_theme_registration_page(){
			require_once( 'pages/registration.php' );
		}

		function getbowtied_theme_plugins_page(){
			require_once( 'pages/plugins.php' );
		}

		function getbowtied_theme_demos_page(){
			require_once( 'pages/demos.php' );
		}

		function getbowtied_theme_help_center_page(){
			require_once( 'pages/help-center.php' );
		}

		function getbowtied_welcome_page() {
			require_once( 'pages/welcome.php' );
		}


		// =============================================================================
		// Styles / Scripts
		// =============================================================================

		function getbowtied_theme_admin_pages() {
			wp_enqueue_style(	"getbowtied_theme_admin_css",				get_template_directory_uri() . "/backend/css/styles.css", 	false, null, "all" );
			wp_enqueue_script(	"getbowtied_theme_demos_js", 				get_template_directory_uri() . "/backend/js/scripts.js", 	array(), false, null );			
		}

		function getbowtied_theme_intercom() {

			if (get_option("getbowtied_".THEME_SLUG."_intercom_email"))
			{

				echo "
			
				<script>
				  window.intercomSettings = {
				    app_id: 'e6oj1xlj',
				    email: '".get_option("getbowtied_".THEME_SLUG."_intercom_email")."', // Email address
				    created_at: '".time()."' // Signup date as a Unix timestamp
				  };
				</script>
				<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==='function'){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/e6oj1xlj';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
				
				";

			}

		}


		// =============================================================================
		// Plug-ins
		// =============================================================================

		function getbowtied_theme_plugin_links( $item ) {

			delete_transient( '_wc_activation_redirect' );
            delete_transient( '_vc_page_welcome_redirect' );

			// see class-tgm-plugin-activation.php

			$installed_plugins = get_plugins();

			$item['sanitized_plugin'] = $item['name'];

            // We need to display the 'Install' hover link.
            if ( ! isset( $installed_plugins[$item['file_path']] ) ) {
                $actions = array(
                    'install' => sprintf(
                        '<a href="%1$s" class="button button-primary" title="' . esc_attr__( 'Install', 'tgmpa' ) . ' %2$s">' . __( 'Install', 'tgmpa' ) . '</a>',
                        esc_url(
						    wp_nonce_url(
                                add_query_arg(
                                    array(
                                        'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
                                        'plugin'        => urlencode( $item['slug'] ),
                                        'plugin_name'   => urlencode( $item['sanitized_plugin'] ),
                                        'plugin_source' => urlencode( $item['source'] ),
                                        'tgmpa-install' => 'install-plugin',
                                        'return_url'    => network_admin_url( 'admin.php?page=getbowtied_theme_plugins' )
                                    ),
                                    network_admin_url( 'themes.php' )
                                ),
                               'tgmpa-install'
                            )
                        ),
                        $item['sanitized_plugin']
                    ),
                );
            }
			
			// We need to display the 'Activate' hover link.
            elseif ( is_plugin_inactive( $item['file_path'] ) ) {
                $actions = array(
                    'activate' => sprintf(
                        '<a href="%1$s" class="button button-primary" title="' . esc_attr__( 'Activate', 'tgmpa' ) . ' %2$s">' . __( 'Activate', 'tgmpa' ) . '</a>',
                        esc_url(
                            add_query_arg(
                                array(
                                    'plugin'               		=> urlencode( $item['slug'] ),
                                    'plugin_name'          		=> urlencode( $item['sanitized_plugin'] ),
                                    'plugin_source'        		=> urlencode( $item['source'] ),
                                    'getbowtied-activate'  		=> 'activate-plugin',
                                    'getbowtied-activate-nonce' => urlencode( wp_create_nonce( 'getbowtied-activate' ) ),
                                ),
                                network_admin_url( 'admin.php?page=getbowtied_theme_plugins' )
                            )
                        ),
                        $item['sanitized_plugin']
                    ),
                );
            }
			
			// We need to display the 'Update' hover link.
            elseif ( version_compare( $installed_plugins[$item['file_path']]['Version'], $item['version'], '<' ) ) {
                $actions = array(
                    'update' => sprintf(
                        '<a href="%1$s" class="button button-primary" title="' . esc_attr__( 'Update', 'tgmpa' ) . ' %2$s">' . __( 'Update', 'tgmpa' ) . '</a>',
                        esc_url(
						    wp_nonce_url(
                                add_query_arg(
                                    array(
                                        'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
                                        'plugin'        => urlencode( $item['slug'] ),
                                        'plugin_name'   => urlencode( $item['sanitized_plugin'] ),
                                        'plugin_source' => urlencode( $item['source'] ),
                                        'tgmpa-update' 	=> 'update-plugin',
                                        'version' 		=> urlencode( $item['version'] ),
                                        'return_url'    => network_admin_url( 'admin.php?page=getbowtied_theme_plugins' )
                                    ),
                                    network_admin_url( 'themes.php' )
                                ),
                               'tgmpa-install'
                            )
                        ),
                        $item['sanitized_plugin']
                    ),
                );
			}
			
			// We need to display the 'Deactivate' hover link.
            elseif ( is_plugin_active( $item['file_path'] ) ) {
                $actions = array(
                    'deactivate' => sprintf(
                        '<a href="%1$s" class="button button-primary" title="' . esc_attr__( 'Deactivate', 'tgmpa' ) . ' %2$s">' . __( 'Deactivate', 'tgmpa' ) . '</a>',
                        esc_url(
                            add_query_arg(
                                array(
                                    'plugin'               			=> urlencode( $item['slug'] ),
                                    'plugin_name'          			=> urlencode( $item['sanitized_plugin'] ),
                                    'plugin_source'        			=> urlencode( $item['source'] ),
                                    'getbowtied-deactivate'  		=> 'deactivate-plugin',
                                    'getbowtied-deactivate-nonce'	=> urlencode( wp_create_nonce( 'getbowtied-deactivate' ) ),
                                ),
                                network_admin_url( 'admin.php?page=getbowtied_theme_plugins' )
                            )
                        ),
                        $item['sanitized_plugin']
                    ),
                );
            }

			return $actions;
		}

		// =============================================================================
		// Theme Updater
		// =============================================================================

		function getbowtied_theme_update() 
		{
			global $wp_filesystem;

			if ( get_option("getbowtied_".THEME_SLUG."_license")  && get_option("getbowtied_".THEME_SLUG."_license_expired") != 1)
			{
				$license_key = get_option("getbowtied_".THEME_SLUG."_license");

				require_once( get_template_directory() . '/backend/functions/_class-updater.php' );
				
				$theme_update = new GetBowtiedUpdater( $license_key );

				// $curr_theme = wp_get_theme();
				// echo '<pre>';
				// print_r($curr_theme);
				// echo $curr_theme->name;
				// print_r($curr_theme->get_template());
				// echo $curr_theme->version;
				// echo '</pre>';
				// die();
				
			}

			if (get_option("getbowtied_".THEME_SLUG."_license_expired") == 1 )
			{
				add_action( 'admin_notices', array(&$this, 'expired_notice') );
			}
		}

		function expired_notice() {
			
			echo '<div class="error getbowtied_admin_notices">
			<p>This site will no longer receive automatic theme updates. Theme\'s <a href="' . admin_url( 'admin.php?page=getbowtied_theme_registration' ) . '">Product Key</a> is no longer active on this domain.</p>
			</div>';
		}

		function getbowtied_admin_notices() {

			if ( ! get_option("getbowtied_".THEME_SLUG."_license") && ( get_option("getbowtied_".THEME_SLUG."_license_expired") == 0 )) {
				
				if( function_exists('wp_get_theme') ) {
					$theme_name = '<strong>'. wp_get_theme() .'</strong>';
				}

				echo '<div class="error getbowtied_admin_notices">
				<p>' . $theme_name . ' - Enter your product key to start receiving automatic updates and support. Go to <a href="' . admin_url( 'admin.php?page=getbowtied_theme_registration' ) . '">Product Activation</a>.</p>
				</div>';

			}

		}

		// function validate_details($api_user, $api_key)
		// {
		// 	$api_user = sanitize_text_field($api_user);
		// 	$api_key  = sanitize_text_field($api_key);
		// 	$api_url  = "http://marketplace.envato.com/api/edge/";

		// 	$url = $api_url.$api_user.'/'.$api_key.'/wp-list-themes.json';
		// 	$request = wp_remote_get( $url );

		// 	if ( is_wp_error( $request ) ) 
		// 	{
		//     	return 'API Error';
		//     }
		//     else
		//     {
		//     	$data = json_decode( $request['body'] );

		//     	if (!empty($data->error))
		//     	{
		//     		return false;
		//     	}
		//     }

		// 	update_option('api_user', $api_user );
		// 	update_option('api_key',  $api_key );
		//     return true;

		// }

		function validate_license($license_key)
		{
			if (empty($license_key))
			{
				return FALSE;
			}
			else
			{
				// $api_url = "http://local.dev/dashboard/api/api_listener.php";
				$api_url = "http://my.getbowtied.com/api/api_listener.php";
				$theme = wp_get_theme();

				$args = array(
								'method' => 'POST',
								'timeout' => 30,
								'body' => array( 'l' => $license_key,  'd' => get_site_url(), 't' => THEME_NAME )
						);
				
				$response = wp_remote_post( $api_url, $args );

				if ( is_wp_error( $response ) ) {
				    $error_message = $response->get_error_message();
				    $request_msg = 'Something went wrong:'.$error_message.'. Please try again!';
				} else {
				  	$rsp = json_decode($response['body']);
				  	$request_msg = '';
				  	// print_r($rsp);
				  	// die();
				  	
				  	switch ($rsp->status)
				  	{
				  		case '0':
				  		$request_msg = 'Something went wrong. Please try again!';
				  		break;

				  		case '1':
				  		update_option("getbowtied_".THEME_SLUG."_license", $license_key);
				  		update_option("getbowtied_".THEME_SLUG."_license_expired", 0);
				  		if (!empty($rsp->email))
				  		{
				  			update_option("getbowtied_".THEME_SLUG."_intercom_email", $rsp->email);
				  		}
				  		break;

				  		case '2':
				  		$request_msg = 'The product key you entered is not valid.';
				  		break;

				  		case '3':
				  		$request_msg = 'The product key you entered is not valid on this domain.';
				  		break;

				  	}

				  	// echo '<h2>'.$request_msg.'</h2>';
				}

			 	return $request_msg;

			}
		}
		
		function update_notice()
		{
			$remote_ver = get_option("getbowtied_".THEME_SLUG."_remote_ver") ? get_option("getbowtied_".THEME_SLUG."_remote_ver") : getbowtied_theme_version();
			$local_ver = getbowtied_theme_version();

		    if(version_compare($local_ver, $remote_ver, '<'))
		    {
				if( function_exists('wp_get_theme') ) {
					$theme_name = '<strong>'. wp_get_theme(get_template()) .'</strong>';
				}
				echo '<div class="error getbowtied_admin_notices">
				<p>There is an update available for the ' . $theme_name . ' theme. <a href="' . admin_url() . 'update-core.php">Update now</a>.</p>
				</div>';
		    }
		}

		// =============================================================================
		// Admin Redirect
		// =============================================================================

		function getbowtied_activation_redirect(){
			if ( current_user_can( 'edit_theme_options' ) ) {
				header('Location:'.admin_url().'admin.php?page=getbowtied_theme');
			}
		}

		// =============================================================================
		// Admin Init
		// =============================================================================

		function getbowtied_theme_admin_init() {

			if ( isset( $_GET['getbowtied-deactivate'] ) && $_GET['getbowtied-deactivate'] == 'deactivate-plugin' ) {
				
				check_admin_referer( 'getbowtied-deactivate', 'getbowtied-deactivate-nonce' );

				$plugins = get_plugins();

				foreach ( $plugins as $plugin_name => $plugin ) {
					if ( $plugin['Name'] == $_GET['plugin_name'] ) {
							deactivate_plugins( $plugin_name );
					}
				}

			} 

			if ( isset( $_GET['getbowtied-activate'] ) && $_GET['getbowtied-activate'] == 'activate-plugin' ) {
				
				check_admin_referer( 'getbowtied-activate', 'getbowtied-activate-nonce' );

				$plugins = get_plugins();

				foreach ( $plugins as $plugin_name => $plugin ) {
					if ( $plugin['Name'] == $_GET['plugin_name'] ) {
						activate_plugin( $plugin_name );
					}
				}

			}

		}

		
		// =============================================================================
		// Edit Menus
		// =============================================================================

		function getbowtied_edit_admin_menus() {
			global $submenu;
			$submenu['getbowtied_theme'][0][0] = __( 'Welcome', 'getbowtied' );
		}

		
		// =============================================================================
		// Let to num
		// =============================================================================

		function let_to_num( $size ) {
			$l   = substr( $size, -1 );
			$ret = substr( $size, 0, -1 );
			switch ( strtoupper( $l ) ) {
				case 'P':
					$ret *= 1024;
				case 'T':
					$ret *= 1024;
				case 'G':
					$ret *= 1024;
				case 'M':
					$ret *= 1024;
				case 'K':
					$ret *= 1024;
			}
			return $ret;
		}
	}
	
	new Getbowtied_Admin_Pages;

}