<?php
// https://developers.facebook.com/docs/reference/php/

class FacebookAPI	{
	private $facebook;
	private $facebookId;
	private $likesCache;
	private $friendsCache;
	private $namesCache;
	private $facebookAccessToken;
	
	public function __construct() {
		$this->facebook = new Facebook(array(
			'appId' => FB_APP_ID,
			'secret' => FB_SECRET,
		));
		
		$this->likesCache = array(); // Cache any likes we request.
		$this->friendsCache = array(); // Cache any friends we request.
		$this->namesCache = array(); // Cache any names we request.
	}
	
	/* 
	 * Returns the user's friends.
	 */
	public function getFriendsOf($facebookId = "") {
		if ($facebookId == "")	{
			$facebookId = $this->getLoggedInUserId();
		}
		
		if (!isset($this->friendsCache[$facebookId])) {
			$friendsResponse = $this->facebook->api('/'.$facebookId.'/friends?access_token='.$this->facebookAccessToken);

			// Store friend's friends so we won't have to look it up again.
			$this->friendsCache[$facebookId] = FacebookAPI::jsonToSubjects($friendsResponse['data']);
		}
		
		return $this->friendsCache[$facebookId];
	}
	
	public function getRandomFriend($facebookId = "") {
		if ($facebookId == "")	{
			$facebookId = $this->getLoggedInUserId();
		}
		
		$friends = $this->getFriendsOf($facebookId);
		
		// Only choose a friend that has likes.
		return $this->chooseFriendWithSufficientLikes($friends);
	}
	
	private function chooseFriendWithSufficientLikes($friends) {
		$MIN_ACCEPTABLE_LIKES = 2;
		$MAX_TRIES = 10;
		$triesCount = 0;
		do {
			if ($triesCount++ == $MAX_TRIES) {
				echo "Could not find a friend with sufficient likes.";
				exit;
			}
			
			$friend = $this->getRandomElement($friends);
			$likesSubjects = $this->getLikesOfFriend($friend->facebookId);
		} while (count($likesSubjects) < $MIN_ACCEPTABLE_LIKES);
		
		return $friend;
	}
	
