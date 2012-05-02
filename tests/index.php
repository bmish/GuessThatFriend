<?php
require_once('simpletest/autorun.php');

class APIIntegrationTests extends UnitTestCase {
	var $fbtok;
	
	/**
	 * getQuizHelper tests the getQuiz API call with passed arguments
	 * @param integer $qCount the number of questions requested
	 * @param integer $oCount the option number
	 * @param integer $cat the category id for the questions
	 * 
	 * @return the last question number returned
	 */
	function getQuizHelper($qCount, $oCount, $cat) {
		$this->fbtok = $_GET['facebookAccessToken'];
		$url = 'http://guessthatfriend.jasonsze.com/api/';
		$addr=$url.'?cmd=getQuestions&facebookAccessToken='.$this->fbtok.'&questionCount='.$qCount.'&optionCount='.$oCount.'&categoryId='.$cat;

		$jsonFile = fopen($addr, 'r');
		$content = NULL;
		while ($out = fread($jsonFile, 1024)) $content .= $out;
		fclose($jsonFile);
		$json=json_decode($content);
		
		$question = NULL;
		foreach ($json->questions as $question) {
			$opNum = sizeof($question->options);
			$this->assertEqual($opNum, $oCount);
		}
		$this->assertEqual($qCount, sizeof($json->questions));
		return $question->questionId;
	}

	/**
	 * submitQuizHelper tests the submitQuiz API call with passed argument
	 * 
	 * @param integer $questionId the question number we are answering.
	 */
	function submitQuizHelper($questionId) {
		$this->fbtok = $_GET['facebookAccessToken'];
		$url = 'http://guessthatfriend.jasonsze.com/api/';
		$addr = $url.'?cmd=submitQuestions&facebookAccessToken='.$this->fbtok.'&facebookIdOfQuestion'.$questionId.'=1';
		$jsonFile = fopen($addr, 'r');
		$content = NULL;
		while ($out = fread($jsonFile, 1024)) $content .= $out;
		fclose($jsonFile);

		$json = json_decode($content);

		$this->assertEqual($json->questionIds[0], $questionId);
	}

	/**
	 * testGetQuizOneQuestion tests getQuiz call with Questions=1,Options=2,Category=2
	 */
        function testGetQuizOneQuestion() {
                echo 'Questions=1,Options=2,Category=2<br><br>';
		$this->getQuizHelper(1,2,2);
	}

	/**
	* testGetQuizFiveQuestion tests getQuiz call with Questions=5,Options=4,Category=3
	*/
        function testGetQuizFiveQuestions() {
                echo 'Questions=5,Options=4,Category=3<br><br>';
		$this->getQuizHelper(5,4,3);
	}

	/**
	 * testGetQuizTenQuestions tests getQuiz call with Questions=10,Options=4,Category=3
	 */
        function testGetQuizTenQuestions() {
                echo 'Questions=10,Options=4,Category=3<br><br>';
		$this->getQuizHelper(10,5,3);
	}

	/**
	* testGetQuizFifteenQuestions tests getQuiz call with Questions=15,Options=6,Category=3
	*/
	function testGetQuizFifteenQuestions() {
                echo 'Questions=15,Options=6,Category=3<br><br>';
		$this->getQuizHelper(15,6,3);
	}

	/**
	 * testSubmitQuizQuestion1 tests submitQuiz call with a single valid question number
	 */
	function testSubmitQuizQuestion1() {
        $qID = intval($this->getQuizHelper(1,2,2));
        echo 'QuestionID='.$qID.'<br><br>';
		$this->submitQuizHelper($qID);
	}
	
	/**
	 * testSubmitQuizQuestionMultiple tests submitQuiz call with many valid question numbers
	 */
	function testSubmitQuizMultiple() {
       $i=1;
       while ($i<10){
       		$qID = intval($this->getQuizHelper(1,2,2));
        	echo 'QuestionID='.$qID.'<br><br>';
			$this->submitQuizHelper($qID);
			$i++;
		}
	}
	
	/**
	 * testSubmitQuizQuestionMultipleDiff tests submitQuiz call with many 
	 */
	function testSubmitQuizQuestionMultipleDiff() {
       $i=1;
       while ($i<10){
       		$qID = intval($this->getQuizHelper($i,2,2));
        	echo 'QuestionID='.$qID.'<br><br>';
			$this->submitQuizHelper($qID);
			$i++;
		}
	}
	
}
?>
