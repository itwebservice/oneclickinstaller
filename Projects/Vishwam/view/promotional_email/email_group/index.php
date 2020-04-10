<?php
include "../../../model/model.php";
$branch_status = $_POST['branch_status'];
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
	
<div class="row text-right mg_bt_10">
	<div class="col-md-12">
		<button class="btn btn-info btn-sm ico_left" data-toggle="modal" data-target="#email_group_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;New Email Group</button>
	</div>
</div>

<div id="div_sms_group_list"></div>


<?php include_once('email_group_save_modal.php'); ?>
<script>
function email_group_list_reflect()
{
    var branch_status = $('#branch_status').val();
	$.post('email_group/email_group_list_reflect.php', { branch_status : branch_status  }, function(data){
		$('#div_sms_group_list').html(data);
	});
}
email_group_list_reflect();
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>