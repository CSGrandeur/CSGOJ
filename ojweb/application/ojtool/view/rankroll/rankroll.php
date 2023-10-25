{js href='__STATIC__/ojtool/js/rank_tool.js' /}
<h1>滚榜：{$contest['title']}</h1>
<div class="btn-group input-group" role="group">
    <button class="btn btn-warning button_fullscreen" disabled><i class="bi bi-arrows-fullscreen"></i>&nbsp;启动</button>
    <button class="btn btn-primary button_init_data"><i class="bi bi-cloud-download"></i>&nbsp;初始化</button>
    <button class="btn btn-info button_help"><i class="bi bi-chat-right-text-fill"></i>&nbsp;帮助</button>
    <select class="exam_info_form form-select" name="with_star_team" id="with_star_team" aria-label="open or close">
        <option selected value="0">打星不排名</option>
        <option value="1">不含打星</option>
        <option value="2">打星参与排名</option>
    </select>
    <a href="__OJTOOL__/rankroll/team_image?cid={$contest['contest_id']}" target="_blank"><button class="btn btn-success"><i class="bi bi-card-image"></i>&nbsp;队伍照片</button></a>
</div>

{include file="public/js_toolbox" /}
{include file="../../csgoj/view/public/js_identicon" /}

<div id="rankroll_div">
    <div id="rank_header_div">
        <div class="h_td h_rank">排名</div>
        <div class="h_td h_logo">图标</div>
        <div class="h_td h_team_content">队伍</div>
        <div class="h_td h_solve">题数</div>
        <div class="h_td h_time">罚时(s)</div>
    </div>
    <div class="grid" id="rank_grid_div">
            
    </div>
    <!-- Modal -->
    <div class="modal fade" id="award_modal_div" tabindex="-1" role="dialog" aria-labelledby="award_modal_div_label" aria-hidden="true">
        <div class="modal-dialog modal-award" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="award_modal_div_label_span">获奖信息</span> &nbsp;&nbsp;</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button> -->
                </div>
                <div class="modal-body" id="award_modal_div_content">
                    <div id="award_div">
                        <div class="award_img_col">
                            <img id="award_team_img" award="" src="#" onerror="TeamPhotoUriOnError(this)" />
                        </div>
                        <div class="award_info_col">
                            <div id="award_info" award="">
                                <div id="award_level" award=""></div>
                                <div id="awrad_school_logo"><img school="#" src="#" onerror="$('#awrad_school_logo').hide()" /></div>
                                <div id="award_school"  class="award_info_div"><div class="award_info_title">单位：</div><div class="award_info_content"></div></div>
                                <div id="award_name"    class="award_info_div"><div class="award_info_title">队伍：</div><div class="award_info_content"></div></div>
                                <div id="award_tmember" class="award_info_div"><div class="award_info_title">成员：</div><div class="award_info_content"></div></div>
                                <div id="award_coach"   class="award_info_div"><div class="award_info_title">教练：</div><div class="award_info_content"></div></div>
                                <div id="award_rk"      class="award_info_div"><div class="award_info_title">名次：</div><div class="award_info_content"></div></div>
                                <div id="award_fb"      class="award_info_div"><div class="award_info_title">首答：</div><div class="award_info_content"></div></div>
                                <div id="award_solved"  class="award_info_div"><div class="award_info_title">题数：</div><div class="award_info_content"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type='hidden' id='page_info' cid="{$contest['contest_id']}" contest_attach="{$contest['attach']}">


<div id="loading_div" class='overlay'>
    <div id="loading_spinner" class="spinner-border">&nbsp;初始化中...</div>
</div>

<link  href="__STATIC__/ojtool/css/rankroll.css" rel="stylesheet" >


