<?php 
//Generic Files
include "../../../../model.php"; 
include "printFunction.php";
global $app_quot_img;

$quotation_id = $_GET['quotation_id'];

$sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Car Rental Quotation' and active_flag ='Active'"));

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from car_rental_quotation_master where quotation_id='$quotation_id'"));
$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));
$quotation_date = $sq_quotation['quotation_date'];
$yr = explode("-", $quotation_date);
$year =$yr[0];

if($sq_emp_info['first_name']==''){
  $emp_name = 'Admin';
}
else{
  $emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
}
?>

<section class="headerPanel main_block">
  <div class="headerImage">
    <img src="<?= $app_quot_img?>" class="img-responsive">
    <div class="headerImageOverLay"></div>
  </div>

<!-- header -->
<section class="print_header main_block side_pad mg_tp_30">
  <div class="col-md-4 no-pad">
    <div class="print_header_logo">
      <img src="<?= $admin_logo_url ?>" class="img-responsive mg_tp_10">
    </div>
  </div>
  <div class="col-md-4 no-pad text-center mg_tp_30">
    <span class="title"><i class="fa fa-pencil-square-o"></i> CAR RENTAL QUOTATION</span>
  </div>

<?php 
include "standard_header_html.php";
?>

  <!-- print-detail -->
  <section class="print_sec main_block side_pad">
    <div class="row">
      <div class="col-md-12">
        <div class="print_info_block">
          <ul class="main_block">
            <li class="col-md-3 mg_tp_10 mg_bt_10">
              <div class="print_quo_detail_block">
                <i class="fa fa-calendar" aria-hidden="true"></i><br>
                <span>QUOTATION DATE</span><br>
                <?= get_date_user($sq_quotation['quotation_date']) ?><br>
              </div>
            </li>
            <li class="col-md-3 mg_tp_10 mg_bt_10">
              <div class="print_quo_detail_block">
                <i class="fa fa-hourglass-half" aria-hidden="true"></i><br>
                <span>DURATION</span><br>
                 <?= $sq_quotation['days_of_traveling'] ?><br>
              </div>
            </li>
            <li class="col-md-3 mg_tp_10 mg_bt_10">
              <div class="print_quo_detail_block">
                <i class="fa fa-users" aria-hidden="true"></i><br>
                <span>TOTAL GUEST</span><br>
                <?= $sq_quotation['total_pax'] ?><br>
              </div>
            </li>
            <li class="col-md-3 mg_tp_10 mg_bt_10">
              <div class="print_quo_detail_block">
                <i class="fa fa-tags" aria-hidden="true"></i><br>
                <span>PRICE</span><br>
                <?= number_format($sq_quotation['total_tour_cost'],2) ?><br>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

</section>

    <!-- Package -->
    <section class="print_sec main_block side_pad mg_tp_30">
      <div class="section_heding">
        <h2>BOOKING DETAILS</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row mg_tp_30">
        <div class="col-md-12">
          <div class="print_info_block">
          <ul class="print_info_list">
            <li class="col-md-6 mg_tp_10 mg_bt_10"><span>ROUTE :</span> <?= $sq_quotation['route'] ?> </li>
            <li class="col-md-6 mg_tp_10 mg_bt_10"><span>CUSTOMER NAME :</span> <?= $sq_quotation['customer_name'] ?></li>
          </ul>
          <ul class="print_info_list">
            <li class="col-md-6 mg_tp_10 mg_bt_10"><span>QUOTATION ID :</span> <?= get_quotation_id($quotation_id,$year) ?></li>
            <li class="col-md-6 mg_tp_10 mg_bt_10"><span>E-MAIL ID :</span> <?= $sq_quotation['email_id'] ?></li>
            <?php if($sq_quotation['mobile_no'] != ''){?><li class="col-md-6 mg_tp_10 mg_bt_10"><span>MOBILE NO :</span> <?= $sq_quotation['mobile_no']?></li><?php } ?>
          </ul>
          <hr class="main_block">
            <ul class="main_block">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>FROM DATE : </span><?= get_datetime_user($sq_quotation['from_date']) ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>TO DATE : </span><?= get_datetime_user($sq_quotation['to_date']) ?></li>
            </ul>
            <ul class="main_block">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>VEHICLE TYPE: </span> <?= $sq_quotation['vehicle_type'] ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>TRIP TYPE : </span><?= $sq_quotation['trip_type'] ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Transport -->
    <section class="print_sec main_block side_pad mg_tp_30">
      <div class="section_heding">
        <h2>PACKAGE DETAILS</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
            <ul class="main_block no-pad">
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>VEHICLE NAME : </span><?= $sq_quotation['vehicle_name'] ?></li>
              <li class="col-md-8 mg_tp_10 mg_bt_10"><span>PLACES TO VISIT : </span><?= $sq_quotation['places_to_visit'] ?></li>
            </ul>
            <ul class="main_block no-pad">
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>DAILY KM : </span><?= $sq_quotation['daily_km'] ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>EXTRA KM COST : </span><?= $sq_quotation['extra_km_cost'] ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>EXTRA HR COST : </span><?= $sq_quotation['extra_hr_cost'] ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Costing -->
    <section class="print_sec main_block side_pad mg_tp_30">
      <div class="row">
        <div class="col-md-6">
          <div class="section_heding">
            <h2>COSTING</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_info_block">
            <ul class="main_block">
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>SUBTOTAL : </span><?= number_format($sq_quotation['subtotal']+ $sq_quotation['markup_cost_subtotal'],2) ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>TAX : </span><?= number_format($sq_quotation['service_tax_subtotal'],2) ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>PERMIT : </span><?= number_format($sq_quotation['permit'],2) ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>TOLL PARKING : </span><?= number_format($sq_quotation['toll_parking'],2) ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>DRIVER ALLOWANCE : </span><?= number_format($sq_quotation['driver_allowance'],2) ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>QUOTATION COST : <?= number_format($sq_quotation['total_tour_cost'],2) ?></span></li>
            </ul>
          </div>
        </div>

    <!-- Bank Detail -->
        <div class="col-md-6">
        <div class="section_heding">
          <h2>BANK DETAILS</h2>
          <div class="section_heding_img">
            <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
          </div>
        </div>
          <div class="print_info_block">
            <ul class="main_block">
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>BANK NAME : </span><?= $bank_name_setting ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>A/C NAME : </span><?= $acc_name ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>BRANCH : </span><?= $bank_branch_name ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>A/C NO : </span><?= $bank_acc_no ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>IFSC : </span><?= $bank_ifsc_code ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>Swift Code : </span><?= $bank_swift_code ?></li>
            </ul>
          </div>
      </div>
      </div>
    </section>

    <!-- Terms and Conditions -->
    <section class="print_sec main_block side_pad mg_tp_30">
    <?php if($sq_terms_cond['terms_and_conditions'] != ''){?>
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>Terms and Conditions</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
           <?= $sq_terms_cond['terms_and_conditions'] ?>
          </div>
        </div>
      </div>
      <?php } ?>
      <div class="row mg_tp_30">
        <div class="col-md-7"></div>
        <div class="col-md-5 mg_tp_30">
          <div class="print_quotation_creator text-center">
            <span>PREPARE BY </span><br><?= $emp_name?>
          </div>
        </div>
      </div>
    </section>

  </body>
</html>