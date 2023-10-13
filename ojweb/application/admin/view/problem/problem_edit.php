<?php $edit_mode = isset($problem); $copy_mode = isset($copy_mode) ? $copy_mode : false;?>
<link rel="stylesheet" type="text/css" href="__STATIC__/csgoj/oj_problem.css" />
<script type="text/javascript" src="__STATIC__/csgoj/oj_problem.js"></script>

<div class="page-header">
    <h1>
        {if $edit_mode }
            {if $copy_mode}Copy{else /}Edit{/if} problem <a href="__OJ__/problemset/problem?pid={$problem['problem_id']}" target="_blank">#{$problem['problem_id']}</a>
            <a href="__ADMIN__/filemanager/filemanager?item=problem&id={$problem['problem_id']}" target="_blank">
                <button class="btn btn-success" id="attachfile">Attach file manager</button>
            </a>
            <a href="__ADMIN__/judge/judgedata_manager?item=problem&id={$problem['problem_id']}" target="_blank">
                <button class="btn btn-info" id="attachfile">Test Data</button>
            </a>
            <?php $defunct = $problem['defunct']; $item_id = $problem['problem_id']; ?>
            {include file="admin/changestatus_button" /}
        {else /}
            Add Problem
        {/if}
    </h1>
</div>
<div class="container">
    <form id="problem_edit_form" method='post' action="__ADMIN__/problem/problem_edit_ajax">
        <div class="form-group">
            {include file="admin/co_editor_input" /}
            <label for="title">Problem Title：</label>
            <input type="text" class="form-control" placeholder="Problem Title..." name="title" {if $edit_mode}value="{$problem['title']}"{/if}>
            <label for="time_limit">Time Limit(S)：</label>
            <input type="text" class="form-control" placeholder="Time Limit..." name="time_limit" value="{if $edit_mode}{$problem['time_limit']}{else/}1{/if}">
            <label for="memory_limit">Memory Limit(MByte)：</label>
            <input type="text" class="form-control" placeholder="Memory Limit..." name="memory_limit" value="{if $edit_mode}{$problem['memory_limit']}{else/}128{/if}">
        </div>

        <label for="description">Description (markdown)：</label>
        <textarea class="form-control" placeholder="Description..." rows="5" cols="50" name="description" >{if $edit_mode}{$problem['description']|htmlspecialchars}{/if}</textarea>

        <label for="input">Input Description (markdown)：</label>
        <textarea class="form-control" placeholder="Input description..." rows="4" cols="50" name="input" >{if $edit_mode}{$problem['input']|htmlspecialchars}{/if}</textarea>

        <label for="output">Output Description (markdown)：</label>
        <textarea class="form-control" placeholder="Output description..." rows="4" cols="50" name="output" >{if $edit_mode}{$problem['output']|htmlspecialchars}{/if}</textarea>

        <div id="real_sample_textarea">
            <label for="sample_input">Sample Input：</label>
            <textarea class="form-control" placeholder="" rows="5" cols="50" name="sample_input" >{if $edit_mode}{$problem['sample_input']|htmlspecialchars}{/if}</textarea>
            
            <label for="sample_output">Sample Output：</label>
            <textarea class="form-control" placeholder="" rows="5" cols="50" name="sample_output" >{if $edit_mode}{$problem['sample_output']|htmlspecialchars}{/if}</textarea>
        </div>
        <label>Samples (not test data)： 
            <button type="button" class="btn btn-sm btn-primary" id="sample_add_btn">+</button>
        </label>
        <div id="fake_sample_div" class="sample_div">

        </div>

        <label for="hint">Hint (markdown)：</label>
        <textarea class="form-control" placeholder="Hint ..." rows="2" cols="50" name="hint" >{if $edit_mode}{$problem['hint']|htmlspecialchars}{/if}</textarea>

        <label for="source">Source (markdown)：</label>
        <input type="text" class="form-control" placeholder="Source ..." name="source" {if $edit_mode}value="{$problem['source']|htmlspecialchars}"{/if}>
        <label for="author">Author：</label>
        <input type="text" class="form-control" placeholder="Author ..." name="author" {if $edit_mode}value="{$problem['author']|htmlspecialchars}"{/if}>

        <div class="checkbox">
            <label>
                <input type="checkbox" kind_active="1" name="spj"  value="true"
                {if $edit_mode && $problem['spj'] == 1 }
                    checked="checked"
                {/if}
                > <span class="text-red">SpecialJudge</span>
            </label>
        </div>
        {if $edit_mode && !$copy_mode}
            <input type="hidden" value="{$problem['problem_id']}" name="problem_id">
        {/if}
        {if $copy_mode}
            <input type="hidden" value="{$problem['problem_id']}" name="problem_copy_id">
        {/if}
        <button type="submit" id="submit_button" class="btn btn-primary">{if $edit_mode}Modify{else /}Add{/if} Problem</button>
    </form>
