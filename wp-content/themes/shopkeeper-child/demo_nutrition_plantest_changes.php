<?php

/*
 * Template Name: Demo Nutrition creation logic changes
 */

global $wpdb;
//$prefix = $wpdb->prefix;
$prefix = 'up_';

$nutrition_types_array = array(
    'nospecial' => '',
    'pescetarian' => 'cw_pescetarian',
    'vegetarian' => 'cw_vegetarian',
    'flexitarian' => '',
    'vegan' => 'cw_vegan',
    'paleo' => 'cw_paleo');

$intolerance_array = array('lactose' => 'cw_lactose_intolerance',
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
    'strawberry' => 'cw_strawberry_intolerance');

/* logic start from here */
$weeknum = 1;
//print_r($_GET);exit;
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
$is_time_to_cook = $_GET['perpare_time'] ? $_GET['perpare_time'] : 'little';
$where_food_buy = $_GET['food'] ? $_GET['food'] : 'cheap';
$most_buy = $_GET['buy'] ? $_GET['buy'] : 'both';
$plan = $_GET['plan'] ? $_GET['plan'] : 164;

$nutrion_plan_detail = array(
    'cur_weight' => $cur_weight,
    'desired_weight' => $desired_weight,
    'gender' => $gender,
    'age' => $age,
    'height' => $height,
    'daily_activity' => $daily_activity,
    'nutrition_type' => $nutrition_type,
    //'allergies' => 'lactose,fructose,histamine,gluten,glutamat,sucrose',
    //'allergies' => 'lactose',
    'allergies' => $allergies,
    //'nuts' => 'peanut,hazelnut,almond',
    'nuts' => '',
    'fruit' => '',
    'exclude' => $exclude,
    'sweet_tooth' => $sweet_tooth,
    'is_time_to_cook' => $is_time_to_cook,
    'where_food_buy' => $where_food_buy,
    'most_buy' => $most_buy,
    'plan' => $plan
);
/* $red = $wpdb->get_results("call `get_nutrition_meals` (
  75, 50,  'f', 30, 154, 24,  'pescetarian',  '',  '',  '',  'Aal',  'no',  'little',  'cheap',  'both', 164
  )");

  print_r($red);exit; */
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
        $price_level = $price_leveld->id;
}

