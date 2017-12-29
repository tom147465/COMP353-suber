<?php
$info = "";
session_start();
require_once("parameter.php");
if(!isset($_SESSION['user'])&&$_SESSION['user']['status']=='inactive')
{
	header('location:login.php');
	exit;
}
else
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM specialoffer ORDER BY Date,depart_time desc");
	$content = $rs->fetchAll();
}
?>
<html>
<head>
<title>My Special Offers</title>
<link rel="stylesheet" type="text/css" href="css/suber.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
.modal-content {
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
</head>
<script>
$(document).ready(function()
    {  
		$("#SpOffer a").click(function() {
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
<body>

<?php require_once("header.php"); ?> 
<h1>My Special Offers</h1>
<?php if($_SESSION['user']['status']!='inactive'): ?>
	<div id="SpOffer">
		<table style="width:60%">
			<tr><th>date</th><th>depart time</th><th>driver</th> <th>Departure</th> <th>Destination</th> <th>GoDetial</th></tr>
			<?php foreach($content as $offer) { ?>
				<tr align="center">
					<?php if(!empty($offer['Date'])): ?>
						<td><?php echo $offer['Date']?></td>
					<?php else: ?>
						<td> <?php echo $offer['Day']?></td>
					<?php endif; ?>
					
					<td> <?php echo $offer['depart_time']?></td>
					
					<?php if($offer['driver']==$_SESSION['user']['username']): ?>
						<td><?php echo $offer['driver'];?></td>
					<?php else :?>
						<td><a href="javascript:;" value="<?php echo $offer['driver'];?>" ><?php echo $offer['driver'];?></a></td>
					<?php endif; ?>
					<td><?php echo $offer['City_departure']." ".$offer['Postcode_departure']?></td>
					<td><?php echo $offer['City_destination']." ".$offer['Postcode_destination']?></td>
					<td><button type="button" onclick="location.replace('detailSpOffer.php?id=<?php echo $offer['ID'] ?>');">detail</button></td>
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
	<p><a id="sendMs" href="javascript:;" title="Email">send massage to him/she</a></p>
    <div id="userinfo">
		
	</div>
 </div>
</div>

</body>
</html>