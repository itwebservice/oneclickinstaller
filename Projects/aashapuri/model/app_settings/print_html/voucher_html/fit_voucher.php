<?php 
//Generic Files
include "../../../model.php"; 
include "../print_functions.php";

$hotel_accomodation_id = $_GET['hotel_accomodation_id'];
$sq_service_voucher = mysql_fetch_assoc( mysql_query("select * from package_tour_hotel_service_voucher1 where hotel_accomodation_id='$hotel_accomodation_id'"));

$sq_accomodation1 = mysql_query("select * from package_hotel_accomodation_master where booking_id='$hotel_accomodation_id'") ;
while($sq_accomodation = mysql_fetch_assoc( $sq_accomodation1))
{ 
$hotel_id = $sq_accomodation['hotel_id'];

$sq_hotel = mysql_fetch_assoc( mysql_query("select * from hotel_master where hotel_id='$hotel_id'") );

$booking_id = $sq_accomodation['booking_id'];
$sq_booking = mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$booking_id'"));
$sq_traveler = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
$name = $sq_traveler['first_name'].' '.$sq_traveler['last_name'];

//Total days
$total_days1=strtotime($sq_accomodation['to_date']) - strtotime($sq_accomodation['from_date']);
$total_days = round($total_days1 / 86400);

$total_pax = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$booking_id' and status='Active'"));

$adults = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$booking_id' and status='Active' and adolescence='Adult'"));

$children = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$booking_id' and status='Active' and adolescence='Children'"));

$infants = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$booking_id' and status='Active' and adolescence='Infant'"));

$sq_package_program = mysql_query("select * from package_quotation_program where quotation_id ='$sq_booking[quotation_id]'");

$emp_id = $_SESSION['emp_id'];
$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id'"));
if($emp_id == '0'){ $emp_name = 'Admin';}
else { $emp_name = $sq_emp['first_name'].' ' .$sq_emp['last_name']; }
?>
    <div class="repeat_section main_block">
    
    <!-- header -->
    <section class="print_header main_block">
      <div class="col-md-6 no-pad">
      <span class="title"><i class="fa fa-file-text"></i> HOTEL SERVICE VOUCHER</span>
        <div class="print_header_logo">
          <img src="<?= $admin_logo_url ?>" class="img-responsive mg_tp_10">
        </div>
      </div>
      <div class="col-md-6 no-pad">
        <div class="print_header_contact text-right">
          <span class="title"><?php echo $sq_hotel['hotel_name']; ?></span><br>
          <p><?php echo $sq_hotel['hotel_address']; ?></p>
          <p class="no-marg"><i class="fa fa-phone" style="margin-right: 5px;"></i> <?php echo $sq_hotel['mobile_no']; ?></p>
          <p><i class="fa fa-envelope" style="margin-right: 5px;"></i> <?php echo $sq_hotel['email_id']; ?></p>
        </div>
      </div>
    </section>

    <!-- print-detail -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
            <ul class="main_block noType">
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-hourglass-half" aria-hidden="true"></i><br>
                  <span>DURATION</span><br>
                  <?= ($total_days).'N/'.($total_days+1).'D' ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-users" aria-hidden="true"></i><br>
                  <span>TOTAL GUEST</span><br>
                  <?= $total_pax ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-home" aria-hidden="true"></i><br>
                  <span>TOTAL ROOM</span><br>
                  <?= $sq_accomodation['rooms'] ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-university" aria-hidden="true"></i><br>
                  <span>ROOM CATEGORY</span><br>
                  <?= $sq_accomodation['catagory'] ?><br>
                </div>
              </li>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- BOOKING -->
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>BOOKING DETAILS</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mg_bt_20">
          <ul class="print_info_list no-pad noType">
            <li><span>CONFIRMATION ID :</span> <?= $sq_accomodation['confirmation_no'] ?></li>
          </ul>
        </div>
        <div class="col-md-6 mg_bt_20">
          <ul class="print_info_list no-pad noType">
            <li><span>CUSTOMER NAME :</span> <?= $name ?></li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
            <ul class="main_block noType">
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>ADULT : </span><?= $adults ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>CHILDREN : </span><?= $children ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>INFANT : </span><?= $infants ?></li>
            </ul>
            <ul class="main_block noType">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>CHECK-IN : </span><?= get_datetime_user($sq_accomodation['from_date']) ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>CHECK-OUT : </span><?= get_datetime_user($sq_accomodation['to_date']) ?></li>
            </ul>
            <ul class="main_block noType">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>MEAL PLAN : </span><?= $sq_accomodation['meal_plan'] ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>CONTACT : </span><?= $sq_hotel['immergency_contact_no'] ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    
    <!-- Terms and Conditions -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>Terms and Conditions</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
            <?php 
            $sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Package Service Voucher' and active_flag ='Active'"));
            echo $sq_terms_cond['terms_and_conditions'];   ?> 
          </div>
        </div>
      </div>
    </section>
    
    <!-- ID Proof -->
    <?php     
    $sq_traveler_id = mysql_fetch_assoc(mysql_query("select * from package_travelers_details where booking_id='$hotel_accomodation_id'"));  
    $id_proof_image = $sq_traveler_id['id_proof_url'];
    if($id_proof_image != ''){
      $newUrl = preg_replace('/(\/+)/','/',$id_proof_image);
      $newUrl = explode('uploads', $newUrl);
      $newUrl = BASE_URL.'uploads'.$newUrl[1];
    ?>
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>ID PROOF</h2>
            <div class="section_heding_img">
              <img src="<?= $newUrl ?>" class="img-responsive">
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php } ?>
    

    <p style="float: left;width: 100%;">Note: Please present this confirmation to service provider (Hotel/Transport) upon arrival</p>

    <!-- Payment Detail -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-7"></div>
        <div class="col-md-5">
          <div class="print_quotation_creator text-center">
            <span>Generated BY </span><br><?= $emp_name ?>
          </div>
        </div>
      </div>
    </section>
    </div>
<?php } ?>
  </body>
</html>