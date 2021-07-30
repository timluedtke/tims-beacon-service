<?php
include("db_connection.php");

$ipaddress = $_SERVER['REMOTE_ADDR'];
$whois = processInputValue($_GET['whois'], $dbConnection);
$cpuTemp = processInputValue($_GET['cputemp'], $dbConnection);
$privateip = processInputValue($_GET['privateip'], $dbConnection);
$uptime = processInputValue($_GET['uptime'], $dbConnection);
$payload = processInputValue($_GET['payload'], $dbConnection);

if ($whois == "NULL") {
    return;
}

if ($payload == "" or $payload == "test" or $payload == "none" or $payload == "NULL") {
    $query_for_inserting = "INSERT INTO `beacons` (`b_devicename`, `b_ip`, `b_privateip`, `b_cputemp`, `b_uptime`) VALUES ('" . $whois . "','" . $ipaddress . "','" . $privateip . "','" . $cpuTemp . "','" . $uptime . "');";
} else {
    $query_for_inserting = "INSERT INTO `beacons` (`b_devicename`, `b_ip`, `b_privateip`, `b_cputemp`, `b_uptime`, `b_payload`) VALUES ('" . $whois . "','" . $ipaddress . "','" . $privateip . "','" . $cpuTemp . "','" . $uptime . "','" . $payload . "');";
}
saveQuery($dbConnection, $query_for_inserting);
$dbConnection->close();

function processInputValue($inputValue, $dbConnection)
{
    return nullOrEscaped(substr(trim($inputValue), 0, 250), $dbConnection);
}

function nullOrEscaped($value, $dbConnection)
{
    if (empty($value)) {
        return null;
    } else {
        return mysqli_real_escape_string($dbConnection, $value);
    }
}

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