<?php

/**
 * Plugin Name: Import Foods & Meals
 * version :1.0
 */


//register_deactivation_hook(__FILE__, 'sr_plugin_deactivate');

add_action('admin_menu', 'register_import_food_page');
global $wpdb;

//$prefix = 'up_';
$prefix = 'up_';

function register_import_food_page() {
		//add_menu_page('import_food_meals', 'Import Foods & Meals', 'manage_options', 'import_food_meals', 'import_food_meals_page', '', 9);
		//add_submenu_page('import_food_meals', 'View Foods Data', 'View Foods data', 'manage_options', 'view_foods_data', 'viewdata');
        add_menu_page('import_food_meals', 'Tabellen Import & Vorschau', 'manage_options', 'import_food_meals', 'import_food_meals_page', '', 9);
        add_submenu_page('import_food_meals', 'CSV importieren', 'CSV importieren', 'manage_options', 'import_food_meals', 'import_food_meals_page');
        add_submenu_page('import_food_meals', 'Tabelle anschauen', 'Tabelle anschauen', 'manage_options', 'view_foods_data', 'viewdata');
        
}

add_action('init', 'admin_css');

function admin_css() {

        wp_enqueue_style('admin_css', get_template_directory_uri() . '/../shopkeeper-child/css/admin_style.css', array(), '', true);
}

