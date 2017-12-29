<script>
$(document).ready(function()
{ 	
	if(<?= $_SESSION['user']['balance']?> < 10)
	{
		alert("Your balance less than 10 c$, please recharge it in profile Option!!");
		setInterval(function(){
			alert("Your balance less than 10 c$, please recharge it in profile Option!!");
		},60000);
	}
	$("#email_button").click(function(){
		window.open("message.php", "", "status=no,location=0,menubar=no,toolbar=no,height=450,width=600,top=100,left=380");
	});
});
</script>
<a href="index.php">MainPage</a>&nbsp; 
<a href="profile.php">profile</a>&nbsp;
<a href="home.php">Home</a> &nbsp;
<a href="logout.php">Logout</a>&nbsp;

<?php if($_SESSION['user']['status']!='inactive'): ?>
	<a href="myTrip.php">my Trip</a>&nbsp; 
	<a href="myBook.php">my Book</a>&nbsp; 
	<input type="button" id="email_button" name="email_button" style="background-image:url('images.jpg');background-size:27px 22px;width:30px;height:25px;">
<?php endif; ?>

<br>

