<?php
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user'])&&$_SESSION['user']['status']=='inactive')
{
	header('location:login.php');
	exit;
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>

<title>search Trip</title>
</head>
<script>
$(document).ready(function()  
{
	$("#search").click(function(){
		$("#result").load("findTrip.php", 
		{
			departure:$("#city_depart").val()+" "+$("#postcode_depart").val(), 
			destination:$("#city_destin").val()+" "+$("#postcode_destin").val(),
			radius:$("#radius").val()
		});
	});
});

</script>
<body>
<?php require_once("header.php"); ?> 
<h1>Search Trip</h1>
<div>
	<br><label>Departure:</label>
	<input type="text" name="city_depart" id="city_depart" placeholder="City of departure" required/>
	<input type="text" name="postcode_depart" id="postcode_depart" placeholder="postal code" required/>

	<label>Destination:</label>
	<input type="text" name="city_destin" id="city_destin" placeholder="City of destination" required/>
	<input type="text" name="postcode_destin" id="postcode_destin" placeholder="postalcode" required/>
</div>
<div>
	<br><label>radius(km):</label>
	<input type="number" name="radius" id="radius" placeholder="radius" min="5" required/>
	<button id="search"> Search </button>
</div>
<br><br>
<div id="result"></div>
</body>
</html>