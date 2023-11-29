{include file="../../csgoj/view/contest/rank_header" /}
<span class="alert alert-info" style="display: block">
<table id="help_info_table"><tr><td>
        <p><strong class="text-success">绿色: </strong>该队通过题目，待发气球</p>
        <p><strong class="text-primary">蓝色: </strong>一血气球（一血计算排除打星队）</p>
        <p><strong class="text-secondary">灰色: </strong>表示已发</p>
        <p><strong class="text-warning">黄色: </strong>键盘字母A开启分配模式，将气球分配给特定发送员后转为黄色，表示已分配</p>
        <p><strong class="text-danger">红色: </strong>提交了代码尚未AC的题目，可以适当留意</p>

        <p><strong>键盘字母D+鼠标左键</strong>可取消标记恢复未发状态</p>
</td>
    <td>
        <div class="col-auto" id="fs_qrcode_div">
            <strong class="text-warning">气球配送员扫码<br/>请用手机系统自带浏览器</strong>
            <div id="fs_page_qrcode"></div>
        </div>
</td>
</tr>
</table>
</span>
<div id="ranklist_table_div">
    <table
        id="ranklist_table"
        data-toggle="table"
        data-side-pagination="client"
        data-method="get"
        data-striped="true"
        data-show-refresh="false"
        data-buttons-align="right"
        data-toolbar-align="left"
        data-unique-id="user_id"
        data-sort-stable="true"
        data-sort-name="balloon"
        data-show-export="true"
        data-toolbar="#ranklist_toobar"
        data-export-types="['excel', 'csv', 'json', 'png']"
        data-export-options='{"fileName":"{$contest[\"contest_id\"]}-{$contest[\"title\"]}-BalloonRank"}'
    >
        <thead>
        <tr>
            <th data-field="user_id" data-align="left" data-valign="middle"  data-sortable="false" data-width="60" data-formatter="FormatterRankUserId">{if $module == 'cpcsys' }Team{else/}User{/if}</th>
            <th data-field="tkind" data-align="left" data-valign="middle"  data-sortable="false" data-width="10"  data-formatter="FormatterTkind"></th>
            <th data-field="room" data-align="left" data-valign="middle"  data-sortable="false" data-width="80">Room</th>
            {foreach($problemIdMap['abc2id'] as $apid=>$pid)}
            <th data-field="{$apid}" data-align="center" data-valign="middle"  data-sortable="false" data-width="50" data-cell-style="balloonCellStype" data-formatter="FormatterRankProBalloon">
                <a href="/{$module}/contest/problem?cid={$contest['contest_id']}&pid={$apid}" pid={$pid} apid={$apid} class="balloon_header_pid" >
                    {$apid}
                </a>
            </th>
            {/foreach}
            <th data-field="balloon" data-align="center" data-valign="middle"  data-sortable="true" data-width="50" data-class="waiting" data-sorter="WaitingSorter" >Waiting</th>
            <th data-field="solved" data-align="center" data-valign="middle"  data-sortable="false" data-width="40">Solved</th>
        </tr>
        </thead>
    </table>
</div>

{include file="../../csgoj/view/public/js_qrcode" /}
<script>
    let page_qrcode_container = $('#page_qrcode_container');
    const qrCodeFull = new QRCodeStyling({
        width: 144,
        height: 144,
        margin: 0,
        type: "svg",
        data: location.href.replace('balloon', 'balloon_queue'),
        image: "/static/image/global/gothic_sign.svg",
        dotsOptions: {
            type: "classy-rounded",
            color: "#337AB7",
            gradient: null
        },
        imageOptions: {
            hideBackgroundDots: true,
            imageSize: 0.4,
            margin: 0
        }
    });
    qrCodeFull.append(document.getElementById("fs_page_qrcode"));

</script>

{include file="../../csgoj/view/contest/rank_footer" /}

{js href='__STATIC__/csgoj/balloon_manager.js' /}    

<style>
    .text-secondary {
        color: #adb5bd;
    }
    #help_info_table td {
        padding: 0 10px;
    }
</style>