<?php
include "../../model/model.php";
$city_id = $_POST['city_id'];
$query = "select * from excursion_master_tariff where 1 ";
if($city_id != ''){
	$query .= " and city_id = '$city_id'";
}
$query .= ' order by entry_id desc';
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table" id="table_paid" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>City</th>
			<th>Excursion_Name</th>
			<th>Transfer_Option</th>
			<th>Duration</th>
			<th>Currency</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = 0;
		$sq_serv = mysql_query($query);
		while($row_ser = mysql_fetch_assoc($sq_serv)){
			$bg='';
			if($row_ser['active_flag']=='Inactive'){
				$bg ='danger';
			}
			$sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_ser[city_id]'"));
			$sq_currency = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id='$row_ser[currency_code]'"));
			?>

			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= $sq_city['city_name'] ?></td>
				<td><?= $row_ser['excursion_name'] ?></td>
				<td><?= $row_ser['transfer_option'] ?></td>
				<td><?= $row_ser['duration'] ?></td>
				<td><?= $sq_currency['currency_code'] ?></td>
				<!-- <?php
	                if($row_ser['image_upload_url']!=""){
	                	$newUrl1 = preg_replace('/(\/+)/','/',$row_ser['image_upload_url']); 
	                ?>	
				<td>
	                  <a href="<?php echo $newUrl1; ?>" download title="View Image" class="btn btn-info btn-sm"><i class="fa fa-download"></i></a> </td>
				<?php } 
				else{ ?>
				<td></td>
				<?php }
				?> -->
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_ser['entry_id'] ?>)" title="Edit Excursion"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php } ?>
	</tbody>
</table>
</div> </div> </div>
<script type="text/javascript">
$('#table_paid').dataTable({
	"pagingType": "full_numbers"
});
</script>