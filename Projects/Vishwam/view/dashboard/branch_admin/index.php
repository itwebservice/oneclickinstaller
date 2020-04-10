<?php 
$login_id = $_SESSION['login_id'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = 'yes';
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='attractions_offers_enquiry/enquiry/index.php'"));
$branch_status1 = $sq['branch_status'];

//**Enquiries
$q1 = "select enquiry_id from enquiry_master where financial_year_id='$financial_year_id' and status!='Disabled'";
if($branch_status1 == 'yes'){
$q1 .= " and branch_admin_id='$branch_admin_id'";
}
$assigned_enq_count = mysql_num_rows(mysql_query($q1));
$converted_count = 0;
$closed_count = 0;
$followup_count = 0;
$infollowup_count = 0;

$q2 = "select enquiry_id from enquiry_master where financial_year_id='$financial_year_id' and status!='Disabled'";
if($branch_status1 == 'yes'){
$q2 .= " and branch_admin_id='$branch_admin_id'";
}
$sq_enquiry = mysql_query($q2);
	while($row_enq = mysql_fetch_assoc($sq_enquiry)){
		$sq_enquiry_entry = mysql_fetch_assoc(mysql_query("select followup_status from enquiry_master_entries where entry_id=(select max(entry_id) as entry_id from enquiry_master_entries where enquiry_id='$row_enq[enquiry_id]')"));
		if($sq_enquiry_entry['followup_status']=="Dropped"){
			$closed_count++;
		}
		if($sq_enquiry_entry['followup_status']=="Converted"){
			$converted_count++;
		}
		if($sq_enquiry_entry['followup_status']=="Active"){
			$followup_count++;
		}
		if($sq_enquiry_entry['followup_status']=="In-Followup"){
			$infollowup_count++;
		}
	}


?>
<div class="app_panel"> 
<div class="dashboard_panel panel-body">
  <input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status1 ?>" >
	<div class="dashboard_enqury_widget_panel main_block mg_bt_25">
            <div class="row">
                <div class="col-sm-3 col-xs-6" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                  <div class="single_enquiry_widget main_block blue_enquiry_widget mg_bt_10_sm_xs">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-cubes"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $assigned_enq_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount"> 
                      Total Enquiries 
                    </div>
                  </div>
                </div>
                <div class="col-sm-2 col-xs-6" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                  <div class="single_enquiry_widget main_block yellow_enquiry_widget mg_bt_10_sm_xs">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-folder-o"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $followup_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                      Active
                    </div>
                  </div>
                </div>
                <div class="col-sm-2 col-xs-6">
                  <div class="single_enquiry_widget main_block gray_enquiry_widget mg_bt_10_sm_xs" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');" >
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-folder-open-o"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $infollowup_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                    In-Followup 
                    </div>
                  </div>
                </div>
                <div class="col-sm-2 col-xs-6" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                  <div class="single_enquiry_widget main_block green_enquiry_widget">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-check-square-o"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $converted_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                      Converted
                    </div>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                  <div class="single_enquiry_widget main_block red_enquiry_widget">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-trash-o"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $closed_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                      Dropped Enquiries
                    </div>
                  </div>
                </div>
            </div>
    </div>


    <div id="history_data"></div>
    <!-- dashboard_tab -->

          <div class="row">
            <div class="col-md-12">
              <div class="dashboard_tab text-center main_block">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs responsive" role="tablist">
                  <li role="presentation" class="active"><a href="#oncoming_tab" aria-controls="oncoming_tab" role="tab" data-toggle="tab">Ongoing Tours</a></li>
                  <li role="presentation"><a href="#upcoming_tab" aria-controls="upcoming_tab" role="tab" data-toggle="tab">Upcoming Tours</a></li>
                  <li role="presentation"><a href="#fit_tab" aria-controls="fit_tab" role="tab" data-toggle="tab">Package Tours</a></li>
                  <li role="presentation"><a href="#git_tab" aria-controls="git_tab" role="tab" data-toggle="tab">Group Tours</a></li>
                  <li role="presentation"><a href="#enquiry_tab" aria-controls="enquiry_tab" role="tab" data-toggle="tab">Followups</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content responsive main_block">
                <!-- Ongoing FIT Tours -->
                  <div role="tabpanel" class="tab-pane active" id="oncoming_tab">
                  <?php 
                  $count = 1;
                  $today = date('Y-m-d');                 
                  ?>
                  <div class="dashboard_table dashboard_table_panel main_block">
                    <div class="row text-left">
                      <div class="col-md-12">
                        <div class="dashboard_table_heading main_block">
                          <div class="col-md-10 no-pad">
                            <h3>Package Tours</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="dashboard_table_body main_block">
                          <div class="col-md-12 no-pad table_verflow"> 
                            <div class="table-responsive">
                              <table class="table table-hover" style="margin: 0 !important;border: 0;">
                                <thead>
                                  <tr class="table-heading-row">
                                    <th>S_No.</th>
                                    <th>Tour_Name</th>
                                    <th>Tour_Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th>Customer_Name</th>
                                    <th>Mobile</th>
                                    <th>Booked By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
                    <?php
                    $query1 = "select * from package_tour_booking_master where tour_status!='Disabled' and financial_year_id='$financial_year_id' and tour_from_date <= '$today' and tour_to_date >= '$today'";
                            
                          $sq_query = mysql_query($query1);
                          while($row_query=mysql_fetch_assoc($sq_query))
                          {
                            $sq_cancel_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]' and status='Cancel'"));
                            $sq_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]'"));
                            if($sq_cancel_count != $sq_count){
                              $sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
                              $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
                    ?>
                                    <tr>
                                      <td><?php echo $count++; ?></td>
                                      <td><?php echo $row_query['tour_name']; ?></td>
                                      <td><?= get_date_user($row_query['tour_from_date']).' To '.get_date_user($row_query['tour_to_date']); ?></td>
                                      <td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
                                      <td><?php echo $row_query['mobile_no']; ?></td>
                                      <td><?= ($row_query['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
                                    </tr>
                                  <?php 
                                 } }
                                     ?>
                                </tbody>
                              </table>
                            </div> 
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  </div>
                <!-- Ongoing FIT Tour summary End -->

                  <!-- Upcoming FIT Tours -->
                  <div role="tabpanel" class="tab-pane" id="upcoming_tab">
                    <?php 
                      $count = 1;
                      $today = date('Y-m-d-h-i-s');
                      $add7days = date('Y-m-d-h-i-s', strtotime('+7 days'));
                        
                      ?>
                      <div class="dashboard_table dashboard_table_panel main_block">
                        <div class="row text-left">
                          <div class="col-md-12">
                            <div class="dashboard_table_heading main_block">
                              <div class="col-md-10 no-pad">
                                <h3>Package Tours</h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="dashboard_table_body main_block">
                              <div class="col-md-12 no-pad table_verflow"> 
                                <div class="table-responsive">
                                  <table class="table table-hover" style="margin: 0 !important;border: 0;">
                                    <thead>
                                      <tr class="table-heading-row">
                                        <th>S_No.</th>
                                        <th>Tour_Name</th>
                                        <th>Tour_Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th>Customer_Name</th>
                                        <th>Mobile</th>
                                        <th>Booked By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = "select * from package_tour_booking_master where tour_status!='Disabled' and financial_year_id='$financial_year_id' and tour_from_date between '$today' and '$add7days'";
                                            
                                    $sq_query = mysql_query($query);

                                    while($row_query=mysql_fetch_assoc($sq_query)){
                                        $sq_cancel_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]' and status='Cancel'"));
                                        $sq_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]'"));
                                        if($sq_cancel_count != $sq_count){
                                          $sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
                                          $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
                                    ?>
                                        <tr class="<?= $bg ?>">
                                          <td><?php echo $count++; ?></td>
                                          <td><?php echo $row_query['tour_name']; ?></td>
                                          <td><?= date('d-m-Y', strtotime($row_query['tour_from_date'])).' To '.get_date_user($row_query['tour_to_date']) ?></td>
                                          <td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
                                          <td><?php echo $row_query['mobile_no']; ?></td>
                                          <td><?php echo $sq_emp['first_name'].' '.$sq_emp['last_name'];?></td>
                                        </tr>
                                      <?php 
                                     } }
                                         ?>
                                    </tbody>
                                  </table>
                                </div> 
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- Upcoming FIT Tour summary End -->
                  <!--  FIT Summary -->
                  <div role="tabpanel" class="tab-pane" id="fit_tab">
                      <?php 
                      $count = 0; $bg=''; 
                      $query = mysql_fetch_assoc(mysql_query("select max(booking_id) as booking_id from package_tour_booking_master"));
                      $sq_package = mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$query[booking_id]'"));
                      $sq_entry = mysql_query("select * from package_travelers_details where booking_id='$query[booking_id]'");
                      ?> 
                      <div class="dashboard_table dashboard_table_panel main_block mg_bt_25">
                        <div class="row text-left">
                            <div class="">
                              <div class="dashboard_table_heading main_block">
                                <div class="col-md-2">
                                  <h3>Package Tours</h3>
                                </div>
                                <div class="col-md-3 col-sm-4 col-md-push-7">
                                  <select style="border-color: #009898; width: 100%;" id="package_booking_id" onchange="package_list_reflect(this.id)">
                                    <?php get_package_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id); ?>                     
                                  </select>
                                </div>
                                <div id="package_div_list">
                                </div>
                              </div>
                            </div>
                            
                        </div>
                      </div>
                  </div>
                 <!--  FIT Summary End -->
                 <!--  GIT Summary -->
                  <div role="tabpanel" class="tab-pane" id="git_tab">
                      <?php 
                        $count = 0; $bg=''; 
                        $query = mysql_fetch_assoc(mysql_query("select max(id) as booking_id from tourwise_traveler_details"));
                        $sq_package = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$query[booking_id]'"));
                        $sq_tour_name = mysql_fetch_assoc(mysql_query("select  * from tour_master where tour_id = '$sq_package[tour_id]'"));
                        $sq_traveler_personal_info = mysql_fetch_assoc(mysql_query("select * from traveler_personal_info where tourwise_traveler_id='$query[booking_id]'"));
                        ?> 
                        <div class="dashboard_table dashboard_table_panel main_block mg_bt_25">
                          <div class="row text-left">
                              <div class="">
                                <div class="dashboard_table_heading main_block">
                                  <div class="col-md-2">
                                    <h3>Group Tours</h3>
                                  </div>
                                <div class="col-md-3 col-sm-4 col-md-push-7">
                                    <select style="border-color: #009898; width: 100%;" id="group_booking_id" onchange="group_list_reflect(this.id)">
                                    <option value="" >Select Booking</option>
                                    <?php
                                    $query = "select * from tourwise_traveler_details where 1 ";
                                    include "../../model/app_settings/branchwise_filteration.php";
                                    $query .= " order by id desc";
                                    $sq_booking = mysql_query($query);
                                    while($row_booking = mysql_fetch_assoc($sq_booking)){
                                  
                                    $date = $row_booking['form_date'];
                                    $yr = explode("-", $date);
                                    $year =$yr[0];
                                  
                                    $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
                                      if($sq_customer['type'] == 'Corporate'){
                                        ?>
                                        <option value="<?php echo $row_booking['id'] ?>"><?php echo get_group_booking_id($row_booking['id'],$year)."-"." ".$sq_customer['company_name']; ?></option>
                                        <?php }
                                        else{ ?> 
                                                      
                                      <option value="<?= $row_booking['id'] ?>"><?= get_group_booking_id($row_booking['id'],$year) ?> : <?= $sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
                                      <?php
                                    }
                                    } ?>
                                    </select>
                                  </div>
                              
                                <div id="group_div_list">
                                </div>

                                </div>
                              </div>
                          </div>
                        </div>
                  </div>
                   <!--  GIT Summary End -->
                  <!-- Enquiry & Followup summary -->
                  <div role="tabpanel" class="tab-pane" id="enquiry_tab">
                      <div class="dashboard_table dashboard_table_panel main_block mg_bt_25">
                         <div class="row text-left">
                          <div class="col-md-6">
                            <div class="dashboard_table_heading main_block">
                              <div class="col-md-12 no-pad">
                                <h3>Followup Reminders</h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-1"></div>
                          <div class="col-md-2 col-sm-6 mg_bt_10">
                            <input type="text" id="followup_from_date_filter" name="followup_from_date_filter" placeholder="Followup From D/T" title="Followup From D/T">
                          </div>
                          <div class="col-md-2 col-sm-6 mg_bt_10">
                            <input type="text" id="followup_to_date_filter" name="followup_to_date_filter" placeholder="Followup To D/T" title="Followup To D/T">
                          </div>
                          <div class="col-md-1 text-left col-sm-6 mg_bt_10">
                            <button class="btn btn-excel btn-sm" id="followup_reflect1" onclick="followup_reflect()" data-toggle="tooltip" title="" data-original-title="Proceed"><i class="fa fa-arrow-right"></i></button>
                          </div>
                          <div id='followup_data'></div>
                          </div>
                        </div>
          </div>
        </div>
    </div>
              <!-- Enquiry & Followup summary end -->
            </div>
        </div>
      </div>
    </div>
    </div>
