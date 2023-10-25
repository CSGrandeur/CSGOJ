var autoRefreshInterval = 20;
var ac_color_to_chose = ['#90ee90', '#f9f3a1'];
var ac_color = ac_color_to_chose[0];
var table_div = $('#ranklist_table_div');
var ranktable = $('#ranklist_table');
var auto_refresh_time_span = $('#auto_refresh_time');
var auto_refresh_time = autoRefreshInterval;
var auto_refresh_time_timeout;
let rank_config;
// var color_blind_mode_box = $('#color_blind_mode_box');
var auto_refresh_box = $('#auto_refresh_box');
let fb_include_star_box = $('#fb_include_star_box');
let rank_db;

function FormatterRankUserId(value, row, index, field) {
    let team_url;
    if(ckind == 'csgoj') {
        team_url = `/csgoj/user/userinfo?user_id=${value}`;
    } else {
        team_url = `/${ckind}/contest/teaminfo?cid=${cid}&team_id=${value}`;
    }
    return `<a href="${team_url}" >${value}</a>`;
}
ranktable.on('post-body.bs.table', function(){
    //处理rank宽度
    if(ranktable[0].scrollWidth > table_div.width())
        table_div.width(ranktable[0].scrollWidth + 20);
});
//set flag for delete balloon mark, 'D' on keybord or middle key on mouse for backup.
$(window).keydown(function(e) {
    //prevent F5 to locally refresh table
    if (e.keyCode == 116 && !e.ctrlKey) {
        if(window.event){
            try{e.keyCode = 0;}catch(e){}
            e.returnValue = false;
        }
        e.preventDefault();
        ranktable.bootstrapTable('refresh');
    }
});
function LoadRankRemote(set_cache) {
    let rank_url = rank_config.attr('url');
    $.get(rank_url, function(ret) {
        ranktable.bootstrapTable('load', ret);
        if(typeof RankLoadSuccessCallback === 'function') {
            RankLoadSuccessCallback(ret);
        }
        if(set_cache) {
            try {
                rank_db.set(`${rank_url}_time`, Date.now());
                rank_db.set(rank_url, ret);
            } catch(e) {
                console.error(e);
            }
        }
    });
}
function RankDataPreprocess(data) {
    // 在rank数据被各类功能使用前，统一按需进行预处理
    if(typeof(data) != 'undefined') {
        let last_team = -1, rank_mi_now = 0, rank_mi_cnt = 0;
        for(var i = 0; i < data.length; i ++) {
            data[i].rank_sec = data[i].rank;    // 留存总罚时按秒的rank
            data[i].penalty_mi = Math.floor(Timestr2Sec(data[i].penalty) / 60 + 0.00000001);
            if(data[i]['tkind'] == 2) {
                data[i].rank_mi = '*';
            } else {
                rank_mi_cnt ++;
                if(last_team == -1 || data[i].solved < data[last_team].solved || data[i].penalty_mi > data[last_team].penalty_mi) {
                    rank_mi_now = rank_mi_cnt;
                }
                data[i].rank_mi = rank_mi_now;
                last_team = i;
            }
            data[i].rank = data[i].rank_mi; // 名次按总罚时近似到分钟以减少偶然性误差，暂不做配置项。
        }
    }
}
function LoadRankData(flag_init=false) {
    let rank_url = rank_config.attr('url');
    if(rank_config.attr('use_cache') == 1) {
        rank_db.get(`${rank_url}_time`).then(tm => {
            let now = Date.now();
            if(now - tm > 15000) {
                LoadRankRemote(true);
            } else {
                rank_db.get(rank_url).then(rank_list => {
                    if(typeof(rank_list) == 'string') {
                        rank_list = JSON.parse(rank_list);
                    }
                    ranktable.bootstrapTable('load', rank_list);
                    if(flag_init) {
                        if(typeof RankLoadSuccessCallback === 'function') {
                            RankLoadSuccessCallback(rank_list);
                        }
                    }
                }).catch((e) => {
                    LoadRankRemote(true);
                })
            }
        }).catch((e) => {
            LoadRankRemote(true);
        })
    } else {
        LoadRankRemote(false);
    }
}
let flag_db_ready = false;
let flag_table_ready = false;
let flag_first_load = false;
function ReadyFlag() {
    if(flag_db_ready && flag_table_ready) {
        LoadRankData(true);
    }
}
ranktable.on('post-body.bs.table', function() {
    flag_table_ready = true;
    if(!flag_first_load) {
        flag_first_load = true;
        ReadyFlag();
    }
});
ranktable.on('refresh.bs.table', function() {
    LoadRankData();
});
$(document).ready(function() {
    rank_config = $('#rank_config');
    InitIndexedDb('ranktable', function(db) {rank_db = db; flag_db_ready = true; ReadyFlag();})

    auto_refresh_box.bootstrapSwitch();
    auto_refresh_time_span.text(auto_refresh_time);
    if (localStorage.getItem('auto_refresh_box')) {
        var auto_refresh = $.trim(localStorage.getItem('auto_refresh_box'));
        if(auto_refresh == 'true') {
            auto_refresh_box.bootstrapSwitch('state', true, true);
        }
        else {
            auto_refresh_box.bootstrapSwitch('state', false, true);
        }
    }
    if(auto_refresh_box.bootstrapSwitch('state')) {
        setTimeout(function(){RefreshTable();}, autoRefreshInterval);
    }

    // fb_include_star_box.bootstrapSwitch();
    // if (localStorage.getItem('fb_include_star_box')) {
    //     var fb_include_star = $.trim(localStorage.getItem('fb_include_star_box'));
    //     if(fb_include_star == 'true') {
    //         fb_include_star_box.bootstrapSwitch('state', true, true);
    //     }
    //     else {
    //         fb_include_star_box.bootstrapSwitch('state', false, true);
    //     }
    // }

});
auto_refresh_box.on('switchChange.bootstrapSwitch', function(event, state) {
    if(state == true) {
        RefreshTable();
        localStorage.setItem('auto_refresh_box', 'true');
    }
    else{
        auto_refresh_time = autoRefreshInterval;
        auto_refresh_time_span.text(auto_refresh_time);
        clearTimeout(auto_refresh_time_timeout);
        localStorage.setItem('auto_refresh_box', 'false');
    }
});

