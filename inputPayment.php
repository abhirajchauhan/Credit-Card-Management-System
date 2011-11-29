<?php

require "config.php";

$accountNumber=$_POST["accountNumber"];
$staffNumber=$_POST["staffNumber"];
$adate=$_POST["adate"];
$merchant=$_POST["merchant"];
$type=$_POST["type"];
$amount=$_POST["amount"];
$conn = oci_connect($dbUser, $dbPass, $dbHost);

$stmt=oci_parse($conn, "select account_number from customer_account");
oci_execute($stmt, OCI_NO_AUTO_COMMIT);

$flag_account=-1;
$flag_staff=-1;

while($row=oci_fetch_array($stmt, OCI_BOTH))
{
	if ($accountNumber==$row[0])
		$flag_account=0;
}

if ($type=="P")
{
	$stmt=oci_parse($conn, "select staff_number from staff");
	oci_execute($stmt, OCI_NO_AUTO_COMMIT);
	while ($row=oci_fetch_array($stmt, OCI_BOTH))
		if ($staffNumber==$row[0])
			$flag_staff=0;
}
else $flag_staff=0;

if ($flag_account==-1)
	echo "Invalid Account Number. Please go back and try again.";
else if ($flag_staff==-1)
	echo "Invalid Staff Number. Please go back and try again";
else {
	$stmt=oci_parse($conn, "select cd_number from credit_card where account_number = :accountNumber");
	oci_bind_by_name($stmt, ":accountNumber", $accountNumber);
	oci_execute($stmt, OCI_NO_AUTO_COMMIT);
	$row=oci_fetch_array($stmt, OCI_BOTH);
	$stmt=oci_parse($conn, "insert into activity values(seq_activity.nextval, :accountNumber, :staffNumber, to_date(:adate,'YYYY-MM-DD'), :type, :merchant, :amount, :cdNumber )");
	oci_bind_by_name($stmt, ":accountNumber", $accountNumber);
	oci_bind_by_name($stmt, ":staffNumber", $staffNumber);
	oci_bind_by_name($stmt, ":adate", $adate);
	oci_bind_by_name($stmt, ":type", $type);
	oci_bind_by_name($stmt, ":merchant", $merchant);
	oci_bind_by_name($stmt, ":amount", $amount);
	oci_bind_by_name($stmt, ":cdNumber", $row[0]);
	oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
	
	echo ("Acitivity Successfully Added.");
	
}

oci_free_statement($stmt);
oci_close($conn);
?>
<br />
<a href="index.html">Homepage</a>
