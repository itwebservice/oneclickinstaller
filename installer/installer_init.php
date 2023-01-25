<?php
$secret_key = "secret_key_for_iTours";
$product_name = $_POST['product_name'];
$database_name = $_POST['database_name'];
$empty_setup = $_POST['empty_setup'];
$setup_type = $_POST['setup_type'];
$creator_name = $_POST['creator_name'];
$b2c = $_POST['b2c'];

$company_name = $_POST['company_name'];
$website = $_POST['website'];
$contact_no = $_POST['contact_no'];
$address = $_POST['address'];
$tax_name = $_POST['tax_name'];
$country = $_POST['country'];
$state = $_POST['state'];
$currency = $_POST['currency'];
$currency_rate = $_POST['currency_rate'];
$ffrom_date = $_POST['ffrom_date'];
$fto_date = $_POST['fto_date'];
$location = $_POST['location'];
$branch = $_POST['branch'];

$source_db = "v8";
$source = "../../v7-version-upgrade";
$destination = '../Projects/'.$product_name;

$table_exclude = array('state_and_cities', 'user_assigned_roles', 'roles', 'role_master', 'bus_master', 'tour_budget_type', 'bank_name_master', 'bank_list_master', 'transport_agency_bus_master', 'city_master', 'currency_name_master', 'vendor_type_master', 'estimate_type_master', 'airport_list_master', 'references_master', 'country_state_list', 'country_list_master','gallary_master','destination_master','airline_master','airport_master','visa_crm_master','visa_type_master','sac_master','state_master','generic_count_master','office_expense_type','branch_assign','ledger_master','group_master','head_master','subgroup_master','cms_master','cms_master_entries','fixed_asset_master','meal_plan_master','modulewise_video_master','meal_plan_master','room_category_master','hotel_type_master','b2b_settings','b2b_settings_second','vehicle_type_master','tax_conditions','other_charges_master', 'ticket_master_airfile', 'ticket_entries_airfile', 'ticket_trip_entries_airfile','video_itinerary_master','app_settings','b2b_transfer_master','itinerary_master','tcs_master','hotel_master','hotel_vendor_images_entries','custom_package_master','custom_package_program','custom_package_hotels','custom_package_transport','custom_package_images');

if (!file_exists($destination)){
    mkdir($destination, 0777, true);
}
else{
	echo "This Product name already exists.";
	exit;
}

function copy_directory($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                copy_directory($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}
copy_directory($source, $destination);

//=========================Deleting Unwanted folders================================//
function deleteDirectory($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object !="..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
    reset($objects);
    rmdir($dirPath);
    }
}
function empty_dir($path){

	$dir = new DirectoryIterator($path);
	foreach ($dir as $fileinfo) {
	    if ($fileinfo->isDir() && !$fileinfo->isDot()) {
	        $new_dir = $path.DIRECTORY_SEPARATOR.$fileinfo->getFilename();
	        deleteDirectory($new_dir);
	    }
	}
}

////////////////// //Truncate uploads directory //////////////////
$path = $destination."/uploads";
empty_dir($path);

///////////////// //Truncate download directory //////////////////
$path = $destination."/download/*";
$files = glob($path); // get all file names
foreach($files as $file){ // iterate files
    if(is_file($file))
    unlink($file); // delete file
}

/////////////////// // Delete DB folder /////////////////////////
$dirPath = $destination."/db/*";
$files = glob($dirPath); // get all file names
foreach($files as $file){ // iterate files
    if(is_file($file))
    unlink($file); // delete file
}
$dirPath = $destination."/db";
deleteDirectory($dirPath);

// ////////////////// //Delete GIT folder /////////////////////////
// $dirPath = $destination."/.git/*";
// $files = glob($dirPath); // get all file names
// foreach($files as $file){ // iterate files
//     if(is_file($file))
//     unlink($file); // delete file
// }
// $dirPath = $destination."/.git";
// empty_dir($dirPath);
// deleteDirectory($dirPath);

///////////// //Delete Tours_B2B folder/////////////////////////
if($setup_type!='4'){

    $dirPath = $destination."/Tours_B2B/*";
    $files = glob($dirPath); // get all file names
    foreach($files as $file){ // iterate files
    if(is_file($file))
        unlink($file); // delete file
    }
    $dirPath = $destination."/Tours_B2B";
    empty_dir($dirPath);
    deleteDirectory($dirPath);
}

//=========================Creating Databse start================================//
$conn = new mysqli("localhost", "root", "");
if($conn->connect_error){
	echo "Connection Failed:".$conn->connect_error;
	exit;
}
if($conn->query("create database $database_name")===TRUE){

}else{
	echo "Defined database already exists.";
	exit;
}
$conn->close();
//=========================Creating Databse end================================//