	public function getRandomFriendWhoLikes($facebookId = "") {
		if ($facebookId == "")	{
			return $this->getRandomFriend();
		}
		
		$friendsWhoLike = array();
		$friends = $this->getFriendsOf($this->getLoggedInUserId());
		foreach ($friends as $friend)	{
			if (likesPage($friend->facebookId, $facebookId)) {
				$friendsWhoLike[] = $friend;
			}
		}
		
		return $this->getRandomElement($friendsWhoLike);
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
	
	public function getRandomPage($category = null, $count = 1) {
		if (!$category) {
			$selectQuery = "SELECT * FROM randomPages";
		} else {
			$selectQuery = "SELECT facebookId, name FROM randomPages WHERE categoryFacebookName = '".$category->facebookName."'";
		}
		
		// Select random row(s).
		$selectQuery .= " ORDER BY RAND() LIMIT ".API::cleanInputForDatabase($count);
		$result = mysql_query($selectQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			return false;
		}
		
		// Return one page?
		if ($count == 1) {
			$page = mysql_fetch_assoc($result);
			$pageCategory = ($category == null) ? Category::getCategoryByFacebookName($page['categoryFacebookName']) : $category;

			return new Subject($page['facebookId'], $page['name'], $pageCategory);
		}
		
		// Return an array of pages.
		$pages = array();
		while ($page = mysql_fetch_assoc($result)) {
			$pageCategory = ($category == null) ? Category::getCategoryByFacebookName($page['categoryFacebookName']) : $category;

			$pages[] = new Subject($page['facebookId'], $page['name'], $pageCategory);
		}
		
		return $pages;
	}
	
	public function getRandomLikedPage($facebookId = "", $category = null)	{
		$likes = $this->getLikesOfFriend($facebookId);
		
		if (!$category) { // No specific category.
			$MIN_ACCEPTABLE_LIKES = 2;
			$MAX_TRIES = 10;
			$triesCount = 0;
			do {
				if ($triesCount++ == $MAX_TRIES) {
					echo "Could not a page with sufficient matching category pages.";
					exit;
				}
				$like = $this->getRandomElement($likes);
			} while(!$like->category->isEnoughCategoryData());
			return $like;
		}	

		//TODO: May need to deal with insufficient data for requested category
		$likesOfCategory = array();
		foreach ($likes as $like)	{
			if ($like->category->categoryId == $category->categoryId) {
				$likesOfCategory[] = $like;
			}
		}
		
		return $this->getRandomElement($likesOfCategory);
	}
	
	private function getRandomElement($arr = null)	{
		if ($arr && count($arr) > 0) {
			return $arr[array_rand($arr, 1)];
		}
		
		return null;
	}
		
	public function getRandomSubject($category = null) {
		if ($category) {
			return $this->getRandomPage($category);
		}
		
		return $this->getRandomFriend();
	}
	
	/*
	 * Returns all friends' likes.
	 */
	public function getLikesOfAllMyFriends() {
		$friends = $this->getFriendsOf($this->getLoggedInUserId());
		if (sizeof($likes) < $friends)	{
			for ($i = 0; $i < sizeof($friends); $i++)	{
				$likes[$friends[$i]->facebookId]['likes'] = $this->getLikesOfFriend($friends[$i]->facebookId);
				$likes[$friends[$i]->facebookId]['subject'] = $friends[$i];
			}
		}
		return $likes;
	}
	
	private static function jsonToSubjects($json) {
		$subjects = array();
		for ($i = 0; $i < sizeof($json); $i++)	{
			$category = isset($json[$i]['category']) ? Category::getCategoryByFacebookName($json[$i]['category']) : null;
			$subjects[$i] = new Subject($json[$i]['id'], $json[$i]['name'], $category);
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
		
		if (!isset($this->likesCache[$facebookId])) {
			$likesResponse = $this->facebook->api('/'.$facebookId.'/likes?access_token='.$this->facebookAccessToken);

			// Store friend's likes so we won't have to look it up again.
			$this->likesCache[$facebookId] = FacebookAPI::jsonToSubjects($likesResponse['data']);
		}
		
		return $this->likesCache[$facebookId];
	}
	
	/*
	 * Returns the facebook object's name for a given ID.
	 */
	public function getNameFromId($facebookId) {
		if ($facebookId == "")	{
			$facebookId = $this->getLoggedInUserId();
		}
		
		if (!isset($namesCache[$facebookId])) {
			$personResponse = $this->facebook->api('/'.$facebookId);
			
			// Store friend's name so we won't have to look it up again.
			$this->namesCache[$facebookId] = $personResponse['name'];
		}
			
		return $this->namesCache[$facebookId];
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
	
	// Log user in with given access token. Return user ID if login is successful, or false if no logged-in user.
	public function authenticate($facebookAccessToken) {
		$this->facebook->setAccessToken($facebookAccessToken);
		$this->facebookAccessToken = API::cleanInputForDatabase($facebookAccessToken);
		
		// Invalid access token?
		if (!$this->getLoggedInUserId()) {
			return false;
		}
			
		// Update our database record for this user.
		$this->updateLoggedInUserDatabaseRecord();
			
		return $this->getLoggedInUserId();
	}
	
	public function updateLoggedInUserDatabaseRecord() {
		mysql_query("INSERT INTO users (facebookId) VALUES ('".$this->getLoggedInUserId()."')"); // This query won't affect anything if the user already exists in the database.
		mysql_query("UPDATE users SET lastVisitedAt = NOW() WHERE facebookId = '".$this->getLoggedInUserId()."' LIMIT 1");
	}
	
	public function getLoggedInUserId() {
		if (!$this->facebookId) {
			$this->facebookId = $this->facebook->getUser();
		}
		
		return $this->facebookId;
	}
	
	/*
	 * Store a page in the database.
	 */
	public function insertPageIntoDatabase($subject)	{
		$replaceQuery = "REPLACE INTO pages SET id = '".$subject->facebookId."', name = '".$subject->name."', category = '".$subject->category->facebookName."';";
		$result = mysql_query($replaceQuery);
		
		if (!$result) {
			echo "Error - unable to insert page ".$subject->facebookId;
		}
	}
}

// Testing
if (isset($_GET['testInsertPage']) && ($_GET['testInsertPage'] == 'true'))	{
	require_once('Subject.php');
	require_once('Category.php');
	require_once('API.php');
	require_once('DB.php');
	require_once('../../references/facebook-php-sdk/src/facebook.php');
	require_once('../config/config.php');
	DB::connect();

	$person = new Subject('4', 'Mark Zuckerberg', null);
	echo "<p>person is person?: ".$person->isPerson()."</p>";
	$page1 = new Subject('123', 'Dummy Page', Category::getCategoryByFacebookName('Movie'));
	echo "<p>page1 is person?: ".$page1->isPerson()."</p>";
	$page2 = new Subject('123', 'Dummy Page - New', Category::getCategoryByFacebookName('Interest'));
	echo "<p>page2 is person?: ".$page2->isPerson()."</p>";
	$page3 = new Subject('456', 'Dummy Page 2', Category::getCategoryByFacebookName('Movie'));
	echo "<p>page3 is person?: ".$page3->isPerson()."</p>";
	$fbapi = new FacebookAPI();
	$fbapi->insertPageIntoDatabase($page1);
	$fbapi->insertPageIntoDatabase($page2);
	$fbapi->insertPageIntoDatabase($page3);

	DB::close();
}
?>
