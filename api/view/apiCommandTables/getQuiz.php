<table class="apiCommandTable" Id="getQuizAPICommandTable">
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
		<td>getQuiz</td>
		<td>N/A</td>
		<td>Generate a quiz on what the authenticated user's friends like.</td>
	</tr>
	<tr>
		<td>questionCount</td>
		<td>int</td>
		<td>1-100</td>
		<td>10</td>
		<td>Number of quiz questions to generate.</td>
	</tr>
	<tr>
		<td>optionCount</td>
		<td>int</td>
		<td>-1: Random<br />0: Fill in the blank<br />2-6: Multiple choice</td>
		<td>4</td>
		<td>How many options (if any) should come with each question? Specifying '-1' means that each question will have a random number of options.</td>
	</tr>
	<tr>
		<td>friendFacebookId</td>
		<td>string</td>
		<td>Friend's Facebook Id</td>
		<td>All friends</td>
		<td>Should the quiz focus on a specific friend or all friends?</td>
	</tr>
	<tr>
		<td>categoryId</td>
		<td>int</td>
		<td><a href="?cmd=getCategories">Get category list</a></td>
		<td>All categories</td>
		<td>Should the quiz focus on a particular category of likes like books or movies?</td>
	</tr>
	<tr>
		<td colspan="5"><a href="?cmd=getQuiz&questionCount=1&optionCount=2">?cmd=getQuiz&questionCount=1&optionCount=2</a> (hardcoded dummy example)</td>
</table>