$flag = -1;
$type="";
function import_food_meals_page() {
	
$default_value=0;
        global $wpdb;
        $flag = -1;
        if (isset($_POST['submit'])) {

                /* ----------Load units ------------ */
                $sql6 = 'select id,unit_symbol From  ' . $prefix . 'units';
                //$res = mysql_query($sql6);
                $res = $wpdb->get_results($sql6);


                $unita = array();
                /* while ($row = mysql_fetch_object($res)) {
                  $unita[$row->unit_symbol] = $row->id;
                  } */
                foreach ($res as $v) {
                        $unita[$v->unit_symbol] = $v->id;
                }
                /* echo '<pre>';
                  print_r($unita);
                  echo '</pre>';
                  exit; */

                $type = $_POST['import_type'];
                $path = wp_upload_dir();
                $uppath = $path['basedir'] . '/csv';
                if (file_exists($uppath)) {
                        $prefix = 'up_';;
                        if ($_FILES["import_csv"]["error"] > 0) {
                                echo "Return Code: " . $_FILES["import_csv"]["error"] . "<br />";
                        } else {
                                $td = date("Y-m-d_H-m-s");
                                //echo $td;
                                $bpath = $path['baseurl'] . '/' . 'csv/';
                                $nm = base64_encode(microtime()) . $td . ".csv";
                                $storagename = $uppath . "/" . $nm;
                                //echo $storagename;
                                move_uploaded_file($_FILES["import_csv"]["tmp_name"], $storagename);
                                //echo "Stored in: " . "upload/" . $_FILES["import_csv"]["name"] . "<br />";
                                if (strtolower($type) == strtolower('unit')) {
                                        $file = fopen($bpath . $nm, "r");
                                        $ckfield = fgetcsv($file);
                                        if (strtolower($ckfield[0]) == strtolower('id') && strtolower($ckfield[1]) == strtolower('Unit') && strtolower($ckfield[2]) == strtolower('Unit Written')) {
                                                
                                                $sql = "CREATE TABLE " . $prefix . "units_new LIKE " . $prefix . "units";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "units TO " . $prefix . "units_old," . $prefix . "units_new TO " . $prefix . "units";
                                                //$sql1 = 'truncate TABLE wp_units';
                                                $wpdb->query($sql1);
                                                $temp = 0;
                                                while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
                                                        //print_r($data);
                                                        $data[3] = str_replace(',', '.', $data[3]);
                                                        $sql2 = "Insert into " . $prefix . "units(name,unit_symbol,unit_gram,created) VALUES('" . $data[2] . "','" . $data[1] . "','" . $data[3] . "','" . date('Y-m-d H:i:s') . "')";
                                                        //echo $sql.' <br>';
                                                        if ($wpdb->query($sql2)) {
                                                                $temp++;
                                                        } else {
                                                                $temp = 0;
                                                                break;
                                                        }
                                                         
                                                }
                                                
                                                if ($temp == 0) {
                                                        $sql = "RENAME TABLE " . $prefix . "units TO " . $prefix . "units_zap," . $prefix . "units_old TO " . $prefix . "units";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "units_zap";
                                                        $wpdb->query($sql12);
                                                        //$sql="ROLLBACK";
                                                        $flag = 0;
                                                } else {
                                                        $sql = "DROP TABLE " . $prefix . "units_old";
                                                        $wpdb->query($sql);
                                                        $flag = 1;
                                                }
                                        }
                                        else
                                        {
											$flag=0;
										}

                                        // print_r(fgetcsv($file));
                                        fclose($file);
                                } else if (strtolower($type) == strtolower('splistcat')) {
                                        $prefix = 'up_';;
                                        $file = fopen($bpath . $nm, "r");
                                        $ckfield = fgetcsv($file);
                                        if (strtolower($ckfield[0]) == strtolower('id') && strtolower($ckfield[1]) == strtolower('Shopping List Category')) {
                                                $sql = "CREATE TABLE " . $prefix . "shopping_list_categories_new LIKE " . $prefix . "shopping_list_categories";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "shopping_list_categories TO " . $prefix . "shopping_list_categories_old," . $prefix . "shopping_list_categories_new TO " . $prefix . "shopping_list_categories";

                                                $wpdb->query($sql1);
											//$wpdb->query("truncate table " . $prefix . "shopping_list_categories");
                                                /* $sql1 = 'delete from ".$prefix."shopping_list_categories';
                                                  $wpdb->query($sql1); */
                                                $temp = 0;
                                                while (($data = fgetcsv($file, 100000, ",")) !== FALSE) {
                                                        // print_r($data);
                                                        /*$slug = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '_', $data[1]);
                                                        $slug = str_replace(' ', '_', $slug);*/
                                                        $slug = sanitize_title($data[1]);
                                                        $sql2 = "Insert into " . $prefix . "shopping_list_categories(name,slug,created) VALUES('" . htmlspecialchars($data[1],ENT_QUOTES) . "','" . $slug . "','" . date('Y-m-d H:i:s') . "')";
                                                        if ($wpdb->query($sql2)) {
                                                                $temp++;
                                                        } else {
                                                                $temp = 0;
                                                                break;
                                                        }
                                                        // echo $sql.' <br>';
                                                }
                                                if ($temp == 0) {
                                                        $sql = "RENAME TABLE " . $prefix . "shopping_list_categories TO " . $prefix . "shopping_list_categories_zap," . $prefix . "shopping_list_categories_old TO " . $prefix . "shopping_list_categories";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "shopping_list_categories_zap";
                                                        $wpdb->query($sql12);
                                                        //$sql="ROLLBACK";
                                                        $flag = 0;
                                                } else {
                                                        $sql = "DROP TABLE " . $prefix . "shopping_list_categories_old";
                                                        $wpdb->query($sql);
                                                        $flag = 1;
                                                }
                                        }
                                        else
                                        {
											$flag=0;
										}

                                        // print_r(fgetcsv($file));
                                        fclose($file);
                                } else if (strtolower($type) == strtolower('food')) {
                                        $prefix = 'up_';
                                        $m = 0;

                                        $file = fopen($bpath . $nm, "r");
                                        $ckfield = fgetcsv($file);
                                        /* echo '<pre>';
                                          print_r($ckfield); */
                                        if (strtolower($ckfield[0]) == strtolower('id') && strtolower($ckfield[1]) == strtolower('Name') && strtolower($ckfield[2]) == strtolower('Shopping List Name') && strtolower($ckfield[67]) == strtolower('Compatible with vegetarian') && strtolower($ckfield[68]) == strtolower('Compatible with vegan') && strtolower($ckfield[69]) == strtolower('Compatible with pescetarian') && strtolower($ckfield[70]) == strtolower('Compatible with Paleo') && strtolower($ckfield[71]) == strtolower('Compatible with lactose intolerance') && strtolower($ckfield[72]) == strtolower('Compatible with fructose intolerance') && strtolower($ckfield[73]) == strtolower('Compatible with histamine intolerance') && strtolower($ckfield[74]) == strtolower('Compatible with gluten intolerance') && strtolower($ckfield[75]) == strtolower('Compatible with glutamat intolerance') && strtolower($ckfield[76]) == strtolower('Compatible with saccharose intolerance') && strtolower($ckfield[77]) == strtolower('Compatible with peanut intolerance') && strtolower($ckfield[78]) == strtolower('Compatible with almond intolerance') && strtolower($ckfield[79]) == strtolower('Compatible with haselnut intolerance') && strtolower($ckfield[80]) == strtolower('Compatible with walnut intolerance')) {
                                                //echo 'yes';

												$sql = "CREATE TABLE " . $prefix . "foods_new LIKE " . $prefix . "foods";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "foods TO " . $prefix . "foods_old," . $prefix . "foods_new TO " . $prefix . "foods";
                                                $wpdb->query($sql1);

                                                $sql = "CREATE TABLE " . $prefix . "food_micros_new LIKE " . $prefix . "food_micros";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "food_micros TO " . $prefix . "food_micros_old," . $prefix . "food_micros_new TO " . $prefix . "food_micros";
                                                $wpdb->query($sql1);

                                                /* $sql3 = 'TRUNCATE TABLE wp_foods';
                                                  $wpdb->query($sql3);
                                                  $sql5 = 'TRUNCATE TABLE wp_food_micros';
                                                  $wpdb->query($sql5); */
                                                $sql6 = 'select id,unit_symbol From  ' . $prefix . 'units';
                                                $res = $wpdb->get_results($sql6);
                                                $unita = array();
                                                foreach ($res as $v) {
                                                        $unita[$v->unit_symbol] = $v->id;
                                                }
                                                /*
                                                  while ($row = mysql_fetch_object($res)) {
                                                  $unita[$row->unit_symbol] = $row->id;
                                                  } */
                                                // print_r($unita);//exit;
                                                $unitarray = array();
                                                //$fp = file('test.csv');
                                                //echo count($bpath.$nm);exit;
                                                $temp = 0;
                                                $temp1 = 0;
                                                
                                                while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
														//echo "data 0".$data[0]."</br>";
                                                        if ($data[0] == '' && count($unitarray) <= 0) {
                                                                $unitarray = $data;
                                                        }
                                                        //echo "unitarray<pre>"; print_r($unitarray);
														
                                                        if (strtolower($data[0]) != strtolower('id') && $data[0] != '') {
                                                                /*$slug = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '_', $data[98]);
                                                                $slug = strtolower(str_replace(' ', '_', $slug));*/
                                                                   $slug =sanitize_title($data[98]);
                                                                   //echo "exchange:".$data[97]."</br>";
                                                                   //echo "slug:".$slug."</br>";
                                                                   //exit;
                                                                $catsql = "Select id from " . $prefix . "food_categories where slug like '" . $slug . "' ";
                                                                $res = $wpdb->get_row($catsql);

                                                                /* print_r($res);
                                                                  exit; */
                                                                /* $result = mysql_fetch_object($res);
                                                                  print_r($result); */

                                                                if (count($res) > 0) {
                                                                        $cat = $res->id;
                                                                        if(empty($cat)){$cat=0;}
                                                                } else {
                                                                        $cat = 0;
                                                                }

								$shop_id = 0;
                                                                if (isset($data[99]) && !empty($data[99])) {
                                                                        $sql_query_cat_shop = "SELECT id FROM " . $prefix . "shopping_list_categories WHERE name='" . htmlspecialchars($data[99]) . "'";
                                                                        $cat_shop_id = $wpdb->get_row($sql_query_cat_shop);
                                                                        //print_r($cat_shop_id);
																	if(!empty($cat_shop_id)){
                                                                        $shop_id = $cat_shop_id->id;
																	}
																	else{ $shop_id = 0;
																	}
                                                                } else {
                                                                        $shop_id = 0;
                                                                }
								
                                                                $sql7 = "INSERT INTO " . $prefix . "foods 
                                                       (`nid`,
                                                       `food_category_id`,
                                                       `shopping_list_category_id`,
                                                        `name`,
                                                        `shopping_list_name`,
                                                        `cw_vegetarian`,
                                                        `cw_vegan`,
                                                        `cw_pescetarian`,
                                                        `cw_paleo`,
                                                        `cw_lactose_intolerance`, 
                                                        `cw_fructose_intolerance`,
                                                        `cw_histamine_intolerance`, 
                                                        `cw_gluten_intolerance`,
                                                        `cw_glutamat_intolerance`,
                                                        `cw_sucrose_intolerance`, 
                                                        `cw_peanut_intolerance`,
                                                        `cw_almond_intolerance`, 
                                                        `cw_hazelnut_intolerance`, 
                                                        `cw_walnut_intolerance`, 
                                                        `cw_cashew_intolerance`, 
                                                        `cw_pecan_nut_intolerance`, 
                                                        `cw_brazil_nut_intolerance`, 
                                                        `cw_pistachio_intolerance`, 
                                                        `cw_banana_intolerance`,
                                                        `cw_avocade_intolerance`, 
                                                        `cw_apple_intolerance`,
                                                        `cw_kiwi_fruit_intolerance`,
                                                        `cw_melon_intolerance`, 
                                                        `cw_papaya_intolerance`,
                                                        `cw_peach_intolerance`,
                                                        `cw_strawberry_intolerance`,
                                                        `is_snack`, 
                                                        `is_fatburner`,
                                                        `price_level_id`,
                                                        `synonym_of`, 
                                                        `exchangeable_with`,
                                                        `created`)
                                        VALUES
                                        ($data[0],$cat,$shop_id,
                                                '" . sanitize_text_field($data[1]) . "',
                                                '" . sanitize_text_field($data[2]) . "',
                                                '" .((($data[67]) == "") ? $default_value : str_replace(',','.',$data[67])) . "',
                                                '" .((($data[68]) == "" )? $default_value : str_replace(',','.',$data[68])) . "',
                                                '" .((($data[69]) == "" )? $default_value : str_replace(',','.',$data[69])) . "',
                                                '" .((($data[70]) == "" )? $default_value : str_replace(',','.',$data[70])) . "',
                                                '" .((($data[71]) == "" )? $default_value : str_replace(',','.',$data[71])) . "',
                                                '" .((($data[72]) == "" )? $default_value : str_replace(',','.',$data[72])) . "',
                                                '" .((($data[73]) == "" )? $default_value : str_replace(',','.',$data[73])) . "',
                                                '" .((($data[74]) == "" )? $default_value : str_replace(',','.',$data[74])) . "',
                                                '" .((($data[75]) == "" )? $default_value : str_replace(',','.',$data[75])) . "',
                                                '" .((($data[76]) == "" )? $default_value : str_replace(',','.',$data[76])) . "',
                                                '" .((($data[77]) == "" )? $default_value : str_replace(',','.',$data[77])). "',
                                                '" .((($data[78]) == "" )? $default_value : str_replace(',','.',$data[78])). "',
                                                '" .((($data[79]) == "" )? $default_value : str_replace(',','.',$data[79])). "',
                                                '" .((($data[80]) == "" )? $default_value : str_replace(',','.',$data[80])). "',
                                                '" .((($data[81]) == "" )? $default_value : str_replace(',','.',$data[81])). "',
                                                '" .((($data[82]) == "" )? $default_value : str_replace(',','.',$data[82])). "',
                                                '" .((($data[83]) == "" )? $default_value : str_replace(',','.',$data[83])). "',
                                                '" .((($data[84]) == "" )? $default_value : str_replace(',','.',$data[84])). "',
                                                '" .((($data[85]) == "" )? $default_value : str_replace(',','.',$data[85])). "',
                                                '" .((($data[86]) == "" )? $default_value : str_replace(',','.',$data[86])). "',
                                                '" .((($data[87]) == "" )? $default_value : str_replace(',','.',$data[87])). "',
                                                '" .((($data[88]) == "" )? $default_value : str_replace(',','.',$data[88])). "',
                                                '" .((($data[89]) == "" )? $default_value : str_replace(',','.',$data[89])). "',
                                                '" .((($data[90]) == "" )? $default_value : str_replace(',','.',$data[90])). "',
                                                '" .((($data[91]) == "" )? $default_value : str_replace(',','.',$data[91])). "',
                                                '" .((($data[92]) == "" )? $default_value : str_replace(',','.',$data[92])). "',
                                                '" .((($data[93]) == "" )? $default_value : str_replace(',','.',$data[93])). "',
                                                '" .((($data[94]) == "" )? $default_value : str_replace(',','.',$data[94])). "',
                                                '" .((($data[95]) == "" )? $default_value : str_replace(',','.',$data[95])). "',
                                                '" . sanitize_text_field($data[96]) . "',
                                                '" . sanitize_text_field($data[97]) . "',
                                                '" . date('Y-m-d H:i:s') . "')";
                                                                
                                                                $m++;
                                                                try {
																		
																		//echo "pescetrain". (($data[69] == "")? $default_value : $data[69])."</br>";
																		//echo $sql7;
																		
                                                                        $wpdb->query($sql7);
                                                                      
                                                                        $temp++;
                                                                } catch (Exception $e) {
                                                                        echo $e;
                                                                        $temp = 0;
                                                                        break;
                                                                }

                                                                $last_id = $wpdb->insert_id;
                                                                /* echo $last_id;
                                                                  if($last_id == 4)
                                                                  {
                                                                  exit;
                                                                  } */
                                                                /*for ($i = 3; $i < 67; $i++) {
                                                                        $sql8 = "Insert into " . $prefix . "food_micros (food_id,name,value,unit_id) VALUES(" . sanitize_text_field($last_id) . ",'" . sanitize_text_field($ckfield[$i]) . "','" . sanitize_text_field($data[$i]) . "','" . sanitize_text_field($unita[$unitarray[$i]]) . "')";
                                                                        //echo $sql8.'<br/>';
                                                                        if ($wpdb->query($sql8)) {
                                                                                $temp1++;
                                                                        } else {
                                                                                $temp = 1;
                                                                                break;
                                                                        }
                                                                        //$wpdb->query($sql8);
                                                                }*/
                                                                $sql8 = "Insert into " . $prefix . "food_micros (food_id,name,value,unit_id) VALUES";
                                                                
                                                                for ($i = 3; $i < 67; $i++) {
																	//echo "unit_id".$unita[$unitarray[$i]];
                                                                        $sql8 .= "(" . sanitize_text_field($last_id) . ",'" . sanitize_text_field($ckfield[$i]) . "','" . str_replace(',','.',$data[$i]) . "','" . sanitize_text_field($unita[$unitarray[$i]]) . "'),";
                                                                        
                                                                        
                                                                        //$wpdb->query($sql8);
                                                                }
                                                               // echo $sql8;
                                                               
                                                                //echo trim($sql8,',').'<br/>';
                                                                if ($wpdb->query(trim($sql8,','))) {
                                                                                $temp1++;
                                                                        } else {
                                                                                $temp1 = 1;
                                                                                break;
                                                                        }

                                                                // echo $last_id;
                                                        }
                                                }
                                                if ($temp == 0 || $temp1 == 0) {
                                                        $sql = "RENAME TABLE " . $prefix . "foods TO " . $prefix . "foods_zap," . $prefix . "foods_old TO wp_foods";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "foods_zap";
                                                        $wpdb->query($sql12);

                                                        $sql = "RENAME TABLE " . $prefix . "food_micros TO " . $prefix . "food_micros_zap," . $prefix . "food_micros_old TO " . $prefix . "food_micros";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "food_micros_zap";
                                                        $wpdb->query($sql12);
                                                        //$sql="ROLLBACK";
                                                        $flag = 0;
                                                } else {
                                                        $sql = "DROP TABLE " . $prefix . "foods_old";
                                                        $wpdb->query($sql);

                                                        $sql = "DROP TABLE " . $prefix . "food_micros_old";
                                                        $wpdb->query($sql);
                                                        $flag = 1;
                                                }
                                        }
                                        else
                                        {
											$flag=0;
										}
                                        // print_r(fgetcsv($file));
                                        fclose($file);
                                } else if (strtolower($type) == strtolower('Food_cat')) {
                                        $prefix = 'up_';
                                        $file = fopen($bpath . $nm, "r");
                                        $ckfield = fgetcsv($file);
                                        if (strtolower($ckfield[0]) == strtolower('id') && strtolower($ckfield[1]) == strtolower('Food Category')) {
                                                //  echo 'yes';
                                                //$sql = 'truncate TABLE wp_food_categories';
                                                $sql = "CREATE TABLE " . $prefix . "food_categories_new LIKE " . $prefix . "food_categories";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "food_categories TO " . $prefix . "food_categories_old," . $prefix . "food_categories_new TO " . $prefix . "food_categories";
                                                $wpdb->query($sql1);
                                                $temp = 0;
                                                while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
                                                        // print_r($data);
                                                        /*$slug = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '_', $data[1]);
                                                        $slug = str_replace(' ', '_', $slug);*/
                                                        $slug = sanitize_title($data[1]);
                                                        $sql = "Insert into " . $prefix . "food_categories(id,name,slug,created) VALUES('" . $data[0] . "','" . $data[1] . "','" . $slug . "','" . date('Y-m-d H:i:s') . "')";
                                                        if ($wpdb->query($sql)) {
                                                                $temp++;
                                                        } else {
                                                                $temp = 0;
                                                                break;
                                                        }                                                // echo $sql.' <br>';
                                                }
                                                if ($temp == 0) {
                                                        $sql = "RENAME TABLE " . $prefix . "food_categories TO " . $prefix . "food_categories_zap," . $prefix . "food_categories_old TO " . $prefix . "food_categories";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "food_categories_zap";
                                                        $wpdb->query($sql12);
                                                        //$sql="ROLLBACK";
                                                        $flag = 0;
                                                } else {
                                                        $sql = "DROP TABLE " . $prefix . "food_categories_old";
                                                        $wpdb->query($sql);
                                                        $flag = 1;
                                                }
                                        }
										else
                                        {
											$flag=0;
										}
                                        // print_r(fgetcsv($file));
                                        fclose($file);
                                } else if (strtolower($type) == strtolower('footattrib')) {
                                        $prefix = 'up_';
                                        $file = fopen($bpath . $nm, "r");
                                        $ckfield = fgetcsv($file);
                                        print_r($ckfield);
                                        //  exit;
                                        if (strtolower($ckfield[1]) == strtolower('male') && strtolower($ckfield[2]) == strtolower('female') && strtolower($ckfield[3]) == strtolower('unit')) {
                                                //  echo 'yes';
                                                $sql = "CREATE TABLE " . $prefix . "food_attribute_types_new LIKE " . $prefix . "food_attribute_types";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "food_attribute_types TO " . $prefix . "food_attribute_types_old," . $prefix . "food_attribute_types_new TO " . $prefix . "food_attribute_types";
                                                $wpdb->query($sql1);


                                                $sql = "CREATE TABLE " . $prefix . "food_attributes_new LIKE " . $prefix . "food_attributes";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "food_attributes TO " . $prefix . "food_attributes_old," . $prefix . "food_attributes_new TO " . $prefix . "food_attributes";
                                                $wpdb->query($sql1);
                                                /* $sql = 'truncate TABLE wp_food_attribute_types';
                                                  $wpdb->query($sql);
                                                  $sql = 'truncate TABLE wp_food_attributes';
                                                  $wpdb->query($sql); */
                                                $last_id = '';
                                                while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {

                                                        if ($data[0] != '' && $data[1] == '' && $data[2] == '' && $data[3] == '') {
                                                                $sql = "insert into " . $prefix . "food_attribute_types(name,created) VALUES('" . $data[0] . "','" . date('Y-m-d H:i:s') . "')";
                                                                //$last_id = mysql_insert_id();
                                                                if ($wpdb->query($sql)) {
                                                                        $temp++;
                                                                } else {
                                                                        $temp = 0;
                                                                        break;
                                                                }
                                                                $last_id = $wpdb->insert_id;
                                                        } else {
                                                                $sql = "insert into " . $prefix . "food_attributes(food_attribute_types_id,name,male,female,unit_id,created) VALUES('" . $last_id . "','" . $data[0] . "','" . $data[1] . "','" . $data[2] . "','" . $unita[$data[3]] . "','" . date('Y-m-d H:i:s') . "')";
                                                                if ($wpdb->query($sql)) {
                                                                        $temp++;
                                                                } else {
                                                                        $temp = 0;
                                                                        break;
                                                                }
                                                        }
                                                }
                                                if ($temp == 0) {
                                                        $sql = "RENAME TABLE " . $prefix . "food_attribute_types TO " . $prefix . "food_attribute_types_zap," . $prefix . "food_attribute_types_old TO " . $prefix . "food_attribute_types";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "food_attribute_types_zap";
                                                        $wpdb->query($sql12);


                                                        $sql = "RENAME TABLE " . $prefix . "food_attributes TO " . $prefix . "food_attributes_zap," . $prefix . "food_attributes_old TO " . $prefix . "food_attributes";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "food_attributes_zap";
                                                        $wpdb->query($sql12);
                                                        //$sql="ROLLBACK";
                                                        $flag = 0;
                                                } else {
                                                        $sql = "DROP TABLE " . $prefix . "food_attribute_types_old";
                                                        $wpdb->query($sql);
                                                        $sql = "DROP TABLE " . $prefix . "food_attributes_old";
                                                        $wpdb->query($sql);
                                                        $flag = 1;
                                                }
                                        }
										else
                                        {
											$flag=0;
										}
                                        // print_r(fgetcsv($file));
                                        fclose($file);
                                } else if (strtolower($type) == strtolower('meal')) {
                                      $prefix = 'up_';
                                        $file = fopen($bpath . $nm, "r");
                                        $ckfield = fgetcsv($file);
                                        if (strtolower($ckfield[0]) == strtolower('id') && strtolower($ckfield[1]) == strtolower('name') && strtolower($ckfield[2]) == strtolower('Ingredient 1') && strtolower($ckfield[3]) == strtolower('Ingredient 1 Quantity')) {
                                                //  echo 'yes';
                                                $sql = "CREATE TABLE " . $prefix . "meals_new LIKE " . $prefix . "meals";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "meals TO " . $prefix . "meals_old," . $prefix . "meals_new TO " . $prefix . "meals";
                                                $wpdb->query($sql1);

                                                $sql = "CREATE TABLE " . $prefix . "meal_ingredients_new LIKE " . $prefix . "meal_ingredients";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "meal_ingredients TO " . $prefix . "meal_ingredients_old," . $prefix . "meal_ingredients_new TO " . $prefix . "meal_ingredients";
                                                $wpdb->query($sql1);

                                                $sql = "CREATE TABLE " . $prefix . "meal_instructions_new LIKE " . $prefix . "meal_instructions";
                                                $wpdb->query($sql);
                                                $sql1 = "RENAME TABLE " . $prefix . "meal_instructions TO " . $prefix . "meal_instructions_old," . $prefix . "meal_instructions_new TO " . $prefix . "meal_instructions";
                                                $wpdb->query($sql1);

                                                /* $sql = 'Truncate TABLE wp_meals';
                                                  $wpdb->query($sql);
                                                  $sql = 'Truncate TABLE wp_meal_ingredients';
                                                  $wpdb->query($sql);
                                                  $sql = 'Truncate TABLE wp_meal_instructions';
                                                  $wpdb->query($sql); */
                                                // echo '<pre>';
                                                
                                                $sql6 = 'select id,unit_symbol From  ' . $prefix . 'units';
                                                $res = $wpdb->get_results($sql6);
                                                $unita = array();
                                                foreach ($res as $v) {
                                                        $unita[$v->unit_symbol] = $v->id;
                                                }
                                                $file = fopen($bpath . $nm, "r");
                                                $ckfield = fgetcsv($file);
                                                //print_r($ckfield);
                                                $key = array_search('Preparation Instruction', $ckfield);
                                                // echo $key;
                                                $last_key = key(array_slice($ckfield, -1, 1, TRUE));
                                                // echo $last_key;
                                                $temp = 0;
                                               
                                                while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
													
                                                        if ($data[1] != '') {
								//$name = wp_specialchars($data[1], $quotes );
													$meal_id=$data[0];
													$name = htmlspecialchars($data[1], ENT_QUOTES );
                                                                $sql = "Insert into " . $prefix . "meals(meal_id,name,created) VALUES('".$meal_id."','" . $name . "','" . date('Y-m-d H:i:s') . "')";
                                                                //echo "meal :".$sql."</br>";
                                                                $res = $wpdb->query($sql);
								
                                                                //$last_id = mysql_insert_id();
                                                                $last_id = $wpdb->insert_id;
                                                                $j = 1;
                                                                $sql1 = "Insert into " . $prefix . "meal_ingredients(meal_id,name,quantity,unit_id,weight,is_ommitable,exchangable_with_ingredient,created) VALUES";
                                                                for ($i = 2; $i < $key; $i = $i + 6) {
                                                                        if ($data[$i] != '') {

                                                                                $sql1 .= "('" . $last_id . "','" . htmlspecialchars($data[$i],ENT_QUOTES) . "','" . str_replace(',','.',$data[$i + 1]) . "','" . $unita[$data[$i + 2]] . "','" . str_replace(',','.',$data[$i + 3]) . "','" . $data[$i + 4] . "','" . $data[$i + 5] . "','" . date('Y-m-d H:i:s') . "'),";
                                                                                
                                                                                
                                                                                
                                                                                $data[$key] = str_replace('[[' . strtolower('ingredient' . $j) . ']]', $data[$i], $data[$key]);
                                                                        }
                                                                        $j++;
                                                                }
                                                               // echo "meal ingrident:".$sql1."</br>";
                                                                
                                                               
                                                                $res1 = $wpdb->query(trim($sql1,','));
                                                                //echo "res1:".$res1."<br>";
                                                                //echo "popularity:".$data[$key + 4]."</br>";
                                                                
                                                                $sql3 = "Insert into " . $prefix . "meal_instructions(meal_id,instruction,difficulty,preparation_time,other_time,popularity,source,meal_category_ids,created) VALUES('" . $last_id . "','" . $data[$key] . "','" . $data[$key + 1] . "','" . $data[$key + 2] . "','" . $data[$key + 3] ."','" .((($data[$key + 4]) == "") ? $default_value : $data[$key + 4])."','" . $data[$key + 5] . "','" . $data[$key + 6] . "','" . date('Y-m-d H:i:s') . "')";
                                                                
                                                               // echo "meal instruction:".$sql3."</br>";
                                                               // exit;
                                                                $res2 = $wpdb->query($sql3);
                                                                //echo "res:".$res2."<br>";
                                                                if ($res == false || $res1 == false || $res2 == false) {
                                                                        $temp = 0;
                                                                        break;
                                                                } else {
                                                                        $temp++;
                                                                }
                                                                //                                                                //echo "temp:".$temp."<br>";
                                                        }
														
                                                }
                                                if ($temp == 0) {
                                                        $sql = "RENAME TABLE " . $prefix . "meals TO " . $prefix . "meals_zap," . $prefix . "meals_old TO " . $prefix . "meals";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "meals_zap";
                                                        $wpdb->query($sql12);

                                                        $sql = "RENAME TABLE " . $prefix . "meal_ingredients TO " . $prefix . "meal_ingredients_zap," . $prefix . "meal_ingredients_old TO " . $prefix . "meal_ingredients";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "meal_ingredients_zap";
                                                        $wpdb->query($sql12);

                                                        $sql = "RENAME TABLE " . $prefix . "meal_instructions TO " . $prefix . "meal_instructions_zap," . $prefix . "meal_instructions_old TO " . $prefix . "meal_instructions";
                                                        $wpdb->query($sql);
                                                        $sql12 = "DROP TABLE " . $prefix . "meal_instructions_zap";
                                                        $wpdb->query($sql12);
                                                        //$sql="ROLLBACK";
                                                        $flag = 0;
                                                } else {
                                                        $sql = "DROP TABLE " . $prefix . "meals_old";
                                                        $wpdb->query($sql);

                                                        $sql = "DROP TABLE " . $prefix . "meal_ingredients_old";
                                                        $wpdb->query($sql);

                                                        $sql = "DROP TABLE " . $prefix . "meal_instructions_old";
                                                        $wpdb->query($sql);
                                                        $flag = 1;
                                                }
                                        }
										else
                                        {
											$flag=0;
										}
                                        // print_r(fgetcsv($file));
                                        fclose($file);
                                }
                        }
                } else {
                        echo 'no';
                }
        }
        ?>
        <div class="">
                <h2>Import CSV</h2>
                <div class="">
                        <form class="" name="import_food_meals" method="post" enctype="multipart/form-data">
                                <div>
                                        <label>Import CSV Type</label>
                                        <select name="import_type" id="import_type">
                                                <option value="Food">Food</option>
                                                <option value="Food_cat">Food Category</option>
                                                <option value="Meal">Meal</option>
                                                <option value="Unit">Unit</option>
                                                <option value="splistcat">Shopping list Category</option>
                                                <!--<option value="Conversion">Unit Conversion</option>
                                                <option value="footattrib">Food Attribute</option>-->
                                        </select>
                                </div>
                                <div>
                                        <label>Import CSV </label>
                                        <input type="file" name="import_csv"/>
                                </div>
                                <div>
                                        <input type="submit" name="submit" value="Submit" class="button button-primary button-large"/>

                                        <?php if ($flag == 1) { ?>
												<input type="submit" name="submit" value="View Data" class="button button-primary button-large" id="view_data"/>
                                                <label id='msg' name='msg'>File Successfully uploaded</label>
                                                
                                        <?php } elseif ($flag == 0) {
                                                ?>
                                                <label id='msg' name='msg'>There is an error in file upload,but database records is not changed.</label>
                                        <?php } ?>

                                </div>
                        </form>
                </div>
        </div>
        <script>
                jQuery(document).ready(function () {
					 jQuery('#view_data').on('click', function () {
						 //var option=jQuery('#import_type').val();
						 var option="<?php echo $type;?>";
						 //alert(option);
						 var href="<?php echo admin_url('admin.php?page=view_foods_data&option=', '' );?>";
						 href+=option;
						 //alert(href);
						 window.open(href,'_blank');
						 return false;
					});
                    /*setTimeout(function () {
                        //alert('2');
                        jQuery('#msg').fadeOut('fast');
                    }, 3000);*/
                });
        </script>
        <?php
}

