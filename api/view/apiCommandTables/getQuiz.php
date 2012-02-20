<table class="apiCommandTable" id="getQuizAPICommandTable">
	<tr>
		<th>GET Parameter</th>
		<th>Type</th>
		<th>Value</th>
		<th>Default</th>
		<th>Description</th>
	</tr>
	<tr>
		<td>cmd</td>
		<td>string</td>
		<td>getQuiz</td>
		<td>N/A</td>
		<td>N/A</td>
	</tr>
	<tr>
		<td>questionCount</td>
		<td>int</td>
		<td>1-100</td>
		<td>10</td>
		<td>Number of quiz questions to generate.</td>
	</tr>
	<tr>
		<td>answerCount</td>
		<td>int</td>
		<td>-1: Random<br />0: Fill in the blank<br />2-6: Multiple choice</td>
		<td>4</td>
		<td>How many choices (if any) should the user have to choose from for each question? Specifying '-1' means that each question will have a random number of choices.</td>
	</tr>
	<tr>
		<td>friendFacebookID</td>
		<td>string</td>
		<td>Friend's Facebook ID</td>
		<td>All friends</td>
		<td>Should the quiz focus on a specific friend or all of the user's friends?</td>
	</tr>
	<tr>
		<td>topicFacebookID</td>
		<td>string</td>
		<td>Topic's Facebook ID</td>
		<td>All topics</td>
		<td>Should the quiz focus on a particular topic like books or movies?</td>
	</tr>
	<tr>
		<td colspan="5"><a href="?cmd=getQuiz&questionCount=1&answerCount=2">?cmd=getQuiz&questionCount=1&answerCount=2</a> (hardcoded dummy example)</td>
</table>