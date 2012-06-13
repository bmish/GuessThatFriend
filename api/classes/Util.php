<?php
/**
 * This class implements general utility functions.
 *
 *
 */
class Util {

	/**
	 * Calculates time taken to load.
	 *
	 * @param float $timeStart Start time
	 * @return float Duration
	 */
	public static function calculateLoadingDuration($timeStart) {
		return round(microtime(true) - $timeStart, 2);
	}
	
	/**
	 * Returns a random element in an array.
	 *
	 * @param array $arr Array containing the elements
	 * @return object Random element
	 */
	public static function getRandomElement($arr = null)	{
		if ($arr && count($arr) > 0) {
			return $arr[array_rand($arr, 1)];
		}
		
		return null;
	}
	
	// http://stackoverflow.com/questions/962915/how-do-i-make-an-asynchronous-get-request-in-php
	// $type must equal 'GET' or 'POST'
	function curl_request_async($url, $params, $type='POST')
	{
		foreach ($params as $key => &$val) {
		  if (is_array($val)) $val = implode(',', $val);
		  $post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$parts=parse_url($url);

		$fp = fsockopen($parts['host'],
		    isset($parts['port'])?$parts['port']:80,
		    $errno, $errstr, 30);

		// Data goes in the path for a GET request
		if('GET' == $type) $parts['path'] .= '?'.$post_string;

		$out = "$type ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		// Data goes in the request body for a POST request
		if ('POST' == $type && isset($post_string)) $out.= $post_string;

		fwrite($fp, $out);
		fclose($fp);
	}
	
	// http://webcheatsheet.com/php/get_current_page_url.php
	public static function curPageURLWithoutGETParams() {
	 	$pageURL = 'http';
	
	 	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	
	 	$pageURL .= "://";
	
	 	if ($_SERVER["SERVER_PORT"] != "80") {
	  		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
	 	} else {
	  		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
	 	}
	 	
		return $pageURL;
	}
}
?>