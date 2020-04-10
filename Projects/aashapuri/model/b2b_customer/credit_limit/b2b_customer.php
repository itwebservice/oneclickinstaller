<?php
class b2b_customer{
    function customer_update(){	
        $approval_id = $_POST['approval_id'];
        $register_id = $_POST['register_id'];
        $entry_id = $_POST['entry_id'];
        $description = $_POST['description'];
        $credit_limit = $_POST['credit_limit'];
        $approve_status= $_POST['approve_status'];

        $financial_year_id = $_SESSION['financial_year_id'];
        $branch_admin_id = $_SESSION['branch_admin_id'];
        $emp_id = $_SESSION['emp_id'];

        $clearance_status = ($payment_mode!="Cash") ? "Pending" : '';
        if($payment_mode=="Cash"){ $clearance_status = ""; }
        $payment_date = date('Y-m-d', strtotime($payment_date));

        $sq_b2b = mysql_query("update b2b_creditlimit_master set approval_status='$approve_status' where entry_id='$entry_id'");
  
        if(!$sq_b2b){
            echo "error--Sorry Information not updated!";
            exit;
        } 
        else{
            $sq_customer = mysql_fetch_assoc(mysql_query("select * from b2b_registration where register_id='$register_id'"));
            $row_b2b = mysql_fetch_assoc(mysql_query("select * from b2b_creditlimit_master where entry_id='$entry_id'"));             
            if($approve_status == 'Approved' && $row_b2b['mail_status'] == ''){
               
                $created_at = date("Y-m-d");
                $sq_max1 = mysql_fetch_assoc(mysql_query("select max(approval_id) as max from b2b_approved_credit"));
                $approval_id = $sq_max1['max'] + 1;
                $sq_credit1 = mysql_query("insert into b2b_approved_credit (approval_id,entry_id,credit_amount, description, approval_date) values ('$approval_id','$entry_id', '$credit_limit', '$description','$created_at')");
            
                //update mail status
                $sq_b2b1 = mysql_query("update b2b_creditlimit_master set mail_status='sent' where entry_id='$entry_id'");

                if($sq_credit1 && $sq_b2b1){
                    //Send Acknowledgement Mails
                    $this->approval_mail_Send($description,$sq_customer['email_id'],$sq_customer['company_name'],$credit_limit);
                }
             }
             else{
                $sq_b2b1 = mysql_query("update b2b_creditlimit_master set description='$description' where entry_id='$entry_id'");
                $sq_b2b1 = mysql_query("update b2b_approved_credit set description='$description' where approval_id='$approval_id'");
             }
             if($approve_status == 'Rejected' && $row_b2b['mail_status'] == ''){
                 $this->rejection_mail_Send($description,$sq_customer['email_id'],$register_id);
             }
            echo "Information has been successfully updated.";
            exit;
        }
    }

    function rejection_mail_Send($description,$email_id,$register_id){
      global $mail_em_style, $mail_font_family, $mail_strong_style, $app_name;
      $content = '
        <tr>
            <td>
            <span style="padding:5px 0; border-bottom:1px dotted #ccc; float: left">
                <span style="font-weight:bold">Description : '.$description.'</span>
            </span>
            </td>
        </tr>
      ';
      global $model;
      $model->app_email_send('106',$email_id, $content,'','1');
   }

   function approval_mail_Send($description,$email_id,$company_name,$credit_limit){
     global $mail_em_style, $mail_font_family, $mail_strong_style, $app_name;
     $content = '
       <tr>
           <td>
           <span style="padding:5px 0; border-bottom:1px dotted #ccc; float: left">
               <span style="font-weight:bold">Credit Limit : '.$credit_limit.'</span>
           </span>
           </td>
       </tr>
       <tr>
           <td>
           <span style="padding:5px 0; border-bottom:1px dotted #ccc; float: left">
               <span style="font-weight:bold">Description : '.$description.'</span>
           </span>
           </td>
       </tr>
     ';
     $subject = 'Credit Limit Increase Acknowledgment : '.$company_name;
     global $model;
     $model->app_email_send('107',$email_id, $content,$subject,'1');
  }
}