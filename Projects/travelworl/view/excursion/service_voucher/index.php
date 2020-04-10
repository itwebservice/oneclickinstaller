<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='package_booking/service_voucher/hotel_voucher/index.php'"));
$branch_status = $sq['branch_status'];
?>
<?= begin_panel('Excursion Service Voucher',45) ?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >

<div class="text-center mg_bt_20">
	<label for="rd_package_tour" class="app_dual_button active">
        <input type="radio" id="rd_package_tour" name="rd_app_feedback" checked  onchange="feedback_content_reflect()">
        &nbsp;&nbsp;Package Booking
    </label>    
    <label for="rd_hotel_tour" class="app_dual_button">
        <input type="radio" id="rd_hotel_tour" name="rd_app_feedback" onchange="feedback_content_reflect()">
        &nbsp;&nbsp;Excursion Booking
    </label>
</div>

<div id="div_feedback_mail_content"></div>

<?= end_panel() ?>


<script>
	function feedback_content_reflect()
	{
		var id = $('input[name="rd_app_feedback"]:checked').attr('id');
		var branch_status = $('#branch_status').val();
		if(id=="rd_package_tour"){
			$.post('package/index.php', {branch_status : branch_status}, function(data){
				$('#div_feedback_mail_content').html(data);
			});
		}
		if(id=="rd_hotel_tour"){
			$.post('excursion/index.php', { branch_status : branch_status}, function(data){
				$('#div_feedback_mail_content').html(data);
			});
		}
	}
	feedback_content_reflect();
</script>
<?php
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>