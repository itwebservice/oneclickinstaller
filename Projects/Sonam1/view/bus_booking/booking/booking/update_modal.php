<?php

include "../../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id']; 
$branch_status = $_POST['branch_status'];
$booking_id = $_POST['booking_id'];

$sq_booking = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id='$booking_id'"));

?>

<form id="frm_update">

<input type="hidden" id="booking_id" name="booking_id" value="<?= $booking_id ?>">



<div class="modal fade" id="update_modal" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog modal-lg" role="document" style="width: 60%">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Update Booking</h4>

      </div>

      <div class="modal-body">

        

        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_10">

            <legend>Customer Details</legend>

            

            <div class="row">

              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">

                <select name="customer_id1" id="customer_id1" class="customer_dropdown" title="Customer Name" style="width:100%" onchange="customer_info_load('1')" disabled>

                  <?php 

                  $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_booking[customer_id]'"));

                  if($sq_customer['type']=='Corporate'){
                  ?>
                    <option value="<?= $sq_customer['customer_id'] ?>"><?= $sq_customer['company_name'] ?></option>
                  <?php }  else{ ?>
                    <option value="<?= $sq_customer['customer_id'] ?>"><?= $sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
                  <?php } ?>

                 <?php get_customer_dropdown($role,$branch_admin_id,$branch_status); ?>

                </select>

              </div>

              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">

                <input type="text" id="mobile_no1" name="mobile_no" placeholder="Mobile No" title="Mobile No" disabled value="<?= $sq_customer['contact_no'] ?>">

              </div>

              <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_sm_xs">

                <input type="text" id="email_id1" name="email_id1" placeholder="Email ID" title="Email ID" disabled value="<?= $sq_customer['email_id'] ?>">

              </div>              

              <div class="col-md-3 col-sm-6 col-xs-12">

                    <input type="text" id="company_name1" class="hidden" name="company_name" title="Company Name" placeholder="Company Name" title="Company Name" readonly>

              </div>  

            </div>



        <script>

          customer_info_load('1');

        </script>

        </div>



        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">

            <legend>Booking Details</legend>

            

            <div class="row mg_bt_10">

                <div class="col-xs-12 text-right">

                    <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('tbl_dynamic_bus_booking')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>

                </div>

            </div>    

            

            <div class="row">

                <div class="col-xs-12">

                    <div class="table-responsive">

                    <table id="tbl_dynamic_bus_booking" name="tbl_dynamic_bus_booking" class="table table-bordered no-marg" style="width:1250px">

                       <?php $update_form = true; ?>

                       <?php include_once('bus_booking_tbl.php'); ?>                        

                    </table>

                    </div>

                </div>

            </div> 



        </div>



        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">

            <legend>Costing Details</legend>



            <div class="row mg_bt_10">

              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">

                <input type="text" id="basic_cost" name="basic_cost" placeholder="Amount" title="Amount" onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_booking['basic_cost'] ?>">

              </div>

              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">

                <input type="text" id="service_charge" name="service_charge" placeholder="Service Charge" title="Service Charge"  onchange="calculate_total_amount();validate_balance(this.id)" value="<?= $sq_booking['service_charge'] ?>">

              </div>

              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">

                <select name="taxation_type" id="taxation_type" title="Taxation Type">

                  <option value="<?= $sq_booking['taxation_type'] ?>"><?= $sq_booking['taxation_type'] ?></option>

                  <?php get_taxation_type_dropdown($setup_country_id) ?>

                </select>

              </div>    

              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">

                <select name="taxation_id" id="taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax', 'calculate_total_amount');">                
                   <?php 
                   if($sq_booking['taxation_id']!='0'){ 
                    $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_booking[taxation_id]'"));
                    $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                  ?>
                  <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
                  <?php } ?>
                  <?php get_taxation_dropdown(); ?>

                </select>
                <input type="hidden" id="service_tax" name="service_tax" value="<?= $sq_booking['service_tax'] ?>">

              </div>

              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">

                <input type="text" id="service_tax_subtotal" name="service_tax_subtotal" placeholder="Tax Amount" title="Tax Amount" readonly value="<?= $sq_booking['service_tax_subtotal'] ?>">

              </div>

              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">

                <input type="text" id="net_total" class="amount_feild_highlight text-right" name="net_total" placeholder="Net Total" title="Net Total" readonly value="<?= $sq_booking['net_total'] ?>">

              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10_xs">
                <input type="text" name="balance_date1" id="balance_date1" value="<?= get_date_user($sq_booking['created_at']) ?>" placeholder="Booking Date" title="Booking Date" onchange="check_valid_date(this.id)">
              </div>

            </div>
        </div>   



        <div class="row text-center">

          <div class="col-md-12">

            <button id="btn_update" class="btn btn-sm btn-success"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>

          </div>

        </div>



      </div>

    </div>

  </div>

</div>



</form>



<script>

