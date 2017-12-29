<?php
session_start();
require_once("parameter.php");
$info="";

if(!isset($_SESSION['user']))
{
	header('location:home.php');
	exit;
}

function check_act_status()
{
	if(!empty($_SESSION['user']['policies'])&&!empty($_SESSION['user']['DOB'])&&!empty($_SESSION['user']['driver_license'])
		&&!empty($_SESSION['user']['address'])&&$_SESSION['user']['balance']!=0)
		{return true;}
}
$userInfoarr= array();
if(!empty($_POST['email_checkbox'])&&!empty($_POST['email'])){$userInfoarr["email"]=$_POST['email'];}
if(!empty($_POST['policies_checkbox'])&&!empty($_POST['policies'])){$userInfoarr["policies"]=$_POST['policies'];}
if(!empty($_POST['DOB_checkbox'])&&!empty($_POST['DOB'])){$userInfoarr["DOB"]=$_POST['DOB'];}
if(!empty($_POST['driver_license_checkbox'])&&!empty($_POST['driver_license'])){$userInfoarr["driver_license"]=$_POST['driver_license'];}
if(!empty($_POST['address_checkbox'])&&!empty($_POST['address'])){$userInfoarr["address"]=$_POST['address'];}


if(isset($_POST['balance']))
{
	$balance = $_POST['balance'] + $_SESSION['user']['balance'];
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$prep = $pdo -> prepare("UPDATE member SET balance= :balance WHERE username= :username");
	$prep -> bindParam(':username', $_SESSION['user']['username'], PDO::PARAM_STR);
	$prep -> bindParam(':balance', $balance, PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs){
		$_SESSION['user']['balance']=$balance;
		echo "recharged successfully";
		exit;
	}
}

