<?php
session_start();
require_once("parameter.php");
$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
$pdo->exec("SET NAMES 'utf8';");
$content="";
if(isset($_GET['id']))
{
	$rs = $pdo -> query("SELECT * FROM publicitem WHERE ID='".$_GET['id']."'");
	if($rs->rowCount()!=0)
	{
		$content = $rs->fetch();
	}
}
elseif(isset($_GET['delete'])){

	$prep = $pdo -> prepare("DELETE FROM publicitem WHERE ID = :id");
	$prep -> bindParam(':id', $_GET['delete'] , PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs)
	{
		echo "<script type='text/javascript'>alert('Deleted successfully'); window.close();</script>";
	}
}
else
{
	header("location:index.php");
}
?>
<html>
<head>
<title>Detail Public Item</title>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<head>
<script>

window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }

</script>
<body>
<br>
<h2><?= $content['title']; ?></h2>
<i> - <?= $content['date']; ?></i>
<p> <?= $content['content']; ?></p>

<?php if(isset($_SESSION['user']) && $_SESSION['user']['privilege']=='admin'): ?>
	<button id="delete" onclick="location.replace('detailPublicItem.php?delete=<?= $_GET['id']; ?>')"> delete it</button>
<?php endif; ?>
</body>
<html>