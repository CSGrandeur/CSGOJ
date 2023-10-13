<div id="article_list_div">
<table
    id="article_list_table"
    data-toggle="table"
    data-url="__HOME__/{$controller}/category_news_list_ajax"
    data-pagination="true"
    data-page-list="[10, 25, 50]"
    data-page-size="10"
    data-side-pagination="client"
    data-method="get"
    data-search="true"
    data-striped="true"
    data-classes="table-no-bordered table table-hover"
    data-pagination-h-align="left"
    data-pagination-detail-h-align="right"
    data-search-align="center"
>
    <thead>
    <tr>
        <th data-field="news_id" data-align="center" data-valign="middle" data-sortable="true" data-width="55">ID</th>
        <th data-field="title" data-align="left" data-valign="middle" data-sortable="false" data-formatter="FormatterTitle">Title</th>
        <th data-field="tags" data-align="center" data-valign="middle" data-sortable="false" data-cell-style="TagCellStyle" data-formatter="FormatterTags">Tags</th>
        <th data-field="user_id" data-align="center" data-valign="middle" data-sortable="false" data-width="80" data-formatter="FormatterUser">Creator</th>
<!--        <th data-field="time" data-align="center" data-valign="middle" data-sortable="false" data-width="160">Time</th>-->
        <th data-field="modify_user_id" data-align="center" data-valign="middle" data-sortable="false" data-width="60" data-formatter="FormatterUser">Editor</th>
        <th data-field="modify_time" data-align="center" data-valign="middle" data-sortable="false" data-width="160">Update Time</th>
    </tr>
    </thead>
</table>
</div>
<input type="hidden" id="page_info" category="{$category}">
<script type="text/javascript">
let page_info = $('#page_info');
let page_category = page_info.attr('category');
function FormatterUser(value, row, index, field) {
    return `<a href='/csgoj/user/userinfo?user_id=${value}' target='_blank'>${value}</a>`;
}
function FormatterTitle(value, row, index, field) {
    return `<a href='/index/${page_category}/detail?nid=${row.news_id}' title='${value}' class='article-title-in-table'>${value}</a>`;
}
function FormatterTags(value, row, index, field) {
    if(value == null) {
        value = '';
    }
    return `<span title='${value}' class='tags-in-table'>${value}</span>`;
}
    $(window).keydown(function(e) {
        if (e.keyCode == 116 && !e.ctrlKey) {
            if(window.event){
                try{e.keyCode = 0;}catch(e){}
                e.returnValue = false;
            }
            e.preventDefault();
            $('#article_list_table').bootstrapTable('refresh');
        }
    });
</script>
<script type="text/javascript">

    var article_list_table = $('#article_list_table');
    var article_list_div = $('#article_list_div');
    article_list_table.on('post-body.bs.table', function(){
        //处理rank宽度
        if(article_list_table[0].scrollWidth > article_list_div.width())
            article_list_div.width(article_list_table[0].scrollWidth + 20)
    });
    function TagCellStyle(value, row, index)
    {
        return {
            css: {'max-width': '120px'}
        };
    }
</script>