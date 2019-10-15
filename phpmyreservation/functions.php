<?php
include_once('config.php');
// Configuration
$dbconfig=mysqli_connect(global_mysqli_server, global_mysqli_user, global_mysqli_password,global_mysqli_database)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
$query=mysqli_query($dbconfig,"SELECT * FROM ". global_mysqli_room_details_table)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
$count=mysqli_num_rows($query);
define('count_rooms',$count);
function get_configuration($data)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_configuration_table)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$configuration = mysqli_fetch_array($query);
	return($configuration[$data]);
}

// Password

function random_password()
{
	$password = rand('1001', '9999');
	return $password;
}

function encrypt_password($password)
{
	$password = crypt($password, '$1$' . global_salt);
	return($password);
}

function add_salt($password)
{
	$password = '$1$' . substr(global_salt, 0, -1) . '$' . $password;
	return($password);
}

function strip_salt($password)
{
	$password = str_replace('$1$' . substr(global_salt, 0, -1) . '$', '', $password);
	return($password);	
}

// String manipulation

function modify_email($email)
{
	$email = str_replace('@', '(at)', $email);
	$email = str_replace('.', '(dot)', $email);
	return($email);
}

// String validation

function validate_user_name($user_name)
{
	if(preg_match('/^[a-z æøåÆØÅ]{2,12}$/i', $user_name))
	{
		return(true);
	}
}

function validate_user_email($user_email)
{
	if(filter_var($user_email, FILTER_VALIDATE_EMAIL) && strlen($user_email) < 51)
	{
		return(true);
	}
}

function validate_user_password($user_password)
{
	if(strlen($user_password) > 3 && trim($user_password) != '')
	{
		return(true);
	}
}

function validate_price($price)
{
	if(is_numeric($price))
	{
		return(true);
	}
}

// User validation

function user_name_exists($user_name)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE user_name='$user_name'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	if(mysqli_num_rows($query) > 0)
	{
		return(true);
	}
}

function user_email_exists($user_email)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE user_email='$user_email'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	if(mysqli_num_rows($query) > 0)
	{
		return(true);
	}
}
function user_roll_no_exists($user_roll_no)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE user_roll_no='$user_roll_no'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	if(mysqli_num_rows($query) > 0)
	{
		return(true);
	}
}
function user_mobile_no_exists($user_mobile_no)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE user_mobile_no='$user_mobile_no'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	if(mysqli_num_rows($query) > 0)
	{
		return(true);
	}
}

// Login

function get_login_data($data)
{
	if($data == 'user_email' && isset($_COOKIE[global_cookie_prefix . '_user_email']))
	{
		return($_COOKIE[global_cookie_prefix . '_user_email']);
	}
	elseif($data == 'user_password' && isset($_COOKIE[global_cookie_prefix . '_user_password']))
	{
		return($_COOKIE[global_cookie_prefix . '_user_password']);
	}
}

function login($user_email, $user_password, $user_remember)
{
	global $dbconfig;
	$user_password_encrypted = encrypt_password($user_password);
	$user_password = add_salt($user_password);
	
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE (user_email='$user_email' AND user_password='$user_password_encrypted'AND user_activated=1) OR( user_email='$user_email' AND user_password='$user_password'AND user_activated=1)")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	if(mysqli_num_rows($query) == 1)
	{
			$user = mysqli_fetch_array($query);

			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_is_admin'] = $user['user_is_admin'];
			$_SESSION['user_email'] = $user['user_email'];
			$_SESSION['user_name'] = $user['user_name'];
			$_SESSION['user_reminder'] = $user['user_reservation_reminder'];
			$_SESSION['logged_in'] = '1';

			if($user_remember == '1')
			{
				$user_password = strip_salt($user['user_password']);

				setcookie(global_cookie_prefix . '_user_email', $user['user_email'], time() + 3600 * 24 * intval(global_remember_login_days));
				setcookie(global_cookie_prefix . '_user_password', $user_password, time() + 3600 * 24 * intval(global_remember_login_days));
			}

			return(1);
	}
}

function check_login()
{
	global $dbconfig;
	if(isset($_SESSION['logged_in']))
	{
		$user_id = $_SESSION['user_id'];
	global $dbconfig;
		$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

		if(mysqli_num_rows($query) == 1)
		{
			return(true);
		}
		else
		{
			logout();
			echo '<script type="text/javascript">window.location.replace(\'.\');</script>';
		}
	}
	else
	{
		logout();
		echo '<script type="text/javascript">window.location.replace(\'.\');</script>';
	}
}

function logout()
{
	session_unset();
	setcookie(global_cookie_prefix . '_user_email', '', time() - 3600);
	setcookie(global_cookie_prefix . '_user_password', '', time() - 3600);
}

