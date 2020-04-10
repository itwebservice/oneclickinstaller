<?php 



$flag = true;







class booking_save{



/////////////Start Complete Booking Information Save////////////////////////////////////////

public function complete_booking_information_save()



{



  $row_spec = 'sales';



  $unique_timestamp = $_POST['unique_timestamp'];







  //** Getting tour information



  $tour_id = $_POST['tour_id']; 



  $tour_group_id = $_POST['tour_group_id'];



  $emp_id = $_POST['emp_id'];



  $taxation_type = $_POST['taxation_type'];



  $customer_id = $_POST['customer_id'];

  $branch_admin_id = $_POST['branch_admin_id1'];
  $financial_year_id = $_POST['financial_year_id'];


  //** Getting member information    



  $m_honorific = $_POST['m_honorific'];



  $m_first_name = $_POST['m_first_name'];



  $m_middle_name = $_POST['m_middle_name'];



  $m_last_name = $_POST['m_last_name'];



  $m_gender = $_POST['m_gender'];



  $m_birth_date = $_POST['m_birth_date'];



  $m_age = $_POST['m_age'];



  $m_adolescence = $_POST['m_adolescence'];



  $m_passport_no = $_POST['m_passport_no'];



  $m_passport_issue_date = $_POST['m_passport_issue_date'];



  $m_passport_expiry_date = $_POST['m_passport_expiry_date'];







  $m_email_id = $_POST['m_email_id'];



  $m_mobile_no = $_POST['m_mobile_no'];



  $m_address = $_POST['m_address'];



  $m_handover_adnary = $_POST['m_handover_adnary'];



  $m_handover_gift = $_POST['m_handover_gift'];







  //**Getting relatives details



  $relative_honorofic = $_POST['relative_honorofic'];



  $relative_name = $_POST['relative_name'];



  $relative_relation = $_POST['relative_relation'];



  $relative_mobile_no = $_POST['relative_mobile_no'];







  //**Hoteling facility



  $double_bed_room = $_POST['double_bed_room'];
  $single_bed_room = $_POST['single_bed_room'];



  $extra_bed = $_POST['extra_bed'];



  $on_floor = $_POST['on_floor'];







  //** Traveling information overall



  $train_expense = $_POST['train_expense'];



  $train_service_charge = $_POST['train_service_charge'];



  $train_taxation_id = $_POST['train_taxation_id'];



  $train_service_tax = $_POST['train_service_tax'];



  $train_service_tax_subtotal = $_POST['train_service_tax_subtotal'];



  $total_train_expense = $_POST['total_train_expense'];







  $plane_expense = $_POST['plane_expense'];



  $plane_service_charge = $_POST['plane_service_charge'];



  $plane_taxation_id = $_POST['plane_taxation_id'];



  $plane_service_tax = $_POST['plane_service_tax'];



  $plane_service_tax_subtotal = $_POST['plane_service_tax_subtotal'];


  $cruise_expense = $_POST['cruise_expense'];
  $cruise_service_charge = $_POST['cruise_service_charge'];
  $cruise_taxation_id = $_POST['cruise_taxation_id'];
  $cruise_service_tax = $_POST['cruise_service_tax'];
  $cruise_service_tax_subtotal = $_POST['cruise_service_tax_subtotal'];
  $total_cruise_expense = $_POST['total_cruise_expense'];

  $total_plane_expense = $_POST['total_plane_expense'];







  $total_travel_expense = $_POST['total_travel_expense'];







  $train_ticket_path = $_POST['train_ticket_path'];



  $plane_ticket_path = $_POST['plane_ticket_path'];
  $cruise_ticket_path = $_POST['cruise_ticket_path'];






  //**Traveling information for train



  $train_uploaded_doc_path = $_POST['train_uploaded_doc_path'];



  $train_travel_date = $_POST['train_travel_date'];



  $train_from_location = $_POST['train_from_location'];



  $train_to_location = $_POST['train_to_location'];



  $train_train_no = $_POST['train_train_no'];



  $train_travel_class = $_POST['train_travel_class'];



  $train_travel_priority = $_POST['train_travel_priority'];



  $train_amount = $_POST['train_amount'];



  $train_seats = $_POST['train_seats'];







  //**Traveling information for plane
  $from_city_id_arr = $_POST['from_city_id_arr'];
  $to_city_id_arr = $_POST['to_city_id_arr'];


  $plane_uploaded_doc_path = $_POST['plane_uploaded_doc_path'];



  $plane_travel_date = $_POST['plane_travel_date'];



  $plane_from_location = $_POST['plane_from_location'];



  $plane_to_location = $_POST['plane_to_location'];



  $plane_amount = $_POST['plane_amount'];



  $plane_seats = $_POST['plane_seats'];



  $plane_company = $_POST['plane_company'];



  $arravl_arr = $_POST['arravl_arr'];


  // Cruise
  $cruise_dept_date_arr =$_POST['cruise_dept_date_arr'];
  $cruise_arrival_date_arr =$_POST['cruise_arrival_date_arr'];
  $route_arr =$_POST['route_arr'];
  $cabin_arr =$_POST['cabin_arr'];
  $sharing_arr =$_POST['sharing_arr'];
  $cruise_seats_arr =$_POST['cruise_seats_arr'];
  $cruise_amount_arr =$_POST['cruise_amount_arr'];




  //**Visa Details



  $visa_country_name = $_POST['visa_country_name'];



  $visa_amount = $_POST['visa_amount'];



  $visa_service_charge = $_POST['visa_service_charge'];



  $visa_taxation_id = $_POST['visa_taxation_id'];



  $visa_service_tax = $_POST['visa_service_tax'];



  $visa_service_tax_subtotal = $_POST['visa_service_tax_subtotal'];



  $visa_total_amount = $_POST['visa_total_amount'];







  $insuarance_company_name = $_POST['insuarance_company_name'];



  $insuarance_amount = $_POST['insuarance_amount'];



  $insuarance_service_charge = $_POST['insuarance_service_charge'];



  $insuarance_taxation_id = $_POST['insuarance_taxation_id'];



  $insuarance_service_tax = $_POST['insuarance_service_tax'];



  $insuarance_service_tax_subtotal = $_POST['insuarance_service_tax_subtotal'];



  $insuarance_total_amount = $_POST['insuarance_total_amount'];







  //**Discount details



  $adult_expense = $_POST['adult_expense'];              



  $children_expense = $_POST['children_expense'];



  $infant_expense = $_POST['infant_expense'];



  $tour_fee = $_POST['tour_fee'];



  $repeater_discount = $_POST['repeater_discount'];



  $adjustment_discount = $_POST['adjustment_discount'];



  $tour_fee_subtotal_1 = $_POST['tour_fee_subtotal_1'];



  $tour_taxation_id = $_POST['tour_taxation_id'];



  $service_tax_per = $_POST['service_tax_per'];



  $service_tax = $_POST['service_tax'];



  $tour_fee_subtotal_2 = $_POST['tour_fee_subtotal_2'];



  $total_tour_fee = $_POST['total_tour_fee'];  











  //**Payment details



  $payment_date = $_POST['payment_date'];



  $payment_mode = $_POST['payment_mode'];



  $payment_amount = $_POST['payment_amount'];



  $bank_name = $_POST['bank_name'];



  $transaction_id = $_POST['transaction_id'];



  $payment_for = $_POST['payment_for'];



  $p_travel_type = $_POST['p_travel_type'];



  $bank_id_arr = $_POST['bank_id_arr'];







  //**Form information



  $special_request = $_POST['special_request'];

  $special_request = addslashes($special_request);

  $due_date = $_POST['due_date'];



  $form_date = $_POST['form_date'];

  $unique_timestamp_count = mysql_num_rows(mysql_query("select unique_timestamp from tourwise_traveler_details where unique_timestamp='$unique_timestamp'"));







    if($unique_timestamp_count>0)



    {



      echo "Error! This timestamp already exists.";



      exit;



    } 















  //Transaction start



  begin_t();







  //** This function saves travelers details and returns traveleres group id.



  $member_group_id = $this->traveler_member_details_save($m_honorific, $m_first_name, $m_middle_name, $m_last_name, $m_gender, $m_birth_date, $m_age, $m_adolescence, $m_passport_no, $m_passport_issue_date, $m_passport_expiry_date, $m_handover_adnary, $m_handover_gift);







  //** This function saves tourwise traveler details and returns tourwise traveler id



  $tourwise_traveler_id = $this->tourwise_traveler_detail_save($unique_timestamp, $member_group_id, $tour_id, $tour_group_id, $emp_id, $taxation_type, $customer_id, $relative_honorofic, $relative_name, $relative_relation, $relative_mobile_no,$single_bed_room, $double_bed_room, $extra_bed, $on_floor, $train_expense, $train_service_charge, $train_taxation_id, $train_service_tax, $train_service_tax_subtotal, $total_train_expense, $plane_expense, $plane_service_charge, $plane_taxation_id, $plane_service_tax, $plane_service_tax_subtotal, $total_plane_expense, $cruise_expense, $cruise_service_charge, $cruise_taxation_id, $cruise_service_tax, $cruise_service_tax_subtotal, $total_cruise_expense, $total_travel_expense, $train_ticket_path, $plane_ticket_path, $cruise_ticket_path, $visa_country_name, $visa_amount, $visa_service_charge, $visa_taxation_id, $visa_service_tax, $visa_service_tax_subtotal, $visa_total_amount, $insuarance_company_name, $insuarance_amount, $insuarance_service_charge, $insuarance_taxation_id, $insuarance_service_tax, $insuarance_service_tax_subtotal, $insuarance_total_amount, $adult_expense, $children_expense, $infant_expense, $tour_fee, $repeater_discount, $adjustment_discount, $tour_fee_subtotal_1, $tour_taxation_id, $service_tax_per, $service_tax, $tour_fee_subtotal_2, $total_tour_fee, $special_request, $due_date, $form_date, $current_booked_seats,$branch_admin_id,$financial_year_id);







  //**Traveler ersonal information save



  $this->traveler_personal_information_save($tourwise_traveler_id,$branch_admin_id, $m_email_id, $m_mobile_no, $m_address);







  //** This function stores the traveling information



  $this->save_traveling_information( $tourwise_traveler_id, $train_travel_date, $train_from_location, $train_to_location, $train_train_no, $train_travel_class, $train_travel_priority, $train_amount, $train_seats, $plane_travel_date, $from_city_id_arr, $plane_from_location, $to_city_id_arr, $plane_to_location, $plane_amount, $plane_seats, $plane_company, $arravl_arr,$cruise_dept_date_arr, $cruise_arrival_date_arr, $route_arr, $cabin_arr, $sharing_arr, $cruise_seats_arr, $cruise_amount_arr);







  //** This function saves payment details



  $this->save_payment_details($tourwise_traveler_id, $payment_date, $payment_mode, $payment_amount, $bank_name, $transaction_id, $payment_for, $p_travel_type, $bank_id_arr, $branch_admin_id, $emp_id);







  //** This function sends confirmation mail to traveler  



  $this->send_mail_to_traveler($tourwise_traveler_id, $tour_id, $tour_group_id, $customer_id, $m_email_id);







  //** This function sends message to the traveler



  $this->booking_successfull_sms_send($m_mobile_no,$customer_id,$tour_id);







  //**=============**Finance Entries save start**============**//



  $booking_save_transaction = new booking_save_transaction;







  $booking_save_transaction->finance_save($tourwise_traveler_id, $row_spec, $branch_admin_id);


  //**=============**Finance Entries save end**============**//











  if($GLOBALS['flag']){



    commit_t();

  $sq = mysql_query("select max(payment_id) as max from payment_master");



  for($i=0; $i<sizeof($payment_amount); $i++)

  {

    $payment_amount1 += $payment_amount[$i];

  }

 

  //Update customer credit note balance
  $payment_amount2 = $payment_amount1;
  $sq_credit_note = mysql_query("select * from credit_note_master where customer_id='$customer_id'");
  $i=0;
  while($row_credit = mysql_fetch_assoc($sq_credit_note)) 
  {   
      if($row_credit['payment_amount'] <= $payment_amount2 && $payment_amount2 != '0'){       
          $payment_amount2 = $payment_amount2 - $row_credit['payment_amount'];
          $temp_amount = 0;
      }
      else{
          $temp_amount = $row_credit['payment_amount'] - $payment_amount2;
          $payment_amount2 = 0;
      }
      $sq_credit = mysql_query("update credit_note_master set payment_amount ='$temp_amount' where id='$row_credit[id]'");
      
  }

  //Getting customer Ledger
  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $total_tour_expense = $total_tour_fee + $total_travel_expense;
  $balance_amount = $total_tour_expense - $payment_amount1;
  $form_date1 = get_date_db($form_date);
  $date = $form_date1;
  $yr = explode("-", $date);
  $year1 =$yr[0];
  global $transaction_master;
  ////////Balance Amount//////
  $module_name = "Group Booking";
  $module_entry_id = $tourwise_traveler_id;
  $transaction_id = "";
  $payment_amount = $balance_amount;
  $payment_date = $form_date1;
  $payment_particular = get_sales_particular(get_group_booking_id($tourwise_traveler_id,$year1), $form_date1, $balance_amount, $customer_id);
  $ledger_particular = get_ledger_particular('To','Group Tour Sales');
  $gl_id = $cust_gl;
  $payment_side = "Debit";
  $clearance_status = "";
  $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular); 



    // payment email send



    $payment_master  = new booking_payment;



    



    $payment_master->payment_email_notification_send($tourwise_traveler_id, $payment_amount1,$payment_mode, $payment_date[0]);







    // payment sms send



    $payment_master->payment_notification_sms_send( $payment_amount1, $payment_mode,$tourwise_traveler_id);



            



    echo "Group Tour has been successfully saved.";  



    exit;



  }



