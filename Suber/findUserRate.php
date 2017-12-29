<?php 
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user']))
{
	header('location:login.php');
	exit;
}
elseif(isset($_POST['username']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs1 = $pdo -> query("select count(*) VAL from trip where trip.driver = '".$_POST['username']."';");
	if($rs1 ->rowCount()==0)
	{
		$count_trip['VAL'] = 0;
	}
	else{
		$count_trip = $rs1->fetch();
	}
	
	$rs2 = $pdo -> query("select count(*) VAL from specialoffer where driver = '".$_POST['username']."';");
	if($rs2 ->rowCount()==0)
	{
		$count_offer['VAL'] = 0;
	}
	else{
		$count_offer = $rs2->fetch();
	}
	
	$rs = $pdo -> query("SELECT AVG(a.rate) RAT FROM 
	(SELECT rate from trip,book WHERE trip.driver = '".$_POST['username']."' AND trip.ID = book.tid 
	UNION ALL 
	SELECT rate FROM specialoffer WHERE specialoffer.driver = '".$_POST['username']."') a ;");
	
	if($rs ->rowCount()==0)
	{
		$rate['RAT'] = 0;
	}
	else{
		$rate = $rs->fetch();;
	}
}
?>
<p> post <b><?= $count_trip['VAL'] ?></b> trips<br>
	supply <b><?= $count_offer['VAL'] ?></b> Special offers<br>
	rate: <b><?= round($rate['RAT'], 1); ?></b></p>