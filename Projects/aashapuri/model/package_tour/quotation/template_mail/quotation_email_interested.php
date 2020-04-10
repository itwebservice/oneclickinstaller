<?php

include_once('../../../model.php');
$quotation_id = $_GET['quotation_id'];
$quotation_id = base64_decode($quotation_id);
$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));

$date = $sq_quotation['created_at'];
$yr = explode("-", $date);
$year =$yr[0];

$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));


if($sq_emp_info['first_name']==''){

	$email_id = $app_email_id;
}
else{
	$email_id = $sq_emp_info['email_id'];
}

global $app_name;
global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

$date = date('d-m-Y H:i:s');

$content = '

			<table style="padding:0 30px; width:100%">	

				<tr>

					<td>

						<p>The following customer is interested for Tour.

						<p>Customer Name : <strong>'.$sq_quotation['customer_name'].'</strong></p>

						<p>Quotation ID : <strong>PTQ-'.$quotation_id.'</strong></p>

						<p>Email ID : <strong>'.$sq_quotation['email_id'].'</strong></p>

						<p>Date/Time of review :- <strong>'.$date.'</strong></p>

					</td>

				</tr>				

			</table>			

';

$subject = 'New Customer is Interested for Tour! (Quotation ID : '.get_quotation_id($quotation_id,$year).' , Customer Name : '.$sq_quotation['customer_name'].' )';

$model->app_email_send('11',$email_id, $content,$subject);
echo "Thanks for showing interest go back to see your quotation again...";
?>
<script>
setTimeout(function () {
	window.history.back();
},10000);
</script>