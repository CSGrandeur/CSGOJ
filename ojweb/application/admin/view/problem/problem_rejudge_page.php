<div class="page-header">
    <h1>{$rejudge_type|ucfirst} {if $rejudge_type == 'contest'}<a href="__OJ__/contest/contest?cid={$cid}">{$cid}</a> {/if} Rejudge</h1>
</div>
<div class="container">

    {if($rejudge_type != 'contest')}
    <span class="alert alert-info" style="display:block;">Solutions in contests will be <strong>ignored</strong>. If you want to rejudge problems in a contest, use <a href="__ADMIN__/contest/contest_rejudge"><strong>contest rejudge</strong></a>.</span>
    {/if}
    <div class="form-group">
        <label for="open_status_window_check">Open Status Window After Rejudge Sent：</label>
        <input type="checkbox" id="open_status_window_check" name='open_status_window_check' class="switch_ctr">
    </div>
    <form id="problem_rejudge_form" method='post' action="{$submit_url}">
        <div class="form-group">
            <label for="all_rejudge_check">Rejudge All Including Accepted: </label>
            <input type="checkbox" id="all_rejudge_check" name='all_rejudge_check' class="switch_ctr" >
        </div>
        <div class="form-group">
            <label for="solution_id">By Solution ID：</label>
            <input type="text" class="form-control" id="solution_id" placeholder="Solution ID..." name="solution_id" style="max-width:400px;">
            <br/>
            <label for="problem_id">By Problem ID：</label>
            <input type="text" class="form-control" id="problem_id" placeholder="{if $controller=='problem'}Numerate ID like 2000,2001...{else/}Alphabet ID like A,B,C...{/if}" name="problem_id" style="max-width:400px;">
            <br/>
        </div>

        <button type="submit" id="submit_button" class="btn btn-primary">Rejudge</button>
        <button type="reset" id="submit_button" class="btn btn-warning">Reset Form</button>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
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
    });
    function SubmitRejudge(form)
    {
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