<?php
session_start();
require_once("parameter.php");
$info="";
if(isset($_SESSION['user']))
{
	header('location:home.php');
	exit;
}
if(isset($_POST['username'])&&isset($_POST['password']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM member WHERE username='".$_POST['username']."' AND password='".md5($_POST['password'])."'");
	$content= $rs->fetch();
	if($rs->rowCount()==0)
	{
		$info="Username or password is wrong!";
	}
	else if($content['status']=='suspended'){
		$info="your account is already suspended";
	}
	else
	{
		$_SESSION['user']=$content;
		header("location:home.php");
		exit;
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Suber - Login</title>
<link rel="stylesheet" type="text/css" href="css/suber.css">
</head>
<body>
<div class="login">
	<h1>Suber</h1>
	<div class="login-top">
		<h1>LOGIN FORM</h1>
		<h3><a href="index.php">Mainpage</a></h3>
		<form method="post">
			Username:<input type="text" name="username" id="username" placeholder="Username" onfocus="this.value = '';">
			<br><br>
			
			Password:<input type="password" name="password" id="password" placeholder="Password" onfocus="this.value = '';">
		<?="<p style='color:red'>".$info."</p>"?>
		<input type="submit" value="Login" >
		</form>
	</div>
	<div class="login-bottom">
		<h3>New User?? >> &nbsp;<a href="register.php">Register</a>&nbsp </h3>
	</div>
</div>	
</body>
</html>