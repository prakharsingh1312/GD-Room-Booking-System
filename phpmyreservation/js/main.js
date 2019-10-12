// Show pages
function show_reservation_details(group_id){
	page_load('see_reservation');
	div_hide('#reservation_details_div');
$.post('see_reservations.php?',{group_id:group_id}, function(data) { $('#reservation_details_div').html(data); div_fadein('#content_div'); page_loaded('see_reservation'); });
}
function showrooms(week,day,time)
{
	page_load('book');
	div_hide('#content_div');
$.post('reservation.php?book',{week:week,day:day,time:time}, function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded('book'); });
	
}
function showabout()
{
	page_load();
	div_hide('#content_div');
	$.get('about.php', function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded('about'); });
}
function showgroups()
{
	page_load();
	div_hide('#group_list');
	$.get('reservation.php?show_groups', function(data) { $('#group_list').html(data); div_fadein('#group_list'); page_loaded('reservations');
		}
		  );
}
function showgroupdetails(group_id){
	$.post('reservation.php?group_details',{group_id:group_id},function(data){
				page_load();
				div_hide('#invitation_div');
				$('#invitation_div').html(data); 
				div_fadein('#invitation_div');
				page_loaded('reservations');});
}
function showinvitations(){
	page_load();
	div_hide('#invitation_div');
	$.get('reservation.php?show_invitations',function(data){
		$('#invitation_div').html(data); 
				div_fadein('#invitation_div');
				page_loaded('reservations');});
	}

function showlogin()
{
	page_load();
	div_hide('#content_div');

	$.get('login.php', function(data)
	{
		$('#content_div').html(data); 
		div_fadein('#content_div');
		page_loaded();

		var user_email = $('#user_email_input').val();
		var user_password = $('#user_password_input').val();

		if(user_email != '' && user_password != '')
		{
			setTimeout(function() { $('#login_form').submit(); }, 250);
		}
		else
		{
			input_focus('#user_email_input');
		}
	});
}

function shownew_user()
{
	page_load();
	div_hide('#content_div');
	$.get('login.php?new_user', function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded(); input_focus('#user_name_input'); });
	
}
function showallreservations()
{
	page_load();
	div_hide('#content_div');
	$.get('see_reservations.php', function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded();  });
	
}

function showforgot_password()
{
	page_load();
	div_hide('#content_div');
	$.get('login.php?forgot_password', function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded(); });
	
}

function showreservations()
{
	page_load('reservation');
	div_hide('#content_div');

	$.get('reservation.php', function(data)
	{
		$('#content_div').html(data);
		div_fadein('#content_div');

		$.get('reservation.php?week='+global_week_number, function(data)
		{
			$('#reservation_table_div').html(data).slideDown('slow', function() { setTimeout(function() { div_fadein('#reservation_table_div'); }, 250); });
			page_loaded();
		});
	});
}

function showweek(week, option)
{
	if(week == 'next')
	{
		var week = parseInt($('#week_number_span').html()) + 1;
	}
	else if(week == 'previous')
	{
		var week = parseInt($('#week_number_span').html()) - 1;
	}
	else
	{
		var week = parseInt(week);
	}

	if(isNaN(week))
	{
		notify('Invalid week number', 4);
	}
	else
	{
		if(week < 1)
		{
			var week = 52;
		}
		else if(week > 52)
		{
			var week = 1;
		}

		page_load('week');
		div_hide('#reservation_table_div');

		$.get('reservation.php?week='+week, function(data)
		{
			$('#reservation_table_div').html(data);
			$('#week_number_span').html(week);
			div_fadein('#reservation_table_div');
			page_loaded('week');

			if(week != global_week_number)
			{
				$('#reservation_today_button').css('visibility', 'visible');
			}

			if(option == 'today')
			{
				setTimeout(function() { $('#today_span').animate({ opacity: 0 }, 250, function() { $('#today_span').animate({ opacity: 1 }, 250);  }); }, 500);
			}
		});
	}
}

function showcp()
{
	page_load();
	div_hide('#content_div');
	$.get('cp.php', function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded(); });
}

function showhelp()
{
	page_load();
	div_hide('#content_div');
	$.get('help.php', function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded(); });
}
function showroom()
{
	page_load();
	div_hide('#content_div');
	$.get('room.php', function(data) { $('#content_div').html(data); div_fadein('#content_div'); page_loaded(); });
}

