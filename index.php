<?php
require_once('credentials.php');
require_once('tb.php');

$interest	  = 'design'; // Your keyword to follow
$follow_limit = '1000'; // Twitter has its own limit so you might not be able to follow many users in a single day
$user = 'levelsio'; //Follow the followers of this user.
$logOnly = true;

$bot = new TwitterBot($key, $secret, $token, $token_secret);
#$bot->FollowNow($interest, $follow_limit, $logOnly);
$bot->followFollowers($user,$follow_limit,$logOnly);
?>
