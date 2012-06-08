<?php
/**
 * This class implements the API functions for communicating with the Facebook server.
 *
 * It uses the Singleton pattern. The Singleton ensures that there can be only one instance
 * of a Class and provides a global access point to that instance. Singleton is a "Gang of
 * Four" Creational Pattern.
 *
 *
 * @see	https://developers.facebook.com/docs/reference/php/
 */
class FacebookAPI	{
	private static $instance;
	
	private $facebook;
	private $facebookId;
	private $facebookAccessToken;
	
	/**
	 * __construct
	 */	
	private function __construct() {
		$this->facebook = new Facebook(array(
			'appId' => FB_APP_ID,
			'secret' => FB_SECRET,
		));
	}
	
	/**
	 * Provides a global access point to the one and only instance of this class.
	 *
	 * @return FacebookAPI The one and only instance of this class
	 */
	public static function singleton() {
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		
		return self::$instance;
	}
	
	/**
	 * Returns the user's friends.
	 *
	 * @param string $facebookId Facebook ID of user to get friends for
	 * @return array Array of Subjects
	 */
	public function getFriendsOf($facebookId = "") {
		if ($facebookId == "")	{
			$facebookId = $this->getLoggedInUserId();
		}
		
		// Get API response.
		$friendsResponse = Cache::requestFacebookAPIWithCaching('/'.$facebookId.'/friends');
		
		return FacebookAPI::jsonToSubjects($friendsResponse['data']);
	}
	
	/**
	 * Returns a random friend.
	 *
	 * @param string $facebookId Facebook ID of user to get a friend for
	 * @return Subject A random friend
	 */
	public function getRandomFriend($facebookId = "") {
		if ($facebookId == "")	{
			$facebookId = $this->getLoggedInUserId();
		}
		
		$friends = $this->getFriendsOf($facebookId);
		
		// Only choose a friend that has likes.
		return $this->chooseFriendWithSufficientLikes($friends);
	}
	
	/**
	 * Choose a friend with sufficient likes.
	 *
	 * @param array $friends List of friends to choose from
	 * @return Subject A friend with sufficient likes
	 */
	private function chooseFriendWithSufficientLikes($friends) {
		$MIN_ACCEPTABLE_LIKES = 2;
		$MAX_TRIES = 10;
		$triesCount = 0;
		do {
			if (++$triesCount == $MAX_TRIES) {
				throw new Exception("Could not find a friend with a sufficient number of likes.");
			}
			
			$friend = Util::getRandomElement($friends);
			$likesSubjects = $this->getLikesOfFriend($friend->facebookId);
		} while (count($likesSubjects) < $MIN_ACCEPTABLE_LIKES);
		
		return $friend;
	}
	
	/**
	 * Returns a random friend who likes/ not like a page.
	 *
	 * @param string $facebookId Facebook ID of the page
	 * @param bool $likeFlag True if the chosen friend should like the page, false otherwise
	 * @return Subject A random friend
	 */
	public function getRandomFriendWhoLikes($pageFacebookId = "", $likeFlag) {
		if ($pageFacebookId == "")	{
			return $this->getRandomFriend();
		}
		
		$friendsWhoLike = array();
		$friends = $this->getFriendsOf($this->getLoggedInUserId());
		foreach ($friends as $friend)	{
			if (likesPage($friend->facebookId, $pageFacebookId) == $likeFlag) {
				$friendsWhoLike[] = $friend;
			}
		}
		
		return Util::getRandomElement($friendsWhoLike);
	}
		
