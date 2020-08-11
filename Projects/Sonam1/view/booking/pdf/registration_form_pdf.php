<?php
include "../../../model/model.php";
require("../../../classes/convert_amount_to_word.php");

$tourwise_id=$_GET['booking_id'];

$tourwise_details = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$tourwise_id' "));

$tour_id=$tourwise_details['tour_id'];
$tour_group_id=$tourwise_details['tour_group_id'];
$traveler_group_id=$tourwise_details['traveler_group_id'];

$tour_name1 = mysql_fetch_assoc(mysql_query("select * from tour_master where tour_id= '$tour_id'"));
$tour_name = $tour_name1['tour_name'];

$tour_group1 = mysql_fetch_assoc(mysql_query("select from_date, to_date from tour_groups where group_id= '$tour_group_id'"));
$from_date = date("d-m-Y", strtotime($tour_group1['from_date']));
$to_date = date("d-m-Y", strtotime($tour_group1['to_date']));

$booking_date =  date("d-m-Y", strtotime($tourwise_details['form_date']));

$sq_total_mem = mysql_num_rows(mysql_query("select traveler_id from travelers_details where traveler_group_id='$tourwise_details[id]'"));

$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$tourwise_details[customer_id]'"));

$_SESSION['generated_by'] = 'booking_form';

define('FPDF_FONTPATH','../../../classes/fpdf/font/');
//require('../../classes/fpdf/fpdf.php');
require('../../../classes/mc_table.php');


$pdf=new PDF_MC_Table();
$pdf->AddPage();
$pdf->setTextColor(40,35,35);

$pdf->Image($booking_form,0,0,210,297);
 
$pdf->SetXY(0,0);

$pdf->Image($admin_logo_url, 30, 8, 46, 17);

$pdf->SetFont('Arial','B',13);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(158, 12);
$pdf->Cell(100, 5, 'BOOKING FORM');

$pdf->SetFont('Arial','',13);
$pdf->SetXY(28,83);
$pdf->Cell(100, 5, 'BOOKING DETAILS');

$pdf->SetFont('Arial','',20);
$pdf->SetTextColor(51,203,204);
$pdf->SetXY(30, 32);
$pdf->Cell(30, 8, strtoupper($app_name));

$pdf->SetFont('Arial','',8);
$pdf->setTextColor(40,35,35);
$pdf->SetXY(33, 46.7);
$pdf->Cell(30, 8, $app_address);

