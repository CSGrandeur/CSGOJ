<table
        id="news_detail_list_table"
        data-toggle="table"
          data-url="__HOME__/{$controller}/category_news_list_ajax"
          data-pagination="true"
          data-page-list="[10, 25, 50]"
          data-page-size="10"
          data-side-pagination="server"
          data-method="get"
          data-search="true"
          data-height="460"
        data-sort-name="news_id"
        data-sort-order="desc"
          data-detail-view="true"
          data-detail-formatter="detailFormatter"
          data-classes="table-no-bordered table table-hover"
          data-pagination-h-align="left"
          data-pagination-detail-h-align="right"
          data-search-align="center"
>
    <thead>
    <tr>
<!--        <th data-field="news_id" data-align="center" data-valign="middle" data-sortable="true" data-width="55">ID</th>-->
        <th data-field="title" data-align="left" data-valign="middle" data-sortable="true" data-cell-style="NewsCellStyle">Title</th>
        <th data-field="tags" data-align="center" data-valign="middle" data-sortable="false" data-cell-style="TagCellStyle">Tags</th>
        <th data-field="user_id" data-align="center" data-valign="middle" data-sortable="false" data-width="80">Creator</th>
        <!--        <th data-field="time" data-align="center" data-valign="middle" data-sortable="false" data-width="160">Time</th>-->
        <th data-field="modify_user_id" data-align="center" data-valign="middle" data-sortable="false" data-width="60">Editor</th>
        <th data-field="modify_time" data-align="center" data-valign="middle" data-sortable="false" data-width="160">Update Time</th>
    </tr>
    </thead>
</table>

<script type="text/javascript">

    $(window).keydown(function(e) {
        if (e.keyCode == 116 && !e.ctrlKey) {
            if(window.event){
                try{e.keyCode = 0;}catch(e){}
                e.returnValue = false;
            }
            e.preventDefault();
            $('#news_detail_list_table').bootstrapTable('refresh');
        }
    });
</script>
<script type="text/javascript">
    var table = $('#news_detail_list_table');
    table.on('post-body.bs.table', function(){
        table.bootstrapTable('resetView', {'height': this.scrollHeight + 120});
    });
    table.on('expand-row.bs.table', function(index, row, $detail){
        table.bootstrapTable('resetView', {'height': this.scrollHeight + 120});
    });
    table.on('collapse-row.bs.table', function(index, row, $detail){
        table.bootstrapTable('resetView', {'height': this.scrollHeight + 120});
    });
    function NewsCellStyle(value, row, index)
    {
        return {
            css: {'max-width': '650px'}
        };
    }
    function TagCellStyle(value, row, index)
    {
        return {
            css: {'max-width': '120px'}
        };
    }
    function detailFormatter(index, row) {
        var html = [];
        $.each(row, function (key, value) {
            if(key == 'content')
                html.push("<article class='md_display_div'>" + value + "</article>");
        });
        return html.join('');
    }
</script>