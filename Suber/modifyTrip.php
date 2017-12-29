<?php
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user']))
{
	header('location:index.php');
	exit;
}
elseif(isset($_GET['id']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM trip WHERE id='".$_GET['id']."'");
	if($rs->rowCount() == 0)
	{
		header('location:home.php');
	}
	else
	{
		$content = $rs->fetch();
	}
}
if(isset($_POST['type'])&&isset($_POST['city_depart'])&&isset($_POST['postcode_depart'])
	&&isset($_POST['city_destin'])&&isset($_POST['postcode_destin'])&&isset($_POST['number_rider'])
	&&isset($_POST['time'])&&isset($_POST['dateOftrip'])&&isset($_POST['detail']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	if($_POST['type']=='oneTime')
	{	
		$prep = $pdo -> prepare("UPDATE trip SET type= :type, City_departure= :city_depart, Postcode_departure= :postcode_depart, 
			City_destination= :city_destin, Postcode_destination= :postcode_destin, Number_rider= :number_rider,
			Date= :dateOftrip, depart_time= :time, detail= :detail WHERE ID= :id");
	}
	elseif($_POST['type']=='Reguler')
	{
		$prep = $pdo -> prepare("UPDATE trip SET type= :type, City_departure= :city_depart, Postcode_departure= :postcode_depart, 
			City_destination= :city_destin, Postcode_destination= :postcode_destin, Number_rider= :number_rider,
			Day = :dateOftrip, depart_time= :time, detail= :detail WHERE ID= :id");
	}
	$prep -> bindParam(':type', $_POST['type'], PDO::PARAM_STR);
	$prep -> bindParam(':city_depart', $_POST['city_depart'], PDO::PARAM_STR);
	$prep -> bindParam(':postcode_depart', $_POST['postcode_depart'], PDO::PARAM_STR);
	$prep -> bindParam(':city_destin', $_POST['city_destin'], PDO::PARAM_STR);
	$prep -> bindParam(':postcode_destin', $_POST['postcode_destin'], PDO::PARAM_STR);
	$prep -> bindParam(':number_rider', $_POST['number_rider'], PDO::PARAM_STR);
	$prep -> bindParam(':dateOftrip', $_POST['dateOftrip'], PDO::PARAM_STR);
	$prep -> bindParam(':time', $_POST['time'], PDO::PARAM_STR);
	$prep -> bindParam(':detail', $_POST['detail'], PDO::PARAM_STR);
	$prep -> bindParam(':id', $_GET['id'], PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs){
	
		echo "<script type='text/javascript'>alert('Modify successfully');location.replace('home.php')</script>";
		exit;
	}
}
?>
<!DOCTYPE HTML>
<html>

<head>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>
<title>Post new Trip</title>
<script>
$(document).ready(function()  
{
	if('<?= $content['type'] ?>' == 'oneTime')
	{
		$("#oneTime").prop('selected', 'selected');
	}
	else if('<?= $content['type'] ?>' == 'Reguler')
	{
		$("#Reguler").prop('selected', 'selected');
	}
	$("#type").change(function ()  
	{
		if($("#type").val()=='oneTime')
		{
			$("#dateOrdays").empty();
			$("#dateOrdays").append('<br><label for="dateOftrip" >date:</label><input type="date" name="dateOftrip" min="<?=date("Y-m-d")?>" max="2020-12-31" value="<?=date("Y-m-d")?>">');
		}
		else if($("#type").val()=='Reguler')
		{
			$("#dateOrdays").empty();
			$("#dateOrdays").append('<br><label for="dateOftrip" >date: regular on every </label><select type="date" name="dateOftrip" >'+
			'<option value="Monday" selected>Monday</option>'+
			'<option value="Tuesday">Tuesday</option>'+
			'<option value="Wednesday" >Wednesday</option>'+
			'<option value="Thursday">Thursday</option>'+
			'<option value="Firday" >Firday</option>'+
			'<option value="Saturday">Saturday</option>'+
			'<option value="Sunday">Sunday</option>'+
			'</select>');
		}
	});
	
	$("form :input, textarea").change(function ()  
	{
		$('form').data('changed',true);
	});
	  
	$("form").submit(function (){
		if(!$('form').data('changed'))  
		{  
			alert('please change one detail at least.!!!');  
			return false;  
		}
		var city = /^([A-Za-z]+\s?)*[A-Za-z]$/;
		var post = /^[A-Za-z0-9 ]+$/;
		var number = /^[0-9]*[1-9][0-9]*$/
		if(!city.test($("#city_depart").val())||!city.test($("#city_destin").val())){
			alert('city should only include letters and space!');  
			return false;
		}
		if(!post.test($("#postcode_depart").val())||!post.test($("#postcode_destin").val())){
			alert('postcode should only include letters, numbers and space!');  
			return false;
		}
		
	});
});
</script>
</head>
	
<body>
<a href="index.php">MainPage</a>&nbsp;<a href="profile.php">profile</a>&nbsp;<a href="home.php">Home</a> &nbsp; <a href="logout.php">Logout</a><br/>
	<div>
	
		<h1>Modify the Trip</h1>
	
		<form method="POST">
			<div>
				select your trip type:
				<select name="type" id="type" >
					<option id="oneTime" value="oneTime" >OneTime</option>
					<option id="Reguler" value="Reguler" >Reguler</option>
				</select>
			</div>
			<div>
				<label>Departure:</label><br/>
				<input type="text" name="city_depart" id="city_depart" placeholder="City of departure" value="<?= $content['City_departure'] ?>" required/>
				<input type="text" name="postcode_depart" id="postcode_depart" placeholder="postal code" value="<?= $content['Postcode_departure'] ?>" required/>
			</div>
			<div>
				<br><label>Destination:</label><br/>
				<input type="text" name="city_destin" id="city_destin" placeholder="City of destination" value="<?= $content['City_destination'] ?>" required/>
				<input type="text" name="postcode_destin" id="postcode_destin" placeholder="postalcode" value="<?= $content['Postcode_destination'] ?>" required/>
			</div>
			
			<?php if($content['type']=='oneTime'||$content['type']=='Reguler'): ?>
				<div>
					<br><label for="number_rider" >Number of Riders:</label>
					<input type="number" name="number_rider" min=1 value="<?= $content['Number_rider'] ?>" required />
				</div>
			<?php endif; ?>
			
			<div id="dateOrdays" >
			<?php if($content['type']=='oneTime'): ?>
				<br><label for="dateOftrip" >date: </label>
				<input type="date" name="dateOftrip" min="<?=date("Y-m-d")?>" max="2020-12-31" value="<?=$content['Date'] ?>" required />
			<?php elseif($content['type']=='Reguler'): ?>
				<br><label for="dateOftrip" >date: regular on every </label>
				<select type="date" name="dateOftrip" value="<?= $content['Day'] ?>" >
					<option value="Monday" selected>Monday</option>
					<option value="Tuesday">Tuesday</option>
					<option value="Wednesday" >Wednesday</option>
					<option value="Thursday">Thursday</option>
					<option value="Firday" >Firday</option>
					<option value="Saturday">Saturday</option>
					<option value="Sunday">Sunday</option>
				</select>
			<?php endif; ?>
			</div>
			
			<div>
				<br><label for="time" >time: </label>
				<input type="time" id="time" name="time" value="<?=  $content['depart_time']; ?>" required>
			</div>
			
			<div>
				<br><label for="detail">more detail:</label><br/>
				<textarea name="detail" cols="50" rows="10" required><?= $content['detail'] ?></textarea>
			</div>
			<div>
				<input type="submit" value="modify" />
				<input type="button" value="cancel" onclick="location.href='home.php'" />
			</div>
		</form>
		
	</div>
</body>
	
</html>