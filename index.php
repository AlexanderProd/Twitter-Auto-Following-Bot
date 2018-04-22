<?php
require_once('credentials.php');
require_once('tb.php');

$interest	  = 'design'; // Your keyword to follow
$follow_limit = '10'; // Twitter has its own limit so you might not be able to follow many users in a single day
$user = 'levelsio'; //Follow the followers of this user.

$bot = new TwitterBot($key, $secret, $token, $token_secret);
#$bot->FollowNow($interest,$follow_limit);
$bot->followFollowers($user,$follow_limit);

?>
