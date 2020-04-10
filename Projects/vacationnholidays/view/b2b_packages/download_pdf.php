<?php
include "../../model/model.php";
require("../../classes/convert_amount_to_word.php");

$package_id=$_GET['package_id'];
$sq_pckg = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$package_id'"));

define('FPDF_FONTPATH','../../classes/fpdf/font/');
//require('../../classes/fpdf/fpdf.php');
require('../../classes/mc_table.php');

ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();

$pdf=new PDF_MC_Table();
$pdf->AddPage();
$pdf->setTextColor(40,35,35);

$pdf->Image($b2b_pdf_image,0,0,20,285);
 
$pdf->SetXY(0,0);

$pdf->Image($admin_logo_url, 30, 8, 46, 17);

$pdf->SetFillColor(51,203,204);
$pdf->rect(27,37,179,11,F);

$pdf->SetFont('Arial','',13);
$pdf->setTextColor(255,255,255);
$pdf->SetXY(28,40);
$pdf->Cell(86, 10, 'PACKAGE DETAILS');

$pdf->SetDrawColor(211,211,211);
$pdf->Line(27, 79, 206, 79);
$pdf->Line(206, 48, 206, 79);
$pdf->Line(27, 48, 27, 79);
$pdf->Line(112, 48,112, 79);

$pdf->Line(27, 58, 206, 58);
$pdf->Line(27, 68, 206, 68);

$pdf->setTextColor(40,35,35);

$pdf->SetFont('Arial','B',9);
$pdf->setXY(28, 50);
$pdf->cell(190, 7, 'DESTINATION        : ', 0, 0);

$sq_dest = mysql_fetch_assoc(mysql_query("select * from destination_master where dest_id='$sq_pckg[dest_id]'"));
$pdf->SetFont('Arial','',9);
$pdf->setXY(63, 50);
$pdf->cell(190, 7, $sq_dest['dest_name'], 0, 0);

