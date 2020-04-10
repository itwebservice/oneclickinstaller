<?php
include "../../../../../model/model.php";

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$emp_id = $_POST['emp_id'];

$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];

$query = "select * from daily_activity where 1 ";
if($from_date!="" && $to_date !=''){
  $from_date = date('Y-m-d',strtotime($from_date));
  $to_date = date('Y-m-d',strtotime($to_date));
  $query .=" and activity_date between '$from_date' and '$to_date'  ";
}
if($emp_id!=""){
  $query .=" and emp_id ='$emp_id'";
}
if($branch_status=='yes' && $role!='Admin'){
   
    $query .=" and emp_id in(select emp_id from emp_master where branch_id='$branch_admin_id')";
}   
 
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table class="table table-bordered" id="activity_report" style="margin: 20px 0 !important;">
	 <thead>
	 	<tr class="table-heading-row">
	 	  <th>S_No.</th>
          <th>Activity_Date</th>          
          <th>User_name</th> 
          <th>Activity_type</th>
          <th>Time_taken</th>
          <th>Description</th>
	 	</tr>
	 </thead>
	 <tbody>
	 <?php 	
	 	$count = 0;
	 	$sq = mysql_query($query);
        while($row=mysql_fetch_assoc($sq))
        {
	 		$count++;          
	 		$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row[emp_id]'"));  
         ?>
         <tr>
            <td><?php echo $count ?></td>
            <td><?php echo date('d-m-Y',strtotime($row['activity_date'])); ?></td>
            <td><?php echo $sq_emp['first_name'].' '.$sq_emp['last_name']; ?></td>
            <td><?php echo $row['activity_type']; ?></td>
            <td><?php echo $row['time_taken']; ?></td>
            <td><?php echo $row['description']; ?></td>
         </tr> 
         <?php 
        }  
        ?>
	 </tbody>
</table>

</div> </div> </div>
<script>
$('#activity_report').dataTable({
		"pagingType": "full_numbers"
	});
</script>