<?php
include "../../model/model.php";
/*======******Header******=======*/
require_once('../layouts/admin_header.php');
?>
<?= begin_panel('Group Tour Refund',67); ?>

<div class="app_panel_content Filter-panel">
    <form action="refund_traveler_booking.php" method="POST" onsubmit="return validate_submit()" class="no-marg">
        <div class="row text_center_xs">
            <div class="col-md-4 col-md-offset-3 col-sm-4 col-sm-offset-3 col-xs-12 mg_bt_10_xs">
                <select id="cmb_tourwise_traveler_id" name="cmb_tourwise_traveler_id" style="width:100%" title="Select Booking ID" class="form-control">
                    <option value="">Select Booking ID</option>
                    <?php 
                        $sq_tourwise_traveler_det = mysql_query("select id, traveler_group_id,form_date from tourwise_traveler_details where tour_group_status != 'Cancel'");
                        while($row_tourwise_traveler_details = mysql_fetch_assoc( $sq_tourwise_traveler_det ))
                        {
                           $sq_travelers_details = mysql_query("select m_honorific, first_name, last_name from travelers_details where traveler_group_id='$row_tourwise_traveler_details[traveler_group_id]' and status='Cancel' "); 
                           while($row_travelers_details = mysql_fetch_assoc( $sq_travelers_details )){
                            $date = $row_tourwise_traveler_details['form_date'];
                            $yr = explode("-", $date);
                            $year =$yr[0];
                            ?>
                            <option value="<?php echo $row_tourwise_traveler_details['id'] ?>"><?php echo get_group_booking_id($row_tourwise_traveler_details['id'],$year).' : '.$row_travelers_details['m_honorific'].' '.$row_travelers_details['first_name'].' '.$row_travelers_details['last_name']; ?></option>
                            <?php
                           } 
                         ?>
                         <?php      
                        }    
                    ?>
                </select>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12 text-left">
                <button class="btn btn-sm btn-info ico_right">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </form>
</div>

<?= end_panel(); ?>          
<script src="<?php echo BASE_URL ?>js/app/field_validation.js"></script>                    

<script>
$("#cmb_tourwise_traveler_id").select2(); 
function validate_submit()
{
    var tourwise_traveler_id = $("#cmb_tourwise_traveler_id").val();

    if(tourwise_traveler_id=="")
    {
        error_msg_alert("Please select Guest Booking ID!");
        return false;
    }
    
}

</script>


<?php
/*======******Footer******=======*/
require_once('../layouts/admin_footer.php'); 
?>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>       