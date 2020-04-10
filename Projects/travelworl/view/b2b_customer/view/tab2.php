<div class="row">
	<div class="col-md-12">
		<div class="profile_box main_block">
		<h3>Contact Person Details</h3>
        	<div class="row">
        	 	<div class="col-md-6 right_border_none_sm" style="min-height: 105px;">
        	   		<span class="main_block">
		                  <i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
		                  <?php echo "<label>Contact Person <em>:</em></label> ".$query['cp_first_name'].' '.$query['cp_last_name']; ?>
		            </span>
		            <span class="main_block">
		                  <i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
		                  <?php echo "<label>Email Id<em>:</em></label> ".$query['email_id'] ?>
		            </span>
        	 		<span class="main_block">
		                  <i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
		                  <?php echo "<label>Mobile No <em>:</em></label> ".$query['mobile_no'] ?>
		            </span>
		            <span class="main_block">
		                  <i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
		                  <?php echo "<label>Whatsapp No <em>:</em></label> ".$query['whatsapp_no']; ?>
		            </span>
		            <span class="main_block">
		                  <i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
		                  <?php echo "<label>Designation <em>:</em></label> ".$query['designation']; ?>
		            </span> 
		            <span class="main_block">
		                  <i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
		                  <?php echo "<label>PAN Card No. <em>:</em></label>".$query['pan_card'] ?>
		            </span>
					<?php
					if($query['id_proof_url']!=''){
						$newUrl1 = preg_replace('/(\/+)/','/',$query['id_proof_url']);
					?>
					<span class="main_block">
					<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
					<?php echo "<label>ID Proof <em>:</em></label> "?><a href="<?php echo $newUrl1; ?>" download title="Download"><i class="fa fa-id-card-o"></i></a> 
					</span>
					<?php } ?>   
		        </div>
		    </div> 
	    </div>
    </div>
</div>
<?php               
$sq_count = mysql_num_rows(mysql_query("select * from b2b_registration_conatcts where register_id = '$register_id'"));
if($sq_count != 0){ ?>
<div class="row mg_tp_10">
	<div class="col-md-12">
		<div class="profile_box main_block">
		<h3 class="editor_title">Official Contact Details</h3>
            <div class="table-responsive">
            <table class="table table-bordered no-marg">
                <thead>
                    <tr class="table-heading-row">
                        <th>S_No.</th>
                        <th>Role Name</th>
                        <th>EMail Id</th>
                        <th>Mobile No</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;                
                $query = mysql_query("select * from b2b_registration_conatcts where register_id = '$register_id'");
                while($row_entry = mysql_fetch_assoc($query)){
                    ?>
                    <tr>

                        <td><?php echo $count++; ?></td>
                        <td><?php echo $row_entry['role']; ?></td>
                        <td><?php echo $row_entry['email_id']; ?></td>
                        <td><?php echo $row_entry['mobile_no']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
		    </div> 
	</div>
</div>
<?php } ?>