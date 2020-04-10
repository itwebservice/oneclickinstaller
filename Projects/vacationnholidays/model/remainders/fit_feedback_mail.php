<?php
include_once('../model.php');
$today = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('-3 days', strtotime($today)));

$sq_booking_info = mysql_num_rows(mysql_query("select * from package_tour_booking_master where tour_to_date='$end_date'"));
if($sq_booking_info>0){

	$sq_booking = mysql_query("select * from package_tour_booking_master where tour_to_date='$end_date'");
	while($row_booking= mysql_fetch_assoc($sq_booking)){
		$customer_id = $row_booking['customer_id'];
		$email_id = $row_booking['email_id'];
		$mobile_no = $row_booking['mobile_no'];
		$tour_name = $row_booking['tour_name'];
		$booking_id = $row_booking['booking_id'];

		$booking_id1 = get_package_booking_id($booking_id);
		$journey_date = date('d-m-Y',strtotime($row_booking['tour_from_date'])).' To '.date('d-m-Y',strtotime($row_booking['tour_to_date']));

		$sq_customer = mysql_query("select * from customer_master where customer_id ='$customer_id'");

		while ($row_cust = mysql_fetch_assoc($sq_customer)) {				
			$username = $row_cust['contact_no'];
			$password = $row_cust['email_id'];
			$cust_name = $row_cust['first_name'].' '.$row_cust['last_name'];

			$sq_count = mysql_num_rows(mysql_query("SELECT * from  remainder_status where remainder_name = 'fit_feedback_remainder' and date='$today' and status='Done'"));
			if($sq_count==0){
				feedback_email_send($email_id,$booking_id,$tour_name,$journey_date,$username,$password,$cust_name,$customer_id);
				feedback_sms_send($mobile_no);
			}
		}
	}
}
$row=mysql_query("SELECT max(id) as max from remainder_status");
$value=mysql_fetch_assoc($row);
$max=$value['max']+1;
$sq_check_status=mysql_query("INSERT INTO `remainder_status`(`id`, `remainder_name`, `date`, `status`) VALUES ('$max','fit_feedback_remainder','$today','Done')");

function feedback_email_send($email_id,$booking_id,$tour_name,$journey_date,$username,$password,$cust_name,$customer_id)
{
	global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;
	$link = BASE_URL.'view/customer';
	 
	$content = '
	<table style="padding:0 30px">		
		<tr>
			<td><p style="line-height: 24px;"><a href='.$link.'>Login</a> </p>
				<p style="line-height: 24px;">Booking ID :'.$booking_id.'</p>
				<p style="line-height: 24px;">Tour Name :'.$tour_name.'</p>
				<p style="line-height: 24px;">Journey Date :'.$journey_date.'</p>
			</td>
		</tr>
		<tr>
			<td>
       			<a style="background: #009898;color: #fff; border:aliceblue;width:24%;text-decoration: none;  display: block;text-transform: uppercase;padding-left: 10px;" href="'.BASE_URL.'view/customer/other/customer_feedback/customer_feedback_form.php?customer_id='.$customer_id.'&booking_id='.$booking_id.'&tour_name=Package Booking">Tour Feedback</a> 
      		</td>
		</tr>
	</table>	
	';
	
	global $model;
	$subject = 'Invite you to leave us your FEEDBACK! (Tour Name : '.$tour_name.', Customer Name : '.$cust_name.' )';
	$model->app_email_send('78',$email_id, $content,$subject'1');
}
function feedback_sms_send($mobile_no){
	global $app_name,$model;
	$message = "We take the opportunity of your valuable feedback of ".$app_name." tours that will help to continue our high quality and to save precious customers.";
   	$model->send_message($mobile_no, $message);
}