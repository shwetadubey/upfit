<?php

/*
 * Template Name: Nutrition creation logic
 */

global $wpdb;

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
$nutrion_plan_detail = array(
    'cur_weight' => 86.2,
    'desired_weight' => 60,
    'gender' => 'm',
    'age' => 32,
    'height' => 174,
    'daily_activity' => 50,
    'nutrition_type' => 'flexitarian,paleo',
    'allergies' => 'lactose,fructose,histamine,gluten,glutamat,sucrose',
    'nuts' => 'peanut,hazelnut,almond',
    'fruit' => '',
    'exclude' => 'Aal,Acerola',
    'sweet_tooth' => 'yes',
    'is_time_to_cook' => 'little',
    'where_food_buy' => 'cheap',
    'most_buy' => 'quality',
    'plan' => 164
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
$where_food_buy = $nutrion_plan_detail['where_food_buy']; // Cheap , Normal, Premium
$what_matters = $nutrion_plan_detail['most_buy']; // Price , Quality, Both
$plan = $nutrion_plan_detail['plan']; //12 weeks upfit superstar


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
                                $res_nut_typeval[] = ' f.cw_vegetarian=1 OR f.cw_vegan=1 ';
                                $res_nut_typeval1[] = ' f1.cw_vegetarian=1 OR f1.cw_vegan=1 ';
                        } else {
                                $val = $nutrition_types_array[$n];
                                $res_nut_type .= 'IFNULL(f.' . $val . ',0) as f_' . $val . ',';
                                $res_nut_type1 .= 'IFNULL(f1.' . $val . ',0) as f1_' . $val . ',';
                                $res_nut_typeval[] = 'f.' . $val . '= 1 ';
                                $res_nut_typeval1[] = 'f1.' . $val . '= 1 ';

                                $nut_type_cond[] = 'f_' . $val . '==0';
                                $nut_type_cond1[] = 'f_' . $val . '==1';

                                $f1nut_type_cond[] = 'f1_' . $val . '==0';
                                $f1nut_type_cond1[] = 'f1_' . $val . '==1';
                        }
                }
        }
        $res_nut_typeval = implode('AND ', $res_nut_typeval);
        $res_nut_typeval1 = implode('AND ', $res_nut_typeval1);

        $nut_type_cond = implode(' && ', $nut_type_cond);
        $nut_type_cond1 = implode(' && ', $nut_type_cond1);
        $f1nut_type_cond = implode(' && ', $f1nut_type_cond);
        $f1nut_type_cond1 = implode(' && ', $f1nut_type_cond1);
}

$res_int_type = $res_int_type1 = $res_int_typeval = $res_int_typeval1 = '';
if (!empty($intolerance)) {

        foreach ($intolerance as $i) {

                $intal = $intolerance_array[$i];
                $res_int_type .= 'IFNULL(f.' . $intal . ',0) as f_' . $intal . ',';
                $res_int_type1 .= 'IFNULL(f1.' . $intal . ',0) as f1_' . $intal . ',';
                $res_int_typeval[] = 'f.' . $intal . '=0 ';
                $res_int_typeval1[] = 'f1.' . $intal . '=0 ';
        }

        $res_int_typeval = implode('AND ', $res_int_typeval);
        //$select_field_int_typeval = implode(',', $res_int_type);
        $res_int_typeval1 = implode('AND ', $res_int_typeval1);
        //$select_field_int_typeval1 = implode(',', $res_int_type1);
}

$synonyms_exclude = $exclude;

/* $available_time_array = array('little' => 'MINUTE(mins.preparation_time) <40', 'normal' => 'MINUTE(mins.preparation_time) >40 AND MINUTE(mins.preparation_time) <60', 'much' => 'MINUTE(mins.preparation_time) >60'); */
$available_time_array = array('little' => 40, 'normal' => 60, 'much' => 200);
$available_time_condi = 'MINUTE(mins.preparation_time) <=' . $available_time_array[$available_time];
$prefix = $wpdb->prefix;

