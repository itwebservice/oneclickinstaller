<?php 
include_once('../../../model/model.php');
include_once('../../../model/finance_master/cheque_clearance/cheque_clearance.php');

$cheque_clearance = new cheque_clearance;
$cheque_clearance->status_update();
?>