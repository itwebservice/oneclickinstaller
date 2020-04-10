<?php 
$flag = true;
class quotation_reply_master_save{

public function quotation_reply_save()
{	

	$transport_cost = $_POST['transport_cost'];
	$total_cost = $_POST['total_cost'];
	$enquiry_spec = $_POST['enquiry_spec'];
	$request_id = $_POST['request_id'];
	$supplier_id = $_POST['supplier_id'];
	$created_by = $_POST['created_by'];
	$currency_code = $_POST['currency_code'];
	$enquiry_id =$_POST['enquiry_id'];

	$created_at = date('Y-m-d');

	begin_t();
	$sq_count = mysql_num_rows(mysql_query("select * from vendor_reply_master where request_id='$request_id' and supplier_id='$supplier_id' and quotation_for='Transport Vendor' "));
	if($sq_count!=0){
		echo "Quotation reply already send!!!";
	}
	else{
	$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from vendor_reply_master"));
	$id = $sq_max['max']+1;
	$enquiry_spec = addslashes($enquiry_spec);
	$sq_request = mysql_query("insert into vendor_reply_master(id,request_id, quotation_for, supplier_id, transport_cost, total_cost, currency_code, enquiry_spec , created_at,created_by,enquiry_id) values ('$id','$request_id', 'Transport Vendor', '$supplier_id', '$transport_cost', '$total_cost', '$currency_code', '$enquiry_spec' , '$created_at', '$created_by','$enquiry_id')");
	if($sq_request){


		if($GLOBALS['flag']){
			commit_t();
			
			echo "Quotation Reply sent!";
			$this->quotation_reply_email($request_id,$transport_cost,$total_cost,$currency_code,$enquiry_spec,$created_by,$created_at,'Transport','0','0','0',$enquiry_id);
			exit;
		}
		else{
			rollback_t();
		}

	}
	else{
		echo "error--Sorry, Quotation Reply not sent!";
		rollback_t();		
		exit;
	}
 }
}
public function hotel_quotation_reply_save()
{	

	$hotel_cost = $_POST['hotel_cost'];
	$total_cost = $_POST['total_cost'];
	$enquiry_spec = $_POST['enquiry_spec'];
	$request_id = $_POST['request_id'];
	$supplier_id = $_POST['supplier_id'];
	$created_by = $_POST['created_by'];
	$currency_code = $_POST['currency_code'];
	$enquiry_id = $_POST['enquiry_id'];
	$created_at = date('Y-m-d H:i:s');
	begin_t();
	$enquiry_spec = addslashes($enquiry_spec);

	$sq_count = mysql_num_rows(mysql_query("select * from vendor_reply_master where request_id='$request_id' and supplier_id='$supplier_id'and quotation_for='Hotel Vendor' "));
	if($sq_count!=0){
		echo "Quotation reply already send!!!";
	}
	else{
	$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from vendor_reply_master"));
	$id = $sq_max['max']+1;
	$q1 = "insert into vendor_reply_master(id ,request_id, quotation_for, supplier_id, hotel_cost, total_cost, currency_code, enquiry_spec , created_at, created_by,enquiry_id) values ('$id','$request_id', 'Hotel Vendor', '$supplier_id', '$hotel_cost', '$total_cost', '$currency_code', '$enquiry_spec' , '$created_at','$created_by','$enquiry_id')";
	$sq_request = mysql_query($q1);
	if($sq_request){

		if($GLOBALS['flag']){
			commit_t();
			echo "Quotation Reply sent!";
			 $this->quotation_reply_email($request_id,$hotel_cost,$total_cost,$currency_code,$enquiry_spec,$created_by,$created_at,'Hotel','0','0','0',$enquiry_id);
			exit;
		}
		else{
			rollback_t();
		}

	}
	else{
		echo "error--Sorry, Quotation Reply not sent!";
		rollback_t();		
		exit;
	}
	}
}

public function dmc_quotation_reply_save()
{	
	$transport_cost = $_POST['transport_cost'];
	$hotel_cost = $_POST['hotel_cost'];
	$excursion_cost = $_POST['excursion_cost'];
	$visa_cost = $_POST['visa_cost'];
	$total_cost = $_POST['total_cost'];
	$enquiry_spec = $_POST['enquiry_spec'];
	$request_id = $_POST['request_id'];
	$supplier_id = $_POST['supplier_id'];
	$created_by = $_POST['created_by'];
	$currency_code = $_POST['currency_code'];
	$enquiry_id = $_POST['enquiry_id'];
	$created_at = date('Y-m-d H:i:s');

	begin_t();
	$enquiry_spec = addslashes($enquiry_spec);
	$sq_count = mysql_num_rows(mysql_query("select * from vendor_reply_master where request_id='$request_id' and supplier_id='$supplier_id'and quotation_for='DMC Vendor' "));
	if($sq_count!=0){
		echo "Quotation reply already send!!!";
	}
	else{
	$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from vendor_reply_master"));
	$id = $sq_max['max']+1;
	$sq_request = mysql_query("insert into vendor_reply_master(id ,request_id, quotation_for, supplier_id, transport_cost, excursion_cost, visa_cost, hotel_cost, total_cost, currency_code, enquiry_spec , created_at, created_by,enquiry_id) values ('$id','$request_id', 'DMC Vendor', '$supplier_id', '$transport_cost', '$excursion_cost', '$visa_cost', '$hotel_cost', '$total_cost', '$currency_code', '$enquiry_spec' , '$created_at','$created_by','$enquiry_id')");
	if($sq_request){


		if($GLOBALS['flag']){
			commit_t();
			//$this->email_send($request_id);
			echo "Quotation Reply sent!";
		 $this->quotation_reply_email($request_id,$hotel_cost,$total_cost,$currency_code,$enquiry_spec,$created_by,$created_at,'DMC',$transport_cost,$excursion_cost,$visa_cost,$enquiry_id);
			exit;
		}
		else{
			rollback_t();
		}

	}
	else{
		echo "error--Sorry, Quotation Reply not sent!";
		rollback_t();		
		exit;
	}
  }	
}

public function quotation_reply_email($request_id,$hotel_cost,$total_cost,$currency_code,$enquiry_spec,$created_by,$created_at,$for,$transport_cost,$excursion_cost,$visa_cost,$enquiry_id){
  global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
	$date = $created_at;
	$yr = explode("-", $date);
	$year =$yr[0];
    if($for=='Transport'){
		$sq_request=mysql_fetch_assoc(mysql_query("select * from vendor_reply_master where request_id='$request_id' "));
	    $sq_count = mysql_fetch_assoc(mysql_query("select * from vendor_request_master where request_id='$sq_request[request_id]' "));
	  
		  $content = '
		  <tr>
		    <td>
		      <table style="width:100%">
		        <tr>
		          <td>Request ID : '.$request_id.'. </td>
		        </tr>
		        <tr>
		          <td>Enquiry ID : '.$sq_count['enquiry_id'].'. </td>
		        </tr>
		         <tr>
		          <td>Transport Cost : '.$hotel_cost .'.</td>
		        </tr>
		       
		        <tr>
		          <td>Total Cost : '.$total_cost.'. </td>
		        </tr>
		        <tr>
		          <td>Currency Code : '.$currency_code.'. </td>
		        </tr>
		        <tr>
		          <td>Other Comments : '.$enquiry_spec.'. </td>
		        </tr>
		        <tr>
		          <td>Date : '.$created_at.'. </td>
		        </tr>
		        <tr>
		          <td>Created By : '.$created_by.'. </td>
		        </tr>

		      </table>  
		      </td>
		    </tr>
		  ';

	}
	if($for=='Hotel'){
	   $sq_request=mysql_fetch_assoc(mysql_query("select * from vendor_reply_master where request_id='$request_id' "));
	   $sq_count = mysql_fetch_assoc(mysql_query("select * from vendor_request_master where request_id='$sq_request[request_id]' "));
	  
	   $content = '
	   <tr>
	    <td>
	      <table style="width:100%">
	        <tr>
	          <td>Request ID : '.$request_id.'. </td>
	        </tr>
	         <tr>
	          <td>Enquiry ID : '.$sq_count['enquiry_id'].'. </td>
	        </tr>
	        <tr>
	          <td>Hotel Cost : '.$hotel_cost .'.</td>
	        </tr>
	        <tr>
	          <td>Total Cost : '.$total_cost.'. </td>
	        </tr>
	        <tr>
	          <td>Currency Code : '.$currency_code.'. </td>
	        </tr>
	        <tr>
	          <td>Other Comments : '.$enquiry_spec.'. </td>
	        </tr>
	        <tr>
	          <td>Date : '.$created_at.'. </td>
	        </tr>
	        <tr>
	          <td>Created By : '.$created_by.'. </td>
	        </tr>

	      </table>  
	      </td>
	    </tr>
	  ';
	}
	if($for=='DMC'){
		$sq_request=mysql_fetch_assoc(mysql_query("select * from vendor_reply_master where request_id='$request_id' "));
		$sq_count = mysql_fetch_assoc(mysql_query("select * from vendor_request_master where request_id='$sq_request[request_id]' "));
			  
		  $content = '
		  <tr>
		    <td>
		      <table style="width:100%">
		        <tr>
		          <td>Request ID : '.$request_id.'. </td>
		        </tr>
		        <tr>
		          <td>Enquiry ID : '.$sq_count['enquiry_id'].'. </td>
		        </tr>
		         <tr>
		          <td>Transport Cost : '.$transport_cost .'.</td>
		        </tr>
		        <tr>
		          <td>Excursion Cost : '.$excursion_cost.'. </td>
		        </tr>
		        <tr>
		          <td>Visa Code : '.$visa_cost.'. </td>
		        </tr>
		        <tr>
		          <td>Hotel Cost : '.$hotel_cost .'.</td>
		        </tr>
		        <tr>
		          <td>Total Cost : '.$total_cost.'. </td>
		        </tr>
		        <tr>
		          <td>Currency Code : '.$currency_code.'. </td>
		        </tr>
		        <tr>
		          <td>Other Comments : '.$enquiry_spec.'. </td>
		        </tr>
		        <tr>
		          <td>Date : '.$created_at.'. </td>
		        </tr>
		        <tr>
		          <td>Created By : '.$created_by.'. </td>
		        </tr>

		      </table>  
		      </td>
		    </tr>
		  ';


	}
	$subject = 'Supplier Quotation Reply (Enquiry ID : '.get_enquiry_id($sq_count['enquiry_id'],$year).' )';
  	global $model;
  	$model->app_email_send('24',$app_email_id, $content,$subject);
}

}

?>