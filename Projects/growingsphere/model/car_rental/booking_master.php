<?php 

$flag = true;

class booking_master{
public function booking_save()
{
  $row_spec = 'sales';
  $customer_id = $_POST['customer_id'];
  $emp_id = $_POST['emp_id'];
  $enquiry_id = $_POST['enquiry_id'];
  $branch_admin_id = $_POST['branch_admin_id'];
  $total_pax = $_POST['total_pax'];
  $pass_name = $_POST['pass_name'];

  $days_of_traveling = $_POST['days_of_traveling'];

  $traveling_date = $_POST['traveling_date'];

  $enquiry_date = $_POST['enquiry_date'];

  $vehicle_type = $_POST['vehicle_type'];

  $travel_type = $_POST['travel_type'];

  $places_to_visit = $_POST['places_to_visit'];

  $vendor_id = $_POST['vendor_id'];

  $vehicle_id_arr = $_POST['vehicle_id_arr'];



  $daily_min_average = $_POST['daily_min_average'];

  $rate_per_km = $_POST['rate_per_km'];

  $extra_km = $_POST['extra_km'];

  $km_total_fee = $_POST['km_total_fee'];

  $actual_cost = $_POST['actual_cost'];

  $taxation_type = $_POST['taxation_type'];

  $taxation_id = $_POST['taxation_id'];

  $service_tax = $_POST['service_tax'];

  $service_tax_subtotal = $_POST['service_tax_subtotal'];

  $total_cost = $_POST['total_cost'];

  $driver_allowance = $_POST['driver_allowance'];

  $permit_charges = $_POST['permit_charges'];

  $toll_and_parking = $_POST['toll_and_parking'];

  $state_entry_tax = $_POST['state_entry_tax'];

  $total_fees = $_POST['total_fees'];

  $due_date = $_POST['due_date'];
  $booking_date = $_POST['booking_date'];



  $payment_amount = $_POST['payment_amount'];

  $payment_date = $_POST['payment_date'];

  $payment_mode = $_POST['payment_mode'];

  $bank_name = $_POST['bank_name'];

  $transaction_id = $_POST['transaction_id'];

  $bank_id = $_POST['bank_id'];





  $traveling_date = date('Y-m-d H:i:s', strtotime($traveling_date));

  $enquiry_date = date('Y-m-d', strtotime($enquiry_date));

  $payment_date = date('Y-m-d', strtotime($payment_date));

  $due_date = date('Y-m-d', strtotime($due_date));
  $booking_date = date('Y-m-d', strtotime($booking_date));

  if($payment_mode=="Cheque"){ 
    $clearance_status = "Pending"; } 
  else {  $clearance_status = ""; } 



  $financial_year_id = $_SESSION['financial_year_id'];



  begin_t();

  //Get Customer id
  if($customer_id == '0'){
    $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
    $customer_id = $sq_max['max'];
  }

  //Car Rental booking
  $sq_max = mysql_fetch_assoc(mysql_query("select max(booking_id) as max from car_rental_booking"));

  $booking_id = $sq_max['max']+1;

  $places_to_visit = addslashes($places_to_visit);
  
  $sq_enq = mysql_query("insert into car_rental_booking( booking_id, customer_id,enquiry_id, branch_admin_id,financial_year_id,pass_name, total_pax, days_of_traveling, traveling_date, enquiry_date, vehicle_type, travel_type, places_to_visit, vendor_id, daily_min_average, rate_per_km, extra_km, km_total_fee, actual_cost, taxation_type, taxation_id, service_tax, service_tax_subtotal, total_cost, driver_allowance, permit_charges, toll_and_parking, state_entry_tax, total_fees, created_at, due_date, emp_id) values ( '$booking_id', '$customer_id','$enquiry_id', '$branch_admin_id','$financial_year_id','$pass_name', '$total_pax', '$days_of_traveling','$traveling_date', '$enquiry_date', '$vehicle_type', '$travel_type', '$places_to_visit', '$vendor_id', '$daily_min_average', '$rate_per_km', '$extra_km', '$km_total_fee', '$actual_cost', '$taxation_type', '$taxation_id', '$service_tax', '$service_tax_subtotal', '$total_cost', '$driver_allowance', '$permit_charges', '$toll_and_parking', '$state_entry_tax', '$total_fees', '$booking_date', '$due_date','$emp_id' )");

  if($sq_enq){



    //Adding Vehicles

    for($i=0; $i<sizeof($vehicle_id_arr); $i++){



      $sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from car_rental_booking_vehicle_entries"));

      $entry_id = $sq_max['max']+1;



      $sq_entry = mysql_query("insert into car_rental_booking_vehicle_entries(entry_id, booking_id, vehicle_id) values ('$entry_id', '$booking_id', '$vehicle_id_arr[$i]')");

      if(!$sq_entry){

        $GLOBALS['flag'] = false;

        echo "error--Sorry, Some vehicles not saved!";

        //exit;

      }



    }



  	//Advance payment

  	$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from car_rental_payment"));

  	$payment_id = $sq_max['max']+1;



  	$sq_payment = mysql_query("insert into car_rental_payment(payment_id, booking_id, financial_year_id, branch_admin_id, emp_id, payment_date, payment_mode, payment_amount, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$booking_id', '$financial_year_id', '$branch_admin_id', '$emp_id',  '$payment_date', '$payment_mode', '$payment_amount', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status')");

  	if(!$sq_payment){

  		$GLOBALS['flag'] = false;

      echo "error--Sorry, Advance payment not done!";

  		//exit;

  	}

    //Update customer credit note balance
    $payment_amount1 = $payment_amount;
    $sq_credit_note = mysql_query("select * from credit_note_master where customer_id='$customer_id'");
    $i=0;
    while($row_credit = mysql_fetch_assoc($sq_credit_note)) 
    { 
      if($row_credit['payment_amount'] <= $payment_amount1 && $payment_amount1 != '0'){   
        $payment_amount1 = $payment_amount1 - $row_credit['payment_amount'];
        $temp_amount = 0;
      }
      else{
        $temp_amount = $row_credit['payment_amount'] - $payment_amount1;
        $payment_amount1 = 0;
      }
      $sq_credit = mysql_query("update credit_note_master set payment_amount ='$temp_amount' where id='$row_credit[id]'");
      
    }



    //Finance save

    $this->finance_save($booking_id, $payment_id, $row_spec, $branch_admin_id);



    //Bank and Cash Book Save

    $this->bank_cash_book_save($booking_id, $payment_id, $branch_admin_id);



    if($GLOBALS['flag']){



      commit_t();

      //Car rental booking mail send

      $this->car_rental_booking_email_send($booking_id);
      $this->booking_sms($booking_id, $customer_id, $booking_date);


      //Payment mail send

      $payment_master = new payment_master;

      $payment_master->payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date);



      //Payment sms send
      if($payment_amount != 0){
        $payment_master->payment_sms_notification_send($booking_id, $payment_amount, $payment_mode);
      }



      echo "Car Rental Booking has been successfully saved.";

      exit;

      

    }

    else{

      rollback_t();

      exit;

    }



    

  }

