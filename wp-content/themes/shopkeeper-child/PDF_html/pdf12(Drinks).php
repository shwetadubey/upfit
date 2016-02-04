
<?php 
/*
 * Template Name: PDF12(Fatburner Drinks)
 */
?>

<!DOCTYPE html>
<html lang="en">
 
 <head>
	<style>
		.box-heading2 {
			text-align: left;
			color: #162c5d;
			text-transform: uppercase;
			font-size: 12px;
			margin: 0;
			padding: 0;
		}
		.box-description-text, .inner-table-detail {
			margin: 0;
			padding: 0;
			text-align: left;
			font-size: 11px;
			margin-top: 5px;
			line-height: normal;
		}
		.border-bottom {
			border-bottom: 1px solid #eaedef;
			margin-bottom: 17.1px;
			padding-bottom: 10.2px;
		}
		.inner-table-detail:first-of-type {
			margin-right: 25px;
			display: inline-block;
			width: 50px;
			color:#70848e;
			font-weight:lighter;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			margin-top: 0;
		}
		.separator {
			border: 0 none;
			border-top:1px solid #F0EEEF;
			margin: 15px 0;
		}
		/*.p6-listStyleP { margin-bottom:0px;	position: relative;	height: 100%; min-height: 150mm;}*/
	</style>
 </head>
 
  <body>
 
 <div style="background:#f0eeef;text-align:center;overflow:hidden; color:#70848e;">
      <div style="margin:0 auto;padding:0px 0px;outline:none; line-height:22px; ">
         <div style="width:94.5%;text-align:center; margin:0 36px 0px 38.4px;">
            <!-- 1 box -->	
           
