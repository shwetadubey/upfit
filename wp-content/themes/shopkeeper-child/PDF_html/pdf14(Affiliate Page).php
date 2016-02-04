<?php 
/*
 * Template Name: PDF14 (Affiliate Page)
 */
?>
<!DOCTYPE html>
<html lang="en">
  	
 
  <body>
	    <div style="background:#fff; padding:15px;">
			<div style="background: #f0eeef; padding: 30px 70px;outline: none; height: 187mm;">
			<?php
			//	$page= get_page_by_path( 'affiliate-page', $output, 'pdf' ); 
			 $page=get_post(56);
			  echo ($page->post_content);
			?>
			</div>
		</div>
  </body>
</html>