function viewdata() {
        ?>
        <style>
                body{background-color:transparent !important;}
                .list_input select {
                    width: 300px;
                    height: 30px;
                    border: 1px solid #d1d1d1;
                    float: left;
                    margin-right: 5px;
                    border-radius: 5px;
                    background: #F1F1F1;
                    font-size: 13px;
                }
                .list_input label {
                    float: left;
                    margin-right: 10px;
                    font-size: 14px;
                    margin-top: 7px;
                }
                .view_buttons  {
                    background: green;
                    color: #FFF;
                    border: none;
                    padding: 5px 15px;
                    cursor: pointer;
                    border-radius: 5px;
                }
                .view_buttons:hover  {
                    background: #23282D;
                }
                .foodlisting {
                    margin: 0 auto;
                    background: #FFF;
                    /* float: left; */
                    padding: 35px;
                    margin-top: 20px;
                    display: inline-block;
                    border:1px solid #d1d1d1;
                    border-radius: 5px;
                }

                .foodslist {
                    width: 98%;
                    margin: 50px 0;
                    background: #FFF;
                    overflow: auto;

                }

                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                }
                /* Zebra striping */
                tr:nth-of-type(odd) { 
                    background: #eee; 
                }
                th { 
                    background: rgba(51, 51, 51, 0.6);
                    color: white; 
                    font-weight: bold; 
                    border: 1px solid #999; 
                    padding: 6px; 
                    text-align: left; 

                }
                td,th{padding: 6px !important; }
                td  { 
                    padding: 6px !important; 
                    border: 1px solid #ccc; 
                    text-align: left;
                    //word-break: break-word;
                }
                .view_micros,.view_meal_ingd{cursor: pointer;color:#2cab50}
                .modal-body {overflow:auto;}
                .modal, .modal.fade.in {
					top: 10%;
				}
				.searchdiv{
					padding-top:20px;
				}
				
				.loader{
					background: #000 none repeat scroll 0 0;
					height: 100%;
					left: 0;
					opacity: 0.4;
					position: absolute;
					top: 0;
					width: 100%;
					z-index: 999;
					display: none;
					text-align: center;
				}
				.imgstyle {
					position: absolute;
					top: 15%;
				}
				
        </style>
        <div class="foodlisting">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
                <div class="webwrap"></div>
                <form>
                        <div class="list_input">
                                <label for="name">Select Type</label>
                                <select name="options_view" id="options_view">
                                        <option value="Food">Food</option>
                                        <option value="Food_cat">Food Category</option>
                                        <option value="Meal">Meal</option>
                                        <option value="Unit">Unit</option>
                                        <option value="splistcat">Shopping list Category</option>
                                        <!--<option value="Conversion">Unit Conversion</option>
                                        <option value="footattrib">Food Attribute</option>-->
                                </select>

                                <input class="view_buttons" name="name" type="button" value="View" id="view_lists" size="30" />

                        </div>
                </form>
        </div>
        <div class="modal fade list_data_001" style="display: none;">
                <div class="modal-dialog">
                        <div class="modal-content modal-lg">
                                <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">View Detail</h4>
                                </div>
                                <div class="modal-body">
                                        <p>One fine body&hellip;</p>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                        </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
        </div>
        <script>
                jQuery(document).ready(function () {
					var option_val="<?php echo $_REQUEST["option"]?>";
					var searchtxt="<?php echo $_REQUEST["searchingi"]?>";
					if(option_val.length!=0)
					{
						jQuery('#options_view').val(option_val);
						jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'POST',
                            data: "action=import_list_data&csv=" + option_val + "&nonce=<?php echo wp_create_nonce('import_list_data'); ?>",
                            beforeSend: function () {
                                //console.log('sending');
                                jQuery(".loader").css("display", "block");
                                jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
                            },
                            success: function (res) {
                                //console.log(res);
                                jQuery("#searching").css("display", "block");
                                jQuery(".loader").css("display", "none");
                                //jQuery('.foodslist').html(res);
                                jQuery('#importlistdata').html(res);
                            }
                        })
					}
					if(searchtxt.length!=0)
					{
						//alert(searchtxt);
						var csv = 'Food';
						
						jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'POST',
                            data: "action=search_list_data1&csv=" + csv + "&searchtxt="+searchtxt+"&nonce=<?php echo wp_create_nonce('search_list_data1'); ?>",
                            beforeSend: function () {
                                //console.log('sending');
                               jQuery(".loader").css("display", "block");
                               jQuery('#importlistdata').css("display", "none");
                               jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
                            },
                            success: function (res) {
                                //console.log(res);
                                jQuery(".loader").css("display", "none");
                                jQuery('#importlistdata').css("display", "block");
                                jQuery('#importlistdata').html(res);
                            }
                        });
					}
					//alert(msg);
                    jQuery('#view_lists').on('click', function () {

                        var csv = jQuery('#options_view').val();
                        //alert(csv);
                        jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'POST',
                            data: "action=import_list_data&csv=" + csv + "&nonce=<?php echo wp_create_nonce('import_list_data'); ?>",
                            beforeSend: function () {
                                //console.log('sending');
                                jQuery(".loader").css("display", "block");
                                jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
                            },
                            success: function (res) {
                                //console.log(res);
                                jQuery("#searching").css("display", "block");
                                jQuery(".loader").css("display", "none");
                                //jQuery('.foodslist').html(res);
                                jQuery('#importlistdata').html(res);
                            }
                        })
                    });

                    jQuery(document).on('click', '.view_micros', function () {

                        var htm = jQuery(this).next('div.view_micros_val').html();
                        var row1 = jQuery(this).closest("tr"),        // Finds the closest row <tr> 
							food_name = row1.find("td:nth-child(1).foodname").text();
						
						jQuery('.modal-title').text('View Detail - '+food_name);
                        jQuery('.list_data_001 .modal-body').html(htm);
                        jQuery('.list_data_001').modal('show');
                    });
                    jQuery(document).on('click', '.view_meal_ingd', function () {
                        var id = this.id;
						var row1 = jQuery(this).closest("tr"),        // Finds the closest row <tr> 
						    meal_name = row1.find("td:nth-child(2).foodname").text();
                        jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'POST',
                            data: "action=meal_ingredient&id=" + id + "&nonce=<?php echo wp_create_nonce('meal_ingredient'); ?>",
                            success: function (res) {
								
								//alert(meal_name);
								jQuery('.modal-title').text('View Detail - '+meal_name);
                                jQuery('.list_data_001 .modal-body').html(res);
                                jQuery('.list_data_001').modal('show');
                            }
                        })
                    })
                    
					jQuery(document).on('click', '.ind_detail', function () {
						//alert('1');
						var search=this.id;
						var csv='Food';
						 var option="<?php echo $type;?>";
						 //alert(option);
						 var href="<?php echo admin_url('admin.php?page=view_foods_data&searchingi=', '' );?>";
						 href+=search;
						 //alert(href);
						 window.open(href,'_blank');
						 return false;
						
					});
                    var page = 0; // What page we are on.
                    var ppp = 10; // Post per page
                    jQuery("#pagnation button").live('click', function () {

                        var ajaxUrl = "<?php echo admin_url('admin-ajax.php') ?>";
                        var buttonId = jQuery(this).attr("id");
                        var csv = jQuery(this).attr("attr-id");
						var search=jQuery('#searchtxt').val();
						
                        if (buttonId == "prev_data")
                        {
                            page--;
                        } else if (buttonId == "next_data")
                        {
                            page++;
                        }

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxUrl,
                            data: {"action": "get_next_prev_data",
                                offset: (page * ppp),
                                ppp: ppp,
                                csv: csv,
                                search: search,
                                //total_record:total_record,

                            },
                            beforeSend: function () {
                                //console.log('sending');
                                jQuery(".loader").css("display", "block");
                                jQuery(".foodslist").css("display", "none");
                                jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
                            },
                            error: function (ds, dd, ff) {
                                console.log(ff);
                            }
                        }).done(function (data) {
                            //alert(data);
                            
                            jQuery(".foodslist	").css("display", "block");
                            jQuery(".loader").css("display", "none");
                            jQuery('#importlistdata').html(data);

                            //jQuery('#cashback_data').tablePaginate({navigateType:'navigator',recordPerPage:10});

                        });
                    });
                    jQuery('#searchbtn').live('click',function(){
						//alert('1');
						var search=jQuery('#searchtxt').val();
						var csv = jQuery('#options_view').val();
						
						jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'POST',
                            data: "action=search_list_data&csv=" + csv + "&searchtxt="+search+"&nonce=<?php echo wp_create_nonce('search_list_data'); ?>",
                            beforeSend: function () {
                                //console.log('sending');
                               jQuery(".loader").css("display", "block");
                               jQuery('#importlistdata').css("display", "none");
                               jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
                            },
                            success: function (res) {
                                //console.log(res);
                                jQuery(".loader").css("display", "none");
                                jQuery('#importlistdata').css("display", "block");
                                jQuery('#importlistdata').html(res);
                            }
                        })
						//searchTable(jQuery(this).val());
					});
					
					jQuery('#searchtxt').keypress(function( event ) {
						if ( event.which == 13 ) {
							//event.preventDefault();
							var search=jQuery('#searchtxt').val();
						var csv = jQuery('#options_view').val();
						
						jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'POST',
                            data: "action=search_list_data&csv=" + csv + "&searchtxt="+search+"&nonce=<?php echo wp_create_nonce('search_list_data'); ?>",
                            beforeSend: function () {
                                //console.log('sending');
                               jQuery(".loader").css("display", "block");
                               jQuery('#importlistdata').css("display", "none");
                               jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
                            },
                            success: function (res) {
                                //console.log(res);
                                jQuery(".loader").css("display", "none");
                                jQuery('#importlistdata').css("display", "block");
                                jQuery('#importlistdata').html(res);
                            }
						  })
						}
					});
                   /* jQuery('#searchtxt').keyup(function() {
						
						var search=jQuery(this).val();
						var csv = jQuery('#options_view').val();
						//alert(csv);
						jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'POST',
                            data: "action=search_list_data&csv=" + csv + "&searchtxt="+search+"&nonce=<?php echo wp_create_nonce('search_list_data'); ?>",
                            beforeSend: function () {
                                //console.log('sending');
                               // jQuery(".loader").css("display", "block");
                                //jQuery('.loader').html('<img src="<?php echo get_template_directory_uri(); ?>/images/AjaxLoader.gif" class="imgstyle"/>');
                            },
                            success: function (res) {
                                //console.log(res);
                                jQuery('#importlistdata').html(res);
                            }
                        })
						//searchTable(jQuery(this).val());
					});
                })*/
              });
        </script>
        </br>
        <div class="loader"></div>
       
        
		<div class="form-group searchdiv" id="searching" style="display:none">
				<label for="searchtxt" class="col-md-3 control-label">Search Name in Table:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="searchtxt" name="searchtxt">
					
				</div>
				
				<input type="submit" name="submit" value="Search" class="button button-primary button-large view_buttons" id="searchbtn"/>
			</div>
		
		
        <div id="importlistdata"></div>
        <!--
                <div class="foodslist">

                </div>
        -->

        <?php
}

