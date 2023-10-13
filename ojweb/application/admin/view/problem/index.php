<table
        class="bootstraptable_refresh_local"
        id="admin_problemlist_table"
        data-toggle="table"
          data-url="__ADMIN__/problem/problem_list_ajax"
          data-pagination="true"
          data-page-list="[25, 50, 100]"
          data-page-size="100"
          data-side-pagination="server"
          data-method="get"
          data-search="true"
          data-sort-name="problem_id"
          data-sort-order="asc"
          data-pagination-v-align="both"
          data-pagination-h-align="left"
          data-pagination-detail-h-align="right"
          data-search-align="center"
        data-cookie="true"
        data-cookie-id-table="{$OJ_SESSION_PREFIX}admin-problemlist"
        data-cookie-expire="5mi"
>
    <thead>
    <tr>
        <th data-field="problem_id" data-align="center" data-valign="middle"  data-sortable="true" data-width="55">ID</th>
        <th data-field="title"         data-align="left"     data-valign="middle"  data-sortable="false" >Title</th>
        <th data-field="source"     data-align="left"     data-valign="middle"  data-sortable="false" >Source</th>
        <th data-field="author"     data-align="left"     data-valign="middle"  data-sortable="false" data-width="80">Author</th>
        <th data-field="defunct"     data-align="center" data-valign="middle"  data-sortable="false" data-width="80">Status</th>
        <th data-field="edit"         data-align="center" data-valign="middle"  data-sortable="false" data-width="60" data-formatter="FormatterEdit">Edit</th>
		<th data-field="copy"		data-align="center" data-valign="middle"  data-formatter="FormatterCopy"  data-width="50">Copy</th>
        <th data-field="attach"     data-align="center" data-valign="middle"  data-sortable="false" data-width="60" data-formatter="FormatterAttach">Attach</th>
        <th data-field="testdata"     data-align="center" data-valign="middle"  data-sortable="false" data-width="60" data-formatter="FormatterTestData">Test</th>
        <th data-field="in_date"     data-align="center" data-valign="middle"  data-sortable="false" data-width="100">Date</th>
    </tr>
    </thead>
</table>
<script>
function FormatterAttach(value, row, index, field) {
    return `<a href="/admin/filemanager/filemanager?item=problem&id=${row.problem_id}" target="_blank">Attach</a>`;
}
function FormatterTestData(value, row, index, field) {
    return parseInt(value) ? `<a href='/admin/judge/judgedata_manager?item=problem&id=${row.problem_id}' target='_blank'>Data</a>` : `-`;
}
function FormatterEdit(value, row, index, field) {
    return parseInt(value) ? `<a href='/admin/problem/problem_edit?id=${row.problem_id}'>Edit</a>` : `-`;
}
function FormatterCopy(value, row, index, field) {
    return "<a href='__ADMIN__/problem/problem_copy?id=" + row['problem_id'] + "'>Copy</a>";
}
</script>
{include file="admin/js_changestatus" /}
{include file="../../csgoj/view/public/refresh_in_table" /}