$('#update_modal').modal('show');

$('#customer_id').select2();

$('#payment_date,#balance_date1').datetimepicker({ timepicker:false, format:'d-m-Y' });

$('#journey_date').datetimepicker({ format:'d-m-Y H:i:s' });



$('#frm_update').validate({

    rules:{

            customer_id : { required : true },

            basic_cost : { required : true },

            service_charge : { required : true },

            taxation_type : {  required : true },

            service_tax : { required : true },

            service_tax_subtotal : { required : true },

            net_total : { required : true },
            balance_date1 : { required:true},



            payment_date : { required : true },

            payment_amount : { required : true, number: true },

            payment_mode : { required : true },

            bank_name : { required : function(){  if($('#payment_mode').val()!="Cash" && $('#payment_amount').val() != '0'){ return true; }else{ return false; }  }  },

            transaction_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },     

            bank_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },  

    },

    submitHandler:function(){



            var booking_id = $('#booking_id').val();

            var customer_id = $('#customer_id1').val();



            var basic_cost = $('#basic_cost').val();

            var service_charge = $('#service_charge').val();

            var taxation_type = $('#taxation_type').val();

            var taxation_id = $('#taxation_id').val();

            var service_tax = $('#service_tax').val();

            var service_tax_subtotal = $('#service_tax_subtotal').val();

            var net_total = $('#net_total').val();
            var balance_date1 = $('#balance_date1').val();

            if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }

            var company_name_arr = new Array();

            var bus_type_arr = new Array();

            var bus_type_new_arr = new Array();

            var pnr_no_arr = new Array();

            var origin_arr = new Array();

            var destination_arr = new Array();

            var date_of_journey_arr = new Array();

            var reporting_time_arr = new Array();

            var boarding_point_access_arr = new Array();

            var entry_id_arr = new Array();





            var msg = "";

            var table = document.getElementById("tbl_dynamic_bus_booking");

            var rowCount = table.rows.length;

            

            for(var i=0; i<rowCount; i++)

            {

              var row = table.rows[i];

               

              if(row.cells[0].childNodes[0].checked)

              {



                  var company_name = row.cells[2].childNodes[0].value;

                  var bus_type = row.cells[3].childNodes[0].value;

                   var bus_type_new = row.cells[4].childNodes[0].value; 

                  var pnr_no = row.cells[5].childNodes[0].value;

                  var origin = row.cells[6].childNodes[0].value;

                  var destination = row.cells[7].childNodes[0].value;

                  var date_of_journey = row.cells[8].childNodes[0].value;

                  var reporting_time = row.cells[9].childNodes[0].value;

                  var boarding_point_access = row.cells[10].childNodes[0].value;                   


                  if(company_name == ''){
                    error_msg_alert("Enter Company name at row "+(i+1));
                    return false;
                  }

                  if(row.cells[11]){

                    entry_id = row.cells[11].childNodes[0].value;

                  }

                  else{

                    entry_id = "";

                  }

                 


                  company_name_arr.push(company_name);

                  bus_type_arr.push(bus_type);

                  bus_type_new_arr.push(bus_type_new);

                  pnr_no_arr.push(pnr_no);

                  origin_arr.push(origin);

                  destination_arr.push(destination);

                  date_of_journey_arr.push(date_of_journey);

                  reporting_time_arr.push(reporting_time);

                  boarding_point_access_arr.push(boarding_point_access);                       

                  entry_id_arr.push(entry_id);                       



              }      

            }



            if(msg!=""){

              error_msg_alert(msg);

              return false;

            }


			//Validation for booking and payment date in login financial year
		  var base_url = $('#base_url').val();
			var check_date1 = $('#balance_date1').val();
			$.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
				if(data !== 'valid'){
					error_msg_alert("The Booking date does not match between selected Financial year.");
					return false;
				}else{
            $('#btn_update').button('loading');
            $.ajax({

              type: 'post',
              url: base_url+'controller/bus_booking/booking/booking_update.php',
              data:{ booking_id : booking_id, customer_id : customer_id, basic_cost : basic_cost, service_charge : service_charge, taxation_type : taxation_type, taxation_id : taxation_id, service_tax : service_tax, service_tax_subtotal : service_tax_subtotal, net_total : net_total, company_name_arr : company_name_arr, bus_type_arr : bus_type_arr, bus_type_new_arr :bus_type_new_arr , pnr_no_arr : pnr_no_arr, origin_arr : origin_arr, destination_arr : destination_arr, date_of_journey_arr : date_of_journey_arr, reporting_time_arr : reporting_time_arr, boarding_point_access_arr : boarding_point_access_arr, entry_id_arr : entry_id_arr,balance_date1 : balance_date1 },
              success: function(result){
                $('#btn_update').button('reset');

                msg_alert(result);

                $('#update_modal').modal('hide');

                list_reflect();

              }
            });
         }
      });



    }

});

</script>

<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>