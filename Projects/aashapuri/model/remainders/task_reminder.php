<?php
include_once('../model.php'); 

$cur_time = date('Y-m-d H:i:s');

$last_time = strtotime($cur_time);
$endTime = strtotime("+15 minutes",$last_time);
$endTime = date('Y-m-d H:i:s', $endTime);

$q = "select * from tasks_master where remind_due_date = '$cur_time'";
$sq_task = mysql_query($q);
echo $q;

while($row_tasks = mysql_fetch_assoc($sq_task)){

    $task = $row_tasks['task_name'];
    $due_date =$row_tasks['due_date'];
    $remind_by = $row_tasks['remind_by'];


    $sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_tasks[emp_id]'"));
    $emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
    $email_id = $sq_emp_info['email_id'];
    $mobile_no = $sq_emp_info['mobile_no'];
     
    task_mail($emp_name,$email_id,$task,$due_date,$mobile_no,$remind_by);
}

function task_mail($emp_name,$email_id,$task,$due_date,$mobile_no,$remind_by)
{
   global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
    global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

    $email_content = '
        <tr>
            <td>
                <table style="width:100%">
                    <tr>
                        <td>
                                <span style="color:'.$mail_color.'">Tasks Name:</span> '.$task.'<br>
                                <span style="color:'.$mail_color.'">Due Date: </span> '.date('d-m-Y H:i:s', strtotime($due_date)).'
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>  
    ';

    $sms_content = "Hello ".$emp_name.". Your task reminder."." 
Tasks : ".$task." , Due DateTime:".date('d-m-Y H:i:s', strtotime($due_date));
    $subject = 'Task Reminder';
    global $model;

    if($remind_by=="Email And SMS"){

        $model->app_email_send('71',$email_id, $email_content,$subject,'1');
        $model->send_message($mobile_no, $sms_content);

    }
    if($remind_by=="Email"){
        $model->app_email_send('71',$email_id, $email_content,$subject,'1');
    }
    if($remind_by=="SMS"){
        $model->send_message($mobile_no, $sms_content);
    }

}

 

 

 
 