<?php
add_action('admin_init','change_prefix');
function change_prefix()
{
	//WC_Meta_Box_Order_Data::$billing_fields['title']['options'] = array(1=>'Mr',2=>'Miss');
}

add_action( 'add_meta_boxes', 'add_page_margin' );
function add_page_margin()
{
    add_meta_box( 'page_margin_id', 'Page Margin', 'page_margin', 'pdf', 'normal', 'high' );
}


function page_margin()
{
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
   // print_r($values);
    $text = isset($values['page_margin']) ? $values['page_margin'] : '';
  
  
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'page_margin_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <label for="page_margin">Page Margin</label>
       
        <select  name="page_margin" id="page_margin">
			<option value="1" <?php if($text[0]==1) echo 'selected'; else echo ''; ?>>Yes</option>
			<option value="0" <?php if($text[0]==0) echo 'selected'; else echo ''; ?>>No</option>
        </select>
    </p>
     <?php        
} 
function save_page_margin( $post_id )
{	
	//print_r($post_id);
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'page_margin_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['page_margin'] ) )
        update_post_meta( $post_id, 'page_margin', wp_kses( $_POST['page_margin'], $allowed ) );
         
}
	add_action( 'save_post', 'save_page_margin' ); 

//------------------PDF Creation--------------------------//
add_shortcode('pdfcreate','pdf_file_creation');
function curl($url) {
	//echo $url;exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    curl_close($ch);
	
    return $data;
}

