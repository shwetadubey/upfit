

	<div id="primary" class="content-area blog-single">
        
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
	
				<?php get_template_part( 'content','pdf' ); ?>
				
	
			
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
           
    </div><!-- #primary -->
