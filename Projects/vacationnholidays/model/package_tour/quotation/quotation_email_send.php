<?php 
class quotation_email_send
{
public function quotation_email()
{

	$quotation_id_arr = $_POST['quotation_id_arr'];
	$msg_status = $_POST['msg_status'];
	$i = 0;
	$whatsapp_msg = '';

	global $app_name, $app_cancel_pdf,$model,$quot_note;
	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;	

	$url1 = "'.BASE_URL.'model/package_tour/quotation/quotation_email_template.php?quotation_id='.$quotation_id.'";


	if($app_cancel_pdf == ''){	$url =  BASE_URL.'view/package_booking/quotation/cancellaion_policy_msg.php'; }

	else{

		$url = explode('uploads', $app_cancel_pdf);

		$url = BASE_URL.'uploads'.$url[1];

	}



	$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id_arr[0]'"));

	$sq_cost =  mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id = '$quotation_id_arr[0]'"));

	$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));

	$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));

	if($sq_emp_info['first_name']==''){

		$emp_name = 'Admin';

	}

	else{

		$emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];

	}

	$content = '
	

		<tr>
            <td>
				<table style="width:100%">
					<tr>
						<td colspan="2">
							<table style="background: #fff; color: #22262e; font-size: 13px;width:100%; margin-bottom:20px;">
									<tr>
										<td colspan="3" style="padding: 5px;border: 1px solid #c1c1c1;text-align: center;font-weight: 600;background: #ddd;font-size: 16px;color: #4e4e4e;">Quotation Information</td>
									</tr>
									<tr>
										<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Name</span> : '.$sq_quotation['customer_name'].'
										<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Tour Date</span> : '.date('d-m-Y', strtotime($sq_quotation['from_date'])).' to '.date('d-m-Y', strtotime($sq_quotation['to_date'])).'</td>
									</tr>
									<tr>
										<td colspan="2" style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #009898;">Created By</span> : <b style="color: #009898;">'.$emp_name.'</b></td>
									</tr>
									<tr>
										<td style="padding-left: 10px;"><span style="font-weight: 600;color: #3c3c3c;"><em style="font-style: normal;">Cancellation Policy : </em> <b style="font-size: 14px;padding-left: 5px;"><a style="color: #4545f0;" href="'.$url.'">Click here</a></b>
										</td>
									</tr>
							</table>
						</td>
					</tr>	
				</table>	
		    </td>
		</tr>	

		<tr>
            <td>
                <table style="width: 100%;">
                   	<tr>		

				

	';

    

    for($i=0;$i<sizeof($quotation_id_arr);$i++)
    {

	$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id_arr[$i]'"));
	$sq_cost =  mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id = '$quotation_id_arr[$i]'"));
	$quotation_cost = $sq_cost['total_tour_cost'] + $sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'] + $sq_quotation['visa_cost'] + $sq_quotation['guide_cost'] + $sq_quotation['misc_cost'];	
	$sq_tours_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$sq_quotation[package_id]'"));
	
	$quotation_no = base64_encode($quotation_id_arr[$i]);

	$content .= '          
                        <td>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" style="width: 100%;background: #f3f3f3;border: 1px solid #e8e8e8;border-top-left-radius: 5px;border-top-right-radius: 5px;text-align: center;font-weight: 600;">
                                            <tr>
                                                <td>
                                                  <img src="'.BASE_URL.'images/thumb.png" style="width: 40%;margin-top: 5px;box-shadow: 0px 0px 2px 1px #c5c5c5;background: #fff;border-radius: 50%;max-width: 50px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 15px;text-transform: capitalize;color: #22262e;line-height: 18px;">'.$sq_tours_package['package_name'].'</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #f76600;font-size: 14px;">'.$sq_tours_package['total_days'].'D/'.$sq_tours_package['total_nights'].'N'.'</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #009898;font-size: 16px;">Rs.'.number_format($quotation_cost,2).'</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr bgcolor="#009898">
									<td colspan="2" style=" padding: 0; width: 100%; text-align: center;display: block;">
										<a style="background: #009898;color: #fff; border:aliceblue;width:100%;text-decoration: none;  display: block;text-transform: uppercase;" href="'.BASE_URL.'model/package_tour/quotation/single_quotation.php?quotation='.$quotation_no.'">View quotation</a> 
									</td>
                                </tr>
                            </table>
                        </td>	';

		//Whatsapp Message to send
		if($msg_status == 'true')
		{
			//$whatsapp_msg .= 'http://www.itouroperatorsoftware.com/';
			$whatsapp_msg = '*Package Name :* '.$sq_tours_package['package_name'].'
*Duration :* '.$sq_tours_package['total_days'].'D/'.$sq_tours_package['total_nights'].'N
*Cost :* Rs.'.number_format($quotation_cost,2).'

Click below Link : 👇 
'.BASE_URL.'model/package_tour/quotation/single_quotation.php?quotation_id='.$quotation_id;
			//mobile no with country code
			$mobile_no = '+91 '.$sq_quotation['mobile_no'];
		    $model->send_whatspp_message($mobile_no,$whatsapp_msg);
		}


    }
	$content .= '
	<tr>
		<td colspan="3">
			<table style="width:100%">
				<tr>
					<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #009898;">'.$quot_note.'</span></td>
				</tr>
			</table>	
		</td>
	<tr>';


	$content .= '</tr>
			</table>	
        </td>
    </tr>';

    $subject = 'New Quotation : ('.$sq_tours_package['package_name'].' )';
	$model->app_email_send('8',$sq_quotation['email_id'], $content,$subject,'1');

	echo "Quotation successfully sent.";

	exit;
}
}

?>