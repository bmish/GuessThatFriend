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
	
	public function getRandomFriend($facebookId = "") {
		if ($facebookId == "")	{
			$facebookId = $this->getLoggedInUserId();
		}
		return getRandomValue($this->getFriendsOf($facebookId));
	}
	
	public function getRandomFriendWhoLikes($facebookId = "") {
		if ($facebookId == "")	{
			return $this->getRandomFriend();
		}
		
		$friendsWhoLike = array();
		$friends = $this->getFriendsOf($this->getLoggedInUserId());
		foreach ($friends as $friend)	{
			if (likesPage($friend->facebookId, $facebookId))
				$friendsWhoLike[] = $friend;
		}
		return getRandomValue($friendsWhoLike);
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
		
		// select one random row
		$selectQuery .= " ORDER BY RAND() LIMIT 1";
			
		$result = mysql_query($selectQuery);
		if (!$result)	{
			return false;
		} else	{
			$page = mysql_fetch_assoc($result);
			$pageCategory = ($category == null) ? new Category(Category::getCategoryId($page['category'])) : $category;
			return new Subject($page['id'], $page['name'], $pageCategory);
		}
	}
	
	public function getRandomLikedPage($facebookId = "", $category = null)	{
		if ($facebookId != "")	{
			$likes = $this->getLikesOfFriend($facebookId);
			if ($category != null)	{
				$likesOfCategory = array();
				foreach ($likes as $like)	{
					if ($like->category->categoryId == $category->categoryId)
						$likesOfCategory[] = $like;
				}
				return getRandomValue($likesOfCategory);
			}
			else return getRandomValue($likes);
		}
		return null;
	}
	
	private function getRandomValue($arr = null)	{
		if ($arr != null)	{
			return (sizeof($arr) > 0) ? $arr[array_rand($arr, 1)] : null;
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
			$category = isset($json[$i]['category']) ? new Category(Category::getCategoryId($json[$i]['category'])) : null;
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
		
		if (!isset($this->likes[$facebookId])) {
			$likesResponse = $this->facebook->api('/'.$facebookId.'/likes?access_token='.$this->facebookAccessToken);

			// Store friend's likes so we won't have to look it up again.
			$this->likes[$facebookId] = FacebookAPI::jsonToSubjects($likesResponse['data']);
			
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
	$page1 = new Subject('123', 'Dummy Page', new Category(Category::getCategoryId('Movie')));
	echo "<p>page1 is person?: ".$page1->isPerson()."</p>";
	$page2 = new Subject('123', 'Dummy Page - New', new Category(Category::getCategoryId('Interest')));
	echo "<p>page2 is person?: ".$page2->isPerson()."</p>";
	$page3 = new Subject('456', 'Dummy Page 2', new Category(Category::getCategoryId('Movie')));
	echo "<p>page3 is person?: ".$page3->isPerson()."</p>";
	$fbapi = new FacebookAPI();
	$fbapi->insertPageIntoDatabase($page1);
	$fbapi->insertPageIntoDatabase($page2);
	$fbapi->insertPageIntoDatabase($page3);

	DB::close();
}
?>
