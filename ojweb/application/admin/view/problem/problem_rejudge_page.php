<div class="page-header">
    <h1>{$rejudge_type|ucfirst} {if $rejudge_type == 'contest'}<a href="__OJ__/contest/contest?cid={$cid}">{$cid}</a> {/if} Rejudge</h1>
</div>
<div class="container">
    {if($rejudge_type != 'contest')}
    <span class="alert alert-info" style="display:block;">
        <p>此处重判将忽略比赛中的提交. 如需重判比赛中的提交，请在具体比赛的控制台中进行操作.</p>
        <p>Solutions in contests will be <strong>ignored</strong>. If you want to rejudge problems in a contest, use contest rejudge.</p>
    </span>
    {/if}
    <form id="problem_rejudge_form" method='post' action="{$submit_url}">
        <div style="display: flex; align-items: flex-start;">
            <div style="display: inline-block;">
                <div ><label><button type="button" class="btn btn-xs btn-danger"  style="width:80px;"  title="全选" id="check_res_all">全选(All)</label></div>
                <div ><label><button type="button" class="btn btn-xs btn-success" style="width:80px;"  title="清空" id="check_res_non">清空(Non)</label></div>
                <div ><label><button type="button" class="btn btn-xs btn-warning" style="width:80px;"  title="反选" id="check_res_rev">反选(Rev)</label></div>
                <div ><label><button type="button" class="btn btn-xs btn-primary" style="width:80px;"  title="默认" id="check_res_dft">默认(Dft)</label></div>
                <div class="checkbox"><label class="text-success"><input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="4"  title="通过"  id="rejudge_res_check_ac" >通过(AC)</label></div>
                <div class="checkbox"><label class="text-danger"> <input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="5"  title="格式错误"checked>格式错误(PE)</label></div>
                <div class="checkbox"><label class="text-danger"> <input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="6"  title="错误"    checked>答案错误(WA)</label></div>
                <div class="checkbox"><label class="text-warning"><input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="7"  title="超时"    checked>超时(TLE)</label></div>
                <div class="checkbox"><label class="text-warning"><input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="8"  title="超内存"  checked>超内存(MLE)</label></div>
                <div class="checkbox"><label class="text-warning"><input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="9"  title="输出超限"checked>输出超限(OLE)</label></div>
                <div class="checkbox"><label class="text-warning"><input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="10" title="运行错误"checked>运行错误(RE)</label></div>
                <div class="checkbox"><label class="text-info">   <input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="11" title="编译错误"checked>编译错误(CE)</label></div>
                <div class="checkbox"><label class="text-default"><input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="2"  title="编译中"  >编译中(CI)</label></div>
                <div class="checkbox"><label class="text-default"><input name="rejudge_res_check[]" class="rejudge_res_check" type="checkbox" value="3"  title="评测中"  >评测中(RJ)</label></div>
            </div>
            <div style="display: inline-block; margin-left: 20px; padding-top:0;">
                <div class="form-group">
                    <label for="open_status_window_check">重判后立刻打开提交状态窗口<br/>(Open Status Window After Rejudge Sent)：</label>
                    <input type="checkbox" id="open_status_window_check" name='open_status_window_check' class="switch_ctr">
                </div>
                <div class="form-group">
                    <label for="solution_id">基于提交号(By Solution ID)：</label>
                    <input type="text" class="form-control" id="solution_id" placeholder="Solution ID..." name="solution_id" style="max-width:400px;">
                    <br/>
                    <label for="problem_id">基于题号(By Problem ID)：</label>
                    <input type="text" class="form-control" id="problem_id" placeholder="{if $controller=='problem'}数字(Numerate ID) 2000,2001...{else/}字母(Alphabet ID) A,B,C...{/if}" name="problem_id" style="max-width:400px;">
                    <br/>
                </div>

                <button type="submit" id="submit_button" class="btn btn-primary">Rejudge</button>
                <button type="reset" id="submit_button" class="btn btn-warning">Reset Form</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
let DEFAULT_REJUDGE_RES = new Set(["5", "6", "7", "8", "9", "10", "11"]);
    $(document).ready(function() {
        $('.switch_ctr').each(function() {
            var switch_ctr = $(this);
            var switch_name = switch_ctr.attr('name');
            switch_ctr.bootstrapSwitch();
            if (localStorage.getItem(switch_name)) {
                var switch_cookie = $.trim(localStorage.getItem(switch_name));
                if (switch_cookie == 'true') {
					if(switch_name === 'all_rejudge_check') {
						alertify.alert('Be careful to rejudge accepted solutions!')
					}
                    switch_ctr.bootstrapSwitch('state', true, true);
                }
                else {
                    switch_ctr.bootstrapSwitch('state', false, true);
                }
            }
            switch_ctr.unbind('switchChange.bootstrapSwitch').on('switchChange.bootstrapSwitch', function(event, state) {
                if(state == true) {
					if($(this).attr('name') === 'all_rejudge_check') {
						alertify.alert('Be careful to rejudge accepted solutions!')
					}
                    localStorage.setItem(switch_name, 'true');
                }
                else{
                    localStorage.setItem(switch_name, 'false');
                }
            });
        });
        $('#check_res_all').click(function() {
            $('.rejudge_res_check').each(function() {
                this.checked = true;
            });
        });
        $('#check_res_non').click(function() {
            $('.rejudge_res_check').each(function() {
                this.checked = false;
            });
        });
        $('#check_res_rev').click(function() {
            $('.rejudge_res_check').each(function() {
                this.checked = !this.checked;
            });
        });
        $('#check_res_dft').click(function() {
            $('.rejudge_res_check').each(function() {
                this.checked = DEFAULT_REJUDGE_RES.has(this.value);
            });
        });
    });
    function SubmitRejudge(form) {
        $(form).ajaxSubmit({
            success: function(ret)
            {
                var submit_button = $('#submit_button');
                if(ret["code"] == 1)
                {
                    alertify.success(ret['msg']);
                    button_delay(submit_button, 3, 'Rejudge');
                    if($('#open_status_window_check').bootstrapSwitch('state') == true)
                        setTimeout(function(){window.open(ret['data']);}, 300);
                }
                else
                {
                    alertify.alert(ret['msg']);
                    button_delay(submit_button, 3, 'Rejudge');
                }
                return false;
            }
        });
    }
</script>