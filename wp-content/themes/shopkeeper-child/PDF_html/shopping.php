<?php 
/*
 * Template Name: Shopping 
 */
?>

<html>
<head>
<style>
.shopping_list_column{box-decoration-break:clone;
	width:23%;float:left;padding:10px 10px 0px 10px;font-size:12px;
}
</style>

</head>
<body>
<?php 
	$order_id= $_GET['order_id']; 
	$week_no= $_GET['week_no']; 
	$site_id=get_current_blog_id();
	global $wpdb;
	$order_details=$wpdb->get_results('select id from up_user_nutrition_plans where order_id='.$order_id.' AND site_id='.$site_id);	
	//echo '<pre> Order Details';
	//print_r($order_details);
	$up_order_meals=$wpdb->get_results('select * from up_order_meals where order_id='.$order_id);
	//echo 'up order meals';
	//print_r($up_order_meals);
	$meal_res=$wpdb->get_results('select * from up_plan_logs where user_nutrition_plan_id='.$order_details[0]->id.' AND week_no='.$week_no.' AND site_id='.$site_id);	
	//echo 'meal res';
	//print_r($meal_res);
//	print_r($up_order_meals);
	$fin_meals=explode(',',$meal_res[0]->final_meals);
	//echo 'final meals';
	//print_r($meal_res[0]->final_meals);
	foreach($up_order_meals as $uom){
		if(in_array($uom->meal_id,$fin_meals)){
			$week_ing_ar[]=explode(',',$uom->ingredient_ids);
		}
		
	}
	foreach($week_ing_ar as $wi){
		foreach($wi as $w){
			$week_final_ing[]=$w;
		}
	}
	//echo 'week final ing';
	//print_r($week_final_ing);
	$week_ing=implode(',',array_unique($week_final_ing));
//	echo '</pre>';
	//echo 'week_ing'.$week_ing;
	$final_meals=rtrim($meal_res[0]->final_meals,',');
	/*$sql='SELECT
		GROUP_CONCAT(uf.shopping_list_name,"@") AS shopping_list_ing,
		GROUP_CONCAT(umi.quantity,"") AS quantity,
		GROUP_CONCAT(umi.unit_id ,"") AS unit_id,
		GROUP_CONCAT(umi.weight ,"") AS real_weight,
		uslc.name as cat_name 
		FROM up_meal_ingredients as umi 
		INNER JOIN up_foods as uf  on umi.name=uf.name 
		JOIN up_units as uun on uun.id=umi.unit_id 
		JOIN up_shopping_list_categories as uslc on uf.shopping_list_category_id=uslc.id 
		WHERE uf.id IN('.$week_ing.') GROUP BY uslc.name ORDER BY uslc.id';
	 */
	 $sql1="SELECT GROUP_CONCAT(uf.shopping_list_name,'@') AS shopping_list_ing,
			GROUP_CONCAT(umi.quantity,'') AS quantity,
			GROUP_CONCAT(umi.unit_id ,'') AS unit_id,
			GROUP_CONCAT(umi.weight ,'') AS real_weight, 
			uslc.name as cat_name 
			FROM up_order_meals om 
			INNER JOIN up_foods uf ON find_in_set(uf.id, om.ingredient_ids) > 0 
			INNER JOIN up_meal_ingredients umi ON umi.meal_id = om.meal_id 
			and (uf.name = umi.name or umi.name in 
		(select f1.name from up_foods f1 where find_in_set(uf.nid, replace(f1.exchangeable_with,". "' ',"."''". ")) > 0 
			and find_in_set(f1.id, om.ingredient_ids) = 0)) 
			INNER JOIN up_units as uun on uun.id=umi.unit_id 
			INNER JOIN up_shopping_list_categories as uslc on uf.shopping_list_category_id=uslc.id 
			WHERE om.order_id=".$order_id." and om.site_id=".$site_id." and om.meal_id IN (".$final_meals.")
			GROUP BY uslc.name ORDER BY uslc.id";
			//	echo $sql1;
			
			$wpdb->query(' SET SESSION group_concat_max_len = 100000');
			$shopping_list=$wpdb->get_results($sql1);
			
			//echo "<pre>";	print_r($shopping_list);echo "</pre>";	
 ?>
