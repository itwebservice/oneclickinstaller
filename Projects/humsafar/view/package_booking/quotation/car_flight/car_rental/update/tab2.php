 
<form id="frm_tab41_c">
	<div class="row mg_bt_10">

	<div class="col-md-2">

  		<input type="text" id="subtotal" name="subtotal" placeholder="Basic Cost" title="Basic Cost" onchange="quotation_cost_calculate1();validate_balance(this.id)" value="<?= $sq_quotation['subtotal'] ?>">  

  	</div>
	
	<div class="col-md-2">
  		<input type="text" id="markup_cost" name="markup_cost" placeholder="Markup Cost(%)" title="Markup Cost(%)" onchange="quotation_cost_calculate1();validate_balance(this.id)" value="<?= $sq_quotation['markup_cost'] ?>">  
  	</div>
	<div class="col-md-2">
  		<input type="text" id="markup_cost_subtotal" name="markup_cost_subtotal" placeholder="Markup Cost Subtotal" title="Markup Cost Subtotal" onchange="quotation_cost_calculate();validate_balance(this.id)" value="<?= $sq_quotation['markup_cost_subtotal'] ?>">  
  	</div>
  	<div class="col-md-2">

  		<select name="taxation_id" id="taxation_id" title="Tax" onchange="quotation_cost_calculate1();generic_tax_reflect(this.id, 'service_tax', 'quotation_cost_calculate1','1');">
			<?php 
	           if($sq_quotation['taxation_id']=="" || $sq_quotation['taxation_id']==0){
	             get_taxation_dropdown();  
	           }
	           else{
	             $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_quotation[taxation_id]'"));
	             $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
	             ?>
	         <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
	         <?php get_taxation_dropdown(); ?>
            <?php
            } ?>

	    </select>

	</div>    

	<input type="hidden" id="service_tax" name="service_tax" value="<?php echo $sq_quotation['service_tax']; ?>" onchange="validate_balance(this.id)">

	<div class="col-md-2">

		<input type="text" id="service_tax_subtotal" name="service_tax_subtotal" readonly placeholder="Tax Amount" title="Tax Amount" value="<?php echo $sq_quotation['service_tax_subtotal']; ?>" onchange="validate_balance(this.id)">

	</div>

	<div class="col-md-2"> 

	 	<input type="text" id="permit1" name="permit1" placeholder="Permit charges" title="Permit charges" value="<?php echo $sq_quotation['permit']; ?>" onchange="quotation_cost_calculate1();validate_balance(this.id)">  

	</div>
</div>
<div class="row mg_bt_10">

    <div class="col-md-2">

	  	<input type="text" id="toll_parking1" name="toll_parking1" placeholder="Toll Parking charges" title="Toll Parking charges" value="<?php echo $sq_quotation['toll_parking']; ?>" onchange="quotation_cost_calculate1();validate_balance(this.id)"> 

	</div>
	<div class="col-md-2">

	    <input type="text" id="driver_allowance1" name="driver_allowance1" placeholder="Driver Allowance" title="Driver Allowance" value="<?php echo $sq_quotation['driver_allowance']; ?>" onchange="quotation_cost_calculate1();validate_balance(this.id)">

	</div>
	<div class="col-md-2">

	    <input type="text" id="total_tour_cost1" name="total_tour_cost1" onchange="validate_balance(this.id);" placeholder="Total" title="Total" value="<?php echo $sq_quotation['total_tour_cost']; ?>" >

	</div>

</div>	 

	<div class="row mg_tp_20 text-center">
		<div class="col-md-12">
			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab1()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>
			&nbsp;&nbsp;
			<button class="btn btn-sm btn-success" id="btn_quotation_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
		</div>
	</div>
</form>

<script>

