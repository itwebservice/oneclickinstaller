<?php 
include_once('../../model/model.php');
include_once('../../model/booker_incentive/group_tour_booker_incentive.php');
include_once('../../model/app_settings/transaction_master.php');
include_once('../../model/app_settings/bank_cash_book_master.php');

$group_tour_booker_incentive = new group_tour_booker_incentive;
$group_tour_booker_incentive->group_tour_booker_incentive_save();
?>