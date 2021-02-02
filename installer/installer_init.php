<?php
$secret_key = "secret_key_for_iTours";
$product_name = $_POST['product_name'];
$database_name = $_POST['database_name'];
$empty_setup = $_POST['empty_setup'];
$country = $_POST['country'];
$setup_type = $_POST['setup_type'];
$creator_name = $_POST['creator_name'];

$source_db = "v7";
$source = "../../V7";
$destination = '../Projects/'.$product_name;

$table_exclude = array('state_and_cities', 'user_assigned_roles', 'roles', 'role_master', 'travel_station_master', 'bus_master', 'tour_budget_type', 'bank_name_master', 'bank_list_master', 'transport_agency_bus_master', 'city_master', 'currency_name_master', 'vendor_type_master', 'estimate_type_master', 'airport_list_master', 'references_master', 'country_state_list', 'country_list_master','email_template_master','gallary_master','destination_master','tax_type_master','airline_master','airport_master','visa_crm_master','visa_type_master','sac_master','state_master','generic_count_master','office_expense_type','branch_assign','ledger_master','group_master','head_master','subgroup_master','tax_country_master','cms_master','cms_master_entries','fixed_asset_master','meal_plan_master','modulewise_video_master','meal_plan_master','room_category_master','hotel_type_master','b2b_settings','b2b_settings_second','tax_conditions','other_charges_master', 'ticket_master_airfile', 'ticket_entries_airfile', 'ticket_trip_entries_airfile','video_itinerary_master');

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

//Truncate uploads directory
$path = $destination."/uploads";
empty_dir($path);

//Truncate download directory
$path = $destination."/download/*";

$files = glob($path); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}

//Delete DB folder
$dirPath = $destination."/db/*";
$files = glob($dirPath); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}
$dirPath = $destination."/db";
deleteDirectory($dirPath);

// //Delete GIT folder
$dirPath = $destination."/.git/*";
$files = glob($dirPath); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}
$dirPath = $destination."/.git";
empty_dir($dirPath);
deleteDirectory($dirPath);

//=========================Deleting Unwanted folders================================//


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
                $query = $conn->query("truncate $table_name");     
                if(!$query){
                    echo $query->error;
                }
            }            
        }   
    }

    $query = $conn->query("delete from user_assigned_roles where role_id in ('2', '3')");
    $query = $conn->query("delete from references_master where reference_id not in ('1', '2', '3','4','5','6','7','8')");
    $query = $conn->query("delete from role_master where role_id not in ('1', '2', '3','4','5','6','7')");
    $query = $conn->query("delete from office_expense_type where expense_type_id >= '21'");
	$query = $conn->query("delete from roles where id!='1'");
    $query = $conn->query("delete from ledger_master where ledger_id >= '230'");
    $query = $conn->query("delete from group_master where group_id >= '22'");
    $query = $conn->query("delete from head_master where head_id >= '14'");
    $query = $conn->query("delete from subgroup_master where subgroup_id >= '112'");
    $query = $conn->query("delete from gallary_master where entry_id >= '759'");
    $query = $conn->query("delete from sac_master where entry_id >= '14'");
    $query = $conn->query("delete from visa_type_master where entry_id >= '12'");

    $admin_username = strtolower($product_name);
    $admin_username = explode(' ', $admin_username);
    $admin_username = implode(' ', $admin_username);
    $admin_password = $admin_username.rand(1000,9999);
    //Encrypt username and password
    $admin_username = rtrim(
        base64_encode(
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_256,
                $secret_key, $admin_username, 
                MCRYPT_MODE_ECB, 
                mcrypt_create_iv(
                    mcrypt_get_iv_size(
                        MCRYPT_RIJNDAEL_256, 
                        MCRYPT_MODE_ECB
                    ), 
                    MCRYPT_RAND)
                )
            ), "\0"
        );
    $admin_password = rtrim(
        base64_encode(
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_256,
                $secret_key, $admin_password, 
                MCRYPT_MODE_ECB, 
                mcrypt_create_iv(
                    mcrypt_get_iv_size(
                        MCRYPT_RIJNDAEL_256, 
                        MCRYPT_MODE_ECB
                    ), 
                    MCRYPT_RAND)
                )
            ), "\0"
        );

    $today_date = date('Y-m-d H:i');
    $generic_query = $conn->query("update generic_count_master set a_enquiry_count = '0', a_temp_enq_count='0', a_task_count='0', a_temp_task_count='0',b_enquiry_count = '0', b_temp_enq_count='0', b_task_count='0', b_temp_task_count='0', setup_country_id='$country',a_leave_count='0', setup_type='$setup_type', setup_creator='$creator_name', setup_created_at='$today_date' where id='1'");

    $generic_query = $conn->query("update app_settings set quot_format = '4', quot_img_url='http://itourscloud.com/quotation_format_images/Portrait Creative/1.jpg' where setting_id='1'");
    $generic_query = $conn->query("UPDATE `b2b_settings_second` SET `entry_id`='1',`col1`='',`col2`='',`col3`='',`terms_cond`='',`privacy_policy`='',`cancellation_policy`='',`refund_policy`='',`careers_policy`='',`footer_strip`='' WHERE entry_id='1'");
    
    $sq_user = $conn->query("update roles set user_name='$admin_username', password='$admin_password' where id='1'"); 
     
    
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