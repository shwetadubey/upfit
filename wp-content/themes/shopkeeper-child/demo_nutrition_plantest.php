<?php

/*
 * Template Name: Demo Nutrition creation logic changes
 */
//ini_set("display_errors", "1");
//error_reporting(E_ALL);
global $wpdb;

$prefix = 'up_';

$nutrition_types_array = array(
    'nospecial' => '',
    'pescetarian' => 'cw_pescetarian',
    'vegetarian' => 'cw_vegetarian',
    'flexitarian' => '',
    'vegan' => 'cw_vegan',
    'paleo' => 'cw_paleo'
);
$intolerance_array = array (
    'lactose' => 'cw_lactose_intolerance',
    'fructose' => 'cw_fructose_intolerance',
    'histamine' => 'cw_histamine_intolerance',
    'gluten' => 'cw_gluten_intolerance',
    'glutamat' => 'cw_glutamat_intolerance',
    'sucrose' => 'cw_sucrose_intolerance',
    'peanut' => 'cw_peanut_intolerance',
    'almond' => 'cw_almond_intolerance',
    'hazelnut' => 'cw_hazelnut_intolerance',
    'walnut' => 'cw_walnut_intolerance',
    'cashew' => 'cw_cashew_intolerance',
    'pecan_nut' => 'cw_pecan_nut_intolerance',
    'brazil_nut' => 'cw_brazil_nut_intolerance',
    'pistachio' => 'cw_pistachio_intolerance',
    'banana' => 'cw_banana_intolerance',
    'avocade' => 'cw_avocade_intolerance',
    'apple' => 'cw_apple_intolerance',
    'kiwi' => 'cw_kiwi_fruit_intolerance',
    'melon' => 'cw_melon_intolerance',
    'papaya' => 'cw_papaya_intolerance',
    'peach' => 'cw_peach_intolerance',
    'strawberry' => 'cw_strawberry_intolerance'
);

/* logic start from here */
$weeknum = 1;
$order_id =  $_GET['order_id'];
$cur_weight = $_GET['cur_weight'] ? $_GET['cur_weight'] : 82.2;
$desired_weight = $_GET['desired_weight'] ? $_GET['desired_weight'] : 60;
$gender = $_GET['gender'] ? $_GET['gender'] : 'm';
$age = $_GET['age'] ? $_GET['age'] : 32;
$height = $_GET['height'] ? $_GET['height'] : 174;
$daily_activity = $_GET['daily_activity'] ? $_GET['daily_activity'] : 24;
$nutrition_type = $_GET['nutrition_type'] ? $_GET['nutrition_type'] : 'pescetarian';
$allergies = $_GET['allergies'] ? $_GET['allergies'] : '';
$exclude = $_GET['exclude'] ? $_GET['exclude'] : '';
$sweet_tooth = $_GET['sweet_tooth'] ? $_GET['sweet_tooth'] : 'yes';
$is_time_to_cook = $_GET['perpare_time'] ? $_GET['perpare_time'] : 'normal';
$where_food_buy = $_GET['food'] ? $_GET['food'] : 'cheap';
$most_buy = $_GET['buy'] ? $_GET['buy'] : 'both';
$plan = $_GET['plan'] ? $_GET['plan'] : 164;
$user_email='lanetteam.shweta@gmail.com';

$nutrion_plan_detail = array (
    'cur_weight' => $cur_weight,
    'desired_weight' => $desired_weight,
    'gender' => $gender,
    'age' => $age,
    'height' => $height,
    'daily_activity' => $daily_activity,
    'nutrition_type' => $nutrition_type,
    'allergies' => $allergies,
    'nuts' => '',
    'fruit' => '',
    'exclude' => $exclude,
    'sweet_tooth' => $sweet_tooth,
    'is_time_to_cook' => $is_time_to_cook,
    'where_food_buy' => $where_food_buy,
    'most_buy' => $most_buy,
    'plan' => $plan
);

$current_weight = $nutrion_plan_detail['cur_weight'];
$desired_weight = $nutrion_plan_detail['desired_weight'];
$gender = $nutrion_plan_detail['gender'];
$age = $nutrion_plan_detail['age'];
$height = $nutrion_plan_detail['height'];
$activity_level = $nutrion_plan_detail['daily_activity'];
$nutrition_type = explode(',', $nutrion_plan_detail['nutrition_type']);

$alergies = $nuts = $fruit = array();
if (!empty($nutrion_plan_detail['allergies'])) {
    $alergies = explode(',', $nutrion_plan_detail['allergies']);
}
if (!empty($nutrion_plan_detail['nuts'])) {
    $nuts = explode(',', $nutrion_plan_detail['nuts']);
}
$alergies_nuts = array_merge($alergies, $nuts);
if (!empty($nutrion_plan_detail['fruit'])) {
    $fruit = explode(',', $nutrion_plan_detail['fruit']);
}
$intolerance = array_merge($alergies_nuts, $fruit);

$exclude = explode(',', $nutrion_plan_detail['exclude']); // synonyms
$sweet_tooth = $nutrion_plan_detail['sweet_tooth']; //'yes'; //yes,sometimes,no
$available_time = $nutrion_plan_detail['is_time_to_cook']; // Little = < 40 min , Normal = 40-60min, Much= > 60min
$where_food_buy = strtolower($nutrion_plan_detail['where_food_buy']); // Cheap , Normal, Premium
$what_matters = $nutrion_plan_detail['most_buy']; // Price , Quality, Both
$plan = $nutrion_plan_detail['plan']; //12 weeks upfit superstar

$price_query = "select id from " . $prefix . "price_levels where name='" . $where_food_buy . "'";
$price_leveld = $wpdb->get_results($price_query);
$price_level = 0;
if (!empty($price_leveld)) {
    $price_level = $price_leveld[0]->id;
}

$res_nut_typeval = $res_nut_typeval1 = $nut_type_cond = $nut_type_cond1 = $f1nut_type_cond = $f1nut_type_cond1 = '';
if (!empty($nutrition_type)) {
    foreach ($nutrition_type as $n) {
        if ($n != 'nospecial' && $n != 'flexitarian') {
            $val = $nutrition_types_array[$n];
            $nut_typeval[] = 'f.' . $val . ' <> 0 ';
            $nut_typeval1[] = 'f1.' . $val . ' <> 0 ';
        }
    }

    if (!empty($nut_typeval)) {
        $res_nut_typeval = implode('AND ', $nut_typeval);
        $res_nut_typeval1 = implode('AND ', $nut_typeval1);

        $res_nut_typeval = " AND (" . $res_nut_typeval . ")";
        $res_nut_typeval1 = " AND (" . $res_nut_typeval1 . ")";
    }
}

$res_int_type = $res_int_type1 = $res_int_typeval = $res_int_typeval1 = '';
if (!empty($intolerance)) {
    foreach ($intolerance as $i) {
        $intal = $intolerance_array[$i];
        $int_typeval[] = 'f.' . $intal . ' <> 0 ';
        $int_typeval1[] = 'f1.' . $intal . ' <> 0 ';
    }

    if (!empty($int_typeval)) {
        $res_int_typeval = implode('AND ', $int_typeval);
        $res_int_typeval1 = implode('AND ', $int_typeval1);

        $res_int_typeval = " AND (" . $res_int_typeval . ")";
        $res_int_typeval1 = " AND (" . $res_int_typeval1 . ")";
    }
}

$synonyms_exclude = $exclude;

$available_time_array = array('little' => ' AND mins.preparation_time <= 45 ', 'normal' => '', 'much' => '');

$available_time_condi = $available_time_array[$nutrion_plan_detail['is_time_to_cook']];

echo '-------------</br>';
echo 'current Weight: ' . $current_weight . '</br>';
echo 'Desired Weight: ' . $desired_weight . '</br>';
echo 'Gender: ' . $gender . '</br>';
echo 'Age: ' . $age . '</br>';
echo 'Height: ' . $height . '</br>';
echo 'Activity Level: ' . $activity_level . '</br>';
echo 'Nutrition types: ' . implode(',', $nutrition_type) . '</br>';
echo 'Intolerance: ' . implode(',', $intolerance) . '</br>';
echo 'Available time: ' . $available_time . '</br>';
echo 'Sweet tooth: ' . $sweet_tooth . '</br>';
echo 'Price level of food: ' . $where_food_buy . '</br>';
echo 'What matters: ' . $what_matters . '</br>';
echo 'Exclude foods : ' . $nutrion_plan_detail['exclude'] . '</br>';

$time_con=array();
if(strcasecmp($available_time,'little')==0) {
	$time_con=array('breakfast'=>20,'lunch'=>45,'dinner'=>30,'snack'=>20);
} else if(strcasecmp($available_time,'much')==0) {
	$time_con=array('breakfast'=>5,'lunch'=>15,'dinner'=>10,'snack'=>5);
}

$time_ideal_value=array();
if(strcasecmp($available_time,'little')==0) {
	$time_ideal_value=array('breakfast'=>3,'lunch'=>9,'dinner'=>6,'snack'=>3);
}

if(strcasecmp($available_time,'normal')==0) {
	$time_ideal_value=array('breakfast'=>12.5,'lunch'=>30,'dinner'=>20,'snack'=>12.5);
} else if(strcasecmp($available_time,'much')==0) {
	$time_ideal_value=array('breakfast'=>30,'lunch'=>60,'dinner'=>37.5,'snack'=>30);
}
$query1 = "SELECT meal_id, COUNT( * ) AS total_ingredient
FROM " . $prefix . "meal_ingredients
GROUP BY meal_id";

$res1 = $wpdb->get_results($query1, OBJECT_K);

$synonyms_que = $synonyms_que1 = '';
if (!empty($nutrion_plan_detail['exclude'])) {
    $synonyms_que = ' AND NOT FIND_IN_SET( f.synonym_of,  "' . $nutrion_plan_detail['exclude'] . '" ) ';
    $synonyms_que1 = ' AND (NOT FIND_IN_SET(f1.synonym_of, "' . $nutrion_plan_detail['exclude'] . '") OR NOT FIND_IN_SET(f.synonym_of, "' . $nutrion_plan_detail['exclude'] . '"))';
}

/* check main ingeredient compatible */
$query3 = "select um.meal_id as meal_id,group_concat(replace(um.name,',','')) as ing_names,group_concat(f.id) as compatible_ingredients,count(*) as main_count from " . $prefix . "meal_ingredients um, " . $prefix . "foods f "
        . ", " . $prefix . "meal_instructions mins where um.name=f.name  " . $res_nut_typeval . $res_int_typeval . $synonyms_que . " AND mins.meal_id = um.meal_id " . $available_time_condi . "
