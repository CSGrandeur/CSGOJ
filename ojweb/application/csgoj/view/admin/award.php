<div class="page-header" style="display:flex;">
    <h1>Award</h1>
    <div style="margin-left: 10px;">
        <article id="teamgen_help_div" class="alert alert-info">
            <p>当正式参赛女队（3名队员皆为女生）数目大于等于3时，可以设置最佳女队奖，排名最高且获得铜奖或以上奖项的正式参赛女队获得。</p>
            <p>可以设置顽强拼搏奖，未获得金奖、银奖或铜奖的正式队伍中最晚解出题目的1或2支参赛队获得顽强拼搏奖。</p>
        </article>
    </div>
</div>
<div id="award_toobar">
    <div class="form-inline" role="form">
        <div class="form-group">
            &nbsp;&nbsp;
            <label for="one_two_three" title="显示为一二三等奖，而不是金银铜">一二三: </label>
            <input type="checkbox" id="switch_one_two_three" data-size="small" name="one_two_three" >
            &nbsp;&nbsp;
            <label for="with_star_team">包含打星: </label>
            <input type="checkbox" id="switch_with_star_team" data-size="small" name="with_star_team" >
            &nbsp;&nbsp;
            <label for="all_team_based" title="否则以过题队为基数">以总数为基数: </label>
            <input type="checkbox" id="switch_all_team_based" data-size="small" name="all_team_based" >
            &nbsp;&nbsp;
        </div>
    </div>
</div>
<div id="award_table_div">
    <table
            id="award_table"
            data-toggle="table"
            data-side-pagination="client"
            data-striped="true"
            data-show-refresh="false"
            data-silent-sort="false"
            data-buttons-align="left"
            data-toolbar-align="right"
            data-sort-stable="true"
            data-show-export="true"
            data-toolbar="#award_toobar"
            data-export-types="['excel', 'csv', 'json', 'png']"
            data-export-options='{"fileName":"{$contest[\"contest_id\"]}-{$contest[\"title\"]}-获奖名单"}'
    >
        <thead>
        <tr>
            <th data-field="rank"    data-align="center" data-valign="middle"  data-sortable="false" data-width="60">排名</th>
            <th data-field="award"   data-align="center" data-valign="middle"  data-sortable="false" data-width="100">获奖</th>
            <th data-field="nick"    data-align="left"   data-valign="middle"  data-sortable="false" data-cell-style="CellStyleName">队名</th>
            <th data-field="tkind"   data-align="left"   data-valign="middle"  data-sortable="false" data-width="50"  data-formatter="TkindAwardFormatter">类型</th>
            <th data-field="solved"  data-align="center" data-valign="middle"  data-sortable="false" data-width="60">解题</th>
            <th data-field="penalty" data-align="center" data-valign="middle"  data-sortable="false" data-width="80">罚时</th>
            <th data-field="school"  data-align="left"   data-valign="middle"  data-sortable="false" data-cell-style="schoolCellStyle">学校</th>
            <th data-field="tmember" data-align="left"   data-valign="middle"  data-sortable="false" data-cell-style="memberCellStyle">选手</th>
            <th data-field="coach"   data-align="left"   data-valign="middle"  data-sortable="false" data-width="80" data-cell-style="coachCellStyle">教练</th>
            <th data-field="user_id" data-align="left"   data-valign="middle"  data-sortable="false" data-width="80">ID</th>
        </tr>
        </thead>
    </table>
</div>


<input type="hidden" id="award_ratio"
gold="<?php echo isset($ratio_gold) ? $ratio_gold : 10;?>"
silver="<?php echo isset($ratio_silver) ? $ratio_silver : 15;?>"
bronze="<?php echo isset($ratio_bronze) ? $ratio_bronze : 20;?>"
module="{$module}"
cid="{$contest['contest_id']}"
/>

