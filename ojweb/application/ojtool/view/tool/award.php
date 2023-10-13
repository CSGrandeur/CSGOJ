
<h1><span id="ctitle">Award Manager</span></h1>
<input type="hidden" id="page_info"
    OJ_MODE="{$OJ_MODE}"
>
<div class="input-group mb-3">
    <span class="input-group-text" >Cid列表</span>
    <input type="text" class="form-control" placeholder="半角逗号隔开 1001,1002..." id="input_cid_list">
    <button class="btn btn-outline-secondary" type="button" id="btn_award_update">Update</button>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" >一等奖比例 (%)</span>
    <input type="text" class="form-control input_award_ratio" id="input_gold_ratio" value=10>
    <span class="input-group-text" >二等奖比例 (%)</span>
    <input type="text" class="form-control input_award_ratio" id="input_silver_ratio" value=20>
    <span class="input-group-text" >三等奖比例 (%)</span>
    <input type="text" class="form-control input_award_ratio" id="input_bronze_ratio" value=30>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" >比赛名称</span>
    <input type="text" class="form-control" id="input_contest_title" value="程序设计竞赛">
    <span class="input-group-text" >副标题</span>
    <input type="text" class="form-control" id="input_contest_subtitle" value="组队赛">
    <span class="input-group-text" >比赛年月日</span>
    <input type="text" class="form-control" id="input_contest_day" value="2023-04-29">
    <span class="input-group-text" >证书前缀</span>
    <input type="text" class="form-control" id="input_contest_prefix" value="SZTU-ACM">
</div>

<div id="award_toobar">
    <div class="btn-group mr-2" role="group" aria-label="First group">
        <div class="input-group">
            <button type="button" class="btn btn-primary" id="btn_gen_awards">生成获奖证书</button>
        </div>
    </div>

    <div class="btn-group mr-2" role="group" aria-label="Second group">
        <div class="input-group" title="以表格第一行为标题上传csv文件，可仅包含部分标题： rank,award,nick,school,tmember,coach,tkind,solved,penalty,cid,user_id">
            <input class="form-control" type="file" id="input_upload_csv" accept=".csv">
            <label class="input-group-text" for="input_upload_csv">上传CSV排名</label>
        </div>
    </div>
    <div class="btn-group mr-2" role="group" aria-label="Third group">
        <div class="form-inline" role="form">
            <div class="form-check form-switch">
                <label class="form-check-label" for="switch_with_star_team">包含打星</label>
                <input class="form-check-input" type="checkbox" role="switch" id="switch_with_star_team">
            </div>
            <div class="form-check form-switch">
                <label class="form-check-label" for="switch_all_team_based" title="否则以过题队为基数">以总数为基数</label>
                <input class="form-check-input" type="checkbox" role="switch" id="switch_all_team_based">
            </div>
        </div>
        <div class="form-inline" role="form">
            <div class="form-check form-switch">
                <label class="form-check-label" for="switch_gsb" title="一二三还是金银铜">金银铜</label>
                <input class="form-check-input" type="checkbox" role="switch" id="switch_gsb">
            </div>
            <div class="form-check form-switch">
                <label class="form-check-label" for="switch_level" title="校级还是社团级">校级</label>
                <input class="form-check-input" type="checkbox" role="switch" id="switch_level">
            </div>
        </div>
    </div>
</div>