  else{

    rollback_t();

  	echo "error--Booking not saved!";

  	exit;

  }



}


public function booking_sms($booking_id, $customer_id, $created_at){

  global $model, $app_name;
  $sq_customer_info = mysql_fetch_assoc(mysql_query("select contact_no from customer_master where customer_id='$customer_id'"));
  $mobile_no = $sq_customer_info['contact_no'];
  
  $date = $created_at;
  $yr = explode("-", $date);
  $yr1 =$yr[0];

  $message = 'Thank you for booking with '.$app_name.'. Booking No : '.get_car_rental_booking_id($booking_id,$yr1).'  Date :'.get_date_user($created_at);
  $model->send_message($mobile_no, $message);  
}

public function finance_save($booking_id, $payment_id, $row_spec, $branch_admin_id)
{
  $customer_id = $_POST['customer_id'];  
  $km_total_fee = $_POST['km_total_fee'];
  $actual_cost = $_POST['actual_cost'];
  $driver_allowance = $_POST['driver_allowance'];
  $permit_charges = $_POST['permit_charges'];
  $toll_and_parking = $_POST['toll_and_parking'];
  $state_entry_tax = $_POST['state_entry_tax'];
  $taxation_type = $_POST['taxation_type'];
  $taxation_id = $_POST['taxation_id'];
  $service_tax = $_POST['service_tax'];
  $service_tax_subtotal = $_POST['service_tax_subtotal'];
  $total_fees = $_POST['total_fees'];
  $booking_date = $_POST['booking_date'];
  $payment_amount1 = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id1 = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];

  $booking_date = date('Y-m-d', strtotime($booking_date));
  $payment_date1 = date('Y-m-d', strtotime($payment_date));
	$year1 = explode("-", $booking_date);
	$yr1 =$year1[0];
	$year2 = explode("-", $payment_date1);
	$yr2 =$year2[0];

  $car_sale_amount = $km_total_fee + $driver_allowance + $permit_charges + $toll_and_parking + $state_entry_tax;
  $balance_amount = $total_fees - $payment_amount1;

  //Get Customer id
  if($customer_id == '0'){
    $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
    $customer_id = $sq_max['max'];
  }
  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
      $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
      $pay_gl = $sq_bank['ledger_id'];
     } 

    global $transaction_master;

    ////////////Sales/////////////

    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $car_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $booking_date, $car_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Car Rental Sales');
    $gl_id = 18;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Car Rental Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_car_rental_booking_id($booking_id,$yr1),$booking_date, $customer_id,$row_spec,$branch_admin_id);

    //////Payment Amount///////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_particular(get_car_rental_booking_id($booking_id,$yr2), $payment_date1, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Balance Amount//////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Car Rental Sales');
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

}



