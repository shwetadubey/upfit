<?php
/*
 * Template Name: test procedure
 */
global $wpdb;

/*$sql = 'CREATE PROCEDURE p() '
        . 'BEGIN SELECT "Hi!"; '
        . 'END;';

$sql1 = $wpdb->get_results($sql);

$test = $wpdb->get_results("call p()");
echo print_r($test,true);*/
$query1 ="SELECT meal_id, COUNT( * ) AS total_ingredient
FROM up_meal_ingredients
GROUP BY meal_id";

$res1 = $wpdb->get_results($query1);
echo "<pre>";print_r($res1);

/*$query2 = "select meal_id as meal_id,count(*) as main_count from up_meal_ingredients um, up_foods uf where um.name=uf.name and (uf.cw_vegetarian=1 or uf.cw_vegan=1)
group by meal_id";

$res2 = $wpdb->get_results($query2);

echo "<pre>";print_r($res2);*/

$query3 ="select meal_id as meal_id,group_concat(um.name SEPARATOR ';') as ing_names,group_concat(uf.id) as compatible_ingredients,count(*) as main_count from up_meal_ingredients um, up_foods uf where um.name=uf.name and (uf.cw_vegetarian=1 or uf.cw_vegan=1)
group by meal_id";

$res3 = $wpdb->get_results($query3);

echo "<pre>";print_r($res3);


$q2 = "select meal.meal_id,meal.name as ingredient_name ,exchangable_with_ingredient,food.*,group_concat(food.id),count(*) 
from up_meal_ingredients meal, up_foods food 
where not find_in_set(replace(meal.name,',',''),(select group_concat(replace(um.name,',',''))
from up_meal_ingredients um, up_foods uf where um.meal_id=meal.meal_id and um.name=uf.name and (uf.cw_vegetarian=1 or uf.cw_vegan=1)))
and FIND_IN_SET(food.id, exchangable_with_ingredient) 
and exchangable_with_ingredient<>'' 
and (food.cw_vegetarian=1 or food.cw_vegan=1) 
group by meal.name, meal.meal_id";

$res2 = $wpdb->get_results($q2);

echo "<pre>";print_r($res2);
$res_meals =array();
foreach($res1 as $k1 =>$r1){
	if(($r1->meal_id == $res3[$k1]->meal_id) && ($r1->total_ingredient != $res3[$k1]->main_count)){
	$temp =0;
		//echo $r1->meal_id.' count = '.$res3[$k1]->main_count.'<br>';
		foreach($res2 as $r){
			if($r->meal_id == $r1->meal_id){
				$temp++;
			}
		}
		$final_count = $res3[$k1]->main_count +$temp;
		
		if($final_count == $r1->total_ingredient){
			$res_meals[]= $r1->meal_id;
		}	

	}
}

$final_meal = implode(',',$res_meals);

$final_query = "SELECT m.id, m.name,mi.meal_id,mi.name as i_name,mi.exchangable_with_ingredient,IFNULL(f.cw_vegetarian,0) as og_veg,IFNULL(f.cw_vegan,0) as og_vegan,IFNULL(f.cw_pescetarian,0) as og_pescetarian,IFNULL(f.cw_paleo,0) as og_paleo,IFNULL(f1.id,0) as fid, IFNULL(f1.cw_vegetarian,0) as vegitarian,IFNULL(f1.cw_vegan,0) as vegan,IFNULL(f1.cw_pescetarian,0) as pescetarian,IFNULL(f1.cw_paleo,0) as paleo
FROM up_meals m
INNER JOIN up_meal_ingredients mi ON mi.meal_id = m.id
INNER JOIN up_meal_instructions mins ON m.id = mins.meal_id
AND MINUTE(mins.preparation_time) <40
left JOIN up_foods f ON f.name = mi.name
LEFT JOIN up_foods f1 ON FIND_IN_SET( f1.id, mi.exchangable_with_ingredient )
where m.id in(".$final_meal.")
ORDER BY m.id;";

echo $final_query;
$final_res = $wpdb->get_results($final_query);

echo "<pre>";print_r($final_res);
echo "--------------";
exit;
foreach($res1 as $k1 =>$r1){
	if(($r1->meal_id == $res3[$k1]->meal_id) && ($r1->total_ingredient != $res3[$k1]->main_count)){
		$not_compatible_meals[$k1][]=$r1->meal_id;
		$not_compatible_meals[$k1][]=$res3[$k1]->compatible_ingredients;
	}else{
		$res_meals[]=$r1->meal_id;
	}
}
echo "<pre>";print_r($not_compatible_meals);
echo "-------------------------";
foreach($not_compatible_meals as $r3){
	$query4 ="select meal_id,f.id as food_id,um.name,um.exchangable_with_ingredient,Group_concat(f.name),concat(um.exchangable_with_ingredient) as food_ids from up_meal_ingredients um left join up_foods f on f.name = um.name where f.id not in(".$r3[1].") and um.meal_id=".$r3[0];
echo $query4;
	$res4 = $wpdb->get_results($query4);

	echo "<pre>";print_r($res4);
}


