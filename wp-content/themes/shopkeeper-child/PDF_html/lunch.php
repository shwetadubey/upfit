	<div class="main-div" style="background:#FFF;padding:10px 16px;margin:15px 0px 0px 0px;border-radius:5px;">
					<!-----------------Lunch-------------->
	<?php 

		$sql='SELECT if(sum(if(uf.cw_vegetarian<>0,1,0))=count(uf.id),1,0) as is_veg,
			if(sum(if(uf.cw_vegan<>0,1,0))=count(uf.id),1,0) as is_vegan,
			if(sum(if(uf.cw_paleo<>0,1,0))=count(uf.id),1,0) as is_paleo
			FROM up_foods as uf
			join up_meal_ingredients as umi on umi.name=uf.name 
			WHERE umi.meal_id='.$lunch[0];
		$meal_extra_details=$wpdb->get_results( $sql,ARRAY_A);
		$res=$om_id=$f_ids=array();
		$lunch_details= $wpdb->get_results('select id,name from up_meals where id='.$lunch[0]);
		$query='select uf.id as f_id,umi.* from up_meal_ingredients umi join up_foods uf on uf.name=umi.name where umi.meal_id='.$lunch[0];	
		$lunch_ingredients=$wpdb->get_results( $query,ARRAY_A);
		foreach($lunch_ingredients as $bi){
			$f_ids[]=$bi['f_id'];
		}

		$lunch_order_meals=$wpdb->get_results( 'select * from up_order_meals where order_id='.$order_id.' AND meal_id='.$lunch[0].' AND site_id='.$site_id,ARRAY_A);
		
		$lunch_final_ingredients=explode(',',$lunch_order_meals[0]['ingredient_ids']);
		//print_r($lunch_final_ingredients);
		if($lunch_order_meals[0]['exchangble']==1){
			
			$res=array_diff($lunch_final_ingredients,$f_ids);
			$e_id=array_values($res);
		}
		if(empty($e_id) && count($e_id) <= 0){
			$res=array_diff($f_ids,$lunch_final_ingredients);
			$om_id=array_values($res);
		}
		//print_r($om_id);
		$meal_details=$wpdb->get_results( 'select * from up_meal_instructions where meal_id='.$lunch[0],ARRAY_A);
		
		$str_time = $meal_details[0]['preparation_time'];
		$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
		//$time_mins = $hours * 60 + $minutes	;
		$time_mins = $str_time;

		$meat_meals1 = $wpdb->get_results('call meat_and_fish_meals("' . $lunch[0] . '","fleisch")', OBJECT_K);
		$meat_meals2 = $wpdb->get_results('call meat_and_fish_meals("' . $lunch[0] . '","Fleischwaren & Wurstwaren")', OBJECT_K);
		$fisch_meals1 = $wpdb->get_results('call meat_and_fish_meals("' . $lunch[0] . '","fisch")', OBJECT_K);
		$fisch_meals2 = $wpdb->get_results('call meat_and_fish_meals("' . $lunch[0] . '","Schalentiere")', OBJECT_K);
		foreach($meat_meals2 as $k=>$v){
			foreach($v as $v1=>$v2){
			if($v1=='Fleischwaren & Wurstwaren')
				$meat_meals3['fleischwaren']=$v2;
			}
		}		
	?>
		
			<div style="border-bottom:0.0315em solid #bec8cc; text-align:center; margin-bottom:17.4px; padding-bottom:17.4px;">
			  <h2 class="p4_meal_heading">MITTAGESSEN</h2>
			  <p style="font-size:12px;margin:3px 0px 8px;"><?php echo $lunch_details[0]->name;?></p>
			  <div class="upfit_icon_p4">
			  <table class="p4-upfiticon">
					<tr>
					<td></td>
					<?php if($meal_extra_details[0]['is_vegan']==1){
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
			
			else if($meat_meals1[$lunch[0]]->fleisch > 0 || $meat_meals3['fleischwaren'] > 0){
			?>
				<td width="48px">
					<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fleisch.png" alt=""/>
					<span class="p4-iconDescription">Fleisch</span>
				</td>
			<?php
			}
			else if($fisch_meals1[$lunch[0]]->fisch > 0 ||  $fisch_meals2[$lunch[0]]->Schalentiere > 0){
			?>
				<td width="48px">
					<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/fisch.png" alt=""/>
					<span class="p4-iconDescription">Fisch</span>
				</td>
			<?php }
					?>
					<td width="48px">
						<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/>
						<span class="p4-iconDescription"><?php echo intval($lunch_kcal[0]->Kilokalorien); ?> kcal</span>
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
				$c=$e=0;
				unset($prise_ar);
				$prise_ar=array();
				$prise_ing='';
				
				foreach($lunch_ingredients as $ing){
					$ing_name='';
					
					$unit=$wpdb->get_results('select unit_symbol from up_units where id='.$ing['unit_id']);
					$c++;
				//	if(!in_array($ing['f_id'],$om_id)){
						if(!in_array($ing['f_id'],$lunch_final_ingredients)){
							
							if(count($e_id)>0  && !empty($e_id)){
								$res1=$wpdb->get_results('select name from up_foods where id='.$e_id[$e]);
								$ing_name=$res1[0]->name;
								$e++;
							}
						}
						else{
							$ing_name= $ing['name'];
						}
					//}
				if(isset($ing_name) && !empty($ing_name)){
				 ?>								
					<tr>				
						<?php
						if((float)$ing['quantity']==1 && $unit[0]->unit_symbol =='Prise'){

							$prise_ar[]= $ing_name;
						}
						else{?>
						<td style="width:70px;">
							<?php
								echo str_replace(".",",",(float)$ing['quantity']); 
								echo ' '.$unit[0]->unit_symbol;
							?>
						</td>
						 <td style="width:150px;">
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
							<td style="">
							<?php
								$prise_ar=array_unique($prise_ar);
								$prise_ing=implode(', ', $prise_ar);
								echo trim($prise_ing,', ');
							?>
							</td>
						</tr>
				<?php } ?>
				</table>
			</div>
		   
			<div class="p4-listStyleP">
				  <p style=""><?php echo $meal_details[0]['instruction']; ?></p>
			  
			</div>
		</div>

