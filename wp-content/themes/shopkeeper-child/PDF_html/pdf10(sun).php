<?php 
/*
 * Template Name: PDF10 (Nutrition plan of sunday)
 */
?>
<!DOCTYPE html>
<html lang="en">
 
  <body class="grey">
	  <?php 
	  $order_id= $_GET['order_id']; 
	   $week_no= $_GET['week_no']; 
	//  $order=new WC_Order(1210);
		//		echo get_template_directory();
//	  $items = $order->get_items();
		 
//	 $item_data=$items [array_keys($items)[0]];
//	  print_r($item_data);
	global $wpdb;
	$res=$om_id=$f_ids=array();
	$site_id=get_current_blog_id();
	$order_details=$wpdb->get_results('select id,meals_per_day from up_user_nutrition_plans where order_id='.$order_id.' AND site_id='.$site_id);	
	
	$sun_meal_res=$wpdb->get_results('select sun from up_plan_logs where user_nutrition_plan_id='.$order_details[0]->id.' AND week_no='.$week_no.' AND site_id='.$site_id);	

	$sun_meal=unserialize($sun_meal_res[0]->sun);
	
	if(!empty($sun_meal['breakfast']))
		$breakfast=array_values($sun_meal['breakfast']);
	if(!empty($sun_meal['lunch']))
		$lunch=array_values($sun_meal['lunch']);
	if(!empty($sun_meal['dinner']))
		$dinner=array_values($sun_meal['dinner']);
	if(!empty($sun_meal['pre_dinner_snack']))
		$pre_dinner_snack=array_values($sun_meal['pre_dinner_snack']);
	//------------Breakfast
	$breakfast_details= $wpdb->get_results('select id,name from up_meals where id='.$breakfast[0]);
	$query='select uf.id as f_id,umi.* from up_meal_ingredients umi join up_foods uf on uf.name=umi.name where umi.meal_id='.$breakfast[0];	
	$breakfast_ingredients=$wpdb->get_results( $query,ARRAY_A);
	foreach($breakfast_ingredients as $bi){
		$f_ids[]=$bi['f_id'];
	}
	//print_r($f_ids);
	$breakfast_order_meals=$wpdb->get_results( 'select * from up_order_meals where order_id='.$order_id.' AND meal_id='.$breakfast[0].' AND site_id='.$site_id,ARRAY_A);
	$breakfast_final_ingredients=explode(',',$breakfast_order_meals[0]['ingredient_ids']);
	if($breakfast_order_meals[0]['exchangble']==1){
			$res=array_diff($breakfast_final_ingredients,$f_ids);
			$e_id=array_values($res);
		}
	if(empty($e_id) && count($e_id) <= 0){
			$res=array_diff($f_ids,$breakfast_final_ingredients);
			$om_id=array_values($res);
		}
	$meal_details=$wpdb->get_results( 'select * from up_meal_instructions where meal_id='.$breakfast[0],ARRAY_A);
	$str_time = $meal_details[0]['preparation_time'];
	$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	//$time_mins = $hours * 60 + $minutes	;
	$time_mins = $str_time;
	//print_r($time_mins);
	
	$sql='SELECT if(sum(if(uf.cw_vegetarian<>0,1,0))=count(uf.id),1,0) as is_veg,
			if(sum(if(uf.cw_vegan<>0,1,0))=count(uf.id),1,0) as is_vegan,
			if(sum(if(uf.cw_paleo<>0,1,0))=count(uf.id),1,0) as is_paleo
			FROM up_foods as uf
			join up_meal_ingredients as umi on umi.name=uf.name 
			WHERE umi.meal_id='.$breakfast[0];
	$meal_extra_details=$wpdb->get_results( $sql,ARRAY_A);

	$breakfast_kcal=$wpdb->get_results( "CALL get_kcal_of_order_meals('".$order_id."','".$breakfast[0]."')");
//	print_r($breakfast_kcal);
	$lunch_kcal=$wpdb->get_results( "CALL get_kcal_of_order_meals('".$order_id."','".$lunch[0]."')");
	$dinner_kcal=$wpdb->get_results( "CALL get_kcal_of_order_meals('".$order_id."','".$dinner[0]."')");
	$pre_dinner_snack_kcal=$wpdb->get_results( "CALL get_kcal_of_order_meals('".$order_id."','".$pre_dinner_snack[0]."')");
$meat_meals1 = $wpdb->get_results('call meat_and_fish_meals("' . $breakfast[0] . '","fleisch")', OBJECT_K);
	$meat_meals2 = $wpdb->get_results('call meat_and_fish_meals("' . $breakfast[0] . '","Fleischwaren & Wurstwaren")', OBJECT_K);
	$fisch_meals1 = $wpdb->get_results('call meat_and_fish_meals("' . $breakfast[0] . '","fisch")', OBJECT_K);
	$fisch_meals2 = $wpdb->get_results('call meat_and_fish_meals("' . $breakfast[0] . '","Schalentiere")', OBJECT_K);
	 foreach($meat_meals2 as $k=>$v){
		foreach($v as $v1=>$v2){
			if($v1 =='Fleischwaren & Wurstwaren');
				$meat_meals3['fleischwaren']=$v2;
			}
	}
	   ?>
	  
    <div style="background:#f0eeef;text-align:center;overflow:hidden; color:#70848e;">
      <div style="background:#f0eeef;margin:0 auto;padding:0px 0px;display:inline-block;outline:none; line-height:22px;height:100%;">
        
		
            <div style="width:93.18%;text-align:center; margin:0 36px 0 38.4px;">
         <!-- 1 box -->
            <div class="p4-listStyleP" style="width: 24.99%; float:left; ">
				<?php if(!empty($breakfast[0])){ 
					include 'breakfast.php';
				}
				?>
			</div>
        
                 <!-- 2 box -->
		 
			<div class="p4-listStyleP" style="width: 25%; float:left;">
				<?php
			//echo $lunch[0];
				 if(!empty($lunch[0])){ 
						include 'lunch.php';
				 } 
				?>
							 
			</div>
                    
		        
		 <?php
		// echo $order_details[0]->meals_per_day;
		  if($order_details[0]->meals_per_day==5){
			if(!empty($sun_meal['pre_lunch_snack'])) 
				$pre_lunch_snack=array_values($sun_meal['pre_lunch_snack']);
				$pre_lunch_snack_kcal=$wpdb->get_results( "CALL get_kcal_of_order_meals('".$order_id."','".$pre_lunch_snack[0]."')");
				//$pre_lunch_snack_kcal=$wpdb->get_results( 'CALL prioritise_val('.$pre_lunch_snack[0].')');
				require_once('5_meals_snacks.php');
			 }
			 else{
				  require_once('4_meals_snacks.php');
				 }
			 ?> 
                    
			</div>
         </div>
    </div>
  </body>
</html>



