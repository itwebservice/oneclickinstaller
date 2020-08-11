<?php
//Generic Files
include "../../../../model.php"; 
include "../../print_functions.php";
require("../../../../../classes/convert_amount_to_word.php"); 

//Parameters
$invoice_no = $_GET['invoice_no'];
$invoice_date = $_GET['invoice_date'];
$customer_id = $_GET['customer_id'];
$service_name = $_GET['service_name'];
$booking_id = $_GET['booking_id'];

$query = mysql_fetch_assoc(mysql_query("select * from b2b_booking_master where booking_id='$booking_id'"));
$cart_checkout_data = json_decode($query['cart_checkout_data']);
$hotel_list_arr = array();
$transfer_list_arr = array();
$activity_list_arr = array();
$tours_list_arr = array();
for($i=0;$i<sizeof($cart_checkout_data);$i++){
  if($cart_checkout_data[$i]->service->name == 'Hotel'){
    array_push($hotel_list_arr,$cart_checkout_data[$i]);
  }
  if($cart_checkout_data[$i]->service->name == 'Transfer'){
    array_push($transfer_list_arr,$cart_checkout_data[$i]);
  }
  if($cart_checkout_data[$i]->service->name == 'Activity'){
    array_push($activity_list_arr,$cart_checkout_data[$i]);
  }
  if($cart_checkout_data[$i]->service->name == 'Combo Tours'){
    array_push($tours_list_arr,$cart_checkout_data[$i]);
  }
}
//Get default currency rate
global $currency;
$sq_to = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency'"));
$to_currency_rate = $sq_to['currency_rate'];

$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_customer[state_id]'"));
$sq_app = mysql_fetch_assoc(mysql_query("select state_id from app_Settings where setting_id='1'"));
$sq_sup_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_app[state_id]'"));
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$branch_details = mysql_fetch_assoc(mysql_query("select * from branches where branch_id='$branch_admin_id'"));

$sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Invoice' and active_flag ='Active'"));   
$emp_id = $_SESSION['emp_id'];  
$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id'"));  
if($emp_id == '0'){ $emp_name = 'Admin';} 
else { $emp_name = $sq_emp['first_name'].' ' .$sq_emp['last_name']; }
?>
  <section class="print_sec_tp_s main_block ">  
<!-- invloice_receipt_hedaer_top--> 
<div class="main_block inv_rece_header_top header_seprator_4 mg_bt_10"> 
  <div class="row"> 
    <div class="col-md-8 pd_tp_5">  
      <div class="inv_rece_header_left">  
        <div class="inv_rece_header_logo">  
          <img src="<?php echo $admin_logo_url ?>" class="img-responsive">  
        </div>  
      </div>  
    </div>  
    <div class="col-md-4">  
      <div class="inv_rece_header_right text-right">  
        <ul class="no-pad no-marg font_s_12"> 
          <li style="list-style-type:none;"><h3 class=" font_5 font_s_16 no-marg no-pad caps_text"><?php echo $app_name; ?></h3></li> 
          <li style="list-style-type:none;"><p><?php echo ($branch_status=='yes' && $role!='Admin') ? $branch_details['address1'].','.$branch_details['address2'].','.$branch_details['city'] : $app_address ?></p></li>  
          <li style="list-style-type:none;"><i class="fa fa-phone" style="margin-right: 5px;"></i> <?php echo ($branch_status=='yes' && $role!='Admin') ?   
           $branch_details['contact_no'] : $app_contact_no ?></li>  
          <li style="list-style-type:none;"><i class="fa fa-envelope" style="margin-right: 5px;"></i> <?php echo $app_email_id; ?></li> 
          <li style="list-style-type:none;"><span class="font_5">TAX NO : </span><?php echo $service_tax_no; ?></li>  
        </ul> 
      </div>  
    </div>  
  </div>  
</div>   
</section>


