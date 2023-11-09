let min_solution_id = 0;
function SetCdata() {
    if(cdata?.solution?.length) {
        min_solution_id = cdata?.solution[cdata.solution.length - 1].solution_id;
        for(let i = 0; i < cdata.solution.length; i ++) {
            if(cdata.solution[i].result < 4 && cdata.solution[i].result >= 0) {
                min_solution_id = cdata.solution[i].solution_id - 1;
            }
        }
    } else {
        min_solution_id = 0;
    }
    // for debug
    // min_solution_id = 5000;
    // |||||||
    cdata.map_team_sol = map_team_sol;
    cdata.map_team = map_team;
    cdata.map_p2num = map_p2num;
    cdata.map_num2p = map_num2p;
    cdata.map_fb = map_fb;
    cdata.rank_gold = rank_gold;
    cdata.rank_silver = rank_silver;
    cdata.rank_bronze = rank_bronze;
    cdata.cnt_base = cnt_base;
    cdata.real_rank_list = real_rank_list;
    cdata.real_rank_map = real_rank_map;
}
function DataTimeLimit(cdata, timestamp_sec_limit) {
    console.log(cdata.solution[0].in_date);
    if(timestamp_sec_limit === null) {
        return;
    }
    cdata.solution = cdata.solution.filter(item => Str2Sec(item.in_date) < timestamp_sec_limit);
}
function DataLoadAll(callback_function, timestamp_sec_limit=null) {
    // 加载全局数据
    // timestamp_sec_limit: 用于调试的时间轴限制
    // define function SomeFunction(data) and then DataLoadAll(SomeFunction) to get data
    if(cid == null) {
        return;
    }
    let requests = [
        csg.get(`/cpcsys/contest/contest_data_ajax?cid=${cid}`)
    ];
    Promise.all(requests)
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(data => {
        let ret = data[0];
        if(ret.code == 1) {
            cdata = ret.data;
            DataTimeLimit(cdata, timestamp_sec_limit);
            ProcessData(cdata);
            SetAwardRank(true);
            SetCdata(cdata)
            callback_function(cdata)
        } else {
            console.error(ret.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error); 
    });
}
function JoinSolution(solution_main, solution_new) {
    if(!Array.isArray(solution_main) || !Array.isArray(solution_new)) {
        console.error(`data not array`, solution_main, solution_new);
        return;
    }
    let map_sol = {};
    for(let i = 0; i < solution_main.length; i ++) {
        map_sol[solution_main[i].solution_id] = i;
    }
    for(let i = 0; i < solution_new.length; i ++) {
        if(solution_new[i].solution_id in map_sol) {
            solution_main[map_sol[solution_new[i].solution_id]] = solution_new[i];
        } else {
            solution_main.push(solution_new[i]);
        }
    }
    // solution_main.sort((a, b) => a.solution_id - b.solution_id); // ProcessData() 里排
}
function DataSync(callback_function, timestamp_sec_limit=null) {
    // 加载增量数据
    // timestamp_sec_limit: 用于调试的时间轴限制
    if(cid == null) {
        return;
    }
    let requests = [
        csg.get(`/cpcsys/contest/contest_data_ajax?cid=${cid}&only_solution=1&min_solution_id=${min_solution_id}`)
    ];
    
    Promise.all(requests)
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(data => {
        let ret = data[0];
        if(ret.code == 1) {
            let data_solution_new = ret.data;
            console.log(data_solution_new)
            if(data_solution_new.length > 0) {
                JoinSolution(cdata.solution, data_solution_new);
            }
            ProcessData(cdata);
            SetAwardRank(true);
            SetCdata(cdata)
            
            callback_function(cdata)
        } else {
            console.error(ret.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error); 
    });

}