<?php 
/*
 * Template Name: Pdf Creation
 * 
 */

if(isset($_POST['submit'])){
	
		$fname=$_POST['fname'];
		$lname=$_POST['lname'];
		$message='Hello, '.$fname.' '.$lname;
		$headers  = "From: ".$from;
	
		
echo do_shortcode("[pdfcreate name='".$message."']");
}
get_header();


?>

<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">


<article >
	

	<header class="entry-header">
	
	</header><!-- .entry-header -->

	<div class="entry-content">
		
<h2>Welcome</h2>
<h3>Register</h3>
<form method="post" action=""> 
	<table>
	<tr>
		<td><label>First Name</label></td>
		<td><input type="text" name="fname"/></td>
		
	</tr>
	<tr>
		<td><label>Last Name</label></td>
	<td>
		<input type="text" name="lname"/></td>
		
	</tr>
	<tr>
	<td colspan='2'><input type="submit" name="submit"/></td>
	</tr>
	
	</table>
</form>
	</div><!-- .entry-content -->






<!-- Begin MailChimp Signup Form -->
<div id="mc_embed_signup" class="form mc4wp-form">
<form action="//lanetteam.us10.list-manage.com/subscribe/post?u=9dbd4ef5899238712edccd39a&amp;id=236f1cc04c" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" novalidate>
    

	<input type="email" value="" name="EMAIL" class="fir_inp inp tooltipstered" id="mce-EMAIL" placeholder="Your email address">
	<input type="submit" value="Kostenlos registrieren" name="subscribe" id="register_email">
	<i class="arrow_icon"></i>
	<span class="email_err"></span>
</form>
</div>

<!--End mc_embed_signup-->
<?php
global $wpdb;
$name='Gewürze & Kräuter';
$sql="select * from up_shopping_list_categories where name='".htmlspecialchars($name)."'";
//echo $sql;
$result=$wpdb->get_results($sql);
foreach($result as $data)
{
	echo $data->id;
	echo $data->name."</br>";
}
echo 'call get_kcal_of_order_meals("1662","1,2,3,4,6,7,8,9,10,85")';
$kcal_result = $wpdb->get_results('call get_kcal_of_order_meals("1662","1,2,3,4,6,7,8,9,10,85")', OBJECT_K);
echo "kcal result:<pre>";print_r($kcal_result);

?>


			


</article><!-- #post-## -->

	</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php 
geT_footer();
?>
