<?php

include_once('main.php');

if(check_login() != true) { exit; }
$yesicon='<i class="fa fa-check" style="color: green;"/>';
$noicon='<i class="fa fa-times" style="color: red;"/>';
if(isset($_GET['changedetail']) )
{
	$room_id=mysqli_real_escape_string($dbconfig,$_POST['room_id']);
	$detail=mysqli_real_escape_string($dbconfig,$_POST['detail']);
	$state=mysqli_real_escape_string($dbconfig,$_POST['state']);
	echo change_detail($detail,$room_id,$state);
}
else{
	$query = mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_room_details_table." ORDER BY room_id")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	// $query2 = mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_reservations_table." WHERE reservation_room_id = $room_id ORDER BY")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
//	echo '
//	<div class="box_div" id="room_detail_div" style="max-width: 50%">
//	<div class="box_top_div">Room Availability</div>	
//	<div class="box_body_div">
//
//	</div>
//	</div>
//	';

	echo '
	<br><br>
	<div class="box_div" id="room_div" style="max-width: 70%">

	<div class="box_top_div">Room Details</div>
	<div class="box_body_div" >';
	// echo show_room_page();
	if(mysqli_num_rows($query) > 0)
	{
		echo "
		<table id='users_table' style='text-align: center'>
		<th>Room Name</th>
		<th>Room Number</th>
		<th>Floor</th>
		<th>Seating Capacity</th>
		<th>TV</th>
		<th>Projector</th>
		<th>HDMI</th>
		<th>VGA</th>
		
		";
		while($rooms = mysqli_fetch_array($query))
		{
			
		echo '<tr><td>'.$rooms['room_name'].'</td>
		<td>'.$rooms['room_code'].'</td>
		<td>'.$rooms['Floor'].'</td>
		<td>'.$rooms['Seating_Capacity'].'</td>';
		if (strcasecmp($rooms['TV'],"yes")==0) 
			$tv='<i class="fa fa-check room_detail_icon" id="TV:'.$rooms['room_id'].':NO" style="color: green;"/>'; 
		else 
			$tv='<i class="fa fa-times room_detail_icon" id="TV:'.$rooms['room_id'].':YES" style="color: red;"/>';
		echo '
		<td>'.$tv.'</td>';
		if (strcasecmp($rooms['Projector'],"yes")==0) 
			$proj='<i class="fa fa-check room_detail_icon" id="Projector:'.$rooms['room_id'].':NO" style="color: green;"/>'; 
		else 
			$proj='<i class="fa fa-times room_detail_icon" id="Projector:'.$rooms['room_id'].':YES" style="color: red;"/>';
		echo '
		<td>'.$proj.'</td>';
		if (strcasecmp($rooms['HDMI'],"yes")==0) 
			$hdmi='<i class="fa fa-check room_detail_icon" id="HDMI:'.$rooms['room_id'].':NO" style="color: green;"/>'; 
		else 
			$hdmi='<i class="fa fa-times room_detail_icon" id="HDMI:'.$rooms['room_id'].':YES" style="color: red;"/>';
		echo '
		<td>'.$hdmi.'</td>';
		if (strcasecmp($rooms['VGA'],"yes")==0) 
			$vga='<i class="fa fa-check room_detail_icon" id="VGA:'.$rooms['room_id'].':NO" style="color: green;"/>';
		else 
			$vga='<i class="fa fa-times room_detail_icon" id="VGA:'.$rooms['room_id'].':YES" style="color: red;"/>';
		echo '
		<td>'.$vga.'</td>
		';
		$imgurl="img/rooms/".$rooms["imageurl"].".jpg";
		$abc='<a href="'.$imgurl.'" target="_blank"> <input type="button" class="blue_button" value="View Image"/> </a>';
		echo '
		<td>'. 
			$abc
			.'
		</td>
		</tr>'
;		
		}
	echo '</table></div></div><br><br>';

}
}
?>