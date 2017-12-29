<?php
$info = "";
$driverName="";
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user']))
{
	header('location:login.php');
	exit;
}
if($_SESSION['user']['status']!='inactive')
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM trip ORDER BY ID desc");
	$content = $rs->fetchAll();
	
}
?>
<html>

<head>

<link rel="stylesheet" type="text/css" href="css/suber.css">
 <style>
	.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	/* Modal Content */
	modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
	}

	/* The Close Button */
	.close {
    color: #aaaaaa;
    float: right;
    font-size: 13px;
    font-weight: bold;
	}

	.close:hover,
	.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
	}
</style>

<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

$(document).ready(function()
    {  
		if('<?=$_SESSION['user']['privilege']?>'=='admin')
		{
			$('#adminPrivilege').append("<a href='userManage.php'>change privilege or status of others</a>");
		}
		if('<?=$_SESSION['user']['status']?>'=='inactive')
		{
			$("input").prop('disabled',true);
			$('.botton_trip').append('<p><font color="red">your account is inactive, Please complete your <a href="profile.php">profile</a></p>');	
		}
		$("#trip_post").click(function(){
			location.replace('postTrip.php');
		});
		$("#find_trip").click(function(){
			location.replace('searchTrip.php');
		});
		$("#spo_post").click(function(){
			location.replace('postSpOffer.php');
		});
		$("#current_trip a").click(function() {
			var nameinfo = $(this).text()
			$("#username_Info").empty();
			$("#username_Info").append(nameinfo);
			$("#user_rate").load("findUserRate.php",{username: nameinfo});
			
			$.post("findUserInfo.php",
				{driver: $(this).text()},
				function(data){
					if( data =='[]')
					{
						$("#userinfo").empty();
						$("#userinfo").append("<p style='color:red'>no personal information!!</p><br>");
						return;
					}
					var jsonarr = jQuery.parseJSON(data);
					$("#userinfo").empty();
					$("#userinfo").append("<p>");
					$.each(jsonarr,function(i,item){
						$("#userinfo").append( i +": "+ item+"<br>");
					});
					$("#userinfo").append("</p><br>");
					
					//$("#userinfo").append(data);
				});
			$("#myModal").show();
		});
		$("#close").click(function() {
			$("#myModal").hide();
		});
		$("#sendMs").click(function(){
			var m_to = $("#username_Info").text();
			/*$("#email").dialog({
				width: 600,
                height: 500,
			});
			$("#email").tabs();*/
			window.open("newmessage.php?m_to="+ m_to, "", "status=no,location=0,menubar=no,toolbar=no,height=450,width=600,top=100,left=380");
		});
    }
);
window.onclick = function(event) {
    if (event.target == document.getElementById("myModal")) {
        $("#myModal").hide();
    }
}

</script>

</head>

<body>


<?php require_once("header.php"); ?>
<h1>Welcome to Suber <?= $_SESSION['user']['username'].'!!!'?> &nbsp;</h1>
	<div id="adminPrivilege">
	</div><br>
	<div class="botton_trip">
	AS driver: <input id="trip_post" type="button" value="Post a trip" /> <input id="spo_post" type="button" value="Post a Special Offer" />
	<br><br>AS riders: <input id="find_trip" type="button" value="Find a trip" />
	<br><br> <a href="listSpOffer.php">Special offers</a>
	
</div>
<br>

<?php if($_SESSION['user']['status']!='inactive'): ?>
	<div id="current_trip">
		<table style="width:60%">
			<tr>
			<th>Trip date</th>
			<th>depart time</th>
			<th>driver</th> 
			<th>Departure</th>
			<th>Destination</th>
			<th>GoDetail</th>
			</tr>
			
			<?php foreach($content as $trip) { ?>
				<tr align="center">
					<?php if(!empty($trip['Date'])): ?>
						<td><?php echo $trip['Date']?></td>
					<?php else: ?>
						<td> <?php echo $trip['Day']?></td>
					<?php endif; ?>
					
					<td> <?php echo $trip['depart_time']?></td>
					
					<?php if($trip['driver']==$_SESSION['user']['username']): ?>
						<td><?php echo $trip['driver'];?></td>
					<?php else :?>
						<td><a href="javascript:;" value="<?php echo $trip['driver'];?>" ><?php echo $trip['driver'];?></a></td>
					<?php endif; ?>
					<td><?php echo $trip['City_departure']." ".$trip['Postcode_departure']?></td>
					<td><?php echo $trip['City_destination']." ".$trip['Postcode_destination']?></td>
					<td><button type="button" onclick="location.replace('detailTrip.php?id=<?php echo $trip['ID'] ?>');">detail</button></td>
				<tr>
			<?php } ?>
		</table>	
	</div>
<?php endif; ?>

<div id="myModal" class="modal">
  <div class="modal-content">
	
    <span class="close" id='close'>close</span>
	<h2 id='username_Info'></h2>
	<div id='user_rate'> </div>
	<p><a id="sendMs" href="javascript:;" title="Email">send message to him/her</a></p>
    <div id="userinfo">
		
	</div>
 </div>
</div>


</body>
</html>