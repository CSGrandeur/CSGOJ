{__NOLAYOUT__} 

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" href="__IMG__/global/favicon.ico" />
    <title><?php echo (isset($pagetitle) ? $pagetitle : "Online Judge"); ?></title>
    {css href='__STATIC__/ojtool/bootstrap/css//bootstrap.min.css' /}
    {css href='__STATIC__/ojtool/bootstrap-icons/font/bootstrap-icons.min.css' /}

    {css href='__STATIC__/bootstrap-table/bootstrap-table.min.css' /}
    {css href='__STATIC__/bootstrap-table/extensions/filter-control/bootstrap-table-filter-control.min.css' /}
    {css href='__STATIC__/bootstrap-table/extensions/reorder-rows/bootstrap-table-reorder-rows.min.css' /}
    {css href='__STATIC__/ojtool/alertifyjs/css/alertify.min.css' /}
    {css href='__STATIC__/ojtool/alertifyjs/css/themes//default.min.css' /}
    {include file="../../ojtool/view/public/global_js" /}
</head>
<body>
<main id="balloon_page">
<h1 id="innerbrowser_warning" style="display:none"></h1>
<div id="balloon_queue_table_toolbar">
    <div class="btn-group" role="group">
        <input id="team_start" placeholder="team001(team start) " class="form-control task_filter" type="text" value="" style="max-width:200px;">
        <input id="team_end" placeholder="team100(team end)" class="form-control task_filter" type="text" value="" style="max-width:200px;">
        <input id="room_list" placeholder="room list by ','" class="form-control task_filter" type="text" value="" style="max-width:200px;">
        <button class="btn btn-success button_get">获取新任务</button>
        <!-- <button class="btn btn-dark button_auto_get">自动获取</button> -->
    </div>
</div>
    <table
    id="balloon_queue_table"
    data-toggle="table"
    data-unique-id="team_pro"
    data-sort-stable="true"
    data-sort-name="st"
    data-url="balloon_queue_ajax?cid={$contest['contest_id']}"
    data-pagination="false"
    data-method="get"
    data-search="false"
    data-classes="table-no-bordered table table-hover"
>
    <thead>
    <tr>
        <th data-field="idx"        data-align="center"         data-valign="middle"   data-width="55" data-formatter="FormatterIdx">Idx</th>
        <th data-field="ast"        data-align="left"           data-valign="middle"   data-width="150" data-formatter="FormatterTaskTime">TaskTime</th>
        <th data-field="room"       data-align="left"           data-valign="middle"   data-formatter="FormatterInfo">Room</th>
        <th data-field="team_id"    data-align="left"           data-valign="middle"   data-formatter="FormatterInfo">TeamID</th>
        <th data-field="problem_id_alpha" data-align="center"   data-valign="middle"   data-width="80" data-formatter="FormatterPro">ProID</th>
        <th data-field="team_pro"   data-align="center"         data-valign="middle"   data-visible="false" >TeamPro</th>
        <th data-field="st"         data-align="center"         data-valign="middle"   data-sortable="true" data-width="180" data-sorter="WaitingSorter" data-formatter="FormatterTaskStatus">Status(DblClick)</th>
    </tr>
    </thead>
</table>

<input type="hidden" id="page_info" cid="{$contest['contest_id']}">
</main>
<script>
function FormatterTaskTime(value, row, index, field) {
    return `<button class="btn btn-danger" >${Timestamp2Time(value)}</button>`
}
function FormatterPro(value, row, index, field) {
    return `<strong class="text-danger">${value}</strong>`;
}
function FormatterTaskStatus(value, row, index, field) {
    let color, txt;
    switch(value) {
        case 4: 
            if(row.fb) {color='primary'; txt = "一血";}
            else {color='success'; txt = "普通"; }
            break;
        case 5: color='secondary'; txt = "已发"; break;
        default: color='warning'; txt = "数据不正确";
    }
    return `<button class="btn btn-${color}">${txt}</button>`;
}
function FormatterInfo(value, row, index, field) {
    if(field == 'room') {
        if(value == null) {
            value = '-';
        }
        return `<span class="room_td" title="${value}">${value}</span>`;
    } else if(field == 'team_id') {
        let tvalue = value.slice(-12);
        return `<span class="team_td" title="${value}">${tvalue.length < value.length ? '...' + tvalue : value}</span>`;
    }
}
function WaitingSorter(va, vb, ra, rb) {
    if(va == vb) {
        if(ra.ast == rb.ast) return 0;
        return ra.ast < rb.ast ? -1 : 1;
    } else {
        return va < vb ? -1 : 1;
    }
}
let map_team, map_baloon, baloon_list;
var ua = navigator.userAgent.toLowerCase();
if (ua.match(/MicroMessenger/i) == "micromessenger" || ua.match(/QQ/i) == "qq") {
    alertify.alert("请用手机自带浏览器打开");
    $('#innerbrowser_warning').text("请用手机自带浏览器打开");
    $('#innerbrowser_warning').show();
    $('#balloon_queue_table_toolbar').hide();
}
document.body.style.zoom = window.innerWidth / 720;
let page_info = $('#page_info');
let cid = page_info.attr("cid");
let balloon_queue_table = $("#balloon_queue_table");
let team_start_input = $('#team_start'), team_start;
let team_end_input = $('#team_end'), team_end;
let room_list_input = $('#room_list'), room_list;

