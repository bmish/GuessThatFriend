<table class="apiCommandTable" id="getQuizAPICommandTable">
	<tr>
		<th class="colParameter">GET Parameter</th>
		<th class="colType">Type</th>
		<th class="colValue">Value</th>
		<th class="colDefault">Default</th>
		<th class="colDescription">Description</th>
	</tr>
	<tr>
		<td>cmd</td>
		<td>string</td>
		<td>submitQuiz</td>
		<td>N/A</td>
		<td>Submit a quiz that the authenticated user generated.</td>
	</tr>
	<tr>
		<td>quizId</td>
		<td>int</td>
		<td>quizId</td>
		<td>N/A</td>
		<td>The quizId of the quiz.</td>
	</tr>
	<tr>
		<td>optionIdOfQuestion[X]</td>
		<td>int</td>
		<td>optionId</td>
		<td>Skipped</td>
		<td>The optionId of the option that was chosen for questionId X. Questions that were skipped can be excluded.</td>
	</tr>
	<tr>
		<td colspan="5"><a href="?cmd=submitQuiz&optionIdOfQuestion11=12">?cmd=submitQuiz&quizId=10&optionIdOfQuestion11=12</a> (hardcoded dummy example)</td>
</table>