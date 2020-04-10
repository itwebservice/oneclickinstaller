<div class="panel panel-default panel-body fieldset profile_background">
	<div class="tab-content">
	    <!-- *****TAb1***** start -->
	    <div role="tabpanel" class="tab-pane active" id="basic_information">
	     	<div class="row">
				<div class="col-md-12">
					<div class="profile_box main_block">
						<?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$sq_query[city_id]'")); ?>
						<div class="row">
							<div class="col-md-6 right_border_none_sm" style="border-right: 1px solid #ddd">
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>City Name <em>:</em></label> " .$sq_city['city_name']; ?>
								</span>
								<?php $sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$sq_query[hotel_id]'")); ?>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Hotel Name <em>:</em></label> " .$sq_hotel['hotel_name']; ?>
								</span>
								<?php $sq_currency = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id='$sq_query[currency_id]'")); ?>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Currency <em>:</em></label> " .$sq_currency['currency_code']; ?>
								</span>
							</div>
							<div class="col-md-6 right_border_none_sm">
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Check-IN <em>:</em></label> " .$sq_query['check_in']; ?>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Check-OUT <em>:</em></label> " .$sq_query['check_out']; ?>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 right_border_none_sm">
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label style='margin: -1px;'>Inclusions <em>:</em></label><br/> " .$sq_query['inclusions']; ?>
								</span>
							</div>
						</div><hr/>						
						<div class="row">
							<div class="col-md-12 right_border_none_sm">
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label style='margin: -1px;'>Exclusions <em>:</em></label><br/> " .$sq_query['exclusions']; ?>
								</span>
							</div>
						</div><hr/>
						<div class="row">
							<div class="col-md-12 right_border_none_sm">
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label style='margin: -1px;'>Terms & Conditions <em>:</em></label><br/> " .$sq_query['terms_conditions']; ?>
								</span>
							</div>
						</div>
			    </div>
			</div>
	    </div>
		</div>
	    <!-- ********Tab1 End******** --> 
	</div>
</div>