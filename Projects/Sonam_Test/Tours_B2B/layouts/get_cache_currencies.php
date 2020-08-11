<?php
if (file_exists('../cache.txt')) {
    $modified_time = filemtime('cache.txt');
}else{
    $modified_time = time()-1*10801;
}
$data = array();
if ($modified_time < time()-1*10800) {
    include_once '../model/model.php';
    
    //Currency Rates
    $result = mysql_query("SELECT * FROM roe_master");
    while($row = mysql_fetch_array($result)) {
        $temp_array = array(
            'entry_id' => $row['entry_id'],
            'currency_id' => $row['currency_id'],
            'currency_rate' => $row['currency_rate']
        );
        array_push($data,$temp_array);
    }
    //Currency Icon
    $sq_currency= mysql_query("select default_currency,id from currency_name_master");
    while($row_currency = mysql_fetch_array($sq_currency)) {
        $temp_array = array(
            'icon' => $row_currency['default_currency'],
            'id' => $row_currency['id']
        );
        array_push($data,$temp_array);
    }

    // store query result in cache.txt
    file_put_contents('../cache.txt', serialize(json_encode($data)));
    //echo json_encode($data);
    $data = json_encode($data);
}
else {
    $data = unserialize(file_get_contents('cache.txt'));
    //echo $data;
}
?>