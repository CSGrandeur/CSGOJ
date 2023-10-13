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
        id="problemset_table"
        data-toggle="table"
          data-url="__OJ__/problemset/problemset_ajax"
          data-pagination="true"
          data-page-list="[25,50,100]"
          data-page-size="25"
          data-side-pagination="server"
          data-method="get"
          data-striped="true"
          data-search="true"
          data-search-align="left"
          data-sort-name="problem_id"
          data-sort-order="asc"
          data-pagination-v-align="both"
          data-pagination-h-align="left"
          data-pagination-detail-h-align="right"
          data-classes="table-no-bordered table table-hover"
          data-cookie="true"
          data-cookie-id-table="{$OJ_SESSION_PREFIX}problemset-problemlist{if(session('?user_id'))}<?php echo '-'.session('user_id'); ?>{/if}"
          data-cookie-expire="1m"
        data-show-refresh="true"
        data-buttons-align="left"
        data-toolbar="#table_toolbar"
>
    <thead>
    <tr>
        <th data-field="ac" data-align="center" data-valign="middle"  data-sortable="false" data-width="30" data-formatter="FormatterProblemAc"></th>
        <th data-field="problem_id" data-align="center" data-valign="middle"  data-sortable="true" data-width="55">ID</th>
        <th data-field="title" data-align="left" data-valign="middle"  data-sortable="false" data-formatter="FormatterProblemTitle">Title</th>
        <th data-field="source" data-align="left" data-valign="middle"  data-sortable="false" data-formatter="FormatterSource">Source</th>
        <th data-field="accepted" data-align="right" data-valign="middle"  data-sortable="true" data-width="80">AC</th>
        <th data-field="submit" data-align="right" data-valign="middle"  data-sortable="true" data-width="100">Submit</th>
    </tr>
    </thead>
</table>
<script type="text/javascript">
function FormatterProblemAc(value, row, index, field) {
    if('ac' in row) {
        return row['ac'] == 1 ? "<span class='text-success'>Y</span>" : "<span class='text-warning'>N</span>";
    } else {
        return "";
    }
}
function FormatterProblemTitle(value, row, index, field) {
    return "<a href='/csgoj/problemset/problem?pid=" + row['problem_id'] +  "'" + (row['spj'] == '1' ? " class='red-link' " : "")  + ">" + value + "</a>";
}
function FormatterSource(value, row, index) {
    let tmpv = value.replace(/(<([^>]+)>)/ig, "");
    if("<p>" + tmpv + "</p>" == value) {
        let search_url = "/csgoj/problemset#search=" + tmpv;
        return "<a href='" + search_url + "'>" + tmpv + "</a>";
    }
    return value;
}
let toobar_ok = $('#toobar_ok');
let page_jump_input = $('#page_jump_input');
let problemset_table = $('#problemset_table');
let search_cookie_name = problemset_table.attr('data-cookie-id-table') + ".bs.table.searchText"
let search_input;
// set table cookie before table rendered for each parameter. Warning: maybe become invalid after bootstrap-table updated.
SetProblemFilter(null, true);

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
            else if(jump_page > problemset_table.bootstrapTable('getOptions')['totalPages'])
                jump_page = problemset_table.bootstrapTable('getOptions')['totalPages'];
        }
        problemset_table.bootstrapTable('selectPage', jump_page);
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
        problemset_table.bootstrapTable('refresh');
    }
});
function SetProblemFilter(search_input=null, onlycookie=false) {
    let search_str = GetAnchor("search");
    if(search_str !== null) {
        document.cookie = [
            search_cookie_name, '=', search_str
        ].join('');
        if(!onlycookie && search_input !== null) {
            search_input.val(search_str).trigger('keyup');
        }
    }
}
// when click source
$(window).on('hashchange', function(e) {
    search_str = GetAnchor("search");
    search_input.val(search_str).trigger('keyup');
});
$(document).ready(function(){
    page_jump_input.val(problemset_table.bootstrapTable('getOptions')['pageNumber']);
    search_input = $(".search>input[placeholder='Search']");
    search_input.on('input', function() {
        SetAnchor(search_input.val(), 'search');
    });
    // SetProblemFilter(search_input);
});
</script>
<style type="text/css">
    .fixed-table-toolbar {
        display: flex
    }
    .fixed-table-toolbar .columns {
        order: -1;
    }
</style>
