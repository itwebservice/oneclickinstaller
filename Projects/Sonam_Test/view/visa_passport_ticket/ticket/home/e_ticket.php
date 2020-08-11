<?php
include_once('../../../../model/model.php');
require("../../../../classes/convert_amount_to_word.php");
define('FPDF_FONTPATH','../../../../classes/fpdf/font/');
require('../../../../classes/mc_table.php');
$ticket_id = $_GET['ticket_id'];
$service_name = $_GET['service_name'];
$invoice_date = $_GET['invoice_date'];

$sq_airline = mysql_fetch_assoc(mysql_query("SELECT * FROM ticket_master_entries WHERE ticket_id='$ticket_id'"));
$sq_terms = mysql_fetch_assoc(mysql_query("SELECT * FROM terms_and_conditions WHERE type='Flight E-Ticket'"));

$pdf=new PDF_MC_Table();
$pdf=new PDF();
$pdf->Rect(5, 5 , 199, 287);
$count=1;

$pdf->SetFont('Arial','',9);
$pdf->SetTextColor(40,35,35);
$sq_trip = mysql_query("SELECT * FROM ticket_trip_entries WHERE ticket_id='$ticket_id'");
while($row_trip = mysql_fetch_assoc($sq_trip)){
	$pdf->addPage();
	$pdf->SetDrawColor(211,211,211);
	$pdf->SetTextColor(40,35,35);

	$y_pos = $pdf->getY();
	//logo
	$pdf->Image($admin_logo_url, 140, 10, 50, 20);

	//App Name
	$y_pos = $pdf->getY()+1;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY(10,$y_pos);
	$pdf->MultiCell(25, 3, "Company        : ",0);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(31,$y_pos);
	$pdf->MultiCell(98, 3,$app_name,0);

	//App Address
	$y_pos = $pdf->getY()+2;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY(10,$y_pos);
	$pdf->MultiCell(25,3, "Address          : ",0);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(31,$y_pos);
	$pdf->MultiCell(85, 3,$app_address,0);

	//App Email
	$y_pos = $pdf->getY()+2;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY(10,$y_pos);
	$pdf->MultiCell(25, 3, "Email               : ",0);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(31,$y_pos);
	$pdf->MultiCell(98, 3,$app_email_id,0);

	//App Contact
	$y_pos = $pdf->getY()+2;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY(10,$y_pos);
	$pdf->MultiCell(25, 3, "Contact           : ",0);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(31,$y_pos);
	$pdf->MultiCell(98, 3,$app_contact_no,0);

	//App Email
	$y_pos = $pdf->getY()+2;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY(10,$y_pos);
	$pdf->MultiCell(25, 3, "Booking Date : ",0);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(31,$y_pos);
	$pdf->MultiCell(98, 3,$invoice_date,0);

	//travel details
	$y_pos = $pdf->getY()+10;
	$x_pos='10';
	$pdf->SetFillColor(0,152,152);
	$pdf->rect(10, $y_pos, 188, 10, 'F');
	$pdf->SetFont('Arial','',11);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetWidths(array(189));
	$pdf->SetXY($x_pos,$y_pos+1);
	$pdf->MultiCell(40, 8, 'TRAVEL DETAILS', 0);

	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(40,35,35);
	$y_pos = $pdf->getY();
	$pdf->setXY(10,$y_pos+1);
	$pdf->MultiCell(94, 7, "From Sector              :  ".$row_trip['departure_city'], 1);
	$pdf->setXY(104, $y_pos+1);
	$pdf->MultiCell(94, 7, "To Sector            :  ".$row_trip['arrival_city'], 1);
	$pdf->setXY(10, $y_pos+8);
	$pdf->MultiCell(94, 7, "Departure Datetime  :  ".date("d-m-Y H:i:s", strtotime($row_trip['departure_datetime'])),  1);
	$pdf->setXY(104, $y_pos+8);
	$pdf->MultiCell(94, 7, "Arrival Datetime :  ".date("d-m-Y H:i:s", strtotime($row_trip['arrival_datetime'])), 1);
	$y_pos = $pdf->getY();
	$pdf->setXY(10, $y_pos);
	$pdf->MultiCell(94, 7, "Flight No                   :  ".$row_trip['flight_no'],  1);
	$pdf->setXY(104, $y_pos);
	$pdf->MultiCell(94, 7, "Airline PNR         :  ".$row_trip['airlin_pnr'], 1);
	$y_pos = $pdf->getY();
	$pdf->setXY(10, $y_pos);
	$pdf->MultiCell(94, 7, "Meal Plan                  :  ".$row_trip['meal_plan'], 1);
	$pdf->setXY(104, $y_pos);
	$pdf->MultiCell(94, 7, "Luggage              :  ".$row_trip['luggage'], 1);
	$y_pos = $pdf->getY();
	$pdf->setXY(10, $y_pos);
	$pdf->MultiCell(94, 7, "Airline Name             :  ".$row_trip['airlines_name'], 1);
		   
	//passenger
	$y_pos = $pdf->getY()+10;
	$x_pos='10';
	$pdf->SetFillColor(0,152,152);
	$pdf->SetY($pdf->GetY()+7.5); 
	$pdf->rect(10, $y_pos, 188, 10, 'F');
	$pdf->SetFont('Arial','',11);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetWidths(array(189));
	$pdf->SetXY($x_pos,$y_pos+1);
	$pdf->MultiCell(40, 8, 'PASSENGERS', 0);

	$count=1;
	$pdf->SetFillColor(235);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetWidths(array(87,30,30,41));
	$pdf->SetTextColor(40,35,35);
	$pdf->SetXY(10,$y_pos+10);
	$pdf->Row(array('Name', 'Adolescence', 'Birthdate', 'Ticket No'));
	$sq_passenger = mysql_query("select * from ticket_master_entries where ticket_id = '$ticket_id'");
	while($row_passenger = mysql_fetch_assoc($sq_passenger)){	
		$pdf->SetFont('Arial','',9);
		$pdf->SetTextColor(40,35,35);	 
		$pass_name = $count.') '.$row_passenger['first_name'].' '.$row_passenger['last_name'];

		$pdf->SetFont('Arial','',9);
		$pdf->Row(array($pass_name, $row_passenger['adolescence'],date("d-m-Y", strtotime($row_passenger['birth_date'])), $row_passenger['ticket_no']));
		$count++;					
	}
	$y_pos = $pdf->getY()+10;
	$x_pos = '10';	
	$pdf->setXY($x_pos, $y_pos);

//Terms and conditions
$y_pos = $pdf->getY();
$x_pos='10';
$pdf->SetFillColor(0,152,152);
$pdf->rect(10, $y_pos, 188, 10, 'F');
$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(255,255,255);
$pdf->SetWidths(array(189));
$pdf->SetXY($x_pos,$y_pos+1);
$pdf->MultiCell(80, 8, 'TERMS AND CONDITIONS', 0);

	$pdf->SetFont('Arial','',8);
	$y_pos = $pdf->getY()+1;
	$x_pos='10';
	$pdf->SetXY($x_pos, $y_pos);
	$pdf->SetTextColor(40,35,35);

	$html = $sq_terms['terms_and_conditions'];
	// HTML parser
	$html = str_replace("\n",' ',$html);
	$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	foreach($a as $i=>$e){
		if($i%2==0){
			// Text
			if($pdf1->HREF){
				$pdf1->PutLink($pdf1->HREF,$e);
			}
			else
				$pdf->Write(5,$e);
		}
		else{
			//Tag
			if($e[0]=='/'){
				$pdf1->CloseTag(strtoupper(substr($e,1))); }
			else{
				// Extract attributes
				$a2 = explode(' ',$e);
				$tag = strtoupper(array_shift($a2));
				$attr = array();
				foreach($a2 as $v){
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])] = $a3[2];
				}
				$pdf1->OpenTag($tag,$attr);
			}
		}
	}
}
$filename = 'Air_Ticket_Voucher_'.$ticket_id.'.pdf';
$pdf->Output($filename,'I');
?>