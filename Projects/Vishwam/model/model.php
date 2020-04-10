<?php
/*$seperator = strstr(strtoupper(substr(PHP_OS, 0, 3)), "WIN") ? "\\" : "/";
session_save_path('..'.$seperator.'xml'.$seperator.'session_dir');
ini_set('session.gc_maxlifetime', 6); // 3 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.cookie_secure', FALSE);
ini_set('session.use_only_cookies', TRUE);*/
ini_set("session.gc_maxlifetime", 3*60*60);
ini_set('session.gc_maxlifetime', 3*60*60);
session_start();

date_default_timezone_set('Asia/Kolkata');

set_error_handler("myErrorHandler");
function myErrorHandler($errno, $errstr, $errfile, $errline){
   // echo  "<br><br>".$errno."<br>".$errstr."<br>".$errfile."<br>".$errline;
}
$localIP = getHostByName(getHostName());

$connection=mysql_connect("localhost","itourscl_vishv_u","rwpV}DJolUP9");
if(!$connection){ echo "Unable To make Connection."; }

$db_connect=mysql_select_db("itourscl_vishavam");
if(!$db_connect) { echo "Database Not Connected."; } 

define('BASE_URL', 'https://itourscloud.com/vishvam/');

mysql_query("SET SESSION sql_mode = ''");

//**********Global Variables start**************//
global $app_version, $admin_logo_url, $circle_logo_url, $report_logo_url, $report_logo_small_url, $terms_conditions_url, $app_email_id_send, $backoffice_email_id, $app_contact_no, $service_tax_no, $app_address, $app_website, $app_name, $bank_acc_no, $bank_name_setting, $bank_branch_name, $bank_ifsc_code, $bank_swift_code, $sms_username, $sms_password, $theme_color, $theme_color_dark, $theme_color_2, $topbar_color, $sidebar_color,$emp_email_id,$emp_id, $app_landline_no,$app_cancel_pdf,$acc_name,$tax_type,$tax_pay_date,$app_invoice_format,$setup_country_id,$app_credit_charge,$txn_feild_note,$cancel_feild_note,$setup_package,$app_quot_format,$app_quot_img,$similar_text;

$admin_logo_url = BASE_URL.'images/Admin-Area-Logo.png';
$circle_logo_url = BASE_URL.'images/logo-circle.png';
$report_logo_small_url = BASE_URL.'images/Receips-Logo-Small.jpg';
$terms_conditions_url = BASE_URL.'images/terms-condition.jpg';
$hotel_service_voucher = BASE_URL.'images/hotel_service_voucher.jpg';
$transport_service_voucher = BASE_URL.'images/transport_service_voucher.jpg';
$transport_service_voucher2 = BASE_URL.'images/transport_service_voucher2.jpg';
$booking_form = BASE_URL.'images/Booking-Form-new.jpg';
$b2b_pdf_image = BASE_URL.'images/b2b_pdf_image.jpg';
$sidebar_strip = BASE_URL.'images/sidebar-strip.jpg';
$voucher_id_proof = BASE_URL.'images/voucher_id_proof.jpg';
$quotation_icon = BASE_URL.'images/quotation-icon.png';

//Sale and Purchase transaction feild detail's Note 
$txn_feild_note="Please make sure Date, Amount, Mode, Creditor bank entered properly.";

//Cancel feild note
$cancel_feild_note = "Note : Kindly take new booking who will travel from partially cancellation.";

//simliar hotel and transports
$similar_text = ' / Similar';

//Quot_note
$quot_note = 'Note : This is only quote. No booking made yet. Rates may differ as per availability.';

//**********App Settings Global Variables start**************//

$sq_app_tax = mysql_fetch_assoc(mysql_query("select * from generic_count_master where id='1'"));
$setup_country_id = $sq_app_tax['setup_country_id'];
$app_invoice_format = $sq_app_tax['invoice_format'];
$setup_package = $sq_app_tax['setup_type'];
$session_emp_id = $_SESSION['emp_id'];

