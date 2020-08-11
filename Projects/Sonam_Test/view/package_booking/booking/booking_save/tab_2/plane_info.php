<div class="row mg_bt_10">
    <div class="col-md-4 col-sm-6">
        <input type="checkbox" id="chk_plane_select_all" onchange="select_all('tbl_plane_travel_details_dynamic_row',this.id);calculate_plane_expense('tbl_plane_travel_details_dynamic_row')" checked>&nbsp;&nbsp;<label for="chk_plane_select_all">Select All</label>
    </div>
    <div class="col-md-8 col-sm-6 text-right">
        <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_plane_travel_details_dynamic_row')" title="Add Row"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_plane_travel_details_dynamic_row');calculate_plane_expense('tbl_plane_travel_details_dynamic_row')" title="Delete Row"><i class="fa fa-trash"></i></button>
        <!--  Code to uploadf button -->
        <div class="div-upload" id="div_upload_button">
            <div id="package_plane_upload" class="upload-button"><span>Ticket</span></div><span id="package_plane_status" ></span>
            <ul id="files" ></ul>
            <input type="hidden" id="txt_plane_upload_dir" name="txt_plane_upload_dir">
        </div>
    </div>
</div>   
                        
<div class="row mg_bt_30"> <div class="col-xs-12"> <div class="table-responsive">
                        
    <table id="tbl_plane_travel_details_dynamic_row" name="tbl_plane_travel_details_dynamic_row" class="table table-bordered table-hover table-striped pd_bt_51 no-marg" style="width: 1400px;">
        <tr>
        <td ><input id="check-btn-plane-1" type="checkbox" onchange="calculate_plane_expense('tbl_plane_travel_details_dynamic_row')" checked ></td>
        <td><input maxlength="15" type="text" id="" name="username" value="1" placeholder="Sr.No." disabled/></td>

        <td><input type="text" id="txt_plane_date-1" name="txt_plane_date-1" onchange="validate_transportDate('txt_plane_date-1' , 'txt_arravl-1');get_to_datetime(this.id,'txt_arravl-1')" title="Departure Date & Time" placeholder="Departure Date & Time" value="<?= Date('d-m-Y  H:i:s')?>" style="width: 154px !important;"/></td>
        <td><select id="from_city-1" name="from_city-1" style="width: 150px;" class="form-control" title="Select City Name" onchange="validate_location('to_city-1' ,'from_city-1');airport_reflect(this.id)" >
            <?php get_cities_dropdown(); ?>
        </select></td>
       <td><select id="plane_from_location-1" name="plane_from_location-1" class="form-control" style="width:150px" title="Sector From">
            <option value="">*Sector From</option>
           </select></td>    
        <td><select id="to_city-1" name="to_city-1" style="width: 150px;" class="form-control form-control" title="Select City Name" onchange="validate_location('from_city-1' , 'to_city-1'); airport_reflect1(this.id)" >
        <?php get_cities_dropdown(); ?>
        </select></td>
        <td><select id="plane_to_location-1" name="plane_to_location-1" class="form-control" style="width:150px" title="Sector To">
            <option value="">*Sector To</option>
        </select></td>             
        <td><select id="txt_plane_company-1" name="txt_plane_company-1" class="app_select2" style="width:150px" title="Airline Name">
            <option value="">*Airline Name</option>
              <?php get_airline_name_dropdown(); ?>
        </select></td>
        <td style="width: 30px;"><input type="text" id="txt_plane_seats-1" onchange="validate_balance(this.id)" name="txt_plane_seats-1" placeholder="Total Seats" title="Total Seats" maxlength="3"  /></td>
        <td style="width: 130px;"><input type="text" id="txt_plane_amount-1" name="txt_plane_amount-1" placeholder="*Amount" onchange="validate_balance(this.id)" title="Amount" onkeyup=" calculate_plane_expense('tbl_plane_travel_details_dynamic_row');" /></td>
        <td><input type="text" id="txt_arravl-1" onchange="validate_arrivalDate('txt_plane_date-1' , 'txt_arravl-1')" name="txt_arravl-1" class="app_datetimepicker" placeholder="Arrival Date & Time" value="<?= Date('d-m-Y H:i:s')?>" title="Arrival Date & Time" style="width: 154px !important;"></td>

        </tr>
    </table>

</div></div></div>
<div class="row">
    <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
        <label>Subtotal</label>
        <input type="text" id="txt_plane_expense" name="txt_plane_expense" placeholder="Subtotal" title="Subtotal" class="text-right" value="0.00" disabled />            
    </div>
    <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
        <label>Service Charge</label>
        <input type="text" id="txt_plane_service_charge" name="txt_plane_service_charge"  class="text-right" placeholder="Service Charge" title="Service Charge" value="0.00" onchange="validate_balance(this.id); calculate_total_plane_expense()" />            
    </div>
    <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
        <label>Tax</label>
        <select name="plane_taxation_id" id="plane_taxation_id" title="Tax" onchange="generic_tax_reflect(this.id, 'plane_service_tax', 'calculate_total_plane_expense');">
            <?php get_taxation_dropdown(); ?>
        </select>
        <input type="hidden" id="plane_service_tax" name="plane_service_tax" value="0">            
    </div>
    <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
        <label>Tax Amount</label>
        <input type="text" id="plane_service_tax_subtotal" name="plane_service_tax_subtotal" placeholder="Tax Amount" title="Tax Amount" value="0.00" disabled>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-12 mg_bt_10_xs">
        <label>Total</label>
        <input type="text" id="txt_plane_total_expense" name="txt_plane_total_expense" placeholder="Air Expense" title="Air Expense" class="text-right amount_feild_highlight" value="0.00" disabled />
    </div>
</div>
    
<script type="text/javascript">
$('#txt_plane_date-1,#txt_arravl-1').datetimepicker({ format:'d-m-Y H:i:s' });
</script>