// Page load

function page_load(page)
{
	// All
	setTimeout(function()
	{
		if($('#content_div').css('opacity') == 0)
		{
			notify('Loading...', 300);
		}
	}, 500);

	// Individual
	if(page == 'reservation')
	{
		setTimeout(function()
		{
			if($('#reservation_table_div').is(':hidden'))
			{
				notify('Loading...', 300);
			}
		}, 500);
	}	
	else if(page == 'week')
	{
		setTimeout(function()
		{
			if($('#reservation_table_div').css('opacity') == 0)
			{
				notify('Loading...', 300);
			}
		}, 500);
	}
}

function page_loaded(page)
{
	// All
	$.get('main.php?day_number', function(data)
	{
		if(data != global_day_number)
		{
			notify('Day have changed. Refreshing...', '300');
			setTimeout(function() { window.location.replace('.'); }, 2000);
		}
	});

	setTimeout(function()
	{
		if($('#notification_inner_cell_div').is(':visible') && $('#notification_inner_cell_div').html() == 'Loading...')
		{
			notify();
		}
	}, 1000);

	read_reservation_details();

	// Individual
	if(page == 'about')
	{
		$('#about_latest_version_p').html('<img src="img/loading.gif" alt="Loading"> Getting latest version...');

		setTimeout(function()
		{
			$.get('main.php?latest_version', function(data)
			{
				if($('#about_latest_version_p').length)
				{
					$('#about_latest_version_p').html(data);
				}
			});
		}, 1000);
	}
}

// Login

function login()
{
	var user_email = $('#user_email_input').val();
	var user_password = $('#user_password_input').val();

	$('#login_message_p').html('<img src="img/loading.gif" alt="Loading"> Logging in...').slideDown('fast');

	var remember_me_checkbox = $('#remember_me_checkbox').prop('checked');

	if(remember_me_checkbox)
	{
		var user_remember = 1;
	}
	else
	{
		var user_remember = 0;
	}

	$.post('login.php?login', { user_email: user_email, user_password: user_password, user_remember: user_remember }, function(data)
	{
		if(data == 1)
		{
			input_focus();
			setTimeout(function() { window.location.replace('.'); }, 1000);
		}
		else
		{
			if(data == '')
			{
				$('#login_message_p').html('<span class="error_span">Wrong email and/or password or Email not verified.</span>');
				$('#user_email_input').val('');
				$('#user_password_input').val('');
				input_focus('#user_email_input');
			}
			else
			{
				$('#login_message_p').html(data);
			}
		}
	});
}

function logout()
{
	notify('Logging out...', 300);
	$.get('login.php?logout', function(data) { setTimeout(function() { window.location.replace('.'); }, 1000); });
}

function create_user()
{
	var user_name = $('#user_name_input').val();
	var user_email = $('#user_email_input').val();
	var user_password = $('#user_password_input').val();
	var user_password_confirm = $('#user_password_confirm_input').val();
	var user_roll_no=$('#user_roll_no_input').val();
	var user_branch=$('#user_branch_input').val();
	var user_mobile_no=$('#user_mobile_no_input').val();
	var regexPattern=new RegExp(/^[0-9-+]+$/);
	if($('#user_secret_code_input').length)
	{
		var user_secret_code =  $('#user_secret_code_input').val();
	}
	else
	{
		var user_secret_code = '';
	}

	if(user_password != user_password_confirm)
	{
		$('#new_user_message_p').html('<span class="error_span">Passwords do not match</span>').slideDown('fast');
		$('#user_password_input').val('');
		$('#user_password_confirm_input').val('');
		input_focus('#user_password_input');
	}
	else if(user_email.indexOf('@thapar.edu') ==-1)
	{
		$('#new_user_message_p').html('<span class="error_span">Please use a thapar.edu mail.</span>').slideDown('fast');
		$('#user_email_input').val('');
		
		input_focus('#user_email_input');
	}
	else if(!regexPattern.test(user_mobile_no))
	{
		$('#new_user_message_p').html('<span class="error_span">Mobile No. should contain numbers only.</span>').slideDown('fast');
		$('#user_mobile_no_input').val('');
		
		input_focus('#user_mobile_no_input');
	}
	else if(user_mobile_no.length!=10)
	{
		$('#new_user_message_p').html('<span class="error_span">Mobile No. should have 10 digits.</span>').slideDown('fast');
		$('#user_mobile_no_input').val('');
		
		input_focus('#user_mobile_no_input');
	}
	else if(user_roll_no.length!=9)
	{
		$('#new_user_message_p').html('<span class="error_span">Roll No. should have 9 digits.</span>').slideDown('fast');
		$('#user_roll_no_input').val('');
		
		input_focus('#user_roll_no_input');
	}
	else if(!regexPattern.test(user_roll_no))
	{
		$('#new_user_message_p').html('<span class="error_span">Roll No. should contain numbers only.</span>').slideDown('fast');
		$('#user_roll_no_input').val('');
		
		input_focus('#user_roll_no_input');
	}
	else
	{
		$('#new_user_message_p').html('<img src="img/loading.gif" alt="Loading"> Creating user...').slideDown('fast');

		$.post('login.php?create_user', { user_name: user_name, user_email: user_email, user_password: user_password, user_secret_code: user_secret_code,user_roll_no:user_roll_no,user_branch:user_branch,user_mobile_no:user_mobile_no}, function(data)
		{
			if(data == 1)
			{
				alert('Your account has been created. A verification link has been sent to your email. You need to verify your email to be able to login.');

				setTimeout(function()
				{
					$('#new_user_message_p').html('User created successfully! Logging in... <img src="img/loading.gif" alt="Loading">');
					setTimeout(function() { window.location.replace('#login'); }, 2000);
				}, 1000);
			}
			else
			{
				input_focus();
				$('#new_user_message_p').html(data);
			}
		});
	}
}