	/**
	 * Returns whether a person likes a particular page.
	 *
	 * @param string $facebookId Facebook ID of the person
	 * @param string $pageFacebookId Facebook ID of the page
	 * @return bool True if the person likes the page, false otherwise
	 */
	public function likesPage($facebookId, $pageFacebookId)	{
		$likes = $this->getLikesOfFriend($facebookId);
		foreach ($likes as $like) {
			if ($like->facebookId == $pageFacebookId) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Returns a list of random pages.
	 *
	 * @param Category $category Category of pages to return
	 * @param int $count Number of pages to return
	 * @param string|null $facebookId Facebook ID of the friend to check for 'likes' condition; null if no check required
	 * @return array Array of random pages
	 */
	public function getRandomPage($category = null, $count = 1, $facebookId = null) {
		if (!$category) {
			$selectQuery = "SELECT * FROM randomPages";
		} else {
			$selectQuery = "SELECT facebookId, name FROM randomPages WHERE categoryFacebookName = '".DB::cleanInputForDatabase($category->facebookName)."'";
		}

		$result = mysql_query($selectQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			return false;
		}
		
		$pages = array();
		$checkedPages = array();
		while (sizeof($pages) < $count)	{
			mysql_data_seek ($result, rand(0,mysql_num_rows($result)-1));
			$page = mysql_fetch_assoc($result);
			$pageFacebookId = $page['facebookId'];
			if (!in_array($pageFacebookId, $checkedPages)) { // Ignore pages that have already been checked.
				if ($facebookId == null || !$this->likesPage($facebookId, $pageFacebookId)) { // Only find pages not liked by $facebookId.
					$pageCategory = ($category == null) ? Category::getCategoryByFacebookName($page['categoryFacebookName']) : $category;
					$pages[] = new Subject($pageFacebookId, $page['name'], $pageCategory);
				}
				$checkedPages[] = $pageFacebookId;
			}
		}
		
		return $pages;
	}
	
	/**
	 * Returns a random like (page) for a particular person.
	 *
	 * @param string $facebookId Facebook ID of the person
	 * @param Category $category Specific category to look for
	 * @return Subject Random page
	 * @todo May need to deal with insufficient data for requested category
	 */
	public function getRandomLikedPage($facebookId = "", $category = null)	{
		$likes = $this->getLikesOfFriend($facebookId);
		
		if (!$category) { // No specific category.
			$MIN_ACCEPTABLE_LIKES = 2;
			$MAX_TRIES = 10;
			$triesCount = 0;
			do {
				if (++$triesCount == $MAX_TRIES) {
					throw new Exception("The randomPages database table may not contain a large enough variety of random pages.");
				}
				
				$like = Util::getRandomElement($likes);
			} while($like->nameIsLikelyASentence() || !$like->category->enoughRandomPagesOfSameCategory());
			return $like;
		}	

		// TODO: May need to deal with insufficient data for requested category.
		$likesOfCategory = array();
		foreach ($likes as $like)	{
			if ($like->category->categoryId == $category->categoryId) {
				$likesOfCategory[] = $like;
			}
		}
		
		return Util::getRandomElement($likesOfCategory);
	}
	
	/**
	 * Returns a random subject (person or page).
	 *
	 * @param Category $category Specific page category to look for; null if none specified or requesting a person
	 * @return Subject Random subject
	 */
	public function getRandomSubject($category = null) {
		if ($category) {
			$randomPages = $this->getRandomPage($category);
			return $randomPages[0];
		}
		
		return $this->getRandomFriend();
	}
	
	/**
	 * Parses JSON string into subjects.
	 *
	 * @param string $json JSON string to parse
	 * @return array Array of Subjects
	 */
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
	 *
	 * @param string $facebookId Facebook ID of user to get likes for
	 * @return array Array of likes
	 */
	public function getLikesOfFriend($facebookId = "") {
		if ($facebookId == "") { // Use logged in user's id.
			$facebookId = $this->getLoggedInUserId();
		}
		
		// Get API response.
		$likesResponse = Cache::requestFacebookAPIWithCaching('/'.$facebookId.'/likes');
		
		return FacebookAPI::jsonToSubjects($likesResponse['data']);
	}
	
	/*
	 * Returns the facebook subject's name for a given ID.
	 *
	 * @param string $facebookId Facebook ID for the subject
	 * @return string Name of the subject
	 */
	public function getNameFromId($facebookId = "") {
		if ($facebookId == "")	{
			$facebookId = $this->getLoggedInUserId();
		}
		
		// Get API response.
		$personResponse = Cache::requestFacebookAPIWithCaching('/'.$facebookId);
		return $personResponse['name'];
	}

	/*
	 * Returns the facebook app URL.
	 *
	 * @return string App URL
	 */
	public function getAppURL() {
		return $this->getFacebookURL(FB_APP_ID);
	}
	
	/*
	 * Returns a facebook object's URL.
	 *
	 * @param string $facebookId Facebook ID of the object
	 * @return string|bool URL if it exists, false otherwise
	 */
	public function getFacebookURL($facebookId)	{
		$content = file_get_contents('https://graph.facebook.com/'.$facebookId);
		$content = json_decode($content);
		
		if ($content->link) {
			return '<a href="'.$content->link.'" target="_blank">';
		} 
		
		return false;
	}	
	
	/**
	 * Log user in with given access token.
	 * 
	 * @param string $facebookAccessToken Facebook Access Token
	 * @return string|bool User Facebook ID if login is successful, false if no logged-in user.
	 */
	public function authenticate($facebookAccessToken) {
		$this->facebook->setAccessToken($facebookAccessToken);
		$this->facebookAccessToken = DB::cleanInputForDatabase($facebookAccessToken);
		
		// Invalid access token?
		if (!$this->getLoggedInUserId()) {
			return false;
		}
			
		// Update our database record for this user.
		$this->updateLoggedInUserDatabaseRecord();
			
		return $this->getLoggedInUserId();
	}
	
	/**
	 * Updates logged-in user's database record.
	 *
	 * @return bool True on successful query, false otherwise
	 */
	public function updateLoggedInUserDatabaseRecord() {
		$insertQuery = "INSERT INTO users (facebookId) VALUES ('".$this->getLoggedInUserId()."')";
		$result = mysql_query($insertQuery); // This query won't affect anything if the user already exists in the database.
		if (!result)	{
			return false;
		}
		
		$updateQuery = "UPDATE users SET lastVisitedAt = NOW() WHERE facebookId = '".$this->getLoggedInUserId()."' LIMIT 1";
		$result = mysql_query($updateQuery);
		if (!$result)	{
			JSON::outputFatalErrorAndExit("Unable to update logged in user's database record.");
			return false;
		}
		return true;
	}
	
	/**
	 * Returns the logged-in user's Facebook ID.
	 *
	 * @return string Facebook ID
	 */
	public function getLoggedInUserId() {
		if (!$this->facebookId) {
			$this->facebookId = $this->facebook->getUser();
		}
		
		return $this->facebookId;
	}
	
	/**
	 * Saves page to database.
	 *
	 * @param Subject $subject Page to be saved
	 * @return bool True on successful query, false otherwise
	 */
	public function insertPageIntoDatabase($subject)	{
		$replaceQuery = "REPLACE INTO pages SET id = '".$subject->facebookId."', name = '".DB::cleanInputForDatabase($subject->name)."', category = '".DB::cleanInputForDatabase($subject->category->facebookName)."';";
		$result = mysql_query($replaceQuery);
		
		if (!$result) {
			JSON::outputFatalErrorAndExit("Unable to save page to database.");
			return false;
		}
		return true;
	}
	
	public function api($requestString) {
		try {
			return $this->facebook->api($requestString);
		} catch (Exception $e) {
			Error::saveErrorToDB($e);
		}
		
		return null;
	}
}
?>