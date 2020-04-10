<?php 
$flag = true;
class sales_payment_master{

public function sales_payment_save()
{
	$row_spec='sales';
	$customer_id = $_POST['customer_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$advance_nullify = $_POST['advance_nullify'];
	$total_payment_amount = $_POST['total_payment_amount'];
	$total_purchase = $_POST['total_purchase'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];
	$branch_admin_id = $_POST['branch_admin_id'];
    $emp_id = $_POST['emp_id'];
	$bank_id = $_POST['bank_id'];

	$payment_amount_arr = $_POST['payment_amount_arr'];
	$purchase_type_arr = $_POST['purchase_type_arr'];
	$purchase_id_arr = $_POST['purchase_id_arr'];

	$payment_date = date('Y-m-d', strtotime($payment_date));
	$year1 = explode("-", $payment_date);
	$yr1 =$year1[0];

	$created_at = date('Y-m-d H:i:s');
	
	if($payment_mode=="Cheque"){ 
      $clearance_status = "Pending"; } 
    else {  $clearance_status = ""; } 

	$financial_year_id = $_SESSION['financial_year_id'];

	$bank_balance_status = bank_cash_balance_check($payment_mode, $bank_id, $payment_amount);
  	if(!$bank_balance_status){ echo bank_cash_balance_error_msg($payment_mode, $bank_id); exit; }  

  	$invoice_no='';
  	$invoice_name='';
  	$module_entry_id='';
	begin_t();

	for($i=0;$i<sizeof($purchase_type_arr);$i++){

		if($purchase_type_arr[$i]=='Group Booking')
		{
			//Package Outstanding	
			$query = "select * from tourwise_traveler_details where 1 and id='$purchase_id_arr[$i]'";
			$booking_amt =0;
			$pending_amt=0;
			$sq_booking = mysql_query($query);
			while($row_booking = mysql_fetch_assoc($sq_booking)){
				$pay_amount = 0;
				//Sale
				$travel_amt = $row_booking['total_travel_expense'];
				$tour_amt = $row_booking['total_tour_fee'];

				//Cancel traveler
				$cancel_est_tr=mysql_fetch_assoc(mysql_query("select * from refund_traveler_estimate where tourwise_traveler_id='$row_booking[id]'"));
				$can_travel_tr = $cancel_est_tr['cancel_amount'];
				$can_tour_tr = $cancel_est_tr['total_tour_amount'];
				
				//Cancel tour
				$cancel_est_tour=mysql_fetch_assoc(mysql_query("select * from refund_tour_estimate where tourwise_traveler_id='$row_booking[id]'"));
				$can_travel_tour = $cancel_est_tour['cancel_amount'] ;
				$can_tour_tour = $cancel_est_tour['total_tour_amount'];

				//Paid
				$sq_tr_pay=mysql_fetch_assoc(mysql_query("select sum(amount) as travel_sum from payment_master where tourwise_traveler_id='$row_booking[id]' and payment_for = 'Travelling' and clearance_status!='Pending' AND clearance_status!='Cancelled'"));
				$total_tr_pay = $sq_tr_pay['travel_sum'];
				$sq_tour_pay=mysql_fetch_assoc(mysql_query("select sum(amount) as tour_sum from payment_master where tourwise_traveler_id='$row_booking[id]' and payment_for = 'Tour' and clearance_status!='Pending' AND clearance_status!='Cancelled'"));
				$total_tour_pay = $sq_tour_pay['tour_sum'];
				$pay_amount = 0;
				//Balance
				$pending_amt_travel = ($travel_amt)-($can_travel_tr)-($can_tour_tr)-($total_tr_pay);
				$pending_amt_tour = ($tour_amt-$can_travel_tour-$can_tour_tour-$total_tour_pay);
				
				//Clear Travel payment first
				if($pending_amt_travel <= $payment_amount_arr[$i])
				{
					 $pay_amount = $payment_amount_arr[$i] - $pending_amt_travel;
				     $sq = mysql_query("select max(payment_id) as max from payment_master");
				     $value = mysql_fetch_assoc($sq);
				     $max_payment_id = $value['max'] + 1;
				  
				     $sq_payment= mysql_query("insert into payment_master (payment_id, financial_year_id, branch_admin_id, emp_id, tourwise_traveler_id, date, payment_mode, amount, bank_name, transaction_id, payment_for, travel_type, bank_id, clearance_status ) values ('$max_payment_id', '$financial_year_id', '$branch_admin_id', '$emp_id', '$purchase_id_arr[$i]', '$payment_date', '$payment_mode', '$pending_amt_travel', '$bank_name', '$transaction_id', 'Travelling', 'All', '$bank_id', '$clearance_status') ");
				     $invoice_no .= get_group_booking_id($purchase_id_arr[$i],$yr1).',';
				     $invoice_name .= ' Group Booking,';
				     $module_entry_id .= $max_payment_id.',';
				     $invoice_no1 = get_group_booking_id($purchase_id_arr[$i],$yr1);
					//Bank and Cash Book Save
					$this->bank_cash_book_save($max_payment_id,'Group Booking',$purchase_id_arr[$i],$pending_amt_travel,$invoice_no1);
				}
				if($pay_amount <= $payment_amount_arr[$i] && $pay_amount!='0'){
				     $sq = mysql_query("select max(payment_id) as max from payment_master");
				     $value = mysql_fetch_assoc($sq);
				     $max_payment_id = $value['max'] + 1;
				  
				     $sq_payment= mysql_query(" insert into payment_master (payment_id, financial_year_id, branch_admin_id, emp_id, tourwise_traveler_id, date, payment_mode, amount, bank_name, transaction_id, payment_for, travel_type, bank_id, clearance_status ) values ('$max_payment_id', '$financial_year_id', '$branch_admin_id', '$emp_id', '$purchase_id_arr[$i]', '$payment_date', '$payment_mode', '$pay_amount', '$bank_name', '$transaction_id', 'Tour', '', '$bank_id', '$clearance_status') ");
				     $invoice_no .= get_group_booking_id($purchase_id_arr[$i],$yr1).',';
				     $invoice_name .= ' Group Booking,';
				     $module_entry_id .= $max_payment_id.',';
				     $invoice_no1 = get_group_booking_id($purchase_id_arr[$i],$yr1);
					//Bank and Cash Book Save
					$this->bank_cash_book_save($max_payment_id,'Group Booking',$purchase_id_arr[$i],$pay_amount,$invoice_no1);
				}
			}
			
		}
		if($purchase_type_arr[$i]=='Package Booking')
		{
			//Package Outstanding	
			$query = "select * from package_tour_booking_master where 1 and booking_id='$purchase_id_arr[$i]'";
			$booking_amt =0;
			$pending_amt=0;
			$sq_booking = mysql_query($query);
			while($row_booking = mysql_fetch_assoc($sq_booking)){
				$pay_amount = 0;
				//Sale
				$travel_amt = $row_booking['total_travel_expense'];
				$tour_amt = $row_booking['actual_tour_expense'];

				//Cancel
				$cancel_est=mysql_fetch_assoc(mysql_query("select * from package_refund_traveler_estimate where booking_id='$row_booking[booking_id]'"));
				$can_travel = $cancel_est['cancel_amount'];
				$can_tour = $cancel_est['total_tour_amount'];

				//Paid
				$sq_tr_pay=mysql_fetch_assoc(mysql_query("select sum(amount) as travel_sum from package_payment_master where booking_id='$row_booking[booking_id]' and payment_for = 'Travelling' and clearance_status!='Pending' AND clearance_status!='Cancelled'"));
				$total_tr_pay = $sq_tr_pay['travel_sum'];
				$sq_tour_pay=mysql_fetch_assoc(mysql_query("select sum(amount) as tour_sum from package_payment_master where booking_id='$row_booking[booking_id]' and payment_for = 'Tour' and clearance_status!='Pending' AND clearance_status!='Cancelled'"));
				$total_tour_pay = $sq_tour_pay['tour_sum'];
				$pay_amount = 0;
				//Balance
				$pending_amt_travel = ($travel_amt)-($can_travel)-($total_tr_pay);
				$pending_amt_tour = ($tour_amt-$can_tour-$total_tour_pay);
				
				//Clear Travel payment first
				if($pending_amt_travel <= $payment_amount_arr[$i])
				{
					$pay_amount = $payment_amount_arr[$i] - $pending_amt_travel;
					$sq = mysql_query("SELECT max(payment_id) as max FROM package_payment_master");
			        $value = mysql_fetch_assoc($sq);
			        $max_payment_id = $value['max'] + 1;
					$sq_payment= mysql_query(" insert into package_payment_master (payment_id, booking_id, financial_year_id, branch_admin_id, emp_id, date, payment_mode, amount, bank_name, transaction_id, payment_for, travel_type, bank_id, clearance_status ) values ('$max_payment_id', '$purchase_id_arr[$i]', '$financial_year_id', '$branch_admin_id', '$emp_id', '$payment_date', '$payment_mode', '$pending_amt_travel', '$bank_name', '$transaction_id', 'Travelling', 'All', '$bank_id', '$clearance_status') ");
					$invoice_no .= get_package_booking_id($purchase_id_arr[$i],$yr1).',';
				    $invoice_name .= ' Package Booking(Travel),';
				    $module_entry_id .= $max_payment_id.',';
				    $invoice_no1 = get_package_booking_id($purchase_id_arr[$i],$yr1);
					//Bank and Cash Book Save
					$this->bank_cash_book_save($max_payment_id,'Package Booking',$purchase_id_arr[$i],$pending_amt_travel,$invoice_no1);
				}
				if($pay_amount <= $payment_amount_arr[$i] && $pay_amount!='0'){

					$sq = mysql_query("SELECT max(payment_id) as max FROM package_payment_master");
			        $value = mysql_fetch_assoc($sq);
			        $max_payment_id = $value['max'] + 1;
					$sq_payment= mysql_query(" insert into package_payment_master (payment_id, booking_id, financial_year_id, branch_admin_id, emp_id, date, payment_mode, amount, bank_name, transaction_id, payment_for, travel_type, bank_id, clearance_status ) values ('$max_payment_id', '$purchase_id_arr[$i]', '$financial_year_id', '$branch_admin_id', '$emp_id', '$payment_date', '$payment_mode', '$pay_amount', '$bank_name', '$transaction_id', 'Tour', '', '$bank_id', '$clearance_status') ");
					$invoice_no .= get_package_booking_id($purchase_id_arr[$i],$yr1).',';
				    $invoice_name .= ' Package Booking(Tour),';
				    $module_entry_id .= $max_payment_id.',';
				    $invoice_no1 = get_package_booking_id($purchase_id_arr[$i],$yr1);
					//Bank and Cash Book Save
					$this->bank_cash_book_save($max_payment_id,'Package Booking',$purchase_id_arr[$i],$pay_amount,$invoice_no1);
				}
			}
	        	
		}
		if($purchase_type_arr[$i]=='Visa Booking'){
			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from visa_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into visa_payment_master (payment_id, visa_id, branch_admin_id, financial_year_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$purchase_id_arr[$i]', '$branch_admin_id', '$financial_year_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
			$invoice_no .= get_visa_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_name .= ' Visa Booking,';
		    $module_entry_id .= $payment_id.',';
		    $invoice_no1 = get_visa_booking_id($purchase_id_arr[$i],$yr1);
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Visa Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
		}
		
		if($purchase_type_arr[$i]=='Miscellaneous Booking'){
			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from miscellaneous_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into miscellaneous_payment_master (payment_id, misc_id, branch_admin_id, financial_year_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$purchase_id_arr[$i]', '$branch_admin_id', '$financial_year_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
			$invoice_no .= get_misc_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_name .= ' Miscellaneous Booking,';
		    $module_entry_id .= $payment_id.',';
		    $invoice_no1 = get_misc_booking_id($purchase_id_arr[$i],$yr1);
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Miscellaneous Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
		}
		if($purchase_type_arr[$i]=='Air Ticket Booking'){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from ticket_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into ticket_payment_master (payment_id, branch_admin_id, ticket_id, financial_year_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$branch_admin_id', '$purchase_id_arr[$i]', '$financial_year_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
			$invoice_no .= get_ticket_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_name .= ' Air Ticket Booking,';
		    $module_entry_id .= $payment_id.',';
		    $invoice_no1 = get_ticket_booking_id($purchase_id_arr[$i],$yr1);
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Air Ticket Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
			
		}
		if($purchase_type_arr[$i]=='Train Booking'){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from train_ticket_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into train_ticket_payment_master (payment_id, train_ticket_id, branch_admin_id, financial_year_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$purchase_id_arr[$i]', '$branch_admin_id', '$financial_year_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
			$invoice_no .= get_train_ticket_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_name .= ' Train Ticket Booking,';
		    $module_entry_id .= $payment_id.',';
		    $invoice_no1 = get_train_ticket_booking_id($purchase_id_arr[$i],$yr1);
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Train Ticket Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
			
		}
		if($purchase_type_arr[$i]=='Hotel Booking'){
			  $sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from hotel_booking_payment"));
			  $payment_id = $sq_max['max']+1;
			  $sq_payment = mysql_query("insert into hotel_booking_payment(payment_id, booking_id, branch_admin_id, financial_year_id, payment_date, payment_mode, payment_amount, bank_name, transaction_id, bank_id, clearance_status, created_at) values ('$payment_id', '$purchase_id_arr[$i]', '$branch_admin_id', '$financial_year_id', '$payment_date', '$payment_mode', '$payment_amount_arr[$i]', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status', '$created_at')");
			$invoice_no .= get_hotel_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_no1 = get_hotel_booking_id($purchase_id_arr[$i],$yr1);
		    $module_entry_id .= $payment_id.',';
		    $invoice_name .= ' Hotel Booking,';
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Hotel Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
			
		}
		if($purchase_type_arr[$i]=='Bus Booking'){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from bus_booking_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into bus_booking_payment_master (payment_id, booking_id, branch_admin_id, financial_year_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$purchase_id_arr[$i]', '$branch_admin_id','$financial_year_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
			$invoice_no .= get_bus_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_no1 = get_bus_booking_id($purchase_id_arr[$i],$yr1);
		    $invoice_name .= ' Bus Booking,';
		    $module_entry_id .= $payment_id.',';
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Bus Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
		}
		if($purchase_type_arr[$i]=='Car Rental Booking'){

			  $sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from car_rental_payment"));
			  $payment_id = $sq_max['max']+1;
			  
			  $sq_payment = mysql_query("insert into car_rental_payment(payment_id, booking_id, financial_year_id, emp_id, branch_admin_id,  payment_date, payment_mode, payment_amount, bank_name, transaction_id, bank_id, clearance_status, created_at) values ('$payment_id', '$purchase_id_arr[$i]', '$financial_year_id', '$emp_id', '$branch_admin_id', '$payment_date', '$payment_mode', '$payment_amount_arr[$i]', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status', '$created_at')");
			$invoice_no .= get_car_rental_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_name .= 'Car Rental Booking,';
		    $invoice_no1 = get_car_rental_booking_id($purchase_id_arr[$i],$yr1);
		    $module_entry_id .= $payment_id.',';
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Car Rental Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
		}
		if($purchase_type_arr[$i]=='Forex Booking'){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from forex_booking_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into forex_booking_payment_master (payment_id, booking_id, branch_admin_id, financial_year_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$purchase_id_arr[$i]', '$branch_admin_id', '$financial_year_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
			$invoice_no .= get_forex_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_name .= ' Forex Booking,';
		    $invoice_no1 = get_forex_booking_id($purchase_id_arr[$i],$yr1);
		    $module_entry_id .= $payment_id.',';
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Forex Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
			
		}
		if($purchase_type_arr[$i]=='Passport Booking'){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from passport_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into passport_payment_master (payment_id, passport_id, financial_year_id, branch_admin_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$purchase_id_arr[$i]', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
			$invoice_no .= get_passport_booking_id($purchase_id_arr[$i],$yr1).',';
		    $invoice_name .= ' Passport Booking,';
		    $invoice_no1 = get_passport_booking_id($purchase_id_arr[$i],$yr1);
		    $module_entry_id .= $payment_id.',';
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Passport Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
			
		}
		if($purchase_type_arr[$i]=='Excursion Booking'){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from exc_payment_master"));
			$payment_id = $sq_max['max'] + 1;

			$sq_payment = mysql_query("insert into exc_payment_master (payment_id, exc_id, financial_year_id, branch_admin_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$purchase_id_arr[$i]', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_amount_arr[$i]', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");		
			$invoice_no .= get_exc_booking_id($purchase_id_arr[$i],$yr1).',';	
		    $invoice_name .= ' Excursion Booking,';
		    $invoice_no1 = get_exc_booking_id($purchase_id_arr[$i],$yr1);
		    $module_entry_id .= $payment_id.',';
			//Bank and Cash Book Save
			$this->bank_cash_book_save($payment_id,'Excursion Booking',$purchase_id_arr[$i],$payment_amount_arr[$i],$invoice_no1);
		}
	}
	if(!$sq_payment){
		rollback_t();
		echo "error--Sorry, Receipt not saved!";
		exit;
	}
	else{
		//Finance Save
		$this->finance_save($payment_id,$invoice_no,$invoice_name,$module_entry_id,$row_spec);



		if($GLOBALS['flag']){
			commit_t();
	    	echo "Sales has been successfully saved.";
			exit;	
		}
		
	}
}

public function finance_save($payment_id,$invoice_no,$invoice_name,$module_entry_id,$row_spec)
{
	$customer_id = $_POST['customer_id'];
	$advance_nullify = $_POST['advance_nullify'];
	$total_payment_amount = $_POST['total_payment_amount'];
	$total_purchase = $_POST['total_purchase'];
	$payment_date = $_POST['payment_date'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];

	$payment_amount_arr = $_POST['payment_amount_arr'];
	$purchase_type_arr = $_POST['purchase_type_arr'];
	$purchase_id_arr = $_POST['purchase_id_arr'];

	$payment_date = date('Y-m-d', strtotime($payment_date));

	global $transaction_master;

    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
	    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
	    $pay_gl = $sq_bank['ledger_id'];
     } 

    //Getting customer Ledger
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer' and group_sub_id='20'"));
	$cust_gl = $sq_cust['ledger_id'];

	//customer Advance Ledger
	$sq_supp = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer' and group_sub_id='22'"));
	$cust_adv_gl = $sq_supp['ledger_id'];

	$sq_payment = mysql_fetch_assoc(mysql_query("select max(finance_transaction_id) as payment_id from finance_transaction_master where module_name='Sale Receipt'"));
	$payment_id = $sq_payment['payment_id'];

	if($total_payment_amount > $total_purchase)
	{
		$balance_amount = $total_payment_amount - $total_purchase;
		////////Customer Amount//////   
	    $module_name = $invoice_name;
	    $module_entry_id = $module_entry_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $total_purchase;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $cust_gl;
	    $payment_side = "Credit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

	    //////Payment Amount///////
	    $module_name = $invoice_name;
	    $module_entry_id = $module_entry_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $payment_amount1;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Debit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

	    //////Advance Nullify Amount///////
	    if($cust_adv_gl !=''){
		    $module_name = $invoice_name;
		    $module_entry_id = $module_entry_id;
		    $transaction_id = $transaction_id1;
		    $payment_amount = $balance_amount;
		    $payment_date = $payment_date;
		    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
				$ledger_particular = get_ledger_particular('By','Cash/Bank');
		    $gl_id = $cust_adv_gl;
		    $payment_side = "Credit";
		    $clearance_status = ($payment_mode!="Cash") ? "Pending" : ""; 
		    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
		}
		if($cust_adv_gl == ''){
			$module_name = $invoice_name;
		    $module_entry_id = $module_entry_id;
		    $transaction_id = $transaction_id1;
		    $payment_amount = $balance_amount;
		    $payment_date = $payment_date;
		    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
				$ledger_particular = get_ledger_particular('By','Cash/Bank');
		    $gl_id = $cust_gl;
		    $payment_side = "Credit";
		    $clearance_status = ($payment_mode!="Cash") ? "Pending" : ""; 
		    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
		}
	}
	else if($total_payment_amount < $total_purchase){
		////////Customer Amount//////   
	    $module_name = $invoice_name;
	    $module_entry_id = $module_entry_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $total_payment_amount;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $cust_gl;
	    $payment_side = "Credit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

		//////Advance Nullify Amount///////

	    if($advance_nullify !='0'){
		    $module_name = $invoice_name;
		    $module_entry_id = $module_entry_id;
		    $transaction_id = $transaction_id1;
		    $payment_amount = $advance_nullify;
		    $payment_date = $payment_date;
		    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
				$ledger_particular = get_ledger_particular('By','Cash/Bank');
		    $gl_id = $cust_adv_gl;
		    $payment_side = "Debit";
		    $clearance_status = ($payment_mode!="Cash") ? "Pending" : ""; 
		    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
		}

	    //////Payment Amount///////
	    $module_name = $invoice_name;
	    $module_entry_id = $module_entry_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $payment_amount1;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Debit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
	}
	else
	{
		////////Customer Amount//////   
	    $module_name = $invoice_name;
	    $module_entry_id = $module_entry_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $total_payment_amount;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $cust_gl;
	    $payment_side = "Credit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

		//////Advance Nullify Amount///////
	    $module_name = $invoice_name;
	    $module_entry_id = $module_entry_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $advance_nullify;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $cust_adv_gl;
	    $payment_side = "Debit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : ""; 
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

	    //////Payment Amount///////
	    $module_name = $invoice_name;
	    $module_entry_id = $module_entry_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $payment_amount1;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular($invoice_name, $payment_date, $total_purchase, $customer_id, $payment_mode, $invoice_no);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Debit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
}
}

public function bank_cash_book_save($payment_id,$invoice_name,$module_entry_id1,$pay_amount,$invoice_no1)
{
	$customer_id = $_POST['customer_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];
	$total_purchase = $_POST['total_purchase'];
	$bank_id = $_POST['bank_id'];
	$payment_evidence_url = $_POST['payment_evidence_url'];

	$payment_date = date('Y-m-d', strtotime($payment_date));

	global $bank_cash_book_master;
	
	$module_name = $invoice_name;
	$module_entry_id = $payment_id;
	$payment_date = $payment_date;
	$payment_amount = $pay_amount;
	$payment_mode = $payment_mode;
	$bank_name = $bank_name;
	$transaction_id = $transaction_id;
	$bank_id = $bank_id;
	$particular = get_sales_paid_particular($invoice_name, $payment_date, $pay_amount, $customer_id, $payment_mode, $invoice_no1);
	$clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
	$payment_side = "Debit";
	$payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

	$bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);
	
}
}
?>