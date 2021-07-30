<html>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    <?php
    include("db_connection.php");

    $queryForDevices = "SELECT DISTINCT `b_devicename` FROM `beacons` ORDER BY `b_devicename` ASC";
    $devices = $dbConnection->query($queryForDevices);
    $numberOfCharts = 1;
    while ($deviceRow = $devices->fetch_assoc()) {
        $deviceName = $deviceRow["b_devicename"];
        drawTempChart($deviceName, "chart_div" . $numberOfCharts, $dbConnection);
        $numberOfCharts++;
    }
    $dbConnection->close();

    function drawTempChart($device, $chartId, $dbConnection)
    {
        echo "google.charts.setOnLoadCallback(draw" . $device . "Chart);\r\n";

        echo "function draw" . $device . "Chart() {";
        echo "var data = google.visualization.arrayToDataTable([";
        echo "['Date', 'Temp']";
        $query_for_showing = "SELECT `b_datetime_tstamp`, `b_cputemp` FROM `beacons` WHERE `b_devicename` = \"" . $device . "\" AND `b_cputemp` != \"NULL\" ORDER BY `b_datetime_tstamp` ASC";
        $allTemperatures = $dbConnection->query($query_for_showing);
        while ($row = $allTemperatures->fetch_assoc()) {
            echo ",['" . $row["b_datetime_tstamp"] . "'," . $row["b_cputemp"] . "]";
        }
        echo "]);";

        echo "var options = {";
        echo "        title: 'CPU Temperature over time - Device: " . $device . "',";
        echo "hAxis: {title: 'Date', titleTextStyle: {color: '#333'}},";
        echo "vAxis: {title: 'Temperature', minValue: 20}";
        echo "};";
        echo "var chart = new google.visualization.AreaChart(document.getElementById('" . $chartId . "'));";
        echo "chart.draw(data, options);";
        echo "}\r\n";
    }
    ?>
  </script>
</head>
<body>
<?php
for ($i = 1; $i < $numberOfCharts; $i++) {
    echo "<div id=\"chart_div" . $i . "\" style=\"width: 100%; height: 800px;\"></div>";
}
?>
</body>
</html>