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
        {if $login_teaminfo['room'] !== null && $login_teaminfo['room'] != '' }
        <div id="room_limit" title="{$login_teaminfo['room']}">ROOM:{$login_teaminfo['room']}</div>
        <input id="room_list_str" type="hidden" placeholder="room list by ','" class="form-control task_filter" value="{$login_teaminfo['room']}" style="max-width:200px;">
        {else /}
        <input id="room_list_str" placeholder="room list by ','" class="form-control task_filter" type="text" value="" style="max-width:200px;">
        {/if}
        <button class="btn btn-secondary balloon_queue_refresh" ><i class="bi bi-repeat"></i></button>
        <button class="btn btn-warning balloon_queue_fullscreen" ><i class="bi bi-arrows-fullscreen"></i></button>
        <button class="btn btn-success button_get">获取</button>
    </div>
</div>
    <table
    id="balloon_queue_table"
    data-toggle="table"
    data-unique-id="team_pro"
    data-sort-stable="true"
    data-sort-name="bst"
    data-pagination="false"
    data-method="get"
    data-search="false"
    data-classes="table-no-bordered table table-hover"
>
    <thead>
    <tr>
        <th data-field="idx"        data-align="center"         data-valign="middle"   data-width="55" data-formatter="FormatterIdx">Idx</th>
        <th data-field="ac_time"    data-align="left"           data-valign="middle"   data-width="150" data-formatter="FormatterAcTime">ACTime</th>
        <th data-field="room"       data-align="left"           data-valign="middle"   data-formatter="FormatterInfo">Room</th>
        <th data-field="team_id"    data-align="left"           data-valign="middle"   data-formatter="FormatterInfo">TeamID</th>
        <th data-field="problem_id" data-align="center"         data-valign="middle"   data-width="80" data-formatter="FormatterPro">ProID</th>
        <th data-field="team_pro"   data-align="center"         data-valign="middle"   data-visible="false" >TeamPro</th>
        <th data-field="bst"        data-align="center"         data-valign="middle"   data-sortable="true" data-width="180" data-sorter="WaitingSorter" data-formatter="FormatterTaskStatus">Status(DblClick)</th>
    </tr>
    </thead>
</table>

<input type="hidden" id="page_info" cid="{$contest['contest_id']}" contest_user="{$contest_user}" >

</main>
<script>
let rank_data = null, rank_data_time = null;
let balloon_task_list, map_team_balloon;
let problem_id_map;
let map_team;
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
let contest_user = page_info.attr("contest_user");
let balloon_queue_table = $("#balloon_queue_table");
let team_start_input = $('#team_start'), team_start;
let team_end_input = $('#team_end'), team_end;
let room_list_str_input = $('#room_list_str'), room_list_str, map_room;
let task_potential;
let flg_fullscreen = false;

