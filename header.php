<?php include_once('main.php'); ?>

<div id="header_inner_div"><div id="header_inner_left_div">

<a href="#about">About</a> | <a href="#check">Check Availability</a>

<?php

if(isset($_SESSION['logged_in']) && !isset($_SESSION['staff']))
{
	echo ' | <a href="#">Home</a> | <a href="#help">Help</a> | <a href="#room">Room Details</a>';
}

?>

</div><div id="header_inner_center_div">

<?php

if(isset($_SESSION['logged_in']))
{
	echo '<b>Week ' . global_week_number . ' - ' . global_day_name . ' ' . date('jS F Y') . '</b>';
}

?>

</div><div id="header_inner_right_div">

<?php

if(isset($_SESSION['logged_in']))
{
	if($_SESSION['user_is_admin'] || isset($_SESSION['staff'])){
		echo '<a href="#ma">See Reservations</a> | ';
	}
	echo '<a href="#cp">Control panel</a> | <a href="#logout">Log out</a>';
}
else
{
	echo '<a href="#login">Login</a>';
}

?>

</div></div>
