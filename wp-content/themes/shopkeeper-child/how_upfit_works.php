<?php 
add_shortcode('how_upfit_works','how_upfit_works_listing');
function how_upfit_works_listing(){
	$posts=get_posts(array('post_type'=>'how_upfit_works','posts_per_page'=>3));
?>
<div class="maincol block_warum">
<?php
	foreach($posts as $p){
	
	?>
	<div class="col">
		<div class="img_warum">
			<?php  
				if(get_the_post_thumbnail($p->ID,'full')){
					echo get_the_post_thumbnail($p->ID,array(200,200));
				}
				else{
				?>
					<img src="<?php echo get_template_directory_uri() . '-child/images/noimage.jpg'; ?>"/>
					
			<?php
				}
			?>
		</div>
		<div class="heading_warum"><h4><?php echo $p->post_title; ?></h4></div>
		<div class="details_warum"><?php echo $p->post_content; ?></div>
	
	</div>
	<?php
	}
?>
</div>
<?php
}