group by um.meal_id";

//echo $query3 . "<br><br>";
$res3 = $wpdb->get_results($query3, OBJECT_K);
//echo "<pre>";print_r($res3);
/* exchangable ingeredient check */

$q2= "SELECT mi.meal_id ,replace(mi.NAME,',','') AS ingredient_name,replace(f1.exchangeable_with, ' ','') as exchangeable_with,mi.is_ommitable, group_concat(f.name) AS compatible_food_name, group_concat(f.nid) AS compatible_food_id ".
" FROM up_meal_ingredients mi INNER JOIN up_foods f1 ON mi.name = f1.name LEFT JOIN up_foods f ON FIND_IN_SET(f.nid, replace(f1.exchangeable_with, ' ','')) ".
" LEFT JOIN up_meal_instructions mins on mi.id = mins.meal_id WHERE (mi.is_ommitable = 1 or ((f1.exchangeable_with is not null and f1.exchangeable_with <> '' and f1.exchangeable_with <> '0') "
.$available_time_condi.$synonyms_que1.$res_nut_typeval.$res_int_typeval.")) GROUP BY mi.meal_id,mi.NAME";

//echo $q2."<br><br>";
$res2 = $wpdb->get_results($q2);
//echo "<pre>";print_r($res2);

$count=$wpdb->get_var("select count(id) from ".$prefix."order_meals where order_id=".$order_id);

if($count!=0){
	$query_del="DELETE from ".$prefix."order_meals where order_id=".$order_id;
	$wpdb->query($query_del);
}
$res_meals = $exchangable_foods = $prioritise_meals =$up_order_meals = array();
if (!empty($res3)) {
    foreach ($res1 as $k1 => $r1) {
        $r3 = $res3[$r1->meal_id];
        if (!is_null($r3) && !empty($r3)) {
            if ($r1->total_ingredient > $r3->main_count) {
                $temp = 0;
                $temp_meal_id="";
                foreach ($res2 as $r) {
                    if ($r->meal_id == $r1->meal_id) {
            			if(!in_array($r->ingredient_name, explode(',',$r3->ing_names))){
            				$exchangables = explode(',',$r->exchangeable_with);
            				if(!empty($exchangables) && $r->exchangeable_with != '0' && $exchangables[0] != '') {
            					foreach($exchangables as $ei) {
            						if(!empty($r->compatible_food_id)) {
            						    $compatible = explode(',',$r->compatible_food_id);
            							if(in_array($ei, $compatible)){
            							    $ei = $wpdb->get_var("select id from up_foods where nid='".$ei."' LIMIT 1;");
                                            if (!is_null($ei) && !empty($ei)) {
                							    $temp++;
                								$temp_meal_id .= ",".$ei;
                								break;
                                            }
            							}
            						}
            					}
            				} else if($r->is_ommitable) {
							    $temp++;
            				}
            			}
                    }
                }

                $final_count = $r3->main_count + $temp;

				//echo "final count:".$final_count."temp:".$temp."</br>";
                if ($final_count == $r1->total_ingredient) {
                    $res_meals[] = $r1->meal_id;
                    $up_order_meals['order_id']=$order_id;
            		$up_order_meals['meal_id']=$r3->meal_id;
            		$up_order_meals['ingredient_ids']=$r3->compatible_ingredients.$temp_meal_id;
            		$count=$wpdb->get_var("select count(id) from up_order_meals where order_id=".$order_id." AND meal_id=".$up_order_meals['meal_id']);
            		if($count==0){
            			$query_insert="insert into up_order_meals (order_id,meal_id,ingredient_ids,exchangble) values(".$order_id.",".$up_order_meals['meal_id'].",'".$up_order_meals['ingredient_ids']."',1)";
            			$wpdb->query($query_insert);
            		}
                }
            } else if ($r1->total_ingredient <= $r3->main_count) {
                $final_count = $r3->main_count;
                $prioritise_meals[] = $r1->meal_id;
                $up_order_meals['order_id']=$order_id;
                $up_order_meals['meal_id']=$r3->meal_id;
                $up_order_meals['ingredient_ids']=$r3->compatible_ingredients;

                $count=$wpdb->get_var("select count(id) from up_order_meals where order_id=".$order_id." AND meal_id=".$up_order_meals['meal_id']);

                if($count==0){
					$query_insert="insert into up_order_meals (order_id,meal_id,ingredient_ids,exchangble) values(".$order_id.",".$up_order_meals['meal_id'].",'".$up_order_meals['ingredient_ids']."',0)";
					$wpdb->query($query_insert);
                }
            }
        }
    }
}

$final_meal = array_merge($prioritise_meals, $res_meals);
echo '<br/>count'.count($final_meal);
$final_meal = implode(',', $final_meal);
echo '<br/>final ' . $final_meal . '<br><br>';
//print_r($exchangable_foods);
//exit;
if (empty($final_meal)) {
    $final_meal = 0;
}

$f_status = $f1_status = '';
if (!empty($res_nut_typeval) && !empty($res_int_typeval)) {
    $cond1 = implode(' AND ', $nut_typeval);
    $cond2 = implode(' AND ', $int_typeval);
    $f_status = "if(((" . $cond1 . ") AND (" . $cond2 . ")),1,0) f_status,";
} else if (!empty($res_nut_typeval) && empty($res_int_typeval)) {
    $cond1 = implode(' AND ', $nut_typeval);
    $f_status = "if((" . $cond1 . "),1,0) f_status,";
} else if (empty($res_nut_typeval) && !empty($res_int_typeval)) {
    $cond2 = implode(' AND ', $int_typeval);
    $f_status = "if((" . $cond2 . "),1,0) f_status,";
} else {
    $f_status = "1 AS f_status,";
}

if (!empty($res_nut_typeval1) && !empty($res_int_typeval1)) {
    $cond1 = implode(' AND ', $nut_typeval1);
    $cond2 = implode(' AND ', $int_typeval1);
    $f1_status = ",if(((" . $cond1 . ") AND (" . $cond2 . ")),1,0) f1_status";
} else if (!empty($res_nut_typeval1) && empty($res_int_typeval1)) {
    $cond1 = implode(' AND ', $nut_typeval1);
    $f1_status = ",if((" . $cond1 . "),1,0) f1_status";
} else if (empty($res_nut_typeval1) && !empty($res_int_typeval1)) {
    $cond2 = implode(' AND ', $int_typeval1);
    $f1_status = ",if((" . $cond2 . "),1,0) f1_status";
} else {
    $f1_status = ",1 AS f1_status";
}

/* get plan data */
$planData = get_post($plan);
echo 'Plan : ' . $planData->post_title . '</br>';

/* get chosen plan period (4 or 12) */
$period = get_post_meta($plan, 'plan_period', true);
echo 'Plan period: ' . $period . '</br>';

$PAL = 1.20;
if ($activity_level >= 0 && $activity_level <= 19.99) {
    $PAL = 1.20;
} else if ($activity_level >= 20 && $activity_level <= 39.99) {
    $PAL = 1.45;
} else if ($activity_level >= 40 && $activity_level <= 59.99) {
    $PAL = 1.65;
} else if ($activity_level >= 60 && $activity_level <= 69.99) {
    $PAL = 1.85;
} else if ($activity_level >= 80 && $activity_level <= 89.99) {
    $PAL = 2.15;
} else if ($activity_level >= 90 && $activity_level <= 100) {
    $PAL = 2.35;
}
echo 'PAL: ' . $PAL . '</br>';

if ($gender == 'f') {
    $Micro_allocation = array(
        'Vitamin A' => 0.8,
        'Vitamin C' => 95,
        'Vitamin D' => 0.02,
        'Vitamin E' => 12,
        'Vitamin K' => 0.062,
        'Vitamin B1' => 1.1,
        'Vitamin B2' => 1.4,
        'Vitamin B6' => 1.2,
        'Vitamin B12' => 0.003,
        'Biotin' => 0.045,
        'Folsaure' => 0.56,
        'Niacin' => 12,
        'Panthotensaure' => 6,
        'Calcium [Ca]' => 1000,
        'Chlor [Cl]' => 830,
        'Kalium [K]' => 2000,
        'Magnesium [Mg]' => 300,
        'Natrium [Na]' => 650,
        'Phosphor [P]' => 700,
        'Schwefel [S]' => 0,
        'Kupfer [Cu]' => 1250,
        'Eisen [Fe]' => 15000,
        'Fluor [F]' => 3000,
        'Mangan [Mn]' => 3500,
        'Jod [J]' => 200,
        'Zink [Zn]' => 7000,
        'Gesattigte Fettsauren' => 0.33,
        'Einfach ungesattigte Fettsauren' => 0.33,
        'Mehrfach ungesattigte Fettsauren' => 0.33,
        'Cholesterin' => 0.33,
        'Alanin' => 0,
        'Arginin' => 0,
        'Aspargin' => 0,
        'Asparginsäure' => 0,
        'Cystein' => 4,
        'Glutamin' => 0,
        'Glutaminsaure' => 0,
        'Glycerin' => 0,
        'Histidin' => 0,
        'Isoleucin' => 0,
        'Leucin' => 39,
        'Lysin' => 30,
        'Methionin' => 0,
        'Phenylalanin' => 0,
        'Prolin' => 0,
        'Serin' => 0,
        'Threonin' => 15,
        'Tryptophan' => 4,
        'Tyrosin' => 0,
        'Valin' => 26,
    );
} else {
    $Micro_allocation = array(
        'Vitamin A' => 1,
        'Vitamin C' => 110,
        'Vitamin D' => 0.02,
        'Vitamin E' => 14,
        'Vitamin K' => 0.07,
        'Vitamin B1' => 1.1,
        'Vitamin B2' => 1.4,
        'Vitamin B6' => 1.5,
        'Vitamin B12' => 0.003,
        'Biotin' => 0.045,
        'Folsaure' => 0.56,
        'Niacin' => 15,
        'Panthotensaure' => 6,
        'Calcium [Ca]' => 1000,
        'Chlor [Cl]' => 830,
        'Kalium [K]' => 2000,
        'Magnesium [Mg]' => 350,
        'Natrium [Na]' => 650,
        'Phosphor [P]' => 700,
        'Schwefel [S]' => 0,
        'Kupfer [Cu]' => 1250,
        'Eisen [Fe]' => 10000,
        'Fluor [F]' => 4000,
        'Mangan [Mn]' => 3500,
        'Jod [J]' => 200,
        'Zink [Zn]' => 10000,
        'Gesattigte Fettsauren' => 0.33,
        'Einfach ungesattigte Fettsauren' => 0.33,
        'Mehrfach ungesattigte Fettsauren' => 0.33,
        'Cholesterin' => 0.33,
        'Alanin' => 0,
        'Arginin' => 0,
        'Aspargin' => 0,
        'Asparginsäure' => 0,
        'Cystein' => 4,
        'Glutamin' => 0,
        'Glutaminsaure' => 0,
        'Glycerin' => 0,
        'Histidin' => 0,
        'Isoleucin' => 0,
        'Leucin' => 39,
        'Lysin' => 30,
        'Methionin' => 0,
        'Phenylalanin' => 0,
        'Prolin' => 0,
        'Serin' => 0,
        'Threonin' => 15,
        'Tryptophan' => 4,
        'Tyrosin' => 0,
        'Valin' => 26,
    );
}

