<?php

/*
 * Template Name: Nutrition logic cron
 * Description: Everyday this will check whether we have to send new week's nutriotion plan for user and generate nutriotion plan for  * it.
 */
global $wpdb;
//$prefix = $wpdb->prefix;
$prefix = 'up_';

$query = 'Select np.id,np.order_id,np.email,np.plan_id,np.no_of_weeks,pl.week_no from ' . $prefix . 'user_nutrition_plans np '
        . 'Inner join ' . $prefix . 'plan_logs pl on pl.user_nutrition_plan_id = np.id and DATE_FORMAT(pl.created,"%Y-%m-%d") = SUBDATE(DATE_FORMAT("2015-12-21","%Y-%m-%d"), INTERVAL 1 week) WHERE pl.week_no < np.no_of_weeks';

//echo $query;
$url = site_url('/nutrition-plan-cron?order_id=');
//echo $url;
$res = $wpdb->get_results($query);
//echo "<pre>";print_r($res);
if (!empty($res)) {
        foreach ($res as $r) {
                $order_id = $r->order_id;
                
                file_get_contents($url.$order_id);
        }
}


