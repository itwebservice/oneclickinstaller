<?php
include "../../../../model/model.php";
$register_id = $_POST['register_id'];
$register_id1 = $_POST['register_id1'];
$query = mysql_fetch_assoc(mysql_query("select * from b2b_approved_credit where entry_id = '$register_id'"));
$sq_query = mysql_fetch_assoc(mysql_query("select * from b2b_creditlimit_master where entry_id = '$register_id'"));
?>
<div class="modal fade profile_box_modal" id="display_modal_tour" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body profile_box_padding">
      	<div>
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#basic_information" aria-controls="home" role="tab" data-toggle="tab" class="tab_name">Credit Information</a></li>
			    <li class="pull-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>
			  </ul>
              <div class="panel panel-default panel-body fieldset profile_background">
				  <div class="tab-content">
				    <!-- *****TAb1 start -->
				    <div role="tabpanel" class="tab-pane active" id="basic_information">
				     <?php include "tab1.php"; ?>
				    </div>
				    <!-- ********Tab1 End******** -->
				  </div>
			  </div>
        </div>
	   </div>
      </div>
    </div>
</div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#display_modal_tour').modal('show');
</script>