//Adjust Total Energy Expenditure per day
$four_week_plan = array('mon' => -35, 'tue' => -35, 'wed' => -35, 'thu' => -35, 'fri' => -35, 'sat' => -35, 'sun' => 10);
$twelve_week_plan = array('mon' => -25, 'tue' => -25, 'wed' => -10, 'thu' => -25, 'fri' => -25, 'sat' => -25, 'sun' => 10);
$weektype = array(12 => $twelve_week_plan, 4 => $twelve_week_plan);

$day = strtolower(date('D'));
$day_value = $weektype[$period][$day];
$plan_week = $weektype[$period];

//Split Total Energy Expenditure on meals
$meals_plan=array();
if ($sweet_tooth == 'yes') {
    echo '5 meals par day</br>';
    $per_day_no_of_melas = 5;
    //5 meals plan
    $meals_plan = array('breakfast' => 25, 'pre_lunch_snack' => 10, 'lunch' => 30, 'pre_dinner_snack' => 10, 'dinner' => 25);
} else {
    echo '4 meals par day</br>';
    $per_day_no_of_melas = 4;
    //4 meals plan
    $meals_plan = array('breakfast' => 27.5, 'lunch' => 35, 'pre_dinner_snack' => 10, 'dinner' => 27.5);
}
$priority_micros = $wpdb->get_results("CALL prioritise_val_v4('" . $order_id . "');", OBJECT_K);
//echo "<pre>";print_r($priority_micros);
//exit;
/* Repetition of meat and fish */
$fish_meals = $meat_meals = array();
if (in_array('nospecial', $nutrition_type)) {
    $meat_meals = $wpdb->get_results('call meat_and_fish_meals("' . $final_meal . '","Fleisch")', OBJECT_K);
    //echo "meat meals<pre>";print_r($meat_meals);
    $fish_meals = $wpdb->get_results('call meat_and_fish_meals("' . $final_meal . '","Fisch")', OBJECT_K);
    //echo "fish meals<pre>";print_r($fish_meals);
}
if (in_array('pescetarian', $nutrition_type)) {
    $fish_meals = $wpdb->get_results('call meat_and_fish_meals("' . $final_meal . '","Fisch")', OBJECT_K);
   //echo "fish meals<pre>";print_r($fish_meals);
}


/* par day NPES and kcal sum value breakfast ,lunch,dinner,snack */
$price_Quality=1;
if(strcasecmp($where_food_buy,"cheap")==0) {
	$price_Quality=1;
} else if(strcasecmp($where_food_buy,"normal")==0) {
	$price_Quality=2;
} else if(strcasecmp($where_food_buy,"premium")==0) {
	$price_Quality=3;
}

$ideal_protien;$ideal_fat;$ideal_carbo;
if ($period == 12) {
    //for 12 week plan
    $ideal_protien=40;
    $ideal_fat=20;
    $ideal_carbo=40;
} else {
    //for 4 week plans
    $ideal_protien=45;
    $ideal_fat=17.5;
    $ideal_carbo=37.5;
}

$ideal_values_for_meal = array(
    'Kilokalorien' => 450.35,
    'Protein' => $ideal_protien,
    'Fett' => $ideal_fat,
    'Kohlenhydrate' => $ideal_carbo,
    'Vorbereitungszeit' => $available_time,
    'Preis/Qualität' => $price_Quality,
    'Gesättigte Fettsäuren' => $Micro_allocation['Gesattigte Fettsauren'],
    'Einfach ungesättigte Fettsäuren' => $Micro_allocation['Einfach ungesattigte Fettsauren'],
    'Mehrfach ungesättigte Fettsäuren' => $Micro_allocation['Mehrfach ungesattigte Fettsauren'],
    //'Cholesterin' => $Micro_allocation['Cholesterin'],
    'Vitamin A' => $Micro_allocation['Vitamin A'],
    'Vitamin C' => $Micro_allocation['Vitamin C'],
    'Vitamin D' => $Micro_allocation['Vitamin D'],
    'Vitamin E' => $Micro_allocation['Vitamin E'],
    'Vitamin K' => $Micro_allocation['Vitamin K'],
    'Vitamin B1' => $Micro_allocation['Vitamin B1'],
    'Vitamin B2' => $Micro_allocation['Vitamin B2'],
    'Vitamin B6' => $Micro_allocation['Vitamin B6'],
    'Vitamin B12' => $Micro_allocation['Vitamin B12'],
    'Biotin' => $Micro_allocation['Biotin'],
    'Folsäure' => $Micro_allocation['Folsaure'],
    'Niacin' => $Micro_allocation['Niacin'],
    'Panthotensäure' => $Micro_allocation['Panthotensaure'],
    'Calcium [Ca]' => $Micro_allocation['Calcium [Ca]'],
    'Chlor [Cl]' => $Micro_allocation['Chlor [Cl]'],
    'Kalium [K]' => $Micro_allocation['Kalium [K]'],
    'Magnesium [Mg]' => $Micro_allocation['Magnesium [Mg]'],
    'Natrium [Na]' => $Micro_allocation['Natrium [Na]'],
    'Phosphor [P]' => $Micro_allocation['Phosphor [P]'],
    //'Schwefel [S]' => $Micro_allocation['Schwefel [S]'],
    'Kupfer [Cu]' => $Micro_allocation['Kupfer [Cu]'],
    'Eisen [Fe]' => $Micro_allocation['Eisen [Fe]'],
    'Fluor [F]' => $Micro_allocation['Fluor [F]'],
    'Mangan [Mn]' => $Micro_allocation['Mangan [Mn]'],
    'Jod [J]' => $Micro_allocation['Jod [J]'],
    'Zink [Zn]' => $Micro_allocation['Zink [Zn]'],
    /* 'Alanin' => $Micro_allocation['Alanin'],
      'Arginin' => $Micro_allocation['Arginin'],
      'Aspargin' => $Micro_allocation['Aspargin'],
      'Asparginsäure' => $Micro_allocation['Asparginsaure'], */
    'Cystein' => $Micro_allocation['Cystein'],
    /* 'Glutamin' => $Micro_allocation['Glutamin'],
      'Glutaminsäure' => $Micro_allocation['Glutaminsaure'],
      'Glycerin' => $Micro_allocation['Glycerin'],
      'Histidin' => $Micro_allocation['Histidin'],
      'Isoleucin' => $Micro_allocation['Isoleucin'], */
    'Leucin' => $Micro_allocation['Leucin'],
    'Lysin' => $Micro_allocation['Lysin'],
    /* 'Methionin' => $Micro_allocation['Methionin'],
      'Phenylalanin' => $Micro_allocation['Phenylalanin'],
      'Prolin' => $Micro_allocation['Prolin'],
      'Serin' => $Micro_allocation['Serin'], */
    'Threonin' => $Micro_allocation['Threonin'],
    'Tryptophan' => $Micro_allocation['Tryptophan'],
    //'Tyrosin' => $Micro_allocation['Tyrosin'],
    'Valin' => $Micro_allocation['Valin'],
);

$fin_meals = array();
$final_meal_ids = array();
$original_weight = $current_weight;
$next_week_weight = $current_weight;

