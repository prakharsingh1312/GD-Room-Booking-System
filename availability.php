<?php
include_once('main.php');
if(isset($_GET['day']))
{
	$day=mysqli_real_escape_string($_POST['day']);
	$week=mysqli_real_escape_string($_POST['week']);
	echo'<table id="reservation_table"><colgroup span="1" id="reservation_time_colgroup"></colgroup><colgroup span="7" id="reservation_day_colgroup"></colgroup><tr><td id="reservation_corner_td"><input type="button" class="blue_button small_button" id="reservation_today_button" value="Today"></td>';
		
		foreach($global_times as $time)
			echo '<th class="reservation_day_th">'.$time.'</th>';
		echo'</tr>';
		echo show_room_availability($week,$day,$global_times);
		echo '</table>';
}
else{
$date=date('d-M-Y',strtotime(global_year."-W".global_week_number."-".global_day_number));

echo'<div class="box_div" id="reservation_div"><div class="box_top_div" id="reservation_top_div"><div id="reservation_top_left_div"><a href="#check" id="previous_day_a">&lt; Previous day</a></div><div id="reservation_top_center_div">Reservations for day <span class="week_number_span" id="'.global_week_number.':'.global_day_number.'">'.$date.'</span></div><div id="reservation_top_right_div"><a href="#check" id="next_day_a">Next day &gt;</a></div></div><div class="box_body_div" id="availability_div">
	<table id="reservation_table"><colgroup span="1" id="reservation_time_colgroup"></colgroup><colgroup span="7" id="reservation_day_colgroup"></colgroup><tr><td id="reservation_corner_td"><input type="button" class="blue_button small_button" id="reservation_today_button" value="Today"></td>';
		 
		foreach($global_times as $time)
			echo '<th class="reservation_day_th">'.$time.'</th>';
		echo'</tr>';
		echo show_room_availability(global_week_number,global_day_number,$global_times);
		
		echo'</table></div></div>';
}
			?>