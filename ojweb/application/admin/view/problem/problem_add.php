<div class="page-header">
    <h1>Add Problem</h1>
</div>
<div class="container">
    <form id="problem_add_form" method='post' action="__ADMIN__/problem/problem_add_ajax">
        <div class="form-group">
            <label for="title">Problem Title：</label>
            <input type="text" class="form-control" id="title" placeholder="Problem Title..." name="title" >
            <br/>
            <label for="time_limit">Time Limit(S)：</label>
            <input type="text" class="form-control" id="title" placeholder="Time Limit..." name="time_limit" value="1">
            <br/>
            <label for="memory_limit">Memory Limit(MByte)：</label>
            <input type="text" class="form-control" id="title" placeholder="Memory Limit..." name="memory_limit" value="128">
            <br/>
        </div>

        <label for="description">Description (markdown)：</label>
        <textarea id="problem_description" class="form-control" placeholder="Description..." rows="10" cols="50" name="description" ></textarea>
        <br/>

        <label for="input">Input Description (markdown)：</label>
        <textarea id="problem_input" class="form-control" placeholder="Input description..." rows="4" cols="50" name="input" ></textarea>
        <br/>

        <label for="output">Output Description (markdown)：</label>
        <textarea id="problem_output" class="form-control" placeholder="Output description..." rows="4" cols="50" name="output" ></textarea>
        <br/>

        <label for="sample_input">Sample Input：</label>
        <textarea class="form-control" placeholder="" rows="8" cols="50" name="sample_input" ></textarea>
        <br/>

        <label for="sample_output">Sample Output：</label>
        <textarea class="form-control" placeholder="" rows="8" cols="50" name="sample_output" ></textarea>
        <br/>

        <label for="hint">Hint (markdown)：</label>
        <textarea class="form-control" placeholder="Hint ..." rows="2" cols="50" name="hint" ></textarea>
        <br/>

        <label for="source">Source (markdown)：</label>
        <input class="form-control" placeholder="Source ..."  name="source" >
        <br/>
        <label for="author">Author：</label>
        <input class="form-control" placeholder="Author ..."  name="author" >
        <br/>

        <div class="checkbox">
            <label>
                <input type="checkbox" id="specialjudge_check" kind_active="1" name="spj"  value="true">
                <span class="text-red">SpecialJudge</span>
            </label>
        </div>
        <button type="submit" id="submit_button" class="btn btn-primary">Add Problem</button>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        var submit_button = $('#submit_button');
        $('#problem_add_form').validate({
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
                    maxlength: 16384
                },
                sample_output: {
                    required: true,
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
            submitHandler: function(form)
            {
                submit_button.attr('disabled', true);
                submit_button.text('Waiting...');
                $(form).ajaxSubmit(
                    {
                        success: function(ret)
                        {
                            if(ret["code"] == 1)
                            {
                                alertify.success(ret['msg']);
                                button_delay(submit_button, 5, 'Submit');
                                setTimeout(function(){location.href='problem_edit?id='+ret['data']['problem_id']}, 500);
                            }
                            else
                            {
                                alertify.error(ret['msg']);
                                button_delay(submit_button, 3, 'Submit');
                            }
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