<?php

/*
 * Template Name: Demo Nutrition creation logic
 */

global $wpdb;
//$prefix = $wpdb->prefix;
$prefix = 'up_';

$nutrition_types_array = array(
    'nospecial' => '',
    'pescetarian' => 'cw_pescetarian',
    'vegetarian' => 'cw_vegetarian',
    'flexitarian' => 'cw_flexitarian',
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
                                $nut_type_cond[] = '(f_cw_vegetarian==0 || f_cw_vegan==0)';
                                $nut_type_cond1[] = '(f_cw_vegetarian==1 || f_cw_vegan==1)';

                                $f1nut_type_cond[] = '(f1_cw_vegetarian==0 || f1_cw_vegan==0)';
                                $f1nut_type_cond1[] = '(f1_cw_vegetarian==1 || f1_cw_vegan==1)';

                                $res_nut_type1 .= 'IFNULL(f1.cw_vegetarian,0) as f1_cw_vegetarian,IFNULL(f1.cw_vegan,0) as f1_cw_vegan,';
                                $nut_typeval[] = ' f.cw_vegetarian=1 OR f.cw_vegan=1 ';
                                $nut_typeval1[] = ' f1.cw_vegetarian=1 OR f1.cw_vegan=1 ';
                        } else {
                                $val = $nutrition_types_array[$n];
                                $res_nut_type .= 'IFNULL(f.' . $val . ',0) as f_' . $val . ',';
                                $res_nut_type1 .= 'IFNULL(f1.' . $val . ',0) as f1_' . $val . ',';
                                $nut_typeval[] = 'f.' . $val . '= 1 ';
                                $nut_typeval1[] = 'f1.' . $val . '= 1 ';
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
                $int_typeval[] = 'f.' . $intal . '=0 ';
                $int_typeval1[] = 'f1.' . $intal . '=0 ';
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
$available_time_condi = $available_time_array[$available_time];


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
/*(if(strcmp($nutrition_type,'pescetarian')==0)
{
	$order_id=1397;
}
else
{
	$order_id=1599;
}*/
//$order_id=1599;
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
$q2= "SELECT mi.meal_id ,mi.NAME AS ingredient_name,mi.exchangable_with_ingredient,mi.is_ommitable,
group_concat(CASE WHEN FIND_IN_SET(f.id, exchangable_with_ingredient) THEN f.name ELSE NULL END) AS fname ,
group_concat(CASE WHEN FIND_IN_SET(f.id, exchangable_with_ingredient) THEN f.id ELSE NULL END) AS comptiable_sub_id 
FROM up_meal_ingredients mi LEFT JOIN up_foods f ON (FIND_IN_SET(f.id, mi.exchangable_with_ingredient) OR is_ommitable = 1) 
LEFT JOIN up_meal_instructions mins on mi.id = mins.meal_id WHERe (f.id NOT IN (SELECT f1.id FROM up_meal_ingredients cmi LEFT JOIN up_foods f1 
ON cmi.NAME = f1.NAME WHERE cmi.meal_id=mi.meal_id" .$res_nut_typeval1.$res_int_typeval1.$synonyms_que1. $available_time_condi.$res_nut_typeval.$res_int_typeval." GROUP BY mi.NAME,mi.meal_id";


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
$res_meals = $exchangable_foods = $prioritise_meals =$up_order_meals = array();
foreach ($res1 as $k1 => $r1) {

        if (!empty($res3)) {
                foreach ($res3 as $r3) {
                        if (($r1->meal_id == $r3->meal_id) && ($r1->total_ingredient != $r3->main_count)) {
                                $temp = 0;
                                //echo $r1->meal_id . ' count = ' . $r3->main_count . '<br>';
                                $temp_meal_id="";
                                foreach ($res2 as $r) {

                                        if ($r->meal_id == $r1->meal_id) {
											if(strpos($r3->ing_names, $r->ingredient_name) == 0){
												$temp++;
												$exchangable_foods[$r->meal_id][$r->ingredient_name] = $r->fname;
												$pos = strpos($r->comptiable_sub_id, ',');
												$pos= ($pos != 0)? $pos: strlen($r->comptiable_sub_id);
												$s_str=substr($r->comptiable_sub_id,0,$pos);
												$temp_meal_id .= ",".$s_str;
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
										//echo "<pre>";print_r($up_order_meals);
										$count=$wpdb->get_var("select count(*) from up_order_meals where order_id=".$order_id." AND meal_id=".$up_order_meals['meal_id']);
										if($count==0){
										
											$query_insert="insert into up_order_meals (order_id,meal_id,ingredient_ids) values(".$order_id.",".$up_order_meals['meal_id'].",'".$up_order_meals['ingredient_ids']."')";
											$wpdb->query($query_insert);
										}
                                }
                        } else if (($r1->meal_id == $r3->meal_id) && ($r1->total_ingredient == $r3->main_count)) {
                                //echo "=else --";
                                $final_count = $r3->main_count;
                                //$res_meals[] = $r1->meal_id;
                                $prioritise_meals[] = $r1->meal_id;
                                $up_order_meals['order_id']=1599;
                                $up_order_meals['meal_id']=$r3->meal_id;
                                $up_order_meals['ingredient_ids']=$r3->compatible_ingredients;
                                //echo "<pre>";print_r($up_order_meals);
                              
                                $count=$wpdb->get_var("select count(*) from up_order_meals where order_id=".$order_id." AND meal_id=".$up_order_meals['meal_id']);
                                
                                if($count==0){
								
									$query_insert="insert into up_order_meals (order_id,meal_id,ingredient_ids) values(".$order_id.",".$up_order_meals['meal_id'].",'".$up_order_meals['ingredient_ids']."')";
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
echo 'final_meals ';


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

$final_query = "SELECT m.id, m.name,mi.name as ingredient_name,fm.value as f_kcal ,mi.exchangable_with_ingredient,mi.quantity,mi.weight,mi.unit_id,u.unit_symbol as unit,mi.is_ommitable,mins.instruction,mins.difficulty,mins.preparation_time,mins.other_time,mins.popularity,mins.source,mins.meal_category_ids,        
IFNULL(f.is_fatburner,0) as f_is_fatburner,f.synonym_of as f_synonym_of,f.source as f_source,f.price_level_id as f_price_level_id,
" . $f_status . "       
IFNULL(f1.id,0) as ingredient_id,fm1.value as f1_kcal,IFNULL(f1.is_fatburner,0) as f1_is_fatburner,f1.synonym_of as f1_synonym_of,f1.source as f1_source,f1.price_level_id as f1_price_level_id
" . $f1_status . "
FROM " . $prefix . "meals m
INNER JOIN " . $prefix . "meal_ingredients mi ON mi.meal_id = m.id
INNER JOIN " . $prefix . "meal_instructions mins ON m.id = mins.meal_id
" . $available_time_condi . "
left join " . $prefix . "units u on u.id=mi.unit_id        
left JOIN " . $prefix . "foods f ON f.name = mi.name
LEFT JOIN " . $prefix . "food_micros fm ON fm.food_id =f.id AND fm.name='Kilokalorien'
LEFT JOIN " . $prefix . "foods f1 ON FIND_IN_SET( f1.id, mi.exchangable_with_ingredient )
LEFT JOIN " . $prefix . "food_micros fm1 ON fm1.food_id =f1.id AND fm1.name='Kilokalorien'
where m.id in(" . $final_meal . ")
ORDER BY m.id,f.price_level_id;";

//echo $final_query;
$final_res = $wpdb->get_results($final_query);
//echo "<pre>";print_r($final_res);

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
echo $current_weight." - "."(".$tee." * (((".-$plan_week['mon'].") / 100) + ((".-$plan_week['tue'].") / 100) + ((".-$plan_week['wed'].") / 100) + ((".-$plan_week['thu'].") / 100) + ((".-$plan_week['fri'].") / 100) + ((".-$plan_week['sat'].") / 100) + (".$sun_percentage.")) * ((1 / 7000)))</br></br></br></br>";
echo $NPES_perweek = $current_weight - ($tee * (((-$plan_week['mon']) / 100) + ((-$plan_week['tue']) / 100) + ((-$plan_week['wed']) / 100) + ((-$plan_week['thu']) / 100) + ((-$plan_week['fri']) / 100) + ((-$plan_week['sat']) / 100) + ($sun_percentage)) * ((1 / 7000)));
//echo $NPES_perweek;
echo 'Adjust Total Energy Expenditure per week (NPES): ' . number_format($NPES_perweek, 2) . 'kg </br>';

//Split Total Energy Expenditure on meals
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
//echo "yes";
/* resultant meal's kcal value */
$meal_kcal = array();
//echo "<pre>";print_r($final_res);
if (!empty($final_res)) {
        $new_meal_id = $kcals = 0;
        foreach ($final_res as $k => $fr) {

                if ($new_meal_id != $fr->id) {
					
                        $new_meal_id = $fr->id;
                        if ($fr->f_status == 1) {
                                if ($fr->weight <= 0) {
                                        $fr->weight = $fr->quantity;
                                }
                                $kcals +=$fr->f_kcal * $fr->weight / 100;
                        } else {
                                if ($fr->weight <= 0) {
                                        $fr->weight = $fr->quantity;
                                }
                                $kcals +=$fr->f1_kcal * $fr->weight / 100;
                        }
                } else {
                        //echo "<br>else";
                        if ($fr->f_status == 1) {
                                if ($fr->weight <= 0) {
                                        $fr->weight = $fr->quantity;
                                }
                                $kcals +=$fr->f_kcal * $fr->weight / 100;
                        } else {
                                if ($fr->weight <= 0) {
                                        $fr->weight = $fr->quantity;
                                }
                                $kcals +=$fr->f1_kcal * $fr->weight / 100;
                        }
                }
                $meal_kcal[$fr->id] = number_format($kcals, 2);
        }
}
//echo "1111<pre>";print_R($meal_kcal);
//$meal_kcal[2] = 585.38;
// C.7   exclude meals that have +/- 15% kcal


/* Meals priority (weighted sum of % variance) */
//call sp for all micro value calculation
//$priority_micros = $wpdb->get_results("CALL prioritise_val('" . $final_meal . "');");
//$order_id=1599;
$priority_micros = $wpdb->get_results("CALL prioritise_val_v2('" . $order_id . "');");
//echo "<pre>";print_r($priority_micros);
//price level and fat burner calculation
$priority_micros_price = $wpdb->get_results("CALL prioritise_micro('" . $final_meal . "');", OBJECT_K);
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
                $days_npes_cal[$day]['NPES'] = number_format($NPES, 2);

                if (!empty($meals_plan)) {
                        foreach ($meals_plan as $k => $m) {
                                $days_npes_cal[$day][$k][0] = number_format($NPES * ($m / 100), 2);
                                $min_variance = number_format($NPES * ($m / 100) - ($NPES * ($m / 100) * (15 / 100)), 2);
                                $max_variance = number_format($NPES * ($m / 100) + ($NPES * ($m / 100) * (15 / 100)), 2);
                                $days_npes_cal[$day][$k][1] = $min_variance;
                                $days_npes_cal[$day][$k][2] = $max_variance;
                                $meals_matches = array();
                        }
                }
        }
}

//print_r($days_npes_cal);

$ideal_values_for_meal = array(
    'Kilokalorien' => 450.35,
    'Protein' => 40,
    'Fett' => 20,
    'Kohlenhydrate' => 40,
    'Vorbereitungszeit' => 'wenig',
    'Preis/Qualität' => 1,
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

$variance = $micros_array = array();
$html = '';
//echo "priority micros:<pre>";print_r($priority_micros);
if (!empty($priority_micros)) {
	
        $html .='<table id="outer">';
        $html .='<tr><td>';
        $html .='<table border="1">';
        $html .='<tr>';
        $html .='<td><strong>Ideal values</strong></td><td><strong>next meal</strong></td>';
        $html .='</tr>';
        foreach ($ideal_values_for_meal as $k => $i) {
                $html .='<tr>';
                $html .='<td title="' . $k . '">' . substr($k, 0, 8) . '</td><td>' . $i . '</td>';
                $html .='</tr>';
        }
        $html .='<tr><td colspan="2">% Variance Sum</td></tr>';
        $html .='</table></td>';

        foreach ($priority_micros as $p => $pm) {
			//echo "in for each<pre>";print_r($pm);
                $sum = 0;
                if ($new_id != $pm->id) {
                        if ($p != 0) {
                                $html .='</table></td>';
                        }

                        $html .='<td><table border="1">';
                        $html .='<tr>';
                        $html .='<td title="' . $pm->name . ' ' . $pm->id . '"><strong> ' . substr($pm->name, 0, 15) . '</strong></td><td><strong>diff vs.' . $pm->id . '</strong></td>';
                        $html .='</tr>';
                }

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
                $micros_array[$pm->id]['id'] = $pm->id;
                $kal = @number_format(((abs($ideal_values_for_meal['Kilokalorien'] - $pm->Kilokalorien)) / $ideal_values_for_meal['Kilokalorien']) * 150, 2);
                //$avg_kcal = @number_format($pm->Kilokalorien/$pm->total_ingredient,2);
                //$kcal = @number_format(($pm->Kilokalorien/ $ideal_values_for_meal['Kilokalorien']) , 2);
                $html .='<tr><td>' . @number_format($pm->Kilokalorien, 4) . '</td><td>' . $kal . '%</td></tr>';
                $sum +=$kal;
                $micros_array[$pm->id]['kcal'] = $pm->Kilokalorien;

                $pr_fat_carb = $pm->Protein + $pm->Fett + $pm->Kohlenhydrate;
                $avg_Protein = @number_format($pm->Protein / $pr_fat_carb * 100, 2);
                $Protein = @number_format(abs($ideal_values_for_meal['Protein'] - $avg_Protein), 2);
                $html .='<tr><td>' . $avg_Protein . '%</td><td>' . $Protein . '%</td></tr>';
                $sum +=$Protein;
                $micros_array[$pm->id]['Protein'] = $avg_Protein;

                //$protein_diff = protein_cal($avg_Protein);


                $avg_Fett = @number_format($pm->Fett / $pr_fat_carb * 100, 2);
                $Fett = @number_format(abs($ideal_values_for_meal['Fett'] - $avg_Fett), 2);
                $html .='<tr><td>' . $avg_Fett . '%</td><td>' . $Fett . '%</td></tr>';
                $sum +=$Fett;
                $micros_array[$pm->id]['Fett'] = $avg_Fett;

                //$fatt_diff = fatt_cal($avg_Fett);

                $avg_Kohlenhydrate = @number_format($pm->Kohlenhydrate / $pr_fat_carb * 100, 2);
                $Kohlenhydrate = @number_format(abs($ideal_values_for_meal['Kohlenhydrate'] - $avg_Kohlenhydrate), 2);
                $html .='<tr><td>' . $avg_Kohlenhydrate . '%</td><td>' . $Kohlenhydrate . '%</td></tr>';
                $sum +=$Kohlenhydrate;
                $micros_array[$pm->id]['Kohlenhydrate'] = $avg_Kohlenhydrate;

                //$carbo_diff = carbo_cal($avg_Kohlenhydrate);
                //$diff = incl_meal($days_npes_cal,$pm->Kilokalorien, $avg_Protein, $avg_Fett, $avg_Kohlenhydrate);
                //echo "<pre>";print_r($diff);
                $prepare_time = @date('i', strtotime($pm->preparation_time));
                $html .='<tr><td>' . $prepare_time . '</td><td>' . $prepare_time . '</td></tr>';
                $sum +=$prepare_time;

                $temp_price_level = @number_format($priority_micros_price[$pm->id]->price_avg, 4);

                $percentage = 15;
                if ($plan == 162) {
                        // If chosen plan = Sparfuchs then 20%  variance.
                        $percentage = 20;
                }
                $price_level = @number_format(abs($temp_price_level - $ideal_values_for_meal['Preis/Qualität']) * $percentage, 2);
                $html .='<tr><td>' . $temp_price_level . '</td><td>' . $price_level . '%</td></tr>';
                $sum +=$price_level;

				$ge_f = @number_format(abs($pm->{'Gesättigte Fettsäuren'} - $ideal_values_for_meal['Gesättigte Fettsäuren']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Gesättigte Fettsäuren'}, 4) . '</td><td>' . $ge_f . '%</td></tr>';
                $sum +=$ge_f;

                $ein_un_f = @number_format(abs($pm->{'Einfach ungesättigte Fettsäuren'} - $ideal_values_for_meal['Einfach ungesättigte Fettsäuren']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Einfach ungesättigte Fettsäuren'}, 4) . '</td><td>' . $ein_un_f . '%</td></tr>';
                $sum +=$ein_un_f;

                $mn_un_f = @number_format(abs($pm->{'Mehrfach ungesättigte Fettsäuren'} - $ideal_values_for_meal['Mehrfach ungesättigte Fettsäuren']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Mehrfach ungesättigte Fettsäuren'}, 4) . '</td><td>' . $mn_un_f . '%</td></tr>';
                $sum +=$mn_un_f;

                /*$Cholesterin = @number_format(abs($pm->{'Cholesterin'} - $ideal_values_for_meal['Cholesterin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Cholesterin'}, 4) . '</td><td>' . $Cholesterin . '%</td></tr>';
                $sum +=$Cholesterin;*/

                $Vitamin_A = @number_format(abs($pm->{'Vitamin A'} - $ideal_values_for_meal['Vitamin A']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin A'}, 4) . '</td><td>' . $Vitamin_A . '%</td></tr>';
                $sum +=$Vitamin_A;

                $Vitamin_C = @number_format(abs($pm->{'Vitamin C'} - $ideal_values_for_meal['Vitamin C']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin C'}, 4) . '</td><td>' . $Vitamin_C . '%</td></tr>';
                $sum +=$Vitamin_C;

                $Vitamin_D = @number_format(abs($pm->{'Vitamin D'} - $ideal_values_for_meal['Vitamin D']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin D'}, 4) . '</td><td>' . $Vitamin_D . '%</td></tr>';
                $sum +=$Vitamin_D;

                $Vitamin_E = @number_format(abs($pm->{'Vitamin E'} - $ideal_values_for_meal['Vitamin E']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin E'}, 4) . '</td><td>' . $Vitamin_E . '%</td></tr>';
                $sum +=$Vitamin_E;

                $Vitamin_K = @number_format(abs($pm->{'Vitamin K'} - $ideal_values_for_meal['Vitamin K']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin K'}, 4) . '</td><td>' . $Vitamin_K . '%</td></tr>';
                $sum +=$Vitamin_K;

                $Vitamin_B1 = @number_format(abs($pm->{'Vitamin B1'} - $ideal_values_for_meal['Vitamin B1']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin B1'}, 4) . '</td><td>' . $Vitamin_B1 . '%</td></tr>';
                $sum +=$Vitamin_B1;

                $Vitamin_B2 = @number_format(abs($pm->{'Vitamin B2'} - $ideal_values_for_meal['Vitamin B2']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin B2'}, 4) . '</td><td>' . $Vitamin_B2 . '%</td></tr>';
                $sum +=$Vitamin_B2;

                $Vitamin_B6 = @number_format(abs($pm->{'Vitamin B6'} - $ideal_values_for_meal['Vitamin B6']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin B6'}, 4) . '</td><td>' . $Vitamin_B6 . '%</td></tr>';
                $sum +=$Vitamin_B6;

                $Vitamin_B12 = @number_format(abs($pm->{'Vitamin B12'} - $ideal_values_for_meal['Vitamin B12']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Vitamin B12'}, 4) . '</td><td>' . $Vitamin_B12 . '%</td></tr>';
                $sum +=$Vitamin_B12;

                $Biotin = @number_format(abs($pm->{'Biotin'} - $ideal_values_for_meal['Biotin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Biotin'}, 4) . '</td><td>' . $Biotin . '%</td></tr>';
                $sum +=$Biotin;

                $Folsaure = @number_format(abs($pm->{'Folsäure'} - $ideal_values_for_meal['Folsäure']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Folsäure'}, 4) . '</td><td>' . $Folsaure . '%</td></tr>';
                $sum +=$Folsaure;

                $Niacin = @number_format(abs($pm->{'Niacin'} - $ideal_values_for_meal['Niacin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Niacin'}, 4) . '</td><td>' . $Niacin . '%</td></tr>';
                $sum +=$Niacin;

                $Panthotensaure = @number_format(abs($pm->{'Panthotensäure'} - $ideal_values_for_meal['Panthotensäure']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Panthotensäure'}, 4) . '</td><td>' . $Panthotensaure . '</td></tr>';
                $sum +=$Panthotensaure;

                $Calcium = @number_format(abs($pm->{'Calcium [Ca]'} - $ideal_values_for_meal['Calcium [Ca]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Calcium [Ca]'}, 4) . '</td><td>' . $Calcium . '%</td></tr>';
                $sum +=$Calcium;

                $Chlor = @number_format(abs($pm->{'Chlor [Cl]'} - $ideal_values_for_meal['Chlor [Cl]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Chlor [Cl]'}, 4) . '</td><td>' . $Chlor . '%</td></tr>';
                $sum +=$Chlor;

                $Kalium = @number_format(abs($pm->{'Kalium [K]'} - $ideal_values_for_meal['Kalium [K]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Kalium [K]'}, 4) . '</td><td>' . $Kalium . '%</td></tr>';
                $sum +=$Kalium;

                $Magnesium = @number_format(abs($pm->{'Magnesium [Mg]'} - $ideal_values_for_meal['Magnesium [Mg]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Magnesium [Mg]'}, 4) . '</td><td>' . $Magnesium . '%</td></tr>';
                $sum +=$Magnesium;

                $Natrium = @number_format(abs($pm->{'Natrium [Na]'} - $ideal_values_for_meal['Natrium [Na]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Natrium [Na]'}, 4) . '</td><td>' . $Natrium . '%</td></tr>';
                $sum +=$Natrium;

                $Phosphor = @number_format(abs($pm->{'Phosphor [P]'} - $ideal_values_for_meal['Phosphor [P]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Phosphor [P]'}, 4) . '</td><td>' . $Phosphor . '%</td></tr>';
                $sum +=$Phosphor;

                /* $Schwefel = @number_format(abs($pm->{'Schwefel [S]'} - $ideal_values_for_meal['Schwefel [S]']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Schwefel [S]'}, 4) . '</td><td>' . $Schwefel . '%</td></tr>';
                  $sum +=$Schwefel; */

                $Kupfer = @number_format(abs($pm->{'Kupfer [Cu]'} - $ideal_values_for_meal['Kupfer [Cu]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Kupfer [Cu]'}, 4) . '</td><td>' . $Kupfer . '%</td></tr>';
                $sum +=$Kupfer;

                $Eisen = @number_format(abs($pm->{'Eisen [Fe]'} - $ideal_values_for_meal['Eisen [Fe]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Eisen [Fe]'}, 4) . '</td><td>' . $Eisen . '%</td></tr>';
                $sum +=$Eisen;

                $Fluor = @number_format(abs($pm->{'Fluor [F]'} - $ideal_values_for_meal['Fluor [F]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Fluor [F]'}, 4) . '</td><td>' . $Fluor . '%</td></tr>';
                $sum +=$Fluor;

                $Mangan = @number_format(abs($pm->{'Mangan [Mn]'} - $ideal_values_for_meal['Mangan [Mn]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Mangan [Mn]'}, 4) . '</td><td>' . $Mangan . '%</td></tr>';
                $sum +=$Mangan;

                $Jod = @number_format(abs($pm->{'Jod [J]'} - $ideal_values_for_meal['Jod [J]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Jod [J]'}, 4) . '</td><td>' . $Jod . '%</td></tr>';
                $sum +=$Jod;

                $Zink = @number_format(abs($pm->{'Zink [Zn]'} - $ideal_values_for_meal['Zink [Zn]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Zink [Zn]'}, 4) . '</td><td>' . $Zink . '%</td></tr>';
                $sum +=$Zink;

                /* if($ideal_values_for_meal['Alanin'] > 0){
                  $Alanin = @number_format(abs($pm->{'Alanin'} - $ideal_values_for_meal['Alanin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Alanin'} ,4). '</td><td>' . $Alanin . '%</td></tr>';
                  $sum +=$Alanin;
                  }else{
                  $html .='<tr><td>0</td><td>0</td></tr>';
                  }



                  $Arginin = @number_format(abs($pm->{'Arginin'} - $ideal_values_for_meal['Arginin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Arginin'} ,4). '</td><td>' . $Arginin . '%</td></tr>';
                  $sum +=$Arginin;

                  $Aspargin = @number_format(abs($pm->{'Aspargin'} - $ideal_values_for_meal['Aspargin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Aspargin'} ,4). '</td><td>' . $Aspargin . '%</td></tr>';
                  $sum +=$Aspargin;

                  $Asparginsaure = @number_format(abs($pm->{'Asparginsäure'} - $ideal_values_for_meal['Asparginsäure']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Asparginsäure'} ,4). '</td><td>' . $Asparginsaure . '%</td></tr>';
                  $sum +=$Asparginsaure;
                 */
                $Cystein = @number_format(abs($pm->{'Cystein'} - $ideal_values_for_meal['Cystein']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Cystein'}, 4) . '</td><td>' . $Cystein . '%</td></tr>';
                $sum +=$Cystein;

                /* $Glutamin = @number_format(abs($pm->{'Glutamin'} - $ideal_values_for_meal['Glutamin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Glutamin'} ,4). '</td><td>' . $Glutamin . '%</td></tr>';
                  $sum +=$Glutamin;

                  $Glutaminsaure = @number_format(abs($pm->{'Glutaminsäure'} - $ideal_values_for_meal['Glutaminsäure']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Glutaminsäure'} ,4). '</td><td>' . $Glutaminsaure . '%</td></tr>';
                  $sum +=$Glutaminsaure;

                  $Glycerin = @number_format(abs($pm->{'Glycerin'} - $ideal_values_for_meal['Glycerin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Glycerin'} ,4). '</td><td>' . $Glutaminsaure . '%</td></tr>';
                  $sum +=$Glycerin;

                  $Histidin = @number_format(abs($pm->{'Histidin'} - $ideal_values_for_meal['Histidin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Histidin'} ,4). '</td><td>' . $Histidin . '%</td></tr>';
                  $sum +=$Histidin;

                  $Isoleucin = @number_format(abs($pm->{'Isoleucin'} - $ideal_values_for_meal['Isoleucin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Isoleucin'} ,4). '</td><td>' . $Isoleucin . '%</td></tr>';
                  $sum +=$Isoleucin;
                 * 
                 */

                $Leucin = @number_format(abs($pm->{'Leucin'} - $ideal_values_for_meal['Leucin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Leucin'}, 4) . '</td><td>' . $Leucin . '%</td></tr>';
                $sum +=$Leucin;

                $Lysin = @number_format(abs($pm->{'Lysin'} - $ideal_values_for_meal['Lysin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Lysin'}, 4) . '</td><td>' . $Lysin . '%</td></tr>';
                $sum +=$Lysin;

                /* $Methionin = @number_format(abs($pm->{'Methionin'} - $ideal_values_for_meal['Methionin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Methionin'} ,4). '</td><td>' . $Methionin . '%</td></tr>';
                  $sum +=$Methionin;

                  $Phenylalanin = @number_format(abs($pm->{'Phenylalanin'} - $ideal_values_for_meal['Phenylalanin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Phenylalanin'} ,4). '</td><td>' . $Phenylalanin . '%</td></tr>';
                  $sum +=$Phenylalanin;

                  $Prolin = @number_format(abs($pm->{'Prolin'} - $ideal_values_for_meal['Prolin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Prolin'} ,4). '</td><td>' . $Prolin . '%</td></tr>';
                  $sum +=$Prolin;

                  $Serin = @number_format(abs($pm->{'Serin'} - $ideal_values_for_meal['Serin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Serin'} ,4). '</td><td>' . $Serin . '%</td></tr>';
                  $sum +=$Serin;
                 * 
                 */

                $Threonin = @number_format(abs($pm->{'Threonin'} - $ideal_values_for_meal['Threonin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Threonin'}, 4) . '</td><td>' . $Threonin . '%</td></tr>';
                $sum +=$Threonin;

                $Tryptophan = @number_format(abs($pm->{'Tryptophan'} - $ideal_values_for_meal['Tryptophan']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Tryptophan'}, 4) . '</td><td>' . $Tryptophan . '%</td></tr>';
                $sum +=$Tryptophan;

                /* $Tyrosin = @number_format(abs($pm->{'Tyrosin'} - $ideal_values_for_meal['Tyrosin']) * 0.1, 2);
                  $html .='<tr><td>' . @number_format($pm->{'Tyrosin'}, 4) . '</td><td>' . $Tyrosin . '%</td></tr>';
                  $sum +=$Tyrosin; */

                $Valin = @number_format(abs($pm->{'Valin'} - $ideal_values_for_meal['Valin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Valin'}, 4) . '</td><td>' . $Valin . '%</td></tr>';
                $sum +=$Valin;

                $html .='<tr><td>&nbsp;</td><td>' . $sum . '%</td></tr>';

                if ($plan == 95 || $plan == 166) {
                        //if plan is fat killer
                        //Is fatkiller = 1 should be reduced by 0,5% 
                        $sum = $sum - ($priority_micros_price[$pm->id]->fatt * 0.5);
                }
                $variance[$pm->id] = $sum;
                //echo $html;
        }
        if (end($priority_micros)) {
                $html .='</table></td>';
        }
        $html .='</tr></table>';
}

echo $html;


if (!empty($variance)) {
//	echo "variance if";
        /* $min = min($variance);  // 1

          echo $key = array_keys($variance, $min);
          echo "<pre>"; */
        //print_r($variance);
        asort($variance);
		/*echo "<pre>";
        print_r($variance);
        echo "<pre>";*/
        //print_r($micros_array);
}
else{
	echo "variance else";
}

/* difference calculation for meal */

/* D. c)balance out the variance */
$rem_meals = array();
echo "days special cal:<pre>";print_r($days_npes_cal);
echo "micros array:<pre>";print_r($micros_array);
//echo "per day meal:".$per_day_no_of_melas;
if ($period == 12) {
//for 12 week plan

        $meals = select_balance_meals($rem_meals, $variance, $micros_array, $days_npes_cal, $per_day_no_of_melas, 40, 20, 40, 0);
		
       // echo "test == <pre>";print_r($meals);
} else {
//for 4 week plans
        $meals = select_balance_meals($rem_meals, $variance, $micros_array, $days_npes_cal, $per_day_no_of_melas, 45, 17.5, 37.5, 0);
}
	
//echo "balance meal<pre>";print_r($meals);

$micros_diff = array();

$fmeals = array();

function select_balance_meals($rem_meals = array(), $variance, $micros_array, $days_npes_cal, $per_day_no_of_melas = 4, $protin, $fat, $carbo, $i = 0, $fmeals = array()) {
        $temp_array = array();
        global $wpdb;
       
        if (!empty($micros_array)) {
                /* check breakfast meal */
                foreach ($variance as $k => $m) {
					 //print_r($k);;
                        $pro_diff = abs((($protin / $micros_array[$k]['Protein']) * 100) - 100);
                        $fat_diff = abs((($fat / $micros_array[$k]['Fett']) * 100) - 100);
                        $carb_diff = abs((($carbo / $micros_array[$k]['Kohlenhydrate']) * 100) - 100);
                        $kcal = $micros_array[$k]['kcal'];
                        foreach ($days_npes_cal as $d => $v) {
                                
                                $breakfast = $lunch = $dinner = $pre_lunch = $pre_dinner = 0;
                                //echo "breakfast kcal:".$v['breakfast'][1];
                                if ($kcal >= $v['breakfast'][1] && $kcal <= $v['breakfast'][2]) {
										
										//echo "breakfast".$k."</br>";
                                        $kcal_diff = abs((($kcal / $v['breakfast'][0] * 100) - 100) * 1.5);

                                        $diff = $kcal_diff + $pro_diff + $fat_diff + $carb_diff;

                                        //$micros_diff[$d]['breakfast'][$micros_array[$k]['id']] = $diff;
                                        //echo "mic_ar";print_r($micros_array);
                                      //  echo 'SELECT meal_category_ids FROM up_meal_instructions WHERE meal_id='.$k;
                                      
                                        $sql_meal_cat=$wpdb->get_results("SELECT meal_category_ids FROM up_meal_instructions WHERE meal_id=".$k);
										//print_r($sql_meal_cat);
                                        $meal_cat = explode(',', $sql_meal_cat[0]->meal_category_ids);
                                       // echo "meal_cat";print_r($meal_cat);
                                      // $breakfast = $micros_array[$k]['id'];
                                      // $micros_diff[$d]['breakfast'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
										if (in_array(1, $meal_cat)) {
                                                $breakfast = $micros_array[$k]['id'];
                                                $micros_diff[$d]['breakfast'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                        }
                                } else {

                                        $micros_diff[$d]['breakfast'][$micros_array[$k]['id']] = '';
                                        unset($micros_diff[$d]['breakfast'][$micros_array[$k]['id']]);
                                }

                                if (count($breakfast) > 0) {
										echo "after breakfast:<pre>";print_r($breakfast);
                                        if ($kcal >= $v['lunch'][1] && $kcal <= $v['lunch'][2]) {
												
                                                $pro_diff_l = abs(2 * $protin - $micros_array[$breakfast]['Protein']);
                                                $fat_diff_l = abs(2 * $fat - $micros_array[$breakfast]['Fett']);
                                                $carb_diff_l = abs(2 * $carbo - $micros_array[$breakfast]['Kohlenhydrate']);

                                                $kcal_diff = abs((($kcal / $v['lunch'][0] * 100) - 100) * 1.5);

                                                $diff = $kcal_diff + $pro_diff_l + $fat_diff_l + $carb_diff_l;

                                                //$micros_diff[$d]['lunch'][$micros_array[$k]['id']] = $diff;
                                                $sql_meal_cat=$wpdb->get_results("SELECT meal_category_ids FROM up_meal_instructions WHERE meal_id=".$k);
                                                $meal_cat = explode(',', $sql_meal_cat[0]->meal_category_ids);
                                                if (in_array(2, $meal_cat)) {
                                                        $micros_diff[$d]['lunch'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                        $lunch = $micros_array[$k]['id'];
                                                }
                                        }
                                } else {
                                        $micros_diff[$d]['lunch'][$micros_array[$k]['id']] = '';
                                        unset($micros_diff[$d]['lunch'][$micros_array[$k]['id']]);
                                }

                                if (count($lunch) > 0) {
									
                                        if ($kcal >= $v['dinner'][1] && $kcal <= $v['dinner'][2]) {
												//echo "ifdinner";
												//echo "k:".$k;
                                                $pro_diff_l = abs(2 * $protin - $micros_array[$lunch]['Protein']);
                                                $fat_diff_l = abs(2 * $fat - $micros_array[$lunch]['Fett']);
                                                $carb_diff_l = abs(2 * $carbo - $micros_array[$lunch]['Kohlenhydrate']);

                                                $kcal_diff = abs((($kcal / $v['dinner'][0] * 100) - 100) * 1.5);

                                                $diff = $kcal_diff + $pro_diff_l + $fat_diff_l + $carb_diff_l;

                                                //$micros_diff[$d]['dinner'][$micros_array[$k]['id']] = $diff;
                                               $sql_meal_cat=$wpdb->get_results("SELECT meal_category_ids FROM up_meal_instructions WHERE meal_id=".$k);
                                               $meal_cat = explode(',', $sql_meal_cat[0]->meal_category_ids);
                                                if (in_array(3, $meal_cat)) {
                                                        $micros_diff[$d]['dinner'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                        $dinner = $micros_array[$k]['id'];
                                                }
                                        }
                                } else {
                                        $micros_diff[$d]['dinner'][$micros_array[$k]['id']] = '';
                                        unset($micros_diff[$d]['dinner'][$micros_array[$k]['id']]);
                                }


                                if (count($dinner) > 0) {
                                        if ($kcal >= $v['pre_dinner_snack'][1] && $kcal <= $v['pre_dinner_snack'][2]) {

                                                $pro_diff_l = abs(2 * $protin - $micros_array[$dinner]['Protein']);
                                                $fat_diff_l = abs(2 * $fat - $micros_array[$dinner]['Fett']);
                                                $carb_diff_l = abs(2 * $carbo - $micros_array[$dinner]['Kohlenhydrate']);

                                                $kcal_diff = abs((($kcal / $v['pre_dinner_snack'][0] * 100) - 100) * 1.5);

                                                $diff = $kcal_diff + $pro_diff_l + $fat_diff_l + $carb_diff_l;

                                                //$micros_diff[$d]['pre_dinner_snack'][$micros_array[$k]['id']] = $diff;
												$sql_meal_cat=$wpdb->get_results("SELECT meal_category_ids FROM up_meal_instructions WHERE meal_id=".$k);
                                               $meal_cat = explode(',', $sql_meal_cat[0]->meal_category_ids);
                                                if (in_array(4, $meal_cat)) {
                                                        $micros_diff[$d]['pre_dinner_snack'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                        $snack = $micros_array[$k]['id'];
                                                }
                                        }
                                } else {
                                        $micros_diff[$d]['pre_dinner_snack'][$micros_array[$k]['id']] = '';
										unset($micros_diff[$d]['pre_dinner_snack'][$micros_array[$k]['id']]);
                                }

                                if ($per_day_no_of_melas == 5) {
                                        if ($snack > 0 && isset($v['pre_lunch_snack'])) {
                                                if ($kcal >= $v['pre_lunch_snack'][1] && $kcal <= $v['pre_lunch_snack'][2]) {

                                                        $pro_diff_l = abs(2 * $protin - $micros_array[$snack]['Protein']);
                                                        $fat_diff_l = abs(2 * $fat - $micros_array[$snack]['Fett']);
                                                        $carb_diff_l = abs(2 * $carbo - $micros_array[$snack]['Kohlenhydrate']);

                                                        $kcal_diff = abs((($kcal / $v['pre_lunch_snack'][0] * 100) - 100) * 1.5);

                                                        $diff = $kcal_diff + $pro_diff_l + $fat_diff_l + $carb_diff_l;

                                                        //$micros_diff[$d]['pre_lunch_snack'][$micros_array[$k]['id']] = $diff;
                                                        $sql_meal_cat=$wpdb->get_results("SELECT meal_category_ids FROM up_meal_instructions WHERE meal_id=".$k);
														$meal_cat = explode(',', $sql_meal_cat[0]->meal_category_ids);
                                                        if (in_array(4, $meal_cat)) {
                                                                $micros_diff[$d]['pre_lunch_snack'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                                $snack1 = $micros_array[$k]['id'];
                                                        }
                                                }
                                        } else {
                                                $micros_diff[$d]['pre_lunch_snack'][$micros_array[$k]['id']] = '';
                                                unset($micros_diff[$d]['pre_lunch_snack'][$micros_array[$k]['id']]);
                                        }
                                }

                                // if(isset($v['breakfast']))
                                //print_r($micros_diff);
                        }
                }
                //print_r($micros_diff);
                /* $breakfast = array_keys($micros_diff, min($micros_diff));
                  $meals['breakfast'] = $breakfast[0];
                  $fmeals['breakfast'][$i] = $breakfast[0];
                  print_r($meals);
                  unset($micros_diff[$breakfast[0]]); */
        }

        //return compact('meals', 'micros_diff');
        return $micros_diff;
}

/* filteration of meal par day */

echo 'final meals :<br>';
//echo "<pre>";print_r($meals);

if (!empty($meals)) {

        $user_email = $billing_email;
        $plan_id = $plan;
        $weeknum = 1;
        $per_day_no_of_melas = $per_day_no_of_melas;
        $final_meal = $final_meal;
        $height = $height;
        $current_weight = $current_weight;
        $desired_weight = $desired_weight;
        $age = $age;

        if ($user_nutrition_plans_id > 0) {
                if (!empty($order_logs)) {
                        $weeknum = $order_logs[0]->week_no + 1;
                }
        }


        $temp_lunch = array();

        /* Veggie meals */
        if (in_array('pescetarian', $nutrition_type) || in_array('nospecial', $nutrition_type)) {
                $temp_lunch = array();
                if ($weeknum == 1 || $weeknum == 3) {
                        foreach ($meals as $day => $fm) {
                                if ($day == 'tue' || $day == 'thu') {
                                        if (!empty($fm['lunch'])) {
                                                foreach ($fm['lunch'] as $l) {
													
                                                        if ($priority_micros_price[$l]->is_veg == 0) {
																
                                                                $meals[$day]['lunch'][$l] = $l;
                                                                $temp_lunch[$day]['lunch'][$l] = $l;
                                                        }
                                                }
                                                $meals[$day]['lunch'] = $temp_lunch[$day]['lunch'];
                                        }
                                }
                        }
                } else if ($weeknum == 2 || $weeknum == 4) {

                        foreach ($meals as $day => $fm) {
                                if ($day == 'mon' || $day == 'wed') {
                                        if (!empty($fm['lunch'])) {
                                                foreach ($fm['lunch'] as $l) {
                                                        if ($priority_micros_price[$l]->is_veg == 0) {
                                                                $meals[$day]['lunch'][$l] = $l;
                                                                $temp_lunch[$day]['lunch'][$l] = $l;
                                                        }
                                                }
                                                $meals[$day]['lunch'] = $temp_lunch[$day]['lunch'];
                                        }
                                }
                        }
                }

                //echo "<pre>temp--"; print_r($temp_lunch);
               // echo "<pre>meallll"; print_r($meals);
        }


        if (in_array('flexitarian', $nutrition_type)) {
                $temp_lunch = array();
                //skip the meal that are not compatible vagetarian attribute
                foreach ($meals as $day => $fm) {
                        if ($day == 'mon' || $day == 'thu') {
                                if (!empty($fm['breakfast'])) {

                                        foreach ($fm['breakfast'] as $l) {
                                                if ($priority_micros_price[$l]->is_veg == 0) {
                                                        $meals[$day]['breakfast'][$l] = $l;
                                                        $temp_lunch[$day]['breakfast'][$l] = $l;
                                                }
                                        }
                                        $meals[$day]['breakfast'] = $temp_lunch[$day]['breakfast'];
                                }
                        }
                        if ($day == 'tue' || $day == 'fri') {
                                if (!empty($fm['lunch'])) {
                                        foreach ($fm['lunch'] as $l) {
                                                if ($priority_micros_price[$l]->is_veg == 0) {
                                                        $meals[$day]['lunch'][$l] = $l;
                                                        $temp_lunch[$day]['lunch'][$l] = $l;
                                                }
                                        }
                                        $meals[$day]['lunch'] = $temp_lunch[$day]['lunch'];
                                }
                        }
                        if ($day == 'wed' || $day == 'sat') {
                                if (!empty($fm['dinner'])) {
                                        foreach ($fm['dinner'] as $l) {
                                                if ($priority_micros_price[$l]->is_veg == 0) {
                                                        $meals[$day]['dinner'][$l] = $l;
                                                        $temp_lunch[$day]['dinner'][$l] = $l;
                                                }
                                        }
                                        $meals[$day]['dinner'] = $temp_lunch[$day]['dinner'];
                                }
                        }
                }
        }

        /* Repetition of meat and fish */

        /* $micros_array[6]['Fisch'] = 1;
          $micros_array[6]['Fleisch'] = 1;
          $micros_array[90]['Fisch'] = 1;
          $micros_array[90]['Fleisch'] = 1;
          $micros_array[106]['Fisch'] = 1;
          $micros_array[106]['Fleisch'] = 1;
          $micros_array[28]['Fisch'] = 1;
          $micros_array[28]['Fleisch'] = 1;
          $micros_array[109]['Fisch'] = 1;
          $micros_array[109]['Fleisch'] = 1;
          print_r($micros_array); */
        $counter_meat_fish = $counter_fish = 0;
        $meat_list=array();
        $fish_list=array();
        $meat_fish_detail = array();
        //echo "before fish & meat:<pre>";print_r($meals);
        if (in_array('nospecial', $nutrition_type)) {
                if (!empty($meat_meals)) {
                        $meat_meals = array_keys($meat_meals);
                        $fish_meals_keys = array_keys($fish_meals);
                        foreach ($meals as $day => $fm) {
								$counter_meat_fish_day=0;
								if (!empty($fm['lunch']) && $counter_meat_fish < 4 && $counter_meat_fish_day!=1) {
										
                                        $meat_list = array_intersect($fm['lunch'], $meat_meals);
                                        $counter_meat_fish_day=(empty($meat_list))? 0 : 1;
										if($counter_meat_fish_day==1){
											//echo "1st meat in lunch".key($meat_list)."</br>";
											//print_r($meat_list);
											$meat_list=array_diff($meat_list,$meat_fish_detail);
											$meals[$day]['lunch']=array_diff($meals[$day]['lunch'], $meat_list);
											$meals[$day]['lunch']=array_diff($meals[$day]['lunch'], $meat_fish_detail);
											$meals[$day]['lunch'][key($meat_list)]=key($meat_list);
											$meat_fish_detail[key($meat_list)]=key($meat_list);
											
											$counter_meat_fish+=$counter_meat_fish_day;
											$fish_list = array_intersect($fm['lunch'], $fish_meals_keys);
											if(!empty($fish_list))
											{
												//echo "fish present in lunch meals.<pre>";print_r($fish_list);
												$meals[$day]['lunch']=array_diff($meals[$day]['lunch'], $fish_list);	
											}
											//echo "after removing fish".$counter_meat_fish_day;
										}
										else
										{
											$fish_list = array_intersect($fm['lunch'], $fish_meals_keys);
											$counter_meat_fish_day=(empty($fish_list))? 0 : 1;
											if($counter_meat_fish_day==1){
												//echo "1st fish in lunch".key($fish_list)."</br>";
												//print_r($fish_list);
												$fish_list=array_diff($fish_list,$meat_fish_detail);
												$meals[$day]['lunch']=array_diff($meals[$day]['lunch'], $fish_list);
												$meals[$day]['lunch']=array_diff($meals[$day]['lunch'], $meat_fish_detail);
												$meals[$day]['lunch'][key($fish_list)]=key($fish_list);
												$meat_fish_detail[key($fish_list)]=key($fish_list);
												
												
												$counter_meat_fish+=$counter_meat_fish_day;
											}
										}

                                }
                                else
                                {
									$meat_list = array_intersect($fm['lunch'], $meat_meals);
									if(!empty($meat_list))
									{
										//echo "lunch else day:".$day."counter meat".$counter_meat_fish."</br>";
										//echo "else of dinner meat meals.<pre>";print_r($meat_list);
										$meals[$day]['lunch']=array_diff($meals[$day]['lunch'], $meat_list);	
									}
									$fish_list = array_intersect($fm['lunch'], $fish_meals_keys);
									if(!empty($fish_list))
									{
											//echo "fish present in breakfast meals.<pre>";print_r($fish_list);
											$meals[$day]['lunch']=array_diff($meals[$day]['lunch'], $fish_list);	
									}
								}
                                
                               if (!empty($fm['breakfast']) && $counter_meat_fish < 4 && $counter_meat_fish_day!=1) {
								   		$meat_list = array_intersect($fm['breakfast'], $meat_meals);
                                        $counter_meat_fish_day=(empty($meat_list))? 0 : 1;
                                        if($counter_meat_fish_day==1){
											//echo "1st meat in breakfast".key($meat_list)."</br>";
											//print_r($meat_list);
											$meat_list=array_diff($meat_list,$meat_fish_detail);
											$meals[$day]['breakfast']=array_diff($meals[$day]['breakfast'], $meat_list);
											$meals[$day]['breakfast']=array_diff($meals[$day]['breakfast'], $meat_fish_detail);
											$meals[$day]['breakfast'][key($meat_list)]=key($meat_list);
											$meat_fish_detail[key($meat_list)]=key($meat_list);
											
											$counter_meat_fish+=$counter_meat_fish_day;
											$fish_list = array_intersect($fm['breakfast'], $fish_meals_keys);
											if(!empty($fish_list))
											{
												//echo "fish present in breakfast meals.<pre>";print_r($fish_list);
												$meals[$day]['breakfast']=array_diff($meals[$day]['breakfast'], $fish_list);	
											}
										}
										else
										{
											$fish_list = array_intersect($fm['breakfast'], $fish_meals_keys);
											$counter_meat_fish_day=(empty($fish_list))? 0 : 1;
											if($counter_meat_fish_day==1){
												//echo "1st fish in breakfast".key($fish_list)."</br>";
												//print_r($fish_list);
												$fish_list=array_diff($fish_list,$meat_fish_detail);
												$meals[$day]['breakfast']=array_diff($meals[$day]['breakfast'], $fish_list);
												$meals[$day]['breakfast']=array_diff($meals[$day]['breakfast'], $meat_fish_detail);
												$meals[$day]['breakfast'][key($fish_list)]=key($fish_list);
												$meat_fish_detail[key($fish_list)]=key($fish_list);
												
												$counter_meat_fish+=$counter_meat_fish_day;
											}
										}
                                }
                                else
                                {
									$meat_list = array_intersect($fm['breakfast'], $meat_meals);
									if(!empty($meat_list))
									{
										//echo "else of breakfast meat meals.<pre>";print_r($meat_list);
										$meals[$day]['breakfast']=array_diff($meals[$day]['breakfast'], $meat_list);	
									}
									$fish_list = array_intersect($fm['breakfast'], $fish_meals_keys);
									if(!empty($fish_list))
									{
										//echo "fish present in breakfast meals.<pre>";print_r($fish_list);
										$meals[$day]['breakfast']=array_diff($meals[$day]['breakfast'], $fish_list);	
									}
								}
                                if (!empty($fm['dinner']) && $counter_meat_fish < 4 && $counter_meat_fish_day!=1) {
                                        $meat_list = array_intersect($fm['dinner'], $meat_meals);
                                        $counter_meat_fish_day=(empty($meat_list))? 0 : 1;
										if($counter_meat_fish_day==1){
											//echo "1st meat in breakfast".key($meat_list)."</br>";
											//print_r($meat_list);
											$meat_list=array_diff($meat_list,$meat_fish_detail);
											$meals[$day]['dinner']=array_diff($meals[$day]['dinner'], $meat_list);
											$meals[$day]['dinner']=array_diff($meals[$day]['dinner'], $meat_fish_detail);
											$meals[$day]['dinner'][key($meat_list)]=key($meat_list);
											$meat_fish_detail[key($meat_list)]=key($meat_list);
											
											$counter_meat_fish+=$counter_meat_fish_day;
											$fish_list = array_intersect($fm['dinner'], $fish_meals_keys);
											if(!empty($fish_list))
											{
												//echo "fish present in dinner meals.<pre>";print_r($fish_list);
												$meals[$day]['dinner']=array_diff($meals[$day]['dinner'], $fish_list);	
											}
										}
										else
										{
											$fish_list = array_intersect($fm['dinner'], $fish_meals_keys);
											$counter_meat_fish_day=(empty($fish_list))? 0 : 1;
											if($counter_meat_fish_day==1){
												//echo "1st fish in dinner".key($fish_list)."</br>";
												//print_r($fish_list);
												$fish_list=array_diff($fish_list,$meat_fish_detail);
												$meals[$day]['dinner']=array_diff($meals[$day]['dinner'], $fish_list);
												$meals[$day]['dinner']=array_diff($meals[$day]['dinner'], $meat_fish_detail);
												$meals[$day]['dinner'][key($fish_list)]=key($fish_list);
												$meat_fish_detail[key($fish_list)]=key($fish_list);
												$counter_meat_fish+=$counter_meat_fish_day;
											}
										}
                                }
                                else
                                {
									$meat_list = array_intersect($fm['dinner'], $meat_meals);
									if(!empty($meat_list))
									{
										//echo "else of dinner meat meals.<pre>";print_r($meat_list);
										$meals[$day]['dinner']=array_diff($meals[$day]['dinner'], $meat_list);	
									}
									$fish_list = array_intersect($fm['dinner'], $fish_meals_keys);
									if(!empty($fish_list))
									{
										//echo "fish present in dinner meals.<pre>";print_r($fish_list);
										$meals[$day]['dinner']=array_diff($meals[$day]['dinner'], $fish_list);	
									}
								}
                                if (!empty($fm['pre_dinner_snack']) && $counter_meat_fish < 4 && $counter_meat_fish_day!=1) {
                                        $meat_list = array_intersect($fm['pre_dinner_snack'], $meat_meals);
                                        $counter_meat_fish_day=(empty($meat_list))? 0 : 1;
										if($counter_meat_fish_day==1){
											//echo "1st meat in breakfast".key($meat_list)."</br>";
											//print_r($meat_list);
											$meat_list=array_diff($meat_list,$meat_fish_detail);
											$meals[$day]['pre_dinner_snack']=array_diff($meals[$day]['pre_dinner_snack'], $meat_list);
											$meals[$day]['pre_dinner_snack']=array_diff($meals[$day]['pre_dinner_snack'], $meat_fish_detail);
											$meals[$day]['pre_dinner_snack'][key($meat_list)]=key($meat_list);
											$meat_fish_detail[key($meat_list)]=key($meat_list);
											
											$counter_meat_fish+=$counter_meat_fish_day;
											$fish_list = array_intersect($fm['pre_dinner_snack'], $fish_meals_keys);
											if(!empty($fish_list))
											{
												//echo "fish present in pre_dinner_snack meals.<pre>";print_r($fish_list);
												$meals[$day]['pre_dinner_snack']=array_diff($meals[$day]['pre_dinner_snack'], $fish_list);	
											}
										}
										else
										{
											$fish_list = array_intersect($fm['pre_dinner_snack'], $fish_meals_keys);
											$counter_meat_fish_day=(empty($fish_list))? 0 : 1;
											if($counter_meat_fish_day==1){
												//echo "1st fish in dinner".key($fish_list)."</br>";
												//print_r($fish_list);
												$fish_list=array_diff($fish_list,$meat_fish_detail);
												$meals[$day]['pre_dinner_snack']=array_diff($meals[$day]['pre_dinner_snack'], $fish_list);
												$meals[$day]['pre_dinner_snack']=array_diff($meals[$day]['pre_dinner_snack'], $meat_fish_detail);
												$meals[$day]['pre_dinner_snack'][key($fish_list)]=key($fish_list);
												$meat_fish_detail[key($fish_list)]=key($fish_list);
												$counter_meat_fish+=$counter_meat_fish_day;
											}
										}
										
                                }
                                 else
                                {
									$meat_list = array_intersect($fm['pre_dinner_snack'], $meat_meals);
									if(!empty($meat_list))
									{
										//echo "else of pre dinner snack meat meals.<pre>";print_r($meat_list);
										$meals[$day]['pre_dinner_snack']=array_diff($meals[$day]['pre_dinner_snack'], $meat_list);	
									}
									$fish_list = array_intersect($fm['pre_dinner_snack'], $fish_meals_keys);
									if(!empty($fish_list))
									{
										//echo "fish present in pre_dinner_snack meals.<pre>";print_r($fish_list);
										$meals[$day]['pre_dinner_snack']=array_diff($meals[$day]['pre_dinner_snack'], $fish_list);	
									}
								}
								if (!empty($fm['pre_lunch_snack'])) {
									if (isset($fm['pre_lunch_snack']) && $counter_meat_fish < 4 && $counter_meat_fish_day!=1) {
                                            $meat_list = array_intersect($fm['pre_lunch_snack'], $meat_meals);
											$counter_meat_fish_day=(empty($meat_list))? 0 : 1;
											if($counter_meat_fish_day==1){
												//echo "1st meat in breakfast".key($meat_list)."</br>";
												//print_r($meat_list);
												$meat_list=array_diff($meat_list,$meat_fish_detail);
												$meals[$day]['pre_lunch_snack']=array_diff($meals[$day]['pre_lunch_snack'], $meat_list);
												$meals[$day]['pre_lunch_snack']=array_diff($meals[$day]['pre_lunch_snack'], $meat_fish_detail);
												$meals[$day]['pre_lunch_snack'][key($meat_list)]=key($meat_list);
												$meat_fish_detail[key($meat_list)]=key($meat_list);
											
												$counter_meat_fish+=$counter_meat_fish_day;
												$fish_list = array_intersect($fm['pre_lunch_snack'], $fish_meals_keys);
												if(!empty($fish_list))
												{
													//echo "fish present in pre_lunch_snack meals.<pre>";print_r($fish_list);
													$meals[$day]['pre_lunch_snack']=array_diff($meals[$day]['pre_lunch_snack'], $fish_list);	
												}
											}
											else
											{
												$fish_list = array_intersect($fm['pre_lunch_snack'], $fish_meals_keys);
												$counter_meat_fish_day=(empty($fish_list))? 0 : 1;
												if($counter_meat_fish_day==1){
													//echo "1st fish in pre_lunch_snack".key($fish_list)."</br>";
													//print_r($fish_list);
													$fish_list=array_diff($fish_list,$meat_fish_detail);
													$meals[$day]['pre_lunch_snack']=array_diff($meals[$day]['pre_lunch_snack'], $fish_list);
													$meals[$day]['pre_lunch_snack']=array_diff($meals[$day]['pre_lunch_snack'], $meat_fish_detail);
													$meals[$day]['pre_lunch_snack'][key($fish_list)]=key($fish_list);
													$meat_fish_detail[key($fish_list)]=key($fish_list);
													$counter_meat_fish+=$counter_meat_fish_day;
												}
										}
                                    }
                                    else
                                    {
										$meat_list = array_intersect($fm['pre_lunch_snack'], $meat_meals);
										if(!empty($meat_list))
										{
											//echo "else of pre lunch snack meat meals.<pre>";print_r($meat_list);
											$meals[$day]['pre_lunch_snack']=array_diff($meals[$day]['pre_lunch_snack'], $meat_list);	
										}
										$fish_list = array_intersect($fm['pre_lunch_snack'], $fish_meals_keys);
										if(!empty($fish_list))
										{
											//echo "fish present in pre_lunch_snack meals.<pre>";print_r($fish_list);
											$meals[$day]['pre_lunch_snack']=array_diff($meals[$day]['pre_lunch_snack'], $fish_list);	
										}
									}
                                }
                               // echo "day:".$day."counter meat fish".$counter_meat_fish."</br>";
                        }
                }
				//echo "after repetion of meat fish:<pre>";print_r($meals);
				//echo "final counter meat".$counter_meat_fish;
				if($counter_meat_fish!=4){
               /* if (!empty($fish_meals)) {
					
                        $fish_meals_keys = array_keys($fish_meals);
                        //print_r($fish_meals_keys);
                        foreach ($meals as $day => $fm) {
                                if (!empty($fm['breakfast']) && $counter_fish < 2) {
                                        $result = array_intersect($fm['breakfast'], $fish_meals_keys);
                                        $counter_fish +=count($result);
                                        $meals[$day]['breakfast'] = $result;
                                }
                                if (!empty($fm['lunch']) && $counter_fish < 2) {
                                        $result = array_intersect($fm['lunch'], $fish_meals_keys);
                                        $counter_fish +=count($result);
                                        $meals[$day]['lunch'] = $result;
                                }
                                if (!empty($fm['dinner']) && $counter_fish < 2) {
                                        $result = array_intersect($fm['dinner'], $fish_meals_keys);
                                        $counter_fish +=count($result);
                                        $meals[$day]['dinner'] = $result;
                                }
                                if (!empty($fm['pre_dinner_snack']) && $counter_fish < 2) {
                                        $result = array_intersect($fm['pre_dinner_snack'], $fish_meals_keys);
                                        $counter_fish +=count($result);
                                        $meals[$day]['pre_dinner_snack'] = $result;
                                }
                                if (isset($fm['pre_lunch_snack']) && $counter_fish < 2) {
                                        if (!empty($fm['pre_lunch_snack'])) {
                                                $result = array_intersect($fm['pre_lunch_snack'], $fish_meals_keys);
                                                $counter_fish +=count($result);
                                                $meals[$day]['pre_lunch_snack'] = $result;
                                        }
                                }
                        }
                }*/
			}
			
               
        }
         //echo "<pre>befire meallll"; print_r($meals);
        if (in_array('pescetarian', $nutrition_type)) {
                if (!empty($fish_meals)) {
					//echo "fish meals<pre>";print_r($fish_meals);
                        $fish_meals_keys = array_keys($fish_meals);
                        foreach ($meals as $day => $fm) {
                                if (!empty($fm['breakfast']) && $counter_fish < 4) {
                                        $result = array_intersect($fm['breakfast'], $fish_meals_keys);
                                        $counter_fish +=count($result);
                                        $meals[$day]['breakfast'] = $result;
                                        
                                        //echo "breakfast<pre>";print_r($meals[$day]['breakfast']);
                                }
                                if (!empty($fm['lunch']) && $counter_fish < 4) {
                                        $result = array_intersect($fm['lunch'], $fish_meals_keys);
                                        $counter_fish +=count($result);
                                        $meals[$day]['lunch'] = $result;
                                        //echo "lunch<pre>";print_r($meals[$day]['lunch']);
                                }
                                if (!empty($fm['dinner']) && $counter_fish < 4) {
                                        $result = array_intersect($fm['dinner'], $fish_meals_keys);
                                        $counter_fish +=count($result);
                                        //$meals[$day]['dinner'] = $result;
                                       // echo "dinner<pre>";print_r($meals[$day]['dinner']);
                                }
                                if (!empty($fm['pre_dinner_snack']) && $counter_fish < 4) {
                                        $result = array_intersect($fm['pre_dinner_snack'], $fish_meals_keys);
                                        $counter_fish +=count($result);
										$meals[$day]['pre_dinner_snack'] = $result;
                                        //echo "pre_dinner_snack<pre>";print_r($meals[$day]['pre_dinner_snack']);
                                }
                                if (isset($fm['pre_lunch_snack']) && $counter_fish < 4) {
                                        if (!empty($fm['pre_lunch_snack'])) {
                                                $result = array_intersect($fm['pre_lunch_snack'], $fish_meals_keys);
                                                $counter_fish +=count($result);
                                                $meals[$day]['pre_lunch_snack'] = $result;
													
                                        }
                                }
                        }
                }
        }
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
}

echo "finallllyyyyy.....<pre>";
print_r($meals);
exit;
$difficulties = array(1 => 'leicht', 2 => 'normal', 3 => 'schwer');
$ommitable = array(0 => 'no', 1 => 'yes');
$html = '';
if (!empty($final_res)) {

        $html = '<table border=1 >';
        $html .= '<tr><th>Attribute</th><th>value</th><th>Unit</th></tr>';
        foreach ($final_res as $k => $fr) {
                if ($k == 0) {
                        $html .= '<tr>';
                        $html .= '<td>Difficulty</td>';
                        $html .= '<td>' . $difficulties[$fr->difficulty] . '</td>';
                        $html .= '<td>&nbsp;</td>';
                        $html .= '</tr>';

                        $html .= '<tr>';
                        $html .= '<td>Preparation Time</td>';
                        $html .= '<td>' . $fr->preparation_time . '</td>';
                        $html .= '<td>min</td>';
                        $html .= '</tr>';
                }

                $html .= '<tr>';
                $html .= '<td>Ingredient ' . ($k + 1) . '</td>';
                $html .= '<td>' . $fr->ingredient_name . '</td>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';

                $html .= '<tr>';
                $html .= '<td>Ingredient ' . ($k + 1) . ' Quantity</td>';
                $html .= '<td>' . $fr->quantity . '</td>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';

                $html .= '<tr>';
                $html .= '<td>Ingredient ' . ($k + 1) . ' Real Weight</td>';
                $html .= '<td>' . $fr->weight . '</td>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';

                $html .= '<tr>';
                $html .= '<td>Ingredient ' . ($k + 1) . ' Omittable</td>';
                $html .= '<td>' . $ommitable[$fr->is_ommitable] . '</td>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';
                $exhn = '';
                if (isset($exchangable_foods[$fr->id][$fr->ingredient_name])) {
                        $exhn = $exchangable_foods[$fr->id][$fr->ingredient_name];
                }
                $html .= '<tr>';
                $html .= '<td>Ingredient ' . ($k + 1) . ' Exchangeable With</td>';
                $html .= '<td>' . $exhn . '</td>';

                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';
        }
        if (end($final_res)) {
                $html .= '<tr>';
                $html .= '<td>Preparation Instruction</td>';
                $html .= '<td>' . $final_res[0]->instruction . '</td>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';
                $html .= '<tr>';

                $html .= '<td>Popularity</td>';
                $html .= '<td>' . $final_res[0]->popularity . '</td>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';

                $html .= '<td>Source</td>';
                $html .= '<td>' . $final_res[0]->source . '</td>';
                $html .= '<td>&nbsp;</td>';
                $html .= '</tr>';
        }
        $html .= '</table>';
}
echo $html;

