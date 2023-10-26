let param = csg.Url2Json();
let cid = param.cid;
if('oj_mode' in param) {
    OJ_MODE = param.oj_mode;
}
let QUERY_MODULE = OJ_MODE == 'online' ? 'csgoj' : 'cpcsys';
const DEFAULT_SPEED = 512;
let flag_auto = false, auto_speed = DEFAULT_SPEED;
let flag_award_area = false;    // 标识是否进入奖区，进入奖区时取消自动模式一次
let flag_nowstep = null;
let flag_judgingdo_step = false;  // 当前操作为judge，而非初始sort或undo sort
let flag_keyvalid = true;
let flag_star_mode = 0;
const STAR_NORANK = 0, STAR_WITHOUT = 1, STAR_RANK = 2;
let cdata = null;  // 比赛原始数据
let time_start, time_end, time_frozen, time_frozen_end;
let cnt_base = null;    // 过题队数量作为评奖基数，金银铜向上取整
let real_rank_list = [], real_rank_map = {}, tmp_rank_list = [], tmp_rank_map = {};
let ratio_gold, ratio_silver, ratio_bronze;
let rank_gold, rank_silver, rank_bronze;
let map_team_sol, map_item = {}, map_team, map_p2num, map_num2p, map_fb, map_fb_now; // data maps
let stack_judge = [];
let judging_team_id, judging_pro_id, judging_team_id_last, judging_ac_flag;    // single judging now

let rank_header_div;
let loading_div;
let grid = null;
let rankroll_div;
let rank_grid_div;
let now_judging_ith = -1, now_order = [], map_now_order = {}, now_rank = {};
let summary_data;

function FontNarrow(target_dom, target_width=null) {
    target_dom.style.transform = "none";
    let width_txt = target_dom.scrollWidth;
    let width_div = target_width === null ? target_dom.clientWidth : target_width;
    if(width_txt > width_div) {
        let ratio = width_div / width_txt;
        target_dom.style['transform-origin'] = `left`;
        target_dom.style.transform = `scaleX(${ratio})`;
    }
}

