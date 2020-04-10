<?php
class b2b_customer{
    //Registration link send
    function reg_form_send()
    {
        global $model;
        $email_id = $_POST['email_id'];
        $mobile_no = $_POST['mobile_no'];
        $content = '             
            <tr>
                <td>
                <table style="width:100%">
                    <tr>
                        <td colspan="2">
                            <a style="font-weight:500;font-size:14px;display:block;color:#ffffff;background:#009898;text-decoration:none;padding:5px 10px;border-radius:25px;width: 95px;text-align: center;" href="'.BASE_URL.'view/b2b_customer/registration/index.php">Register</a> 
                        </td> 
                    </tr>
                </table>
                </td>
            </tr>
        ';

        if($mobile_no != ''){
            $message = 'Dear Travel Partners,'. 
'Request you to fill the attached registration form. And will get back to you with your credentials soon. Use this link :- '.BASE_URL.'model/attractions_offers_enquiry/tour_enquiry.php';
            $model->send_message($mobile_no, $message);
        }

        $subject = "B2B Portal Registration Request!";
        $model->app_email_send('104',$email_id, $content,$subject,'1');

        echo "Registration Link has been successfully sent.";
    }

    //B2B customer deposit update
    function customer_update(){	
        $register_id = $_POST['register_id'];
        $credit_limit = $_POST['credit_limit'];
        $deposit = $_POST['deposit'];
        $old_deposit = $_POST['old_deposit'];
        $active_flag = $_POST['active_flag'];
        $agreement_url = $_POST['agreement_url'];
        $approve_status= $_POST['approve_status'];

        $payment_date = $_POST['payment_date'];
        $payment_mode = $_POST['payment_mode'];
        $bank_name = $_POST['bank_name'];
        $transaction_id = $_POST['transaction_id'];
        $bank_id= $_POST['bank_id'];

        $financial_year_id = $_SESSION['financial_year_id'];
        $branch_admin_id = $_SESSION['branch_admin_id'];
        $emp_id = $_SESSION['emp_id'];

        $clearance_status = ($payment_mode!="Cash") ? "Pending" : '';
        $payment_date = date('Y-m-d', strtotime($payment_date));

        //Agent Code creation
        $sq_approve = mysql_fetch_assoc(mysql_query("select * from b2b_registration where register_id='$register_id'"));
        $agent_company = substr($sq_approve['company_name'],0,3);
        $agent_mobile = substr($sq_approve['mobile_no'],-4);
        $agent_code = $agent_company.$agent_mobile;

        $sq_b2b = mysql_query("update b2b_registration set financial_year_id='$financial_year_id',emp_id='$emp_id',branch_admin_id='$branch_admin_id', payment_date='$payment_date', payment_mode='$payment_mode', bank_name='$bank_name', transaction_id='$transaction_id', bank_id='$bank_id', clearance_status='$clearance_status',deposite='$deposit',active_flag='$active_flag',agreement_url='$agreement_url',approval_status='$approve_status',username='$sq_approve[company_name]',password='$agent_mobile',agent_code='$agent_code' where register_id='$register_id'");
  
        if(!$sq_b2b){
            echo "error--Sorry Information not updated!";
            exit;
        }
        else{
            if($approve_status=='Approved' && $sq_approve['mail_status']==''){

                //B2B customer creation
                $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
                $customer_id = $sq_max['max'] + 1;
                $sq_customer = mysql_query("insert into customer_master (customer_id,type,first_name, middle_name, last_name, gender, birth_date, age, contact_no,landline_no, email_id,alt_email,company_name, address, address2, city, active_flag, created_at,service_tax_no,state_id,pan_no, branch_admin_id) values ('$customer_id','B2B', '$sq_approve[cp_first_name]', '', '$sq_approve[cp_last_name]', '', '', '', '$sq_approve[mobile_no]','$sq_approve[telephone]', '$sq_approve[email_id]','','$sq_approve[company_name]', '$sq_approve[address1]','$sq_approve[address2]','$sq_approve[city]', 'Active', '$sq_approve[created_at]', '','','$sq_approve[pan_card]','$sq_approve[branch_admin_id]')");

                //Ledger Creation
                $sq_max = mysql_fetch_assoc(mysql_query("select max(ledger_id) as max from ledger_master"));
                $ledger_id = $sq_max['max'] + 1;
                $ledger_name = $sq_approve['company_name'];
                $sq_ledger = mysql_query("insert into ledger_master (ledger_id, ledger_name, alias, group_sub_id, balance, dr_cr,customer_id,user_type) values ('$ledger_id', '$ledger_name', '', '20', '0','Dr','$customer_id','customer')");

                //Credit Limit insert
                $sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from b2b_creditlimit_master"));
                $entry_id = $sq_max['max'] + 1;
                $sq_credit = mysql_query("insert into b2b_creditlimit_master (entry_id,register_id,credit_amount, reason_for_revise, raised_by, credit_type, approval_status, created_at) values ('$entry_id','$register_id', '$credit_limit', '', '', '', '','$sq_approve[created_at]')");

                //Finance Save
                $this->finance_save($register_id,$branch_admin_id);
                //Bank and Cash Book Save
                $this->bank_cash_book_save($register_id,$branch_admin_id);
            
                $this->mail_b2blogin_box($sq_approve['company_name'], $agent_mobile,$agent_code, $sq_approve['email_id'],$register_id);
                $sq_reg = mysql_query("update b2b_registration set mail_status='Sent' where register_id='$register_id'");
                
            }
            else{
                if($approve_status == 'Rejected'){
                    $this->rejection_mail_Send($sq_approve['email_id']);
                }
                if($sq_approve['reference']=='direct'&& $deposit!=0&&$deposit==$old_deposit){
                    $this->finance_save($register_id,$branch_admin_id);
                    //Bank and Cash Book Save
                    $this->bank_cash_book_save($register_id,$branch_admin_id);
                }else{
                    //Finance update
                    $this->finance_update($register_id,$branch_admin_id);
                    //Bank and Cash Book update
                    $this->bank_cash_book_update($register_id,$branch_admin_id);
                }
            }
            echo "Information has been successfully updated.";
            exit;
        }
    }