  else{



    rollback_t();



    exit;



  }



  







}











///////////////////////////////////***** Saving Traveleres Members Information *********/////////////////////////////////////////////////////////////



public function traveler_member_details_save($m_honorific, $m_first_name, $m_middle_name, $m_last_name, $m_gender, $m_birth_date, $m_age, $m_adolescence, $m_passport_no, $m_passport_issue_date, $m_passport_expiry_date, $m_handover_adnary, $m_handover_gift)



{



  $sq = mysql_query("select max(traveler_group_id) as max from travelers_details");



  $value = mysql_fetch_assoc($sq);



  $max_traveler_group_id = $value['max']+1;







    



    $m_handover_adnary = mysql_real_escape_string($m_handover_adnary);    



    $m_handover_gift = mysql_real_escape_string($m_handover_gift);



    



  for($i=0; $i<sizeof($m_first_name); $i++)



  {



    //$max_traveler_id = $max_traveler_id + 1;



    $sq = mysql_query("select max(traveler_id) as max from travelers_details");



    $value = mysql_fetch_assoc($sq);



    $max_traveler_id = $value['max'] + 1;







    $m_honorific[$i] = mysql_real_escape_string($m_honorific[$i]);



    $m_first_name[$i] = mysql_real_escape_string($m_first_name[$i]);



    $m_middle_name[$i] = mysql_real_escape_string($m_middle_name[$i]);



    $m_last_name[$i] = mysql_real_escape_string($m_last_name[$i]);



    $m_gender[$i] = mysql_real_escape_string($m_gender[$i]);



    $m_birth_date[$i] = mysql_real_escape_string($m_birth_date[$i]);



    $m_age[$i] = mysql_real_escape_string($m_age[$i]);



    $m_adolescence[$i] = mysql_real_escape_string($m_adolescence[$i]);



    $m_passport_no[$i] = mysql_real_escape_string($m_passport_no[$i]);



    $m_passport_issue_date[$i] = mysql_real_escape_string($m_passport_issue_date[$i]);



    $m_passport_expiry_date[$i] = mysql_real_escape_string($m_passport_expiry_date[$i]);







    if($m_birth_date[$i]!=""){  $m_birth_date[$i] = date("Y-m-d", strtotime($m_birth_date[$i])); }



    if($m_passport_issue_date[$i]!=""){  $m_passport_issue_date[$i] = date("Y-m-d", strtotime($m_passport_issue_date[$i])); }



    if($m_passport_expiry_date[$i]!=""){  $m_passport_expiry_date[$i] = date("Y-m-d", strtotime($m_passport_expiry_date[$i])); }



     



    $sq = mysql_query("insert into travelers_details (traveler_id, traveler_group_id, m_honorific, first_name, middle_name, last_name, gender, birth_date, age, adolescence, passport_no, passport_issue_date, passport_expiry_date, handover_adnary, handover_gift, status) values ('$max_traveler_id','$max_traveler_group_id', '$m_honorific[$i]', '$m_first_name[$i]', '$m_middle_name[$i]', '$m_last_name[$i]', '$m_gender[$i]', '$m_birth_date[$i]', '$m_age[$i]', '$m_adolescence[$i]', '$m_passport_no[$i]', '$m_passport_issue_date[$i]', '$m_passport_expiry_date[$i]', '$m_handover_adnary', '$m_handover_gift', 'Active')");   







    if(!$sq)



    {



      $GLOBALS['flag'] = false;



      echo "Error at row".($i+1)." for traveler members.";



      //exit;



    }  



    



  }  



  



  return $max_traveler_group_id;  







}











