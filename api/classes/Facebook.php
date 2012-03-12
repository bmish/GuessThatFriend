<?php
class Facebook_API	{

	const APP_ID = '178461392264777';
	const SECRET = '7bddd2e7e8d4094ff02bdda4c7fd44ee';
	
	private $facebook;
	
	public static function setupFacebook()	{
		global $facebook;
		if (!isset($facebook))	{
			require 'FacebookSDK.php';
			$facebook = new Facebook(array(
				'appId' => self::APP_ID,
				'secret' => self::SECRET,
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
		return $friends['data'];
	}
	
	/*
	 * Returns all friends' likes.
	 */
	public static function getFriendsLikes($userId)	{
		for ($i = 0; $i < sizeof($friends); $i++)	{
			$likes[$i] = self::getFriendsLikes($friends[$i]['id']);
		}
		return $likes;
	} 
	
	/*
	 * Returns a particular friend's likes.
	 */
	public static function getFriendLikes($friendId)	{
		self::setupFacebook();
		global $facebook;
		
		$accessToken = $facebook->getAccessToken();
		$likes = $facebook->api('/'.$friendId.'/likes?access_token='.$accessToken);
		return $likes['data'];
	}

	/*
	 * Returns the facebook app URL.
	 */
	public static function getAppURL() {
		return self::getFacebookURL(self::APP_ID);
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
	var_dump(Facebook_API::getFriendLikes($testUserId));
}
?>
