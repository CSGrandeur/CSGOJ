<br/>
<form role="form" id="topic_add_form" action="/{$module}/{$contest_controller}/topic_add_ajax" method="POST">
    <div class="form-group">
        <label for="apid" >Problem ID:</label>
        <select id='topic-apid' name="apid" class="form-control" style="width:200px;" >
            <option value="-1">
                All
            </option>
            {foreach($abc2id as $k => $val) }
            <option value="{$k}">
                {$k}
            </option>
            {/foreach}
        </select>
        <label for="topic_title">Topic Title：</label>
        <input type="text" class="form-control" id="title" placeholder="1~64 characters" name="topic_title" style="max-width:900px;" >

        <label for="topic_content">Topic Content：</label>
        <textarea class="form-control" rows="20" name="topic_content" spellcheck="false" placeholder="^_^" style="max-width:900px;" ></textarea>
    </div>
    <input type="hidden" id="contest_id_input" class="form-control" name="cid" value="{$contest['contest_id']}" >
    <div class="form-group" id="fn-nav">
        <button type="submit" id='submit_button' class="btn btn-primary">Submit Topic</button>
    </div>
</form>

<script type="text/javascript">
    var submit_button = $('#submit_button');
    $(document).ready(function(){
        $('#topic_add_form').validate({
            rules:{
                topic_title: {
                    required: true,
                    minlength: 1,
                    maxlength: 64
                },
                topic_content: {
                    minlength: 3,
                    maxlength: 16384
                }
            },
            submitHandler: function(form)
            {
                submit_button.attr('disabled', true);
                $(form).ajaxSubmit(function(ret)
                {
                    if(ret["code"] == 1)
                    {
                        alertify.success(ret['msg']);
                        setTimeout(function(){location.href='/{$module}/{$contest_controller}/topic_detail?cid=' + ret['data']['contest_id'] + '&topic_id=' + ret['data']['topic_id'];}, 1000);
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