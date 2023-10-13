<div class="page-header">
    <h1>
        {if $edit_mode && !$copy_mode }
        编辑练习 <a href="__OJ__/contest/problemset?cid={$contest['contest_id']}" target="_blank">#{$contest['contest_id']}</a>
        <a href="__ADMIN__/filemanager/filemanager?item={$controller}&id={$contest['contest_id']}" target="_blank"><button class="btn btn-success" id="attachfile">附件管理</button></a>
        <?php $defunct = $contest['defunct']; $item_id = $contest['contest_id']; ?>
        {include file="admin/changestatus_button" /}
        {else /}
        添加练习
        {/if}
    </h1>
</div>
{include file="../../csgoj/view/public/bootstrap_select" /}

    <form id="contest_edit_form" method='post' action="__ADMIN__/contest/{if $edit_mode && !$copy_mode}contest_edit_ajax{else /}contest_add_ajax{/if}">
        <div class="row">
            <div class="col-md-9 col-sm-9">
                <div class="form-group">
                    <label for="title">练习名称：</label>
                    <input type="text" class="form-control" id="title" placeholder="标题 ..." name="title" {if $edit_mode}value="{$contest['title']}"{/if}>
                </div>
                <div class="form-inline">
                    <label for="start_time" style="width:80px;">开始时间:</label>
                    <input type="text" class="form-control" name="start_year"  style="width:80px;" value="{$start_year}">   <label for="year">年</label>   
                    <input type="text" class="form-control" name="start_month" style="width:50px;" value="{$start_month}">  <label for="Month">月</label>  
                    <input type="text" class="form-control" name="start_day"   style="width:50px;" value="{$start_day}">    <label for="Day">日</label>    
                    <input type="text" class="form-control" name="start_hour"  style="width:50px;" value="{$start_hour}">   <label for="Hour">时</label>   
                    <input type="text" class="form-control" name="start_minute"style="width:50px;" value="{$start_minute}"> <label for="Minute">分</label> 
                </div>
                <div class="form-inline">
                    <label for="end_time" style="width:80px;">结束时间:</label>
                    <input type="text" class="form-control" name="end_year"  style="width:80px;" value="{$end_year}">       <label for="year">年</label>  
                    <input type="text" class="form-control" name="end_month" style="width:50px;" value="{$end_month}">      <label for="Month">月</label> 
                    <input type="text" class="form-control" name="end_day"   style="width:50px;" value="{$end_day}">        <label for="Day">日</label>   
                    <input type="text" class="form-control" name="end_hour"  style="width:50px;" value="{$end_hour}">       <label for="Hour">时</label>  
                    <input type="text" class="form-control" name="end_minute"style="width:50px;" value="{$end_minute}">     <label for="Minute">分</label>
                </div>
                <div class="form-inline">
                    <label for="award_ratio" style="width:80px;">评奖比例:</label>
                    <input type="text" class="form-control" name="ratio_gold"  style="width:50px;" value="{$ratio_gold}">       <label for="ratio_gold">% 金 </label>    
                    <input type="text" class="form-control" name="ratio_silver" style="width:50px;" value="{$ratio_silver}">    <label for="ratio_silver">% 银 </label>  
                    <input type="text" class="form-control" name="ratio_bronze"   style="width:50px;" value="{$ratio_bronze}">  <label for="ratio_bronze">% 铜 </label>  
                </div>
                <div class="form-inline">
                    <!-- <label for="frozen_minute" style="width:150px;">Frozen Minutes:</label> -->
                    <!-- <label for="frozen_minute">结束前封榜时长</label>      -->
                    <input type="hidden" class="form-control" name="frozen_minute"  style="width:80px;" value="0">
                    <!-- <label for="frozen_after">结束前封榜时长</label>    -->
                    <input type="hidden" class="form-control" name="frozen_after" style="width:80px;" value="0">
                </div>
                <!-- <div class="form-inline">
                    <span style="display: inline-block;">
                        <label style="width:150px;">Contest Type:</label>
                        <div class="radio">
                            <label><input name="private" type="radio" value="0" {if !isset($private) || $private % 10 == 0} checked {/if} />Public</label>
                            <label><input name="private" type="radio" value="1" {if isset($private) && $private % 10 == 1} checked {/if} />Private</label>
                            <label><input name="private" type="radio" value="2" {if isset($private) && $private % 10 == 2} checked {/if} />Standard</label>
                        </div>
                        &nbsp;&nbsp;
                    </span>
                </div> -->
                <input name="private" type="hidden" value="4" >
                <div class="form-inline">
                    <label for="password" style="width:80px;">班级:</label>
                    {if $edit_mode && !$copy_mode}
                    <span id="clss_show">{$contest['password']}</span>
                    <input name="password" type="hidden" value="{$contest['password']}">
                    {else /}
                    <select name="password" class="form-control" id="clss_input" style="width: 300px">
                    </select>
                    {/if}
                </div>
                <div class="form-inline">
                    <label style="width:80px;">附加题:</label>
                    <div class="radio">
                        <label><input name="attach_pro" type="radio" value="1" {if !isset($private) || round($private / 10) == 1} checked {/if} />有</label>
                        <label><input name="attach_pro" type="radio" value="0" {if isset($private) && round($private / 10) == 0} checked {/if} />无</label>
                    </div>
                </div>
                <!-- <div class="form-inline">
                    <label for="start_time" style="width:150px;">Top Team Number for School Rank:</label>
                    <input type="text" class="form-control" name="topteam"  style="width:80px;" value="{$topteam}">
                </div> -->
                <input type="hidden" class="form-control" name="topteam"  style="width:80px;" value="{$topteam}">
                <div class="form-inline">
                    <span style="display: inline-block;">
                        <label for="language" style="width:80px;">允许语言:</label>
                        <?php $ojLang = config('CsgojConfig.OJ_LANGUAGE'); ?>
                        <select name="language[]" class="selectpicker" multiple="multiple">
                            {foreach($ojLang as $k=>$val) }
                            <option value="{$k}" {if $edit_mode && (($contest['langmask'] >> $k) & 1)}selected="selected"{/if}>
                                {$val}
                            </option>
                            {/foreach}
                        </select>
                        &nbsp;&nbsp;
                    </span>
                </div>
                <div class="form-group">
                    <label for="problems">题目（英文逗号','分隔题号）:</label>
                    <input type="text" class="form-control" id="title" placeholder="1000,1001,1002..." name="problems" {if $edit_mode}value="{$problems}"{/if}>
                </div>
                <label for="description">通知 (markdown)：</label>
                <textarea id="contest_description" class="form-control" placeholder="Content..." rows="5" cols="50" name="description" >{if $edit_mode}{$contest['description']|htmlspecialchars}{/if}</textarea>
            </div>
            <!-- <div class="col-md-3 col-sm-3">
                <label for="users">Users(split user_id with '\n')：</label>
                <textarea class="form-control" placeholder="team001&#10;team002&#10;..." rows="20" name="users" >{if $edit_mode && !$copy_mode}{$users}{/if}</textarea>
            </div> -->
            <textarea class="form-control" placeholder="team001&#10;team002&#10;..." rows="20" name="users" style="display:none" >{if $edit_mode && !$copy_mode}{$users}{/if}</textarea>
        </div>
        {if $edit_mode && !$copy_mode }
        <input type="hidden" id='id_input' value="{$contest['contest_id']}" name="contest_id">
        {/if}
        <br/>
        <button type="submit" id="submit_button" class="btn btn-primary">{if $edit_mode && !$copy_mode}修改{else/}添加{/if}</button>
        {if !$edit_mode || $copy_mode}
        <button type="button" id="distribute_button" class="btn btn-danger">分发至学期所有班级</button>
        {/if}
    </form>