echo '-------------</br>';
echo 'current Weight:' . $current_weight . '</br>';
echo 'Desired Weight:' . $desired_weight . '</br>';
echo 'Gender:' . $gender . '</br>';
echo 'Age:' . $age . '</br>';
echo 'Height:' . $height . '</br>';
echo 'Activity Level:' . $activity_level . '</br>';
echo 'Nutrition types: ' . implode(',', $nutrition_type) . '</br>';
echo 'Intolerance: ' . implode(',', $intolerance) . '</br>';
echo 'Available time:' . $available_time . '</br>';
echo 'Plan : ' . $plan . '</br></br>';


$meals_query = 'select  m.id,m.name,mi.meal_id,f.id as i_id,mi.name as i_name,mi.exchangable_with_ingredient,mins.preparation_time,' . trim($res_nut_type, ',') . ',' . trim($res_int_type, ',') . ',f.synonym_of as f_synonym_of,IFNULL(f1.id,0) as fid,' . trim($res_nut_type1, ',') . ',' . trim($res_int_type1, ',') . ',f1.synonym_of as f1_synonym_of from ' . $prefix . 'meals m'
        . ' Inner join ' . $prefix . 'meal_ingredients mi on mi.meal_id = m.id'
        . ' Inner join ' . $prefix . 'meal_instructions mins on m.id= mins.meal_id And ' . $available_time_condi
        . ' LEFT join ' . $prefix . 'foods f on f.name = mi.name and ((' . trim($res_nut_typeval, 'AND') . ') OR (' . trim($res_int_typeval, 'AND') . ') )'
        . 'LEFT JOIN ' . $prefix . 'foods f1 ON FIND_IN_SET( f1.id, mi.exchangable_with_ingredient )'
        . 'AND (
  (
  "' . $res_nut_typeval1 . '"
  )
  OR (
  "' . $res_int_typeval1 . '"
  )
  )'
        . ' order by m.id';

