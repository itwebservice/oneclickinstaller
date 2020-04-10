<?php 
include "../../../model/model.php"; 

$bus_id = $_POST['bus_id'];  
$row = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$bus_id'"));
?>
<form id="frm_transport_bus_update">
<input type="hidden" id="bus_id_u" name="bus_id_u" value="<?= $bus_id ?>">
<div class="modal fade" id="transport_agency_bus_master_update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Vehicle Update</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6 mg_bt_10">
            <input type="text" class="form-control" id="txt_transport_agency_bus_name" placeholder="*Vehicle Name" title="Vehicle Name" name="txt_transport_agency_bus_name" onchange="validate_vehicle(this.id);" value="<?php echo $row['bus_name'] ?>"  >    
          </div>
          <div class="col-sm-6 mg_bt_10">
            <input type="text" class="form-control" id="txt_transport_agency_bus_capacity" placeholder="Seat Capacity" title="Seat Capacity"name="txt_transport_agency_bus_capacity" value="<?php echo $row['bus_capacity'] ?>" onchange="validate_balance(this.id)">
          </div>
          <div class="col-sm-6 mg_bt_10">
            <input type="text" class="form-control" id="txt_transport_agency_bus_cost" placeholder="Per Day Cost " title="Per Day Cost" name="txt_transport_agency_bus_cost" value="<?php echo $row['per_day_cost'] ?>" onchange="validate_balance(this.id)" >
          </div>
          <div class="col-sm-6 mg_bt_10">
            <select name="active_flag11" id="active_flag11" title="Status" style="width:100%">
              <option value="<?= $row['active_flag'] ?>"><?= $row['active_flag'] ?></option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="row mg_tp_10">
          <div class="col-md-12 text-center">
             <button class="btn btn-sm btn-success" id="bus_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button> 
          </div>
        </div>
        
      </div>      
    </div>
  </div>
</div>
</form>

<script>
  $('#transport_agency_bus_master_update_modal').modal('show');

  ///////////////////////***Transport Agency Bus Master update start*********//////////////
  $(function(){
    $('#frm_transport_bus_update').validate({
      rules:{
              txt_transport_agency_bus_name : { required : true },
      },
      submitHandler:function(form){
        $('#bus_update').button('loading');
        var base_url = $('#base_url').val();
        var bus_id = $("#bus_id_u").val();
        var bus_name = $("#txt_transport_agency_bus_name").val();
        var bus_capacity = $("#txt_transport_agency_bus_capacity").val();
        var per_day_cost = $("#txt_transport_agency_bus_cost").val();
        var status = $("#active_flag11").val();

        $.post( 
               base_url+"controller/group_tour/transport_agency/transport_agency_bus_master_update_c.php",
               { bus_id : bus_id, bus_name : bus_name, bus_capacity : bus_capacity,per_day_cost : per_day_cost, status : status },
               function(data) {  
                var msg = data.split('--');
                if(msg[0]=="error"){
                  error_msg_alert(msg[1]);
                  $('#bus_update').button('reset');
                }
                else{
                 $('#bus_update').button('reset');
                      msg_alert(data);
                      $('#transport_agency_bus_master_update_modal').modal('hide');
                      $('#div_transport_agency_bus_list').load('transport_agency_bus_master_list_load.php').fadeIn(500);
                }
               });
      }
    });
  });
 
  ///////////////////////***Transport Agency Bus Master update end*********//////////////
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>