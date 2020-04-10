<?php include "../../../model/model.php"; ?>
<?php
$password=mysql_real_escape_string($_POST['password']);
$username=mysql_real_escape_string($_POST['username']);
$agent_code=mysql_real_escape_string($_POST['agent_code']);

$qq = "select * from b2b_registration where username='$username' and password='$password' and active_flag!='Inactive' and approval_status='Approved' and agent_code='$agent_code'";
$row_count=mysql_num_rows(mysql_query($qq));
if($row_count>0){

	$_SESSION['b2b_agent_code'] = $agent_code;
	$_SESSION['b2b_username'] = $username;
	$_SESSION['b2b_password'] = $password;

	$sq = mysql_query("select * from b2b_registration where username='$username' and password='$password' and active_flag!='Inactive' and approval_status='Approved' and agent_code='$agent_code'");
    $_SESSION['company_name'] = $sq['company_name'];
    
	echo "valid";
}	
else{
	echo "Please enter proper credentials!";
}
?>