<!---- 1/1 ---->
			<div class="p6-listStyleP" style="width: 25%; float:left; ">
				<?php
					//----------Change Drink id here--------------//
					$drink=get_post(1706);
						
					if(!empty($drink) && $drink->post_type="fatburner_drink"){
					?>
				 <div class="" style="background:#FFF;padding:10px 16px;margin:15px 15px 0px 0px;border-radius:5px;height:345px;max-height:345px;overflow: hidden;">
					<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:17.4px; padding-bottom:17.4px;">
						<h2 style="font-size:15px;margin:0px 0; color:#162c5d"><?php echo $drink->post_title; ?></h2>
						<p style="font-size:12px;margin:3px 0px 8px;"><?php echo get_post_meta($drink->ID,'drink_short_disc',true) ?></p>

						<table class="p6-upfiticon">
							
							
							<tr>
								<td>
									<?php
										$drink_type=get_post_meta($drink->ID,'drink_type',true);
									  ?>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/<?php echo $drink_type?>.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo $drink_type ?></span>
								</td>
								
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_calary',true);?> kcal</span>
								</td>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_time',true);?> min</span>
								</td>
							</tr>
						</table>
					</div>
					
					<div class="postid">
					<?php 
						echo $drink->post_content;
					 ?>
					</div>
					
				</div>
			<?php }
				unset($drink);
					
				$drink=get_post(1708);
					
				if(!empty($drink) && $drink->post_type="fatburner_drink"){
			 ?>
				 <div class="" style="background:#FFF;padding:10px 16px;margin:15px 15px 0px 0px;border-radius:5px;height:275px;max-height:275px;overflow: hidden;">
					<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:17.4px; padding-bottom:17.4px;">
						<h2 style="font-size:15px;margin:0px 0; color:#162c5d"><?php echo $drink->post_title; ?></h2>
						<p style="font-size:12px;margin:3px 0px 8px;"><?php echo get_post_meta($drink->ID,'drink_short_disc',true) ?></p>

						<table class="p6-upfiticon">
							<tr>
								<td>
									<?php
									$drink_type=get_post_meta($drink->ID,'drink_type',true);
									  ?>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/<?php echo $drink_type?>.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo $drink_type ?></span>
								</td>
								
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_calary',true);?> kcal</span>
								</td>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_time',true);?> min</span>
								</td>
							</tr>
						</table>
					</div>
					
					<div class="postid">
					<?php 
						echo $drink->post_content;
					 ?>
					</div>
				 </div>
				<?php }?>
            </div>

                 <!---- 2/1 ---->
			<div class="p6-listStyleP" style="width: 25%; float:left; ">
				<?php
					unset($drink);
					
					$drink=get_post(1692);
				 	if(!empty($drink) && $drink->post_type="fatburner_drink"){
						//print_r($post);
				?>
				 <div class="" style="background:#FFF;padding:10px 16px;margin:15px 15px 0px 0px;border-radius:5px;height:240;">
					<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:17.4px; padding-bottom:17.4px;">
						<h2 style="font-size:15px;margin:0px 0; color:#162c5d"><?php echo $drink->post_title; ?></h2>
						<p style="font-size:12px;margin:3px 0px 8px;"><?php echo get_post_meta($drink->ID,'drink_short_disc',true) ?></p>

						<table class="p6-upfiticon">
							<tr>
								<td>
									<?php
									$drink_type=get_post_meta($drink->ID,'drink_type',true);
									  ?>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/<?php echo $drink_type?>.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo $drink_type ?></span>
								</td>
								
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_calary',true);?> kcal</span>
								</td>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_time',true);?> min</span>
								</td>
							</tr>
						</table>
					</div>
					<div class="postid">
					<?php 
						echo $drink->post_content;
					 ?>
					</div>
					<?php } ?>
				
				 </div>
				<?php
					unset($drink);
					
					$drink=get_post(1704);
				 	if(!empty($drink) && $drink->post_type="fatburner_drink"){
						//print_r($post);
				?>

				 <div class="" style="background:#FFF;padding:10px 16px;margin:15px 15px 0px 0px;border-radius:5px;height:362;">
					<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:17.4px; padding-bottom:17.4px;">
						<h2 style="font-size:15px;margin:0px 0; color:#162c5d"><?php echo $drink->post_title; ?></h2>
						<p style="font-size:12px;margin:3px 0px 8px;"><?php echo get_post_meta($drink->ID,'drink_short_disc',true) ?></p>

						<table class="p6-upfiticon">
							<tr>
								<td>
									<?php
									$drink_type=get_post_meta($drink->ID,'drink_type',true);
									  ?>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/<?php echo $drink_type?>.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo $drink_type ?></span>
								</td>
								
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/874kcal.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_calary',true);?> kcal</span>
								</td>
								<td>
									<img width=48 height=48 src="<?php echo get_template_directory_uri(); ?>-child/images/icons/time.png" alt=""/><br/>
									<span class="p6-iconDescription"><?php echo get_post_meta($drink->ID,'drink_time',true);?> min</span>
								</td>
							</tr>
						</table>
					</div>
					<div class="postid">
					<?php 
						echo $drink->post_content;
					 ?>
					</div>

				 </div>
	
				<?php } ?>
			 </div>

                 <!---- 3/1 ---->
			<div class="p6-listStyleP" style="width: 25%; float:left; ">
				<?php
					unset($drink);
					
					$drink=get_post(983);
				 	if(!empty($drink) && $drink->post_type="fatburner_drink"){
						//print_r($post);
				?>
	                 <div class="" style="background:#FFF;padding:10px 16px;margin:15px 15px 0px 0px;border-radius:5px;height:655.4px;">
	                 	<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:17.4px;">
	                 		<h2 style="font-size:15px;margin:0px 0; color:#162c5d"><?php echo $drink->post_title; ?></h2>
	                 		<p style="font-size:12px;margin:3px 0px 8px;"><?php echo get_post_meta($drink->ID,'drink_short_disc',true) ?></p>


	                 	</div>
							<div class="postid">
							<?php 
								echo $drink->post_content;
							 ?>
							</div>

				</div>
			<?php }
					unset($drink);
					
					$drink=get_post(1239);
				 	if(!empty($drink) && $drink->post_type="fatburner_drink"){
						//print_r($post);
				?>
			<?php }?>

                 </div>

                 <!---- 4/1 ---->
			<div class="p6-listStyleP" style="width: 25%; float:left; ">
            
				<?php $drink=get_post(997);
				 	if(!empty($drink) && $drink->post_type="fatburner_drink"){ ?>
	                 <div class="" style="background:#FFF;padding:10px 16px;margin:15px 15px 0px 0px;border-radius:5px;height:647.4px;">
	                 	<div style="border-bottom:1px solid #eaedef; text-align:center; margin-bottom:17.4px;">
	                 		<h2 style="font-size:15px;margin:0px 0; color:#162c5d"><?php echo $drink->post_title; ?></h2>
	                 		<p style="font-size:12px;margin:3px 0px 8px;"><?php echo get_post_meta($drink->ID,'drink_short_disc',true) ?></p>

	                 	</div>
						<div class="postid">
							<?php 
								echo $drink->post_content;
							 ?>
							</div>

	                 </div>

				<?php }
					unset($drink);
					
					$drink=get_post(1235);
				 	if(!empty($drink) && $drink->post_type="fatburner_drink"){
						//print_r($post);
				?>

				<?php } ?>

                 </div>


			</div>
         </div>
    </div>
  </body>
</html>



