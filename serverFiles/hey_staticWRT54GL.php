<?php
$version = "1.2";
include("db_connection.php");

$ipaddress = $_SERVER['REMOTE_ADDR'];
$whois = "WRT54GL";

$query_for_inserting = "INSERT INTO `beacons` (`b_devicename`, `b_ip`) VALUES ('" . $whois . "','" . $ipaddress . "');";
saveQuery($dbConnection, $query_for_inserting);
$dbConnection->close();

function processInputValue($inputValue, $dbConnection)
{
    return nullOrEscaped(substr(trim($inputValue), 0, 250), $dbConnection);
}

function nullOrEscaped($value, $dbConnection)
{
    if (empty($value)) {
        return "NULL";
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
