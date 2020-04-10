<?
include_once('../model.php');
 function employee_sign_up_mail($first_name, $last_name, $username, $password, $email_id)
{
  global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
  global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;
   $link = BASE_URL.'view/customer';
  $content = '
  <tr>
    <td colspan="2">
      <table style="padding:0 30px">
      <tr>
          <td>Dear <span>'.$first_name.' '.$last_name.' </span>,</td>
      </tr>
      <tr>
          <td>Welcome aboard!</td>
      </tr>
      <tr>
         <td colspan="2"> <p style="margin: 0;line-height: 24px;">Thank you for Choosing <span>'.$app_name.'</span>for your exciting vacation with your family or friends.</p></td>
      </tr>
      <tr>
        <td colspan="2">
          <p style="margin: 0;line-height: 24px;">We assure you the Best of the Services to ensure that you Simply Enjoy the Tour & Create Cherishing Memories& leave all the worries about making your tour a great one to us.</p>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <p style="margin: 0;line-height: 24px;">For your Highest Convenience we are happy to provide you access to our system where in you can check our upcoming offers, download various documents like Confirmation Vouchers, Invoices, Air Tickets Booked through us etc.</p>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <p style="margin: 0;line-height: 24px;">With this we Hope that You Travel Long with <span>'.$app_name.'</span>, your ultimate choice when it comes to create Cherishing Memories and Lasting Experience.</p>
        </td>
      </tr>
        <tr>
          <td colspan="2">
            '.mail_login_box($username, $password, $link).'
          </td>
        </tr>
      </table>  
      </td>
    </tr>
  ';

  global $model;
  $subject = "Welcome Aboard";
  $model->app_email_master($email_id, $content, $subject,'1');
}


?>