<div class="container">
    <div style="background:#f0eeef;margin:0 auto;outline:none;padding:0px; line-height:15px; width:100%; ">
		  
		<div class="page_wrapper">
         <!-- 1 box -->
			<div class="p5-listStyleP" style="width:100%; float:left;padding:10px 16px 0px 16px; ">
				<div style="border-bottom:0.0315em solid #bec8cc; text-align:center; margin-bottom:10px; padding-bottom:5px;">
					<h2 style="font-size:15px;margin:5px 0 9px; color:#162c5d">EINKAUFSLISTE WOCHE <?php echo $week_no; ?>
					</h2>
				
				</div>
				<div class="shopping_list_column">
				<?php
					$c=0;
					$d=$b=0;
					$a=34;
					foreach($shopping_list as $list) {
						//$c++;
						$ct++;
						if($c == $a || $c >= $a-$d){
							$d=$c=0;
							if($b != 4){
								$b++;
								echo '</div><div class="shopping_list_column">';
							}
							else if($b==4){
								$b=0;
								echo '</div>
									</div>
									</div>
								 <div class="page_wrapper">
								<div class="p5-listStyleP" style="width:100%;float:left;padding:10px 16px 0px 16px;">
									<div style="border-bottom:0.0315em solid #bec8cc; text-align:center; margin-bottom:10px; padding-bottom:5px;">
										<h2 style="font-size:15px;margin:5px 0 9px; color:#162c5d">EINKAUFSLISTE WOCHE '.$week_no.'</h2>
									
									</div>
									<div class="shopping_list_column">';
							}
						}
						if($b>=0 ){
							if($ct!=1){	
								if($c == $a || $c >= $a-($d+4) || $c >= $a-4 ){
									$d=$c=0;
									$b++;
									echo '</div><div class="shopping_list_column">';
								}
								if($c>=1 ){
									
					?>
						<div><?php $c++; ?><p style="height:4.5px;float:left; width:100%;margin:5px 0 7px;padding:0;">&#32;</p></div>
						<div class="shopping_hr_border"></div>
						<div><?php $c++; ?><p style="height:7px; float:left; width:100%;margin:5px 0 8px;padding:0;">&#32;</p></div>
						<?php
								}
							}
						}
						 	
						if($c == $a || $c >= $a-$d ){
							$d=$c=0;
							$b++;
							echo '</div><div class="shopping_list_column">';
						}
						
						?> 
						<h3 class="category_name"><?php $c++; echo $list->cat_name;?></h3>
						<?php 
							if($c==$a || $c >= $a-$d){
							$d=$c=0;
								$b++;
								//echo $c;
								echo '</div><div class="shopping_list_column">';
							}
							
						?>
						<div>
							<?php $c++; ?>
							<p style="height:7px;float:left; width:100%;margin:7px 0 7px;padding:0;">&#32;</p>
						</div>
					<?php	
						$shopping_list_ing=explode('@,',$list->shopping_list_ing);
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
						$shopping_list_ing = array_map(function($el) { return str_replace('@','',$el); }, $shopping_list_ing);
						//file_put_contents('inj.json', json_encode($shopping_list_ing),FILE_APPEND);
						
						for( $i = 0; $i <= sizeof($shopping_list_ing); $i++) 
						{
							if(isset($shopping_list_ing[$i])){
								$ing_q_ar[$shopping_list_ing[$i]][]=$quantity[$i];
								$ing_w_ar[$shopping_list_ing[$i]][]=$real_weight[$i];
								$unit_id_ar[$shopping_list_ing[$i]][]=$unit_id[$i];
								if(!in_array($shopping_list_ing[$i],$ing_names))
									$ing_names[]=str_replace("@", '', $shopping_list_ing[$i]);
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
						
					foreach($w_sum as $k=>$v){
							
						if(isset($k)) {
							$c++;
							if($b==4){
								$b=0;
								echo '</div>
									</div>
									</div>
								 <div class="page_wrapper">
								<div class="p5-listStyleP" style="width:100%; float:left;padding:10px 10px 0px 10px;">
									<div style="border-bottom:0.0315em solid #bec8cc; text-align:center; margin-bottom:10px; padding-bottom:5px;">
										<h2 style="font-size:15px;margin:8px 0 8px; color:#162c5d">EINKAUFSLISTE WOCHE '.$week_no.'</h2>
									
									</div>
									<div class="shopping_list_column">';
							}
							if( $unit_id_ar_c[$k] > 1) { 
								$value=str_replace(".",",",(float)$v);
								$u='g';
							}
							else{
								//echo 'select unit_symbol from up_units where id='.array_unique($unit_id_ar[$k])[0];
								$value=str_replace(".",",",(float)$q_sum[$k]);
								$u_res=$wpdb->get_results('select unit_symbol from up_units where id='.$unit_id_ar[$k][0]);
								//print_r($u_res);
								$u=$u_res[0]->unit_symbol;
							}
							
								if(strlen($k)>21){
								//	$k1=substr($k,0,18).'...';
								
									$d++;
								}
								//echo strlen('Magerquark (<10% Fett)');
							}
							//echo strlen('Magerquark (<10% Fett)');
							//echo $c;
							?>
							<div class="font-size-same" style="line-height:15px;margin:0;width:100%;float:left;">
								<div style="float:left; width:12px; padding-top:3px;padding-right:5px;">
									<img height=10 width=10 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/checkbox.png">
								</div>
								<div style="width:80px; float:left;">
									<span><?php echo $value.' '.$u; ?></span>
								</div>
								<div style="float:left;width:120px;word-break:break-word ">
									<span><?php echo $k;?></span>
								</div>
							</div>
						<?php
							
							if($c==$a || $c >= $a-$d){
								$d=$c=0;
								$b++;
								if($b != 4){
									echo '</div><div class="shopping_list_column">';
								}else if($b==4){
									$b=0;
									echo '</div>
									</div>
									</div>
								 <div class="page_wrapper">
								<div class="p5-listStyleP" style="width:100%;float:left;padding:10px 16px 0px 16px;">
									<div style="border-bottom:0.0315em solid #bec8cc; text-align:center; margin-bottom:10px; padding-bottom:5px;">
										<h2 style="font-size:15px;margin:8px 0 8px; color:#162c5d">EINKAUFSLISTE WOCHE '.$week_no.'</h2>
									</div>
									<div class="shopping_list_column">';
								}
							}
						}
					}?>
					
				</div>  
			</div>  
        </div>  
    </div>
</div>
</body>
</html>