<script>
let cid = parseInt($('#page_info').attr('cid')), contest_attach = $('#page_info').attr('contest_attach');
let flag_rolling = false;
const DEFAULT_SPEED = 256;
let flag_auto = false, auto_speed = DEFAULT_SPEED;
let flag_award_area = false;    // 标识是否进入奖区，进入奖区时取消自动模式一次
let flag_nowstep = null;
let flag_judgingdo_step = false;  // 当前操作为judge，而非初始sort或undo sort
let flag_keyvalid = true;
let flag_star_mode = 0;
const STAR_NORANK = 0, STAR_WITHOUT = 1, STAR_RANK = 2;
let cdata;  // 比赛原始数据
let time_start, time_end, time_frozen;
let cnt_base = null;    // 过题队数量作为评奖基数，金银铜向上取整
let real_rank_list = [], real_rank_map = {}, tmp_rank_list = [], tmp_rank_map = {};
let ratio_gold, ratio_silver, ratio_bronze;
let rank_gold, rank_silver, rank_bronze;
let map_team_sol, map_item, map_team, map_p2num, map_num2p, map_fb, map_fb_now; // data maps
let stack_judge = [];
let judging_team_id, judging_pro_id, judging_team_id_last, judging_ac_flag;    // single judging now

let rank_header_div = $('#rank_header_div');
let loading_div = $('#loading_div');
let award_modal_div = $('#award_modal_div');
let award_modal_obj = new bootstrap.Modal(document.getElementById('award_modal_div'));
// AwardModalToggle(true);