$sq_app_setting_count = mysql_num_rows(mysql_query("select * from app_settings"));
if($sq_app_setting_count==1){
  $sq_app_setting = mysql_fetch_assoc(mysql_query("select * from app_settings"));
  $app_version = $sq_app_setting['app_version'];
  $backoffice_email_id = $sq_app_setting['backoffice_email_id'];
  $app_contact_no = $sq_app_setting['app_contact_no'];
  $app_landline_no = $sq_app_setting['app_landline_no'];
  $service_tax_no = strtoupper($sq_app_setting['service_tax_no']);
  $app_address = $sq_app_setting['app_address'];
  $app_website = $sq_app_setting['app_website'];
  $app_name = $sq_app_setting['app_name'];
  $bank_acc_no = $sq_app_setting['bank_acc_no'];
  $cin_no = $sq_app_setting['app_cin'];
  $bank_name_setting = $sq_app_setting['bank_name'];
  $acc_name = $sq_app_setting['acc_name'];
  $bank_branch_name = $sq_app_setting['bank_branch_name'];
  $bank_ifsc_code = $sq_app_setting['bank_ifsc_code'];
  $bank_swift_code = $sq_app_setting['bank_swift_code'];
  $sms_username = $sq_app_setting['sms_username'];
  $sms_password = $sq_app_setting['sms_password'];
  $accountant_email = $sq_app_setting['accountant_email'];
  $tax_type = $sq_app_setting['tax_type'];
  $tax_pay_date = $sq_app_setting['tax_pay_date'];

  $app_email_id = $sq_app_setting['app_email_id'];
  if($session_emp_id == 0){
    $app_email_id_send = $sq_app_setting['app_email_id'];
    $app_smtp_status = $sq_app_setting['app_smtp_status'];
    $app_smtp_host = $sq_app_setting['app_smtp_host'];
    $app_smtp_port = $sq_app_setting['app_smtp_port'];
    $app_smtp_password = $sq_app_setting['app_smtp_password'];
    $app_smtp_method = $sq_app_setting['app_smtp_method'];
  }
  else{
    $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$session_emp_id'"));
    $app_email_id_send = $sq_emp['email_id'];
    $app_smtp_status = $sq_emp['app_smtp_status'];
    $app_smtp_host = $sq_emp['app_smtp_host'];
    $app_smtp_port = $sq_emp['app_smtp_port'];
    $app_smtp_password = $sq_emp['app_smtp_password'];
    $app_smtp_method = $sq_emp['app_smtp_method'];
  }
  $app_cancel_pdf = $sq_app_setting['policy_url'];
  $app_credit_charge = $sq_app_setting['credit_card_charges'];
  $_SESSION['unique_receipt_id']= $app_version."/";
  $app_quot_format = $sq_app_setting['quot_format'];
  $app_quot_img = $sq_app_setting['quot_img_url'];
}
else{
  $app_version = $app_email_id = $app_email_id_send = $backoffice_email_id = $app_contact_no = $service_tax_no = $app_address = $app_website = $app_name = $bank_acc_no = $bank_name_setting = $bank_branch_name = $bank_ifsc_code = $bank_swift_code = $app_smtp_status = $app_smtp_host = $app_smtp_port = $app_smtp_password = $app_smtp_method = $app_terms_condition = $cin_no = $app_landline_no = $acc_name = $accountant_email=$app_credit_charge=$app_quot_format=$app_quot_img="";
}


//**********Theme color scheme variables**************//
$sq_count = mysql_num_rows( mysql_query("select * from app_color_scheme") );
if($sq_count==1){
  $sq_scheme = mysql_fetch_assoc(mysql_query("select * from app_color_scheme"));
  $theme_color = $sq_scheme['theme_color'];
  $theme_color_dark = $sq_scheme['theme_color_dark'];
  $theme_color_2 = $sq_scheme['theme_color_2'];
  $topbar_color = $sq_scheme['topbar_color'];
  $sidebar_color = $sq_scheme['sidebar_color'];
}else{
  $theme_color = "#009898";
  $theme_color_dark = "#239ede";
  $theme_color_2 = "#1d4372";
  $topbar_color = "#ffffff";
  $sidebar_color = "#36aae7"; 
}

