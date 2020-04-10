<?php 
$country_id = $_POST['country_id'];
$tax_name = '';

$conn = new mysqli("localhost", "root", "",'v7');
if($conn->connect_error){
	echo "Connection Failed:".$conn->connect_error;
	exit;
}
$query1 = $conn->query("select * from tax_type_master where country_id='$country_id'");

while($row_query = $query1 -> fetch_assoc()){
	$tax_name = $tax_name.$row_query['tax_type'].' ';
}

$conn->close();

echo $tax_name;
?>