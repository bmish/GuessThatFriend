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
		
		// Check current in-memory cache.
		if (!isset($this->friendsCache[$facebookId])) {
			// Check filesystem cache.
			if (!($friendsResponse = Cache::checkForCachedAPIRequest('/'.$facebookId.'/friends'))) {
				$friendsResponse = $this->facebook->api('/'.$facebookId.'/friends');
				Cache::cacheAPIRequest('/'.$facebookId.'/friends', $friendsResponse);
			}

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
			if (++$triesCount == $MAX_TRIES) {
				API::outputFailure("Could not find a friend with a sufficient number of likes.");
				return null;
			}
			
			$friend = $this->getRandomElement($friends);
			$likesSubjects = $this->getLikesOfFriend($friend->facebookId);
		} while (count($likesSubjects) < $MIN_ACCEPTABLE_LIKES);
		
		return $friend;
	}
	
	/*
	 * Returns a random friend who likes/ not like a page.
	 * @param facebookId facebook id of the page
	 * @param likeFlag boolean flag indicating whether the returned friend should like or not like the page
	 * @return the random friend (subject)
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
	
	/*
	 * Returns a list of random pages.
	 * @param category - the category of pages to return
	 * @param count - the number of pages to return; default to 1
	 * @param facebookId - the facebook Id of the friend to check for 'likes' condition; null if no check required
	 * @return the list of random pages
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
			$pagefacebookId = $page['facebookId'];
			if (!in_array($pagefacebookId, $checkedPages))	{
				if (($faceboookId == null) || (!likesPage($facebookId, $pagefacebookId)))	{
					$pageCategory = ($category == null) ? Category::getCategoryByFacebookName($page['categoryFacebookName']) : $category;
					$pages[] = new Subject($pagefacebookId, $page['name'], $pageCategory);
				}
				$checkedPages[] = $pagefacebookId;
			}
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
				if (++$triesCount == $MAX_TRIES) {
					API::outputFailure("The randomPages database table may not contain a large enough variety of random pages.");
					return null;
				}
				$like = $this->getRandomElement($likes);
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
			$randomPages = $this->getRandomPage($category);
			return $randomPages[0];
		}
		
		return $this->getRandomFriend();
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
		
		// Check current in-memory cache.
		if (!isset($this->likesCache[$facebookId])) {
			// Check filesystem cache.
			if (!($likesResponse = Cache::checkForCachedAPIRequest('/'.$facebookId.'/likes'))) {
				$likesResponse = $this->facebook->api('/'.$facebookId.'/likes');
				Cache::cacheAPIRequest('/'.$facebookId.'/likes', $likesResponse);
			}

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
		
		// Check current in-memory cache.
		if (!isset($namesCache[$facebookId])) {
			// Check filesystem cache.
			if (!($personResponse = Cache::checkForCachedAPIRequest('/'.$facebookId))) {
				$personResponse = $this->facebook->api('/'.$facebookId);
				Cache::cacheAPIRequest('/'.$facebookId, $personResponse);
			}
			
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
		$this->facebookAccessToken = DB::cleanInputForDatabase($facebookAccessToken);
		
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
?>