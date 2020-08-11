<?php 

class paid_service_master{



public function service_save()
{

	$city_id = $_POST['city_id'];	
	$service_name = mysql_real_escape_string($_POST['service_name']);
	$adult_cost = $_POST['adult_cost'];
	$child_cost = $_POST['child_cost'];
	$active_flag = $_POST['active_flag'];
	$description = $_POST['description'];
	$photo_upload_url = $_POST['photo_upload_url'];

	$created_at = date('Y-m-d H:i:s');
	$sq_count = mysql_num_rows(mysql_query("select service_id from itinerary_paid_services where service_name='$service_name' and city_id='$city_id'"));

	if($sq_count>0){

		echo "error--Sorry, Excursion name already exists!";

		exit;

	}



	$sq_max = mysql_fetch_assoc(mysql_query("select max(service_id) as max from itinerary_paid_services"));
	$service_id = $sq_max['max'] + 1;


	$description = addslashes($description);
	$sq_service = mysql_query("insert into itinerary_paid_services (service_id, city_id, service_name, adult_cost,child_cost,active_flag, created_at,description,image_upload_url) values ('$service_id', '$city_id', '$service_name','$adult_cost','$child_cost','$active_flag',  '$created_at','$description','$photo_upload_url')");

	if($sq_service){

		echo "Excursion has been successfully saved.";

		exit;

	}

	else{

		echo "error--Sorry, Excrusion not saved!";

		exit;

	}

}



public function service_update()

{

	$service_id = $_POST['service_id'];
	$city_id = $_POST['city_id'];
	$service_name = mysql_real_escape_string($_POST['service_name']);
	$adult_cost = $_POST['adult_cost'];
	$child_cost = $_POST['child_cost'];
	$active_flag = $_POST['active_flag'];
	$description = $_POST['description'];

	$sq_count = mysql_num_rows(mysql_query("select service_id from itinerary_paid_services where service_name='$service_name' and city_id='$city_id' and service_id!='$service_id'"));

	if($sq_count>0){

		echo "error--Sorry, Excrusion name already exists!";

		exit;

	}
	$description = addslashes($description);
	$sq_service = mysql_query("update itinerary_paid_services set city_id='$city_id', service_name='$service_name', adult_cost='$adult_cost',child_cost='$child_cost',active_flag = '$active_flag',description ='$description' where service_id='$service_id'");
	if($sq_service){

		echo "Excursion has been successfully updated.";

		exit;

	}

	else{

		echo "error--Sorry, Excrusion not updated!";

		exit;

	}

}

public function service_img_update()
{

	$img_upload_url = $_POST['img_upload_url'];

	$service_id = $_POST['service_id'];

	$sq_service = mysql_query("update itinerary_paid_services set image_upload_url='$img_upload_url' where service_id='$service_id'");
}

public function exc_csv_save(){
    $vendor_csv_dir = $_POST['vendor_csv_dir'];
    $base_url=$_POST['base_url'];
    $flag = true;

    $vendor_csv_dir = explode('uploads', $vendor_csv_dir);
    $vendor_csv_dir = BASE_URL.'uploads'.$vendor_csv_dir[1];

    begin_t();
    $count = 1;
    $validCount=0;
    $invalidCount=0;
    $unprocessedArray=array();
    $arrResult  = array();
    $handle = fopen($vendor_csv_dir, "r");
    if(empty($handle) === false) {

        while(($data = fgetcsv($handle, ",")) !== FALSE){
            if($count == 1) { $count++; continue; }
            if($count>0){
                
				$sq_max = mysql_fetch_assoc(mysql_query("select max(service_id) as max from itinerary_paid_services"));
				$service_id = $sq_max['max'] + 1;

				$city_id = $data[0];
				$exc_name = $data[1];
				$adult_cost = $data[2];
				$child_cost = $data[3];
				$description = $data[4];
				$created_at = date('Y-m-d H:i:s');
				$downloaded_at = date('Y-m-d');
						if(preg_match('/^[0-9]*$/', $city_id) && (!empty($city_id)) && (!empty($exc_name)))
						{
							$sq_exc_count = mysql_num_rows(mysql_query("select * from itinerary_paid_services where city_id='$city_id' and service_name='$exc_name'"));
							if($sq_exc_count==0){
								$validCount++;
								$sq_service = mysql_query("insert into itinerary_paid_services (service_id, city_id, service_name, adult_cost,child_cost,active_flag, created_at,description) values ('$service_id', '$city_id', '$exc_name','$adult_cost','$child_cost','Active','$created_at','$description')");
							}
							else{
								$invalidCount++;
								array_push($unprocessedArray, $data);
							}
					}
					else{
						$invalidCount++;
						array_push($unprocessedArray, $data);
					}
            }
            $count++;
        }
        fclose($handle);
         if(isset($unprocessedArray) && !empty($unprocessedArray))
        {
          $filePath='../../download/unprocessed_excursion_records'.$downloaded_at.'.csv';
          $save = preg_replace('/(\/+)/','/',$filePath);
          $downloadurl='../../download/unprocessed_excursion_records'.$downloaded_at.'.csv';
          header("Content-type: text/csv ; charset:utf-8");
					header("Content-Disposition: attachment; filename=file.csv");
					header("Pragma: no-cache");
					header("Expires: 0");
					$output = fopen($save, "w");
					fputcsv($output, array('city_id' , 'Excursion Name' , 'Adult Cost' , 'Child Cost' ,'Description'));
				
					foreach($unprocessedArray as $row){
          	fputcsv($output, $row);
          }
          fclose($output);
          echo "<script> window.location ='$downloadurl'; </script>";  
        }
    }
    if($flag){
      commit_t();
      if($validCount > 0){
          echo  $validCount." records successfully imported<br>
          ".$invalidCount." records are failed.";
      }
      else{
        echo " No Excursion information imported";
      }
      exit;
    }
    else{
      rollback_t();
      exit;
    }
}

}

?>