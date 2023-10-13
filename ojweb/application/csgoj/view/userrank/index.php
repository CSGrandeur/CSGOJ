<div id="table_toolbar">
    <div class="form-inline fake-form" role="form">
        &nbsp;
        <button id="toobar_ok" type="button" class="btn btn-default">Jump To</button>
        <div class="form-group">
            <span>Page:</span>
            <input id="page_jump_input" name="page_jump" class="form-control" type="text" style="max-width:50px;" value="1">
        </div>
        &nbsp;&nbsp;
    </div>
</div>
<table
        class="bootstraptable_refresh_local"
        id="userrank_table"
        data-toggle="table"
        data-url="__OJ__/userrank/userrank_ajax"
        data-pagination="true"
        data-page-list="[50,100]"
        data-page-size="50"
        data-side-pagination="server"
        data-method="get"
        data-query-params="QueryParams"
        data-striped="true"
        data-search="true"
        data-search-align="left"
        data-pagination-v-align="bottom"
        data-pagination-h-align="left"
        data-pagination-detail-h-align="right"
        data-classes="table-no-bordered table table-hover"
        data-show-refresh="true"
        data-buttons-align="left"
        data-toolbar="#table_toolbar"
    >
    <thead>
    <tr>
        <th data-field="rank"       data-align="center" data-valign="middle"  data-sortable="false" data-width="55" data-formatter="FormatterRank">Rank</th>
        <th data-field="user_id"    data-align="left" data-valign="middle"  data-sortable="false"   data-width="200" data-formatter="FormatterUserId">User ID</th>
        <th data-field="nick"       data-align="left" data-valign="middle"  data-sortable="false"   data-formatter="FormatterDomSantize">Nick Name</th>
        <th data-field="school"     data-align="left" data-valign="middle"  data-sortable="false"   data-formatter="FormatterDomSantize">School</th>
        <th data-field="solved"     data-align="left" data-valign="middle"  data-sortable="false"   data-width="80">Solved</th>
        <th data-field="submit"     data-align="left" data-valign="middle"  data-sortable="false"   data-width="100">Submit</th>
        <th data-field="ratio"      data-align="left" data-valign="middle"  data-sortable="false"   data-width="100" data-formatter="FormatterRatio">Ratio</th>
    </tr>
    </thead>
</table>
{include file="../../csgoj/view/public/refresh_in_table" /}
<script src="__STATIC__/csgoj/tt_formatter.js"></script>
<script type="text/javascript">
var toobar_ok = $('#toobar_ok');
var page_jump_input = $('#page_jump_input');
var userrank_table = $('#userrank_table');
let query_params = {};
function FormatterUserId(value, row, index, field) {
    let tt_info_html = TtFormatter(row.volume, false);
    return `<a href='/csgoj/user/userinfo?user_id=${value}' class='rank_userid'>${value} <sup title="In School Training Team">${tt_info_html}</sup></a>`;
}
function FormatterRatio(value, row, index, field) {
    const submit_num = parseInt(row.submit);
    const solved_num = parseInt(row.solved);
    if(!isNaN(submit_num) && !isNaN(solved_num) && submit_num > 0) {
        return `${(solved_num * 100 / submit_num).toFixed(3)}%`;
    }
    return '-';
}
function FormatterRank(value, row, index, field) {
    return query_params.offset + index + 1;
}
function QueryParams(params) {
    console.log(params);
    query_params = params
    return params;
}
toobar_ok.on('click', function(){
    JumpPage();
});
page_jump_input.on('keydown', function(e){
    if(e.keyCode == '13') {
        JumpPage();
    }
});
function JumpPage()
{
    var jump_page = page_jump_input.val();
    if(typeof(jump_page) != 'undefined' && !isNaN(jump_page))
    {
        if(jump_page.length == 0)
            jump_page = 1;
        else
        {
            jump_page = parseInt(jump_page);
            if(jump_page < 1)
                jump_page = 1;
            else if(jump_page > userrank_table.bootstrapTable('getOptions')['totalPages'])
                jump_page = userrank_table.bootstrapTable('getOptions')['totalPages'];
        }
        userrank_table.bootstrapTable('selectPage', jump_page);
        page_jump_input.val(jump_page);
    }
}
$(window).keydown(function(e) {
    if (e.keyCode == 116 && !e.ctrlKey) {
        if(window.event){
            try{e.keyCode = 0;}catch(e){}
            e.returnValue = false;
        }
        e.preventDefault();
        RefreshTable();
    }
});

function RefreshTable()
{
    $('#userrank_table').bootstrapTable('refresh');
}
</script>
<style type="text/css">
    .fixed-table-toolbar {
        display: flex
    }
    .fixed-table-toolbar .columns {
        order: -1;
    }
    .rank_userid {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
