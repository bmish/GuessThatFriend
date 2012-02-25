<div class="apiCommandSection" id="apiCommandSectionGetQuiz">
	<span class="apiCommandSectionTitle">getQuiz</span>
	<div class="apiCommandSectionTable" Id="apiCommandSectionTableGetQuiz">
		<table>
			<tr>
				<td class="colParameter">cmd</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">&ldquo;getQuiz&rdquo;</td>
				<td class="colDefault"></td>
				<td>Generate a quiz on what the authenticated user's friends like.</td>
			</tr>
			<tr>
				<td class="colParameter">facebookAccessToken</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">User's access token</td>
				<td class="colDefault"></td>
				<td>The access token for the user's current Facebook session.</td>
			</tr>
			<tr>
				<td class="colParameter">questionCount</td>
				<td class="colType fontCode">int</td>
				<td class="colValue">1-100</td>
				<td class="colDefault">Default: 10</td>
				<td>Number of quiz questions to generate.</td>
			</tr>
			<tr>
				<td class="colParameter">optionCount</td>
				<td class="colType fontCode">int</td>
				<td class="colValue">-1: Random<br />0: Fill in the blank<br />2-6: Multiple choice</td>
				<td class="colDefault">Default: 4</td>
				<td>How many options (if any) should come with each question? Specifying '-1' means that each question will have a random number of options.</td>
			</tr>
			<tr>
				<td class="colParameter">friendFacebookId</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">Friend's Facebook Id</td>
				<td class="colDefault">Default: All friends</td>
				<td>Should the quiz focus on a specific friend or all friends?</td>
			</tr>
			<tr>
				<td class="colParameter">categoryId</td>
				<td class="colType fontCode">int</td>
				<td class="colValue"><a href="?cmd=getCategories">Get category list</a></td>
				<td class="colDefault">Default: All categories</td>
				<td>Should the quiz focus on a particular category of likes like books or movies?</td>
			</tr>
		</table>
		<div class="apiCommandSectionExample"><a href="?cmd=getQuiz&amp;facebookAccessToken=xxx&amp;questionCount=1&amp;optionCount=2&amp;categoryId=2">?cmd=getQuiz&amp;facebookAccessToken=xxx&amp;questionCount=1&amp;optionCount=2&amp;categoryId=2</a> - Hardcoded dummy example.</div>
	</div>
</div>