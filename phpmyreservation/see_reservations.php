<?php

include_once('main.php');

if(check_login() != true) { exit; }

if(isset($_GET['reservation_details']))
{
	$reservation_id=mysqli_real_escape_string($dbconfig,$_POST['reservation_id']);
	echo reservation_details($reservation_id);
}
else if(isset($_GET['reservation_check_in']))
{
	$reservation_id=mysqli_real_escape_string($dbconfig,$_POST['reservation_id']);
	echo reservation_check_in($reservation_id);
	
}
else if(isset($_GET['reservation_check_out']))
{
	$reservation_id=mysqli_real_escape_string($dbconfig,$_POST['reservation_id']);
	echo reservation_check_out($reservation_id);
	
}
else{
	echo '<div class="box_div" id="reservation_details_div2"><div class="box_top_div">Group Details</div><div class=box_body_div><br></div></div>
	<br><br><div class="box_div" id="reservations_div"><div class="box_top_div">Upcoming Reservations</div><div class="box_body_div">';
	echo list_reservations();
	echo "&nbsp;&nbsp;<a href='exportbookings.php' target='_blank'><input type='button' class='blue_button'>Export Excel</button></a>";

	echo'
	
	</div></div>
	';
}
?>