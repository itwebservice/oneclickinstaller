<?php 

$flag = true;

class ticket_update{



public function ticket_master_update()

{

	$row_spec = 'sales';

	$train_ticket_id = $_POST['train_ticket_id'];



	$customer_id = $_POST['customer_id'];

	$type_of_tour = $_POST['type_of_tour'];

	$basic_fair = $_POST['basic_fair'];

	$service_charge = $_POST['service_charge'];

	$delivery_charges = $_POST['delivery_charges'];

	$gst_on = $_POST['gst_on'];

	$taxation_type = $_POST['taxation_type'];

	$taxation_id = $_POST['taxation_id'];

	$service_tax = $_POST['service_tax'];

	$service_tax_subtotal = $_POST['service_tax_subtotal'];

	$net_total = $_POST['net_total'];

	$payment_due_date = $_POST['payment_due_date'];
	$booking_date1 = $_POST['booking_date1'];


	$honorific_arr = $_POST['honorific_arr'];

	$first_name_arr = $_POST['first_name_arr'];

	$middle_name_arr = $_POST['middle_name_arr'];

	$last_name_arr = $_POST['last_name_arr'];

	$birth_date_arr = $_POST['birth_date_arr'];

	$adolescence_arr = $_POST['adolescence_arr'];

	$coach_number_arr = $_POST['coach_number_arr'];

	$seat_number_arr = $_POST['seat_number_arr'];

	$ticket_number_arr = $_POST['ticket_number_arr'];

	$entry_id_arr = $_POST['entry_id_arr'];



	$travel_datetime_arr = $_POST['travel_datetime_arr'];

	$travel_from_arr = $_POST['travel_from_arr'];

	$travel_to_arr = $_POST['travel_to_arr'];

	$train_name_arr = $_POST['train_name_arr'];

	$train_no_arr = $_POST['train_no_arr'];

	$ticket_status_arr = $_POST['ticket_status_arr'];

	$class_arr = $_POST['class_arr'];

	$booking_from_arr = $_POST['booking_from_arr'];

	$boarding_at_arr = $_POST['boarding_at_arr'];

	$arriving_datetime_arr = $_POST['arriving_datetime_arr'];

	$trip_entry_id = $_POST['trip_entry_id'];



	$payment_due_date = get_date_db($payment_due_date);	
	$booking_date1 = get_date_db($booking_date1);	




	begin_t();



	$sq_ticket_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$train_ticket_id'"));





	//**Update ticket

	$sq_ticket = mysql_query("UPDATE train_ticket_master SET customer_id='$customer_id', type_of_tour='$type_of_tour', basic_fair='$basic_fair', service_charge='$service_charge', delivery_charges='$delivery_charges', gst_on='$gst_on', taxation_type='$taxation_type', taxation_id='$taxation_id', service_tax='$service_tax', service_tax_subtotal='$service_tax_subtotal', net_total='$net_total', payment_due_date='$payment_due_date',created_at='$booking_date1' WHERE train_ticket_id='$train_ticket_id'");

	if(!$sq_ticket){

		$GLOBALS['flag'] = false;

		echo "error--Sorry, Ticket not updated!";

	}



	//**Updating entries

	for($i=0; $i<sizeof($first_name_arr); $i++){



		$birth_date_arr[$i] = get_date_db($birth_date_arr[$i]);



		if($entry_id_arr[$i]==""){



			$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from train_ticket_master_entries"));

			$entry_id = $sq_max['max'] + 1;



			$sq_entry = mysql_query("INSERT INTO train_ticket_master_entries (entry_id, train_ticket_id, honorific, first_name, middle_name, last_name, birth_date, adolescence, coach_number, seat_number, ticket_number) VALUES ('$entry_id', '$train_ticket_id', '$honorific_arr[$i]', '$first_name_arr[$i]', '$middle_name_arr[$i]', '$last_name_arr[$i]', '$birth_date_arr[$i]', '$adolescence_arr[$i]', '$coach_number_arr[$i]', '$seat_number_arr[$i]', '$ticket_number_arr[$i]')");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Some entries not saved!";

			}



		}

		else{



			$sq_entry = mysql_query("UPDATE train_ticket_master_entries SET  honorific='$honorific_arr[$i]', first_name='$first_name_arr[$i]', middle_name='$middle_name_arr[$i]', last_name='$last_name_arr[$i]', birth_date='$birth_date_arr[$i]', adolescence='$adolescence_arr[$i]', coach_number='$coach_number_arr[$i]', seat_number='$seat_number_arr[$i]', ticket_number='$ticket_number_arr[$i]' WHERE entry_id='$entry_id_arr[$i]' ");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Some entries not updated!";

			}



		}

		



	}



	//**Updating trip

	for($i=0; $i<sizeof($travel_datetime_arr); $i++){



		$travel_datetime_arr[$i] = get_datetime_db($travel_datetime_arr[$i]);

		$arriving_datetime_arr[$i] = get_datetime_db($arriving_datetime_arr[$i]);



		if($trip_entry_id[$i]==""){



			$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from train_ticket_master_trip_entries"));

			$entry_id = $sq_max['max'] + 1;

			



			$sq_entry = mysql_query("INSERT INTO train_ticket_master_trip_entries (entry_id, train_ticket_id, travel_datetime, travel_from, travel_to, train_name, train_no, ticket_status, class, booking_from, boarding_at, arriving_datetime) VALUES ('$entry_id', '$train_ticket_id', '$travel_datetime_arr[$i]', '$travel_from_arr[$i]', '$travel_to_arr[$i]', '$train_name_arr[$i]', '$train_no_arr[$i]', '$ticket_status_arr[$i]', '$class_arr[$i]', '$booking_from_arr[$i]', '$boarding_at_arr[$i]', '$arriving_datetime_arr[$i]')");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Some trip entries not saved!";

			}



		}

		else{



			$sq_entry = mysql_query("UPDATE train_ticket_master_trip_entries SET  travel_datetime='$travel_datetime_arr[$i]', travel_from='$travel_from_arr[$i]', travel_to='$travel_to_arr[$i]', train_name='$train_name_arr[$i]', train_no='$train_no_arr[$i]', ticket_status='$ticket_status_arr[$i]', class='$class_arr[$i]', booking_from='$booking_from_arr[$i]', boarding_at='$boarding_at_arr[$i]', arriving_datetime='$arriving_datetime_arr[$i]' WHERE entry_id='$trip_entry_id[$i]' ");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Some trip entries not updated!";

			}



		}

		



	}



	//Finance update

	$this->finance_update($sq_ticket_info, $row_spec);



	if($GLOBALS['flag']){

		commit_t();

		echo "Train Ticket Booking has been successfully updated.";

		exit;	

	}

	else{

		rollback_t();

		exit;

	}



		

}