///////////////////////////////////***** Saving Tourwise Traveler Information *********/////////////////////////////////////////////////////////////



public function tourwise_traveler_detail_save($unique_timestamp, $member_group_id, $tour_id, $tour_group_id, $emp_id, $taxation_type, $customer_id, $relative_honorofic, $relative_name, $relative_relation, $relative_mobile_no,$single_bed_room, $double_bed_room, $extra_bed, $on_floor, $train_expense, $train_service_charge, $train_taxation_id, $train_service_tax, $train_service_tax_subtotal, $total_train_expense, $plane_expense, $plane_service_charge, $plane_taxation_id, $plane_service_tax, $plane_service_tax_subtotal, $total_plane_expense, $cruise_expense, $cruise_service_charge, $cruise_taxation_id, $cruise_service_tax, $cruise_service_tax_subtotal, $total_cruise_expense, $total_travel_expense, $train_ticket_path, $plane_ticket_path, $cruise_ticket_path, $visa_country_name, $visa_amount, $visa_service_charge, $visa_taxation_id, $visa_service_tax, $visa_service_tax_subtotal, $visa_total_amount, $insuarance_company_name, $insuarance_amount, $insuarance_service_charge, $insuarance_taxation_id, $insuarance_service_tax, $insuarance_service_tax_subtotal, $insuarance_total_amount, $adult_expense, $children_expense, $infant_expense, $tour_fee, $repeater_discount, $adjustment_discount, $tour_fee_subtotal_1, $tour_taxation_id, $service_tax_per, $service_tax, $tour_fee_subtotal_2, $total_tour_fee, $special_request, $due_date, $form_date, $current_booked_seats,$branch_admin_id,$financial_year_id)