function FontNarrowRank() {
    let g_name_list = document.getElementsByClassName('g_name');
    for(let i = 0; i < g_name_list.length; i ++) {
        FontNarrow(g_name_list[i]);
    }
}
function ContestUserId(user_id) {
    if(user_id.startsWith('#')) {
        return user_id.replace(/#cpc\d+?_/g, '');
    }
    return user_id;
}
function Str2Sec(time_str) {
    return Math.floor(new Date(time_str).getTime() / 1000 + 1e-6);
}
function SummaryTemplate() {
    return {
        4: 0,
        5: 0,
        6: 0,
        7: 0,
        8: 0,
        9: 0,
        10: 0,
        'sum': 0 
    }
}
const RES_CODE = {
    4:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-success">  <strong>题目通过<br/>（A C）</strong></div>',
    5:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-danger">   <strong>格式错误<br/>（P E）</strong></div>',
    6:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-danger">   <strong>结果错误<br/>（W A）</strong></div>',
    7:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-warning">  <strong>时间超限<br/>（TLE）</strong></div>',
    8:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-warning">  <strong>内存超限<br/>（MLE）</strong></div>',
    9:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-warning">  <strong>输出过多<br/>（OLE）</strong></div>',
    10: '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-warning">  <strong>运行错误<br/>（R E）</strong></div>',
    11: '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-info">     <strong>编译错误<br/>（C E）</strong></div>',
    0:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-default "> <strong>等待评测<br/>（P D）</strong></div>',
    1:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-default "> <strong>等待重测<br/>（P R）</strong></div>',
    2:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-default">  <strong>正在编译<br/>（C I）</strong></div>',
    3:  '<div style="height:60px;padding:5px;margin:2px;" class="alert alert-info">     <strong>正在运行<br/>（R J）</strong></div>',
}
function ProcessData() {
    cnt_base = null;
    map_team_sol = {};
    map_team = {};
    stack_judge = [];
    summary_data = {
        'total': SummaryTemplate(),
        'pro_list': []
    };
    for(let i = 0; i < cdata.problem.length; i ++) {
        summary_data.pro_list.push(SummaryTemplate());
    }
    
    time_start = Str2Sec(cdata.contest.start_time);
    time_end = Str2Sec(cdata.contest.end_time);
    time_frozen = time_end - parseInt(cdata.contest.frozen_minute) * 60;
    time_frozen_end = time_end + parseInt(cdata.contest.frozen_after) * 60;
    
    ratio_gold = cdata.contest.award_ratio % 1000;
    ratio_silver = Math.floor(cdata.contest.award_ratio / 1000) % 1000;
    ratio_bronze = Math.floor(cdata.contest.award_ratio / 1000000);
    if(ratio_gold < 100) {
        ratio_gold = ratio_gold / 100.0 - 0.0000001;
    }
    if(ratio_silver < 100) {
        ratio_silver = ratio_silver / 100.0;
    }
    if(ratio_bronze < 100) {
        ratio_bronze = ratio_bronze / 100.0;
    }

    for(let i = 0; i < cdata.team.length; i ++) {
        map_team[cdata.team[i].team_id] = cdata.team[i];
    }
    // process problem id map
    map_p2num = {};
    map_num2p = new Array(cdata.problem.length);
    for(let i = 0; i < cdata.problem.length; i ++) {
        map_p2num[cdata.problem[i].problem_id] = cdata.problem[i].num;
        map_num2p[cdata.problem[i].num] = cdata.problem[i].problem_id;
    }
    // process solutions
    cdata.solution.sort((a, b) => a.in_date == b.in_date ? 0 : (a.in_date < b.in_date ? -1 : 1));
    for(let i = 0; i < cdata.solution.length; i ++) {
        cdata.solution[i].user_id = ContestUserId(cdata.solution[i].user_id);
        cdata.solution[i].in_date = Str2Sec(cdata.solution[i].in_date);
        if(cdata.solution[i].in_date < time_start || 
            cdata.solution[i].in_date > time_end || 
            // cdata.solution[i].result < 4 || 
            cdata.solution[i].result == 11 ||
            !(cdata.solution[i].user_id in map_team)
        ) {
            continue;
        }
        // process summary
        let p_numid = map_p2num[cdata.solution[i].problem_id];
        if(cdata.solution[i].result in summary_data.pro_list[p_numid]) {
            summary_data.pro_list[p_numid][cdata.solution[i].result] ++;
            summary_data.pro_list[p_numid].sum ++;
            // console.log(p_numid, cdata.solution[i].result);
            summary_data.total[cdata.solution[i].result] ++;
            summary_data.total.sum ++;
        }
        if(!(cdata.solution[i].user_id in map_team_sol)) {
            map_team_sol[cdata.solution[i].user_id] = {};
            map_team_sol[cdata.solution[i].user_id].ac = {};
            map_team_sol[cdata.solution[i].user_id].frozen = {};
        }
        let user_sol = map_team_sol[cdata.solution[i].user_id];
        if(!(cdata.solution[i].problem_id in user_sol)) {
            user_sol[cdata.solution[i].problem_id] = [];
        }
        let user_sol_pro = user_sol[cdata.solution[i].problem_id];
        if(cdata.solution[i].problem_id in user_sol.ac) {
            continue;   // 已ac之后的提交忽略
        }
        if(cdata.solution[i].result >= 4) {
            // cdata.solution[i].frozen = false;
            if(cdata.solution[i].result == 4) {
                user_sol.ac[cdata.solution[i].problem_id] = cdata.solution[i].in_date;
            }
        } else {
            // cdata.solution[i].frozen = true;
            user_sol.frozen[cdata.solution[i].problem_id] = true;
        }
        user_sol_pro.push(cdata.solution[i]);
    }
    $('#page_header_main').text(cdata.contest.title);
    if(typeof(SummaryUpdate) == 'function') {
        SummaryUpdate();
    }
}
function Timeint2Str(sec_int) {
    let hour = Math.floor(sec_int / 3600 + 0.00000001);
    let mi = Math.floor(sec_int / 60 + 0.00000001) % 60;
    let sec = sec_int % 60;
    return `${pad0left(hour, 2, '0')}:${pad0left(mi, 2, '0')}:${pad0left(sec, 2, '0')}`;
}
function SolTime(in_date, mi=true, format_int=true) {
    let ret = in_date - time_start;
    if(mi) {
        ret = Math.floor(ret / 60 + 0.00000001);
    }
    if(!format_int) {
        if(mi) {
            ret *= 60;
        }
        ret =Timeint2Str(ret);
    }
    return ret;
}
function DomSantize(st) {
    return st
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
function StrEmpty(st) {
    return st === null || st.trim() == '';
}
function ProPenalty(team_id, pro_id, ith_ac) {
    return ith_ac * 20 * 60 + map_team_sol[team_id][pro_id][ith_ac].in_date - time_start;
}
function LoadData() {
    if(cid == null) {
        return;
    }
    let requests = [
        csg.get(`/${QUERY_MODULE}/contest/contest_data_ajax?cid=${cid}`)
    ];
    Promise.all(requests)
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(data => {
        let ret = data[0];
        if(ret.code == 1) {
            cdata = ret.data;
            ProcessData();
            ProcessItem();
            
        } else {
            alertify.error(ret.msg);
        }
        loading_div.hide();
    })
    .catch(error => {
        console.error('Error:', error); 
        loading_div.hide();
    });
}
function StopAutoRefresh(flag_msg=true) {
    if(interval_id != null) {
        clearInterval(interval_id);
        interval_id = null;
    }
    if(flag_msg) {
        alertify.message("关闭自动更新");
    }
}
function TryStopInterval() {
    if(typeof(interval_id) != 'undefined' && interval_id != null && TimeLocal() > cdata.contest.end_time) {
        clearInterval(interval_id);
        interval_id = null;
    }
}
function InitData(re_query=false) {
    // loading_div.show();
    try {
        if(re_query) {
            LoadData();
        } else {
            ProcessData();
            ProcessItem();
        }
    } catch (e) {
        console.error(e);
        loading_div.hide();
    }
}
function PostprocessDataItem(team_line_item) {
    if('penalty' in team_line_item) {
        // 处理penalty的单位
        team_line_item.penalty_mi = Math.floor(team_line_item.penalty / 60 + 0.00000001);
        team_line_item.penalty_sec = team_line_item.penalty;
        team_line_item.penalty = team_line_item.penalty_mi;
    }
    return team_line_item;
}
function TeamItem(team_id, with_dom=true) {
    if(!(team_id in map_team)) {
        return null;
    }
    let pro_res = TeamItemRes(team_id);
    return {
        'solved': pro_res.solved,
        'penalty': pro_res.penalty,
        'penalty_sec': pro_res.penalty_sec,
        'penalty_mi': pro_res.penalty_mi,
        'dom': with_dom ? `<div class="item" id="g_${team_id}" team_id="${team_id}" tkind="${map_team[team_id].tkind}" >
            <div class="item-content" solved="${pro_res.solved}" penalty="${pro_res.penalty}" team_id="${team_id}">
                <div class="g_td g_rank"></div>
                <div class="g_td g_logo"><img class="school_logo" school="${map_team[team_id].school}" onerror="SchoolLogoUriOnError(this)"/></div>
                <div class="g_team_content">
                    <div class="g_name" title="${map_team[team_id].tmember}&#10;${map_team[team_id].coach}&#10;${map_team[team_id].tkind == 0 ? '常规队' : (map_team[team_id].tkind == 1 ? '女队' : '打星队')}">
                    ${TkindIcon(map_team[team_id].tkind)}${DomSantize(map_team[team_id].school)}${!StrEmpty(map_team[team_id].school) && !StrEmpty(map_team[team_id].name) ? '：' : '' }${DomSantize(map_team[team_id].name)}
                    </div>
                    <div class="g_pro_group">
                        ${pro_res.pro_list_dom}
                    </div>
                </div>
                <div class="g_td g_solve">${pro_res.solved}</div>
                <div class="g_td g_time" title="${Timeint2Str(pro_res.penalty_sec)}">${pro_res.penalty}</div>
            </div>
        </div>` : ''
    }
}
function InitGrid() {
    if(grid != null) {
        return;
        grid.destroy(true);
    }
    grid = new Muuri('.grid', {
        dragEnabled: false,
        layoutOnInit: true,
        horizontal: false,
        dragAxis: 'y',
            
        layoutDuration: Math.min(2000, Object.keys(map_team).length * 10),
        layoutEasing: 'cubic-bezier(1, 0, 1, 1)',
        // layoutEasing: 'ease-in',
        sortData: {
            solved: function (item, element) {
                return parseInt(element.children[0].getAttribute('solved'));
            },
            penalty: function (item, element) {
                return parseInt(element.children[0].getAttribute('penalty'));
            },
            team_id: function (item, element) {
                return element.children[0].getAttribute('team_id');
            },
        }
    });
    grid.on('sort', function (currentOrder, previousOrder) {
        let pre_ith, after_ith;
        if(flag_judgingdo_step) {
            pre_ith = now_rank[judging_team_id].ith;
        }
        GridResetRank(currentOrder);
        if(flag_judgingdo_step) {
            after_ith = now_rank[judging_team_id].ith;
            let layout_dur = Math.min(Math.max((Math.abs(after_ith - pre_ith) / 10 + 1) * auto_speed << 1, DEFAULT_SPEED), 3000);
            grid._settings.layoutDuration = layout_dur;
        } else {
            grid._settings.layoutDuration = DEFAULT_SPEED;
        }
    });
    grid.on('filter', function (shownItems, hiddenItems) {
        JudgeSort(false);
    });
}
let interval_id = null;
function StartAutoRefresh(flag_msg=true) {
    interval_id = setInterval(() => {
        InitData(true);
    }, 60000);
    if(flag_msg=true) {
        alertify.success("开启自动更新");
    }
}
let flag_auto_scroll=false;
let auto_scroll_task_id = [];
function AutoScroll(duration, delay, first=true) {
    if(!flag_auto_scroll) {
        return;
    }
    if(first) {
        window.scrollTo(0, 0);
        auto_scroll_task_id.push(setTimeout(() => AutoScroll(duration, delay, false), 0));
    } else {
        // const perTick = document.body.scrollHeight / duration * 50;
        const perTick = 500;
        auto_scroll_task_id.push(setTimeout(function() {
            window.scrollBy(0, perTick);
            if(flag_auto_scroll) {
                if (window.innerHeight + window.scrollY < document.body.scrollHeight) {
                    AutoScroll(duration, delay, false);
                } else {
                    auto_scroll_task_id.push(setTimeout(function() {
                        AutoScroll(duration, delay, true);
                    }, 5000));
                }
            }
        }, delay));
    }
}

$(document).ready(function() {
    rank_header_div = $('#rank_header_div');
    loading_div = $('#loading_div');
    rankroll_div = $("#rankroll_div");
    rank_grid_div = $('#rank_grid_div');
    $('#alink_school').attr('href', 'schoolrank?cid=' + cid);
    $('#alink_team').attr('href', 'rank?cid=' + cid);
    loading_div.show();
    InitData(true);
    $('#with_star_team').change(function() {
        flag_star_mode = parseInt(this.value);
        if(typeof(SetAwardRank) == 'function') {
            SetAwardRank(false);
        }
        GridFilter();
        flag_award_area = false
    });
    let sticky = rank_header_div.offset().top;
    window.onscroll = function() {
        if (window.scrollY > sticky) {
            rank_header_div.addClass("sticky");
        } else {
            rank_header_div.removeClass("sticky");
        }
    };
});

window.onkeydown = (event) => {
    if (!event || !event.isTrusted || !event.cancelable) {
        return;
    }
    const key = event.key;
    if (key === 'F5') {
        event.preventDefault();
        event.returnValue = false;
        InitData(true);
    }
}

$(document).on('click', function (e) {
    if(e.detail == 3){
        if(interval_id === null) {
            StartAutoRefresh();
        } else {
            StopAutoRefresh();
        }
    }
});


let flag_forbid_f5 = false;

window.onkeydown = (e) => {
    if (!e || !e.isTrusted || !e.cancelable) {
        return;
    }
    if(e.key == 'A' || e.key == 'a') {
        if(interval_id === null) {
            StartAutoRefresh();
        } else {
            StopAutoRefresh();
        }
    } else if(e.key == 'B' || e.key == 'b') {
        flag_auto_scroll = !flag_auto_scroll;
        if(flag_auto_scroll) {
            alertify.success("开启自动滚动");
            AutoScroll(1000, 10000, true);
        } else {
            for(let i = 0; i < auto_scroll_task_id.length; i ++) {
                try {
                    clearTimeout(auto_scroll_task_id[i]);
                } catch {}
            }
            auto_scroll_task_id = [];
            alertify.message("关闭自动滚动");
        }

    } else if(e.keyCode == 116 && !e.ctrlKey) {
        e.preventDefault();
        if(flag_forbid_f5) {
            alertify.warning("不需要太频繁刷新呦~")
        } else {
            flag_forbid_f5 = true;
            setTimeout(()=>{flag_forbid_f5=false;}, 5000);
            alertify.success("更新数据...")
            StopAutoRefresh(false);
            InitData(true);
        }
    }
}