</div>
<script type="text/javascript">
	$('#followup_from_date_filter, #followup_to_date_filter').datetimepicker({format:'d-m-Y H:i' });
$('#group_booking_id,#package_booking_id').select2();
function package_list_reflect(){
  var booking_id = $('#package_booking_id').val();
  $.post('branch_admin/package_list_reflect.php', { booking_id : booking_id }, function(data){
    $('#package_div_list').html(data);
  });
}
package_list_reflect();
function group_list_reflect(){
  var booking_id = $('#group_booking_id').val();
  $.post('branch_admin/group_list_reflect.php', { booking_id : booking_id }, function(data){
    $('#group_div_list').html(data);
  });    
}
group_list_reflect();
function display_history(enquiry_id){
		$.post('branch_admin/followup_history.php', { enquiry_id : enquiry_id }, function(data){
		$('#history_data').html(data);
		});
}
	followup_reflect();
	function followup_reflect(){
		var from_date = $('#followup_from_date_filter').val();
		var to_date = $('#followup_to_date_filter').val();
		$.post('branch_admin/followup_list_reflect.php', { from_date : from_date,to_date:to_date }, function(data){
			$('#followup_data').html(data);
		});
	}

</script>
<script type="text/javascript">
  (function($) {
      fakewaffle.responsiveTabs(['xs', 'sm']);
  })(jQuery);
</script>