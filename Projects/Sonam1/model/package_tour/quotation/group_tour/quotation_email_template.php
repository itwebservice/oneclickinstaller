<?php
include_once('../../../../model/model.php');
$quotation_id1 = $_GET['quotation'];
$to = $_GET['to'];
$quotation_id = base64_decode($quotation_id1);

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from group_tour_quotation_master where quotation_id='$quotation_id'"));

$sq_tour = mysql_fetch_assoc(mysql_query("select * from tour_master where tour_id='$sq_quotation[tour_group_id]'"));

$schedule_content = '';

$sq_train_count = mysql_num_rows(mysql_query("select * from group_tour_program where tour_id ='$sq_quotation[tour_group_id]'"));

if($sq_train_count>0){



	$schedule_content .= '
				<tr>
					<td>
						<table style="padding:0; width:100%; border-collapse:collapse;margin-bottom:20px; text-align:left;">
								<tr>
									<td colspan="1" style="padding:5px; border:1px solid #c1c1c1; text-align:center; font-weight:600; background: #ddd;color: #3c3c3c;">Itinerary Information</td>
								</tr>																					
	';

	$count = 0;
	$sq_package_program = mysql_query("select * from group_tour_program where tour_id ='$sq_quotation[tour_group_id]'");
	while($row_schedule = mysql_fetch_assoc($sq_package_program)){

		$schedule_content .= '
						<tr>
						<td style="padding:5px;border:1px solid #c1c1c1">
						    <table style="padding:0; width:100%; border-collapse:collapse; text-align:left;">
								<tr>
									<td><span style="color: #3c3c3c;">Day : </span><span>'.++$count.'</span></td>
								<tr>
								<tr>
									<td><span style="color: #3c3c3c;">Attraction : </span><span>'.$row_schedule['attraction'].'</span></td>
								</tr>
								<tr>
									<td><span style="color: #3c3c3c;">Day-wise Program : </span><span><pre style="font-family: Roboto, sans-serif;overflow: initial;background: transparent;border: 0;white-space: pre-wrap;

    word-wrap: break-word;line-height: 21px;">'.$row_schedule['day_wise_program'].'</pre></span></td>
								</tr>
								<tr>
									<td><span style="color: #3c3c3c;">Stay : </span><span>'.$row_schedule['stay'].'</span></td>
								</tr>
								<tr>
									<td><span style="color: #3c3c3c;">Meal Plan : </span><span>'.$row_schedule['meal_plan'].'<span></td>
								</tr>
						    </table>
						</td>	
						</tr>

		';



	}



	$schedule_content .= '

						</table>

					</td>

				</tr>

	';



}



$train_content = '';

$sq_train_count = mysql_num_rows(mysql_query("select * from group_tour_quotation_train_entries where quotation_id='$quotation_id'"));

