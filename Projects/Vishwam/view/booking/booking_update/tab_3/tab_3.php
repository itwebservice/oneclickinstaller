<?php
$sq1 = mysql_query("select * from travelers_details where traveler_group_id ='$traveler_group_id' and adolescence='Adult' and status='Active'");
$total_adult = mysql_num_rows($sq1);

$sq1 = mysql_query("select * from travelers_details where traveler_group_id ='$traveler_group_id' and adolescence='Children' and status='Active' ");
$total_children = mysql_num_rows($sq1);

$sq1 = mysql_query("select * from travelers_details where traveler_group_id ='$traveler_group_id' and adolescence='Infant' and status='Active' ");
$total_infant = mysql_num_rows($sq1);

$total_seats = $total_adult + $total_children + $total_infant;
?>
<form id="frm_tab_3">

<div class="app_panel"> 

 <!--=======Header panel======-->
    <div class="app_panel_head mg_bt_20">
        <div class="container">
            <h2 class="pull-left"></h2>
              <div class="pull-right header_btn">
                <button type="button" onclick="switch_back_tab_2()">
                    <a>
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </button>
              </div>
              <div class="pull-right header_btn">
                <button>
                    <a></a>
                </button>
              </div>
        </div>
    </div> 

  <!--=======Header panel end======-->
    <div class="app_panel_content no-pad">
        <div class="container">
            <div class="row"> 
                <div class="panel panel-default panel-body main_block bg_light mg_bt_30">
                        <legend>Costing Details</legend>
                        <div class="bg_white main_block panel-default-inner">
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>No. of Adult's</label>
                            <input type="text" id="txt_adult_seats" name="txt_adult_seats" value="<?php echo $total_adult; ?>" class="text-center" title="No. of Adult's" readonly />            
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>No. of Children's</label>
                            <input type="text" id="txt_children_seats" name="txt_children_seats" value="<?php echo $total_children; ?>" class="text-center" title="No. of Children's" readonly/>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>No. of Infant's</label>
                            <input type="text" id="txt_infant_seats" title="No. of infant's" name="txt_infant_seats" value="<?php echo $total_infant; ?>" class="text-center" readonly />           
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>Total Passenger</label>
                            <input type="text" id="txt_total_seats" title="Total Seats" name="txt_total_seats" value="<?php echo $total_seats; ?>" class="text-center" readonly />            
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>Adult</label>
                            <input type="text" id="txt_adult_expense" title="Adult Cost" name="txt_adult_expense" class="text-right" value="<?php echo $tourwise_details['adult_expense'] ?>" onchange="calculate_total_discount(); validate_balance(this.id)" />          
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>Children</label>
                            <input type="text" id="txt_children_expense" title="Children Cost"  name="txt_children_expense" class="text-right" value="<?php echo $tourwise_details['children_expense'] ?>" onchange="calculate_total_discount(); validate_balance(this.id)" />           
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>Infant</label>
                            <input type="text" id="txt_infant_expense" title="Infant Cost"  name="txt_infant_expense" class="text-right" value="<?php echo $tourwise_details['infant_expense'] ?>" onchange="calculate_total_discount(); validate_balance(this.id)" />         
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs text-center">
                            <label>Total Amount</label>
                            <input type="text" id="txt_total_expense" title="Total Cost"  name="txt_total_expense" class="text-right amount_feild_highlight" value="<?php echo $tourwise_details['tour_fee'] ?>" />   
                        </div>
                </div>
                </div>   
                 <div class="panel panel-default panel-body main_block bg_light mg_bt_30">
                    <legend>Visa & Insurance Details</legend>
                    <div class="bg_white main_block panel-default-inner">
                        <div class="text-center">
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
                                <input type="text" id="visa_country_name" onchange="validate_city(this.id)" name="visa_country_name" placeholder="Country Name" title="Country Name" value="<?= $tourwise_details['visa_country_name'] ?>">
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
                                <input type="text" id="visa_amount" name="visa_amount" placeholder="Amount" title="Amount" onchange="validate_balance(this.id); calculate_total_discount()" value="<?= $tourwise_details['visa_amount'] ?>">
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
                                <input type="text" id="visa_service_charge" name="visa_service_charge" title="Service Charge" class="text-right" onchange="validate_balance(this.id); calculate_total_discount()" value="<?= $tourwise_details['visa_service_charge'] ?>" />            
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
                                <select name="visa_taxation_id" id="visa_taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'visa_service_tax', 'calculate_total_discount');">
                                    <?php 
                                    if($tourwise_details['visa_taxation_id']!='0'){
                                        $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$tourwise_details[visa_taxation_id]'"));

                                        $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                                        ?>
                                        <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
                                        <?php } ?>
                                    <?php get_taxation_dropdown(); ?>
                                </select>
                                <input type="hidden" id="visa_service_tax" name="visa_service_tax" value="<?= $tourwise_details['visa_service_tax'] ?>">        
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
                                <input type="text" id="visa_service_tax_subtotal" name="visa_service_tax_subtotal" value="<?= $tourwise_details['visa_service_tax_subtotal'] ?>" title="Tax Amount" readonly>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10">
                                <input type="text" id="visa_total_amount" class="amount_feild_highlight text-right" name="visa_total_amount" placeholder="Total Amount" title="Total Amount" onchange="validate_balance(this.id); calculate_total_discount()" value="<?= $tourwise_details['visa_total_amount'] ?>" readonly>
                            </div>    
                        </div>
                        <div class="text-center">
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
                                <input type="text" id="insuarance_company_name" name="insuarance_company_name" onchange="validate_company(this.id)" placeholder="Insurance Company" title="Insurance Company" value="<?= $tourwise_details['insuarance_company_name'] ?>">
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
                                <input type="text" id="insuarance_amount" name="insuarance_amount" placeholder="Insurance Amount" title="Insurance Amount" onchange="validate_balance(this.id); calculate_total_discount()" value="<?= $tourwise_details['insuarance_amount'] ?>">
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
                                <input type="text" id="insuarance_service_charge" title="Service Charge" name="insuarance_service_charge"  class="text-right" onchange="validate_balance(this.id); calculate_total_discount()" value="<?= $tourwise_details['insuarance_service_charge'] ?>" />            
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
                                <select name="insuarance_taxation_id" id="insuarance_taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'insuarance_service_tax', 'calculate_total_discount');">
                                    <?php 
                                    if($tourwise_details['insuarance_taxation_id']!='0'){
                                        $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$tourwise_details[insuarance_taxation_id]'"));
                                        $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                                        ?>
                                        <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
                                        <?php
                                    }      ?>
                                    <?php get_taxation_dropdown(); ?>
                                </select>
                                <input type="hidden" id="insuarance_service_tax" name="insuarance_service_tax" value="<?= $tourwise_details['insuarance_service_tax'] ?>">        
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
                                <input type="text" id="insuarance_service_tax_subtotal" name="insuarance_service_tax_subtotal" value="<?= $tourwise_details['insuarance_service_tax_subtotal'] ?>" placeholder="Tax Amount" title="Tax Amount" readonly>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
                                <input type="text" id="insuarance_total_amount" class="amount_feild_highlight text-right" name="insuarance_total_amount" placeholder="Total Amount" title="Total Amount" onchange="validate_balance(this.id); calculate_total_discount()"  value="<?= $tourwise_details['insuarance_total_amount'] ?>" readonly>
                            </div>    
                        </div>
                    </div>
                </div>
                 <div class="panel panel-default panel-body main_block bg_light mg_bt_30">
                    <legend>Tours and Travels Amount</legend>
                    <div class="bg_white main_block panel-default-inner">
                        <div class="text-right text_center_xs mg_tp_10">
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label>Repeater's Discount</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10 text_center_xs">        
                                <input type="text" id="txt_repeater_discount" title="Repeater's Travelling Discount" name="txt_repeater_discount" class="text-right" placeholder="Repeaters Travelling Discount" onkeyup="calculate_total_discount()" value="<?php echo $tourwise_details['repeater_discount'] ?>"  onchange="validate_balance(this.id);" />
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label>Adjustment discount</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10 text_center_xs">        
                                <input type="text" id="txt_adjustment_discount" title="Adjustment discount" name="txt_adjustment_discount" class="text-right" placeholder="Adjustment Discount" onkeyup="calculate_total_discount()" value="<?php echo $tourwise_details['adjustment_discount'] ?>"  onchange="validate_balance(this.id);"/>
                            </div>

                            <?php $total_discount = $tourwise_details['repeater_discount'] + $tourwise_details['adjustment_discount']; ?>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label class="theme_col">Total Discount</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10 text_center_xs">        
                                <input type="text" id="txt_total_discount" name="txt_total_discount" title="Total Discount" class="text-right amount_feild_highlight" placeholder="Total Discount " value="<?php echo $total_discount ?>" readonly />
                            </div>
                        </div>
                        <div class="text-right text_center_xs">
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label>Tour Amount</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">        
                                <input type="text" id="txt_tour_fee" name="txt_tour_fee" class="text-right" title="Tour Amount" Placeholder="Tour Amount" readonly value="<?php echo $tourwise_details['tour_fee_subtotal_1'] ?>"/>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label>Tax(%)</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">        
                                <select name="tour_taxation_id" id="tour_taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax_per', 'calculate_total_discount');">
                                    <?php 
                                    if($tourwise_details['tour_taxation_id']=="" || $tourwise_details['tour_taxation_id']==0){
                                        ?>
                                        <option value="0">Tax</option>  
                                        <?php   
                                    }
                                    else{
                                        $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$tourwise_details[tour_taxation_id]'"));
                                        $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                                        ?>
                                        <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
                                        <?php
                                    }
                                    ?>
                                    <?php get_taxation_dropdown(); ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label>Tax Total</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">  
                                <input type="hidden" id="service_tax_per" name="service_tax_per" value="<?= $tourwise_details['service_tax_per'] ?>">
                                <input type="text" id="txt_service_charge" name="txt_service_charge" class="text-right" disabled value="<?php echo $tourwise_details['service_tax'] ?>"/>
                            </div>   
                        </div>
                        <div class="text-right text_center_xs">
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label>Total</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">        
                                <input type="text" id="txt_total_tour_fee1" name="txt_total_tour_fee1" class="text-right" placeholder="Total" title="Total" readonly value="<?php echo $tourwise_details['tour_fee_subtotal_2'] ?>"/>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label for="visa_total_amount1">Visa Amount</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">        
                                <input type="text" id="visa_total_amount1" name="visa_total_amount1" placeholder="Visa Amount" title="Visa Amount" onchange="validate_balance(this.id)" value="<?= $tourwise_details['visa_total_amount'] ?>" readonly>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label for="insuarance_total_amount1">Insurance Amount</label></div>
                                <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">        
                                    <input type="text" id="insuarance_total_amount1" name="insuarance_total_amount1" placeholder="Insuarance Amount" title="Insurance Amount" placeholder="Insurance Amount" onchange="validate_balance(this.id);" value="<?= $tourwise_details['insuarance_total_amount'] ?>" readonly>
                                </div>
                        </div>
                        <div class="text-right text_center_xs mg_bt_10">
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10"><label class="theme_col">Total Tour Amount</label></div>
                            <div class="col-md-2 col-sm-3 col-xs-12 mg_bt_10">        
                                <input type="text" id="txt_total_tour_fee" name="txt_total_tour_fee" class="amount_feild_highlight text-right" placeholder="Total Tour Amount" title="Total Tour Amount" value="<?= $tourwise_details['total_tour_fee'] ?>" readonly />
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="panel panel-default panel-body main_block bg_light mg_bt_30">
                    <legend>Booking Summary</legend>
                    <div class="bg_white main_block panel-default-inner">
                        <?php date_default_timezone_set('Asia/Kolkata'); ?>
                        <div class="text-center mg_bt_20">
                            <div class="col-xs-12">  
                                <textarea id="txt_special_request" name="txt_special_request" rows="2" placeholder="Enter your special request i.e Veg food, driver language : English" title="Enter your special request i.e Veg food, driver language : English"><?php echo $tourwise_details['special_request'] ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </div>