add_action( 'wp_ajax_pdf_file_creation', 'pdf_file_creation' );
add_action( 'wp_ajax_nopriv_pdf_file_creation', 'pdf_file_creation' );
add_shortcode('create_pdf','pdf_file_creation');
function pdf_file_creation($atts){
	
	require_once 'mpdf/mpdf.php';
	global $wpdb, $shopkeeper_theme_options;
	try{
	 $site_logo = $shopkeeper_theme_options['light_transparent_header_logo']['url'];
	//$order_id=$_REQUEST['order_id'];
	//echo $order_id;

		$order_id=$atts['order_id'];
		$order_item_id=$atts['order_item_id'];	
		
		$billing_first_name = get_post_meta($order_id,'_billing_first_name',true);
		$billing_last_name = get_post_meta($order_id,'_billing_last_name',true);
		$billing_email = get_post_meta($order_id,'_billing_email',true);

		$order=new WC_Order($order_id);
		$items = $order->get_items();
		$item_data=$items [array_keys($items)[0]];
		
		$plan_id=$item_data['item_meta']['_product_id'][0];
		$site_id=get_current_blog_id();
		$plan_details=get_post($plan_id);
		$plan_period=get_post_meta($plan_id,'plan_period',true);
		$path = wp_upload_dir();
		$pdfname='';
		
		$order_details=$wpdb->get_results('select id,meals_per_day,no_of_weeks,regenerate,current_weight from up_user_nutrition_plans where order_id='.$order_id.' AND site_id='.$site_id);
		
		$weight_range=$wpdb->get_results('select expected_weight from up_plan_logs where user_nutrition_plan_id=(select id from  up_user_nutrition_plans where order_id='.$order_id.' AND site_id='.$site_id.')',ARRAY_N);
		
		$no_of_weeks=$order_details[0]->no_of_weeks;

		if(isset($order_details) && !empty($order_details)){
		  
			$plan_name=$items [array_keys($items)[0]]['name'];
			$username="admin";
			$password="upfit@123";
			$opts = array(
				  'http'=>array(
					'method'=>"GET",
					'header'=>"Accept-language: en\r\n" .
							  "Cookie: foo=bar\r\n".
							  "Authorization: Basic " . base64_encode("$username:$password")
				  )
				);
		
			$context = stream_context_create($opts);
			
			$html1=file_get_contents(home_url().'/pdf1/?order_id='.$order_id,false,$context);
		 
			$html2=file_get_contents(home_url().'/pdf2/?order_id='.$order_id,false,$context);
			  
			$html3=file_get_contents(home_url().'/pdf3/?order_id='.$order_id,false,$context);
			
			  //echo $html1;
			for($i=1;$i<=$no_of_weeks;$i++){
			
				$weight_range_new[$i]=$weight_range[$i-1][0];
				
				// echo $weight_range_new[$i].'<br/>';
				// echo "in for loop".$i;
				$html4[$i]=file_get_contents(home_url().'/pdf4/?order_id='.$order_id.'&week_no='.$i,false,$context);
				  
				$html5[$i]=file_get_contents(home_url().'/pdf5/?order_id='.$order_id.'&week_no='.$i,false,$context);
				  
				$html6[$i]=file_get_contents(home_url().'/pdf6/?order_id='.$order_id.'&week_no='.$i,false,$context);
			  
				$html7[$i]=file_get_contents(home_url().'/pdf7/?order_id='.$order_id.'&week_no='.$i,false,$context);
				 
				$html8[$i]=file_get_contents(home_url().'/pdf8/?order_id='.$order_id.'&week_no='.$i,false,$context);
			  
				$html9[$i]=file_get_contents(home_url().'/pdf9/?order_id='.$order_id.'&week_no='.$i,false,$context);
					  
				$html10[$i]=file_get_contents(home_url().'/pdf10/?order_id='.$order_id.'&week_no='.$i,false,$context);
		
				$html11[$i]=file_get_contents(home_url().'/shopping/?order_id='.$order_id.'&week_no='.$i,false,$context);
			 }
			
			
			$html12=file_get_contents(home_url().'/pdf12/?order_id='.$order_id,false,$context);
			  
			$html13=file_get_contents(home_url().'/pdf13/?order_id='.$order_id,false,$context);
			 
			//$html14=file_get_contents(home_url().'/pdf14/?order_id='.$order_id);
		//	print_r($weight_range_new);
			
		// mode, format, default_font_size, default_font, margin_left, margin_right,
				// margin_top, margin_bottom, margin_header, margin_footer, orientation
			//echo "after for loop";
			$mpdf = new mPDF('UTF-8','A4-L');
			$mpdf->defaultPagebreakType='clonebycss';
			$mpdf->SetCompression(true);
			$mpdf->useLang = true;
			//$mpdf->use_kwt = true;  
			$css=file_get_contents(get_template_directory_uri().'-child/PDF_html/pdf_style.css',false,$context);
		
			//--------------------------------------------------, L , R , T , B , 
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,0,0,0, 'L');
			$mpdf->WriteHTML($css,1);
			
			$mpdf->WriteHTML(htmlspecialchars_decode($html1),2);
			//file_put_contents($uppath1.'status.json',json_encode(array('current'=>'58','total'=>'100')));
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,0,0,0, 'L');
			$mpdf->WriteHTML(htmlspecialchars_decode($html2));

			$header3=__('<div style="padding:0px 0px 0px 0px;height:12mm;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
								 <div style="width:20%; float:left; color:#162c5d;text-align:left; line-height:10px;">
									  <p style="float:left;">Tipps & Tricks</p>
								</div>
								<div style="float:left;width:60%;text-align:center; vertical-align:middle;">
									<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
								<div style="float:right;width:19%;padding-right:2px; color:#162c5d;text-align:right;line-height:10px; ">
									<p>Seite {PAGENO}/{nbpg}</p>
								</div>
							</div>     
						  </div>', 'shopkeeper' );
						  
			$mpdf->SetHTMLHeader(htmlspecialchars_decode($header3));
			
		//	$mpdf->SetHTMLFooter(htmlspecialchars_decode($header3));
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
			$mpdf->WriteHTML(htmlspecialchars_decode($html3));
/*<div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div> */
			for($i=1;$i<=$no_of_weeks;$i++){
				/*if($i==1){ 
					$min_w=str_replace(".",",",0.1);
				}
				else{
					//$min_w=	str_replace(".",",",(float)$order_details[0]->current_weight-(float)$weight_range_new[$i]);
					$min_w=$max_w;
				}*/
		
				$wr= (float)$order_details[0]->current_weight-(float)$weight_range_new[$i];
				$min_w=($wr*0.75);
				$max_w=($wr*1.75);
				$max_w=	number_format($max_w,"1",",","");
				$min_w=	number_format($min_w,"1",",","");

				$header4[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:43%; float:left; color:#162c5d;text-align:left;padding-bottom:1px; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Montag</p>
							  </div>
							 
							  <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'">
							</div>
							  <div style="float:right;width:44%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header4[$i]));
			
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html4[$i]));

				$header5[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:43%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Dienstag</p>
							  </div>
							   
							  <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
							  <div style="float:right;width:44%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );

				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header5[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html5[$i]));

				$header6[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:43%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Mittwoch</p>
							  </div>
							 
							  <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
							  <div style="float:right;width:44%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header6[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html6[$i]));

				$header7[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:43%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Donnerstag</p>
							  </div>
							 
							  <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
							  <div style="float:right;width:44%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header7[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html7[$i]));
			
				$header8[$i]=__( '<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:43%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Freitag</p>
							  </div>
							 
							   <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
							  <div style="float:right;width:44%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );

				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header8[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html8[$i]));
				
				$header9[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:43%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Samstag</p>
							  </div>
							  
							   <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
							  <div style="float:right;width:44%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header9[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html9[$i]));
				
				$header10[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:43%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Sonntag</p>
							  </div>
							 
							   <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
							  <div style="float:right;width:44%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header10[$i]));
				
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html10[$i]));

				$header11[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
								<div style="padding:1px 38.4px 0px 37px;">
									  <div style="width:20%; float:left; color:#162c5d;text-align:left; line-height:10px;">
										  <p style="float:left;">Einkaufsliste</p>
										 
										</div>
									 <div style="float:left;width:60%;text-align:center; vertical-align:middle;">
										<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
									<div style="float:right;width:19%;padding-right:2px; color:#162c5d;text-align:right;line-height:10px; ">
										<p>Seite {PAGENO}/{nbpg}</p>
									</div>
								</div>     
							  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header11[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html11[$i]));
		}
		
			$header12=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
								  <div style="width:20%; float:left; color:#162c5d;text-align:left; line-height:10px;">
									  <p style="float:left;">Fatburner Getrankeliste</p>
									 </div>
								  <div style="float:left;width:60%;text-align:center; vertical-align:middle;">
									<img style="margin-top:8px;" width="" height="28"  src="'.$site_logo.'"></div>
								<div style="float:right;width:19%;padding-right:2px; color:#162c5d;text-align:right;line-height:10px; ">
									<p>Seite {PAGENO}/{nbpg}</p>
								</div>
							</div>     
						  </div>', 'shopkeeper' );
			$mpdf->SetHTMLHeader(htmlspecialchars_decode($header12));
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
			$mpdf->WriteHTML(htmlspecialchars_decode($html12));

			$mpdf->SetHTMLHeader('');
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,0,0,0,0);
			$mpdf->WriteHTML(htmlspecialchars_decode($html13));
			
		/*	$mpdf->SetHTMLHeader('');
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,0,0,0,0);
			$mpdf->WriteHTML(htmlspecialchars_decode($html14));*/
			
			$path = wp_upload_dir(); 
			//$path['basedir']
			if(!file_exists($path['path'] . '/pdf')){mkdir($path['path'] . '/pdf/',0777, true);}
			/*for($i=71;$i<100;$i++){
				file_put_contents($uppath1.'status.json',json_encode(array('current'=>$i,'total'=>'100')));
				usleep(300000);
			}*/
		$pdfpath="";
		if($order_details[0]->regenerate == 1){
			//echo "if";
			$plan_name = str_replace(' ', "_", $plan_name);
			$pdfpath_db=$path['url'] . '/'.'pdf/'.$plan_name.'_Ernährungsplan_'.$billing_first_name.'_'.$billing_last_name.'_update_'.$order_id.'.pdf';			
			$pdf_name=$plan_name."_Ernährungsplan_".$billing_first_name."_".$billing_last_name."_update";
			$pdfpath=$path['path'] . '/'.'pdf/'.$plan_name.'_Ernährungsplan_'.$billing_first_name.'_'.$billing_last_name.'_update_'.$order_id.'.pdf';
			$sql="update up_user_nutrition_plans set regenerate=0 where order_id=".$order_id;
			$wpdb->query($sql);
			$q=$wpdb->query('UPDATE  up_user_nutrition_plans SET `re_pdf_name`="'.$pdf_name.'",`re_pdf_path`="'.$pdfpath_db.'" WHERE `order_id` ='.$order_id.' AND site_id='.$site_id);
		}
		else
		{// Ernährungsplan
			$plan_name = str_replace(' ', "_", $plan_name);
			$pdfpath_db=$path['url'].'/'.'pdf/'.$plan_name.'_Ernährungsplan_'.$billing_first_name.'_'.$billing_last_name.'_'.$order_id.'.pdf';			
			$pdf_name=$plan_name."_Ernährungsplan_".$billing_first_name."_".$billing_last_name;
			$pdfpath=$path['path'].'/'.'pdf/'.$plan_name.'_Ernährungsplan_'.$billing_first_name.'_'.$billing_last_name.'_'.$order_id.'.pdf';
			$q=$wpdb->query('UPDATE  up_user_nutrition_plans SET `pdf_name`="'.$pdf_name.'",`pdf_path`="'.$pdfpath_db.'" WHERE `order_id` ='.$order_id.' AND site_id='.$site_id);
		}
		//echo $pdfpath;
		//exit;
		
		//$pdfdoc = $mpdf->Output();
	//	exit;
		$mpdf->Output($pdfpath, "F");
		
		if($order_item_id != ''){
			$pdf_processing_description='Nutrition Plan(PDF) is generated';
			$sql1="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value = 3 WHERE order_item_id=".$order_item_id." and meta_key = 'pdf_processing_status'";
			$wpdb->query($sql1);
			$sql2="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value ='".$pdf_processing_description."' WHERE order_item_id=".$order_item_id." and meta_key = 'pdf_processing_description'";
				$wpdb->query($sql2);
		}
		$blog_id = get_current_blog_id();
		$date1 = date('Y-m-d H:i:s');
		$dataq = $wpdb->get_results("select meta_value from  ".$wpdb->prefix."woocommerce_order_itemmeta where order_item_id='".$order_item_id."' and meta_key='pdf_processing_status'");
		$dataq= json_decode(json_encode($dataq),true); 
		$cron_sql="insert into up_cron_status (order_id,description,pdf_processing_status,description_done_datetime,site_id) values ('".$order_id."','pdf has been successfully generated.','3','".$date1."','".$blog_id."')";
		//echo "3rd".$cron_sql."</br>";
		//$cron=$wpdb->query($cron_sql);
				
			if($dataq[0]['meta_value']==3 && (get_post_meta($order->id, '_payment_method', true) != 'bacs' || get_post_meta($order->id, '_payment_method', true) == ''))
			{
		//		echo 'here';
				//wp_mail('lanetteam.milan@gmail.com','test-cron-pdf','mail sent successfully from pdf_function');
				$order = new WC_Order( $order_id );
				$status=$order->get_status();
				if($order->has_status('processing')){
					//echo 'in processing';
					$order->update_status( 'completed' );
				}
				/*$pdf_processing_description='Mail is sent to user successfully in pdf_function';
				$wpdb->query('update '.$wpdb->prefix.'woocommerce_order_itemmeta set meta_value=4 where meta_key="pdf_processing_status" and order_item_id="'.$order_item_id.'"');
				$sql2="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value ='".$pdf_processing_description."' WHERE order_item_id=".$order_item_id." and meta_key = 'pdf_processing_description'";
				$wpdb->query($sql2);*/
				$cron_sql="insert into up_cron_status (order_id,description,pdf_processing_status,description_done_datetime,site_id) values ('".$order_id."','order is completed and mail is sent','4','".$date1."','".$blog_id."')";
				//echo "4th".$cron_sql."</br>";
				//$cron=$wpdb->query($cron_sql);
		
				/*$ml = new WC_Email_Customer_Completed_Order();
				$ml->trigger($order_id);*/
			}
		
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
	//exit;
//wp_die();
}

function custom_woocommerce_auto_complete_order( $order_id ) {
    global $woocommerce;
     if ( !$order_id )
        return;
    $order = new WC_Order( $order_id );
    $order->update_status( 'completed' );
}
//------------------Single PDF view----------------//
add_shortcode('pdfview','single_pdf_view');
function single_pdf_view($atts){
	
	require_once 'mpdf/mpdf.php';
		
	$post_id=$atts['post_id'];
$css=file_get_contents(get_stylesheet_directory().'/PDF_html/pdf_style.css');
	
// mode, format, default_font_size, default_font, margin_left, margin_right,
        // margin_top, margin_bottom, margin_header, margin_footer, orientation
  $post=get_post($post_id);
//	 $html1=file_get_contents(get_template_directory_uri().'/PDF_html/pdf1.html');
$mpdf = new mPDF('UTF-8','A4-L');
if(get_post_meta($post_id,'page_margin',true)==1){
	$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
}
else{
	$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,0,0,0, 'L');
}
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($post->post_content,2);
 
$mpdf->Output();  
    //	$pdfdoc = $mpdf->Output();
//$mpdf->Output("pdf/test.pdf", "F");

}

