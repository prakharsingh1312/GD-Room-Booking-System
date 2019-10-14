<?php

include_once('main.php');

if(check_login() != true) { exit; }
$yesicon='<i class="fa fa-check" style="color: green;"/>';
$noicon='<i class="fa fa-times" style="color: red;"/>';
if(isset($_GET['roomdetail']))
{
	$room_id=mysqli_real_escape_string($dbconfig,$_POST['room_id']);
	echo room_detail($room_id);
}
else{
	$query = mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_room_details_table." ORDER BY room_id")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	// $query2 = mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_reservations_table." WHERE reservation_room_id = $room_id ORDER BY")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	echo '
	<div class="box_div" id="room_detail_div" style="max-width: 50%">
	<div class="box_top_div">Room Availability</div>	
	<div class="box_body_div">

	</div>
	</div>
	';

	echo '
	<br><br>
	<div class="box_div" id="room_div" style="max-width: 70%">

	<div class="box_top_div">Room Details</div>
	<div class="box_body_div" >';
	// echo show_room_page();
	if(mysqli_num_rows($query) > 0)
	{
		echo "
		<table id='users_table'>
		<th>Room Name</th>
		<th>Room Number</th>
		<th>Floor</th>
		<th>Seating Capacity</th>
		<th>TV</th>
		<th>Projector</th>
		<th>HDMI</th>
		<th>VGA</th>
		<!--<th>Availability</th>-->
		";
		while($rooms = mysqli_fetch_array($query))
		{
			
		echo '<tr><td>'.$rooms['room_name'].'</td>
		<td>'.$rooms['room_id'].'</td>
		<td>'.$rooms['Floor'].'</td>
		<td>'.$rooms['Seating_Capacity'].'</td>';
		if (strcasecmp($rooms['TV'],"yes")==0) 
			$tv=$yesicon; 
		else 
			$tv=$noicon;
		echo '
		<td>'.$tv.'</td>';
		if (strcasecmp($rooms['Projector'],"yes")==0) 
			$proj=$yesicon; 
		else 
			$proj=$noicon;
		echo '
		<td>'.$proj.'</td>';
		if (strcasecmp($rooms['HDMI'],"yes")==0) 
			$hdmi=$yesicon; 
		else 
			$hdmi=$noicon;
		echo '
		<td>'.$hdmi.'</td>';
		if (strcasecmp($rooms['VGA'],"yes")==0) 
			$vga=$yesicon;
		else 
			$vga=$noicon;
		echo '
		<td>'.$vga.'</td>
		<!--<td> <input type="button" class="blue_button check_availability_button" id="'.$rooms['room_id'].'" value="Check Availability"/></td>-->
		</tr>';
		
		}
	echo '</table></div></div><br><br>';

}
}
?>