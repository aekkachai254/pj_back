<?php
$db_host = "teamproject.ddns.net";
$db_user ="team";
$db_pass = "Te@m1234!";
$db_name ="management";

$con = mysqli_connect($db_host,$db_user,$db_pass,$db_name);

if ($con->connect_error) {
    die("connection failed:" .$con->connect_error);
}