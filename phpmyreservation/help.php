<?php

include_once('main.php');

if(check_login() != true) { exit; }

?>

<div class="box_div" id="help_div">
<div class="box_top_div"><a href="#">Start</a> &gt; Help</div>
<div class="box_body_div">

<h3>Reservations</h3>

<ul>
<li><b>Can I make more than one reservation?</b><br>No a user can make only one reservation at a time.</li>
<li><b>Where can I find all the details of each discussion room?</b><br>All the details of each room is given in the <a href="#room">room details</a> page </li>
<li><b>What are the minimum number of people who can book a discussion room?</b><br>
	4 </li>

</ul>

<h3>Groups</h3>
<br>
<li><b>Creating a group</b><br>To create a new group, go to <a href="">home</a> and under 'Groups', type in a name for the group and press 'Create Group'. The user who creates the group, will be made the admin of the group.</li>
<br>
<li>
	<b>Inviting new members to the group</b><br>
Please note that <b>only the group admin can add new members</b><br>
Under <b>Groups</b>, highlight the radio button in front of the group in which you wish to add new members, and then click <b>Group Details</b>. Then, under Invitations/Group Details, put in the roll number and the email of the user you wish to invite to your group. Please note that the user should be registered on the room booking platform before you do this. 
</li>
<br>
<li>
	<b>Accepting Invitations</b><br>
	Whenever a user invites you to the group, this application notifies you on your email. New invitations appear under the 'Invitations/Group Details' section. You can either accept them, or reject them. 


</li>

<h3>Account</h3>

<ul>
<li><b>How do I change my name, email and/or password?</b><br>You can do that in the <a href="#cp">control panel</a>.</li>
</ul>

<?php

if($_SESSION['user_is_admin'] == '1')
{

?>

<!--
<h3>Admin help</h3>

<ul>
<li><b>Are there any reservation restrictions for admins?</b><br>No, you can make and remove reservations back and forward in time as you wish, and you can delete other users' reservations. It will require a confirmation.</li>
<li><b>How do I manage users and reservations?</b><br>You can do that in the <a href="#cp">control panel</a>. You can reset a user's passwords (if the user has forgot it), change a user's permissions (admin or not), delete a user's reservations and delete a user. Just pick a user and choose what to do. All the red buttons will require a confirmation.</li>
<li><b>Can I delete all users and reservations?</b><br>You can do that in the <a href="#cp">control panel</a>. Your user and reservations will not be deleted unless you choose to delete everything.</li>
<li><b>How do I change the other options, like possible reservation times, secret code etc?</b><br>The webmaster must do that in the configuration file (config.php).</li>
<li><b>Will changing the price affect previous reservations?</b><br>No. A new price will only apply for reservations made after the price change.</li>
</ul>
-->

<?php

}

?>

</div></div>
