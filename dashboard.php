<?php
    $JSON_URL = "http://www.citibikenyc.com/stations/json"; //Citibike JSON data dump
    $MAP_URL = "https://member.citibikenyc.com/map/"; //Citibike station map
    $THRESHOLD = 5; //When available bikes or docks are less than this, the location will be bolded and red
    $IN_SERVICE = "In Service"; //String used by Citibike API to show that station is in service

    $myLocations = array();
    //Enter as many stations as you'd like using Citibike's exact station name
    $myLocations["HOME"] = array("E 17 St & Broadway", "E 16 St & 5 Ave");
    $myLocations["WORK"] = array("W 44 St & 5 Ave", "Pershing Square North", "Pershing Square South");

    //Initial setup of station data for selected stations
    $json = file_get_contents($JSON_URL);
    $CitiBikeData = json_decode($json,true);
    $StationData = $CitiBikeData["stationBeanList"];
    $myStations = array();
    foreach ($myLocations as $key => $stations) {
        $rank = 1;
        foreach ($stations as $station) {
            $stationIndex = findStationIndex($station, $StationData);
            $myStations[] = new CitiBikeStation($station, $key, $rank, $StationData[$stationIndex]["availableBikes"], $StationData[$stationIndex]["availableDocks"], $StationData[$stationIndex]["statusValue"]);

            $rank++;
        }
    }

    //Data structure for station data
    class CitiBikeStation {
        public $name, $bikes, $docks, $status, $type, $rank;

        function __construct($name, $type, $rank = 1, $bikes = 0, $docks = 0, $status = "") {
            $this->name = $name;
            $this->type = $type;
            $this->rank = $rank;
            $this->bikes = $bikes;
            $this->docks = $docks;
            $this->status = $status;
        }
    }

    //Finds index of station in the array
    function findStationIndex($checkStationName, $dataArray) {
        foreach($dataArray as $key => $station) 
            if ($checkStationName == $station["stationName"]) return $key;
        return -1;
    }

    //Formats and displays station
    function displayStation ($station, $value)
    {
        global $THRESHOLD, $IN_SERVICE;
        $str = $station->$value." @ ".$station->name;

        if ($station->status != $IN_SERVICE)
            return "<b><font color='red'>".$station->status."</font></b>";
        if (intval($station->$value) < $THRESHOLD)
            return "<b><font color='red'>".$str."</font></b>";

        return $str;
    }

    //Formats and displays dashboard of stations
    function displayDashboard($myLocations, $myStations, $lastUpdated) {
?>
 <?=$lastUpdated?> | <a href="<?=$GLOBALS['MAP_URL']?>" target="_blank">citibike live station map</a>
 <table cellpadding="2">
            <tr><th colspan="2">Going to work</th></tr>
            <tr><td>Available Bikes</td><td>Available Docks</td></tr>
            <tr valign="top">
        <?php      
                    $count = 0;
                    foreach ($myLocations as $type => $location) { 
        ?>
                    <td>
        <?php 
                        foreach ($myStations as $station) 
                            if ($station->type == $type)  
                                echo displayStation($station, $count == 0 ? "bikes" : "docks")."<br />";
        ?>
                    </td>
        <?php       
                        $count++;
                    } 
        ?>
            </tr>
            <tr><th colspan="2">Heading home</th></tr>
            <tr><td>Available Bikes</td><td>Available Docks</td></tr>
            <tr valign="top">
            <tr valign="top">
        <?php     
                    $count = 0;
                    foreach (array_reverse($myLocations) as $type => $location) { 
        ?>
                    <td>
        <?php 
                        foreach ($myStations as $station) 
                            if ($station->type == $type) 
                                echo displayStation($station, $count == 0 ? "bikes" : "docks")."<br />";
        ?>
                    </td>
        <?php       
                        $count++;
                    }  
        ?>
            </tr>
        </table>
<?php
    }
?>