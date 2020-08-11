<div class="modal fade" id="transport_agency_bust_master_save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Vehicle Details</h4>
      </div>
      <div class="modal-body">

        
          <div class="row mg_bt_10 text-right">
            <div class="col-md-12">
              <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('tbl_dynamic_transport_agency_bus')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
              <button type="button" class="btn btn-danger btn-sm ico_left" onClick="deleteRow('tbl_dynamic_transport_agency_bus')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
            </div>
          </div>

          <div class="row mg_bt_10">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="tbl_dynamic_transport_agency_bus" name="tbl_dynamic_transport_agency_bus" class="table border_0 table-hover no-marg"  cellspacing="0">
                    <tr>
                        <td><input id="chk_tour_group1" type="checkbox" checked></td>
                        <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." disabled  class="form-control" /></td>
                        <td><input placeholder="*Vehicle Name" id="vehicle_name_new" title="Vehicle Name" onchange="validate_vehicle(this.id)" class="form-control"/></td>
                        <td><input placeholder="Seat Capacity" id="seat_capacity" title="Seat Capacity" class="form-control" onchange="validate_balance(this.id)" /></td>
                        <td><input placeholder="Per Day Cost" id="per_day_cost" title="Per Day Cost" class="form-control" onchange="validate_balance(this.id)"/></td>
                        <td><select name="active_flag" id="active_flag" title="Status" style="width:100%" class="hidden">
                              <option value="Active">Active</option>
                              <option value="Inactive">Inactive</option>
                            </select>
                        </td>
                    </tr>                                
                </table>
              </div>
            </div>
          </div>
            

            <div class="row text-center mg_tp_10">
              <div class="col-md-12">
                <button class="btn btn-sm btn-success" onclick="transport_agency_bus_master_save()"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
              </div>
            </div>
        
      </div>      
    </div>
  </div>
</div>