<?php 
$meal_extra_details=$wpdb->get_results( 'SELECT m.id,count(DISTINCT f.id) as total_ingredient,SUM(f.price_level_id) as price_level,Sum(f.is_fatburner) as fatt,if(sum(f.cw_vegetarian) = count(DISTINCT f.id),1,0) as is_veg,if(sum(f.cw_vegan) = count(DISTINCT f.id),1,0) as is_vegan
					FROM '.$wpdb->prefix.'meals m 
					INNER JOIN '.$wpdb->prefix.'meal_ingredients mi ON mi.meal_id = m.id 
					LEFT JOIN '.$wpdb->prefix.'foods f ON f.name = mi.name OR (mi.name <> f.name and find_in_set(f.id, mi.exchangable_with_ingredient) > 0) 
					where m.id in('.$pre_lunch_snack[0].') group BY m.id',ARRAY_A);
					$pre_lunch_snack_details= $wpdb->get_results('select id,name from '.$wpdb->prefix.'meals where id='.$pre_lunch_snack[0]);	
					
					$pre_lunch_snack_ingredients=$wpdb->get_results( 'select * from '.$wpdb->prefix.'meal_ingredients where meal_id='.$pre_lunch_snack[0],ARRAY_A);
					
					$meal_details=$wpdb->get_results( 'select * from '.$wpdb->prefix.'meal_instructions where meal_id='.$pre_lunch_snack[0],ARRAY_A);
					$str_time = $meal_details[0]['preparation_time'];
					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
					$time_mins = $hours * 60 + $minutes	;
					
					$meat_meals = $wpdb->get_results('call meat_and_fish_meals("' . $pre_lunch_snack[0] . '","fleisch")', OBJECT_K);
					$fisch_meals = $wpdb->get_results('call meat_and_fish_meals("' . $pre_lunch_snack[0] . '","fisch")', OBJECT_K);
		?>
				<div  style="background:#FFF;padding:10px 16px;margin:15px 0px 0px 0px;border-radius:5px;height:88mm">
					<!-----------pre_lunch_snack-------------->
					
						<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:17.4px; padding-bottom:17.4px;">
						  <h2 class="p4_meal_heading">VORMITTAGSSNACK</h2>
						  <p style="font-size:12px;margin:3px 0px 8px;"><?php echo $pre_lunch_snack_details[0]->name;?></p>
						  <div class="upfit_icon_p4">
						  <table class="p4-upfiticon">
							<tr>
								<?php// if($meal_extra_details[0]['is_vegan']==1){
							?>	<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/vegan.png" alt=""/>
									<span class="p4-iconDescription">
										Vegan
									</span>
									
								</td>
							<?php	//}
							if($meal_extra_details[0]['is_veg']==1){
							?>	
							<td>
								<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/veggie.png" alt=""/>
								<span class="p4-iconDescription">
									Veggie
								</span>
									
							</td>
							<?php	}
							if($meat_meals[$pre_lunch_snack[0]]->fisch==1){
							?>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fisch.png" alt=""/>
									<span class="p4-iconDescription">Fisch</span>
								</td>
							<?php }
							if($meat_meals[$pre_lunch_snack[0]]->fleisch==1){
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
									<span class="p4-iconDescription"><?php echo intval($pre_lunch_snack_kcal[0]->Kilokalorien); ?>kcal</span>
								</td>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/>
									<span class="p4-iconDescription"><?php echo $time_mins.'min'; ?></span>
								</td>
							</tr>
						  </table>
						  </div>
						</div>
					   
					   
					   <div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:17.1px; padding-bottom:10.2px;">
						  <h3 class="letterspacing_1" style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px; margin-bottom:7.2px;">ZUTATEN</h3>
						  
						  <table class="p4-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
							  
								<?php foreach($pre_lunch_snack_ingredients as $ing){
									 $unit=$wpdb->get_results('select unit_symbol from '.$wpdb->prefix.'units where id='.$ing['unit_id']);
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
								<p class="letterspacing_5" style="margin-bottom: 0;"><?php  echo $meal_details[0]['instruction']; ?> </p>
							
						  
						</div>
					
					</div>