function create_user($user_name, $user_email, $user_password, $user_secret_code,$user_branch,$user_mobile_no,$user_roll_no)
{
	global $dbconfig;
	if(validate_user_name($user_name) != true)
	{
		return('<span class="error_span">Name must be <u>letters only</u> and be <u>2 to 12 letters long</u>. If your name is longer, use a short version of your name</span>');
	}
	elseif(validate_user_email($user_email) != true)
	{
		return('<span class="error_span">Email must be a valid email address and be no more than 50 characters long</span>');
	}
	elseif(validate_user_password($user_password) != true)
	{
		return('<span class="error_span">Password must be at least 4 characters</span>');
	}
	elseif(global_secret_code != '0' && $user_secret_code != global_secret_code)
	{
		return('<span class="error_span">Wrong secret code</span>');
	}
	elseif(user_name_exists($user_name) == true)
	{
		return('<span class="error_span">Name is already in use. If you have the same name as someone else, use another spelling that identifies you</span>');
	}
	elseif(user_email_exists($user_email) == true)
	{
		return('<span class="error_span">Email is already registered. <a href="#forgot_password">Forgot your password?</a></span>');
	}
	elseif(user_roll_no_exists($user_roll_no) == true)
	{
		return('<span class="error_span">Roll No. is already registered. <a href="#forgot_password">Forgot your password?</a></span>');
	}
	elseif(user_mobile_no_exists($user_mobile_no) == true)
	{
		return('<span class="error_span">Mobile No. is already registered. <a href="#forgot_password">Forgot your password?</a></span>');
	}
	else
	{
		$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . "")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

		if(mysqli_num_rows($query) == 0)
		{
			$user_is_admin = '1';
		}
		else
		{
			$user_is_admin = '0';
		}

		$user_password = encrypt_password($user_password);
		$user_hash=md5(rand(0,100000)."pineapples");
		$token=md5($user_email);
		$query=mysqli_query($dbconfig,"INSERT INTO " . global_mysqli_users_table . " (user_is_admin,user_email,user_password,user_name,user_reservation_reminder,user_roll_no,user_mobile_no,user_branch_id,user_hash) VALUES ($user_is_admin,'$user_email','$user_password','$user_name','0','$user_roll_no','$user_mobile_no','$user_branch','$user_hash')")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		if($query)
		{
                     
$headers = 'From:gdroombooking@thapar.edu' . "\r\n"; // Set from headers
require_once "Mail.php";

$from = 'prakharsingh@gmail.com';
$to = $user_email;
$subject = 'Signup | Verification'; // Give the email a subject
$body = '
 
Thanks for signing up!
Your account has been created, you can login after you have verified your email address.
 
Please click this link to verify you email address:
http://146.148.48.62/roombook/phpmyreservation/login.php?token='.$token.'&hash='.$user_hash.'&verify'; // Our message above including the link

$headers = array(
    'From' => $from,
    'To' => $to,
    'Subject' => $subject
);

$smtp = Mail::factory('smtp', array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => '465',
        'auth' => true,
        'username' => 'prakharsingh13@gmail.com',
        'password' => 'jangipur13'
    ));

$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail)) {
      echo("<scrpit type='text/javascript'>alert('" . $mail->getMessage() . "')</script>");}
		}
		$user_password = strip_salt($user_password);

		setcookie(global_cookie_prefix . '_user_email', $user_email, time() + 3600 * 24 * intval(global_remember_login_days));
		setcookie(global_cookie_prefix . '_user_password', $user_password, time() + 3600 * 24 * intval(global_remember_login_days));

		return(1);
	}
}

function list_admin_users()
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE user_is_admin='1' ORDER BY user_name")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	if(mysqli_num_rows($query) < 1)
	{
		return('<span class="error_span">There are no admins</span>');
	$dbconfig=mysqli_connect(global_mysqli_server, global_mysqli_user, global_mysqli_password,global_mysqli_database)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');}
	else
	{
		$return = '<table id="forgot_password_table"><tr><th>Name</th><th>Email</th></tr>';

		$i = 0;

		while($user = mysqli_fetch_array($query))
		{
			$i++;

			$return .= '<tr><td>' . $user['user_name'] . '</td><td><span id="email_span_' . $i . '"></span></td></tr><script type="text/javascript">$(\'#email_span_' . $i . '\').html(\'<a href="mailto:\'+$.base64.decode(\'' . base64_encode($user['user_email']) . '\')+\'">\'+$.base64.decode(\'' . base64_encode($user['user_email']) . '\')+\'</a>\');</script>';
		}

		$return .= '</table>';

		return($return);
	}
}

// Reservations

function highlight_day($day)
{
	$day = str_ireplace(global_day_name, '<span id="today_span">' . global_day_name . '</span>', $day);
	return $day;
}


function read_reservation($week, $day, $time)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_reservations_table . " WHERE reservation_week='$week' AND reservation_day='$day' AND reservation_time='$time'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$reservation = mysqli_num_rows($query);
	$count=count_rooms-$reservation;
	if(count_rooms<=$reservation)
		return('<b id="roomAvailability'.$week.$day.$time.'">All rooms Booked<b>');
	else
		return ('<b id="roomAvailability'.$week.$day.$time.'">Rooms Left:'.$count.'</b>');
}