public function finance_update($sq_ticket_info, $row_spec)
{
	$train_ticket_id = $_POST['train_ticket_id'];
	$customer_id = $_POST['customer_id'];
	$basic_fair = $_POST['basic_fair'];
	$service_charge = $_POST['service_charge'];
  $delivery_charges = $_POST['delivery_charges'];
	$gst_on = $_POST['gst_on'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$net_total = $_POST['net_total'];
	$bank_id1 = $_POST['bank_id'];
	$booking_date1 = $_POST['booking_date1'];

	$booking_date = get_date_db($booking_date1);
	$year2 = explode("-", $booking_date);
	$yr2 =$year2[0];

	$train_sale_amount = $basic_fair + $service_charge;
	//get total payment against train_ticket id
  $sq_train_ticket = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from train_ticket_payment_master where train_ticket_id='$train_ticket_id'"));
	$balance_amount = $net_total - $sq_train_ticket['payment_amount'];

  //Getting customer Ledger
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
	$cust_gl = $sq_cust['ledger_id'];

	//Getting cash/Bank Ledger
	if($payment_mode == 'Cash') {  $pay_gl = 20; }
	else{ 
		$sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id1' and user_type='bank'"));
		$pay_gl = $sq_bank['ledger_id'];
		}

	global $transaction_master;

	////////////Sales/////////////
	$module_name = "Train Ticket Booking";
	$module_entry_id = $train_ticket_id;
	$transaction_id = "";
	$payment_amount = $train_sale_amount;
	$payment_date = $booking_date;
	$payment_particular = get_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $booking_date, $train_sale_amount, $customer_id);
	$ledger_particular = get_ledger_particular('To','Train Ticket Sales');
	$old_gl_id = $gl_id = 133;
	$payment_side = "Credit";
	$clearance_status = "";
	$transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

	///////// Delivery charges //////////
	$module_name = "Train Ticket Booking";
	$module_entry_id = $train_ticket_id;
	$transaction_id = "";
	$payment_amount = $delivery_charges;
	$payment_date = $booking_date;
	$payment_particular = get_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $booking_date, $delivery_charges, $customer_id);
	$ledger_particular = get_ledger_particular('To','Train Ticket Sales');
	$old_gl_id = $gl_id = 33;
	$payment_side = "Credit";
	$clearance_status = "";
	$transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

	/////////Tax Amount/////////
	tax_reflection_update('Train Ticket Booking',$service_tax_subtotal,$taxation_type,$train_ticket_id,get_train_ticket_booking_id($train_ticket_id,$yr2),$booking_date, $customer_id, $row_spec);


	////////Balance Amount//////
	$module_name = "Train Ticket Booking";
	$module_entry_id = $train_ticket_id;
	$transaction_id = "";
	$payment_amount = $balance_amount;
	$payment_date = $booking_date;
	$payment_particular = get_sales_particular(get_train_ticket_booking_id($train_ticket_id,$yr2), $booking_date, $balance_amount, $customer_id);
	$ledger_particular = get_ledger_particular('To','Train Ticket Sales');
	$old_gl_id = $gl_id = $cust_gl;
	$payment_side = "Debit";
	$clearance_status = "";
	$transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);


}



}

?>