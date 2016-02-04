<?php global $yith_wcwl, $woocommerce; ?>

<header id="masthead" class="site-header" role="banner">

    <?php if ( (isset($shopkeeper_theme_options['header_width'])) && ($shopkeeper_theme_options['header_width'] == "custom") ) : ?>
    <div class="row">		
        <div class="large-12 columns">
    <?php endif; 
    // echo esc_html($header_max_width_style); ?>
            <div class="site-header-wrapper" style="max-width:940px;">
             
                <div class="wrapper_header_layout">
                        
                    <div class="header_col left_menu">
                           <?php
					
							if ( (isset($shopkeeper_theme_options['site_logo']['url'])) && (trim($shopkeeper_theme_options['site_logo']['url']) != "" ) ) {
								if (is_ssl()) {
									$site_logo = str_replace("http://", "https://", $shopkeeper_theme_options['site_logo']['url']);	
									if ($header_transparency_class == "transparent_header")	{
										if ( ($transparency_scheme == "transparency_light") && (isset($shopkeeper_theme_options['light_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['light_transparent_header_logo']['url']) != "") ) {
											$site_logo = str_replace("http://", "https://", $shopkeeper_theme_options['light_transparent_header_logo']['url']);	
										}
										if ( ($transparency_scheme == "transparency_dark") && (isset($shopkeeper_theme_options['dark_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['dark_transparent_header_logo']['url']) != "") ) {
											$site_logo = str_replace("http://", "https://", $shopkeeper_theme_options['dark_transparent_header_logo']['url']);	
										}
									}
								} else {
									$site_logo = $shopkeeper_theme_options['site_logo']['url'];
									if ($header_transparency_class == "transparent_header")	{
										if ( ($transparency_scheme == "transparency_light") && (isset($shopkeeper_theme_options['light_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['light_transparent_header_logo']['url']) != "") ) {
											$site_logo = $shopkeeper_theme_options['light_transparent_header_logo']['url'];
										}
										if ( ($transparency_scheme == "transparency_dark") && (isset($shopkeeper_theme_options['dark_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['dark_transparent_header_logo']['url']) != "") ) {
											$site_logo = $shopkeeper_theme_options['dark_transparent_header_logo']['url'];
										}
									}
								}
								
								if ( (isset($shopkeeper_theme_options['sticky_header_logo']['url'])) && (trim($shopkeeper_theme_options['sticky_header_logo']['url']) != "" ) ) {
									if (is_ssl()) {
										$sticky_header_logo = str_replace("http://", "https://", $shopkeeper_theme_options['sticky_header_logo']['url']);		
									} else {
										$sticky_header_logo = $shopkeeper_theme_options['sticky_header_logo']['url'];
									}
								}
								
								
							?>
            
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                    <img class="site-logo" src="<?php echo esc_url($site_logo); ?>" title="<?php bloginfo( 'description' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
                                    <?php if ( (isset($shopkeeper_theme_options['sticky_header_logo']['url'])) && (trim($shopkeeper_theme_options['sticky_header_logo']['url']) != "" ) ) { ?>
                                        <img class="sticky-logo" src="<?php echo esc_url($sticky_header_logo); ?>" title="<?php bloginfo( 'description' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
                                    <?php } ?>
                                </a>
                            
                            <?php } else { ?>
                            
                                <div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
                            
                            <?php } ?>
                            
                    </div>
                    
                    <div class="header_col branding">
                        <div class="site-branding">
                         <div class="punch_line">
							 
							 <p><?php echo get_bloginfo('description','display'); ?></p>
							</div>
                        
                        </div><!-- .site-branding -->
                    </div>
                    <!----- -------Trust Badge ----- ------>
                    <div class="header_col right_menu">
						 <div class="iosmarker" >
							 <?php
							// print_r($shopkeeper_theme_options['header_trust_badge2']);
						if ( (isset($shopkeeper_theme_options['header_trust_badge1']['url'])) && (trim($shopkeeper_theme_options['header_trust_badge1']['url']) != "" ) ) {
							if(isset($shopkeeper_theme_options['badge_url1'])){
								$badge_url=$shopkeeper_theme_options['badge_url1'];
								}
								else{
									$badge_url=home_url();
									}
							?>
							<a href=<?php echo $badge_url; ?> >
							<img src=" <?php echo $shopkeeper_theme_options['header_trust_badge1']['url'];?>" />
							</a>
							<?php
						}
						?>
						</div>
					<?php 
						if ( (isset($shopkeeper_theme_options['header_trust_badge2']['url'])) && (trim(	$shopkeeper_theme_options['header_trust_badge2']['url']) != "" ) ) { 
						if(isset($shopkeeper_theme_options['badge_url2'])){
								$badge_url2=$shopkeeper_theme_options['badge_url2'];
								}
								else{
									$badge_url2=home_url();
									}
							?>
						<div class="iosmarker marg-10" >
							<a href=<?php echo $badge_url2; ?> >
								<img src=" <?php echo $shopkeeper_theme_options['header_trust_badge2']['url'];?>" />
							</a>
						</div>
					<?php 
						} 
						if ( (isset($shopkeeper_theme_options['header_trust_badge3']['url'])) && (trim($shopkeeper_theme_options['header_trust_badge3']['url']) != "" ) ) { 
							if(isset($shopkeeper_theme_options['badge_url3'])){
								$badge_url3=$shopkeeper_theme_options['badge_url3'];
								}
								else{
									$badge_url3=home_url();
									}
						?>
						<div class="iosmarker" >
							<a href=<?php echo $badge_url3; ?> >
								<img src=" <?php echo $shopkeeper_theme_options['header_trust_badge3']['url'];?>" />
							</a>
						</div>
						<?php } ?>
					</div>
                        
                </div>           
                    
                <?php 
				$site_tools_padding_class = "";
				if ( (isset($shopkeeper_theme_options['main_header_off_canvas'])) && ($shopkeeper_theme_options['main_header_off_canvas'] == "1") ) {
					if ( (!isset($shopkeeper_theme_options['main_header_off_canvas_icon']['url'])) || ($shopkeeper_theme_options['main_header_off_canvas_icon']['url']) == "" ) {
                		$site_tools_padding_class = "offset";
					}
				}
				elseif ( (isset($shopkeeper_theme_options['main_header_search_bar'])) && ($shopkeeper_theme_options['main_header_search_bar'] == "1") ) {
                	if ( (!isset($shopkeeper_theme_options['main_header_search_bar_icon']['url'])) || ($shopkeeper_theme_options['main_header_search_bar_icon']['url']) == "" ) {
						$site_tools_padding_class = "offset";
					}
				}
                ?>
                
                <div class="site-tools <?php echo esc_html($site_tools_padding_class); ?>">
                    <ul>
                        
                        <?php if (class_exists('YITH_WCWL')) : ?>
                        <?php if ( (isset($shopkeeper_theme_options['main_header_wishlist'])) && ($shopkeeper_theme_options['main_header_wishlist'] == "1") ) : ?>
                        <li class="wishlist-button">
                            <a href="<?php echo esc_url($yith_wcwl->get_wishlist_url()); ?>" class="tools_button">
                                <span class="tools_button_icon">
                                    <?php if ( (isset($shopkeeper_theme_options['main_header_wishlist_icon']['url'])) && ($shopkeeper_theme_options['main_header_wishlist_icon']['url'] != "") ) : ?>
                                    <img src="<?php echo esc_url($shopkeeper_theme_options['main_header_wishlist_icon']['url']); ?>">
                                    <?php else : ?>
                                    <i class="fa fa-heart-o"></i>
									<?php endif; ?>
                                </span>
                                <span class="wishlist_items_number"><?php echo yith_wcwl_count_products(); ?></span>
                            </a>
                        </li>							
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if (class_exists('WooCommerce')) : ?>
                        <?php if ( (isset($shopkeeper_theme_options['main_header_shopping_bag'])) && ($shopkeeper_theme_options['main_header_shopping_bag'] == "1") ) : ?>
                        <?php if ( (isset($shopkeeper_theme_options['catalog_mode'])) && ($shopkeeper_theme_options['catalog_mode'] == 1) ) : ?>
                        <?php else : ?>
                        <li class="shopping-bag-button">
                            <a href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" class="tools_button">
                                <span class="tools_button_icon">
                                	<?php if ( (isset($shopkeeper_theme_options['main_header_shopping_bag_icon']['url'])) && ($shopkeeper_theme_options['main_header_shopping_bag_icon']['url'] != "") ) : ?>
                                    <img src="<?php echo esc_url($shopkeeper_theme_options['main_header_shopping_bag_icon']['url']); ?>">
                                    <?php else : ?>
                                    <i class="fa fa-shopping-cart"></i>
									<?php endif; ?>
                                </span>
                                <span class="shopping_bag_items_number"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if ( (isset($shopkeeper_theme_options['main_header_search_bar'])) && ($shopkeeper_theme_options['main_header_search_bar'] == "1") ) : ?>
                        <li class="search-button">
                            <a class="tools_button">
                                <span class="tools_button_icon">
                                	<?php if ( (isset($shopkeeper_theme_options['main_header_search_bar_icon']['url'])) && ($shopkeeper_theme_options['main_header_search_bar_icon']['url'] != "") ) : ?>
                                    <img src="<?php echo esc_url($shopkeeper_theme_options['main_header_search_bar_icon']['url']); ?>">
                                    <?php else : ?>
                                    <i class="fa fa-search"></i>
									<?php endif; ?>
                                </span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="offcanvas-menu-button <?php if ( (isset($shopkeeper_theme_options['main_header_off_canvas'])) && ($shopkeeper_theme_options['main_header_off_canvas'] == "0") ) : ?>hide-for-large-up<?php endif; ?>">
                            <a class="tools_button">
								<span class="menu-button-text"><?php _e('menu1', 'shopkeeper' ); ?></span>
                                <span class="tools_button_icon">
                                	<?php if ( (isset($shopkeeper_theme_options['main_header_off_canvas_icon']['url'])) && ($shopkeeper_theme_options['main_header_off_canvas_icon']['url'] != "") ) : ?>
                                    <img src="<?php echo esc_url($shopkeeper_theme_options['main_header_off_canvas_icon']['url']); ?>">
                                    <?php else : ?>
                                    <i class="fa fa-bars"></i>
									<?php endif; ?>
                                </span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
                 
                   <div class="wrapper_header_mobile_layout">
                        
                    <div class="header_col left_menu">
                           <?php
					
							if ( (isset($shopkeeper_theme_options['site_logo']['url'])) && (trim($shopkeeper_theme_options['site_logo']['url']) != "" ) ) {
								if (is_ssl()) {
									$site_logo = str_replace("http://", "https://", $shopkeeper_theme_options['site_logo']['url']);	
									if ($header_transparency_class == "transparent_header")	{
										if ( ($transparency_scheme == "transparency_light") && (isset($shopkeeper_theme_options['light_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['light_transparent_header_logo']['url']) != "") ) {
											$site_logo = str_replace("http://", "https://", $shopkeeper_theme_options['light_transparent_header_logo']['url']);	
										}
										if ( ($transparency_scheme == "transparency_dark") && (isset($shopkeeper_theme_options['dark_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['dark_transparent_header_logo']['url']) != "") ) {
											$site_logo = str_replace("http://", "https://", $shopkeeper_theme_options['dark_transparent_header_logo']['url']);	
										}
									}
								} else {
									$site_logo = $shopkeeper_theme_options['site_logo']['url'];
									if ($header_transparency_class == "transparent_header")	{
										if ( ($transparency_scheme == "transparency_light") && (isset($shopkeeper_theme_options['light_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['light_transparent_header_logo']['url']) != "") ) {
											$site_logo = $shopkeeper_theme_options['light_transparent_header_logo']['url'];
										}
										if ( ($transparency_scheme == "transparency_dark") && (isset($shopkeeper_theme_options['dark_transparent_header_logo']['url'])) && (trim($shopkeeper_theme_options['dark_transparent_header_logo']['url']) != "") ) {
											$site_logo = $shopkeeper_theme_options['dark_transparent_header_logo']['url'];
										}
									}
								}
								
								if ( (isset($shopkeeper_theme_options['sticky_header_logo']['url'])) && (trim($shopkeeper_theme_options['sticky_header_logo']['url']) != "" ) ) {
									if (is_ssl()) {
										$sticky_header_logo = str_replace("http://", "https://", $shopkeeper_theme_options['sticky_header_logo']['url']);		
									} else {
										$sticky_header_logo = $shopkeeper_theme_options['sticky_header_logo']['url'];
									}
								}
								
								
							?>
            
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                    <img class="site-logo" src="<?php echo esc_url($site_logo); ?>" title="<?php bloginfo( 'description' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
                                    <?php if ( (isset($shopkeeper_theme_options['sticky_header_logo']['url'])) && (trim($shopkeeper_theme_options['sticky_header_logo']['url']) != "" ) ) { ?>
                                        <img class="sticky-logo" src="<?php echo esc_url($sticky_header_logo); ?>" title="<?php bloginfo( 'description' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
                                    <?php } ?>
                                </a>
                            
                            <?php } else { ?>
                            
                                <div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
                            
                            <?php } ?>
                            
                    </div>
                      <div class="header_col right_menu">
						 <div class="iosmarker">
							 <?php
						if ((isset($shopkeeper_theme_options['header_trust_badge']['url'])) && (trim($shopkeeper_theme_options['header_trust_badge']['url']) != "" ) ) {
								//print_r($shopkeeper_theme_options);
								if(isset($shopkeeper_theme_options['badge_url']))
								{
									$front_url=$shopkeeper_theme_options['badge_url'];
								//	echo $front_url;
								}
							?>
								
							<a href="<?php echo $front_url;?>">
								<img src=" <?php echo $shopkeeper_theme_options['header_trust_badge']['url'];?>" />
							</a>
							<?php
						}
						?>
						</div>
					</div>
                   
                    <div class="header_col branding">
                        <div class="site-branding">
                         <div class="punch_line">
							 
							 <p><?php echo get_bloginfo('description','display'); ?></p>
							</div>
                        
                        </div><!-- .site-branding -->
                    </div>
                    <!----- -------Trust Badge ----- ------>
                       
                </div>           
                    
                           
            </div><!--.site-header-wrapper-->
        
    <?php if ( (isset($shopkeeper_theme_options['header_width'])) && ($shopkeeper_theme_options['header_width'] == "custom") ) : ?>
        </div><!-- .columns -->
    </div><!-- .row -->
    <?php endif; ?>

</header><!-- #masthead -->



<script>

	jQuery(document).ready(function($) {

    "use strict";
	
		$(window).scroll(function() {
			
			if ($(window).scrollTop() > 0) {
				
				<?php if ( (isset($shopkeeper_theme_options['sticky_header'])) && (trim($shopkeeper_theme_options['sticky_header']) == "1" ) ) { ?>
					$('#site-top-bar').addClass("hidden");
					$('.site-header').addClass("sticky");
					<?php if ( (isset($shopkeeper_theme_options['sticky_header_logo']['url'])) && (trim($shopkeeper_theme_options['sticky_header_logo']['url']) != "" ) ) { ?>
						$('.site-logo').attr('src', '<?php echo esc_url($sticky_header_logo); ?>');
					<?php } ?>
				<?php } ?>
				
			} else {
				
				<?php if ( (isset($shopkeeper_theme_options['sticky_header'])) && (trim($shopkeeper_theme_options['sticky_header']) == "1" ) ) { ?>
					$('#site-top-bar').removeClass("hidden");
					$('.site-header').removeClass("sticky");
					<?php if ( (isset($shopkeeper_theme_options['sticky_header_logo']['url'])) && (trim($shopkeeper_theme_options['sticky_header_logo']['url']) != "" ) ) { ?>
						$('.site-logo').attr('src', '<?php echo esc_url($site_logo); ?>');
					<?php } ?>
				<?php } ?>
				
			}
			
		});
	
	});
	
</script>


