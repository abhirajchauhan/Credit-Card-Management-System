<?php
$err = 1;
while($err > 0) {
	$err = 0;
	$susername = "";
	$spassword = "";
	if (empty($_POST['susername'])) {
		++$err;
		echo "<p> Enter username.</p>\n";
	}
	if (empty($_POST['spassword'])) {
		++$err;
		echo "<p> Enter password.</p>\n";
	}
}
$dbconnect = oci_connect($db_user, $db_pass, $db_host);
if ($dbconnect === FALSE) {
	echo "<p>Unable to connect to the database server.</p>\n";
	++$err;
}

if ($err == 0) {
	$plsql = "SELECT NAME FROM STAFF, STAFF_NAME_SSN WHERE USERNAME = :susername AND PASSWORD = :spassword AND STAFF.SSN = STAFF_NAME_SSN.SSN";
	$stmt = oci_parse($dbconnect, $plsql);
	oci_bind_by_name($stmt, ":susername", $susername);
	oci_bind_by_name($stmt, ":spassword", $spassword);
	oci_define_by_name($stmt, "NAME", $staffname);
	oci_execute($stmt);
	if (oci_fetch($stmt)) {
		echo "<p>Welcome back, $staffname.</p>\n";
	}
	else {
		echo "<p>Username and password combination is not valid.</p>\n";
		++$err;
	}
}
?>