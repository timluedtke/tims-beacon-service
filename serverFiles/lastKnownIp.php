<?php
include("db_connection.php");
$version = "v1.5.1";
?>
  <html>
  <head>
    <meta charset="utf-8">
    <title>Beacon Service of timluedtke.de</title>
    <style>
      body {
        text-align: center;
        font: normal 12px/150% Arial, Helvetica, sans-serif;
      }

      .datagrid table {
        border-collapse: collapse;
        text-align: center;
        width: 100%;
      }

      .datagrid {
        margin-right: auto;
        margin-left: auto;
        width: 1000px;
        font: normal 12px/150% Arial, Helvetica, sans-serif;
        background: #fff;
        overflow: hidden;
        border: 1px solid #006699;
        -webkit-border-radius: 11px;
        -moz-border-radius: 11px;
        border-radius: 11px;
      }

      .datagrid table td, .datagrid table th {
        padding: 15px 15px;
      }

      .datagrid table thead th {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F));
        background: -moz-linear-gradient(center top, #006699 5%, #00557F 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');
        background-color: #006699;
        color: #FFFFFF;
        font-size: 15px;
        font-weight: bold;
        border-left: 1px solid #0070A8;
      }

      .datagrid table thead th:first-child {
        border: none;
      }

      .datagrid table tbody td {
        color: #00496B;
        border-left: 1px solid #E1EEF4;
        font-size: 14px;
        font-weight: normal;
      }

      .datagrid table tbody .alt td {
        background: #E1EEF4;
        color: #00496B;
      }

      .datagrid table tbody .break td {
        border-top: 4px #13364a double;
      }

      .datagrid table tbody td:first-child {
        border-left: none;
      }

      .datagrid table tbody tr:last-child td {
        border-bottom: none;
      }

      .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
      }

      .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
      }

      .tooltip:hover .tooltiptext {
        visibility: visible;
      }
    </style>

  </head>
  <body>
  <div style="margin-bottom:10px">
    <img src="Beacon_ballonicon2.svg" alt="beacon" height="120px" width="120px">
    <!-- CC-BY-3.0 author: pixelbuddha @ https://commons.wikimedia.org/wiki/File%3ABeacon_ballonicon2.svg -->
  </div>
  <div class="datagrid">
    <table>
      <thead>
      <tr>
        <th>Device</th>
        <th>last Seen</th>
        <th>external IP</th>
        <th>internal IP</th>
        <th>Temparture</th>
        <th>Uptime</th>
        <th>Payload</th>
      </tr>
      </thead>
      <tbody>
<?php
$alternatingRowNow = false;
$hasBreakLineBeenUsed = false;
$query_for_devices_id = "SELECT MAX(`b_id`) AS `b_id` FROM `beacons` GROUP BY `b_devicename` ORDER BY `b_id` DESC";
$ids = $dbConnection->query($query_for_devices_id);

while ($idsRow = $ids->fetch_assoc()) {
    $query_for_showing = "SELECT * FROM `beacons` WHERE `b_id` = " . $idsRow["b_id"];
    $result = $dbConnection->query($query_for_showing);
    while ($row = $result->fetch_assoc()) {
        $breakClass = "";
        if (!$hasBreakLineBeenUsed && longerThanLimit($row["b_datetime_tstamp"])) {
            $breakClass = " break";
            $hasBreakLineBeenUsed = true;
        }
        if ($alternatingRowNow) {
            $cssAlt = " class=\"alt" . $breakClass . "\"";
            $alternatingRowNow = false;
        } else {
            $cssAlt = $breakClass != "" ? " class=\"" . $breakClass . "\"" : "";
            $alternatingRowNow = true;
        }
        $lastSeen = convertLastSeen($row["b_datetime_tstamp"]);
        echo " <tr" . $cssAlt . ">
          <td><b>" . $row["b_devicename"] . "</b></td>
          <td><div class=\"tooltip\">" . $lastSeen[0] . "<span class=\"tooltiptext\">" . $lastSeen[1] . "</span></div></td>
          <td>" . $row["b_ip"] . "</td><td>" . $row["b_privateip"] . "</td>
          <td>" . formatTemperature($row["b_cputemp"]) . "</td>
          <td>" . timestampToDuration(surpressStupidValues($row["b_uptime"])) . "</td>
          <td>" . surpressStupidValues($row["b_payload"]) . "</td></tr>";
    }
}
$dbConnection->close();

echo " </tbody>
    </table>
  </div>
  <div style=\"margin-top:5px\">Tims Beacon Service " . $version . "</div>
  </body>
  </html>";


function timestampToDuration($timestamp)
{
    if ($timestamp == "") return "";
    $uptimeHours = abs($timestamp - time()) / 60 / 60;
    if ($uptimeHours > 24) {
        return floor($uptimeHours / 24) . " Tage";
    }
    return floor($uptimeHours) . " Stunden";
}

function surpressStupidValues($value)
{
    if ("" == $value OR "NULL" == $value OR "up" == $value OR "\"\"" == $value) {
        return "";
    }
    return $value;
}

function longerThanLimit($datetime_text)
{
    $date = DateTime::createFromFormat("Y-m-d H:i:s", $datetime_text);
    $now = new DateTime();
    return $now->diff($date)->days > 1;
}

function formatTemperature($tempString)
{
    if ("" == $tempString OR "NULL" == $tempString) {
        return "";
    }
    return $tempString . " °C";
}

function convertLastSeen($datetime_text)
{
    $date = DateTime::createFromFormat("Y-m-d H:i:s", $datetime_text);
    $textDate[0] = "vor <b>" . time_since($date) . "</b>";
    $textDate[1] = $date->format('H:i:s d.m.Y');
    return $textDate;
}

function time_since($since)
{
    $chunks = array(
        array(60 * 60 * 24 * 365, 'Jahr', 'Jahren'),
        array(60 * 60 * 24 * 30, 'Monat', 'Monaten'),
        array(60 * 60 * 24 * 7, 'Woche', 'Wochen'),
        array(60 * 60 * 24, 'Tag', 'Tagen'),
        array(60 * 60, 'Stunde', 'Stunden'),
        array(60, 'Minute', 'Minuten'),
        array(1, 'Sekunde', 'Sekunden')
    );
    $count = 0;
    $singular = "";
    $plural = "";
    $since = time() - date_timestamp_get($since);
    for ($i = 0, $numOfChunks = count($chunks); $i < $numOfChunks; $i++) {
        $seconds = $chunks[$i][0];
        $singular = $chunks[$i][1];
        $plural = $chunks[$i][2];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }
    return ($count == 1) ? '1 ' . $singular : "$count {$plural}";
}

?>