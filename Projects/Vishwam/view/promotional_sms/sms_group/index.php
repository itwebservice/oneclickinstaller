<?php
include "../../../model/model.php";
$branch_status = $_POST['branch_status'];
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >

<div class="row text-right mg_bt_10">
	<div class="col-md-12">
		<button class="btn btn-info btn-sm ico_left" data-toggle="modal" data-target="#sms_group_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;New SMS Group</button>
	</div>
</div>


<div id="div_sms_group_list" class="loader_parent"></div>

<?php include_once('sms_group_save_modal.php'); ?>
<script>
function sms_group_list_reflect()
{
	$('#div_sms_group_list').append('<div class="loader"></div>');
	var branch_status = $('#branch_status').val();
	$.post('sms_group/sms_group_list_reflect.php', {  branch_status : branch_status }, function(data){
		$('#div_sms_group_list').html(data);
	});
}
sms_group_list_reflect();
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>