<?php

include_once('main.php');

if(check_login() != true) { exit; }

if(isset($_GET['make_reservation']))
{
	$week = mysqli_real_escape_string($dbconfig,$_POST['week']);
	$day = mysqli_real_escape_string($dbconfig,$_POST['day']);
	$time = mysqli_real_escape_string($dbconfig,$_POST['time']);
	$room_id=mysqli_real_escape_string($dbconfig,$_POST['room_id']);
	echo make_reservation($week, $day, $time,$room_id);
}
elseif(isset($_GET['delete_member'])){
	$member_id=mysqli_real_escape_string($dbconfig,$_POST['member_id']);
	echo delete_member($member_id);
}
elseif(isset($_GET['delete_group'])){
	$group_id=mysqli_real_escape_string($dbconfig,$_POST['group_id']);
	echo delete_group($group_id);
}

elseif(isset($_GET['select_group'])){
	$group_id=mysqli_real_escape_string($dbconfig,$_POST['group_id']);
	echo select_group($group_id);
}
elseif(isset($_GET['group_details'])){
	$group_id=mysqli_real_escape_string($dbconfig,$_POST['group_id']);
	echo group_details($group_id);
}
elseif(isset($_GET['invite'])){
	$group_id=mysqli_real_escape_string($dbconfig,$_POST['group_id']);
	$email=mysqli_real_escape_string($dbconfig,$_POST['invite_email']);
	$rollno=mysqli_real_escape_string($dbconfig,$_POST['invite_roll_no']);
	echo invite_user($group_id,$email,$rollno);
}
elseif(isset($_GET['accept_invite']))
{
	$member_id=mysqli_real_escape_string($dbconfig,$_POST['member_id']);
	echo accept_invite($member_id);
}
elseif(isset($_GET['reject_invite']))
{
	$member_id=mysqli_real_escape_string($dbconfig,$_POST['member_id']);
	echo reject_invite($member_id);
}
elseif(isset($_GET['show_invitations']))
{
	echo show_invitations();
}
elseif(isset($_GET['delete_reservation']))
{
	$week = mysqli_real_escape_string($dbconfig,$_POST['week']);
	$day = mysqli_real_escape_string($dbconfig,$_POST['day']);
	$time = mysqli_real_escape_string($dbconfig,$_POST['time']);
	$id = mysqli_real_escape_string($dbconfig,$_POST['id']);
	echo delete_reservation($week, $day, $time,$id);
}
elseif(isset($_GET['show_groups']))
{
	echo show_groups();
}
elseif(isset($_GET['read_reservation']))
{
	$week = mysqli_real_escape_string($dbconfig,$_POST['week']);
	$day = mysqli_real_escape_string($dbconfig,$_POST['day']);
	$time = mysqli_real_escape_string($dbconfig,$_POST['time']);
	echo read_reservation($week, $day, $time);
}
elseif(isset($_GET['read_reservation_details']))
{
	$week = mysqli_real_escape_string($dbconfig,$_POST['week']);
	$day = mysqli_real_escape_string($dbconfig,$_POST['day']);
	$time = mysqli_real_escape_string($dbconfig,$_POST['time']);
	echo read_reservation_details($week, $day, $time);
}

elseif(isset($_GET['create_group']))
{
	$group_name=mysqli_real_escape_string($dbconfig,$_POST['group_name']);
	echo create_group($group_name);
}
elseif(isset($_GET['book'])&& isset($_SESSION['selected_group']))
{
	$week=mysqli_real_escape_string($dbconfig,$_POST['week']);
	$day=mysqli_real_escape_string($dbconfig,$_POST['day']);
	$time=mysqli_real_escape_string($dbconfig,$_POST['time']);
	echo get_room_details($week,$day,$time);
}
elseif(isset($_GET['week']))
{
	if(isset($_SESSION['selected_group']))
	{
	$week = $_GET['week'];

	echo 'Group Selected:'.group_selected_name().'<br><table id="reservation_table"><colgroup span="1" id="reservation_time_colgroup"></colgroup><colgroup span="7" id="reservation_day_colgroup"></colgroup>';

	$days_row = '<tr><td id="reservation_corner_td"><input type="button" class="blue_button small_button" id="reservation_today_button" value="Today"></td><th class="reservation_day_th">Monday</th><th class="reservation_day_th">Tuesday</th><th class="reservation_day_th">Wednesday</th><th class="reservation_day_th">Thursday</th><th class="reservation_day_th">Friday</th><th class="reservation_day_th">Saturday</th><th class="reservation_day_th">Sunday</th></tr>';

	if($week == global_week_number)
	{
		echo highlight_day($days_row);
	}
	else
	{
		echo $days_row;
	}

	foreach($global_times as $time)
	{
		echo '<tr><th class="reservation_time_th">' . $time . '</th>';

		$i = 0;

		while($i < 7)
		{
			$i++;

			echo '<td><div class="reservation_time_div"';
			if($_SESSION['user_is_admin']!=1 && strtotime('now')-86400>=strtotime(global_year."W".$week."-".($i)))
				echo'style="pointer-events:none;opacity:.5;"';
			echo'><div class="reservation_time_cell_div" id="div:' . $week . ':' . $i . ':' . $time . '" onclick="void(0)">' . read_reservation($week, $i, $time) . '</div></div></td>';
		}

		echo '</tr>';
	}

	echo '</table>';
	}
	else{
		echo 'Please select a group to make reservations.';
	}
}
else
{
	if(check_reservation()>0){
	echo slot_booked();
	
}
	if(check_reservation()<2){
	echo'<div class="box_div" id="group_div"><div class="box_top_div">Groups</div><div class="box_body_div"><div id="group_list">';
	echo show_groups();
	echo'
	
	</div></div></div><br><br><div class="box_div" id="invitation_div">';
	echo show_invitations();
	echo'
	
	</div>
	<br><br>
	
	<div class="box_div" id="reservation_div"><div class="box_top_div" id="reservation_top_div"><div id="reservation_top_left_div"><a href="." id="previous_week_a">&lt; Previous week</a></div><div id="reservation_top_center_div">Reservations for week <span id="week_number_span">' . global_week_number . '</span></div><div id="reservation_top_right_div"><a href="." id="next_week_a">Next week &gt;</a></div></div><div class="box_body_div"><div id="reservation_table_div"></div></div></div>';
}}

?>
