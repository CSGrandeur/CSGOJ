let top_team_num = param.topteam;
let map_school = {};
const STATUS_PRIORITY = {
    'g_pro_ac': 4,
    'g_pro_pd': 3,
    'g_pro_wa': 2,
    'g_pro_nn': 1
}
function GridFilter() {
    if(grid != null) {
        grid.filter(function(item) {
            return item.getElement().getAttribute('remove') != 1;
        });
        now_judging_ith = Object.keys(map_item).length;
        judging_team_id_last = null;
    }
}
function GridResetRank(currentOrder) {
    $('.g_rank').removeAttr('award');
    now_order = [];
    map_now_order = {};
    now_rank = {}; 
    let rk_real = 0, rk_show, last_item = null, show_i = 0;
    for(let i = 0; i < currentOrder.length; i ++) {
        let team_id = currentOrder[i]._element.getAttribute('team_id');
        now_order.push(team_id);
        map_now_order[team_id] = now_order.length - 1;
        rk_real ++;
        if(last_item == null || last_item.solved > map_school[team_id].item.solved || last_item.penalty < map_school[team_id].item.penalty) {
            rk_show = rk_real;
        }
        last_item = map_school[team_id].item;
        let now_rank_show = rk_show;
        now_rank[team_id] = {
            rank: now_rank_show,
            ith: show_i + 1
        }
        map_school[team_id].item.dom.find('.g_rank').text(now_rank_show);
        map_school[team_id].item.dom.attr('linetype', show_i & 1 ? 'odd' : 'even');
        show_i ++;
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
function SchoolTeamItemRes(team_id) {
    if(!(team_id in map_team)) {
        return null;
    }
    let solved = 0;
    let penalty = 0;
    let sol = map_team_sol[team_id];
    let pro_map = {};
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
        pro_map[map_num2p[i]] = {
            'submit_num': submit_num,
            'pro_idx': pro_idx,
            'last_submit': last_submit,
            'pro_status': pro_status
        }
    }
    return {
        'solved': solved,
        'penalty': penalty,
        'pro_map': pro_map
    }
}
function SchoolItem(school_str) {
    let sorted_school_team = Object.entries(map_school[school_str].school_team_map).sort((a, b) => {
        if (a[1].solved !== b[1].solved) {
            return b[1].solved - a[1].solved;
        }
        if (a[1].penalty !== b[1].penalty) {
            return a[1].penalty - b[1].penalty;
        }
        return a[0] == b[0] ? 0 : (a[0] < b[0] ? -1 : 1);
    });
    let item = {
        'solved': 0,
        'penalty': 0,
        'top_team': null,
        'team_num': 0,
        'pro_map': {},
        'dom': ''
    }
    let cnt = 0;
    for(let i = 0; i < sorted_school_team.length && cnt < top_team_num; i ++) {
        let sorted_school_team_item = sorted_school_team[i];
        if(flag_star_mode == STAR_RANK || map_team[sorted_school_team_item[0]].tkind != 2) {
            cnt ++;
            item.solved += sorted_school_team_item[1].solved;
            item.penalty += sorted_school_team_item[1].penalty;
            item.team_num ++;
            if(item.top_team === null) {
                item.top_team = sorted_school_team_item[0];
            }
            for(let key in sorted_school_team_item[1].pro_map) {
                let s_pro_status = sorted_school_team_item[1].pro_map[key].pro_status;
                if(!(key in item.pro_map)) {
                    item.pro_map[key] = {
                        'submit_num': 0,
                        'pro_idx': sorted_school_team_item[1].pro_map[key].pro_idx,
                        'last_submit': sorted_school_team_item[1].pro_map[key].last_submit,
                        'pro_status': s_pro_status
                    }
                }
                let item_now = item.pro_map[key]
                item_now.submit_num += sorted_school_team_item[1].pro_map[key].submit_num;
                if(sorted_school_team_item[1].pro_map[key].last_submit > item_now.last_submit) {
                    item_now.last_submit = sorted_school_team_item[1].pro_map[key].last_submit;
                }
                if(STATUS_PRIORITY[s_pro_status] > STATUS_PRIORITY[item_now.pro_status]) {
                    item_now.pro_status = s_pro_status;
                }
            }
        }            
    }
    pro_list_dom = ``;
    for(let i = 0; i < map_num2p.length; i ++) {
        let pro_pid = map_num2p[i];
        let pro_status = pro_pid in item.pro_map ? item.pro_map[pro_pid].pro_status : 'g_pro_nn';
        let submit_num = pro_pid in item.pro_map ? item.pro_map[pro_pid].submit_num : '';
        let last_submit = pro_pid in item.pro_map ? item.pro_map[pro_pid].last_submit : '';
        let pro_idx = pro_pid in item.pro_map ? item.pro_map[pro_pid].pro_idx : '';
        pro_list_dom += `
            <div class="g_pro ${pro_status}" problem_id="${pro_pid}">
                <span class="g_pro_lspan">${submit_num > 0 ? submit_num : ''}</span>
                <span class="g_pro_mspan">${pro_idx}</span>
                <span class="g_pro_rspan">${last_submit}</span>
            </div>
            `
    }
    item.dom = `<div class="item" team_id="${DomSantize(school_str)}" title="Top Team: ${item.top_team}">
        <div class="item-content" solved="${item.solved}" penalty="${item.penalty}" team_id="${DomSantize(school_str)}">
            <div class="g_td g_rank"></div>
            <div class="g_td g_logo"><img class="school_logo" school="${DomSantize(school_str)}" onerror="SchoolLogoUriOnError(this)"/></div>
            <div class="g_team_content">
                <div class="g_name">${DomSantize(school_str)} | Top Team: ${item.top_team} - ${map_team?.[item.top_team]?.name}</div>
                <div class="g_pro_group">
                    ${pro_list_dom}
                </div>
            </div>
            <div class="g_td g_solve">${item.solved}</div>
            <div class="g_td g_time">${item.penalty}</div>
        </div>
    </div>` 
    return item;
}

function ProcessItem() {
    // map_item = {};
    InitGrid();
    top_team_num = 'topteam' in cdata.contest ? cdata.contest.topteam : 1;
    $('#top_team_span').text(top_team_num);
    now_judging_ith = -1;
    judging_team_id_last = null;
    // map_school = {};
    // list_school = [];
    for(let team_id in map_team_sol) {
        let team_item = SchoolTeamItemRes(team_id);
        if(team_item != null) {
            let school_team_map;
            if(!(map_team[team_id].school in map_school)) {
                school_team_map = {};
                map_school[map_team[team_id].school] = {};
                map_school[map_team[team_id].school].school_team_map = school_team_map;
            } else {
                school_team_map = map_school[map_team[team_id].school].school_team_map;
            }       
            map_item[team_id] = team_item;
            school_team_map[team_id] = team_item;
        }
    }
    for(let school in map_school) {
        let school_item = SchoolItem(school);
        school_item.dom = $(school_item.dom);
        if('item' in map_school[school]) {
            for(let key in school_item) {
                if(key != 'dom') {
                    map_school[school].item[key] = school_item[key]
                }
            }
            if(school_item.top_team != null) {
                map_school[school].item.dom.html(school_item.dom.html());
            }
            map_school[school].gitem._sortData.solved = map_school[school].item.solved;
            map_school[school].gitem._sortData.penalty = map_school[school].item.penalty;
        } else {
            map_school[school].item = school_item;
            grid.add(school_item.dom[0]);
        }
    }
    let gitem_list = grid.getItems();
    for(let i = 0; i < gitem_list.length; i ++) {
        let school_str = gitem_list[i]._element.getAttribute('team_id');
        map_school[school_str].gitem = gitem_list[i];
        if(map_school[school_str].item.top_team == null) {
            map_school[school_str].gitem.getElement().setAttribute('remove', 1);
        } else {
            map_school[school_str].gitem.getElement().setAttribute('remove', 0);
        }
    }
    GridFilter();
    flag_award_area = false;
    FontNarrowRank();
    // JudgeSort();
    if(Object.keys(map_team).length == 1) {
        // 只有一个队时不会触发sort事件
        GridResetRank([Object.values(map_school)[0].gitem]);
    }
    SetFB();
    BatchProcessSchoolLogo();
    TryStopInterval();
}
function SetFB() {
    // let mf = map_fb.formal;
    // if(flag_star_mode === STAR_RANK) {
    //     mf = map_fb.global;
    // }
    // $('.g_pro_fb').removeClass('g_pro_fb');
    // for(let pro_id in mf) {
    //     for(let team_id in mf[pro_id].teams) {
    //         map_item[team_id].dom.find(`.g_pro[problem_id="${pro_id}"]`).addClass('g_pro_fb');            
    //     }
    // }
}
function JudgeSort() {
    grid.sort('solved:desc penalty school');
}

$(document).ready(function() {
    $('#with_star_team').change(function() {
        flag_star_mode = parseInt(this.value);
        ProcessItem();
    });
});