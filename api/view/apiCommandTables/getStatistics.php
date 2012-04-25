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
				<td class="colValue">&ldquo;friends&rdquo;<br> &ldquo;history&rdquo;</td>
				<td class="colDefault">Default: &ldquo;friends&rdquo;</td>
				<td>Type of statistics to generate.</td>
			</tr>	
		</table>
		<div class="apiCommandSectionExample"><a href="?cmd=getStatistics&amp;facebookAccessToken=xxx&amp;type=friends">?cmd=getStatistics&amp;facebookAccessToken=xxx&amp;type=friends</a> - Hardcoded example.<br /><a href="?cmd=getStatistics&amp;facebookAccessToken=xxx&amp;type=history">?cmd=getStatistics&amp;facebookAccessToken=xxx&amp;type=history</a> - Hardcoded example.</div>
	</div>
</div>
