<?php 
class ticket_cancel{

public function ticket_cancel_save()
{
	$entry_id_arr = $_POST['entry_id_arr'];

	for($i=0; $i<sizeof($entry_id_arr); $i++){
		$sq_cancel = mysql_query("update train_ticket_master_entries set status='Cancel' where entry_id='$entry_id_arr[$i]'");
		if(!$sq_cancel){
			echo "error--Sorry, Cancelation not done!";
			exit;
		}
	}

	//Cancelation notification mail send
	$this->cancel_mail_send($entry_id_arr);

	//Cancelation notification sms send
	$this->cancelation_message_send($entry_id_arr);

	echo "Train ticket has been successfully cancelled.";
}


public function cancel_mail_send($entry_id_arr){

	global $model;
	$sq_entry = mysql_fetch_assoc(mysql_query("select * from train_ticket_master_entries where entry_id='$entry_id_arr[0]'"));
	$sq_train_ticket_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$sq_entry[train_ticket_id]'"));

	$date = $sq_train_ticket_info['created_at'];
    $yr = explode("-", $date);
    $year =$yr[0];

	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_train_ticket_info[customer_id]'"));
	$content1 = '';

	for($i=0; $i<sizeof($entry_id_arr); $i++){
	$sq_entry = mysql_fetch_assoc(mysql_query("select * from train_ticket_master_entries where entry_id='$entry_id_arr[$i]'"));

	$content1 .= '<tr>
	                <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.($i+1).'</td>
	                <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.$sq_entry['first_name'].' '.$sq_entry['last_name'].'</td>
	              </tr>';
	}

	$content = '
        <tr>
          <td>
            <table cellspacing="0" style="background: #fff; color: #22262e; font-size: 13px;width:100%; margin-bottom:20px;">
                <tr>
                  <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Sr.No</th>
                  <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Passenger Name</th>
                </tr>
                '.$content1.'
            </table>
          </td>
        </tr>';
	$subject = 'Train Ticket Cancellation Confirmation('.get_train_ticket_booking_id($sq_train_ticket_info['train_ticket_id'],$year).' )';
	
	$model->app_email_send('34',$sq_customer['email_id'], $content,$subject);
}


public function cancelation_message_send($entry_id_arr){
	$sq_entry = mysql_fetch_assoc(mysql_query("select * from train_ticket_master_entries where entry_id='$entry_id_arr[0]'"));
	$sq_train_ticket_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$sq_entry[train_ticket_id]'"));
	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_train_ticket_info[customer_id]'"));
	
	$message = 'We are accepting your cancellation request for Train Ticket booking.';
  	global $model;
  	$model->send_message($sq_customer['contact_no'], $message);
}

}
?>