let grid = null;
let rankroll_div = $("#rankroll_div");
let rank_grid_div = $('#rank_grid_div');
let now_judging_ith = -1, now_order = [], map_now_order = {}, now_rank = {};
function GridFilter() {
    if(grid != null) {
        grid.filter(function(item) {
            return flag_star_mode != STAR_WITHOUT || parseInt(item.getElement().getAttribute('tkind')) != 2;
        });
        now_judging_ith = Object.keys(map_item).length;
        judging_team_id_last = null;
    }
}
function GridResetRank(currentOrder) {
    function RankDivClass(rk_show) {
        if(rk_show === '*') {
            return '';
        } else if(rk_show <= rank_gold) {
            return 'gold';
        } else if(rk_show <= rank_silver) {
            return 'silver';
        } else if(rk_show <= rank_bronze) {
            return 'bronze';
        }
    }
    $('.g_rank').removeAttr('award');
    now_order = [];
    map_now_order = {};
    now_rank = {}; 
    let rk_real = 0, rk_show, last_item = null, show_i = 0;
    for(let i = 0; i < currentOrder.length; i ++) {
        let team_id = currentOrder[i]._element.getAttribute('team_id');
        let team_info = map_team[team_id];
        let star_team = false;
        if(team_info.tkind == 2) {
            if(flag_star_mode == STAR_WITHOUT) {
                continue;
            } else if(flag_star_mode == STAR_NORANK) {
                star_team = true;
            }
        }
        now_order.push(team_id);
        map_now_order[team_id] = now_order.length - 1;
        if(!star_team) {
            rk_real ++;
        }
        if(last_item == null || last_item.solved > map_item[team_id].solved || last_item.penalty < map_item[team_id].penalty) {
            rk_show = rk_real;
        }
        if(!star_team) {
            last_item = map_item[team_id];
        }
        let now_rank_show = star_team ? '*' : rk_show;
        now_rank[team_id] = {
            rank: now_rank_show,
            ith: show_i + 1
        }
        map_item[team_id].dom.find('.g_rank').text(now_rank_show).attr('award', RankDivClass(now_rank_show));
        map_item[team_id].dom.attr('linetype', show_i & 1 ? 'odd' : 'even');
        show_i ++;
    }
}
function InitGrid() {
    if(grid != null) {
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
function FontNarrowAward() {
    let award_info_list = document.getElementsByClassName('award_info_content');
    for(let i = 0; i < award_info_list.length; i ++) {
        FontNarrow(award_info_list[i]);
    }
}
function ContestUserId(user_id) {
    if(user_id.startsWith('#')) {
        return user_id.replace(`#cpc${cid}_`, '');
    }
    return user_id;
}
function Str2Sec(time_str) {
    return Math.floor(new Date(time_str).getTime() / 1000 + 1e-6);
}
function ProcessData() {
    cnt_base = null;
    map_team_sol = {};
    map_team = {};
    stack_judge = [];
    // time_start = cdata.contest.start_time;
    // time_end = cdata.contest.end_time;
    // time_frozen = Timestamp2Time(new Date(time_end).getTime() - parseInt(cdata.contest.frozen_minute) * 60 * 1000);
    
    time_start = Str2Sec(cdata.contest.start_time);
    time_end = Str2Sec(cdata.contest.end_time);
    time_frozen = time_end - parseInt(cdata.contest.frozen_minute) * 60;
    
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
    cdata.solution.sort((a, b) => a.in_date == b.in_date ? 0 : (a.in_date < b.in_date ? -1 : 1));
    for(let i = 0; i < cdata.solution.length; i ++) {
        cdata.solution[i].user_id = ContestUserId(cdata.solution[i].user_id);
        cdata.solution[i].in_date = Str2Sec(cdata.solution[i].in_date);
        if(cdata.solution[i].in_date < time_start || 
            cdata.solution[i].in_date > time_end || 
            cdata.solution[i].result < 4 || 
            cdata.solution[i].result == 11 ||
            !(cdata.solution[i].user_id in map_team)
        ) {
            continue;
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
        if(cdata.solution[i].in_date <= time_frozen) {
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
    map_p2num = {};
    map_num2p = new Array(cdata.problem.length);
    for(let i = 0; i < cdata.problem.length; i ++) {
        map_p2num[cdata.problem[i].problem_id] = cdata.problem[i].num;
        map_num2p[cdata.problem[i].num] = cdata.problem[i].problem_id;
    }
}
function TeamPhotoUri(team_id) {
    return `/upload/contest_attach/${contest_attach}/team_photo/${team_id}.jpg?t=${new Date().getTime()}`;
}
function TeamPhotoUriOnError(img_obj) {
    let ic;
    let award = img_obj.getAttribute('award');
    if(award == 'gold') {
        ic = "🥇";
    } else if(award == 'silver') {
        ic = "🥈"
    } else {
        ic = "🥉"
    }
    if(img_obj.getAttribute('award'))
    img_obj.src = `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'%3E%3Ctext x='50%25' y='50%25' font-size='32rem' fill='%23a2a9b6' font-family='等线,system-ui, sans-serif' text-anchor='middle' dominant-baseline='middle'%3E${ic}%3C/text%3E%3C/svg%3E`
}
function SolTime(in_date, mi=true) {
    let ret = in_date - time_start;
    if(mi) {
        ret = parseInt(ret / 60);
    }
    return ret;
}
function TeamItemRes(team_id) {
    let solved = 0;
    let penalty = 0;
    let sol = map_team_sol[team_id];
    let pro_list_dom = ``;
    for(let i = 0; i < map_num2p.length; i ++) {
        let pro_status = '';
        let submit_num = map_num2p[i] in sol ? sol[map_num2p[i]].length : '';
        let last_submit = map_num2p[i] in sol ? SolTime(sol[map_num2p[i]][sol[map_num2p[i]].length - 1].in_date) : '';
        let pro_idx = String.fromCharCode('A'.charCodeAt(0) + i);
        if(map_num2p[i] in sol.ac) {
            solved ++;
            penalty += ProPenalty(team_id, map_num2p[i], sol[map_num2p[i]].length - 1);
            pro_status = 'g_pro_ac';
        } else if(map_num2p[i] in sol.frozen) {
            pro_status = 'g_pro_pd';
        } else if(map_num2p[i] in sol) {
            pro_status = 'g_pro_wa';
        } else {
            pro_status = 'g_pro_nn';
        }
        pro_list_dom += `
        <div class="g_pro ${pro_status}" problem_id="${map_num2p[i]}">
            <span class="g_pro_lspan">${submit_num}</span>
            <span class="g_pro_mspan">${pro_idx}</span>
            <span class="g_pro_rspan">${last_submit}</span>
        </div>
        `
    }
    return {
        'solved': solved,
        'penalty': penalty,
        'pro_list_dom': pro_list_dom
    }
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
function TeamItem(team_id) {
    if(!(team_id in map_team)) {
        return null;
    }
    let pro_res = TeamItemRes(team_id);
    return {
        'solved': pro_res.solved,
        'penalty': pro_res.penalty,
        'dom': `<div class="item" id="g_${team_id}" team_id="${team_id}" tkind="${map_team[team_id].tkind}">
            <div class="item-content" solved="${pro_res.solved}" penalty="${pro_res.penalty}" team_id="${team_id}">
                <div class="g_td g_rank"></div>
                <div class="g_td g_logo"><img class="school_logo" school="${map_team[team_id].school}" onerror="SchoolLogoUriOnError(this)"/></div>
                <div class="g_team_content">
                    <div class="g_name">${TkindIcon(map_team[team_id].tkind)}${DomSantize(map_team[team_id].school)}${!StrEmpty(map_team[team_id].school) && !StrEmpty(map_team[team_id].name) ? '：' : '' }${DomSantize(map_team[team_id].name)}</div>
                    <div class="g_pro_group">
                        ${pro_res.pro_list_dom}
                    </div>
                </div>
                <div class="g_td g_solve">${pro_res.solved}</div>
                <div class="g_td g_time">${pro_res.penalty}</div>
            </div>
        </div>`
    }
}
function ProcessItem() {
    map_item = {};
    InitGrid();
    now_judging_ith = -1;
    judging_team_id_last = null;
    for(let team_id in map_team_sol) {
        let team_item = TeamItem(team_id);
        if(team_item != null) {
            team_item.dom = $(team_item.dom);
            map_item[team_id] = team_item
            // rank_grid_div.append(dom_obj);
            grid.add(team_item.dom[0]);
            now_judging_ith ++;
        }
    }
    let gitem_list = grid.getItems();
    for(let i = 0; i < gitem_list.length; i ++) {
        map_item[gitem_list[i]._element.getAttribute('team_id')].gitem = gitem_list[i];
    }
    GridFilter();
    flag_award_area = false;
    FontNarrowRank();
    SetAwardRank(true);
    JudgeSort();
    if(Object.keys(map_team).length == 1) {
        // 只有一个队时不会触发sort事件
        GridResetRank([Object.values(map_item)[0].gitem]);
    }
    BatchProcessSchoolLogo();
}
function ProPenalty(team_id, pro_id, ith_ac) {
    return ith_ac * 20 * 60 + map_team_sol[team_id][pro_id][ith_ac].in_date - time_start;
}
function SetFB() {
    let mf = map_fb.formal;
    if(flag_star_mode === STAR_RANK) {
        mf = map_fb.global;
    }
    $('.g_pro_fb').removeClass('g_pro_fb');
    for(let pro_id in mf) {
        for(let team_id in mf[pro_id].teams) {
            map_item[team_id].dom.find(`.g_pro[problem_id="${pro_id}"]`).addClass('g_pro_fb');            
        }
    }
}
function SetAwardRank(re_calc=false) {
    // get real final rank
    function UpdateFb(mf, team_id, pro_id, in_date) {
        if(!(pro_id in mf) || in_date < mf[pro_id].in_date) {
            mf[pro_id] = {
                'in_date': in_date,
                'teams': {}
            }
        }
        if(in_date == mf[pro_id].in_date) {
            mf[pro_id].teams[team_id] = true;
        }
    }
    if(re_calc) {
        map_fb = {
            'global': {},
            'formal': {}
        };
        real_rank_list = [];
        for(let team_id in map_team_sol) {
            item = {
                'team_id': team_id,
                'sol': 0,
                'penalty': 0
            }
            let team_sol = map_team_sol[team_id];
            for(let i = 0; i < map_num2p.length; i ++) {
                let pro_id = map_num2p[i];
                if(pro_id in team_sol.ac) {
                    UpdateFb(map_fb.global, team_id, pro_id, team_sol[pro_id][team_sol[pro_id].length - 1].in_date);
                    if(map_team[team_id].tkind != 2) {
                        UpdateFb(map_fb.formal, team_id, pro_id, team_sol[pro_id][team_sol[pro_id].length - 1].in_date);
                    }
                    item.sol ++;
                    item.penalty += ProPenalty(team_id, pro_id, team_sol[pro_id].length - 1);
                } else if(pro_id in team_sol) {
                    for(let j = 0; j < team_sol[pro_id].length; j ++) {
                        if(team_sol[pro_id][j].result == 4) {
                            UpdateFb(map_fb.global, team_id, pro_id, team_sol[pro_id][j].in_date);
                            if(map_team[team_id].tkind != 2) {
                                UpdateFb(map_fb.formal, team_id, pro_id, team_sol[pro_id][j].in_date);
                            }
                            item.sol ++;
                            item.penalty += ProPenalty(team_id, pro_id, j);
                            break;
                        }
                    }
                }
            }
            real_rank_list.push(item);
        }
        real_rank_list.sort((a, b) => {
            if(a.sol == b.sol) {
                if(a.penalty == b.penalty) {
                    if(a.team_id == b.team_id) {
                        return 0;
                    } else {
                        return a.team_id < b.team_id ? -1 : 1;
                    }
                } else {
                    return a.penalty < b.penalty ? -1 : 1;
                }
            } else {
                return a.sol < b.sol ? 1 : -1;
            }
        });
    }
    let rk_real = 0, rk_show = 0, last_item = null, show_i = 0;
    cnt_base = 0;
    real_rank_map = {};
    for(let i = 0; i < real_rank_list.length; i ++) {
        let tm_rrkl = real_rank_list[i];
        let star_team = false;
        if(map_team[tm_rrkl.team_id].tkind == 2) {
            if(flag_star_mode == STAR_WITHOUT) {
                continue;
            } else if(flag_star_mode == STAR_NORANK) {
                star_team = true;
            }
        }
        if(!star_team) {
            rk_real ++;
        }
        if(last_item == null || tm_rrkl.sol < last_item.sol || tm_rrkl.penalty > last_item.penalty) {
            rk_show = rk_real;
        }
        if(!star_team) {
            last_item = tm_rrkl;
        }
        real_rank_map[tm_rrkl.team_id] = {
            rank: star_team ? '*' : rk_show,
            ith: show_i + 1
        }
        show_i ++;
        if(real_rank_list[i].sol) {
            cnt_base += star_team ? 0 : 1;
        }
    }
    SetFB();
    
    rank_gold = ratio_gold >= 100 ? ratio_gold - 100 : Math.ceil(cnt_base * ratio_gold);
    let tmp_ratio_gold = ratio_gold >= 100 ? rank_gold / cnt_base : ratio_gold;
    rank_silver = ratio_silver >= 100 ? rank_gold + ratio_silver - 100 : Math.ceil(cnt_base * (tmp_ratio_gold + ratio_silver));
    let tmp_ratio_silver = ratio_silver >= 100 ? rank_silver / cnt_base : tmp_ratio_gold + ratio_silver;
    rank_bronze = ratio_bronze >= 100 ? rank_silver + ratio_bronze - 100 : Math.ceil(cnt_base * (tmp_ratio_silver + ratio_bronze));
}
function DomJudgeConfirm(team_id, pro_id, undo=false, pos_before=null) {
    AwardModalToggle(false);
    $('.g_pro_judging').removeClass('g_pro_judging');
    $('.g_team_judging').removeClass('g_team_judging');
    map_item[team_id].dom.addClass('g_team_judging');
    if(pro_id !== null) {
        map_item[team_id].dom.find(`.g_pro[problem_id="${pro_id}"]`).addClass('g_pro_judging');
    }
    if(undo || stack_judge.length == 0 || stack_judge[stack_judge.length - 1].team_id != team_id) {
        var elementPosition = pos_before === null ? map_item[team_id].gitem._top : pos_before;
        var offsetPosition = elementPosition - $(window).height() + 300;
        rankroll_div.animate({
            scrollTop: offsetPosition
        }, auto_speed);
    }
}
function JudgeConfirm() {
    flag_nowstep = 'confirm';
    let team_sol;
    
    AwardModalToggle(false);
    for(; now_judging_ith >= 0; now_judging_ith --) {
        if(!(now_judging_ith in now_order) || !(now_order[now_judging_ith] in map_team_sol)) {
            continue;
        }
        team_sol = map_team_sol[now_order[now_judging_ith]];
        let frozen_flag = false;
        for(let i = 0; i < map_num2p.length; i ++) {
            if(map_num2p[i] in team_sol.frozen) {
                frozen_flag = true;
                judging_pro_id = map_num2p[i];
                break;
            }
        }
        judging_team_id = now_order[now_judging_ith];
        if(!frozen_flag) {
            if(judging_team_id_last === judging_team_id) {
                // 无frozen题目情况下是否已查看过该team
                continue;
            }
            judging_pro_id = null;
        }
        judging_team_id_last = judging_team_id;
        DomJudgeConfirm(judging_team_id, judging_pro_id);
        if(flag_auto) {
            setTimeout(function(){JudgeDo();}, auto_speed);
        }
        return {
            'team_id': judging_team_id,
            'pro_id': judging_pro_id
        }
    }
    return null;
}
function UpdateSolPenalty(team_id, solved, penalty) {
    let team_item = map_item[team_id];
    team_item.dom.find('.item-content').attr('solved', solved).attr('penalty', penalty);
    team_item.dom.find('.g_solve').text(solved);
    team_item.dom.find('.g_time').text(penalty);
    team_item.gitem._sortData.solved = parseInt(solved);
    team_item.gitem._sortData.penalty = parseInt(penalty);
}
function JudgeDo() {
    flag_nowstep = 'do';
    flag_judgingdo_step = true;
    if(now_judging_ith < 0) {
        return;
    }
    let change_solved = 0;
    let change_penalty = 0;
    if(judging_pro_id !== null) {
        let team_sol = map_team_sol[judging_team_id];
        let submit_num = 0;
        let last_submit_time = 0;
        let ac = false;
        for(let i = 0; i < team_sol[judging_pro_id].length; i ++) {
            submit_num = i + 1;
            last_submit_time = team_sol[judging_pro_id][i].in_date;
            if(team_sol[judging_pro_id][i].result == 4) {
                ac = true;
                break;
            }
        }
        let team_item = map_item[judging_team_id];
        let pro_div = team_item.dom.find(`.g_pro[problem_id="${judging_pro_id}"]`);
        judging_ac_flag = false;
        if(ac) {
            judging_ac_flag = true;
            change_solved = 1;
            change_penalty = ProPenalty(judging_team_id, judging_pro_id, submit_num - 1);
            team_item.solved += change_solved;
            team_item.penalty += change_penalty;
            team_sol.ac[judging_pro_id] = last_submit_time;
            pro_div.children()[0].innerText = submit_num;
            pro_div.children()[2].innerText = SolTime(last_submit_time);
        }
        delete team_sol.frozen[judging_pro_id];

        UpdateSolPenalty(judging_team_id, team_item.solved, team_item.penalty);

        pro_div.removeClass('g_pro_nn g_pro_pd g_pro_wa g_pro_ac').addClass(ac ? 'g_pro_ac' : 'g_pro_wa');
    }
    stack_judge.push({
        'team_id': judging_team_id,
        'pro_id': judging_pro_id,
        'change_solved': change_solved,
        'change_penalty': change_penalty,
        'pos_before': map_item[judging_team_id].gitem._top
    });
    if(judging_pro_id === null) {
        JudgeSort(true);
    } else if(flag_auto) {
        setTimeout(function(){JudgeSort(true);}, auto_speed);
    }
}
const AWARD_NAME = {
    'gold': '金奖',
    'silver': '银奖',
    'bronze': '铜奖'
};
function DisplayInfo(info_key, team_item, content=null) {
    if(content === null && (info_key in team_item) && !StrEmpty(team_item[info_key])) {
        $(`#award_${info_key}`).show().find(`.award_info_content`).text(team_item[info_key]);
    } else if(content !== null && content.toString().length > 0) {
        $(`#award_${info_key}`).show().find(`.award_info_content`).text(content);
    } else {
        $(`#award_${info_key}`).hide();
    }
}
function AwardModalSet(team_id, award) {
    function GetFbList(team_id) {
        let mf = flag_star_mode == STAR_RANK ? map_fb.global : map_fb.formal;
        ret = [];
        for(let i = 0; i < map_num2p.length; i ++) {
            if((map_num2p[i] in mf) && (team_id in mf[map_num2p[i]].teams)) {
                ret.push(String.fromCharCode('A'.charCodeAt(0) + i));
            }
        }
        return ret.join(', ');
    }
    $('#award_team_img').attr('award', award);
    $('#award_team_img').attr('src', TeamPhotoUri(team_id));
    $('#award_info').attr('award', award);
    $('#award_level').text(AWARD_NAME[award]).attr('award', award);
    $('#awrad_school_logo').show().find('img').attr('school', DomSantize(map_team[team_id].school)).attr('src', SchoolLogoUri(map_team[team_id].school));
    DisplayInfo('school', map_team[team_id]);
    DisplayInfo('name', map_team[team_id]);
    DisplayInfo('tmember', map_team[team_id]);
    DisplayInfo('coach', map_team[team_id]);
    DisplayInfo('rk', null, now_rank[team_id].rank);
    DisplayInfo('fb', null, GetFbList(team_id));
    DisplayInfo('solved', null, map_item[team_id].solved);
}
function AwardModalToggle(show=true) {
    award_modal_div.find('.modal-dialog').css('transition', `transform ${Math.min(auto_speed >> 1, DEFAULT_SPEED)}ms ease-out`);
    if(show) {
        award_modal_obj.show();
    } else {
        award_modal_obj.hide();
    }
}
function JudgeAward() {
    // 该队获奖
    flag_nowstep = 'award';
    if(now_judging_ith < 0) {
        return;
    }
    let award_modal_show_flag = false;
    if(real_rank_map[judging_team_id].rank != '*' && now_rank[judging_team_id].ith == real_rank_map[judging_team_id].ith && Object.keys(map_team_sol[judging_team_id].frozen).length == 0) {
        let award = null;
        if(real_rank_map[judging_team_id].rank <= rank_gold) {
            award = 'gold';
        } else if(real_rank_map[judging_team_id].rank <= rank_silver) {
            award = 'silver';
        } else if(real_rank_map[judging_team_id].rank <= rank_bronze) {
            award = 'bronze';
        }
        if(award != null) {
            AwardModalSet(judging_team_id, award);
            award_modal_show_flag = true;
            if(!flag_award_area) {
                flag_auto = false;  // 进入奖区暂停auto
                auto_speed = DEFAULT_SPEED;
            }
            flag_award_area = true;
            AwardModalToggle(true);
        }
    }
    if(!award_modal_show_flag) {
        JudgeConfirm();
    }
    else if(flag_auto) {
        setTimeout(function(){JudgeConfirm();}, Math.max(auto_speed, 256));
    }
}
function JudgeUndo() {
    if(stack_judge.length == 0) {
        return;
    }
    flag_auto = false;
    let judge_record = stack_judge[stack_judge.length - 1];
    judging_team_id = judge_record.team_id;
    DomJudgeConfirm(judge_record.team_id, judge_record.pro_id, true, judge_record.pos_before);
    if(judge_record.pro_id !== null) {
        let team_sol = map_team_sol[judge_record.team_id];
        let team_item = map_item[judge_record.team_id];
        let pro_div = team_item.dom.find(`.g_pro[problem_id="${judge_record.pro_id}"]`);
        let change_solved = 0;
        let change_penalty = 0;
        
        team_item.solved -= judge_record.change_solved;
        team_item.penalty -= judge_record.change_penalty;
        if(judge_record.pro_id in team_sol.ac) {
            delete team_sol.ac[judge_record.pro_id];
        }
        pro_div.children()[0].innerText = team_sol[judge_record.pro_id].length;
        pro_div.children()[2].innerText = SolTime(team_sol[judge_record.pro_id][team_sol[judge_record.pro_id].length - 1].in_date);
        team_sol.frozen[judge_record.pro_id] = true;

        UpdateSolPenalty(judge_record.team_id, team_item.solved, team_item.penalty)

        pro_div.removeClass('g_pro_nn g_pro_pd g_pro_wa g_pro_ac').addClass('g_pro_pd');
    }
    JudgeSort();
    // now_judging_ith = now_order.length;
    now_judging_ith = map_now_order[judge_record.team_id];
    judging_team_id_last = null;
    flag_nowstep = null;
    if(now_rank[judge_record.team_id].rank != '*' && now_rank[judge_record.team_id].rank >= rank_bronze) {
        flag_award_area = false;
    }
    stack_judge.pop();
}
function JudgeNextStep() {
    if(flag_nowstep == 'sort' || flag_nowstep == null) {
        JudgeConfirm();
    } else if(flag_nowstep == 'confirm') {
        JudgeDo();
    } else if(flag_nowstep == 'do') {
        JudgeSort(true);
    } else if(flag_nowstep == 'sort_award') {
        JudgeAward();
    } else if(flag_nowstep == 'award') {
        JudgeConfirm();
    }
}
function JudgeSort(judging_flag=false) {
    flag_nowstep = judging_flag ? 'sort' : null;
    let team_ith_before, team_ith_after;
    if(judging_flag) {
        team_ith_before = now_rank[judging_team_id].ith;
    }
    $('.g_team_judging_last').removeClass('g_team_judging_last');
    $('.g_team_judging').addClass('g_team_judging_last');
    $('.g_team_judging').removeClass('g_team_judging');
    grid.sort('solved:desc penalty team_id');
    if(judging_flag) {
        team_ith_after = now_rank[judging_team_id].ith;
        if(team_ith_before == team_ith_after && Object.keys(map_team_sol[judging_team_id].frozen).length == 0) {
            // 该team题目已经判完且无名次变动
            DomJudgeConfirm(judging_team_id, null);
            flag_nowstep = 'sort_award';
            JudgeAward();
        } else {
            JudgeConfirm();
        }
    }
    flag_judgingdo_step = false;
}
function InitData(re_query=false) {
    loading_div.show();
    try {
        if(re_query) {
            $.get('contest_data_ajax?cid=' + cid, function(ret) {
                if(ret.code == 1) {
                    cdata = ret.data;
                    ProcessData();
                    ProcessItem();
                } else {
                    alertify.error(ret.msg);
                }
                loading_div.hide();
            });
        } else {
            ProcessData();
            ProcessItem();
        }
    } catch (e) {
        console.error(e);
        loading_div.hide();
    }
}
award_modal_div.on('shown.bs.modal', function() {
    // 需在modal显示完毕后方可获取宽度
    FontNarrowAward();
});
$('.button_init_data').click(function(){
    alertify.confirm("确认", "确认初始化数据？", function() {
        InitData(true);
        $('.button_fullscreen').removeAttr('disabled');
    }, function(){});
});
$('.button_fullscreen').click(function(){ToggleFullScreen('rankroll_div')});
document.addEventListener("fullscreenchange", function () {
    if (!document.fullscreenElement) {
        rank_header_div.css({'position': 'relative'});
        flag_rolling = false;
        flag_auto = false;
        AwardModalToggle(false);
        JudgeUndo();    // 退出全屏时Undo消除重新进入的不确定性
    } else {
        rank_header_div.css({'position': 'fixed'});
        flag_rolling = true;
        if(stack_judge.length == 0) {
            rankroll_div.animate({
                scrollTop: rankroll_div.offset().top + rankroll_div[0].scrollHeight
            }, Math.min(now_order.length * 100, 5000));
        } else {
            JudgeConfirm();
        }
    }
});

$('.button_help').click(function(){
    alertify.alert("帮助", `首先点击“初始化”预处理，然后点击“启动”进入全屏模式<br/>
    W：加速<br/>
    S：减速<br/>
    R：恢复默认速度<br/>
    A：自动，再次按下取消自动（进入奖区时会暂停自动）<br/>
    N：手动前进一步<br/>
    U：撤回一步<br/>
    屏蔽了F5页面刷新以免误操作，可CTRL+R刷新页面`);
});
$('#with_star_team').change(function() {
    flag_star_mode = parseInt(this.value);
    SetAwardRank(false);
    GridFilter();
    flag_award_area = false
});
function KeyTimeout() {
    flag_keyvalid = false;
    setTimeout(function(){flag_keyvalid = true;}, auto_speed + 10);
}
function RollForward() {
    if(flag_keyvalid) {
        flag_auto = false;
        JudgeNextStep();
        KeyTimeout();
    }
}
function RollBackward() {
    if(flag_keyvalid) {
        flag_auto = false;
        JudgeUndo();
        KeyTimeout();
    }
}
rankroll_div.dblclick(function() {
    RollForward();
})
window.onkeydown = (event) => {
    if (!event || !event.isTrusted || !event.cancelable) {
        return;
    }
    const key = event.key;
    if (key === 'F5') {
        event.preventDefault();
        event.returnValue = false;
    }
    if(flag_rolling) {
        if(key === 'a' || key === 'A') {
            flag_auto = !flag_auto;
            if(flag_auto) {
                JudgeNextStep();
            }
        } else if(key === 'w' || key === 'W') {
            if(auto_speed > 32) {
                auto_speed >>= 1;
            }
        } else if(key === 's' || key === 'S') {
            if(auto_speed < 4096) {
                auto_speed <<= 1;
            }
        } else if(key === 'u' || key === 'U') {
            RollBackward();
        } else if(key === 'n' || key === 'N') {
            RollForward();
        } else if(key === 'r' || key === 'R') {
            // flag_auto = false;
            auto_speed = DEFAULT_SPEED;
        } else if(key == 'PageUp') {
            event.preventDefault();
            RollBackward();
        } else if(key == 'PageDown') {
            event.preventDefault();
            RollForward();
        }
    }
}
</script>