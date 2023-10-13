<h1>监考信息投屏设置</h1>
<div style="max-width: 800px;">
    <div class="mb-3">
        <label for="km" class="form-label">科目</label>
        <input type="email" class="form-control tp_cls_input" id="km">
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label" id="time_db_click_reset">开始时间（请仔细检查，双击本行文字重置）</label>
        <input class="form-control tp_cls_input" id="start_time"></input>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">结束时间（请仔细检查）</label>
        <input class="form-control tp_cls_input" id="end_time"></input>
    </div>
    <div class="mb-3 form-check form-switch form-check">
        <input class="form-check-input tp_cls_input" type="checkbox" id="show_date">
        <label class="form-check-label" for="show_date">显示日期</label>
    </div>
    <div class="mb-3">
        <label for="xx" class="form-label">重要信息（如IP等）</label>
        <input class="form-control tp_cls_input" id="xx"></input>
    </div>
    <div class="mb-3">
        <label for="np" class="form-label">每页人数（只一页可不填），英文逗号隔开</label>
        <input class="form-control tp_cls_input" id="np" placeholder="15,30..."></input>
    </div>
    <div class="mb-3">
        <label for="cn" class="form-label">各列人数（面向讲台从右到左），英文逗号隔开</label>
        <input class="form-control tp_cls_input" id="cn" placeholder="7,8,8,7,7..."></input>
    </div>
    <div class="mb-3">
        <label for="pt" class="form-label">教室格局</label>
        <select class="form-select tp_cls_input"  id="pt" aria-label="open or close">
            <option selected    value="0">窗-讲台-门</option>
            <option             value="1">门-讲台-门</option>
            <option             value="2">门-讲台-窗</option>
            <option             value="3">窗-讲台-窗</option>
            <option             value="4">墙-讲台-门</option>
            <option             value="5">墙-讲台-窗</option>
            <option             value="6">门-讲台-墙</option>
            <option             value="7">窗-讲台-墙</option>
            <option             value="8">[空]-讲台-[空]</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="nf" class="form-label">通知信息（支持markdown、mathjax数学公式）</label>
        <textarea class="form-control tp_cls_input" id="nf" rows="6"></textarea>
    </div>
    <button type="button" class="btn btn-primary" id="tp_btn">保存</button>
    <a href="time_page_show" class="btn btn-success" target="_blank">打开信息页</a>
</div>
<br/>
<div>一些说明：信息页F11全屏，点击座位表调整显示位置，滚轮点击右侧座位表可变换90°</div>

<script>
let info_map = {};
function ResetTime() {
    let now = new Date();
    let minutes = now.getMinutes() + 30;
    let halfHour = Math.floor(minutes / 30) * 30;
    now.setMinutes(halfHour);
    now.setSeconds(0);
    now.setMilliseconds(0);
    info_map['start_time'] = TimestampToTime(now.getTime(), 'yyyy-MM-dd HH:mm:00');
    info_map['end_time'] = TimestampToTime(now.getTime() + 7200000, 'yyyy-MM-dd HH:mm:00');
}
function GetInfo() {
    let input_list = csg.getdom('.tp_cls_input');
    for(let i = 0; i < input_list.length; i ++) {
        let tid = input_list[i].id;
        info_map[tid] = localStorage.getItem("time_page#" + tid)
        if(tid == 'show_date') {
            input_list[i].checked = info_map[tid] == 'true';
        } else {
            if(tid == 'start_time' || tid == 'end_time') {
                if(info_map[tid] == null || info_map[tid] == '') {
                    // info_map[tid] = TimestampToTime(new Date().getTime() + (tid == 'start_time' ? 3600000 : 10800000), 'yyyy-MM-dd HH:00:00');
                    ResetTime();
                }
            } else if(tid == 'pt') {
                if(info_map[tid] == null) {
                    info_map[tid] = 0;
                }
            }
            input_list[i].value = info_map[tid];
        }
    }
}
function SetInfo() {
    let input_list = csg.getdom('.tp_cls_input');
    for(let i = 0; i < input_list.length; i ++) {
        let tid = input_list[i].id;
        if(tid == 'show_date') {
            info_map[tid] = input_list[i].checked;
        } else {
            info_map[tid] = input_list[i].value;
        }
        localStorage.setItem("time_page#" + tid, info_map[tid]);
    }
}
$(window).keydown(function(e) {
    if (e.keyCode == 83 && e.ctrlKey) {
        e.preventDefault();
        var a=document.createEvent("MouseEvents");
        a.initEvent("click", true, true);
        $('#tp_btn')[0].dispatchEvent(a);
    }
});
csg.docready(function(){
    GetInfo();
    csg.getdom('#tp_btn').addEventListener('click', function(){
        SetInfo();
        alertify.success("设置成功，可打开或刷新信息页");
    });
});
$('#time_db_click_reset').dblclick(function() {
    ResetTime();
    $('#start_time').val(info_map['start_time']);
    $('#end_time').val(info_map['end_time']);
})
</script>