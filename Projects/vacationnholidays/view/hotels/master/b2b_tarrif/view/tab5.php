<?php
$sq_query = mysql_query("select * from hotel_offers_tarrif where pricing_id='$pricing_id'");
?>
<div class="panel panel-default panel-body fieldset profile_background">
	<div class="tab-content">
	    <div role="tabpanel" class="tab-pane active" id="basic_information">
	     	<div class="row">
				<div class="col-md-12">
					<div class="profile_box main_block">
          <h3 class="editor_title">Offers/Discounts/Coupon</h3>
            <div class="table-responsive">
                <table class="table table-bordered no-marg">
                  <thead>
                      <tr class="table-heading-row">
                        <th>S_No.</th>
                        <th>Type</th>
                        <th>Valid_From_Date</th>
                        <th>Valid_To_Date</th>
                        <th>Offer</th>
                        <th>Agent_Type</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                  $count=1;
                  while($row_query = mysql_fetch_assoc($sq_query)){ ?>
                    <tr>
                      <td><?= $count++ ?></td>
                      <td><?= $row_query['type'] ?></td>
                      <td><?= get_date_user($row_query['from_date']) ?></td>
                      <td><?= get_date_user($row_query['to_date']) ?></td>
                      <td><?= $row_query['offer'] ?></td>
                      <td><?= $row_query['agent_type'] ?></td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
        </div>
      </div>
	</div>
</div>