function FormatterAcTime(value, row, index, field) {
    return `<button class="btn btn-danger" >${Timeint2Str(value)}</button>`
}
function FormatterPro(value, row, index, field) {
    let apid = problem_id_map['id2abc']?.[value];
    if(apid === null) {
        apid = "Unkonw";
    }
    return `<strong class="text-danger"><i class="bi bi-balloon"></i>${apid}</strong>`;
}
function FormatterTaskStatus(value, row, index, field) {
    let color, txt;
    let pstatus = row.bst == 5 ? row.bst : row.pst;
    switch(pstatus) {
        case 2: color='success'; txt = "普通"; break;
        case 3: color='primary'; txt = "一血"; break;
        case 5: color='secondary'; txt = "已发"; break;
        default: color='warning'; txt = "数据错误";
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
        if(ra.ac_time == rb.ac_time) return 0;
        return ra.ac_time < rb.ac_time ? -1 : 1;
    } else {
        return va < vb ? -1 : 1;
    }
}

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
    room_list_str = csg.store('room_list_str_' + cid);
    if(room_list_str == null) {
        room_list_str = '';
    }
    if(room_list_str_input.val() == '') {
        room_list_str_input.val(room_list_str);
    }
}
function SetInfoLocal() {
    team_start = team_start_input.val();
    csg.store('team_start_' + cid, team_start);
    team_end = team_end_input.val();
    csg.store('team_end_' + cid, team_end);
    room_list_str = room_list_str_input.val();
    csg.store('room_list_str_' + cid, room_list_str);
}
function GetRoomMap() {
    room_list_str = room_list_str_input.val().trim();
    if(room_list_str == '') {
        map_room = null;
    }
    let room_list = room_list_str.split(',');
    map_room = {};
    for(let i = 0; i < room_list.length; i ++) {
        if(room_list[i].trim() != '') {
            map_room[room_list[i].trim()] = true;
        }
    }
}
function MapTeamBaloonAdd(team_id, problem_id, task_item) {
    if(!(team_id in map_team_balloon)) {
        map_team_balloon[team_id] = {};
    }
    if(!(problem_id in map_team_balloon[team_id])) {
        map_team_balloon[team_id][problem_id] = task_item;
    }
}
function SendTaskQuery(ith, total_get) {
    if(ith < task_potential.length && total_get < 5) {
        let row = task_potential[ith].row, apid = task_potential[ith].apid;
        let task_data = {
            'team_id':          row.user_id,
            'apid':             apid,
            'bst':              4,
            'pst':              row[apid].pst,
            'room':             row.room,
            'ac_time':          row[apid].ac,
            'balloon_sender':   contest_user
        };
        task_data.problem_id = problem_id_map['abc2id']?.[task_data.apid];
        task_data.team_pro = `${task_data.team_id}_${task_data.problem_id}`;
        $.post(`balloon_change_status_ajax?cid=${cid}`, task_data, function (ret) {
            if (ret.code === 1) {
                balloon_queue_table.bootstrapTable('insertRow', {index: 1, row: task_data});
                SendTaskQuery(ith + 1, total_get + 1);
                MapTeamBaloonAdd(task_data.team_id, task_data.problem_id, task_data);
            }
            else {
                SendTaskQuery(ith + 1, total_get);
            }
        })
    } else if(total_get > 0){
        alertify.success(`获取了${total_get}个新任务`);
    } else {
        alertify.message(`本次没有新任务`);
    }
}
function CalcTask() {
    task_potential = [];
    for(let apid in problem_id_map.abc2id) {
        for(let i = 0; i < rank_data.length; i ++) {
            if((apid in rank_data[i]) && (rank_data[i][apid].pst == 2 || rank_data[i][apid].pst == 3)) {
                let problem_id = problem_id_map.abc2id[apid];
                if(!(rank_data[i].user_id in map_team_balloon) || !(problem_id in map_team_balloon[rank_data[i].user_id])) {
                    task_potential.push({
                        'apid': apid,
                        'row': rank_data[i]
                    })
                }
            }
        }
    }
    SendTaskQuery(0, 0);
}
function GetTask() {
    if(rank_data_time === null || Math.abs(new Date() - rank_data_time) > 60000) {
        $.get(`ranklist_ajax?cid=${cid}`, function(ret) {
            rank_data = ret;
            rank_data = rank_data.filter((a) => map_room === null || (a.room in map_room));
            rank_data_time = new Date();
            CalcTask();
        });
    } else {
        CalcTask();
    }
}
function InitBalloonQueueShow() {
    balloon_queue_table.bootstrapTable('load', balloon_task_list);
}
let flg_init = 0;
function InitFinishFlag() {
    flg_init ++;
    if(flg_init >= 2) {
        InitBalloonQueueShow();
    }
}
function MakeMapTeamBalloon() {
    map_team_balloon = {};
    for(let i = 0; i < balloon_task_list.length; i ++) {
        balloon_task_list[i].apid = problem_id_map['id2abc']?.[balloon_task_list[i].problem_id];
        balloon_task_list[i].team_pro = `${balloon_task_list[i].team_id}_${balloon_task_list[i].problem_id}`;
        if(!(balloon_task_list[i].team_id in map_team_balloon)) {
            map_team_balloon[balloon_task_list[i].team_id] = {};
        }
        if(!(balloon_task_list[i].problem_id in map_team_balloon[balloon_task_list[i].team_id])) {
            map_team_balloon[balloon_task_list[i].team_id][balloon_task_list[i].problem_id] = balloon_task_list[i];
        }
    }
}
function RefreshBalloonQueue() {
    $.get(`balloon_task_ajax`, {'cid': cid}, function(ret) {
        if(ret.code == 1) {
            balloon_task_list = ret.data.balloon_task_list;
            problem_id_map = ret.data.problem_id_map;
            MakeMapTeamBalloon();
            InitFinishFlag();
            alertify.success(`加载到${balloon_task_list.length}个任务`)
        }
    });
}
function RemoveTask(row) {
    // 退回的task
    balloon_queue_table.bootstrapTable('removeByUniqueId', row.team_pro);
    if(typeof(map_team_balloon?.[row.team_id]?.[row.problem_id]) !== 'undefined') {
        delete map_team_balloon[row.team_id][row.problem_id]
    }
}
$('document').ready(function() {
    GetInfoLocal();
    GetRoomMap();
    SetFrontAlertify('balloon_page');
    balloon_queue_table.css("margin-top", $('#balloon_queue_table_toolbar').height());
    map_team = {};
    $.get('balloon_team_list_ajax?cid=' + cid, function(ret) {
        for(let i = 0; i < ret.length; i ++) {
            map_team[ret[i].team_id] = ret[i];
        }
        InitFinishFlag();
    });
    RefreshBalloonQueue();
    $('.button_get').click(function() {
        let total_list = balloon_queue_table.bootstrapTable('getData');
        if(total_list.filter((a) => a.bst == 4).length >= 20) {
            alertify.warning("有较多气球还未发<br/>暂不获取新任务");
        } else {
            GetTask();
        }
    });
    $('.balloon_queue_fullscreen').click(function() {
        flg_fullscreen = !flg_fullscreen;
        ToggleFullScreen('balloon_page', null, flg_fullscreen);
    });
    $('.balloon_queue_refresh').click(function() {
        RefreshBalloonQueue();
    });

    $('.task_filter').on('input', function() {
        SetInfoLocal();
    });
    let flag_first_st = false;
    let flag_first_ast = false;
    
    balloon_queue_table.on('click-cell.bs.table', function(e, field, td, row){
        if(field == 'bst') {
            if(!flag_first_st){
                flag_first_st = true;
                alertify.message("双击标记与取消标记");
            }
        } else if(field == 'ac_time') {
            if(!flag_first_ast) {
                flag_first_ast = true;
                alertify.message("双击退还任务");
            }
        }
    });
    balloon_queue_table.on('dbl-click-cell.bs.table', function(e, field, td, row){
        let apid = problem_id_map['id2abc']?.[row.problem_id];
        if(field == 'bst') {
            let change_to = row.bst == 5 ? 4 : 5;
            $.post(`balloon_change_status_ajax?cid=${cid}`, {
                'team_id': row.team_id,
                'apid': apid,
                'bst': change_to
            }, function(ret) {
                if(ret.code == 1) {
                    row.bst = change_to;
                    balloon_queue_table.bootstrapTable('updateByUniqueId', {
                        id: row.team_pro,
                        row: row
                    });
                    if(change_to == 4) {
                        alertify.warning(`${row.team_id}-${apid} <br/>待发`);
                    } else {
                        alertify.success(`${row.team_id}-${apid} <br/>已发`);
                    }
                } else {
                    alertify.error(ret.msg);
                }
            })
        } else if(field == 'ac_time') {
            $.post('balloon_change_status_ajax?cid=' + cid, {
                'team_id': row.team_id,
                'apid': apid,
                'bst': 0
            }, function(ret) {
                if(ret.code == 1) {
                    RemoveTask(row);
                    alertify.warning(`${row.team_id}-${row.apid} 任务已退还`);
                } else {
                    alertify.error(ret.msg);
                }
            })
        } else {
            alertify.message(`
                <table>
                <tr><td class="td_l">队&nbsp;&nbsp;号:</td> <td class="td_r">${row.team_id}</td></tr>
                <tr><td class="td_l">区&nbsp;&nbsp;域:</td> <td class="td_r">${row.room}</td></tr>
                <tr><td class="td_l">题&nbsp;&nbsp;号:</td> <td class="td_r">${problem_id_map['id2abc']?.[row.problem_id]}</td></tr>
                <tr><td class="td_l">AC时间:</td> <td class="td_r">${Timeint2Str(row.ac_time)}</td></tr>
                </table>
            `)
        }
    });
    $("#room_limit").click(function() {
        alertify.alert(this.title.split(',').join('<br/>')).set("title", "管理的房间");
    })
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
#room_limit {
    width: 200px;
    text-align: center;
    overflow-x: ellipsis;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50px; /* 这应该与你的div的高度相同 */
}
</style>
</body>
</html>