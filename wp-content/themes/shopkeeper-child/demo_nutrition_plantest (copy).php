<?php

/*
 * Template Name: Demo Nutrition creation logic
 */

global $wpdb;
//$prefix = $wpdb->prefix;
$prefix = 'up_';

$nutrition_types_array = array(
    'no_special' => 'cw_traditional',
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
//print_r($_GET);exit;
$cur_weight= $_GET['cur_weight']?$_GET['cur_weight']:82.2;
$desired_weight= $_GET['desired_weight']?$_GET['desired_weight']:60;
$gender= $_GET['gender']?$_GET['gender']:'m';
$age= $_GET['age']?$_GET['age']:32;
$height= $_GET['height']?$_GET['height']:174;
$daily_activity= $_GET['daily_activity']?$_GET['daily_activity']:24;
$nutrition_type= $_GET['nutrition_type']?$_GET['nutrition_type']:'pescetarian';
$allergies= $_GET['allergies']?$_GET['allergies']:'';
$exclude= $_GET['exclude']?$_GET['exclude']:'';
$sweet_tooth= $_GET['sweet_tooth']?$_GET['sweet_tooth']:'yes';
$is_time_to_cook= $_GET['perpare_time']?$_GET['perpare_time']:'little';
$where_food_buy= $_GET['food']?$_GET['food']:'cheap';
$most_buy= $_GET['buy']?$_GET['buy']:'both';
$plan= $_GET['plan']?$_GET['plan']:164;
$nutrion_plan_detail = array(
    'cur_weight' => $cur_weight,
    'desired_weight' => $desired_weight,
    'gender' => $gender,
    'age' => $age,
    'height' => $height,
    'daily_activity' => $daily_activity,
    'nutrition_type' => $nutrition_type,
    //'allergies' => 'lactose,fructose,histamine,gluten,glutamat,sucrose',
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
                if ($n != 'no_special') {

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

$synonyms_exclude = $exclude;

/* $available_time_array = array('little' => 'MINUTE(mins.preparation_time) <40', 'normal' => 'MINUTE(mins.preparation_time) >40 AND MINUTE(mins.preparation_time) <60', 'much' => 'MINUTE(mins.preparation_time) >60'); */
//$available_time_array = array('little' => 40, 'normal' => 60, 'much' => 200);
//$available_time_condi = 'MINUTE(mins.preparation_time) <=' . $available_time_array[$available_time];
$available_time_array = array('little' => 'AND MINUTE(mins.preparation_time) <= 45 ', 'normal' => '', 'much' => 'AND MINUTE(mins.preparation_time) >45');
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


$query1 = "SELECT meal_id, COUNT( * ) AS total_ingredient
FROM " . $prefix . "meal_ingredients
GROUP BY meal_id";
//echo $query1."<br>";
$res1 = $wpdb->get_results($query1);

$synonyms_que = '';
if (!empty($nutrion_plan_detail['exclude'])) {
        $synonyms_que = 'AND NOT FIND_IN_SET( f.synonym_of,  "' . $nutrion_plan_detail['exclude'] . '" ) ';
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
$q2 = "select meal.meal_id,meal.name as ingredient_name ,exchangable_with_ingredient,group_concat(f.name) as fname,group_concat(f.id),count(*) 
from " . $prefix . "meal_ingredients meal, " . $prefix . "foods f ," . $prefix . "meal_instructions mins
where not find_in_set(replace(meal.name,',',''),(select group_concat(replace(um.name,',',''))
from " . $prefix . "meal_ingredients um, " . $prefix . "foods f where um.meal_id=meal.meal_id and um.name=f.name " . $res_nut_typeval . $res_int_typeval . "))
and FIND_IN_SET(f.id, exchangable_with_ingredient) 
and exchangable_with_ingredient<>'' 
" . $res_nut_typeval . $res_int_typeval . $synonyms_que . "
AND mins.meal_id = meal.meal_id " . $available_time_condi . "
group by meal.name, meal.meal_id";
//echo $q2."<br><br>";
$res2 = $wpdb->get_results($q2);
//echo "<pre>";print_r($res2);

$res_meals = $exchangable_foods = $prioritise_meals = array();
foreach ($res1 as $k1 => $r1) {

        if (!empty($res3)) {
                foreach ($res3 as $r3) {
                        if (($r1->meal_id == $r3->meal_id) && ($r1->total_ingredient != $r3->main_count)) {
                                $temp = 0;
                                //echo $r1->meal_id . ' count = ' . $r3->main_count . '<br>';
                                foreach ($res2 as $r) {

                                        if ($r->meal_id == $r1->meal_id) {
                                                $temp++;
                                                $exchangable_foods[$r->meal_id][$r->ingredient_name] = $r->fname;
                                        }
                                }
                                $final_count = $r3->main_count + $temp;

                                if ($final_count == $r1->total_ingredient) {
                                        $res_meals[] = $r1->meal_id;
                                }
                        } else if (($r1->meal_id == $r3->meal_id) && ($r1->total_ingredient == $r3->main_count)) {
                                //echo "=else --";
                                $final_count = $r3->main_count;
                                //$res_meals[] = $r1->meal_id;
                                $prioritise_meals[] = $r1->meal_id;
                        }
                }
        }
}

$final_meal = array_merge($prioritise_meals, $res_meals);
$final_meal = implode(',', $final_meal);
echo 'here';
echo ' final ' . $final_meal . '<br><br>';
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
}
echo 'test123';

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
//echo "<pre";print_r($final_res);

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
        $RMR = (65.51 + (9.6 * $current_weight) + (1.8 * $height) - (4.7 * $age)); //-$PAL*(100-25/100);
} else {
        $RMR = (66.47 + (13.7 * $current_weight) + (5 * $height) - (6.8 * $age)); //-$PAL*(100-25);
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

$weeknum = strtolower(date('W'));

/* sunday is cheat day -- 0% every uneven week & +10% every even week */
/* if ($weeknum % 2 == 0) {
  $day_percentage = $day_value / 100;
  } else {
  $day_percentage = 0;
  }

  $NPES = $tee * ((100 / 100) + ($day_percentage));
  echo 'Adjust Total Energy Expenditure per day (NPES): ' . $NPES . '</br>'; */

// Adjust Total Energy Expenditure per week  
//Weight of the last week in kg - (sum of kcal deficit of the entire week * (1/7000))
$plan_week = $weektype[$period];

$NPES_perweek = $current_weight - ($tee * (((-$plan_week['mon']) / 100) + ((-$plan_week['tue']) / 100) + ((-$plan_week['wed']) / 100) + ((-$plan_week['thus']) / 100) + ((-$plan_week['fri']) / 100) + ((-$plan_week['sat']) / 100) - ($plan_week['sun'] / 100)) * ((1 / 7000)));
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
echo 'here';
/* resultant meal's kcal value */
$meal_kcal = array();

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
//echo "<pre>";print_R($meal_kcal);
//$meal_kcal[2] = 585.38;
// C.7   exclude meals that have +/- 15% kcal

/* par day NPES value */
/* $days_npes_cal = array();
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
  if (!empty($meal_kcal)) {
  foreach ($meal_kcal as $mid => $m) {
  if ($m >= $min_variance && $m <= $max_variance) {
  $meals_matches[] = $mid;
  }
  }
  }
  if (!empty($meals_matches)) {
  $days_npes_cal[$day][$k]['meals'] = implode(',', $meals_matches);
  } else {
  $days_npes_cal[$day][$k]['meals'] = '';
  }
  }
  }
  //echo "==" . $day_percentage . '==';
  }
  } */
//echo "<pre>";print_r($days_npes_cal);

/* Meals priority (weighted sum of % variance) */
//call sp for all micro value calculation
$priority_micros = $wpdb->get_results("CALL prioritise_val('" . $final_meal . "');");
//print_r($priority_micros);
//price level and fat burner calculation
$priority_micros_price = $wpdb->get_results("CALL prioritise_micro('" . $final_meal . "');", OBJECT_K);
//echo "<pre>";print_r($priority_micros_price);
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
                                /* if (!empty($meal_kcal)) {
                                  foreach ($meal_kcal as $mid => $m) {
                                  if ($m >= $min_variance && $m <= $max_variance) {
                                  $meals_matches[] = $mid;
                                  }
                                  }
                                  }
                                  if (!empty($meals_matches)) {
                                  $days_npes_cal[$day][$k]['meals'] = implode(',', $meals_matches);
                                  } else {
                                  $days_npes_cal[$day][$k]['meals'] = '';
                                  } */
                        }
                }
                //echo "==" . $day_percentage . '==';
        }
}
//echo "<pre>";print_r($days_npes_cal);
$ideal_values_for_meal = array(
    'Kilokalorien' => 450.35,
    'Protein' => 40,
    'Fett' => 20,
    'Kohlenhydrate' => 40,
    'Vorbereitungszeit' => 'wenig',
    'Preis/Qualität' => 1,
    'Gesättigte Fettsäuren' => 6.86,
    'Einfach ungesättigte Fettsäuren' => 6.86,
    'Mehrfach ungesättigte Fettsäuren' => 6.86,
    'Cholesterin' => 0,
    'Vitamin A' => 0.22,
    'Vitamin C' => 26.13,
    'Vitamin D' => 5.5,
    'Vitamin E' => 3.3,
    'Vitamin K' => 17.05,
    'Vitamin B1' => 0.30,
    'Vitamin B2' => 0.39,
    'Vitamin B6' => 0.33,
    'Vitamin B12' => 0.83,
    'Biotin' => 12.38,
    'Folsäure' => 154,
    'Niacin' => 3.3,
    'Panthotensäure' => 1.65,
    'Calcium [Ca]' => 275,
    'Chlor [Cl]' => 228.25,
    'Kalium [K]' => 550,
    'Magnesium [Mg]' => 82.5,
    'Natrium [Na]' => 178.75,
    'Phosphor [P]' => 195.5,
    'Schwefel [S]' => 0,
    'Kupfer [Cu]' => 0.34,
    'Eisen [Fe]' => 4.13,
    'Fluor [F]' => 0.83,
    'Mangan [Mn]' => 0.96,
    'Jod [J]' => 55,
    'Zink [Zn]' => 1.93,
    /* 'Alanin' => 0,
      'Arginin' => 0,
      'Aspargin' => 0,
      'Asparginsäure' => 0, */
    'Cystein' => 86.16,
    /* 'Glutamin' => 0,
      'Glutaminsäure' => 0,
      'Glycerin' => 0,
      'Histidin' => 0,
      'Isoleucin' => 0, */
    'Leucin' => 810.81,
    'Lysin' => 623.7,
    /* 'Methionin' => 0,
      'Phenylalanin' => 0,
      'Prolin' => 0,
      'Serin' => 0, */
    'Threonin' => 311.85,
    'Tryptophan' => 83.16,
    'Tyrosin' => 0,
    'Valin' => 540.54,
);