// Reservation

function toggle_reservation_time(id, week, day, time, room_id)
{
	if(session_user_is_admin == '1')
	{
		if(week < global_week_number || week == global_week_number && day < global_day_number)
		{
			notify('You are reserving back in time. You can do that because you\'re an admin', 4);
		}
		else if(week > global_week_number + global_weeks_forward)
		{
			notify('You are reserving more than '+global_weeks_forward+' weeks forward in time. You can do that because you\'re an admin', 4);
		}
	}

	var user_name = $(id).html();
	var book_confirm=confirm('Proceed to book this slot?');
	if(book_confirm==true)
	{
		$(id).html('Wait...'); 

		$.post('reservation.php?make_reservation', { week: week, day: day, time: time,room_id:room_id }, function(data) 
		{
			if(data == 1)
			{
				location.reload(true);
			}
			else
			{
				notify(data, 4);
				setTimeout(function() { read_reservation(id, week, day, time); }, 2000);			
			}
		});
	}
	else
	{
		if(offclick_event == 'mouseup' || from == 'details')
		{
			if(user_name == 'Wait...')
			{
				notify('One click is enough', 4);
			}
			else if(user_name == session_user_name || session_user_is_admin == '1')
			{
				if(user_name != session_user_name && session_user_is_admin == '1')
				{
					var delete_confirm = confirm('This is not your reservation, but because you\'re an admin you can remove other users\' reservations. Are you sure you want to do this?');
				}
				else
				{
					var delete_confirm = confirm('Are you sure you want to cancel this booking?');
				}

				if(delete_confirm)
				{
					$(id).html('Wait...');

					$.post('reservation.php?delete_reservation', { week: week, day: day, time: time }, function(data)
					{
						if(data == 1)
						{
							setTimeout(function() { read_reservation(id, week, day, time); }, 1000);
						}
						else
						{
							notify(data, 4);
							setTimeout(function() { read_reservation(id, week, day, time); }, 2000);
						}
					});
				}
			}
			else
			{
				notify('You can\'t remove other users\' reservations', 2);
			}

			if($('#reservation_details_div').is(':visible'))
			{
				read_reservation_details();
			}
		}
	}
}

function read_reservation(id, week, day, time)
{
	$.post('reservation.php?read_reservation', { week: week, day: day, time: time }, function(data) { $(id).html(data); });
}

