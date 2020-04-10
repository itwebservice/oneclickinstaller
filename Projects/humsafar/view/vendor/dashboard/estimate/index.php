<?php 
include_once('../../../../model/model.php');
$role = $_SESSION['role'];
$emp_id= $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
include_once('estimate_save_modal.php');
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>">
<div class="row text-right mg_bt_10"> <div class="col-xs-12">
	<button class="btn btn-excel btn-sm mg_bt_10" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>&nbsp;&nbsp;
	<button class="btn btn-info btn-sm ico_left mg_bt_10" data-toggle="modal" data-target="#estimate_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;Purchase Costing</button>
</div> </div>

	
	<div class="app_panel_content Filter-panel">
		<div class="row">
			<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
				<select name="estimate_type2" id="estimate_type2" title="Purchase Type" onchange="payment_for_data_load(this.value, 'div_payment_for_content2', '2')">
					<option value="">Purchase Type</option>
					<?php 
					$sq_estimate_type = mysql_query("select * from estimate_type_master order by estimate_type");
					while($row_estimate = mysql_fetch_assoc($sq_estimate_type)){
						?>
						<option value="<?= $row_estimate['estimate_type'] ?>"><?= $row_estimate['estimate_type'] ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div id="div_payment_for_content2"></div>
			<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
				<select name="vendor_type2" id="vendor_type2" title="Supplier Type" onchange="vendor_type_data_load(this.value, 'div_vendor_type_content2', '2')">
					<option value="">Supplier Type</option>
					<?php 
					$sq_vendor = mysql_query("select * from vendor_type_master order by vendor_type");
					while($row_vendor = mysql_fetch_assoc($sq_vendor)){
						?>
						<option value="<?= $row_vendor['vendor_type'] ?>"><?= $row_vendor['vendor_type'] ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div id="div_vendor_type_content2"></div>
		</div>
		<div class="row">
			<div class="col-md-3 col-sm-6 col-xs-12">
				<button class="btn btn-sm btn-info ico_right" onclick="vendor_estimate_list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
			</div>
		</div>
	</div>

<div id="div_vendor_estimate_list" class="main_block loader_parent"></div>

<script>
function vendor_estimate_list_reflect()
{
	$('#div_vendor_estimate_list').append('<div class="loader"></div>');
	var estimate_type = $('#estimate_type2').val();
	var vendor_type = $('#vendor_type2').val();
	var branch_status = $('#branch_status').val();
	var estimate_type_id = get_estimate_type_id('estimate_type2', '2');
 	var vendor_type_id = get_vendor_type_id('vendor_type2', '2');

	$.post('estimate/vendor_estimate_list_reflect.php', { estimate_type : estimate_type, estimate_type_id : estimate_type_id, vendor_type : vendor_type, vendor_type_id : vendor_type_id , branch_status : branch_status}, function(data){
		$('#div_vendor_estimate_list').html(data);
	});
}
vendor_estimate_list_reflect();


function calculate_estimate_amount(offset='')
{
	var basic_cost = $('#basic_cost'+offset).val();
	var non_recoverable_taxes = $('#non_recoverable_taxes'+offset).val();
	var service_charge = $('#service_charge'+offset).val();
	var other_charges = $('#other_charges'+offset).val();
	var taxation_id = $('#taxation_id'+offset).val();
	var service_tax = $('#service_tax'+offset).val();
	var service_tax_subtotal = $('#service_tax_subtotal'+offset).val();
	var discount = $('#discount'+offset).val();
	var our_commission = $('#our_commission'+offset).val();
	var tds = $('#tds'+offset).val();

	if(basic_cost==""){ basic_cost = 0; }
	if(non_recoverable_taxes==""){ non_recoverable_taxes = 0; }
	if(service_charge==""){ service_charge = 0; }
	if(other_charges==""){ other_charges = 0; }
	if(service_tax==""){ service_tax = 0; }
	if(service_tax_subtotal==""){ service_tax_subtotal = 0; }
	if(discount==""){ discount = 0; }
	if(our_commission==""){ our_commission = 0; }
	if(tds==""){ tds = 0; }

	var total_charge = parseFloat(service_charge) + parseFloat(other_charges);
	var service_tax_subtotal = (parseFloat(total_charge)/100)*parseFloat(service_tax);
	service_tax_subtotal = Math.round(service_tax_subtotal);
	$('#service_tax_subtotal'+offset).val(service_tax_subtotal.toFixed(2));

	var net_total = parseFloat(basic_cost) + parseFloat(non_recoverable_taxes) + parseFloat(total_charge) + parseFloat(service_tax_subtotal) - parseFloat(discount) - parseFloat(our_commission) + parseFloat(tds);
	net_total = net_total.toFixed(2);
	$('#net_total'+offset).val(net_total);
}

function excel_report()
{
	var estimate_type = $('#estimate_type2').val();
	var vendor_type = $('#vendor_type2').val();
	var vendor_type_id = get_vendor_type_id('vendor_type2', '2');
	var estimate_type_id = get_estimate_type_id('estimate_type2', '2');
	var branch_status = $('#branch_status').val();

	window.location = 'estimate/excel_report.php?estimate_type='+estimate_type+'&vendor_type='+vendor_type+'&vendor_type_id='+vendor_type_id+'&estimate_type_id='+estimate_type_id+'&branch_status='+branch_status;


}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>