<!DOCTYPE html>
<html>
<head>
	<script lang="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script lang="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
</head>
<body>

<?php 
include_once('main.php');
include_once('config.php');
$dbconfig=mysqli_connect(global_mysqli_server, global_mysqli_user, global_mysqli_password,global_mysqli_database)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	$query = mysqli_query($dbconfig,"SELECT * FROM `phpmyreservation_groups`,`phpmyreservation_reservations`,`phpmyreservation_roomdetails` WHERE group_id=reservation_group_id AND reservation_room_id=room_id ORDER BY reservation_year DESC,reservation_week DESC,reservation_time DESC,reservation_room_id,reservation_check_in DESC")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	$users = '<table id="users_table" ><tr><th>ID</th><th>Name</th><th>Members</th><th>Reservation</th><th>Room</th><th>Check In Time</th><th>Check Out Time</th><th></th></tr>';

	while($user = mysqli_fetch_array($query))
	{
		
		$time="Time:".$user['reservation_time']."<br>".date("d-M-Y", strtotime($user['reservation_year']."W".$user['reservation_week']."-".$user['reservation_day']));
		$users .= '<tr id="user_tr_' . $user['group_id'] . '"><td><label for="user_radio_' . $user['group_id'] . '">' . $user['group_id'] . '</label></td><td><label for="user_radio_' . $user['group_id'] . '">' . $user['group_name'] . '</label></td><td>' . $time . '</td>	<td>'.$user['room_name'].'</td><td>'.$user['reservation_check_in'].'</td><td>'.$user['reservation_check_out'].'</td><td><input type="radio" class=" blue_button reservation_details_radio" id="'.$user['reservation_id'].'" value="'.$user['reservation_id'].'" name="reservation_details_radio"></td></tr>';
		}
	

	$users .= '</table><br><br>
	<div style="display: none">
	<input type="button" class=" blue_button " id="reservation_details_button" value="Details">
	&nbsp;&nbsp;&nbsp;<input type="button" class=" blue_button " id="reservation_check_in_button" value="Mark Check In">
	&nbsp;&nbsp;&nbsp;<input type="button" class=" button " id="reservation_check_out_button" value="Mark Check Out"> </div>';

	echo $users;
	echo "<button id='button-a'>Create Excel</button>";
	
	echo'
	
	</div></div>
	';


 ?>
<script>
	 

        var wb = XLSX.utils.table_to_book(document.getElementById('users_table'), {sheet:"Sheet JS"});
        var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});
        function s2ab(s) {
                        var buf = new ArrayBuffer(s.length);
                        var view = new Uint8Array(buf);
                        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                        return buf;
        }

        <?php 
        echo "
        $('#button-a').click(function(){
        saveAs(new Blob([s2ab(wbout)],{type:'application/octet-stream'}), 'GD Room Reservations.xlsx');
        });
		
		";
		?>
		// $("#button-a").trigger('click');
</script>
</body>
</html>