public function bank_cash_book_save($booking_id, $payment_id, $branch_admin_id)

{

    global $bank_cash_book_master;



    $customer_id = $_POST['customer_id'];  

    $payment_amount = $_POST['payment_amount'];

    $payment_date = $_POST['payment_date'];

    $payment_mode = $_POST['payment_mode'];

    $bank_name = $_POST['bank_name'];

    $transaction_id = $_POST['transaction_id'];

    $bank_id = $_POST['bank_id'];
    $payment_date = date('Y-m-d', strtotime($payment_date));
    $year1 = explode("-", $payment_date);
    $yr1 =$year1[0];
    
    //Get Customer id
    if($customer_id == '0'){
      $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
      $customer_id = $sq_max['max'];
    }

    $module_name = "Car Rental Booking";

    $module_entry_id =$payment_id;

    $payment_date = $payment_date;

    $payment_amount = $payment_amount;

    $payment_mode = $payment_mode;

    $bank_name = $bank_name;

    $transaction_id = $transaction_id;

    $bank_id = $bank_id;

    $particular = get_sales_paid_particular(get_car_rental_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $customer_id, $payment_mode, get_car_rental_booking_id($booking_id,$yr1));

    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";

    $payment_side = "Debit";

    $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

    $bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);



}







public function booking_update()

{

  $row_spec = 'sales';

  $booking_id = $_POST['booking_id'];

  $customer_id = $_POST['customer_id'];

  $total_pax = $_POST['total_pax'];
  $pass_name = $_POST['pass_name'];

  $days_of_traveling = $_POST['days_of_traveling'];

  $traveling_date = $_POST['traveling_date'];

  $enquiry_date = $_POST['enquiry_date'];

  $vehicle_type = $_POST['vehicle_type'];

  $travel_type = $_POST['travel_type'];

  $places_to_visit = $_POST['places_to_visit'];

  $vendor_id = $_POST['vendor_id'];

  $vehicle_id_arr = $_POST['vehicle_id_arr'];



  $daily_min_average = $_POST['daily_min_average'];

  $rate_per_km = $_POST['rate_per_km'];

  $extra_km = $_POST['extra_km'];

  $km_total_fee = $_POST['km_total_fee'];

  $actual_cost = $_POST['actual_cost'];

  $taxation_type = $_POST['taxation_type'];

  $taxation_id = $_POST['taxation_id'];

  $service_tax = $_POST['service_tax'];

  $service_tax_subtotal = $_POST['service_tax_subtotal'];

  $total_cost = $_POST['total_cost'];

  $driver_allowance = $_POST['driver_allowance'];

  $permit_charges = $_POST['permit_charges'];

  $toll_and_parking = $_POST['toll_and_parking'];

  $state_entry_tax = $_POST['state_entry_tax'];

  $total_fees = $_POST['total_fees'];

  $due_date1 = $_POST['due_date1'];
  $booking_date1 = $_POST['booking_date1'];




  $traveling_date = date('Y-m-d H:i:s', strtotime($traveling_date));

  $enquiry_date = date('Y-m-d', strtotime($enquiry_date));
  $booking_date1 = date('Y-m-d', strtotime($booking_date1));

  $due_date1 = date('Y-m-d',strtotime($due_date1));



  $sq_booking_info = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));





  begin_t();


  $places_to_visit = addslashes($places_to_visit);
  $sq_enq = mysql_query("update car_rental_booking set customer_id='$customer_id', total_pax='$total_pax',pass_name='$pass_name', days_of_traveling='$days_of_traveling', traveling_date='$traveling_date', enquiry_date='$enquiry_date', vehicle_type='$vehicle_type', travel_type='$travel_type', places_to_visit='$places_to_visit', vendor_id='$vendor_id', daily_min_average='$daily_min_average', rate_per_km='$rate_per_km', extra_km='$extra_km', km_total_fee='$km_total_fee', actual_cost='$actual_cost', taxation_type='$taxation_type', taxation_id='$taxation_id', service_tax='$service_tax', service_tax_subtotal='$service_tax_subtotal', total_cost='$total_cost', driver_allowance='$driver_allowance', permit_charges='$permit_charges', toll_and_parking='$toll_and_parking', state_entry_tax='$state_entry_tax', total_fees='$total_fees', due_date='$due_date1',created_at='$booking_date1' where booking_id='$booking_id'");

  if($sq_enq){



    //Deleting Vehicles

    $sq_vehicle_del = mysql_query("delete from car_rental_booking_vehicle_entries where booking_id='$booking_id'");

    //Adding Vehicles

    for($i=0; $i<sizeof($vehicle_id_arr); $i++){



      $sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from car_rental_booking_vehicle_entries"));

      $entry_id = $sq_max['max']+1;



      $sq_entry = mysql_query("insert into car_rental_booking_vehicle_entries(entry_id, booking_id, vehicle_id) values ('$entry_id', '$booking_id', '$vehicle_id_arr[$i]')");

      if(!$sq_entry){

        $GLOBALS['flag'] = false;

        echo "error--Sorry, Some vehicles not saved!";

        //exit;

      }



    }



    //Finance update

    $this->finance_update($sq_booking_info, $row_spec);

  	

    if($GLOBALS['flag']){

      commit_t();

      echo "Car Rental Booking has been successfully updated.";

      exit;  

    }

    else{

      rollback_t();

      exit;

    }

  	

  }

  else{

    rollback_t();

  	echo "error--Booking not updated!";

  	exit;

  }



}

