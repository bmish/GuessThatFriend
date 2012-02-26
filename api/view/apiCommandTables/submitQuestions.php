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
				<td class="colParameter">optionIdOfQuestion[X]</td>
				<td class="colType fontCode">int</td>
				<td class="colValue">Chosen optionId</td>
				<td class="colDefault">Default: Skipped</td>
				<td>The optionId of the option that was chosen for questionId X. Questions that were skipped can be excluded.</td>
			</tr>
		</table>
		<div class="apiCommandSectionExample"><a href="?cmd=submitQuestions&amp;facebookAccessToken=xxx&amp;optionIdOfQuestion11=12">?cmd=submitQuestions&amp;facebookAccessToken=xxx&amp;optionIdOfQuestion11=12</a> - Hardcoded dummy example.</div>
	</div>
</div>