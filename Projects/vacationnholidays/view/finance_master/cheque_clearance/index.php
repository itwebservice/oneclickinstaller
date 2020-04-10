<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
 
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='finance_master/cheque_clearance/index.php'"));
$branch_status = $sq['branch_status'];
?>
<?= begin_panel('Cheque Clearance',33) ?>

<div class="app_panel_content">

<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 col-sm-6">
			<select id="status_filter" name="status_filter" title="Status" onchange="list_reflect()">
				<option value="Pending">Pending</option>
				<option value="Cancelled">Cancelled</option>
				<option value="Cleared">Cleared</option>
				<option value="All">All</option>
			</select>
		</div>
	</div>
</div>

<div id="div_list" class="main_block"></div>
<?= end_panel() ?>

<script>
function list_reflect()
{
  var branch_status = $('#branch_status').val();
	var status = $('#status_filter').val();
    $.post('list_reflect.php',{ status : status, branch_status : branch_status }, function(data){
        $('#div_list').html(data);
    });
}
list_reflect();
</script>
<?= end_panel() ?>
<?php
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>