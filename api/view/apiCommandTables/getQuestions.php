<div class="apiCommandSection" id="apiCommandSectionGetQuestions">
	<span class="apiCommandSectionTitle">getQuestions</span>
	<div class="apiCommandSectionTable" Id="apiCommandSectionTableGetQuestions">
		<table>
			<tr>
				<td class="colParameter">cmd</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">&ldquo;getQuestions&rdquo;</td>
				<td class="colDefault"></td>
				<td>Generate questions on what the authenticated user's friends like.</td>
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
				<td>Number of questions to generate.</td>
			</tr>
			<tr>
				<td class="colParameter">optionCount</td>
				<td class="colType fontCode">int</td>
				<td class="colValue"><?php echo OptionType::RANDOM; ?>: Random<br /><?php echo OptionType::FILL_IN_THE_BLANK; ?>: Fill in the blank<br /><?php echo OptionType::MC_MIN; ?>-<?php echo OptionType::MC_MAX; ?>: Multiple choice</td>
				<td class="colDefault">Default: <?php echo OptionType::DEFAULT_TYPE; ?></td>
				<td>How many options (if any) should come with each question?</td>
			</tr>
			<tr>
				<td class="colParameter">topicFacebookId</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">Topic's Facebook Id</td>
				<td class="colDefault">Default: All friends</td>
				<td>Should the questions focus on a specific friend or all friends?</td>
			</tr>
			<tr>
				<td class="colParameter">categoryId</td>
				<td class="colType fontCode">int</td>
				<td class="colValue"><a href="?cmd=getCategories">Get category list</a></td>
				<td class="colDefault">Default: All categories</td>
				<td>Should the questions focus on a particular category of likes like books or movies?</td>
			</tr>
		</table>
		<div class="apiCommandSectionExample"><a href="?cmd=getQuestions&amp;facebookAccessToken=xxx&amp;questionCount=1&amp;optionCount=<?php echo OptionType::MC_2; ?>&amp;categoryId=2">?cmd=getQuestions&amp;facebookAccessToken=xxx&amp;questionCount=1&amp;optionCount=<?php echo OptionType::MC_2; ?>&amp;categoryId=2</a> - Hardcoded example.</div>
	</div>
</div>