function read_reservation_details(id, week, day, time)
{
	if(typeof id != 'undefined' && $(id).html() != '' && $(id).html() != 'Wait...')
	{
		if($('#reservation_details_div').is(':hidden'))
		{
			var position = $(id).position();
			var top = position.top + 50;
			var left = position.left - 100;

			$('#reservation_details_div').html('Getting details...');
			$('#reservation_details_div').css('top', top+'px').css('left', left+'px');
			$('#reservation_details_div').fadeIn('fast');

			reservation_details_id = id;
			reservation_details_week = week;
			reservation_details_day = day;
			reservation_details_time = time;

			$.post('reservation.php?read_reservation_details', { week: week, day: day, time: time }, function(data)
			{
				setTimeout(function()
				{
					if(data == 0)
					{
						$('#reservation_details_div').html('This reservation no longer exists. Wait...');
						
						setTimeout(function()
						{
							if($('#reservation_details_div').is(':visible') && $('#reservation_details_div').html() == 'This reservation no longer exists. Wait...')
							{
								read_reservation(reservation_details_id, reservation_details_week, reservation_details_day, reservation_details_time);
								read_reservation_details();
							}
						}, 2000);
					}
					else
					{
						$('#reservation_details_div').html(data);

						if(offclick_event == 'touchend')
						{
							if($(reservation_details_id).html() == session_user_name || session_user_is_admin == '1')
							{
								var delete_link_html = '<a href="." onclick="toggle_reservation_time(reservation_details_id, reservation_details_week, reservation_details_day, reservation_details_time, \'details\'); return false">Delete</a> | ';
							}
							else
							{
								var delete_link_html = '';
							}

							$('#reservation_details_div').append('<br><br>'+delete_link_html+'<a href="." onclick="read_reservation_details(); return false">Close this</a>');
						}
					}
				}, 500);
			});
		}
	}
	else
	{
		$('div#reservation_details_div').fadeOut('fast');
	}
}

// Admin control panel

function list_users()
{
	$.get('cp.php?list_users', function(data) { $('#users_div').html(data); });
}
function list_reservations()
{
	$.get('cp.php?list_reservations',function(data){$('#system_configuration_form').html(data);});
}

function reset_user_password()
{
	if(typeof $(".user_radio:checked").val() !='undefined')
	{
		var user_id = $(".user_radio:checked").val();

		$('#user_administration_message_p').html('<img src="img/loading.gif" alt="Loading"> Resetting password...').slideDown('fast');

		$.post('cp.php?reset_user_password', { user_id: user_id }, function(data)
		{
			if(data == 0)
			{
				$('#user_administration_message_p').html('<span class="error_span">You can change your password at the bottom of this page</span>').slideDown('fast');
			}
			else
			{
				setTimeout(function() { $('#user_administration_message_p').html(data); }, 1000);
			}
		});
	}
	else
	{
		$('#user_administration_message_p').html('<span class="error_span">You must pick a user</span>').slideDown('fast');
	}
}

function change_user_permissions()
{
	if(typeof $(".user_radio:checked").val() !='undefined')
	{
		var user_id = $(".user_radio:checked").val();

		$('#user_administration_message_p').html('<img src="img/loading.gif" alt="Loading"> Changing permissions...').slideDown('fast');

		$.post('cp.php?change_user_permissions', { user_id: user_id }, function(data)
		{
			if(data == 1)
			{
				setTimeout(function()
				{
					list_users();
					$('#user_administration_message_p').html('Permissions changed successfully. The user must re-login to get the new permissions');
				}, 1000);
			}
			else
			{
				$('#user_administration_message_p').html(data);
			}
		});
	}
	else
	{
		$('#user_administration_message_p').html('<span class="error_span">You must pick a user</span>').slideDown('fast');
	}
}

function delete_user_data(delete_data)
{
	if(typeof $(".user_radio:checked").val() !='undefined')
	{
		var delete_confirm = confirm('Are you sure?');

		if(delete_confirm)
		{
			var user_id = $(".user_radio:checked").val();

			$('#user_administration_message_p').html('<img src="img/loading.gif" alt="Loading"> Deleting...').slideDown('fast');

			$.post('cp.php?delete_user_data', { user_id: user_id, delete_data: delete_data }, function(data)
			{
				if(data == 1)
				{
					setTimeout(function()
					{
						$('#user_administration_message_p').slideUp('fast', function()
						{
							if(delete_data == 'reservations')
							{
								list_users();
								get_usage();
							}
							else if(delete_data == 'user')
							{
								list_users();
							}
						});
					}, 1000);
				}
				else
				{
					$('#user_administration_message_p').html(data);
				}
			});
		}
	}
	else
	{
		$('#user_administration_message_p').html('<span class="error_span">You must pick a user</span>').slideDown('fast');
	}
}