<script>
    let table_award_table = $('#award_table');
    let page_info = $('#award_ratio');
    let switch_with_star_team = $('#switch_with_star_team');
    let switch_all_team_based = $('#switch_all_team_based');
    let switch_one_two_three = $('#switch_one_two_three');
    let flag_with_star_team = false;
    let flag_all_team_based = false;
    let flag_one_two_three = false;
    let data_table = [];
    let data_show = [];
    let page_module = page_info.attr('module');
    let page_cid = page_info.attr('cid');

    let ratio_gold = parseInt(page_info.attr("gold"));
    let ratio_silver = parseInt(page_info.attr("silver"));
    let ratio_bronze = parseInt(page_info.attr("bronze"));
    if(ratio_gold < 100) {
        ratio_gold = ratio_gold / 100.0 - 0.0000001;
    }
    if(ratio_silver < 100) {
        ratio_silver = ratio_silver / 100.0;
    }
    if(ratio_bronze < 100) {
        ratio_bronze = ratio_bronze / 100.0;
    }

    let map_data_show = [];
    let ac_order = [];
    let team_struggle = null, team_best_girl = null;
    let rank_gold;
    let rank_silver;
    let rank_bronze;
    
    $(document).ready(function() {
        switch_with_star_team.bootstrapSwitch();
        switch_all_team_based.bootstrapSwitch();
        switch_one_two_three.bootstrapSwitch();
        $.get(
            
            '/' + page_module + '/contest/ranklist_ajax',{
                'cid': page_cid,
            },
            function(ret) {
                for(let i = 0; i < ret.length; i ++) {
                    if(ret[i]['user_id'].includes("<a")) {
                        ret[i]['user_id'] = /<a.*>(.+?)<\/a>/.exec(ret[i]['user_id'])[1];
                    }
                    data_table.push({
                        "coach": ret[i]['coach'],
                        "nick": ret[i]['nick'],
                        "penalty": ret[i]['penalty'],
                        "rank": ret[i]['rank'],
                        "school": ret[i]['school'],
                        "solved": parseInt(ret[i]['solved']),
                        "tkind": ret[i]['tkind'],
                        "tmember": ret[i]['tmember'],
                        "user_id": ret[i]['user_id']
                    });
                    for(let pro in ret[i]) {
                        if(/^[A-Z]$/.test(pro) && ret[i][pro]?.ac != null) {
                            ac_order.push({
                                'ac': ret[i][pro].ac,
                                'user_id': ret[i].user_id
                            })
                        }
                    }
                }
                ac_order.sort((a, b) => {
                    return a.ac == b.ac ? 0 : (a.ac < b.ac ? 1 : -1);
                })
                refresh_data_show();
            }
        );
    });
    function GetTeamStruggle() {
        team_struggle = null;
        for(let i = 0; i < ac_order.length; i ++) {
            if(ac_order[i].user_id in map_data_show && map_data_show[ac_order[i].user_id].rank > rank_bronze) {
                team_struggle = ac_order[i].user_id;
                break;
            }
        }
        return team_struggle;
    }
    function refresh_data_show() {
        // https://ccpc.io/rules/45
        // 3) 当正式参赛女队（3名队员皆为女生）数目大于等于3时，可以设置最佳女队奖，排名最高且获得铜奖或以上奖项的正式参赛女队获得最佳女队奖，颁发奖牌和获奖证书。
        // 4) 可以设置顽强拼搏奖，未获得金奖、银奖或铜奖的正式队伍中最晚解出题目的1或2支参赛队获得顽强拼搏奖，颁发奖牌。
        data_show = [];
        let rk_real = 0, rk_show = 0;
        let cnt_base = 0;
        let last_solved = -1, last_penalty = "";
        map_data_show = {};
        team_best_girl = null;
        let team_girl_num = 0;
        for(let i = 0; i < data_table.length; i ++) {
            if((data_table[i]['tkind'] < 2 || flag_with_star_team)) {   // 是否包含打星队
                let tmp = data_table[i]
                rk_real ++;
                if(tmp['solved'] != last_solved || tmp['penalty'] != last_penalty) {
                    rk_show = rk_real;
                    last_solved = tmp['solved'];
                    last_penalty = tmp['penalty'];
                }
                tmp['rank'] = rk_show; 
                if(data_table[i]['solved'] > 0 || flag_all_team_based) { // 基数是否包含未过题队
                    cnt_base ++;
                }
                data_show.push(tmp);
                if(tmp.tkind == 1) {
                    team_girl_num ++;
                }
                map_data_show[tmp.user_id] = tmp;
            }
        }
        rank_gold = ratio_gold >= 100 ? ratio_gold - 100 : Math.ceil(cnt_base * ratio_gold);
        let tmp_ratio_gold = ratio_gold >= 100 ? rank_gold / cnt_base : ratio_gold;
        rank_silver = ratio_silver >= 100 ? rank_gold + ratio_silver - 100 : Math.ceil(cnt_base * (tmp_ratio_gold + ratio_silver));
        let tmp_ratio_silver = ratio_silver >= 100 ? rank_silver / cnt_base : tmp_ratio_gold + ratio_silver;
        rank_bronze = ratio_bronze >= 100 ? rank_silver + ratio_bronze - 100 : Math.ceil(cnt_base * (tmp_ratio_silver + ratio_bronze));
        for(let i = 0; i < data_show.length; i ++) {
            if(data_show[i]['solved'] <= 0) {
                continue;
            }
            if(data_show[i]['rank'] <= rank_gold) {
                data_show[i]['award'] = flag_one_two_three ? "一等奖" : "金奖";
            } else if(data_show[i]['rank'] <= rank_silver) {
                data_show[i]['award'] = flag_one_two_three ? "二等奖" : "银奖";
            } else if(data_show[i]['rank'] <= rank_bronze) {
                data_show[i]['award'] = flag_one_two_three ? "三等奖" : "铜奖";
            } else {
                data_show[i]['award'] = "-"
            }
            if(team_girl_num >= 3 && team_best_girl === null && data_show[i].tkind == 1 && data_show[i].rank <= rank_bronze) {
                team_best_girl = data_show[i].user_id;
                data_show[i].award += typeof(data_show[i].tmember == 'string') && 
                    (data_show[i].tmember.includes("、") || 
                    data_show[i].tmember.includes(",") || 
                    data_show[i].tmember.includes("，")) ? '&最佳女队奖' : '&最佳女生奖';
            }
        }
        GetTeamStruggle();
        if(team_struggle !== null) {
            map_data_show[team_struggle].award = '顽强拼搏奖';
        }
        table_award_table.bootstrapTable('load', data_show);
    }
    switch_with_star_team.on('switchChange.bootstrapSwitch', function(event, state) {
        if(state == true) {
            flag_with_star_team = true;
        }
        else{
            flag_with_star_team = false;
        }
        refresh_data_show();
    });
    switch_all_team_based.on('switchChange.bootstrapSwitch', function(event, state) {
        if(state == true) {
            flag_all_team_based = true;
        }
        else{
            flag_all_team_based = false;
        }
        refresh_data_show();
    });
    switch_one_two_three.on('switchChange.bootstrapSwitch', function(event, state) {
        if(state == true) {
            flag_one_two_three = true;
        }
        else{
            flag_one_two_three = false;
        }
        refresh_data_show();
    });
    function TkindAwardFormatter(value, row, index)  {
        let v = value === null ? 0 : value;
        if(v == 1) {
            return "女队";
        } else if(v == 2) {
            return "打星";
        }
        return "常规";
    }
</script>