{



  $sq = mysql_query("select max(id) as max from tourwise_traveler_details");



  $value = mysql_fetch_assoc($sq);



  $tourwise_traveler_id = $value['max']+1;



  $form_date=date("Y-m-d H:i:s", strtotime($form_date));
  $due_date=date("Y-m-d", strtotime($due_date));



 echo $current_booked_seats;
 

  $sq = mysql_query("insert into tourwise_traveler_details (id, traveler_group_id, tour_id, tour_group_id, emp_id, taxation_type, customer_id, branch_admin_id,financial_year_id,  relative_honorofic, relative_name, relative_relation, relative_mobile_no,s_single_bed_room, s_double_bed_room, s_extra_bed, s_on_floor, train_expense, train_service_charge, train_taxation_id, train_service_tax, train_service_tax_subtotal, total_train_expense, plane_expense, plane_service_charge, plane_taxation_id, plane_service_tax, plane_service_tax_subtotal, total_plane_expense,cruise_expense, cruise_service_charge, cruise_taxation_id, cruise_service_tax, cruise_service_tax_subtotal, total_cruise_expense, total_travel_expense, train_upload_ticket, plane_upload_ticket,cruise_upload_ticket, visa_country_name, visa_amount, visa_service_charge, visa_taxation_id, visa_service_tax, visa_service_tax_subtotal, visa_total_amount, insuarance_company_name, insuarance_amount, insuarance_service_charge, insuarance_taxation_id, insuarance_service_tax, insuarance_service_tax_subtotal, insuarance_total_amount, adult_expense, children_expense, infant_expense, tour_fee, repeater_discount, adjustment_discount, tour_fee_subtotal_1, tour_taxation_id, service_tax_per, service_tax, tour_fee_subtotal_2, total_tour_fee, special_request, balance_due_date, form_date, current_booked_seats, unique_timestamp) values ('$tourwise_traveler_id', '$member_group_id', '$tour_id', '$tour_group_id', '$emp_id', '$taxation_type', '$customer_id', '$branch_admin_id','$financial_year_id', '$relative_honorofic', '$relative_name', '$relative_relation', '$relative_mobile_no', '$single_bed_room', '$double_bed_room', '$extra_bed', '$on_floor', '$train_expense', '$train_service_charge', '$train_taxation_id', '$train_service_tax', '$train_service_tax_subtotal', '$total_train_expense', '$plane_expense', '$plane_service_charge', '$plane_taxation_id', '$plane_service_tax', '$plane_service_tax_subtotal', '$total_plane_expense', '$cruise_expense', '$cruise_service_charge', '$cruise_taxation_id', '$cruise_service_tax', '$cruise_service_tax_subtotal', '$total_cruise_expense', '$total_travel_expense', '$train_ticket_path', '$plane_ticket_path', '$cruise_ticket_path', '$visa_country_name', '$visa_amount', '$visa_service_charge', '$visa_taxation_id', '$visa_service_tax', '$visa_service_tax_subtotal', '$visa_total_amount', '$insuarance_company_name', '$insuarance_amount', '$insuarance_service_charge', '$insuarance_taxation_id', '$insuarance_service_tax', '$insuarance_service_tax_subtotal', '$insuarance_total_amount', '$adult_expense', '$children_expense', '$infant_expense', '$tour_fee', '$repeater_discount', '$adjustment_discount', '$tour_fee_subtotal_1', '$tour_taxation_id', '$service_tax_per', '$service_tax', '$tour_fee_subtotal_2', '$total_tour_fee', '$special_request', '$due_date', '$form_date', '$current_booked_seats', '$unique_timestamp') ");







  if(!$sq)



    {



      $GLOBALS['flag'] = false;



      echo "<br><b>Sorry, Booking not Saved!</b><br>";



      //exit;



    }    







  return $tourwise_traveler_id;



}







