<html>
<head>
<meta http-equiv="refresh" content="300">
<title>Citibikes Dashboard</title>
<style>
  @import url(http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,300,400,700);
  body {
    font-family: 'Open Sans', sans-serif;
  }
  th{
    background-color: #83B7E1;
  font-weight: heavy;
  }
</style>
</head>
<body>

<?
$url = 'http://www.citibikenyc.com/stations/json'; //Citibike JSON data dump
$threshold = 5; //When available bikes or docks are less than this, the location will be bolded and read
$json = file_get_contents($url);
$array = json_decode($json,true);
?>

<?=$array["executionTime"]?> | <a href="https://www.citibikenyc.com/stations">citibike live station map</a><br>

<?
    $array = $array["stationBeanList"];
    foreach ($array as $name => $value) {
        if ($value["stationName"] == "E 17 St & Broadway")
            $key_17andBroadway =  $name;
        elseif ($value["stationName"] == "E 16 St & 5 Ave")
            $key_16and5 = $name;
        elseif ($value["stationName"] == "9 Ave & W 16 St")
            $key_9and16 = $name;
        elseif ($value["stationName"] == "9 Ave & W 14 St")
            $key_9and14 = $name;
    }
    function displayText ($number, $text, $threshold)
    {
        $str = $number." @ ".$text;
        if (intval($number) < $threshold)
            $str = "<b><font color='red'>".$str."</font></b>";
        return $str;
    }
?>

<table cellpadding="2">
    <tr><th colspan="2">To Cornell Tech</th></tr>
    <tr><td>Available Bikes</td><td>Available Docks</td></tr>
    <tr valign="top">
        <td><?=displayText($array[$key_17andBroadway]["availableBikes"],  $array[$key_17andBroadway]["stationName"], $threshold)?><br>
            <?=displayText($array[$key_16and5]["availableBikes"],$array[$key_16and5]["stationName"], $threshold)?>
        </td>
        <td><?=displayText($array[$key_9and16]["availableDocks"],$array[$key_9and16]["stationName"], $threshold)?><br>
            <?=displayText($array[$key_9and14]["availableDocks"],$array[$key_9and14]["stationName"], $threshold)?>
        </td>
    </tr>
    <tr><th colspan="2">From Cornell Tech</th></tr>
    <tr><td>Available Bikes</td><td>Available Docks</td></tr>
    <tr valign="top">
        <td><?=displayText($array[$key_9and14]["availableBikes"],$array[$key_9and14]["stationName"], $threshold)?><br>
        <?=displayText($array[$key_9and16]["availableBikes"],$array[$key_9and16]["stationName"], $threshold)?>        </td>
        <td><?=displayText($array[$key_16and5]["availableDocks"],$array[$key_16and5]["stationName"], $threshold)?><br>
        <?=displayText($array[$key_17andBroadway]["availableDocks"],$array[$key_17andBroadway]["stationName"], $threshold)?><br>
        </td>
    </tr>
</table>
</body>
</html>