function read_reservation_details($week, $day, $time)
{global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_reservations_table . " WHERE reservation_week='$week' AND reservation_day='$day' AND reservation_time='$time'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$reservation = mysqli_fetch_array($query);

	if(empty($reservation))
	{
		return(0);
		
	}
	else
	{
		return('<b>Reservation made:</b> ' . $reservation['reservation_made_time'] . '<br><b>User\'s email:</b> ' . $reservation['reservation_group_id']);
	}
}
function list_rooms($week,$day,$time){
		global $dbconfig;
}
function make_reservation($week, $day, $time,$room_id)
{
	global $dbconfig;
	$user_id = $_SESSION['user_id'];
	$user_email = $_SESSION['user_email'];
	$user_name = $_SESSION['user_name'];
	$price = global_price;
	$query=mysqli_query($dbconfig,"SELECT member_user_id FROM ".global_mysqli_group_members_table." WHERE member_group_id={$_SESSION['selected_group']}");
	$flag=0;
	$flag1=0;
	while($result=mysqli_fetch_array($query))
	{
		$check=mysqli_query($dbconfig,"SELECT count(*) from ".global_mysqli_reservations_table.",".global_mysqli_group_members_table." WHERE member_user_id={$result['member_user_id']} and member_group_id=reservation_group_id and reservation_week>=".global_week_number." and reservation_day>=".global_day_number." group by member_user_id");
		$res=mysqli_fetch_array($check);
		if($res['count(*)']==2)
		{
			return('One or more members from your group has already made 2 reservations.');
		}
		$check2=mysqli_query($dbconfig,"SELECT * from ".global_mysqli_reservations_table.",".global_mysqli_group_members_table." WHERE member_user_id={$result['member_user_id']} and member_group_id=reservation_group_id and reservation_week=$week and reservation_day=$day and reservation_time='$time'");
		if(mysqli_num_rows($check2)>0){
			return('One or more members from your group has already booked a slot for this time of the week.');
		}
		$t=(string)(((int)substr($time,0,2))-2).'-'.(string)(((int)substr($time,3,2))-2);
		$t1=(string)(((int)substr($time,0,2))+2).'-'.(string)(((int)substr($time,3,2))+2);
		$check3=mysqli_query($dbconfig,"SELECT * from ".global_mysqli_reservations_table.",".global_mysqli_group_members_table.",".global_mysqli_room_details_table." WHERE reservation_room_id=$room_id and reservation_group_id=member_group_id and reservation_week=$week and reservation_day=$day and (reservation_time='$t' or reservation_time='$t1') and member_user_id={$result['member_user_id']}");
		if(mysqli_num_rows($check3)>0)
			return('You cannot book consecutive slots in the same room.');
	}
	if($week == '0' && $day == '0' && $time == '0')
	{
		mysqli_query($dbconfig,"INSERT INTO " . global_mysqli_reservations_table . " (reservation_made_time,reservation_week,reservation_day,reservation_time,reservation_price,reservation_group_id,reservation_room_id) VALUES (now(),'$week','$day','$time','$price','{$_SESSION['selected_group']}',".count_rooms.")")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

		return(1);
	}
	elseif($week < global_week_number && $_SESSION['user_is_admin'] != '1' || $week == global_week_number && $day < global_day_number && $_SESSION['user_is_admin'] != '1')
	{
		return('You can\'t reserve back in time');
	}
	elseif($week > global_week_number + global_weeks_forward && $_SESSION['user_is_admin'] != '1')
	{
		return('You can only reserve ' . global_weeks_forward . ' weeks forward in time');
	}
	else
	{
		$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_reservations_table . " WHERE reservation_week='$week' AND reservation_day='$day' AND reservation_time='$time'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

		if(mysqli_num_rows($query) < count_rooms)
		{
			$year = global_year;

			mysqli_query($dbconfig,"INSERT INTO " . global_mysqli_reservations_table . " (reservation_made_time,reservation_year,reservation_week,reservation_day,reservation_time,reservation_price,reservation_group_id,reservation_room_id) VALUES (now(),'$year','$week','$day','$time','$price','{$_SESSION['selected_group']}','$room_id'".")")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

			return(1);
		}
		else
		{
			return('Someone else just reserved this time');
		}
	}
}

function delete_reservation($week, $day, $time,$id)
{
	global $dbconfig;
	if($week < global_week_number && $_SESSION['user_is_admin'] != '1' || $week == global_week_number && $day < global_day_number && $_SESSION['user_is_admin'] != '1')
	{
		return('You can\'t reserve back in time');
	}
	elseif($week > global_week_number + global_weeks_forward && $_SESSION['user_is_admin'] != '1')
	{
		return('You can only reserve ' . global_weeks_forward . ' weeks forward in time');
	}
	else
	{
		$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_reservations_table . " WHERE reservation_week='$week' AND reservation_day='$day' AND reservation_time='$time' AND reservation_id=$id")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_reservations_table . " WHERE reservation_week='$week' AND reservation_day='$day' AND reservation_time='$time' AND reservation_id=$id")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		$user = mysqli_fetch_array($query);

		if($user['reservation_group_id'] == $_SESSION['selected_group'] || $_SESSION['user_is_admin'] == '1')
		{
			mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_reservations_table . " WHERE reservation_week='$week' AND reservation_day='$day' AND reservation_time='$time' AND reservation_id=$id")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

			return(1);
		}
		else
		{
			return('You can\'t remove other users\' reservations');
		}
	}
}

// Admin control panel

function list_users()
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " ORDER BY user_is_admin DESC, user_name")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	$users = '<table id="users_table"><tr><th>ID</th><th>Admin</th><th>Name</th><th>Email</th><th>Reminders</th><th></th></tr>';

	while($user = mysqli_fetch_array($query))
	{
		$users .= '<tr id="user_tr_' . $user['user_id'] . '"><td><label for="user_radio_' . $user['user_id'] . '">' . $user['user_id'] . '</label></td><td>' . $user['user_is_admin'] . '</td><td><label for="user_radio_' . $user['user_id'] . '">' . $user['user_name'] . '</label></td><td><label for="user_radio_' . $user['user_id'] . '">' . $user['user_email'] . '</label></td><td>' . $user['user_reservation_reminder'] . '</td>	<td><input type="radio" name="user_radio" class="user_radio" id="user_radio_' . $user['user_id'] . '" value="' . $user['user_id'] . '"></td></tr>';
	}

	$users .= '</table>';

	return($users);
}

