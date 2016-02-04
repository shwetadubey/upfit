<?php 
/*
 * Template Name: PDF3 (Tipps & Tricks )
 */
?>
<!DOCTYPE html>
<html lang="en">
 <head>
 <style>
 	.p3-last-div{
		 margin-bottom: 0
		}
		@page{
			margin-footer: 0mm;margin-bottom: 0mm;
			}
 </style>
 
 </head>
  <body class="grey">
  <div style="background:#f0eeef;overflow: hidden;text-align:center;;page-break-inside:avoid; color:#70848e;marign:0">
      <div style="padding:0px;outline:none;margin:0; line-height:22px">
        
      <?php
       $taxonomy = 'tipps_tricks_category';
		$tax_terms = get_terms($taxonomy);
       //    print_r($tax_terms);
	    $posts=get_posts( array('post_type'=>'tipps_tricks',
											'order'   => 'ASC',
											'orderby' => 'menu_order'
											
								 //or 'meta_value_num'
								));
								//print_r($posts);
           ?>
		 <div style="width:94.5%;text-align:center; margin:0 36px 0px 38.4px;overflow: hidden; position: absolute;">
			 
		  <?php foreach($posts as $p){
					$tax=get_the_terms($p->ID,$taxonomy);
		  ?>
			 <div class="p4-listStyleP" style="width: 25%; float:left; ">
					
				<div class="p3-main-div" >
					<div style="border-bottom:0.063em solid #bec8cc; text-align:center; margin-bottom:16.8px; padding-bottom:8.5px;">
						<h2 style="font-size:15px;font-weight:normal;margin:4.5px 0 0; color:#162c5d">
						<?php echo $tax[0]->name; ?>
						</h2>
						<p style="font-size:12px;margin:6px 0px 6px;">
							 <?php
								
									  echo $p->post_title;
								
							?>
						</p>
					</div>
					<div class="p3-listStyleP" style="margin-bottom:0px;">
					  <?php
						  echo $p->post_content;
					  
						  ?>
					</div>
				</div>
   
			</div>
   <?php }?>
           
		
         </div>
      </div>
    </div>
  </body>
</html>
