<?php include "../../../model/model.php"; ?>
<table class="table table-hover mg_bt_10" id="bus_table_list">
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
          <button class="btn btn-info btn-sm" onclick="transport_agency_bus_master_update_modal(<?php echo $row['bus_id'] ?>)"><i class="fa fa-pencil-square-o"></i></button>
        </td>
     </tr> 
     <?php 
    }  
    ?>
  </tbody>  
</table>  
<script src="../js/transport_agency_bus.js"></script>
<script>
  $('#bus_table_list').dataTable({
    "pagingType": "full_numbers"
  });
</script>