<div id="award_table_div">
    <table
            id="award_table"
            data-toggle="table"
            data-side-pagination="false"
            data-show-refresh="false"
            data-silent-sort="false"
            data-buttons-align="left"
            data-toolbar-align="right"
            data-sort-stable="true"
            data-show-export="true"
            data-toolbar="#award_toobar"
            data-export-types="['csv', 'excel', 'json', 'png']"
            data-export-options='{"fileName":"获奖名单"}'
            class="table-striped"
    >
        <thead>
        <tr>
            <th data-field="rank" data-align="center" data-valign="middle"  data-sortable="false" data-width="60">排名</th>
            <th data-field="award" data-align="center" data-valign="middle"  data-sortable="false" data-width="120" data-formatter="AwardFormatter">获奖</th>
            <th data-field="nick" data-align="left" data-valign="middle"  data-sortable="false" data-cell-style="CellStyleName">队名</th>
            <th data-field="school" data-align="left" data-valign="middle"  data-sortable="false" data-cell-style="schoolCellStyle">学校</th>
            <th data-field="tmember" data-align="left" data-valign="middle"  data-sortable="false" data-cell-style="memberCellStyle">选手</th>
            <th data-field="coach" data-align="left" data-valign="middle"  data-sortable="false" data-cell-style="coachCellStyle">教练</th>
            <th data-field="tkind" data-align="left" data-valign="middle"  data-sortable="false" data-width="10"  data-formatter="TkindAwardFormatter">类型</th>
            <th data-field="solved" data-align="center" data-valign="middle"  data-sortable="false" data-width="60">解题</th>
            <th data-field="penalty" data-align="center" data-valign="middle"  data-sortable="false" data-width="80">罚时</th>
            <th data-field="cid" data-align="left" data-valign="middle"  data-sortable="false" data-cell-style="coachCellStyle">CID</th>
            <th data-field="user_id" data-align="left" data-valign="middle"  data-sortable="false" data-width="80">ID</th>
        </tr>
        </thead>
    </table>
</div>



