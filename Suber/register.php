<?php
session_start();
require_once("parameter.php");
$info="";
$email="";

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['confirm'])&&isset($_POST['email']))
{
	$email = test_input($_POST['email']);
	$username = test_input($_POST['username']);
	if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
	  $info = "invalid email format!!!"; 
	}
	elseif (!preg_match("/^[\w+]{6,12}$/",$username)) {
	  $info = "the username only can include number, letter and '_ ', its size must be between 6 and 12!!"; 
	}
	elseif(strcmp($_POST['password'],$_POST['confirm'])){
		$info="two passwords are different!";
	}
	else{
		$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
		$pdo->exec("SET NAMES 'utf8';");
		$rs = $pdo -> query("SELECT * FROM member WHERE username='".$_POST['username']."'");
		$rs1 = $pdo -> query("SELECT * FROM member WHERE email='".$_POST['email']."'");
		if($rs->rowCount()!=0)
		{
			$info="username has been used!";
		}
		else if($rs1->rowCount()!=0){
			$info="Email has been used!";
		}
		else
		{
			$pdo -> exec("INSERT INTO member(username,password,email) VALUES('".$_POST['username']."','".md5($_POST['password'])."','".$_POST['email']."')");
			echo "<script type='text/javascript'>alert('Register finished');location.replace('login.php')</script>";
			exit;
		}
	}
}
?>



<html>
<head>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<title>Suber - Register</title>
</head>
<body>
<div class="login">

	<h1>Suber</h1>
	<div class="login-top">
		<h1>REGISTRATION FORM</h1>
		<form method="post">
			Username: <input type="text" name="username" id="username" value="" onfocus="this.value = '';" required>
			<br><br>
			
			Password: <input type="password" name="password" id="password" value="" onfocus="this.value = '';" required>
			<br><br>
			confirm: <input type="password" name="confirm" id="confirm" value="" onfocus="this.value = '';" required>
			<br><br>
			Email: <input type="text" name="email" id="email" value="" onfocus="this.value = '';" required>
			<?="<p style='color:red'>".$info."</p>"?>
	    	<input type="submit" value="Submit" >
			
		</form>
	</div>
	
	<div class="login-bottom">
		<h3>Already have an account? >> &nbsp;<a href="login.php">Login</a>&nbsp </h3>
	</div>
</div>	

</body>
</html>