if($sq_train_count>0){



	$train_content .= '

				<tr>

					<td>

						<table style="padding:0; width:100%; border-collapse:collapse; margin-bottom:20px; text-align:left;">

								<tr>

									<td colspan="6" style="padding:5px; border:1px solid #c1c1c1; text-align:center; font-weight:600; background: #ddd;color: #3c3c3c;">Train Information</td>

								</tr>

								<tr>

									<th style="padding:5px; border:1px solid #c1c1c1">Sr_No</th>

									<th style="padding:5px; border:1px solid #c1c1c1">From</th>

									<th style="padding:5px; border:1px solid #c1c1c1">To</th>

									<th style="padding:5px; border:1px solid #c1c1c1">Class</th>

									<th style="padding:5px; border:1px solid #c1c1c1">Departure_Datetime</th>

									<th style="padding:5px; border:1px solid #c1c1c1">Arrival_Datetime</th> 

								</tr>

																

						

	';



	$count = 0;

	$sq_train = mysql_query("select * from group_tour_quotation_train_entries where quotation_id='$quotation_id'");

	while($row_train = mysql_fetch_assoc($sq_train)){



		$train_content .= '

							<tr>

								<td style="padding:5px; border:1px solid #c1c1c1">'.++$count.'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_train['from_location'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_train['to_location'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_train['class'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.get_datetime_user($row_train['departure_date']).'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.get_datetime_user($row_train['arrival_date']).'</td>

							</tr>	

		';



	}



	$train_content .= '

						</table>

					</td>

				</tr>

	';



}



$plane_content = '';

$sq_plane_count = mysql_num_rows(mysql_query("select * from group_tour_quotation_plane_entries where quotation_id='$quotation_id'"));

if($sq_plane_count>0){



	$plane_content .= '

				<tr>

					<td>

						<table style="padding:0; width:100%; border-collapse:collapse; margin-bottom:20px; text-align:left;">

								<tr>

									<td colspan="7" style="padding:5px; border:1px solid #c1c1c1; text-align:center; font-weight:600; background: #ddd;color: #3c3c3c;">Flight Information</td>

								</tr>

								<tr>

									<th style="padding:5px; border:1px solid #c1c1c1">Sr_No</th>

									<th style="padding:5px; border:1px solid #c1c1c1">From</th>

									<th style="padding:5px; border:1px solid #c1c1c1">To</th>

									<th style="padding:5px; border:1px solid #c1c1c1">Airline</th>

									<th style="padding:5px; border:1px solid #c1c1c1">Class</th>

									<th style="padding:5px; border:1px solid #c1c1c1">Departure_Datetime</th>

									<th style="padding:5px; border:1px solid #c1c1c1">Arrival_Datetime</th>

								</tr>

																

						

	';



	$count = 0;

	$sq_plane = mysql_query("select * from group_tour_quotation_plane_entries where quotation_id='$quotation_id'");

	while($row_plane = mysql_fetch_assoc($sq_plane)){

		$sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_plane[airline_name]'"));

		$plane_content .= '

							<tr>

								<td style="padding:5px; border:1px solid #c1c1c1">'.++$count.'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_plane['from_location'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_plane['to_location'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$sq_airline['airline_name'].'('.$sq_airline['airline_code'].')'.'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_plane['class'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.get_datetime_user($row_plane['dapart_time']).'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.get_datetime_user($row_plane['arraval_time']).'</td>

							</tr>	

		';



	}



	$plane_content .= '

						</table>

					</td>

				</tr>

	';



}

$cruise_content = '';

$cruise_count = mysql_num_rows(mysql_query("select * from group_tour_quotation_cruise_entries where quotation_id='$quotation_id'"));

if($cruise_count>0){
	$cruise_content .= '
				<tr>
					<td>
					<table style="padding:0; width:100%; border-collapse:collapse; margin-bottom:20px; text-align:left;">

						<tr>

							<td colspan="6" style="padding:5px; border:1px solid #c1c1c1; text-align:center; font-weight:600; background: #ddd;color: #3c3c3c;">Cruise Information</td>

						</tr>

						<tr>

							<th style="padding:5px; border:1px solid #c1c1c1">Sr_No</th>

							<th style="padding:5px; border:1px solid #c1c1c1">Departure_Datetime</th>

							<th style="padding:5px; border:1px solid #c1c1c1">Arrival_Datetime</th>

							<th style="padding:5px; border:1px solid #c1c1c1">Route</th>

							<th style="padding:5px; border:1px solid #c1c1c1">Cabin</th>

							<th style="padding:5px; border:1px solid #c1c1c1">Sharing</th> 

						</tr>																		
	';
	$count = 0;
	$sq_cruise = mysql_query("select * from group_tour_quotation_cruise_entries where quotation_id='$quotation_id'");
	while($row_cruise = mysql_fetch_assoc($sq_cruise)){

		$cruise_content .= '
							<tr>
			   				    <td style="padding:5px; border:1px solid #c1c1c1">'.++$count.'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.get_datetime_user($row_cruise['dept_datetime']).'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.get_datetime_user($row_cruise['arrival_datetime']).'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_cruise['route'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_cruise['cabin'].'</td>

								<td style="padding:5px; border:1px solid #c1c1c1">'.$row_cruise['sharing'].'</td>
							</tr>	
		';
	}
	$cruise_content .= '
						</table>
					</td>
				</tr>
	';



}


	global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
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
      
      <table cellspacing="0" cellpadding="0" style="width: 900px;margin: 0 auto;border: 1px solid #e2e2e2;margin-top: 1px;border-bottom: 0;">
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
            <table style="width: 900px;margin-left: -4px;margin-right: -4px;background-image: url('.BASE_URL.'/images/email/bg.jpg);background-repeat: no-repeat;background-size: cover;padding: 50px 15px 15px 50px;color: #fff;line-height: 36px;font-size: 14px;">
        <tr>
          <td>

			<table style="width:100%">	

				<tr>

					<td>

						<table style="padding:0; width:100%; border-collapse:collapse; margin-bottom:20px">

								<tr>

									<td colspan="3" style="padding:5px; border:1px solid #c1c1c1; text-align:center; font-weight:600; background: #ddd;color: #3c3c3c;">Quotation Information</td>

								</tr>

								<tr>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Name</span> : '.$sq_quotation['customer_name'].'</td>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Tour Name</span> : '.$sq_quotation['tour_name'].'</td>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Tour Date</span> : '.date('d-m-Y', strtotime($sq_quotation['from_date'])).' to '.date('d-m-Y', strtotime($sq_quotation['to_date'])).'</td>

								</tr>

								<tr>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Total Days</span> : '.$sq_quotation['total_days'].'</td>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Adults</span> : '.$sq_quotation['total_adult'].'</td>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Childrens</span> : '.$sq_quotation['total_children'].'</td>

								</tr>

								<tr>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Infants</span> : '.$sq_quotation['total_infant'].'</td>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Child Without Bed</span> : '.$sq_quotation['children_without_bed'].'</td>

									<td style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Child With Bed</span> : '.$sq_quotation['children_with_bed'].'</td>

								</tr>

								<tr>

									<td colspan="2" style="padding:5px; border:1px solid #c1c1c1"><span style="font-weight: 600; color: #3c3c3c;">Quotation Cost</span> : <b>'.$sq_quotation['quotation_cost'].'</b></td>

								</tr>

						</table>

					</td>

				</tr>

				'.$train_content.$plane_content.$cruise_content.$schedule_content.'					
				<tr>

					<td>

						<table style="padding:0; width:100%; border-collapse:collapse; margin-bottom:20px; text-align:left">

								<tr>

									<td colspan="2" style="padding:5px; border:1px solid #c1c1c1; text-align:center; font-weight:600; background: #ddd;color: #3c3c3c;">Terms & Conditions</td>

								</tr>									

								<tr>

									<td style="padding:5px; border:1px solid #c1c1c1">'.$sq_quotation['terms'].'</td>

								</tr>

						</table>

					</td>

				</tr>
				<tr>

					<td>

						<table style="padding:0; width:100%; border-collapse:collapse; margin-bottom:20px; text-align:left">

								<tr>

									<td colspan="2" style="padding:5px; border:1px solid #c1c1c1; text-align:center; font-weight:600; background: #ddd;color: #3c3c3c;">Inclusions & Exclusions</td>

								</tr>									

								<tr>

									<th style="padding:5px; border:1px solid #c1c1c1">Inclusions</th>

									<td style="padding:5px; border:1px solid #c1c1c1">'.$sq_quotation['incl'].'</td>

								</tr>

								<tr>

									<th style="padding:5px; border:1px solid #c1c1c1">Exclusions</th>

									<td style="padding:5px; border:1px solid #c1c1c1">'.$sq_quotation['excl'].'.</td>

								</tr>

						</table>

					</td>

				</tr>

				 

			</table>

		</td>

	</tr>	
	</table>
              </td>
          </tr>
          <tr>
            <td colspan="2">
              <table style="width: 900px;margin-left: -4px;margin-right: -4px;background: #1da38a;color: #fff;padding-left: 50px;font-size: 14px;padding: 10px 0 10px 50px;">
                <tr>
                  <td><span>'.$app_name.'</span></td>
                  <td style="text-align: right;padding-right: 38px;"><img src="'.BASE_URL.'/images/email/phone.png" style="margin-bottom: -1px;"> <span>'.$app_contact_no.'</span></td>
                </tr> 
              </table>
            </td>
          </tr>
        </table>
      </body>
    </html>	

';

echo $content;

$sq_quot = mysql_fetch_assoc(mysql_query("select * from group_tour_quotation_master where quotation_id='$quotation_id'"));
if($sq_quot['customer_status'] == '' && $to == ''){

	$date = date('d-m-Y H:i:s');
	$content1 ='

	<tr>
	<table width="85%" cellspacing="0" cellpadding="5" style="color: #888888;border: 1px solid #888888;margin: 0px auto;margin-top:20px; min-width: 100%;" role="presentation">
	  <tr><td style="text-align:left;border: 1px solid #888888;">Name</td>   <td style="text-align:left;border: 1px solid #888888;">'.$sq_quotation['customer_name'].'</td></tr>
	  <tr><td style="text-align:left;border: 1px solid #888888;">Quotation Id</td>   <td style="text-align:left;border: 1px solid #888888;" >'.$quotation_id.'</td></tr>
	  <tr><td style="text-align:left;border: 1px solid #888888;">Review Date</td>   <td style="text-align:left;border: 1px solid #888888;">'.$date.'</td></tr>
	</table>
  </tr>';
	$subject = "Quotation Reviewed by Customer!";
	$model->app_email_send('9','Admin',$app_email_id, $content1, $subject,'1');
	$sq_update = mysql_query("update group_tour_quotation_master set customer_status='yes' where quotation_id='$quotation_id'");
}

?>