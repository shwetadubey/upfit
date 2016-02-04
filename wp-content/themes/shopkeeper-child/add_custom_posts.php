<?php
add_action( 'init', 'create_post_type' );
function create_post_type() {
	/*----------Nutrition Plan Visualization(PDF Pages)-------------*/
	$labels=array(
			'name' => __( 'PDF Seiten' ),
			'singular_name' => __( 'PDF' ),
			'menu_name'=>_('PDF Seiten'),
			'name_admin_bar'=>_('Erstellen PDF Seiten'),
			'all_items'=>_('Alle PDF Seiten'),
			'add_new'=>_('Erstellen'),
			'add_new_item'=>_('Erstellen Seite'),
			'edit_item'=>_('Edit Page'),
			'new_item'=>_('Erstellen PDF Seite'),
			'view_item'=>_('View PDF Seite'),
			'search_items'=>_('Search Seite'),
			'not_found'=>_('Not Found'),
			'not_found_in_trash'=>_('Not Found in Trash'),
			'parent_item_colon'=>_('PDF Seiten'),
		);
	$args= array(
			'labels' => $labels,
			'description'=>'Nutrition Plan Visualization',
			'public' => true,
			'capability_type'    => 'post',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'has_archive' => true,
			'rewrite' => array('slug' => 'pdf'),
			'supports'=>array('title','editor','thumbnail','author','custom-fields','revisions'),
			'can_export'=>true,
	);
  register_post_type( 'pdf',$args);
  /*-----------------Tootlips--------------------*/
  $labels=array(
			'name' => __( 'Alle Tooltipps' ),
			'singular_name' => __( 'Tooltip' ),
			'menu_name'=>_('Tooltipps'),
			'name_admin_bar'=>_('Erstellen Tootlip'),
			'all_items'=>_('Alle Tooltipps'),
			'add_new'=>_('Erstellen'),
			'add_new_item'=>_('Erstellen Tootlip'),
			'edit_item'=>_('Edit Tootlip'),
			'new_item'=>_('Erstellen Tootlip'),
			'view_item'=>_('View Tootlip'),
			'search_items'=>_('Search Tootlip'),
			'not_found'=>_('Not Found'),
			'not_found_in_trash'=>_('Not Found in Trash'),
			'parent_item_colon'=>_('Tooltipps'),
		);
	$args= array(
			'labels' => $labels,
			'description'=>'Tootlips',
			'public' => true,
			'capability_type'    => 'post',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'has_archive' => true,
			'rewrite' => array('slug' => 'tooltip'),
			'supports'=>array('title','editor','thumbnail','author','revisions'),
			'can_export'=>true,
	);
	 register_post_type( 'tooltip',$args);
	 
	
	  $labels=array(
			'name' => __( ' Fettkiller Getränkeliste' ),
			'singular_name' => __( 'Getränke' ),
			'menu_name'=>_(' Fettkiller Getränkeliste'),
			'name_admin_bar'=>_('Erstellen Getränke'),
			'all_items'=>_(' Alle Getränke'),
			'add_new'=>_('Erstellen'),
			'add_new_item'=>_('Erstellen Drink'),
			'edit_item'=>_('Edit Getränke'),
			'new_item'=>_('Erstellen Getränke'),
			'view_item'=>_('View Getränke'),
			'search_items'=>_('Search Getränke'),
			'not_found'=>_('Not Found'),
			'not_found_in_trash'=>_('Not Found in Trash'),
			'parent_item_colon'=>_('Fettkiller Getränkeliste '),
		);
	$args= array(
			'labels' => $labels,
			'description'=>'Fettkiller Getränkeliste',
			'public' => true,
			'capability_type'    => 'post',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 10,
			'has_archive' => true,
			'rewrite' => array('slug' => 'fatburner_drink'),
			'supports'=>array('title','editor','thumbnail','author','revisions','custom-fields'),
			'can_export'=>true,
	);
	 register_post_type( 'fatburner_drink',$args);
	 
	 
	 /*-------------- Tipps & Tracks ---------*/
	  $support_labels = array(
    'name' => _x( 'Tips Kategorien', 'taxonomy general name' ),
    'singular_name' => _x( 'Tips Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Tips Kategorien' ),
    'all_items' => __( 'Alle Tips Kategorien' ),
    'parent_item' => __( 'Parent Tips Kategorien' ),
    'parent_item_colon' => __( 'Parent Tips Kategorien:' ),
    'edit_item' => __( 'Edit Tips Kategorien' ), 
    'update_item' => __( 'Update Kategorien' ),
    'add_new_item' => __( 'Erstellen Kategorien' ),
    'new_item_name' => __( 'New Kategorien Name' ),
    'menu_name' => __( 'Tipps & Tricks Kategorien' ),
  ); 	

// Now register the taxonomy

  register_taxonomy('tipps_tricks_category',array('Tipps & Tricks'), array(
    'hierarchical' => true,
    'labels' => $support_labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tips topics' ),
  ));

	$labels=array(
			'name' => __( 'Tipps & Tricks' ),
			'singular_name' => __( 'Tipps-Tricks' ),
			'menu_name'=>_('Tipps & Tricks'),
			'name_admin_bar'=>_('Erstellen Tipp & Trick'),
			'all_items'=>_('Alle Tipps & Tricks'),
			'add_new'=>_('Erstellen'),
			'add_new_item'=>_('Erstellen Tipp-trick'),
			'edit_item'=>_('Edit Tipp-trick'),
			'new_item'=>_('Erstellen Tipp-trick'),
			'view_item'=>_('View Tipp-trick'),
			'search_items'=>_('Search Tipps & Tricks'),
			'not_found'=>_('Not Found'),
			'not_found_in_trash'=>_('Not Found in Trash'),
			'parent_item_colon'=>_('Tipps & Tracks'),
		);
	$args= array(
			'labels' => $labels,
			'description'=>'Tipps and tricks',
			'public' => true,
			'capability_type'    => 'post',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 11,
			'has_archive' => true,
			'rewrite' => array('slug' => 'tipps_tricks'),
			'supports'=>array('title','editor','thumbnail','author','revisions','custom-fields','page-attributes'),
			'taxonomies' => array('tipps_tricks_category'),
			'can_export'=>true,
	);
	 register_post_type( 'tipps_tricks',$args);
	 
}


add_filter( 'post_row_actions', 'remove_row_actions', 10, 1 );
function remove_row_actions( $actions )
{
    if( get_post_type() === 'tooltip' )
        unset( $actions['trash'] );
        unset( $actions['inline hide-if-no-js'] );
    return $actions;
}
/*
add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page() {

	add_menu_page( 'list-meta-boxes', 'List metabox', 'manage_options', 'lst-meta', 'lists_meta_boxes', '', '' );

}
function lists_meta_boxes()
{
	
}
function get_meta_boxes( $screen = null, $context = 'advanced' ) {
    global $wp_meta_boxes;

    if ( empty( $screen ) )
        $screen = get_current_screen();
    elseif ( is_string( $screen ) )
        $screen = convert_to_screen( $screen );

    $page = $screen->id;

    return $wp_meta_boxes[$page][$context];          
}*/

/*function custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;
    return array(
      'index.php', // Dashboard
      'admin.php?page=yith_vendor_commissions',
      'admin.php?page=wc-reports',
      'admin.php?page=yith_vendor_settings',// Last separator
      'profile.php'
    );
  }

 /* add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
  add_filter('menu_order', 'custom_menu_order');*/
