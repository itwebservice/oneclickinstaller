<?php
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
$start=$_POST['start'];
$limit=$_POST['limit'];
$count = $start;
?>

<?php
$query = "select * from sms_mobile_no where 1 ";
if($branch_status=='yes' && $role=='Branch Admin'){
	$query .=" and branch_admin_id = '$branch_admin_id'";
}
$query .= " LIMIT $start, $limit";
$sq_mobile_no = mysql_query($query);
while($row_mobile_no = mysql_fetch_assoc($sq_mobile_no)){
	++$count;
	?>
	<tr>
		<td>
			<input type="checkbox" id="chk_mobile_no_<?= $count ?>" name="chk_mobile_no" value="<?= $row_mobile_no['mobile_no_id'] ?>">
		</td>
		<td><?= $count ?></td>
		<td><?= $row_mobile_no['mobile_no'] ?></td>
		<td>
			<?php
			$group_name = "";
			$sq_group_entries = mysql_query("select * from sms_group_entries where mobile_no_id='$row_mobile_no[mobile_no_id]'");
			while($row_group_entry = mysql_fetch_assoc($sq_group_entries)){
				$sms_group_id = $row_group_entry['sms_group_id'];
				
				$sq_sms_group = mysql_fetch_assoc(mysql_query("select * from sms_group_master where sms_group_id='$sms_group_id'"));
				$sms_group_name = $sq_sms_group['sms_group_name'];

				$group_name .= $sms_group_name.', ';	
			}
			echo trim($group_name, ', ')
			?>
		</td>
		<td><button class="btn btn-info btn-sm" onclick="mobile_no_edit_modal('<?= $row_mobile_no['mobile_no_id'] ?>')" title="Edit No."><i class="fa fa-pencil-square-o"></i></button></td>
	</tr>
	<?php
}
?>

<script src="<?= BASE_URL ?>view/promotional_sms/js/mobile_no.js"></script>
<script>
$('#limit').val('<?php echo $limit;?>');
$('#start').val('<?php echo $start;?>');
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>