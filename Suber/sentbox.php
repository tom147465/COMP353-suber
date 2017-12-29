<?php
session_start();
require_once("parameter.php");
$bodyMsg="";
if(isset($_SESSION['user']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM message WHERE m_from ='".$_SESSION['user']['username']."' ORDER BY senddate desc");
	if($rs->rowCount()!=0)
	{
		$content = $rs->fetchAll();
		foreach($content as $msg)
		{
			$bodyMsg = $bodyMsg."<p> SentTO: <b>".$msg['m_to']."</b>  --- ".$msg['senddate']."<br><b>".$msg['content']."</b></p>";
		}
	}
	else
	{
		$bodyMsg = "<h3 style='color:red'> NO Message </h3>";
	}
}
?>

<html>
<head>
<title>Mail Box</title>
<link rel="stylesheet" type="text/css" href="css/suber.css">
</head>
<body>
<h2><a href="message.php">Inbox</a>&nbsp <a href="sentbox.php">Sent Message</a>&nbsp <a href="newmessage.php">New Message</a>&nbsp <br>
<h2>Sent Box</h2>

<div id="sentbox">
<?= $bodyMsg?>
</div>

</body>
</html>