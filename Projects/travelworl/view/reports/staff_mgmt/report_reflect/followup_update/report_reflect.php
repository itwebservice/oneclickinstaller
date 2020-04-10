<?php
include "../../../../../model/model.php";

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$today_date1 = date('Y-m-d 00:00');
$today_date2 = date('Y-m-d 23:00');

$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];

$query = "select * from enquiry_master_entries where 1 ";
if($from_date!="" && $to_date !=''){
  $from_date = date('Y-m-d H:i',strtotime($from_date));
  $to_date = date('Y-m-d H:i',strtotime($to_date));
  $query .=" and followup_date between '$from_date' and '$to_date'  ";
}else{
  $query .=" and followup_date between '$today_date1' and '$today_date2'  ";
}
if($branch_status=='yes' && $role!='Admin'){
    $query .=" and enquiry_id in(select enquiry_id from enquiry_master where branch_admin_id='$branch_admin_id')";
}
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table class="table table-bordered" id="activity_report" style="margin: 20px 0 !important;">
	 <thead>
	 	<tr class="table-heading-row">
	 	  <th>S_No.</th>
      <th>Customer_Name</th>
      <th>Assigned_To</th>
      <th>Followup_Date</th>
      <th>Followup_Type</th>
      <th>Followup_Description</th>
	 	</tr>
	 </thead>
	 <tbody>
	 <?php
	 	$count = 0;
	 	$sq = mysql_query($query);
    while($row=mysql_fetch_assoc($sq))
    {
      if($row['followup_type']!=''){
        $count++;
        $sq_enq = mysql_fetch_assoc(mysql_query("select * from enquiry_master where enquiry_id='$row[enquiry_id]'"));
        $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_enq[assigned_emp_id]'"));
          ?>
          <tr>
              <td><?php echo $count ?></td>
              <td><?php echo $sq_enq['name']; ?></td>
              <td><?php echo $sq_emp['first_name'].' '.$sq_emp['last_name']; ?></td>
              <td><?php echo get_datetime_user($row['followup_date']); ?></td>
              <td><?php echo $row['followup_type']; ?></td>
              <td><?php echo $row['followup_reply']; ?></td>
          </tr>
         <?php
      }
    } ?>
	 </tbody>
</table>

</div> </div> </div>
<script>
$('#activity_report').dataTable({
		"pagingType": "full_numbers"
	});
</script>