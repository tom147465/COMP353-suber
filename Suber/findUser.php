<?php
session_start();
require_once("parameter.php");
$info ="";
$userinfo="";
if(isset($_POST['username']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM member WHERE username='".$_POST['username']."'");
	if($rs->rowCount()==0)
	{
		$info="<font color='red'>user not exist!!</font>";
		echo $info;
	}
	else
	{
		$userinfo = $rs -> fetch();
		$status_member="";
		$status_admin="";
		$status_suspended="";
		$status_active="";
		$status_inactive="";
		if($userinfo['privilege']=='member'){$status_member='selected';}
		else if($userinfo['privilege']=='admin'){$status_admin='selected';}
		if($userinfo['status']=='suspended'){$status_suspended='checked';}
		else if($userinfo['status']=='active'){$status_active="checked";}
		else{$status_inactive="checked";}
		$content = '<form method="post"><label>username:</label><input type="text"  id="Memberusername" name="Memberusername" value="'. $_POST["username"].'" readonly="readonly"/>'.
					'<label>privilege:</label>'.
					'<select name="privilege" id="privilege">
						<option value="member"'. $status_member.'> member</option>
						<option value="admin"' .$status_admin .'> admin</option></select>
					<input type="radio" name="status" id="status" value="suspended"'.$status_suspended.'> suspended
					<input type="radio" name="status" id="status" value="active"'.$status_active.'> active
					<input type="radio" name="status" id="status" value="inactive"'.$status_inactive.'> inactive
					<input type="submit" value="submit" >
					<input type="button" id="deleteMember" value="delete" onclick="if(confirm(\'Are you sure you want to delete user:'.$_POST['username'].'?\')== true)
						{ location.href=\'userManage.php?delete='.$_POST['username'].'\';}" >
					</form>';
		echo $content;
	}
}
?>