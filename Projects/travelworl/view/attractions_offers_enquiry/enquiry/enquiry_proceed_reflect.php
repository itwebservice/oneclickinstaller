<?php include "../../../model/model.php";
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$login_id = $_SESSION['login_id'];
$emp_id = $_SESSION['emp_id'];

$enquiry_type = $_POST['enquiry_type'];
$enquiry = $_POST['enquiry'];
$enquiry_status_filter = $_POST['enquiry_status'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$emp_id_filter = $_POST['emp_id_filter'];
$branch_status = $_POST['branch_status'];
$branch_filter = $_POST['branch_filter'];
$reference_id_filter=$_POST['reference_id_filter'];
$start=$_POST['start'];
$limit=$_POST['limit'];

//////////Calculate no.of .enquiries Start///////////////////
$enq_count = "SELECT * FROM `enquiry_master` left join enquiry_master_entries as ef on enquiry_master.entry_id=ef.entry_id where enquiry_master.status!='Disabled'";

if($financial_year_id!=""){
	$enq_count .=" and financial_year_id='$financial_year_id'";
}
if($emp_id_filter!=""){
	$enq_count .=" and assigned_emp_id='$emp_id_filter'";
}
elseif($branch_status=='yes' && $role=='Branch Admin'){
  $enq_count .= " and branch_admin_id='$branch_admin_id'";
}
if($enquiry!="" && $enquiry!=='undefined'){
	$enq_count .=" and enquiry='$enquiry' ";
}
if($branch_filter!=""){
	$enq_count .=" and branch_admin_id='$branch_filter' ";
}
if($enquiry_type!=""){
	$enq_count .=" and enquiry_type='$enquiry_type' ";
}
if($reference_id_filter!=""){
	$enq_count .=" and reference_id='$reference_id_filter' ";
}
if($from_date!='' && $from_date!='undefined' && $to_date!="" && $to_date!='undefined'){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$enq_count .=" and (enquiry_date between '$from_date' and '$to_date')";
}
if($branch_status=='yes' && $role!='Admin'){
		$enq_count .= " and branch_admin_id = '$branch_admin_id'";
}
if($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	if($role !='Admin' && $role!='Branch Admin')
	{
		$enq_count .= " and assigned_emp_id='$emp_id' and enquiry_master.status!='Disabled' ";  
		if($enquiry_type!=""){
			$enq_count .=" and enquiry_type='$enquiry_type' ";
		}
		if($reference_id_filter!=""){
			$enq_count .=" and reference_id='$reference_id_filter' ";
		}
		if($from_date!='' && $from_date!='undefined' && $to_date!="" && $to_date!='undefined'){
			$from_date = get_date_db($from_date);
			$to_date = get_date_db($to_date);
			$enq_count .=" and (enquiry_date between '$from_date' and '$to_date')";
		}
		if($enquiry!=""){
			$enq_count .=" and enquiry='$enquiry' ";
		}
	}   
}
if($enquiry_status_filter!='')
{
	if($enquiry_status_filter=='Active')	{
		$enq_count .= " and ef.followup_status='Active'";
	}
	if($enquiry_status_filter=='In-Followup'){
		$enq_count .= " and ef.followup_status='In-Followup' ";
	}
	if($enquiry_status_filter=='Converted')	{
		$enq_count .= " and ef.followup_status='$enquiry_status_filter'";
	}
	if($enquiry_status_filter=='Dropped'){
		$enq_count .= " and ef.followup_status='$enquiry_status_filter'";
	}
}
$enq_count .= " ORDER BY enquiry_master.enquiry_id DESC ";
$enquiry_count=mysql_num_rows(mysql_query($enq_count));
//////////Calculate no.of .enquiries End///////////////////

///////////////////Enquiry table data start////////////////////////////////
$query = "SELECT * FROM `enquiry_master` left join enquiry_master_entries as ef on enquiry_master.entry_id=ef.entry_id where enquiry_master.status!='Disabled'";

if($financial_year_id!=""){
	$query .=" and financial_year_id='$financial_year_id'";
}
if($emp_id_filter!=""){
	$query .=" and assigned_emp_id='$emp_id_filter'";
}
if($branch_status=='yes' && $role=='Branch Admin'){
	$query .=" and branch_admin_id = '$branch_admin_id'";
}	
if($enquiry!="" && $enquiry!=='undefined'){
    $query .=" and enquiry='$enquiry' ";
}		
if($enquiry_type!=""){
	$query .=" and enquiry_type='$enquiry_type' ";
}
if($branch_filter!=""){
	$query .=" and branch_admin_id='$branch_filter' ";
}
if($reference_id_filter!=""){
	$query .=" and reference_id='$reference_id_filter' ";
}
if($branch_status=='yes' && $role!='Admin'){
		$query .= " and branch_admin_id = '$branch_admin_id'";
}
if($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	$query .=" and assigned_emp_id='$emp_id' ";
	if($enquiry_type!=""){
		$query .=" and enquiry_type='$enquiry_type' ";
	}
	if($reference_id_filter!=""){
					$query .=" and reference_id='$reference_id_filter' ";
	}
	if($from_date!='' && $from_date!='undefined' && $to_date!="" && $to_date!='undefined'){
		$from_date = get_date_db($from_date);
		$to_date = get_date_db($to_date);
		$query .=" and (enquiry_date between '$from_date' and '$to_date')";
	}
	if($enquiry!=""){
			$query .=" and enquiry='$enquiry' ";
	}
}
if($from_date!='' && $to_date!=""){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .=" and (enquiry_date between '$from_date' and '$to_date')";
}
if($enquiry_status_filter!=''){
	if($enquiry_status_filter=='Active'){
		$query .= " and ef.followup_status='Active' ";
	}
	if($enquiry_status_filter=='Converted'){
		$query .= " and ef.followup_status='$enquiry_status_filter'";
	}
	if($enquiry_status_filter=='Dropped'){
		$query .= " and ef.followup_status='$enquiry_status_filter'";
	}
	if($enquiry_status_filter=='In-Followup'){
		$query .= " and ef.followup_status='In-Followup' ";
	}
}
$query .= " ORDER BY enquiry_master.enquiry_id DESC LIMIT $start, $limit";
///////////////////Enquiry table data End////////////////////////////////

$count = $start;
$sq_enquiries=mysql_query($query);
while($row = mysql_fetch_assoc($sq_enquiries)){
	$enquiry_id = $row['enquiry_id'];
	$assigned_emp_id = $row['assigned_emp_id'];
	$sq_emp = mysql_fetch_assoc(mysql_query("select first_name,last_name from emp_master where emp_id='$assigned_emp_id'"));
	$allocated_to = ($assigned_emp_id != 0)?$sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';

	$enquiry_content = $row['enquiry_content'];
	$enquiry_content_arr1 = json_decode($enquiry_content, true);

	$enquiry_status1 = mysql_fetch_assoc(mysql_query("select followup_date,followup_reply,followup_status from enquiry_master_entries where enquiry_id='$row[enquiry_id]' order by entry_id DESC"));
	$followup_date1 = $enquiry_status1['followup_date'];
	if($enquiry_status1['followup_status']=='Active'){
		$followup_status='Active';
	}
	elseif($followup_status == 'In-Followup'){
		$followup_status = 'In-Followup';
	}
	else{
		$followup_status=$enquiry_status1['followup_status'];
	}

	if($followup_status == 'Converted'){
		$bg = 'success';
	}
	elseif($followup_status == 'Dropped'){
		$bg = 'danger';
	}
	else{
		$bg = '';
	}
	
	if($enquiry_status_filter!=''){
		if($enquiry_status_filter=='Active' || $enquiry_status_filter=='In-Followup' && $enquiry_status1['followup_reply']==''){
				continue;
		}
		elseif($enquiry_status_filter!="Open" ){
			if($enquiry_status1['followup_status']!=$enquiry_status_filter){
				continue;
			}
		}
	}
	
	$date = $row['enquiry_date'];
	$yr = explode("-", $date);
	$year =$yr[0];
?>
	<tr class="<?= $bg ?>">
		<td><?= ++$count ?></td>
		<td><?= get_enquiry_id($enquiry_id,$year) ?></td>
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
	<?php } 
		
?>
<script>
$('#enquiry_count').html('<?php echo $enquiry_count;?>');
$('#limit').val('<?php echo $limit;?>');
$('#start').val('<?php echo $start;?>');

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
		