function quotation_cost_calculate1(){
	var subtotal = $('#subtotal').val();  
	var markup_cost = $('#markup_cost').val(); 
    var markup_cost_subtotal = $('#markup_cost_subtotal').val(); 
	var service_tax = $('#service_tax').val();
	var permit = $('#permit1').val();
	var toll_parking = $('#toll_parking1').val();
	var driver_allowance = $('#driver_allowance1').val();

    if(subtotal==""||subtotal=='0'){ subtotal = 0;}
    if(markup_cost==""||markup_cost=="0"){ markup_cost = 0;}
	if(markup_cost_subtotal==""||markup_cost_subtotal=='0'){ markup_cost_subtotal = 0;}
    if(service_tax==""||service_tax=='0'){ service_tax = 0;}
    if(permit==""||permit=='0'){permit = 0;}
    if(toll_parking==""||toll_parking=='0'){ toll_parking = 0;}
    if(driver_allowance==""||driver_allowance=='0'){ driver_allowance = 0;}

	if(parseFloat(markup_cost) == 0){
   		var t_subtotal = parseFloat(subtotal) + parseFloat(markup_subtotal);
    }
    else{
   	   var markup_subtotal = (parseFloat(subtotal)/100) * parseFloat(markup_cost);
	   var t_subtotal = parseFloat(subtotal) + parseFloat(markup_subtotal);
    }
	if(markup_subtotal==""){markup_subtotal = 0;}

	var service_tax_amount = (parseFloat(markup_cost_subtotal)/100) * parseFloat(service_tax);

	total_tour_cost = parseFloat(subtotal) + parseFloat(markup_subtotal) + parseFloat(permit) + parseFloat(toll_parking) + parseFloat(driver_allowance) + parseFloat(service_tax_amount);
	$('#service_tax_subtotal').val(service_tax_amount.toFixed(2));
	$('#markup_cost_subtotal').val(markup_subtotal.toFixed(2));
	$('#total_tour_cost1').val(total_tour_cost.toFixed(2));

}
function switch_to_tab1(){ $('a[href="#tab_1_c"]').tab('show'); }

$('#frm_tab41_c').validate({
	rules:{
		markup_cost : { required : true, number : true },
		tour_cost : { required : true, number: true },
		taxation_id : { required : true },
	},
	submitHandler:function(form){
		var enquiry_id = $("#enquiry_id1").val();
		var quotation_id = $('#quotation_id1').val();
	 
		var customer_name = $('#customer_name1').val();

		var email_id = $('#email_id1').val();

		var mobile_no = $('#mobile_no1').val();

		var total_pax = $("#total_pax1").val();

		var days_of_traveling = $('#days_of_traveling1').val();

		var traveling_date = $('#traveling_date1').val();

		var vehicle_type = $('#vehicle_type1').val();

		var travel_type = $('#travel_type1').val();

		var places_to_visit = $('#places_to_visit1').val();

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
		var permit = $('#permit1').val();
		var toll_parking = $('#toll_parking1').val();
		var driver_allowance = $('#driver_allowance1').val();
		var total_tour_cost = $('#total_tour_cost1').val();
		var quotation_date = $('#quotation_date').val();
 
		var base_url = $('#base_url').val();

		if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage"); return false; }

		$('#btn_quotation_save').button('loading');

		$.ajax({

			type:'post',

			url: base_url+'controller/package_tour/quotation/car_rental/quotation_update.php',

			data:{quotation_id : quotation_id, enquiry_id : enquiry_id , total_pax : total_pax, days_of_traveling : days_of_traveling,traveling_date : traveling_date,vehicle_type : vehicle_type, travel_type : travel_type, places_to_visit : places_to_visit,vehicle_name : vehicle_name, from_date : from_date, to_date : to_date,trip_type : trip_type, route : route,extra_km_cost : extra_km_cost , extra_hr_cost : extra_hr_cost, daily_km : daily_km, subtotal : subtotal,markup_cost : markup_cost,markup_cost_subtotal : markup_cost_subtotal, taxation_id : taxation_id, service_tax : service_tax , service_tax_subtotal : service_tax_subtotal, permit : permit, toll_parking : toll_parking, driver_allowance : driver_allowance , total_tour_cost : total_tour_cost, customer_name : customer_name,quotation_date : quotation_date,email_id : email_id, mobile_no : mobile_no},
			success: function(message){			
                	$('#btn_quotation_update').button('reset');
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
					        	  $('#quotation_update_modal').modal('hide');
					        	 document.location.reload();
					        	 //quotataion_list_reflect();
						        }
						      }
						});
					}

                }  
		});

	}
});

        	 
</script>
