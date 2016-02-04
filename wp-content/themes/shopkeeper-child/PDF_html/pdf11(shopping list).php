<?php 
/*
 * Template Name: PDF11 (Shopping List)
 */
?>

<!DOCTYPE html>
<html lang="en">
  
  <body class="grey">
 <?php 
  $order_id= $_GET['order_id']; 
	global $wpdb;
	$order_details=$wpdb->get_results('select id from '.$wpdb->prefix.'user_nutrition_plans where order_id='.$order_id);	
	$meal_res=$wpdb->get_results('select * from '.$wpdb->prefix.'plan_logs where user_nutrition_plan_id='.$order_details[0]->id.' AND week_no=1');
	//print_r($meal_res);
	$final_meals=($meal_res[0]->final_meals);
//print_r($final_meals);
		$shopping_list=$wpdb->get_results('SELECT
			GROUP_CONCAT(uf.shopping_list_name,"") AS shopping_list_ing,
			GROUP_CONCAT(umi.id,"") AS ing_id,
			GROUP_CONCAT(umi.quantity,"") AS quantity,
			GROUP_CONCAT(umi.unit_id ,"") AS unit_id,
			GROUP_CONCAT(umi.weight ,"") AS real_weight,
			GROUP_CONCAT(uun.unit_symbol,"") AS unit,uslc.name as cat_name 
			FROM `up_meal_ingredients` as umi 
			INNER JOIN `up_foods` as uf  on umi.name=uf.name 
			JOIN `up_units` as uun on uun.id=umi.unit_id 
			JOIN `up_shopping_list_categories` as uslc on uf.shopping_list_category_id=uslc.id 
			WHERE umi.meal_id IN('.$meal_res[0]->final_meals.') GROUP BY uslc.name');
			
			//echo "<pre>";	print_r($shopping_list);
 ?>
 <div style="margin:0;background:#f0eeef;text-align:center;overflow:hidden; color:#70848e;">
      <div style="max-width:100%;margin:0 auto;padding:0px;display:inline-block;outline:none; line-height:22px; height:100%; ">
           
          <div style="width:92%;text-align:center; margin:0 50px;float:left; ">
         <!-- 1 box -->
            <div class="p5-listStyleP" style="width:50%; float:left;">
				
				<div class="p5-main-div" style="float:left;padding:10px;margin:15px 15px 15px 0px;border-radius:5px;background:#FFF;">
					<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:10px; padding-bottom:0px;">
						<h2 style="font-size:15px;margin:8px 0 8px; color:#162c5d">EINKAUFSLISTE WOCHE 1</h2>
					
					</div>
					<?php 
						$j=0;
						foreach($shopping_list as $list) {
						
						?>
					<div style="width:45%;float:left;">
						<!-- 1/1 box -->
						<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:0px;">
						  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px"><?php echo $list->cat_name;?></h3>
						  
						  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="page-break-before: always;border:0;outline:none; padding-bottom:5px;">
							<?php 
							$shopping_list_ing=explode(',',$list->shopping_list_ing);
							$quantity=explode(',',$list->quantity);
						//	$unit=explode(',',$list->unit);
							$unit_id=explode(',',$list->unit_id);
							//$ing_id=explode(',',$list->ing_id);
							$real_weight=explode(',',$list->real_weight);
							$unit_id_ar=array();
							unset($q_sum);
							unset($w_sum);
							unset($ing_names);
							unset($unit_id_ar);
							unset($ing_q_ar);
							unset($ing_w_ar);
							$q_sum=$w_sum=$ing_names=array();
							
							//print_r($shopping_list_ing);
							for( $i = 0; $i <= sizeof($shopping_list_ing); $i++) 
							{
								if(isset($shopping_list_ing[$i])){
									$ing_q_ar[$shopping_list_ing[$i]][]=$quantity[$i];
									$ing_w_ar[$shopping_list_ing[$i]][]=$real_weight[$i];
									$unit_id_ar[$shopping_list_ing[$i]][]=$unit_id[$i];
									if(!in_array($shopping_list_ing[$i],$ing_names))
										$ing_names[]=$shopping_list_ing[$i];
								}
								
							}
							foreach($ing_q_ar as $key=>$v){
								$q_sum[$key]=array_sum($v);
							}
							foreach($ing_w_ar as $key=>$v){
								$w_sum[$key]=array_sum($v);
							}
							foreach($unit_id_ar as $key=>$v){
								
								if(!in_array($v,$unit_id_ar[$key]))
								$unit_id_ar_c[$key]=count(array_unique($unit_id_ar[$key]));
								
							}
							
						//	print_r($w_sum);
								//print_r(($unit_id_ar));
							foreach($w_sum as $k=>$v){
								$j++;
								if(isset($k)) {
									//echo 'test';	
									//echo $k;	
								//echo '<pre>';
										//print_r(array_unique($unit_id_ar[$k]));
									if( $unit_id_ar_c[$k] > 1) { 
										$value=str_replace(".",",",(float)$v);
										$u='g';
									}
									else{
										//echo 'select unit_symbol from '.$wpdb->prefix.'units where id='.array_unique($unit_id_ar[$k])[0];
										$value=str_replace(".",",",(float)$q_sum[$k]);
										$u_res=$wpdb->get_results('select unit_symbol from '.$wpdb->prefix.'units where id='.$unit_id_ar[$k][0]);
										//print_r($u_res);
										$u=$u_res[0]->unit_symbol;
										}
								}
								
								?>
								<tr>
									<td width="12"><img height=10 width=10 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/checkbox.png"></td>
								
									<td style="page-break-inside: avoid;width:40px;"> <?php echo $value; ?> </td>
									<td style="width:45px;"><?php echo $u; ?></td>
									
									 <td><?php echo $k ?></td>
									
								</tr>
								 <?php
								 }
								 ?>
											
							</table>
						</div>	
						
						<!-- 1/2 box -->
						<?php if($j==30){ ?>
						</div> <div style="width:45%;float:left;">
							<?php }?>
					</div> 
					<?php 
					
					}?>
						
				</div>
				
            </div>         
		
		<!-- 2 box -->
				
			<div class="p5-listStyleP" style="width:50%; float:left;">
				
				<div class="p5-main-div" style="float:left;padding:10px;margin:15px 15px 15px 0px;border-radius:5px;background:#FFF;">
						<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:10px; padding-bottom:0px;">
							<h2 style="font-size:15px;margin:8px 0 10px; color:#162c5d">EINKAUFSLISTE WOCHE 2</h2>
							
						</div>
						
						<div style="width:45%;float:left;">
							<!-- 1/1 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:5px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:0px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												<tr>
													<td>2 EL</td>
													 <td >Pflanzencal</td>
												</tr>
												
											</table>
									</div>
							
							<!-- 1/2 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:5px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												
												
											</table>
									</div>
							
							<!-- 1/3 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:5px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												
										</table>
									</div>
							
							<!-- 1/4 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:5px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												<tr>
													<td>2 EL</td>
													 <td >Pflanzencal</td>
												</tr>
												
											</table>
									</div>
							
							<!-- 1/5 box -->
							<div style="text-align:left; ">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												
												
											</table>
									</div>
						
						</div> 
								   
						<div style="width:45%;float:right;">
							<!-- 1/1 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:0px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												<tr>
													<td>2 EL</td>
													 <td >Pflanzencal</td>
												</tr>
												
											</table>
									</div>
							
							<!-- 1/2 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:5px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												<tr>
													<td>2 EL</td>
													 <td >Pflanzencal</td>
												</tr>
												
											</table>
									</div>
							
							<!-- 1/3 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:5px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												<tr>
													<td>2 EL</td>
													 <td >Pflanzencal</td>
												</tr>
												
											</table>
									</div>
							
							<!-- 1/4 box -->
							<div style="border-bottom:1px solid #eaedef; text-align:left; margin-bottom:7px; padding-bottom:5px;">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:5px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												
											</table>
									</div>
							
							<!-- 1/5 box -->
							<div style="text-align:left; ">
									  <h3 style="color: #162c5d;font-size:12px;line-height:13px;margin-top:0px">ZUTATEN</h3>
									  
									  <table class="p5-listingingredients" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0;outline:none; padding-bottom:0px;">
												<tr>
													<td style="width:70px;">4 gr.</td>
													 <td>Ei(er)</td>
												</tr>
												 <tr>
													<td>2 EL</td>
													 <td>Wasser</td>
												</tr>
												<tr>
													<td>4</td>
													 <td>Frahlingszwiebel(n), gehackt</td>
												</tr>
												 <tr>
													<td>1</td>
													 <td>Chilischote(n), rot, fein gehackt</td>
												</tr>
												<tr>
													<td>2 EL</td>
													 <td >Pflanzencal</td>
												</tr>
												
											</table>
									</div>
						
						</div> 		   
								 
											</div>
				
            </div>         
				
                    
         </div>
    </div>
  </body>
</html>



