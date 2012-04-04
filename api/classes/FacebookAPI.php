<?php
// https://developers.facebook.com/docs/reference/php/

class FacebookAPI	{
	private $facebook;
	private $likes;
	private $facebookAccessToken;
	
	public function __construct() {
		$this->facebook = new Facebook(array(
			'appId' => FB_APP_ID,
			'secret' => FB_SECRET,
		));
		
		$this->likes = array(); // Cache any likes we request.
	}
	
	/* 
	 * Returns the user's friends.
	 */
	public function getFriendsOf($facebookId) {
		$friends = $this->facebook->api('/'.$facebookId.'/friends?access_token='.$this->facebookAccessToken);
		
		return FacebookAPI::jsonToSubjects($friends['data']);
	}
	
	public function getRandomFriend($facebookId = "") { // TODO
		return null;
	}
	
	public function getRandomSubject($category = null) {
		if ($category) {
			return getRandomPage($category);
		}
		
		return getRandomFriend();
	}
	
	/*
	 * Returns all friends' likes.
	 */
	public function getLikesOfAllMyFriends() {
		$friends = $this->getFriendsOf($this->getLoggedInUserId());
		for ($i = 0; $i < sizeof($friends); $i++)	{
			$likes[$friends[$i]->facebookId]['likes'] = $this->getLikesOfFriend($friends[$i]->facebookId);
			$likes[$friends[$i]->facebookId]['subject'] = $friends[$i];
		}
		
		return $likes;
	}
	
	private static function jsonToSubjects($json) {
		$subjects = array();
		for ($i = 0; $i < sizeof($json); $i++)	{
			$subjects[$i] = new Subject($json[$i]);
		}
		
		return $subjects;
	}
	
	/*
	 * Returns a particular friend's likes.
	 */
	public function getLikesOfFriend($facebookId = "") {
		if ($facebookId == "") { // Use logged in user's id.
			$facebookId = $this->getLoggedInUserId();
		}
		
		if (!isset($this->likes[$facebookId])) {
			$likesResponse = $this->facebook->api('/'.$facebookId.'/likes?access_token='.$this->facebookAccessToken);

			// Store friend's likes so we won't have to look it up again.
			$this->likes[$facebookId] = FacebookAPI::jsonToSubjects($likesResponse);
		}
		
		return $this->likes[$facebookId];
	}
	
	/*
	 * Returns the facebook object's name for a given ID.
	 */
	public function getNameFromId($facebookId) {
		if ($facebookId == "") {
			return "";
		}
		
		$object = $this->facebook->api('/'.$facebookId);
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
		
		if ($content->link) {
			return '<a href="'.$content->link.'" target="_blank">';
		} 
		
		return false;
	}	
	
	// Log user in with given access token.
	public function authenticate($facebookAccessToken) {
		$this->facebook->setAccessToken($facebookAccessToken);
		$this->facebookAccessToken = $facebookAccessToken;
		
		return $this->getLoggedInUserId(); // User ID of current user, or 0 if no logged-in user.
	}
	
	public function getLoggedInUserId() {
		return $this->facebook->getUser();
	}
	
	/*
	 * Returns whether a person likes a particular page.
	 * @param facebookId - the facebook Id of the person
	 * @param pageFacebookId - the facebook Id of the page
	 * @return true if the person likes the page, false otherwise
	 */
	public function likesPage($facebookId, $pageFacebookId)	{
		$likes = $this->getLikesOfFriend($facebookId);
		foreach ($likes as $like)	{
			if ($like->facebookId == $facebookId)	{
				return true;
			}
		}
		
		return false;
	}
	
	public function getRandomPage($category = null) {
		if ($category == null)
			$selectQuery = "SELECT * FROM pages";
		else
			$selectQuery = "SELECT id, name FROM pages WHERE category = ".$category->facebookName;
			
		$pages = mysql_query($selectQuery);
		
		if (!$pages)	{
			return false;
		} else	{
			mysql_data_seek ($pages, rand(0,mysql_num_rows($pages)-1));
			$page = mysql_fetch_array($pages);
			if ($category == null)
				return array('id' => $page[0], 'name' => $page[1], 'category' => $page[2]);
			else
				return array('id' => $page[0], 'name' => $page[1], 'category' => $category->facebookName);
		}
	}
	
	/*
	 * TODO: This function will probably get our app banned from facebook.
	 * @return a random Facebook ID of a particular category.
	 */
	public function getRandomlyGeneratedFacebookId($category = null)	{
		$maxTries = 10;
		$triesCount = 0;
		
		while ($triesCount++ <= $maxTries)	{
			// Only generates ids up to 12 digits long
			$randId = rand(1,9);
			for ($i = 1; $i <= 12; $i++)	{
				if (rand(0,1) == 1) {
					$randId = $randId.rand(0,9);
				}
			}
			
			// Check if we found a valid facebook id
			$contents = @file_get_contents('http://graph.facebook.com/'.$randId);
			if (($contents != false) && (strcmp($contents, 'false') != 0))	{
				$contents = json_decode($contents);
				
				// Check if id belongs to a page of the correct category
				if (!empty($contents->category) && (empty($contents->category) || strcmp($contents->category, $category->facebookName) == 0)) {
					return $randId;
				}
			}
		}
		
		return "";
	}
}
?>