if(isset($_POST['email'])&&$_POST['email']!=$_SESSION['user']['email'])
{
	$userInfo=json_encode($userInfoarr);
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	
	$rs = $pdo -> query("SELECT email FROM member WHERE username<>'".$_SESSION['user']['username']."' AND email='".$_POST['email']."'");
	if($rs->rowCount()!=0)
	{
		$info="Email has been Used!";
	}
	else
	{
		$prep = $pdo -> prepare("UPDATE member SET email= :email, policies= :policies, DOB= :DOB, driver_license= :driver_license, address= :address, userInfo= :userInfo
								WHERE username= :username");
		$prep -> bindParam(':username', $_SESSION['user']['username'], PDO::PARAM_STR);
		$prep -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
		$prep -> bindParam(':policies', $_POST['policies'], PDO::PARAM_STR);
		$prep -> bindParam(':DOB', $_POST['DOB'], PDO::PARAM_STR);
		$prep -> bindParam(':driver_license', $_POST['driver_license'], PDO::PARAM_STR);
		$prep -> bindParam(':address', $_POST['address'], PDO::PARAM_STR);
		$prep -> bindParam(':userInfo', $userInfo, PDO::PARAM_STR);
		$rs = $prep -> execute();
		if($rs){
			$_SESSION['user']['email']=$_POST['email'];
			$_SESSION['user']['policies']=$_POST['policies'];
			$_SESSION['user']['DOB']=$_POST['DOB'];
			$_SESSION['user']['driver_license']=$_POST['driver_license'];
			$_SESSION['user']['address']=$_POST['address'];
			$_SESSION['user']['userInfo']=$userInfo;
			echo "<script type='text/javascript'>alert('changed successfully');location.replace('home.php')</script>";
			exit;
		}
	}	
}
else if(isset($_POST['email'])&&$_POST['email']==$_SESSION['user']['email'])
{
	$userInfo=json_encode($userInfoarr);
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$prep = $pdo -> prepare("UPDATE member SET policies= :policies, DOB= :DOB, driver_license= :driver_license, address= :address, userInfo= :userInfo
			WHERE username= :username");
	$prep -> bindParam(':username', $_SESSION['user']['username'], PDO::PARAM_STR);
	$prep -> bindParam(':policies', $_POST['policies'], PDO::PARAM_STR);
	$prep -> bindParam(':DOB', $_POST['DOB'], PDO::PARAM_STR);
	$prep -> bindParam(':driver_license', $_POST['driver_license'], PDO::PARAM_STR);
	$prep -> bindParam(':address', $_POST['address'], PDO::PARAM_STR);
	$prep -> bindParam(':userInfo', $userInfo, PDO::PARAM_STR);
	$rs = $prep -> execute();
	if($rs){
		$_SESSION['user']['policies']=$_POST['policies'];
		$_SESSION['user']['DOB']=$_POST['DOB'];
		$_SESSION['user']['driver_license']=$_POST['driver_license'];
		$_SESSION['user']['address']=$_POST['address'];
		$_SESSION['user']['userInfo']=$userInfo;
		if($_SESSION['user']['status']=='inactive')
		{
			if(check_act_status())
			{
				$rs = $pdo -> query("UPDATE member SET status = 'active' WHERE username = '".$_SESSION['user']['username']."'");
				if($rs)
				{
					$_SESSION['user']['status']='active';
					echo "<script type='text/javascript'>alert('Your account has been actived!!');</script>";
				}
			}
		}
		echo "<script type='text/javascript'>alert('changed successfully');location.replace('home.php')</script>";
		exit;
	}
}
?>

<html>
<head>

<link rel="stylesheet" type="text/css" href="css/suber.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<title>Suber - Login</title>

<script>
$(document).ready(function()  
    {
		
		if('<?=$_SESSION['user']['status']?>'=='active')
		{
			$("form :input:not(:checkbox), textarea").prop('required',true);
		};
		
		var userInfo = '<?=$_SESSION['user']['userInfo']?>';
		var jsonarr = jQuery.parseJSON(userInfo);
		$.each(jsonarr,function(i,item){
			switch(i){
				case 'email':
					$("#email_checkbox").prop('checked', true);
					$("#email_checkbox").prop('value', 'email');break;
				case 'policies':
					$("#policies_checkbox").prop('checked', true);break;
				case 'DOB':
					$("#DOB_checkbox").prop('checked', true);break;
				case 'driver_license':
					$("#driver_license_checkbox").prop('checked', true);break;
				case 'address':
					$("#address_checkbox").prop('checked', true);break;
			}
		});
		
		$("form :input, textarea").change(function ()  
        {
            $('form').data('changed',true);
        });
          
        $("form").submit(function (){
            if(!$('form').data('changed'))  
            {  
                alert('please change one detail at least.!!!');  
                return false;  
            }
			
			var email = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
			if(!email.test($("#email").val())){
				alert('invalid email format!!!');  
				return false;
			}
        });
		
		$("#recharge").click(function(){
			$( "#confirm" ).dialog({
				resizable: false,
				height: 300,
				width: 400,
				modal: true,
				buttons: {
					"Confirm": function() {
						$.post("profile.php",{balance:$("#money_Nu").val() },
						function(result){
							alert(result);
							location.replace("home.php");
						});
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		});
    }
); 
</script>	

</head>
<body>
<div>
	<h2>Suber</h2>
	<div class="profile" >
		<h1>Profile</h1>
		
		<form method="post" id="profile_form">
			<?='<p><font color="red">'.$info.'</font></p>'?>
			<p>check the box to select which personal information is accessible to other members!!</p>
			<table>
				<tr align="left">
					<th><input type="checkbox" id="email_checkbox" name="email_checkbox" onclick="if(this.checked){this.value='email'}else{this.value=''}">
					<label for="email_checkbox"> E-mail: </label></th>
					<th><input type="text" name="email" id="email" value="<?= $_SESSION['user']['email']?>" placeholder="email" required></th>
				</tr>
				<tr align="left">
					<th><input type="checkbox" id="policies_checkbox" name="policies_checkbox" onclick="if(this.checked){this.value='policies'}else{this.value=''}">
					<label for="policies_checkbox"> policies: </label></th>
					<th><input type="text" name="policies" id="policies" value="<?= $_SESSION['user']['policies']?>" placeholder="policies" ></th>
				</tr>
				<tr align="left">
					<th><input type="checkbox" id="DOB_checkbox" name="DOB_checkbox" onclick="if(this.checked){this.value='DOB'}else{this.value=''}">
					<label for="DOB_checkbox"> DOB:</label></th>
					<th><input type="date" name="DOB" id="DOB" value="<?= $_SESSION['user']['DOB']?>" placeholder="YYYY-MM-DD" ></th>
				</tr>
				<tr align="left">
					<th><input type="checkbox" id="driver_license_checkbox" name="driver_license_checkbox" onclick="if(this.checked){this.value='driver_license'}else{this.value=''}">
					<label for="driver_license_checkbox"> driver license:</label></th>
					<th><input type="text" name="driver_license" id="driver_license" value="<?= $_SESSION['user']['driver_license']?>" placeholder="driver_lience" ></th>
				</tr>
				<tr align="left">
					<th><input type="checkbox" id="address_checkbox" name="address_checkbox" onclick="if(this.checked){this.value='address'}else{this.value=''}">
					<label for="address_checkbox">address: </label></th>
					<th><textarea rows="4" cols="30" name="address" id="address" form="profile_form" placeholder="Enter Address Here.." style="resize:none"><?= $_SESSION['user']['address']?></textarea></th>
				</tr>
			</table>
			<label>Account balance: </label>
			<b><?= $_SESSION['user']['balance']?> </b>
			<input type="button" id="recharge" value="Recharge" /><br><br>
			
			<input type="submit" value="Change" />
			
		</form>
		<br>
		<a href="home.php">Cancel</a>
	</div>
<div id="confirm" style='display:none'>
	<label>How much money you would like Recharge: </label>
	<input type="number" id="money_Nu" min="50" />
</div>

</div>	
</body>
</html>