///////////////////////////////////***** Traveler personal information save*********/////////////////////////////////////////////////////////////



public function traveler_personal_information_save($tourwise_traveler_id,$branch_admin_id, $m_email_id, $m_mobile_no, $m_address)



{



    $m_email_id = mysql_real_escape_string($m_email_id);



    $m_mobile_no1 = mysql_real_escape_string($m_mobile_no1);



    $m_address1 = mysql_real_escape_string($m_address1);







    $sq_max_id = mysql_fetch_assoc(mysql_query("select max(personal_info_id) as max from traveler_personal_info"));



    $personal_info_id = $sq_max_id['max']+1;







    $sq = mysql_query("insert into traveler_personal_info ( personal_info_id, tourwise_traveler_id,branch_admin_id, email_id, mobile_no, address ) values ( '$personal_info_id', '$tourwise_traveler_id', '$branch_admin_id', '$m_email_id', '$m_mobile_no', '$m_address' )");







    if(!$sq){



      $GLOBALS['flag'] = false;



      echo "Traveler personal information not saved.";



      //exit;



    }



}











 ///////////////////////////////////***** Saving Traveling Information *********/////////////////////////////////////////////////////////////



 public function save_traveling_information($tourwise_traveler_id, $train_travel_date, $train_from_location, $train_to_location, $train_train_no, $train_travel_class, $train_travel_priority, $train_amount, $train_seats, $plane_travel_date, $from_city_id_arr, $plane_from_location, $to_city_id_arr,  $plane_to_location, $plane_amount, $plane_seats, $plane_company, $arravl_arr,$cruise_dept_date_arr, $cruise_arrival_date_arr, $route_arr, $cabin_arr, $sharing_arr, $cruise_seats_arr, $cruise_amount_arr)



 {







      //**Saves Train Information    



      



      for($i=0; $i<sizeof($train_travel_date); $i++)



      {



        //$max_train_id = $max_train_id + 1;



        $sq = mysql_query("select max(train_id) as max from train_master");



        $value = mysql_fetch_assoc($sq);



        $max_train_id = $value['max'] + 1;







        $train_travel_date[$i] = mysql_real_escape_string($train_travel_date[$i]);



        $train_from_location[$i] = mysql_real_escape_string($train_from_location[$i]);



        $train_to_location[$i] = mysql_real_escape_string($train_to_location[$i]);



        $train_train_no[$i] = mysql_real_escape_string($train_train_no[$i]);



        $train_travel_class[$i] = mysql_real_escape_string($train_travel_class[$i]);



        $train_travel_priority[$i] = mysql_real_escape_string($train_travel_priority[$i]);



        $train_amount[$i] = mysql_real_escape_string($train_amount[$i]);



        $train_seats[$i] = mysql_real_escape_string($train_seats[$i]);







        $train_travel_date[$i] = date("Y-m-d H:i:s", strtotime($train_travel_date[$i]));







        $sq = mysql_query("insert into train_master (train_id, tourwise_traveler_id, date, from_location, to_location, train_no, amount, seats, train_priority, train_class) values ('$max_train_id', '$tourwise_traveler_id', '$train_travel_date[$i]', '$train_from_location[$i]', '$train_to_location[$i]', '$train_train_no[$i]', '$train_amount[$i]', '$train_seats[$i]', '$train_travel_priority[$i]', '$train_travel_class[$i]') ");



        if(!$sq)



        {



          $GLOBALS['flag'] = false;



          echo "Error at row".($i+1)." for train information.";



          //exit;



        }   







      }  



     







      //**Saves Plane Information          



      for($i=0; $i<sizeof($plane_travel_date); $i++)



      {



        //$max_plane_id = $max_plane_id + 1;



        $sq = mysql_query("select max(plane_id) as max from plane_master");



        $value = mysql_fetch_assoc($sq);



        $max_plane_id = $value['max'] + 1;


        $plane_travel_date[$i] = mysql_real_escape_string($plane_travel_date[$i]);

        $from_city_id_arr[$i] = mysql_real_escape_string($from_city_id_arr[$i]);

        $plane_from_location[$i] = mysql_real_escape_string($plane_from_location[$i]);

        $to_city_id_arr[$i] = mysql_real_escape_string($to_city_id_arr[$i]);

        $plane_to_location[$i] = mysql_real_escape_string($plane_to_location[$i]);



        $plane_amount[$i] = mysql_real_escape_string($plane_amount[$i]);



        $plane_seats[$i] = mysql_real_escape_string($plane_seats[$i]);



        $plane_company[$i] = mysql_real_escape_string($plane_company[$i]);



        $arravl_arr[$i] = mysql_real_escape_string($arravl_arr[$i]);







        $plane_travel_date[$i] = date("Y-m-d H:i:s", strtotime($plane_travel_date[$i]));



        $arravl_arr[$i] = date('Y-m-d H:i:s', strtotime($arravl_arr[$i]));


        $sq = mysql_query(" insert into plane_master (plane_id, tourwise_traveler_id, date, from_city, from_location, to_city, to_location, company, amount, seats, arraval_time ) values ('$max_plane_id', '$tourwise_traveler_id',   '$plane_travel_date[$i]','$from_city_id_arr[$i]', '$plane_from_location[$i]', '$to_city_id_arr[$i]', '$plane_to_location[$i]', '$plane_company[$i]', '$plane_amount[$i]', '$plane_seats[$i]', '$arravl_arr[$i]') ");







        if(!$sq)



        {



          $GLOBALS['flag'] = false;



          echo "Error at row".($i+1)." for Flight information.";



          //exit;



        }  



      }

      //**Saves Cruise Information    

      

      for($i=0; $i<sizeof($cruise_dept_date_arr); $i++)

      {


        $sq = mysql_query("select max(cruise_id) as max from group_cruise_master");

        $value = mysql_fetch_assoc($sq);

        $max_cruise_id = $value['max'] + 1;



        $cruise_dept_date_arr[$i] = mysql_real_escape_string($cruise_dept_date_arr[$i]);
        $cruise_arrival_date_arr[$i] = mysql_real_escape_string($cruise_arrival_date_arr[$i]);
        $route_arr[$i] = mysql_real_escape_string($route_arr[$i]);
        $cabin_arr[$i] = mysql_real_escape_string($cabin_arr[$i]);
        $sharing_arr[$i] = mysql_real_escape_string($sharing_arr[$i]);
        $cruise_seats_arr[$i] = mysql_real_escape_string($cruise_seats_arr[$i]);
        $cruise_amount_arr[$i] = mysql_real_escape_string($cruise_amount_arr[$i]);

        $cruise_dept_date_arr[$i] = date("Y-m-d, H:i:s", strtotime($cruise_dept_date_arr[$i]));        
        $cruise_arrival_date_arr[$i] = date("Y-m-d, H:i:s", strtotime($cruise_arrival_date_arr[$i])); 
       



        $sq = mysql_query("insert into group_cruise_master (cruise_id, booking_id, dept_datetime, arrival_datetime, route, cabin,sharing, seats, amount ) values ('$max_cruise_id', '$tourwise_traveler_id', '$cruise_dept_date_arr[$i]', '$cruise_arrival_date_arr[$i]', '$route_arr[$i]', '$cabin_arr[$i]', '$sharing_arr[$i]', '$cruise_seats_arr[$i]', '$cruise_amount_arr[$i]') ");

        if(!$sq)

        {

          $GLOBALS['flag'] = false;

          echo "Error at row".($i+1)." for cruise information.";

          //exit;

        }   
      }  
 }







 ///////////////////////////////////***** Saving Payment Information *********/////////////////////////////////////////////////////////////



 public function save_payment_details($tourwise_traveler_id, $payment_date, $payment_mode, $payment_amount, $bank_name, $transaction_id, $payment_for, $p_travel_type, $bank_id_arr, $branch_admin_id, $emp_id)



 {
    $financial_year_id = $_SESSION['financial_year_id'];


    for($i=0; $i<sizeof($payment_date); $i++)



    {



      $payment_date[$i] = date("Y-m-d", strtotime($payment_date[$i]));

    if($payment_mode[$i]=="Cheque"){ 
      $clearance_status = "Pending"; } 
    else {  $clearance_status = ""; } 


     $sq = mysql_query("select max(payment_id) as max from payment_master");

     $value = mysql_fetch_assoc($sq);



     $max_payment_id = $value['max'] + 1;

     $sq= mysql_query(" insert into payment_master (payment_id, tourwise_traveler_id, financial_year_id, branch_admin_id, emp_id, date, payment_mode, amount, bank_name, transaction_id, bank_id, payment_for, travel_type, clearance_status, advance_status ) values ('$max_payment_id', '$tourwise_traveler_id', '$financial_year_id', '$branch_admin_id', '$emp_id', '$payment_date[$i]', '$payment_mode[$i]', '$payment_amount[$i]', '$bank_name[$i]', '$transaction_id[$i]', '$bank_id_arr[$i]', '$payment_for[$i]', '$p_travel_type[$i]', '$clearance_status','true') ");



     include_once('../booking_payment.php');


     $booking_payment = new booking_payment();


     $booking_payment->booking_registration_reciept_save( $tourwise_traveler_id, $max_payment_id, $payment_for[$i]);


     if(!$sq)
      {

        $GLOBALS['flag'] = false;

        echo "Error for payment information for $payment_for[$i].";

      }  

      $booking_save_transaction = new booking_save_transaction;



      $booking_save_transaction->payment_finance_save($tourwise_traveler_id, $max_payment_id, $payment_date[0], $payment_mode[$i], $payment_amount[$i], $transaction_id[$i],$bank_id_arr[$i], $branch_admin_id);


      //Bank and Cash Book Save



      $booking_save_transaction->bank_cash_book_save($tourwise_traveler_id, $max_payment_id, $payment_date[$i], $payment_mode[$i], $payment_amount[$i], $transaction_id[$i], $bank_name[$i], $bank_id_arr[$i], $branch_admin_id);



    }  
 }


