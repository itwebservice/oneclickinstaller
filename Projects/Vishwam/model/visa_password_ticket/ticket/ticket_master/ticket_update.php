<?php 

$flag = true;

class ticket_update{



public function ticket_master_update()

{

	$row_spec = "sales";

	$ticket_id = $_POST['ticket_id'];

	$customer_id = $_POST['customer_id'];	

	$tour_type = $_POST['tour_type'];

	$type_of_tour = $_POST['type_of_tour'];



	$adults = $_POST['adults'];

	$childrens = $_POST['childrens'];

	$infant = $_POST['infant'];

	$adult_fair = $_POST['adult_fair'];

	$children_fair = $_POST['children_fair'];

	$infant_fair = $_POST['infant_fair'];

	$basic_cost = $_POST['basic_cost'];

	$basic_cost_markup = $_POST['basic_cost_markup'];

	$basic_cost_discount = $_POST['basic_cost_discount'];

	$yq_tax = $_POST['yq_tax'];

	$yq_tax_markup = $_POST['yq_tax_markup'];

	$yq_tax_discount = $_POST['yq_tax_discount'];

	$g1_plus_f2_tax = $_POST['g1_plus_f2_tax'];

	$service_charge = $_POST['service_charge'];

	$taxation_type = $_POST['taxation_type'];

	$taxation_id = $_POST['taxation_id'];

	$service_tax = $_POST['service_tax'];

	$service_tax_subtotal = $_POST['service_tax_subtotal'];

	$tds = $_POST['tds'];

	$due_date = $_POST['due_date'];
	$booking_date = $_POST['booking_date1'];
	$sup_id = $_POST['sup_id'];


	$ticket_total_cost = $_POST['ticket_total_cost'];

	

	$first_name_arr = $_POST['first_name_arr'];

	$middle_name_arr = $_POST['middle_name_arr'];

	$last_name_arr = $_POST['last_name_arr'];

	$birth_date_arr = $_POST['birth_date_arr'];

	$adolescence_arr = $_POST['adolescence_arr'];

	$ticket_no_arr = $_POST['ticket_no_arr'];

	$gds_pnr_arr = $_POST['gds_pnr_arr'];

	$entry_id_arr = $_POST['entry_id_arr'];

	$from_city_id_arr = $_POST['from_city_id_arr'];
	$to_city_id_arr = $_POST['to_city_id_arr'];

	$departure_datetime_arr = $_POST['departure_datetime_arr'];

	$arrival_datetime_arr = $_POST['arrival_datetime_arr'];

	$airlines_name_arr = $_POST['airlines_name_arr'];

	$class_arr = $_POST['class_arr'];

	$flight_no_arr = $_POST['flight_no_arr'];

	$airlin_pnr_arr = $_POST['airlin_pnr_arr'];

	$departure_city_arr = $_POST['departure_city_arr'];

	$arrival_city_arr = $_POST['arrival_city_arr']; 

	$special_note_arr = $_POST['special_note_arr'];

	$trip_entry_id_arr = $_POST['trip_entry_id_arr'];
	$meal_plan_arr = $_POST['meal_plan_arr'];
	$luggage_arr = $_POST['luggage_arr'];



	$due_date=date('Y-m-d',strtotime($due_date));
	$booking_date=date('Y-m-d',strtotime($booking_date));


	

	begin_t();



	//**Old information

	$sq_ticket_info = mysql_fetch_assoc(mysql_query("select * from ticket_master where ticket_id='$ticket_id'"));



	//**Update ticket

	$sq_ticket = mysql_query("update ticket_master set customer_id='$customer_id', tour_type='$tour_type', type_of_tour='$type_of_tour', adults='$adults', childrens='$childrens', infant='$infant', adult_fair='$adult_fair', children_fair='$children_fair', infant_fair='$infant_fair', basic_cost='$basic_cost', basic_cost_markup='$basic_cost_markup', basic_cost_discount='$basic_cost_discount', yq_tax='$yq_tax', yq_tax_markup='$yq_tax_markup', yq_tax_discount='$yq_tax_discount', g1_plus_f2_tax='$g1_plus_f2_tax', service_charge='$service_charge', taxation_type='$taxation_type', taxation_id='$taxation_id', service_tax='$service_tax', service_tax_subtotal='$service_tax_subtotal', tds='$tds', due_date='$due_date', ticket_total_cost='$ticket_total_cost',created_at='$booking_date',supplier_id='$sup_id' where ticket_id='$ticket_id' ");

	if(!$sq_ticket){

		$GLOBALS['flag'] = false;

		echo "error--Sorry, Ticket not updated!";

	}



	//**Update Member

	for($i=0; $i<sizeof($first_name_arr); $i++){



		$birth_date_arr[$i] = get_date_db($birth_date_arr[$i]);



		if($entry_id_arr[$i]==""){

			 

			$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from ticket_master_entries"));

			$entry_id = $sq_max['max'] + 1;



			$sq_entry = mysql_query("insert into ticket_master_entries(entry_id, ticket_id, first_name, middle_name, last_name, birth_date, adolescence, ticket_no, gds_pnr) values('$entry_id', '$ticket_id', '$first_name_arr[$i]','$middle_name_arr[$i]','$last_name_arr[$i]', '$birth_date_arr[$i]', '$adolescence_arr[$i]', '$ticket_no_arr[$i]', '$gds_pnr_arr[$i]')");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Error in member information!";

			}



		}

		else{

			 

			$sq_entry = mysql_query("update ticket_master_entries set first_name='$first_name_arr[$i]', middle_name='$middle_name_arr[$i]', last_name='$last_name_arr[$i]', birth_date='$birth_date_arr[$i]', adolescence='$adolescence_arr[$i]', ticket_no='$ticket_no_arr[$i]', gds_pnr='$gds_pnr_arr[$i]', entry_id='$entry_id_arr[$i]' where entry_id='$entry_id_arr[$i]' ");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Some entries not updated!";

			}



		}

		



	}



	//***Trip information

	for($i=0; $i<sizeof($departure_datetime_arr); $i++){

 

		$departure_datetime_arr[$i] = get_datetime_db($departure_datetime_arr[$i]);

		$arrival_datetime_arr[$i] = get_datetime_db($arrival_datetime_arr[$i]);

		
		$special_note1 = addslashes($special_note_arr[$i]);
		if($trip_entry_id_arr[$i]==""){



			$sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from ticket_trip_entries"));

			$entry_id = $sq_max['max'] + 1;			

			$sq_count = mysql_num_rows(mysql_query("select * from ticket_trip_entries where airlin_pnr='$airlin_pnr_arr[$i]' and airlin_pnr!=''"));
		
			if($sq_count!= '0'){
				$GLOBALS['flag'] = false;
				echo "error--Repeated Airline PNR not allowed!";
			}
			
			
			$sq_entry = mysql_query("insert into ticket_trip_entries(entry_id, ticket_id, departure_datetime, arrival_datetime, airlines_name, class, flight_no, airlin_pnr, departure_city, arrival_city,meal_plan,luggage, special_note, from_city, to_city) values('$entry_id', '$ticket_id', '$departure_datetime_arr[$i]', '$arrival_datetime_arr[$i]', '$airlines_name_arr[$i]', '$class_arr[$i]', '$flight_no_arr[$i]', '$airlin_pnr_arr[$i]', '$departure_city_arr[$i]', '$arrival_city_arr[$i]', '$meal_plan_arr[$i]', '$luggage_arr[$i]', '$special_note1','$from_city_id_arr[$i]','$to_city_id_arr[$i]')");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Error in trip information save!";

			}



		}

		else{

			$q1 = "select * from ticket_trip_entries where airlin_pnr='$airlin_pnr_arr[$i]' and airlin_pnr!='' and entry_id!='$trip_entry_id_arr[$i]'";			
			$sq_count = mysql_num_rows(mysql_query($q1));
		
			if($sq_count!= '0'){
				$GLOBALS['flag'] = false;
				echo "error--Repeated Airline PNR not allowed....!";
			}
			
			
			$sq_entry = mysql_query("update ticket_trip_entries set departure_datetime='$departure_datetime_arr[$i]', arrival_datetime='$arrival_datetime_arr[$i]', airlines_name='$airlines_name_arr[$i]', class='$class_arr[$i]', flight_no='$flight_no_arr[$i]', airlin_pnr='$airlin_pnr_arr[$i]', departure_city='$departure_city_arr[$i]', arrival_city='$arrival_city_arr[$i]',meal_plan= '$meal_plan_arr[$i]',luggage='$luggage_arr[$i]', special_note='$special_note1', from_city='$from_city_id_arr[$i]', to_city='$to_city_id_arr[$i]' where entry_id='$trip_entry_id_arr[$i]'");

			if(!$sq_entry){

				$GLOBALS['flag'] = false;

				echo "error--Error in trip information update!";

			}



		}

		



	}



	//Finance update

	$this->finance_update($sq_ticket_info, $row_spec);



	if($GLOBALS['flag']){

		commit_t();

		echo "Flight Ticket Booking has been successfully updated.";

		exit;	

	}

	else{

		rollback_t();

		exit;

	}		

}