function delete_all(delete_data)
{
	if(delete_data == 'reservations')
	{
		var delete_confirm = confirm('Are you sure you want to delete ALL reservations? Database backup is a good idea!');
	}
	else if(delete_data == 'users')
	{
		var delete_confirm = confirm('Are you sure you want to delete ALL users? Database backup is a good idea!');
	}
	else if(delete_data == 'everything')
	{
		var delete_confirm = confirm('Are you sure you want to delete EVERYTHING (including you)? The first user created afterwards will become admin. Database backup is a good idea!');
	}

	if(delete_confirm)
	{
		$('#database_administration_message_p').html('<img src="img/loading.gif" alt="Loading"> Deleting...').slideDown('fast');

		$.post('cp.php?delete_all', { delete_data: delete_data }, function(data)
		{
			if(data == 1)
			{
				setTimeout(function()
				{
					if(delete_data == 'everything')
					{
						window.location.replace('#logout');
					}
					else
					{
						list_users();
						$('#database_administration_message_p').slideUp('fast');
					}
				}, 1000);
			}
			else
			{
				$('#database_administration_message_p').html(data);
			}
		});
	}
}

function save_system_configuration()
{
	var price = $('#price_input').val();

	$('#system_configuration_message_p').html('<img src="img/loading.gif" alt="Loading"> Saving...');
	$('#system_configuration_message_p').slideDown('fast');

	$.post('cp.php?save_system_configuration', { price: price }, function(data)
	{
		if(data == 1)
		{
			input_focus();

			setTimeout(function()
			{
				$('#system_configuration_message_p').slideUp('fast', function()
				{
					get_usage();
				});
			}, 1000);
		}
		else
		{
			input_focus('#price_input');
			$('#system_configuration_message_p').html(data);
		}
	});
}

// User control panel

function get_usage()
{
	$.get('cp.php?get_usage', function(data) { $('#usage_div').html(data); });
}

function get_reservation_reminders()
{
	$.get('cp.php?get_reservation_reminders', function(data) { $('#reservation_reminders_span').html(data); });
}

function add_one_reservation()
{
	$('#usage_message_p').html('<img src="img/loading.gif" alt="Loading"> Saving...').slideDown('fast');

	$.post('reservation.php?make_reservation', { week: '0', day: '0', time: '0' }, function(data)
	{
		if(data == 1)
		{
			setTimeout(function()
			{
				if($('#users_div').length)
				{
					list_users();
				}

				get_usage();
				$('#usage_message_p').slideUp('fast');
			}, 1000);
		}
		else
		{
			$('#usage_message_p').html(data);
		}
	});
}

function toggle_reservation_reminder()
{
	$('#settings_message_p').html('<img src="img/loading.gif" alt="Loading"> Saving...').slideDown('fast');

	$.post('cp.php?toggle_reservation_reminder', function(data)
	{
		if(data == 1)
		{
			setTimeout(function()
			{
				if($('#users_div').length)
				{
					list_users();		
				}

				get_reservation_reminders();
				$('#settings_message_p').slideUp('fast');
			}, 1000);
		}
		else
		{
			$('#settings_message_p').html(data);
		}
	});
}

function change_user_details()
{
	var user_name = $('#user_name_input').val();
	var user_email = $('#user_email_input').val();
	var user_password = $('#user_password_input').val();
	var user_password_confirm = $('#user_password_confirm_input').val();

	if(user_password != user_password_confirm)
	{
		$('#user_details_message_p').html('<span class="error_span">Passwords do not match</span>').slideDown('fast');
		$('#user_password_input').val('');
		$('#user_password_confirm_input').val('');
		input_focus('#user_password_input');
	}
	else
	{	
		$('#user_details_message_p').html('<img src="img/loading.gif" alt="Loading"> Saving and refreshing...').slideDown('fast');

		$.post('cp.php?change_user_details', { user_name: user_name, user_email: user_email, user_password: user_password }, function(data)
		{
			if(data == 1)
			{
				input_focus();
				setTimeout(function() { window.location.replace('.'); }, 1000);
			}
			else
			{
				input_focus();
				$('#user_details_message_p').html(data);
			}
		});
	}
}

// UI

function div_fadein(id)
{
	setTimeout(function()
	{
		if(global_css_animations == 1)
		{
			$(id).addClass('div_fadein');
		}
		else
		{
			$(id).animate({ opacity: 1 }, 250);
		}
	}, 1);
}