add_action('wp_ajax_meal_ingredient', 'meal_ingredient');

function meal_ingredient() {
        global $wpdb;
        if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'meal_ingredient')) {
                return;
        }
        $id = $_REQUEST['id'];
        $prefix = 'up_';
        $querystr = 'select mi.*,u.unit_symbol from ' . $prefix . 'meal_ingredients mi left join ' . $prefix . 'units u on u.id =mi.unit_id where mi.meal_id = ' . $id;
       // echo $querystr;
        $ingd = $wpdb->get_results($querystr, OBJECT);
        $content = '<table>';
        if (!empty($ingd)) {

                foreach ($ingd as $k => $i) {
                        $j = $k + 1;
                        $content .= '<tr>';
                        $content .= '<td>Ingredient ' . $j . '</td>';
                        $content .= '<td>Ingredient ' . $j . ' Quantity</td>';
                        $content .= '<td>Ingredient ' . $j . ' Unit</td>';
                        $content .= '<td>Ingredient ' . $j . ' Real Weight (g)</td>';
                        $content .= '<td>Ingredient ' . $j . ' Omittable</td>';
                        $content .= '<td>Ingredient ' . $j . ' Exchangeable With</td>';
                        $content .= '</tr>';
                        $content .= '<tr>';
                        $content .= '<td><a href="javascript:void(0)" class="ind_detail" id="'.$i->name.'">' . $i->name . '</a></td>';
                        $content .= '<td>' . $i->quantity . '</td>';
                        $content .= '<td>' . $i->unit_symbol . '</td>';
                        $content .= '<td>' . $i->weight . '</td>';
                        $content .= '<td>' . $i->is_ommitable . '</td>';
                        $content .= '<td>' . $i->exchangable_with_ingredient . '</td>';
                        $content .= '</tr>';
                }
        }
        $content .= '</table>';
        echo $content;
        exit;
}

