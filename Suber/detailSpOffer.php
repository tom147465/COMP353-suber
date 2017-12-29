<?php 

session_start();
require_once("parameter.php");


if(!isset($_SESSION['user']))
{
	header('location:index.php');
	exit;
}
if(isset($_POST['id'])&&isset($_POST['price']))
{
	if($_SESSION['user']['balance']>$_POST['price'])
	{
		$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
		$pdo->exec("SET NAMES 'utf8';");

		$prep = $pdo -> prepare("UPDATE specialoffer set customer= :customer, price= :price WHERE ID=:id");
		$prep -> bindParam(':customer', $_SESSION['user']['username'] , PDO::PARAM_STR);
		$prep -> bindParam(':price', $_POST['price'], PDO::PARAM_STR);
		$prep -> bindParam(':id', $_POST['id'] , PDO::PARAM_STR);
		$rs = $prep -> execute();
		
		$balance = $_SESSION['user']['balance'] - $_POST['price'];
		$prep1 = $pdo -> prepare("UPDATE member SET balance= :balance WHERE username= :username");
		$prep1 -> bindParam(':username', $_SESSION['user']['username'], PDO::PARAM_STR);
		$prep1 -> bindParam(':balance', $balance, PDO::PARAM_STR);
		$rs1 = $prep1 -> execute();
		
		if($rs&&$rs1)
		{
			$_SESSION['user']['balance'] = $balance;
			echo "Book successfully";
			exit;
		}
	}
	else
	{
		echo "you do not have enough balance in your account!!!";
		exit;
	}
}
if(isset($_GET['delete']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$prep = $pdo -> prepare("DELETE FROM specialoffer WHERE ID = :id");
	$prep -> bindParam(':id', $_GET['delete'] , PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs)
	{
		echo "<script type='text/javascript'>alert('Deleted successfully'); location.replace('home.php')</script>";
		exit;
	}
}
elseif(isset($_GET['id']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM specialoffer WHERE id='".$_GET['id']."'");
	if($rs->rowCount() == 0)
	{
		echo "<script type='text/javascript'>alert('Sent Message successfully');</script>";
		header('location:home.php');
	}
	else
	{
		$content = $rs->fetch();
	}
}
?>


<html>
  <head>
  
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
	
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
    <title>Draggable directions</title>
	<link rel="stylesheet" type="text/css" href="css/suber.css">
	
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
		left: 10px;
		top: 20px;
        height: 80%;
        float: left;
        width: 40%;
      }
      #right-panel {
        float: right;
        width: 50%;
        height: 100%;
      }
#right-panel {
  font-family: 'Roboto','sans-serif';
  line-height: 30px;
  padding-left: 10px;
}

#right-panel select, #right-panel input {
  font-size: 15px;
}

#right-panel select {
  width: 100%;
}

#right-panel i {
  font-size: 12px;
}

.panel {
	height: 100%;
	overflow: auto;
}
</style>
  </head>
  <body>
    <div id="map"></div>
    <div id="right-panel">
		<br><a href="home.php">Cancel</a><br>
		
		
		<b>Date: <?= $content['Date']?>
			---<font style="color:red;">Depart time: <?= $content['depart_time'] ?> </font>
		</b><br>
		type: <?= $content['type'] ?>
		<p>From: <?= $content['City_departure']." ".$content['Postcode_departure']?><br>
		TO: <?= $content['City_destination']." ".$content['Postcode_destination']?><br>
		Total Distance: <span id="total"></span></p>
		price: <b id="totalprice" style="font-size:20px;"></b> c$ (1 c$/Km)
		<p><?= $content['detail']?></p><br><br>
		<div id="button">
		<?php if($_SESSION['user']['username'] == $content['driver']): ?>
			<button onclick="location.replace('modifySpOffer.php?id=<?=$_GET['id']?>')">modify</button>
			<button onclick="location.replace('detailOffer.php?delete=<?=$_GET['id']?>')">delete</button>
		<?php elseif(!empty($content['customer'])): ?>
			<button disabled >booked</button>
			
		<?php else:?>
			<button onclick='$( "#confirm" ).dialog({
									resizable: false,
									height: 300,
									width: 400,
									modal: true,
									buttons: {
										"Confirm": function() {
											$.post("detailSpOffer.php",{id:<?=$_GET['id']?>, price:$("#price").text() },
											function(result){
												alert(result);
												location.replace("home.php");
											});
											$( this ).dialog( "close" );
										},
										Cancel: function() {
											$( this ).dialog( "close" );
										}
									}});'>Book
			</button>
		<?php endif;?>
		</div>
    </div>

	<div id="confirm" style='display:none'>
		<b>Date: <?= $content['Date'] ?></b><br>
		<p>From: <?= $content['City_departure']." ".$content['Postcode_departure']?><br>
		TO: <?= $content['City_departure']." ".$content['Postcode_departure']?><br>
		Total Distance: <span id="distance"></span></p>
		Pay: <b id="price" style="font-size:20px;"></b> c$ (1 c$/Km)
	</div>
    <script>
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 4,
    center: {lat: 62.227, lng: 105.380}
  });

  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer({
    draggable: true,
    map: map,
  });

  directionsDisplay.addListener('directions_changed', function() {
    computeTotalDistance(directionsDisplay.getDirections());
  });

  displayRoute('<?= $content['City_departure']." ".$content['Postcode_departure']?>, CA', '<?= $content['City_destination']." ".$content['Postcode_destination']?>, CA', directionsService,
      directionsDisplay);
}

function displayRoute(origin, destination, service, display) {
  service.route({
    origin: origin,
    destination: destination,
    travelMode: google.maps.TravelMode.DRIVING,
    avoidTolls: false
  }, function(response, status) {
    if (status === google.maps.DirectionsStatus.OK) {
      display.setDirections(response);
    } else {
      alert('Could not display directions due to: ' + status);
    }
  });
}

function computeTotalDistance(result) {
  var total = 0;
  var myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
  }
  total = total / 1000;
  document.getElementById('total').innerHTML = total + ' km';
  document.getElementById('distance').innerHTML = total + ' km';
  document.getElementById('price').innerHTML = (total * 1).toFixed(2);
  document.getElementById('totalprice').innerHTML = (total * 1).toFixed(2);
}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlGLkm55s_8dY9-AHMffqrqewylwlFPBU&callback=initMap"
        async defer></script>
  </body>
</html>