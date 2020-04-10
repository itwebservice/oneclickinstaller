<?php
include "../../model/model.php";

$tourwise_traveler_id = $_POST['tourwise_traveler_id'];
$emp_id = $_POST['emp_id'];

$sq_incentive = mysql_fetch_assoc(mysql_query("select * from booker_incentive_group_tour where tourwise_traveler_id='$tourwise_traveler_id' and emp_id='$emp_id'"));
?>

<form id="frm_incentive_edit">
<div class="modal fade" id="group_tour_incentive_edit_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Group Tour Incentive Edit</h4>
      </div>
      <div class="modal-body text-center">
    
		<div class="row">
			<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
				<input type="text" class="form-control" id="basic_amount" name="basic_amount" placeholder="Basic Amount" title="Basic Amount" onchange="validate_balance(this.id);incentive_calculate('basic_amount', 'tds', 'incentive_amount')"  value="<?= $sq_incentive['basic_amount'] ?>">
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
				<input type="text" class="form-control" id="tds" name="tds" placeholder="TDS(%)" title="TDS" onchange="validate_balance(this.id);incentive_calculate('basic_amount', 'tds', 'incentive_amount')"  value="<?= $sq_incentive['tds'] ?>">
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
				<input type="text" class="form-control" id="incentive_amount" name="incentive_amount" placeholder="Incentive Amount" title="Incentive Amount" value="<?= $sq_incentive['incentive_amount'] ?>" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<button class="btn btn-sm btn-success"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
			</div>
		</div>

      </div>
    </div>
  </div>
</div>
</form>

<script>
	$('#group_tour_incentive_edit_modal').modal('show');
	$(function(){
		$('#frm_incentive_edit').validate({
			rules:{
				basic_amount: { required : true, number:true },
				tds: { required : true, number:true },
				incentive_amount: { required:true },
			},
			submitHandler:function(form){
				var tourwise_traveler_id = <?= $tourwise_traveler_id ?>;
				var emp_id = <?= $emp_id ?>;
				var basic_amount = $('#basic_amount').val();
				var tds = $('#tds').val();
				var incentive_amount = $('#incentive_amount').val();
				var base_url = $('#base_url').val();
				$.ajax({
					type:'post',
					url: base_url+'controller/booker_incentive/group_tour_incentive_update.php',
					data:{ tourwise_traveler_id : tourwise_traveler_id, emp_id : emp_id, basic_amount : basic_amount, tds : tds, incentive_amount : incentive_amount },
					success:function(result){
						$('#group_tour_incentive_edit_modal').modal('hide');
						$('#group_tour_incentive_edit_modal').on('hidden.bs.modal', function () {
						    msg_alert(result);
							booking_list_reflect();
						});
					}
				});
			}
		});
	});
</script>