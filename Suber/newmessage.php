<?php
session_start();
require_once("parameter.php");
$info="";
date_default_timezone_set('Etc/GMT+5');
if(isset($_POST['to_who'])&&isset($_POST['content']))
{
	if($_POST['to_who']==$_SESSION['user']['username'])
	{
		$info="Cannot send message to yourself";
	}
	else
	{	
		$senddate = date("Y-m-d H:i:s");
		$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
		$pdo->exec("SET NAMES 'utf8';");
		$rsname = $pdo -> query("SELECT * FROM member WHERE username='".$_POST['to_who']."'");
		if($rsname->rowCount() == 0)
		{
			$info="Nobody here with that name";
		}
		else
		{
			$prep = $pdo -> prepare("INSERT INTO message(content,m_from,m_to,senddate) VALUE('".$_POST['content']."','".$_SESSION['user']['username']."','".$_POST['to_who']."','".$senddate."')");
			$rs = $prep -> execute();
			if($rs)
			{
				echo "<script type='text/javascript'>alert('Sent Message successfully'); window.close();</script>";
				exit;
			}
		}
	}
}

?>
<html>
<head>
<title>New Message</title>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<head>
<body>
<h2><a href="message.php">Inbox</a>&nbsp <a href="sentbox.php">Sent Message</a>&nbsp <a href="newmessage.php">New Message</a>&nbsp <br>
<h2>New message</h2>
<?="<p style='color:red'>".$info."</p>"?>
<form name="newmessaage" id="newmessaage" method="post" >
	<label>To:<label>&nbsp
	<?php if(isset($_GET['m_to'])): ?>
		<input type="text" name="to_who" id="to_who" value="<?=$_GET['m_to']?>" readonly="readonly" required><br>
	<?php else: ?>
		<input type="text" name="to_who" id="to_who" value=""  required ><br>
	<?php endif; ?>
	<br>
	<textarea rows="10" cols="50" name="content" id="content" form="newmessaage" placeholder="Enter content Here.." style="resize:none" required></textarea>
	<br><br>
	<input type="submit" value="Send" >
</form>

</body>
<html>