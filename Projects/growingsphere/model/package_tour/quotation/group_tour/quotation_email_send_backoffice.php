<?php 

class quotation_email_send_backoffice{





public function quotation_email_backoffice()
{

	$quotation_id = $_POST['quotation_id'];
	$quotation_no = base64_encode($quotation_id);

	$email_id = $_POST['email_id'];

	$sq_quotation = mysql_fetch_assoc(mysql_query("select * from group_tour_quotation_master where quotation_id='$quotation_id'"));

	$quotation_date = $sq_quotation['quotation_date'];
	$yr = explode("-", $quotation_date);
	$year =$yr[0];

	$sq_inclusion = mysql_fetch_assoc(mysql_query("select * from inclusions_exclusions_master where type='Inclusion'"));
	$sq_exclusion = mysql_fetch_assoc(mysql_query("select * from inclusions_exclusions_master where type='Exclusion'"));	

	$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
	$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));



	if($sq_emp_info['first_name']==''){

		$emp_name = 'Admin';

	}

	else{

		$emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];

	}



	global $app_name, $app_cancel_pdf;



	$url = explode('uploads', $app_cancel_pdf);

	$url = BASE_URL.'uploads'.$url[1];	



	$content = '
		<tr>
			<td>
				<table style="padding:0 30px; width:100%">	

					<tr>

						<td style=" padding: 0; width: 100%; text-align: center; margin-bottom: 20px !important;

                              display: block;">

							<a style="background: #3d9482; border-radius: 3px;color: #ffffff;text-decoration: none; text-transform: uppercase; font-size: 18px; font-weight: 500; display: block; margin:0 auto 5px; width: 232px; text-align: center;padding:5px 0;" href="'.BASE_URL.'model/package_tour/quotation/group_tour/quotation_email_template.php?quotation='.$quotation_no.'&to=user">View quotation</a> 

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

										<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Tour Name</span> : '.$sq_quotation['tour_name'].'

										</td>

									</tr>

									<tr>

									<td style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #3c3c3c;">Tour Date</span> : '.date('d-m-Y', strtotime($sq_quotation['from_date'])).' to '.date('d-m-Y', strtotime($sq_quotation['to_date'])).'</td>

									</tr>

									 

									<tr>

										<td colspan="2" style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #009898;">Quotation Cost</span> : <b style="color: #009898;">'.$sq_quotation['quotation_cost'].'</b></td>

									</tr>

									<tr>

										<td colspan="2" style="padding-left: 10px;border-bottom: 1px solid #eee;"><span style="font-weight: 600; color: #009898;">Created By</span> : <b style="color: #009898;">'.$emp_name.'</b></td>

									</tr>
							</table>

						</td>

					</tr>	

					 

				</table>

				
			</td>
		</tr>

				

	';


	$subject = "Confirmed Quotation"."(".get_quotation_id($quotation_id,$year).")";

	global $model;

	// $model->app_email_master($email_id, $content, $subject);
	$model->app_email_send('7',$email_id, $content,$subject,'1');
	echo "Quotation sent successfully!";

	exit;

	

}



}

?>