//**********Mailer gloabal Variables**************//
global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;
$mail_color = "#2fa6df";
$mail_em_style = "border-bottom: 1px dotted #1f1f1f; padding-bottom: 4px; margin-bottom: 4px; display: inline-block; font-style:normal; color:#2fa6df;";
$mail_em_style1 = "font-style:normal; color:#2fa6df";
$mail_font_family = "font-family: 'Raleway', sans-serif;";
$mail_strong_style = "font-weight: 500; color:#000";

global $model;
$model=new model();
class model
{

public function emailer_head()
  {
    global $app_email_id_send, $app_name, $app_contact_no, $admin_logo_url, $app_website;
    global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;

    $em_style = "border-bottom: 1px dotted #1f1f1f; padding-bottom: 4px; margin-bottom: 4px; display: inline-block; font-style:normal; color: #2fa6df; ";
    $font_family = "font-family: 'Lato', sans-serif;";
    $strong_style = "font-weight: 500; color:#000";
    $content = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Online Booking</title>
      <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    </head>
    <body style="font-family: Roboto, sans-serif;background-color: #ffffff;margin-top: 0px !important;margin-right: 0px !important;margin-left: 0px !important;padding: 0px !important;">
      
      <table cellspacing="0" cellpadding="0" style="width: 620px;margin-left: -4px;margin-right: -4px;border: 1px solid #e2e2e2;margin-top: 1px;border-bottom: 0;">
        <tr> 
          <td>
             <a href='.$app_website.' target="blank">
                <img src="'.BASE_URL.'/images/Admin-Area-Logo.png" style="width: 225px;">
             </a>
          </td>
          <td>
            <h1 style="color: #009898;text-transform: uppercase;text-align: right;padding-right: 20px;font-weight: 400;font-size: 24px;">'.$app_name.'</h1>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <table style="width: 620px;margin-left: -4px;margin-right: -4px;background-image: url('.BASE_URL.'/images/email/bg.jpg);background-repeat: no-repeat;background-size: cover;padding: 50px 15px 15px 50px;color: #fff;line-height: 36px;font-size: 14px;">
    
';

    return $content;
  }

  public function emailer_footer()
  {
    global $app_email_id_send, $app_name, $app_contact_no, $admin_logo_url, $app_website;
    global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;

    $content = '
                </table>
              </td>
          </tr>
          <tr>
            <td colspan="2">
              <table style="width: 620px;margin-left: -4px;margin-right: -4px;background: #1da38a;color: #fff;padding-left: 50px;font-size: 14px;padding: 10px 0 10px 50px;">
                <tr>
                  <td><span>'.$app_name.'</span></td>
                  <td style="text-align: right;padding-right: 38px;"><img src="'.BASE_URL.'/images/email/phone.png" style="margin-bottom: -1px;"> <span>'.$app_contact_no.'</span></td>
                </tr> 
              </table>
            </td>
          </tr>
        </table>
      </body>
    </html>';

    return $content;
  }
  public function generic_payment_mail($cms_id,$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $to,$subject)
  {
    global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;
    if($payment_amount != '0' && $payment_amount != ''){
    $balance_amount = $total_amount - $paid_amount;
    $total_amount= number_format($total_amount,2);
    $balance_amount= number_format($balance_amount,2);
 
    $content = '
      <tr>
        <td>
          <table style="padding:15px 0 0 0">
            <tr>
              <td>
                <table style=" background: #fff; color: #22262e; font-size: 13px;width:100%; margin-bottom:20px;">
                  <tr>
                    <td><span style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Total Amount  :&nbsp;&nbsp;<span style="color:'.$mail_color.'">'.$total_amount.'</span></td>
                  </tr>
                  <tr>
                    <td><span style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Paid Amount  :&nbsp;&nbsp;<span style="color:'.$mail_color.'">'.$paid_amount.'</span></td>
                  </tr>
                  <tr>
                    <td><span style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Balance Amount  :&nbsp;&nbsp;<span style="color:'.$mail_color.'">'.$balance_amount.'</span></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    ';

    $content;
    $this->app_email_send($cms_id,$to, $content,$subject);
  }
 }

