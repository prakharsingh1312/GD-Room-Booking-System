<?php

include_once('main.php');

if(check_login() != true) { exit; }

if(isset($_GET['reservation_details']))
{
	$reservation_id=mysqli_real_escape_string($dbconfig,$_POST['reservation_id']);
	echo reservation_details($reservation_id);
}
if(isset($_GET['reservation_check_in']))
{
	$reservation_id=mysqli_real_escape_string($dbconfig,$_POST['reservation_id']);
	if(reservation_check_in($reservation_id))
	{
		echo '<div class="box_top_div">Upcoming Reservations</div><div class="box_body_div">';
	echo list_reservations();
	echo'
	
	</div>';
	}
	else
		echo 0;
}
else{
	echo '<div class="box_div" id="reservation_details_div2"><div class="box_top_div">Group Details</div><div class=box_body_div><br></div></div>
	<br><br><div class="box_div" id="reservations_div"><div class="box_top_div">Upcoming Reservations</div><div class="box_body_div">';
	echo list_reservations();
	echo'
	
	</div></div>
	';
}
?>