// add the action
//add_action( 'woocommerce_email_customer_details', 'action_woocommerce_email_customer_details', 10, 3 );

 function change_cancel_order_url_try( $redirect = '' ) {

    // Get cancel endpoint
    
    $cancel_endpoint = WC_Abstract_Order::get_cancel_endpoint();

    return apply_filters( 'woocommerce_get_cancel_order_url_raw', add_query_arg( array(
      'cancel_order' => 'true',
      'order'        => $this->order_key,
      'order_id'     => $this->id,
      'redirect'     => $redirect,
      '_wpnonce'     => wp_create_nonce( 'woocommerce-cancel_order' )
    ), $cancel_endpoint ) );
  }
  /*function get_cancel_endpoint() {

		$cancel_endpoint = wc_get_page_permalink( 'checkout' );
		if ( ! $cancel_endpoint ) {
			$cancel_endpoint = home_url();
		}

		if ( false === strpos( $cancel_endpoint, '?' ) ) {
			$cancel_endpoint = trailingslashit( $cancel_endpoint );
		}

		return $cancel_endpoint;
	}*/
	

add_filter('woocommerce_get_order_item_totals','get_order_item_totals1',0,1);	

function get_order_item_totals1( $tax_display = '' ) {
	if(is_wc_endpoint_url( 'order-received' ))
	{
		
		$order_id=wc_get_order_id_by_order_key( $_GET['key']);
		$order=new WC_Order($order_id);
		/*echo '<pre>';
		print_r($order);
		echo '</pre>';*/
		$coupons=$order->get_used_coupons();
		$coupon_code=$coupons[0];
		$coupon_data=new WC_Coupon($coupon_code);
		$discount = get_post_meta($coupon_data->id,'coupon_amount',true);
		$type = get_post_meta($coupon_data->id,'discount_type',true);
		
		if($type=='percent'){
			$coupon_label=$discount.'&#37; Rabatt';
		}
		else{
			$coupon_label='Rabatt';
		}
		if ( ! $tax_display ) {
			$tax_display = $order->tax_display_cart;
		}
		
		
		if ( 'itemized' == get_option( 'woocommerce_tax_total_display' ) ) {
			
			
			foreach ( $order->get_tax_totals() as $code => $tax ) {
				$tax->rate = WC_Tax::get_rate_percent( $tax->rate_id );
				
				if ( ! isset( $tax_array[ 'tax_rate'] ) )
					$tax_array[ 'tax_rate' ] = array( 'tax' => $tax, 'amount' => $tax->amount, 'contains' => array( $tax ) );
				else {
					array_push( $tax_array[ 'tax_rate' ][ 'contains' ], $tax );
					$tax_array[ 'tax_rate' ][ 'amount' ] += $tax->amount;
				}
			}
			if(isset($tax_array['tax_rate']['tax']->rate))
			$tax_label='<span class="include_tax">(inkl.&nbsp;'.$tax_array['tax_rate']['tax']->rate.'&nbsp;'.$tax_array['tax_rate']['tax']->label.')</span>';
		}
			
		$total_rows = array();
		
		$shippingcost='0 '.get_woocommerce_currency_symbol();
		
		if ( $order->get_total_discount() > 0 ) {
			$total_rows['discount'] = array(
				'label' => __( $coupon_label, 'woocommerce' ),
				'value'	=> '-' . $order->get_discount_to_display( $tax_display )
			);
		}

		
			$total_rows['shipping'] = array(
				'label' => __( 'Versandkosten', 'woocommerce' ),
				'value'	=>$shippingcost
			);
		

		$total_rows['order_total'] = array(
			'label' => __( 'Gesamtsumme '.$tax_label, 'woocommerce' ),
			'value'	=> $order->get_formatted_order_total( $tax_display )
		);

		return $total_rows;
	}
}
function skyverge_show_coupon_js() {
?>
<script>
		jQuery( "body" ).bind( "updated_checkout", function() {
			jQuery( ".showcoupon" ).click( function() {
				if(jQuery( ".checkout_coupon" ).css('display') === 'none'){
					jQuery( ".checkout_coupon" ).fadeIn();
					}
					else if(jQuery( ".checkout_coupon" ).css('display') === 'block'){
				jQuery( ".checkout_coupon" ).fadeOut();
			}	
			//	jQuery( "html, body" ).animate( { scrollTop: 0 }, "slow" );
  				return false;
			} );
		} );
		
</script>
<?php
}
//add_action( 'wp_footer', 'skyverge_show_coupon_js' );
//is_wc_endpoint_url( 'order-received' );
