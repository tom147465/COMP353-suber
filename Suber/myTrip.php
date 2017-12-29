<?php

session_start();
require_once("parameter.php");
if(!isset($_SESSION['user'])&&$_SESSION['user']['status']=='inactive')
{
	header('location:login.php');
	exit;
}
else
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM trip WHERE driver='".$_SESSION['user']['username']."' ORDER BY ID desc");
	$content = $rs->fetchAll();
}
?>
<html>
<head>
<title>My Trip</title>
<link rel="stylesheet" type="text/css" href="css/suber.css">
</head>
<body>

<?php require_once("header.php"); ?> 
<h1>My Trip</h1>
<?php if($_SESSION['user']['status']!='inactive'): ?>
	<div id="current_trip">
		<table style="width:60%">
			<tr><th>trip date</th><th>depart time</th><th>driver</th> <th>Departure</th> <th>Destination</th> <th>GoDetail</th></tr>
			<?php foreach($content as $trip) { ?>
				<tr align="center">
					<?php if(!empty($trip['Date'])): ?>
						<td><?php echo $trip['Date']?></td>
					<?php else: ?>
						<td> <?php echo $trip['Day']?></td>
					<?php endif; ?>
					<td> <?php echo $trip['depart_time']?></td>
					<td><?php echo $trip['driver'];?></td>
					<td><?php echo $trip['City_departure']." ".$trip['Postcode_departure']?></td>
					<td><?php echo $trip['City_destination']." ".$trip['Postcode_destination']?></td>
					<td><button type="button" onclick="location.replace('detailTrip.php?id=<?php echo $trip['ID'] ?>');">detail</button></td>
				<tr>
			<?php } ?>
		</table>	
	</div>
<?php endif; ?>

</body>
</html>