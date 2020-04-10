<form id="frm_tab4">

<div class="row mg_bt_10">

	<div class="col-md-2">
  		<input type="text" id="subtotal" name="subtotal" placeholder="Basic Cost" title="Basic Cost" onchange="quotation_cost_calculate();validate_balance(this.id)" value="0.00">  
  	</div>
	<div class="col-md-2">
  		<input type="text" id="markup_cost" name="markup_cost" placeholder="Markup Cost(%)" title="Markup Cost(%)" onchange="quotation_cost_calculate();validate_balance(this.id)" value="0.00">  
  	</div>
	<div class="col-md-2">
  		<input type="text" id="markup_cost_subtotal" name="markup_cost_subtotal" placeholder="Markup Cost Subtotal" title="Markup Cost Subtotal" onchange="quotation_cost_calculate();" value="0.00">  
  	</div>
  	<div class="col-md-2">
  		<select name="taxation_id" id="taxation_id" title="Tax" onchange="quotation_cost_calculate();generic_tax_reflect(this.id, 'service_tax', 'quotation_cost_calculate');">
	        <?php get_taxation_dropdown(); ?>
	    </select>
	</div>    

	<input type="hidden" id="service_tax" name="service_tax" value="0.00">

	<div class="col-md-2">

		<input type="text" id="service_tax_subtotal" name="service_tax_subtotal" readonly placeholder="Tax Amount" title="Tax Amount">

	</div>

	<div class="col-md-2"> 

	 	<input type="text" id="permit" name="permit" placeholder="Permit charges" title="Permit charges" value="0.00" onchange="quotation_cost_calculate();validate_balance(this.id)">  

	</div>
</div>
<div class="row mg_bt_10">

    <div class="col-md-2">

	  	<input type="text" id="toll_parking" name="toll_parking" placeholder="Toll Parking charges" title="Toll Parking charges" value="0.00" onchange="quotation_cost_calculate();validate_balance(this.id)"> 

	</div>
	<div class="col-md-2">

	    <input type="text" id="driver_allowance" name="driver_allowance" placeholder="Driver Allowance" title="Driver Allowance" value="0.00" onchange="quotation_cost_calculate();validate_balance(this.id)">

	</div>

	<div class="col-md-2">

	    <input type="text" id="total_tour_cost" name="total_tour_cost" placeholder="Total" title="Total" value="0.00" onchange="validate_balance(this.id)" >

	</div>

</div>	 

	<div class="row mg_tp_20 text-center">

		<div class="col-md-12">

			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab1()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>

			&nbsp;&nbsp;

			<button class="btn btn btn-success" id="btn_quotation_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>

		</div>

	</div>

</form>



<script>

function switch_to_tab1(){ $('a[href="#tab1"]').tab('show'); }

$('#frm_tab4').validate({

	rules:{

		

	},

	submitHandler:function(form){

		var enquiry_id = $("#enquiry_id").val();

		var login_id = $("#login_id").val();

		var emp_id = $("#emp_id").val();

		var customer_name = $('#customer_name').val();

		var email_id = $('#email_id').val();

		var mobile_no = $('#mobile_no').val();

		var total_pax = $("#total_pax").val();

		var days_of_traveling = $('#days_of_traveling').val();

		var traveling_date = $('#traveling_date').val();

		var vehicle_type = $('#vehicle_type').val();

		var travel_type = $('#travel_type').val();

		var places_to_visit = $('#places_to_visit').val();

		var vehicle_name = $('#vehicle_name').val();

		var from_date = $('#from_date').val();

		var to_date = $('#to_date').val();

		var trip_type = $('#trip_type').val();

		var route = $('#route').val();

		var extra_km_cost = $('#extra_km_cost').val();

		var extra_hr_cost = $('#extra_hr_cost').val();		

		var daily_km = $('#daily_km').val();

		var subtotal = $('#subtotal').val();

		var markup_cost = $('#markup_cost').val();

		var markup_cost_subtotal = $('#markup_cost_subtotal').val();

		var taxation_id = $('#taxation_id').val();

		var service_tax = $('#service_tax').val();

		var service_tax_subtotal = $('#service_tax_subtotal').val();

		var permit = $('#permit').val();

		var toll_parking = $('#toll_parking').val();

		var driver_allowance = $('#driver_allowance').val();

		var total_tour_cost = $('#total_tour_cost').val();

		var quotation_date = $('#quotation_date').val();

		 var branch_admin_id = $('#branch_admin_id1').val();
		 var financial_year_id = $('#financial_year_id').val();
 		
		var base_url = $('#base_url').val();
		if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }

		$('#btn_quotation_save').button('loading');

		$.ajax({

			type:'post',

			url: base_url+'controller/package_tour/quotation/car_rental/quotation_save.php',

			data:{ enquiry_id : enquiry_id , login_id : login_id, emp_id : emp_id,total_pax : total_pax, days_of_traveling : days_of_traveling,traveling_date : traveling_date,vehicle_type : vehicle_type, travel_type : travel_type, places_to_visit : places_to_visit,vehicle_name : vehicle_name, from_date : from_date, to_date : to_date,trip_type : trip_type, route : route,extra_km_cost : extra_km_cost , extra_hr_cost : extra_hr_cost, daily_km : daily_km, subtotal : subtotal,markup_cost : markup_cost,markup_cost_subtotal : markup_cost_subtotal, taxation_id : taxation_id, service_tax : service_tax , service_tax_subtotal : service_tax_subtotal, permit : permit, toll_parking : toll_parking, driver_allowance : driver_allowance , total_tour_cost : total_tour_cost, customer_name : customer_name,quotation_date : quotation_date, email_id : email_id, mobile_no : mobile_no, branch_admin_id : branch_admin_id,financial_year_id :financial_year_id},

			success: function(message){

					$('#btn_quotation_save').button('reset');

                	var msg = message.split('--');

					if(msg[0]=="error"){

						error_msg_alert(msg[1]);

					}

					else{

						$('#vi_confirm_box').vi_confirm_box({

						            false_btn: false,

						            message: message,

						            true_btn_text:'Ok',

						    callback: function(data1){

						        if(data1=="yes"){

						        	$('#btn_quotation_save').button('reset');

						        	$('#quotation_save_modal').modal('hide');

						        	quotation_list_reflect();


						        }

						      }

						});

					}



                }  



                

		});

	}  



});



        	 

</script>