///////////////////////////////////***** Send mail to traveler *********/////////////////////////////////////////////////////////////



function send_mail_to_traveler($tourwise_traveler_id, $tour_id, $tour_group_id, $customer_id, $m_email_id)

{

  global $app_email_id, $app_name, $app_contact_no, $admin_logo_url;
  global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;

  if($m_email_id=="")

  {
    $m_email_id=$app_email_id;

  }  
  $sq_booking = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$tourwise_traveler_id'"));

  // $mobile_no = $sq_booking['mobile_no'];

  $date = $sq_booking['form_date'];
  $yr = explode("-", $date);
  $year =$yr[0];

  $tour_name1 = mysql_fetch_assoc(mysql_query("select tour_name from tour_master where tour_id= '$tour_id'"));

  $tour_name = $tour_name1['tour_name'];

  $tour_group1 = mysql_fetch_assoc(mysql_query("select from_date, to_date from tour_groups where group_id= '$tour_group_id'"));

  $from_date = date("d-m-Y", strtotime($tour_group1['from_date']));

  $to_date = date("d-m-Y", strtotime($tour_group1['to_date']));
  $sq_traveler_personal_info = mysql_fetch_assoc(mysql_query("select mobile_no from traveler_personal_info where tourwise_traveler_id='$tourwise_traveler_id'"));
  $subject = 'Booking confirmation acknowledgement! ( '.get_group_booking_id($tourwise_traveler_id,$year). ' )';

  $mobile_no = $sq_traveler_personal_info['mobile_no'];

  $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
  $username = $sq_customer['contact_no'];
  $password = $sq_customer['email_id'];
  $link = BASE_URL.'/view/customer/';



  



  $content = '
    <tr>
      <td>
        <table style="width:100%">
          <tr>
            <td colspan="2">
              <table style="width:100%;"> 
                <tr>
                   <td>
                      '.mail_login_box($username, $password,$link).'
                  </td>
                </tr>  
                <tr>
                  <td style="padding-top:15px"><strong style="'.$mail_strong_style.'">Booking ID:</strong> '.get_group_booking_id($tourwise_traveler_id,$year).'</td>
                </tr>

                <tr>
                  <td><strong style="'.$mail_strong_style.'">Tour Name:</strong> '.$tour_name.'</td>
                </tr>


                <tr>
                  <td><strong style="'.$mail_strong_style.'">Journey Date:</strong> '.$from_date.' to '.$to_date.'</td>
                </tr>
              </table>
            </td>

          </tr>
        </table>
      </td>
    </tr>
  ';
  

  global $model,$backoffice_email_id;

$model->app_email_send('13',$m_email_id, $content,$subject);
$model->app_email_send('13',$backoffice_email_id, $content,$subject);

}



//////////////////////////////////**Booking successfull sms send to traveler start**/////////////////////////////////////
 function booking_successfull_sms_send($m_mobile_no,$customer_id,$tour_id)
 {
  global $app_name;

  $sq_tour = mysql_fetch_assoc(mysql_query("select tour_name from tour_master where tour_id= '$tour_id'"));
  $tour_name = $sq_tour['tour_name'];
   $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));

  $message = "Dear ".$sq_customer['first_name']." ".$sq_customer['last_name'].", your booking for ".$tour_name." has been confirmed. Voucher details send on your Mail ID within 3 working days." ;
 
  global $model;
  $model->send_message($m_mobile_no, $message);
 }
}



?>