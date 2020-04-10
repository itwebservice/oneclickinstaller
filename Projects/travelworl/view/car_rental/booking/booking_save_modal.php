<?php
include '../../../model/model.php';
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id= $_SESSION['emp_id'];
$branch_status= $_POST['branch_status'];
?>
<form id="frm_booking_save">
<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
<div class="modal fade" id="booking_save_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 70%;">
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Booking</h4>
      </div>
      <div class="modal-body">
        <div class="panel panel-default panel-body app_panel_style feildset-panel">
         <legend>Customer Details</legend>
          <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
              <select name="customer_id" id="customer_id" class="customer_dropdown" title="Select Customer" style="width:100%" onchange="customer_info_load('')">
                  <?php get_new_customer_dropdown($role,$branch_admin_id,$branch_status); ?>
              </select>
            </div>        
            <div id="cust_details">
              <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
                    <input type="text" id="email_id" name="email_id" class="form-control" title="Email Id" placeholder="Email ID" readonly>
                  </div>    
              <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
                    <input type="text" id="mobile_no" name="mobile_no" class="form-control" title="Mobile Number" placeholder="Mobile No" readonly>
              </div>  
              <div class="col-md-3 col-sm-4 col-xs-12">
                    <input type="text" id="company_name1" class="hidden form-control" name="company_name" title="Company Name" placeholder="Company Name" readonly>
              </div>    
              <div class="col-md-3 col-sm-4 col-xs-12">
                <input type="text" id="credit_amount" class="hidden form-control" name="credit_amount" placeholder="Credit Note Balance" title="Credit Note Balance" readonly>
              </div>
            </div>
            <div id="new_cust_div"></div>
          </div>
          <div class="row mg_tp_10">
            <div class="col-md-3 col-sm-4 col-xs-12">
              <input type="text" id="pass_name" name="pass_name" class="form-control" title="Passenger Name" placeholder="Passenger Name">
            </div>
          </div>
        </div>
        <div class="panel panel-default panel-body app_panel_style feildset-panel">
         <legend>Enquiry Details</legend>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <select name="enquiry_id" id="enquiry_id" style="width:100%" onchange="get_enquiry_details('')" class="form-control">
                <option value="">Select Enquiry</option>
                <?php
                $query = "SELECT * FROM `enquiry_master` where 1";
                $query .=" and status!='Disabled'";
                $query .=" and enquiry_type='Car Rental'";
                if($branch_status=='yes'){
                  if($role=='Branch Admin' || $role=='Accountant' || $role_id>'7'){
                      $query .= " and branch_admin_id = '$branch_admin_id'";
                  }
                  elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
                      $query .= " and assigned_emp_id='$emp_id' and branch_admin_id = '$branch_admin_id'";
                  }
                }
                elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
                  $query .= " and assigned_emp_id='$emp_id'";
                }
                $sq_enq = mysql_query($query);   
                while($row_enq = mysql_fetch_assoc($sq_enq)){
                  ?>
              <option value="<?= $row_enq['enquiry_id'] ?>">Enq<?= $row_enq['enquiry_id'] ?> : <?= $row_enq['name'] ?></option>
                  <?php
                }
                ?>
              </select> 
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="total_pax" name="total_pax" onchange="validate_balance(this.id);" placeholder="No Of Pax" title="No Of Pax" class="form-control">
            </div>  
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="days_of_traveling" onchange="validate_balance(this.id)" name="days_of_traveling" class="form-control" placeholder="Days Of Travelling" title="Days Of Travelling">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="traveling_date" name="traveling_date" placeholder="Travelling Date&Time" title="Travelling Date&Time" value="<?= date('d-m-Y')?>" class="form-control">
            </div> 
          </div>  
           <div class="row">   
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
              <input type="text" id="enquiry_date" name="enquiry_date" placeholder="Enquiry Date" title="Enquiry Date" value="<?= date('d-m-Y')?>" class="form-control">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
              <select name="vehicle_type" id="vehicle_type" title="Vehicle Type" class="form-control">
                <option value="">Vehicle Type</option>
                <option value="AC">AC</option>
                <option value="Non AC">Non AC</option>
              </select>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_sm_xs">
              <select name="travel_type" id="travel_type" title="Travel Type" class="form-control">
                <option value="">Travel Type</option>
                <option value="Local">Local</option>
                <option value="Outstation">Outstation</option>
              </select>
            </div>            
            <div class="col-md-3 col-sm-6 col-xs-12">
              <textarea type="text" name="places_to_visit" class="form-control" id="places_to_visit" onchange="validate_spaces(this.id)" placeholder="Places To Visit" title="Places To Visit" rows="1"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 col-sm-12 col-xs-12 mg_bt_10">              
              <div class="panel panel-default panel-body app_panel_style feildset-panel">
              <legend>Vehicle Details</legend>        
                <div class="row mg_bt_10">
                  <div class="col-xs-12">
                    <select name="vendor_id" id="vendor_id" title="Select Supplier" style="width:100%" class="form-control" onchange="vehicle_dropdown_reflect(this.id, 'vehicle_id')">
                      <option value="">Select Supplier</option>
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
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <select name="vehicle_id[]" id="vehicle_id" title="Select Vehicle" placeholder="*Select Vehicle" class="form-control" multiple></select>
                  </div>
                </div>

              </div>

          </div>

          <div class="col-md-9 col-sm-12 col-xs-12">
            
            <div class="panel panel-default panel-body app_panel_style feildset-panel">
            <legend>Costing Details</legend>

              <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="daily_min_average" name="daily_min_average" class="form-control" placeholder="Daily Min Average" title="Daily Min Average" onchange="calculate_total_fees();validate_balance(this.id)">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="rate_per_km" name="rate_per_km" class="form-control" placeholder="Rate Per KM" title="Rate Per KM" onchange="calculate_total_fees();validate_balance(this.id)">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="km_total_fee" class="text-right form-control" name="km_total_fee" placeholder="KM Total Amount" title="KM Total Amount" readonly onchange="calculate_total_fees()">
                </div>    
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <select name="taxation_type" id="taxation_type" title="*Tax Name" class="form-control">
                    <?php get_taxation_type_dropdown($setup_country_id) ?>
                  </select>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <select name="taxation_id" id="taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax', 'calculate_total_fees');" class="form-control">
                      <?php get_taxation_dropdown(); ?>
                  </select>
                  <input type="hidden" id="service_tax" name="service_tax" value="0" class="form-control">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="service_tax_subtotal" class="text-right form-control" name="service_tax_subtotal" placeholder="*Tax Amount" title="*Tax Amount" readonly>                  
                </div>                
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="total_cost" name="total_cost" class="text-right form-control" placeholder="Total" title="Total" onchange="calculate_total_fees()" readonly>
                </div>               
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="extra_km" name="extra_km" class="form-control" placeholder="Extra Km Rate" title="Extra KM Rate" onchange="validate_balance(this.id)">
                </div>               
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="actual_cost" name="actual_cost" class="form-control" placeholder="Extra Hr Rate" title="Extra Hr Rate" onchange="validate_balance(this.id)">
                </div>                
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="driver_allowance" name="driver_allowance" placeholder="Driver Allowance" class="text-right form-control" title="Driver Allowance" onchange="calculate_total_fees();validate_balance(this.id)">
                </div>  
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="permit_charges" name="permit_charges" class="form-control" placeholder="Permit Charges" title="Permit Charges" onchange="calculate_total_fees();validate_balance(this.id)">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                  <input type="text" id="toll_and_parking" name="toll_and_parking" class="form-control" placeholder="Toll & Parking" title="Toll & Parking" onchange="calculate_total_fees();validate_balance(this.id)">
                </div>            
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
                  <input type="text" id="state_entry_tax" name="state_entry_tax" class="form-control" placeholder="State Entry Tax" title="State Entry Tax" onchange="calculate_total_fees();validate_balance(this.id)">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
                  <input type="text" id="total_fees" class="amount_feild_highlight text-right form-control" name="total_fees" placeholder="Net Total" title="Net Total" readonly>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
                  <input type="text" name="due_date" id="due_date" placeholder="Due Date" title="Due Date" class="form-control" value="<?= date('d-m-Y') ?>">
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <input type="text" name="balance_date" id="balance_date" value="<?= date('d-m-Y') ?>" class="form-control" placeholder="Booking Date" title="Booking Date" onchange="check_valid_date(this.id)">
                </div>
              </div>

            </div>

          </div>
        </div>

      

        <div class="panel panel-default panel-body app_panel_style feildset-panel">
        <legend>Advance Details</legend>
    
            <div class="row ">
              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
                <input type="text" id="payment_date" name="payment_date" class="form-control" placeholder="Date" title="Date" value="<?= date('d-m-Y')?>" onchange="check_valid_date(this.id)">
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
                <input type="text" id="payment_amount" name="payment_amount" class="form-control" placeholder="*Amount" title="Amount" onchange="payment_amount_validate(this.id,'payment_mode','transaction_id', 'bank_name','bank_id');validate_balance(this.id)">
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
                <select id="payment_mode" name="payment_mode" class="form-control" required title="Mode" onchange="payment_master_toggles(this.id, 'bank_name', 'transaction_id', 'bank_id')">
                    <?php get_payment_mode_dropdown(); ?>
                </select>  
              </div>              
              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10_xs">
                <input type="text" id="bank_name" name="bank_name" class="form-control bank_suggest" placeholder="Bank Name" title="Bank Name" disabled />
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10_sm_xs">
                <input type="text" id="transaction_id" name="transaction_id" onchange="validate_specialChar(this.id)" class="form-control" placeholder="Cheque No / ID" title="Cheque No / ID" disabled />
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <select name="bank_id" id="bank_id" title="Select Bank" disabled class="text-right form-control" >
                  <?php get_bank_dropdown(); ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-9 col-sm-9">
               <span style="color: red;line-height: 35px;" data-original-title="" title="" class="note" class="form-control"><?= $txn_feild_note ?></span>
             </div>
            </div>
        </div>
        <div class="row text-center">
          <div class="col-xs-12">
              <button id="btn_booking_save" class="btn btn-sm btn-success"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
          </div>
        </div>

      </div>      
    </div>
  </div>
