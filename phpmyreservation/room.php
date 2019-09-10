<?php

include_once('main.php');

if(check_login() != true) { exit; }

if(isset($_GET['roomdetail']))
{
	$room_id=mysqli_real_escape_string($dbconfig,$_POST['room_id']);
	echo room_detail($room_id);
}
else{
	echo '<div class="box_div" id="room_div">';
	echo show_room_page();
	echo'
	
	</div>
	<br><br><div class="box_div" id="room_details_div">';
}
?>