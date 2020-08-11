<?php 
include_once('../../../model/model.php');
require("../../../classes/convert_amount_to_word.php");

define('FPDF_FONTPATH','../../../classes/fpdf/font/');
require('../../../classes/mc_table.php');
$_SESSION['generated_by'] = $app_name;


$booking_id = $_GET['booking_id'];
$sq_booking = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));
$no_of_car = ceil($sq_booking['total_pax']/$sq_booking['capacity']);
$booking_date = $sq_booking['created_at'];
$yr = explode("-", $booking_date);
$year =$yr[0];
if($sq_booking['travel_type']=='Outstation'){
	$travel_date =  date('d-m-Y H:i:s', strtotime($sq_booking['traveling_date']));
}else{
	$travel_date = date('d-m-Y H:i:s', strtotime($sq_booking['from_date'])).'-'.date('d-m-Y H:i:s', strtotime($sq_booking['to_date']));
}
 if($sq_booking['travel_type']=='Local'){
	$place_to_visit =$sq_booking['local_places_to_visit'];
 }else{
	$place_to_visit =$sq_booking['places_to_visit'];
 }

 $basic_amount = $sq_booking['basic_amount']+$sq_booking['markup_cost_subtotal'];

$sq_vendor = mysql_fetch_assoc(mysql_query("select * from car_rental_vendor where vendor_id='$sq_booking[vendor_id]'"));

$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_booking[customer_id]'"));

if($sq_customer['type']=='Corporate'){
	$customer_name = $sq_customer['company_name'];
}else{
	$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
}

$pdf=new PDF_MC_Table();
$pdf->addPage();

$pdf->SetFont('Arial','',12);
$pdf->SetXY(0,0);
$pdf->Cell( 100, 12, $pdf->Image($admin_logo_url,10,5,60,25), 10, 0, 'C', false );

$pdf->line(10,31,200,31);

$pdf->SetFont('Arial','',9);
$pdf->SetXY(10, 30);
$pdf->MultiCell(100, 8,$app_address);

$pdf->SetXY(110, 30);
$pdf->MultiCell(45, 8,"Phone: ".$app_contact_no);

$pdf->SetXY(145, 30);
$pdf->MultiCell(70, 8,"Email: ". $app_email_id);

$pdf->SetFont('Arial','',12);
$y_pos = $pdf->getY()+10;
$pdf->setXY(10, $y_pos);
$pdf->Cell(190, 8, 'Car Rental Booking',1, 0, 'C');

$pdf->SetFont('Arial','',10);

$y_pos = $pdf->getY()+12;
$pdf->setXY(150, $y_pos);
$pdf->Cell(100, 7, 'Booking Date : '.get_date_user($sq_booking['created_at']));
$pdf->Line(150, $y_pos+7, 200, $y_pos+7);

$y_pos = $pdf->getY()+9;
$pdf->setXY(150, $y_pos);
$pdf->Cell(100, 7, 'Booking ID : '.get_car_rental_booking_id($booking_id,$year));
$pdf->Line(150, $y_pos+7, 200, $y_pos+7);


$y_pos = $pdf->getY()+12;
$pdf->setXY(10, $y_pos);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(190, 8, 'General Information',1, 0, 'C');

$y_pos = $pdf->getY()+8;
$pdf->SetFont('Arial','',9);
$pdf->setXY(10, $y_pos);
$pdf->SetWidths(array(30,65,30,65));
$pdf->Row(array('Customer Name', $customer_name, 'Total Pax', $sq_booking['total_pax']));
$pdf->Row(array('Mobile No', $sq_customer['contact_no'], 'Email ID', $sq_customer['email_id']));
$pdf->Row(array('Travelling Date',$travel_date, 'Days Of Travel', $sq_booking['days_of_traveling']));

$pdf->SetWidths(array(30,160));
$pdf->Row(array('Total vehicle', $no_of_car));
$pdf->Row(array('Passenger', $sq_booking['pass_name']));
$pdf->Row(array('Address', $sq_customer['address']));
$pdf->Row(array('Places To Visit', $place_to_visit));
$pdf->Row(array('Vendor Name', $sq_vendor['vendor_name']));

$y_pos = $pdf->getY()+5;
$pdf->SetFont('Arial','B',9);
$pdf->setXY(10, $y_pos);
$pdf->Cell(190, 8, 'Costing Information',1, 0, 'C');

$pdf->SetFont('Arial','',9);
$y_pos = $pdf->getY()+8;
$pdf->setXY(10, $y_pos);
$pdf->SetWidths(array(30,65,30,65));

$pdf->Row(array('Starting KM',' ' , 'Ending Km', $sq_booking['rate_per_km']));
$pdf->Row(array('Extra KM',' '));
$pdf->Row(array(get_tax_name().' (%)', $sq_booking['service_tax'],get_tax_name().' Amount', $sq_booking['service_tax_subtotal']));
$pdf->Row(array('Total Amount', $basic_amount ,'Extra Km Rate', $sq_booking['extra_km']));
$pdf->Row(array('Extra Hr Rate', $sq_booking['actual_cost'],'Driver Allowance', $sq_booking['driver_allowance']));

$pdf->SetWidths(array(30,65,30,65));
$pdf->Row(array('Permit Charges', $sq_booking['permit_charges'],'Toll & Parking', $sq_booking['toll_and_parking']));
$pdf->Row(array('State Entry Tax', $sq_booking['state_entry_tax']));

$pdf->SetFont('Arial','B',9);
$pdf->SetWidths(array(30,160));
$pdf->Row(array('Total', $sq_booking['total_fees'], ''));

$y_pos = $pdf->getY()+5;
$pdf->SetFont('Arial','B',9);
$pdf->setXY(10, $y_pos);
$pdf->Cell(190, 8, 'Vehicle Information',1, 0, 'C');

$y_pos = $pdf->getY()+8;
$pdf->SetFont('Arial','',9);
$pdf->setXY(10, $y_pos);
$pdf->SetWidths(array(20,30,30,40, 40, 30));
$pdf->Row(array('Sr. No', 'Vehicle Name', 'Vehicle No', 'Driver Name', 'Mobile No', 'Type'));

$count = 0;
// $sq_vehicle_entries = mysql_query("select * from car_rental_booking_vehicle_entries where booking_id='$booking_id'");
// while($row_vehicle = mysql_fetch_assoc($sq_vehicle_entries)){

	$count++;

	$sq_vehicle = mysql_fetch_assoc(mysql_query("select * from car_rental_vendor_vehicle_entries where vehicle_id='$row_vehicle[vehicle_id]'"));

	$pdf->Row(array($count, $sq_booking['vehicle_name'], $sq_vehicle['vehicle_no'], $sq_vehicle['vehicle_driver_name'], $sq_vehicle['vehicle_mobile_no'], $sq_vehicle['vehicle_type']));

// }


$pdf->Output();
?>