//echo $meals_query;
$meals_array = $wpdb->get_results($meals_query);
echo "<pre>";print_r($meals_array);exit;
if (!empty($meals_array)) {

        $temp_exclude_meal_ids = 0;
        $final_meal_id = '';

        echo $nut_type_cond1;
        echo $nut_type_cond;
        echo $f1nut_type_cond1;
        /* $nut_type_cond1;
          $nut_type_cond;
          $f1nut_type_cond;
          $f1nut_type_cond1; */

        $flex = $types =array();

        foreach ($meals_array as $k => $m) {
                echo "<br>";
                if ($temp_exclude_meal_ids != $m->id) {
                        $exchng_ingr = $m->exchangable_with_ingredient;
                        $food_id = $m->fid;
                        $f_synonyms = $m->f_synonym_of;
                        $f1_synonyms = $m->f1_synonym_of;
                        $f_cw_vegetarian = $m->f_cw_vegetarian;
                        $f_cw_vegan = $m->f_cw_vegan;
                        $f_cw_paleo = $m->f_cw_paleo;
                        $f1_cw_vegetarian = $m->f1_cw_vegetarian;
                        $f1_cw_vegan = $m->f1_cw_vegan;
                        $f1_cw_paleo = $m->f1_cw_paleo;
                        
                         $cond1 = str_replace('f_cw_vegetarian', $m->f_cw_vegetarian, $nut_type_cond1);
                          $cond1 = str_replace('f_cw_vegan', $m->f_cw_vegan, $cond1);
                          $cond1 = str_replace('f_cw_paleo', $m->f_cw_paleo, $cond1);

                          echo $cond1.'---<br>';

                          $cond = str_replace('f_cw_vegetarian', $m->f_cw_vegetarian, $nut_type_cond);
                          $cond = str_replace('f_cw_vegan', $m->f_cw_vegan, $cond);
                          $cond = str_replace('f_cw_paleo', $m->f_cw_paleo, $cond);
                          echo $cond.'---<br>';

                          $f1cond1 = str_replace('f1_cw_vegetarian', $m->f1_cw_vegetarian, $f1nut_type_cond1);
                          $f1cond1 = str_replace('f1_cw_vegan', $m->f1_cw_vegan, $f1cond1);
                          $f1cond1 = str_replace('f1_cw_paleo', $m->f1_cw_paleo, $f1cond1);
                          echo $f1cond1.'---<br>';
                          
                          if (($m->f_cw_vegetarian==1 || $m->f_cw_vegan==1) && $m->f_cw_paleo==1) {
                                echo "if -- ";
                        } else if ($cond && !empty($exchng_ingr) && !empty($food_id) && $f1cond1) {
                                echo "else if -- ";
                        } else {
                                echo "else -- ";
                                $temp_exclude_meal_ids = $m->id;
                                $final_meal_id .= $m->id . ',';
                        }
                        
                       /* $cond1 = str_replace('f_cw_vegetarian', $f_cw_vegetarian, $nut_type_cond1);
                          $cond1 = str_replace('f_cw_vegan', $f_cw_vegan, $cond1);
                          $cond1 = str_replace('f_cw_paleo', $f_cw_paleo, $cond1);

                          echo $cond1.'---';

                          $cond = str_replace('f_cw_vegetarian', $f_cw_vegetarian, $nut_type_cond);
                          $cond = str_replace('f_cw_vegan', $f_cw_vegan, $cond);
                          $cond = str_replace('f_cw_paleo', $f_cw_paleo, $cond);
                          echo $cond.'---';

                          $f1cond1 = str_replace('f1_cw_vegetarian', $f1_cw_vegetarian, $f1nut_type_cond1);
                          $f1cond1 = str_replace('f1_cw_vegan', $f1_cw_vegan, $f1cond1);
                          $f1cond1 = str_replace('f1_cw_paleo', $f1_cw_paleo, $f1cond1);
                          echo $f1cond1.'---';*/

                          echo ' m '.$m->id . '---'; 

                        /*if (!empty($nutrition_type)) {

                                foreach ($nutrition_type as $n) {
                                        if ($n != 'no_special') {
                                                if ($n == 'flexitarian') {
                                                        $flex1 = 'f_cw_vegetarian';
                                                        $flex2 = 'f_cw_vegan';
                                                        
                                                } else {
                                                       $types[] = 'f_cw_paleo'; 
                                                }
                                        }
                                }
                        }*/
                        
                        /*if (($m->f_cw_vegetarian == 1 || $m->f_cw_vegan == 1) && $m->f_cw_paleo == 1) {
                                echo "if -- ";
                        } else if ((($m->f_cw_vegetarian == 0 || $m->f_cw_vegan == 0) && $m->f_cw_paleo == 0) && !empty($exchng_ingr) && !empty($food_id) && (($m->f1_cw_vegetarian == 1 || $m->f1_cw_vegan == 1) && $m->f1_cw_paleo == 1)) {
                                echo "else if -- ";
                        } else {
                                echo "else -- ";
                                $temp_exclude_meal_ids = $m->id;
                                $final_meal_id .= $m->id . ',';
                        }*/
                }
        }

        echo "----" . $final_meal_id;
}

function veg(){
        
}

function vegan(){
        
}

function paleo(){
        
}
function pescaterian(){
        
}

function flex(){
        
}
/* echo 'CALL new_logic("'.$res_nut_typeval.'","'.$res_nut_typeval1.'","'.$res_int_typeval.'","'.$res_int_typeval1.'","'.$available_time_array[$available_time].'")'; */
/* $meals_array = $wpdb->get_results('CALL new_logic("' . $res_nut_typeval . '","' . $res_nut_typeval1 . '","' . $res_int_typeval . '","' . $res_int_typeval1 . '","' . $available_time_array[$available_time] . '")'); */
//echo "<pre>";print_r($meals_array);

