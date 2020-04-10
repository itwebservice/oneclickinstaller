<?php 
include "../../../../model/model.php";
 
$hotel_id = $_POST['hotel_id'];

$sq_vendor = mysql_fetch_assoc(mysql_query("select * from vendor_login where user_id='$hotel_id'"));
$login_id = $sq_vendor['login_id']; 
$hotel_pricing = mysql_query("select * from hotel_vendor_price_master where login_id = '$login_id'");
?>
<div class="modal fade profile_box_modal" id="hotel_view_modal" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-body profile_box_padding">
      	
	      		<div>
				  <!-- Nav tabs -->
				  	<ul class="nav nav-tabs" role="tablist">
				    	<li role="presentation" class="active"><a href="#basic_information" aria-controls="home" role="tab" data-toggle="tab" class="tab_name">Hotel Tariff Information</a></li>
				    	<li class="pull-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>
				  	</ul>

		            <div class="panel panel-default panel-body fieldset profile_background">
						<!-- Tab panes1 -->
						<div class="tab-content">
						    <!-- *****TAb1 start -->
						    <div role="tabpanel" class="tab-pane active" id="basic_information">
						     	<div class="row">
									<div class="col-md-12">
										<div class="profile_box main_block">
											<div class="table-responsive no-marg-sm">
												<table class="table table-hover bg_white no-marg-sm" id="tbl_req_list" style="width: 100%">
													<thead>
														<tr class="table-heading-row">
															<th>S_No.</th>
															<th>Currency</th>
															<th>Room_Category</th>
															<th>Valid_From</th>
															<th>Valid_To </th>
															<th>Single_Bed</th>
															<th>Double_Bed</th>
															<th>Triple_Bed</th>
															<th>Quad_Bed</th>
															<th>Extra_Bed</th>
															<th>Queen</th>
															<th>King</th>
															<th>Twin</th>
															<th>Meal_Plan</th>
														</tr>
													</thead>
													<tbody>
														<?php 
														$count = 0;											
														while($row_req1 = mysql_fetch_assoc($hotel_pricing)){
															$sq_req = mysql_query("select * from hotel_vendor_price_list where  pricing_id = '$row_req1[pricing_id]'");
														while($row_req = mysql_fetch_assoc($sq_req)){
															$sq_query = mysql_fetch_assoc(mysql_query("select * from hotel_vendor_price_master where pricing_id = '$row_req[pricing_id]'"));
															$sq_currency = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id = '$sq_query[currency_id]'"));
															?>
															<tr class="<?= $bg ?>">
																<td><?= ++$count ?></td>
																<td><?= $sq_currency['currency_code'] ?></td>
																<td><?= $row_req['without_bed_cost'] ?></td>
																<td><?=  date('d/m/Y', strtotime($row_req['from_date'])) ?></td>
																<td><?= date('d/m/Y', strtotime($row_req['to_date'])) ?></td>
																<td><?= $row_req['single_bed_cost'] ?></td>
																<td><?= $row_req['double_bed_cost'] ?></td>
																<td><?= $row_req['triple_bed_cost'] ?></td>
																<td><?= $row_req['quad_bed_cost'] ?></td>
																<td><?= $row_req['with_bed_cost'] ?></td>
																<td><?= $row_req['queen'] ?></td>
																<td><?= $row_req['king'] ?></td>
																<td><?= $row_req['twin'] ?></td>
																<td><?= $row_req['meal_plan'] ?></td>
															</tr>
															<?php
															}
														}
														?>
													</tbody>
												</table>
											</div>

											</div> </div>
								    	</div> 
								    	<div class="row">
								    		<div class="profile_box main_block">
								    			<div class="col-sm-6">
										       	 	<h3>Inclusions</h3>
											       	 	<div class="panel panel-default panel-body panel_height_limit" style="min-height: 50px;">
											       	 		<?php echo $sq_query['inclusions']; ?>
											       	 	</div>
							        			</div>
												<div class="col-sm-6">
										       	 	<h3>Exclusions</h3>
											       	 	<div class="panel panel-default panel-body panel_height_limit" style="min-height: 50px;">
											       	 		<?php echo $sq_query['exclusions']; ?>
											       	 	</div>
							        			</div>
								    		</div>
								    	</div>
								    		<div class="row">
								    		<div class="profile_box main_block">
								    			<div class="col-sm-6">
										       	 	<h3>Terms & Conditions</h3>
											       	 	<div class="panel panel-default panel-body panel_height_limit" style="min-height: 50px;">
											       	 		<?php echo $sq_query['terms_conditions']; ?>
											       	 	</div>
							        			</div>
							        			<div class="col-sm-3">
										       	 	<h3>Check-In</h3>
											       	 	<div class="panel panel-default panel-body panel_height_limit" style="min-height: 50px;">
											       	 		<?php echo $sq_query['check_in']; ?>
											       	 	</div>
							        			</div>
							        			<div class="col-sm-3">
										       	 	<h3>Check-Out</h3>
											       	 	<div class="panel panel-default panel-body panel_height_limit" style="min-height: 50px;">
											       	 		<?php echo $sq_query['check_out']; ?>
											       	 	</div>
							        			</div>
								    		</div>
								    	</div>
								    </div>
								</div>
							</div>

						</div>  
					</div>
						    <!-- ********Tab1 End******** --> 
				</div>
			</div>
        </div>
	</div>
</div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#hotel_view_modal').modal('show');
</script>  
 