</div>
<input type="hidden" id='page_info' edit_mode="{if $edit_mode}1{else/}0{/if}" copy_mode="{if isset($copy_mode) && $copy_mode}1{else /}0{/if}">
<script type="text/javascript">
const sample_num_max = 10;
const sample_length_max = 1024;
const hlevel = 5;
let real_sample_textarea;
let real_sample_in;
let real_sample_out;
let sample_in_str;
let sample_out_str;
let fake_sample_div;
function GetSampleNum() {
    return $('.sample_item_div').length;
}
function GetJointSampleTxt(name='input') {
    let textareas = document.getElementsByClassName(`sample_${name}_area`);
    let values = Array.prototype.map.call(textareas, function(textarea) {
        JudgeSampleLenth(textarea);
        return textarea.value;
    });
    return values.join('\n##CASE##\n');
}
function UpdateRealSample() {
    real_sample_in.val(GetJointSampleTxt('input'));
    real_sample_out.val(GetJointSampleTxt('output'));
}
function SwapDiv(div1, div2) {
    let tmp_div = document.createElement("div");
    div1.parentNode.insertBefore(tmp_div, div1);
    div2.parentNode.insertBefore(div1, div2);
    tmp_div.parentNode.insertBefore(div2, tmp_div);
    tmp_div.remove();
}
function JudgeSampleLenth(target_obj) {
    if(target_obj.value.length > sample_length_max) {
        alertify.warning(`Sample ${target_obj.getAttribute('stype')} ${target_obj.getAttribute('cs')} too long.<br/>Content truncated.`)
        target_obj.value = target_obj.value.substring(0, 1024);
    }
}
// sample process
$(document).ready(function() {
    real_sample_textarea = $('#real_sample_textarea');
    real_sample_in = real_sample_textarea.find('textarea[name="sample_input"]')
    real_sample_out = real_sample_textarea.find('textarea[name="sample_output"]')
    sample_in_str = real_sample_in.val();
    sample_out_str = real_sample_out.val();
    fake_sample_div = $('#fake_sample_div');
    fake_sample_div.html(ProblemSampleHtml(sample_in_str, sample_out_str, hlevel, true));
    $('#sample_add_btn').click(function() {
        let sample_num = GetSampleNum();
        if(GetSampleNum() > sample_num_max) {
            alertify.error("To many samples.");
        } else {
            fake_sample_div.append($(OneSample(sample_num, '', '', hlevel, true))[0]);
        }
    });
    document.addEventListener('click', (e) => {
        if(e.target.classList.contains('up_sample_btn')) {
            let sample_item_div_this = e.target.closest('.sample_item_div');
            let sample_item_div_pre = sample_item_div_this.previousElementSibling;
            if(sample_item_div_pre !== null) {
                SwapDiv(sample_item_div_this, sample_item_div_pre);
                UpdateRealSample();
                ResetSampleIdx(fake_sample_div);
            }
        } else if(e.target.classList.contains('down_sample_btn')) {
            let sample_item_div_this = e.target.closest('.sample_item_div');
            let sample_item_div_pre = sample_item_div_this.nextElementSibling;
            if(sample_item_div_pre !== null) {
                SwapDiv(sample_item_div_this, sample_item_div_pre);
                UpdateRealSample();
                ResetSampleIdx(fake_sample_div);
            }
        }
    });
    document.addEventListener('dblclick', (e) => {
        if(e.target.classList.contains('del_sample_btn')) {
            if(GetSampleNum() <= 1) {
                alertify.error("At least 1 sample.");
            } else {
                e.target.closest('.sample_item_div').remove();
                ResetSampleIdx(fake_sample_div);
                UpdateRealSample();
            }
        }
    });
    document.addEventListener('change', (e) => {
        if(e.target.classList.contains('sample_text_input')) {
            UpdateRealSample();
        }
    });
});

// form
    var page_info = $('#page_info');
    var edit_mode = page_info.attr('edit_mode');
    let copy_mode = page_info.attr('copy_mode');
    var submit_button = $('#submit_button');
    var submit_button_text = submit_button.text();
    $(document).ready(function() {
        $('input[type="text"],textarea').tooltipster({
            trigger: 'custom',
            position: 'bottom',
            animation: 'grow',
            theme: 'tooltipster-noir',
            distance: -15
        });
        $('#problem_edit_form').validate({
            rules:{
                title:{
                    required: true,
                    maxlength: 200
                },
                time_limit:{
                    required: true,
                    maxlength: 200
                },
                memory_limit:{
                    required: true,
                    maxlength: 200
                },
                description: {
                    required: true,
                    maxlength: 65536
                },
                input: {
                    maxlength: 65536
                },
                output: {
                    maxlength: 65536
                },
                sample_input: {
                    required: true,
                    minlength: 1,
                    maxlength: 16384
                },
                sample_output: {
                    required: true,
                    minlength: 1,
                    maxlength: 16384
                },
                hint: {
                    maxlength: 65536
                },
                source: {
                    maxlength: 100
                },
                author: {
                    maxlength: 100
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
                try {
                    $(element).removeClass(errorClass).addClass(validClass).tooltipster('close');
                } catch(e) {}
            },
            submitHandler: function(form)
            {
                submit_button.attr('disabled', true);
                submit_button.text('Waiting...');
                $('.sample_input_area').prop('disabled', true);
                UpdateRealSample();
                $(form).ajaxSubmit({
                    success: function(ret) {
                        if(ret['code'] == 1) {
                            if(typeof(ret['data']['alert']) != 'undefined' && ret['data']['alert'] == true){
                                alertify.alert(ret['msg']);
                            }else{
                                alertify.success(ret['msg']);
                            }
                            button_delay(submit_button, 3, submit_button_text);
                            if(edit_mode != 1 || copy_mode == 1) {
                                setTimeout(function(){location.href='problem_edit?id='+ret.data.problem_id}, 500);
                            }
                        }
                        else {
                            alertify.alert(ret['msg']);
                            button_delay(submit_button, 3, submit_button_text);
                        }
                        $('.sample_input_area').prop('disabled', false);
                        return false;
                    }
                });
                return false;
            }
        });
    });
    $(window).keydown(function(e) {
        if (e.keyCode == 83 && e.ctrlKey) {
            e.preventDefault();
            var a=document.createEvent("MouseEvents");
            a.initEvent("click", true, true);
            $('#submit_button')[0].dispatchEvent(a);
        }
    });
</script>