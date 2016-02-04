<?php 
/*
 * Template Name: PDF5 (Nutrition plan of tuesday)
 */
?>
<!DOCTYPE html>
<html lang="en">
 
  <body class="grey">
	  <?php 
	  $order_id= $_GET['order_id']; 
	    $week_no= $_GET['week_no']; 
	global $wpdb;
	
	$order_details=$wpdb->get_results('select id,meals_per_day from '.$wpdb->prefix.'user_nutrition_plans where order_id='.$order_id);		
	
	$tue_meal_res=$wpdb->get_results('select tue from '.$wpdb->prefix.'plan_logs where user_nutrition_plan_id='.$order_details[0]->id.' AND week_no='.$week_no);
	$tue_meal=unserialize($tue_meal_res[0]->tue);
	//print_r($tue_meal);
	if(!empty($tue_meal['breakfast']))
		$breakfast=array_values($tue_meal['breakfast']);
	if(!empty($tue_meal['lunch']))
		$lunch=array_values($tue_meal['lunch']);
	if(!empty($tue_meal['dinner']))
		$dinner=array_values($tue_meal['dinner']);
	if(!empty($tue_meal['pre_dinner_snack']))
		$pre_dinner_snack=array_values($tue_meal['pre_dinner_snack']);
	//------------Breakfast
	$breakfast_details= $wpdb->get_results('select id,name from '.$wpdb->prefix.'meals where id='.$breakfast[0]);	
	$breakfast_ingredients=$wpdb->get_results( 'select * from '.$wpdb->prefix.'meal_ingredients where meal_id='.$breakfast[0],ARRAY_A);
	$meal_details=$wpdb->get_results( 'select * from '.$wpdb->prefix.'meal_instructions where meal_id='.$breakfast[0],ARRAY_A);
	$str_time = $meal_details[0]['preparation_time'];
	$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	$time_mins = $hours * 60 + $minutes	;
	//print_r($time_mins);
	
	$meal_extra_details=$wpdb->get_results( 'SELECT m.id,count(DISTINCT f.id) as total_ingredient,SUM(f.price_level_id) as price_level,Sum(f.is_fatburner) as fatt,if(sum(f.cw_vegetarian) = count(DISTINCT f.id),1,0) as is_veg,if(sum(f.cw_vegan) = count(DISTINCT f.id),1,0) as is_vegan
	FROM '.$wpdb->prefix.'meals m 
	INNER JOIN '.$wpdb->prefix.'meal_ingredients mi ON mi.meal_id = m.id 
	LEFT JOIN '.$wpdb->prefix.'foods f ON f.name = mi.name OR (mi.name <> f.name and find_in_set(f.id, mi.exchangable_with_ingredient) > 0) 
	where m.id in('.$breakfast[0].') group BY m.id',ARRAY_A);
	//print_r($meal_extra_details);
	$breakfast_kcal=$wpdb->get_results( 'CALL prioritise_val('.$breakfast[0].')');
	$lunch_kcal=$wpdb->get_results( 'CALL prioritise_val('.$lunch[0].')');
	$dinner_kcal=$wpdb->get_results( 'CALL prioritise_val('.$dinner[0].')');
	$pre_dinner_snack_kcal=$wpdb->get_results( 'CALL prioritise_val('.$pre_dinner_snack[0].')');
	
	$meat_meals = $wpdb->get_results('call meat_and_fish_meals("' . $breakfast[0] . '","fleisch")', OBJECT_K);
	$fisch_meals = $wpdb->get_results('call meat_and_fish_meals("' . $breakfast[0] . '","fisch")', OBJECT_K);
	
	  ?>
     <div style="background:#f0eeef;text-align:center;overflow:hidden; color:#70848e;">
      <div style="margin:0 auto;padding:0px 0px;display:inline-block;outline:none; line-height:22px">
         
		
          <div style="width:93.18%;text-align:center; margin:0 36px 0 38.4px;">
         <!-- 1 box -->
            <div class="p4-listStyleP" style="width: 24.99%; float:left; ">
			<?php if(isset($breakfast[0])){ ?>
				<div class="main-div" style="background:#FFF;padding:10px;margin:15px 15px 0px 0px;border-radius:5px;">
					<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:10px; padding-bottom:15px;">
						
					  <h2 class="p4_meal_heading">FRÜHSTÜCK</h2>
					  <p style="font-size:12px;margin:3px 0px 8px;"><?php  echo $breakfast_details[0]->name;?></p>
						  <table class="p4-upfiticon">
								<tr>
							<?php if($meal_extra_details[0]['is_vegan']==0){
							?>	<td>
						<!--	<svg width="100%" height="100%" viewBox="0 0 360 240">
							   
							   <image xlink:href="<?php// echo get_template_directory_uri(); ?>-child/images/icons/Vegan.png" preserveAspectRatio="none" x="20" y="120" width="200" height="200" clip-path="url(#sample)"/>
							   
							</svg>-->
									<img id="" width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/vegan.png" alt=""/>
									<span class="p4-iconDescription">
										Vegan
									</span>
									
								</td>
							<?php	}?>
							<?php if($meal_extra_details[0]['is_veg']==1){
							?>	<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/veggie.png" alt=""/>
									<span class="p4-iconDescription">
										Veggie
									</span>
									
								</td>
							<?php	}
							if($meat_meals[$breakfast[0]]->fisch==1){
							?>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fisch.png" alt=""/>
									<span class="p4-iconDescription">Fisch</span>
								</td>
							<?php }
								if($meat_meals[$breakfast[0]]->fleisch==1){
							?>
									<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fleisch.png" alt=""/>
									<span class="p4-iconDescription">fleisch</span>
								</td>
							<?php
							}
								?>
									<td>
										<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/>
										<span class="p4-iconDescription"><?php echo intval($breakfast_kcal[0]->Kilokalorien); ?>kcal</span>
									</td>
									<td>
										<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/>
										<span class="p4-iconDescription"><?php echo $time_mins.'min'; ?></span>
									</td>
							</tr>
						  </table>
					</div>
				   
						   
					   <div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:10px; padding-bottom:15px;">
						  <h3 class="letterspacing_1" style="color: #162c5d;font-size:12px; font-weight:normal;line-height:13px;margin-top:0px">ZUTATEN</h3>
						  
						  <table class="p4-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
								<?php 
								$c=0;
								foreach($breakfast_ingredients as $ing){ 
								$unit=$wpdb->get_results('select unit_symbol from '.$wpdb->prefix.'units where id='.$ing['unit_id']);
								 $c++;
								 ?>								
								<tr>
									<td style="width:70px;"><?php
									  echo str_replace(".",",",(float)$ing['quantity']); 
										echo ' '.$unit[0]->unit_symbol;
										 ?></td>
									 <td><?php echo $ing['name'] ?></td>
								</tr>
								<?php } ?>
					 
							</table>
						</div>
					  
						<div class="p4-listStyleP">
						
							  <strong class="p4-sub_headdings" style="">ZUBEREITUNG</strong>
							  <p class="letterspacing_5" ><?php echo $meal_details[0]['instruction']; ?></p>
						  </div>				  
						
					</div>
			<?php }?>
				</div>
        
        
                 <!-- 2 box -->
		 
			<div class="p4-listStyleP" style="width: 25%; float:left;">
		<?php if(isset($lunch[0])){ ?>
				<div class="main-div" style="background:#FFF;padding:10px;margin:15px 15px 0px 0px;border-radius:5px;">
					<!-- ----------------Lunch------------ -->
					<?php 
					$meal_extra_details=$wpdb->get_results( 'SELECT m.id,count(DISTINCT f.id) as total_ingredient,SUM(f.price_level_id) as price_level,Sum(f.is_fatburner) as fatt,if(sum(f.cw_vegetarian) = count(DISTINCT f.id),1,0) as is_veg,if(sum(f.cw_vegan) = count(DISTINCT f.id),1,0) as is_vegan
					FROM '.$wpdb->prefix.'meals m 
					INNER JOIN '.$wpdb->prefix.'meal_ingredients mi ON mi.meal_id = m.id 
					LEFT JOIN '.$wpdb->prefix.'foods f ON f.name = mi.name OR (mi.name <> f.name and find_in_set(f.id, mi.exchangable_with_ingredient) > 0) 
					where m.id in('.$lunch[0].') group BY m.id',ARRAY_A);
					
					$lunch_details= $wpdb->get_results('select id,name from '.$wpdb->prefix.'meals where id='.$lunch[0]);	
					//print_r($pre_lunch_snack_details);
					$lunch_ingredients=$wpdb->get_results( 'select * from '.$wpdb->prefix.'meal_ingredients where meal_id='.$lunch[0],ARRAY_A);
					$meal_details=$wpdb->get_results( 'select * from '.$wpdb->prefix.'meal_instructions where meal_id='.$lunch[0],ARRAY_A);
					$str_time = $meal_details[0]['preparation_time'];
					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
					$time_mins = $hours * 60 + $minutes	;
					
					$meat_meals = $wpdb->get_results('call meat_and_fish_meals("' . $lunch[0] . '","fleisch")', OBJECT_K);
					$fisch_meals = $wpdb->get_results('call meat_and_fish_meals("' . $lunch[0] . '","fisch")', OBJECT_K);
					?>
					
						<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:10px; padding-bottom:15px;">
						  <h2 class="p4_meal_heading">MITTAGESSEN</h2>
						  <p style="font-size:12px;margin:8px 0px 8px;"><?php echo $lunch_details[0]->name;?></p>
						  <table class="p4-upfiticon">
								<tr>
							<?php if($meal_extra_details[0]['is_vegan']==1){
							?>	<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/vegan.png" alt=""/>
									<span class="p4-iconDescription">
										Vegan
									</span>
									
								</td>
							<?php	}?>
							<?php if($meal_extra_details[0]['is_veg']==1){
							?>	<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/veggie.png" alt=""/>
									<span class="p4-iconDescription">
										Veggie
									</span>
									
								</td>
							<?php	}
							if($meat_meals[$lunch[0]]->fisch==1){
							?>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fisch.png" alt=""/>
									<span class="p4-iconDescription">Fisch</span>
								</td>
							<?php }
								if($meat_meals[$lunch[0]]->fleisch==1){
							?>
									<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fleisch.png" alt=""/>
									<span class="p4-iconDescription">fleisch</span>
								</td>
							<?php
							}
								?>
									<td>
										<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/>
										<span class="p4-iconDescription"><?php echo intval($lunch_kcal[0]->Kilokalorien); ?>kcal</span>
									</td>
									<td>
										<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/>
										<span class="p4-iconDescription"><?php echo $time_mins.'min'; ?></span>
									</td>
							</tr>
						  </table>
						</div>
					   
					   
					   <div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:10px; padding-bottom:15px;">
						  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
						  
						  <table class="p4-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
								<?php
								$c=0;
								 foreach($lunch_ingredients as $ing){$unit=$wpdb->get_results('select unit_symbol from '.$wpdb->prefix.'units where id='.$ing['unit_id']);
									 $c++;
								 ?>								
								<tr>
									<td style="width:70px;"><?php
									  echo str_replace(".",",",(float)$ing['quantity']); 
										echo ' '.$unit[0]->unit_symbol;
										 ?></td>
									 <td><?php echo $ing['name'] ?></td>
								</tr>
								<?php } ?>
							</table>
						</div>
					   
						 
						<div class="p4-listStyleP">
						
							  <strong class="p4-sub_headdings" style="">ZUBEREITUNG</strong>
							  <p class="letterspacing_5" ><?php echo $meal_details[0]['instruction']; ?></p>
						  </div>
						  
						
					</div>
			
            <?php } ?>         
				</div>
                    
		 <?php
		// echo $order_details[0]->meals_per_day;
		  if($order_details[0]->meals_per_day==5){
			 	if(!empty($tue_meal['pre_lunch_snack']))
				$pre_lunch_snack=array_values($tue_meal['pre_lunch_snack']);
				
				$pre_lunch_snack_kcal=$wpdb->get_results( 'CALL prioritise_val('.$pre_lunch_snack[0].')');
			 require_once('5_meals_snacks.php');
			 }
			 else{
				  require_once('4_meals_snacks.php');
				 }
			 ?> 
                    
         </div>
    </div>
  </body>
</html>



