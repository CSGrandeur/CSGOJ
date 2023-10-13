<div class="row">
    <div class="col-md-8 col-sm-8">
        <table
            data-toggle="table"
            data-striped="true"
            data-classes="table-no-bordered table table-hover"
        >
            <thead>
            <tr>
                <th data-field="ac" data-align="center" data-valign="middle"  data-sortable="false" data-width="30"></th>
                <th data-field="problem_id" data-align="center" data-valign="middle"  data-sortable="false" data-width="70">ID</th>
                <th data-field="title" data-align="left" data-valign="middle"  data-sortable="false" data-width="500">Title</th>
                <!-- {if $OJ_OPEN_OI == true}
                <th data-field="pscore" data-align="right" data-valign="middle"  data-sortable="false" data-width="80">Score</th>
                {/if} -->
                <th data-field="accepted" data-align="right" data-valign="middle"  data-sortable="false" data-width="80">AC</th>
                <th data-field="submit" data-align="right" data-valign="middle"  data-sortable="false" data-width="100">Submit</th>
            </tr>
            </thead>
            <tbody>
            {foreach $problemList as $problem}
            <tr>
                {foreach $outputOrder as $item}
                <td>{$problem[$item]}</td>
                {/foreach}
            </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div class="col-md-4 col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title" style"float: left; display:inline-block;>Notification:
                {if $isContestAdmin}
                <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#page_modal">Change</button>
                {include file="../../csgoj/view/contest/change_notification" /}
                {/if}
                </h3>
            </div>
            <div class="panel-body" style="min-height:400px; max-height:450px; overflow-y: auto;">
                <article class="md_display_div" id="contest_notification_div">
                {$contest['description']}
                </article>
            </div>
        </div>
    </div>
</div>

{if IsAdmin('contest', $contest['contest_id'])}
{include file="../../csgoj/view/contest/change_notification" /}
{/if}
{include file="../../csgoj/view/public/mathjax_js" /}