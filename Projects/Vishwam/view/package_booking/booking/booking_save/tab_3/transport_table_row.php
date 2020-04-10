<div class="row" style="margin-top: 5px"> <div class="col-md-12 text-right">
    <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_transport_infomration')"><i class="fa fa-plus"></i></button>
    <button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_package_transport_infomration')"><i class="fa fa-trash"></i></button>
</div> </div>
<div class="row main_block">
    <div class="col-xs-12"> 
        <div class="table-responsive">
            <table id="tbl_package_transport_infomration" class="table table-bordered table-hover table-striped" style="width: 100%;">
                <tr>
                    <td><input id="check-btn-tr-acm-1" type="checkbox" checked ></td>
                    <td><input maxlength="15" type="text" name="username"  value="1" placeholder="Sr. No." disabled/></td>
                    <td><select name="vehicle_name1" id="vehicle_name1" title="Vehicle Name" style="width:100%">
                        <option value="">Select Vehicle</option>
                            <?php
                            $sq_transport_buses = mysql_query("select * from transport_agency_bus_master order by bus_name asc");
                            while($row_transport_bus = mysql_fetch_assoc($sq_transport_buses)){
                            ?>
                            <option value="<?= $row_transport_bus['bus_id'] ?>"><?= $row_transport_bus['bus_name'] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><input type="text" id="txt_tsp_from_date" name="txt_tsp_from_date"  onchange="validate_validDate('txt_tsp_from_date' ,'txt_tsp_to_date')" placeholder="Start Date" title="Start Date" ></td>
                    <td><input type="text" id="txt_tsp_to_date" onchange="validate_issueDate('txt_tsp_from_date' ,'txt_tsp_to_date')" name="txt_tsp_to_date" placeholder="End Date" title="End Date"></td>
                </tr>
          </table>
        </div>
    </div>
</div>