<div class="row">
	<div class="col-md-12">
		<div class="profile_box main_block">
			<div class="row">
				<div class="col-md-12 right_border_none_sm" style="min-height: 105px;">
					<span class="main_block">
						<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
						<?php echo "<label>Credit Amount <em>:</em></label> ".$query['credit_amount']; ?>
					</span>
					<span class="main_block">
					<?php $descr = ($query['approval_status'] == 'Approved') ? $query['description'] : $sq_query['description']; ?>
						<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
						<?php echo "<label>Description <em>:</em></label> ".$descr ?>
					</span>
					<span class="main_block">
						<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
						<?php echo "<label>Approval Date <em>:</em></label> ".get_date_user($query['approval_date'])?>
					</span>  
				</div>
			</div>   
		</div> 
	</div>
</div>
<?php
$sq_count = mysql_num_rows(mysql_query("select * from b2b_creditlimit_master where register_id = '$register_id1' and created_at<='$query[approval_date]'"));
if($sq_count != 0){
?>
<div class="row mg_tp_10">
	<div class="col-md-12">
		<div class="profile_box main_block">
		<h3 class="editor_title">History</h3>
            <div class="table-responsive">
            <table class="table table-bordered no-marg">
                <thead>
                    <tr class="table-heading-row">
                        <th>S_No.</th>
                        <th>Request Date</th>
                        <th>Credit Amount</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
				$sq_credit = mysql_query("select * from b2b_creditlimit_master where register_id = '$register_id1' and created_at<='$query[approval_date]' order by entry_id");
                while($row_entry = mysql_fetch_assoc($sq_credit)){
                    ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo get_date_user($row_entry['created_at']); ?></td>
                        <td><?php echo $row_entry['credit_amount']; ?></td>
                        <td><?php echo ($row_entry['approval_status'] == '')?'NA':$row_entry['approval_status']; ?></td>
                        <td><?php echo $row_entry['description']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
		    </div> 
	</div>
</div>
<?php } ?>