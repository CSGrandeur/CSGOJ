{include file="../../ojtool/view/public/js_qrcode" /}

<!-- <div id="fs_qr_logo" class="fs-4 mb-3"><i class="bi bi-qr-code"></i></div> -->
<div id="contest_fullscreen_info">
    <h1 class="tp_cls_show" id="km"></h1>
    <div class="row d-flex flex-nowrap">
        <div class="col-auto" id="fs_qrcode_div">
            <div id="fs_page_qrcode"></div>
            <div>扫码查看信息</div>
        </div>
        <div class="col-auto" id="fs_time_div">
            <table id="fs_contest_time_table">
                <tr class="tm_div"><td>开始：</td><td class="tp_cls_show" id="start_time"></td></tr>
                <tr class="tm_div"><td>结束：</td><td class="tp_cls_show" id="end_time"></td></tr>
                <tr class="tm_div"><td>当前：</td><td><span class="text-primary" id="current_time_div" time_stamp=<?php echo microtime(true); ?> ></span></td></tr>
            </table>
            <div class="tp_cls_show" id="xx"></div>
            <div class="tp_cls_show" id="cn1"></div>
        </div>
        <div class="col-auto" id="cn2_div">
            <div class="tp_cls_show" id="cn2"></div>
        </div>
        <div class="col-5" id="fs_info_div">
            <div class="md_display_div tp_cls_show" id="nf"></div>
        </div>
    </div>
</div>

