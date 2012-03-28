<?php
// https://developers.facebook.com/docs/reference/php/

require_once("FacebookSDK.php");

class FacebookAPI	{
	private $facebook;
	
	public function __construct() {
		$this->facebook = new Facebook(array(
			'appId' => FB_APP_ID,
			'secret' => FB_SECRET,
		));
	}
	
	/* 
	 * Returns the user's friends.
	 */
	public function getFriends($userId)	{
		$accessToken = $this->facebook->getAccessToken();
		$friends = $this->facebook->api('/'.$userId.'/friends?access_token='.$accessToken);
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
	public function getFriendsLikes($userId)	{
		$friends = $this->getFriends($userId);
		for ($i = 0; $i < sizeof($friends); $i++)	{
			$likes[$i] = $this->getFriendLikes($friends[$i]);
		}
		
		return $likes;
	} 
	
	/*
	 * Returns a particular friend's likes.
	 */
	public function getFriendLikes($friend)	{
		$accessToken = $this->facebook->getAccessToken();
		$likes = $this->facebook->api('/'.($friend -> facebookId).'/likes?access_token='.$accessToken);
		return array (
			'friend' => $friend,
			'friendLikes' => $likes['data'],
		);
	}
	
	/*
	 * Returns the facebook object's name for a given ID.
	 */
	public function getNameFromId($id)	{
		$object = $this->facebook->api('/'.$id);
		return $object['name'];
	}

	/*
	 * Returns the facebook app URL.
	 */
	public function getAppURL() {
		return $this->getFacebookURL(FB_APP_ID);
	}
	
	/*
	 * Returns a facebook object's URL.
	 */
	public function getFacebookURL($id)	{
		$content = file_get_contents('https://graph.facebook.com/'.$id);
		$content = json_decode($content);
		
		if ($content -> link) {
			return "<a href=".$content->link." target=_blank>";
		} else {
			return false;
		}
	}	
	
	// Log user in with given access token.
	public function authenticate($facebookAccessToken) {
		$this->facebook->setAccessToken($facebookAccessToken);
		
		return $this->facebook->getUser(); // User ID of current user, or 0 if no logged-in user.
	}
}
?>
