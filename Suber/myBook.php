<?php

session_start();
require_once("parameter.php");
if(!isset($_SESSION['user'])&&$_SESSION['user']['status']=='inactive')
{
	header('location:login.php');
	exit;
}
elseif(isset($_POST['rate'])&&isset($_POST['tid']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$prep = $pdo -> prepare("UPDATE book set rate= :rate where tid= :tid AND username= :username");
	$prep -> bindParam(':rate', $_POST['rate'], PDO::PARAM_STR);
	$prep -> bindParam(':tid', $_POST['tid'], PDO::PARAM_STR);
	$prep -> bindParam(':username', $_SESSION['user']['username'], PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs){
	
		echo "submit successfully";
		exit;
	}
}
elseif(isset($_POST['Sporate'])&&isset($_POST['Spoid']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$prep = $pdo -> prepare("UPDATE specialoffer set rate= :Sporate where ID= :Spoid ");
	$prep -> bindParam(':Sporate', $_POST['Sporate'], PDO::PARAM_STR);
	$prep -> bindParam(':Spoid', $_POST['Spoid'], PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs){
	
		echo "submit successfully";
		exit;
	}
}
else
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM trip,book WHERE trip.ID = book.tid AND username='".$_SESSION['user']['username']."' ORDER BY ID desc");
	$content = $rs->fetchAll();
	$rs1 = $pdo -> query("SELECT * FROM specialoffer WHERE customer='".$_SESSION['user']['username']."' ORDER BY ID desc");
	$content_offer = $rs1->fetchAll();
	
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<title>My Book</title>
<script>
$(document).ready(function()
{
	$("#rating").click(function(){
		var tid = $(this).val();
		alert(tid);
		$( "#dialog-form" ).dialog({
			height: 250,
			width: 350,
			modal: true,
			buttons: {
				"Confirm": function() {
					$.post("mybook.php",{rate:$('#rate').val(), tid:tid},
					function(result){
						alert(result);
						location.replace("home.php");
					});
					$( this ).dialog( "close" );
				},
				Cancel: function() {
					dialog.dialog( "close" );
				}
			}
		});
	});
	
	$("#rating_offer").click(function(){
		var id = $(this).val();
		alert(id);
		$( "#dialog-form" ).dialog({
			height: 250,
			width: 350,
			modal: true,
			buttons: {
				"Confirm": function() {
					$.post("mybook.php",{Sporate:$('#rate').val(), Spoid:id},
					function(result){
						alert(result);
						location.replace("home.php");
					});
					$( this ).dialog( "close" );
				},
				Cancel: function() {
					dialog.dialog( "close" );
				}
			}
		});
	});
});
</script>
</head>
<body>

<?php 
require_once("header.php"); 
?> 

<h1>My Book</h1>
<?php if($_SESSION['user']['status']!='inactive'): ?>
	<div id="trip">
		<b>trip : </b><br><br>
		<table style="width:60%">
			<tr><th>trip date</th> <th>depart time</th> <th>driver</th> <th>Departure</th> <th>Destination</th> <th>price</th> <th>Rating</th> <th>GoDetial</th></tr>
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
					<td><?php echo $trip['price'] ?></td>
					<td><button type="button" id="rating" value="<?=$trip['ID']?>" >Rating</button></td>
					<td><button type="button" onclick="location.replace('detailTrip.php?id=<?php echo $trip['ID'] ?>');">detail</button></td>
				<tr>
			<?php } ?>
		</table>	
	</div>
	
	<div id="Spoffer">
		<b>special offer : </b><br><br>
		<table style="width:60%">
			<tr><th>date</th> <th>depart time</th> <th>driver</th> <th>Departure</th> <th>Destination</th> <th>price</th> <th>Rating</th> <th>GoDetial</th></tr>
			<?php foreach($content_offer as $offer) { ?>
				<tr align="center">
					<?php if(!empty($offer['Date'])): ?>
						<td><?php echo $offer['Date']?></td>
					<?php else: ?>
						<td> <?php echo $offer['Day']?></td>
					<?php endif; ?>
					<td> <?php echo $offer['depart_time']?></td>
					<td><?php echo $offer['driver'];?></td>
					<td><?php echo $offer['City_departure']." ".$offer['Postcode_departure']?></td>
					<td><?php echo $offer['City_destination']." ".$offer['Postcode_destination']?></td>
					<td><?php echo $offer['price'] ?></td>
					<td><button type="button" id="rating_offer" value="<?=$offer['ID']?>" >Rating</button></td>
					<td><button type="button" onclick="location.replace('detailSpOffer.php?id=<?php echo $offer['ID'] ?>');">detail</button></td>
				<tr>
			<?php } ?>
		</table>	
	</div>
<?php endif; ?>

<div id="dialog-form" title="Create new user" style="display:none;" >
	<form>
		  <div>
			select rate for the service(3 as default):<br><br>
			<input type="radio" name="type" id="rate" value="1" /> 1 (Bad) &nbsp
			<input type="radio" name="type" id="rate" value="2" /> 2 (So-so)&nbsp
			<input type="radio" name="type" id="rate" value="3" checked /> 3 (Good)&nbsp
		</div>
	</form>
</div>


</body>
</html>