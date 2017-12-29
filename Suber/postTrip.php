<?php

session_start();
date_default_timezone_set('Etc/GMT+5');
require_once("parameter.php");
$info="";
$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
$pdo->exec("SET NAMES 'utf8';");
if(!isset($_SESSION['user']) && empty($_SESSION['user'])) header("location:login.php");
if(isset($_POST['type'])&&isset($_POST['city_depart'])&&isset($_POST['postcode_depart'])
	&&isset($_POST['city_destin'])&&isset($_POST['postcode_destin'])&&isset($_POST['number_rider'])
	&&isset($_POST['dateOftrip'])&&isset($_POST['detail']))
{
	if($_POST['type']=='oneTime')
	{
		$prep = "INSERT INTO trip( type,City_departure,Postcode_departure,City_destination,Postcode_destination,Number_rider,Date,depart_time,detail,driver) 
		VALUES('".$_POST['type']."','".$_POST['city_depart']."','".$_POST['postcode_depart']."','".$_POST['city_destin']."','".
		$_POST['postcode_destin']."','".$_POST['number_rider']."','".$_POST['dateOftrip']."',
		'".$_POST['time']."','".$_POST['detail']."','".$_SESSION['user']['username']."')";
		$rs = $pdo -> exec($prep);
		if($rs==0)
		{
			echo "Error updating record:";
		}else {
			echo "<script type='text/javascript'>alert('Succesfully posted!');location.replace('home.php')</script>";
		}
	}
	elseif($_POST['type'] =='Regular')
	{
		$prep = "INSERT INTO trip(type,City_departure,Postcode_departure,City_destination,Postcode_destination,Number_rider,Day,detail,driver) 
		VALUES('".$_POST['type']."','".$_POST['city_depart']."','".$_POST['postcode_depart']."','".$_POST['city_destin']."','".
		$_POST['postcode_destin']."','".$_POST['number_rider']."','".$_POST['dateOftrip']."'
		,'".$_POST['detail']."','".$_SESSION['user']['username']."')";
		$rs = $pdo -> exec($prep);
		if($rs==0)
		{
			echo "Error updating record:";
		}else {
			echo "<script type='text/javascript'>alert('post successfully');location.replace('home.php')</script>";
		}
	}
}
// if(isset($_POST['title']) && isset($_POST['body'])) {
	// $postManager = new PdoPostManager(); //SimplePostManager;;
	// $postManager->addPost($_POST['title'], $_POST['body'], $_SESSION['user']);
	// header('Location: index.php');
// }

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
	
	$("form").submit(function (){
		
		var city = /^([A-Za-z]+\s?)*[A-Za-z]$/;
		var post = /^[A-Za-z0-9 ]+$/;
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
<?php require_once("header.php");  ?>
	<div>
	
		<h1>Post a new Trip</h1>
	
		<form method="POST">
			<div>
				select your trip type:
				<select name="type" id="type" >
					<option value="oneTime" selected>OneTime</option>
					<option value="Reguler">Reguler</option>
				</select>
			</div>
			<div>
				<br><label>Departure:</label><br/>
				<input type="text" name="city_depart" id="city_depart" placeholder="City of departure" required/>
				<input type="text" name="postcode_depart" id="postcode_depart" placeholder="postal code" required/>
			</div>
			<div>
				<br><label>Destination:</label><br/>
				<input type="text" name="city_destin" id="city_destin" placeholder="City of destination" required/>
				<input type="text" name="postcode_destin" id="postcode_destin" placeholder="postalcode" required/>
			</div>
			
			<div>
				<br><label for="number_rider" >Number of Riders:</label>
				<input type="number" name="number_rider" />
			</div>
			<div id="dateOrdays" >
				<br><label for="dateOftrip" >date: </label>
				<input type="date" name="dateOftrip" min="<?=date("Y-m-d")?>" max="2020-12-31" value="<?=date("Y-m-d")?>" required>
				
			</div>
			<div>
				<br><label for="time" >time: </label>
				<input type="time" id="time" name="time" value="00:00" required>
			</div>
			<div>
				<br><label for="detail">more detail:</label><br/>
				<textarea name="detail" cols="50" rows="10" required></textarea>
			</div>
			<div>
				<input type="submit" value="Submit" />
			</div>
		</form>
		
	</div>
</body>
	
</html>
