<?php
session_start();
require_once("parameter.php");
$info="";
$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
$pdo->exec("SET NAMES 'utf8';");
$rs = $pdo -> query("SELECT * FROM publicitem ORDER BY date desc");
$content = $rs->fetchAll();
?>

<html>
<head>

<title>Welcome Suber</title>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>


</head>

<script>
$(document).ready(function()
{
	$("#publicitem a").click(function(){
		var id = $(this).attr("value");
		window.open("detailPublicItem.php?id="+id, "", "status=no,location=0,menubar=no,toolbar=no,height=450,width=600,top=100,left=380");
	});
	$("#header button").click(function(){
		
	});
});
</script>


<body>


<div id="header" >
	<h1>Welcome to suber</h1>
	
	<?php if(isset($_SESSION['user'])): ?>
		<a href="logout.php">Logout</a>&nbsp
		<a href="home.php">home</a><br/>
	<?php else: ?>
		<a href="register.php">Register</a>&nbsp
		<a href="login.php">Login</a><br/>
	<?php endif; ?>
	
	<?php if(isset($_SESSION['user'])&&$_SESSION['user']['privilege']=='admin'): ?>
		<br>
		<button onclick='window.open("newPublicItem.php","","status=no,location=0,menubar=no,toolbar=no,height=450,width=600,top=100,left=380");'>
		New public Item 
		</button>
		
	<?php endif; ?>
	
</div>

<div id="publicitem">
	<?php foreach($content as $item) { ?>
		<h2><a href="javascript:;" value="<?php echo $item['ID'] ?>" ><?php echo $item['title']; ?></a></h2>
		<i> - <?php echo $item['date']; ?></i>
		<p><?php echo $item['content']; ?></p>
	<?php } ?>
</div>

</body>

</html>