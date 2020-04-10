<?php
include "../../model/model.php";
/*======******Header******=======*/
require_once('../layouts/admin_header.php');
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
?>
<?= begin_panel('Group Booking Cancellation',66) ?>
<div class="app_panel_content Filter-panel">
    <div class="row"> 
        <div class="col-md-10 col-md-offset-1 col-xs-12">
        <form action="traveler_booking_cancelation.php" method="POST" onsubmit="return validate_submit()" class="no-marg">
            <div class="text-center form-inline">
                <div class="form-group" style="width:250px">
                    <select title="Booking ID" id="cmb_tourwise_traveler_id" name="cmb_tourwise_traveler_id" style="width:100%;">
                        <?php get_group_booking_dropdown($role, $branch_admin_id, $branch_status,$emp_id,$role_id) ?>
                    </select>                
                </div>&nbsp;&nbsp;
                <div class="form-group">
                    <button class="btn btn-sm btn-info ico_right">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
<?= end_panel() ?>
<script>
$("#cmb_tourwise_traveler_id").select2();
function validate_submit(){
    var tourwise_traveler_id = $("#cmb_tourwise_traveler_id").val();
    if(tourwise_traveler_id==""){
        error_msg_alert("Please Select Group Booking ID!");
        return false;
    }
}
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>
<?php
require_once('../layouts/admin_footer.php'); 
?>