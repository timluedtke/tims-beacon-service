<html>
<head>
  <title>Wetter</title>
  <meta http-equiv="refresh" content="150">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    <?php
    include("db_connection.php");
    $deviceName = "tlraspi1";
    $GLOBALS["lastTempValue"] = "";
    $GLOBALS["lastHumidValue"] = "";
	if(isset($_REQUEST['timeframe']) && $_REQUEST['timeframe']!="") {
 		$timeframe= $_REQUEST['timeframe'];
	} else {
		$timeframe = "1";
	}
    drawTempChart($deviceName, "chart_div1", $dbConnection, $timeframe);
    $dbConnection->close();

    function drawTempChart($device, $chartId, $dbConnection, $timeframe)
    {
        echo "google.charts.setOnLoadCallback(draw" . $device . "Chart);\r\n";

        echo "function draw" . $device . "Chart() {";
        echo "var data = google.visualization.arrayToDataTable([";
        echo "['Datum', 'Temperatur in °C', 'relative Luftfeuchte in %']";
        $query_for_showing = "SELECT `b_datetime_tstamp`, `b_payload` FROM `beacons` WHERE `b_devicename` = \"" . $device . "\" AND `b_payload` != \"NULL\" AND `b_datetime_tstamp` > (NOW() - INTERVAL ".$timeframe." DAY) ORDER BY `b_datetime_tstamp` ASC";
        $allTemperatures = $dbConnection->query($query_for_showing);
        while ($row = $allTemperatures->fetch_assoc()) {
            $json_decoded = json_decode($row["b_payload"], true);
            $temperature = $json_decoded['temperature'];
            $humidity = $json_decoded['humidity'];
            echo ",['" . $row["b_datetime_tstamp"] . "'," . $temperature . "," . $humidity . "]";
            $GLOBALS["lastTempValue"] = $temperature;
            $GLOBALS["lastHumidValue"] = $humidity;
        }
        echo "]);";

        echo "var options = {";
        echo "title: 'Temperatur und Luftfeuchte der letzten ".$timeframe." Tage',";
        echo "crosshair: { trigger: 'both' },";
        echo "hAxis: {title: 'Zeitachse', titleTextStyle: {color: '#333'}},";
        echo "vAxis: {title: 'Temperatur & Luftfeuchte'},";
        echo "lineWidth: 3";
        echo "";
        echo "};";
        echo "var chart = new google.visualization.AreaChart(document.getElementById('" . $chartId . "'));";
        echo "chart.draw(data, options);";
        echo "}\r\n";
    }
    ?>
  </script>
</head>
<body>
<div style="width: 100%; font-family:'Arial Black'; font-size: 32px; text-align: center"><?php echo "aktuell: " . $GLOBALS["lastTempValue"] . "°C & " . $GLOBALS["lastHumidValue"] . "%rLF";?>
<div class="dropdown">
	<form action="raspi1dht22.php" method="POST" style="margin: 0;">
  	<select name="timeframe">
    	<option value="1" <?php if($timeframe == "1") echo "selected"; ?>>24 Stunden</option>
    	<option value="2" <?php if($timeframe == "2") echo "selected"; ?>>48 Stunden</option>
    	<option value="7" <?php if($timeframe == "7") echo "selected"; ?>>1 Woche</option>
    	<option value="30" <?php if($timeframe == "30") echo "selected"; ?>>1 Monat</option>
  	</select><input type="submit" value="Go" />
	</form>
</div>
<div id="chart_div1" style="width: 100%; height: 800px;"></div>
</div>
</body>
</html>