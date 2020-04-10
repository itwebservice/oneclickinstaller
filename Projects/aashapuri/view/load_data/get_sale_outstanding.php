<?php include_once("../../model/model.php");
$booking_type = $_POST['booking_type'];
$booking_id = $_POST['booking_id'];

if($booking_type=="visa"){
    $sq_visa = mysql_fetch_assoc(mysql_query("select * from visa_master where visa_id='$booking_id'"));
    $total_sale = $sq_visa['visa_total_cost'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from visa_payment_master where clearance_status!='Cancelled' and visa_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="flight"){
    $sq_ticket_info = mysql_fetch_assoc(mysql_query("select * from ticket_master where ticket_id='$booking_id'"));
    $total_sale = $sq_ticket_info['ticket_total_cost'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from ticket_payment_master where clearance_status!='Cancelled' and ticket_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="passport"){
    $sq_passport_info = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id='$booking_id'"));
    $total_sale = $sq_passport_info['passport_total_cost'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from passport_payment_master where clearance_status!='Cancelled' and passport_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="train"){
    $sq_train_ticket_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$booking_id'"));
    $total_sale = $sq_train_ticket_info['net_total'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from train_ticket_payment_master where clearance_status!='Cancelled' and train_ticket_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="hotel"){
    $sq_booking = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));
    $total_sale = $sq_booking['total_fee'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from hotel_booking_payment where clearance_status!='Cancelled' and booking_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="bus"){
    $sq_bus_info = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id='$booking_id'"));
    $total_sale = $sq_bus_info['net_total'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from bus_booking_payment_master where clearance_status!='Cancelled' and booking_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="car"){
    $sq_booking = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));
    $total_sale = $sq_booking['total_fees'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from car_rental_payment where clearance_status!='Cancelled' and booking_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="forex"){
    $sq_forex_info = mysql_fetch_assoc(mysql_query("select * from forex_booking_master where booking_id='$booking_id'"));
    $total_sale = $sq_forex_info['net_total'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from forex_booking_payment_master where clearance_status!='Cancelled' and booking_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="excursion"){
    $sq_exc_info = mysql_fetch_assoc(mysql_query("select * from excursion_master where exc_id='$booking_id'"));
    $total_sale = $sq_exc_info['exc_total_cost'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from exc_payment_master where clearance_status!='Cancelled' and exc_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="miscellaneous"){
    $sq_visa_info = mysql_fetch_assoc(mysql_query("select * from miscellaneous_master where misc_id='$booking_id'"));
    $total_sale = $sq_visa_info['misc_total_cost'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from miscellaneous_payment_master where clearance_status!='Cancelled' and misc_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else if($booking_type=="package"){
    $sq_booking = mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$booking_id'"));
    $total_sale = $sq_booking['total_travel_expense'] + $sq_booking['actual_tour_expense'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(amount) as sum from package_payment_master where clearance_status!='Cancelled' and booking_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
    $outstanding =  $total_sale - $total_pay_amt;
}
else if($booking_type=="group"){
    $sq_booking = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$booking_id'"));
    $total_sale = $sq_booking['total_travel_expense'] + $sq_booking['total_tour_fee'];
    $sq_pay = mysql_fetch_assoc(mysql_query("select sum(amount) as sum from payment_master where clearance_status!='Cancelled' and tourwise_traveler_id='$booking_id'"));
    $total_pay_amt = $sq_pay['sum'];
}
else{
    $total_sale = 0;
    $total_pay_amt = 0;
}
$outstanding =  $total_sale - $total_pay_amt;
echo $outstanding;
?>