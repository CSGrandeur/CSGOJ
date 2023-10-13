<table
        class="bootstraptable_refresh_local"
        id="user_list_table"
        data-toggle="table"
        data-url="__ADMIN__/usermanager/user_list_ajax"
        data-pagination="true"
        data-page-list="[25, 50, 100]"
        data-page-size="25"
        data-side-pagination="server"
        data-method="get"
        data-search="true"
        data-sort-name="reg_time"
        data-sort-order="desc"
        data-pagination-v-align="both"
        data-pagination-h-align="left"
        data-pagination-detail-h-align="right"
        data-search-align="center"
        data-cookie="true"
        data-cookie-id-table="{$OJ_SESSION_PREFIX}admin-userlist"
        data-cookie-expire="5mi"
>
    <thead>
    <tr>
        <th data-field="idx" data-align="right" 	data-valign="middle"  data-width="30" 	data-formatter="AutoId">Idx</th>
        <th data-field="user_id" 	data-align="left" 	data-valign="middle"  data-width="120"	data-formatter="UserIdFormatter">UserID</th>
        <th data-field="nick" 		data-align="left" 	data-valign="middle"  data-width="120">Nick</th>
        <th data-field="school" 	data-align="left" 	data-valign="middle"  data-width="150">School</th>
        <th data-field="email" 		data-align="left" 	data-valign="middle"  data-width="150">Email</th>
        <th data-field="submit" 	data-align="right" 	data-valign="middle"  data-width="60">Submit</th>
        <th data-field="solved" 	data-align="right" 	data-valign="middle"  data-width="60">Solved</th>
        <th data-field="reg_time" 	data-align="center" data-valign="middle"  data-width="150">Reg Time</th>
        <th data-field="edit" 		data-align="center" data-valign="middle"  data-width="80"	data-formatter="EditFormatter">Edit</th>
        <th data-field="del" 		data-align="center" data-valign="middle"  data-width="80"	data-formatter="DelFormatter">Del(DbClick)</th>
    </tr>
    </thead>
</table>
<script>
let user_list_table = $('#user_list_table');
function AutoId(value, row, index, field) {
    return index + 1;
}
function UserIdFormatter(value, row, index, field) {
    return `<a href='/csgoj/user/userinfo?user_id=${value}'>${value}</a>`;
}
function EditFormatter(value, row, index, field) {
    return "<a href='/csgoj/user/modify?user_id=" + row['user_id'] + "'><button class='btn btn-primary'>Edit</button></a>";
}
function DelFormatter(value, row, index, field) {
    return "<button class='btn btn-danger' user_id='" + row['user_id'] + "'>Delete</button>";
}
$(document).ready(function(){
    user_list_table.on('dbl-click-cell.bs.table', function(e, field, value, row, $element){
        $.get(
            '__ADMIN__/usermanager/user_del_ajax', 
            {'user_id': row.user_id},
            function(ret) {
                if(ret['code'] == 1) {
                    alertify.success(ret['msg']);
                    user_list_table.bootstrapTable('remove', {field: 'user_id', values: [row.user_id]});
                }
                else {
                    alertify.error(ret['msg']);
                }
            }
        )
    });
});
</script>
{include file="../../csgoj/view/public/refresh_in_table" /}
