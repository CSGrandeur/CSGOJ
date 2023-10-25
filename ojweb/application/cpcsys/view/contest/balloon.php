{include file="../../csgoj/view/contest/rank_header" /}
<span class="alert alert-info" style="display: block">
<table id="help_info_table"><tr><td>
        <p><strong class="text-success">绿色: </strong>该队通过题目，待发气球</p>
        <p><strong class="text-primary">蓝色: </strong>一血气球（一血计算排除打星队）</p>
        <p><strong class="text-secondary">灰色: </strong>鼠标左键点击绿色待发气球转为灰色，表示已发</p>
        <p><strong class="text-warning">黄色: </strong>键盘字母A开启assign模式，将气球分配给特定发送员后转为黄色，表示已分配</p>
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
        data-url="balloon_ajax?cid={$contest['contest_id']}"
        data-side-pagination="client"
        data-method="get"
        data-striped="true"
        data-show-refresh="true"
        data-buttons-align="left"
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
            <th data-field="rank" data-align="center" data-valign="middle"  data-sortable="false" data-width="60">Rank</th>
            <th data-field="user_id" data-align="left" data-valign="middle"  data-sortable="false" data-width="80" data-formatter="FormatterRankUserId">{if $module == 'cpcsys' }Team{else/}User{/if}</th>
            <th data-field="room" data-align="left" data-valign="middle"  data-sortable="false" data-width="80">Room</th>
            {foreach($problemIdMap['abc2id'] as $apid=>$pid)}
            <th data-field="{$apid}" data-align="center" data-valign="middle"  data-sortable="false" data-width="50" data-cell-style="balloonCellStype" data-formatter="FormatterRankProBalloon">
                <a href="/{$module}/contest/problem?cid={$contest['contest_id']}&pid={$apid}">
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


<script type="text/javascript">
    var ranktable = $('#ranklist_table');
    var waiting = false;
    var deleteBalloonKey = false;
    let flagAssignBalloon = false;
    var balloon_waiting_num_span = $('#balloon_waiting_num_span');
    let balloon_assign_num_span = $('#balloon_assign_num_span');
    var balloon_waiting_num = 0, balloon_assign_num = 0;
    let balloon_sender = [];
    let sender_div = $("<div></div>");
    function FormatterRankProBalloon(value, row, index, field) {
        function AC(res) {
            return typeof(res) == 'undefined' || res === null ? '' : '1';
        }
        function WA(res) {
            return typeof(res) == 'undefined' || res === null ? '' : '<br/>' + res;
        }
        let title = '';
        let fb = 0;
        if(`${field}_bal_info` in row) {
            title = `sender: ${row[`${field}_bal_info`].asu} &#10;&#13;fb: ${row[`${field}_bal_info`].fb} &#10;&#13;task_time: ${Timestamp2Time(row[`${field}_bal_info`].ast)}`;
            fb = row[`${field}_bal_info`].fb;
        }
        return `<span pstatus="${row[field + '_pstatus']}" rstatus="${row[field + '_rstatus']}" title="${title}" fb="${fb}">
            ${AC(row[field + '_ac_sec'])}${WA(row[field + '_wa_sec'])}${fb ? "-FB" : ""}
        </span>`;
    }
    function ProStatus(value, row, field, status_key) {
        return parseInt(row?.[`${field}_${status_key}`] || 0);
    }
    $(window).keydown(function(e) {
        if(e.keyCode === 'D'.charCodeAt(0))
            //set flag for delete balloon mark
            deleteBalloonKey = true;
    });
    $(window).keyup(function(e) {
        if(e.keyCode === 'D'.charCodeAt(0))
            //set flag for delete balloon mark
            deleteBalloonKey = false;
    });
