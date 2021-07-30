<?php
$version = "1.1";
include("db_connection.php");

$whois = mysqli_real_escape_string($dbConnection, trim($_GET['whois']));
$ipaddress = $_SERVER['REMOTE_ADDR'];
$payload = substr(trim($_GET['payload']), 0, 250);
$payload = empty($payload) ? "NULL" : mysqli_real_escape_string($dbConnection, $payload);

$query_for_inserting = "INSERT INTO `beacons` (`b_devicename`, `b_ip`, `b_cputemp`) VALUES ('" . $whois . "','" . $ipaddress . "','" . $payload . "');";
saveQuery($dbConnection, $query_for_inserting);
$dbConnection->close();

function saveQuery($dbConnection, $query_for_inserting)
{
    if ($dbConnection->query($query_for_inserting) === TRUE) {
        echo "OK";
        http_response_code(200);
    } else {
        echo "NOK";
        http_response_code(418);
    }
}
?>