<?php
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user'])&&$_SESSION['user']['status']=='inactive')
{
	header('location:login.php');
	exit;
}

if(isset($_POST['departure'])&&isset($_POST['destination'])&&isset($_POST['radius']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM trip WHERE Number_rider > Number_offer ORDER BY ID desc");
	$content = $rs->fetchAll();
	//print_r($content);
}
?>
<div id="location" style="display:none" >
<p id="lat1" ></p>
<p id="lng1" ></p>
<p id="lat2" ></p>
<p id="lng2" ></p>
</div>


<div>
<b>Results:</b><br><br>
<table style="width:60%" id="result_table">
<tr><th>trip date</th><th>depart time</th><th>driver</th> <th>Departure</th> <th>Destination</th> <th>SeeDetails</th></tr>

</table>
</div>

<script>
function initMap() 
{
	var geocoder = new google.maps.Geocoder();
	
	geocoder.geocode({'address': '<?= $_POST['departure']?>, CA'}, function(results, status) {
		if (status === google.maps.GeocoderStatus.OK) {
			
			$("#lat1").text(results[0].geometry.location.lat());
			$("#lng1").text(results[0].geometry.location.lng());
			geocoder.geocode({'address': '<?= $_POST['destination']?>, CA'}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
				  $("#lat2").text(results[0].geometry.location.lat());
				  $("#lng2").text(results[0].geometry.location.lng());
				} else {
				  alert('Geocode was not successful for the following reason: ' + status);
				}
			});
			
		} else {
		  alert('Geocode was not successful for the following reason: ' + status);
		}
	});
	
	
	
<?php foreach($content as $trip) { ?>
	geocoder.geocode({'address': '<?= $trip['City_departure']." ".$trip['Postcode_departure'] ?>, CA'}, function(results, status) {
		if (status === google.maps.GeocoderStatus.OK) {
			var latlng_t1 = results[0].geometry.location;
			
			geocoder.geocode({'address': '<?= $trip['City_destination']." ".$trip['Postcode_destination'] ?>, CA'}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					var latlng_t2 = results[0].geometry.location;
					var latlng1 = new google.maps.LatLng($("#lat1").text(),$("#lng1").text());
					var latlng2 = new google.maps.LatLng($("#lat2").text(),$("#lng2").text());
					var distance1 = google.maps.geometry.spherical.computeDistanceBetween(latlng1, latlng_t1);
					var distance2 = google.maps.geometry.spherical.computeDistanceBetween(latlng2, latlng_t2);
					if(distance1 < <?= $_POST['radius']*1000?> && distance2 < <?= $_POST['radius']*1000?>)
					{
						<?php if($trip['driver']==$_SESSION['user']['username']): ?>
						var driver = "<td><?php echo $trip['driver'];?></td>";
						<?php else :?>
						var driver=	"<td><a href='javascript:;' value='<?php echo $trip['driver'];?>' ><?php echo $trip['driver'];?></a></td>";
						<?php endif; ?>
						
						
						var content = 	"<tr align='center'><td><?= $trip['Date'].$trip['Day']?></td>"+
										"<td> <?= $trip['depart_time']?></td>"+ driver +
										"<td><?php echo $trip['City_departure'].' '.$trip['Postcode_departure']?></td>"+
										"<td><?php echo $trip['City_destination'].' '.$trip['Postcode_destination']?></td>"+
										"<td><button type='button' onclick=\"location.replace('detailTrip.php?id=<?php echo $trip['ID'] ?>');\">detail</button></td><tr>";
						$("#result_table").append(content);
					}
				} else {
					alert('Geocode was not successful for the following reason: ' + status);
				}
			});
			//$("#latlng_t1").text("2"+results[0].geometry.location);
			//$("#location").append('<b>'+results[0].geometry.location+'</b>');
		} else {
			alert('Geocode was not successful for the following reason: ' + status);
		}
	});
<?php }?>
}

function check(latlng_t1, latlng_t2){
	var latlng1 = new google.maps.LatLng($("#lat1").text(),$("#lng1").text());
	var latlng2 = new google.maps.LatLng($("#lat2").text(),$("#lng2").text());
	var distance1 = google.maps.geometry.spherical.computeDistanceBetween(latlng1, latlng_t1);
	var distance2 = google.maps.geometry.spherical.computeDistanceBetween(latlng2, latlng_t2);
	if(distance1 < <?= $_POST['radius']*1000?> && distance2 < <?= $_POST['radius']*1000?>)
	{
		<?php if($trip['driver']==$_SESSION['user']['username']): ?>
		var driver = "<td><?php echo $trip['driver'];?></td>";
		<?php else :?>
		var driver=	"<td><a href='javascript:;' value='<?php echo $trip['driver'];?>' ><?php echo $trip['driver'];?></a></td>";
		<?php endif; ?>
		
		
		var content = 	"<tr align='center'><td><?= $trip['Date'].$trip['Day']?></td>"+
						"<td> <?= $trip['depart_time']?></td>"+ driver +
						"<td><?php echo $trip['City_departure'].' '.$trip['Postcode_departure']?></td>"+
						"<td><?php echo $trip['City_destination'].' '.$trip['Postcode_destination']?></td>"+
						"<td><button type='button' onclick=\"location.replace('detailTrip.php?id=<?php echo $trip['ID'] ?>');\">detail</button></td><tr>";
		$("#result_table").appand(content);
	}
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlGLkm55s_8dY9-AHMffqrqewylwlFPBU&libraries=geometry&callback=initMap"
        async defer></script>