$pdf->SetFont('Arial','B',8);
$pdf->setXY(28, 47);
$pdf->cell(190, 7, "A ", 0, 0);
$pdf->setXY(155.5, 30.8);
$pdf->cell(192, 7, "E", 0, 0);
$pdf->setXY(155.5 , 38.5);
$pdf->cell(190, 7, "P ", 0, 0);
$pdf->setXY(155.5, 45.8);
$pdf->cell(190, 7, "L ", 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(160, 30.8);
$pdf->cell(190, 7, $app_email_id, 0, 0);

$pdf->setXY(160 , 38.5);
$pdf->cell(190, 7, $app_contact_no, 0, 0);

$pdf->setXY(160, 45.8);
$pdf->cell(190, 7, $app_landline_no, 0, 0);

$pdf->SetFont('Arial','B',9);
$pdf->setXY(30, 64);
$pdf->cell(190, 7, 'CONTACT PERSON', 0, 0);

$pdf->setXY(138, 64);
$pdf->cell(190, 7, 'CONTACT NO', 0, 0);

$pdf->setXY(28, 93);
$pdf->cell(190, 7, 'BOOKING ID', 0, 0);

$pdf->setXY(117.5, 93);
$pdf->cell(190, 7, 'DATE', 0, 0);

$pdf->setXY(28, 103);
$pdf->cell(190, 7, 'TOUR NAME', 0, 0);

$pdf->setXY(117, 103);
$pdf->cell(190, 7, 'TOUR DATE', 0, 0);

$pdf->setXY(28, 113);
$pdf->cell(190, 7, 'VISA', 0, 0);

$pdf->setXY(117, 113);
$pdf->cell(190, 7, 'INSURANCE', 0, 0);

$pdf->setXY(28, 124);
$pdf->cell(190, 7, 'TOTAL GUEST', 0, 0);

$pdf->setXY(117, 124);
$pdf->cell(190, 7, 'TOTAL ROOMS', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(70 , 64);
$pdf->cell(190, 7, $customer_name, 0, 0);

$pdf->setXY(174, 64);
$pdf->cell(190, 7, $sq_customer['contact_no'], 0, 0);

$pdf->setXY(63, 93);
$pdf->cell(190, 7, get_group_booking_id($tourwise_id), 0, 0);

$pdf->setXY(150, 93);
$pdf->cell(190, 7, $booking_date, 0, 0);

$pdf->setXY(63, 103);
$pdf->cell(190, 7, $tour_name, 0, 0);

$pdf->setXY(150, 103);
$pdf->cell(190, 7, $from_date.' To '.$to_date, 0, 0);

$visa_name= ($tourwise_details['visa_country_name']!="") ? $tourwise_details['visa_country_name']: NA;

$pdf->setXY(63, 113);
$pdf->cell(190, 7, $visa_name, 0, 0);

$insuarance_name= ($tourwise_details['insuarance_company_name']!="") ? $tourwise_details['insuarance_company_name']: NA;
 
$pdf->setXY(150, 113);
$pdf->cell(190, 7, $insuarance_name, 0, 0);

$pdf->setXY(63, 124);
$pdf->cell(190, 7, $sq_total_mem, 0, 0);

$pdf->setXY(150, 124);
$pdf->cell(190, 7, $tourwise_details['s_double_bed_room'], 0, 0);

$pdf->SetFillColor(51,203,204);
$pdf->rect(27,138,181,11,F);

$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(28,141.5);
$pdf->Cell(100, 5, 'PASSENGER DETAILS');

$pdf->SetDrawColor(211,211,211);
$pdf->SetFont('Arial','',9);
$pdf->setTextColor(40,35,35);
$pdf->SetY($pdf->GetY()+7.5); 

    if($pdf->GetY()+25>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($sidebar_strip,7,0,10,297);
    }
    $pdf->SetX(27);

    $pdf->SetFillColor(235);
    $pdf->rect(27,149, 181, 7, 'F');
    $pdf->SetFont('Arial','B',9);

    $pdf->SetWidths(array(80,30,30,41));
    $pdf->Row(array('Full Name', 'Gender', 'Age', 'DOB'));

    $sq_members = mysql_query("select * from travelers_details where traveler_group_id = '$tourwise_details[traveler_group_id]'");
    while($row_members = mysql_fetch_assoc($sq_members))
    {
        $pdf->SetX(27);
        $pdf->SetFont('Arial','',9);
        $pdf->Row(array($row_members['first_name'].' '.$row_members['middle_name'].' '.$row_members['last_name'], $row_members['gender'], $row_members['age'],  date("d-m-Y", strtotime($row_members['birth_date']))));
    }

$pdf->SetFillColor(51,203,204);
$y_pos1 = $pdf->getY();
$y_pos1+=5;
$pdf->rect(27,$y_pos1,181,11,F);

$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(255,255,255);
$y_pos1 = $pdf->getY();
$y_pos1+=7.5;
$pdf->SetXY(28,$y_pos1);
$pdf->Cell(100, 5, 'ACCOMMODATION');

$pdf->setTextColor(40,35,35);
$pdf->SetFont('Arial','',9);

$y_pos1 = $pdf->getY();
$y_pos1+=8.5;
$pdf->SetXY(27,$y_pos1);
$pdf->SetFillColor(235);
$pdf->rect(27,$y_pos1, 40, 8, 'F');
 $pdf->SetFont('Arial','B',9);
$pdf->MultiCell(40, 8, 'EXTRA BED', 1);

$pdf->SetFont('Arial','',9);
$pdf->SetXY(67, $y_pos1);
$pdf->MultiCell(50, 8, $tourwise_details['s_extra_bed'] , 1);
 
$pdf->SetXY(117, $y_pos1);
$pdf->SetFillColor(235);
$pdf->rect(117,$y_pos1, 40, 8, 'F');
$pdf->SetFont('Arial','B',9);
$pdf->MultiCell(40, 8, 'ON FLOOR', 1);

 $pdf->SetFont('Arial','',9);
$pdf->SetXY(157, $y_pos1);
$pdf->MultiCell(51, 8, $tourwise_details['s_on_floor'], 1);

//Train
$sq_train = mysql_num_rows(mysql_query("select tourwise_traveler_id from train_master where tourwise_traveler_id='$tourwise_id'"));
$train_count = 0;

if($sq_train>0)
{

$y_pos1 = $pdf->getY();
$y_pos1+=5;

$pdf->SetFillColor(51,203,204);
$pdf->rect(27,$y_pos1,181,11,F);

$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(28,$y_pos1+3);
$pdf->Cell(100, 5, 'TRAVEL-TRAIN');


$pdf->setTextColor(40,35,35);
$pdf->SetY($pdf->GetY()+8); 

if($pdf->GetY()+25>$pdf->PageBreakTrigger)
{
    $pdf->AddPage($pdf->CurOrientation);
    $pdf->Image($sidebar_strip,7,0,10,297);
}
$pdf->SetX(27);

$y_pos1 = $pdf->getY();

$pdf->SetFillColor(235);
$pdf->rect(27,$y_pos1, 181, 7, 'F');

$pdf->SetFont('Arial','B',9);
$pdf->SetWidths(array(35,35,30,15,15,20,31));
$pdf->Row(array('FROM', 'TO', 'TRAIN','SEATS','CLASS','PRIORITY','DATE/TIME'));

$sq_train_details = mysql_query("select * from train_master where tourwise_traveler_id='$tourwise_id'");
while($row_train_details = mysql_fetch_assoc($sq_train_details))
{
    $pdf->SetX(27);
    $pdf->SetFont('Arial','',9);
    $pdf->Row(array($row_train_details['from_location'], $row_train_details['to_location'], $row_train_details['train_no'], $row_train_details['seats'], $row_train_details['train_class'], $row_train_details['train_priority'],date("d-m-Y H:i", strtotime($row_train_details['date']))));
}
}

//Flight
$sq_air = mysql_num_rows(mysql_query("select tourwise_traveler_id from plane_master where tourwise_traveler_id='$tourwise_id'"));
$air_count = 0;

if($sq_air>0)
{
    $y_pos1 = $pdf->getY();
    $y_pos1+=5;

    $pdf->SetFillColor(51,203,204);
    $pdf->rect(27,$y_pos1,181,11,F);

    $pdf->SetFont('Arial','',13);
    $pdf->SetTextColor(255,255,255);
    
    $pdf->SetXY(28, $y_pos1+2);
    $pdf->Cell(200, 7, 'TRAVEL-FLIGHT');

    $pdf->setTextColor(40,35,35);
    $pdf->SetY($pdf->GetY()+9); 

    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    $pdf->AddPage($pdf->CurOrientation);
    $pdf->Image($sidebar_strip,7,0,10,297);
    $pdf->SetX(27);

    $y_pos1 = $pdf->getY();

    $pdf->SetFillColor(235);
    $pdf->rect(27,$y_pos1, 181, 7, 'F');
    $pdf->SetFont('Arial','B',9);

    $pdf->SetWidths(array(35,30,35,18,32,31));
    $pdf->Row(array('FROM', 'TO', 'AIRLINE','SEATS','DEPARTURE D/T','ARRIVAL D/T'));


    $sq_air_details = mysql_query("select * from plane_master where tourwise_traveler_id='$tourwise_id'");
    while($row_air_details = mysql_fetch_assoc($sq_air_details))
    {
        $sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_air_details[company]'"));

        $pdf->SetX(27);
        $pdf->SetFont('Arial','',10);
        $pdf->Row(array($row_air_details['from_location'], $row_air_details['to_location'], $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')', $row_air_details['seats'], date("d-m-Y H:i", strtotime($row_air_details['date'])), date("d-m-Y H:i", strtotime($row_air_details['arraval_time']))));

        if($pdf->GetY()+20>$pdf->PageBreakTrigger)
        {
            $pdf->AddPage($pdf->CurOrientation);
            $pdf->Image($transport_service_voucher2,8,0,8,297);
        }

    }
}

// Cruise
$sq_cruise = mysql_num_rows(mysql_query("select booking_id from group_cruise_master where booking_id='$tourwise_id'"));
if($sq_cruise>0)
{
    $y_pos1 = $pdf->getY();
    $y_pos1+=5;

    $pdf->SetFillColor(51,203,204);
    $pdf->rect(27,$y_pos1,181,11,F);

    $pdf->SetFont('Arial','',13);
    $pdf->SetTextColor(255,255,255);
    
    $pdf->SetXY(28, $y_pos1+2);
    $pdf->Cell(200, 7, 'TRAVEL-CRUISE');

    $pdf->setTextColor(40,35,35);
    $pdf->SetY($pdf->GetY()+9); 

    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    $pdf->AddPage($pdf->CurOrientation);
    $pdf->Image($sidebar_strip,7,0,10,297);
    $pdf->SetX(27);

    $y_pos1 = $pdf->getY();

    $pdf->SetFillColor(235);
    $pdf->rect(27,$y_pos1, 181, 7, 'F');
    $pdf->SetFont('Arial','B',9);

    $pdf->SetWidths(array(35,30,35,18,32,31));
    $pdf->Row(array('DEPARTURE D/T','ARRIVAL D/T','ROUTE','CABIN','SHARING','SEATS'));


    $sq_cruise_details = mysql_query("select * from group_cruise_master where booking_id='$tourwise_id'");
    while($row_cruise_details = mysql_fetch_assoc($sq_cruise_details))
    {
        $pdf->SetX(27);
        $pdf->SetFont('Arial','',10);
        $pdf->Row(array(date("d-m-Y H:i:s", strtotime($row_cruise_details['dept_datetime'])), date("d-m-Y H:i:s", strtotime($row_cruise_details['arrival_datetime'])),$row_cruise_details['route'],$row_cruise_details['cabin'],$row_cruise_details['sharing'],$row_cruise_details['seats']));

        if($pdf->GetY()+20>$pdf->PageBreakTrigger)
        {
            $pdf->AddPage($pdf->CurOrientation);
            $pdf->Image($transport_service_voucher2,8,0,8,297);
        }

    }
}


//INCLUSION 
$pdf->SetFillColor(51,203,204);
$y_pos = $pdf->getY();
$y_pos+=8;
$pdf->rect(28,$y_pos,179,11,F);

$y_pos = $pdf->getY();
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(255,255,255);
$y_pos = $pdf->getY();
$y_pos+=8;
$pdf->SetXY(28,$y_pos+1);
$pdf->Cell(86, 10, 'INCLUSIONS' , 0, 0);

$y_pos = $pdf->getY()+15;
$pdf->SetTextColor(40,35,35);
$pdf->SetFont('Arial','',9);
$pdf->setXY(32, $y_pos);
$pdf->MultiCell(156, 4,$tour_name1['inclusions'], 0, 1);

//exclusion
$pdf->SetFillColor(51,203,204);
$y_pos = $pdf->getY();
$y_pos+=8;
$pdf->rect(28,$y_pos-1,179,11,F);

$y_pos = $pdf->getY()+10;
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(255,255,255);
$y_pos = $pdf->getY();
$y_pos+=8;
$pdf->SetXY(28,$y_pos);
$pdf->Cell(86, 10, 'EXCLUSIONS' , 0, 0);

$y_pos = $pdf->getY()+15;
$pdf->SetTextColor(40,35,35);
$pdf->SetFont('Arial','',9);
$pdf->setXY(32, $y_pos);
//Adding new page if end of page is found
        if($pdf->GetY()+20>$pdf->PageBreakTrigger)
        {
            $pdf->AddPage($pdf->CurOrientation);
            $pdf->Image($transport_service_voucher2,8,0,8,297);
        }
$pdf->MultiCell(156, 4,$tour_name1['exclusions'], 0, 1);

if($pdf->GetY()+5>$pdf->PageBreakTrigger)
$pdf->AddPage();
$pdf->setTextColor(40,35,35);
$pdf->Image($sidebar_strip,7,0,10,297);

$tour_amount = ($tourwise_details['tour_fee_subtotal_1']!="") ? $tourwise_details['tour_fee_subtotal_1']: 0;
$tour_total_amount= ($tourwise_details['total_tour_fee']!="") ? $tourwise_details['total_tour_fee']: 0;

$pdf->AddPage($pdf->CurOrientation);
$pdf->Image($sidebar_strip,7,0,10,297);
$pdf->SetFillColor(51,203,204);
$y_pos1 = $pdf->getY();
$y_pos1+=5;
$pdf->rect(27,$y_pos1,181,11,F);

$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(255,255,255);
$y_pos1 = $pdf->getY();
$y_pos1+=8;
$pdf->SetXY(28,$y_pos1);
$pdf->Cell(100, 5, 'PAYMENT DETAILS');

//Adding new page if end of page is found
if($pdf->GetY()+25>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($sidebar_strip,7,0,10,297);
    }

$pdf->setTextColor(40,35,35);
$pdf->SetFont('Arial','B',9);

$y_pos1 = $pdf->getY();
$y_pos1+=8;
$pdf->SetXY(27,$y_pos1);
$pdf->SetFillColor(235);
$pdf->rect(27,$y_pos1, 40, 8, 'F');
$pdf->MultiCell(40, 8, 'TOUR AMOUNT', 1);

$pdf->SetFont('Arial','',9);       
$pdf->SetXY(67, $y_pos1);
$pdf->MultiCell(50, 8, $tourwise_details['tour_fee_subtotal_1'], 1,'R');

$pdf->SetFont('Arial','B',9); 
$pdf->SetXY(117, $y_pos1);
$pdf->SetFillColor(235);
$pdf->rect(117,$y_pos1, 40, 8, 'F');
$pdf->MultiCell(40, 8, 'Tax AMOUNT', 1);

$pdf->SetFont('Arial','',9); 
$pdf->SetXY(157, $y_pos1);
$pdf->MultiCell(50, 8, $tourwise_details['service_tax'] , 1,'R');

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(27,$y_pos1+8);
$pdf->SetFillColor(235);
$pdf->rect(27,$y_pos1+8, 40, 8, 'F');
$pdf->MultiCell(40, 8, 'TRAIN AMOUNT', 1);

$pdf->SetFont('Arial','',9);
$pdf->SetXY(67, $y_pos1+8);
$pdf->MultiCell(50, 8, $tourwise_details['total_train_expense'] , 1,'R');

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(117, $y_pos1+8);
$pdf->SetFillColor(235);
$pdf->rect(117,$y_pos1+8, 40, 8, 'F');
$pdf->MultiCell(40, 8, 'FLIGHT AMOUNT', 1);

$pdf->SetFont('Arial','',9);
$pdf->SetXY(157, $y_pos1+8);
$pdf->MultiCell(50, 8, $tourwise_details['total_plane_expense'] , 1,'R');

if($pdf->GetY()+25>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($sidebar_strip,7,0,10,297);
    }

$y_pos1 = $pdf->getY();
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(27, $y_pos1);
$pdf->SetFillColor(235);
$pdf->rect(27,$y_pos1, 40, 8, 'F');
$pdf->MultiCell(40, 8, 'CRUISE AMOUNT', 1);

$pdf->SetFont('Arial','',9);
$pdf->SetXY(67, $y_pos1);
$pdf->MultiCell(50, 8, $tourwise_details['total_cruise_expense'] , 1,'R');

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(117,$y_pos1);
$pdf->SetFillColor(235);
$pdf->rect(117,$y_pos1, 40, 8, 'F');
$pdf->MultiCell(40, 8, 'VISA AMOUNT', 1);

$pdf->SetFont('Arial','',9);
$pdf->SetXY(157, $y_pos1);
$pdf->MultiCell(50, 8, $tourwise_details['visa_total_amount'] , 1,'R');


$y_pos1 = $pdf->getY();

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(27, $y_pos1);
$pdf->SetFillColor(235);
$pdf->rect(27,$y_pos1, 40, 8, 'F');
$pdf->MultiCell(40, 8, 'INSURANCE AMOUNT', 1);

$pdf->SetFont('Arial','',9);
$pdf->SetXY(67, $y_pos1);
$pdf->MultiCell(50, 8, $tourwise_details['insuarance_total_amount'] , 1,'R');

if($pdf->GetY()+25>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($sidebar_strip,7,0,10,297);
    }

$pdf->SetFillColor(51,203,204);
$y_pos1 = $pdf->getY();
$y_pos1+=5;
$pdf->rect(117,$y_pos1,45,11,F);

$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(120, $y_pos1+2);
$pdf->Cell(35, 8, 'TOTAL AMOUNT');

$pdf->SetFont('Arial','B',13);
$pdf->setTextColor(40,35,35);
$pdf->SetXY(162, $y_pos1);
$pdf->MultiCell(45,11, number_format($tour_total_amount + $tourwise_details['total_travel_expense'],2), 1,'R');

$pdf->SetFont('Arial','',10);

$filename = $sq_customer['first_name'].'_'.$sq_customer['last_name'].'_bookingform'.'.pdf';
$pdf->Output($filename,'I');
?>