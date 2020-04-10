<?php
include "../../model/model.php";

$dest_id = $_POST['dest_id'];

$sq_packages1 = mysql_query("select * from custom_package_master where dest_id='$dest_id' and status='Active'");
while($sq_packages = mysql_fetch_assoc($sq_packages1))
{
	$sq_hotel = mysql_fetch_assoc(mysql_query("select * from custom_package_hotels where package_id='$sq_packages[package_id]'"));
	$sq_hname = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$sq_hotel[hotel_name]'"));
	$sq_program = mysql_query("select * from custom_package_program where package_id='$sq_packages[package_id]'"); 	
  $package_id = $sq_packages['package_id'];
  //$url1 = BASE_URL."model/app_settings/print_html/quotation_html/b2b_package_html.php?package_id=$package_id";
  if($app_quot_format == 2){
    $url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_2/b2b_package_html.php?package_id=$package_id";
  }
  else if($app_quot_format == 3){
    $url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_3/b2b_package_html.php?package_id=$package_id";
  }
  else if($app_quot_format == 4){
    $url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_4/b2b_package_html.php?package_id=$package_id";
  }
  else if($app_quot_format == 5){
    $url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_5/b2b_package_html.php?package_id=$package_id";
  }
  else if($app_quot_format == 6){
    $url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_6/b2b_package_html.php?package_id=$package_id";
  }
  else{
    $url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_1/b2b_package_html.php?package_id=$package_id";
  }
  $sq_dest = mysql_fetch_assoc(mysql_query("select * from custom_package_images where package_id='$sq_packages[package_id]'"));
  $url = $sq_dest['image_url'];
  $pos = strstr($url,'uploads');
  if ($pos != false)   {
      $newUrl1 = preg_replace('/(\/+)/','/',$sq_dest['image_url']); 
      $newUrl = BASE_URL.str_replace('../', '', $newUrl1);
  }
  else{
      $newUrl =  $sq_dest['image_url']; 
  }
  
?>	
<div class="panel panel-default panel-body main_block mg_bt_30">
          <div class="single_b2b_package main_block">
             <div class="row">
               <div class="col-md-4 col-sm-12 col-xs-12 mg_bt_20_xs">
                 <div class="b2b_pkg_img">
                   <img src="<?php echo $newUrl; ?>" class="img-responsive">
                 </div>
               </div>
               <div class="col-md-8 col-sm-12 col-xs-12 no-pad">
                 <div class="b2b_pkg_detail main_block">
                  <div class="col-sm-7 col-xs-12 mg_bt_20_xs">
                    <h3 class="b2b_pkg_title"><?= $sq_packages['package_name'] ?></h3>
                     <div class="col-sm-5 no-pad mg_bt_20_sm_xs">
                       <p class="b2b_pkg_code"><?php echo '('.$sq_packages['package_code'].')'; ?></p>
                     </div>
                     
                  </div>
                  <div class="col-sm-5 col-xs-12 text-right mg_bt_20_xs">
                     <p class="b2b_pkg_duration"><i class="fa fa-sun-o"></i> <?= $sq_packages['total_days'] ?> Days <span class="duration_seprator">|</span><i class="fa fa-moon-o"></i> <?= $sq_packages['total_nights'] ?> Nights </p>
                  </div>
                  <div class="col-xs-12">
                    <div class="b2b_pkg_text_detail">
                      <p class="b2b_hotel_detail"><strong>Hotel            : </strong> <em class="b2b_hotel_category"><?= $sq_hname['hotel_name'] ?><?= '-'.$sq_hotel['hotel_type'] ?></em>
                      <span>  (Per Person On Twin Sharing)</span></p>
                      <p class="b2b_sightseeing"><strong>Sightseeing : </strong> <em class="sightseeing_content"> <?php while($row_program=mysql_fetch_assoc($sq_program)){ ?><?= $row_program['attraction'].', ' ?> <?php } ?></em></p>
                     
                    </div>
                  </div>
                 </div>
               </div>
             </div> 
             <div class="row mg_tp_20">
               <div class="col-sm-6 col-xs-12 mg_bt_20_xs">
                 <div class="b2b_pkg_btns">
                   <ul class="no-pad no-marg">
                     <li><button class="btn btn-sm btn-success ico_left" onclick="view_modal(<?= $sq_packages['package_id'] ?>)">&nbsp;&nbsp;View<i class="fa fa-eye"></i></button></li>
                     <li><a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm ico_left"><i class="fa fa-print"></i>&nbsp;&nbsp;Print</a></li>
                   </ul>
                 </div>
                 </div>
                <div class="col-md-6 col-xs-12 text-right">
                  <button class="btn btn-success btn-sm" onclick="send_quotation()"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send Quotation</button>
                </div>
             </div>
          </div>    
        </div>
<?php } ?>        