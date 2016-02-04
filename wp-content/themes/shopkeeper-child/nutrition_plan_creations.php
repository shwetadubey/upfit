<?php
/*
 * Template Name: Nutrition plan creation Template 
 */
global $shopkeeper_theme_options;

$page_id = "";
if (is_single() || is_page()) {
    $page_id = get_the_ID();
} else if (is_home()) {
    $page_id = get_option('page_for_posts');
}

$page_header_src = "";

if (has_post_thumbnail())
    $page_header_src = wp_get_attachment_url(get_post_thumbnail_id($page_id));

$page_title_option = "on";

if (get_post_meta($page_id, 'page_title_meta_box_check', true)) {
    $page_title_option = get_post_meta($page_id, 'page_title_meta_box_check', true);
}
?>

<?php
get_header();

$data = get_posts(array('post_type' => 'page', 'order' => 'ASC', 'orderby' => 'date','meta_query' => array(
		array(
			'key' => 'nutrition_plan_creations',
			'value' => 'form',
		)
	)));
foreach ($data as $k) {
    if ($k->post_title != 'Home') {
        ?>
        <div class="gewicht_pat">
            <div class="gewicht_form first_list">
                <h2 class="form_heading "><?php echo $k->post_title; ?></h2>
                <p class="animated pulse"><?php echo get_post_meta($k->ID, 'description', true); ?></p>
                <div>
                    <?php echo do_shortcode($k->post_content); ?>
                </div>

            </div>



        </div>
		
        <?php
    }
}


get_footer();
?>
