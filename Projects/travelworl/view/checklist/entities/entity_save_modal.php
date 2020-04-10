
<?php
$sq = mysql_fetch_assoc(mysql_query("select * from branch_assign where link='checklist/index.php'"));
$branch_status = $sq['branch_status'];
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<div class="modal fade" id="entity_save_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Checklist</h4>
      </div>
      <div class="modal-body">
        
        <form id="frm_entity_save">
        <input type="hidden" name="emp_id" id="emp_id" value="<?= $emp_id ?>">
        <div class="row">
          <div class="col-sm-4 mg_bt_30">
            <select name="entity_for" id="entity_for" title="For" onchange="feild_reflect()">
              <option value="">*For</option>
              <option value="Group Tour">Group Tour</option>
              <option value="Package Tour">Package Tour</option>
            </select>
          </div>
           <div id="div_reflect_tour"></div>
        </div>
       
       <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
         <legend>Checklist Entries</legend>
          <div class="row mg_bt_10"> <div class="col-md-12 text-right">
            <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('tbl_dynamic_tour_name')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
            <button type="button" class="btn btn-danger btn-sm ico_left" onClick="deleteRow('tbl_dynamic_tour_name')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
          </div> </div>

          <div class="row"> <div class="col-md-12"> 
        
            <table id="tbl_dynamic_tour_name" name="tbl_dynamic_tour_name" class="table table-bordered table-hover no-marg"  cellspacing="0">
              <tr>
                  <td class="col-md-1"><input id="chk_tour_group1" type="checkbox" checked></td>
                  <td class="col-md-1"><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
                  <td class="col-md-10"><input placeholder="*Checklist Name" onchange="validate_specialChar(this.id);" id="entity_name" name="entity_name" title="Checklist Name" class="form-control"/></td>
              </tr>                                
            </table>  

          </div> </div>
       </div>
          <div class="row text-center mg_tp_20">
            <div class="col-md-12">
              <button class="btn btn-sm btn-success" id="save_checklist"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
            </div>
          </div>
      </form>
      </div>      
    </div>
  </div>
</div>

<script>

  function feild_reflect(){
    var entity_for = $('#entity_for').val();
    var emp_id = $('#emp_id').val();
    var base_url = $('#base_url').val();
    var branch_status = $('#branch_status').val();
    $.post(base_url+'view/checklist/entities/tour_load.php', { entity_for : entity_for, emp_id : emp_id,branch_status : branch_status }, function(data){
      $('#div_reflect_tour').html(data);
    });
  }


$(function(){
  $('#frm_entity_save').validate({
    rules:{
      tour_id : { required:true },
      entity_for: { required:true },
      tour_group_id : { required:true },
      booking_id : { required:true },
    },
    submitHandler:function(form){

      var tour_id = $('#tour_id').val();
      var entity_for = $('#entity_for').val();
      var tour_group_id =$('#tour_group_id').val();
      var base_url = $('#base_url').val();
      var booking_id = $('#booking_id').val();
     
      var entity_name_arr = new Array();
      var table = document.getElementById("tbl_dynamic_tour_name");
      var rowCount = table.rows.length;

      for(var i=0; i<rowCount; i++)
      {
        var row = table.rows[i];
        if(rowCount == 1){
          if(!row.cells[0].childNodes[0].checked){
            error_msg_alert("Atleast one checklist details is required!");
            return false;
          }
        }
        if(row.cells[0].childNodes[0].checked)
        {  

          var entity_name = row.cells[2].childNodes[0].value;          
          if(entity_name=="")
          {
            error_msg_alert("Enter Checklist name in row"+(i+1));
            return false;
          }  
          entity_name_arr.push(entity_name);          
        } 
      }
      $('#save_checklist').button('loading');
      $.ajax({
        type:'post',
        url:base_url+'controller/checklist/entities/entity_save.php',
        data:{entity_for : entity_for, tour_id : tour_id,tour_group_id : tour_group_id, booking_id : booking_id,entity_name_arr : entity_name_arr},
        success:function(result){
          msg_alert(result);
          $('#save_checklist').button('reset');
          $('#entity_save_modal').modal('hide');
          reset_form('frm_entity_save');
          entities_list_reflect();
        }
      });
    }
  });
});
</script>