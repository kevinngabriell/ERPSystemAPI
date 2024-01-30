<?php

$hostname = "erp_systems";
$database = "";
$username = "root";
$password = "";

$connect = mysqli_connect($hostname, $username, $password, $database);

if (!$connect) {
    die("Connection error" . mysqli_connect_error());
}

?>