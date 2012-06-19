<div class="apiCommandSection" id="apiCommandSectionGetStatistics">
	<span class="apiCommandSectionTitle">getStatistics</span>
	<div class="apiCommandSectionTable" id="apiCommandSectionTableGetStatistics">
		<table>
			<tr>
				<td class="colParameter">cmd</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">&ldquo;getStatistics&rdquo;</td>
				<td class="colDefault"></td>
				<td>Generate statistics based on the authenticated user's history.</td>
			</tr>
			<tr>
				<td class="colParameter">facebookAccessToken</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">User's access token</td>
				<td class="colDefault"></td>
				<td>The access token for the user's current Facebook session.</td>
			</tr>
			<tr>
				<td class="colParameter">type</td>
				<td class="colType fontCode">string</td>
				<td class="colValue">&ldquo;<?php echo StatisticType::FRIENDS; ?>&rdquo;<br>&ldquo;<?php echo StatisticType::CATEGORIES; ?>&rdquo;<br>&ldquo;<?php echo StatisticType::HISTORY; ?>&rdquo;</td>
				<td class="colDefault">Default: &ldquo;<?php echo StatisticType::DEFAULT_TYPE; ?>&rdquo;</td>
				<td>Type of statistics to generate.</td>
			</tr>	
		</table>
		<div class="apiCommandSectionExample"><a href="?cmd=getStatistics&amp;type=<?php echo StatisticType::FRIENDS; ?>&amp;facebookAccessToken=xxx">?cmd=getStatistics&amp;type=<?php echo StatisticType::FRIENDS; ?>&amp;facebookAccessToken=xxx</a> - Hardcoded example<br /><a href="?cmd=getStatistics&amp;type=<?php echo StatisticType::CATEGORIES; ?>&amp;facebookAccessToken=xxx">?cmd=getStatistics&amp;type=<?php echo StatisticType::CATEGORIES; ?>&amp;facebookAccessToken=xxx</a> - Hardcoded example<br /><a href="?cmd=getStatistics&amp;type=<?php echo StatisticType::HISTORY; ?>&amp;facebookAccessToken=xxx">?cmd=getStatistics&amp;type=<?php echo StatisticType::HISTORY; ?>&amp;facebookAccessToken=xxx</a> - Hardcoded example
  </div>
	</div>
</div>