    //Approved b2b customer save
    function b2b_customer_save(){

        //Basic Details
        $company_name = $_POST['company_name'];
        $acc_name = $_POST['acc_name'];
        $iata_status = $_POST['iata_status'];
        $iata_reg = $_POST['iata_reg'];
        $nature = $_POST['nature'];
        $currency = $_POST['currency'];
        $telephone = $_POST['telephone'];
        $latitude= $_POST['latitude'];
        $turnover_slab = $_POST['turnover_slab'];
        $skype_id = $_POST['skype_id'];
        $website = $_POST['website'];
        //Address Details
        $address1 = $_POST['address1'];
        $address2 = $_POST['address2'];
        $city = $_POST['city'];
        $pincode = $_POST['pincode'];
        $country = $_POST['country'];
        $timezone = $_POST['timezone'];
        $address_upload_url = $_POST['address_upload_url'];
        //Address Details
        $contact_personf = $_POST['contact_personf'];
        $contact_personl = $_POST['contact_personl'];
        $email_id = $_POST['email_id'];
        $mobile_no = $_POST['mobile_no'];
        $whatsapp_no = $_POST['whatsapp_no'];
        $designation = $_POST['designation'];
        $pan_card = $_POST['pan_card'];
        $photo_upload_url = $_POST['photo_upload_url'];
        //Access Details
        $username = $_POST['username'];
        $password = $_POST['password'];

        $financial_year_id = $_SESSION['financial_year_id'];
        $branch_admin_id = $_SESSION['branch_admin_id'];
        $emp_id = $_SESSION['emp_id'];
        $created_at = date("Y-m-d");

        $company_name = addslashes($company_name);
        $company_count = mysql_num_rows(mysql_query("select * from b2b_registration where company_name='$company_name'")); 
        if($company_count>0){
          echo "error--Sorry, The Company has already been taken.";
          exit;
        }
        
        //Agent Code creation
        $agent_company = substr($company_name,0,3);
        $agent_mobile = substr($mobile_no,-4);
        $agent_code = $agent_company.$agent_mobile;

        //Registration
        $sq_max1 = mysql_fetch_assoc(mysql_query("select max(register_id) as max from b2b_registration"));
        $register_id = $sq_max1['max'] + 1;
        $sq_insert = mysql_query("INSERT INTO `b2b_registration`(`register_id`, `emp_id`, `company_name`, `accounting_name`, `iata_status`, `iata_reg_no`, `nature_of_business`, `currency`, `telephone`, `latitude`, `turnover`, `skype_id`, `website`, `cp_first_name`, `cp_last_name`, `mobile_no`, `email_id`, `whatsapp_no`, `designation`, `pan_card`, `id_proof_url`, `address1`, `address2`, `city`, `pincode`, `country`, `timezone`, `address_proof_url`, `username`, `password`, `branch_admin_id`, `financial_year_id`, `active_flag`, `approval_status`, `agent_code`, `created_at`, `mail_status`, `approval_date`,`reference`) VALUES ('$register_id','$emp_id','$company_name','$acc_name','$iata_status','$iata_reg','$nature','$currency','$telephone','$latitude','$turnover_slab','$skype_id','$website','$contact_personf','$contact_personl','$mobile_no','$email_id','$whatsapp_no','$designation','$pan_card','$photo_upload_url','$address1','$address2','$city','$pincode','$country','$timezone','$address_upload_url','$username','$password','$branch_admin_id','$financial_year_id','Active','Approved','$agent_code','$created_at','Sent','$created_at','direct')");

        //////////////////////////////////////////////////////////////////////
        if($sq_insert){
            //B2B customer creation
            $sq_max = mysql_fetch_assoc(mysql_query("select max(customer_id) as max from customer_master"));
            $customer_id = $sq_max['max'] + 1;
            $sq_customer = mysql_query("insert into customer_master (customer_id,type,first_name, middle_name, last_name, gender, birth_date, age, contact_no,landline_no, email_id,alt_email,company_name, address, address2, city, active_flag, created_at,service_tax_no,state_id,pan_no, branch_admin_id) values ('$customer_id','B2B', '$contact_personf', '', '$contact_personl', '', '', '', '$mobile_no','$telephone', '$email_id','','$company_name', '$address1','$address2','$city', 'Active', '$created_at', '','','$pan_card','$branch_admin_id')");

            //Ledger Creation
            $sq_max = mysql_fetch_assoc(mysql_query("select max(ledger_id) as max from ledger_master"));
            $ledger_id = $sq_max['max'] + 1;
            $ledger_name = $company_name;
            $sq_ledger = mysql_query("insert into ledger_master (ledger_id, ledger_name, alias, group_sub_id, balance, dr_cr,customer_id,user_type) values ('$ledger_id', '$ledger_name', '', '20', '0','Dr','$customer_id','customer')");

            //Credit Limit insert
            $sq_max = mysql_fetch_assoc(mysql_query("select max(entry_id) as max from b2b_creditlimit_master"));
            $entry_id = $sq_max['max'] + 1;
            $sq_credit = mysql_query("insert into b2b_creditlimit_master (entry_id,register_id,credit_amount, reason_for_revise, raised_by, credit_type, approval_status, created_at) values ('$entry_id','$register_id', '0', '', '', '', '','$created_at')");

            if($sq_ledger && $sq_credit){
                //Send Acknowledgement Mails
                $this->mail_b2blogin_box($username, $password,$agent_code, $email_id,$register_id);
            }
            echo "Information has been successfully saved.";
            exit;
        }
        else{
            echo "error-Information has not been saved.";
            exit;
        }
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Registration Request by b2b customer
    function reg_customer_save(){

        //Basic Details
        $company_name = $_POST['company_name'];
        $acc_name = $_POST['acc_name'];
        $iata_status = $_POST['iata_status'];
        $iata_reg = $_POST['iata_reg'];
        $nature = $_POST['nature'];
        $currency = $_POST['currency'];
        $telephone = $_POST['telephone'];
        $latitude= $_POST['latitude'];
        $turnover_slab = $_POST['turnover_slab'];
        $skype_id = $_POST['skype_id'];
        $website = $_POST['website'];
        //Address Details
        $address1 = $_POST['address1'];
        $address2 = $_POST['address2'];
        $city = $_POST['city'];
        $pincode = $_POST['pincode'];
        $country = $_POST['country'];
        $timezone = $_POST['timezone'];
        $address_upload_url = $_POST['address_upload_url'];
        //Address Details
        $contact_personf = $_POST['contact_personf'];
        $contact_personl = $_POST['contact_personl'];
        $email_id = $_POST['email_id'];
        $mobile_no = $_POST['mobile_no'];
        $whatsapp_no = $_POST['whatsapp_no'];
        $designation = $_POST['designation'];
        $pan_card = $_POST['pan_card'];
        $photo_upload_url = $_POST['photo_upload_url'];
        //Access Details
        $username = $_POST['username'];
        $password = $_POST['password'];

        $financial_year_id = $_SESSION['financial_year_id'];
        $branch_admin_id = $_SESSION['branch_admin_id'];
        $emp_id = $_SESSION['emp_id'];
        $created_at = date("Y-m-d");

        $company_name = addslashes($company_name);
        $company_count = mysql_num_rows(mysql_query("select * from b2b_registration where company_name='$company_name'")); 
        if($company_count>0){
        echo "error--Sorry, The Company has already been taken.";
        exit;
        }
        //Registration
        $sq_max1 = mysql_fetch_assoc(mysql_query("select max(register_id) as max from b2b_registration"));
        $register_id = $sq_max1['max'] + 1;
        $sq_insert = mysql_query("INSERT INTO `b2b_registration`(`register_id`, `emp_id`, `company_name`, `accounting_name`, `iata_status`, `iata_reg_no`, `nature_of_business`, `currency`, `telephone`, `latitude`, `turnover`, `skype_id`, `website`, `cp_first_name`, `cp_last_name`, `mobile_no`, `email_id`, `whatsapp_no`, `designation`, `pan_card`, `id_proof_url`, `address1`, `address2`, `city`, `pincode`, `country`, `timezone`, `address_proof_url`, `username`, `password`, `branch_admin_id`, `financial_year_id`, `active_flag`, `approval_status`, `agent_code`, `created_at`, `mail_status`, `approval_date`) VALUES ('$register_id','$emp_id','$company_name','$acc_name','$iata_status','$iata_reg','$nature','$currency','$telephone','$latitude','$turnover_slab','$skype_id','$website','$contact_personf','$contact_personl','$mobile_no','$email_id','$whatsapp_no','$designation','$pan_card','$photo_upload_url','$address1','$address2','$city','$pincode','$country','$timezone','$address_upload_url','$username','$password','','','Active','','','$created_at','','$created_at')");

        //////////////////////////////////////////////////////////////////////
        if($sq_insert){    
            //Send Acknowledgement Mails
            $this->acknowledgement_mail_Send($email_id);
        
            echo "Information has been successfully saved.";
            exit;
        }
        else{
            echo "error-Information has not been saved.";
            exit;
        }
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //B2B customer update
    function b2b_customer_update(){
        $register_id = $_POST['register_id'];
        //Basic Details
        $company_old = $_POST['company_old'];
        $company_name = $_POST['company_name'];
        $acc_name = $_POST['acc_name'];
        $iata_status = $_POST['iata_status'];
        $iata_reg = $_POST['iata_reg'];
        $nature = $_POST['nature'];
        $currency = $_POST['currency'];
        $telephone = $_POST['telephone'];
        $latitude= $_POST['latitude'];
        $turnover_slab = $_POST['turnover_slab'];
        $skype_id = $_POST['skype_id'];
        $website = $_POST['website'];
        //Address Details
        $address1 = $_POST['address1'];
        $address2 = $_POST['address2'];
        $city = $_POST['city'];
        $pincode = $_POST['pincode'];
        $country = $_POST['country'];
        $timezone = $_POST['timezone'];
        $address_upload_url = $_POST['address_upload_url'];
        //Address Details
        $contact_personf = $_POST['contact_personf'];
        $contact_personl = $_POST['contact_personl'];
        $email_id = $_POST['email_id'];
        $mobile_no = $_POST['mobile_no'];
        $whatsapp_no = $_POST['whatsapp_no'];
        $designation = $_POST['designation'];
        $pan_card = $_POST['pan_card'];
        $photo_upload_url = $_POST['photo_upload_url'];
        //Access Details
        $username = $_POST['username'];
        $password = $_POST['password'];
        $active_flag = $_POST['active_flag'];

        $company_name = addslashes($company_name);
        $company_old = addslashes($company_old);

        $sq_old = mysql_fetch_assoc(mysql_query("select * from b2b_registration where register_id='$register_id'")); 

        $company_count = mysql_num_rows(mysql_query("select * from b2b_registration where company_name='$company_name' and register_id!='$register_id'"));
        if($company_count>0){
          echo "error--Sorry, The Company has already been taken.";
          exit;
        }
        
        //Registration
        $sq_update = mysql_query("UPDATE `b2b_registration` SET `company_name`='$company_name',`accounting_name`='$acc_name',`iata_status`='$iata_status',`iata_reg_no`='$iata_reg',`nature_of_business`='$nature',`currency`='$currency',`telephone`='$telephone',`latitude`='$latitude',`turnover`='$turnover_slab',`skype_id`='$skype_id',`website`='$website',`cp_first_name`='$contact_personf',`cp_last_name`='$contact_personl',`mobile_no`='$mobile_no',`email_id`='$email_id',`whatsapp_no`='$whatsapp_no',`designation`='$designation',`pan_card`='$pan_card',`id_proof_url`='$photo_upload_url',`address1`='$address1',`address2`='$address2',`city`='$city',`pincode`='$pincode',`country`='$country',`timezone`='$timezone',`address_proof_url`='$address_upload_url',`username`='$username',`password`='$password',`active_flag`='$active_flag' WHERE register_id='$register_id'");
        //////////////////////////////////////////////////////////////////////

        if($sq_update){
            //B2B customer updatation
            $sq_cust = mysql_fetch_assoc(mysql_query("select customer_id from customer_master where company_name='$company_old'"));
            $q = "update customer_master set first_name='$contact_personf', last_name='$contact_personl', contact_no='$mobile_no',landline_no='$telephone', email_id='$email_id',company_name='$company_name', address='$address1', address2='$address2', city='$city',pan_no='$pan_card' where customer_id='$sq_cust[customer_id]'";
            $sq_customer = mysql_query($q);

            //Ledger updatation
            $ledger_name = $company_name;

            $sq_ledger = mysql_query("update ledger_master set ledger_name='$ledger_name' where ledger_name='$company_old' and customer_id='$sq_cust[customer_id]' and user_type='customer'");
            

            if($sq_old['password']!=$password){
                //Send Acknowledgement Mails
                $this->mail_b2blogin_box($username, $password,$sq_old['agent_code'], $email_id,$register_id);
            }
            echo "Information has been successfully updated.";
            exit;
        }
        else{
            echo "error-Information has not been updated";
            exit;
        }
    }

    function finance_save($entry_id,$branch_admin_id){
        $row_spec = 'b2b deposit';
        $deposit = $_POST['deposit'];
        $bank_id = $_POST['bank_id'];
        $payment_date = $_POST['payment_date'];
        $payment_mode = $_POST['payment_mode'];
        $bank_name = $_POST['bank_name'];
        $transaction_id = $_POST['transaction_id'];

        $payment_date1 = date('Y-m-d', strtotime($payment_date));
        //Getting cash/Bank Ledger
        if($payment_mode == 'Cash') {  $pay_gl = 20; }
        else{ 
            $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
            $pay_gl = $sq_bank['ledger_id'];
        }
        
        global $transaction_master;

        ////////////Basic deposit Amount/////////////

        $module_name = "B2b Deposit";
        $module_entry_id = $entry_id;
        $transaction_id = $transaction_id;
        $payment_amount = $deposit;
        $payment_date = $payment_date1;
        $payment_particular = get_b2b_deposit_particular($bank_id,$deposit);    
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = 34;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

        /////////bank////////
        $module_name = "B2b Deposit";
        $module_entry_id = $entry_id;
        $transaction_id = $transaction_id;
        $payment_amount = $deposit;
        $payment_date = $payment_date1;
        $payment_particular = get_b2b_deposit_particular($bank_id,$deposit);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $pay_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    }
    public function bank_cash_book_save($register_id,$branch_admin_id)
    {
        global $bank_cash_book_master;
    
        $deposit = $_POST['deposit'];
        $bank_id = $_POST['bank_id'];
        $payment_date = $_POST['payment_date'];
        $payment_mode = $_POST['payment_mode'];
        $bank_name = $_POST['bank_name'];
        $transaction_id = $_POST['transaction_id'];

        $payment_date1 = date('Y-m-d', strtotime($payment_date));

        $module_name = "B2b Deposit";
        $module_entry_id = $register_id;
        $payment_date = $payment_date1;
        $payment_amount = $deposit;
        $payment_mode = $payment_mode;
        $bank_name = $bank_name;
        $transaction_id = $transaction_id;
        $bank_id = $bank_id;
        $particular = get_b2b_deposit_particular($bank_id,$deposit);
        $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
        $payment_side = "Debit";
        $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";
    
        $bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);
        
    }
    
    function finance_update($entry_id,$branch_admin_id){
        $row_spec = 'b2b deposit';
        $deposit = $_POST['deposit'];
        $bank_id = $_POST['bank_id'];
        $payment_date = $_POST['payment_date'];
        $payment_mode = $_POST['payment_mode'];
        $bank_name = $_POST['bank_name'];
        $transaction_id = $_POST['transaction_id'];

        $payment_date1 = date('Y-m-d', strtotime($payment_date));

        //Getting cash/Bank Ledger
        if($payment_mode == 'Cash') {  $pay_gl = 20; }
        else{ 
            $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
            $pay_gl = $sq_bank['ledger_id'];
        }
        
        global $transaction_master;

        ////////////Basic deposit Amount/////////////

        $module_name = "B2b Deposit";
        $module_entry_id = $entry_id;
        $transaction_id = $transaction_id;
        $payment_amount = $deposit;
        $payment_date = $payment_date1;
        $payment_particular = get_b2b_deposit_particular($bank_id,$deposit);    
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $old_gl_id = 34;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

        /////////bank////////
        $module_name = "B2b Deposit";
        $module_entry_id = $entry_id;
        $transaction_id = $transaction_id;
        $payment_amount = $deposit;
        $payment_date = $payment_date1;
        $payment_particular = get_b2b_deposit_particular($bank_id,$deposit);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $old_gl_id = $pay_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id, $payment_side, $clearance_status, $row_spec,$ledger_particular);

    }
    public function bank_cash_book_update($register_id,$branch_admin_id)
    {
        global $bank_cash_book_master;
    
        $deposit = $_POST['deposit'];
        $bank_id = $_POST['bank_id'];
        $payment_date = $_POST['payment_date'];
        $payment_mode = $_POST['payment_mode'];
        $bank_name = $_POST['bank_name'];
        $transaction_id = $_POST['transaction_id'];

        $payment_date1 = date('Y-m-d', strtotime($payment_date));

        $module_name = "B2b Deposit";
        $module_entry_id = $register_id;
        $payment_date = $payment_date1;
        $payment_amount = $deposit;
        $payment_mode = $payment_mode;
        $bank_name = $bank_name;
        $transaction_id = $transaction_id;
        $bank_id = $bank_id;
        $particular = get_b2b_deposit_particular($bank_id,$deposit);
        $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
        $payment_side = "Debit";
        $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";
    
        $bank_cash_book_master->bank_cash_book_master_update($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);
        
    }

