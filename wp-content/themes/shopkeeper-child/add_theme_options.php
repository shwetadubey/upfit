<?php 
function header_trust_badge_customizer( $wp_customize ) {
    // Fun code will go here
 $wp_customize->add_section( 'header_trust_badge_section' , array(
    'title'       => __( 'Trust Badge', 'shopkeeper' ),
    'priority'    => 20,
    'description' => 'Upload a Trust Badge to replace the default Trust Badge',
) );
$wp_customize->add_setting( 'header_trust_badge_icon',array(
	'type'=>'option',
) );
$wp_customize->add_control( new  WP_Customize_Image_Control( $wp_customize, 'header_trust_badge_icon', array(
    'label'    => __( 'Trust Badge', 'shopkeeper' ),
    'section'  => 'header_trust_badge_section',
    'settings' => 'header_trust_badge_icon',
   
    
) ) );
$wp_customize->add_setting( 'header_trust_badge_icon_width',array(
				
) );
$wp_customize->add_control( 'header_trust_badge_icon_width', array(
    'label'    => __( 'Width', 'shopkeeper' ),
    'section'  => 'header_trust_badge_section',
    'settings' => 'header_trust_badge_icon_width',
     'type' => 'range',
     'description'=>'Adjust width of badge',
     'input_attrs' => array(
        'min' => 50,
        'max' => 100,
        'step' => 1,
        'class' => 'example-class',
        'style' => 'color: #0a0',
        'title'=>'value'
    ),   
)  );
$wp_customize->add_setting( 'header_trust_badge_icon_height' );
$wp_customize->add_control( 'header_trust_badge_icon_height', array(
    'label'    => __( 'Height', 'shopkeeper' ),
    'section'  => 'header_trust_badge_section',
    'settings' => 'header_trust_badge_icon_height',
     'type' => 'range', 
     'description'=>'Adjust height of badge',
     'input_attrs' => array(
        'min' => 50,
        'max' => 100,
        'step' => 1,
        'class' => 'example-class',
        'style' => 'color: #0a0',
    ),  
)  
);

/*$wp_customize->add_setting( 'header_trust_badge_icon_badge_url' );
$wp_customize->add_control( 'header_trust_badge_icon_badge_url', array(
    'label'    => __( 'badge_url', 'shopkeeper' ),
    'section'  => 'header_trust_badge_section',
    'settings' => 'header_trust_badge_icon_badge_url',
     'type' => 'text', 
     'description'=>'set frontend of badge',
     
)  
);*/


add_theme_support( 'header_trust_badge_icon', $header_args );
}
add_action( 'customize_register', 'header_trust_badge_customizer' );


add_action( 'after_setup_theme', 'change_heading_and_subheading' );
function change_heading_and_subheading(){
 $opt_name = "shopkeeper_theme_options";

  Redux::setSection($opt_name,array(
	'icon'   => 'fa fa-arrow-circle-up',
	'title'=>'Trust Badges',
	'fields' => array(
        
            array (
                'title' => __('Trust Badge1', 'shopkeeper'),
                'subtitle' => __('<em>Upload your trust badge to right side of header.</em>', 'shopkeeper'),
                'id' => 'header_trust_badge1',
                'type' => 'media',
                'default' => array (
					'url' => get_template_directory_uri() . '-child/images/trust_badge_1.png',
                ),
            ),
           array (
                'title' => __('Trust Badge2', 'shopkeeper'),
                'subtitle' => __('<em>Upload your trust badge to right side of header.</em>', 'shopkeeper'),
                'id' => 'header_trust_badge2',
                'type' => 'media',
                'default' => array (
					'url' => get_template_directory_uri() . '-child/images/trust_badge_2.png',
                ),
            ),          
            array (
                'title' => __('Trust Badge3', 'shopkeeper'),
                'subtitle' => __('<em>Upload your trust badge to right side of header.</em>', 'shopkeeper'),
                'id' => 'header_trust_badge3',
                'type' => 'media',
                'default' => array (
					'url' => get_template_directory_uri() . '-child/images/trust_badge_3.png',
                ),
            ),
            
            array(
                'title' => __('Trust Badge Container Min Width', 'shopkeeper'),
                'subtitle' => __('<em>Drag the slider to set the trust badge container min width.</em>', 'shopkeeper'),
                'id' => 'badge_min_height',
                'type' => 'slider',
                "default" => 300,
                "min" => 0,
                "step" => 1,
                "max" => 600,
                'display_value' => 'text',
               
            ),
            
            array(
                'title' => __('Trust Badge Height', 'shopkeeper'),
                'subtitle' => __('<em>Drag the slider to set the trust badge height <br/>(ignored if there\'s no uploaded trust badge).</em>', 'shopkeeper'),
                'id' => 'badge_height',
                'type' => 'slider',
                "default" => 46,
                "min" => 0,
                "step" => 1,
                "max" => 300,
                'display_value' => 'text',
            ),
            
            array(
                'title' => __('Trust Badge1 Url', 'shopkeeper'),
                'subtitle' => __('<em>Set trust badge frontend url</em>', 'shopkeeper'),
                'id' => 'badge_url1',
                'type' => 'text',
                'display_value' => 'text',
            ),
            array(
                'title' => __('Trust Badge2 Url', 'shopkeeper'),
                'subtitle' => __('<em>Set trust badge frontend url</em>', 'shopkeeper'),
                'id' => 'badge_url2',
                'type' => 'text',
                'display_value' => 'text',
            ),
            array(
                'title' => __('Trust Badge3 Url', 'shopkeeper'),
                'subtitle' => __('<em>Set trust badge frontend url</em>', 'shopkeeper'),
                'id' => 'badge_url3',
                'type' => 'text',
                'display_value' => 'text',
            ),
            
        )  
	));
 
}
