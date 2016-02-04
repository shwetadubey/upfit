<?php
/**
 * Plugin Name: Upfit Questionnaire Plugin
 * Description: Upfit Questionnaire Plugin
 * Author: Lanet
 * Author URI: http://lanetteam.com/
 * Version: 1.0
 */
//echo "http://".$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]."";

register_activation_hook(__FILE__, 'upq_activate');

register_deactivation_hook(__FILE__, 'upq_deactivate');

global $wpdb;
$prefix='up_';
function upq_activate()
{
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS `".$prefix."questionnaire` (
  `qid` bigint(20) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `cur_weight` float NOT NULL,
  `desired_weight` float NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `age` int(11) NOT NULL,
  `height` float NOT NULL,
  `daily_activity` float NOT NULL,
  `nutrition_type` text NOT NULL,
  `allergies` text NOT NULL,
  `nuts` text NOT NULL,
  `fruit` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
$sql1 = "ALTER TABLE `".$prefix."questionnaire`
ADD PRIMARY KEY (`id`);
";
$wpdb->query($sql1);
$sql2 = "ALTER TABLE `".$prefix."questionnaire`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
$wpdb->query($sql2);
}

function upq_deactivate()
{
   // global $wpdb;
   // $sql = "DROP TABLE `".$prefix."questionnaire`;";
    //$wpdb->query($sql);
}
class WC_Settings_Tab_Demo_d {

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
          add_filter( 'woocommerce_admin_reports',__CLASS__ .'::filter_woocommerce_admin_reports', 50 );
    }
    public static function filter_woocommerce_admin_reports( $reports ) 
    {
        $reports['questionnaire'] = array(
                    'title'  => __( 'Questionnaire', 'woocommerce' ),
                    'reports' => array(
                        "questionnaire_by_name" => array(
                            'title'       => __( 'Questionnaire by name', 'woocommerce' ),
                            'description' => '',
                            'hide_title'  => true,
                            'callback'    => array( __CLASS__, 'get_report' )
                        ),
                        "questionnaire_by_date" => array(
                            'title'       => __( 'Questionnaire by date', 'woocommerce' ),
                            'description' => '',
                            'hide_title'  => true,
                            'callback'    => array( __CLASS__, 'get_report' )
                        ),
                    )
                );
        return $reports;
    }
    
    public static function get_report($name)
    {
       /* global $wpdb;
     if($name=='questionnaire_by_date'){
       $sql="SELECT * FROM  `".$prefix."questionnaire` ORDER BY  `date_created` ";
        $res= $wpdb->get_results($sql);
        //print_r($res);
     }
     else{
        $sql="SELECT * FROM  `".$prefix."questionnaire` ORDER BY  `user_email` ";
        $res= $wpdb->get_results($sql);
       // print_r($res);
         
     }*/

	$current_site_id=get_current_blog_id();
    $sites=wp_get_sites();
    foreach($sites as $s){
		$site_id=$s['blog_id'];
		switch_to_blog($site_id);
		$orders[$site_id] = get_posts( array(
				'post_type'   => 'shop_order',
				'posts_per_page'=>-1,
				'order'=>'ASC',
				'orderby'=>'date'
			) 
		);
	}
	restore_current_blog();
	$site_orders=array();
		if(count($orders)>0){
			//$key='';
			foreach($orders as $key=>$item){
			
				foreach($item as $i){
					switch_to_blog($key);
					$order=new WC_Order($i->ID);
					$order_details=$order->get_items();
					$billing_email = get_post_meta($i->ID,'_billing_email',true);
					
					foreach($order_details as $or)	{
					//	print_r($or);
						$data=unserialize($or['wdm_user_custom_data']);
						if(count($data)>0 && !empty($data)){
							$data['user_email']=$billing_email;
							$data['order_id']=$i->ID;
							$data['site_name']=get_blog_details( $key )->blogname;
						}
						$site_orders[]=$data;
						
					}	
				}
				restore_current_blog();	
			}
		}	
	switch_to_blog($current_site_id);
	$res=array();
	foreach($site_orders as $or){
		if(isset($or['cur_weight']))
		{
			$res[]=$or;
		}
	}
	$res=json_decode(json_encode($res));
	
 ?>
	<link rel='stylesheet' href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" />
	 <div>
		<div id="poststuff" class="woocommerce-reports-wide">
		<button id="export_all">Export to CSV</button>
			<table class="wp-list-table widefat fixed striped stock" id="q_r_table">
				<thead>
					<tr>
						<th scope="col" id="q_email" class="manage-column column-primary">Site Name</th>
						<th scope="col" id="q_email" class="manage-column column-primary">Order ID</th>
						<th scope="col" id="q_email" class="manage-column column-primary">Email</th>
						<th scope="col" id="q_cur_weight" class="manage-column">Aktuelles Gewicht</th>
						<th scope="col" id="q_desired_weight" class="manage-column ">Wunschgewicht</th>
						<th scope="col" id="q_gender" class="manage-column ">Geschlecht</th>
						<th scope="col" id="q_age" class="manage-column ">Alter</th>  
						<th scope="col" id="Q_height" class="manage-column "><?php echo utf8_encode('Körpergröße'); ?></th>  
						<th scope="col" id="Q_daily_activity" class="manage-column "><?php echo utf8_encode('Aktivitätsniveau im Alltag'); ?></th>  
						<th scope="col" id="Q_nutrition_type" class="manage-column "><?php echo utf8_encode('Ernährungsweise'); ?></th>  
						<th scope="col" id="Q_allergies" class="manage-column "><?php echo utf8_encode('Allergien & Intoleranzen'); ?>
						</th>  
						<th scope="col" id="Q_allergies_nuts" class="manage-column "><?php echo utf8_encode('Nüsse'); ?></th>  
						<th scope="col" id="Q_fruit" class="manage-column "><?php echo utf8_encode('Früchte'); ?></th>  
						  <th scope="col" id="Q_exclude" class="manage-column "><?php echo utf8_encode('Auszuschliessende Lebensmittel'); ?></th>  
						<th scope="col" id="Q_sweet_tooth" class="manage-column "><?php echo utf8_encode('NASCHST/SNACKST DU GERN?'); ?></th>  
						<th scope="col" id="Q_is_time_to_cook" class="manage-column "><?php echo utf8_encode('DEINE ZEIT/LUST ZUM KOCHEN PRO TAG?'); ?></th>  
						<th scope="col" id="Q_where_food_buy" class="manage-column "><?php echo utf8_encode('WO KAUFST DU DEINE LEBENSMITTEL?'); ?></th>  
						<th scope="col" id="Q_most_buy" class="manage-column "><?php echo utf8_encode('WORAUF ACHTEST DU BEIM KAUF BESONDERS?'); ?></th>    
					</tr>
				</thead>
				<tbody id="the_q_list" data-wp-lists="list:stock">
			 
				  <?php 
					$nut_types=$nuts=$fruits=$allergies=array();
					$nut_types=array(
								'nospecial'=>'Keine besondere',
								'vegetarian'=>'Vegetarisch',
								'vegan'=>'Vegan',
								'pescetarian'=>'Pescetarian',
								'flexitarian'=>'Flexitarisch',
								'paleo'=>'Paleo'
								);
					$allergies=array(
								'lactose'=>'Laktose (Milchzucker)',
								'fructose'=>'Fruktose (Fruchtzucker)',
								'histamine'=>'Histamin',
								'gluten'=>'Gluten (Zöliakie)',
								'glutamat'=>'Glutamat',
								'sucrose'=>'Saccharose (Haushaltszucker)',
								);
					$nuts_ar=array(
								'hazelnut'=>'Haselnuss',
								'almond'=>'Mandel',
								'pecan_nut'=>'Pekannuss',
								'walnut'=>'Walnuss',
								'peanut'=>'Erdnuss',
								'cashew'=>'Cashewnuss',
								'brazil_nut'=>'Paranuss',
								'pistachio'=>'Pistazie',
								);
					$fruits_ar=array(
								'apple'=>'Apfel',
								'avocade'=>'Avocado',
								'banana'=>'Banane',
								'kiwi'=>'Kiwi',
								'papaya'=>'Papaya',
								'strawberry'=>'Erdbeere',
								'melon'=>'Melone',
								'peach'=>'Pfirsich',
								);
					if(count($res)>0){
							//print_r($res);
						foreach ($res as $val) {
							//echo '<pre>';
							//print_r($val);
							$ex=$val->exclude;
							$nu_t_new=$al_new=$nuts_new=$fruits_new=array();
							$nu_t=explode(',',$val->nutrition_type);
							$al=explode(',',$val->allergies);
							$nuts=explode(',',$val->nuts);
							$fruits=explode(',',$val->fruit);
							
							for($i=0;$i<count($nu_t);$i++){
								$nu_t_new[]=$nut_types[$nu_t[$i]];
							
							}
							for($i=0;$i<count($al);$i++){
								if(isset($al[$i])){
									$al_new[]=$allergies[$al[$i]];
								}
							}
							for($i=0;$i<count($nuts);$i++){
								if(isset($nuts[$i])){
									$nuts_new[]=$nuts_ar[$nuts[$i]];
								}
							}
							for($i=0;$i<count($fruits);$i++){
								if(isset($fruits[$i])){
									$fruits_new[]=$fruits_ar[$fruits[$i]];
								}
							}
							
							$nu_t_new1=implode(',',$nu_t_new);
							$al_new1=implode(',',$al_new);
							$nuts_new1=implode(',',$nuts_new);
							$fruits_new1=implode(',',$fruits_new);
						   ?>
							<tr >
							<td scope="col"  class="manage-column q_email column-primary"><?php echo $val->site_name ?></td>
							<td scope="col"  class="manage-column q_email column-primary"><?php echo $val->order_id ?></td>
							<td scope="col"  class="manage-column q_email column-primary"><?php echo $val->user_email ?></td>
							<td scope="col"  class="manage-column q_cur_weight"><?php echo $val->cur_weight ?></td>
							<td scope="col"  class="manage-column q_desired_weight"><?php echo $val->desired_weight ?></td>
							<td scope="col"  class="manage-column q_gender"><?php echo $val->gender ?></td>
							<td scope="col"  class="manage-column q_age"><?php echo $val->age ?></td>  
							<td scope="col"  class="manage-column Q_height"><?php echo $val->height ?></td>  
							<td scope="col"  class="manage-column Q_daily_activity"><?php echo $val->daily_activity ?></td>  
							<td scope="col"  class="manage-column Q_nutrition_type"><?php echo utf8_encode(str_replace(',',', ',$nu_t_new1)) ?></td>  
							<td scope="col"  class="manage-column Q_allergies"><?php echo utf8_encode(str_replace(',',', ',$al_new1)) ?></td>  
							<td scope="col"  class="manage-column Q_nuts"><?php echo utf8_encode(str_replace(',',', ',$nuts_new1))?></td>  
							<td scope="col"  class="manage-column Q_fruit"><?php echo utf8_encode(str_replace(',',', ',$fruits_new1)) ?></td> 
							<td scope="col"  class="manage-column Q_allergies"><?php echo utf8_encode(str_replace(',',', ',$val->exclude))  ?></td> 
							<td scope="col"  class="manage-column Q_date"><?php echo $val->sweet_tooth ?></td> 
							<td scope="col"  class="manage-column Q_date"><?php echo $val->is_time_to_cook ?></td> 
							<td scope="col"  class="manage-column Q_date"><?php echo $val->where_food_buy ?></td> 
							<td scope="col"  class="manage-column Q_date"><?php echo $val->most_buy ?></td> 
							</tr> 
							<?php 
						}
					  
					}
					else
					{
					?>
							<tr class="no-items"><td class="colspanchange" colspan="11">No Record found for Questionnaire</td></tr> 
					<?php
					}
				   ?>
				</tbody>
				<!--<tfoot>
					<tr>
						<th scope="col" id="q_email" class="manage-column column-primary">Email</th>
						<th scope="col" id="q_cur_weight" class="manage-column">Current Weight</th>
						<th scope="col" id="q_desired_weight" class="manage-column ">Desired weight</th>
						<th scope="col" id="q_gender" class="manage-column ">gender</th>
						<th scope="col" id="q_age" class="manage-column ">age</th>  
						<th scope="col" id="Q_height" class="manage-column ">height</th>  
						<th scope="col" id="Q_daily_activity" class="manage-column ">Daily Activity</th>  
						<th scope="col" id="Q_nutrition_type" class="manage-column ">nutrition type</th>  
						<th scope="col" id="Q_allergies" class="manage-column ">allergies</th>  
						<th scope="col" id="Q_allergies" class="manage-column ">nuts</th>  
						<th scope="col" id="Q_allergies" class="manage-column ">fruit</th>   
					</tr>
				</tfoot>-->
			</table>
		</div>
	</div>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
       var oTable= jQuery('#q_r_table').dataTable( {"scrollX": true}   );;
		function table2csv(oTable, exportmode, tableElm) {
        var csv = '';
        var headers = [];
        var rows = [];
         console.log(tableElm);
        // Get header names
        
        jQuery(tableElm+' thead').find('th').each(function() {
            var $th = jQuery(this);
            var text = jQuery.trim($th.text());
            // headers.push(header); // original code
            if(jQuery.trim(text)) headers.push(text); // actually datatables seems to copy my original headers so there ist an amount of TH cells which are empty
        });
        csv += headers.join(',') + "\n";
		
        // get table data
        if (exportmode == "full") { // total data
            var total = oTable.fnSettings().fnRecordsTotal();
            for(i = 0; i < total; i++) {
                var row = oTable.fnGetData(i);
                //row = strip_tags(row);
                rows.push(row);
            }
        } else { // visible rows only
            jQuery(tableElm+' tr:visible').each(function(index) {
                var row = oTable.fnGetData(this);
                row = strip_tags(row);
                rows.push(row);
            })
        }
        csv += rows.join("\n");
 
        // if a csv div is already open, delete it
        if(jQuery('.csv-data').length) jQuery('.csv-data').remove();
        // open a div with a download link
        jQuery('body').append('<div class="csv-data"><form class="frm" enctype="multipart/form-data" method="post" action="<?php echo site_url(); ?>/csvimp/"><textarea class="form" name="csv">'+csv+'</textarea><input type="submit" class="submit" value="Download as file" /></form></div>');
		jQuery('.frm').submit();
 
	}
	
	jQuery('#export_all').click(function(event) {
        event.preventDefault();
        table2csv(oTable, 'full', 'table#q_r_table');
    })
       /* var qrow=jQuery('#the_q_list tr');
        qrow.slice(0,5).show();
      jQuery('.toview').change(function(){
        console.log(jQuery(this).val());
        var tos=jQuery(this).val();
        if(tos==='All'){
            qrow.show();
        }else{
            qrow.hide();
            qrow.slice(0,tos).show();
        }
      });*/
     </script>
    <?php
    }
}

WC_Settings_Tab_Demo_d::init();
