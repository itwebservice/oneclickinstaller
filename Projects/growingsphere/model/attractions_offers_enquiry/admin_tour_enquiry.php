<?php 
include_once('../model.php');

$name=$_POST['name']; 
$mobile_no=$_POST['mobile_no']; 
$landline_no=$_POST['landline_no']; 
$email_id=$_POST['email_id']; 
$tour_name=$_POST['tour_name']; 
$travel_from_date=$_POST['travel_from_date']; 
$travel_to_date=$_POST['travel_to_date']; 
$budget=$_POST['budget']; 
$total_adult=$_POST['total_adult']; 
$total_children=$_POST['total_children']; 
$total_infant=$_POST['total_infant']; 
$reference_id=$_POST['reference_id']; 
$enquiry_spec=$_POST['enquiry_spec']; 
$hotel_type=$_POST['hotel_type']; 

global $app_name,$app_email_id;
global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

$content = '
			<table style="padding:0 30px; width:100%">
			    <tr>
			    	<p>Refer new enquiry details.</p>
			    </tr>	
					<tr>
						<td>
							<p>Customer Name : <strong>'.$name.'</strong></p>
						</td>
						<td>						
							<p>Mobile No : <strong>'.$mobile_no.'</strong></p>
						</td>
					</tr>
					<tr>
					   	<td>    
							<p>Email ID : <strong>'.$email_id.'</strong></p>
						</td>
						<td>
					   	    <p>Whatsapp No : <strong>'.$landline_no.'</strong></p>
					   	</td>
					</tr>
					<tr>
						<td>	
							<p>Interested Tour: <strong>'.$tour_name.'</strong></p>
						</td>
						<td>
							<p>Travel From: <strong>'.$travel_from_date.'</strong></p>
						</td>
					</tr>
					<tr>
					   <td>
							<p>Travel To : <strong>'.$travel_to_date.'</strong></p>
						</td>
						<td>
							<p>Budget: <strong>'.$budget.'</strong></p>
						</td>
					</tr>
					<tr>
						<td>	
							<p>Total Adult: <strong>'.$total_adult.'</strong></p>
						</td>
						<td>
							<p>Total Children: <strong>'.$total_children.'</strong></p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Total Infant: <strong>'.$total_infant.'</strong></p>
						</td>
						<td>
							<p>Hotel Type: <strong>'.$hotel_type.'</strong></p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Reference: <strong>'.$reference_id.'</strong></p>
						</td>
						<td>
							<p>Enq. Specification: <strong>'.$enquiry_spec.'</strong></p>
						</td>
					</tr>				
			</table>
			
';
 

$subject = "New enquiry information received! : ".$name;
$model->app_email_master($app_email_id, $content, $subject,'1');
echo "Thank you for your interest. Our team will get back to you soon.";
?>