$variance = $micros_array = array();
$html = '';
//echo "<pre>";print_r($priority_micros);
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

                $temp_price_level = @number_format($priority_micros_price[$pm->id]->price_level / $priority_micros_price[$pm->id]->total_ingredient, 2);

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

                $Cholesterin = @number_format(abs($pm->{'Cholesterin'} - $ideal_values_for_meal['Cholesterin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Cholesterin'}, 4) . '</td><td>' . $Cholesterin . '%</td></tr>';
                $sum +=$Cholesterin;

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

                $Schwefel = @number_format(abs($pm->{'Schwefel [S]'} - $ideal_values_for_meal['Schwefel [S]']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Schwefel [S]'}, 4) . '</td><td>' . $Schwefel . '%</td></tr>';
                $sum +=$Schwefel;

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

                $Tyrosin = @number_format(abs($pm->{'Tyrosin'} - $ideal_values_for_meal['Tyrosin']) * 0.1, 2);
                $html .='<tr><td>' . @number_format($pm->{'Tyrosin'}, 4) . '</td><td>' . $Tyrosin . '%</td></tr>';
                $sum +=$Tyrosin;

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
        }
        if (end($priority_micros)) {
                $html .='</table></td>';
        }
        $html .='</tr></table>';
}

echo $html;

if (!empty($variance)) {
        /* $min = min($variance);  // 1

          echo $key = array_keys($variance, $min);
          echo "<pre>"; */
        //print_r($variance);
        asort($variance);
        echo "<pre>";
        //print_r($variance);
        echo "<pre>";
        //print_r($micros_array);
}

/* difference calculation for meal */

/* D. c)balance out the variance */
$rem_meals = array();
//echo "<pre>";print_r($days_npes_cal);
//echo "<pre>";print_r($micros_array);
if ($period == 12) {
//for 12 week plan

        $meals = select_balance_meals($rem_meals, $variance, $micros_array, $days_npes_cal, $per_day_no_of_melas, 40, 20, 40, 0);

        //echo "test == <pre>";print_r($meals);
} else {
//for 4 week plans
        $meals = select_balance_meals($rem_meals, $variance, $micros_array, $days_npes_cal, $per_day_no_of_melas, 45, 17.5, 37.5, 0);
}
$micros_diff = array();

$fmeals = array();

function select_balance_meals($rem_meals = array(), $variance, $micros_array, $days_npes_cal, $per_day_no_of_melas = 4, $protin, $fat, $carbo, $i = 0, $fmeals = array()) {
        $temp_array = array();
        if (!empty($micros_array)) {
                /* check breakfast meal */
                foreach ($variance as $k => $m) {
                        $pro_diff = abs((($protin / $micros_array[$k]['Protein']) * 100) - 100);
                        $fat_diff = abs((($fat / $micros_array[$k]['Fett']) * 100) - 100);
                        $carb_diff = abs((($carbo / $micros_array[$k]['Kohlenhydrate']) * 100) - 100);
                        $kcal = $micros_array[$k]['kcal'];
                        foreach ($days_npes_cal as $d => $v) {

                                //print_r($kcal);
                                //print_r($v['breakfast'][1]);
                                $breakfast = $lunch = $dinner = $pre_lunch = $pre_dinner = 0;
                                if ($kcal >= $v['breakfast'][1] && $kcal <= $v['breakfast'][2]) {

                                        $kcal_diff = abs((($kcal / $v['breakfast'][0] * 100) - 100) * 1.5);

                                        $diff = $kcal_diff + $pro_diff + $fat_diff + $carb_diff;

                                        //$micros_diff[$d]['breakfast'][$micros_array[$k]['id']] = $diff;
                                        $micros_diff[$d]['breakfast'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                        $breakfast = $micros_array[$k]['id'];
                                } else {

                                        $micros_diff[$d]['breakfast'][$micros_array[$k]['id']] = '';
                                        unset($micros_diff[$d]['breakfast'][$micros_array[$k]['id']]);
                                }

                                if ($breakfast > 0) {

                                        if ($kcal >= $v['lunch'][1] && $kcal <= $v['lunch'][2]) {

                                                $pro_diff_l = abs(2 * $protin - $micros_array[$breakfast]['Protein']);
                                                $fat_diff_l = abs(2 * $fat - $micros_array[$breakfast]['Fett']);
                                                $carb_diff_l = abs(2 * $carbo - $micros_array[$breakfast]['Kohlenhydrate']);

                                                $kcal_diff = abs((($kcal / $v['lunch'][0] * 100) - 100) * 1.5);

                                                $diff = $kcal_diff + $pro_diff_l + $fat_diff_l + $carb_diff_l;

                                                //$micros_diff[$d]['lunch'][$micros_array[$k]['id']] = $diff;
                                                $micros_diff[$d]['lunch'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                $lunch = $micros_array[$k]['id'];
                                        }
                                } else {
                                        $micros_diff[$d]['lunch'][$micros_array[$k]['id']] = '';
                                        unset($micros_diff[$d]['lunch'][$micros_array[$k]['id']]);
                                }

                                if ($lunch > 0) {
                                        if ($kcal >= $v['dinner'][1] && $kcal <= $v['dinner'][2]) {

                                                $pro_diff_l = abs(2 * $protin - $micros_array[$lunch]['Protein']);
                                                $fat_diff_l = abs(2 * $fat - $micros_array[$lunch]['Fett']);
                                                $carb_diff_l = abs(2 * $carbo - $micros_array[$lunch]['Kohlenhydrate']);

                                                $kcal_diff = abs((($kcal / $v['dinner'][0] * 100) - 100) * 1.5);

                                                $diff = $kcal_diff + $pro_diff_l + $fat_diff_l + $carb_diff_l;

                                                //$micros_diff[$d]['dinner'][$micros_array[$k]['id']] = $diff;
                                                $micros_diff[$d]['dinner'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                $dinner = $micros_array[$k]['id'];
                                        }
                                } else {
                                        $micros_diff[$d]['dinner'][$micros_array[$k]['id']] = '';
                                        unset($micros_diff[$d]['dinner'][$micros_array[$k]['id']]);
                                }


                                if ($dinner > 0) {
                                        //echo $kcal.'--';
                                        //echo $v['pre_dinner_snack'][1].'<br>';
                                        if ($kcal >= $v['pre_dinner_snack'][1] && $kcal <= $v['pre_dinner_snack'][2]) {

                                                $pro_diff_l = abs(2 * $protin - $micros_array[$dinner]['Protein']);
                                                $fat_diff_l = abs(2 * $fat - $micros_array[$dinner]['Fett']);
                                                $carb_diff_l = abs(2 * $carbo - $micros_array[$dinner]['Kohlenhydrate']);

                                                $kcal_diff = abs((($kcal / $v['pre_dinner_snack'][0] * 100) - 100) * 1.5);

                                                $diff = $kcal_diff + $pro_diff_l + $fat_diff_l + $carb_diff_l;

                                                //$micros_diff[$d]['pre_dinner_snack'][$micros_array[$k]['id']] = $diff;
                                                $micros_diff[$d]['pre_dinner_snack'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                $snack = $micros_array[$k]['id'];
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
                                                        $micros_diff[$d]['pre_lunch_snack'][$micros_array[$k]['id']] = $micros_array[$k]['id'];
                                                        $snack1 = $micros_array[$k]['id'];
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
echo "<pre>";print_r($meals);

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
                //echo "<pre>meallll"; print_r($meals);
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
        if (in_array('nospecial', $nutrition_type)) {
                $meat_meals = $wpdb->get_results('call meat_and_fish_meals("'.$final_meal.'","fleisch")',OBJECT_K);
                
                //print_r($meat_meals);
                $fish_meals = $wpdb->get_results('call meat_and_fish_meals("'.$final_meal.'","Fisch")',OBJECT_K);
                /*print_r($fish_meals);
                foreach ($meals as $day => $fm) {
                        
                }*/
                
        }
        if (in_array('pescetarian', $nutrition_type)) {
                $fish_meals = $wpdb->get_results('call meat_and_fish_meals("'.$final_meal.'","Fisch")',OBJECT_K);
                /*print_r($fish_meals);
                foreach ($meals as $day => $fm) {
                        
                }*/
        }

        //Repetition of meals per week

        if ($plan != 162) {
                
        }
        
}
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

