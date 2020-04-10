
<?php 
include "../../../../../model/model.php";
$customer_id = $_GET['customer_id'];
$booking_type = $_GET['booking_type'];
$booking_id = $_GET['booking_id'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
 
$query = "select * from customer_feedback_master where 1 ";
if($booking_type!=""){
	$query .=" and booking_type='$booking_type'";
}
if($booking_id!=''){
	$query .=" and booking_id='$booking_id'";
}
if($customer_id!=""){
    $query.=" and customer_id='$customer_id'";
}
if($branch_status=='yes' && $role=='Branch Admin'){
   
    $query .= " and customer_id in(select customer_id from customer_master where branch_admin_id = '$branch_admin_id')";
}   
 
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
    
    <table class="table table-hover" id="tbl_list" style="margin: 20px 0 !important;">
        <thead>
            <tr class="table-heading-row">
                <th>S_No.</th>
                <th>Customer_Name</th>
                <th>Booking_ID</th> 
                <th>Tour_Type</th>
                <th>Tour_Date</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0; 
            $sq = mysql_query($query);
            while($row = mysql_fetch_assoc($sq)){
                $sq_query_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id ='$row[customer_id]'")); 
                if($row['booking_type']=="Group Booking"){ 
                    $sq_booking =mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$row[booking_id]'"));  
                    $sq_tour_group_name = mysql_query("select from_date,to_date from tour_groups where group_id='$sq_booking[tour_group_id]'");
                    $row_tour_group_name = mysql_fetch_assoc($sq_tour_group_name);
                    $tour_date = date("d-m-Y", strtotime($row_tour_group_name['from_date'])).' To '.date("d-m-Y", strtotime($row_tour_group_name['to_date']));
                     
                }
                else{
                    
                   $sq_booking =mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$row[booking_id]'")); 
                   $tour_date = date("d-m-Y", strtotime($sq_booking['tour_from_date'])).' To '.date("d-m-Y", strtotime($sq_booking['tour_to_date']));
                }
            ?>
                <tr>
                    <td><?= ++$count ?></td>
                    <td><?= $sq_query_cust['first_name'].' '.$sq_query_cust['last_name'] ?></td>
                    <td><?php if($row['booking_type']=="Group Booking"){echo get_group_booking_id($row['booking_id']); }else{echo get_package_booking_id($row['booking_id']); }?></td>
                    <td><?= $row['booking_type'] ?></td>
                    <td><?= $tour_date ?></td>
                    <td><button class="btn btn-info btn-sm" onclick="view_modal(<?= $row['feedback_id'] ?>)" title="View Information"><i class="fa fa-eye"></i></button></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

</div> </div> </div>
<script>
$('#tbl_list').dataTable({"pagingType": "full_numbers"});
</script>
