<?php
include "../../../model/model.php";
$count=0;
$limit = $_POST['limit'];
$start = $_POST['start'];
$query = "select * from city_master where 1 LIMIT $start, $limit";
$sq = mysql_query($query);
while($row=mysql_fetch_assoc($sq)){
  $count++;
  $bg = ($row['active_flag']=="Inactive") ? "danger" : "";
  ?>
  <tr class="<?= $bg ?>">
    <td><?php echo $row['city_id'] ?></td>
    <td><?php echo $row['city_name'] ?></td>
    <td>
      <a href="javascript:void(0)" onclick="city_master_update_modal(<?php echo $row['city_id'] ?>)" class="btn btn-info btn-sm" title="Edit city"><i class="fa fa-pencil-square-o"></i></a>
    </td>
  </tr>
<?php } ?>