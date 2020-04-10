<?php
include "../../../model/model.php";

$booking_id = $_POST['booking_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id']; 
$branch_status = $_POST['branch_status'];
$sq_enq_info = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));
?>
<form id="frm_booking_update">
<input type="hidden" id="booking_id" name="booking_id" value="<?= $booking_id ?>">

<div class="modal fade" id="booking_update_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 70%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Booking</h4>
      </div>
      <div class="modal-body">


        <div class="panel panel-default panel-body app_panel_style feildset-panel">
        <legend>Customer Details</legend>        
          <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <select name="customer_id1" id="customer_id1" style="width: 100%" onchange="customer_info_load('1')" disabled>
                  <?php 
                  $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_enq_info[customer_id]'"));
                  if($sq_customer['type']=='Corporate'){
                  ?>
                    <option value="<?= $sq_customer['customer_id'] ?>"><?= $sq_customer['company_name'] ?></option>
                  <?php }  else{ ?>
                    <option value="<?= $sq_customer['customer_id'] ?>"><?= $sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
                  <?php } ?>
                 <?php get_customer_dropdown($role,$branch_admin_id,$branch_status); ?>
                  </select>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                    <input type="text" id="email_id1" name="email_id1" title="Email Id" placeholder="Email ID" title="Email ID" readonly>
                  </div>    
              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                    <input type="text" id="mobile_no1" name="mobile_no1" title="Mobile Number" placeholder="Mobile No" title="Mobile No" readonly>
              </div>   
              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                    <input type="text" id="company_name1" class="hidden" name="company_name1" title="Company Name" placeholder="Company Name" title="Company Name" readonly>
                  </div>             
              <script>
                customer_info_load('1');
              </script>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
              <input type="text" id="pass_name1" name="pass_name1" title="Passenger Name" placeholder="Passenger Name" value="<?= $sq_enq_info['pass_name'] ?>">
            </div>
          </div>
        </div>
        <div class="panel panel-default panel-body app_panel_style feildset-panel">
        <legend>Enquiry Details</legend>   
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">             
              <?php 
               $sq_name = mysql_fetch_assoc(mysql_query("select * from enquiry_master where enquiry_type='Car Rental' and enquiry_id='$sq_enq_info[enquiry_id]'"));
               ?>
                 <input type="text" name="enquiry_id1" id="enquiry_id1" value="<?php echo 'Enq'. $sq_enq_info['enquiry_id'].':'.$sq_name['name']; ?>" readonly>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="total_pax1" name="total_pax1" onchange="validate_balance(this.id);" placeholder="No Of Pax" title="No Of Pax" value="<?= $sq_enq_info['total_pax'] ?>">
            </div>          
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="days_of_traveling1" name="days_of_traveling1" onchange="validate_balance(this.id);" placeholder="Days Of Travelling" title="Days Of Travelling" value="<?= $sq_enq_info['days_of_traveling'] ?>">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="traveling_date1" name="traveling_date1" placeholder="Travelling Date&Time" title="Travelling Date&Time" value="<?= date('d-m-Y H:i:s', strtotime($sq_enq_info['traveling_date'])) ?>">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
              <input type="text" id="enquiry_date1" name="enquiry_date1" placeholder="Enquiry Date" title="Enquiry Date" value="<?= date('d-m-Y', strtotime($sq_enq_info['enquiry_date'])) ?>">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
              <select name="vehicle_type1" id="vehicle_type1" title="Vehicle Type">
                <option value="<?= $sq_enq_info['vehicle_type'] ?>"><?= $sq_enq_info['vehicle_type'] ?></option>
                <option value="AC">AC</option>
                <option value="Non AC">Non AC</option>
              </select>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10sm_xs">
              <select name="travel_type1" id="travel_type1" title="Travel Type">
                <option value="<?= $sq_enq_info['travel_type'] ?>"><?= $sq_enq_info['travel_type'] ?></option>
                <option value="Local">Local</option>
                <option value="Outstation">Outstation</option>
              </select>
            </div>            
            <div class="col-md-3 col-sm-6 col-xs-12">
              <textarea type="text" name="places_to_visit1" onchange="validate_spaces(this.id)" id="places_to_visit1" placeholder="Places To Visit" title="Places To Visit" rows="1"><?= $sq_enq_info['places_to_visit'] ?></textarea>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-3 col-sm-12 col-xs-12 mg_bt_10">

            <div class="panel panel-default panel-body app_panel_style feildset-panel">
            <legend>Vehicle Details</legend>

              <div class="row">
                <div class="col-xs-12 mg_bt_10">
                  <select name="vendor_id1" id="vendor_id1" title="Select Supplier" style="width:100%" onchange="vehicle_dropdown_reflect(this.id, 'vehicle_id1')">
                    <?php 
                    $sq_vendor = mysql_fetch_assoc(mysql_query("select * from car_rental_vendor where vendor_id='$sq_enq_info[vendor_id]'"));
                    ?>
                    <option value="<?= $sq_vendor['vendor_id'] ?>"><?= $sq_vendor['vendor_name'] ?></option>
                    <?php
                    $sq_vendor = mysql_query("select * from car_rental_vendor where active_flag!='Inactive'");
                    while($row_vendor = mysql_fetch_assoc($sq_vendor)){
                      ?>
                      <option value="<?= $row_vendor['vendor_id'] ?>"><?= $row_vendor['vendor_name'] ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>                
                <div class="col-xs-12">
                  <select name="vehicle_id1[]" id="vehicle_id1" title="Select Vehicle" placeholder="Select Vehicle" multiple>
                    <?php 
                    $sq_vehicle_entries = mysql_query("select * from car_rental_vendor_vehicle_entries where vendor_id='$sq_enq_info[vendor_id]'");
                    while($row_veh = mysql_fetch_assoc($sq_vehicle_entries)){

                      $sq_entry_count = mysql_num_rows(mysql_query("select * from car_rental_booking_vehicle_entries where booking_id='$sq_enq_info[booking_id]' and vehicle_id='$row_veh[vehicle_id]'"));
                      $selected = ($sq_entry_count==0) ? "" : "selected";
                      ?>
                      <option value="<?= $row_veh['vehicle_id'] ?>" <?= $selected ?>><?= $row_veh['vehicle_name'].'('.$row_veh['vehicle_no'].')' ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </div>
              </div>

            </div>

          </div>
          <div class="col-md-9 col-sm-12 col-xs-12">
            
            <div class="panel panel-default panel-body app_panel_style feildset-panel">
            <legend>Costing Details</legend>

              <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="daily_min_average1" name="daily_min_average1" placeholder="Daily Min Average" title="Daily Min Average" onchange="validate_balance(this.id);calculate_total_fees('1')" value="<?= $sq_enq_info['daily_min_average'] ?>">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="rate_per_km1" name="rate_per_km1" placeholder="Rate Per KM" title="Rate Per KM" onchange="calculate_total_fees('1');validate_balance(this.id)" value="<?= $sq_enq_info['rate_per_km'] ?>">
                </div> 
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="km_total_fee1" name="km_total_fee1" placeholder="KM Total Amount" title="KM Total Amount" readonly onchange="calculate_total_fees('1');validate_balance(this.id)" value="<?= $sq_enq_info['km_total_fee'] ?>">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <select name="taxation_type1" id="taxation_type1" title="Tax Name">
                    <?php if($sq_enq_info['taxation_type']!=''){ ?>
                    <option value="<?= $sq_enq_info['taxation_type'] ?>"><?= $sq_enq_info['taxation_type'] ?></option>
                    <?php } 
                    get_taxation_type_dropdown($setup_country_id) ?>
                  </select>
                </div>    
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <select name="taxation_id1" id="taxation_id1" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax1', 'calculate_total_fees', '1');">
                      <?php 
                      if($sq_enq_info['taxation_id']!='0'){

                        $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_enq_info[taxation_id]'"));
                        $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                        ?>
                       <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
                       <?php
                        }
                      ?>
                      <?php get_taxation_dropdown(); ?>
                  </select>
                  <input type="hidden" id="service_tax1" name="service_tax1" value="<?= $sq_enq_info['service_tax'] ?>">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="service_tax_subtotal1" name="service_tax_subtotal1" value="<?= $sq_enq_info['service_tax_subtotal'] ?>" placeholder="Tax Amount" title="Tax Amount" readonly>
                </div>                
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="total_cost1" name="total_cost1" placeholder="Total" title="Total" onchange="calculate_total_fees('1');validate_balance(this.id)" readonly value="<?= $sq_enq_info['total_cost'] ?>">
                </div>              
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="extra_km1" name="extra_km1" placeholder="Extra Km Rate" title="Extra Km Rate" onchange="validate_balance(this.id)" value="<?= $sq_enq_info['extra_km'] ?>">
                </div>              
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="actual_cost1" name="actual_cost1" placeholder="Extra Hr Rate" title="Extra Hr Rate" onchange="validate_balance(this.id)" value="<?= $sq_enq_info['actual_cost'] ?>">
                </div>       
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="driver_allowance1" name="driver_allowance1" placeholder="Driver Allowance" title="Driver Allowance" onchange="calculate_total_fees('1');validate_balance(this.id)" value="<?= $sq_enq_info['driver_allowance'] ?>">
                </div>  
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="permit_charges1" name="permit_charges1" placeholder="Permit Charges" title="Permit Charges" onchange="calculate_total_fees('1');validate_balance(this.id)" value="<?= $sq_enq_info['permit_charges'] ?>">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="toll_and_parking1" name="toll_and_parking1" placeholder="Toll & Parking" title="Toll & Parking" onchange="calculate_total_fees('1');validate_balance(this.id)" value="<?= $sq_enq_info['toll_and_parking'] ?>">
                </div>                
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
                  <input type="text" id="state_entry_tax1" name="state_entry_tax1" placeholder="State Entry Tax" title="State Entry Tax" onchange="calculate_total_fees('1');validate_balance(this.id)" value="<?= $sq_enq_info['state_entry_tax'] ?>">
                </div>                
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
                  <input type="text" id="total_fees1" class="amount_feild_highlight text-right" name="total_fees1" placeholder="Net Total" title="Net Total" readonly value="<?= $sq_enq_info['total_fees'] ?>">
                </div>
                 <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
                  <input type="text" name="due_date1" id="due_date1" placeholder="Due Date" title="Due Date" value="<?= get_date_user($sq_enq_info['due_date'])?>">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <input type="text" name="booking_date1" id="booking_date1" value="<?= get_date_user($sq_enq_info['created_at']) ?>" placeholder="Booking Date" title="Booking Date" onchange="check_valid_date(this.id)">
                </div>
              </div>

            </div>

          </div>
        </div>


        <div class="row text-center">
          <div class="col-xs-12">
              <button class="btn btn-sm btn-success" id="car_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
          </div>
        </div>

      </div>      
    </div>
  </div>
</div>
</form>


<script>
$('#vendor_id1, #customer_id1').select2();

$('#booking_update_modal').modal('show');

$('#enquiry_date1,#due_date1,#booking_date1').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#traveling_date1').datetimepicker({ format:'d-m-Y H:i:s' });

$(function(){
  $('#frm_booking_update').validate({
      rules:{
        booking_date1 : { required : true },
      },
      submitHandler:function(form){

              var booking_id = $('#booking_id').val();
              var customer_id = $('#customer_id1').val();
              var total_pax = $('#total_pax1').val();
              var days_of_traveling = $('#days_of_traveling1').val();
              var traveling_date = $('#traveling_date1').val();
              var enquiry_date = $('#enquiry_date1').val();
              var vehicle_type = $('#vehicle_type1').val();
              var travel_type = $('#travel_type1').val();
              var places_to_visit = $('#places_to_visit1').val();
              var pass_name = $('#pass_name1').val();

              var vendor_id = $('#vendor_id1').val();
              var vehicle_count = $('#vehicle_id1 option:selected').length;
              var vehicle_id_arr = new Array();
              $('#vehicle_id1 option:selected').each(function(){
                vehicle_id_arr.push( $(this).attr('value') );
              });


              var daily_min_average = $('#daily_min_average1').val();
              var rate_per_km = $('#rate_per_km1').val();
              var extra_km = $('#extra_km1').val();
              var km_total_fee = $('#km_total_fee1').val();
              var actual_cost = $('#actual_cost1').val();
              var taxation_type = $('#taxation_type1').val();
              var taxation_id = $('#taxation_id1').val();
              var service_tax = $('#service_tax1').val();
              var service_tax_subtotal = $('#service_tax_subtotal1').val();
              var total_cost = $('#total_cost1').val();
              var driver_allowance = $('#driver_allowance1').val();
              var permit_charges = $('#permit_charges1').val();
              var toll_and_parking = $('#toll_and_parking1').val();
              var state_entry_tax = $('#state_entry_tax1').val();
              var total_fees = $('#total_fees1').val();
              var due_date1 = $('#due_date1').val();
              var booking_date1 = $('#booking_date1').val();

              var base_url = $('#base_url').val();

              if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }
              //Validation for booking and payment date in login financial year
              var check_date1 = $('#booking_date1').val();
              $.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
                if(data !== 'valid'){
                  error_msg_alert("The Booking date does not match between selected Financial year.");
                  return false;
                }else{
                  $('#car_update').button('loading');
                  
                  $.ajax({
                    type:'post',
                    url: base_url+'controller/car_rental/booking/booking_update.php',
                    data:{ booking_id : booking_id, customer_id : customer_id, total_pax : total_pax,pass_name:pass_name, days_of_traveling : days_of_traveling, traveling_date : traveling_date, enquiry_date : enquiry_date, vehicle_type : vehicle_type, travel_type : travel_type, places_to_visit : places_to_visit, vendor_id : vendor_id, vehicle_id_arr : vehicle_id_arr, daily_min_average : daily_min_average, rate_per_km : rate_per_km, extra_km : extra_km, km_total_fee : km_total_fee, actual_cost : actual_cost, taxation_type : taxation_type, taxation_id : taxation_id, service_tax : service_tax, service_tax_subtotal : service_tax_subtotal, total_cost : total_cost, driver_allowance : driver_allowance, permit_charges : permit_charges, toll_and_parking : toll_and_parking, state_entry_tax : state_entry_tax, total_fees : total_fees ,due_date1 : due_date1, booking_date1 : booking_date1 },
                    success:function(result){
                      msg_popup_reload(result);
                    },
                    error:function(result){
                      console.log(result.responseText);
                    }
                  });
                }
              });


      }
  });
});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>