<?php
class Facebook_API	{
	
	private $facebook;
	
	public static function setupFacebook()	{
		global $facebook;
		if (!isset($facebook))	{
			require 'FacebookSDK.php';
			require 'Subject.php';
			require '../fns/config.php';
			$facebook = new Facebook(array(
				'appId' => FB_APP_ID,
				'secret' => FB_SECRET,
			));
		}
	}
	
	/* 
	 * Returns the user's friends.
	 */
	public static function getFriends($userId)	{
		self::setupFacebook();
		global $facebook;
		
		$accessToken = $facebook->getAccessToken();
		$friends = $facebook->api('/'.$userId.'/friends?access_token='.$accessToken);
		$friendsData = $friends['data'];
		
		for ($i = 0; $i < sizeof($friendsData); $i++)	{
			$subjects[$i] = new Subject();
			$subjects[$i]->name = $friendsData[$i]['name'];
			$subjects[$i]->facebookId = $friendsData[$i]['id'];
			$subjects[$i]->picture = 'https://graph.facebook.com/'.$friendsData[$i]['id'].'/picture';
			$subjects[$i]->link = 'facebook.com/'.$friendsData[$i]['id'];
		}
		return $subjects;
	}
	
	/*
	 * Returns all friends' likes.
	 */
	public static function getFriendsLikes($userId)	{
		$friends = self::getFriends($userId);
		for ($i = 0; $i < sizeof($friends); $i++)	{
			$likes[$i] = self::getFriendLikes($friends[$i]);
		}
		return $likes;
	} 
	
	/*
	 * Returns a particular friend's likes.
	 */
	public static function getFriendLikes($friend)	{
		self::setupFacebook();
		global $facebook;
		
		$accessToken = $facebook->getAccessToken();
		$likes = $facebook->api('/'.($friend -> facebookId).'/likes?access_token='.$accessToken);
		return array (
			'friend' => $friend,
			'friendLikes' => $likes['data'],
		);
	}
	
	/*
	 * Returns the facebook object's name for a given ID.
	 */
	public static function getNameFromId($id)	{
		$object = $facebook->api('/'.$id);
		return $object['name'];
	}

	/*
	 * Returns the facebook app URL.
	 */
	public static function getAppURL() {
		return self::getFacebookURL(self::FB_APP_ID);
	}
	
	/*
	 * Returns a facebook object's URL.
	 */
	public static function getFacebookURL($id)	{
		$content = file_get_contents('https://graph.facebook.com/'.$id);
		$content = json_decode($content);
		
		if ($content -> link) {
			echo "<a href=".$content->link." target=_blank>";
		} else {
			return false;
		}
	}	
}

// Testing
if ($_GET['testFB'] == 'true') {
	$testUserId = '100003539848423';
	echo "<p>Friends:</p>";
	var_dump(Facebook_API::getFriends($testUserId));
	echo "<p>Likes:</p>";
	var_dump(Facebook_API::getFriendsLikes($testUserId));
}
?>
