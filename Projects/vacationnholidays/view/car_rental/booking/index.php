<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
$emp_id= $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='car_rental/booking/index.php'"));
$branch_status = $sq['branch_status'];
 
?>
 <input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<?= begin_panel('Car Rental Booking',56) ?>
	<div class="app_panel_content">
		<input type="hidden" value="<?= $emp_id ?>" id="emp_id"/>
		<div class="row text-right mg_bt_10">
			<div class="col-xs-12">
			    <button class="btn btn-excel btn-sm mg_bt_10" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
				<button class="btn btn-info btn-sm ico_left mg_bt_10" onclick="save_modal()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Booking</button>
			</div>
		</div>

		<div class="app_panel_content Filter-panel">
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
				        <select name="cust_type_filter" class="form-control" id="cust_type_filter" onchange="dynamic_customer_load(this.value,'company_filter'); company_name_reflect();" title="Customer Type">
				            <?php get_customer_type_dropdown(); ?>
				            
				            
							
		                    
				        </select>
			    </div>
			    <div id="company_div" class="hidden">
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10" id="customer_div">		
				</div>	
				<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
					<input type="text" id="traveling_date_from_filter" name="traveling_date_from_filter" placeholder="From Date" title="From Date">
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
					<input type="text" id="traveling_date_to_filter" name="traveling_date_to_filter" placeholder="To Date" title="To Date">
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
					<button class="btn btn-sm btn-info ico_right" onclick="booking_list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
				</div>
			</div>
		</div>


		<div id="div_booking_list" class="main_block"></div>
		<div id="div_booking_update"></div>
		<div id="div_car_content_display"></div>
	</div>
</div>
 <?= end_panel() ?>
 <script src="<?php echo BASE_URL ?>js/app/field_validation.js"></script>

