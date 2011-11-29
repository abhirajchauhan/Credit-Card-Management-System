<?php

require "config.php";

function connect($dbUser, $dbPass, $dbHost)
{


$conn = oci_connect($dbUser, $dbPass, $dbHost);

}

function disconnect()
{
oci_close($conn);

}
?>