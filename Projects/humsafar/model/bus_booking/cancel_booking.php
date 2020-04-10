<?php 
class cancel_booking{

public function cancel_booking_save()
{
	$entry_id_arr = $_POST['entry_id_arr'];

	for($i=0; $i<sizeof($entry_id_arr); $i++){
		$sq_cancel = mysql_query("update bus_booking_entries set status='Cancel' where entry_id='$entry_id_arr[$i]'");
		if(!$sq_cancel){
			echo "error--Sorry, Cancellation has been successfully done!";
			exit;
		}
	}
	
	//Cancelation notification mail send
	$this->cancel_mail_send($entry_id_arr);
	echo "Cancellation has been successfully done!";
	exit;

}

public function cancel_mail_send($entry_id_arr)
{
	$sq_entry = mysql_fetch_assoc(mysql_query("select * from bus_booking_entries where entry_id='$entry_id_arr[0]'"));
	$sq_bus_info = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id='$sq_entry[booking_id]'"));
	$date = $sq_bus_info['created_at'];
    $yr = explode("-", $date);
    $year =$yr[0];
	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_bus_info[customer_id]'"));

	$content1 = '';

	for($i=0; $i<sizeof($entry_id_arr); $i++)
	{
	$sq_entry = mysql_fetch_assoc(mysql_query("select * from bus_booking_entries where entry_id='$entry_id_arr[$i]'"));

	$content1 .= '<tr>
	                <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.($i+1).'</td>
	                <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.$sq_entry['company_name'].'</td>
	              </tr>
	';

	}

	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;
	$content = '	                    
        <tr>
          <td>
            <table cellspacing="0" style="background: #fff; color: #22262e; font-size: 13px;width:100%; margin-bottom:20px;">
                <tr>
                  <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Sr.No</th>
                  <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Bus Operator</th>
                </tr>
                '.$content1.'
            </table>
          </td>
        </tr>
	';
	$subject = 'Bus Cancellation Confirmation('.get_bus_booking_id($sq_entry['booking_id'],$year).' )';
	global $model;
	$model->app_email_send('36',$sq_customer['email_id'], $content, $subject);
}


}
?>