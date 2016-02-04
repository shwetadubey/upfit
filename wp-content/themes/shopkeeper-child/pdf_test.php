<?php 
/*
 * Template Name: PDF-Canvas Test
 * 
 */

	
?>


<!DOCTYPE html>
<html lang="en">
  <?php get_header(); ?>
 
  <body>
	
    <div id="content" style="background:#f0eeef;margin:0 auto;padding:50px 70px;height:100%;display:inline-block;outline:none;">
       
           <?php $html1=file_get_contents(home_url().'/pdf1/?order_id=1347'); 
           echo $html1;?>
           
      </div>
      <div id="canvas"></div>
    
      <script>
      jQuery(document).ready(function($){
		 html2canvas($('#content'), { 
			onrendered: function (canvas) {
			var canvasImg = canvas.toDataURL("image/jpg");
			$('#canvas').html('<img src="'+canvasImg+'" alt="">');
			}
			});
		 // jQuery('#content').hide();
		  });
		/*  function captureAndUpload($) {
						 
			
}*/
      </script>
      <?php get_footer();?>
  <script src="<?php echo get_template_directory_uri(); ?>-child/js/html2canvas.js"></script>
  </body>
  
</html>