function div_hide(id)
{
	$(id).removeClass('div_fadein');
	$(id).css('opacity', '0');
}

function notify(text, time)
{
	if(typeof text != 'undefined')
	{
		if(typeof notify_timeout != 'undefined')
		{
			clearTimeout(notify_timeout);
		}

		$('#notification_inner_cell_div').css('opacity', '1');

		if($('#notification_div').is(':hidden'))
		{
			$('#notification_inner_cell_div').html(text);
			$('#notification_div').slideDown('fast');
		}
		else
		{
			$('#notification_inner_cell_div').animate({ opacity: 0 }, 250, function() { $('#notification_inner_cell_div').html(text); $('#notification_inner_cell_div').animate({ opacity: 1 }, 250); });
		}

		notify_timeout = setTimeout(function() { $('#notification_inner_cell_div').animate({ opacity: 0 }, 250, function() { $('#notification_div').slideUp('fast'); }); }, 1000 * time);
	}
	else
	{
		if($('#notification_div').is(':visible'))
		{
			$('#notification_inner_cell_div').animate({ opacity: 0 }, 250, function() { $('#notification_div').slideUp('fast'); });
		}
	}
}

function input_focus(id)
{
	if(offclick_event == 'touchend')
	{
		$('input').blur();
	}
	if(typeof id != 'undefined')
	{
		$(id).focus();
	}
}

// Document ready

$(document).ready( function()
{
	// Detect touch support
	if('ontouchstart' in document.documentElement)
	{
		onclick_event = 'touchstart';
		offclick_event = 'touchend';
	}
	else
	{
		onclick_event = 'mousedown';
		offclick_event = 'mouseup';
	}

	// Visual feedback on click
	$(document).on(onclick_event, 'input:submit, input:button, .reservation_time_div', function() { $(this).css('opacity', '0.5'); });
	$(document).on(offclick_event+ ' mouseout', 'input:submit, input:button, .reservation_time_div', function() { $(this).css('opacity', '1.0'); });

	// Buttons
	$(document).on('click', '#reservation_today_button', function() { showweek(global_week_number, 'today'); });
	$(document).on('click', '.reservation_details_button', function() { 
		var array=this.id.val();
		show_reservation_details(array); });
	$(document).on('click', '#delete_group_button', function() { delete_group(); });
	$(document).on('click', '#reservation_details_button', function() { reservation_details(); });
	$(document).on('click', '#reservation_check_in_button', function() { reservation_check_in(); });
	$(document).on('click', '#reservation_check_out_button', function() { reservation_check_out(); });
	$(document).on('click', '#group_details_button', function() { group_details(); });
	$(document).on('click', '#select_group_button', function() { select_group(); });
	$(document).on('click', '#delete_member_button', function() { delete_member(); });
	$(document).on('click', '#reset_user_password_button', function() { reset_user_password(); });
	$(document).on('click', '#change_user_permissions_button', function() { change_user_permissions(); });
	$(document).on('click', '.check_availability_button', function() { 
		var room_id = this.id.val();
		check_availability(room_id); });
	$(document).on('click', '.delete_user_reservations_button', function() { 
		var array = this.id.split(':');
		delete_user_reservation(array[1],array[2],array[3],array[4])});
	$(document).on('click','.invite_member_button',function(){
		var array = this.id.split(':');
		invite_member(array[1]); });
	$(document).on('click','.accept_invite_button',function(){
		var array = this.id.split(':');
		accept_invite(array[1]); });
	$(document).on('click','.reject_invite_button',function(){
		var array = this.id.split(':');
		reject_invite(array[1]); });
	$(document).on('click', '#delete_user_button', function() { delete_user_data('user'); });
	$(document).on('click', '#delete_all_reservations_button', function() { delete_all('reservations'); });
	$(document).on('click', '#delete_all_users_button', function() { delete_all('users'); });
	$(document).on('click', '#delete_everything_button', function() { delete_all('everything'); });
	$(document).on('click', '#add_one_reservation_button', function() { add_one_reservation();});
	$(document).on('click','#create_group_button',function(){create_group();});
	$(document).on('click','.close',function(){$('#myModal').css("display","none");})
	// Checkboxes
	$(document).on('click', '#reservation_reminders_checkbox', function() { toggle_reservation_reminder(); });

	// Forms
	$(document).on('submit', '#login_form', function() { login(); return false; });
	$(document).on('submit', '#new_user_form', function() { create_user(); return false; });
	$(document).on('submit', '#system_configuration_form', function() { save_system_configuration(); return false; });
	$(document).on('submit', '#user_details_form', function() { change_user_details(); return false; });

	// Links
	$(document).on('click mouseover', '#user_secret_code_a', function() { div_fadein('#user_secret_code_div'); return false; });
	$(document).on('click', '#previous_week_a', function() { showweek('previous'); return false; });
	$(document).on('click', '#next_week_a', function() { showweek('next'); return false; });

	// Divisions
	$(document).on('mouseout', '.reservation_time_cell_div', function() { read_reservation_details(); });

	$(document).on('click', '.reservation_time_cell_div', function()
	{
		var array = this.id.split(':');
		var text=$('#roomAvailability'+array[1]+array[2]+array[3]).html();
		if(text.indexOf("Rooms Left:")!=-1){
		showrooms(array[1],array[2],array[3]);
		}
		else
			notify('All rooms for this date are booked.',4);
		
	});

	$(document).on('mousemove', '.reservation_time_cell_div', function()
	{
		var array = this.id.split(':');
		read_reservation_details(this, array[1], array[2], array[3]);
	});

	// Mouse pointer
	$(document).on('mouseover', 'input:button, input:submit, .reservation_time_div', function() { this.style.cursor = 'pointer'; });
	$(document).on('click','.roomBookButton',function(){
		var array = this.id.split(':');
		toggle_reservation_time(this,array[1],array[2],array[3],array[0])
		
	})
});

