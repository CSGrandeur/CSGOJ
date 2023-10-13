<table
		class="bootstraptable_refresh_local"
		data-toggle="table"
		  data-url="__ADMIN__/news/news_list_ajax"
		  data-pagination="true"
		  data-page-list="[25, 50, 100]"
		  data-page-size="10"
		  data-side-pagination="client"
		  data-method="get"
		  data-search="true"
		  data-sort-name="news_id"
		  data-sort-order="desc"
		  data-pagination-v-align="both"
		  data-pagination-h-align="left"
		  data-pagination-detail-h-align="right"
		  data-search-align="center"
		data-cookie="true"
		data-cookie-id-table="{$OJ_SESSION_PREFIX}admin-newslist"
>
	<thead>
	<tr>
		<th data-field="news_id" data-align="center" data-valign="middle" data-sortable="true" data-width="55">ID</th>
		<th data-field="title" data-align="left" data-valign="middle" data-sortable="false" >Title</th>
		<th data-field="category_show" data-align="left" data-valign="middle" data-sortable="false" data-width="60">Category</th>
		<th data-field="defunct" data-align="center" data-valign="middle" data-sortable="false" data-width="80">Status</th>
		<th data-field="edit" data-align="left" data-valign="middle" data-sortable="false" data-width="60">Edit</th>
		<th data-field="user_id" data-align="left" data-valign="middle" data-sortable="false" data-width="60">Creator</th>
		<th data-field="time" data-align="left" data-valign="middle" data-sortable="false" data-width="160">Time</th>
	</tr>
	</thead>
</table>


{include file="admin/js_changestatus" /}
{include file="../../csgoj/view/public/refresh_in_table" /}