 public function generic_payment_remainder_mail($cms_id,$balance_amount, $tour_name, $booking_id, $customer_id, $to, $acc_status='' ){
    global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;
    $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
 
    $content = '
    <tr>
      <td>
        <table style="width:100%">
          <tr>
            <td>
              <p style="line-height: 24px;">Payment of '.$balance_amount.' for your booking '.$tour_name.'('.$booking_id.') has been outstanding today.</p>
              <p style="line-height: 24px;">Tour would be confirmed on receipt of 100% payment only.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>';

    $content;
    if($acc_status!=''){
      $this->app_email_send($cms_id,$to, $content,$subject);
    }
    else{
      $this->app_email_send($cms_id,$to, $content,$subject,'1');
    }
  }

  public function topup_remainder_mail($balance_amount, $supplier_name )
  {
    global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color,$app_contact_no;
   

    $content = '
      <tr>
        <td>
          <table style="width : 100%">
            <tr>
              <td><strong style="'.$mail_strong_style.'">Airline Name :</strong> '.$supplier_name.'</td>
            </tr>
            <tr>
              <td><strong style="'.$mail_strong_style.'">Current low balance :</strong> '.$balance_amount.'</td>
            </tr>
          </table>
        </td>
      </tr>
    ';

    $content;
    $message = "Hello Admin, Your airline balance became low. Please transfer the payment and upgrade the balance.Airline Name : ".$supplier_name." Current low balance : ".$balance_amount;
  
    $this->app_email_send('75',$app_email_id_send, $content);
    $this->send_message($app_contact_no, $message);      
  }

  public function visa_topup_remainder_mail($balance_amount, $supplier_name )
  {
    global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color,$app_contact_no,$app_email_id_send;

    $content = '
      <tr>
        <td>
          <table style="width : 100%">
            <tr>
              <td><strong style="'.$mail_strong_style.'">Visa Supplier Name :</strong> '.$supplier_name.'</td>
            </tr>
            <tr>
              <td><strong style="'.$mail_strong_style.'">Current low balance :</strong> '.$balance_amount.'</td>
            </tr>
          </table>
        </td>
      </tr>
   ';

    $content;
    $message = "Hello Admin, Your visa balance became low. Please transfer the payment and upgrade the balance.Visa Supplier Name : ".$supplier_name." Current low balance : ".$balance_amount;
  
    $this->app_email_send('76',$app_email_id_send, $content);
    $this->send_message($app_contact_no, $message);      
  }
  public function while_topup_mail_send($amount, $supplier_name,$for)
  {
    global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color,$app_contact_no,$app_email_id_send;
    if($for=='visa'){
      
       $content = '
      <tr>
        <td>
          <table style="width : 100%">
            <tr>
              <td><strong style="'.$mail_strong_style.'">Supplier Name :</strong> '.$supplier_name.'</td>
            </tr>
            <tr>
              <td><strong style="'.$mail_strong_style.'">Amount :</strong> '.$amount.'</td>
            </tr>
          </table>
        </td>
      </tr>
      ';

    $content;
    $this->app_email_send('66',$app_email_id_send, $content);  
    }
    else{
      
    $content = ' 
      <tr>
        <td>
          <table>
            <tr>
              <td><strong style="'.$mail_strong_style.'">Supplier Name :</strong> '.$supplier_name.'</td>
            </tr>
            <tr>
              <td><strong style="'.$mail_strong_style.'">Amount :</strong> '.$amount.'</td>
            </tr>
          </table>
        </td>
      </tr>
      ';

    $content;
    $this->app_email_send('65',$app_email_id_send, $content);    
  }
}