function reset_user_password($user_id)
{
	$password = random_password();
	$password_encrypted = encrypt_password($password);
	global $dbconfig;
	mysqli_query($dbconfig,"UPDATE " . global_mysqli_users_table . " SET user_password='$password_encrypted' WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	if($user_id == $_SESSION['user_id'])
	{
		return(0);
	}
	else
	{
		return('The password to the user with ID ' . $user_id . ' is now "' . $password . '". The user can now log in and change the password');
	}
}

function change_user_permissions($user_id)
{
	global $dbconfig;
	if($user_id == $_SESSION['user_id'])
	{
		return('<span class="error_span">Sorry, you can\'t use your superuser powers to remove them</span>');
	}
	else
	{
		mysqli_query($dbconfig,"UPDATE " . global_mysqli_users_table . " SET user_is_admin = 1 - user_is_admin WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

		return(1);
	}
}

function delete_user_data($group_id, $data)
{
	global $dbconfig;
	if($user_id == $_SESSION['user_id'] && $data != 'reservations')
	{
		return('<span class="error_span">Sorry, self-destructive behaviour is not accepted</span>');
	}
	else
	{
		if($data == 'reservations')
		{
			mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_reservations_table . " WHERE reservation_group_id='$group_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		}
		elseif($data == 'user')
		{
			mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_users_table . " WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
			mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_reservations_table . " WHERE reservation_group_id='$group_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		}

		return(1);
	}
}

function delete_all($data)
{
	$user_id = $_SESSION['user_id'];
global $dbconfig;
	if($data == 'reservations')
	{
		mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_reservations_table . " WHERE reservation_group_id!='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	}
	elseif($data == 'users')
	{
		mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_users_table . " WHERE user_id!='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_reservations_table . " WHERE reservation_group_id!='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	}
	elseif($data == 'everything')
	{
		mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_users_table . "")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		mysqli_query($dbconfig,"DELETE FROM " . global_mysqli_reservations_table . "")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	}

	return(1);
}

function save_system_configuration($price)
{
	global $dbconfig;
	if(validate_price($price) != true)
	{
		return('<span class="error_span">Price must be a number (use . and not , if you want to use decimals)</span>');
	}
	else
	{
		mysqli_query($dbconfig,"UPDATE " . global_mysqli_configuration_table . " SET price='$price'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	}

	return(1);
}

// User control panel

function get_usage()
{
	$usage = '<table id="usage_table"><tr><th>Reservations</th><th>Cost</th><th>Current price per reservation</th></tr><tr><td>' . count_reservations($_SESSION['user_id']) . '</td><td>' . cost_reservations($_SESSION['user_id']) . ' ' . global_currency . '</td><td>' . global_price . ' ' . global_currency . '</td></tr></table>';
	return($usage);
}

function count_reservations($user_id)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_reservations_table . " WHERE reservation_group_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$count = mysqli_num_rows($query);
	return($count);
}

function cost_reservations($user_id)
{global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_reservations_table . " WHERE reservation_group_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	$cost = 0;

	while($reservation = mysqli_fetch_array($query))
	{
		$cost =+ $cost + $reservation['reservation_price'];	
	}

	return($cost);
}

function get_reservation_reminders()
{
	global $dbconfig;
	$user_id = $_SESSION['user_id'];
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_users_table . " WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$user = mysqli_fetch_array($query);

	if($user['user_reservation_reminder'] == 1)
	{
		$return = '<input type="checkbox" id="reservation_reminders_checkbox" checked="checked">';
	}
	else
	{
		$return = '<input type="checkbox" id="reservation_reminders_checkbox">';
	}

	return($return);
}

function toggle_reservation_reminder()
{
	global $dbconfig;
	$user_id = $_SESSION['user_id'];
	mysqli_query($dbconfig,"UPDATE " . global_mysqli_users_table . " SET user_reservation_reminder = 1 - user_reservation_reminder WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	return(1);
}

function change_user_details($user_name, $user_email, $user_password)
{
	$user_id = $_SESSION['user_id'];
	global $dbconfig;
	if(validate_user_name($user_name) != true)
	{
		return('<span class="error_span">Name must be <u>letters only</u> and be <u>2 to 12 letters long</u>. If your name is longer, use a short version of your name</span>');
	}
	if(validate_user_email($user_email) != true)
	{
		return('<span class="error_span">Email must be a valid email address and be no more than 50 characters long</span>');
	}
	elseif(validate_user_password($user_password) != true && !empty($user_password))
	{
		return('<span class="error_span">Password must be at least 4 characters</span>');
	}
	elseif(user_name_exists($user_name) == true && $user_name != $_SESSION['user_name'])
	{
		return('<span class="error_span">Name is already in use. If you have the same name as someone else, use another spelling that identifies you</span>');
	}
	elseif(user_email_exists($user_email) == true && $user_email != $_SESSION['user_email'])
	{
		return('<span class="error_span">Email is already registered</span>');
	}
	else
	{
		if(empty($user_password))
		{
			mysqli_query($dbconfig,"UPDATE " . global_mysqli_users_table . " SET user_name='$user_name', user_email='$user_email' WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		}
		else
		{
			$user_password = encrypt_password($user_password);

			mysqli_query($dbconfig,"UPDATE " . global_mysqli_users_table . " SET user_name='$user_name', user_email='$user_email', user_password='$user_password' WHERE user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		}

		//mysqli_query($dbconfig,"UPDATE " . global_mysqli_reservations_table . " SET reservation_user_name='$user_name', reservation_user_email='$user_email' WHERE reservation_user_id='$user_id'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

		$_SESSION['user_name'] = $user_name;
		$_SESSION['user_email'] = $user_email;

		$user_password = strip_salt($user_password);

		setcookie(global_cookie_prefix . '_user_email', $user_email, time() + 3600 * 24 * intval(global_remember_login_days));
		setcookie(global_cookie_prefix . '_user_password', $user_password, time() + 3600 * 24 * intval(global_remember_login_days));

		return(1);
	}
}
function check_reservation(){
	global $dbconfig;
	$year=date("Y",time());
	$week=date("W",time());
	$day=date("N",time());
	$query=mysqli_query($dbconfig,"SELECT * FROM ". global_mysqli_reservations_table .",".global_mysqli_group_members_table." WHERE reservation_group_id=member_group_id and member_user_id={$_SESSION['user_id']} and ((reservation_week=$week and reservation_year=$year and reservation_day>=$day) or (reservation_week>$week and reservation_year=$year)or(reservation_year>$year))")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$num=mysqli_num_rows($query);
	return $num;
}
function slot_booked(){
	global $dbconfig;
	$year=date("Y",time());
	$week=date("W",time());
	$day=date("N",time());
	
	$return="";	
	$query=mysqli_query($dbconfig,"SELECT * FROM ". global_mysqli_reservations_table .",".global_mysqli_group_members_table.",".global_mysqli_groups_table.",".global_mysqli_room_details_table." WHERE room_id=reservation_room_id and reservation_group_id=member_group_id and group_id=member_group_id and member_user_id={$_SESSION['user_id']} and ((reservation_week=$week and reservation_year=$year and reservation_day>=$day) or (reservation_week>$week and reservation_year=$year)or(reservation_year>$year))")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	while($num=mysqli_fetch_array($query)){
	$return=$return.'<div class="box_div" style="width:500px;"><div class="box_top_div">Booking Status</div><div class="box_body_div"><center>Slot Booked<br><br>Group Name:'.$num['group_name'].'<br>Week:'.$num['reservation_week'].'<br>Day:'.$num['reservation_day'].'<br>Time:'.$num['reservation_time'].'<br>Room Name:'.$num['room_name'];
	//if($num['group_admin_id']==$_SESSION['user_id'])
	$return=$return.'<br><br><input type="button" class="blue_button deleteBookingButton" id="'.$num['reservation_id'].':'.$num['reservation_week'].':'.$num['reservation_day'].':'.$num['reservation_time'].'" value="Delete Slot"></center></div></div><br><br><br>';
	//else
		//$return=$return.'<br><br>To cancel this booking contact your group admin.';
	$_SESSION['selected_group']=$num['group_id'];}
	return $return;
}
function get_room_details($week,$day,$time){
	global $dbconfig;
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_room_details_table." WHERE room_id NOT IN (SELECT room_id FROM ".global_mysqli_room_details_table.",".global_mysqli_reservations_table." WHERE room_id=reservation_room_id AND reservation_week='$week' and reservation_time='$time' and reservation_day='$day')")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$return="<div class='box_div' id ='room_details_div'><div class='box_top_div'><div id='room_details_top_left_div'><a href='#'>Start</a> &gt; Rooms Available</div><div id='room_details_top_center_div'>Time:".$time."</div><div id='room_details_top_right_div'>Date ".date("d-M-Y",strtotime(global_year."-W".$week."-".$day))."</div></div><div class='box_body_div'><p>Group: ".group_selected_name()."</p>";
	$i=0;
	while($room=mysqli_fetch_array($query))
	{
		if($room['STATUS']=='Y')
		$return=$return.'<input type="button" class="blue_button roomBookButton" id="'.$room['room_id'].':'.$week.':'.$day.':'.$time.'" value="'.$room['room_code'].": ".$room['room_name'].'">&nbsp;&nbsp;&nbsp;';
		$i++;
		if($i==4){
			$return=$return."<br><br>";
			$i=0;
		}
	}
	$return=$return."</div></div>";
	return $return;
}
function list_reservations()
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_groups_table . ",".global_mysqli_reservations_table.",".global_mysqli_room_details_table." WHERE group_id=reservation_group_id AND reservation_room_id=room_id ORDER BY reservation_year DESC,reservation_week DESC,reservation_time DESC,reservation_room_id,reservation_check_in DESC")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	$users = '<table id="users_table"><tr><th>ID</th><th>Name</th><th>Reservation</th><th>Room</th><th>Floor</th><th>Check In Time</th><th>Check Out Time</th><th></th></tr>';

	while($user = mysqli_fetch_array($query))
	{
		
		$time="Time:".$user['reservation_time']."<br>".date("d-M-Y", strtotime($user['reservation_year']."W".$user['reservation_week']."-".$user['reservation_day']));
		$users .= '<tr id="user_tr_' . $user['group_id'] . '"><td><label for="user_radio_' . $user['group_id'] . '">' . $user['group_id'] . '</label></td><td><label for="user_radio_' . $user['group_id'] . '">' . $user['group_name'] . '</label></td><td>' . $time . '</td>	<td>'.$user['room_name'].'</td><td>'.$user['Floor'].'</td><td>'.$user['reservation_check_in'].'</td><td>'.$user['reservation_check_out'].'</td><td><input type="radio" class=" blue_button reservation_details_radio" id="'.$user['reservation_id'].'" value="'.$user['reservation_id'].'" name="reservation_details_radio"></td></tr>';
		}
	

	$users .= '</table><br><br><input type="button" class=" blue_button " id="reservation_details_button" value="Details">
	&nbsp;&nbsp;&nbsp;<input type="button" class=" blue_button " id="reservation_check_in_button" value="Mark Check In">
	&nbsp;&nbsp;&nbsp;<input type="button" class=" button " id="reservation_check_out_button" value="Mark Check Out">';

	return($users);
}

function list_reservations_floor($floor)
{
	global $dbconfig;
	$query = mysqli_query($dbconfig,"SELECT * FROM " . global_mysqli_groups_table . ",".global_mysqli_reservations_table.",".global_mysqli_room_details_table." WHERE group_id=reservation_group_id AND reservation_room_id=room_id ORDER BY reservation_year DESC,reservation_week DESC,reservation_time DESC,reservation_room_id,reservation_check_in DESC")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');

	$users = '<table id="users_table"><tr><th>ID</th><th>Name</th><th>Reservation</th><th>Room</th><th>Floor</th><th>Check In Time</th><th>Check Out Time</th><th></th></tr>';

	while($user = mysqli_fetch_array($query))
	{
		if ($user['Floor'] != $floor) continue;
		
		$time="Time:".$user['reservation_time']."<br>".date("d-M-Y", strtotime($user['reservation_year']."W".$user['reservation_week']."-".$user['reservation_day']));
		$users .= '<tr id="user_tr_' . $user['group_id'] . '"><td><label for="user_radio_' . $user['group_id'] . '">' . $user['group_id'] . '</label></td><td><label for="user_radio_' . $user['group_id'] . '">' . $user['group_name'] . '</label></td><td>' . $time . '</td>	<td>'.$user['room_name'].'</td><td>'.$user['Floor'].'</td><td>'.$user['reservation_check_in'].'</td><td>'.$user['reservation_check_out'].'</td><td><input type="radio" class=" blue_button reservation_details_radio" id="'.$user['reservation_id'].'" value="'.$user['reservation_id'].'" name="reservation_details_radio"></td></tr>';
		}
	

	$users .= '</table><br><br><input type="button" class=" blue_button " id="reservation_details_button" value="Details">
	&nbsp;&nbsp;&nbsp;<input type="button" class=" blue_button " id="reservation_check_in_button" value="Mark Check In">
	&nbsp;&nbsp;&nbsp;<input type="button" class=" button " id="reservation_check_out_button" value="Mark Check Out">';

	return($users);
}


function list_branches_option(){
	global $dbconfig;
	$return="";
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_branches_table)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	while($result=mysqli_fetch_array($query))
	{
		if($result['STATUS']=="Y")
		$return=$return."<option value='".$result['branch_id']."'>".$result['branch_code']."-".$result['branch_name'].'</option>';
	}
	return $return;
}
function verify_account($token,$hash)
{
	global $dbconfig;
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_users_table." WHERE user_hash='$hash'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	while($result=mysqli_fetch_array($query))
	{
		if(md5($result['user_email'])==$token)
		{
			$query=mysqli_query($dbconfig,"UPDATE ".global_mysqli_users_table." SET user_hash=
			'' , user_activated=1 WHERE user_id='{$result['user_id']}'");
			return 1;
		}
	}
	return 0;
}
function create_group($group_name){
	global $dbconfig;
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_groups_table." where group_name='$group_name'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	if(mysqli_num_rows($query)!=0)
		return "Group name already taken please use another name.";
		else{
	$query=mysqli_query($dbconfig,"INSERT INTO ".global_mysqli_groups_table." (group_name,group_admin_id) VALUES ('$group_name','{$_SESSION['user_id']}')")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_groups_table." WHERE group_name='$group_name'")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		$result=mysqli_fetch_array($query);
	$query=mysqli_query($dbconfig,"INSERT INTO ".global_mysqli_group_members_table." (member_group_id,member_user_id,member_status) VALUES ({$result['group_id']},{$_SESSION['user_id']},1)")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	if($query)
		return 1;
		}
	
}
function show_groups(){
	global $dbconfig;
	$return="";
	$query=mysqli_query($dbconfig,"SELECT group_id,group_name FROM ".global_mysqli_groups_table.",".global_mysqli_group_members_table." WHERE group_id=member_group_id and member_user_id={$_SESSION['user_id']} and member_status!=0")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	if(mysqli_num_rows($query)!=0)
	{
		$return=$return.'<table id="group_table"><tr><th>Group ID</th><th>Group Name</th><th>Group Members</th><th></th></tr>';
	while($result=mysqli_fetch_array($query)){
		$q=mysqli_query($dbconfig,"SELECT count(*) from ".global_mysqli_group_members_table." where member_group_id={$result['group_id']} and member_status!=0");
		$q2=mysqli_query($dbconfig,"SELECT count(*) from ".global_mysqli_group_members_table." where member_group_id={$result['group_id']} and member_status=0");
		$result2=mysqli_fetch_array($q);
		$result3=mysqli_fetch_array($q2);
		$return=$return."<tr><td>".$result['group_id']."</td><td>".$result['group_name']."</td><td>".$result2['count(*)'];
		if($result3['count(*)']>0)
			$return=$return.' ('.$result2['count(*)'].' pending)';
		$return=$return.'</td><td><input type="radio" name="group_radio" class="group_radio" id="group_radio_' . $result['group_id'] . '" value="' . $result['group_id'] . '"></td></tr>';
	}
	$return=$return.'</table><input type="button" class="blue_button" id="group_details_button" value="Group Details">
	&nbsp;&nbsp;
	<input type="button" class="red_button" id="delete_group_button" value="Delete Group">
	&nbsp;&nbsp;
	<input type="button" class="blue_button" id="select_group_button" value="Select Group">
	<br><br>
	';
	}
	$return=$return.'
	<label for="group_name_input">Group Name:</label><br><input type="text" id="group_name_input" autocapitalize="off">
	<br><br>
	<input type="button" class="blue_button" id="create_group_button" value="Create Group">';
	return $return;
}
function delete_group($group_id){
	global $dbconfig;
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_groups_table." WHERE group_id=$group_id AND group_admin_id={$_SESSION['user_id']}")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
	if(mysqli_num_rows($query)==1)
	{
		$query=mysqli_query($dbconfig,"DELETE FROM ".global_mysqli_groups_table." WHERE group_id=$group_id")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		$query=mysqli_query($dbconfig,"DELETE FROM ".global_mysqli_group_members_table." WHERE member_group_id=$group_id")or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
		if($query)
		{
			unset($_SESSION['selected_group']);
			return 1;
		}
	}
	else
		return 'You can only delete groups that you\'ve created'; 
}
function show_invitations(){
	global $dbconfig;
	$return='<div class="box_top_div">Invitations</div><div class="box_body_div"><div id="invitation_list">';
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_group_members_table.",".global_mysqli_groups_table.",".global_mysqli_users_table." WHERE  group_id=member_group_id and group_admin_id=user_id and member_user_id={$_SESSION['user_id']} and member_status=0");
	while($invite=mysqli_fetch_array($query))
	{
		$return=$return.'<label for="invite'.$invite['member_id'].'">'.$invite['user_name'].' has invited you to join '.$invite['group_name'].':&nbsp;&nbsp;&nbsp;</label><input type="button" class="blue_button accept_invite_button" id="invite_accept_button:'.$invite['member_id'].'" value="Accept Invite">
	&nbsp;&nbsp;
	<input type="button" class="red_button reject_invite_button" id="reject_invite_button:'.$invite['member_id'].'" value="Reject Invite"><br><br>';
		
	}
	$return=$return.'</div></div>';
	return $return;
}
function group_details($group_id){
	global $dbconfig;
	$return='<div class="box_top_div"><a href="">Invitations</a>/Group Details</div><div class="box_body_div"><div id="invitation_list"><table id="group_table"><tr><th>User Roll Number</th><th>User Name</th><th>Status</th><th></th></tr>';
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_group_members_table.",".global_mysqli_groups_table.",".global_mysqli_users_table." WHERE  group_id=member_group_id and member_user_id=user_id and group_id=$group_id");
	while($member=mysqli_fetch_array($query))
	{
		$return=$return.'<tr><td>'.$member['user_roll_no'].'</td><td>'.$member['user_name'].'</td><td>';
		if($member['member_status']==0)
		$return=$return. 'Invite Sent';
		elseif($member['member_status']==1 && $member['group_admin_id']==$member['user_id'])
			$return=$return. 'Admin';
		else
			$return=$return. 'Member';
		$return=$return. '</td><td><input type="radio" name="group_details_radio" class="group_details_radio" id="group_details_radio_' . $member['member_id'] . '" value="' . $member['member_id'] . ':'.$member['group_id'].'"></td></tr>';
		
	}
	$query2=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_groups_table." where group_id=$group_id and group_admin_id={$_SESSION['user_id']}");
	$return=$return.'</table><br><br>';
	if(mysqli_num_rows($query)<8 && mysqli_num_rows($query2)==1)
		$return=$return.'
	<input type="button" class="red_button" id="delete_member_button" value="Delete Member"><br><br>
	<label for="invite_roll_no_input">Roll No:</label>&nbsp;&nbsp;<input type="text" id="invite_roll_no_input" autocapitalize="off">
	<br><br><label for="invite_email_input">Email:</label>&nbsp;&nbsp;<input type="text" id="invite_email_input" autocapitalize="off">
	<br><br>
	<input type="button" class="blue_button invite_member_button" id="invite_member_button:'.$group_id.'" value="Invite User">';
	$return=$return.'
	</div></div>';
	return $return;
}
function invite_user($group_id,$email,$rollno){
	if(!validate_user_email($email))
	{
		return 'Email format incorrect.';
	}
	

	else
	{
		global $dbconfig;
		$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_users_table." WHERE user_roll_no=$rollno and user_email='$email'");
		if(mysqli_num_rows($query)!=1){
			return "Incorect Email and Roll No. combination.";
		}
		else{
			$result=mysqli_fetch_array($query);
			$id=$result['user_id'];
			$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_group_members_table." WHERE member_user_id=$id and member_group_id=$group_id");
			if(mysqli_num_rows($query)!=0)
			{
				$result=mysqli_fetch_array($query);
				if($result['member_status']==0)
					return "Invite already sent.";
				else
					return 'User is already a member.';
			}
			else
			{
				$query=mysqli_query($dbconfig,"INSERT INTO ".global_mysqli_group_members_table." (member_group_id,member_user_id) VALUES ($group_id,$id)");
				if($query)
				{$query1=mysqli_query($dbconfig,'SELECT * FROM '.global_mysqli_groups_table.",".global_mysqli_users_table." WHERE group_admin_id=user_id and group_id=$group_id");
				$result=mysqli_fetch_array($query1);
				
$headers = 'From:gdroombooking@thapar.edu' . "\r\n"; // Set from headers
require_once "Mail.php";

$from = 'prakharsingh@gmail.com';
$to = $email;
$subject = 'Signup | Verification'; // Give the email a subject
$body = $result['user_name'].' has invited you to join '.$result['group_name'].'.
 
 
 
 
Please click this link to accept or reject this invite:
http://146.148.48.62/roombook/phpmyreservation/; // Our message above including the link';

$headers = array(
    'From' => $from,
    'To' => $to,
    'Subject' => $subject
);

$smtp = Mail::factory('smtp', array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => '465',
        'auth' => true,
        'username' => 'prakharsingh13@gmail.com',
        'password' => 'jangipur13'
    ));

$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail)) {
      echo("<scrpit type='text/javascript'>alert('" . $mail->getMessage() . "')</script>");}
		
					return 1;
			}
		}}
				
		}
	}