<script>
$('#customer_id_filter,#cust_type_filter').select2();
$('#traveling_date_from_filter, #traveling_date_to_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });
dynamic_customer_load('','');
function booking_list_reflect()
{
	var customer_id = $('#customer_id_filter').val();
	var traveling_date_from = $('#traveling_date_from_filter').val();
	var traveling_date_to = $('#traveling_date_to_filter').val();
	var cust_type = $('#cust_type_filter').val();
	var company_name = $('#company_filter').val();
	var branch_status = $('#branch_status').val();
	$.post('booking_list_reflect.php', { customer_id : customer_id, traveling_date_from : traveling_date_from, traveling_date_to : traveling_date_to, cust_type : cust_type, company_name : company_name , branch_status : branch_status }, function(data){
		$('#div_booking_list').html(data);
	});
}
booking_list_reflect();
function save_modal(){
	var branch_status = $('#branch_status').val();
	$.post('booking_save_modal.php', { branch_status : branch_status }, function(data){
		$('#div_car_content_display').html(data);
	});

}
function customer_info_load(offset='')
{
	 var customer_id = $('#customer_id'+offset).val();
	 var base_url = $('#base_url').val();
	if(customer_id==0&&customer_id!=''){
		$('#cust_details').addClass('hidden');
	    $('#new_cust_div').removeClass('hidden');
		$.ajax({
		type:'post',
		url:base_url+'view/load_data/new_customer_info.php',
		data:{},
		success:function(result){
			$('#new_cust_div').html(result);
			}
		});
	}
	else{
		if(customer_id!=''){
			$('#new_cust_div').addClass('hidden');
			$('#cust_details').removeClass('hidden');
			$.ajax({
				type:'post',
				url:base_url+'view/load_data/customer_info_load.php',
				data:{ customer_id : customer_id },
				success:function(result){
					result = JSON.parse(result);
					$('#mobile_no'+offset).val(result.contact_no);
					$('#email_id'+offset).val(result.email_id);
					if(result.company_name != ''){
						$('#company_name1'+offset).removeClass('hidden');
						$('#company_name1'+offset).val(result.company_name);	
					}
					else
					{
						$('#company_name1'+offset).addClass('hidden');
					}
					if(result.payment_amount != '' || result.payment_amount != '0'){
						$('#credit_amount'+offset).removeClass('hidden');
						$('#credit_amount'+offset).val(result.payment_amount);	
						if(result.company_name != ''){
							$('#credit_amount'+offset).addClass('mg_tp_10');}
						else{
							$('#credit_amount'+offset).removeClass('mg_tp_10');
							$('#credit_amount'+offset).addClass('mg_bt_10');
						}
					}
					else{
						$('#credit_amount'+offset).addClass('hidden');
					}
				}
			});
		}
    }
}

function booking_update_modal(booking_id)
{	
	var branch_status = $('#branch_status').val();
	$.post('booking_update_modal.php', { booking_id : booking_id, branch_status : branch_status}, function(data){
		$('#div_booking_update').html(data);
	});
}

function get_enquiry_details(offset)
{
	var enquiry_id = $('#enquiry_id'+offset).val();

	$.ajax({
		type:'post',
		dataType: "json",
		url:'get_enquiry_details.php',
		data:{ enquiry_id : enquiry_id },
		success:function(result){
			$('#total_pax'+offset).val(result.total_pax);
			$('#days_of_traveling'+offset).val(result.days_of_traveling);
			$('#traveling_date'+offset).val(result.traveling_date);
			$('#enquiry_date'+offset).val(result.enquiry_date);
			$('#vehicle_type'+offset).val(result.vehicle_type);			
			$('#travel_type'+offset).val(result.travel_type);			
			$('#places_to_visit'+offset).val(result.places_to_visit);
		}
	});
}
function vehicle_dropdown_reflect(vendor_id_dropdown, vehicle_id_dropdown)
{
	var vendor_id = $('#'+vendor_id_dropdown).val();

	$.post('vehicle_dropdown_reflect.php', { vendor_id : vendor_id }, function(data){
		$('#'+vehicle_id_dropdown).html(data);
	});
}

function calculate_total_fees(offset="")
{
	var rate_per_km = $('#rate_per_km'+offset).val();
	var daily_min_average = $('#daily_min_average'+offset).val();
	var extra_km = $('#extra_km'+offset).val();	
	var actual_cost = $('#actual_cost'+offset).val();	
	var service_tax = $('#service_tax'+offset).val();

	var driver_allowance = $('#driver_allowance'+offset).val();
	var permit_charges = $('#permit_charges'+offset).val();
	var toll_and_parking = $('#toll_and_parking'+offset).val();
	var state_entry_tax = $('#state_entry_tax'+offset).val();
	

	if(rate_per_km==""){ rate_per_km = 0; }
	if(daily_min_average==""){ daily_min_average = 0; }
	if(extra_km==""){ extra_km = 0; }
	if(actual_cost==""){ actual_cost = 0; }
	if(service_tax==""){ service_tax = 0; }
	if(driver_allowance==""){ driver_allowance = 0; }
	if(permit_charges==""){ permit_charges = 0; }
	if(toll_and_parking==""){ toll_and_parking = 0; }
	if(state_entry_tax==""){ state_entry_tax = 0; }
	

	var km_total_fee = ( parseFloat(rate_per_km) * parseFloat(daily_min_average) );
	km_total_fee = km_total_fee.toFixed(2);
	$('#km_total_fee'+offset).val(km_total_fee);
	
	var service_tax_cost = (parseFloat(km_total_fee)/100)*parseFloat(service_tax);
	service_tax_cost = Math.round(service_tax_cost);
	$('#service_tax_subtotal'+offset).val(service_tax_cost.toFixed(2));
	var total_cost = ( parseFloat(km_total_fee) + parseFloat(service_tax_cost) );
	total_cost = total_cost.toFixed(2);
	$('#total_cost'+offset).val(total_cost);

	var total_fees = parseFloat(total_cost) + parseFloat(driver_allowance) + parseFloat(permit_charges) + parseFloat(toll_and_parking) + parseFloat(state_entry_tax);
	total_fees = total_fees.toFixed(2);
	$('#total_fees'+offset).val(total_fees);
}
	function excel_report()
	{
		var customer_id = $('#customer_id_filter').val();
		var from_date = $('#traveling_date_from_filter').val();
		var to_date = $('#traveling_date_to_filter').val();
		var cust_type = $('#cust_type_filter').val();
		var company_name = $('#company_filter').val();
		var branch_status = $('#branch_status').val();
		window.location = 'excel_report.php?customer_id='+customer_id+'&from_date='+from_date+'&to_date='+to_date+'&cust_type='+cust_type+'&company_name='+company_name+'&branch_status='+branch_status;
	}
function company_name_reflect()
{  
	var cust_type = $('#cust_type_filter').val();
	var branch_status = $('#branch_status').val();
		$.post('company_name_load.php', { cust_type : cust_type, branch_status : branch_status }, function(data){
		 if(cust_type=='Corporate'){
		  		$('#company_div').addClass('company_class');	
		    }
		    else
		    {
		    	$('#company_div').removeClass('company_class');		
		    }
		 $('#company_div').html(data);
	});
}
company_name_reflect();

//*******************Get Dynamic Customer Name Dropdown**********************//
function dynamic_customer_load(cust_type, company_name)
{
  var cust_type = $('#cust_type_filter').val();
  var company_name = $('#company_filter').val();
  var branch_status = $('#branch_status').val();
    $.get("get_customer_dropdown.php", { cust_type : cust_type , company_name : company_name, branch_status : branch_status}, function(data){
    $('#customer_div').html(data);
  });   
}


function car_display_modal(booking_id)
{
	$.post('view/index.php', { booking_id : booking_id }, function(data){
		$('#div_car_content_display').html(data);
	});
}
</script>
<?php
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