while($weeknum <= $period) {
    $week_meal_ids = array();
    $NPES = 0; //Nutrition Plan Energy Supply
    $tee = 0;
    $current_weight = $next_week_weight;
    echo 'Weeknum :' . $weeknum . '</br>';
    echo 'Current week weight: ' . number_format($current_weight, 2) . 'kg </br>';

    /* sunday is cheat day -- 0% every uneven week & +10% every even week */
    if ($weeknum % 2 == 0) {
        $sun_percentage = -($plan_week['sun']) / 100;
    } else {
        $sun_percentage = 0;
    }

    //Resting Metabolic Rate
    if ($gender == 'f') {
        $RMR = (655.1 + (9.6 * $current_weight) + (1.8 * $height) - (4.7 * $age)); //-$PAL*(100-25/100);
    } else {
        $RMR = (66.47 + (13.7 * $current_weight) + (5 * $height) - (6.8 * $age)); //-$PAL*(100-25);
    }

    $tee = $RMR * $PAL; //Total Energy Expenditure
    echo 'RMR: ' . $RMR . '</br>';
    echo 'TEE: ' . $tee . '</br>';

    $next_week_weight = $current_weight - ($tee * (((-$plan_week['mon']) / 100) + ((-$plan_week['tue']) / 100) + ((-$plan_week['wed']) / 100) + ((-$plan_week['thu']) / 100) + ((-$plan_week['fri']) / 100) + ((-$plan_week['sat']) / 100) + ($sun_percentage)) * ((1 / 7000)));
    echo 'Next week weight: ' . number_format($next_week_weight, 2) . 'kg </br>';

    $days_npes_cal = array();
    if (!empty($plan_week)) {
        foreach ($plan_week as $day => $day_value) {
            if ($day == 'sun') {
                if ($weeknum % 2 == 0) {
                    $day_percentage = $day_value / 100;
                } else {
                    $day_percentage = 0;
                }
            } else {
                $day_percentage = $day_value / 100;
            }

            $NPES = $tee * ((100 / 100) + ($day_percentage));
            $days_npes_cal[$day]['NPES'] = @round($NPES, 2);

            if (!empty($meals_plan)) {
                foreach ($meals_plan as $k => $m) {
                    $days_npes_cal[$day][$k][0] = @round($NPES * ($m / 100), 2);
                    $min_variance = @round($NPES * ($m / 100) - ($NPES * ($m / 100) * (15 / 100)), 2);
                    $max_variance = @round($NPES * ($m / 100) + ($NPES * ($m / 100) * (15 / 100)), 2);
                    $days_npes_cal[$day][$k][1] = $min_variance;
                    $days_npes_cal[$day][$k][2] = $max_variance;
                    $meals_matches = array();
                }
            }
        }
    }

    $breakfast_variance=$lunch_variance=$dinner_variance=$snack_variance=$variance = $micros_array = array();
    $html = '';
    $meal_portion=1;

    if(!empty($priority_micros)) {
        $variance=0.1;
        //If chosen plan = Fettkiller or couch potatoes then, 1% variance for Einfach ungesättigte Fettsäuren and Mehrfach ungesättigte Fettsäuren
        if($plan == 95 || $plan == 166) {
            $variance=0.5;
        }

        $percentage = 15;
        //If chosen plan = Sparfuchs then 20%  variance.
        if ($plan == 162) {
        	$percentage = 20;
        }

    	$html .='<table id="outer">';
    	$html .='<tr><td>';
    	$html .='<table border="1">';
    	$html .='<tr>';
    	$html .='<td><strong>Ideal values</strong></td><td><strong>next meal</strong></td>';
    	$html .='</tr>';
    	foreach ($ideal_values_for_meal as $k => $i) {
			$html .='<tr>';
			if($k == 'Kilokalorien') {
				$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . $days_npes_cal['mon']['breakfast'][0].",".$days_npes_cal['mon']['lunch'][0].",".$days_npes_cal['mon']['dinner'][0].",".$days_npes_cal['mon']['pre_dinner_snack'][0]. '</td>';
			} else if($k == 'Gesättigte Fettsäuren' || $k == 'Cystein' || $k == 'Leucin' || $k == 'Lysin' || $k == 'Threonin' || $k == 'Tryptophan' || $k == 'Valin') {
    			$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' .  ($cur_weight * $i * ($meals_plan['breakfast']/100)).','.($cur_weight * $i * ($meals_plan['lunch']/100)) .','.($cur_weight * $i * ($meals_plan['dinner']/100)).','.($cur_weight * $i * ($meals_plan['pre_dinner_snack']/100)) . '</td>';
            } else if($k == 'Einfach ungesättigte Fettsäuren' || $k == 'Mehrfach ungesättigte Fettsäuren') {
    			$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' .  ($cur_weight * $i * ($meals_plan['breakfast']/100)).','.($cur_weight * $i * ($meals_plan['lunch']/100)) .','.($cur_weight * $i * ($meals_plan['dinner']/100)).','.($cur_weight * $i * ($meals_plan['pre_dinner_snack']/100)) . '('.$variance.'%)</td>';
            } else if($k != 'Protein' && $k !='Fett' && $k!='Kohlenhydrate' && $k != 'Vorbereitungszeit' && $k != 'Preis/Qualität') {
	    		$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . ($i * ($meals_plan["breakfast"]/100)) .',' . ($i * ($meals_plan["lunch"]/100)) . ',' . ($i * ($meals_plan["dinner"]/100)). ',' . ($i * ($meals_plan["pre_dinner_snack"]/100)). '</td>';
            } else if($k=='Vorbereitungszeit') {
				$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . $i.'-'. implode(',',$time_ideal_value) . ' ('.$percentage.'%)</td>';
			} else if($k=='Preis/Qualität') {
				$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . $where_food_buy.'-'. $i . ' ('.$percentage.'%)</td>';
			} else {
				$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . $i. '</td>';
			}
			$html .='</tr>';
    	}

        //Variance calculation For partially included meals.
        $part_nut_type_string = $part_tolarance_type_string = '';
        $part_nut_type = $part_tolarance_type = array();
        if (!empty($nutrition_type)) {
            foreach ($nutrition_type as $n) {
                if ($n != 'nospecial' && $n != 'flexitarian') {
                    $val = $nutrition_types_array[$n];
                    $part_nut_type[] = 'sum(if(f.' . $val . ' = 0.5, 1, 0))';
                }
            }

            if (!empty($part_nut_type)) {
                $part_nut_type_string = implode(' + ', $part_nut_type);
            }
        }

        if (!empty($intolerance)) {
            foreach ($intolerance as $i) {
                $intal = $intolerance_array[$i];
                $part_tolarance_type[] = 'sum(if(f.' . $intal . ' = 0.5, 1, 0))';
            }

            if (!empty($part_tolarance_type)) {
                $part_tolarance_type_string = implode(' + ', $part_tolarance_type);
            }
        }

        if(!empty($part_nut_type_string) || !empty($part_tolarance_type_string)) {
            $part_sql = 'SELECT m.id, ';
            $add_sql = '';
            if(!empty($part_nut_type_string)) {
                $part_sql .= $part_nut_type_string;
                $add_sql = ' + ';
            }
            if(!empty($part_tolarance_type_string)) {
                $part_sql .= $add_sql . $part_tolarance_type_string;
            }
            $part_sql .= ' AS total_partial FROM up_meals m INNER JOIN up_meal_ingredients mi ON mi.meal_id = m.id left JOIN up_foods f ON f.name = mi.name where m.id in(' . $final_meal . ') group by m.id ORDER BY m.id;';

            $part_meals = $wpdb->get_results($part_sql, OBJECT_K);

            $html .='<tr><td title="Partly compatible">Partial</td><td>4</td></tr>';
        }

        //if ($plan == 95 || $plan == 166) {
            $html .='<tr><td title="Fat killer">Fat kill</td><td>-2</td></tr>';
        //}

    	$html .='<tr><td colspan="2">% Variance Sum</td></tr>';
    	$html .='</table></td>';

    	foreach ($priority_micros as $p => $pm) {
            $micros_array[$pm->id]['is_veg'] = $pm->is_veg;
            $micros_array[$pm->id]['fatt'] = $pm->fatt;
            $micros_array[$pm->id]['price_avgr'] = $pm->price_avgr;

    		$meal_categories=explode(",",$pm->meal_category_ids);
    		foreach ($meal_categories as $mc => $meal_category) {
    			$sum = 0;$breakfast_id;$lunch_id;$dinner_id;$snack_id;
    			$meal_type = '';

    			if (!empty($fish_meals)) {
					if (isset($fish_meals[$pm->id])) {
			            $micros_array[$pm->id]['Fisch'] = 1;
					}
    			}

    			if (!empty($meat_meals)) {
					if (isset($meat_meals[$pm->id])) {
			            $micros_array[$pm->id]['Fleisch'] = 1;
					}
    			}

    			$time_value=0;
    			if($meal_category == 1) {
    				$breakfast_id=$pm->id;
    				$meal_portion=$meals_plan['breakfast']/100;
    				$meal_type = 'breakfast';
                    $kal = @round(((abs($days_npes_cal['mon']['breakfast'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['breakfast'][0]) * 150, 5);
                    $time_value=$time_ideal_value['breakfast'];
                    $breakfast_variance[$breakfast_id]['kal'] = $pm->Kilokalorien;
    			} else if($meal_category == 2) {
    				$lunch_id=$pm->id;
    				$meal_portion=$meals_plan['lunch']/100;
    				$meal_type = 'lunch';
                    $kal = @round(((abs($days_npes_cal['mon']['lunch'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['lunch'][0]) * 150, 5);
                    $time_value=$time_ideal_value['lunch'];
                    $lunch_variance[$lunch_id]['kal'] = $pm->Kilokalorien;
    			} else if($meal_category == 3) {
    				$dinner_id=$pm->id;
    				$meal_portion=$meals_plan['dinner']/100;
    				$meal_type = 'dinner';
                    $kal = @round(((abs($days_npes_cal['mon']['dinner'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['dinner'][0]) * 150, 5);
                    $time_value=$time_ideal_value['dinner'];
                    $dinner_variance[$dinner_id]['kal'] = $pm->Kilokalorien;
    			} else if($meal_category == 4) {
    				$snack_id=$pm->id;
    				$meal_portion=$meals_plan['pre_dinner_snack']/100;
    				$meal_type = 'snack';
                    $kal = @round(((abs($days_npes_cal['mon']['pre_dinner_snack'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['pre_dinner_snack'][0]) * 150, 5);
                    $time_value=$time_ideal_value['snack'];
                    $snack_variance[$snack_id]['kal'] = $pm->Kilokalorien;
    			}

    			if ($mc != 0) {
    				$html .='</table></td>';
    			}

    			$html .='<td><table border="1">';
    			$html .='<tr>';
    			$html .='<td title="' . $pm->name . ' ' . $pm->id . '"><strong> ' . substr($pm->name, 0, 15) . '</strong></td><td><strong>diff vs.' . $pm->id . ' (' . $meal_type . ') </strong></td>';
    			$html .='</tr>';

    			$micros_array[$pm->id]['id'] = $pm->id;
                $micros_array[$pm->id]['kcal']=$pm->Kilokalorien;

    			$html .='<tr><td>' . @round($pm->Kilokalorien, 4) . '</td><td>' . $kal . '%</td></tr>';

    			$pr_fat_carb = $pm->Protein + $pm->Fett + $pm->Kohlenhydrate;
    			$avg_Protein = @round($pm->Protein / $pr_fat_carb * 100, 2);
    			$Protein = @round(abs($ideal_values_for_meal['Protein'] - $avg_Protein), 5);
    			$sum +=$Protein;
    			$html .='<tr><td>' . $avg_Protein . '%</td><td>' . $Protein . '%</td></tr>';
    			$micros_array[$pm->id]['Protein'] = $avg_Protein;
    			$micros_array[$pm->id]['Protein_variance'] = $Protein;

    			$avg_Fett = @round($pm->Fett / $pr_fat_carb * 100, 2);
    			$Fett = @round(abs($ideal_values_for_meal['Fett'] - $avg_Fett), 5);
    			$sum +=$Fett;
    			$html .='<tr><td>' . $avg_Fett . '%</td><td>' . $Fett . '%</td></tr>';
    			$micros_array[$pm->id]['Fett'] = $avg_Fett;
                $micros_array[$pm->id]['Fett_variance'] = $Fett;

    			$avg_Kohlenhydrate = @round($pm->Kohlenhydrate / $pr_fat_carb * 100, 5);
    			$Kohlenhydrate = @round(abs($ideal_values_for_meal['Kohlenhydrate'] - $avg_Kohlenhydrate), 5);
    			$sum +=$Kohlenhydrate;
    			$html .='<tr><td>' . $avg_Kohlenhydrate . '%</td><td>' . $Kohlenhydrate . '%</td></tr>';
    			$micros_array[$pm->id]['Kohlenhydrate'] = $avg_Kohlenhydrate;
                $micros_array[$pm->id]['Kohlenhydrate_variance'] = $Kohlenhydrate;

    			$prepare_time = @round(abs((date("i",strtotime($pm->preparation_time))) - $time_value) * ($percentage/10), 5);
    			$html .='<tr><td>' . $pm->preparation_time . '</td><td>' . $prepare_time . '%</td></tr>';
    			$sum +=$prepare_time;

    			$temp_price_level = @round($pm->price_avgr, 4);
    			$price_level = @round(abs($temp_price_level - $ideal_values_for_meal['Preis/Qualität']) * $percentage, 5);
    			$sum +=$price_level;
    			$html .='<tr><td>' . $temp_price_level . '</td><td>' . $price_level . '%</td></tr>';

    			$ge_f = calculate_micro_value($pm->{'Gesättigte Fettsäuren'},($cur_weight * $ideal_values_for_meal['Gesättigte Fettsäuren']),$meal_portion,0.1);
    			$sum +=$ge_f;
    			$html .='<tr><td>' . @round($pm->{'Gesättigte Fettsäuren'}, 4) . '</td><td>' . $ge_f . '%</td></tr>';

    			$ein_un_f = calculate_micro_value($pm->{'Einfach ungesättigte Fettsäuren'},($cur_weight * $ideal_values_for_meal['Einfach ungesättigte Fettsäuren']),$meal_portion,$variance);
    			$sum +=$ein_un_f;
    			$html .='<tr><td>' . @round($pm->{'Einfach ungesättigte Fettsäuren'}, 4) . '</td><td>' . $ein_un_f . '%</td></tr>';

    			$mn_un_f = calculate_micro_value($pm->{'Mehrfach ungesättigte Fettsäuren'},($cur_weight * $ideal_values_for_meal['Mehrfach ungesättigte Fettsäuren']),$meal_portion,$variance);
    			$sum +=$mn_un_f;
    			$html .='<tr><td>' . @round($pm->{'Mehrfach ungesättigte Fettsäuren'}, 4) . '</td><td>' . $mn_un_f . '%</td></tr>';

    			$Vitamin_A = calculate_micro_value($pm->{'Vitamin A'},$ideal_values_for_meal['Vitamin A'],$meal_portion,0.1);
    			$sum +=$Vitamin_A;
    			$html .='<tr><td>' . @round($pm->{'Vitamin A'}, 4) . '</td><td>' . $Vitamin_A . '%</td></tr>';

    			$Vitamin_C = calculate_micro_value($pm->{'Vitamin C'},$ideal_values_for_meal['Vitamin C'],$meal_portion,0.1);
    			$sum +=$Vitamin_C;
    			$html .='<tr><td>' . @round($pm->{'Vitamin C'}, 4) . '</td><td>' . $Vitamin_C . '%</td></tr>';

    			$Vitamin_D = calculate_micro_value($pm->{'Vitamin D'},$ideal_values_for_meal['Vitamin D'],$meal_portion,0.1);
    			$sum +=$Vitamin_D;
    			$html .='<tr><td>' . @round($pm->{'Vitamin D'}, 4) . '</td><td>' . $Vitamin_D . '%</td></tr>';

    			$Vitamin_E = calculate_micro_value($pm->{'Vitamin E'},$ideal_values_for_meal['Vitamin E'],$meal_portion,0.1);
    			$sum +=$Vitamin_E;
    			$html .='<tr><td>' . @round($pm->{'Vitamin E'}, 4) . '</td><td>' . $Vitamin_E . '%</td></tr>';

    			$Vitamin_K = calculate_micro_value($pm->{'Vitamin K'},$ideal_values_for_meal['Vitamin K'],$meal_portion,0.1);
    			$sum +=$Vitamin_K;
    			$html .='<tr><td>' . @round($pm->{'Vitamin K'}, 4) . '</td><td>' . $Vitamin_K . '%</td></tr>';

    			$Vitamin_B1 = calculate_micro_value($pm->{'Vitamin B1'},$ideal_values_for_meal['Vitamin B1'],$meal_portion,0.1);
    			$sum +=$Vitamin_B1;
    			$html .='<tr><td>' . @round($pm->{'Vitamin B1'}, 4) . '</td><td>' . $Vitamin_B1 . '%</td></tr>';

    			$Vitamin_B2 = calculate_micro_value($pm->{'Vitamin B2'},$ideal_values_for_meal['Vitamin B2'],$meal_portion,0.1);
    			$sum +=$Vitamin_B2;
    			$html .='<tr><td>' . @round($pm->{'Vitamin B2'}, 4) . '</td><td>' . $Vitamin_B2 . '%</td></tr>';

    			$Vitamin_B6 = calculate_micro_value($pm->{'Vitamin B6'},$ideal_values_for_meal['Vitamin B6'],$meal_portion,0.1);
    			$sum +=$Vitamin_B6;
    			$html .='<tr><td>' . @round($pm->{'Vitamin B6'}, 4) . '</td><td>' . $Vitamin_B6 . '%</td></tr>';

    			$Vitamin_B12 = calculate_micro_value($pm->{'Vitamin B12'},$ideal_values_for_meal['Vitamin B12'],$meal_portion,0.1);
    			$sum +=$Vitamin_B12;
    			$html .='<tr><td>' . @round($pm->{'Vitamin B12'}, 4) . '</td><td>' . $Vitamin_B12 . '%</td></tr>';

    			$Biotin = calculate_micro_value($pm->{'Biotin'},$ideal_values_for_meal['Biotin'],$meal_portion,0.1);
    			$sum +=$Biotin;
    			$html .='<tr><td>' . @round($pm->{'Biotin'}, 4) . '</td><td>' . $Biotin . '%</td></tr>';

    			$Folsaure = calculate_micro_value($pm->{'Folsäure'},$ideal_values_for_meal['Folsäure'],$meal_portion,0.1);
    			$sum +=$Folsaure;
    			$html .='<tr><td>' . @round($pm->{'Folsäure'}, 4) . '</td><td>' . $Folsaure . '%</td></tr>';

    			$Niacin = calculate_micro_value($pm->{'Niacin'},$ideal_values_for_meal['Niacin'],$meal_portion,0.1);
    			$sum +=$Niacin;
    			$html .='<tr><td>' . @round($pm->{'Niacin'}, 4) . '</td><td>' . $Niacin . '%</td></tr>';

    			$Panthotensaure = calculate_micro_value($pm->{'Panthotensäure'},$ideal_values_for_meal['Panthotensäure'],$meal_portion,0.1);
    			$sum +=$Panthotensaure;
    			$html .='<tr><td>' . @round($pm->{'Panthotensäure'}, 4) . '</td><td>' . $Panthotensaure . '%</td></tr>';

    			$Calcium = calculate_micro_value($pm->{'Calcium [Ca]'},$ideal_values_for_meal['Calcium [Ca]'],$meal_portion,0.1);
    			$sum +=$Calcium;
    			$html .='<tr><td>' . @round($pm->{'Calcium [Ca]'}, 4) . '</td><td>' . $Calcium . '%</td></tr>';

    			$Chlor = calculate_micro_value($pm->{'Chlor [Cl]'},$ideal_values_for_meal['Chlor [Cl]'],$meal_portion,0.1);
    			$sum +=$Chlor;
    			$html .='<tr><td>' . @round($pm->{'Chlor [Cl]'}, 4) . '</td><td>' . $Chlor . '%</td></tr>';

    			$Kalium = calculate_micro_value($pm->{'Kalium [K]'},$ideal_values_for_meal['Kalium [K]'],$meal_portion,0.1);
    			$sum +=$Kalium;
    			$html .='<tr><td>' . @round($pm->{'Kalium [K]'}, 4) . '</td><td>' . $Kalium . '%</td></tr>';

    			$Magnesium = calculate_micro_value($pm->{'Kalium [K]'},$ideal_values_for_meal['Kalium [K]'],$meal_portion,0.1);
    			$sum +=$Magnesium;
    			$html .='<tr><td>' . @round($pm->{'Magnesium [Mg]'}, 4) . '</td><td>' . $Magnesium . '%</td></tr>';

    			$Natrium = calculate_micro_value($pm->{'Natrium [Na]'},$ideal_values_for_meal['Natrium [Na]'],$meal_portion,0.1);
    			$sum +=$Natrium;
    			$html .='<tr><td>' . @round($pm->{'Natrium [Na]'}, 4) . '</td><td>' . $Natrium . '%</td></tr>';

    			$Phosphor = calculate_micro_value($pm->{'Phosphor [P]'},$ideal_values_for_meal['Phosphor [P]'],$meal_portion,0.1);
    			$sum +=$Phosphor;
    			$html .='<tr><td>' . @round($pm->{'Phosphor [P]'}, 4) . '</td><td>' . $Phosphor . '%</td></tr>';

    			$Kupfer = calculate_micro_value($pm->{'Kupfer [Cu]'},$ideal_values_for_meal['Kupfer [Cu]'],$meal_portion,0.1);
    			$sum +=$Kupfer;
    			$html .='<tr><td>' . @round($pm->{'Kupfer [Cu]'}, 4) . '</td><td>' . $Kupfer . '%</td></tr>';

    			$Eisen = calculate_micro_value($pm->{'Eisen [Fe]'},$ideal_values_for_meal['Eisen [Fe]'],$meal_portion,0.1);
    			$sum +=$Eisen;
    			$html .='<tr><td>' . @round($pm->{'Eisen [Fe]'}, 4) . '</td><td>' . $Eisen . '%</td></tr>';

    			$Fluor = calculate_micro_value($pm->{'Fluor [F]'},$ideal_values_for_meal['Fluor [F]'],$meal_portion,0.1);
    			$sum +=$Fluor;
    			$html .='<tr><td>' . @round($pm->{'Fluor [F]'}, 4) . '</td><td>' . $Fluor . '%</td></tr>';

    			$Mangan = calculate_micro_value($pm->{'Mangan [Mn]'},$ideal_values_for_meal['Mangan [Mn]'],$meal_portion,0.1);
    			$sum +=$Mangan;
    			$html .='<tr><td>' . @round($pm->{'Mangan [Mn]'}, 4) . '</td><td>' . $Mangan . '%</td></tr>';

    			$Jod = calculate_micro_value($pm->{'Jod [J]'},$ideal_values_for_meal['Jod [J]'],$meal_portion,0.1);
    			$sum +=$Jod;
    			$html .='<tr><td>' . @round($pm->{'Jod [J]'}, 4) . '</td><td>' . $Jod . '%</td></tr>';

    			$Zink = calculate_micro_value($pm->{'Zink [Zn]'},$ideal_values_for_meal['Zink [Zn]'],$meal_portion,0.1);
    			$sum +=$Zink;
    			$html .='<tr><td>' . @round($pm->{'Zink [Zn]'}, 4) . '</td><td>' . $Zink . '%</td></tr>';

				$Cystein = calculate_micro_value($pm->{'Cystein'},($cur_weight * $ideal_values_for_meal['Cystein']),$meal_portion,0.1);
    			$sum +=$Cystein;
    			$html .='<tr><td>' . @round($pm->{'Cystein'}, 4) . '</td><td>' . $Cystein . '%</td></tr>';

    			$Leucin = calculate_micro_value($pm->{'Leucin'},($cur_weight * $ideal_values_for_meal['Leucin']),$meal_portion,0.1);
    			$sum +=$Leucin;
    			$html .='<tr><td>' . @round($pm->{'Leucin'}, 4) . '</td><td>' . $Leucin . '%</td></tr>';

    			$Lysin = calculate_micro_value($pm->{'Lysin'},($cur_weight * $ideal_values_for_meal['Lysin']),$meal_portion,0.1);
    			$sum +=$Lysin;
    			$html .='<tr><td>' . @round($pm->{'Lysin'}, 4) . '</td><td>' . $Lysin . '%</td></tr>';

    			$Threonin = calculate_micro_value($pm->{'Threonin'},($cur_weight * $ideal_values_for_meal['Threonin']),$meal_portion,0.1);
    			$sum +=$Threonin;
    			$html .='<tr><td>' . @round($pm->{'Threonin'}, 4) . '</td><td>' . $Threonin . '%</td></tr>';

    			$Tryptophan = calculate_micro_value($pm->{'Tryptophan'},($cur_weight * $ideal_values_for_meal['Tryptophan']),$meal_portion,0.1);
    			$sum +=$Tryptophan;
    			$html .='<tr><td>' . @round($pm->{'Tryptophan'}, 4) . '</td><td>' . $Tryptophan . '%</td></tr>';

    			$Valin = calculate_micro_value($pm->{'Valin'},($cur_weight * $ideal_values_for_meal['Valin']),$meal_portion,0.1);
    			$sum +=$Valin;
    			$html .='<tr><td>' . @round($pm->{'Valin'}, 5) . '</td><td>' . $Valin . '%</td></tr>';

                // Add partial variance in total variance.
                $total_partial = 0;
                $partial_variance = 0;
                if (!empty($part_meals)) {
                    if(array_key_exists($pm->id, $part_meals)){
                        $total_partial = $part_meals[$pm->id]->total_partial;
                        $partial_variance = ($total_partial * 4);
                    }
                }
                if(!empty($part_nut_type_string) || !empty($part_tolarance_type_string)) {
                    $sum = $sum + $partial_variance;
        			$html .='<tr><td>' . $total_partial . '</td><td>' . $partial_variance . '%</td></tr>';
                }

                //if plan is fat killer, Is fatkiller = 1 should be reduced by 0,5%
                $total_fat = 0;
                $fat_variance = 0;
    			//if ($plan == 95 || $plan == 166) {
                    $total_fat = $pm->fatt;
                    $fat_variance = - ($total_fat * 2);
                    $sum = $sum + $fat_variance;
                    $html .='<tr><td>' . $total_fat . '</td><td>' . $fat_variance . '%</td></tr>';
    			//}

    			if(strcasecmp($available_time,'little')==0) {
    				if($meal_category == 1 && ($pm->preparation_time <= $time_con['breakfast'])) {
    					foreach ($days_npes_cal as $d => $v) {
    						$daykal = @round(((abs($v['breakfast'][0] - $pm->Kilokalorien)) / $v['breakfast'][0]) * 150, 5);
    					}

    					$breakfast_variance[$breakfast_id]['meal_id']=$breakfast_id;
    					$breakfast_variance[$breakfast_id]['exchangble'] = $pm->exchangble;
    					$breakfast_variance[$breakfast_id]['assign'] = 0;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
										if($k=='breakfast'){
											$breakfast_variance[$breakfast_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
										}
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 2 && ($pm->preparation_time <= $time_con['lunch'])) {
    					$lunch_variance[$lunch_id]['meal_id']=$lunch_id;
    					$lunch_variance[$lunch_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='lunch'){
                                            $lunch_variance[$lunch_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
                                         }
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 3 && ($pm->preparation_time <= $time_con['dinner'])) {
    					$dinner_variance[$dinner_id]['meal_id']=$dinner_id;
    					$dinner_variance[$dinner_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
    									if($k=='dinner'){
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        $dinner_variance[$dinner_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
                                        //}
    									}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 4 && ($pm->preparation_time <= $time_con['snack'])) {
    					$snack_variance[$snack_id]['meal_id']=$snack_id;
    					$snack_variance[$snack_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='pre_dinner_snack'){
                                            $snack_variance[$snack_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
                                        }
                                        //}
                                    }
                                }
                            }
                        }
    				}
    			} else if(strcasecmp($available_time,'much')==0) {
    				if($meal_category == 1 && ($pm->preparation_time >= $time_con['breakfast'])) {
    					$breakfast_variance[$breakfast_id]['meal_id']=$breakfast_id;
    					$breakfast_variance[$breakfast_id]['exchangble'] = $pm->exchangble;
    					$breakfast_variance[$breakfast_id]['assign'] = 0;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='breakfast'){
                                            $breakfast_variance[$breakfast_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
    									}
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 2 && ($pm->preparation_time >= $time_con['lunch'])) {
    					$lunch_variance[$lunch_id]['meal_id']=$lunch_id;
    					$lunch_variance[$lunch_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='lunch'){
                                            $lunch_variance[$lunch_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
    									}
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 3 && ($pm->preparation_time >= $time_con['dinner'])) {
    					$dinner_variance[$dinner_id]['meal_id']=$dinner_id;
    					$dinner_variance[$dinner_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='dinner'){
                                            $dinner_variance[$dinner_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
    									}
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 4 && ($pm->preparation_time >= $time_con['snack'])) {
    					$snack_variance[$snack_id]['meal_id']=$snack_id;
    					$snack_variance[$snack_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='pre_dinner_snack'){
                                            $snack_variance[$snack_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
                                         }
                                        //}
                                    }
                                }
                            }
                        }
    				}
    			} else {
    				if($meal_category == 1) {
    					$breakfast_variance[$breakfast_id]['meal_id']=$breakfast_id;
    					$breakfast_variance[$breakfast_id]['exchangble'] = $pm->exchangble;
    					$breakfast_variance[$breakfast_id]['assign'] = 0;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
										if($k=='breakfast'){
											$breakfast_variance[$breakfast_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
										}
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 2) {
    					$lunch_variance[$lunch_id]['meal_id']=$lunch_id;
    					$lunch_variance[$lunch_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='lunch'){
                                            $lunch_variance[$lunch_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
    									}
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 3) {
    					$dinner_variance[$dinner_id]['meal_id']=$dinner_id;
    					$dinner_variance[$dinner_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='dinner'){
                                            $dinner_variance[$dinner_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
    									}
                                        //}
                                    }
                                }
                            }
                        }
    				} else if($meal_category == 4) {
    					$snack_variance[$snack_id]['meal_id']=$snack_id;
    					//$snack_variance[$snack_id]['variance'] = $sum;
    					$snack_variance[$snack_id]['exchangble'] = $pm->exchangble;

                        if (!empty($plan_week)) {
                            foreach ($plan_week as $day => $day_value) {
                                if (!empty($meals_plan)) {
                                    foreach ($meals_plan as $k => $m) {
                                        //if(empty($breakfast_variance[$breakfast_id]['variance']) || !array_key_exists($m, $breakfast_variance[$breakfast_id]['variance'])) {
                                        if($k=='pre_dinner_snack'){
                                            $snack_variance[$snack_id]['variance'][$day][$m] = $sum + (@round(((abs(@round($days_npes_cal[$day]['NPES'] * ($m / 100), 2) - $pm->Kilokalorien)) / @round($days_npes_cal[$day]['NPES'] * ($m / 100), 2)) * 150, 5));
    									}
                                        //}
                                    }
                                }
                            }
                        }
    				}
    			}

                $sum +=$kal;
    			$html .='<tr><td>&nbsp;</td><td>' . $sum . '%</td></tr>';
    		}

    		if (end($meal_categories)) {
    			$html .='</table></td>';
    		}
    	}

    	if (end($priority_micros)) {
    		$html .='</table></td>';
    	}

    	$html .='</tr></table>';
    }



    /* D. c)balance out the variance */
    $rem_meals=$breakfast_meals=$pre_lunch_snack_meals=$lunch_meals=$dinner_meals=$pre_dinner_snack_meals=$fin_meals_db= array();
    $pescetarian_fish_count = 0;
    $nospecial_meat_fish_count = 0;
  //  echo "breakfast variance:<pre>";print_r($breakfast_variance);
    $breakfast_meals = select_balance_meals($plan, $nutrition_type, $breakfast_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'breakfast', $weeknum, null,$fin_meals,$fin_meals_db, $final_meal_ids, $week_meal_ids, $pescetarian_fish_count, $nospecial_meat_fish_count);
   // echo "breakfast meals:<pre>";print_r($breakfast_meals);

    $lunch_meals = select_balance_meals($plan, $nutrition_type, $lunch_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'lunch', $weeknum, $breakfast_meals,$fin_meals,$fin_meals_db, $final_meal_ids, $week_meal_ids, $pescetarian_fish_count, $nospecial_meat_fish_count);
    $dinner_meals = select_balance_meals($plan, $nutrition_type, $dinner_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'dinner', $weeknum, $lunch_meals,$fin_meals,$fin_meals_db, $final_meal_ids, $week_meal_ids, $pescetarian_fish_count, $nospecial_meat_fish_count);
    $pre_dinner_snack_meals = select_balance_meals($plan, $nutrition_type, $snack_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'pre_dinner_snack', $weeknum, $dinner_meals,$fin_meals,$fin_meals_db, $final_meal_ids, $week_meal_ids, $pescetarian_fish_count, $nospecial_meat_fish_count);
    if ($sweet_tooth == 'yes') {
        $pre_lunch_snack_meals = select_balance_meals($plan, $nutrition_type, $snack_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'pre_lunch_snack', $weeknum, $pre_dinner_snack_meals,$fin_meals,$fin_meals_db,$final_meal_ids, $week_meal_ids, $pescetarian_fish_count, $nospecial_meat_fish_count);
    }
   // if($weeknum == 1) {
        echo $html;

        echo "<br />breakfast variance<pre>";print_r($breakfast_variance);
        echo "<br />lunch variance<pre>";print_r($lunch_variance);
        echo "<br />dinner variance<pre>";print_r($dinner_variance);
        echo "<br />snack variance<pre>";print_r($snack_variance);

        echo "<br />day wise NPES:<pre>";print_r($days_npes_cal);
        echo "<br />micro values:<pre>";print_r($micros_array);

        echo "<br />breakfast meal<pre>";print_r($breakfast_meals);
        echo "<br />lunch meal<pre>";print_r($lunch_meals);
        echo "<br />dinner meal<pre>";print_r($dinner_meals);
        echo "<br />pre dinner meal<pre>";print_r($pre_dinner_snack_meals);
        echo "<br />pre lunch meal<pre>";print_r($pre_lunch_snack_meals);

        echo "<br />final meals of Week ".$weeknum.": <pre>";print_r($fin_meals[$weeknum]);
        echo "<br />pescetarian_fish_count:".$pescetarian_fish_count;
        echo "<br />nospecial_meat_fish_count:".$nospecial_meat_fish_count;

    //}

    $week_meals = implode(',', $week_meal_ids);

	$blog_id = get_current_blog_id();

    //echo "<br />week meal ids: <pre>";print_r($week_meal_ids);
    //echo "<br />week meals: ".$week_meals;
    //echo "weeknum: ".$weeknum."</br>";

    echo "<br />final meal id for database: <pre>";print_r($fin_meals_db);
    $last_user_nutrition_id = $wpdb->get_var("CALL insert_user_nutrition_plan(" . $order_id . ",'".$user_email."',". $plan.",".$period.",".count($meals_plan).",'".$final_meal."',".$height.",".$original_weight.",".$desired_weight.",".$age.",".$blog_id.");");
    $insert_meals="CALL insert_plan_logs(" . $last_user_nutrition_id . ",".$weeknum.",'".$week_meals."','".serialize($fin_meals_db['mon'])."','".serialize($fin_meals_db['tue'])."','".serialize($fin_meals_db['wed'])."','".serialize($fin_meals_db['thu'])."','".serialize($fin_meals_db['fri'])."','".serialize($fin_meals_db['sat'])."','".serialize($fin_meals_db['sun'])."',".$next_week_weight.",".$blog_id.");";
    $wpdb->query($insert_meals);
    $weeknum++;
}

echo "<br />final meals : <pre>";print_r($fin_meals);
echo "<br />final meal id repeats count: <pre>";print_r($final_meal_ids);


exit;

function calculate_micro_value($meal_micro_value,$ideal_value,$meal_portion,$weight_variance) {
	$ideal_value=$ideal_value*$meal_portion;
	$microcal=@round(abs((($meal_micro_value/$ideal_value * 100)-100)*$weight_variance),5);
	return $microcal;
}

function array_sort($variance_arr,$key1,$day,$percent) {
	if (!empty($variance_arr)) {
        $sort = array();
		foreach($variance_arr as $k=>$v) {
			//echo '$v here <pre>';
			//print_r($v);
			$sort['variance'][$day][$percent][$k] = $v['variance'][$day][$percent];
			$sort[$key1][$k] = $v[$key1];
		}

    	# sort by exchangble asc and then variance asc
    	array_multisort($sort[$key1],SORT_ASC, $sort['variance'][$day][$percent],SORT_ASC,$variance_arr);
	}

	return $variance_arr;
}

function select_balance_meals($plan, $nutrition_type, $variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $protin, $fat, $carbo, $meal_cat, $weeknum, $last_category_meals, &$fin_meals,&$fin_meals_db, &$final_meal_ids, &$week_meal_ids, &$pescetarian_fish_count, &$nospecial_meat_fish_count) {
    $micros_diff = array();
    $assigned_days = array();
    $meals_below_kcal = array();
    $meals_above_kcal = array();

	if (!empty($micros_array)) {
        // If chosen plan = Sparfuchs then repeat main meals all weeks.
	    if ($plan == 162) {
	        if($weeknum-1 > 0 && !empty($fin_meals[$weeknum-1]) && count($fin_meals[$weeknum-1]) > 0) {
                if ($meal_cat != "pre_lunch_snack" && $meal_cat != "pre_dinner_snack" ) {
                    $assigned_days = array("mon", "tue", "wed", "thu", "fri", "sat");
                    shuffle($assigned_days);
                    $random_days = $assigned_days;
                    $assigned_days = array("mon", "tue", "wed", "thu", "fri", "sat");

                    foreach ($assigned_days as $d => $v) {
                        if(array_key_exists($meal_cat, $fin_meals[$weeknum-1][$random_days[$d]]) && !empty($fin_meals[$weeknum-1][$random_days[$d]][$meal_cat])) {
                            $final_id = key($fin_meals[$weeknum-1][$random_days[$d]][$meal_cat]);
                            $final_meal_ids[$final_id] = $final_meal_ids[$final_id] + 1;
                            $micros_diff[$v][$meal_cat][$final_id] = $final_id;
                            $fin_meals_db[$v][$meal_cat][$final_id] = $final_id;
                            $fin_meals[$weeknum][$v][$meal_cat]=$fin_meals[$weeknum-1][$random_days[$d]][$meal_cat];
                            $fin_meals[$weeknum][$v][$meal_cat][$final_id]['reassigned'] = false;
                            $fin_meals[$weeknum][$v][$meal_cat][$final_id]['repeated'] = true;

                            if(empty($week_meal_ids) || !in_array($final_id,  $week_meal_ids)) {
                                 $week_meal_ids[$final_id] = $final_id;
                            }
                        } else {
                            unset($assigned_days[$v]);
                        }
                    }
                }
            }
        } else {
            // If chosen plan != Sparfuchs then repeat main meals of first 2 week to next 2 weeks.
            if($weeknum-2 > 0 && !empty($fin_meals[$weeknum-2]) && count($fin_meals[$weeknum-2]) > 0) {
                if(in_array($weeknum, array(3,4,7,8,11,12))) {
                    if ($meal_cat != "pre_lunch_snack" && $meal_cat != "pre_dinner_snack" ) {
                        $assigned_days = array("mon", "tue", "wed", "thu", "fri", "sat");
                        shuffle($assigned_days);
                        $random_days = $assigned_days;
                        $assigned_days = array("mon", "tue", "wed", "thu", "fri", "sat");
                        foreach ($assigned_days as $d => $v) {
                            if(array_key_exists($meal_cat, $fin_meals[$weeknum-1][$random_days[$d]]) && !empty($fin_meals[$weeknum-2][$random_days[$d]][$meal_cat])) {
                                $final_id = key($fin_meals[$weeknum-2][$random_days[$d]][$meal_cat]);
                                $final_meal_ids[$final_id] = $final_meal_ids[$final_id] + 1;
                                $micros_diff[$v][$meal_cat][$final_id] = $final_id;
                                $fin_meals_db[$v][$meal_cat][$final_id] = $final_id;
                                $fin_meals[$weeknum][$v][$meal_cat]=$fin_meals[$weeknum-2][$random_days[$d]][$meal_cat];
                                $fin_meals[$weeknum][$v][$meal_cat][$final_id]['reassigned'] = false;
                                $fin_meals[$weeknum][$v][$meal_cat][$final_id]['repeated'] = true;

                                if(empty($week_meal_ids) || !in_array($final_id,  $week_meal_ids)) {
                                     $week_meal_ids[$final_id] = $final_id;
                                }
                            } else {
                                unset($assigned_days[$v]);
                            }
                        }
                    }
                }
            }
        }
		//echo 'variance here<pre>';
		//print_r($variance);
    	foreach ($days_npes_cal as $d => $v) {
    	    if(!in_array($d, $assigned_days)) {
        		$variance=array_sort($variance,'exchangble',$d,$meals_plan[$meal_cat]);
                $is_found = true;
                $min_variance = 0;
                $max_variance = 0;
                $last_meal_id = 0;
                $meals_below_kcal = array();
                $meals_above_kcal = array();

               // echo "</br></br>day:".$d."</br></br>";
				//echo "variance:<pre>";print_r($v[$meal_cat]);
        		foreach ($variance as $k => $m) {
        		    $is_found = false;

                    $min_variance = $v[$meal_cat][1];
                    $max_variance = $v[$meal_cat][2];

                    $pro_diff = $protin;
                    $fat_diff = $fat;
                    $carb_diff = $carbo;
                    $new_kcal_meal_cat = $micros_array[$m['meal_id']]['kcal'];

                    if ($meal_cat == "breakfast" || $meal_cat == "lunch" || $meal_cat == "dinner") {
                        if($new_kcal_meal_cat <= $v[$meal_cat][0]) {
                            $meals_below_kcal[$m['meal_id']] =  $new_kcal_meal_cat;
                        } else {
                            $meals_above_kcal[$m['meal_id']] =  $new_kcal_meal_cat;
                        }
                    }

        		    if(!empty($last_category_meals) && !empty($last_category_meals[$d])) {
        		        $last_category = key($last_category_meals[$d]);
                        $last_meal_id = key($last_category_meals[$d][$last_category]);

                        if (!is_null($last_meal_id) && $last_meal_id != 0) {
                            $new_kcal_meal_cat=$v[$last_category][0]-$micros_array[$last_meal_id]['kcal']+$v[$meal_cat][0];
                            $min_variance = @round($new_kcal_meal_cat - ($new_kcal_meal_cat * (15 / 100)), 2);
                            $max_variance = @round($new_kcal_meal_cat + ($new_kcal_meal_cat * (15 / 100)), 2);

                            $pro_diff = $protin - $micros_array[$last_meal_id]['Protein'] + $protin;
                            $fat_diff = $fat - $micros_array[$last_meal_id]['Fett'] + $fat;
                            $carb_diff = $carbo - $micros_array[$last_meal_id]['Kohlenhydrate'] + $carbo;


                        }
                    }

                    $dif = $new_kcal_meal_cat + $pro_diff + $fat_diff + $carb_diff;
					$kcal = $micros_array[$m['meal_id']]['kcal'];
					/*if($meal_cat=='breakfast'){

						echo "breakfast min variance:".$min_variance."</br>";
						echo "breakfast max variance:".$max_variance."</br>";


						echo "meal id:".$m['meal_id']."kcal:".$kcal."</br>";
					}*/

        			if ($kcal >= $min_variance && $kcal <= $max_variance) {
                        $diff_array = array();
                        $current_meal_variance=$m['variance'][$d][$meals_plan[$meal_cat]];
                        $i = $k;
                        while (!empty($variance[$i])){
                           $next_meal_variance = $variance[$i]['variance'][$d][$meals_plan[$meal_cat]];

                           if($current_meal_variance != $next_meal_variance) {
                               break;
                           } else {
                                $next_micro_values = $micros_array[$variance[$i]['meal_id']];
                                $diff_array[$variance[$i]['meal_id']] = $dif - ($next_micro_values['kcal'] + $next_micro_values['Protein_variance'] + $next_micro_values['Fett_variance']   + $next_micro_values['Kohlenhydrate_variance']);
                                $i++;
                           }
                        }

                        $new_meal_id = $m['meal_id'];

                        if(!empty($diff_array) && count($diff_array) > 1) {
                            asort($diff_array);
                            $new_meal_id = key($diff_array);
                        }

                        $micro_values = $micros_array[$new_meal_id];

                        if(empty($final_meal_ids) || !array_key_exists($new_meal_id, $final_meal_ids)) {
                            $final_meal_id = meals_output_per_weeks($nutrition_type, $d, $meal_cat, $weeknum, $micro_values, $pescetarian_fish_count, $nospecial_meat_fish_count);
                            if(!empty($final_meal_id) && $final_meal_id != 0) {
                                $is_found = true;
                                $final_meal_ids[$final_meal_id] = 1;
                                $micros_diff[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                                $fin_meals_db[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['meal_id'] = $final_meal_id;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_ideal'] = $v[$meal_cat][0];
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_min'] = $min_variance;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_max'] = $max_variance;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal'] = $micros_array[$final_meal_id]['kcal'];
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_new'] = $new_kcal_meal_cat;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['reassigned'] = false;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['repeated'] = false;

                                if(empty($week_meal_ids) || !in_array($final_meal_id,  $week_meal_ids)) {
                                     $week_meal_ids[$final_meal_id] = $final_meal_id;
                                }
                                break;
                            }
                        }
        			}
        		}

                //if no compatible food found then assigned that are already assigned.
                if($is_found == false && !empty($final_meal_ids) && count($final_meal_ids) > 0) {
					//echo "no compatible";
                    asort($final_meal_ids);
                    foreach ($final_meal_ids as $key_meal =>$count) {
                        $min_variance = $v[$meal_cat][1];
                        $max_variance = $v[$meal_cat][2];

                        $pro_diff = $protin;
                        $fat_diff = $fat;
                        $carb_diff = $carbo;
                        $new_kcal_meal_cat = $micros_array[$key_meal]['kcal'];
            		    if(!empty($last_category_meals) && !empty($last_category_meals[$d])){
            		        $last_category = key($last_category_meals[$d]);
                            $last_meal_id = key($last_category_meals[$d][$last_category]);

                            if (!is_null($last_meal_id) && $last_meal_id != 0) {
                                $new_kcal_meal_cat=$v[$last_category][0]-$micros_array[$last_meal_id]['kcal']+$v[$meal_cat][0];
                                $min_variance = @round($new_kcal_meal_cat - ($new_kcal_meal_cat * (15 / 100)), 2);
                                $max_variance = @round($new_kcal_meal_cat + ($new_kcal_meal_cat * (15 / 100)), 2);
                            }
                        }


            			$kcal = $micros_array[$key_meal]['kcal'];

            			if ($kcal >= $min_variance && $kcal <= $max_variance) {
                            $micro_values = $micros_array[$new_meal_id];
							/*if($meal_cat=='breakfast'){
								echo "</br></br>day:".$d."</br></br>";
								echo "breakfast min variance:".$min_variance."</br>";
								echo "breakfast max variance:".$max_variance."</br>";


								echo "repeat meal id:".$key_meal."kcal:".$kcal."</br>";
							}*/

                            $final_meal_id = meals_output_per_weeks($nutrition_type, $d, $meal_cat, $weeknum, $micro_values, $pescetarian_fish_count, $nospecial_meat_fish_count);

                            if(!empty($final_meal_id) && $final_meal_id != 0) {
                                $is_found = true;
                                $final_meal_ids[$final_meal_id] = (isset($final_meal_ids[$final_meal_id]) ? $final_meal_ids[$final_meal_id] : 0) + 1;
                                $micros_diff[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                                $fin_meals_db[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['meal_id'] = $final_meal_id;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_ideal'] = $v[$meal_cat][0];
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_min'] = $min_variance;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_max'] = $max_variance;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal'] = $micros_array[$final_meal_id]['kcal'];
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_new'] = $new_kcal_meal_cat;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['reassigned'] = true;
                                $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['repeated'] = false;

                                if(empty($week_meal_ids) || !in_array($final_meal_id,  $week_meal_ids)) {
                                     $week_meal_ids[$final_meal_id] = $final_meal_id;
                                }
                                break;
                            }
            			}
            		}
                }

                if($is_found == false) {
                    if (!empty($meals_below_kcal)) {
                        arsort($meals_below_kcal);
                        echo "day:".$d;
                        echo "asort<pre>";print_r($meals_below_kcal);
                        echo "</br>";
                        $final_meal_id = key($meals_below_kcal);
                        $final_meal_ids[$final_meal_id] = (isset($final_meal_ids[$final_meal_id]) ? $final_meal_ids[$final_meal_id] : 0) + 1;
                        $micros_diff[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                        $fin_meals_db[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['meal_id'] = $final_meal_id;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_ideal'] = $v[$meal_cat][0];
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_min'] = $min_variance;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_max'] = $max_variance;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal'] = $micros_array[$final_meal_id]['kcal'];
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_new'] = $new_kcal_meal_cat;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['reassigned'] = false;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['repeated'] = true;

                        if(empty($week_meal_ids) || !in_array($final_meal_id,  $week_meal_ids)) {
                             $week_meal_ids[$final_meal_id] = $final_meal_id;
                        }
                    } else if (!empty($meals_above_kcal)) {
                        asort($meals_above_kcal);
                        $final_meal_id = key($meals_above_kcal);
                        $final_meal_ids[$final_meal_id] = (isset($final_meal_ids[$final_meal_id]) ? $final_meal_ids[$final_meal_id] : 0) + 1;
                        $micros_diff[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                        $fin_meals_db[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['meal_id'] = $final_meal_id;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_ideal'] = $v[$meal_cat][0];
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_min'] = $min_variance;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_max'] = $max_variance;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal'] = $micros_array[$final_meal_id]['kcal'];
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['kcal_new'] = $new_kcal_meal_cat;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['reassigned'] = false;
                        $fin_meals[$weeknum][$d][$meal_cat][$final_meal_id]['repeated'] = true;

                        if(empty($week_meal_ids) || !in_array($final_meal_id,  $week_meal_ids)) {
                             $week_meal_ids[$final_meal_id] = $final_meal_id;
                        }
                    } else {
                        $micros_diff[$d][$meal_cat][NULL] = NULL;
                        $fin_meals_db[$d][$meal_cat][NULL] = NULL;
                        $fin_meals[$weeknum][$d][$meal_cat][NULL] = NULL;
                        $fin_meals[$weeknum][$d][$meal_cat][NULL]['kcal_ideal'] = $v[$meal_cat][0];
                        $fin_meals[$weeknum][$d][$meal_cat][NULL]['kcal_min'] = $min_variance1;
                        $fin_meals[$weeknum][$d][$meal_cat][NULL]['kcal_max'] = $max_variance1;
                    }
                }
            }
    	}
    }

    return $micros_diff;
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

function meals_output_per_weeks($nutrition_type, $day, $meal_category, $weeknum, $micro_array, &$pescetarian_fish_count, &$nospecial_meat_fish_count) {
    if (in_array('nospecial', $nutrition_type)) {
        if($meal_category == 'lunch') {
            if ($weeknum == 1 || $weeknum == 3) {
                if ($day == 'tue' || $day == 'thu') {
                    if ($micro_array['is_veg'] == 0) {
                       return $micro_array['id'];
                    }
                }
            } else if ($weeknum == 2 || $weeknum == 4) {
                if ($day == 'mon' || $day == 'wed') {
                    if ($micro_array['is_veg'] == 0) {
                       return $micro_array['id'];
                    }
                }
            }
        }

        if(!empty($micro_array)){
			if(array_key_exists('Fleisch', $micro_array) && $nospecial_meat_fish_count < 4) {
				$nospecial_meat_fish_count++;
				return $micro_array['id'];
			}
			if(array_key_exists('Fisch', $micro_array) && $nospecial_meat_fish_count < 4) {
				$nospecial_meat_fish_count++;
				return $micro_array['id'];
			}
		}
    } else if (in_array('pescetarian', $nutrition_type)) {
        if($meal_category == 'lunch') {
            if ($weeknum == 1 || $weeknum == 3) {
                if ($day == 'tue' || $day == 'thu') {
                    if ($micro_array['is_veg'] == 0) {
                       return $micro_array['id'];
                    }
                }
            } else if ($weeknum == 2 || $weeknum == 4) {
                if ($day == 'mon' || $day == 'wed') {
                    if ($micro_array['is_veg'] == 0) {
                       return $micro_array['id'];
                    }
                }
            }
        }
 if(!empty($micro_array)){
        if(array_key_exists('Fisch', $micro_array) && $pescetarian_fish_count < 4) {
            $pescetarian_fish_count++;
            return $micro_array['id'];
        }
	}
    } else if (in_array('flexitarian', $nutrition_type)) {
        //skip the meal that are not compatible vagetarian attribute
        if($meal_category == 'breakfast') {
            if ($day == 'mon' || $day == 'thu') {
                if ($micro_array['is_veg'] == 0) {
                   return $micro_array['id'];
                }
            }
        } else if($meal_category == 'lunch') {
            if ($day == 'tue' || $day == 'fri') {
                if ($micro_array['is_veg'] == 0) {
                   return $micro_array['id'];
                }
            }
        } else if($meal_category == 'dinner') {
            if ($day == 'wed' || $day == 'sat') {
                if ($micro_array['is_veg'] == 0) {
                   return $micro_array['id'];
                }
            }
        }
    }

    return $micro_array['id'];
}
exit;
