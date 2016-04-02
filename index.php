<?php 
    require('dashboard.php');
?>
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
<?php
    displayDashboard($myLocations, $myStations, $CitiBikeData['executionTime']);
?>
</body>
</html>