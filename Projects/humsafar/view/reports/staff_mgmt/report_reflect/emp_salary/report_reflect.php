<?php
include "../../../../../model/model.php";
$emp_id = $_POST['emp_id'];
$year = $_POST['year'];
$month = $_POST['month'];
if($emp_id != '' || $year != '' || $month !=''){

$query = "select * from emp_master where 1 and active_flag!='Inactive'";
if($emp_id!=''){
  $query .= " and emp_id = '$emp_id'";
} 
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
  
<table class="table table-bordered" id="salary_report" style="margin: 20px 0 !important;">
  
  <thead>
    <tr class="table-heading-row">
        <th>SR_No</th>  
        <th>User_ID</th>  
        <th>User_Name</th>
        <th>Days_Worked</th>
        <th>Gross_Salary</th> 
        <th>salary_advance</th> 
        <th>Employer_PF</th>
        <th>Employee_PF</th>
        <th>ESIC_Deduction</th>  
        <th>PT_Deduction</th>
        <th>LWF_Deduction</th>
        <th>TDS_deduction</th>  
        <th>Surcharge_deduction</th>
        <th>Cess_deduction </th>
        <th>leave_deduction</th>  
        <th>Total_Deduction</th>  
        <th>Net_Salary</th>
        <th>Edit</th>
        <th>Salary_Slip</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    $count = 1;
    $sq_a = mysql_query($query);
    while($row_emp = mysql_fetch_assoc($sq_a)){ 
        $p_count =mysql_num_rows(mysql_query( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Present'"));

        $b_url = BASE_URL."model/app_settings/print_html/salary_slip/salary_slip.php?emp_id=$row_emp[emp_id]";
        
        $sq_salary = "select * from employee_salary_master where emp_id='$row_emp[emp_id]' ";
        if($year!=''){
          $sq_salary .= " and year = '$year'";
        } 
        if($month!=''){
          $sq_salary .= " and month = '$month'";
        }        
        $sq_sal1 =mysql_query($sq_salary);
        while($sq_sal = mysql_fetch_assoc($sq_sal1)){ 
            
            ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row_emp['emp_id'] ?></td>
                <td><?= $row_emp['first_name'].' '.$row_emp['last_name']  ?></td>
                <td><?= $p_count ?></td>
                <td><?= ($sq_sal['gross_salary']!="") ? $sq_sal['gross_salary'] : number_format(0,2) ?></td> 
                <td><?= ($sq_sal['salary_advance']!="") ? $sq_sal['salary_advance'] : number_format(0,2) ?></td>    
                <td><?= ($sq_sal['employer_pf']!="") ? $sq_sal['employer_pf'] : number_format(0,2) ?></td> 
                <td><?= ($sq_sal['employee_pf']!="") ? $sq_sal['employee_pf'] : number_format(0,2)?></td>
                <td><?= ($sq_sal['esic']!="") ? $sq_sal['esic'] : number_format(0,2)?></td>
                <td><?= ($sq_sal['pt']!="") ? $sq_sal['pt']  : number_format(0,2)?></td>
                <td><?= ($sq_sal['labour_all']!="") ?$sq_sal['labour_all'] : number_format(0,2) ?></td>   
                <td><?= ($sq_sal['tds']!="") ? $sq_sal['tds'] : number_format(0,2) ?></td> 
                <td><?= ($sq_sal['surcharge_deduction']!="") ? $sq_sal['surcharge_deduction'] : number_format(0,2) ?></td>
                <td><?= ($sq_sal['cess_deduction']!="") ? $sq_sal['cess_deduction'] : number_format(0,2) ?></td>
                <td><?= ($sq_sal['leave_deduction']!="") ? $sq_sal['leave_deduction'] : number_format(0,2) ?></td>
                <td><?= ($sq_sal['deduction']!="") ? $sq_sal['deduction'] : number_format(0,2) ?></td>
                <td><?= ($sq_sal['net_salary']!="") ? $sq_sal['net_salary'] : number_format(0,2)?></td>   
                <td>
                    <button class="btn btn-info btn-sm" onclick="update_modal(<?= $sq_sal['salary_id'] ?>, <?= $month ?>)" title="Edit User"><i class="fa fa-pencil-square-o"></i></button>
                </td>
                <td>
                    <a onclick="loadOtherPage('<?= $b_url ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
                </td>
            </tr>
        <?php
      }
     }
    ?>
  </tbody>
  
</table>

</div> </div> </div>
<?php } ?>
 <script>
$('#salary_report').dataTable({
    "pagingType": "full_numbers"
  });
</script>