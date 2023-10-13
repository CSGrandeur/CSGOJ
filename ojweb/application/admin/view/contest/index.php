<table
		class="bootstraptable_refresh_local"
		data-toggle="table"
		  data-url="__ADMIN__/contest/contest_list_ajax"
		  data-pagination="true"
		  data-page-list="[25, 50, 100]"
		  data-page-size="25"
		  data-side-pagination="client"
		  data-method="get"
		  data-search="true"
		  data-sort-name="contest_id"
		  data-sort-order="desc"
		  data-pagination-v-align="bottom"
		  data-pagination-h-align="left"
		  data-pagination-detail-h-align="right"
		  data-search-align="center"
		data-cookie="true"
		data-cookie-id-table="{$OJ_SESSION_PREFIX}admin-contestlist"
		data-cookie-expire="5mi"
>
	<thead>
	<tr>
		<th data-field="contest_id" data-align="center" data-valign="middle"  data-sortable="true" data-width="55">ID</th>
		<th data-field="title" 		data-align="left" 	data-valign="middle"  data-formatter="FormatterTitle"  >Title</th>
		<th data-field="private" 	data-align="center" data-valign="middle"  data-formatter="FormatterType"  data-width="100">Type</th>
		<th data-field="defunct" 	data-align="center" data-valign="middle"  data-formatter="FormatterStatus"  data-width="80">Status</th>
		<th data-field="edit"		data-align="center" data-valign="middle"  data-formatter="FormatterEdit"  data-width="50">Edit</th>
		<th data-field="copy"		data-align="center" data-valign="middle"  data-formatter="FormatterCopy"  data-width="50">Copy</th>
		<th data-field="attach" 	data-align="center" data-valign="middle"  data-formatter="FormatterAttach"  data-width="60">Attach</th>
		<th data-field="rejudge" 	data-align="center" data-valign="middle"  data-formatter="FormatterRejudge"  data-width="60">Rejudge</th>
		<th data-field="start_time" data-align="center" data-valign="middle"  data-width="100">Start Time</th>
		<th data-field="end_time" 	data-align="center" data-valign="middle"  data-width="100">End Time</th>
	</tr>
	</thead>
</table>

{include file="../../csgoj/view/contest/contest_table_formatter" /}
{include file="admin/js_changestatus" /}
{include file="../../csgoj/view/public/refresh_in_table" /}
