<?php 

class supplier_package_master{

public function package_save()
{

	$city_id = $_POST['city_id'];
	$supplier_id = $_POST['supplier_id'];
	$supplier_name = $_POST['supplier_name'];
    $active_flag = $_POST['active_flag'];
	$photo_upload_url = $_POST['photo_upload_url'];
	$valid_from = $_POST['valid_from'];
	$valid_to = $_POST['valid_to'];
	
	$created_at = date('Y-m-d H:i:s');
	$valid_from = get_date_db($valid_from);
	$valid_to = get_date_db($valid_to);

	$sq_max = mysql_fetch_assoc(mysql_query("select max(package_id) as max from supplier_packages"));

	$id = $sq_max['max'] + 1;



	$sq_service = mysql_query("insert into supplier_packages (package_id, city_id, supplier_type, name,active_flag, image_upload_url,valid_from,valid_to,created_at) values ('$id', '$city_id', '$supplier_id','$supplier_name','$active_flag','$photo_upload_url','$valid_from','$valid_to',  '$created_at')");

	if($sq_service){

		echo "Supplier Packages has been successfully saved.";

		exit;

	}

	else{

		echo "error--Sorry, Package not saved!";

		exit;

	}

}



public function package_update()

{

	$package_id = $_POST['package_id'];
	$city_id = $_POST['city_id'];
	$supplier_id = $_POST['supplier_id'];
	$supplier_name = $_POST['supplier_name'];
    $active_flag = $_POST['active_flag'];
    $valid_from = $_POST['valid_from'];
	$valid_to = $_POST['valid_to'];
	
	$valid_from = get_date_db($valid_from);
	$valid_to = get_date_db($valid_to);

	$sq_service = mysql_query("update supplier_packages set city_id='$city_id', supplier_type='$supplier_id', name='$supplier_name',valid_from ='$valid_from',valid_to='$valid_to',active_flag = '$active_flag' where package_id='$package_id'");
	if($sq_service){

		echo "Supplier Packages has been successfully updated.";

		exit;

	}

	else{

		echo "error--Sorry, Package not updated!";

		exit;

	}

}

public function package_img_update()

	{

		$img_upload_url = $_POST['img_upload_url'];

		$package_id = $_POST['package_id'];

		$sq_service = mysql_query("update supplier_packages set image_upload_url='$img_upload_url' where package_id='$package_id'");
	} 

}

?>