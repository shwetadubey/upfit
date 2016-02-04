<?php
/*
 * Template Name: check query
 */
 global $wpdb;
 $data = $wpdb->query("insert into up_user_nutrition_plans(order_id,site_id,email,plan_id,no_of_weeks,meals_per_day,final_meals,height,current_weight,desired_weight,age) values(1593,1,'stanislaw.schmidt84@gmail.com',95,4,5,'1,2,3,4,5,6,7,8',174,80,70,24)");
 echo "insert into up_user_nutrition_plans(order_id,site_id,email,plan_id,no_of_weeks,meals_per_day,final_meals,height,current_weight,desired_weight,age) values(1593,1,'stanislaw.schmidt84@gmail.com',95,4,5,'1,2,3,4,5,6,7,8',174,80,70,24)";
?>
