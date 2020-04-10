<?php
include "../model.php";
global $model;
$app_email_id = $_GET['app_email_id'];
$sq_pass = mysql_fetch_assoc(mysql_query("select user_name,password from roles where emp_id='0'"));

$content = '
    <tr>
        <td>
            <span style="padding:5px 0;">
                <span style="font-weight:bold">Dear Admin,</span>
            </span>    
        </td>
    </tr>
    <tr>
        <td>
            <span style="border-bottom:1px dotted #ccc; float: left"">
                <span style="font-weight:bold">You recent Username & Password credentials.</span>
            </span>    
        </td>
    </tr>
    <tr>
        <td>
            <span style="padding:5px 0; float: left">
                <span style="font-weight:bold">Username</span> : <span>'.$sq_pass['user_name'].'</span>
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <span style="float: left">
                <span style="font-weight:bold">Password</span> : <span>'.$sq_pass['password'].'</span>
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <span style="padding:5px 0; border-bottom:1px dotted #ccc; float: left">
                <span style="font-weight:bold">Please ensure, your credential is secured.</span>
            </span>    
        </td>
    </tr>
  ';


  $subject = 'Your User Name & Password Reset!';
  $model->app_email_send('',$app_email_id, $content,$subject,'1');
  echo "Mail sent successfully!";
?>