  public function app_email_master($to, $content, $subject, $acc_status='')
  {
    global $app_email_id_send, $app_name, $app_contact_no, $admin_logo_url, $app_website, $app_smtp_status, $app_smtp_host, $app_smtp_port, $app_smtp_password, $app_smtp_method,$emp_email_id,$emp_id,$accountant_email;
    /*
    if($from_mail == ""){
       $from_mail = $app_email_id_send;
    }    */
    $session_emp_id = $_SESSION['emp_id'];
    $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$session_emp_id'"));

    $body = $this->emailer_head();
    $body .= '<p style="line-height:21px">'.$content.'</p>';
    $body .= $this->emailer_footer();

    include_once dirname(dirname(__FILE__)).'/classes/PHPMailer_5.2.4/class.phpmailer.php';
    $mail= new PHPMailer;
    $mail->IsSMTP();
    if($app_smtp_status=="Yes"){
      $mail->Host = $app_smtp_host;
      $mail->Port = $app_smtp_port;
      $mail->SMTPAuth = true;
      $mail->SMTPDebug = 1;
      $mail->Username = $app_email_id_send;
      $mail->Password = $app_smtp_password;
      $mail->SMTPSecure = $app_smtp_method;
    }
    
    $mail->AddReplyTo($app_email_id_send,$app_name);
    $mail->SetFrom($app_email_id_send, $app_name);
    $mail->AddReplyTo($app_email_id_send,$app_name);
    $mail->AddAddress($to,"");
    $mail->AddCC($app_email_id_send, $app_name);
    //$mail->AddCC($sq_emp['email_id'], $app_name);

    //keep accountant in accountant
    if($acc_status == ''){
      $mail->AddCC($accountant_email, $app_name);
    }
    $mail->Subject    = $subject;
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
    $mail->MsgHTML($body);
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
      //echo "Mail sent!";
    }  
  }
  ///////////////////////////// New Mail(CMS) Draft///////////////////////////////////////////////
  public function app_email_send($email_for,$to, $temp_content, $subject, $acc_status='')
  {
    global $app_email_id_send, $app_name, $app_contact_no, $admin_logo_url, $app_website, $app_smtp_status, $app_smtp_host, $app_smtp_port, $app_smtp_password, $app_smtp_method,$emp_email_id,$emp_id,$accountant_email;

    $sq_cms = mysql_fetch_assoc(mysql_query("select * from cms_master_entries where entry_id='$email_for'"));
    if($sq_cms['active_flag'] != 'Inactive'){
      $content = $sq_cms['draft'];
      $content .= $temp_content;
      $content .= $sq_cms['signature'];

      $body = $this->emailer_head();
      $body .= '<p style="line-height:21px">'.$content.'</p>';
      $body .= $this->emailer_footer();

      include_once dirname(dirname(__FILE__)).'/classes/PHPMailer_5.2.4/class.phpmailer.php';
      include_once dirname(dirname(__FILE__)).'/classes/PHPMailer_5.2.4/class.smtp.php';
      $mail= new PHPMailer;
      $mail->IsSMTP();
      if($app_smtp_status=="Yes"){
        $mail->Host = $app_smtp_host;
        $mail->Port = $app_smtp_port;
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 1;
        $mail->Username = $app_email_id_send;
        $mail->Password = $app_smtp_password;
        $mail->SMTPSecure = $app_smtp_method;
      }
      $mail->addReplyTo($app_email_id_send,$app_name);
      $mail->setFrom($app_email_id_send,$app_name);
      $mail->addReplyTo($app_email_id_send,$app_name);
      $mail->addAddress($to);
      $mail->addCC($app_email_id_send,$app_name);

      //keep accountant in cc
      if($acc_status == ''){
        $mail->AddCC($accountant_email, $app_name);
      }
      if(!empty($subject)){
        $mail->Subject = $subject;
      }
      else{
        $mail->Subject = $sq_cms['subject'];
      }
      $mail->AltBody  = "To view the message, please use an HTML compatible email viewer!";
      $mail->MsgHTML($body);
      
      if(!$mail->Send()){
        echo "Mailer Error: ". $mail->ErrorInfo;
      } 
      else{
        //echo "Mail sent!";
      } 
    }
  }

  public function app_template_email_master($to, $content, $subject)
  {
    global $app_email_id_send, $app_name, $app_contact_no, $admin_logo_url, $app_website, $app_smtp_status, $app_smtp_host, $app_smtp_port, $app_smtp_password, $app_smtp_method,$emp_email_id;
    
    $body .= $content;

    include_once dirname(dirname(__FILE__)).'/classes/PHPMailer_5.2.4/class.phpmailer.php';
    $mail= new PHPMailer;
    $mail->IsSMTP();
    if($app_smtp_status=="Yes"){
      $mail->Host = $app_smtp_host;             
      $mail->Port = $app_smtp_port;                               
      $mail->SMTPAuth = true;
      $mail->SMTPDebug = 1;                               
      $mail->Username = $app_email_id_send;               
      $mail->Password = $app_smtp_password;                
      $mail->SMTPSecure = $app_smtp_method;                    
    }
    
    $mail->AddReplyTo($app_email_id_send,$app_name);
    $mail->SetFrom($app_email_id_send,$app_name);
    $mail->AddReplyTo($app_email_id_send,$app_name);
    $mail->AddAddress($to,"");
    $mail->AddCC($app_email_id_send, $app_name);
    //$mail->AddCC($sq_emp['email_id'], $app_name);
    
    $mail->Subject = $subject;
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
    $mail->MsgHTML($body);
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
      //echo "Mail sent!";
    }  
  }
