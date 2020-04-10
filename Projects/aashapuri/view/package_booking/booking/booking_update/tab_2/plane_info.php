 <div class="row mg_bt_10">
    <div class="col-xs-12 text-right">
        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_plane_travel_details_dynamic_row')" title="Add row"><i class="fa fa-plus"></i></button>
        <!--  Code to uploadf button -->
        <div class="div-upload" id="div_upload_button">
            <div id="package_plane_upload" class="upload-button"><span>Ticket</span></div><span id="package_plane_status" ></span>
            <ul id="files" ></ul>
            <input type="hidden" id="txt_plane_upload_dir" name="txt_plane_upload_dir" value="<?= $sq_booking_info['plane_upload_ticket'] ?>">
        </div>
    </div>
</div>   

<div class="row mg_bt_30"> <div class="col-xs-12"> <div class="table-responsive">
                        
    <table id="tbl_plane_travel_details_dynamic_row" name="tbl_plane_travel_details_dynamic_row" class="table table-bordered table-hover pd_bt_51 no-marg" style="width: 1400px;">
    <?php
     $sq_plane_info_count = mysql_num_rows(mysql_query("select * from package_plane_master where booking_id='$booking_id'"));
    if($sq_plane_info_count==0)
    { ?>
        <tr>
        <td ><input id="check-btn-plane-1" type="checkbox" onchange="calculate_plane_expense('tbl_plane_travel_details_dynamic_row')" checked ></td>
        <td><input maxlength="15" type="text" id="" name="username" value="1" placeholder="Sr.No." disabled/></td>

        <td><input type="text" id="txt_plane_date-1" name="txt_plane_date-1" title="Departure Date & Time" onchange="validate_transportDate('txt_plane_date-1','txt_arravl-1');get_to_datetime(this.id,'txt_arravl-1')" placeholder="Departure Date & Time"/></td>
        <td><select id="from_city-1" name="from_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="airport_reflect(this.id)" >
            <?php get_cities_dropdown(); ?>
        </select></td>
       <td><select id="plane_from_location-1" name="plane_from_location-1" class="app_select2" style="width:150px" title="Sector From">
            <option value="">*Sector From</option>
        </select></td>    
        <td><select id="to_city-1" name="to_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="airport_reflect1(this.id)" >
        <?php get_cities_dropdown(); ?>
        </select></td>
        <td><select id="plane_to_location-1" name="plane_to_location-1" class="app_select2" style="width:150px" title="Sector To">
            <option value="">*Sector To</option>
        </select></td>
        <td><select id="txt_plane_company-1" name="txt_plane_company-1" class="app_select2" style="width:150px" title="Airline Name">
            <option value="">*Airline Name</option>
              <?php get_airline_name_dropdown(); ?>
        </select></td>
        <td style="width: 30px;"><input type="text" id="txt_plane_seats-1" name="txt_plane_seats-1" placeholder="Total Seats" title="Total Seats" maxlength="2"  /></td>
        <td style="width: 130px;"><input type="text" id="txt_plane_amount-1" name="txt_plane_amount-1" placeholder="*Amount" onchange="validate_balance(this.id)" title="Amount" onkeyup=" calculate_plane_expense('tbl_plane_travel_details_dynamic_row');" /></td>
        <td><input type="text" id="txt_arravl-1" name="txt_arravl-1" class="app_datetimepicker" onchange="validate_arrivalDate('txt_plane_date-1','txt_arravl-1')" placeholder="Arrival Date & Time" title="Arrival Date & Time"></td>
        </tr>
        <script type="text/javascript">
        $('#txt_plane_date-1,#txt_arravl-1').datetimepicker({ format:'d-m-Y H:i:s' });
        </script>
 <?php    }
    else{
    $offset = "_u";
    $count = 0;
    $sq_plane_details = mysql_query("select * from package_plane_master where booking_id='$booking_id'");
    while($row_plane_details = mysql_fetch_assoc($sq_plane_details))
    {                            
        $count++;
    ?>

        <tr>

            <td ><input id="check-btn-plane-<?= $offset.$count ?>_d" type="checkbox" onchange="calculate_plane_expense('tbl_plane_travel_details_dynamic_row')" checked disabled ></td>

            <td><input maxlength="15" type="text" id="" name="username" value="<?php echo $count ?>" placeholder="Sr.No." disabled/></td>

            <td><input type="text" id="txt_plane_date-<?= $offset.$count ?>_d" name="txt_plane_date-<?= $offset.$count ?>_d ?>" placeholder="Departure Date" title="Departure Date & Time" onchange="validate_transportDate('txt_plane_date-<?= $offset.$count ?>_d' , 'txt_arravl-<?= $offset.$count ?>_d');get_to_datetime(this.id,'txt_arravl-<?= $offset.$count ?>_d')" value="<?php echo date("d-m-Y H:i", strtotime($row_plane_details['date'])) ?>"/></td>
            <td><select id="from_city-<?= $offset.$count ?>_d" name="from_city-<?= $offset.$count ?>_d" style="width: 150px;" class="form-control" title="Select City Name" onchange="airport_reflect(this.id)">
           <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_plane_details[from_city]'")); ?>
           <option value="<?php echo $row_plane_details['from_city'] ?>"><?php echo $sq_city['city_name'] ?></option>
                        <?php get_cities_dropdown(); ?>
            </select></td>
           <td><select id="plane_from_location-<?= $offset.$count ?>_d" name="plane_from_location-<?= $offset.$count ?>_d" class="app_select2 form-control" style="width:150px">
                <option value="<?php echo $row_plane_details['from_location'] ?>"><?php echo $row_plane_details['from_location'] ?></option>
            </select></td>
            <td><select id="to_city-<?= $offset.$count ?>_d" name="to_city-<?= $offset.$count ?>_d" style="width: 150px;" class="form-control" title="Select City Name" onchange="airport_reflect1(this.id)">
                <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_plane_details[to_city]'")); ?>
                        <option value="<?php echo $row_plane_details['to_city'] ?>"><?php echo $sq_city['city_name'] ?></option>
                    <?php get_cities_dropdown(); ?>
            </select></td>
            <td><select id="plane_to_location-<?= $offset.$count ?>_d" name="plane_to_location-<?= $offset.$count ?>_d" class="app_select2 form-control" style="width:150px">
                <option value="<?php echo $row_plane_details['to_location'] ?>"><?php echo $row_plane_details['to_location'] ?></option>
            </select></td>
            <td><select id="txt_plane_company-<?= $offset.$count ?>_d" name="txt_plane_company-<?= $offset.$count ?>_d" class="app_select2" style="width:150px">
                <?php 
                 $sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_plane_details[company]'"));?>
                <option value="<?php echo $sq_airline['airline_id'] ?>"><?php echo $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></option>
                <?php get_airline_name_dropdown(); ?>
            </select></td>
            <td style="width: 30px;"><input type="text" id="txt_plane_seats-<?= $offset.$count ?>_d" name="txt_plane_seats-<?= $offset.$count ?>_d" placeholder="Total Seats" title="Total Seats"  maxlength="2" onchange="validate_balance(this.id);"  value="<?php echo $row_plane_details['seats'] ?>"/></td>
            <td style="width: 130px;"><input type="text" id="txt_plane_amount-<?= $offset.$count ?>_d" name="txt_plane_amount-<?= $offset.$count ?>_d" placeholder="Amount" title="Amount" onchange="validate_balance(this.id)"  onkeyup=" calculate_plane_expense('tbl_plane_travel_details_dynamic_row');"  value="<?php echo $row_plane_details['amount'] ?>"/></td>
            <td><input type="text" id="txt_arravl-<?= $offset.$count ?>_d" name="txt_arravl-<?= $offset.$count ?>_d" placeholder="Arrival date & time" title="Arrival date & time" onchange="validate_arrivalDate('txt_plane_date-<?= $offset.$count ?>_d' , 'txt_arravl-<?= $offset.$count ?>_d')" class="app_datetimepicker" value="<?php echo date("d-m-Y H:i:s", strtotime($row_plane_details['arraval_time'])) ?>"/></td>
            <td><input type="hidden" value="<?php echo $row_plane_details['plane_id'] ?>"></td>
        </tr>
        <script>
            $('#plane_from_location-<?= $offset.$count ?>_d,#plane_to_location-<?= $offset.$count ?>_d, #txt_plane_company-<?= $offset.$count ?>_d').select2();
            $('#txt_arravl-<?= $offset.$count ?>_d, #txt_plane_date-<?= $offset.$count ?>_d').datetimepicker({ format:'d-m-Y H:i:s' });
        </script>
    <?php }
    } ?>
    </table>
    <input type = "hidden" id="txt_plane_date_generate" value="<?php echo $count ?>">