//=========================Creating tables start================================//
if (!$con = new mysqli('localhost', 'root', '', $source_db)) {
    die('An error occurred while connecting to the MySQL server!&lt;br&gt;&lt;br&gt;' . $con->connect_error);
}
$sq_list_table = $con->query("show tables");
while($row = $sq_list_table->fetch_assoc()){

	$table_name = $row['Tables_in_'.$source_db];
	$query = $con->query("create table $database_name.$table_name like $source_db.$table_name");
	$query = $con->query("insert into $database_name.$table_name select * from $source_db.$table_name");
}
$con->close();
//=========================Creating tables end================================//

$conn = new mysqli('localhost', 'root', '', $database_name);
if($conn->connect_error){
    echo "Connection Failed:".$conn->connect_error;
    exit;
}

if($empty_setup=="Yes"){
    //=========================Emptying tables data start================================//
    
    $sq_trucate_table = $conn->query('show tables');

    $sq_list_table = $conn->query("show tables");

    while($row = $sq_list_table->fetch_assoc()){

        $table_name = $row['Tables_in_'.$database_name];

        if( !in_array($table_name, $table_exclude) ){

            if($conn->query("SHOW TABLES LIKE '".$table_name."'")->num_rows==1) {
                $query1 = $conn->query("truncate $table_name");     
                if(!$query1){
                    echo $query1->error;
                }
            }            
        }   
    }

    $query = $conn->query("delete from user_assigned_roles where role_id in ('2', '3')");
    $query = $conn->query("delete from references_master where reference_id not in ('1', '2', '3','4','5','6','7','8','9','10','11','12')");
    $query = $conn->query("delete from role_master where role_id not in ('1', '2', '3','4','5','6','7')");
    $query = $conn->query("delete from office_expense_type where expense_type_id >= '21'");
	$query = $conn->query("delete from roles where id!='1'");
    // $query = $conn->query("delete from ledger_master where ledger_id >= '233'");
    $query = $conn->query("delete from group_master where group_id >= '22'");
    $query = $conn->query("delete from head_master where head_id >= '14'");
    $query = $conn->query("delete from subgroup_master where subgroup_id >= '114'");
    $query = $conn->query("delete from gallary_master where entry_id >= '1086'");
    $query = $conn->query("delete from sac_master where sac_id >= '13'");
    $query = $conn->query("delete from visa_type_master where visa_type_id >= '12'");
    
    // //////////////Ready data for hotel,packages START//////////////////////
    // HOTEL(// 1.hotel_master,2.hotel_vendor_images_entries)
    // PACKAGE TOUR
    // 1.custom_package_master, 2.custom_package_program, 3.custom_package_hotels, 4.custom_package_transport, 5.custom_package_images, 6.ledger_master
    $query = $conn->query("delete from hotel_master where hotel_id>='131'");
    $query = $conn->query("delete from hotel_vendor_images_entries where hotel_id>='131'");
    $query = $conn->query("delete from custom_package_master where package_id>='29'");

    $query = $conn->query("delete from custom_package_program where package_id>='29'");
    $query = $conn->query("delete from custom_package_hotels where package_id>='29'");
    $query = $conn->query("delete from custom_package_transport where package_id>='29'");
    $query = $conn->query("delete from custom_package_images where package_id>='29'");

    $query = $conn->query("delete from ledger_master where ledger_id >= '363'");
    // /////////////////////////END///////////////////////////////////////////
    $query = $conn->query("UPDATE `ledger_master` SET `balance`=0 WHERE 1");

    if($b2c == 'true'){
        $generic_query = $conn->query("update app_settings set b2c_flag = '1' where setting_id='1'");
    }else{
        $generic_query = $conn->query("update app_settings set b2c_flag = '0' where setting_id='1'");
    }

    $admin_username = strtolower($product_name);
    $admin_username = explode(' ', $admin_username);
    $admin_username = implode(' ', $admin_username);
    $admin_password = $admin_username.rand(1000,9999);
    //Encrypt username and password
    $key = "secret_key_for_iTours";
    // Store the cipher method
    $ciphering = "AES-128-CTR";
            
    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    // Non-NULL Initialization Vector for encryption
    $encryption_iv = '1234567891011121';

    // Use openssl_encrypt() function to encrypt the data
    $admin_username = openssl_encrypt($admin_username, $ciphering,
                $key, $options, $encryption_iv);
    $admin_password = openssl_encrypt($admin_password, $ciphering,
                $key, $options, $encryption_iv);


    $today_date = date('Y-m-d H:i');
    $generic_query = $conn->query("update generic_count_master set a_enquiry_count = '0', a_temp_enq_count='0', a_task_count='0', a_temp_task_count='0',b_enquiry_count = '0', b_temp_enq_count='0', b_task_count='0', b_temp_task_count='0', setup_country_id='$country',a_leave_count='0', setup_type='$setup_type', setup_creator='$creator_name', setup_created_at='$today_date' where id='1'");

    $generic_query = $conn->query("UPDATE `app_settings` SET `app_version`='',`app_email_id`='',`currency`='',`app_smtp_status`='',`app_smtp_host`='',`app_smtp_port`='',`app_smtp_password`='',`app_smtp_method`='',`app_contact_no`='',`app_landline_no`='',`service_tax_no`='',`tax_name`='',`app_address`='',`app_website`='',`app_name`='',`app_cin`='',`bank_acc_no`='',`acc_name`='',`bank_name`='',`bank_branch_name`='',`bank_ifsc_code`='',`bank_swift_code`='',`sms_username`='',`sms_password`='',`server_link`='',`server_username`='',`server_password`='',`policy_url`='',`state_id`='',`accountant_email`='',`tax_type`='',`tax_pay_date`='',`credit_card_charges`='',`quot_format`='',`quot_img_url`='',`ip_addresses`='',`transfer_service_time`='',`country`='' WHERE `setting_id`='1'");

    $generic_query = $conn->query("update app_settings set app_name='$company_name',app_website='$website',app_contact_no='$contact_no',app_address='$address',tax_name='$tax_name',country='$country',state_id='$state',currency='$currency',quot_format = '4', quot_img_url='http://itourscloud.com/quotation_format_images/Portrait Creative/1.jpg' where setting_id='1'");

    $generic_query = $conn->query("UPDATE `b2b_settings_second` SET `entry_id`='1',`col1`='',`col2`='',`col3`='',`terms_cond`='',`privacy_policy`='',`cancellation_policy`='',`refund_policy`='',`careers_policy`='',`footer_strip`='' WHERE entry_id='1'");
    
    $generic_query = $conn->query("INSERT INTO `roe_master`(`entry_id`, `currency_id`, `currency_rate`) VALUES ('1','$currency','$currency_rate')");

    $ffrom_datea = date('Y-m-d', strtotime($ffrom_date));
    $fto_datea = date('Y-m-d', strtotime($fto_date));
    $generic_query = $conn->query("INSERT INTO `financial_year`(`financial_year_id`, `from_date`, `to_date`, `active_flag`, `created_at`) VALUES ('1','$ffrom_datea','$fto_datea','Active','$today_date')");
    
    $today_date1 = date('Y-m-d');
    $generic_query = $conn->query("INSERT INTO `locations`(`location_id`, `location_name`, `active_flag`, `created_at`) VALUES ('1','$location','Active','$today_date1')");
    $generic_query = $conn->query("INSERT INTO `branches`(`branch_id`, `location_id`, `branch_name`,`contact_no`,`active_flag`, `created_at`,`state`) VALUES ('1','1','$branch','$contact_no','Active','$today_date1','$state')");

    $sq_user = $conn->query("update roles set user_name='$admin_username', password='$admin_password',emp_id='1' where id='1'");

    $sq_tcs = $conn->query("UPDATE `tcs_master` SET `tax_amount`='0',`calc`='0',`apply`='0' WHERE 1");

    $sq = $conn->query("insert into emp_master (emp_id, first_name, username, password, address,mobile_no, location_id, branch_id, role_id, active_flag,app_smtp_status) values ('1', '$company_name', '$admin_username', '$admin_password', '$address','$contact_no','1', '1', '1', 'Active','Yes')");

    $query = $conn->query("delete from b2b_transfer_master where entry_id>'5'");
    
    unlink($destination.'/view/cache_data.txt');
    $conn->close();
    //=========================Emptying tables data end================================//
}
else{
    $today_date = date('Y-m-d H:i');
    $generic_query = $conn->query("update generic_count_master set setup_country_id='$country', setup_type='$setup_type', setup_creator='$creator_name', setup_created_at='$today_date',invoice_format='Standard' where id='1'");
    $generic_query = $conn->query("update app_settings set quot_format = '4', quot_img_url='http://itourscloud.com/quotation_format_images/Portrait Creative/1.jpg' where setting_id='1'");
    $conn->close();
}
echo "New Product is created successfully.";

?>