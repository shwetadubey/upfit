<div class="main-div" style="background:#FFF;padding:10px;margin:15px 15px 0px 0px;border-radius:5px;">
				
				<!-----------------pre_dinner_snack-------------->
			<?php 
				$sql='SELECT if(sum(if(uf.cw_vegetarian<>0,1,0))=count(uf.id),1,0) as is_veg,
				if(sum(if(uf.cw_vegan<>0,1,0))=count(uf.id),1,0) as is_vegan,
				if(sum(if(uf.cw_paleo<>0,1,0))=count(uf.id),1,0) as is_paleo
				FROM up_foods as uf
				join up_meal_ingredients as umi on umi.name=uf.name 
				WHERE umi.meal_id='.$pre_dinner_snack[0];
				$meal_extra_details=$wpdb->get_results( $sql,ARRAY_A);
					
				$pre_dinner_snack_details= $wpdb->get_results('select id,name from up_meals where id='.$pre_dinner_snack[0]);	
				//print_r($pre_lunch_snack_details);
				$pre_dinner_snack_ingredients=$wpdb->get_results( 'select * from up_meal_ingredients where meal_id='.$pre_dinner_snack[0],ARRAY_A);
				$meal_details=$wpdb->get_results( 'select * from up_meal_instructions where meal_id='.$pre_dinner_snack[0],ARRAY_A);
				$str_time = $meal_details[0]['preparation_time'];
				$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
				sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
				//$time_mins = $hours * 60 + $minutes	;
				$time_mins=$str_time;
				$meat_meals = $wpdb->get_results('call meat_and_fish_meals("' . $pre_dinner_snack[0] . '","fleisch")', OBJECT_K);
				$fisch_meals = $wpdb->get_results('call meat_and_fish_meals("' . $pre_dinner_snack[0] . '","fisch")', OBJECT_K);
				
			?>
					<div style="border-bottom:0.0315em solid #bec8cc; text-align:center; margin-bottom:10px; padding-bottom:15px;">
					  <h2 class="p4_meal_heading">NACHMITTAGSSNACK</h2>
					  <p style="font-size:12px;margin:3px 0px 8px;"><?php echo $pre_dinner_snack[0]->name;?></p>
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
						if($meal_extra_details[0]['is_paleo']==1){
						?>	
							<td width="48px">
								<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/paleo.png" alt=""/>
								<span class="p4-iconDescription">
									Paleo
								</span>
									
							</td>
						<?php	}
							if($meat_meals[$pre_dinner_snack[0]]->fisch==1){
						?>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fisch.png" alt=""/>
									<span class="p4-iconDescription">Fisch</span>
								</td>
						<?php }
							if($meat_meals[$pre_dinner_snack[0]]->fleisch==1){
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
									<span class="p4-iconDescription"><?php echo intval($pre_dinner_snack_kcal[0]->Kilokalorien); ?>kcal</span>
								</td>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/>
									<span class="p4-iconDescription"><?php echo $time_mins.'min'; ?></span>
								</td>
						</tr>
					  </table>
					</div>
				   
				   
				   <div style="border-bottom:0.0315em solid #bec8cc; text-align:left; margin-bottom:4px; padding-bottom:15px;">
					  
					  <table class="p4-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
							<?php foreach($pre_dinner_snack_ingredients as $ing){ ?>
							
								<tr>				
								<?php
								if((float)$ing['quantity']==1 && $unit[0]->unit_symbol =='Prise'){

									$prise_ar[]= $ing['name'];
								}
								else{?>
								<td style="width:70px;">
									<?php
										echo str_replace(".",",",(float)$ing['quantity']); 
										echo ' '.$unit[0]->unit_symbol;
									?>
								</td>
								 <td>
									 <?php echo $ing['name'] ?>
								</td>
							 <?php }
								?>
							</tr>
							
						<?php } 
							if(!empty($prise_ar)){  ?>
								<tr>
									<td style="width:70px;">
									<?php
										echo '1 Prise'; 
									?>
									</td>
									<td>
									<?php
										$prise_ar=array_unique($prise_ar);
										$prise_ing=implode(', ', $prise_ar);
										echo $prise_ing;
									?>
									</td>
								</tr>
						<?php } ?>
						</table>
							
					</div>
				   
					<div class="p4-listStyleP" style="">
					  <div style="">
						 <p class="letterspacing_5"><?php echo $meal_details[0]['instruction']; ?></p>
					  </div>
					  
					</div>
				</div>
	
