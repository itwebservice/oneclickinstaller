<?php include "../../../model/model.php";
$branch_admin_id = $_SESSION['branch_admin_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$login_id = $_SESSION['login_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$emp_id = $_SESSION['emp_id'];

$search = $_POST['search'];
$limit = $_POST['limit'];
$start = $_POST['start'];
$branch_status = $_POST['branch_status'];

$count = 1;
$enq_count = "SELECT * FROM `enquiry_master` left join enquiry_master_entries as ef on enquiry_master.entry_id=ef.entry_id where enquiry_master.status!='Disabled' and (enquiry_master.mobile_no like '%$search%' or enquiry_master.name like '%$search%' or enquiry_master.email_id like '%$search%' or enquiry_master.enquiry_id like '%$search%')";
if($role=='Branch Admin'){
	$enq_count .=" and branch_admin_id = '$branch_admin_id'";
}
if($financial_year_id!=""){
	$enq_count .=" and financial_year_id='$financial_year_id'";
}
if($branch_status=='yes' && $role!='Admin'){
		$enq_count .= " and branch_admin_id = '$branch_admin_id'";
}
if($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	$enq_count .=" and assigned_emp_id='$emp_id' ";	
}
$enq_count .= " ORDER BY enquiry_master.enquiry_id DESC ";
$enquiry_count=mysql_num_rows(mysql_query($enq_count));

$query = "SELECT * FROM `enquiry_master` left join enquiry_master_entries as ef on enquiry_master.entry_id=ef.entry_id where enquiry_master.status!='Disabled' and (enquiry_master.mobile_no like '%$search%' or enquiry_master.name like '%$search%' or enquiry_master.email_id like '%$search%' or enquiry_master.enquiry_id like '%$search%')";
if($role=='Branch Admin'){
	$query .=" and branch_admin_id = '$branch_admin_id'";
}
if($financial_year_id!=""){
	$query .=" and financial_year_id='$financial_year_id'";
}
if($branch_status=='yes' && $role!='Admin'){
		$query .= " and branch_admin_id = '$branch_admin_id'";
}
if($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	$query .=" and assigned_emp_id='$emp_id' ";
}

$query .= " ORDER BY enquiry_master.enquiry_id DESC";
$sq_enquiries = mysql_query($query);
while($row = mysql_fetch_assoc($sq_enquiries)){

				$assigned_emp_id = $row['assigned_emp_id'];
				$sq_emp = mysql_fetch_assoc(mysql_query("select first_name,last_name from emp_master where emp_id='$assigned_emp_id'"));
				$allocated_to = ($assigned_emp_id != 0)?$sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';

				$enquiry_content = $row['enquiry_content'];
				$enquiry_content_arr1 = json_decode($enquiry_content, true);

				$enquiry_status1 = mysql_fetch_assoc(mysql_query("select followup_date,followup_reply,followup_status from enquiry_master_entries where enquiry_id='$row[enquiry_id]' order by entry_id DESC"));
				$followup_date1 = $enquiry_status1['followup_date'];
				if($enquiry_status1['followup_status']=='Active')	{
					$followup_status='Active';
				}
				else{
					$followup_status=$enquiry_status1['followup_status'];
				}
				$date = $row['enquiry_date'];
				$yr = explode("-", $date);
				$year =$yr[0];
			?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= get_enquiry_id($row['enquiry_id'],$year) ?></td>
					<td style="text-transform:capitalize;"><?= $row['name'] ?></td>
					<td><?= $row['enquiry_type'] ?></td>
					<td><?= get_date_user($row['enquiry_date']) ?></td>
					<td><?= get_datetime_user($followup_date1) ?></td>
					<td>
						<a class="btn btn-info btn-sm" href="<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/followup/index.php?enquiry_id=<?php echo $row['enquiry_id'] ?>&branch_status=<?= $branch_status ?>" title="Update Enquiry" target="_blank"><i class="fa fa-reply-all"></i></a>
					</td>
					<?php if($role=='Admin' || $role=='Branch Admin'){ ?>
					<td><?= $allocated_to; ?></td>
					<?php } ?>
					<td><?= $followup_status; ?></td>
					<td>
						<button class="btn btn-info btn-sm" onclick="view_modal(<?= $row['enquiry_id'] ?>)" title="View Enquiry"><i class="fa fa-eye"></i></button>
					</td>
					<td class="hidden">
						<button class="btn btn-<?= $cl ?> btn-sm ico_left" <?= $state ?> onclick="enquiry_status_done(<?= $row['enquiry_id'] ?>)"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;Done</button>
					</td>
					<?php 
					if($role=="Admin" || $role=='Branch Admin'){ ?>
					<td>
						<button class="btn btn-danger btn-sm" onclick="enquiry_status_disable(<?= $row['enquiry_id'] ?>)" title="Disable"><i class="fa fa-times"></i></button>
					</td>
					<?php } ?>
				</tr>
				<?php } ?>

<script>
$('#enquiry_count').html('<?php echo $enquiry_count;?>');
function enquiry_status_done(enquiry_id){
	var base_url = $('#base_url').val();

	$('#vi_confirm_box').vi_confirm_box({
			callback: function(data1){
					if(data1=="yes"){
						$.post(base_url+'controller/attractions_offers_enquiry/enquiry_status.php', { enquiry_id : enquiry_id }, function(data){
					msg_alert(data);
					enquiry_list_reflect(20,0);
				})
					}
				}
	});
}
function enquiry_status_disable(enquiry_id){
	var base_url = $('#base_url').val();
	$('#vi_confirm_box').vi_confirm_box({
			callback: function(data1){
					if(data1=="yes"){
						$.post(base_url+'controller/attractions_offers_enquiry/enquiry_status_disable.php', { enquiry_id : enquiry_id }, function(data){
					msg_alert(data);
					enquiry_list_reflect(20,0);
				})
					}
				}
	});
}
</script>