$res_nut_type = $res_nut_type1 = $res_nut_typeval = $res_nut_typeval1 = $nut_type_cond = $nut_type_cond1 = $f1nut_type_cond = $f1nut_type_cond1 = '';
if (!empty($nutrition_type)) {

        foreach ($nutrition_type as $n) {
                if ($n != 'nospecial') {

                        if ($n == 'flexitarian') {
                                $res_nut_type .= 'IFNULL(f.cw_vegetarian,0) as f_cw_vegetarian,IFNULL(f.cw_vegan,0) as f_cw_vegan,';
                                /*$nut_type_cond[] = '(f_cw_vegetarian==0 || f_cw_vegan==0)';
                                $nut_type_cond1[] = '(f_cw_vegetarian==1 || f_cw_vegan==1)';

                                $f1nut_type_cond[] = '(f1_cw_vegetarian==0 || f1_cw_vegan==0)';
                                $f1nut_type_cond1[] = '(f1_cw_vegetarian==1 || f1_cw_vegan==1)';*/

                                $res_nut_type1 .= 'IFNULL(f1.cw_vegetarian,0) as f1_cw_vegetarian,IFNULL(f1.cw_vegan,0) as f1_cw_vegan,';
                                /*$nut_typeval[] = ' f.cw_vegetarian=1 OR f.cw_vegan=1 ';
                                $nut_typeval1[] = ' f1.cw_vegetarian=1 OR f1.cw_vegan=1 ';*/
                        } else {
                                $val = $nutrition_types_array[$n];
                                $res_nut_type .= 'IFNULL(f.' . $val . ',0) as f_' . $val . ',';
                                $res_nut_type1 .= 'IFNULL(f1.' . $val . ',0) as f1_' . $val . ',';
                                $nut_typeval[] = 'f.' . $val . '<> 0 ';
                                $nut_typeval1[] = 'f1.' . $val . '<> 0 ';
                        }
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
                $res_int_type .= 'IFNULL(f.' . $intal . ',0) as f_' . $intal . ',';
                $res_int_type1 .= 'IFNULL(f1.' . $intal . ',0) as f1_' . $intal . ',';
                $int_typeval[] = 'f.' . $intal . '<> 1 ';
                $int_typeval1[] = 'f1.' . $intal . '<> 1 ';
        }

        if (!empty($int_typeval)) {
                $res_int_typeval = implode('AND ', $int_typeval);
                $res_int_typeval1 = implode('AND ', $int_typeval1);

                $res_int_typeval = " AND (" . $res_int_typeval . ")";
                $res_int_typeval1 = " AND (" . $res_int_typeval1 . ")";
        }

        //$select_field_int_typeval = implode(',', $res_int_type);
        //$select_field_int_typeval1 = implode(',', $res_int_type1);
}
//echo '<pre>';print_r($res_int_typeval);
$synonyms_exclude = $exclude;

/* $available_time_array = array('little' => 'MINUTE(mins.preparation_time) <40', 'normal' => 'MINUTE(mins.preparation_time) >40 AND MINUTE(mins.preparation_time) <60', 'much' => 'MINUTE(mins.preparation_time) >60'); */
//$available_time_array = array('little' => 40, 'normal' => 60, 'much' => 200);
//$available_time_condi = 'MINUTE(mins.preparation_time) <=' . $available_time_array[$available_time];
$available_time_array = array('little' => 'AND MINUTE(mins.preparation_time) <= 45 ', 'normal' => '', 'much' => 'AND MINUTE(mins.preparation_time) >15');
//$available_time_condi = $available_time_array[$available_time];
$available_time_condi = ' ';


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
echo 'Weeknum :' . $weeknum . '</br>';


$time_con=array();
if(strcasecmp($available_time,'little')==0)
{
	$time_con=array('breakfast'=>20,'lunch'=>45,'dinner'=>30,'snack'=>20);
}
else if(strcasecmp($available_time,'much')==0)
{
	$time_con=array('breakfast'=>5,'lunch'=>15,'dinner'=>10,'snack'=>5);
}


$time_ideal_value=array();
if(strcasecmp($available_time,'little')==0)
{
	$time_ideal_value=array('breakfast'=>5,'lunch'=>15,'dinner'=>10,'snack'=>5);
}

if(strcasecmp($available_time,'normal')==0)
{
	$time_ideal_value=array('breakfast'=>20,'lunch'=>45,'dinner'=>30,'snack'=>20);
}
else if(strcasecmp($available_time,'much')==0)
{
	$time_ideal_value=array('breakfast'=>20,'lunch'=>45,'dinner'=>30,'snack'=>20);
}

//echo "time for all meal:<pre>";print_r($time_con);
$query1 = "SELECT meal_id, COUNT( * ) AS total_ingredient
FROM " . $prefix . "meal_ingredients
GROUP BY meal_id";
//echo $query1."<br>";
$res1 = $wpdb->get_results($query1);

//echo "<pre>";print_r($res1);
$synonyms_que = '';
if (!empty($nutrion_plan_detail['exclude'])) {
        $synonyms_que = 'AND NOT FIND_IN_SET( f.synonym_of,  "' . $nutrion_plan_detail['exclude'] . '" ) ';
        $synonyms_que1 = 'AND NOT FIND_IN_SET(f1.synonym_of, "' . $nutrion_plan_detail['exclude'] . '")) OR mi.is_ommitable = 1)';
}

//echo "<pre>";print_r($res1);
/* check main ingeredient compatible */
$query3 = "select um.meal_id as meal_id,group_concat(replace(um.name,',','')) as ing_names,group_concat(f.id) as compatible_ingredients,count(*) as main_count from " . $prefix . "meal_ingredients um, " . $prefix . "foods f "
        . ", " . $prefix . "meal_instructions mins where um.name=f.name  " . $res_nut_typeval . $res_int_typeval . $synonyms_que . " AND mins.meal_id = um.meal_id " . $available_time_condi . "
group by um.meal_id";
//echo $query3 . "<br><br>";
$res3 = $wpdb->get_results($query3);

//echo "<pre>";print_r($res3);
/* exchangable ingeredient check */
//INNER JOIN  " . $prefix . "meal_instructions mins ON mins.meal_id=meal.id ".$available_time_condi."
/* changed by krutika */
/*$q2 = "select meal.meal_id,meal.name as ingredient_name ,exchangable_with_ingredient,group_concat(f.name) as fname,group_concat(f.id) as comptiable_sub_id,count(*)
from " . $prefix . "meal_ingredients meal, " . $prefix . "foods f ," . $prefix . "meal_instructions mins
where not find_in_set(replace(meal.name,',',''),(select group_concat(replace(um.name,',',''))
from " . $prefix . "meal_ingredients um, " . $prefix . "foods f where um.meal_id=meal.meal_id and um.name=f.name " . $res_nut_typeval . $res_int_typeval . "))
and FIND_IN_SET(f.id, exchangable_with_ingredient)
and exchangable_with_ingredient<>''
" . $res_nut_typeval . $res_int_typeval . $synonyms_que . "
AND mins.meal_id = meal.meal_id " . $available_time_condi . "
group by meal.name, meal.meal_id";*/
//echo "new query";
/*$q2= "SELECT mi.meal_id ,mi.NAME AS ingredient_name,mi.exchangable_with_ingredient,mi.is_ommitable,
group_concat(CASE WHEN FIND_IN_SET(f.id, exchangable_with_ingredient) THEN f.name ELSE NULL END) AS fname ,
group_concat(CASE WHEN FIND_IN_SET(f.id, exchangable_with_ingredient) THEN f.id ELSE NULL END) AS comptiable_sub_id
FROM up_meal_ingredients mi LEFT JOIN up_foods f ON (FIND_IN_SET(f.id, mi.exchangable_with_ingredient) OR is_ommitable = 1)
LEFT JOIN up_meal_instructions mins on mi.id = mins.meal_id WHERe (f.id NOT IN (SELECT f1.id FROM up_meal_ingredients cmi LEFT JOIN up_foods f1
ON cmi.NAME = f1.NAME WHERE cmi.meal_id=mi.meal_id " .$res_nut_typeval1.$res_int_typeval1.$synonyms_que1. $available_time_condi.$res_nut_typeval.$res_int_typeval." GROUP BY mi.NAME,mi.meal_id";*/
$q2= "SELECT mi.meal_id ,mi.NAME AS ingredient_name,mi.exchangable_with_ingredient,mi.is_ommitable, group_concat(f.name) AS fname, group_concat(f.id) AS comptiable_sub_id ".
" FROM up_meal_ingredients mi LEFT JOIN up_foods f ON FIND_IN_SET(f.id, mi.exchangable_with_ingredient) ".
" LEFT JOIN up_meal_instructions mins on mi.id = mins.meal_id WHERE (mi.is_ommitable = 1 or (mi.exchangable_with_ingredient <> '0' and mi.exchangable_with_ingredient <> '' and mi.exchangable_with_ingredient is not null)) "
.$available_time_condi.$res_nut_typeval.$res_int_typeval." GROUP BY mi.NAME,mi.meal_id";

/*$q2 = "select meal.meal_id,meal.name as ingredient_name ,exchangable_with_ingredient,group_concat(CASE WHEN FIND_IN_SET(f.id, exchangable_with_ingredient) THEN f.name ELSE NULL END) AS fname,group_concat(CASE WHEN FIND_IN_SET(f.id, exchangable_with_ingredient) THEN f.id ELSE NULL END) as comptiable_sub_id
from " . $prefix . "meal_ingredients meal, " . $prefix . "foods f ," . $prefix . "meal_instructions mins
where (not find_in_set(replace(meal.name,',',''),(select group_concat(replace(um.name,',',''))
from " . $prefix . "meal_ingredients um, " . $prefix . "foods f where um.meal_id=meal.meal_id and um.name=f.name
 " . $res_nut_typeval . $res_int_typeval ." )) OR is_ommitable = 1)
and (FIND_IN_SET(f.id, exchangable_with_ingredient) OR is_ommitable = 1)
" . $res_nut_typeval . $res_int_typeval . $synonyms_que1 . "
AND mins.meal_id = meal.meal_id " . $available_time_condi . "
group by meal.name, meal.meal_id";*/
//echo $q2."<br><br>";
$res2 = $wpdb->get_results($q2);
//echo "<pre>";print_r($res2);
/* changed by krutika */

$count=$wpdb->get_var("select count(id) from ".$wpdb->prefix."order_meals where order_id=".$order_id);
if($count!=0){
	$query_del="DELETE from ".$wpdb->prefix."order_meals where order_id=".$order_id;
	$wpdb->query($query_del);
}

//exit;
$res_meals = $exchangable_foods = $prioritise_meals =$up_order_meals = array();
foreach ($res1 as $k1 => $r1) {
        if (!empty($res3)) {
                foreach ($res3 as $r3) {
                        if (($r1->meal_id == $r3->meal_id) && ($r1->total_ingredient != $r3->main_count)) {
                                $temp = 0;
                                $temp_meal_id="";
                                foreach ($res2 as $r) {
                                        if ($r->meal_id == $r1->meal_id) {
											if(!in_array($r->ingredient_name, explode(',',$r3->ing_names))){
												$exchangables = explode(',',$r->exchangable_with_ingredient);
												if(!empty($exchangables)) {
													foreach($exchangables as $ei) {
														if(!empty($r->comptiable_sub_id)) {
															if(in_array($ei, explode(',',$r->comptiable_sub_id))){
															    $temp++;
																$temp_meal_id .= ",".$ei;
																break;
															}
														}
													}
												}
											}
                                        }
                                }

                                $final_count = $r3->main_count + $temp;
                                if( $r1->meal_id == 15) {
                                    echo "main_count:".$r3->main_count;
                                    echo "final_count:".$final_count;
                                }
								//echo "final count:".$final_count."temp:".$temp."</br>";
                                if ($final_count == $r1->total_ingredient) {
                                        $res_meals[] = $r1->meal_id;
                                        $up_order_meals['order_id']=$order_id;
										$up_order_meals['meal_id']=$r3->meal_id;
										$up_order_meals['ingredient_ids']=$r3->compatible_ingredients.$temp_meal_id;
										//echo "<pre>";print_r($up_order_meals);
										$count=$wpdb->get_var("select count(id) from up_order_meals where order_id=".$order_id." AND meal_id=".$up_order_meals['meal_id']);
										if($count==0){

											$query_insert="insert into up_order_meals (order_id,meal_id,ingredient_ids,exchangble) values(".$order_id.",".$up_order_meals['meal_id'].",'".$up_order_meals['ingredient_ids']."',1)";
											$wpdb->query($query_insert);
										}
                                }
                        } else if (($r1->meal_id == $r3->meal_id) && ($r1->total_ingredient == $r3->main_count)) {
                                //echo "=else --";
                                $final_count = $r3->main_count;
                                //$res_meals[] = $r1->meal_id;
                                $prioritise_meals[] = $r1->meal_id;
                                $up_order_meals['order_id']=$order_id;
                                $up_order_meals['meal_id']=$r3->meal_id;
                                $up_order_meals['ingredient_ids']=$r3->compatible_ingredients;
                                //echo "<pre>";print_r($up_order_meals);

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
echo 'count'.count($final_meal);
$final_meal = implode(',', $final_meal);
echo ' final ' . $final_meal . '<br><br>';
//echo 'final_meals ';


//print_r($exchangable_foods);
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
}
else{
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
}else{
 $f1_status = ",1 AS f1_status";
}

/* get plan data */
$planData = get_post($plan);
echo 'Plan : ' . $planData->post_title . '</br>';

/* get chosen plan period (4 or 12) */
$period = get_post_meta($plan, 'plan_period', true);
echo 'Plan perid: ' . $period . '</br>';

$tee = 0;

$PAL = 1.20; //Resting Metabolic Rate
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
		//echo "(655.1 + (9.6 * ".$current_weight.") + (1.8 * ".$height.") - (4.7 * ".$age."))</br></br>";
        $RMR = (655.1 + (9.6 * $current_weight) + (1.8 * $height) - (4.7 * $age)); //-$PAL*(100-25/100);
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
        $RMR = (66.47 + (13.7 * $current_weight) + (5 * $height) - (6.8 * $age)); //-$PAL*(100-25);
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

$tee = $RMR * $PAL; //Total Energy Expenditure
echo 'RMR: ' . $RMR . '</br>';
echo 'TEE: ' . $tee . '</br>';

$NPES = 0; //Nutrition Plan Energy Supply
//Adjust Total Energy Expenditure per day
$four_week_plan = array('mon' => -35, 'tue' => -35, 'wed' => -35, 'thu' => -35, 'fri' => -35, 'sat' => -35, 'sun' => 10);
$twelve_week_plan = array('mon' => -25, 'tue' => -25, 'wed' => -10, 'thu' => -25, 'fri' => -25, 'sat' => -25, 'sun' => 10);
$weektype = array(12 => $twelve_week_plan, 4 => $twelve_week_plan);

$day = strtolower(date('D'));

$day_value = $weektype[$period][$day];

//$weeknum = strtolower(date('W'));
// Adjust Total Energy Expenditure per week
//Weight of the last week in kg - (sum of kcal deficit of the entire week * (1/7000))
$plan_week = $weektype[$period];

/* sunday is cheat day -- 0% every uneven week & +10% every even week */
if ($weeknum % 2 == 0) {
        $sun_percentage = -($plan_week['sun']) / 100;
} else {
        $sun_percentage = 0;
}
//echo $current_weight." - "."(".$tee." * (((".-$plan_week['mon'].") / 100) + ((".-$plan_week['tue'].") / 100) + ((".-$plan_week['wed'].") / 100) + ((".-$plan_week['thu'].") / 100) + ((".-$plan_week['fri'].") / 100) + ((".-$plan_week['sat'].") / 100) + (".$sun_percentage.")) * ((1 / 7000)))</br></br></br></br>";
$NPES_perweek = $current_weight - ($tee * (((-$plan_week['mon']) / 100) + ((-$plan_week['tue']) / 100) + ((-$plan_week['wed']) / 100) + ((-$plan_week['thu']) / 100) + ((-$plan_week['fri']) / 100) + ((-$plan_week['sat']) / 100) + ($sun_percentage)) * ((1 / 7000)));
//echo $NPES_perweek;
echo 'Adjust Total Energy Expenditure per week (NPES): ' . number_format($NPES_perweek, 2) . 'kg </br>';

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

// C.7   exclude meals that have +/- 15% kcal


/* Meals priority (weighted sum of % variance) */
//call sp for all micro value calculation
//$priority_micros = $wpdb->get_results("CALL prioritise_val('" . $final_meal . "');");

$priority_micros = $wpdb->get_results("CALL prioritise_val_v2('" . $order_id . "');", OBJECT_K);
//echo "<pre>";print_r($priority_micros);

//price level and fat burner calculation
//$priority_micros_price = $wpdb->get_results("CALL prioritise_micro('" . $final_meal . "');", OBJECT_K);
//echo "repetition of meal";
/* Repetition of meat and fish */
$fish_meals = $meat_meals = array();
if (in_array('nospecial', $nutrition_type)) {
	//echo "nospecial";
       // $meat_meals = $wpdb->get_results('call meat_and_fish_meals("' . $final_meal . '","fleisch")', OBJECT_K);

		$meat_meals = $wpdb->get_results('call meat_and_fish_meals("' . $final_meal . '","Fleisch")', OBJECT_K);
		//echo "meat meals<pre>";print_r($meat_meals);
        $fish_meals = $wpdb->get_results('call meat_and_fish_meals("' . $final_meal . '","Fisch")', OBJECT_K);
        //echo "fish meals<pre>";print_r($fish_meals);
      //  echo 'test111';
}
if (in_array('pescetarian', $nutrition_type)) {
        $fish_meals = $wpdb->get_results('call meat_and_fish_meals("' . $final_meal . '","Fisch")', OBJECT_K);
        //$fish_meals = $wpdb->get_results('call meat_and_fish_meals("74","Fisch")', OBJECT_K);
        //print_r($fish_meals);
        //print_r(array_keys($fish_meals));
}
// C.7   exclude meals that have +/- 15% kcal

/* par day NPES and kcal sum value breakfast ,lunch,dinner,snack */
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

$price_Quality=1;
if(strcasecmp($where_food_buy,"cheap")==0)
{
	$price_Quality=1;
}
else if(strcasecmp($where_food_buy,"normal")==0)
{
	$price_Quality=2;
}
else if(strcasecmp($where_food_buy,"premium")==0)
{
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

//echo "price/Quality:".$price_Quality."</br>";
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

$breakfast_variance=$lunch_variance=$dinner_variance=$snack_variance=$variance = $micros_array = array();
$html = '';
$meal_portion=1;
//echo "priority micros :<pre>";print_r($priority_micros);

/*foreach ($days_npes_cal as $d => $v) {
		echo "day:".$d." breakfast kcal:".$v['breakfast'][0]."</br>";
		$daykal = @round(((abs($v['breakfast'][0] - 200)) / $v['breakfast'][0]) * 150, 5);
		echo $daykal;
}
exit;*/
//$weight_variance=0.1;
if(!empty($priority_micros))
{
	$html .='<table id="outer">';
	$html .='<tr><td>';
	$html .='<table border="1">';
	$html .='<tr>';
	$html .='<td><strong>Ideal values</strong></td><td><strong>next meal</strong></td>';
	$html .='</tr>';
	foreach ($ideal_values_for_meal as $k => $i) {
			$html .='<tr>';
			if($k == 'Kilokalorien')
			{
				$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . $days_npes_cal['mon']['breakfaset'][0].",".$days_npes_cal['mon']['lunch'][0].",".$days_npes_cal['mon']['dinner'][0].",".$days_npes_cal['mon']['pre_dinner_snack'][0]. '</td>';
			}
            else if($k == 'Gesättigte Fettsäuren' || $k == 'Einfach ungesättigte Fettsäuren' || $k == 'Mehrfach ungesättigte Fettsäuren' || $k == 'Cystein' || $k == 'Leucin' || $k == 'Lysin' || $k == 'Threonin' || $k == 'Tryptophan' || $k == 'Valin') {
    			$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' .  ($cur_weight * $i * ($meals_plan['breakfast']/100))  . '</td>';
            } else if($k != 'Protein' && $k !='Fett' && $k!='Kohlenhydrate' && $k != 'Vorbereitungszeit' && $k != 'Preis/Qualität') {
				
	    		$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . ($i * ($meals_plan["breakfast"]/100)) .',' . ($i * ($meals_plan["lunch"]/100)) . ',' . ($i * ($meals_plan["dinner"]/100)). ',' . ($i * ($meals_plan["pre_dinner_snack"]/100)). '</td>';
            }
            else if($k=='Vorbereitungszeit')
            {
				$html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . $i.'-'. implode(',',$time_ideal_value) . '</td>';
			}
            else
            {
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
        #echo "<br />Partial Meals: <pre>"; print_r($part_meals);

        $html .='<tr><td title="Partly compatible">Partial</td><td>0.5</td></tr>';
    }

    //if ($plan == 95 || $plan == 166) {
        $html .='<tr><td title="Fat killer">Fat kill</td><td>-0.5</td></tr>';
    //}

	$html .='<tr><td colspan="2">% Variance Sum</td></tr>';
	$html .='</table></td>';

	foreach ($priority_micros as $p => $pm) {
		//echo "in for each<pre>";print_r($pm);
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
			if($meal_category == 1)
			{
				$breakfast_id=$pm->id;
				$meal_portion=$meals_plan['breakfast']/100;
				$meal_type = 'breakfast';
                $kal = @round(((abs($days_npes_cal['mon']['breakfast'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['breakfast'][0]) * 150, 5);
                $time_value=$time_ideal_value['breakfast'];
              //  echo "in breakfast time".$time_value;
                
			} else if($meal_category == 2)
			{
				$lunch_id=$pm->id;
				$meal_portion=$meals_plan['lunch']/100;
				$meal_type = 'lunch';
                $kal = @round(((abs($days_npes_cal['mon']['lunch'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['lunch'][0]) * 150, 5);
                $time_value=$time_ideal_value['lunch'];
                
			} else if($meal_category == 3)
			{
				$dinner_id=$pm->id;
				$meal_portion=$meals_plan['dinner']/100;
				$meal_type = 'dinner';
                $kal = @round(((abs($days_npes_cal['mon']['dinner'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['dinner'][0]) * 150, 5);
                $time_value=$time_ideal_value['dinner'];
                
			} else if($meal_category == 4)
			{
				$snack_id=$pm->id;
				$meal_portion=$meals_plan['pre_dinner_snack']/100;
				$meal_type = 'snack';
                $kal = @round(((abs($days_npes_cal['mon']['pre_dinner_snack'][0] - $pm->Kilokalorien)) / $days_npes_cal['mon']['pre_dinner_snack'][0]) * 150, 5);
                $time_value=$time_ideal_value['snack'];
			}

			if ($mc != 0) {
				$html .='</table></td>';
			}

			$html .='<td><table border="1">';
			$html .='<tr>';
			$html .='<td title="' . $pm->name . ' ' . $pm->id . '"><strong> ' . substr($pm->name, 0, 15) . '</strong></td><td><strong>diff vs.' . $pm->id . ' (' . $meal_type . ') </strong></td>';
			#$html .='<td>sum</td>';
			$html .='</tr>';

			$micros_array[$pm->id]['id'] = $pm->id;
            $micros_array[$pm->id]['kcal']=$pm->Kilokalorien;



			//$avg_kcal = @round($pm->Kilokalorien/$pm->total_ingredient,2);
			//$kcal = @round(($pm->Kilokalorien/ $ideal_values_for_meal['Kilokalorien']) , 2);

			$html .='<tr><td>' . @round($pm->Kilokalorien, 4) . '</td><td>' . $kal . '%</td></tr>';

			// Kcal array


			$pr_fat_carb = $pm->Protein + $pm->Fett + $pm->Kohlenhydrate;
			$avg_Protein = @round($pm->Protein / $pr_fat_carb * 100, 2);
			$Protein = @round(abs($ideal_values_for_meal['Protein'] - $avg_Protein), 5);
			$sum +=$Protein;
			$html .='<tr><td>' . $avg_Protein . '%</td><td>' . $Protein . '%</td></tr>';
			$micros_array[$pm->id]['Protein'] = $avg_Protein;
			$micros_array[$pm->id]['Protein_variance'] = $Protein;
			//$protein_diff = protein_cal($avg_Protein);


			$avg_Fett = @round($pm->Fett / $pr_fat_carb * 100, 2);
			$Fett = @round(abs($ideal_values_for_meal['Fett'] - $avg_Fett), 5);
			$sum +=$Fett;
			$html .='<tr><td>' . $avg_Fett . '%</td><td>' . $Fett . '%</td></tr>';
			$micros_array[$pm->id]['Fett'] = $avg_Fett;
            $micros_array[$pm->id]['Fett_variance'] = $Fett;

			//$fatt_diff = fatt_cal($avg_Fett);

			$avg_Kohlenhydrate = @round($pm->Kohlenhydrate / $pr_fat_carb * 100, 5);
			$Kohlenhydrate = @round(abs($ideal_values_for_meal['Kohlenhydrate'] - $avg_Kohlenhydrate), 5);
			$sum +=$Kohlenhydrate;
			$html .='<tr><td>' . $avg_Kohlenhydrate . '%</td><td>' . $Kohlenhydrate . '%</td></tr>';
			$micros_array[$pm->id]['Kohlenhydrate'] = $avg_Kohlenhydrate;
            $micros_array[$pm->id]['Kohlenhydrate_variance'] = $Kohlenhydrate;

			//$carbo_diff = carbo_cal($avg_Kohlenhydrate);
			//$diff = incl_meal($days_npes_cal,$pm->Kilokalorien, $avg_Protein, $avg_Fett, $avg_Kohlenhydrate);
			//echo "<pre>";print_r($diff);
			

			$temp_price_level = @round($pm->price_avgr, 4);

			$percentage = 15;
			if ($plan == 162) {
					// If chosen plan = Sparfuchs then 20%  variance.
					$percentage = 20;
			}
			//echo "time value".$time_value."</br>";
			//echo "min".date("i",strtotime($pm->preparation_time))."</br>";
			$prepare_time = @round(abs((date("i",strtotime($pm->preparation_time))) - $time_value) * ($percentage/100), 5);
			//$prepare_time = @date('i', strtotime($pm->preparation_time));
			$html .='<tr><td>' . $pm->preparation_time . '</td><td>' . $prepare_time . '%</td></tr>';
			$sum +=$prepare_time;
			
			$price_level = @round(abs($temp_price_level - $ideal_values_for_meal['Preis/Qualität']) * $percentage, 5);
			$sum +=$price_level;
			$html .='<tr><td>' . $temp_price_level . '</td><td>' . $price_level . '%</td></tr>';

			$variance=0.1;
			if($plan == 95 || $plan == 166){
				$variance=1;
			}
			$ge_f = @round(abs($pm->{'Gesättigte Fettsäuren'} - ($cur_weight * $ideal_values_for_meal['Gesättigte Fettsäuren'] * $meal_portion)) * 0.1, 5);
			$sum +=$ge_f;
			$html .='<tr><td>' . @round($pm->{'Gesättigte Fettsäuren'}, 4) . '</td><td>' . $ge_f . '%</td></tr>';

			$ein_un_f = @round(abs($pm->{'Einfach ungesättigte Fettsäuren'} - ($cur_weight * $ideal_values_for_meal['Einfach ungesättigte Fettsäuren'] * $meal_portion)) * $variance, 5);
			$sum +=$ein_un_f;
			$html .='<tr><td>' . @round($pm->{'Einfach ungesättigte Fettsäuren'}, 4) . '</td><td>' . $ein_un_f . '%</td></tr>';

			$mn_un_f = @round(abs($pm->{'Mehrfach ungesättigte Fettsäuren'} - ($cur_weight * $ideal_values_for_meal['Mehrfach ungesättigte Fettsäuren'] * $meal_portion)) * $variance, 5);
			$sum +=$mn_un_f;
			$html .='<tr><td>' . @round($pm->{'Mehrfach ungesättigte Fettsäuren'}, 4) . '</td><td>' . $mn_un_f . '%</td></tr>';
						
			$Vitamin_A = calculate_micro_value($pm->{'Vitamin A'},$ideal_values_for_meal['Vitamin A'],$meal_portion,0.1);
			//$Vitamin_A = @round(abs($pm->{'Vitamin A'} - $ideal_values_for_meal['Vitamin A']) * 0.1, 5);
			$sum +=$Vitamin_A;
			$html .='<tr><td>' . @round($pm->{'Vitamin A'}, 4) . '</td><td>' . $Vitamin_A . '%</td></tr>';

			$Vitamin_C = calculate_micro_value($pm->{'Vitamin C'},$ideal_values_for_meal['Vitamin C'],$meal_portion,0.1);
			//$Vitamin_C = @round(abs($pm->{'Vitamin C'} - $ideal_values_for_meal['Vitamin C']) * 0.1, 5);
			$sum +=$Vitamin_C;
			$html .='<tr><td>' . @round($pm->{'Vitamin C'}, 4) . '</td><td>' . $Vitamin_C . '%</td></tr>';

			$Vitamin_D = calculate_micro_value($pm->{'Vitamin D'},$ideal_values_for_meal['Vitamin D'],$meal_portion,0.1);
			//$Vitamin_D = @round(abs($pm->{'Vitamin D'} - $ideal_values_for_meal['Vitamin D']) * 0.1, 5);
			$sum +=$Vitamin_D;
			$html .='<tr><td>' . @round($pm->{'Vitamin D'}, 4) . '</td><td>' . $Vitamin_D . '%</td></tr>';

			$Vitamin_E = calculate_micro_value($pm->{'Vitamin E'},$ideal_values_for_meal['Vitamin E'],$meal_portion,0.1);
			//$Vitamin_E = @round(abs($pm->{'Vitamin E'} - $ideal_values_for_meal['Vitamin E']) * 0.1, 5);
			$sum +=$Vitamin_E;
			$html .='<tr><td>' . @round($pm->{'Vitamin E'}, 4) . '</td><td>' . $Vitamin_E . '%</td></tr>';

			$Vitamin_K = calculate_micro_value($pm->{'Vitamin K'},$ideal_values_for_meal['Vitamin K'],$meal_portion,0.1);
			//$Vitamin_K = @round(abs($pm->{'Vitamin K'} - $ideal_values_for_meal['Vitamin K']) * 0.1, 5);
			$sum +=$Vitamin_K;
			$html .='<tr><td>' . @round($pm->{'Vitamin K'}, 4) . '</td><td>' . $Vitamin_K . '%</td></tr>';

			$Vitamin_B1 = calculate_micro_value($pm->{'Vitamin B1'},$ideal_values_for_meal['Vitamin B1'],$meal_portion,0.1);
			//$Vitamin_B1 = @round(abs($pm->{'Vitamin B1'} - $ideal_values_for_meal['Vitamin B1']) * 0.1, 5);
			$sum +=$Vitamin_B1;
			$html .='<tr><td>' . @round($pm->{'Vitamin B1'}, 4) . '</td><td>' . $Vitamin_B1 . '%</td></tr>';

			$Vitamin_B2 = calculate_micro_value($pm->{'Vitamin B2'},$ideal_values_for_meal['Vitamin B2'],$meal_portion,0.1);
			//$Vitamin_B2 = @round(abs($pm->{'Vitamin B2'} - $ideal_values_for_meal['Vitamin B2']) * 0.1, 5);
			$sum +=$Vitamin_B2;
			$html .='<tr><td>' . @round($pm->{'Vitamin B2'}, 4) . '</td><td>' . $Vitamin_B2 . '%</td></tr>';

			$Vitamin_B6 = calculate_micro_value($pm->{'Vitamin B6'},$ideal_values_for_meal['Vitamin B6'],$meal_portion,0.1);
			//$Vitamin_B6 = @round(abs($pm->{'Vitamin B6'} - $ideal_values_for_meal['Vitamin B6']) * 0.1, 5);
			$sum +=$Vitamin_B6;
			$html .='<tr><td>' . @round($pm->{'Vitamin B6'}, 4) . '</td><td>' . $Vitamin_B6 . '%</td></tr>';

			$Vitamin_B12 = calculate_micro_value($pm->{'Vitamin B12'},$ideal_values_for_meal['Vitamin B12'],$meal_portion,0.1);
			//$Vitamin_B12 = @round(abs($pm->{'Vitamin B12'} - $ideal_values_for_meal['Vitamin B12']) * 0.1, 5);
			$sum +=$Vitamin_B12;
			$html .='<tr><td>' . @round($pm->{'Vitamin B12'}, 4) . '</td><td>' . $Vitamin_B12 . '%</td></tr>';

			$Biotin = calculate_micro_value($pm->{'Biotin'},$ideal_values_for_meal['Biotin'],$meal_portion,0.1);
			//$Biotin = @round(abs($pm->{'Biotin'} - $ideal_values_for_meal['Biotin']) * 0.1, 5);
			$sum +=$Biotin;
			$html .='<tr><td>' . @round($pm->{'Biotin'}, 4) . '</td><td>' . $Biotin . '%</td></tr>';

			$Folsaure = calculate_micro_value($pm->{'Folsäure'},$ideal_values_for_meal['Folsäure'],$meal_portion,0.1);
			//$Folsaure = @round(abs($pm->{'Folsäure'} - $ideal_values_for_meal['Folsäure']) * 0.1, 5);
			$sum +=$Folsaure;
			$html .='<tr><td>' . @round($pm->{'Folsäure'}, 4) . '</td><td>' . $Folsaure . '%</td></tr>';

			$Niacin = calculate_micro_value($pm->{'Niacin'},$ideal_values_for_meal['Niacin'],$meal_portion,0.1);
			//$Niacin = @round(abs($pm->{'Niacin'} - $ideal_values_for_meal['Niacin']) * 0.1, 5);
			$sum +=$Niacin;
			$html .='<tr><td>' . @round($pm->{'Niacin'}, 4) . '</td><td>' . $Niacin . '%</td></tr>';

			$Panthotensaure = calculate_micro_value($pm->{'Panthotensäure'},$ideal_values_for_meal['Panthotensäure'],$meal_portion,0.1);
			//$Panthotensaure = @round(abs($pm->{'Panthotensäure'} - $ideal_values_for_meal['Panthotensäure']) * 0.1, 5);
			$sum +=$Panthotensaure;
			$html .='<tr><td>' . @round($pm->{'Panthotensäure'}, 4) . '</td><td>' . $Panthotensaure . '%</td></tr>';

			$Calcium = calculate_micro_value($pm->{'Calcium [Ca]'},$ideal_values_for_meal['Calcium [Ca]'],$meal_portion,0.1);
			//$Calcium = @round(abs($pm->{'Calcium [Ca]'} - $ideal_values_for_meal['Calcium [Ca]']) * 0.1, 5);
			$sum +=$Calcium;
			$html .='<tr><td>' . @round($pm->{'Calcium [Ca]'}, 4) . '</td><td>' . $Calcium . '%</td></tr>';

			$Chlor = calculate_micro_value($pm->{'Chlor [Cl]'},$ideal_values_for_meal['Chlor [Cl]'],$meal_portion,0.1);
			//$Chlor = @round(abs($pm->{'Chlor [Cl]'} - $ideal_values_for_meal['Chlor [Cl]']) * 0.1, 5);
			$sum +=$Chlor;
			$html .='<tr><td>' . @round($pm->{'Chlor [Cl]'}, 4) . '</td><td>' . $Chlor . '%</td></tr>';

			$Kalium = calculate_micro_value($pm->{'Kalium [K]'},$ideal_values_for_meal['Kalium [K]'],$meal_portion,0.1);
			//$Kalium = @round(abs($pm->{'Kalium [K]'} - $ideal_values_for_meal['Kalium [K]']) * 0.1, 5);
			$sum +=$Kalium;
			$html .='<tr><td>' . @round($pm->{'Kalium [K]'}, 4) . '</td><td>' . $Kalium . '%</td></tr>';

			$Magnesium = calculate_micro_value($pm->{'Kalium [K]'},$ideal_values_for_meal['Kalium [K]'],$meal_portion,0.1);
			//$Magnesium = @round(abs($pm->{'Magnesium [Mg]'} - $ideal_values_for_meal['Magnesium [Mg]']) * 0.1, 5);
			$sum +=$Magnesium;
			$html .='<tr><td>' . @round($pm->{'Magnesium [Mg]'}, 4) . '</td><td>' . $Magnesium . '%</td></tr>';

			$Natrium = calculate_micro_value($pm->{'Natrium [Na]'},$ideal_values_for_meal['Natrium [Na]'],$meal_portion,0.1);
			//$Natrium = @round(abs($pm->{'Natrium [Na]'} - $ideal_values_for_meal['Natrium [Na]']) * 0.1, 5);
			$sum +=$Natrium;
			$html .='<tr><td>' . @round($pm->{'Natrium [Na]'}, 4) . '</td><td>' . $Natrium . '%</td></tr>';

			$Phosphor = calculate_micro_value($pm->{'Phosphor [P]'},$ideal_values_for_meal['Phosphor [P]'],$meal_portion,0.1);
			//$Phosphor = @round(abs($pm->{'Phosphor [P]'} - $ideal_values_for_meal['Phosphor [P]']) * 0.1, 5);
			$sum +=$Phosphor;
			$html .='<tr><td>' . @round($pm->{'Phosphor [P]'}, 4) . '</td><td>' . $Phosphor . '%</td></tr>';

			$Kupfer = calculate_micro_value($pm->{'Kupfer [Cu]'},$ideal_values_for_meal['Kupfer [Cu]'],$meal_portion,0.1);
			//$Kupfer = @round(abs($pm->{'Kupfer [Cu]'} - $ideal_values_for_meal['Kupfer [Cu]']) * 0.1, 5);
			$sum +=$Kupfer;
			$html .='<tr><td>' . @round($pm->{'Kupfer [Cu]'}, 4) . '</td><td>' . $Kupfer . '%</td></tr>';

			$Eisen = calculate_micro_value($pm->{'Eisen [Fe]'},$ideal_values_for_meal['Eisen [Fe]'],$meal_portion,0.1);
			//$Eisen = @round(abs($pm->{'Eisen [Fe]'} - $ideal_values_for_meal['Eisen [Fe]']) * 0.1, 5);
			$sum +=$Eisen;
			$html .='<tr><td>' . @round($pm->{'Eisen [Fe]'}, 4) . '</td><td>' . $Eisen . '%</td></tr>';

			$Fluor = calculate_micro_value($pm->{'Fluor [F]'},$ideal_values_for_meal['Fluor [F]'],$meal_portion,0.1);
			//$Fluor = @round(abs($pm->{'Fluor [F]'} - $ideal_values_for_meal['Fluor [F]']) * 0.1, 5);
			$sum +=$Fluor;
			$html .='<tr><td>' . @round($pm->{'Fluor [F]'}, 4) . '</td><td>' . $Fluor . '%</td></tr>';

			$Mangan = calculate_micro_value($pm->{'Mangan [Mn]'},$ideal_values_for_meal['Mangan [Mn]'],$meal_portion,0.1);
			//$Mangan = @round(abs($pm->{'Mangan [Mn]'} - $ideal_values_for_meal['Mangan [Mn]']) * 0.1, 5);
			$sum +=$Mangan;
			$html .='<tr><td>' . @round($pm->{'Mangan [Mn]'}, 4) . '</td><td>' . $Mangan . '%</td></tr>';

			$Jod = calculate_micro_value($pm->{'Jod [J]'},$ideal_values_for_meal['Jod [J]'],$meal_portion,0.1);
			//$Jod = @round(abs($pm->{'Jod [J]'} - $ideal_values_for_meal['Jod [J]']) * 0.1, 5);
			$sum +=$Jod;
			$html .='<tr><td>' . @round($pm->{'Jod [J]'}, 4) . '</td><td>' . $Jod . '%</td></tr>';

			$Zink = calculate_micro_value($pm->{'Zink [Zn]'},$ideal_values_for_meal['Zink [Zn]'],$meal_portion,0.1);
			//$Zink = @round(abs($pm->{'Zink [Zn]'} - $ideal_values_for_meal['Zink [Zn]']) * 0.1, 5);
			$sum +=$Zink;
			$html .='<tr><td>' . @round($pm->{'Zink [Zn]'}, 4) . '</td><td>' . $Zink . '%</td></tr>';

			$Cystein = @round(abs($pm->{'Cystein'} - ($cur_weight * $ideal_values_for_meal['Cystein'] * $meal_portion)) * 0.1, 5);
			$sum +=$Cystein;
			$html .='<tr><td>' . @round($pm->{'Cystein'}, 4) . '</td><td>' . $Cystein . '%</td></tr>';

			$Leucin = @round(abs($pm->{'Leucin'} - ($cur_weight * $ideal_values_for_meal['Leucin'] * $meal_portion)) * 0.1, 5);
			$sum +=$Leucin;
			$html .='<tr><td>' . @round($pm->{'Leucin'}, 4) . '</td><td>' . $Leucin . '%</td></tr>';

			$Lysin = @round(abs($pm->{'Lysin'} - ($cur_weight * $ideal_values_for_meal['Lysin'] * $meal_portion)) * 0.1, 5);
			$sum +=$Lysin;
			$html .='<tr><td>' . @round($pm->{'Lysin'}, 4) . '</td><td>' . $Lysin . '%</td></tr>';

			$Threonin = @round(abs($pm->{'Threonin'} - ($cur_weight * $ideal_values_for_meal['Threonin'] * $meal_portion)) * 0.1, 5);
			$sum +=$Threonin;
			$html .='<tr><td>' . @round($pm->{'Threonin'}, 4) . '</td><td>' . $Threonin . '%</td></tr>';

			$Tryptophan = @round(abs($pm->{'Tryptophan'} - ($cur_weight * $ideal_values_for_meal['Tryptophan'] * $meal_portion)) * 0.1, 5);
			$sum +=$Tryptophan;
			$html .='<tr><td>' . @round($pm->{'Tryptophan'}, 4) . '</td><td>' . $Tryptophan . '%</td></tr>';

			$Valin = @round(abs($pm->{'Valin'} - ($cur_weight * $ideal_values_for_meal['Valin'] * $meal_portion)) * 0.1, 5);
			$sum +=$Valin;
			$html .='<tr><td>' . @round($pm->{'Valin'}, 5) . '</td><td>' . $Valin . '%</td></tr>';

            // Add partial variance in total variance.
            $total_partial = 0;
            $partial_variance = 0;
            if (!empty($part_meals)) {
                if(array_key_exists($pm->id, $part_meals)){
                    $total_partial = $part_meals[$pm->id]->total_partial;
                    $partial_variance = ($total_partial * 2);
                }
            }
            if(!empty($part_nut_type_string) || !empty($part_tolarance_type_string)) {
                $sum = $sum + $total_partial;
    			$html .='<tr><td>' . $total_partial . '</td><td>' . $partial_variance . '%</td></tr>';
            }

            //if plan is fat killer, Is fatkiller = 1 should be reduced by 0,5%
            $total_fat = 0;
            $fat_variance = 0;
			//if ($plan == 95 || $plan == 166) {
                $total_fat = $pm->fatt;
                $fat_variance = - ($total_fat * 0.5);
                $sum = $sum + $fat_variance;
                $html .='<tr><td>' . $total_fat . '</td><td>' . $fat_variance . '%</td></tr>';
			//}


			if(strcasecmp($available_time,'little')==0) {
				if($meal_category == 1 && ($pm->preparation_time <= $time_con['breakfast']))
				{
					foreach ($days_npes_cal as $d => $v) {
						$daykal = @round(((abs($v['breakfast'][0] - $pm->Kilokalorien)) / $v['breakfast'][0]) * 150, 5);
					}
					$breakfast_variance[$breakfast_id]['meal_id']=$breakfast_id;
					//$breakfast_variance[$breakfast_id]['variance'] = $sum;
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
				}
				else if($meal_category == 2 && ($pm->preparation_time <= $time_con['lunch']))
				{
					$lunch_variance[$lunch_id]['meal_id']=$lunch_id;
					//$lunch_variance[$lunch_id]['variance'] = $sum;
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
				}
				else if($meal_category == 3 && ($pm->preparation_time <= $time_con['dinner']))
				{
					$dinner_variance[$dinner_id]['meal_id']=$dinner_id;
					//$dinner_variance[$dinner_id]['variance'] = $sum;
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
				}
				else if($meal_category == 4 && ($pm->preparation_time <= $time_con['snack']))
				{
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
			else if(strcasecmp($available_time,'much')==0){
				if($meal_category == 1 && ($pm->preparation_time >= $time_con['breakfast']))
				{
					$breakfast_variance[$breakfast_id]['meal_id']=$breakfast_id;
					//$breakfast_variance[$breakfast_id]['variance'] = $sum;
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
				}
				else if($meal_category == 2 && ($pm->preparation_time >= $time_con['lunch']))
				{
					$lunch_variance[$lunch_id]['meal_id']=$lunch_id;
					//$lunch_variance[$lunch_id]['variance'] = $sum;
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
				}
				else if($meal_category == 3 && ($pm->preparation_time >= $time_con['dinner']))
				{
					$dinner_variance[$dinner_id]['meal_id']=$dinner_id;
					//$dinner_variance[$dinner_id]['variance'] = $sum;
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
				}
				else if($meal_category == 4 && ($pm->preparation_time >= $time_con['snack']))
				{
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
			else
			{
				if($meal_category == 1)
				{
					$breakfast_variance[$breakfast_id]['meal_id']=$breakfast_id;
					//$breakfast_variance[$breakfast_id]['variance'] = $sum;
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
				}
				else if($meal_category == 2)
				{
					$lunch_variance[$lunch_id]['meal_id']=$lunch_id;
					//$lunch_variance[$lunch_id]['variance'] = $sum;
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
				}
				else if($meal_category == 3)
				{
					$dinner_variance[$dinner_id]['meal_id']=$dinner_id;
					//$dinner_variance[$dinner_id]['variance'] = $sum;
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
				}
				else if($meal_category == 4)
				{
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
echo $html;

//echo "<pre>";print_r($breakfast_variance);


function calculate_micro_value($meal_micro_value,$ideal_value,$meal_portion,$weight_variance)
{
	//echo "befor".$ideal_value;
	$ideal_value=$ideal_value*$meal_portion;
	//echo "after:ideal_value".$ideal_value."meal value:".$meal_micro_value."meal_portion".$meal_portion."weight variance".$weight_variance."</br>";
	$microcal=@round(abs((($meal_micro_value/$ideal_value * 100)-100)*$weight_variance),5);
	return $microcal;
}


function array_sort($variance_arr,$key1,$day,$percent)
{
	if (!empty($variance_arr)) {
       $sort = array();
		foreach($variance_arr as $k=>$v) {
			$sort['variance'][$day][$percent][$k] = $v['variance'][$day][$percent];
			$sort[$key1][$k] = $v[$key1];
		}
    	# sort by exchangble asc and then variance asc
    	array_multisort($sort[$key1],SORT_ASC, $sort['variance'][$day][$percent],SORT_ASC,$variance_arr);
	}
	return $variance_arr;
}

/* D. c)balance out the variance */
$rem_meals=$breakfast_meals=$pre_lunch_snack_meals=$lunch_meals=$dinner_meals=$pre_dinner_snack_meals=$fin_meals = array();

$pescetarian_fish_count = 0;
$nospecial_meat_fish_count = 0;
$breakfast_meals = select_balance_meals($nutrition_type, $breakfast_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'breakfast', $weeknum, null,$fin_meals, $pescetarian_fish_count, $nospecial_meat_fish_count);
$lunch_meals = select_balance_meals($nutrition_type, $lunch_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'lunch', $weeknum, $breakfast_meals,$fin_meals, $pescetarian_fish_count, $nospecial_meat_fish_count);
$dinner_meals = select_balance_meals($nutrition_type, $dinner_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'dinner', $weeknum, $lunch_meals,$fin_meals, $pescetarian_fish_count, $nospecial_meat_fish_count);
$pre_dinner_snack_meals = select_balance_meals($nutrition_type, $snack_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'pre_dinner_snack', $weeknum, $dinner_meals,$fin_meals, $pescetarian_fish_count, $nospecial_meat_fish_count);
if ($sweet_tooth == 'yes') {
    $pre_lunch_snack_meals = select_balance_meals($nutrition_type, $snack_variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $ideal_protien, $ideal_fat, $ideal_carbo, 'pre_lunch_snack', $weeknum, $pre_dinner_snack_meals,$fin_meals, $pescetarian_fish_count, $nospecial_meat_fish_count);
}

echo "<br />day wise NPES:<pre>";print_r($days_npes_cal);
echo "<br />micro values:<pre>";print_r($micros_array);
echo "<br />meat_meals:<pre>";print_r($meat_meals);
echo "<br />fish_meals:<pre>";print_r($fish_meals);

echo "<br />breakfast meal<pre>";print_r($breakfast_meals);
echo "<br />lunch meal<pre>";print_r($lunch_meals);
echo "<br />dinner meal<pre>";print_r($dinner_meals);
echo "<br />pre dinner meal<pre>";print_r($pre_dinner_snack_meals);
echo "<br />pre lunch meal<pre>";print_r($pre_lunch_snack_meals);

echo "<br />final meal<pre>";print_r($fin_meals);
echo "<br />pescetarian_fish_count:".$pescetarian_fish_count;
echo "<br />nospecial_meat_fish_count:".$nospecial_meat_fish_count;

exit;

function select_balance_meals($nutrition_type, $variance, $micros_array, $meat_meals, $fish_meals, $meals_plan, $days_npes_cal, $protin, $fat, $carbo, $meal_cat, $weeknum, $last_category_meals, &$fin_meals, &$pescetarian_fish_count, &$nospecial_meat_fish_count) {
    $micros_diff = array();

	if (!empty($micros_array)) {
    	foreach ($days_npes_cal as $d => $v) {
    		$variance=array_sort($variance,'exchangble',$d,$meals_plan[$meal_cat]);
            //echo "day wise ".$d." after sort meal<pre>";print_r($variance);
    		foreach ($variance as $k => $m) {
                $min_variance = $v[$meal_cat][1];
                $max_variance = $v[$meal_cat][2];

                $pro_diff = $protin;
                $fat_diff = $fat;
                $carb_diff = $carbo;

    		    if(!empty($last_category_meals) && !empty($last_category_meals[$d])){
    		        $last_category = key($last_category_meals[$d]);
                    $last_meal_id = key($last_category_meals[$d][$last_category]);
                    //echo "<br />last_category:". $last_category;
                    //echo "<br />last_meal_id:". $last_meal_id;

                    $new_kcal_meal_cat=$v[$last_category][0]-$micros_array[$last_meal_id]['kcal']+$v[$meal_cat][0];
                    $min_variance = @round($new_kcal_meal_cat - ($new_kcal_meal_cat * (15 / 100)), 2);
                    $max_variance = @round($new_kcal_meal_cat + ($new_kcal_meal_cat * (15 / 100)), 2);

                    $pro_diff = $protin - $micros_array[$last_meal_id]['Protein'] + $protin;
                    $fat_diff = $fat - $micros_array[$last_meal_id]['Fett'] + $fat;
                    $carb_diff = $carbo - $micros_array[$last_meal_id]['Kohlenhydrate'] + $carbo;
                }

                $dif = $new_kcal_meal_cat + $pro_diff + $fat_diff + $carb_diff;

    			$kcal = $micros_array[$m['meal_id']]['kcal'];

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

                    if(empty($fin_meals) || !in_array_r($new_meal_id, $fin_meals)) {
                        $final_meal_id = meals_output_per_weeks($nutrition_type, $d, $meal_cat, $weeknum, $micro_values, $pescetarian_fish_count, $nospecial_meat_fish_count);

                        if(!empty($final_meal_id) && $final_meal_id != 0) {
                            $micros_diff[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                            $fin_meals[$d][$meal_cat][$final_meal_id] = $final_meal_id;
                            break;
                        }
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
    /* Veggie meals */
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

        if(array_key_exists('Fleisch', $micro_array) && $nospecial_meat_fish_count < 4) {
            $nospecial_meat_fish_count++;
            return $micro_array['id'];
        }
        if(array_key_exists('Fisch', $micro_array) && $nospecial_meat_fish_count < 4) {
            $nospecial_meat_fish_count++;
            return $micro_array['id'];
        }
    }
    else if (in_array('pescetarian', $nutrition_type)) {
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

        if(array_key_exists('Fisch', $micro_array) && $pescetarian_fish_count < 4) {
            $pescetarian_fish_count++;
            return $micro_array['id'];
        }
    }
    else if (in_array('flexitarian', $nutrition_type)) {
        //skip the meal that are not compatible vagetarian attribute
        if($meal_category == 'breakfast') {
            if ($day == 'mon' || $day == 'thu') {
                if ($micro_array['is_veg'] == 0) {
                   return $micro_array['id'];
                }
            }
        }
        else if($meal_category == 'lunch') {
            if ($day == 'tue' || $day == 'fri') {
                if ($micro_array['is_veg'] == 0) {
                   return $micro_array['id'];
                }
            }
        }
        else if($meal_category == 'dinner') {
            if ($day == 'wed' || $day == 'sat') {
                if ($micro_array['is_veg'] == 0) {
                   return $micro_array['id'];
                }
            }
        }
    }

    return $micro_array['id'];
}

//repetition  of meals per week
function reptition_of_week_sparfuchs($fin_meals)
{
    $i=1;
    if ($plan == 162) { //if user has chosen the plan = Sparfuchs
        if($weeknum!=1)
        {
            while($i<4)
            {
                $temp_week=$weeknum+$i;
                if($temp_week%2==0)
                {
                    $fin_meals[$temp_week]['mon']['breakfast']=$fin_meals[$weeknum]['thu']['breakfast'];
                    $fin_meals[$temp_week]['mon']['lunch']=$fin_meals[$weeknum]['fri']['lunch'];
                    $fin_meals[$temp_week]['mon']['dinner']=$fin_meals[$weeknum]['sat']['dinner'];

                    $fin_meals[$temp_week]['tue']['breakfast']=$fin_meals[$weeknum]['fri']['breakfast'];
                    $fin_meals[$temp_week]['tue']['lunch']=$fin_meals[$weeknum]['sat']['lunch'];
                    $fin_meals[$temp_week]['tue']['dinner']=$fin_meals[$weeknum]['mon']['dinner'];

                    $fin_meals[$temp_week]['wed']['breakfast']=$fin_meals[$weeknum]['sat']['breakfast'];
                    $fin_meals[$temp_week]['wed']['lunch']=$fin_meals[$weeknum]['mon']['lunch'];
                    $fin_meals[$temp_week]['wed']['dinner']=$fin_meals[$weeknum]['tue']['dinner'];

                    $fin_meals[$temp_week]['thu']['breakfast']=$fin_meals[$weeknum]['mon']['breakfast'];
                    $fin_meals[$temp_week]['thu']['lunch']=$fin_meals[$weeknum]['tue']['lunch'];
                    $fin_meals[$temp_week]['thu']['dinner']=$fin_meals[$weeknum]['wed']['dinner'];

                    $fin_meals[$temp_week]['fri']['breakfast']=$fin_meals[$weeknum]['tue']['breakfast'];
                    $fin_meals[$temp_week]['fri']['lunch']=$fin_meals[$weeknum]['wed']['lunch'];
                    $fin_meals[$temp_week]['fri']['dinner']=$fin_meals[$weeknum]['thu']['dinner'];

                    $fin_meals[$temp_week]['sat']['breakfast']=$fin_meals[$weeknum]['wed']['breakfast'];
                    $fin_meals[$temp_week]['sat']['lunch']=$fin_meals[$weeknum]['thu']['lunch'];
                    $fin_meals[$temp_week]['sat']['dinner']=$fin_meals[$weeknum]['fri']['dinner'];
                }
                else
                {
                    $fin_meals[$temp_week]['mon']['breakfast']=$fin_meals[$weeknum]['fri']['breakfast'];
                    $fin_meals[$temp_week]['mon']['lunch']=$fin_meals[$weeknum]['sat']['lunch'];
                    $fin_meals[$temp_week]['mon']['dinner']=$fin_meals[$weeknum]['mon']['dinner'];

                    $fin_meals[$temp_week]['tue']['breakfast']=$fin_meals[$weeknum]['sat']['breakfast'];
                    $fin_meals[$temp_week]['tue']['lunch']=$fin_meals[$weeknum]['mon']['lunch'];
                    $fin_meals[$temp_week]['tue']['dinner']=$fin_meals[$weeknum]['tue']['dinner'];

                    $fin_meals[$temp_week]['wed']['breakfast']=$fin_meals[$weeknum]['mon']['breakfast'];
                    $fin_meals[$temp_week]['wed']['lunch']=$fin_meals[$weeknum]['tue']['lunch'];
                    $fin_meals[$temp_week]['wed']['dinner']=$fin_meals[$weeknum]['thu']['dinner'];

                    $fin_meals[$temp_week]['thu']['breakfast']=$fin_meals[$weeknum]['tue']['breakfast'];
                    $fin_meals[$temp_week]['thu']['lunch']=$fin_meals[$weeknum]['wed']['lunch'];
                    $fin_meals[$temp_week]['thu']['dinner']=$fin_meals[$weeknum]['thu']['dinner'];

                    $fin_meals[$temp_week]['fri']['breakfast']=$fin_meals[$weeknum]['wed']['breakfast'];
                    $fin_meals[$temp_week]['fri']['lunch']=$fin_meals[$weeknum]['thu']['lunch'];
                    $fin_meals[$temp_week]['fri']['dinner']=$fin_meals[$weeknum]['fri']['dinner'];

                    $fin_meals[$temp_week]['sat']['breakfast']=$fin_meals[$weeknum]['thu']['breakfast'];
                    $fin_meals[$temp_week]['sat']['lunch']=$fin_meals[$weeknum]['fri']['lunch'];
                    $fin_meals[$temp_week]['sat']['dinner']=$fin_meals[$weeknum]['sat']['dinner'];
                }
                $i++;
            }
        }
    }
}

/* filteration of meal par day */

 //echo "<pre>new meallll"; print_r($meals);
        //Repetition of meals per week
        if ($plan == 162) { //if user has chosen the plan = Sparfuchs
                if ($weeknum = 2) {
                        $temp_meals['mon'] = $meals['tue'];
                        $temp_meals['tue'] = $meals['mon'];
                        $temp_meals['wed'] = $meals['thu'];
                        $temp_meals['thu'] = $meals['wed'];
                        $temp_meals['fri'] = $meals['sat'];
                        $temp_meals['sat'] = $meals['fri'];
                        $temp_meals['sun'] = $meals['sun'];
                        $meals = $temp_meals;
                } else if ($weeknum = 3) {
                        $temp_meals['mon'] = $meals['wed'];
                        $temp_meals['tue'] = $meals['tue'];
                        $temp_meals['wed'] = $meals['mon'];
                        $temp_meals['thu'] = $meals['fri'];
                        $temp_meals['fri'] = $meals['sat'];
                        $temp_meals['sat'] = $meals['thu'];
                        $temp_meals['sun'] = $meals['sun'];
                        $meals = $temp_meals;
                } else if ($weeknum = 4) {
                        $temp_meals['mon'] = $meals['sat'];
                        $temp_meals['tue'] = $meals['fri'];
                        $temp_meals['wed'] = $meals['thu'];
                        $temp_meals['thu'] = $meals['wed'];
                        $temp_meals['fri'] = $meals['tue'];
                        $temp_meals['sat'] = $meals['mon'];
                        $temp_meals['sun'] = $meals['sun'];
                        $meals = $temp_meals;
                }
        }


echo "finallllyyyyy.....<pre>";
print_r($meals);
exit;
