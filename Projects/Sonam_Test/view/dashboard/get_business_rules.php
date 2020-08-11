<?php
if (file_exists('business_rule.txt')) {
    $modified_time = filemtime('business_rule.txt');
}else{
    $modified_time = time()-1*86400001;
}
$taxes_data = array();
$taxes_rules_data = array();
$other_rules_data = array();
$new_array = array();
if ($modified_time < time()-1*86400000) {
    include_once '../../model/model.php';
    
    //Taxes
    $result = mysql_query("SELECT * FROM tax_master");
    while($row = mysql_fetch_array($result)) {
        $temp_array = array(
            'entry_id' => $row['entry_id'],
            'name' => $row['name'],
            'rate_in' => $row['rate_in'],
            'rate' => $row['rate'],
            'status' => $row['status']
        );
        array_push($taxes_data,$temp_array);
    }
    //Tax Rules
    $result = mysql_query("SELECT * FROM tax_master_rules");
    while($row = mysql_fetch_array($result)) {
        $temp_array = array(
            'rule_id' => $row['rule_id'],
            'entry_id' => $row['entry_id'],
            'name' => $row['name'],
            'validity' => $row['validity'],
            'from_date' => $row['from_date'],
            'to_date' => $row['to_date'],
            'ledger_id' => $row['ledger_id'],
            'travel_type' => $row['travel_type'],
            'calculation_mode' => json_encode($row['calculation_mode']),
            'target_amount' => $row['target_amount'],
            'conditions' => $row['conditions'],
            'status' => $row['status']
        );
        array_push($taxes_rules_data,$temp_array);
    }

    //Other Rules
    $result = mysql_query("SELECT * FROM other_master_rules");
    while($row = mysql_fetch_array($result)) {
        $temp_array = array(
            'rule_id' => $row['rule_id'],
            'rule_for' => $row['rule_for'],
            'name' => $row['name'],
            'type' => $row['type'],
            'validity' => $row['validity'],
            'from_date' => $row['from_date'],
            'to_date' => $row['to_date'],
            'ledger_id' => $row['ledger_id'],
            'travel_type' => $row['travel_type'],
            'fee' => $row['fee'],
            'fee_type' => $row['fee_type'],
            'target_amount' => $row['target_amount'],
            'conditions' => $row['conditions'],
            'status' => $row['status']
        );
        array_push($other_rules_data,$temp_array);
    }
    

    $new_array = array('taxes'=>$taxes_data,'tax_rules'=>$taxes_rules_data,'other_rules'=>$other_rules_data);
    // store query result in business_rule.txt
    file_put_contents('business_rule.txt', serialize(json_encode($new_array)));
    $new_array = json_encode($new_array);
}
else {
    $new_array = unserialize(file_get_contents('business_rule.txt'));
}
?>