//=======================Send Mail with attachment===========================//
public function new_app_email_send($email_for,$to,$subject,$arrayAttachment, $temp_content, $acc_status=''){
      global $app_email_id_send, $app_name , $app_smtp_status, $app_smtp_host, $app_smtp_port, $app_smtp_password, $app_smtp_method,$emp_id,$accountant_email;
  
      include_once dirname(dirname(__FILE__)).'/classes/PHPMailer_5.2.4/class.phpmailer.php';
      $session_emp_id = $_SESSION['emp_id'];
      $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$session_emp_id'"));
      $content .= $temp_content;
  
      $body = $this->emailer_head();
      $body .= $content;
      $body .= $this->emailer_footer();
      $mail= new PHPMailer;
      $mail->IsSMTP();
      if($app_smtp_status=="Yes"){
        $mail->Host = $app_smtp_host;
        $mail->Port = $app_smtp_port;
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 1;
        $mail->Username = $app_email_id_send;
        $mail->Password = $app_smtp_password;
        $mail->SMTPSecure = $app_smtp_method;
      }
      $mail->AddReplyTo($app_email_id_send,$app_name);
      $mail->SetFrom($app_email_id_send, $app_name);
      $mail->AddReplyTo($app_email_id_send,$app_name);
      $mail->AddAddress($to, "");
      $mail->AddCC($app_email_id_send, $app_name);
      //$mail->AddCC($sq_emp['email_id'], $app_name);

      foreach($arrayAttachment as $attachment)
      {
        $dir = dirname(dirname(__FILE__));
        $att_url =  str_replace("'/'","'\'",$dir);
        $mail->AddAttachment($att_url.'/'.$attachment);
      } 
      //keep accountant in cc
      if($acc_status == ''){
        $mail->AddCC($accountant_email, $app_name);
      }
      $mail->AddCC($sq_emp['email_id'], $app_name);
      if(!empty($subject)){
        $mail->Subject = $subject;
      }
      else{
        $mail->Subject = $sq_cms['subject'];
      }
      $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
      $mail->MsgHTML($body);
      $mail->IsHTML(true);
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
      }else{
        unlink($arrayAttachment[0]);
      }
}

  //=======================Send Mobile message Message start===========================//
  public function send_message($mobile_no, $message)
  {
    global $sms_username, $sms_password;
    $username = urlencode($sms_username);
    $sender_id = 'ITOURS';
    $sms_password = urlencode($sms_password); // optional (compulsory in transactional sms) 
    $message = urlencode($message); 
    $mobile = urlencode($mobile_no); 

    $api = "http://smsjust.com/sms/user/urlsms.php?username=$username&pass=$sms_password&senderid=$sender_id&message=$message&dest_mobileno=$mobile&response=Y"; 
    $response = file_get_contents($api,FALSE);
  }

  //Send Whatsapp messages  
  public function send_whatspp_message($mobile_no, $message)
  {
    $data = array();
    $data = [
    'phone' => $mobile_no, // Receivers phone
    'body' => $message, // Message
    ];
    $json = json_encode($data); // Encode data to JSON
    // URL for request POST /message
    $url = 'https://eu26.chat-api.com/instance17553/message?token=nrrtgd9v1ktsqid9';
    // Make a POST request
    $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
    ]);
    // Send a request
    $result = file_get_contents($url, false, $options);
  }
}

//=======================App generic functions===========================//  
include_once('app_settings/app_generic_functions.php');
include_once('app_settings/get_ids.php');
include_once('app_settings/dropdown_master.php');
include_once('app_settings/particular_functions.php');

?>