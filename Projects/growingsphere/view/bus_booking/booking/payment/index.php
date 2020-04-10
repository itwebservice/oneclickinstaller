<?php

include "../../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<div class="row text-right mg_bt_20">

	<div class="col-xs-12">

		<button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>

		<button class="btn btn-info btn-sm ico_left" id="btn_save_modal" onclick="save_modal()"><i class="fa fa-plus"></i>Receipt</button>

	</div>

</div>



<div class="app_panel_content Filter-panel">

	<div class="row">

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

		        <select name="cust_type_filter" id="cust_type_filter" style="width:100%" onchange="dynamic_customer_load(this.value,'company_filter');company_name_reflect();" title="Customer Type">

		            <?php get_customer_type_dropdown(); ?>
		            
		            
					
                    

		        </select>

	    </div>

	    <div  id="company_div" class="hidden">

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10" id="customer_div">    

	    </div> 

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<select name="booking_id_filter" id="booking_id_filter" style="width:100%" title="Booking ID">
 
		   	<?php   get_bus_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id,$role_id) ?>

		    </select>

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<select name="payment_mode_filter" id="payment_mode_filter" class="form-control" title="Mode">

				<?php get_payment_mode_dropdown(); ?>

			</select>

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10 hidden">

			<select name="financial_year_id_filter" id="financial_year_id_filter" title="Financial Year">

				<?php get_financial_year_dropdown(); ?>

			</select>

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="payment_from_date_filter" name="payment_from_date_filter" placeholder="From Date" title="From Date">

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">

			<input type="text" id="payment_to_date_filter" name="payment_to_date_filter" placeholder="To Date" title="To Date">

		</div>

	</div>

	<div class="row">

		<div class="col-xs-12 text-center">

			<button class="btn btn-sm btn-info ico_right" onclick="list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>

		</div>

	</div>

</div>





<div id="div_modal"></div>

<div id="div_list" class="main_block"></div>





<script>

$('#payment_from_date_filter, #payment_to_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });

$('#customer_id_filter, #booking_id_filter,#cust_type_filter').select2();

dynamic_customer_load('','');

function save_modal()

{
	var branch_status = $('#branch_status').val();
	$('#btn_save_modal').button('loading');

	$.post('payment/save_modal.php', { branch_status : branch_status}, function(data){

		$('#btn_save_modal').button('reset');

		$('#div_modal').html(data);

	});

}

function list_reflect()

{
	var branch_status = $('#branch_status').val();
	var customer_id = $('#customer_id_filter').val();

	var booking_id = $('#booking_id_filter').val();

	var payment_mode = $('#payment_mode_filter').val();

	var financial_year_id = $('#financial_year_id_filter').val();

	var payment_from_date = $('#payment_from_date_filter').val();

	var payment_to_date = $('#payment_to_date_filter').val();

	var cust_type = $('#cust_type_filter').val();

	var company_name = $('#company_filter').val();



	$.post('payment/list_reflect.php', { customer_id : customer_id, booking_id : booking_id, payment_mode : payment_mode, financial_year_id : financial_year_id, payment_from_date : payment_from_date, payment_to_date : payment_to_date , cust_type : cust_type, company_name : company_name, branch_status : branch_status }, function(data){

		$('#div_list').html(data);

	});

}

list_reflect();

function update_modal(payment_id)

{	
	var branch_status = $('#branch_status').val();
	$.post('payment/update_modal.php', { payment_id : payment_id, branch_status : branch_status }, function(data){

		$('#div_modal').html(data);

	});

}

function excel_report()

	{

		var customer_id = $('#customer_id_filter').val();

		var booking_id = $('#booking_id_filter').val();

		var payment_from_date = $('#payment_from_date_filter').val();

		var payment_to_date = $('#payment_to_date_filter').val();

		var financial_year_id = $('#financial_year_id_filter').val();

		var payment_mode = $('#payment_mode_filter').val();

		var cust_type = $('#cust_type_filter').val();

		var company_name = $('#company_filter').val();
		var branch_status = $('#branch_status').val();
		 

		window.location = 'payment/excel_report.php?customer_id='+customer_id+'&booking_id='+booking_id+'&payment_from_date='+payment_from_date+'&payment_to_date='+payment_to_date+'&financial_year_id='+financial_year_id+'&payment_mode='+payment_mode+'&cust_type='+cust_type+'&company_name='+company_name+'&branch_status='+branch_status;

	}



</script>

<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>