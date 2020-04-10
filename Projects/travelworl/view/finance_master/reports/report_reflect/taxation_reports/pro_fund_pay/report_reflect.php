<?php include "../../../../../../model/model.php";

$month = $_POST['month'];
$branch_status = $_POST['branch_status'];
$role = $_POST['role'];
$branch_admin_id = $_POST['branch_admin_id'];

$query = "select * from employee_salary_master where 1 ";
if($month != ''){
	$query .= " and month='$month'";
}
if($branch_status=='yes'){
	if($role=='Branch Admin'){
		$query .= " and emp_id in(select emp_id from emp_master where branch_id='$branch_admin_id')";
	}
	elseif($role!='Admin' && $role!='Branch Admin'){
      $query .= " and emp_id='$emp_id'";
    }
}
$sq_query = mysql_query($query);
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="tbl_pf_pay" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>SR.NO</th>
			<th>User_ID</th>
			<th>User_Name</th>
			<th>UAN</th>
			<th>Employer_Contribution</th>
			<th>User_Contribution</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<?php
		 $count = 1;
		 while($row_query = mysql_fetch_assoc($sq_query))
		 {
		 	$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_query[emp_id]'")); ?> 
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $row_query['emp_id'] ?></td>
				<td><?= $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
				<td><?= ($sq_emp['uan_code'] == '') ? 'NA' : $sq_emp['uan_code'] ?></td>
				<td><?= $row_query['employer_pf'] ?></td>
				<td><?= $row_query['employee_pf'] ?></td>
				<td><?= number_format($row_query['employee_pf'] + $row_query['employer_pf'],2) ?></td>
			</tr>
		<?php } ?>	 	
	</tbody>
	<tfoot>
		 
	</tfoot>
</table>
</div> </div> </div>
<script>
$('#tbl_pf_pay').dataTable({
		"pagingType": "full_numbers"
	});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>