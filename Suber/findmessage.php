<?php
session_start();
require_once("parameter.php");

if(isset($_SESSION['user']))
{
	$pdo = new PDO("mysql:host=".$dbhost.";dbname=".$dbname,$dbuser,$dbpassword); 
	$pdo->exec("SET NAMES 'utf8';");
	$rs = $pdo -> query("SELECT * FROM message WHERE m_to ='".$_SESSION['user']['username']."' ORDER BY senddate desc");
	if($rs->rowCount()!=0)
	{
		$content = $rs->fetchAll();
		foreach($content as $msg)
		{
			echo "<div id='msg'><p><a href='javascript:;' value='".$msg['m_from']."' >".$msg['m_from']."</a>  --- ".$msg['senddate']."<br><b>".$msg['content']."</b></p></div>";
		}
	}
	else
	{
		echo "<h3 style='color:red'> NO Message </h3>";
	}
}
?>


<script>
$(document).ready(function()
    {  
		$("#msg a").click(function() {
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
					
				});
			$("#myModal").show();
		});
		$("#close").click(function() {
			$("#myModal").hide();
		});
		$("#sendMs").click(function(){
			var m_to = $("#username_Info").text();
			window.location.replace("newmessage.php?m_to="+ m_to, "", "status=no,location=0,menubar=no,toolbar=no,height=450,width=600,top=100,left=380");
		});
    }
);
window.onclick = function(event) {
    if (event.target == document.getElementById("myModal")) {
        $("#myModal").hide();
    }
}
</script>
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