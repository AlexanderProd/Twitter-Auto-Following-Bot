<?php

require_once('twitteroauth/twitteroauth.php');
require_once('time.php');
require_once('functions.php');

date_default_timezone_set('UTC');

class TwitterBot
{
	protected $oauth;

	public function __construct($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret) {
		$this->oauth = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
	}

	//Use this function if you want to tweet
	public function post($message) {
		$this->oauth->post('statuses/update', array('status' => $message));
	}

	//searches for users
	public function search(array $query) {
		return $this->oauth->get('search/tweets', $query);
	}

	//get Followers of a user
	public function getFollowers(array $query) {
		return $this->oauth->get('followers/ids', $query);
	}

	//get details about a user
	public function usersShow($query) {
		return $this->oauth->get('users/show', $query);
	}


	public function FollowNow($interest, $follow_limit, $logOnly) {

		$query = array(
		  "q"           => $interest,
		  "count"       => "100",
		  "result_type" => "recent",
			"until"				=> date("Y-m-d"),
			"geocode"     => "49.460983,11.061859,20km"
		);

		$results = $this->search($query);
		foreach ($results->statuses as $result) {
			if ($result->user->lang == "de") {
				$users_id[] = $result->user->id;
			}
		}

		if (empty($users_id)) {
			echo "Nobody found!";
			exit();
		}

		$users_id = array_unique($users_id);
		if ($logOnly == true){
			$new_followings = $this->logToFile($users_id);
		} else {
			$new_followings = $this->autoFollowUserID($users_id);
		}
	}


	public function followFollowers($user, $follow_limit, $logOnly) {

		$users_id = [];
		$cursor=-1;

		$query = array(
			"screen_name" => $user,
			"cursor" 			=> $cursor,
			"count"   		=> $follow_limit,
		);

		for ($i=0; $i < (/* $this->getFollowersNumber($user) */intdiv(47984, $follow_limit)+2); $i++) {
			echo "Iteration:".$i;
			$results = $this->getFollowers($query);
			foreach ($results->ids as $result) {
			  //$users_id[] = $result;
				array_push($users_id,$result);
			}
			$this->$cursor = end($users_id);
		}

		$users_id = array_unique($users_id);

		if ($logOnly == true){
			$new_followings = $this->logToFile($users_id);
		} else {
			$new_followings = $this->autoFollowUserID($users_id);
		}
	}

	public function checkUserLanguage($user){

		$query = array(
			"user_id" => $user,
		);

		$result = $this->usersShow($query);
		if ($result->lang == "de") {
			return true;
		} else {
			return false;
		}
	}

	public function getFollowersNumber($user){

		$query = array(
			"screen_name" => $user,
		);

		$result = $this->usersShow($query);
		$numberFollowers = $result->followers_count;
		echo intval($numberFollowers);
		return $numberFollowers;
	}


	public function autoFollowUserID($users_id = array()) {

		$friends = $this->oauth->get('friends/ids', array('cursor' => -1));
		foreach ($users_id as $user_id) {
			if (!in_array($user_id, $friends->ids)) {
				sleep(10);
				$this->oauth->post('friendships/create', array('user_id' => $user_id));

				echo 'Followed User ID: <a href="https://twitter.com/intent/user?user_id='.$user_id.'" target="_blank">'.$user_id.'</a><br/><br/>';
			}
		}
	}

	public function logToFile($users_id = array()) {

		array_unique($users_id);
		foreach ($users_id as $user_id) {
			#echo "ID: ".$user_id;
			if ($this->checkUserLanguage($user_id)==true /* && checkIfIdExistsInDb($user_id)==false */){
				/* writeIdToDb($user_id); */
				echo $user_id . " added! <br /><br />";
				$myfile = fopen("log.html", "a+") or die("Unable to open file!");
				$txt = serverTime().' Followed User ID: <a href="https://twitter.com/intent/user?user_id='.$user_id.'" target="_blank">'.$user_id.'</a><br/><br/>';
				fwrite($myfile, $txt);
				fclose($myfile);
			} else {
				echo $user_id . " not added! <br /><br />";
			}

			#$myfile = fopen("log.html", "a+") or die("Unable to open file!");
			#$txt = 'Followed User ID: <a href="https://twitter.com/intent/user?user_id='.$user_id.'" target="_blank">'.$user_id.'</a><br/><br/>';
			#fwrite($myfile, $txt);
			#fclose($myfile);
		}
	}

}
