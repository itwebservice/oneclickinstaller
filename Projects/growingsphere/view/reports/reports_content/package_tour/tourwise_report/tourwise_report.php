<?php include "../../../../../model/model.php";

$booking_id=$_POST['booking_id'];
 ?>


<div class="panel panel-default panel-body mg_bt_10">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table trable-hover" id="fit_pass_list" style="margin: 20px 0 !important;">

<?php

$query = "select * from package_tour_booking_master where 1 ";

if($booking_id!="")
{
	$query .=" and booking_id = '$booking_id'";
}
?>
<thead>
<tr class="table-heading-row">
    <th>S_No.</th>
    <th>Tour_Name</th>
    <th>From_Date</th>
    <th>To_Date</th>
    <th>Passenger_Name</th>
    <!-- <th>Last Name</th> -->
    <th>ADOL</th>
    <th>Mobile</th>
</tr>
</thead>
<tbody>
<?php
$count = 0;
$bg;
$sq1 =mysql_query($query);
while($row1 = mysql_fetch_assoc($sq1))
{
	

	$sq2 = mysql_query("select * from package_travelers_details where booking_id = '$row1[booking_id]'");
	while($row2 = mysql_fetch_assoc($sq2))
	{
	 $count++;
	($row2['status']=='Cancel')?$bg='danger':$bg='';
?>
	  <tr class="<?= $bg?>">
	  	<td><?php echo $count ?></td>
	  	<td><?php echo $row1['tour_name'] ?></td>
	  	<td><?= get_date_user($row1['tour_from_date']) ?></td>
	  	<td><?= get_date_user($row1['tour_to_date']) ?></td>
	  	<td><?php echo $row2['first_name']." ".$row2['last_name'] ?></td>
	  	<!-- <td><?php echo $row2['last_name'] ?></td> -->
	  	<td><?php echo $row2['adolescence'] ?></td> 
	  	<td><?php echo $row1['mobile_no'] ?></td>
	  </tr>	
<?php		
	}
}
?>
</tbody>
</table>
</div>	</div> </div>
</div>
<script>
$('#fit_pass_list').dataTable({
		"pagingType": "full_numbers"
	});

</script>
<script src="js/adnary.js"></script>