<div class="panel panel-default panel-body pad_8 text-center main_block bg_light mg_bt_0">
    <button type="button" class="btn btn-sm btn-info ico_left" onclick="switch_back_tab_2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button> &nbsp;&nbsp;&nbsp;
    <button class="btn btn-sm btn-success" id="btn_update_booking"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
</div>

</form>

<?= end_panel() ?>
<script>
    $('#txt_payment_date1, #txt_payment_date2, #txt_balance_due_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
    $('#txt_date').datetimepicker({ format:'d-m-Y H:i:s' });
    /////////// Calculations for tour amount cal ///////////////////////////
    function tour_cost_calculate_u1()
    {
      var adult_expense = $('#txt_adult_expense').val();  
      var children_expense = $('#txt_children_expense').val();
      var infant_expense = $('#txt_infant_expense').val();

      if(adult_expense==""){ adult_expense = 0;}
      if(children_expense==""){ children_expense = 0;}
      if(infant_expense==""){infant_expense = 0;}

      var total = parseFloat(adult_expense) + parseFloat(children_expense) + parseFloat(infant_expense);

      $('#txt_total_expense').val(total.toFixed(2));
      
      //$('#txt_total_tour_fee').val(total.toFixed(2));
      //$('#txt_tour_fee').val(total.toFixed(2));
      //$('#txt_total_tour_fee1').val(total.toFixed(2));
    }
    function switch_back_tab_2()
    {
        $('#tab_3_head').removeClass('active');
        $('#tab_2_head').addClass('active');
        $('.bk_tab').removeClass('active');
        $('#tab_2').addClass('active');
        $('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
    }
</script>
<script src="../js/booking_update.js"></script>