<section class="no-pad main_block gst_invoice side_pad_10">

  
  <!-- invoice_receipt_detail_table-->
  <div class="main_block inv_rece_table">
    <div class="row">
      <div class="col-md-12">
       <div class="table-responsive">
        <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">
          <tr>
            <td rowspan="3" class="text-center col-md-7"><h1 class="no-marg" style="font-size: 26px;">Tax Invoice</h1></td>
            <td class="col-md-1"> </td>
            <td class="col-md-4">Original For Receipient</td>
          </tr>
          <tr>
            <td class="col-md-1"></td>
            <td class="col-md-4">Duplicate for supplier/Transport</td>
          </tr>
          <tr>
            <td class="col-md-1"></td>
            <td class="col-md-4">Triplicate for Supplier</td>
          </tr>
        </table>
       </div>
     </div>
    </div>
  </div>


  <!-- invoice_receipt_detail_table-->
  <div class="main_block inv_rece_table">
    <div class="col-md-6 no-pad" style="padding-right: 0">
     <div class="table-responsive">
      <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">
        <tr>
          <td class="text-right col-md-4">Reverse Charge :</td>
          <td class="col-md-8"></td>
        </tr>
        <tr>
          <td class="text-right col-md-4">Invoice No. :</td>
          <td class="col-md-8"><?= $invoice_no ?></td>
        </tr>
        <tr>
          <td class="text-right col-md-4">Invoice Date :</td>
          <td class="col-md-8"><?=$invoice_date ?></td>
        </tr>
        <tr>
          <td class="text-right col-md-4">GSTIN :</td>
          <td class="col-md-8"><?= $service_tax_no; ?></td>
        </tr>
      </table>
     </div>
   </div>

   <div class="col-md-6 no-pad" style="padding-right: 0">
     <div class="table-responsive">
      <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">
        <tr>
          <td class="text-right col-md-4">Transportation :</td>
          <td class="col-md-8"></td>
        </tr>
        <tr>
          <td class="text-right col-md-4">Vehical No. :</td>
          <td class="col-md-8"></td>
        </tr>
        <tr>
          <td class="text-right col-md-4">Date of Travelling :</td>
          <td class="col-md-8"></td>
        </tr>
        <tr>
          <td class="text-right col-md-4">Destination :</td>
          <td class="col-md-8"></td>
        </tr>
      </table>
     </div>
   </div>
  </div>



  
  <!-- invoice_receipt_detail_table-->
  <div class="main_block inv_rece_table">
    <div class="row">
      <div class="col-md-6" style="padding-right: 0">
       <div class="table-responsive">
        <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">
          <tr>
            <td class="text-right col-md-3">State :</td>
            <td class="col-md-3"><?= $sq_sup_state['state_name'] ?></td>
            <td class="text-right col-md-3"></td>
            <td class="col-md-3"></td>
          </tr>
        </table>
       </div>
     </div>
    </div>
  </div>

  <!-- invoice_receipt_body_table-->
  <div class="main_block inv_rece_table">
      <div class="col-md-6 no-pad">
       <div class="table-responsive">
        <table class="table table-bordered no-marg mg_tp_5 gst_invoice" id="tbl_emp_list" style="padding: 0 !important;">
            <tr class="hightlited_row">
              <td colspan="4" class="text-center"><h5 class="no-marg" style="font-size:10px;">Details of Receiver / Billed to :</h5></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">Name :</td>
              <td colspan="3"><?php echo $sq_customer['first_name'].''.$sq_customer['last_name']; ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">Address :</td>
              <td colspan="3"><?php echo $sq_customer['address'].','.$sq_customer['address2'].','. $sq_customer['city']; ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">GSTIN :</td>
              <td colspan="3"><?php echo $sq_customer['service_tax_no']; ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">State :</td>
              <td><?= $sq_state['state_name'] ?></td>
              <td class="text-right"></td>
              <td></td>
            </tr>
        </table>
       </div>
     </div>

      <div class="col-md-6 no-pad">
       <div class="table-responsive">
        <table class="table table-bordered no-marg mg_tp_5 gst_invoice" id="tbl_emp_list" style="padding: 0 !important;">
            <tr class="hightlited_row">
              <td colspan="4" class="text-center"><h5 class="no-marg" style="font-size:10px;">Details of Consignee / Shipped to :</h5></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">Name :</td>
              <td colspan="3"><?php echo $sq_customer['first_name'].''.$sq_customer['last_name']; ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">Address :</td>
              <td colspan="3"><?php echo $sq_customer['address'].','.$sq_customer['address2'].','. $sq_customer['city']; ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">GSTIN :</td>
              <td colspan="3"><?php echo $sq_customer['service_tax_no']; ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">State :</td>
              <td><?= $sq_state['state_name'] ?></td>
              <td class="text-right"></td>
              <td></td>
            </tr>
        </table>
       </div>
     </div>
  </div>

  <!-- invoice_receipt_detail_table-->
  <div class="main_block inv_rece_table">
    <div class="row">
      <div class="col-md-12">
       <div class="table-responsive">
        <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">
          <tr class="hightlited_row">
            <td class="text-center">S.No</td>
            <td class="text-center">Name Of Service</td>
            <td class="text-center">HSN / SAC</td>
            <td class="text-center">Qty</td>
            <td class="text-center">Rate / Per Pax</td>
            <td class="text-center">Amount</td>
            <td class="text-center">Less : Dis.</td>
            <td class="text-center">Taxable Value</td>
            <td colspan="2" class="text-center">CGST <?php if($taxation_type=="SGST CGST"){ ?>(<?= $service_tax_per/2 ?>%)<?php }?></td>
            <td colspan="2" class="text-center">SGST <?php if($taxation_type=="SGST CGST"){ ?>(<?= $service_tax_per/2 ?>%)<?php }?></td>
            <td colspan="2" class="text-center">IGST<?php if($taxation_type=="IGST"){ ?>(<?= $service_tax_per ?>%)<?php }?></td>  
            <td colspan="2" class="text-center">UGST<?php if($taxation_type=="UGST"){ ?>(<?= $service_tax_per ?>%)<?php }?></td>
            <td class="text-center">Total</td>
          </tr>
          <tr>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center">Rate</td>
            <td class="text-center">Amt</td>
            <td class="text-center">Rate</td>
            <td class="text-center">Amt</td>
            <td class="text-center">Rate</td>
            <td class="text-center">Amt</td>
            <td class="text-center">Rate</td> 
            <td class="text-center">Amt</td>
            <td class="text-center"></td>
          </tr>
          <tr>
           <?php  
           $count = 1;
           if(sizeof($hotel_list_arr)>0){
            $sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Hotel / Accommodation'"));
            $sac_code = $sq_sac['hsn_sac_code'];
            $tax_total = 0;
            $hotel_total = 0;
            for($i=0;$i<sizeof($hotel_list_arr);$i++){
                //Applied Tax
                $room_cost = 0;
                $tax_amount = 0;
                $total_amount = 0;
                $tax_arr = explode(',',$hotel_list_arr[$i]->service->hotel_arr->tax);
                for($j=0;$j<sizeof($hotel_list_arr[$i]->service->item_arr);$j++){
                  $room_types = explode('-',$hotel_list_arr[$i]->service->item_arr[$j]);
                  $room_cost = $room_types[2];
                  $h_currency_id = $room_types[3];

                  $tax = ($room_cost * $tax_arr[1] / 100);
                  
                  //Convert into default currency
                  $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
                  $from_currency_rate = $sq_from['currency_rate'];
                  $room_cost = ($from_currency_rate / $to_currency_rate * $room_cost);
                  $tax = ($from_currency_rate / $to_currency_rate * $tax);

                  $tax_amount += $tax;
                  $total_amount = $total_amount + $room_cost;
                  
                  $tax_total = 0;
                  $hotel_total = 0;
                  for($i=0;$i<sizeof($hotel_list_arr);$i++){
                    //Applied Tax
                    $room_cost = 0;
                    $tax_amount = 0;
                    $total_amount = 0;
                    $tax_arr = explode(',',$hotel_list_arr[$i]->service->hotel_arr->tax);
                    for($j=0;$j<sizeof($hotel_list_arr[$i]->service->item_arr);$j++){
                      $room_types = explode('-',$hotel_list_arr[$i]->service->item_arr[$j]);
                      $room_cost  += $room_types[2];
                      $h_currency_id = $room_types[3];
                      $tax = ($room_cost * $tax_arr[1] / 100);
                      //Convert into default currency
                      $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
                      $from_currency_rate = $sq_from['currency_rate'];
                      $room_cost = ($from_currency_rate / $to_currency_rate * $room_cost);
                      $tax = ($from_currency_rate / $to_currency_rate * $tax);

                      $tax_amount = $tax;
                      $total_amount = $total_amount + $room_cost;
                    }
                    $price_total += $room_cost;
                    $tax_total += $tax_amount;
                    $hotel_total += $total_amount;
                  
                  }
                }
                $row_total = $price_total + $tax_total;
                ?>
                <tr>
                  <td><?= $count ?></td>
                  <td><strong class="font_5">Hotel</strong></td>
                  <td><?= $sac_code ?></td>
                  <td><?= 'NA' ?></td>
                  <td><?= '' ?></td>
                  <td class="text-right"><?php echo number_format($price_total,2); ?></td>
                  <td class="text-right"><?php echo number_format(0,2); ?></td>
                  <td class="text-right"><?php echo number_format($price_total,2); ?></td>
                  <?php if($tax_arr[0] == 'SGST+CGST'){ ?>
                    <td><?php echo ($tax_arr[1]/2); ?></td>
                    <td><?php echo number_format($tax_total/2,2); ?></td>
                    <td><?php echo ($tax_arr[1]/2); ?></td>
                    <td><?php echo number_format($tax_total/2,2); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  <?php }
                  else if($tax_arr[0] == 'IGST'){ ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                    <td class="text-right"><?php echo number_format($tax_total,2); ?></td>
                    <td></td>
                    <td></td>
                  <?php }
                  else{ ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                    <td class="text-right"><?php echo number_format($tax_total,2); ?></td>
                  <?php } ?>
                  <td class="text-right"><?php echo number_format($row_total,2); ?></td>
              </tr>
          <?php } }
           if(sizeof($transfer_list_arr)>0){
            $sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Car Rental'"));
            $sac_code = $sq_sac['hsn_sac_code'];
            
            $trtax_total = 0;
            $transfer_total = 0;
            for($i=0;$i<sizeof($transfer_list_arr);$i++){
                  
              for($j=0;$j<sizeof($transfer_list_arr[$i]->service);$j++){
                $tax_arr = explode('-',$transfer_list_arr[$i]->service->service_arr[$j]->taxation);
                $transfer_cost = explode('-',$transfer_list_arr[$i]->service->service_arr[$j]->transfer_cost);
                $room_cost = $transfer_cost[0];
                $h_currency_id = $transfer_cost[1];
                $tax_amount = ($room_cost * $tax_arr[1] / 100);
                $total_amount = $room_cost + $tax_amount;
                //Convert into default currency
                $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
                $from_currency_rate = $sq_from['currency_rate'];
                $room_cost1 = ($from_currency_rate / $to_currency_rate * $room_cost);
                $tax_amount1 = ($from_currency_rate / $to_currency_rate * $tax_amount);
                
                $trprice_total += $room_cost1;
                $trtax_total += $tax_amount1;
                $transfer_total += $room_cost1 + $tax_amount1;
                ?>
                <tr>
                  <td><?= $count ?></td>
                  <td><strong class="font_5">Transfer</strong></td>
                  <td><?= $sac_code ?></td>
                  <td><?= 'NA' ?></td>
                  <td><?= '' ?></td>
                  <td class="text-right"><?php echo number_format($trprice_total,2); ?></td>
                  <td class="text-right"><?php echo number_format(0,2); ?></td>
                  <td class="text-right"><?php echo number_format($trprice_total,2); ?></td>
                  <?php if($tax_arr[0] == 'SGST+CGST'){ ?>
                    <td><?php echo ($tax_arr[1]/2); ?></td>
                    <td><?php echo number_format($trtax_total/2,2); ?></td>
                    <td><?php echo ($tax_arr[1]/2); ?></td>
                    <td><?php echo number_format($trtax_total/2,2); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  <?php }
                  else if($tax_arr[0] == 'IGST'){ ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                    <td class="text-right"><?php echo number_format($trtax_total,2); ?></td>
                    <td></td>
                    <td></td>
                  <?php }
                  else{ ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                    <td class="text-right"><?php echo number_format($trtax_total,2); ?></td>
                  <?php } ?>
                  <td class="text-right"><?php echo number_format($transfer_total,2); ?></td>
              </tr>
          <?php } }
           }
           if(sizeof($activity_list_arr)>0){
            $sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Excursion'"));
            $sac_code = $sq_sac['hsn_sac_code'];
            
            $acttax_total = 0;
            $activity_total = 0;
            for($i=0;$i<sizeof($activity_list_arr);$i++){
                $tax_arr = explode('-',$activity_list_arr[$i]->service->service_arr[0]->taxation);
                $transfer_cost1 = explode('-',$activity_list_arr[$i]->service->service_arr[0]->transfer_type);
                $room_cost = $transfer_cost1[1];
                $h_currency_id = $transfer_cost1[2];
                $tax_amount = ($room_cost * $tax_arr[1] / 100);
                $total_amount = $room_cost + $tax_amount;
                //Convert into default currency
                $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
                $from_currency_rate = $sq_from['currency_rate'];
                $room_cost1 = ($from_currency_rate / $to_currency_rate * $room_cost);
                $tax_amount1 = ($from_currency_rate / $to_currency_rate * $tax_amount);
                
                $actprice_total += $room_cost1;
                $acttax_total += $tax_amount1;
                $activity_total += $room_cost1 + $tax_amount1;
                ?>
                <tr>
                  <td><?= $count ?></td>
                  <td><strong class="font_5">Activity</strong></td>
                  <td><?= $sac_code ?></td>
                  <td><?= 'NA' ?></td>
                  <td><?= '' ?></td>
                  <td class="text-right"><?php echo number_format($room_cost1,2); ?></td>
                  <td class="text-right"><?php echo number_format(0,2); ?></td>
                  <td class="text-right"><?php echo number_format($room_cost1,2); ?></td>
                  <?php if($tax_arr[0] == 'SGST+CGST'){ ?>
                    <td><?php echo ($tax_arr[1]/2); ?></td>
                    <td><?php echo number_format($tax_amount1/2,2); ?></td>
                    <td><?php echo ($tax_arr[1]/2); ?></td>
                    <td><?php echo number_format($tax_amount1/2,2); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  <?php }
                  else if($tax_arr[0] == 'IGST'){ ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                    <td class="text-right"><?php echo number_format($tax_amount1,2); ?></td>
                    <td></td>
                    <td></td>
                  <?php }
                  else{ ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                    <td class="text-right"><?php echo number_format($tax_amount1,2); ?></td>
                  <?php } ?>
                  <td class="text-right"><?php echo number_format($activity_total,2); ?></td>
              </tr>
            <?php }
          }
          if(sizeof($tours_list_arr)>0){
           $sq_sac = mysql_fetch_assoc(mysql_query("select * from sac_master where service_name='Excursion'"));
           $sac_code = $sq_sac['hsn_sac_code'];
           
           $tourstax_total = 0;
           $tours_total = 0;
           for($i=0;$i<sizeof($tours_list_arr);$i++){
               $tax_arr = explode('-',$tours_list_arr[$i]->service->service_arr[0]->taxation);
               $room_cost = $tours_list_arr[$i]->service->service_arr[0]->total_cost;
               $h_currency_id = $tours_list_arr[$i]->service->service_arr[0]->currency_id;
               $tax_amount = ($room_cost * $tax_arr[1] / 100);
               $total_amount = $room_cost + $tax_amount;
               //Convert into default currency
               $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
               $from_currency_rate = $sq_from['currency_rate'];
               $room_cost1 = ($from_currency_rate / $to_currency_rate * $room_cost);
               $tax_amount1 = ($from_currency_rate / $to_currency_rate * $tax_amount);
               
               $toursprice_total += $room_cost1;
               $tourstax_total += $tax_amount1;
               $tours_total += $room_cost1 + $tax_amount1;
               ?>
               <tr>
                 <td><?= $count ?></td>
                 <td><strong class="font_5">Combo Tours</strong></td>
                 <td><?= $sac_code ?></td>
                 <td><?= 'NA' ?></td>
                 <td><?= '' ?></td>
                 <td class="text-right"><?php echo number_format($room_cost1,2); ?></td>
                 <td class="text-right"><?php echo number_format(0,2); ?></td>
                 <td class="text-right"><?php echo number_format($room_cost1,2); ?></td>
                 <?php if($tax_arr[0] == 'SGST+CGST'){ ?>
                   <td><?php echo ($tax_arr[1]/2); ?></td>
                   <td><?php echo number_format($tax_amount1/2,2); ?></td>
                   <td><?php echo ($tax_arr[1]/2); ?></td>
                   <td><?php echo number_format($tax_amount1/2,2); ?></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                 <?php }
                 else if($tax_arr[0] == 'IGST'){ ?>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                   <td class="text-right"><?php echo number_format($tax_amount1,2); ?></td>
                   <td></td>
                   <td></td>
                 <?php }
                 else{ ?>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td class="text-right"><?php echo number_format($tax_arr[1],2); ?></td>
                   <td class="text-right"><?php echo number_format($tax_amount1,2); ?></td>
                 <?php } ?>
                 <td class="text-right"><?php echo number_format($tours_total,2); ?></td>
             </tr>
           <?php }
         }
          $net_amount = $net_amount + $row_total + $transfer_total + $activity_total + $tours_total;
          
          $total_amt_before_tax = $price_total + $trprice_total + $actprice_total + $toursprice_total;
          $total_amt_tax = $tax_total + $acttax_total + $trtax_total + $tourstax_total;
          $total_service_tax_subtotal = $tax_total;
          if($query['coupon_code'] != ''){
            $sq_hotel_count = mysql_num_rows(mysql_query("select offer,offer_amount from hotel_offers_tarrif where coupon_code='$query[coupon_code]'"));
            if($sq_hotel_count > 0){
              $sq_coupon = mysql_fetch_assoc(mysql_query("select offer as offer,offer_amount from hotel_offers_tarrif where coupon_code='$query[coupon_code]'"));
            }else{
              $sq_coupon = mysql_fetch_assoc(mysql_query("select offer_in as offer,offer_amount from excursion_master_offers where coupon_code='$query[coupon_code]'"));
            }
            if($sq_coupon['offer']=="Flat"){
              $net_amount1 = $net_amount - $sq_coupon['offer_amount'];
            }else{
              $net_amount1 = $net_amount - ($hotel_total*$sq_coupon['offer_amount']/100);
            }
          }
          else{
            $net_amount1 = $net_amount;
          }
          $amount_in_word = $amount_to_word->convert_number_to_words($net_amount1); ?>
          <tfoot>
            <tr class="hightlited_row">
              <td colspan="3" class="text-center">Total</td>
              <td class="text-center"></td>
              <td class="text-right"></td>
              <td class="text-right"></td>
              <td class="text-right"></td>
              <td class="text-right"></td>
              <td class="text-center"></td>
              <td class="text-right"></td>
              <td class="text-center"></td>
              <td class="text-right"></td>
              <td class="text-center"></td>
              <td class="text-right"></td>
              <td class="text-right"></td>
              <td class="text-right"></td>
            <td class="text-center"><?php echo number_format($net_amount,2); ?></td>
            </tr>
          </tfoot>
        </table>
       </div>
     </div>
    </div>
  </div>
  <!-- invoice_receipt_detail_table-->
  <div class="main_block inv_rece_table">
    <div class="row">
      <div class="col-md-8">
       <div class="col-md-12 no-pad mg_tp_10">
         <div class="table-responsive">
          <table class="table no-marg gst_invoice" style="padding: 0 !important;">
            <tr>
              <td class="text-right col-md-4">Bank Details :</td>
              <td class="col-md-8"><?=  ($branch_status=='yes' && $role!='Admin') ? $branch_details['bank_name'] : $bank_name_setting ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">Bank A/C No. :</td>
              <td class="col-md-8"><?=  ($branch_status=='yes' && $role!='Admin') ? $branch_details['bank_acc_no'] : $bank_acc_no ?></td>
            </tr>
            <tr>
              <td class="text-right col-md-4">Branch IFSC Code :</td>
              <td class="col-md-8"><?= ($branch_status=='yes' && $role!='Admin') ? $branch_details['ifsc_code'] : $bank_ifsc_code ?></td>
            </tr>
          </table>
         </div>
       </div>
       <div class="col-md-12 no-pad mg_tp_10">
        <div class="table-responsive">
          <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">
            <tr class="hightlited_row">
              <td class="text-right col-md-4">Total Invoice Amount in Words :</td>
              <td class="col-md-8"><?php echo $amount_in_word; ?></td>
            </tr>
          </table>
        </div>
       </div>
       <div class="col-md-9 no-pad mg_tp_10">
        <h5 class="no-marg" style="font-size:10px;">Terms And Condition :</h5>
        <p class="less_opact" style="font-size:8px;"><?= $sq_terms_cond['terms_and_conditions'] ?></p>
       </div>
      </div>

     <div class="col-md-4">
       <div class="row">
         <div class="col-md-12">
           <div class="table-responsive">
            <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">  
              <tr>  
                <td class="text-right col-md-8">Total Amount Before Tax :</td>  
                <td class="text-right col-md-4"><?= number_format($total_amt_before_tax,2) ?></td> 
              </tr> 
              <tr>  
                <td class="text-right col-md-8">Add : CGST <?php if($tax_arr[0]=="SGST+CGST"){ ?>@<?= $tax_arr[1]/2 ?>%<?php } ?> :</td> 
                <td class="text-right col-md-4"><?php if($tax_arr[0]=="SGST+CGST"){ echo number_format($total_amt_tax/2,2); } ?></td>  
              </tr> 
              <tr>  
                <td class="text-right col-md-8">Add : SGST <?php if($tax_arr[0]=="SGST+CGST"){ ?>@<?= $tax_arr[1]/2 ?>%<?php } ?> :</td> 
                <td class="text-right col-md-4"><?php if($tax_arr[0]=="SGST+CGST"){ echo number_format($total_amt_tax/2,2); } ?></td>  
              </tr> 
              <tr>  
                <td class="text-right col-md-8">Add : IGST <?php if($tax_arr[0]=="IGST"){ ?>@<?= $tax_arr[1] ?>%<?php } ?> :</td>  
                <td class="text-right col-md-4"><?php if($tax_arr[0]=="IGST"){ echo number_format($total_amt_tax,2); } ?></td> 
              </tr> 
              <tr>  
                <td class="text-right col-md-8">Add : UGST <?php if($tax_arr[0]=="UGST"){ ?>@<?= $tax_arr[1] ?>%<?php } ?> :</td>  
                <td class="text-right col-md-4"><?php if($tax_arr[0]=="UGST"){ echo number_format($total_amt_tax,2); } ?></td> 
              </tr> 
              <tr>  
                <td class="text-right col-md-8">Tax Amount : GST @<?= $tax_arr[1] ?>% :</td>  
                <td class="text-right col-md-4"><?php echo number_format($total_amt_tax,2) ?></td>  
              </tr> 
              <tr class="hightlited_row"> 
                <td class="text-right col-md-8">Total Amount After Tax :</td> 
                <td class="text-right col-md-4"><?php echo number_format($net_amount,2); ?></td>  
              </tr> 
              <?php
              if($query['coupon_code'] != ''){ ?>
              <tr> 
                <td class="text-right col-md-8">Coupon Discount :</td> 
                <td class="text-right col-md-4"><?php echo '-'.number_format($sq_coupon['offer_amount'],2); ?></td>  
              </tr> 
              <tr class="hightlited_row"> 
                <td class="text-right col-md-8">Net Amount :</td> 
                <td class="text-right col-md-4"><?php echo number_format($net_amount1,2); ?></td>  
              </tr>
              <?php } ?>
            </table>
           </div>
         </div>
      </div>

       <div class="row mg_tp_10">
         <div class="col-md-12">
           <div class="table-responsive">
            <table class="table no-marg mg_tp_5 gst_invoice" style="padding: 0 !important;">
              <tr>
                <td class="text-right col-md-8">GST Payable onReverse Charge :</td>
                <td class="text-right col-md-4"></td>
              </tr>
            </table>
           </div>
         </div>
         <div class="col-md-12 text-center">
           <small style="font-size: 6px;">Certified that the particulars given above are true & Correct</small>
         </div>
       </div>

      <div class="row mg_tp_10">

        <div class="col-md-3"></div>

        <div class="col-md-9">
          <h3 class="no-marg" style="font-size:12px;">For <?= $app_name ?></h3>
          <div class="signature_block"></div>
          <h5 class="no-marg" style="font-size:10px;">Authorised Signatory</h5>
          <small style="font-size: 7px;">[E&OE] Sunbject to Pune Jurisdiction Only.</small>
        </div>

      </div>

     </div>

    </div>
  </div>

</section>

</body>
</html>