<script>
const info_keys = ['km', 'start_time', 'end_time', 'xx', 'np', 'cn', 'pt', 'nf', 'show_date'];
let pt_list = [
    ['窗', '门'],
    ['门', '门'],
    ['门', '窗'],
    ['窗', '窗'],
    ['墙', '门'],
    ['墙', '窗'],
    ['门', '墙'],
    ['窗', '墙'],
    ['', '']
]
let pt;
let info_map = {};
let fs_info_div = $('#fs_info_div');
function ProcessColNum(cn_str, np_str, pt, rotate=false) {
    function NR(snow) {
        // now real
        if(num_perpage_list.length == 1) {
            return snow;
        }
        let tmpi;
        for(tmpi = 0; tmpi < num_perpage_list.length; tmpi ++) {
            if(snow > num_perpage_list[tmpi]) {
                snow -= num_perpage_list[tmpi];
            } else {
                break;
            }
        }
        return `(P${pad0left(tmpi+1, 2, ' ')}-${pad0left(snow, 2, ' ')})`;
    }
    // let col_num_list = cn_str.split(',');   // 各列位置数
    let col_num_list = cn_str.split(/[,，]+/);   // 各列位置数
    let col_sum = 0, page_sum = 0;
    for(let i in col_num_list) {
        col_num_list[i] = parseInt(col_num_list[i]);
        col_sum += col_num_list[i];
    }
    let num_perpage_list;   // 各页名单人数
    if(np_str === null) {
        num_perpage_list = [col_sum];
    } else {
        // num_perpage_list = np_str.split(',');
        num_perpage_list = np_str.split(/[,，]+/);
    }
    for(let i in num_perpage_list) {
        num_perpage_list[i] = parseInt(num_perpage_list[i]);
        page_sum += num_perpage_list[i];
    }
    let infomat = [[], [], []];
    let last = 0, now = 0;
    let limit_col_num = col_num_list.length;
    for(let i = 0; i < col_num_list.length; i ++) {
        let num = parseInt(col_num_list[i]);
        col_num_list[i] = num;
        last = now;
        now += num;
        let shownum = i & 1 ? now : last + 1;
        if(shownum > page_sum) {
            if(i & 1) {
                limit_col_num = i + 1;
                shownum = page_sum;
            } else {
                limit_col_num = i;
                break;
            }
        }
        infomat[0].push(NR(shownum))  // 蛇形排位
        if(shownum >= page_sum) {
            break;
        }
    }
    for(let i = 0; i < limit_col_num; i ++) {
        infomat[1].push(`${i & 1 ? 'u' : 'd'}`); // 上下箭头
    }
    last = 0, now = 0;
    for(let i = 0; i < limit_col_num; i ++) {
        let num = col_num_list[i]
        last = now;
        now += num;
        let shownum = i & 1 ? last + 1 : now;
        if(shownum > page_sum) {
            shownum = page_sum;
        }
        infomat[2].push(NR(shownum));
    }
    let ret = "";
    if(rotate) {
        ret += `<table rotate='true'><tr><td style="margin:auto;writing-mode:vertical-rl;">讲台</td><td style="text-align: left">`;
        ret += `<table class='cn_table'>`;
        for(let i = -1; i <= infomat[0].length; i ++) {
            ret += "<tr>"
            if(i == -1) {
                ret += `<td>${pt_list[pt][1]}</td><td></td><td></td>`;
            } else if(i == infomat[0].length) {
                ret += `<td>${pt_list[pt][0]}</td><td></td><td></td>`;
            } else {
                for(let j = 0; j < 3; j ++) {
                    if(infomat[j][i] == 'u') {
                        infomat[j][i] = '&larr;';
                    } else if(infomat[j][i] == 'd') {
                        infomat[j][i] = '&rarr;';                    
                    }
                    ret += `<td>${infomat[j][i]}</td>`;
                }
            }
        }
        ret += "</table>"
        ret += "</td></tr>"
    } else {
        ret += "讲台";
        ret += "<table class='cn_table' rotate='false'>"
        for(let i = 0; i < 3; i ++) {
            ret += "<tr>"
            ret += i == 0 ? `<td>${pt_list[pt][0]}</td>` : "<td></td>";
            for(let j = infomat[i].length - 1; j >= 0; j --) {
                if(infomat[i][j] == 'u') {
                    infomat[i][j] = '&uarr;';
                } else if(infomat[i][j] == 'd') {
                    infomat[i][j] = '&darr;';                    
                }
                ret += `<td>${infomat[i][j]}</td>`;
            }
            ret += i == 0 ? `<td>${pt_list[pt][1]}</td>` : "<td></td>";
            ret += "</tr>"
        }
        ret += "</table>"
    }
    return ret;
}
function GetInfo() {
    let show_list = csg.getdom('.tp_cls_show');
    for(let i = 0; i < info_keys.length; i ++) {
        let tid = info_keys[i];
        info_map[tid] = localStorage.getItem("time_page#" + tid)
    }
    pt = parseInt(info_map.pt);
    if(isNaN(pt)) {
        pt = 0;
    }
    for(let i = 0; i < show_list.length; i ++) {
        let tid = show_list[i].id;
        if(info_map[tid] == null) {
            info_map[tid] = '';
        }
        if(tid == 'start_time' || tid == 'end_time') {
            if(info_map[tid] == null || info_map[tid] == '') {
                info_map[tid] = TimestampToTime(new Date().getTime() + (tid == 'start_time' ? 3600000 : 10800000), 'yyyy-MM-dd HH:00:00');
            }
            show_list[i].innerHTML = TimeLocal(info_map[tid], info_map['show_date'] == 'true' ? 'yyyy-MM-dd HH:mm:ss' : 'HH:mm:ss');
        } else if(tid == 'nf') {
            info_map[tid] = marked.parse(info_map[tid]);
            show_list[i].innerHTML = info_map[tid];
            if(show_list[i].textContent.trim() != '') {
                MathjaxRender('#nf', document, true);
                fs_info_div.show();
            } else {
                fs_info_div.hide();
            }
            ChangeTimeFont();       
        } else if(tid == 'cn1' || tid == 'cn2') {
            if(('cn' in info_map) && $.trim(info_map.cn) != '') {
                show_list[i].innerHTML = ProcessColNum(info_map.cn, 'np' in info_map ? info_map.np : null, pt, tid == 'cn2');
            }
        }else {
            show_list[i].innerHTML = info_map[tid];
        }
    }
}
let contest_fullscreen_info = $('#contest_fullscreen_info');
let fs_qrcode_div = $('#fs_qrcode_div');
let fs_time_div = $('#fs_time_div');
let fs_qr_logo = $('#fs_qr_logo');
let url_span = $('#xx');
function InitQrCode() {
    const qrCodeFull = new QRCodeStyling({
        width: window.screen.width / 3.5,
        height: window.screen.width / 3.5,
        margin: 0,
        type: "svg",
        data: url_span.text(),
        image: "/static/image/global/gothic_sign.svg",
        dotsOptions: {
            type: "classy-rounded",
            color: "#337AB7",
            gradient: null
        },
        imageOptions: {
            hideBackgroundDots: true,
            imageSize: 0.4,
            margin: 0
        }
    });
    qrCodeFull.append(document.getElementById("fs_page_qrcode"));
}
fs_qrcode_div.click(function(){
    // 不需要二维码时隐藏
    fs_qrcode_div.hide();
    // fs_qr_logo.show();
    ChangeTimeFont();
});
url_span.click(function(){
    fs_qrcode_div.show();
    // fs_qr_logo.hide();
    ChangeTimeFont();
})
function ChangeTimeFont() {
    if(fs_info_div.is(':visible') || fs_qrcode_div.is(':visible')) {
        // 通知信息或二维码显示时，时间字体缩小，座位放下方
        $('.tm_div').css('font-size', '3vw');
        url_span.css('font-size', Math.min(90 / StrWidthLength(url_span.text()), 6) + "vw");
        $('#km').css('font-size', '4vw');
        $('#cn1').show(); $('#cn2_div').hide();
    } else {
        // 仅有时间信息时，设置较大字号
        $('.tm_div').css('font-size', '4vw');
        url_span.css('font-size', Math.min(110 / StrWidthLength(url_span.text()), 5) + "vw");
        $('#km').css('font-size', '6vw');
        $('#cn1').hide(); $('#cn2').html() == '' ? $('#cn2_div').hide() : $('#cn2_div').show();
    }
    if($.trim(url_span.text()) == '') {
        // 没有IP等特殊信息时，座位放时间下方
        $('#cn1').css('font-size', '3vw');
        $('#cn1').show(); $('#cn2_div').hide();
    }
}
function MathjaxRender(selector_str, dom_range=document) {
    const markdown_eles = Array.from(dom_range.querySelectorAll(selector_str));
    markdown_eles.forEach(m_item => {
        const code_eles = Array.from(m_item.querySelectorAll("code"));
        code_eles.forEach(c_item => {
            let content = c_item.textContent.trim();
            if(c_item.classList.contains('language-mathjax') || content[0] == '$' && content[content.length - 1] == '$' && content.length > 2) {
                if(content.startsWith('$$') && content.length > 4) {
                    content = '\\[' + content.slice(2, content.length - 2) + '\\]';
                } else if(content.startsWith('$')) {
                    content = '\\(' + content.slice(1, content.length - 1) + '\\)';
                } else {
                    content = '\\[' + content + '\\]';
                }
                const newItem = dom_range.createElement("span");
                newItem.textContent = content;
                const parentNode = c_item.parentNode;
                if(parentNode.tagName.toLowerCase() === 'pre') {
                    parentNode.replaceWith(newItem);
                } else {
                    c_item.replaceWith(newItem);
                }
            }
        });
    });
    MathJax.typeset(document.querySelectorAll('.marked_math_div'));
    MathJax.typeset(document.querySelectorAll('.md_display_div'));
}

