<?php
$info = "";
session_start();
require_once("parameter.php");

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
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-3.1.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<title>Mail Box</title>
<script>
$(document).ready(function()
{
	$("#inbox").load("findmessage.php");
	setInterval(function(){
		$("#inbox").load("findmessage.php");
	},5000);
});
</script>
</head>
<body>
<h2><a href="message.php">Inbox</a>&nbsp <a href="sentbox.php">Sent Message</a>&nbsp <a href="newmessage.php">New Message</a>&nbsp <br>
<h2>Inbox</h2>

<div id="inbox">
</div>

</body>
</html>