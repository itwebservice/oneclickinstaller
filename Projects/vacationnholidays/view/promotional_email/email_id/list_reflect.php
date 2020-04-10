<?php 
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
$start=$_POST['start'];
$limit=$_POST['limit'];
$count = 0;

$query1 = "select * from sms_email_id where 1";
if($branch_status=='yes' && $role=='Branch Admin'){
	$query1 .=" and branch_admin_id = '$branch_admin_id'";
}
$query1 .= " LIMIT $start, $limit";
$sq_email_no = mysql_query($query1);
	
while($row_email_no = mysql_fetch_assoc($sq_email_no)){
	$count++;
	?>
	<tr>
		<td>
			<input type="checkbox" id="chk_mobile_no_<?= $count ?>" name="chk_email_id" value="<?= $row_email_no['email_id_id'] ?>">
		</td>
		<td><?= $count ?></td>
		<td><?= $row_email_no['email_id'] ?></td>
		<td>
			<?php
			$group_name = "";
			$sq_group_entries = mysql_query("select * from email_group_entries where email_id_id='$row_email_no[email_id_id]'");
			while($row_group_entry = mysql_fetch_assoc($sq_group_entries)){
				$email_group_id = $row_group_entry['email_group_id'];
				
				$sq_sms_group = mysql_fetch_assoc(mysql_query("select * from email_group_master where email_group_id='$email_group_id'"));
				$email_group_name = $sq_sms_group['email_group_name'];

				$group_name .= $email_group_name.', ';	
			}
			echo trim($group_name, ', ')
			?>
		</td>
		<td>
			<button class="btn btn-info btn-sm" onclick="email_id_edit_modal(<?= $row_email_no['email_id_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></button>
		</td>
	</tr>
	<?php
}
?>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
