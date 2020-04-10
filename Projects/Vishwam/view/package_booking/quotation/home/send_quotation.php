<?php 
include "../../../../model/model.php";
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$branch_status = $_POST['branch_status'];
$email_id = $_POST['email_id'];
$mobile_no = $_POST['mobile_no'];

$query = "select * from package_tour_quotation_master where email_id = '$email_id' ";
if($role != 'Admin' && $role!='Branch Admin'){
	$query .= " and emp_id='$emp_id'";
}
if($branch_status=='yes' && $role=='Branch Admin'){
	$query .= " and branch_admin_id = '$branch_admin_id'";
}
if($branch_admin_id != '' && $role=='Branch Admin'){
	$query .= " and branch_admin_id = '$branch_admin_id'";
}
$sq_query = mysql_query($query);
?>
<div class="modal fade" id="quotation_send_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg"  role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Send Quotation</h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-xs-12">
				<input type="checkbox" id="check_all" name="check_all" onClick="select_all_check(this.id,'custom_package')">&nbsp;&nbsp;&nbsp;<span style="text-transform: initial;">Check All</span>
			</div>
		</div>
	  <div class="row">
	  <div class="col-xs-12">
      	<div class="table-responsive">
		  <table class="table table-hover table-bordered no-marg" id="tbl_tour_list">
		    <tr class="table-heading-row">
		      <th></th>
		      <th>SR No.</th>
		      <th>Quotation ID</th>
		      <th>Package Name</th>
		      <th>Days/Nights</th>
		      <th>Quotation Cost</th>
		    </tr> 
		    <?php 
		    $quotation_cost = 0;  $count  = 1;
		    while($row_tours = mysql_fetch_assoc($sq_query)){
		    	$sq_tours_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$row_tours[package_id]'"));
		    	$sq_cost = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id='$row_tours[quotation_id]'"));
			    $quotation_cost = $row_tours['train_cost'] + $row_tours['flight_cost'] + $row_tours['cruise_cost'] + $row_tours['visa_cost'] + $sq_cost['total_tour_cost'] + $row_tours['guide_cost'] + $row_tours['misc_cost'];
				$quotation_date = $row_tours['quotation_date'];
				$yr = explode("-", $quotation_date);
				$year =$yr[0];
				?>
			    <tr>
			       <td><input type="checkbox" value="<?php echo $row_tours['quotation_id']; ?>" id="<?php echo $row_tours['quotation_id']; ?>" name="custom_package" class="custom_package"/></td> 
			       <td><?php echo $count; ?></td>
			       <td><?php echo get_quotation_id($row_tours['quotation_id'],$year); ?></td>
			       <td><?php echo $sq_tours_package['package_name']; ?></td>
			       <td><?php echo $sq_tours_package['total_days'].'D/'.$sq_tours_package['total_nights'].'N'; ?></td>
			       <td><?= number_format($quotation_cost,2) ?></td>
			    </tr>
			<?php $count++;
			}
		    ?>
		  </table>
		</div>
		</div>
		</div>
		<div class="row text-center">
			<div class="col-xs-12 mg_tp_20">
				<h3 class="editor_title" onclick="check_what_app()">
					<input type="checkbox" id="custom_package_msg" name="custom_package_msg">&nbsp;&nbsp;&nbsp;<span style="text-transform: initial;">Send quotations to Whatsapp also</span>
				</h3>
			</div>
			<div class="col-md-12 mg_tp_20">
				<button class="btn btn-sm btn-success" id="btn_quotation_send" onclick="multiple_quotation_mail();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
			</div>
		</div>
      </div>  
    </div>
  </div>
</div>
<script>
$('#quotation_send_modal').modal('show');
function select_all_check(id,custom_package){
	var checked = $('#'+id).is(':checked');
	// Select all
	if(checked){
		$('.custom_package1').each(function() {
			$(this).prop("checked",true);
		});
	}
	else{
		// Deselect All
		$('.custom_package1').each(function() {
			$(this).prop("checked",false);
		});
	}
}

function multiple_quotation_mail()
{
	 var quotation_id_arr = new Array();
	 var msg_status = 'false';

	 $('input[name="custom_package_msg"]:checked').each(function(){
			msg_status = 'true';
	 });
	 var base_url = $('#base_url').val();
		$('input[name="custom_package"]:checked').each(function(){
			quotation_id_arr.push($(this).val());
		});
		if(quotation_id_arr.length==0){
			error_msg_alert('Please select at least one quotation!');
			return false;
		}	
	$('#btn_quotation_send').button('loading'); 
	$.ajax({
			type:'post',
			url: base_url+'controller/package_tour/quotation/quotation_email_send.php',
			data:{ quotation_id_arr : quotation_id_arr,msg_status : msg_status },
			success: function(message){
					msg_alert(message);
					$('#btn_quotation_send').button('reset'); 
					$('#quotation_send_modal').modal('hide');             	
                }  
		}); 
}
</script>
<script src="<?php echo BASE_URL ?>view/package_booking/quotation/js/quotation.js"></script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>