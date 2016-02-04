<?php

	function load_settings($theme_slug)
	{
		$settings = array(); 

		/*
		** 	STANDARD SETTINGS
		*/

		$settings['woocommerce_docs'] 			= 'http://support.getbowtied.com/hc/en-us/categories/200549461';
		$settings['wordpress_docs'] 			= 'http://support.getbowtied.com/hc/en-us/categories/200561452-WordPress-for-Beginners';
		$settings['getbowtied_url']	 			= 'http://my.getbowtied.com/';
		$settings['getbowtied_update_url'] 		= 'http://my.getbowtied.com/api/update_theme.php';
		$settings['getbowtied_validate_url']	= 'http://my.getbowtied.com/api/api_listener.php';

		switch ($theme_slug)
		{
			case 'shopkeeper':

				$settings['theme_docs'] 		= 'http://support.getbowtied.com/hc/en-us/categories/200308912-Shopkeeper';
				$settings['release_notes'] 		= 'http://support.getbowtied.com/hc/en-us/sections/200757281-Updates';
				$settings['purchase'] 			= 'http://themeforest.net/item/shopkeeper-responsive-wordpress-theme/9553045?license=regular&open_purchase_for_item_id=9553045&purchasable=source';
				$settings['dummy_data_preview'] = 'http://import.getbowtied.com/shopkeeper-v1.1/';
				$settings['demo_xml_file_url']  = 'http://my.getbowtied.com/api/shopkeeper/demos/demo.gz';
				$settings['options_file_url']	= 'http://my.getbowtied.com/api/shopkeeper/theme_options/theme_options.json';
				$settings['theme_logo']			= get_template_directory_uri().'/backend/img/shopkeeper.png';
				$settings['demo_image']			= get_template_directory_uri().'/backend/img/demos/shopkeeper/default.png';

			break;

			case 'mr_tailor':

				$settings['theme_docs'] 		= 'http://support.getbowtied.com/hc/en-us/categories/200103142-Mr-Tailor';
				$settings['release_notes'] 		= 'http://support.getbowtied.com/hc/en-us/sections/200658372-Updates';
				$settings['purchase'] 			= 'http://themeforest.net/item/mr-tailor-responsive-woocommerce-theme/7292110?license=regular&open_purchase_for_item_id=7292110&purchasable=source';
				$settings['dummy_data_preview'] = 'http://mr-tailor.getbowtied.com/blank-v2/';
				$settings['demo_xml_file_url']  = 'http://my.getbowtied.com/api/mr_tailor/demos/demo.gz';
				$settings['options_file_url']	= 'http://my.getbowtied.com/api/mr_tailor/theme_options/theme_options.json';
				$settings['theme_logo']			= get_template_directory_uri().'/backend/img/mr_tailor.png';
				$settings['demo_image']			= get_template_directory_uri().'/backend/img/demos/mr_tailor/default.png';

			break;

			case 'the_retailer':

				$settings['theme_docs'] 		= 'http://support.getbowtied.com/hc/en-us/categories/200102021-The-Retailer';
				$settings['release_notes'] 		= 'http://support.getbowtied.com/hc/en-us/sections/200649072-Updates';
				$settings['purchase'] 			= 'http://themeforest.net/item/the-retailer-responsive-wordpress-theme/4287447?license=regular&open_purchase_for_item_id=4287447&purchasable=source&ref=getbowtied';
				$settings['dummy_data_preview'] = 'http://import.getbowtied.com/the-retailer-v1.1/';
				$settings['demo_xml_file_url']  = 'http://my.getbowtied.com/api/the_retailer/demos/demo.gz';
				$settings['options_file_url']	= 'http://my.getbowtied.com/api/the_retailer/theme_options/theme_options.txt';
				$settings['theme_logo']			= get_template_directory_uri().'/backend/img/the_retailer.png';
				$settings['demo_image']			= get_template_directory_uri().'/backend/img/demos/the_retailer/default.png';

			break;

			default:
			break;
		}

		return $settings;
	}

	$getbowtied_settings = load_settings(THEME_SLUG);
?>