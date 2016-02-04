<?php 
add_shortcode('plan_listing','plan_listing_with_description');
function plan_listing_with_description(){
	
	$plans=get_posts(array('post_type'=>'product',
							'tax_query' => array( array(
										'taxonomy' => 'product_cat',
										//'field' => 'term_id',
										//'terms' => 6,
										'field' => 'slug',
										'terms' => 'plan-pricing'
										)),
							'posts_per_page'    => 4,
							'order'     => 'ASC',
							'meta_key'=>'_price',
						/*	'meta_query' => array(
								'relation' => 'OR',
								'_regular_price' => array(
									'key' => '_regular_price',
								  //  'value' => 'Wisconsin',
								  'orderby' => 'meta_value_num',
                        'order' => 'ASC'
								),
								'_sale_price' => array(
									'key' => '_sale_price',
									'orderby' => 'meta_value_num',
                        'order' => 'ASC'
									//'compare' => 'EXISTS',
								), 
							),*/
							'orderby'   => 'meta_value_num'
							
							)
					);
	//print_r($plans);

?>	
<div class="pricing_table">
<?php

 global  $woocommerce;
  $checkout_url = $woocommerce->cart->get_checkout_url();
	$pl=0;
  //echo $checkout_url;
	foreach($plans as $p){
		
		//print_r($p);
		$regular_price=get_post_meta($p->ID,'_regular_price',true);
		$period=get_post_meta($p->ID,'plan_period',true);
		$best=get_post_meta($p->ID,'choose_besteller',true);
		$sale_price=get_post_meta($p->ID,'_sale_price',true);
		$plan_name = explode(" ", $p->post_title);
		$home = home_url();
		$p1++;
	//echo do_shortcode('[add_to_cart id='.$p->ID.']');
		 if($best=='yes') {
			 
		?>
		<div class="price_table_wrapper ">
		<div class="price_table  <?php echo 'pl_'.strtolower($plan_name[1]); ?> active " datap="<?php echo 'plan_'.$p1; ?>">
			
          <h4>BESTSELLER</h4>
          
          <div class="top_price ">
              <span><a href="javascript:void(0)" id="<?php echo strtolower($plan_name[1]);?>"><?php echo strtoupper($plan_name[0]).'<br/>'.strtoupper($plan_name[1]); ?></a></span>
              <?php
               if(isset($sale_price) && !empty($sale_price)){ ?>
				<strong><?php echo $sale_price.get_woocommerce_currency_symbol(); ?><span class="text-through"><?php echo $regular_price.get_woocommerce_currency_symbol(); ?></span></strong>
				<?php }
					else{?>
						<strong><?php echo $regular_price.get_woocommerce_currency_symbol(); ?></strong>
				<?php	
					}
				?>
				<p><?php echo $period; ?>&nbsp;Wochen</p>
        <!--<img src="<?php// echo get_template_directory_uri(); ?>-child/images/border-triangle_green_bg.png" alt="">-->
          </div>
          <div class="border-triangle_best"></div>
         
          <div class="center_description">
            <p><?php echo get_post_meta($p->ID,'Description1',true);?></p>
				<p><?php echo get_post_meta($p->ID,'Description2',true);?></p>
				<p><?php echo get_post_meta($p->ID,'Description3',true);?></p>
            <a href="<?php echo $checkout_url.'?add-to-cart='.$p->ID ?>" weeks="<?php echo $period; ?>" data-id="<?php echo $p->ID ?>" class="plan_redirect_btn"><?php
				$btn_text=get_post_meta($p->ID,'plan_button_text',true);
				if(isset($btn_text) && !empty($btn_text)){
					echo $btn_text;
					}
					else{
						echo 'Auswählen';
						}
				 ?></a>
            <?php
			 if(isset($sale_price) && !empty($sale_price)){
					$price=$sale_price;
				 }else{
					 $price=$regular_price;
				 }
             ?>
            <span>Nur <?php echo number_format($price/($period*7),2,',','').get_woocommerce_currency_symbol(); ?>/Tag</span>
          </div>
		</div>
		<a class="plan_more_details" href="<?php echo home_url(); ?>/ernaehrungsplaene_uebersicht/#<?php echo strtolower($plan_name[1]);?>">Mehr Details</a>
		</div>
		<?php
	}else{
		
		
		?>
		<div class="price_table_wrapper ">
		<div class="price_table  <?php echo 'pl_'.strtolower($plan_name[1]); ?> " datap="<?php echo 'plan_'.$p1; ?>">
		
		 <h4>BESTSELLER</h4>
			<div class="top_price">
				<span><a href="javascript:void(0)" id="<?php echo strtolower($plan_name[1]);?>"><?php echo strtoupper($p->post_title); ?></a></span>
				 <?php
					if(isset($sale_price) && !empty($sale_price)){ ?>
						<strong><?php echo $sale_price.get_woocommerce_currency_symbol(); ?><span class="text-through"><?php echo $regular_price.get_woocommerce_currency_symbol(); ?></span></strong>
					<?php }
						else{?>
						<strong><?php echo $regular_price.get_woocommerce_currency_symbol(); ?></strong>
					<?php	
						}
					?>
				<p><?php echo $period; ?>&nbsp;Wochen</p>
			</div>
		
			 <div class="border-triangle"></div>
			  <div class="center_description">
				<p><?php echo get_post_meta($p->ID,'Description1',true);?></p>
				<p><?php echo get_post_meta($p->ID,'Description2',true);?></p>
				<p><?php echo get_post_meta($p->ID,'Description3',true);?></p>
				
				<a href="<?php echo $checkout_url.'?add-to-cart='.$p->ID ?>" weeks="<?php echo $period; ?>" data-id="<?php echo $p->ID ?>" class="plan_redirect_btn"><?php
				$btn_text=get_post_meta($p->ID,'plan_button_text',true);
				if(isset($btn_text) && !empty($btn_text)){
					echo $btn_text;
					}
					else{
						echo 'Auswählen';
					}
				 ?></a>
				<?php
			 if(isset($sale_price) && !empty($sale_price)){
					$price=$sale_price;
				 }else{
					 $price=$regular_price;
				 }
             ?>
				<span>Nur <?php echo number_format($price/($period*7),2,',','').get_woocommerce_currency_symbol(); ?>/Tag</span>
			  </div>
		 </div>
		 		<a class="plan_more_details" href="<?php echo home_url(); ?>/ernaehrungsplaene_uebersicht/#<?php echo strtolower($plan_name[1]);?>" >Mehr Details</a>
		  </div>
		<?php
	
	}
}
?>
</div>
<?php	
}
