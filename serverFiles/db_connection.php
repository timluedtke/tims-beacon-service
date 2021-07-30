<?php
$dbConnection = new mysqli("localhost", "user", "password", "tim_beacon");

if ($dbConnection->connect_error) {
    die("DB Connection failed");
}
?>