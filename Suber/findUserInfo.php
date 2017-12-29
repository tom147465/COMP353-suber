<?php
session_start();
require_once("parameter.php");
if(isset($_POST['driver']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT userInfo FROM member where username='".$_POST['driver']."'");
	$json_content = $rs->fetch();
	if(empty($json_content['userInfo']))
	{
		echo "<h2>".$_POST['driver']."</h2><p style='color:red' >no more information!</p>";
		exit;
	}
	else{
		//$content = json_decode($json_content['userInfo'],true);
		//var_dump($content);
		echo $json_content['userInfo'];
		//$result="<h2>".$_POST['driver']."</h2><p>";
		//foreach ($content as $value)
		//{
		//	$eachinfo = explode("|", $value);
		//	$result = $result.$eachinfo[0].":".$eachinfo[1]."<br>";
		//}
		//$result=$result."<p>";
		//echo $result;
	}
}
?>
