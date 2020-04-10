<?php
include "../../../../../model/model.php";
include_once('../inc/quotation_hints_modal.php');
/*======******Header******=======*/
require_once('../../../../layouts/admin_header.php');
$role = $_SESSION['role'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq =mysql_fetch_assoc(mysql_query("select * from branch_assign where link='package_booking/quotation/car_flight/car_rental/index.php'"));
$branch_status = $sq['branch_status'];
 
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<?= begin_panel('Car Rental Quotation',42) ?>
  <!--=======Header panel end======-->
  
	<div class="app_panel_content">
	    <div class="row">
	        <div class="col-md-12">
	            <div id="div_id_proof_content">
			        <div class="row mg_bt_20">
			            <div class="col-md-8">
			            </div>
			            <div class="col-md-4 text-right">
			                <button class="btn btn-info btn-sm ico_left" onclick="save_modal()" id="cquot_save"><i class="fa fa-plus"></i>&nbsp;&nbsp;Quotation</button>
			            </div>
			        </div>

					<div class="app_panel_content Filter-panel">
						<div class="row">
							<div class="col-md-4 col-sm-4 col-xs-12 mg_bt_10_xs">
								<input type="text" id="from_date_filter"  name="from_date_filter" placeholder="From Date" title="From Date" onchange="quotation_list_reflect()">
							</div>
							<div class="col-md-4 col-sm-3 col-xs-12 mg_bt_10_xs">
								<input type="text" id="to_date_filter" name="to_date_filter" placeholder="To Date" title="To Date" onchange="quotation_list_reflect()">
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 mg_bt_10_xs">
								<select name="quotation_id" title="Select Quotation" id="quotation_id" onchange="quotation_list_reflect()" style="width:100%">
									<option value="">Select Quotation</option>
									<?php 
									$query = "select * from car_rental_quotation_master where 1";
									if($role=='Sales' || $role=='Backoffice'){
										$query .= " and emp_id='$emp_id'";
									}
									if($branch_status=='yes' && $role!='Admin'){
											$query .= " and branch_admin_id = '$branch_admin_id'";
									}
									if($branch_status=='yes' && $role=='Branch Admin'){
										$query .= " and branch_admin_id='$branch_admin_id'";
									}
									$query .= " order by quotation_id desc";
									$sq_quotation = mysql_query($query);
									while($row_quotation = mysql_fetch_assoc($sq_quotation)){
									$quotation_date = $row_quotation['quotation_date'];
									$yr = explode("-", $quotation_date);
									$year =$yr[0];
										?>
										<option value="<?= $row_quotation['quotation_id'] ?>"><?= get_quotation_id($row_quotation['quotation_id'],$year) ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
					</div>

					<div id="div_quotation_list_reflect" class="main_block"></div>
					<div id="div_quotation_form"></div>
					<div id="div_quotation_update"></div>
					<div id="div_modal_content"></div>

	            </div>
	        </div>
	    </div>
	</div>
<?= end_panel() ?>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>

<script>
$('#quotation_id').select2();
$('#from_date_filter, #to_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });

function quotation_list_reflect()
{
	var from_date = $('#from_date_filter').val();
	var to_date = $('#to_date_filter').val();
	var quotation_id = $('#quotation_id').val();
	var branch_status = $('#branch_status').val();

	$.post('quotation_list_reflect.php', { from_date : from_date, to_date : to_date, quotation_id : quotation_id , branch_status : branch_status}, function(data){
		$('#div_quotation_list_reflect').html(data);
	})
}
quotation_list_reflect();
 
function save_modal()
{
	var branch_status = $('#branch_status').val();
	$('#cquot_save').button('loading');
	$.post('save/index.php', { branch_status : branch_status}, function(data){
		$('#div_quotation_form').html(data);
		$('#cquot_save').button('reset');
	});
}
function update_modal(quotation_id)
{
	var branch_status = $('#branch_status').val();
	$.post('update/index.php', { quotation_id : quotation_id , branch_status : branch_status}, function(data){
		$('#div_quotation_update').html(data);
	});
}

</script>

<?php
/*======******Footer******=======*/
require_once('../../../../layouts/admin_footer.php'); 
?>