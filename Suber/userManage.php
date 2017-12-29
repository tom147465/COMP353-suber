<?php
$info = "";
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user']))
{
	header('location:login.php');
	exit;
}
else if($_SESSION['user']['privilege']!='admin')
{
	header('location:home.php');
	exit;
}
if(isset($_POST['Memberusername'])&&isset($_POST['privilege'])&&isset($_POST['status']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$prep = $pdo -> prepare("UPDATE member SET privilege= :privilege, status= :status
			WHERE username= :username");
	$prep -> bindParam(':username', $_POST['Memberusername'], PDO::PARAM_STR);
	$prep -> bindParam(':privilege', $_POST['privilege'], PDO::PARAM_STR);
	$prep -> bindParam(':status', $_POST['status'], PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs)
	{
		if($_SESSION['user']['username']==$_POST['Memberusername'])
		{
			$_SESSION['user']['privilege'] = $_POST['privilege'];
			$_SESSION['user']['status'] = $_POST['status'];
		}
		echo "<script type='text/javascript'>alert('changed successfully');location.replace('home.php')</script>";
	}
}
if(isset($_GET['delete']))
{
	if($_SESSION['user']['username']==$_GET['delete'])
	{
		echo "<script type='text/javascript'>alert('you cannot delete yourself!!!');location.replace('userManage.php')</script>";
		exit;
	}
	else{
		$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
		$pdo->exec("SET NAMES 'utf8';");
		$prep = $pdo -> prepare("DELETE FROM member WHERE username = :username");
		$prep -> bindParam(':username', $_GET['delete'] , PDO::PARAM_STR);
		$rs = $prep -> execute();
		if($rs)
		{
			echo "<script type='text/javascript'>alert('Deleted successfully');location.replace('userManage.php')</script>";
		}
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>
<script>
$(document).ready(function(){
	$("#searchMember").click(function(){
		$.post("findUser.php",
				{username: $("#username").val()},
				function(data){
			$(".User_Status").empty();
			$(".User_Status").append(data);
		});
	});
});
</script>
<title>Suber - UserManage</title>
</head>
<body>
<div class="UserManage">
	<?php require_once("header.php"); ?>
	<h1>Manage members</h1>
	<div>
		<br><h3>Input the username whose status you want to change !!<br>  <a href="home.php">cancel</a></h3>
		<label>Username:</label>
		<input type="text" name="username" id="username" placeholder="Username" required ><br>
		<?="<center>".$info."</center>"?>
		<button type="button" id="searchMember" >GO</button>
	</div>
	<div class="User_Status">
		
	</div>
</div>	
</body>
</html>