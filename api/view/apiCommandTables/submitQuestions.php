<div class="apiCommandSection" id="apiCommandSectionSubmitQuestions">
	<span class="apiCommandSectionTitle">submitQuestions</span>
	<div class="apiCommandSectionTable" id="apiCommandSectionTableSubmitQuestions">
		<table>
			<tr>
				<td class="colParameter">cmd</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">&ldquo;submitQuestions&rdquo;</td>
				<td class="colDefault"></td>
				<td>Submit questions that the authenticated user answered.</td>
			</tr>
			<tr>
				<td class="colParameter">facebookAccessToken</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">User's access token</td>
				<td class="colDefault"></td>
				<td>The access token for the user's current Facebook session.</td>
			</tr>
			<tr>
				<td class="colParameter">facebookIdOfQuestion[X]</td>
				<td class="colType fontCode">int</td>
				<td class="colValue">Chosen facebookId</td>
				<td class="colDefault"></td>
				<td>The facebookId of the option that was chosen for questionId X.</td>
			</tr>
			<tr>
				<td class="colParameter">responseTimeOfQuestion[X]</td>
				<td class="colType fontCode">int</td>
				<td class="colValue">User's response time in milliseconds</td>
				<td class="colDefault"></td>
				<td>The time it took the user to answer the question.</td>
			</tr>
			<tr>
				<td class="colParameter">skipQuestionIds[]</td>
				<td class="colType fontCode">int</td>
				<td class="colValue">QuestionId to skip</td>
				<td class="colDefault"></td>
				<td>The questions that the user chose to skip (one per parameter).</td>
			</tr>
		</table>
		<div class="apiCommandSectionExample"><a href="?cmd=submitQuestions&amp;facebookAccessToken=xxx&amp;facebookIdOfQuestion11=12&amp;responseTimeOfQuestion11=2500&amp;skipQuestionIds[]=12&amp;skipQuestionIds[]=13">?cmd=submitQuestions&amp;facebookAccessToken=xxx&amp;facebookIdOfQuestion11=12&amp;responseTimeOfQuestion11=2500&amp;skipQuestionIds[]=12&amp;skipQuestionIds[]=13</a> - Hardcoded example.</div>
	</div>
</div>
