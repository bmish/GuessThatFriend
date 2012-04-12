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
				<td class="colDefault">Default: Skipped</td>
				<td>The facebookId of the option that was chosen for questionId X. Questions that were skipped can be excluded.</td>
			</tr>
			<tr>
				<td class="colParameter">responseTimeOfQuestion[X]</td>
				<td class="colType fontCode">int</td>
				<td class="colValue">User's response time in milliseconds</td>
				<td class="colDefault"></td>
				<td>The time it took the user to answer the question.</td>
		</table>
		<div class="apiCommandSectionExample"><a href="?cmd=submitQuestions&amp;facebookAccessToken=xxx&amp;facebookIdOfQuestion11=12&amp;responseTimeOfQuestion11=2500">?cmd=submitQuestions&amp;facebookAccessToken=xxx&amp;facebookIdOfQuestion11=12&amp;responseTimeOfQuestion11=2500</a> - Hardcoded dummy example.</div>
	</div>
</div>
