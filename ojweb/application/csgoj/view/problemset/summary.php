{include file="problemset/problem_header" /}
<div id="summary_div">
    <div class="md_display_div" id="summary_statistic">
        <table >
            <h3>Statistic</h3>
            <thead>
            <tr><th></th><th></th></tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <a href="__OJ__/status?problem_id={$problem['problem_id']}">Total Submissions</a>
                </td class="textright">
                <td>{$statistic['total_submissions']}</td>
            </tr>
            <tr>
                <td>Users Submitted</td>
                <td class="textright">{$statistic['users_submitted']}</td>
            </tr>
            <tr>
                <td>Users Solved</td>
                <td class="textright">{$statistic['users_solved']}</td>
            </tr>
            <?php foreach($ojResultsHtml as $key=>$value):
                 if($key == 13) break;
            ?>
            <tr>
                <td>
                    <a href="__OJ__/status?problem_id={$problem['problem_id']}&result={$key}">{$value[1]}</a>
                </td>
                <td class="textright">
                    {$statistic[$key]}
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div id="summary_rank">
        <div id="summary_toolbar">
            <div class="form-inline fake-form" role="form">
                <h3 style="display: inline-block; line-height: 0px;">Solution Rank</h3>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="form-group">
                    <span>Language:</span>
                    <select id="language_select" name="language" class="form-control">
                        <option value="-1" selected="true">
                            All
                        </option>
                        {foreach($allowLanguage as $key=>$value)}
                        <option value="{$key}">
                            {$value}
                        </option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div id="summary_table_div">
            <table
                    class="bootstraptable_refresh_local"
                    id="summary_table"
                      data-toggle="table"
                      data-url="__OJ__/problemset/summary_ajax?pid={$problem['problem_id']}"
                      data-pagination="true"
                      data-page-list="[20]"
                      data-page-size="20"
                      data-side-pagination="server"
                      data-method="get"
                      data-striped="true"
                      data-sort-name="time"
                      data-sort-order="asc"
                      data-pagination-v-align="bottom"
                      data-pagination-h-align="left"
                      data-pagination-detail-h-align="right"
                      data-toolbar-align="left"
                      data-toolbar="#summary_toolbar"
                      data-query-params="queryParams"
            >
                <thead>
                <tr>
                    <th data-field="rank" data-align="center" data-valign="middle"  data-sortable="false" data-width="20">Rank</th>
                    <th data-field="solution_id" data-align="center" data-valign="middle"  data-sortable="false" data-width="20">RunID</th>
                    <th data-field="user_id" data-align="center" data-valign="middle"  data-sortable="false" >User</th>
                    <th data-field="memory" data-align="right" data-valign="middle"  data-sortable="true" data-width="80">Memory(kB)</th>
                    <th data-field="time" data-align="right" data-valign="middle"  data-sortable="true" data-width="80">Time(ms)</th>
                    <th data-field="language" data-align="center" data-valign="middle"  data-sortable="false" data-width="80">Language</th>
                    <th data-field="code_length" data-align="right" data-valign="middle"  data-sortable="true" data-width="80">Length</th>
                    <th data-field="in_date" data-align="center" data-valign="middle"  data-sortable="false"  data-width="160">Submit Time</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
<style type="text/css">
    .problem_statistic td
    {
        white-space:nowrap;
    }
    #summary_div
    {
        position: relative;
        min-height: 400px;
    }
    #summary_statistic
    {
        position: absolute;
        left: 0;
    }
    #summary_rank
    {
        margin-left: 260px;
    }
    .textright
    {
        text-align: right;
    }
</style>
<script type="text/javascript">

    var summary_table = $('#summary_table');
    var summary_table_div = $('#summary_table_div');
    var summary_toolbar = $('#summary_toolbar');
    summary_table.on('post-body.bs.table', function(){
        if(summary_table[0].scrollWidth > summary_table_div.width())
            summary_table_div.width(summary_table[0].scrollWidth + 20);
    });
    function queryParams(params) {
        summary_toolbar.find('input[name]').each(function () {
            params[$(this).attr('name')] = $(this).val();
        });
        summary_toolbar.find('select[name]').each(function () {
            params[$(this).attr('name')] = $(this).val();
        });
        return params;
    }
    $('#language_select').on('change', function(){
        summary_table.bootstrapTable('refresh', {silent: true});
    });
</script>
{include file="../../csgoj/view/public/refresh_in_table" /}