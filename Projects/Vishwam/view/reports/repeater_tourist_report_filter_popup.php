<?php include "../../model/model.php"; ?>

<?php
$traveler_group_id = $_GET['traveler_group_id'];
$traveler_group_id_arr = explode(",", $traveler_group_id);
$count = 1;
?>
<div class="modal fade profile_box_modal" id="display_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
  	
    <div class="modal-content">
      <div class="modal-body profile_box_padding">
      	<!-- Nav tabs -->

				  	<ul class="nav nav-tabs" role="tablist">

				    	<li role="presentation" class="active"><a href="#basic_information" aria-controls="home" role="tab" data-toggle="tab" class="tab_name">Tours Attended</a></li>

				    	<li class="pull-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>

				  	</ul>



		            <div class="panel panel-default panel-body fieldset profile_background">

						<!-- Tab panes1 -->

						<div class="tab-content">

						    <!-- *****TAb1 start -->

						    <div role="tabpanel" class="tab-pane active" id="basic_information">

						     	<div class="row">

									<div class="col-md-12">
										<table class="table table-bordered">
											<tr class="table-heading-row">
												<td>Sr. No.</td>
												<td>Tour Name</td>
												<td>From Date</td>
												<td>To Date</td>
											</tr>
										<?php

										for($i=0; $i<sizeof($traveler_group_id_arr); $i++)
										{
											$sq = mysql_fetch_assoc(mysql_query("select tour_id, tour_group_id from tourwise_traveler_details where traveler_group_id='$traveler_group_id_arr[$i]'"));
											$tour_id = $sq['tour_id'];
											$tour_group_id = $sq['tour_group_id'];

											$sq = mysql_fetch_assoc(mysql_query("select tour_name from tour_master where tour_id='$tour_id'"));
											$tour_name = $sq['tour_name'];

											$sq = mysql_fetch_assoc(mysql_query("select from_date, to_date from tour_groups where tour_id='$tour_id'"));
											$tour_group_from = $sq['from_date'];
											$tour_group_to = $sq['to_date'];
										?>

											<tr>
												<td><?php echo $count ?></td>
												<td><?php echo $tour_name ?></td>
												<td><?php echo date("d-m-Y", strtotime($tour_group_from)) ?></td>
												<td><?php echo date("d-m-Y", strtotime($tour_group_to)) ?></td>
											</tr>	

										<?php
											$count++;
										}	
										?>

										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
			
</div>
</div>
</div>
</div>


<?php
?>
<script type="text/javascript">
	$('#display_modal').modal('show');
</script>