function contest_clock(target_dom, time_diff) {
    target_dom.textContent = TimestampToTime(new Date().getTime()+time_diff, info_map['show_date'] == 'true' ? 'yyyy-MM-dd HH:mm:ss' : 'HH:mm:ss');
    setTimeout(() => {
        contest_clock(target_dom, time_diff);
    }, 1000);
}
function SetAutoTime() {
    let current_time_div = document.getElementById('current_time_div')
    let time_diff = new Date(current_time_div.getAttribute('time_stamp') * 1000).getTime()-new Date().getTime();
    contest_clock(current_time_div, time_diff);
}
function Turn90(table_this_div) {
    if(table_this_div.children()[0].getAttribute('rotate') == 'true') {
        table_this_div.html(ProcessColNum(info_map.cn, 'np' in info_map ? info_map.np : null, pt, false));
    } else {
        table_this_div.html(ProcessColNum(info_map.cn, 'np' in info_map ? info_map.np : null, pt, true));
    }
}
csg.docready(function(){
    GetInfo();
    if(url_span.text().length > 0) {
        url_span.css('font_size', Math.min(90 / StrWidthLength(url_span.text()), 6) + "vw");
    }
    ChangeTimeFont();
    SetAutoTime();
    InitQrCode();
    
    $('#cn1').click(function(){
        $('#cn1').hide(); $('#cn2_div').show();
    });
    // $('#cn1').mousedown(function(e){
    //     if(e.which == 2){
    //         $(this).off("scroll");
    //         Turn90($(this));
    //     }
    // });
    $('#cn2_div').click(function() {
        $('#cn1').show(); $('#cn2_div').hide();
    });
    $('#cn2').mouseup(function(e){
        if(e.which == 2){
            $(this).off("scroll");
            Turn90($(this));
        }
    });
});

</script>
<style>
    :-webkit-full-screen,
    :-moz-full-screen,
    :-ms-fullscreen,
    :fullscreen {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    #xx {
        font-size: 5vw;
    }
    #contest_fullscreen_info>h1 {
        margin-bottom: 2vw;
        hyphens: auto;
        font-size: 4vw;
    }
    #contest_fullscreen_info,#fs_qrcode_div,#fs_time_div,#fs_qr_logo{
        margin: auto;
    }
    #fs_qr_logo {
        /* 二维码logo小图标按钮 */
        position: fixed;
        bottom: 0;
        left: 0;
    }
    #fs_qrcode_div {
        display: none;
    }
    #fs_info_div {
        display: none;
        max-height: 80vh;
        overflow-y: scroll;
    }
    #nf {
        font-size: 2vw !important;
        text-align: left;
        word-wrap: break-word;
        line-height: 1.2;
    }
    #fs_contest_time_table {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #contest_fullscreen_info {
        /* padding: 3vw; */
        font-weight: bold;
        text-align: center;
        font-size: 3vw;
        font-family: "等线", Helvetica Neue,Helvetica,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Noto Sans CJK SC,WenQuanYi Micro Hei,Arial,sans-serif;
        background-color: white;
        position: fixed;
        width: 100%;
        /* height: 100%; */
        margin: auto;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .cn_table {
        margin: auto;
    }
    #cn1, #cn2_div {
        display: none;
        color: saddlebrown;
        margin: auto;
    }
    #cn1 {
        font-size: 2vw;
    }
    #cn2 {
        font-size: 3vw;
    }
    .cn_table td {
        padding-left: 1vw;
    }
</style>