<?php
include "../../../../../model/model.php";
$year = $_POST['year'];
$month= $_POST['month'];
$emp_id = $_POST['emp_id'];

$query = "select * from employee_performance_master where 1 ";
if($year!=''){
  $query .= " and year = '$year'";
}
if($month!=''){
  $query .= " and month = '$month'";
}
if($emp_id!=''){
  $query .= " and emp_id = '$emp_id'";
}

?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered" id="per_report" style="margin: 20px 0 !important;">
  <thead>
    <tr class="table-heading-row">
      <th>User_ID</th>
      <th>User_Name</th>
      <th>Teamwork</th>
      <th>Leadership</th>
      <th>Communication</th>
      <th>Analytical Skills</th>
      <th>Ethics</th>
      <th>Conceptual_Thinking</th>
      <th>Average Rating</th>
    </tr>
  </thead>
  <tbody>
    <?php
     $sq_a = mysql_query($query);
    while($row_emp = mysql_fetch_assoc($sq_a)){
      $sq_p =mysql_fetch_assoc(mysql_query( "select * from emp_master where emp_id='$row_emp[emp_id]'"));?>
      <tr>
        <td><?= $sq_p['emp_id'] ?></td>
        <td><?= $sq_p['first_name'].' '.$sq_p['last_name']  ?></td>
        <td class="text-center"><?= ($row_emp['teamwork']!="") ? $row_emp['teamwork'] : '-' ?></td>
        <td class="text-center"><?= ($row_emp['leadership']!="") ? $row_emp['leadership'] : '-' ?></td>
        <td class="text-center"><?= ($row_emp['communication']!="") ? $row_emp['communication'] : '-' ?></td>
        <td class="text-center"><?= ($row_emp['analytical_skills']!="") ? $row_emp['analytical_skills'] : '-' ?></td>
        <td class="text-center"><?= ($row_emp['ethics']) ? $row_emp['ethics'] : '-' ?></td>
        <td class="text-center"><?= ($row_emp['conceptual_thinking']) ? $row_emp['conceptual_thinking'] : '-' ?></td>
        <td class="text-center"><?= ($row_emp['ave_ratings']!="") ? $row_emp['ave_ratings'] : '-' ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
</div> </div> </div>
<script>
$('#per_report').dataTable({
    "pagingType": "full_numbers"
});
</script>