// Hash change

function hash()
{
	var hash = window.location.hash.slice(1);

	if(hash == '')
	{
		if(typeof session_logged_in != 'undefined')
		{
			showreservations();
		}
		else
		{
			showlogin();
		}
	}
	else
	{
		if(hash == 'about')
		{
			showabout();
		}
		else if(hash == 'new_user')
		{
			shownew_user();
		}
		else if(hash == 'forgot_password')
		{
			showforgot_password();
		}
		else if(hash == 'help')
		{
			showhelp();
		}
		else if(hash == 'cp')
		{
			showcp();
		}
		else if(hash == 'logout')
		{
			logout();
		}
		else if(hash == 'room')
			{
				showroom()
			}
		else if(hash == 'ma')
			{
				showallreservations()
			}
		else
		{
			window.location.replace('.');
		}
	}
}

// Window load

$(window).load(function()
{
	// Make sure cookies are enabled
	$.cookie(global_cookie_prefix+'_cookies_test', '1');
	var test_cookies_cookie = $.cookie(global_cookie_prefix+'_cookies_test');

	if(test_cookies_cookie == null)
	{
		window.location.replace('error.php?error_code=3');
	}
	else
	{
		$.cookie(global_cookie_prefix+'_cookies_test', null);

		hash();

		$(window).bind('hashchange', function ()
		{
			hash();
		});
	}
});

// Settings

