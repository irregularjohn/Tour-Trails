<!DOCTYPE html>
<html>
<head>
<title>Tour Trails</title>
 <meta charset='utf-8'>
     <link href="tour.css" rel="stylesheet" type="text/css" />
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>


<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?sensor=false">
    </script>

	     <script src="trails.js" type="text/javascript"></script>
</head>

 

<body onload="load()">
<div id="cont">
<div id="left">
Band name: <BR/>

	<input type="text" id="band" class="stdinput" onKeyPress="if (event.which == 13) doRequest();"></input>
	<input type="button" class="stdbutton" onclick="doRequest()" value="GO"/>
	<div id="loader"><img src="ajax-loader.gif"> <small>Downloading data...</small></div>
<div id="years">
</div>
<?php

?>

</div>
<div id="right">

<div id="map"></div>

<?php

?>

</div>

</div>

<!--  ///////////// scripts -->
<div id="scripts"></div>
<div id="lastfm"><img src="lastfm-logo.png"> Data provided by Last.fm.</div>
</body>




</html>