    function customer_delete(){	
        $register_id = $_POST['register_id'];

        $sq_b2b = mysql_query("delete from b2b_registration where register_id='$register_id'");
  
        if(!$sq_b2b){
            echo "error--Sorry Information not deleted!";
            exit;
        }
        else{
            echo "Information has been successfully deleted.";
            exit;
        }
    }

    function mail_b2blogin_box($username, $password,$agent_code, $email_id,$register_id){
      global $mail_em_style, $mail_font_family, $mail_strong_style, $app_name;
      $sq_customer = mysql_fetch_assoc(mysql_query("select * from b2b_registration where register_id='$register_id'"));
      $link = '';
      $content = '';
      if($sq_customer['deposite'] != 0){
        $bank_details = ($sq_customer['payment_mode'] == 'Cash')? '':'('.$sq_customer['bank_name'].'--'.$sq_customer['transaction_id'].')';
        $content .= '<tr>
                        <td>
                        <span style="padding:5px 0; border-bottom:1px dotted #ccc; float: left">
                            <span style="font-weight:bold">Thank you for your confirmation with deposit fees of '.$sq_customer['deposite'].' towards '.$app_name.' on '.get_date_user($sq_customer['payment_date']).' by '.$sq_customer['payment_mode'].' '.$bank_details.'</span>
                        </span>    
                        </td>
                    </tr>';
      }
      $content .= '
      <table style="width: 338px;margin: 40px auto;background: #fff;border: 1px solid #ccc;">
          <tr >
              <td style="border-bottom: 1px solid #e6e6e6;padding: 0;margin: 0;"><h3 style="color: #009898;text-align: center;margin: 0;background: #f5f7f7;text-transform: uppercase;font-weight: 300;padding: 10px 0;font-size: 24px;">your sign in ready!</h3></td>
          </tr>
          <tr>
              <td>
                  <table style="width: 100%;padding: 20px;">
                    <tr>
                        <td>
                            <span style="float: left;"><img src="'.BASE_URL.'/images/email/code-icon.jpg"></span>
                            <span style="color: #559ee8;background: #f5f7f7;width: 217px;font-size: 18px;padding: 4px 0 4px 20px;float: left;">'.$agent_code.'</span>
                        </td>            
                    </tr>
                    <tr>
                        <td>
                          <span style="float: left;"><img src="'.BASE_URL.'/images/email/name-icon.jpg"></span>
                          <span style="color: #559ee8;background: #f5f7f7;width: 217px;font-size: 18px;padding: 4px 0 4px 20px;float: left;">'.$username.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <span style="float: left;"><img src="'.BASE_URL.'/images/email/password-icon.jpg"></span>
                           <span style="color: #559ee8;background: #f5f7f7;width: 217px;font-size: 18px;padding: 4px 0 4px 20px;float: left;">'.$password.'</span>
                        </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="text-align: center;background: #4bbba4;padding: 3px;">
                          <a href="'.$link.'" style="text-decoration: none;text-transform: uppercase;color: #fff;font-size: 19px;font-weight: 500;display: block;">Go for it!</a>
                      </td>
                    </tr>
                  </table>
              </td>
          </tr>
      </table>
      ';
     
    global $model;
    $subject = 'B2B Customer Login : '.$app_name;
    $model->app_email_send('105',$email_id, $content,$subject,'');
   }

   function acknowledgement_mail_Send($email_id){
       
    global $model,$app_name;
    $subject = 'B2B Portal Registration Request! : '.$app_name;
    $model->app_email_send('104',$email_id,'','');
   }
   function rejection_mail_Send($email_id){

    global $model,$app_name;
    $subject = 'B2B Portal Registration Request! : '.$app_name;
    $model->app_email_send('112',$email_id,'','');
   }
}