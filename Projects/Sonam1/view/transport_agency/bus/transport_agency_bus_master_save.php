<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
?>
<?= begin_panel('Vehicle Information',13) ?>
<div class="header_bottom">
  <div class="row mg_tp_10">
      <div class="col-md-12 text-right">
          <button class="btn btn-info btn-sm ico_left btn-primary" type="button" data-toggle="modal" data-target="#transport_agency_bust_master_save_modal" title="Add Vehicle"><i class="fa fa-plus"></i>&nbsp;&nbsp;Vehicle</button>
      </div>
  </div>
</div>

<div class="app_panel_content">
  <div class="row"> <div class="col-md-12 no-pad">
  <div class="table-responsive" id="div_transport_agency_bus_list">
    <table class="table table-hover border_0 mg_bt_10" id="bus_table" cellspacing="0" style="margin: 20px 0 !important;">
      <thead>
        <tr class="active table-heading-row">
          <th>S_No.</th>
          <th>Vehicle</th>
          <th>Seats_Capacity</th>
          <th>Per Day Cost</th>
          <th>Edit</th>
        </tr>  
      </thead>  
      <tbody>
        <?php
        $count=0;
        $sq = mysql_query("select * from transport_agency_bus_master");
        while($row=mysql_fetch_assoc($sq))
        {
          $count++;
          $bg = ($row['active_flag']=="Inactive") ? "danger" : "";
         ?>
         <tr class="<?= $bg ?>">
            <td><?php echo $count ?></td>
            <td><?php echo $row['bus_name'] ?></td>
            <td><?php echo $row['bus_capacity'] ?></td>
            <td><?php echo $row['per_day_cost'] ?></td>
            <td>
              <button class="btn btn-info btn-sm" onclick="transport_agency_bus_master_update_modal(<?php echo $row['bus_id'] ?>)" title="Edit Information"><i class="fa fa-pencil-square-o"></i></button>
            </td>
         </tr> 
         <?php 
        }  
        ?>
      </tbody>  
    </table>  
  </div>
  </div> </div>
                    
<div id="div_transport_agency_bus_update_modal"></div>
<?= end_panel() ?>

<script src="<?php echo BASE_URL ?>js/app/field_validation.js"></script>
<script src="../js/transport_agency_bus.js">
 
</script>
<script type="text/javascript">
 $('#bus_table').dataTable({
    "pagingType": "full_numbers"
  });
 </script>
<?= end_panel() ?>
<?php
require('transport_agency_bus_master_save_modal.php');
/*======******Footer******=======*/
require_once('../../layouts/admin_footer.php'); 
?>