$(document).ready( function()
{
	$.ajaxSetup({ cache: false });
});
$(document).on('click','.deleteBookingButton',function()
			   {
	var array = this.id.split(':');
	
		delete_booking(array[1], array[2
									  ], array[3], array[0]);
});
function delete_booking(week,day,time,id){
				var delete_confirm = confirm('Are you sure you want to cancel this booking?');
				

				if(delete_confirm)
				{
					$.post('reservation.php?delete_reservation', { week: week, day: day, time: time ,id:id}, function(data)
					{
						if(data == 1)
						{
							location.reload(true);
						}
						else
						{
							notify(data, 4);
							setTimeout(function() { read_reservation(id, week, day, time); }, 2000);
						}
					});
				}
}
function delete_user_reservation(week,day,time,id)
{
	var delete_confirm=confirm('Are you sure?');
	if(delete_confirm)
		{
	$.post('reservation.php?delete_reservation',{week:week,day:day,time:time,id:id},function(data)
			{
				
				if(data == 1)
				{
					
					$('#system_configuration_message_p').html('<img src="img/loading.gif" alt="Loading"> Deleting...').slideDown('fast');
					
					setTimeout(function()
					{
						$('#system_configuration_message_p').slideUp('fast', function()
						{
						list_reservations();
							
						});
					}, 1000);
				}
				else
				{
					$('#user_administration_message_p').html(data);
				}
			})
		}
}
function create_group()
{
if($("#group_name_input").length!=0){
	var name=$("#group_name_input").val();
	
	$.post('reservation.php?create_group',{group_name:name}, function(data)
	{
		if(data == '1')
		{
			notify("Group Created Successfully.",4);
			showgroups();
		}
		else
			notify(data,4);
	});
}
	else
		notify('Group name cannot be empty.',4);
}
function delete_group(){
	if(typeof $('.group_radio:checked').val()!= 'undefined')
		{
			var delete_confirm=confirm('Are you sure?');
			if (delete_confirm){
	var group_id=$('.group_radio:checked').val();
			$.post('reservation.php?delete_group',{group_id:group_id},function(data){
				if (data==1){
				notify('Group deleted successfully',4);
					showgroups();
				}
				else
					notify(data,4);			
			})
		}
		}
	else{
		notify('Please select a group.',4);
	}
}
function reservation_details(){
	if(typeof $('.reservation_details_radio:checked').val()!= 'undefined')
		{
			var delete_confirm=confirm('Are you sure?');
			if (delete_confirm){
	var reservation_id=$('.reservation_details_radio:checked').val();
			$.post('see_reservations.php?reservation_details',{reservation_id:reservation_id},function(data){
				$('#reservation_details_div').html(data);
				input_focus('#reservation_details_div');
			})
		}
		}
	else{
		notify('Please select a group.',4);
	}
}
function delete_member(){
	if(typeof $('.group_details_radio:checked').val()!= 'undefined')
		{
			var delete_confirm=confirm('Are you sure?');
			if (delete_confirm){
				var array= $('.group_details_radio:checked').val().split(':');
	var member_id=array[0];
				var group_id=array[1];
			$.post('reservation.php?delete_member',{member_id:member_id},function(data){
				if (data==1){
				notify('Member deleted successfully',4);
					showgroupdetails(group_id);
				}
				else
					notify(data,4);			
			})
		}
		}
	else{
		notify('Please select a group.',4);
	}
}
function group_details()
{
	if(typeof $('.group_radio:checked').val()!= 'undefined')
		{
			var group_id=$('.group_radio:checked').val();
			showgroupdetails(group_id);
			}
	else
		notify('Please select a group.',4);
		}
function invite_member(group_id)
{
	var invite_roll_no=$('#invite_roll_no_input').val();
	var invite_email=$('#invite_email_input').val();
	var regexPattern=new RegExp(/^[0-9-+]+$/);
	if(invite_email.length==0)
		notify('Email cannot be empty.',4);
	else if(invite_roll_no.length==0)
		notify('Roll No cannot be empty.',4);
	else if(!regexPattern.test(invite_roll_no))
		notify('Roll No should contain numbers only.',4);
	else if(invite_roll_no.length!=9)
		notify('Roll No should be 9 digits long.',4)
	else
	{
		
		
		$.post('reservation.php?invite',{invite_email:invite_email,invite_roll_no:invite_roll_no,group_id:group_id},function(data){
			if (data==1)
				{
				notify('Invite sent.',4);
				showgroupdetails(group_id);
				showgroups();
				}
			else
				notify(data,4);
		});}
	
}
function accept_invite(member_id)
{
	$.post('reservation.php?accept_invite',{member_id:member_id},function(data){
		if(data==1)
			notify('Invite Accepted.',4);
			showgroups();
			showinvitations();
	});
}
function reject_invite(member_id)
{
	$.post('reservation.php?reject_invite',{member_id:member_id},function(data){
		if(data==1)
			notify('Invite Deleted.',4);
			showgroups();
			showinvitations();
	});
}
function select_group(){
	if(typeof $('.group_radio:checked').val()!= 'undefined')
		{
			var group_id=$('.group_radio:checked').val();
			$.post('reservation.php?select_group',{group_id:group_id},function(data){
				notify(data,4);
				showweek(global_week_number, 'today');
			});
			}
	else
		notify('Please select a group.',4);
}
function check_availability(room_id){
	page_load('room_details');
	div_hide('#room_detail_div');
$.post('room.php?roomdetail',{week:week,day:day,time:time}, function(data) { $('#room_detail_div').html(data); div_fadein('#room_detail_div'); page_loaded('room_details'); });
	
}