</div>
</form>


<script>
$('#booking_save_modal').modal('show');
$('#enquiry_id, #vendor_id,#customer_id').select2();

$('#enquiry_date, #payment_date,#due_date,#balance_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#traveling_date').datetimepicker({ format:'d-m-Y H:i:s' });

$(function(){
  $('#frm_booking_save').validate({
      rules:{
          taxation_id :{ required : true },
          taxation_type : { required : true },
          payment_amount : { required : true },
          service_tax :{ required : true, number:true },
          service_tax_subtotal :{ required : true, number:true },
          customer_id : { required : true },
          balance_date : { required : true },
          bank_name : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },
          transaction_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },     
          bank_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },
      },
      submitHandler:function(form){

              var enquiry_id = $('#enquiry_id').val();
              var base_url = $('#base_url').val();
              var customer_id = $('#customer_id').val();
              var cust_first_name = $('#cust_first_name').val();
              var cust_middle_name = $('#cust_middle_name').val();
              var cust_last_name = $('#cust_last_name').val();
              var gender = $('#cust_gender').val();
              var cust_birth_date = $('#cust_birth_date').val();
              var age = $('#cust_age').val();
              var contact_no = $('#cust_contact_no').val();
              var email_id = $('#cust_email_id').val();
              var address = $('#cust_address1').val();
              var address2 = $('#cust_address2').val();
              var city = $('#city').val();
              var service_tax_no = $('#cust_service_tax_no').val();  
              var landline_no = $('#cust_landline_no').val();
              var alt_email_id = $('#cust_alt_email_id').val();
              var company_name = $('#corpo_company_name').val();
              var cust_type = $('#cust_type').val();
              var state = $('#cust_state').val();
              var active_flag = 'Active';
              var branch_admin_id = $('#branch_admin_id1').val();

              //New Customer save
              if(customer_id == '0'){
                  $.ajax({
                      type: 'post',
                      url: base_url+'controller/customer_master/customer_save.php',
                      data:{ first_name : cust_first_name, middle_name : cust_middle_name, last_name : cust_last_name, gender : gender, birth_date : cust_birth_date, age : age, contact_no : contact_no, email_id : email_id, address : address,address2 : address2,city:city,  active_flag : active_flag ,service_tax_no : service_tax_no, landline_no : landline_no, alt_email_id : alt_email_id,company_name : company_name, cust_type : cust_type,state : state, branch_admin_id : branch_admin_id},
                      success: function(result){
                      }
                  });
              }
              var emp_id = $('#emp_id').val();
              var total_pax = $('#total_pax').val();
              var days_of_traveling = $('#days_of_traveling').val();
              var traveling_date = $('#traveling_date').val();
              var enquiry_date = $('#enquiry_date').val();
              var vehicle_type = $('#vehicle_type').val();
              var travel_type = $('#travel_type').val();
              var places_to_visit = $('#places_to_visit').val();
              var credit_amount = $('#credit_amount').val();

              var vendor_id = $('#vendor_id').val();
              var vehicle_count = $('#vehicle_id option:selected').length;

              var vehicle_id_arr = new Array();
              $('#vehicle_id option:selected').each(function(){
                vehicle_id_arr.push( $(this).attr('value') );
              });
              var pass_name = $('#pass_name').val();
              var daily_min_average = $('#daily_min_average').val();
              var rate_per_km = $('#rate_per_km').val();
              var extra_km = $('#extra_km').val();
              var km_total_fee = $('#km_total_fee').val();
              var actual_cost = $('#actual_cost').val();
              var taxation_type = $('#taxation_type').val();
              var taxation_id = $('#taxation_id').val();
              var service_tax = $('#service_tax').val();
              var service_tax_subtotal = $('#service_tax_subtotal').val();
              var total_cost = $('#total_cost').val();
              var driver_allowance = $('#driver_allowance').val();
              var permit_charges = $('#permit_charges').val();
              var toll_and_parking = $('#toll_and_parking').val();
              var state_entry_tax = $('#state_entry_tax').val();
              var total_fees = $('#total_fees').val();
              var due_date = $('#due_date').val();
              var booking_date = $('#balance_date').val();
              var payment_amount = $('#payment_amount').val();
              var payment_date = $('#payment_date').val();
              var payment_mode = $('#payment_mode').val();
              var bank_name = $('#bank_name').val();
              var transaction_id = $('#transaction_id').val();
              var bank_id = $('#bank_id').val();

              if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }
              if(credit_amount != ''){ 
                if(parseFloat(payment_amount) > parseFloat(credit_amount)) { error_msg_alert('Low Credit note balance'); return false; }
              }
            //Validation for booking and payment date in login financial year
            $('#btn_booking_save').button('loading');
            var check_date1 = $('#balance_date').val();
            $.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
              if(data !== 'valid'){
                error_msg_alert("The Booking date does not match between selected Financial year.");
                $('#btn_booking_save').button('reset');
                return false;
              }else{
                var payment_date = $('#payment_date').val();
                $.post(base_url+'view/load_data/finance_date_validation.php', { check_date: payment_date }, function(data){
                if(data !== 'valid'){
                  error_msg_alert("The Payment date does not match between selected Financial year.");
                  $('#btn_booking_save').button('reset');
                  return false;
                }else{
                    $('#btn_booking_save').button('loading');
                    $.ajax({
                      type:'post',
                      url: base_url+'controller/car_rental/booking/booking_save.php',
                      data:{ emp_id : emp_id, customer_id : customer_id,enquiry_id : enquiry_id, total_pax : total_pax,pass_name : pass_name, days_of_traveling : days_of_traveling, traveling_date : traveling_date, enquiry_date : enquiry_date, vehicle_type : vehicle_type, travel_type : travel_type, places_to_visit : places_to_visit, vendor_id : vendor_id, vehicle_id_arr : vehicle_id_arr, daily_min_average : daily_min_average, rate_per_km : rate_per_km, extra_km : extra_km, km_total_fee : km_total_fee, actual_cost : actual_cost, taxation_type : taxation_type, taxation_id : taxation_id, service_tax : service_tax, service_tax_subtotal : service_tax_subtotal, total_cost : total_cost, driver_allowance : driver_allowance, permit_charges : permit_charges, toll_and_parking : toll_and_parking, state_entry_tax : state_entry_tax, total_fees : total_fees, payment_amount : payment_amount, payment_date : payment_date, payment_mode : payment_mode, bank_name : bank_name, transaction_id : transaction_id, bank_id : bank_id , due_date : due_date,booking_date : booking_date, branch_admin_id : branch_admin_id},
                      success:function(result){
                        $('#btn_booking_save').button('reset');
                        var msg = "Booking saved successfully!";
                        msg_popup_reload(msg);
                      },
                      error:function(result){
                        console.log(result.responseText);
                      }
                    });
							}
						});
					}
				});


      }
  });
});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>