<input type="hidden" id='page_info' edit_mode="{if $edit_mode && !$copy_mode}1{else/}0{/if}" now_year="<?php echo date("Y"); ?>">


<div id="loading_div" class='overlay'>
    <div class="d-flex align-items-center" id="loading_spinner_div">
        <div id="loading_spinner" class="spinner-border ms-auto" aria-hidden="true"></div>
        <strong id="loading_text" role="status">提交中...</strong>
    </div>
</div>
<style type="text/css">
.form-inline {
    padding-bottom: 10px;
}

#loading_spinner_div {
    position: fixed;
    top: 10vw;
    left: 50%;
    z-index: 21;
}
#loading_div {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(200,200,200,0.7);
    z-index: 20;
}
#loading_text {
    font-size: 24px;
}
</style>
<script type="text/javascript">
    var page_info = $('#page_info');
    let now_year = parseInt(page_info.attr('now_year'));
    var edit_mode = page_info.attr('edit_mode');
    let contest_edit_form = $('#contest_edit_form');
    var submit_button = $('#submit_button');
    var submit_button_text = submit_button.text();
    let clss_to_submit_list = [], clss_html_list = [], semester_map;

    let clss_list = [], clss_map = {};
    let clss_input = $('#clss_input');
    let loading_div = $('#loading_div'), loading_text = $('#loading_text');
    function InitClssSelection() {
        if(clss_input.length == 0) {
            let clss_id = $('#clss_show').text();
            $.get('/csgoj/clss/JudgeClss?clss_id=' + clss_id, function(ret) {
                $('#clss_show').text(`${ret.clss.clss_id}：${ret.clss.title}`)
            });
        } else {
            $.post('/admin/clss/clss_list_ajax', null, function(ret) {
                clss_list = ret;
                clss_list.sort((a, b) => {
                    if(a.semester == b.semester) {
                        return b.clss_id - a.clss_id;
                    } else {
                        return a.semester > b.semester ? -1 : 1;
                    }
                });
                let clss_option_list = [];
                for(let i = 0; i < clss_list.length; i ++) {
                    if(Math.abs(parseInt(clss_list[i].semester.split('-')[0]) - now_year) > 3) {
                        break;
                    }
                    clss_option_list.push(`<option value="${clss_list[i].clss_id}" >${clss_list[i].title}</option>`)
                    clss_map[clss_list[i].clss_id] = clss_list[i];
                }
                clss_input.html(clss_option_list.join(''));
                
                semester_map = {};
                for(let i = 0; i < clss_list.length; i ++) {
                    if(!(clss_list[i].semester in semester_map)) {
                        semester_map[clss_list[i].semester] = []
                    }
                    semester_map[clss_list[i].semester].push(clss_list[i]);
                }
            });
        }
    }
    $(document).ready(function() {
        InitClssSelection();
        $('input[type="text"],select,textarea').tooltipster({
            trigger: 'custom',
            position: 'bottom',
            animation: 'grow',
            theme: 'tooltipster-noir',
            distance: -15
        });
        contest_edit_form.validate({
            rules:{
                title:{
                    required: true,
                    maxlength: 200
                },
                description: {
                    maxlength: 16384
                },
                password:{
                    required: true
                },
                ratio_gold: {
                    number: true,
                    max: 100,
                    min: 0
                },
                ratio_silver: {
                    number: true,
                    max: 100,
                    min: 0
                },
                ratio_bronze: {
                    number: true,
                    max: 100,
                    min: 0
                },
                frozen_minute: {
                    number: true,
                    max: 2592000,
                    min: -1
                },
                frozen_after: {
                    number: true,
                    max: 2592000,
                    min: -1
                }
            },
            errorPlacement: function (error, element) {
                var ele = $(element),
                    err = $(error),
                    msg = err.text();
                if (msg != null && msg !== '') {
                    ele.tooltipster('content', msg);
                    ele.tooltipster('open');
                }
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass(errorClass).addClass(validClass).tooltipster('close');
            },
            submitHandler: function(form) {
                submit_button.attr('disabled', true);
                submit_button.text('Waiting...');
                $(form).ajaxSubmit({
                    success: function(ret)
                    {
                        if(ret['code'] == 1)
                        {
                            if(typeof(ret['data']['alert']) != 'undefined' && ret['data']['alert'] == true){
                                alertify.alert(ret['msg']);
                            }else{
                                alertify.success(ret['msg']);
                            }
                            button_delay(submit_button, 3, submit_button_text);
                            if(edit_mode != '1') {
                                setTimeout(function(){location.href='contest_edit?id='+ret['data']['id']}, 500);
                            }
                        }
                        else
                        {
                            alertify.alert(ret['msg']);
                            button_delay(submit_button, 3, submit_button_text);
                        }
                        return false;
                    }
                });
                return false;
            }
        });
    });
    function SemesterHtmlList(semester_now) {
        clss_html_list = [];
        for(let i = 0; i < semester_map[semester_now].length && i < 20; i ++) {
            clss_html_list.push(`<li><div class="checkbox"><label><input type="checkbox" class="semester_clss_check" value="${semester_map[semester_now][i].clss_id}" checked >${semester_map[semester_now][i].clss_id}: ${DomSantize(semester_map[semester_now][i].title)}</label></div></li>`);
        }
    }
    function SemesterClssList() {
        clss_to_submit_list = $('input.semester_clss_check[type=checkbox]:checked').map(function() {
            return clss_map[parseInt(this.value)];
        })
    }
    function SubmitToSemester(ith_clss) {
        if(ith_clss >= clss_to_submit_list.length) {
            alertify.success("提交完毕");
            window.location = "/admin/contest";
            loading_div.hide();
            return;
        }
        clss_input.val(clss_to_submit_list[ith_clss].clss_id);
        loading_text.text(`正在提交：${clss_to_submit_list[ith_clss].clss_id}: ${clss_to_submit_list[ith_clss].title} ...`)
        contest_edit_form.ajaxSubmit({
            success: function(ret) {
                if(ret.code == 1) {
                    loading_text.text(`正在提交：${clss_to_submit_list[ith_clss].clss_id}: ${clss_to_submit_list[ith_clss].title} 成功`)
                    SubmitToSemester(ith_clss + 1);
                }
                else {
                    alertify.alert(ret.msg);
                    loading_div.hide();
                }
                return false;
            }
        });
    }
    document.addEventListener('change', function(e) {
        if(e.target.id == 'semester_select') {
            SemesterClssList(e.target.value);
            $('#clss_list_to_semester_ol').html(clss_html_list.join(''));
        }
    })
    $('#distribute_button').click(function() {
        let semester_option_html = []
        let semester_now = null;
        for(let semester in semester_map) {
            if(semester.trim() == '') {
                continue;
            }
            if(semester_now == null) {
                semester_now = semester;
            }
            semester_option_html.push(`<option value="${semester}" >${semester}</option>`)
        }
        SemesterHtmlList(semester_now);
        alertify.confirm(
            "确认学期", 
            `<select class="form-control" id="semester_select" style="width: 200px">${semester_option_html.join('')}</select><ol id="clss_list_to_semester_ol">${clss_html_list.join('')}</ol>`, 
            function() {
                loading_div.show();
                let semester = $('#semester_select').val().trim();
                let clss_str_list = [];
                SemesterClssList(semester);
                SubmitToSemester(0);
            }, function() {
                alertify.notify("什么也没有发生");
            })
    })
    $(window).keydown(function(e) {
        if (e.keyCode == 83 && e.ctrlKey) {
            e.preventDefault();
            var a=document.createEvent("MouseEvents");
            a.initEvent("click", true, true);
            $('#submit_button')[0].dispatchEvent(a);
        }
    });

</script>