<p class="help-block">
    Only top <strong class="text-danger">{$schoolRankTeamNum}</strong> team(s) of each school are considered.
</p>
{include file="../../csgoj/view/contest/rank_header" /}
<div id="ranklist_table_div">
    <div class="frozen_mask">Frozen</div>
    <table
        id="ranklist_table"
        data-toggle="table"
        data-side-pagination="client"
        data-method="get"
        data-striped="true"
        data-show-refresh="true"
        data-silent-sort="false"
        data-buttons-align="left"
        data-toolbar-align="left"
        data-sort-stable="true"
        data-show-export="true"
        data-toolbar="#ranklist_toobar"
        data-export-types="['excel', 'json', 'png']"
        data-export-options='{"fileName": "{$contest[\"contest_id\"]}-{$contest[\"title\"]}-SchoolRank"}'
    >
        <thead>
        <tr>
            <th data-field="rank" data-align="center" data-valign="middle"  data-sortable="true" data-sorter="rankSorter" data-width="60">Rank</th>
            <th data-field="school" data-align="left" data-valign="middle"  data-sortable="false" data-width="64" data-formatter="FormatterSchoolLogo" >Logo</th>
            <th data-field="school" data-align="center" data-valign="middle"  data-sortable="true" data-cell-style="schoolCellStyle">School</th>
            <th data-field="solved" data-align="center" data-valign="middle"  data-sortable="false" data-width="60">Solved</th>
            <th data-field="penalty" data-align="center" data-valign="middle"  data-sortable="false" data-width="80">Penalty</th>
            {foreach($problemIdMap['abc2id'] as $apid=>$pid)}
            <th data-field="{$apid}" data-align="center" data-valign="middle"  data-sortable="false" data-width="90" data-cell-style="acCellStyle" data-formatter="FormatterRankProSchool">
                <a href="/{$module}/{$controller}/problem?cid={$contest['contest_id']}&pid={$apid}">
                    {$apid}
                </a>
            </th>
            {/foreach}
            <th data-field="topteam" data-align="left" data-valign="middle"  data-sortable="false" data-cell-style="topteamCellStyle">Top Team</th>
        </tr>
        </thead>
    </table>
</div>
<input type="hidden" id="rank_config" url="schoolrank_ajax?cid={$contest['contest_id']}" use_cache="{$use_cache}" >
{include file="../../csgoj/view/contest/rank_footer" /}