function GetInfoLocal() {
    team_start = csg.store('team_start_' + cid);
    if(team_start == null) {
        team_start = '';
    }
    team_start_input.val(team_start);
    team_end = csg.store('team_end_' + cid);
    if(team_end == null) {
        team_end = '';
    }
    team_end_input.val(team_end);
    room_list = csg.store('room_list_' + cid);
    if(room_list == null) {
        room_list = '';
    }
    room_list_input.val(room_list);
}
function SetInfoLocal() {
    team_start = team_start_input.val();
    csg.store('team_start_' + cid, team_start);
    team_end = team_end_input.val();
    csg.store('team_end_' + cid, team_end);
    room_list = room_list_input.val();
    csg.store('room_list_' + cid, room_list);
}
function UpdateTaskList() {
    $.get('balloon_queue_ajax?cid=' + cid, function(data) {
        baloon_list = data;
        map_baloon = [];
        for(let i = 0; i < baloon_list.length; i ++) {
            if(baloon_list[i].team_id in map_team) {
                baloon_list[i].room = map_team[baloon_list[i].team_id]['room'];
            }
            map_baloon[baloon_list[i].team_pro] = baloon_list[i];
        }
        baloon_list.sort((a, b) => {
            return b.sol_time - a.sol_time;
        });
        balloon_queue_table.bootstrapTable('load', baloon_list);
    })
}
$('document').ready(function() {
    GetInfoLocal();
    SetFrontAlertify('balloon_page');
    balloon_queue_table.css("margin-top", $('#balloon_queue_table_toolbar').height());
    map_team = {};
    $.get('balloon_team_list_ajax?cid=' + cid, function(ret) {
        for(let i = 0; i < ret.length; i ++) {
            map_team[ret[i].team_id] = ret[i];
        }
        UpdateTaskList();
    });
    $('.button_get').click(function() {
        let total_list = balloon_queue_table.bootstrapTable('getData');
        if(total_list.filter((a) => a.st == 4).length >= 20) {
            alertify.warning("有较多气球还未发<br/>暂不获取新任务");
        } else {
            $.get('balloon_queue_get_ajax?cid=' + cid, {
                'team_start': team_start,
                'team_end': team_end,
                'room_list': room_list
            }, function(ret) {
                if(ret.code == 1) {
                    if(ret.data.new_num > 0) {
                        alertify.success(`新任务：${ret.data.new_num}个`);
                        UpdateTaskList();
                    } else {
                        alertify.message(`暂无新任务`);
                    }
                } else {
                    alertify.error(ret.msg);
                }
            });
        }
        ToggleFullScreen('balloon_page', null, true);
    });


    $('.task_filter').on('input', function() {
        SetInfoLocal();
    });
    let flag_first_st = false;
    let flag_first_ast = false;
    
    balloon_queue_table.on('click-cell.bs.table', function(e, field, td, row){
        if(field == 'st') {
            if(!flag_first_st){
                flag_first_st = true;
                alertify.message("双击标记与取消标记");
            }
        } else if(field == 'ast') {
            if(!flag_first_ast) {
                flag_first_ast = true;
                alertify.message("双击退还任务");
            }
        }
    });
    balloon_queue_table.on('dbl-click-cell.bs.table', function(e, field, td, row){
        if(field == 'st') {
            $.post('balloon_send_change_ajax?cid=' + cid, {
                'team_id': row.team_id,
                'pro_id': row.problem_id
            }, function(ret) {
                if(ret.code == 1) {
                    row.st = ret.data.st_now;
                    balloon_queue_table.bootstrapTable('updateByUniqueId', {
                        id: row.team_pro,
                        row: row
                    });
                    if(row.st == 4) {
                        alertify.warning(`${row.team_id}-${row.problem_id_alpha}状态变为：<br/>待发`);
                    } else {
                        alertify.success(`${row.team_id}-${row.problem_id_alpha}状态变为：<br/>已发`);
                    }
                } else {
                    alertify.error(ret.msg);
                }
            })
        } else if(field == 'ast') {
            $.post('balloon_send_change_ajax?cid=' + cid, {
                'team_id': row.team_id,
                'pro_id': row.problem_id,
                'rtn': 1
            }, function(ret) {
                if(ret.code == 1) {
                    balloon_queue_table.bootstrapTable('removeByUniqueId', row.team_pro);
                    alertify.error(`${row.team_id}-${row.problem_id_alpha} 任务已退还`);
                } else {
                    alertify.error(ret.msg);
                }
            })
        } else {
            alertify.message(`
                <table>
                <tr><td class="td_l">队&nbsp;&nbsp;号:</td> <td class="td_r">${row.team_id}</td></tr>
                <tr><td class="td_l">区&nbsp;&nbsp;域:</td> <td class="td_r">${row.room}</td></tr>
                <tr><td class="td_l">题&nbsp;&nbsp;号:</td> <td class="td_r">${row.problem_id_alpha}</td></tr>
                <tr><td class="td_l">任务时:</td> <td class="td_r">${row.ast}</td></tr>
                </table>
            `)
        }
    });
});
</script>
<style>
#balloon_page {
    background-color: white;
    overflow-y: auto;
}
#balloon_queue_table_toolbar {
    position: fixed;
    top: 0;
    z-index: 10;
    background-color: white;
    width: 100%;
}
#balloon_queue_table {
    margin-top: 30px;
}
#innerbrowser_warning {
    z-index: 5000;
}
.room_td, .team_td {
    overflow: hidden;
    white-space: nowrap;
    display: block;
    text-overflow: ellipsis;
}
.room_td {
    width: 100px;
}
.team_td {
    width: 150px;
}
.td_l {
    text-align: right;
}
.td_r {
    text-align: left;
    padding-left: 10px;
}
</style>
</body>
</html>