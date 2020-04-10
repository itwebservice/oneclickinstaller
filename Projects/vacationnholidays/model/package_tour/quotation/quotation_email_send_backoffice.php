<?php 
class quotation_email_send_backoffice{


public function quotation_email_backoffice()
{
	$quotation_id = $_POST['quotation_id'];
	$quotation_no = base64_encode($quotation_id);
	$email_id = $_POST['email_id'];

	$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));
	$date = $sq_quotation['created_at'];
    $yr = explode("-", $date);
    $year =$yr[0];
	$sq_cost =  mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id = '$quotation_id'"));
	$quotation_cost = $sq_cost['total_tour_cost'] + $sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'] + $sq_quotation['visa_cost'] + $sq_quotation['guide_cost'] + $sq_quotation['misc_cost'];

	$sq_package_name = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$sq_quotation[package_id]'"));

	$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
	$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));

	if($sq_emp_info['first_name']==''){
		$emp_name = 'Admin';
	}
	else{
		$emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
	}

	global $app_name, $app_cancel_pdf;
	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

	$sq_package_program = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id ='$sq_quotation[package_id]'"));

    if($app_cancel_pdf == ''){	$url =  BASE_URL.'view/package_booking/quotation/cancellaion_policy_msg.php'; }
	else{
		$url = explode('uploads', $app_cancel_pdf);
		$url = BASE_URL.'uploads'.$url[1];
	}	

	$content = '
		<tr>
			<td>
				<table style="width:100%">	
					<tr>
						<td style=" padding: 0; width: 100%; text-align: center; margin: 20 0px !important;
		                      display: block;">
							<a style="background: #3d9482; border-radius: 3px;color: #ffffff;text-decoration: none; text-transform: uppercase; font-size: 18px; font-weight: 500; display: block; margin:0 auto 5px; width: 232px; text-align: center;padding:5px 0;" href="'.BASE_URL.'model/package_tour/quotation/quotation_email_template.php?quotation_id='.$quotation_no.'">View quotation</a> 
						</td>
					</tr>					
					<tr>
						<td>
							<table style="background: #fff; color: #22262e; font-size: 13px;width:100%; margin-bottom:20px;">
									<tr>
										<td colspan="3" style="padding: 5px;border: 1px solid #c1c1c1;text-align: center;font-weight: 600;background: #ddd;font-size: 16px;color: #4e4e4e;">Quotation Information</td>
									</tr>
									<tr>
										<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Name</span> : '.$sq_quotation['customer_name'].'
										</td>
									</tr>
									<tr>
										<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Package Name</span> : '.$sq_package_program['package_name'].'
										</td>
									</tr>
									<tr>
									<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Tour Date</span> : '.date('d-m-Y', strtotime($sq_quotation['from_date'])).' to '.date('d-m-Y', strtotime($sq_quotation['to_date'])).'</td>
									</tr>
									 
									<tr>
										<td colspan="2" style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #009898;">Quotation Cost</span> : <b style="color: #009898;">'.number_format($quotation_cost,2).'</b></td>
									</tr>
									<tr>
										<td colspan="2" style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #009898;">Created By</span> : <b style="color: #009898;">'.$emp_name.'</b></td>
									</tr>
									<tr>
										<td style="padding-left: 10px;"><span style="font-weight: 600;color: #3c3c3c;"><em style="font-style: normal;">Cancellation Policy : </em> <b style="font-size: 14px;
												padding-left: 5px;"><a style="color: #4545f0;" href="'.$url.'">Click here</a></b></td>
									</tr>
							</table>
						</td>
					</tr>	
					 
				</table>
			</td>
		</tr>
	';
	$subject = 'Confirmed Quotation Details : ( Quotation ID : '.get_quotation_id($quotation_id,$year).' )';
    //$emp_id = $row['email_id'];
	global $model;
	$model->app_email_send('7',$email_id, $content,$subject,'1');
	echo "Quotation sent successfully!";
	exit;
	
}


}
?>