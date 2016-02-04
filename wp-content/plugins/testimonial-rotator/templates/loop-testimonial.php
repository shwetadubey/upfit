<?php

// WRAPPER
echo "<div class=\"slide slide{$slide_count} testimonial_rotator_slide hreview itemreviewed item {$has_image} cf-tr\">\n";		

// POST THUMBNAIL
if ( $has_image )
{ 
	echo "	<div class=\"testimonial_rotator_img img\">" . get_the_post_thumbnail( get_the_ID(), $img_size) . "</div>\n"; 
}

// DESCRIPTION
echo "	<div class=\"text testimonial_rotator_description\">\n";

// IF SHOW TITLE
if($show_title) echo "	<{$title_heading} class=\"testimonial_rotator_slide_title\">" . get_the_title() . "</{$title_heading}>\n";
$weight_change=get_post_meta(get_the_ID(),'testimonial_weight_badge',true);
echo '<p class="testimonial_rotator_weight_badge">'.$weight_change.' kg</p>';
// RATING
if($rating)
{
	echo "<div class=\"testimonial_rotator_stars cf-tr\">\n";
	for($r=1; $r <= $rating; $r++)
	{
		echo "	<span class=\"testimonial_rotator_star testimonial_rotator_star_$r\"><i class=\"fa {$testimonial_rotator_star}\"></i></span>";
	}
	echo "</div>\n";
}

// CONTENT
echo "<div class=\"testimonial_rotator_quote\">\n";
echo ($show_size == "full") ? do_shortcode(nl2br(get_the_content(' '))) : get_the_excerpt();
echo "</div>\n";
// Button and Notice
$notice=get_post_meta(get_the_ID(),'testimonial_notice',true);
$btntext=get_post_meta(get_the_ID(),'testimonial_button_text',true);
$page_id=get_post_meta(get_the_ID(),'testimonial_button_link',true);
if(isset($page_id) && !empty($page_id)){
	$pagelink= get_page_link($page_id);
}
else{
$pagelink='';
}
if(isset($btntext) && !empty($btntext) && isset($pagelink) && !empty($pagelink)){
	echo '<div class="blue_hover vc_btn3-center vc_custom_1447678019882">
	<a href="'.$pagelink.'" title="" target="_self">'.strtoupper(get_post_meta(get_the_ID(),'testimonial_button_text',true)).
	'<i class="vc_btn3-icon fa fa-angle-right"></i>
	</a></div>';
}
//Notice
if(isset($notice) && !empty($notice)){
	echo '<div>';	
	echo '<p class="testimonial_rotator_notice">'.get_post_meta(get_the_ID(),'testimonial_notice',true). '</p>';
	echo '</div>';
}
// AUTHOR INFO
if( $cite )
{
	echo "<div class=\"testimonial_rotator_author_info cf-tr\">\n";
	echo wpautop($cite);
	echo "</div>\n";				
}

echo "	</div>\n";

// MICRODATA 
if($show_microdata)
{
	$global_rating = $global_rating + $rating;

	echo "	<div class=\"testimonial_rotator_microdata\">\n";

		if($itemreviewed) echo "\t<div class=\"fn\">{$itemreviewed}</div>\n";
		if($rating) echo "\t<div class=\"rating\">{$rating}.0</div>\n";

		echo "	<div class=\"dtreviewed\"> " . get_the_date('c') . "</div>";
		echo "	<div class=\"reviewer\"> ";
			echo "	<div class=\"fn\"> " . wpautop($cite) . "</div>";
			if ( has_post_thumbnail() ) { echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array('class' => 'photo' )); }
		echo "	</div>";
		echo "	<div class=\"summary\"> " . wp_trim_excerpt(get_the_excerpt()) . "</div>";
		echo "	<div class=\"permalink\"> " . get_permalink() . "</div>";
	
	echo "	</div> <!-- .testimonial_rotator_microdata -->\n";
}

echo "</div>\n";
