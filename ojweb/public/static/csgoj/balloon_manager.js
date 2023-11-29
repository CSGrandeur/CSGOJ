
var ranktable;
var deleteBalloonKey;
let flagAssignBalloon;
var balloon_waiting_num_span;
let balloon_assign_num_span;
var balloon_waiting_num;
let balloon_assign_num;
let balloon_sender_list;
let sender_div;
let problem_id_map;
let rank_data, balloon_task_list;
let map_team_balloon;
let map_balloon_sender;

function FormatterRankProBalloon(value, row, index, field) {
    function AC(val) {return val.ac ? field : '';}
    function WA(val) {
        let wa = val.wa ? parseInt(val.wa) : 0;
        let tr = val.tr ? parseInt(val.tr) : 0;
        return AC(val) != field && wa + tr ? `(${wa + tr})` : '&nbsp;';
    }
    function FB(val) {return val.pst == 3 ? ' - FB' : '';}
    let title = '';
    if('balloon_sender' in value) {
        title = `sender: ${value.balloon_sender} - ${map_balloon_sender?.[value.balloon_sender]?.name} &#10;ac_time: ${Timeint2Str(value.ac)}`;
    }
    return `<span title="${title}">${AC(value)}${FB(value)}<br/>${WA(value)}</span>`;
}
function ProStatus(value, status_key) {
    return parseInt(value?.[status_key] || 0);
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
        alertify.warning("分配模式");
        $('.task_assign').show();
        $('.task_finish').hide();
    } else {
        alertify.success("标记模式");
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
ranktable.on('load-success.bs.table', function(e, data) {
    //刷新时重新统计待发气球数，主要是核实数据，容错。
    balloon_waiting_num = 0;
    balloon_assign_num = 0;
    for(let i = 0; i < data.length; i ++) {
        for(let item in data[i]) {
            if(/^[A-Z]+$/.test(item)) {
                let bst = 'bst' in data[i][item] ? data[i][item].bst : 0;
                balloon_waiting_num += bst < 4;
                balloon_assign_num += bst == 4;
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
function ChangeStatus(value, row, field, change_type, balloon_sender=null) {
    function DoChange(change_to, row, field, balloon_sender=null) {
        $.post(`balloon_change_status_ajax?cid=${cid}`, {
            'team_id':          row.user_id,
            'apid':             field,
            'bst':              change_to,
            'pst':              value.pst,
            'room':             row.room,
            'ac_time':          value.ac,
            'balloon_sender':   balloon_sender
        }, function (ret) {
            if (ret.code === 1) {
                if(change_to < 4) {
                    row.balloon ++;
                    if(value.bst == 4) {
                        balloon_assign_num --;
                    }
                    balloon_waiting_num ++;
                } else if(change_to == 4 || change_to == 5) {
                    row.balloon --;
                    if(change_to == 4) {
                        balloon_assign_num ++;
                        value.balloon_sender = assign_user;
                    }
                    balloon_waiting_num --;
                }
                value.bst = change_to;
                ranktable.bootstrapTable('updateByUniqueId', {
                    id: row['user_id'],
                    row: row
                });
                balloon_waiting_num_span.text(balloon_waiting_num);
                balloon_assign_num_span.text(balloon_assign_num);
                const alt_func = change_to > 3 ? alertify.success : alertify.message;
                alt_func(`<table style='text-align:left'>
                    <tr><td>队号:</td>      <td>&nbsp;&nbsp;${row.user_id}</td></tr>
                    <tr><td>题号:</td>      <td>&nbsp;&nbsp;${field}</td></tr>
                    <tr><td>状态:</td>      <td>&nbsp;&nbsp;${change_to}</td></tr>
                    <tr><td>一血:</td>      <td>&nbsp;&nbsp;${value.pst == 2 ? 'false' : 'true'}</td></tr>
                    <tr><td>区域:</td>      <td>&nbsp;&nbsp;${row.room}</td></tr>
                    <tr><td>AC时间:</td>    <td>&nbsp;&nbsp;${Timeint2Str(value.ac)}</td></tr>
                    <tr><td>配送员:</td>    <td>&nbsp;&nbsp;${balloon_sender}</td></tr>
                    </table>
                `).delay(10);
            }
            else {
                alertify.error(ret.msg);
            }
        })
    }
    let pst = ProStatus(value, 'pst');
    let bst = ProStatus(value, 'bst');
    let change_to = null, assign_user = null;
    switch(change_type) {
        case 'reset':
            if(pst != bst) {
                change_to = 0;
                DoChange(change_to, row, field) ;
            }
            break;
        case 'assign':
            if((pst == 2 || pst == 3) && bst < 4) {
                alertify.confirm("确认配送员", sender_div.html(), function() {
                    change_to = 4;
                    assign_user = $('#balloon_sender_select').val();
                    DoChange(change_to, row, field, assign_user) ;
                }, function(){})
            }
            break;
        case 'sent':
            if((pst == 2 || pst == 3) && bst < 5) {
                change_to = 5;
                DoChange(change_to, row, field) ;
            }
            break;
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
    let pst = ProStatus(value, 'pst'), bst = ProStatus(value, 'bst');
    let pro_status = bst > 0 ? bst : pst;
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
    for(let i = 0; i < balloon_sender_list.length; i ++) {
        sender_list.push(`<option value="${balloon_sender_list[i].team_id}">${balloon_sender_list[i].team_id} . ${balloon_sender_list[i].name}</option>`);
        map_balloon_sender[balloon_sender_list[i].team_id] = balloon_sender_list[i];
    }
    sender_div.html(`
        <select class="form-control" id="balloon_sender_select">
        ${sender_list.join('')}
        </select>
    `)
}
function GetBalloonSender() {
    $.get('balloon_sender_list_ajax?cid=' + cid, function(ret) {
        balloon_sender_list = ret;
        UpdateSenderSelection();
        InitFinishFlag();
    });
}
function SetInfoLocal() {
    csg.store(`${cid}_balloon_assign_mode`, flagAssignBalloon ? '1' : '0');
}
function GetInfoLocal() {
    let flg = csg.store(`${cid}_balloon_assign_mode`);
    ChangeFlagAssign(flg !== '0');
}
function InitVars() {
    ranktable = $('#ranklist_table');
    deleteBalloonKey = false;
    flagAssignBalloon = true;
    balloon_waiting_num_span = $('#balloon_waiting_num_span');
    balloon_assign_num_span = $('#balloon_assign_num_span');
    balloon_waiting_num = 0, balloon_assign_num = 0;
    balloon_sender_list = [];
    map_balloon_sender = {};
    sender_div = $("<div></div>");
}
function MakeMapTeamBalloon() {
    map_team_balloon = {};
    for(let i = 0; i < balloon_task_list.length; i ++) {
        if(!(balloon_task_list[i].team_id in map_team_balloon)) {
            map_team_balloon[balloon_task_list[i].team_id] = {};
        }
        if(!(balloon_task_list[i].problem_id in map_team_balloon[balloon_task_list[i].team_id])) {
            map_team_balloon[balloon_task_list[i].team_id][balloon_task_list[i].problem_id] = balloon_task_list[i];
        }
    }
}
function InitBalloonTableShow() {
    MakeMapTeamBalloon();
    for(let i = 0; i < rank_data.length; i ++) {
        rank_data[i].balloon = 0;
        for(let apid in problem_id_map.abc2id) {
            if((apid in rank_data[i]) && (rank_data[i][apid].pst == 2 || rank_data[i][apid].pst == 3)) {
                rank_data[i].balloon ++;
                balloon_waiting_num ++;
            }
        }
        if(!(rank_data[i].user_id in map_team_balloon)) {
            continue;
        }
        for(let apid in problem_id_map.abc2id) {
            if((apid in rank_data[i]) && (rank_data[i][apid].pst == 2 || rank_data[i][apid].pst == 3)) {
                if(problem_id_map.abc2id[apid] in map_team_balloon[rank_data[i].user_id]) {
                    let balloon_task = map_team_balloon[rank_data[i].user_id][problem_id_map.abc2id[apid]];
                    rank_data[i][apid].bst = balloon_task.bst;
                    if(typeof(balloon_task.balloon_sender) == 'string' && balloon_task.balloon_sender != '') {
                        rank_data[i][apid].balloon_sender = balloon_task.balloon_sender;
                    }
                    if(balloon_task.bst >= 4) {
                        rank_data[i].balloon --;
                        balloon_waiting_num --;
                    }
                    if(balloon_task.bst == 4) {
                        balloon_assign_num ++;
                    }
                } else {
                    rank_data[i][apid].bst = 0;
                }
            }
        }
    }
    balloon_waiting_num_span.text(balloon_waiting_num);
    balloon_assign_num_span.text(balloon_assign_num);
    ranktable.bootstrapTable('load', rank_data);
}
function GetBalloonData() {
    $.get(`/${oj_module}/contest/ranklist_ajax?cid=${cid}`, function(ret) {    // oj_module 、 oj_controller 、 cid 在rank_header中初始化
        rank_data = ret;
        $.get(`/${oj_module}/contest/balloon_task_ajax?cid=${cid}`, function(ret) {
            if(ret.code == 1) {
                balloon_waiting_num = 0, balloon_assign_num = 0;
                balloon_task_list = ret.data.balloon_task_list;
                problem_id_map = ret.data.problem_id_map;
                InitFinishFlag();
            }
        });
    });    
}
let flg_init = 0;
function InitFinishFlag() {
    flg_init ++;
    if(flg_init >= 2) {
        InitBalloonTableShow();   
    }
}
function DoTableRefresh() {
    // 覆盖 rank_pub 中的表刷新方式
    GetBalloonData();
}
$('#balloon_refresh').click(function() {
    DoTableRefresh();
});
$(document).ready(function() {
    deleteBalloonKey = false;
    InitVars();
    GetBalloonData();
    GetBalloonSender();
    GetInfoLocal();
});