add_action('wp_ajax_import_list_data', 'import_list_data');

function import_list_data() {



        if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'import_list_data')) {
                return;
        }

        /* $querystr = "
          SELECT $wpdb->foods.*
          FROM $wpdb->foods, $wpdb->food_micros, $wpdb->units
          WHERE $wpdb->foods.id = $wpdb->food_micros.$wpdb->food_id
          AND $wpdb->units.id = $wpdb->food_micros.$wpdb->unit_id
          ORDER BY $wpdb->foods.id ASC limit 5
          "; */
        global $wpdb;
        $prefix = 'up_';
        $csv = strtolower($_REQUEST['csv']);

        if (strtolower($csv) == 'food') {

                /*$str = "SELECT f.name,f.shopping_list_name, GROUP_CONCAT( fm.name ) AS micro , GROUP_CONCAT( CONCAT( fm.value, u.unit_symbol ) ) AS value,`cw_vegetarian`,`cw_vegan`,`cw_pescetarian`,`cw_paleo`,`cw_lactose_intolerance`,`cw_fructose_intolerance`,`cw_histamine_intolerance`,`cw_gluten_intolerance`,`cw_glutamat_intolerance`,`cw_sucrose_intolerance`,`cw_peanut_intolerance`,`cw_almond_intolerance`,`cw_hazelnut_intolerance`, `cw_walnut_intolerance`, `cw_cashew_intolerance`, `cw_pecan_nut_intolerance`, `cw_brazil_nut_intolerance`, `cw_pistachio_intolerance`, `cw_banana_intolerance`, `cw_avocade_intolerance`, `cw_apple_intolerance`, `cw_kiwi_fruit_intolerance`, `cw_melon_intolerance`, `cw_papaya_intolerance`, `cw_peach_intolerance`, `cw_strawberry_intolerance`,`is_snack`, `is_fatburner`, `price_level_id`, `synonym_of`,fc.name as fc_name , wslc.name as slist_cat,wpl.name as prize_level , f.source
FROM " . $prefix . "foods f
INNER JOIN " . $prefix . "food_micros fm ON f.id = fm.food_id
INNER JOIN " . $prefix . "units u ON fm.unit_id = u.id
Left join " . $prefix . "shopping_list_categories wslc on wslc.id= f.shopping_list_category_id
Left join " . $prefix . "price_levels wpl On wpl.id = f.price_level_id
LEFT JOIN " . $prefix . "food_categories fc ON f.food_category_id = fc.id
GROUP BY f.name";
                //echo $str;
                $wpdb->get_results($str, OBJECT);
                $total_record = $wpdb->num_rows;*/
                

                $querystr = "SELECT SQL_CALC_FOUND_ROWS f.name,f.shopping_list_name, GROUP_CONCAT( fm.name ) AS micro , GROUP_CONCAT( CONCAT( fm.value, u.unit_symbol ) ) AS value,`cw_vegetarian`,`cw_vegan`,`cw_pescetarian`,`cw_paleo`,`cw_lactose_intolerance`,`cw_fructose_intolerance`,`cw_histamine_intolerance`,`cw_gluten_intolerance`,`cw_glutamat_intolerance`,`cw_sucrose_intolerance`,`cw_peanut_intolerance`,`cw_almond_intolerance`,`cw_hazelnut_intolerance`, `cw_walnut_intolerance`, `cw_cashew_intolerance`, `cw_pecan_nut_intolerance`, `cw_brazil_nut_intolerance`, `cw_pistachio_intolerance`, `cw_banana_intolerance`, `cw_avocade_intolerance`, `cw_apple_intolerance`, `cw_kiwi_fruit_intolerance`, `cw_melon_intolerance`, `cw_papaya_intolerance`, `cw_peach_intolerance`, `cw_strawberry_intolerance`,`is_snack`, `is_fatburner`, `price_level_id`, `synonym_of`,fc.name as fc_name , wslc.name as slist_cat,wpl.name as prize_level , f.source,f.exchangeable_with
FROM " . $prefix . "foods f
INNER JOIN " . $prefix . "food_micros fm ON f.id = fm.food_id
INNER JOIN " . $prefix . "units u ON fm.unit_id = u.id
Left join " . $prefix . "shopping_list_categories wslc on wslc.id= f.shopping_list_category_id
Left join " . $prefix . "price_levels wpl On wpl.id = f.price_level_id
LEFT JOIN " . $prefix . "food_categories fc ON f.food_category_id = fc.id
GROUP BY f.name LIMIT 10";
              //  echo $querystr;
                
                $foods = $wpdb->get_results($querystr, OBJECT);
                $foodst = $wpdb->get_results('SELECT FOUND_ROWS() as records;',OBJECT);
                //print_r($foodst);
                $total_record = $foodst[0]->records;
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Name</th>';
                $content .= '<th>Shopping list name</th>';
                $content .= '<th>Compatible with vegetarian</th>';
                $content .= '<th>Compatible with vegan</th>';
                $content .= '<th>Compatible with pescetarian</th>';
                $content .= '<th>Compatible with paleo</th>';
                $content .= '<th>Compatible with lactose intolerance</th>';
                $content .= '<th>Compatible with fructose intolerance</th>';
                $content .= '<th>Compatible with histamine intolerance</th>';
                $content .= '<th>Compatible with gluten intolerance</th>';
                $content .= '<th>Compatible with glutamat intolerance</th>';
                $content .= '<th>Compatible with sucrose intolerance</th>';
                $content .= '<th>Compatible with peanut intolerance</th>';
                $content .= '<th>Compatible with almond intolerance</th>';
                $content .= '<th>Compatible with hazelnut intolerance</th>';
                $content .= '<th>Compatible with walnut intolerance</th>';
                $content .= '<th>Compatible with cashew intolerance</th>';
                $content .= '<th>Compatible with pecan nut intolerance</th>';
                $content .= '<th>Compatible with brazil nut intolerance</th>';
                $content .= '<th>Compatible with pistachio intolerance</th>';
                $content .= '<th>Compatible with banana intolerance</th>';
                $content .= '<th>Compatible with avocade intolerance</th>';
                $content .= '<th>Compatible with apple intolerance</th>';
                $content .= '<th>Compatible with kiwi intolerance</th>';
                $content .= '<th>Compatible with melon intolerance</th>';
                $content .= '<th>Compatible with papaya intolerance</th>';
                $content .= '<th>Compatible with peach intolerance</th>';
                $content .= '<th>Compatible with strawberry intolerance</th>';
                $content .= '<th>Is snack</th>';
                $content .= '<th>Is fatburner</th>';
                $content .= '<th>Price level</th>';
                $content .= '<th>synonym of</th>';
                $content .= '<th>exchangeable with</th>';
                $content .= '<th>Food category</th>';
                $content .= '<th>Food shopping list category</th>';
                $content .= '<th>Source</th><th>Action</th></tr>';

                foreach ($foods as $f) {
                        if(strlen($f->name)>0){
                        $content .= '<tr>';
                        $content .= '<td class="foodname">' . $f->name . '</td>';
                        $content .= '<td>' . $f->shopping_list_name . '</td>';
                        $content .= '<td>' . $f->cw_vegetarian . '</td>';
                        $content .= '<td>' . $f->cw_vegan . '</td>';
                        $content .= '<td>' . $f->cw_pescetarian . '</td>';
                        $content .= '<td>' . $f->cw_paleo . '</td>';
                        $content .= '<td>' . $f->cw_lactose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_fructose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_histamine_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_gluten_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_glutamat_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_sucrose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peanut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_almond_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_hazelnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_walnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_cashew_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pecan_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_brazil_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pistachio_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_banana_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_avocade_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_apple_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_kiwi_fruit_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_melon_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_papaya_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peach_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_strawberry_intolerance . '</td>';
                        $content .= '<td>' . $f->is_snack . '</td>';
                        $content .= '<td>' . $f->is_fatburner . '</td>';
                        $content .= '<td>' .$f->price_level_id.'</td>';
                        //$content .= '<td>' . $f->prize_level . '</td>';
                        $content .= '<td>' . $f->synonym_of . '</td>';
                        $content .= '<td>' . $f->exchangeable_with . '</td>';
                        $content .= '<td>' . $f->fc_name . '</td>';
                        $content .= '<td>' . $f->slist_cat . '</td>';
                        $content .= '<td>' . $f->source . '</td>';

                        $emicro = explode(',', $f->micro);
                        $emv = explode(',', $f->value);
                        
                        $content1 = '<table><tr>';
                        foreach ($emicro as $m) {

                                $content1 .= '<th>' . $m . '</th>';
                        }
                        $content1 .= '</tr>';
                        $content1 .= '<tr>';
                        foreach ($emv as $mv) {
                                $content1 .= '<td>' . $mv . '</td>';
                        }
                        $content1 .= '</tr></table>';

                        $content .= '<td><span class="view_micros">View</span><div class="view_micros_val" style="display:none;">
' . $content1 . '
</div></td>';
                        $content .= '</tr>';
                    }
                }
                $content .= '</table>';
                $content .= '</div>';
                if ($total_record > 10) {
                        $content.='<div id="pagnation">
							<button type="button" id="prev_data" class="btn btn-success pagination-btn" disabled attr-id="food"><</button>
							<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="food">></button>
					</div>';
                }

                echo $content;
                exit;
        } else if ($csv == 'food_cat') {

                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>No</th>';
                $content .= '<th>Name</th>';
                $content .= '<th>Created</th>';
                $content .= '<th>Modified</th>';
                $content .= '</tr>';

                $query = "SELECT * FROM " . $prefix . "food_categories";

                $result = $wpdb->get_results($query);
                foreach ($result as $key) {
                        $content .= '<tr><td>' . $key->id . '</td>';
                        $content .= '<td>' . $key->name . '</td>';
                        $content .= '<td>' . $key->created . '</td>';
                        $content .= '<td>' . $key->modified . '</td></tr>';
                }
                $content .= '</table>';
                $content .= '</div>';
                echo $content;
                exit;
        } else if ($csv == 'meal') {
               /* $str1 = "SELECT m . * , mi . instruction,mi.preparation_time,mi.other_time,mi.popularity,mi.source,mi.meal_category_ids , GROUP_CONCAT( mc.name ) as meal_cats
				FROM  " . $prefix . "meals m
				INNER JOIN " . $prefix . "meal_instructions mi ON mi.meal_id = m.id
				INNER JOIN " . $prefix . "meal_categories mc ON FIND_IN_SET( mc.id, mi.meal_category_ids ) 
				GROUP BY m.id";
                //echo $str;
                $wpdb->get_results($str1, OBJECT);
                $total_record = $wpdb->num_rows;*/


                $query = "SELECT SQL_CALC_FOUND_ROWS m . * , mi . instruction,mi.preparation_time,mi.other_time,mi.popularity,mi.source,mi.meal_category_ids , GROUP_CONCAT( mc.name ) as meal_cats
					FROM  " . $prefix . "meals m
					INNER JOIN " . $prefix . "meal_instructions mi ON mi.meal_id = m.id
					INNER JOIN " . $prefix . "meal_categories mc ON FIND_IN_SET( mc.id, mi.meal_category_ids ) 
					GROUP BY m.id LIMIT 10";
                //echo $query;
                $result = $wpdb->get_results($query);
                $foodst = $wpdb->get_results('SELECT FOUND_ROWS() as records;',OBJECT);
                //print_r($foodst);
                $total_record = $foodst[0]->records;
                
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
				$content .= '<th>Meal Id</th>';
                $content .= '<th>Name</th>';
              
                $content .= '<th>Other Name</th>';
                $content .= '<th>Instructions</th>';
                $content .= '<th>Preparation Time(In Minute)</th>';
                $content .= '<th>Other Time(In Minute)</th>';
                $content .= '<th>Popularity</th>';
                $content .= '<th>Source</th>';
                $content .= '<th>Meal Category</th>';
                $content .= '<th>Ingredient</th>';
                $content .= '</tr>';

                if (!empty($result)) {
                        foreach ($result as $key) {
								$content .= '<tr><td>' . $key->meal_id . '</td>';
                                $content .= '<td class="foodname">' . $key->name . '</td>';
                                $content .= '<td>' . $key->other_name . '</td>';
                                $content .= '<td>' . $key->instruction . '</td>';
                                $content .= '<td>' . $key->preparation_time . '</td>';
                                $content .= '<td>' . $key->other_time . '</td>';
                                $content .= '<td>' . $key->popularity . '</td>';
                                $content .= '<td style="word-break: break-word;">' . $key->source . '</td>';
                                $content .= '<td style="word-break: break-word;">' . $key->meal_cats . '</td>';
                                $content .= '<td><span class="view_meal_ingd" id="' . $key->id . '">View</span></td></tr>';
                        }
                }

                $content .= '</table>';
                $content .= '</div>';
                if ($total_record > 10) {
                        $content.='<div id="pagnation">
							<button type="button" id="prev_data" class="btn btn-success pagination-btn" disabled attr-id="meal"><</button>
							<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="meal">></button>
					</div>';
                }
                echo $content;
                exit;
        } else if ($csv == 'unit') {
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Full Name</th>';
                $content .= '<th>Symbol</th>';
                $content .= '<th>Unit:g</th>';
                $content .= '<th>Created</th>';
                $content .= '<th>Modified</th>';
                $content .= '</tr>';

                $query = "SELECT * FROM " . $prefix . "units";

                $result = $wpdb->get_results($query);
                //print_r($result);
                if (!empty($result)) {
                        foreach ($result as $key) {
                                $content .= '<tr><td>' . $key->name . '</td>';
                                $content .= '<td>' . $key->unit_symbol . '</td>';
                                $content .= '<td>' . str_replace('.', ',', $key->unit_gram) . '</td>';
                                $content .= '<td>' . $key->created . '</td>';
                                $content .= '<td>' . $key->modified . '</td></tr>';
                        }
                }

                $content .= '</table>';
                $content .= '</div>';
                echo $content;
                exit;
        } else if ($csv == 'splistcat') {

                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>No</th>';
                $content .= '<th>Name</th>';
                $content .= '<th>Created</th>';
                $content .= '<th>Modified</th>';
                $content .= '</tr>';
                $query = "SELECT * FROM " . $prefix . "shopping_list_categories";

                $result = $wpdb->get_results($query);
                foreach ($result as $key) {
                        $content .= '<tr><td>' . $key->id . '</td>';
                        $content .= '<td>' . $key->name . '</td>';
                        $content .= '<td>' . $key->created . '</td>';
                        $content .= '<td>' . $key->modified . '</td></tr>';
                }
                $content .= '</table>';
                $content .= '</div>';
                echo $content;
                exit;
        } else if ($csv == 'splistcat') {
                
        }



        //echo $querystr;
        //print_r($pageposts);
}

