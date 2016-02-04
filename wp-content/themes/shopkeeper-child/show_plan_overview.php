<?php
add_shortcode('display_anchorheading','show_anchorheading');
function show_anchorheading($atts)
{
	$args = array(
	'posts_per_page'   => 4,
	'category'         => '',
	'category_name'    => '',
	'offset'           => 0,
	'order'     => 'ASC',
	'meta_key' => '_price',
	'orderby'   => 'meta_value_num', 
	/*'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',*/
	'post_type'        => 'product',
	/*'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',*/
	'post_status'      => 'publish',
	'suppress_filters' => true,
	'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'plan-pricing'
            )
        )
);

$the_query = new WP_Query( $args );
//print_r($the_query);
$i=1;
$page = get_page_by_path( 'ernaehrungsplan_erstellen' );
//echo $page->ID;

while ( $the_query->have_posts() ) : $the_query->the_post();
$period = get_post_meta(get_the_ID(),'plan_period',true);
$regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
$sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
$weight_loss=get_post_meta(get_the_ID(),'range_of_weight_loss_meta',true);
$reason_desc=get_post_meta(get_the_ID(),'reason_description_meta',true);
$extras=get_post_meta(get_the_ID(),'extras',true);

$price_info_label=get_post_meta(get_the_ID(),'price_info_label',true);
$range_of_weight_label=get_post_meta(get_the_ID(),'range_of_weight_label',true);
$reason_description_label=get_post_meta(get_the_ID(),'reason_description_label',true);
$extras_label=get_post_meta(get_the_ID(),'extras_label',true);

$plan_name = explode(" ",get_the_title());


//$period=get_post_meta($post->ID,'plan_period',true);
if($i%2==0)
{
?>

<div class="plan_overview even" id="<?php echo strtolower($plan_name[1]);?>">
<?php } 
else
{
?>
<div class="plan_overview odd" id="<?php echo strtolower($plan_name[1]);?>">
<?php }?>
	<div class="box_wrapper content_highlight">
	  <div class="row">
		
		<div class="vc_col-sm-6 wpb_column vc_column_container img_box">
			<div class="price_tag2 tag_<?php echo strtolower($plan_name[1]) ?>">
			<span><?php 
				if(isset($sale_price) && !empty($sale_price)){
					$price=$sale_price;
				}
				else{
					$price=$regular_price;
				}
			echo $price.get_woocommerce_currency_symbol();?></span>
			<p><?php  echo "Nur ".number_format($price/($period*7),2,',','').get_woocommerce_currency_symbol() . "/Tag";?></p>
		</div>
		<div class="price_info2">
			<p>Berücksichtigt </br>Allergien,</br>Intoleranzen</p>
		</div>
			<?php
				if (has_post_thumbnail()) {
						the_post_thumbnail();
				} else {
						$noimg = get_template_directory_uri() . '/img/noimage.jpg';
						?>
						<img src="<?php echo $noimg; ?>" style="height: 160px;width: 160px;"/>
						<?php
				}
				?>
		</div>
		<div class="vc_col-sm-6 wpb_column vc_column_container txt_box">
			<div class="details_overview">
								
				<p><?php echo $period;?>&nbsp;Wochen</p>
				<h3><?php the_title();?></h3>
				<div>
					<span><b><?php echo $price_info_label; ?> </b>
					<?php if(isset($sale_price) && !empty($sale_price)){?>
					<p><?php echo $sale_price.get_woocommerce_currency_symbol()." <strong>".$regular_price.get_woocommerce_currency_symbol()."</strong> (Nur ".number_format($sale_price/($period*7),2,',','').get_woocommerce_currency_symbol() . " pro Tag)";?></p></span	>
					<?php
					} 
					else{
						?>
						<p><?php echo $regular_price.get_woocommerce_currency_symbol()." (Nur ".number_format($regular_price/($period*7),2,',','').get_woocommerce_currency_symbol() . " pro Tag)";?></p></span>
					<?php }?>
				</div>
				<div>
					<span><b><?php echo $range_of_weight_label; ?> </b><p><?php echo $weight_loss;?></p></span>
				</div>
				<div>
					<span><b><?php echo $reason_description_label; ?></b><p><?php echo $reason_desc;?></p></span>
				</div>
				<div>
					<span><b><?php echo $extras_label; ?> </b><p><?php echo $extras;?></p></span>
				</div>
				<div class="welter_btn">
					<a class="btnext"  href="<?php echo get_permalink($page->ID); ?>" target="_self"><button type="submit" value="">Plan auswählen</button></a>
				</div>
			</div>	
			
		
	  </div>
	</div>
	
	</div>
	</div>
<?php
$i++;
endwhile;
wp_reset_postdata();
}
