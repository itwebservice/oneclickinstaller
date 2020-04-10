<?php 
class cancel_booking{

public function cancel_booking_save($traveler_id_arr, $booking_id)
{
	for($i=0; $i<sizeof($traveler_id_arr); $i++){

		$sq = mysql_query("update package_travelers_details set status='Cancel' where traveler_id='$traveler_id_arr[$i]'");
		if(!$sq){
			echo "error--Sorry, Some error in cancellation.";
			exit;
		}

	}

	echo "Package booking has been successfully Cancelled.";
	//Cancelation mail send
    $this->traveler_cancelation_mail_send($traveler_id_arr, $booking_id);
}

public function traveler_cancelation_mail_send($traveler_id_arr, $booking_id)
{
  $sq_tour = mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$booking_id'"));
  $tour_date = date('d-m-Y', strtotime($sq_tour['tour_from_date'])). ' To '.date('d-m-Y', strtotime($sq_tour['tour_to_date']));
  $customer_id = $sq_tour['customer_id'];

  $date = $sq_tour['booking_date'];
  $yr = explode("-", $date);
  $year =$yr[0];
  $sq_personal_info1 = mysql_query("select * from package_travelers_details where booking_id='$booking_id'");
  $content1 = '';

    $count = 0;
    while($sq_personal_info = mysql_fetch_assoc($sq_personal_info1)){
    $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
    $count++;
    $content1 .= '<tr>
                    <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.$count.'</td>
                    <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.$sq_personal_info['first_name'].' '.$sq_personal_info['last_name'].'</td>
                  </tr>
    ';
  }

  global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;
  $content = '
        <tr>
          <td>
            <table cellspacing="0" style="width:100%">
              <tr>
                <td><span style="padding:7px 0; border-bottom:1px dotted #b3b3b3; display: inline-block;"><strong>Tour Name:</strong>&nbsp;&nbsp;'.$sq_tour['tour_name'].'</span></td>
              </tr>
              <tr>
                <td><span style="padding:7px 0; border-bottom:1px dotted #b3b3b3; display: inline-block;"><strong>Tour Date:</strong>&nbsp;&nbsp;'.$tour_date.'</span></td>
              </tr>
              <tr>
                <td>
                  <table style="background: #fff; color: #22262e; font-size: 13px;width:100%; margin-bottom:20px;">
                      <tr>
                        <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Sr.No</th>
                        <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Passenger Name</th>
                      </tr>
                      '.$content1.'
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>    
  ';
  $subject = 'Tour Cancellation Confirmation. ( '.get_package_booking_id($booking_id,$year).' ,'.$sq_tour['tour_name'].' )';
  global $model;

  $model->app_email_send('28',$sq_customer['email_id'], $content,$subject);

}
///////////////////////////////////////Traveler Cancelation mail send end/////////////////////////////////////////////////////////////////////////////////////////
}
?>