<table	data-toggle="table"
		data-url="__HOME__/index/news_list_ajax"
		data-pagination="true"
		data-page-list="[10, 20, 50]"
		data-page-size="10"
		data-side-pagination="server"
		data-method="get"
		data-search="true"
		data-sort-name="news_id"
		data-sort-order="desc"
		data-pagination-v-align="both"
		data-pagination-h-align="left"
		data-pagination-detail-h-align="right"
		data-search-align="right"
		data-cookie="true"
		data-cookie-id-table="{$OJ_SESSION_PREFIX}news-set"
	>
	<thead>
	<tr>
		<th data-field="news_id" data-align="center" data-valign="middle"  data-checkbox="false">ID</th>
		<th data-field="title" data-align="left" data-valign="middle"  data-sortable="false">公告标题</th>
		<th data-field="time" data-align="center" data-valign="middle"  data-sortable="false">更新时间</th>
		<th data-field="user_id" data-align="center" data-valign="middle"  data-sortable="false">编辑</th>
	</tr>
	</tr>
	</thead>
</table>