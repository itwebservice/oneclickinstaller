<?php include "../../../../../model/model.php"; ?>
<div class="app_panel_content Filter-panel mg_bt_10">
    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
        <select id="tour_id_filter" name="tour_id_filter" onchange="tour_group_dynamic_reflect1();" style="width:100%" title="Tour Name"> 
            <option value="">Passanger Name</option>
            <?php
                $sq=mysql_query("select distinct first_name,last_name,traveler_id from travelers_details order by first_name");
                while($row=mysql_fetch_assoc($sq))
                {
                  echo "<option value='$row[traveler_id]'>".$row['first_name'].''.$row['last_name']."</option>";
                }    
            ?>
        </select>
      </div>
      
   
</div>
<div id="div_list" class="main_block mg_tp_20"></div>
<script>
  $('#from_date, #to_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
  $('#tour_id_filter').select2();
</script>
<script type="text/javascript">
function tour_group_dynamic_reflect1()
{

  var traveler_id = $('#tour_id_filter').val();
    $.post('reports_content/group_tour/repeater_tourist_report/repeater_tourist_report_filter.php', { traveler_id:traveler_id}, function(data){
        $('#div_list').html(data);
    });
}
tour_group_dynamic_reflect1();
</script>