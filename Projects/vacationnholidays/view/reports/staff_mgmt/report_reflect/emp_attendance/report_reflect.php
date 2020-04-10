<?php
include "../../../../../model/model.php";

$year = $_POST['year'];
$month = $_POST['month'];
$emp_id = $_POST['emp_id'];

$from_date = "1-$month-$year";
$from_date = get_date_db($from_date);

$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
$to_date = "$days-$month-$year";
$to_date = get_date_db($to_date);

$query = "select * from emp_master where 1 and active_flag!='Inactive'";
  
  if($emp_id!=''){
  $query .= " and emp_id = '$emp_id'";
  } 

?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
  
<table class="table table-bordered" id="attendance_report" style="margin: 20px 0 !important;">
  
 <thead>
  <tr class="table-heading-row">
    <th>User_ID</th>  
    <th>User_Name</th>
     <?php 
      
      for($i=0; strtotime($from_date)<=strtotime($to_date); $i++){
          $count++;
        ?> 
        
        <th><?= $count ?></th>
          
      <?php 
          $date = strtotime("+1 day", strtotime($from_date));
          $from_date = date("Y-m-d", $date);
          }  
          ?>
          <th>Present</th>
          <th>Absent</th>
          <th>On_Tour</th>
          <th>Half_Day</th>
          <th>Work_FromHome</th>
          <th>Holiday_OFF</th>
          <th>Weekly_OFF</th>
  </tr>  
  
  </thead>
  <tbody>
    <?php 
     $sq_a = mysql_query($query);
    while($row_emp = mysql_fetch_assoc($sq_a)){ 

    ?>
    <tr>
      <td><?= $row_emp['emp_id'] ?></td>
      <td><?= $row_emp['first_name'].' '.$row_emp['last_name']  ?></td>
      <?php  
      $i=0;
      while($i<$days) {  
       $p_count=0;
       $a_count=0;
       $ot_count=0;
       $hd_count=0;
       $wfh_count=0;
       $ho_count=0;
       $wo_count=0;
        $j = $i+1;
      $query1 =mysql_fetch_assoc(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and day(att_date)='$j'"));
      

        if($query1['status']!=""){ $status = $query1['status'];}
        else{ $status = '-';}
        ?>
      <td><?= $status ?></td>
    <?php  
    $i++; }
    $p_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Present'"));
    $a_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Absent'"));
    $ot_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='On Tour'"));
    $hd_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Half Day'"));
    $wfh_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Work From Home'"));
    $ho_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Holiday Off'"));
    $wo_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Weekly Off'"));     
     ?>
      <td><?= $p_count ?></td>
      <td><?= $a_count ?></td>
      <td><?= $ot_count ?></td>
      <td><?= $hd_count ?></td>
      <td><?= $wfh_count ?></td>
      <td><?= $ho_count ?></td>
      <td><?= $wo_count ?></td>
    <?php
  }?>
     
    </tr>
  </tbody>
    
   <tfoot>
   
   </tfoot>
</table>

</div> </div> </div>
<script>
$('#attendance_report').dataTable({
    "pagingType": "full_numbers"
  });
</script>
 