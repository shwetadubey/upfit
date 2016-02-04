 		<div class="main-div" style="background:#FFF;padding:10px 16px;margin:15px 15px 0px 0px;border-radius:5px;">
				<div style="border-bottom:0.0315em solid #bec8cc; text-align:center; margin-bottom:17.4px; padding-bottom:17.4px;">
					
				  <h2 class="p4_meal_heading"><?php echo ('FRÜHSTÜCK'); ?></h2>
				  <p style="font-size:12px;margin:3px 0px 8px;"><?php  echo $breakfast_details[0]->name;?></p>
					<div class="upfit_icon_p4">
					<table class="p4-upfiticon">
								
						<tr>
							<td></td>
							<?php 
							if($meal_extra_details[0]['is_vegan']==1){
						?>	<td width="48px">
								<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/vegan.png" alt=""/>
								<span class="p4-iconDescription">
									Vegan
								</span>
								
							</td>
						<?php	}
						if($meal_extra_details[0]['is_veg']==1 && $meal_extra_details[0]['is_vegan'] != 1){
						?>	
						<td width="48px">
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
						
						else if($meat_meals1[$breakfast[0]]->fleisch > 0 || $meat_meals3['fleischwaren'] > 0){
						?>
							<td width="48px">
								<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fleisch.png" alt=""/>
								<span class="p4-iconDescription">Fleisch</span>
							</td>
						<?php
						}
						else if($fisch_meals1[$breakfast[0]]->fisch > 0 ||  $fisch_meals2[$breakfast[0]]->Schalentiere > 0){
						?>
							<td width="48px">
								<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fisch.png" alt=""/>
								<span class="p4-iconDescription">Fisch</span>
							</td>
						<?php }
							?>
							<td width="48px">
								<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/>
								<span class="p4-iconDescription"><?php echo intval($breakfast_kcal[0]->Kilokalorien); ?> kcal</span>
							</td>
							<td width="48px">
								<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/>
								<span class="p4-iconDescription"><?php echo $time_mins.' min'; ?></span>
							</td>
							<td></td>
						</tr>
					</table>
					</div>
				</div>
			   
					   
				   <div style="border-bottom:0.0315em solid #bec8cc; text-align:left; margin-bottom:4px; padding-bottom:10.2px;">
					  
					  <table class="p4-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
						<?php 
							$c=0;
							$prise_ar=array();
							unset($prise_ar);
							$e=0;
						foreach($breakfast_ingredients as $ing){
							//echo 'select unit_symbol from '.$wpdb->prefix.'units where id='.$ing["unit_id"];
							$unit=$wpdb->get_results('select unit_symbol from up_units where id='.$ing['unit_id']);
							$c++;
							if(!in_array($ing['f_id'],$breakfast_final_ingredients)){
									
									if(count($e_id)>0  && !empty($e_id)){
										$res1=$wpdb->get_results('select name from up_foods where id='.$e_id[$e]);
									
										$ing_name=$res1[0]->name;
										$e++;
									}
								}
							else{
								$ing_name= $ing['name'];
							}
							if(isset($ing_name)){
							 ?>								
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
									 <?php echo $ing_name; ?>
								</td>
							 <?php }
								?>
							</tr>
							
						<?php } 
						}
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
				   
					<div class="p4-listStyleP">
						  <p class="letterspacing_5" ><?php echo $meal_details[0]['instruction']; ?></p>				
					</div>	
				</div>
			
