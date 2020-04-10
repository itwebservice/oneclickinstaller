<?php 
class transport_agency_bus{

///////////////////////***Transport Agency bus Master save start*********//////////////

function transport_agency_bus_master_save($bus_name, $bus_capacity,$per_day_cost, $active_flag_arr)
{

  for($i=0; $i<sizeof($bus_name); $i++)
  {
    $bus_name1 = ltrim($bus_name[$i]);
    $bus_name_count = mysql_num_rows(mysql_query("select bus_name from transport_agency_bus_master where bus_name='$bus_name1'"));
    if($bus_name_count>0)
    {
      echo "error--".$bus_name1." already exists!";
      exit;
    }  
  }  

  for($i=0; $i<sizeof($bus_name); $i++)
  {
    $max_id1 = mysql_fetch_assoc(mysql_query("select max(bus_id) as max from transport_agency_bus_master"));
    $max_id = $max_id1['max']+1;

    $sq = mysql_query("insert into transport_agency_bus_master (bus_id, bus_name, bus_capacity,per_day_cost, active_flag ) values ('$max_id', '$bus_name[$i]', '$bus_capacity[$i]', '$per_day_cost[$i]', '$active_flag_arr[$i]') ");
    if(!$sq)
    {
      echo "error--".$bus_name[$i]." not saved!";
      exit;
    }  
  }  
  echo "Vehicle has been successfully saved.";
}


///////////////////////***Transport Agency bus Master save end*********//////////////


///////////////////////***Transport Agency bus Master Update start*********//////////////

function transport_agency_bus_master_update($bus_id, $bus_name, $bus_capacity,$per_day_cost, $status)
{
  for($i=0; $i<sizeof($bus_name); $i++)
  {
    $bus_name1 = ltrim($bus_name);
    $bus_name_count = mysql_num_rows(mysql_query("select bus_name from transport_agency_bus_master where bus_name='$bus_name1' and bus_id!='$bus_id'"));
    if($bus_name_count>0)
    {
      echo "error--".$bus_name1." already exists!";
      exit;
    }  
  }  


  $sq = mysql_query("update transport_agency_bus_master set bus_name='$bus_name', bus_capacity='$bus_capacity', per_day_cost='$per_day_cost', active_flag='$status' where bus_id='$bus_id' ");
  if(!$sq)
  {
    echo "error--Information not updated!";
    exit;
  }  
  else
  {
    echo "Vehicle has been successfully updated.";
    return true;
  }  
}

///////////////////////***Transport Agency bus Master Update end*********//////////////



}
?>