</div>  </div> </div>

    <div class="row">
        <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
            <label>Subtotal</label>
            <input type="text" id="txt_plane_expense" name="txt_plane_expense"  class="text-right" value="<?php echo $sq_booking_info['plane_expense'] ?>" placeholder="Subtotal" title="Subtotal" disabled />
        </div>
        <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
            <label>Service Charge</label>
            <input type="text" id="txt_plane_service_charge" name="txt_plane_service_charge"  class="text-right" value="<?php echo $sq_booking_info['plane_service_charge'] ?>"placeholder="Service Charge" title="Service Charge" onchange="validate_balance(this.id); calculate_total_plane_expense()" />            
        </div>
        <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
            <label>Tax</label>
            <select name="plane_taxation_id" id="plane_taxation_id" onchange="generic_tax_reflect(this.id, 'plane_service_tax', 'calculate_total_plane_expense');">
                <?php 
                if($sq_booking_info['plane_taxation_id']!='0'){
                    $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_booking_info[plane_taxation_id]'"));
                    $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                    ?>
                    <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option>
                    <?php } ?>
                <?php get_taxation_dropdown(); ?>
            </select>
            <input type="hidden" id="plane_service_tax" name="plane_service_tax" value="<?= $sq_booking_info['plane_service_tax'] ?>">            
        </div>
        <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
            <label>Tax Amount</label>
            <input type="text" id="plane_service_tax_subtotal" name="plane_service_tax_subtotal" value="<?= $sq_booking_info['plane_service_tax_subtotal'] ?>" title="Tax Amount" disabled>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
            <label>Total</label>
            <input type="text" id="txt_plane_total_expense" name="txt_plane_total_expense" value="<?php echo $sq_booking_info['total_plane_expense'] ?>" placeholder="total expense" title="Total expense" disabled />
        </div>
    </div>    

<script>
    
function generating_plane_date()
{
    var count = $("#txt_plane_date_generate").val();
    for(var i=0; i<=count; i++)
    {
        $( "#txt_plane_date-"+i).datetimepicker({ format: "d-m-Y H:i:s"  });
    }             
}
generating_plane_date();
</script>