function accept_invite($member_id)
{
	global $dbconfig;
	$query=mysqli_query($dbconfig,"UPDATE ".global_mysqli_group_members_table." SET member_status=1 WHERE member_id=$member_id");
	if ($query)
		return 1;
}
function reject_invite($member_id)
{
	global $dbconfig;
	$query=mysqli_query($dbconfig,"DELETE FROM ".global_mysqli_group_members_table." WHERE member_id=$member_id");
	if ($query)
		return 1;
}
function select_group($group_id)
{
	unset ($_SESSION['selected_group']);
	global $dbconfig;
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_groups_table.",".global_mysqli_group_members_table." WHERE group_id=$group_id AND member_group_id=group_id and member_user_id={$_SESSION['user_id']}");
	if(mysqli_num_rows($query)==1)
	{
		$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_group_members_table." WHERE member_group_id=$group_id and member_status!=0");
		if(mysqli_num_rows($query)<minimum_members())
			return 'Minimum '.minimum_members()." members should be there in a group to book a slot";
		else{
		$_SESSION['selected_group']=$group_id;
		return 'Group Selected Successfully.';
		}
	}
	else
	{
		return 'Only group members can select groups.';
	}
	
}
function minimum_members(){
	return 2;
}
function group_selected_name(){
	global $dbconfig;
	if(isset($_SESSION['selected_group']))
	{
		$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_groups_table." WHERE group_id={$_SESSION['selected_group']}");
		if(mysqli_num_rows($query)>0){
		$result=mysqli_fetch_array($query);
			return $result['group_name'];
		}
		else
			return 'Not Found';
	}
}
function delete_member($member_id){
	global $dbconfig;
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_group_members_table.",".global_mysqli_groups_table." WHERE member_id=$member_id and group_id=member_group_id");
	$result=mysqli_fetch_array($query);
	if($result['group_admin_id']==$_SESSION['user_id'] && $result['member_user_id']!=$result['group_admin_id'])
	{
		$query=mysqli_query($dbconfig,"DELETE FROM ".global_mysqli_group_members_table." WHERE member_id=$member_id");
		unset($_SESSION['selected_group']);
		return 1;
	}
	elseif($result['member_user_id']==$result['group_admin_id']){
		return "You can not delete yourself from a group that you created.";
	}
	else
	{
		return "You can not delete someone from a group that you haven't created.";
	}
	
}
function reservation_details($reservation_id){
	global $dbconfig;
	$return='<div id="group_details" class="box_top_div">Group Details</div><div id="group_details_body" class="box_body_div"><table id="reservation_table"><tr><th>Name</th><th>Roll No.</th><th>Branch</th><th>Email</th><th>Mobile Number</th></tr>';
	$query=mysqli_query($dbconfig,"SELECT * FROM ".global_mysqli_reservations_table.",".global_mysqli_group_members_table.",".global_mysqli_users_table.",".global_mysqli_branches_table." WHERE reservation_id=$reservation_id and user_id=member_user_id and reservation_group_id=member_group_id and branch_id=user_branch_id");
	while($result=mysqli_fetch_array($query)){
		$return=$return.'<tr><td>'.$result['user_name'].'</td><td>'.$result['user_roll_no'].'</td><td>'.$result['branch_code'].'</td><td>'.$result['user_email'].'</td><td>'.$result['user_mobile_no'].'</td></tr>';
	}
	$return=$return.'</table></div>';
	return $return;
}
function reservation_check_in($reservation_id){
	global $dbconfig;
	$query=mysqli_query($dbconfig,"UPDATE ".global_mysqli_reservations_table." SET reservation_check_in=CURRENT_TIMESTAMP where reservation_id=$reservation_id");
	if($query)
		return 1;
	else
		return 0;
}
function reservation_check_out($reservation_id){
	global $dbconfig;
	$query=mysqli_query($dbconfig,"UPDATE ".global_mysqli_reservations_table." SET reservation_check_out=CURRENT_TIMESTAMP where reservation_id=$reservation_id");
	if($query)
		return 1;
	else
		return 0;
}
function change_detail($detail,$room_id,$state){
	if($_SESSION['user_is_admin']){
	global $dbconfig;
	$query=mysqli_query($dbconfig,"UPDATE ".global_mysqli_room_details_table." SET $detail='$state' WHERE room_id=$room_id");
	if($query)
		return 'Detail altered.';
	}
	else
		return;
}
?>