add_action('wp_ajax_get_next_prev_data', 'get_next_prev_data');

function get_next_prev_data() {

        global $wpdb;
        $prefix = 'up_';
        $offset = $_POST["offset"];
        $limit = $_POST["ppp"];
        $csv = $_POST["csv"];
        $search = $_POST["search"];
        if (strtolower($csv) == 'food') {
                
              $querystr = "SELECT SQL_CALC_FOUND_ROWS f.name,f.shopping_list_name, GROUP_CONCAT( fm.name ) AS micro , GROUP_CONCAT( CONCAT( fm.value, u.unit_symbol ) ) AS value,`cw_vegetarian`,`cw_vegan`,`cw_pescetarian`,`cw_paleo`,`cw_lactose_intolerance`,`cw_fructose_intolerance`,`cw_histamine_intolerance`,`cw_gluten_intolerance`,`cw_glutamat_intolerance`,`cw_sucrose_intolerance`,`cw_peanut_intolerance`,`cw_almond_intolerance`,`cw_hazelnut_intolerance`, `cw_walnut_intolerance`, `cw_cashew_intolerance`, `cw_pecan_nut_intolerance`, `cw_brazil_nut_intolerance`, `cw_pistachio_intolerance`, `cw_banana_intolerance`, `cw_avocade_intolerance`, `cw_apple_intolerance`, `cw_kiwi_fruit_intolerance`, `cw_melon_intolerance`, `cw_papaya_intolerance`, `cw_peach_intolerance`, `cw_strawberry_intolerance`,`is_snack`, `is_fatburner`, `price_level_id`, `synonym_of`,fc.name as fc_name , wslc.name as slist_cat,wpl.name as prize_level , f.source,f.exchangeable_with
				FROM " . $prefix . "foods f
				INNER JOIN " . $prefix . "food_micros fm ON f.id = fm.food_id
				INNER JOIN " . $prefix . "units u ON fm.unit_id = u.id
				Left join " . $prefix . "shopping_list_categories wslc on wslc.id= f.shopping_list_category_id
				Left join " . $prefix . "price_levels wpl On wpl.id = f.price_level_id
				LEFT JOIN " . $prefix . "food_categories fc ON f.food_category_id = fc.id
				where f.shopping_list_name like '".$search."%' or f.name like '".$search."%'
				GROUP BY f.name LIMIT " . $limit . " OFFSET " . $offset;
				//echo $querystr;
				$foods = $wpdb->get_results($querystr, OBJECT);
                //print_r($foods);
                $foodst = $wpdb->get_results('SELECT FOUND_ROWS() as records;',OBJECT);
                //print_r($foodst);
                $total_record = $foodst[0]->records;
                
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Name</th>';
                $content .= '<th>Shopping list name</th>';
                $content .= '<th>Compatible with vegetarian</th>';
                $content .= '<th>Compatible with vegan</th>';
                $content .= '<th>Compatible with pescetarian</th>';
                $content .= '<th>Compatible with paleo</th>';
                $content .= '<th>Compatible with lactose intolerance</th>';
                $content .= '<th>Compatible with fructose intolerance</th>';
                $content .= '<th>Compatible with histamine intolerance</th>';
                $content .= '<th>Compatible with gluten intolerance</th>';
                $content .= '<th>Compatible with glutamat intolerance</th>';
                $content .= '<th>Compatible with sucrose intolerance</th>';
                $content .= '<th>Compatible with peanut intolerance</th>';
                $content .= '<th>Compatible with almond intolerance</th>';
                $content .= '<th>Compatible with hazelnut intolerance</th>';
                $content .= '<th>Compatible with walnut intolerance</th>';
                $content .= '<th>Compatible with cashew intolerance</th>';
                $content .= '<th>Compatible with pecan nut intolerance</th>';
                $content .= '<th>Compatible with brazil nut intolerance</th>';
                $content .= '<th>Compatible with pistachio intolerance</th>';
                $content .= '<th>Compatible with banana intolerance</th>';
                $content .= '<th>Compatible with avocade intolerance</th>';
                $content .= '<th>Compatible with apple intolerance</th>';
                $content .= '<th>Compatible with kiwi intolerance</th>';
                $content .= '<th>Compatible with melon intolerance</th>';
                $content .= '<th>Compatible with papaya intolerance</th>';
                $content .= '<th>Compatible with peach intolerance</th>';
                $content .= '<th>Compatible with strawberry intolerance</th>';
                $content .= '<th>Is snack</th>';
                $content .= '<th>Is fatburner</th>';
                $content .= '<th>Price level</th>';
                $content .= '<th>synonym of</th>';
                $content .= '<th>exchangeable with</th>';
                $content .= '<th>Food category</th>';
                $content .= '<th>Food shopping list category</th>';
                $content .= '<th>Source</th><th>Action</th></tr>';

                foreach ($foods as $f) {
                        $content .= '<tr>';
                        $content .= '<td class="foodname">' . $f->name . '</td>';
                        $content .= '<td>' . $f->shopping_list_name . '</td>';
                        $content .= '<td>' . $f->cw_vegetarian . '</td>';
                        $content .= '<td>' . $f->cw_vegan . '</td>';
                        $content .= '<td>' . $f->cw_pescetarian . '</td>';
                        $content .= '<td>' . $f->cw_paleo . '</td>';
                        $content .= '<td>' . $f->cw_lactose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_fructose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_histamine_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_gluten_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_glutamat_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_sucrose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peanut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_almond_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_hazelnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_walnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_cashew_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pecan_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_brazil_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pistachio_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_banana_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_avocade_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_apple_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_kiwi_fruit_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_melon_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_papaya_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peach_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_strawberry_intolerance . '</td>';
                        $content .= '<td>' . $f->is_snack . '</td>';
                        $content .= '<td>' . $f->is_fatburner . '</td>';
                        $content .= '<td>' .$f->price_level_id.'</td>';
                        //$content .= '<td>' . $f->prize_level . '</td>';
                        $content .= '<td>' . $f->synonym_of . '</td>';
                        $content .= '<td>' . $f->exchangeable_with . '</td>';
                        $content .= '<td>' . $f->fc_name . '</td>';
                        $content .= '<td>' . $f->slist_cat . '</td>';
                        $content .= '<td>' . $f->source . '</td>';

                        $emicro = explode(',', $f->micro);
                        $emv = explode(',', $f->value);

                        $content1 = '<table><tr>';
                        foreach ($emicro as $m) {

                                $content1 .= '<th>' . $m . '</th>';
                        }
                        $content1 .= '</tr>';
                        $content1 .= '<tr>';
                        foreach ($emv as $mv) {
                                $content1 .= '<td>' . $mv . '</td>';
                        }
                        $content1 .= '</tr></table>';

                        $content .= '<td><span class="view_micros">View</span><div class="view_micros_val" style="display:none;">' . $content1 . '
							</div></td>';
                        $content .= '</tr>';
                }
                $content .= '</table>';
                $content .= '</div>';
                $content .= '<div id="pagnation">';
                if ($offset == 0) {
                        $content .= '<button type="button" id="prev_data" class="btn btn-success pagination-btn" disabled attr-id="food"><</button>';
                        $content .= '<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="food">></button>';
                } else if (($offset + 10) > $total_record) {
                        $content .= '<button type="button" id="prev_data" class="btn btn-success pagination-btn" attr-id="food"><</button>';
                        $content .= '<button type="button" id="next_data" class="btn btn-success pagination-btn" disabled attr-id="food">></button>';
                } else {
                        $content .= '<button type="button" id="prev_data" class="btn btn-success pagination-btn" attr-id="food" ><</button>';
                        $content .= '<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="food">></button>';
                }
                $content .= '</div>';
                echo $content;
                exit;
        } else if (strtolower($csv) == 'meal') {
              


                $query = "SELECT SQL_CALC_FOUND_ROWS m . * , mi . instruction,mi.preparation_time,mi.other_time,mi.popularity,mi.source,mi.meal_category_ids , GROUP_CONCAT( mc.name ) as meal_cats
					FROM  " . $prefix . "meals m
					INNER JOIN " . $prefix . "meal_instructions mi ON mi.meal_id = m.id
					INNER JOIN " . $prefix . "meal_categories mc ON FIND_IN_SET( mc.id, mi.meal_category_ids ) 
					where m.name like '".$search."%'
					GROUP BY m.id LIMIT " . $limit . " OFFSET " . $offset;

                $result = $wpdb->get_results($query);
                $foodst = $wpdb->get_results('SELECT FOUND_ROWS() as records;',OBJECT);
                //print_r($foodst);
                $total_record = $foodst[0]->records;
                
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Meal Id</th>';
                $content .= '<th>Name</th>';
                $content .= '<th>Other Name</th>';
                $content .= '<th>Instructions</th>';
                $content .= '<th>Preparation Time(In Minute)</th>';
                $content .= '<th>Other Time(In Minute)</th>';
                $content .= '<th>Popularity</th>';
                $content .= '<th>Source</th>';
                $content .= '<th>Meal Category</th>';
                $content .= '<th>Ingredient</th>';
                $content .= '</tr>';

                if (!empty($result)) {
                        foreach ($result as $key) {
								$content .= '<tr><td>' . $key->meal_id . '</td>';
                                $content .= '<td class="foodname">' . $key->name . '</td>';
                                $content .= '<td>' . $key->other_name . '</td>';
                                $content .= '<td>' . $key->instruction . '</td>';
                                $content .= '<td>' . $key->preparation_time . '</td>';
                                $content .= '<td>' . $key->other_time . '</td>';
                                $content .= '<td>' . $key->popularity . '</td>';
                                $content .= '<td style="word-break: break-word;">' . $key->source . '</td>';
                                $content .= '<td style="word-break: break-word;">' . $key->meal_cats . '</td>';
                                $content .= '<td><span class="view_meal_ingd" id="' . $key->id . '">View</span></td></tr>';
                        }
                }

                $content .= '</table>';
                $content .= '</div>';
                $content .= '<div id="pagnation">';

                if ($offset == 0) {
                        $content .= '<button type="button" id="prev_data" class="btn btn-success pagination-btn" disabled attr-id="meal"><</button>';
                        $content .= '<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="meal">></button>';
                } else if (($offset + 10) >= $total_record) {
                        $content .= '<button type="button" id="prev_data" class="btn btn-success pagination-btn" attr-id="meal"><</button>';
                        $content .= '<button type="button" id="next_data" class="btn btn-success pagination-btn" disabled attr-id="meal">></button>';
                } else {
                        $content .= '<button type="button" id="prev_data" class="btn btn-success pagination-btn" attr-id="meal" ><</button>';
                        $content .= '<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="meal">></button>';
                }
                $content .= '</div>';
                echo $content;
                exit;
        }
        //wp_die();
}