public function finance_update($sq_ticket_info, $row_spec){
	$ticket_id = $_POST['ticket_id'];
	$customer_id = $_POST['customer_id'];
	$tour_type = $_POST['tour_type'];
	$basic_cost = $_POST['basic_cost'];
	$basic_cost_markup = $_POST['basic_cost_markup'];
	$basic_cost_discount = $_POST['basic_cost_discount'];
	$yq_tax = $_POST['yq_tax'];
	$yq_tax_markup = $_POST['yq_tax_markup'];
	$yq_tax_discount = $_POST['yq_tax_discount'];
	$g1_plus_f2_tax = $_POST['g1_plus_f2_tax'];
	$service_charge = $_POST['service_charge'];
	$taxation_type = $_POST['taxation_type'];
	$taxation_id = $_POST['taxation_id'];
	$service_tax = $_POST['service_tax'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
	$tds = $_POST['tds'];
	$due_date = $_POST['due_date'];
	$ticket_total_cost = $_POST['ticket_total_cost'];
	$booking_date = $_POST['booking_date1'];
	
	$booking_date = date('Y-m-d', strtotime($booking_date));
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];
		
	$total_sale = $basic_cost + $basic_cost_markup + $yq_tax + $yq_tax_markup + $g1_plus_f2_tax  + $service_charge - $yq_tax_discount;
	//get total payment against ticket id
    $sq_ticket = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from ticket_payment_master where ticket_id='$ticket_id'"));
	$balance_amount = $ticket_total_cost - $sq_ticket['payment_amount'];

    //Getting customer Ledger
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
	$cust_gl = $sq_cust['ledger_id'];

	global $transaction_master;
	$sale_gl = ($tour_type == 'Domestic') ? 50 : 174;

    ////////////Sales/////////////

    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $total_sale;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $total_sale, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $old_gl_id = $gl_id = $sale_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Air Ticket Booking',$service_tax_subtotal,$taxation_type,$ticket_id,get_ticket_booking_id($ticket_id,$yr1),$booking_date, $customer_id, $row_spec);

    /////////TDS////////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $tds;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $tds, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $old_gl_id = $gl_id = 127;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);


    /////////Discount////////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $basic_cost_discount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $basic_cost_discount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $old_gl_id = $gl_id = 36;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);


    ////////Balance Amount//////
    $module_name = "Air Ticket Booking";
    $module_entry_id = $ticket_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_ticket_booking_id($ticket_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Flight Ticket Sales');
    $old_gl_id = $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

}
}

?>