// fb_include_star_box.on('switchChange.bootstrapSwitch', function(event, state) {
//     if(state == true) {
//         localStorage.setItem('fb_include_star_box', 'true');
//     }
//     else{
//         localStorage.setItem('fb_include_star_box', 'false');
//     }
// });
function RefreshTable()
{
    auto_refresh_time --;
    if(auto_refresh_time <= 0)
    {
        ranktable.bootstrapTable('refresh');
        auto_refresh_time = autoRefreshInterval;
    }
    auto_refresh_time_span.text(pad0left(auto_refresh_time, 2, '0'));
    if(auto_refresh_box.bootstrapSwitch('state')) {
        auto_refresh_time_timeout = setTimeout(function () {
            RefreshTable();
        }, 1000);
    }
}
function rankSorter(a, b, ra, rb) {
    if(ra.solved == rb.solved) {
        if(ra.penalty == rb.penalty) {
            return ra.user_id < rb.user_id ? -1 : 1;
        } else {
            return ra.penalty < rb.penalty ? -1 : 1;
        }
    } else {
        return ra.solved < rb.solved ? 1 : -1;
    }
}
const wa_color = '#ee9090';
const first_blood_color = '#69b2f1';
const tr_color = '#A0A0A0';
const rank_cell_color_list = [
    'transparent',
    wa_color,
    ac_color,
    first_blood_color,
    'transparent',
    tr_color
]
function IndexFormatter(value, row, index) {
    return index + 1;
};
function CellStyleName(value, row, index)
{
    return {
        css: {'min-width': '300px'}
    };
}
function FormatterTkind(value, row, index) {
    let v = value === null ? 0 : value;
    let glyphicon = "console", title_tip = "Common Team", txtcolor="text-success";
    if(v == 1) {
        glyphicon = "heart", title_tip = "Girls Team", txtcolor="text-danger";
    } else if(v == 2) {
        glyphicon = "asterisk", title_tip = "Star Team", txtcolor="text-primary";
    }
    return `<span class="${txtcolor} glyphicon glyphicon-${glyphicon}" aria-hidden="true" title="${title_tip}"></span>`;
}
function schoolCellStyle(value, row, index)
{
    return {
        css: {'min-width': '180px'}
    };
}
function memberCellStyle(value, row, index)
{
    return {
        css: {'min-width': '220px'}
    };
}
function coachCellStyle(value, row, index)
{
    return {
        css: {'min-width': '150px'}
    };
}
document.addEventListener('click', function(e) {
    if(e.target.id == 'rank_fullscreen_btn' || e.target.id == 'rank_fullscreen_span') {
        e.stopPropagation();
        document.getElementById("rank_div").classList.toggle('rank_fullscreen');
    }
});
document.addEventListener('keydown', function(e) {
    if (!e || !e.isTrusted || !e.cancelable) {
        return;
    }
    if(e.key == 'Escape') {
        let rank_div = document.getElementById("rank_div");
        if(rank_div != null && rank_div.classList.contains('rank_fullscreen')) {
            rank_div.classList.remove('rank_fullscreen');
        }
    }
});