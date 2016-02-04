<?php
/*
 * Template Name: Test PDF Style
 */
 
require_once 'mpdf/mpdf.php';
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

	global $wpdb, $shopkeeper_theme_options;
//$pdf = new HTML2PDF('L', 'cm', 'A4');
	try{
		$site_logo = $shopkeeper_theme_options['site_logo']['url'];
		$order_id=$_GET['order_id'];
		
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
		
		$order_details=$wpdb->get_results('select id,meals_per_day,no_of_weeks,regenerate,current_weight from '.$wpdb->prefix.'user_nutrition_plans where order_id='.$order_id.' AND site_id='.$site_id);
		
		$weight_range=$wpdb->get_results('select expected_weight from  up_plan_logs where user_nutrition_plan_id=(select id from  up_user_nutrition_plans where order_id='.$order_id.' AND site_id='.$site_id.')',ARRAY_N);
		
		$no_of_weeks=$order_details[0]->no_of_weeks;

		if(isset($order_details) && !empty($order_details)){
		  
			$plan_name=$items [array_keys($items)[0]]['name'];
		
			$opts = array(
				  'http'=>array(
					'method'=>"GET",
					'header'=>"Accept-language: en\r\n" .
							  "Cookie: foo=bar\r\n"
				  )
				);
		
			$context = stream_context_create($opts);
			
			$html1=file_get_contents(home_url().'/pdf1/?order_id='.$order_id);
		 
			$html2=file_get_contents(home_url().'/pdf2/?order_id='.$order_id);
			  
			$html3=file_get_contents(home_url().'/pdf3/?order_id='.$order_id);
			
			  //echo $html1;
			for($i=1;$i<2;$i++){
			
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
			
			
			$html12=file_get_contents(home_url().'/pdf12/?order_id='.$order_id);
			  
			$html13=file_get_contents(home_url().'/pdf13/?order_id='.$order_id);
			 
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
			$css=file_get_contents(get_template_directory_uri().'-child/PDF_html/pdf_style.css');
		
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
									<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
								<div style="float:right;width:19%;padding-right:2px; color:#162c5d;text-align:right;line-height:10px; ">
									<p>Seite {PAGENO}/{nbpg}</p>
								</div>
							</div>     
						  </div>', 'shopkeeper' );
						  
			$mpdf->SetHTMLHeader(htmlspecialchars_decode($header3));
			
		//	$mpdf->SetHTMLFooter(htmlspecialchars_decode($header3));
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
			$mpdf->WriteHTML(htmlspecialchars_decode($html3));

			for($i=1;$i<2;$i++){
				if($i==1){ 
					$min_w=str_replace(".",",",0.1);
				}
				else{
					//$min_w=	str_replace(".",",",(float)$order_details[0]->current_weight-(float)$weight_range_new[$i]);
					$min_w=$max_w;
				}
		
				$wr= (float)$order_details[0]->current_weight-(float)$weight_range_new[$i];
			
				$max_w=	number_format($wr,"1",",","");

				$header4[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:13%; float:left; color:#162c5d;text-align:left;padding-bottom:1px; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Montag</p>
							  </div>
							  <div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div>
							  <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'">
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
							  <div style="width:13%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Tuesday</p>
							  </div>
							   <div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div>
							  <div style="float:left;width:12%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
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
							  <div style="width:13%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Mittwoch</p>
							  </div>
							  <div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								   <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div>
							  <div style="float:left;width:13.5%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
							  <div style="float:right;width:43%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header6[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html6[$i]));

				$header7[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:15%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Donnerstag</p>
							  </div>
							  <div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div>
							  <div style="float:left;width:9%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
							  <div style="float:right;width:45%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header7[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html7[$i]));
			
				$header8[$i]=__( '<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:13%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Freitag</p>
							  </div>
							  <div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								   <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div>
							   <div style="float:left;width:14%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
							  <div style="float:right;width:42%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );

				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header8[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html8[$i]));
				
				$header9[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:13%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Samstag</p>
							  </div>
							  <div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								 <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div>
							   <div style="float:left;width:14%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
							  <div style="float:right;width:42%; color:#162c5d;text-align:right;line-height:10px; ">
								  <p>Seite {PAGENO}/{nbpg}</p>
							  </div>
							</div>     
						  </div>', 'shopkeeper' );
				$mpdf->SetHTMLHeader(htmlspecialchars_decode($header9[$i]));
				$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,16.7,10.18,0,0);
				$mpdf->WriteHTML(htmlspecialchars_decode($html9[$i]));
				
				$header10[$i]=__('<div style="padding:0px 0px 0px 0px;width:100%;background:#FFF;border:0;outline:none;margin:0;">
							<div style="padding:1px 38.4px 0px 37px;">
							  <div style="width:13%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								  <p style="float:left;">Woche '.$i.': Sonntag</p>
							  </div>
							   <div style="width:30%; float:left; color:#162c5d;text-align:left; line-height:10px;">
								 <p class="p4-header_green">Abnahme Woche '.$i.': ca. '.$min_w.' - '.$max_w.' kg</p>
							  </div>
							   <div style="float:left;width:14%;text-align:center; vertical-align:middle;">
								<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
							  <div style="float:right;width:42%; color:#162c5d;text-align:right;line-height:10px; ">
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
										<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
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
									<img style="margin-top:8px;" width="100" height=""  src="'.$site_logo.'"></div>
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
			
			$mpdf->SetHTMLHeader('');
			$mpdf->AddPage('UTF-8','A4-L', 0, 'avenir', 0,0,0,0,0,0,0);
			$mpdf->WriteHTML(htmlspecialchars_decode($html14));
			
			$path = wp_upload_dir(); 
			//$path['basedir']
			if(!file_exists($path['basedir'] . '/pdf')){mkdir($path['basedir'] . '/pdf/',0777, true);}
			/*for($i=71;$i<100;$i++){
				file_put_contents($uppath1.'status.json',json_encode(array('current'=>$i,'total'=>'100')));
				usleep(300000);
			}*/
			
			
			
			//$mpdf->Output($pdfpath, "F");
			if($order_item_id != ''){
				$sql="Update ".$wpdb->prefix."woocommerce_order_itemmeta set meta_value = 3 WHERE order_item_id=".$order_item_id." and meta_key = 'pdf_processing_status'";
				$wpdb->query($sql);
			}
			//echo "success";
			$pdfdoc = $mpdf->Output();
		
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
	exit;
?>