public function finance_update($sq_booking_info, $row_spec)
{
    $booking_id = $_POST['booking_id'];
    $customer_id = $_POST['customer_id'];    
    $km_total_fee = $_POST['km_total_fee'];
    $actual_cost = $_POST['actual_cost'];
    $driver_allowance = $_POST['driver_allowance'];
    $permit_charges = $_POST['permit_charges'];
    $toll_and_parking = $_POST['toll_and_parking'];
    $state_entry_tax = $_POST['state_entry_tax'];
    $taxation_type = $_POST['taxation_type'];
    $taxation_id = $_POST['taxation_id'];
    $service_tax = $_POST['service_tax'];
    $service_tax_subtotal = $_POST['service_tax_subtotal'];
    $total_fees = $_POST['total_fees'];
    $booking_date1 = $_POST['booking_date1'];

    $booking_date = date('Y-m-d', strtotime($booking_date1));
    $year1 = explode("-", $booking_date);
    $yr1 =$year1[0];

    global $transaction_master;
    $car_sale_amount = $km_total_fee + $driver_allowance + $permit_charges + $toll_and_parking + $state_entry_tax;
    //get total payment against booking id
    $sq_booking = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from car_rental_payment where booking_id='$booking_id'"));
    $balance_amount = $total_fees - $sq_booking['payment_amount'];

    //Getting customer Ledger
    $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
    $cust_gl = $sq_cust['ledger_id'];


    global $transaction_master;
    
    ////////////Sales/////////////

    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $car_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $booking_date, $car_sale_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Car Rental Sales');
    $old_gl_id = $gl_id = 18;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    /////////Tax Amount/////////
    tax_reflection_update('Car Rental Booking',$service_tax_subtotal,$taxation_type,$booking_id,get_car_rental_booking_id($booking_id,$yr1),$booking_date, $customer_id,$row_spec);

    ////////Balance Amount//////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $balance_amount;
    $payment_date = $booking_date;
    $payment_particular = get_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $booking_date, $balance_amount, $customer_id);
    $ledger_particular = get_ledger_particular('To','Car Rental Sales');
    $old_gl_id = $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular); 


}





public function car_rental_booking_email_send($booking_id)

{

  global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

  global $app_name;



  $sq_booking = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));

  $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
  $date = $sq_booking['created_at'];
  $yr = explode("-", $date);
  $year =$yr[0];

  $subject = 'Booking confirmation acknowledgement! ( '.get_car_rental_booking_id($booking_id,$year). ' )';

  $email_id = $sq_customer['email_id'];

  $customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];

  $password= $email_id;

  $username =$sq_customer['contact_no'];

  $link = BASE_URL.'view/customer';



  $content = '

    <tr>
        <td>
            <table style="width:100%">  
              <tr>
                <td>
                  <p style="line-height:24px">
                      <span style="font-weight: bold"> Booking ID : </span> '.get_car_rental_booking_id($booking_id,$year).'
                  </p>
                </td>
              </tr>
              <tr>
                <td>
                  <table style="width:100%">
                    <tr>
                      <td>
                        '.mail_login_box($username, $password, $link).'
                      </td> 
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
        </td>
    </tr>
  ';



  global $model,$backoffice_email_id;

  $model->app_email_send('20',$email_id, $content, $subject);
  $model->app_email_send('20',$backoffice_email_id, $content, $subject);



}


 public function employee_sign_up_mail($first_name, $last_name, $username, $password, $email_id)
{
  global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
  global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;
   $link = BASE_URL.'view/customer';
  $content = '
  <tr>
    <td colspan="2">
      <table style="padding:0 30px">
        <tr>
          <td colspan="2">
            '.mail_login_box($username, $password, $link).'
          </td>
        </tr>
      </table>  
      </td>
    </tr>
  ';
  $subject ='Welcome aboard!';
  global $model;
  $model->app_email_send('2',$email_id, $content,$subject,'1');
}

}

?>