<?php

register_activation_hook(__FILE__, 'upq_activate');

register_deactivation_hook(__FILE__, 'upq_deactivate');

global $wpdb;
$prefix=$wpdb->prefix;
function upq_activate()
{
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS `".$prefix."questionnaire` (
  `qid` bigint(20) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `cur_weight` float NOT NULL,
  `desired_weight` float NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `age` int(11) NOT NULL,
  `height` float NOT NULL,
  `daily_activity` float NOT NULL,
  `nutrition_type` text NOT NULL,
  `allergies` text NOT NULL,
  `nuts` text NOT NULL,
  `fruit` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
$sql1 = "ALTER TABLE `".$prefix."questionnaire`
ADD PRIMARY KEY (`id`);
";
$wpdb->query($sql1);
$sql2 = "ALTER TABLE `".$prefix."questionnaire`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
$wpdb->query($sql2);
}

function upq_deactivate()
{
   // global $wpdb;
   // $sql = "DROP TABLE `".$prefix."questionnaire`;";
    //$wpdb->query($sql);
}

class WC_Settings_Tab_Demo {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
    }
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_demo'] = __( 'Settings Demo Tab', 'woocommerce-settings-tab-demo' );
        return $settings_tabs;
    }
}
WC_Settings_Tab_Demo::init();