function ChangeFlagAssign(flg) {
    flagAssignBalloon = flg;
    if(flagAssignBalloon) {
        alertify.warning("开启分配模式");
        $('.task_assign').show();
        $('.task_finish').hide();
    } else {
        alertify.success("关闭分配模式");
        $('.task_assign').hide();
        $('.task_finish').show();
    }
    SetInfoLocal();
}
    window.onkeydown = (event) => {
        if (!event || !event.isTrusted || !event.cancelable) {
            return;
        }
        const key = event.key;
        if(event.key == 'A' || event.key == 'a') {
            ChangeFlagAssign(!flagAssignBalloon);
        }
    }
    $(document).ready(function(){
        waiting = false;
        deleteBalloonKey = false;
    });
    ranktable.on('load-success.bs.table', function(e, data) {
        //刷新时重新统计待发气球数，主要是核实数据，容错。
        balloon_waiting_num = 0;
        for(let i = 0; i < data.length; i ++) {
            for(let item in data[i]) {
                if(/^[A-Z]+$/.test(item)) {
                    let pstatus = ProStatus(data[i][item], data[i], item, 'pstatus');
                    balloon_waiting_num += pstatus === 2 || pstatus === 3;
                    balloon_assign_num += pstatus === 4;
                }
            }
        }
        balloon_waiting_num_span.text(balloon_waiting_num);
        balloon_assign_num_span.text(balloon_assign_num);
    });
    ranktable.on('click-cell.bs.table', function(e, field, value, row, $elem) {
        let change_type;
        if(deleteBalloonKey === true) {
            change_type = 'reset';
        }
        else {
            change_type = flagAssignBalloon ? 'assign' : 'sent';
        }
        ChangeStatus(value, row, field, change_type);
    });
    function ChangeStatus(value, row, field, change_type, assign_user=null) {
        function DoChange(change_to, row, field, fb=0, assign_user=null) {
            waiting = true;
            $.post('balloon_change_status_ajax', {
                'cid': cid,
                'apid': field,
                'user_id': row.user_id,
                'change_to': change_to,
                'fb': fb,
                'assign_user': assign_user
            }, function (ret) {
                if (ret.code === 1) {
                    if(change_to == 2 || change_to == 3) {
                        row.balloon ++;
                        if(row[`${field}_rstatus`] == 4) {
                            balloon_assign_num --;
                        }
                        balloon_waiting_num ++;
                        
                    } else if(change_to == 4 || change_to == 5) {
                        row.balloon --;
                        if(change_to == 4) {
                            balloon_assign_num ++;
                        }
                        balloon_waiting_num --;
                    }
                    row[`${field}_pstatus`] = change_to;
                    ranktable.bootstrapTable('updateByUniqueId', {
                        id: row['user_id'],
                        row: row
                    });
                    balloon_waiting_num_span.text(balloon_waiting_num);
                    balloon_assign_num_span.text(balloon_assign_num);
                }
                else {
                    alertify.error(ret.msg);
                }
                waiting = false;
            })
        }
        if(waiting === false) {
            let pstatus = ProStatus(value, row, field, 'pstatus');
            let rstatus = ProStatus(value, row, field, 'rstatus');
            let change_to = null, assign_user = null;
            switch(change_type) {
                case 'reset':
                    if(pstatus != rstatus) {
                        change_to = rstatus;
                        DoChange(change_to, row, field) ;
                    }
                    break;
                case 'assign':
                    if(pstatus == 2 || pstatus == 3) {
                        alertify.confirm("确认派送员", sender_div.html(), function() {
                            change_to = 4;
                            assign_user = $('#balloon_sender_select').val();
                            DoChange(change_to, row, field, rstatus == 3 ? 1 : 0, assign_user) ;
                        }, function(){})
                    }
                    break;
                case 'sent':
                    if(pstatus == 2 || pstatus == 3) {
                        change_to = 5;
                        DoChange(change_to, row, field, rstatus == 3 ? 1 : 0) ;
                    }
                    break;
            }
        } else {
            alertify.warning("等待上一个请求响应");
        }
    }
    const balloon_assign_color = '#FFC107';
    const balloon_sent_color = '#A0A0A0';
    const balloon_color = [
        'white',
        wa_color,
        ac_color,
        first_blood_color,
        balloon_assign_color,
        balloon_sent_color
    ];
    function balloonCellStype(value, row, index, field) {
        var retcolor = '', pro_status = ProStatus(value, row, field, 'pstatus');
        return {
            css: {
                'background-color': balloon_color[pro_status],
                'min-width': '50px'
            }
        };
    }
    function WaitingSorter(fa, fb, ra, rb) {
        if(fa == fb) {
            ra.user_id < rb.user_id ? -1 : 1;
        } else {
            return ra.balloon < rb.balloon ? 1 : -1;
        }
    }
function UpdateSenderSelection() {
    let sender_list = [];
    for(let i = 0; i < balloon_sender.length; i ++) {
        sender_list.push(`<option value=${balloon_sender[i].team_id}>${balloon_sender[i].team_id} . ${balloon_sender[i].name}</option>`);
    }
    sender_div.html(`
        <select class="form-control" id="balloon_sender_select">
        ${sender_list.join('')}
        </select>
    `)
}
function GetBalloonSender() {
    $.get('balloon_sender_list_ajax?cid=' + cid, function(ret) {
        balloon_sender = ret;
        UpdateSenderSelection();
    });
}
function SetInfoLocal() {
    csg.store(`${cid}_balloon_assign_mode`, flagAssignBalloon ? '1' : '0');
}
function GetInfoLocal() {
    let flg = csg.store(`${cid}_balloon_assign_mode`);
    if(flg === '1') {
        ChangeFlagAssign(true);
    }
}
$(document).ready(function() {
    GetBalloonSender();
    GetInfoLocal();
});
</script>

<style>
    .text-secondary {
        color: #adb5bd;
    }
    #help_info_table td {
        padding: 0 10px;
    }
</style>