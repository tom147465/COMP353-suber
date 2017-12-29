<?php
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user']))
{
	header("location:index.php");
}
elseif(isset($_POST['title'])&&isset($_POST['content']))
{
	$date = date("Y-m-d");
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$prep = $pdo -> prepare("INSERT INTO publicitem(date,title,content) VALUE('".$date."','".$_POST['title']."','".$_POST['content']."')");
	$rs = $prep -> execute();
	if($rs)
	{
		echo "<script type='text/javascript'>alert('Sent Message successfully'); window.close();</script>";
		exit;
	}
	
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<title>Mail Box</title>
<script>
window.onunload = refreshParent;
function refreshParent() {
	window.opener.location.reload();
}
</script>
</head>
<body>

<h2>New Public Item</h2>

<form method="post">
	<div id="dateOrdays" >
		<br><label for="title" >title: </label>
		<input type="text" name="title" placeholder="title"  required>
	</div><br>
	<div>
		<label for="content">content:</label><br/>
		<textarea name="content" cols="50" rows="10" style="resize:none" required></textarea>
	</div>
	<input type="submit" value="Submit" >
</form>

</body>
</html>