<?php

// About

define('global_project_name', 'GD Room Booking');
define('global_project_version', '1.0');
define('global_project_website', '');

// Include necessary files


include_once('functions.php');

// mysqli

$dbconfig=mysqli_connect(global_mysqli_server, global_mysqli_user, global_mysqli_password,global_mysqli_database)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
//mysqli_select_db(global_mysqli_database)or die('<span class="error_span"><u>mysqli error:</u> ' . htmlspecialchars(mysqli_error($dbconfig)) . '</span>');
mysqli_set_charset($dbconfig,'utf8');

define('global_mysqli_configuration_table', 'phpmyreservation_configuration');
define('global_mysqli_users_table', 'phpmyreservation_users');
define('global_mysqli_reservations_table', 'phpmyreservation_reservations');
define('global_mysqli_branches_table','phpmyreservation_branches');
define('global_mysqli_groups_table','phpmyreservation_groups');
define('global_mysqli_group_members_table','phpmyreservation_group_members');


// Cookies

define('global_cookie_prefix', 'phpmyreservation');

// Start session

session_start();

// Configuration

define('global_price', get_configuration('price'));

// Date

define('global_year', date('Y'));
define('global_week_number', ltrim(date('W'), '0'));
define('global_day_number', date('N'));
define('global_day_name', date('l'));

// User agent

if(isset($_SERVER['HTTP_USER_AGENT']))
{
	define('global_ua', $_SERVER['HTTP_USER_AGENT']);
}
else
{
	define('global_ua', 'CLI');
}

if(strstr(global_ua, 'iPhone') || strstr(global_ua, 'iPod') || strstr(global_ua, 'iPad') || strstr(global_ua, 'Android'))
{
	if(strstr(global_ua, 'AppleWebKit'))
	{
		if(strstr(global_ua, 'OS 5_') || strstr(global_ua, 'Android 2.3') || strstr(global_ua, 'Android 3') || strstr(global_ua, 'Android 4'))
		{
			define('global_css_animations', '1');
		}
	}
}
//elseif(strstr(global_ua, 'Chrome') || strstr(global_ua, 'Safari') && strstr(global_ua, 'Macintosh') || strstr(global_ua, 'Safari') && strstr(global_ua, 'Windows') || strstr(global_ua, 'Firefox') || strstr(global_ua, 'Opera') || strstr(global_ua, 'MSIE 10'))
//{
//	define('global_css_animations', '1');
//}
//else
//{
//	define('global_css_animations', '0');
//}
define('global_css_animations', '1');
// Check stuff

if(strlen(global_salt) != 9)
{
	echo '<script type="text/javascript">window.location.replace(\'error.php?error_code=1\');</script>';
	exit;
}

if(isset($_GET['day_number']))
{
	echo date('N');
}
elseif(isset($_GET['latest_version']))
{
	$latest_version_url = global_project_website . 'latest-version.php?version=' . urlencode(global_project_version);
	$latest_version_url_context = stream_context_create(array('http'=>array('timeout'=>5)));
	@$latest_version = file_get_contents($latest_version_url, false, $latest_version_url_context);
	$latest_version = trim($latest_version);

	if(empty($latest_version) || !is_numeric($latest_version))
	{
		echo '<span class="error_span">Could not get latest version</span>';
	}
	else
	{
		echo 'Latest version: ' . $latest_version;
	}
}

?>
