	<div class="p4-listStyleP" style="width: 25%; float:left;">
		<!-----------------Dinner-------------->
			<?php 
				if(!empty($dinner[0])){
						include 'dinner.php';
					}?>
                     
	</div>
           
            <!-- 4 box -->
            
        <div class="p4-listStyleP" style="width: 25%; float:left;">
			<?php 
			if(!empty($pre_lunch_snack[0])){
				include 'pre_lunch_snack_small.php';
			 }
				if(!empty($pre_dinner_snack[0])){
				include 'pre_dinner_snack_small.php';
							 } ?>
	
		</div>
          