$pdf->SetFont('Arial','B',9);
$pdf->setXY(117.5, 50);
$pdf->cell(190, 7, 'PACKAGE NAME  : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(150, 50);
$pdf->cell(190, 7, $sq_pckg['package_name'], 0, 0);

$pdf->SetFont('Arial','B',9); 
$pdf->setXY(28, 60);
$pdf->cell(190, 7, 'PACKAGE CODE   : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(63, 60);
$pdf->cell(190, 7, $sq_pckg['package_code'], 0, 0);

$pdf->SetFont('Arial','B',9);
$pdf->setXY(117, 60);
$pdf->cell(190, 7, 'TOTAL DAYS         : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(150, 60);
$pdf->cell(190, 7, $sq_pckg['total_days'], 0, 0);

$pdf->SetFont('Arial','B',9);
$pdf->setXY(28, 70);
$pdf->cell(190, 7, 'TOTAL NIGHTS      : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(63, 70);
$pdf->cell(190, 7, $sq_pckg['total_nights'], 0, 0);
 
$pdf->SetFont('Arial','B',9);
$pdf->setXY(117, 70);
$pdf->cell(190, 7, 'TRANSPORT         : ', 0, 0);

$transport_bus = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$sq_pckg[transport_id]'"));
$pdf->SetFont('Arial','',9); 
$pdf->setXY(150, 70);
$pdf->cell(190, 7, $transport_bus['bus_name'], 0, 0);

// Itinerary
$y_pos = $pdf->getY()+15;
$pdf->setXY(28, $y_pos);

$pdf->SetFillColor(51,203,204);
$pdf->rect(27,$y_pos-1,179,11,F);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(225,255,255);
$pdf->Cell(86, 10, 'TOUR ITINERARY' , 0, 0);

$pdf->SetTextColor(40,35,35);
$count = 1;
$sq_package_program = mysql_query("select * from custom_package_program where package_id = '$package_id'");
while($row_itinarary = mysql_fetch_assoc($sq_package_program)){

    //Adding new page if end of page is found
    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($transport_service_voucher2,8,0,8,297);
    }

$pdf->SetFont('Arial','B',8);
$y_pos = $pdf->getY()+13;
$pdf->setXY(28, $y_pos);
$pdf->cell(190, 7, 'DAY : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(36, $y_pos);
$pdf->cell(190, 7, $count, 0, 0);
$count++;

    
    //Adding new page if end of page is found
    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($transport_service_voucher2,8,0,8,297);
    }

$pdf->SetFont('Arial','B',8);
$y_pos = $pdf->getY()+6;
$pdf->setXY(28, $y_pos);
$pdf->cell(190, 7, 'ATTRACTION : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(49, $y_pos);
$pdf->cell(190, 7, $row_itinarary['attraction'], 0, 0);

    
    //Adding new page if end of page is found
    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($transport_service_voucher2,8,0,8,297);
    }

$pdf->SetFont('Arial','B',8);
$y_pos = $pdf->getY()+6;
$pdf->setXY(28, $y_pos);
$pdf->cell(190, 7, 'DAY-WISE PROGRAM : ', 0, 0);

    
    //Adding new page if end of page is found
    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($transport_service_voucher2,8,0,8,297);
    }

$pdf->SetFont('Arial','',9);
$y_pos = $pdf->getY()+6;
$pdf->setXY(28, $y_pos);
$pdf->MultiCell(175, 5,$row_itinarary['day_wise_program'], 0, 1);

    
    //Adding new page if end of page is found
    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($transport_service_voucher2,8,0,8,297);
    }

$pdf->SetFont('Arial','B',8);
$y_pos = $pdf->getY()+2;
$pdf->setXY(28, $y_pos);
$pdf->cell(190, 7, 'STAY : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(38, $y_pos);
$pdf->cell(190, 7, $row_itinarary['stay'], 0, 0);

    
    //Adding new page if end of page is found
    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($transport_service_voucher2,8,0,8,297);
    }

$pdf->SetFont('Arial','B',8);
$y_pos = $pdf->getY()+6;
$pdf->setXY(28, $y_pos);
$pdf->cell(190, 7, 'MEAL PLAN : ', 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->setXY(47, $y_pos);
$pdf->cell(190, 7, $row_itinarary['meal_plan'], 0, 0);

    
    //Adding new page if end of page is found
    if($pdf->GetY()+20>$pdf->PageBreakTrigger)
    {
        $pdf->AddPage($pdf->CurOrientation);
        $pdf->Image($transport_service_voucher2,8,0,8,297);
    }

$pdf->SetDrawColor(154,154,154);
$y_pos = $pdf->getY()+10;
$pdf->line(28, $y_pos, 205,$y_pos);

}
//HOTEL
$sq_hotel_count = mysql_num_rows(mysql_query("select * from custom_package_hotels where package_id='$package_id'"));

if($sq_hotel_count>0){
    $pdf->AddPage($pdf->CurOrientation);
    $pdf->Image($b2b_pdf_image,0,0,20,285);
    $pdf->SetX(27);
    $pdf->SetFillColor(51,203,204);
    $y_pos = $pdf->getY();
    $y_pos+=8;
    $pdf->rect(27,$y_pos-1,179,11,F);

    $y_pos = $pdf->getY()+10;
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(255,255,255);
    $y_pos = $pdf->getY();
    $y_pos+=7.5;
    $pdf->SetXY(27,$y_pos);
    $pdf->Cell(86, 10, 'ACCOMMODATIONS' , 0, 0);

    $y_pos = $pdf->getY();
    $sq_hotel = mysql_query("select * from custom_package_hotels where package_id='$package_id'");
    //$y_pos = $pdf->getY()+10;
    $pdf->setXY(27, $y_pos);
    $pdf->SetTextColor(40,35,35);
    $pdf->SetFont('Arial','',9);
    while($row_hotel = mysql_fetch_assoc($sq_hotel)){
    $y_pos = $pdf->getY()+10;   
    $pdf->setXY(27, $y_pos);
    $pdf->SetFillColor(192);
    $pdf->rect(27, $y_pos, 179,7, 'F');

    $hotel_name = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_hotel[hotel_name]'"));
    $city_name = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_hotel[city_name]'"));
    
    $pdf->SetWidths(array(59,60,40,20));
    $pdf->Row(array('City : ', 'Hotel : ','Type : ', 'Days : '));
    $x_pos = $pdf->getX()+10;
    $pdf->setXY(39,$y_pos-1.5);
    $pdf->Cell(93, 10,' '.$city_name['city_name'], 0, 1);
    $pdf->setXY(101,$y_pos-1.5);
    $pdf->Cell(93, 10,' '.$hotel_name['hotel_name'], 0, 1);
    $pdf->setXY(157,$y_pos-1.5 );
    $pdf->Cell(93, 10,' '.$row_hotel['hotel_type'], 0, 1);
    $pdf->setXY(199,$y_pos-1.5 );
    $pdf->Cell(93, 10,' '.$row_hotel['total_days'], 0, 1);

    $sq_count_h = mysql_num_rows(mysql_query("select * from hotel_vendor_images_entries where hotel_id='$row_hotel[hotel_name]' "));
    $x_pos = '27';  
    $y_pos = $pdf->getY()-1;
    if($sq_count_h ==0){
    $pdf->setXY($x_pos,$y_pos);
    $pdf->rect($x_pos,$y_pos,60,45);
     $download_url =  BASE_URL.'images/dummy-image.jpg';
        $pdf->Image($download_url, $x_pos, $y_pos,60,45);
    }
 
        $sq_hotel_image = mysql_query("select * from hotel_vendor_images_entries where hotel_id = '$row_hotel[hotel_name]'");
        $x_pos = '27';  
        $y_pos = $pdf->getY()-1;

        while($row_hotel_image = mysql_fetch_assoc($sq_hotel_image)){
            
            $image = $row_hotel_image['hotel_pic_url']; 
            $pdf->setXY($x_pos,$y_pos);
            $pdf->rect($x_pos,$y_pos,60,45);
            $newUrl = preg_replace('/(\/+)/','/',$image);
            $newUrl = explode('uploads', $newUrl);
            $newUrl = BASE_URL.'uploads'.$newUrl[1];
            $pdf->Image($newUrl, $x_pos, $y_pos,60,45);  
            $x_pos = $pdf->getX()+59.5;
        }
        $y_pos = $pdf->getY()+43;
        $pdf->setXY(27, $y_pos);
        //Adding new page if end of page is found
        if($pdf->GetY()+20>$pdf->PageBreakTrigger)
        {
            $pdf->AddPage($pdf->CurOrientation);
            $pdf->Image($b2b_pdf_image,0,0,20,285);
        }
    }

}
/// Destination gallery
$pdf->AddPage($pdf->CurOrientation);
$pdf->Image($b2b_pdf_image,0,0,20,285);

$pdf->SetFillColor(51,203,204);
    $y_pos = $pdf->getY();
    $y_pos+=8;
    $pdf->rect(27,$y_pos-1,179,11,F);

    $y_pos = $pdf->getY()+10;
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(255,255,255);
    $y_pos = $pdf->getY();
    $y_pos+=7.5;
    $pdf->SetXY(27,$y_pos);
    $pdf->Cell(86, 10, 'SIGHTSEEING' , 0, 0);
    $count=0;
    $count_i = 0;
    $x_pos = '28';  
    $y_pos = $pdf->getY()+11;
    $sq_img = mysql_query("select * from custom_package_images where package_id='$package_id'");
        $x_pos = '27';  
        $y_pos = $pdf->getY()+11;

         while ($row_img = mysql_fetch_assoc($sq_img) ) 
         {
           $count_i++;
           $query1 = "select * from gallary_master where image_url = '$row_img[image_url]'";

           $sq_gallary1 = mysql_query($query1);

           while($row_gallary1 = mysql_fetch_assoc($sq_gallary1)){
            $count++;
            $newUrl =  $row_gallary1['image_url']; 
             
            if($count_i <= 3 && $count_i != 1 || $count_i >= 5 && $count_i <7 || $count_i >= 8 && $count_i <10 || $count_i >= 11 && $count_i <13 || $count_i >= 14 && $count_i <16){
                $x_pos = $x_pos+59.2;   
            }                   
            elseif($count_i == 4 || $count_i == 7 || $count_i == 10 || $count_i == 13){
                $y_pos = $pdf->getY()+45;
                $x_pos = '28';
            }
            else{
            }

                $pdf->setXY($x_pos, $y_pos);
                $pdf->Image($newUrl, $x_pos, $y_pos,60,45);                      
        }  
    }
if($pdf->GetY()+30>$pdf->PageBreakTrigger)
{
    $pdf->AddPage($pdf->CurOrientation);
    $pdf->Image($b2b_pdf_image,0,0,20,285);
} 
// INCLUSIONS    
$pdf->SetFillColor(51,203,204);
    $y_pos = $pdf->getY();
    $y_pos+=55;
    $pdf->rect(27,$y_pos-1,179,11,F);

    $y_pos = $pdf->getY()+10;
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(255,255,255);
    $y_pos = $pdf->getY();
    $y_pos+=55;
    $pdf->SetXY(27,$y_pos);
    $pdf->Cell(86, 10, 'INCLUSIONS' , 0, 0);

    $y_pos+=9;
    $pdf->SetTextColor(40,35,35);
    $pdf->SetFont('Arial','',9);
    $pdf->SetXY(27,$y_pos);
    $pdf->multiCell(150,8, $sq_pckg['inclusions'] , 0, 1);

if($pdf->GetY()+30>$pdf->PageBreakTrigger)
{
    $pdf->AddPage($pdf->CurOrientation);
    $pdf->Image($b2b_pdf_image,0,0,20,285);
}
// EXCLUSIONS
$pdf->SetFillColor(51,203,204);
    $y_pos = $pdf->getY();
    $y_pos+=15;
    $pdf->rect(27,$y_pos-1,179,11,F);

    $y_pos = $pdf->getY()+17;
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(255,255,255);
    $y_pos = $pdf->getY();
    $y_pos+=14;
    $pdf->SetXY(27,$y_pos);
    $pdf->Cell(86, 10, 'EXCLUSIONS' , 0, 0);   

    $pdf->SetTextColor(40,35,35);
    $pdf->SetFont('Arial','',9);
    $y_pos+=10;
    $pdf->SetXY(27,$y_pos);
    $pdf->multiCell(150,8, $sq_pckg['exclusions'] , 0, 1);
if($pdf->GetY()+30>$pdf->PageBreakTrigger)
{
    $pdf->AddPage($pdf->CurOrientation);
    $pdf->Image($b2b_pdf_image,0,0,20,285);
}
$pdf->SetFont('Arial','',9);

$filename = $sq_pckg['package_name'].' '.'Itinerary'.'.pdf';
$pdf->Output($filename,'D');
ob_end_flush();
?>