add_action('wp_ajax_search_list_data', 'search_list_data');

function search_list_data() {



        if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'search_list_data')) {
                return;
        }
		
        global $wpdb;
        $prefix = 'up_';
        $csv = strtolower($_REQUEST['csv']);
        $search = $_REQUEST['searchtxt'];
       
        if (strtolower($csv) == 'food') {
                

                $querystr = "SELECT SQL_CALC_FOUND_ROWS f.name,f.shopping_list_name, GROUP_CONCAT( fm.name ) AS micro , GROUP_CONCAT( CONCAT( fm.value, u.unit_symbol ) ) AS value,`cw_vegetarian`,`cw_vegan`,`cw_pescetarian`,`cw_paleo`,`cw_lactose_intolerance`,`cw_fructose_intolerance`,`cw_histamine_intolerance`,`cw_gluten_intolerance`,`cw_glutamat_intolerance`,`cw_sucrose_intolerance`,`cw_peanut_intolerance`,`cw_almond_intolerance`,`cw_hazelnut_intolerance`, `cw_walnut_intolerance`, `cw_cashew_intolerance`, `cw_pecan_nut_intolerance`, `cw_brazil_nut_intolerance`, `cw_pistachio_intolerance`, `cw_banana_intolerance`, `cw_avocade_intolerance`, `cw_apple_intolerance`, `cw_kiwi_fruit_intolerance`, `cw_melon_intolerance`, `cw_papaya_intolerance`, `cw_peach_intolerance`, `cw_strawberry_intolerance`,`is_snack`, `is_fatburner`, `price_level_id`, `synonym_of`,fc.name as fc_name , wslc.name as slist_cat,wpl.name as prize_level , f.source,f.exchangeable_with
FROM " . $prefix . "foods f
INNER JOIN " . $prefix . "food_micros fm ON f.id = fm.food_id
INNER JOIN " . $prefix . "units u ON fm.unit_id = u.id
Left join " . $prefix . "shopping_list_categories wslc on wslc.id= f.shopping_list_category_id
Left join " . $prefix . "price_levels wpl On wpl.id = f.price_level_id
LEFT JOIN " . $prefix . "food_categories fc ON f.food_category_id = fc.id
where f.shopping_list_name like '%".$search."%' or f.name like '%".$search."%'
GROUP BY f.name LIMIT 10
             ";
                //echo $querystr;
                
                $foods = $wpdb->get_results($querystr, OBJECT);
                $foodst = $wpdb->get_results('SELECT FOUND_ROWS() as records;',OBJECT);
                //print_r($foodst);
                $total_record = $foodst[0]->records;
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Name</th>';
                $content .= '<th>Shopping list name</th>';
                $content .= '<th>Compatible with vegetarian</th>';
                $content .= '<th>Compatible with vegan</th>';
                $content .= '<th>Compatible with pescetarian</th>';
                $content .= '<th>Compatible with paleo</th>';
                $content .= '<th>Compatible with lactose intolerance</th>';
                $content .= '<th>Compatible with fructose intolerance</th>';
                $content .= '<th>Compatible with histamine intolerance</th>';
                $content .= '<th>Compatible with gluten intolerance</th>';
                $content .= '<th>Compatible with glutamat intolerance</th>';
                $content .= '<th>Compatible with sucrose intolerance</th>';
                $content .= '<th>Compatible with peanut intolerance</th>';
                $content .= '<th>Compatible with almond intolerance</th>';
                $content .= '<th>Compatible with hazelnut intolerance</th>';
                $content .= '<th>Compatible with walnut intolerance</th>';
                $content .= '<th>Compatible with cashew intolerance</th>';
                $content .= '<th>Compatible with pecan nut intolerance</th>';
                $content .= '<th>Compatible with brazil nut intolerance</th>';
                $content .= '<th>Compatible with pistachio intolerance</th>';
                $content .= '<th>Compatible with banana intolerance</th>';
                $content .= '<th>Compatible with avocade intolerance</th>';
                $content .= '<th>Compatible with apple intolerance</th>';
                $content .= '<th>Compatible with kiwi intolerance</th>';
                $content .= '<th>Compatible with melon intolerance</th>';
                $content .= '<th>Compatible with papaya intolerance</th>';
                $content .= '<th>Compatible with peach intolerance</th>';
                $content .= '<th>Compatible with strawberry intolerance</th>';
                $content .= '<th>Is snack</th>';
                $content .= '<th>Is fatburner</th>';
                $content .= '<th>Price level</th>';
                $content .= '<th>synonym of</th>';
                $content .= '<th>exchangeable with</th>';
                $content .= '<th>Food category</th>';
                $content .= '<th>Food shopping list category</th>';
                $content .= '<th>Source</th><th>Action</th></tr>';

                foreach ($foods as $f) {
                        $content .= '<tr>';
                        $content .= '<td class="foodname">' . $f->name . '</td>';
                        $content .= '<td>' . $f->shopping_list_name . '</td>';
                        $content .= '<td>' . $f->cw_vegetarian . '</td>';
                        $content .= '<td>' . $f->cw_vegan . '</td>';
                        $content .= '<td>' . $f->cw_pescetarian . '</td>';
                        $content .= '<td>' . $f->cw_paleo . '</td>';
                        $content .= '<td>' . $f->cw_lactose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_fructose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_histamine_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_gluten_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_glutamat_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_sucrose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peanut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_almond_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_hazelnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_walnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_cashew_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pecan_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_brazil_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pistachio_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_banana_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_avocade_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_apple_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_kiwi_fruit_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_melon_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_papaya_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peach_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_strawberry_intolerance . '</td>';
                        $content .= '<td>' . $f->is_snack . '</td>';
                        $content .= '<td>' . $f->is_fatburner . '</td>';
                        $content .= '<td>' . $f->price_level_id.'</td>';
                        //$content .= '<td>' . $f->prize_level . '</td>';
                        $content .= '<td>' . $f->synonym_of . '</td>';
                        $content .= '<td>' . $f->exchangeable_with . '</td>';
                        $content .= '<td>' . $f->fc_name . '</td>';
                        $content .= '<td>' . $f->slist_cat . '</td>';
                        $content .= '<td>' . $f->source . '</td>';

                        $emicro = explode(',', $f->micro);
                        $emv = explode(',', $f->value);

                        $content1 = '<table><tr>';
                        foreach ($emicro as $m) {

                                $content1 .= '<th>' . $m . '</th>';
                        }
                        $content1 .= '</tr>';
                        $content1 .= '<tr>';
                        foreach ($emv as $mv) {
                                $content1 .= '<td>' . $mv . '</td>';
                        }
                        $content1 .= '</tr></table>';

                        $content .= '<td><span class="view_micros">View</span><div class="view_micros_val" style="display:none;">
' . $content1 . '
</div></td>';
                        $content .= '</tr>';
                }
                $content .= '</table>';
                $content .= '</div>';
                if ($total_record > 10) {
                        $content.='<div id="pagnation">
							<button type="button" id="prev_data" class="btn btn-success pagination-btn" disabled attr-id="food"><</button>
							<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="food">></button>
					</div>';
                }

                echo $content;
                exit;
        } else if ($csv == 'food_cat') {

                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>No</th>';
                $content .= '<th>Name</th>';
                $content .= '<th>Created</th>';
                $content .= '<th>Modified</th>';
                $content .= '</tr>';

                $query = "SELECT * FROM " . $prefix . "food_categories where name like '%".$search."%'";

                $result = $wpdb->get_results($query);
                foreach ($result as $key) {
                        $content .= '<tr><td>' . $key->id . '</td>';
                        $content .= '<td>' . $key->name . '</td>';
                        $content .= '<td>' . $key->created . '</td>';
                        $content .= '<td>' . $key->modified . '</td></tr>';
                }
                $content .= '</table>';
                $content .= '</div>';
                echo $content;
                exit;
        } else if ($csv == 'meal') {
               /* $str1 = "SELECT m . * , mi . instruction,mi.preparation_time,mi.other_time,mi.popularity,mi.source,mi.meal_category_ids , GROUP_CONCAT( mc.name ) as meal_cats
				FROM  " . $prefix . "meals m
				INNER JOIN " . $prefix . "meal_instructions mi ON mi.meal_id = m.id
				INNER JOIN " . $prefix . "meal_categories mc ON FIND_IN_SET( mc.id, mi.meal_category_ids ) 
				GROUP BY m.id";
                //echo $str;
                $wpdb->get_results($str1, OBJECT);
                $total_record = $wpdb->num_rows;*/


                $query = "SELECT SQL_CALC_FOUND_ROWS m . * , mi . instruction,mi.preparation_time,mi.other_time,mi.popularity,mi.source,mi.meal_category_ids , GROUP_CONCAT( mc.name ) as meal_cats
					FROM  " . $prefix . "meals m
					INNER JOIN " . $prefix . "meal_instructions mi ON mi.meal_id = m.id
					INNER JOIN " . $prefix . "meal_categories mc ON FIND_IN_SET( mc.id, mi.meal_category_ids ) 
					where m.name like '%".$search."%'
					GROUP BY m.id LIMIT 10";
                //echo $query;
                $result = $wpdb->get_results($query);
                $foodst = $wpdb->get_results('SELECT FOUND_ROWS() as records;',OBJECT);
                //print_r($foodst);
                $total_record = $foodst[0]->records;
                
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Meal Id</th>';
                $content .= '<th>Name</th>';
                $content .= '<th>Other Name</th>';
                $content .= '<th>Instructions</th>';
                $content .= '<th>Preparation Time(In Minute)</th>';
                $content .= '<th>Other Time(In Minute)</th>';
                $content .= '<th>Popularity</th>';
                $content .= '<th>Source</th>';
                $content .= '<th>Meal Category</th>';
                $content .= '<th>Ingredient</th>';
                $content .= '</tr>';

                if (!empty($result)) {
                        foreach ($result as $key) {
								$content .= '<tr><td>' . $key->meal_id . '</td>';
                                $content .= '<td class="foodname">' . $key->name . '</td>';
                                $content .= '<td>' . $key->other_name . '</td>';
                                $content .= '<td>' . $key->instruction . '</td>';
                                $content .= '<td>' . $key->preparation_time . '</td>';
                                $content .= '<td>' . $key->other_time . '</td>';
                                $content .= '<td>' . $key->popularity . '</td>';
                                $content .= '<td style="word-break: break-word;">' . $key->source . '</td>';
                                $content .= '<td style="word-break: break-word;">' . $key->meal_cats . '</td>';
                                $content .= '<td><span class="view_meal_ingd" id="' . $key->id . '">View</span></td></tr>';
                        }
                }

                $content .= '</table>';
                $content .= '</div>';
                if ($total_record > 10) {
                        $content.='<div id="pagnation">
							<button type="button" id="prev_data" class="btn btn-success pagination-btn" disabled attr-id="meal"><</button>
							<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="meal">></button>
					</div>';
                }
                echo $content;
                exit;
        } else if ($csv == 'unit') {
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Full Name</th>';
                $content .= '<th>Symbol</th>';
                $content .= '<th>Unit:g</th>';
                $content .= '<th>Created</th>';
                $content .= '<th>Modified</th>';
                $content .= '</tr>';

                $query = "SELECT * FROM " . $prefix . "units where name like '%".$search."%'";

                $result = $wpdb->get_results($query);
                //print_r($result);
                if (!empty($result)) {
                        foreach ($result as $key) {
                                $content .= '<tr><td>' . $key->name . '</td>';
                                $content .= '<td>' . $key->unit_symbol . '</td>';
                                $content .= '<td>' . str_replace('.', ',', $key->unit_gram) . '</td>';
                                $content .= '<td>' . $key->created . '</td>';
                                $content .= '<td>' . $key->modified . '</td></tr>';
                        }
                }

                $content .= '</table>';
                $content .= '</div>';
                echo $content;
                exit;
        } else if ($csv == 'splistcat') {

                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>No</th>';
                $content .= '<th>Name</th>';
                $content .= '<th>Created</th>';
                $content .= '<th>Modified</th>';
                $content .= '</tr>';
                $query = "SELECT * FROM " . $prefix . "shopping_list_categories where name like '%".$search."%'";

                $result = $wpdb->get_results($query);
                foreach ($result as $key) {
                        $content .= '<tr><td>' . $key->id . '</td>';
                        $content .= '<td>' . $key->name . '</td>';
                        $content .= '<td>' . $key->created . '</td>';
                        $content .= '<td>' . $key->modified . '</td></tr>';
                }
                $content .= '</table>';
                $content .= '</div>';
                echo $content;
                exit;
        } else if ($csv == 'splistcat') {
                
        }



        //echo $querystr;
        //print_r($pageposts);
}

