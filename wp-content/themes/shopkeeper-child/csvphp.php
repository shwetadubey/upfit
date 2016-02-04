<?php
/**
 * Plugin Name: Upfit Questionnaire Plugin
 * Description: Upfit Questionnaire Plugin
 * Author: Lanet
 * Author URI: http://lanetteam.com/
 * Version: 1.0
 */

register_activation_hook(__FILE__, 'upq_activate');

register_deactivation_hook(__FILE__, 'upq_deactivate');

global $wpdb;
$prefix=$wpdb->prefix;
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
		$orders = get_posts( array(
        'post_type'   => 'shop_order') );
		$order=new WC_Order(1210);
		$order_details=$order->get_items();
		$ar=array();
		function mf($val){
			$order=new WC_Order($val->ID);
			$order_details=$order->get_items();
			$billing_email = get_post_meta($val->ID,'_billing_email',true);
			foreach($order_details as $or)	{
				$aa=unserialize($or['wdm_user_custom_data']);
				if(count($aa)>0){$aa['user_email']=$billing_email;}	
			}		
				return $aa;
		}
		$order_ar=array_map('mf',$orders);

		$res=array();
		foreach($order_ar as $or){
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
			<button id="export_all">Export</button
                <table class="wp-list-table widefat fixed striped stock" id="q_r_table">
                    <thead>
                        <tr>
                            <th scope="col" id="q_email" class="manage-column column-primary">Email</th>
                            <th scope="col" id="q_cur_weight" class="manage-column">Aktuelles Gewicht</th>
                            <th scope="col" id="q_desired_weight" class="manage-column ">Wunschgewicht</th>
                            <th scope="col" id="q_gender" class="manage-column ">Geschlecht</th>
                            <th scope="col" id="q_age" class="manage-column ">Alter</th>  
                            <th scope="col" id="Q_height" class="manage-column ">Körpergröße</th>  
                            <th scope="col" id="Q_daily_activity" class="manage-column ">Aktivitätsniveau im Alltag</th>  
                            <th scope="col" id="Q_nutrition_type" class="manage-column ">Ernährungsweise</th>  
                            <th scope="col" id="Q_allergies" class="manage-column ">Allergien & Intoleranzen</th>  
                            <th scope="col" id="Q_allergies" class="manage-column ">NÜSSE</th>  
                            <th scope="col" id="Q_fruit" class="manage-column ">FRÜCHTE</th>  
                            <th scope="col" id="Q_sweet_tooth" class="manage-column ">NASCHST/SNACKST DU GERN?</th>  
							<th scope="col" id="Q_is_time_to_cook" class="manage-column ">DEINE ZEIT/LUST ZUM KOCHEN PRO TAG?</th>  
							<th scope="col" id="Q_where_food_buy" class="manage-column ">WO KAUFST DU DEINE LEBENSMITTEL?</th>  
							<th scope="col" id="Q_most_buy" class="manage-column ">WORAUF ACHTEST DU BEIM KAUF BESONDERS?</th>    
                        </tr>
                    </thead>
                    <tbody id="the_q_list" data-wp-lists="list:stock">
                 
                      <?php  if(count($res)>0)
                        {
						echo '<pre>';
						print_r($res);
						echo '</pre>';
                            foreach ($res as $val) {
                               ?>
                                <tr >
                                <td scope="col"  class="manage-column q_email column-primary"><?php echo $val->user_email ?></td>
                                <td scope="col"  class="manage-column q_cur_weight"><?php echo $val->cur_weight ?></td>
                                <td scope="col"  class="manage-column q_desired_weight"><?php echo $val->desired_weight ?></td>
                                <td scope="col"  class="manage-column q_gender"><?php echo $val->gender ?></td>
                                <td scope="col"  class="manage-column q_age"><?php echo $val->age ?></td>  
                                <td scope="col"  class="manage-column Q_height"><?php echo $val->height ?></td>  
                                <td scope="col"  class="manage-column Q_daily_activity"><?php echo $val->daily_activity ?></td>  
                                <td scope="col"  class="manage-column Q_nutrition_type"><?php echo str_replace(',',', ',$val->nutrition_type) ?></td>  
                                <td scope="col"  class="manage-column Q_allergies"><?php echo str_replace(',',', ',$val->allergies) ?></td>  
                                <td scope="col"  class="manage-column Q_nuts"><?php echo str_replace(',',', ',$val->nuts)?></td>  
                                <td scope="col"  class="manage-column Q_fruit"><?php echo str_replace(',',', ',$val->fruit) ?></td> 
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
        jQuery('#q_r_table').dataTable( {"scrollX": true } );;
		/*function table2csv(oTable, exportmode, tableElm) {
        var csv = '';
        var headers = [];
        var rows = [];
		console.log(oTable);
		console.log(oTable.$);
		console.log(oTable.fnSettings);
		alert('here');
        // Get header names
        jQuery(tableElm+' thead').find('th').each(function() {
            var $th = $(this);
            var text = $th.text();
            var header = '"' + text + '"';
            // headers.push(header); // original code
            if(text != "") headers.push(header); // actually datatables seems to copy my original headers so there ist an amount of TH cells which are empty
        });
        csv += headers.join(',') + "\n";
 
        // get table data
        if (exportmode == "full") { // total data
            var total = oTable.fnSettings().fnRecordsTotal();
			alert(total);
            for(i = 0; i < total; i++) {
                var row = oTable.fnGetData(i);
                row = strip_tags(row);
                rows.push(row);
            }
        } else { // visible rows only
            jQuery(tableElm+' tbody tr:visible').each(function(index) {
                var row = oTable.fnGetData(this);
                row = strip_tags(row);
                rows.push(row);
            })
        }
        csv += rows.join("\n");
 
        // if a csv div is already open, delete it
        if(jQuery('.csv-data').length) jQuery('.csv-data').remove();
        // open a div with a download link
        jQuery('body').append('<div class="csv-data"><form enctype="multipart/form-data" method="post" action="/csv.php"><textarea class="form" name="csv">'+csv+'</textarea><input type="submit" class="submit" value="Download as file" /></form></div>');
 
	}
	
	jQuery('#export_all').click(function(event) {
        event.preventDefault();
        table2csv(oTable, 'full', 'table.display');
    })*/
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
