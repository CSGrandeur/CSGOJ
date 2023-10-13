{include file="problemset/problem_header" /}
<form role="form" id="problem_submit_form" action="__OJ__/{$controller}/submit_ajax" method="POST">
    <div class="form-inline">
        <label for="language" >Language:</label>
        <select id='submit_language_select' name="language" class="form-control" style="width:200px;" >
            {foreach($allowLanguage as $key=>$value)}
            <option value="{$key}">
                {$value}
            </option>
            {/foreach}
        </select>&nbsp;&nbsp;&nbsp;&nbsp;
<!--        <label for="pid" >Problem ID:</label>-->
        <input type="hidden" class="form-control" name="pid" value="{$problem['problem_id']}" style="width:200px;" >
    </div>
    <br/>
    <div class="form-group">
        <textarea class="form-control" rows="20" name="source" spellcheck="false" style="max-width:800px;" ></textarea>
    </div>
    <div class="form-group" id="fn-nav">
        {if(isset($contest)) }
        <input type="hidden" id="contest_id_input" class="form-control" name="cid" value="{$contest['contest_id']}" >
        {/if}
        <button type="submit" id='problem_submit_button' class="btn btn-primary">Submit</button>
    </div>
</form>

<script type="text/javascript">
    var cookiename;
    var cid;
    $(document).ready(function(){
        //cookie设置默认语言
        var contest_id_input = $('#contest_id_input');
        var submit_language_select = $('#submit_language_select');
        if(contest_id_input.length > 0) {
            cookiename = 'lastlanguage' + contest_id_input.val();
            cid = contest_id_input.val();
        }
        else {
            cookiename = 'global_lastlanguage';
            cid = -1;
        }
        if(localStorage.getItem(cookiename))
        {
            submit_language_select.val(localStorage.getItem(cookiename))
        }

        $('#problem_submit_form').validate({
            rules:{
                source: {
                    required: true,
                    minlength: 6,
                    maxlength: 65536
                }
            },
            submitHandler: function(form)
            {
                localStorage.setItem(cookiename, submit_language_select.val());
                var submit_button = $('#problem_submit_button');
                submit_button.attr('disabled', true);
                $(form).ajaxSubmit(function(ret)
                {
                    if(ret["code"] == 1)
                    {
                        alertify.success(ret['msg']);
                        if(cid == -1)
                            setTimeout(function(){location.href='__OJ__/status?user_id=' + ret['data']['user_id'];}, 1000);
                        else
                            setTimeout(function(){location.href='__OJ__/contest/status?cid=' + ret['data']['contest_id'] + '&user_id=' + ret['data']['user_id'];}, 1000);
                        return true;
                    }
                    else
                    {
                        alertify.alert(ret["msg"]);
                        button_delay(submit_button, 3, 'Submit');
                    }
                    return false;
                });
                return false;
            }
        });
    });
    $("textarea").on('keydown',function(e){
        if(e.keyCode == 9){
            e.preventDefault();
            var indent = '    ';
            var start = this.selectionStart;
            var end = this.selectionEnd;
            var selected = window.getSelection().toString();
            selected = indent + selected.replace(/\n/g,'\n'+indent);
            this.value = this.value.substring(0,start) + selected + this.value.substring(end);
            this.setSelectionRange(start+indent.length,start+selected.length);
        }
    })
</script>