$html = '<table>';
$html .= '<tr>';
$html .= '<th>id</th>';
$html .= '<th>Meal name</th>';
$html .= '<th>Ingeredient name</th>';
$html .= '<th>exchangable with ingredient</th>';
$html .= '<th>compatible with Vegetarian</th>';
$html .= '<th>compatible with vegan</th>';
$html .= '<th>compatible with pescetarian</th>';
$html .= '<th>compatible with paleo</th>';
$html .= '<th>Food id</th>';
$html .= '<th>compatible with Vegetarian</th>';
$html .= '<th>compatible with vegan</th>';
$html .= '<th>compatible with pescetarian</th>';
$html .= '<th>compatible with paleo</th>';
$html .= '</tr>';
foreach ($meals_array as $m) {
        $html .= '<tr>';
        $html .= '<td>' . $m->id . '</td>';
        $html .= '<td>' . $m->name . '</td>';
        $html .= '<td>' . $m->i_name . '</td>';
        $html .= '<td>' . $m->exchangable_with_ingredient . '</td>';
        $html .= '<td>' . $m->og_veg . '</td>';
        $html .= '<td>' . $m->og_vegan . '</td>';
        $html .= '<td>' . $m->og_pescetarian . '</td>';
        $html .= '<td>' . $m->og_paleo . '</td>';
        $html .= '<td>' . $m->fid . '</td>';
        $html .= '<td>' . $m->vegitarian . '</td>';
        $html .= '<td>' . $m->vegan . '</td>';
        $html .= '<td>' . $m->pescetarian . '</td>';
        $html .= '<td>' . $m->paleo . '</td>';
        $html .= '</tr>';
}
$html .= '</table>';
echo $html;
/* get plan data */
$planData = get_post($plan);

/* get chosen plan period (4 or 12) */
$period = get_post_meta($plan, 'plan_period', true);

$tee = 0;

/* echo 'current weight----->'.$current_weight.'</br>';
  echo '$desired_weight----->'.$desired_weight.'</br>';
  echo 'gender----->'.$gender.'</br>';
  echo 'age----->'.$age.'</br>';
  echo 'height----->'.$height.'</br>';
  echo 'activity_level----->'.$activity_level.'</br>';
  echo 'nutrition_type----->'.$nutrition_type.'</br>';
  print_r($intolerance);
  print_r($weight_loss);
  echo 'sweet_tooth----->'.$sweet_tooth.'</br>';
  echo '$available_time----->'.$available_time.'</br>';
  echo '$where_buy_food----->'.$where_buy_food.'</br>';
  echo '$what_matters----->'.$what_matters.'</br>';
  echo 'plan----->'.$plan.'</br>'; */

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


if ($gender == 'f') {
        $RMR = (65.51 + (9.6 * $current_weight) + (1.8 * $height) - (4.7 * $age)); //-$PAL*(100-25/100);
} else {
        $RMR = (66.47 + (13.7 * $current_weight) + (5 * $height) - (6.8 * $age)); //-$PAL*(100-25);
}

$tee = $RMR * $PAL; //Total Energy Expenditure

$NPES = 0; //Nutrition Plan Energy Supply
//Adjust Total Energy Expenditure per day
$four_week_plan = array('mon' => -35, 'tue' => -35, 'wed' => -35, 'thu' => -35, 'fri' => -35, 'sat' => -35, 'sun' => 10);
$twelve_week_plan = array('mon' => -25, 'tue' => -25, 'wed' => -10, 'thu' => -25, 'fri' => -25, 'sat' => -25, 'sun' => 10);
$weektype = array(12 => $twelve_week_plan, 4 => $twelve_week_plan);

$day = strtolower(date('D'));


$day_value = $weektype[$period][$day];

$weeknum = strtolower(date('W'));

/* sunday is cheat day -- 0% every uneven week & +10% every even week */
if ($weeknum % 2 == 0) {
        $day_percentage = $day_value / 100;
} else {
        $day_percentage = 0;
}

$NPES = $tee * ((100 / 100) + ($day_percentage));

// Adjust Total Energy Expenditure per week  
