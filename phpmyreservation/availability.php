<?php
include_once('main.php');
?>

<div class="box_div" id="reservation_div"><div class="box_top_div" id="reservation_top_div"><div id="reservation_top_left_div"><a href="." id="previous_day_a">&lt; Previous day</a></div><div id="reservation_top_center_div">Reservations for day<span id="week_number_span"></span></div><div id="reservation_top_right_div"><a href="." id="next_day_a">Next day &gt;</a></div></div><div class="box_body_div">
	<table ><colgroup span="1" ></colgroup><colgroup span="7" ></colgroup><tr><td ><input type="button" class="blue_button small_button" id="reservation_today_button" value="Today"></td>
		<?php 
		foreach($global_times as $time)
			echo '<th >'.$time.'</th>';
		?>
		</tr></div></div>