<script>
    let page_info = csg.getdom("#page_info");
    let sc = {
        OJ_MODE: page_info.getAttribute("OJ_MODE")
    };
    let input_cid_list = csg.getdom('#input_cid_list');
    let btn_award_update = csg.getdom('#btn_award_update');
    let cid_list = [];          // 要处理的cid列表
    let rank_dict = {};         // 多个contest的rank数据，key为cid
    let data_rank_all = []      // 对应表里的rank数据
    let data_show = [];         // 用于显示的数据
    let table_award_table = $('#award_table');  // 表对象
    let switch_with_star_team = csg.getdom('#switch_with_star_team');
    let flag_with_star_team = false;
    let switch_all_team_based = csg.getdom('#switch_all_team_based');
    let flag_all_team_based = false;
    let switch_gsb = csg.getdom('#switch_gsb');
    let flag_gsb = false;
    let switch_level = csg.getdom('#switch_level');
    let flag_level = false;
    
    let input_upload_csv = csg.getdom('#input_upload_csv');        // 上传rank用于自定义内容
    let btn_gen_awards = csg.getdom('#btn_gen_awards');            // 生成获奖证书

    let input_contest_title = csg.getdom('#input_contest_title');
    let input_contest_subtitle = csg.getdom('#input_contest_subtitle');
    let input_contest_day = csg.getdom('#input_contest_day');
    let input_contest_prefix = csg.getdom('#input_contest_prefix');
    
    let base_ratio_gold = 10;
    let base_ratio_silver = 20;
    let base_ratio_bronze = 30;

    
    csg.docready(function(){
        Anchor2Cid();
        SetCidInput();
        GetPageCookie();
        RefreshAward();
    });
    function GetPageCookie() {
        let contest_title = csg.GetStore("award_contest_title")
        if(contest_title !== null && contest_title.trim() != '') {
            input_contest_title.value = contest_title
        }
        let contest_subtitle = csg.GetStore("award_contest_subtitle")
        if(contest_subtitle !== null && contest_subtitle.trim() != '') {
            input_contest_subtitle.value = contest_subtitle
        }
        let contest_day = csg.GetStore("award_contest_day")
        if(contest_day !== null && contest_day.trim() != '') {
            input_contest_day.value = contest_day
        }
        let contest_prefix = csg.GetStore("award_contest_prefix")
        if(contest_prefix !== null && contest_prefix.trim() != '') {
            input_contest_prefix.value = contest_prefix
        }
    }
    function SetPageCookie() {
        csg.SetStore("award_contest_title", input_contest_title.value);
        csg.SetStore("award_contest_subtitle", input_contest_subtitle.value);
        csg.SetStore("award_contest_day", input_contest_day.value);
        csg.SetStore("award_contest_prefix", input_contest_prefix.value);
    }
    // ##################################################
    // Cid List
    // ##################################################
    function ValidateCidList() {
        let res_list = [];
        let cnt = 0;
        for(let i = 0; i < cid_list.length && cnt < 5; i ++) {
            cid_list[i] = parseInt(cid_list[i]);
            if(cid_list[i] > 0 && cid_list[i] < 1000000) {
                res_list.push(cid_list[i]);
                cnt ++;
            }
        }
        cid_list = res_list;
        award_table.setAttribute('data-export-options', '{"fileName":"获奖名单_' + cid_list.join('_') + '"}');
    }
    function Anchor2Cid() {
        // 由Anchor获取cid_list
        let clist_str = csg.GetAnchor("cid_list");
        cid_list = clist_str !== null ? clist_str.split(',') : [];
        ValidateCidList();
    }
    function Input2Cid() {
        // 由Input获取cid_list
        let clist_str = input_cid_list.value;
        cid_list = clist_str !== null ? clist_str.split(',') : [];
        ValidateCidList();
    }
    function SetCidAnchor(clist=null) {
        // 按Cid设置Anchor
        if(clist === null) {
            ValidateCidList();
            clist = cid_list;
        }
        csg.SetAnchor(clist.join(','), "cid_list");
    }
    function SetCidInput(clist=null) {
        // 按Cid设置Input
        if(clist === null) {
            ValidateCidList();
            clist = cid_list;
        }
        input_cid_list.value = clist.join(',');
    }
    // ##################################################
    // Events
    // ##################################################
    input_cid_list.addEventListener('input', () => {
        Input2Cid();
        SetCidAnchor();
    });
    input_cid_list.addEventListener('blur', () => {
        SetCidInput();
    });
    btn_award_update.addEventListener('click', () => {
        RefreshAward();
    });
    csg.getdom('.input_award_ratio').forEach((element) => {
        element.addEventListener('blur', () => {
            element.value = parseInt(element.value);
            if(element.value < 0) element.value = 0;
            if(element.value > 100) element.value = 100;
            switch(element.id) {
                case "input_gold_ratio":
                    base_ratio_gold = element.value;
                    break;
                case "input_silver_ratio":
                    base_ratio_silver = element.value;
                    break;
                case "input_bronze_ratio":
                    base_ratio_bronze = element.value;
                    break;
            }
            RefreshDataShow();
        });
    });
    switch_with_star_team.addEventListener('change', (event) => {
        flag_with_star_team = event.target.checked;
        RefreshDataShow();
    });
    switch_all_team_based.addEventListener('change', () => {
        flag_all_team_based = event.target.checked;
        RefreshDataShow();
    });
    switch_gsb.addEventListener('change', () => {
        flag_gsb = event.target.checked;
        RefreshDataShow();
    });
    switch_level.addEventListener('change', () => {
        // 校级和社团级，证书的落款不同
        flag_level = event.target.checked;
    });
    btn_gen_awards.addEventListener('click', () => {
        GenAwardCert(); // 生成获奖证书
    });
    input_contest_title.addEventListener('blur', () => {
        SetPageCookie();
    });
    input_contest_subtitle.addEventListener('blur', () => {
        SetPageCookie();
    });
    input_contest_day.addEventListener('blur', () => {
        let day = parseInt(input_contest_day.value.replace(/-/g, ''));
        input_contest_day.value = Math.floor(day / 10000 + 1e-6).toString().padStart(4, '0') + "-" + Math.floor(day / 100 % 100 + 1e-6).toString().padStart(2, '0') + '-' + Math.floor(day % 100 + 1e-6).toString().padStart(2, '0');
        SetPageCookie();
    });
    // ##################################################
    // Rank Process
    // ##################################################
    function AwardRatioProcess() {
        ratio_gold = base_ratio_gold / 100.0 - 0.0000001;
        ratio_silver = ratio_gold + base_ratio_silver / 100.0;
        ratio_bronze = ratio_silver + base_ratio_bronze / 100.0;
    }
    async function GetMultipleRank(cid_list) {
        // 请求并等待数据返回
        const promises = cid_list.map(cid => csgn.async_get("/" + (sc.OJ_MODE == "online" ? "csgoj" : sc.OJ_MODE) + "/contest/ranklist_ajax", {"cid": cid}));
        const response_list = await Promise.all(promises);
        return response_list;
    }

    async function RefreshAward() {
        // 请求数据、更新获奖名单
        Input2Cid();
        rank_dict = {};
        let valid_cnt = 0;
        ret_list = await GetMultipleRank(cid_list);
        for(let i = 0; i < ret_list.length; i ++) {
            if(Array.isArray(ret_list[i])) {
                rank_dict[cid_list[i]] = ret_list[i];
            } else {
                console.warn("result of " + cid_list[i] + " is not array");
            }
        }
        data_rank_all = JointRank(rank_dict);
        RefreshDataShow();
    }
    function RankCmp(a, b) {
        if(a['solved'] > b['solved']) return -1;
        if(a['solved'] < b['solved']) return 1;
        if(a['penalty'] < b['penalty']) return -1;
        if(a['penalty'] > b['penalty']) return 1;
        return 0;
    }
    function JointRank(rank_dict) {
        // 合并rank
        rank = [];
        for(const key in rank_dict) {
            for(let i = 0; i < rank_dict[key].length; i ++) {
                if(rank_dict[key][i]['user_id'].includes("<a")) {
                    rank_dict[key][i]['user_id'] = /<a.*>(.+?)<\/a>/.exec(rank_dict[key][i]['user_id'])[1];
                }
                rank.push({
                    "coach": rank_dict[key][i]['coach'],
                    "nick": rank_dict[key][i]['nick'],
                    "penalty": rank_dict[key][i]['penalty'],
                    "rank": rank_dict[key][i]['rank'],
                    "school": rank_dict[key][i]['school'],
                    "solved": parseInt(rank_dict[key][i]['solved']),
                    "tkind": rank_dict[key][i]['tkind'],
                    "tmember": rank_dict[key][i]['tmember'],
                    "user_id": rank_dict[key][i]['user_id'],
                    "cid": key
                });
            }
        }
        rank.sort(RankCmp);
        return rank;
    }
    function RefreshDataShow() {
        // 基于处理好的数据，按选项更新表格
        data_show = [];
        let rk = 0;
        let cnt_base = 0;
        let last_solved = -1, last_penalty = "";
        for(let i = 0; i < data_rank_all.length; i ++) {
            if((data_rank_all[i]['tkind'] < 2 || flag_with_star_team)) {   // 是否包含打星队
                let tmp = data_rank_all[i]
                if(tmp['solved'] != last_solved || tmp['penalty'] != last_penalty) {
                    rk ++;
                    last_solved = tmp['solved'];
                    last_penalty = tmp['penalty'];
                }
                tmp['rank'] = rk; 
                if(data_rank_all[i]['solved'] > 0 || flag_all_team_based) { // 基数是否包含未过题队
                    cnt_base ++;
                }
                data_show.push(data_rank_all[i]);
            }
        }
        AwardRatioProcess();
        let rank_gold = Math.ceil(cnt_base * ratio_gold);
        let rank_silver = Math.ceil(cnt_base * ratio_silver);
        let rank_bronze = Math.ceil(cnt_base * ratio_bronze);
        for(let i = 0; i < data_show.length; i ++) {
            if(data_show[i]['solved'] <= 0) {
                continue;
            }
            if(data_show[i]['rank'] <= rank_gold) {
                data_show[i]['award'] = flag_gsb ? 21 : 1;
            }
            else if(data_show[i]['rank'] <= rank_silver) {
                data_show[i]['award'] = flag_gsb ? 22 : 2;
            }
            else if(data_show[i]['rank'] <= rank_bronze) {
                data_show[i]['award'] = flag_gsb ? 23 : 3;
            }
            else {
                data_show[i]['award'] = 99
            }
        }
        table_award_table.bootstrapTable('load', data_show);
    }
    // ##################################################
    // Upload Rank
    // ##################################################
    let header_reverse_dict = {
        "排名":     "rank",
        "获奖":     "award",
        "队名":     "nick",
        "学校":     "school",
        "选手":     "tmember",
        "教练":     "coach",
        "类型":     "tkind",
        "解题":     "solved",
        "罚时":     "penalty",
        "CID":      "cid",
        "ID":       "user_id",
    }
    let header_dict = {
        "rank":         "排名",
        "award":        "获奖",
        "nick":         "队名",
        "school":       "学校",
        "tmember":      "选手",
        "coach":        "教练",
        "tkind":        "类型",
        "solved":       "解题",
        "penalty":      "罚时",
        "cid":          "CID",
        "user_id":      "ID",
    }
    let award_type_dict = {
        1: "一等奖",
        2: "二等奖",
        3: "三等奖",
        4: "最佳女生奖",
        5: "最佳女队奖",
        6: "顽强拼搏奖",
        11: "金奖",
        12: "银奖",
        13: "铜奖",
        21: "金（Gold）",
        22: "银（Silver）",
        23: "铜（Bronze）",
        99: "-"
    };
    let award_reverse_dict = {
        "一等奖": 1,
        "一等": 1,
        "金奖": 1,
        "Gold": 1,
        "金（Gold）": 1,
        "二等奖": 2,
        "二等": 2,
        "银奖": 2,
        "Silver": 2,
        "银（Silver）": 2,
        "三等奖": 3,
        "三等": 3,
        "铜奖": 3,
        "Bronze": 3,
        "铜（Bronze）": 3,
        "最佳女生奖": 4,
        "最佳女生": 4,
        "最佳女队奖": 5,
        "最佳女队": 5,
        "顽强拼搏奖": 6,
        "顽强拼搏": 6,
        11: 1,
        21: 1,
        12: 2,
        22: 2,
        13: 3,
        23: 3
    }
    input_upload_csv.addEventListener('change', () => {
        // 读取csv内容设置data_rank_all
        const file = input_upload_csv.files[0];
        const reader = new FileReader();
        reader.readAsText(file, 'utf-8');
        reader.onload = function(event) {
            const csv_data = event.target.result;
            if(csv_data.includes("�")) {
                alert("请转码为UTF-8");
                return;
            }
            let item_list = csv_data.split("\n");
            if(item_list.length <= 0) {
                return;
            }
            let table_header = item_list[0].trim().split(',');
            let header_list = [];
            for(let i = 0; i < table_header.length; i ++) {
                if(table_header[i] in header_reverse_dict) {
                    header_list.push(header_reverse_dict[table_header[i]]);
                } else if(table_header[i] in header_dict) {
                    header_list.push(table_header[i])
                }
            }
            let j = 1;
            if(header_list.length == 0) {
                // 如果没有合法标题的header，则认为是个无header csv文件
                header_list = Object.keys(header_dict);
                j = 0;
            }
            data_rank_all = []
            for(; j < item_list.length; j ++) {
                let line_info = item_list[j].trim().split(',');
                let line_item = {}
                for(let k = 0; k < line_info.length && k < header_list.length; k ++) {
                    if(header_list[k] == 'award') {
                        // 将文字奖项转为编号
                        if(line_info[k] in award_reverse_dict) {
                            line_info[k] = award_reverse_dict[line_info[k]];
                        } else if(!(line_info[k] in award_type_dict)) {
                            line_info[k] = 99;
                        }
                    }
                    line_item[header_list[k]] = line_info[k];
                }
                data_rank_all.push(line_item);
            }
            data_show = data_rank_all;
            table_award_table.bootstrapTable('load', data_show);
        }
    });
    function AwardCode(idx, team_idx=1) {
        return input_contest_prefix.value + "-" + input_contest_day.value.replace(/-/g, '-') + "-" + idx.toString().padStart(3, '0') + "-" + team_idx.toString().padStart(2, '0');
    }
    function OneAward(info, baseurl, idx, team_idx=1) {
        let institution = ""
        let serial = AwardCode(idx, team_idx);
        let serial_dom = `
            <span class="award_text award_serial">编号：` + serial + `</span>
        `
        if(flag_level){
            institution = `
            <span class="line line_college"></span>
            <span class="award_text award_college">共青团深圳技术大学委员会</span>
            <span class="line line_committee"></span>
            <span class="award_text award_committee">大数据与互联网学院</span>
            `
        } else {
            institution = `
            <span class="line line_association"></span>
            <span class="award_text award_association">深圳技术大学ACM爱好者协会</span>
            `
        }
        if(!('tmember' in info)) {
            info['tmember'] = '';
        }
        if(!('nick' in info)) {
            info['nick'] = '';
        }
        content = `
        <div class="page-break">
            <span class="award_text award_title">获奖证书</span>
            <span class="award_text award_grant">授予</span>
            <span class="award_text award_nick">` + info['nick'] + `</span>
            <span class="award_text award_tmember">` + info['tmember'] + `</span>
            <span class="award_text award_level">` + award_type_dict[info['award']] + `</span>
            <span class="award_text award_contest_title">` + input_contest_title.value + `</span>
            <span class="award_text award_contest_subtitle">` + input_contest_subtitle.value + input_contest_day.value + `</span>
            
            <span class="line line_coach"></span>
            <span class="award_text award_coach_sign">深圳技术大学ACM集训队主教练</span>
            ` + institution + serial_dom + `
            <img class="award_school_logo" src="` +  baseurl + `__STATIC__/ojtool/image/sztu_logo.svg">
            <img class="award_sztuacm_logo" src="` +  baseurl + `__STATIC__/ojtool/image/sztuacm_logo.svg">
            <img class="award_icpc_logo" src="` +  baseurl + `__STATIC__/ojtool/image/icpc_logo.svg">
        </div>
        `
        return content;
    }
    function PdfBookMark(filename_list) {
        
        let bookmark = `<\?xml version="1.0" encoding="UTF-8"\?>
        <BOOKMARKS>\n`;
        for(let page = 0; page < filename_list.length; page ++) {
            bookmark += `<ITEM NAME="` + filename_list[page] + `" PAGE="` + (page + 1) + `" FITETYPE="XYZ" VIEWRECT="Left=0.000000;Top=0" ZOOM="1.000000" INDENT="0"/>\n`
        }
        bookmark += `</BOOKMARKS>`
        return bookmark;
    }
    function GenAwardCert() {
        let baseurl = window.location.protocol + '//' + window.location.host;
        
        let award_page_list = [];
        let filename_list = [];
        let file_idx = 1;
        for(let i = 0; i < data_show.length; i ++) {
            if(!('tmember' in data_show[i])) {
                data_show[i]['tmember'] = '';
            }
            if(data_show[i]['award'] == 99) {
                break;
            }
            if(data_show[i]['tmember'].includes('、')) {
                let members = data_show[i]['tmember'].split('、');
                for(let j = 0; j < members.length; j ++) {
                    filename_list.push(AwardCode(i + 1, j + 1) + '_' + members[j] + '_' + award_type_dict[data_show[i]['award']] + '.pdf');
                    let tmp_str = members[j];
                    for(let k = 1; k < members.length; k ++) {
                        tmp_str = tmp_str + "、" + members[(j + k) % members.length];
                    }
                    data_show[i]['tmember'] = tmp_str;
                    award_page_list.push(OneAward(data_show[i], baseurl, i + 1, j + 1));
                }
            } else {
                filename_list.push(AwardCode(i + 1) + '_' + data_show[i]['tmember'] + '_' + data_show[i]['nick'] + '_' + award_type_dict[data_show[i]['award']] + '.pdf');
                award_page_list.push(OneAward(data_show[i], baseurl, i + 1));
            }
        }
        let style =   `<style type='text/css'>
                        body {
                            height: 220mm;
                            width: 297mm;
                            margin: 0;
                            padding-bottom: 20px;
                        }
                        @page {
                            size: B4 landscape;
                            margin: 0;
                        }
                        .page-break {
                            position: relative;
                            width: 100%;
                            height: 100%;
                            margin: 0;
                            padding: 0;
                            page-break-after: always;
                        }
                        .award_text {
                            font-family: "Open Sans", "Lato", "Helvetica Neue", "等线", "Arial";
                            font-weight: bolder;
                        }
                        .award_serial {
                            position: absolute;
                            top: 60px;
                            left: 90%;
                            transform: translate(-25%);
                            font-size: 16;
                            color: #4A82C3;
                            white-space: nowrap;
                        }
                        .award_title {
                            position: absolute;
                            top: 100px;
                            left: 50%;
                            transform: translate(-25%);
                            font-size: 80;
                            letter-spacing: 40px;
                            white-space: nowrap;
                        }
                        .award_grant {
                            position: absolute;
                            top: 300px;
                            left: 85%;
                            transform: translate(-40%);
                            text-align: center;
                            font-size: 24;
                            letter-spacing: 24px;
                            white-space: nowrap;
                        }
                        .award_nick {
                            position: absolute;
                            top: 380px;
                            left: 85%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 36;
                            white-space: nowrap;
                        }
                        .award_tmember {
                            position: absolute;
                            top: 450px;
                            left: 85%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 36;
                            white-space: nowrap;
                        }
                        .award_level {
                            position: absolute;
                            top: 520px;
                            left: 85%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 64;
                            white-space: nowrap;
                        }
                        .award_contest_title {
                            position: absolute;
                            top: 600px;
                            left: 85%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 36;
                            white-space: nowrap;
                        }
                        .award_contest_subtitle {
                            position: absolute;
                            top: 650px;
                            left: 85%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 24;
                            white-space: nowrap;
                        }
                        .award_coach_sign {
                            position: absolute;
                            top: 855px;
                            left: 90%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 16;
                            white-space: nowrap;
                            color: #4A82C3;
                        }
                        .award_committee, .award_association {
                            position: absolute;
                            top: 855px;
                            left: 60%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 16;
                            white-space: nowrap;
                            color: #4A82C3;
                        }
                        .award_college {
                            position: absolute;
                            top: 855px;
                            left: 30%;
                            transform: translate(-50%);
                            text-align: center;
                            font-size: 16;
                            white-space: nowrap;
                            color: #4A82C3;
                        }

                        .award_school_logo {
                            position: absolute;
                            top: 100px;
                            left: 25%;
                            transform: translate(-50%);
                            width: 100px;
                        }
                        .award_sztuacm_logo {
                            position: absolute;
                            top: 90px;
                            left: 50%;
                            transform: translate(400px);
                            width: 130px;
                        }
                        .award_icpc_logo {
                            position: absolute;
                            top: 330px;
                            left: 35%;
                            transform: translate(-50%);
                            width: 400px;
                        }
                        .line {
                            width: 280px;
                            height: 2px;
                            border-top: 2px solid #4A82C3;
                        }
                        .line_coach {
                            position: absolute;
                            top: 840px;
                            left: 90%;
                            transform: translate(-50%);
                        }
                        .line_committee, .line_association {
                            position: absolute;
                            top: 840px;
                            left: 60%;
                            transform: translate(-50%);
                        }
                        .line_college {
                            position: absolute;
                            top: 840px;
                            left: 30%;
                            transform: translate(-50%);
                        }
                    </style>`
        let html = `<html>
                <head lang="en">
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
                <meta name="renderer" content="webkit" />
                <body>` + 
                award_page_list.join("") + 
                "</body>" + 
                style + "</html>";
        
        let blob = new Blob([html], {type: "text/html"});
        let url = URL.createObjectURL(blob);
        window.open(url, "_blank");
        // 生成文件列表并执行下载
        const aLink = document.createElement('a');
        aLink.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(PdfBookMark(filename_list)));
        aLink.setAttribute('download', "证书文件名_" + input_contest_title.value + ".xml")
        aLink.style.display = 'none';
        document.body.appendChild(aLink);
        aLink.click();
        document.body.removeChild(aLink);
    }
    // ##################################################
    // Formatters
    // ##################################################
    function AwardFormatter(value, row, index) {
        return award_type_dict[value];
    }
    function TkindAwardFormatter(value, row, index)  {
        let v = value === null ? 0 : value;
        if(v == 1) {
            return "女队(girs)";
        } else if(v == 2) {
            return "打星(star)";
        }
        return "常规(normal)";
    }
</script>