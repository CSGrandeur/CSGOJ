<br/>
<form role="form" id="submit_form" action="/{$module}/{$contest_controller}/submit_ajax" method="POST">
    <div class="form-inline">
        <label for="language" >Language:</label>
        <select id='submit-language' name="language" class="form-control" style="width:200px;" >
            {foreach($allowLanguage as $k=>$la) }
            <option value="{$k}">
                {$la}
            </option>
            {/foreach}
        </select>&nbsp;&nbsp;&nbsp;&nbsp;
        <label for="pid" >Problem ID:</label>
        <input type="text" class="form-control" name="pid" value="{$apid}" style="width:200px;" >
    </div>
    <br/>
    <div class="form-group">
        <textarea class="form-control" rows="20" name="source" spellcheck="false" style="max-width:800px;" ></textarea>
    </div>
    <input type="hidden" id="contest_id_input" class="form-control" name="cid" value="{$contest['contest_id']}" >
    <div class="form-group" id="fn-nav">
        <button type="submit" id='submit_button' class="btn btn-primary">Submit</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        //cookie设置默认语言
        if(localStorage.getItem('lastlanguage' + $('#contest_id_input').val()))
        {
            $('#submit-language').val(localStorage.getItem('lastlanguage' + $('#contest_id_input').val()))
        }


        $('#submit_form').validate({
            rules:{
                source: {
                    required: true,
                    minlength: 6,
                    maxlength: 65536
                }
            },
            submitHandler: function(form)
            {
                localStorage.setItem('lastlanguage' + $('#contest_id_input').val(), $('#submit-language').val());

                var submit_button = $('#submit_button');
                submit_button.attr('disabled', true);
                $(form).ajaxSubmit(function(ret)
                {
                    if(ret["code"] == 1)
                    {
                        alertify.success(ret['msg']);
                        setTimeout(function(){location.href='/{$module}/{$contest_controller}/status?cid=' + ret['data']['contest_id'] + '&user_id=' + ret['data']['user_id'];}, 1000);
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
</script>