add_action('wp_ajax_search_list_data1', 'search_list_data1');

function search_list_data1() {



        if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'search_list_data1')) {
                return;
        }
		
        global $wpdb;
        $prefix = 'up_';
        $csv = strtolower($_REQUEST['csv']);
        $search = $_REQUEST['searchtxt'];
       
        if (strtolower($csv) == 'food') {
                

                $querystr = "SELECT SQL_CALC_FOUND_ROWS f.name,f.shopping_list_name, GROUP_CONCAT( fm.name ) AS micro , GROUP_CONCAT( CONCAT( fm.value, u.unit_symbol ) ) AS value,`cw_vegetarian`,`cw_vegan`,`cw_pescetarian`,`cw_paleo`,`cw_lactose_intolerance`,`cw_fructose_intolerance`,`cw_histamine_intolerance`,`cw_gluten_intolerance`,`cw_glutamat_intolerance`,`cw_sucrose_intolerance`,`cw_peanut_intolerance`,`cw_almond_intolerance`,`cw_hazelnut_intolerance`, `cw_walnut_intolerance`, `cw_cashew_intolerance`, `cw_pecan_nut_intolerance`, `cw_brazil_nut_intolerance`, `cw_pistachio_intolerance`, `cw_banana_intolerance`, `cw_avocade_intolerance`, `cw_apple_intolerance`, `cw_kiwi_fruit_intolerance`, `cw_melon_intolerance`, `cw_papaya_intolerance`, `cw_peach_intolerance`, `cw_strawberry_intolerance`,`is_snack`, `is_fatburner`, `price_level_id`, `synonym_of`,fc.name as fc_name , wslc.name as slist_cat,wpl.name as prize_level , f.source,f.exchangeable_with
FROM " . $prefix . "foods f
INNER JOIN " . $prefix . "food_micros fm ON f.id = fm.food_id
INNER JOIN " . $prefix . "units u ON fm.unit_id = u.id
Left join " . $prefix . "shopping_list_categories wslc on wslc.id= f.shopping_list_category_id
Left join " . $prefix . "price_levels wpl On wpl.id = f.price_level_id
LEFT JOIN " . $prefix . "food_categories fc ON f.food_category_id = fc.id
where f.shopping_list_name like '".$search."' or f.name like '".$search."'
GROUP BY f.name LIMIT 10
             ";
                //echo $querystr;
                
                $foods = $wpdb->get_results($querystr, OBJECT);
                $foodst = $wpdb->get_results('SELECT FOUND_ROWS() as records;',OBJECT);
                //print_r($foodst);
                $total_record = $foodst[0]->records;
                $content = '<div class="foodslist">';
                $content .= '<table class="headlist"><tr>';
                $content .= '<th>Name</th>';
                $content .= '<th>Shopping list name</th>';
                $content .= '<th>Compatible with vegetarian</th>';
                $content .= '<th>Compatible with vegan</th>';
                $content .= '<th>Compatible with pescetarian</th>';
                $content .= '<th>Compatible with paleo</th>';
                $content .= '<th>Compatible with lactose intolerance</th>';
                $content .= '<th>Compatible with fructose intolerance</th>';
                $content .= '<th>Compatible with histamine intolerance</th>';
                $content .= '<th>Compatible with gluten intolerance</th>';
                $content .= '<th>Compatible with glutamat intolerance</th>';
                $content .= '<th>Compatible with sucrose intolerance</th>';
                $content .= '<th>Compatible with peanut intolerance</th>';
                $content .= '<th>Compatible with almond intolerance</th>';
                $content .= '<th>Compatible with hazelnut intolerance</th>';
                $content .= '<th>Compatible with walnut intolerance</th>';
                $content .= '<th>Compatible with cashew intolerance</th>';
                $content .= '<th>Compatible with pecan nut intolerance</th>';
                $content .= '<th>Compatible with brazil nut intolerance</th>';
                $content .= '<th>Compatible with pistachio intolerance</th>';
                $content .= '<th>Compatible with banana intolerance</th>';
                $content .= '<th>Compatible with avocade intolerance</th>';
                $content .= '<th>Compatible with apple intolerance</th>';
                $content .= '<th>Compatible with kiwi intolerance</th>';
                $content .= '<th>Compatible with melon intolerance</th>';
                $content .= '<th>Compatible with papaya intolerance</th>';
                $content .= '<th>Compatible with peach intolerance</th>';
                $content .= '<th>Compatible with strawberry intolerance</th>';
                $content .= '<th>Is snack</th>';
                $content .= '<th>Is fatburner</th>';
                $content .= '<th>Price level</th>';
                $content .= '<th>synonym of</th>';
                $content .= '<th>exchangeable with</th>';
                $content .= '<th>Food category</th>';
                $content .= '<th>Food shopping list category</th>';
                $content .= '<th>Source</th><th>Action</th></tr>';

                foreach ($foods as $f) {
                        if(strlen($f->name)>0 && !empty($f->name)){
                        $content .= '<tr>';
                        $content .= '<td class="foodname">' . $f->name . '</td>';
                        $content .= '<td>' . $f->shopping_list_name . '</td>';
                        $content .= '<td>' . $f->cw_vegetarian . '</td>';
                        $content .= '<td>' . $f->cw_vegan . '</td>';
                        $content .= '<td>' . $f->cw_pescetarian . '</td>';
                        $content .= '<td>' . $f->cw_paleo . '</td>';
                        $content .= '<td>' . $f->cw_lactose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_fructose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_histamine_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_gluten_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_glutamat_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_sucrose_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peanut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_almond_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_hazelnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_walnut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_cashew_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pecan_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_brazil_nut_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_pistachio_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_banana_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_avocade_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_apple_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_kiwi_fruit_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_melon_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_papaya_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_peach_intolerance . '</td>';
                        $content .= '<td>' . $f->cw_strawberry_intolerance . '</td>';
                        $content .= '<td>' . $f->is_snack . '</td>';
                        $content .= '<td>' . $f->is_fatburner . '</td>';
                        $content .= '<td>' . $f->price_level_id.'</td>';
                        //$content .= '<td>' . $f->prize_level . '</td>';
                        $content .= '<td>' . $f->synonym_of . '</td>';
                        $content .= '<td>' . $f->exchangeable_with . '</td>';
                        $content .= '<td>' . $f->fc_name . '</td>';
                        $content .= '<td>' . $f->slist_cat . '</td>';
                        $content .= '<td>' . $f->source . '</td>';

                        $emicro = explode(',', $f->micro);
                        $emv = explode(',', $f->value);

                        $content1 = '<table><tr>';
                        foreach ($emicro as $m) {

                                $content1 .= '<th>' . $m . '</th>';
                        }
                        $content1 .= '</tr>';
                        $content1 .= '<tr>';
                        foreach ($emv as $mv) {
                                $content1 .= '<td>' . $mv . '</td>';
                        }
                        $content1 .= '</tr></table>';

                        $content .= '<td><span class="view_micros">View</span><div class="view_micros_val" style="display:none;">
' . $content1 . '
</div></td>';
                        $content .= '</tr>';
                    }
                }
                $content .= '</table>';
                $content .= '</div>';
                if ($total_record > 10) {
                        $content.='<div id="pagnation">
							<button type="button" id="prev_data" class="btn btn-success pagination-btn" disabled attr-id="food"><</button>
							<button type="button" id="next_data" class="btn btn-success pagination-btn" attr-id="food">></button>
					</div>';
                }

                echo $content;
                exit;
        } 


        //echo $querystr;
        //print_r($pageposts);
}
