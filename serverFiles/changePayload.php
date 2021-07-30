<?php
include("db_connection.php");

$query_for_showing = "SELECT `b_id` FROM `beacons` WHERE `b_payload` = 'NULL'";
$result = $dbConnection->query($query_for_showing);

$count = 0;
while ($row = $result->fetch_assoc()) {
    $count++;
    $updateQuery = "UPDATE `beacons` SET `b_payload` = NULL WHERE `b_id` = " . $row["b_id"];
    $dbConnection->query($updateQuery);
}
echo $count . " updated";
?>