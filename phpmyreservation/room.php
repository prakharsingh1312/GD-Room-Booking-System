<?php

include_once('main.php');

if(check_login() != true) { exit; }

if(isset($_GET['roomdetail']))
{
	$room_id=mysqli_real_escape_string($dbconfig,$_POST['room_id']);
	echo 
}
echo ''
?>