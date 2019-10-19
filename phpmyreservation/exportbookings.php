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

	global $dbconfig;
	$query1=mysqli_query($dbconfig,"SELECT member_group_id,count(*) from ".global_mysqli_group_members_table." GROUP by member_group_id");
	$group_size=array();
	while($result=mysqli_fetch_array($query1)){
		$group_size[$result['member_group_id']]=$result['count(*)'];
	}
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_groups_table . ",".global_mysqli_reservations_table.",".global_mysqli_room_details_table." WHERE group_id=reservation_group_id AND reservation_room_id=room_id ORDER BY reservation_year DESC,reservation_week DESC,reservation_time DESC,reservation_room_id,reservation_check_in DESC")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	$users = '
	<button id="button-a"> Create Excel </button>
	<table id="users_table" border="1"><tr><th>Group ID</th><th>Group Name</th><th>Reservation Date</th><th>Room</th><th>Floor</th><th>Check In Time</th><th>Check Out Time</th><th>Student Name</th><th>Student Roll No.</th><th>Student Branch</th><th>Student Email</th><th>Student Mobile Number</th></tr>';

	while($user = mysqli_fetch_array($query))
	{
		
		$time="Time:".$user['reservation_time']."<br>".date("d-M-Y", strtotime($user['reservation_year']."W".$user['reservation_week']."-".$user['reservation_day']));
		$users .= '<tr id="user_tr_' . $user['group_id'] . '">
		<td rowspan="'.$group_size[$user['group_id']].'"><label for="user_radio_' . $user['group_id'] . '">' . $user['group_id'] . '</label></td><td rowspan="'.$group_size[$user['group_id']].'"><label for="user_radio_' . $user['group_id'] . '">' . $user['group_name'] . '</label></td>
		<td rowspan="'.$group_size[$user['group_id']].'">' . $time . '</td>
		<td rowspan="'.$group_size[$user['group_id']].'">'.$user['room_name'].'</td>
		<td rowspan="'.$group_size[$user['group_id']].'">'.$user['Floor'].'</td>
		<td rowspan="'.$group_size[$user['group_id']].'">'.$user['reservation_check_in'].'</td>
		<td rowspan="'.$group_size[$user['group_id']].'">'.$user['reservation_check_out'].'</td>
		';
	$query3=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_group_members_table.",".global_mysqli_users_table.",".global_mysqli_branches_table." WHERE user_id=member_user_id and member_group_id={$user['group_id']} and branch_id=user_branch_id");
		$i=0;
	while($result=mysqli_fetch_array($query3)){
		if($i!=0)
		$users.='<tr>';
		$i++;
		$users=$users.'<td>'.$result['user_name'].'</td>
		<td>'.$result['user_roll_no'].'</td>
		<td>'.$result['branch_code'].'</td>
		<td>'.$result['user_email'].'</td>
		<td>'.$result['user_mobile_no'].'</td>
		</tr>

		';
	}
	}
$users.="</table>";
		echo $users;
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