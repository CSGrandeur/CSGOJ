{include file="../../csgoj/view/contest/rank_header" /}
<div id="ranklist_table_div">
    <div class="frozen_mask">Frozen</div>
    <table
            id="ranklist_table"
            data-toggle="table"
            data-side-pagination="client"
            data-method="post"
            data-striped="true"
            data-show-refresh="true"
            data-silent-sort="false"
            data-sort-name="score"
            data-sort-order="desc"
            data-buttons-align="left"
            data-toolbar-align="left"
            data-sort-stable="true"
            data-show-export="true"
            data-toolbar="#ranklist_toobar"
            data-export-types="['excel', 'csv', 'json', 'png']"
            data-export-options='{"fileName":"{$contest[\"contest_id\"]}-{$contest[\"title\"]}-Scorerank"}'
    >
        <thead>
        <tr>
            <th data-field="sn" data-align="center" data-valign="middle"  data-sortable="true" data-sorter="rankSorter" data-width="60" data-formatter="IndexFormatter" data-cell-style="rankCellStyle">SN</th>
            <th data-field="user_id" data-align="left" data-valign="middle"  data-sortable="true" data-width="100">ID</th>
            {if $module == 'cpcsys' }
            <th data-field="nick" data-align="left" data-valign="middle"  data-sortable="true" data-width="100"  data-cell-style="CellStyleName">Name</th>
            {/if}
            <th data-field="score" data-align="center" data-valign="middle"  data-sortable="true" data-width="60">Score</th>
            {foreach($problemIdMap['abc2id'] as $apid=>$pid)}
            <th data-field="{$apid}" data-align="center" data-valign="middle"  data-sortable="true" data-width="90" data-cell-style="acCellStyle">
                <a href="problem?cid={$contest['contest_id']}&pid={$apid}">
                    {$apid}({$problemIdMap['id2score'][$pid]}')
                </a>
            </th>
            {/foreach}
            <th data-field="solved" data-align="center" data-valign="middle"  data-sortable="true" data-width="60">Solved</th>
            <th data-field="penalty" data-align="center" data-valign="middle"  data-sortable="true" data-width="80">Penalty</th>
            {if $module == 'csgoj' }
            <th data-field="nick" data-align="left" data-valign="middle"  data-sortable="true" data-width="100"  data-cell-style="CellStyleName">Nick</th>
            {/if}
            <th data-field="school" data-align="left" data-valign="middle"  data-sortable="false" data-width="80" data-cell-style="schoolCellStyle">School</th>
            {if $module == 'cpcsys' }
            <th data-field="tmember" data-align="left" data-valign="middle"  data-sortable="false" data-width="80" data-cell-style="memberCellStyle">Member</th>
            {/if}
        </tr>
        </thead>
    </table>
</div>
<input type="hidden" id="rank_config" url="scorerank_ajax?cid={$contest['contest_id']}" use_cache="{$use_cache}" >
{include file="../../csgoj/view/contest/rank_footer" /}