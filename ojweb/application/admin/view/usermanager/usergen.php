<div class="page-header">
    <h1>User Generator
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#usergen_help_div" aria-expanded="false" aria-controls="navbar">
            Help
        </button>
</div>
<div class="container">
    <article id="usergen_help_div" class="md_display_div alert alert-info collapse">
        每行一个用户，信息由制表符<code>\t</code>或半角字符<code>#</code>隔开。信息从左到右依次为：<br/>
        <span class="text-info">User ID（比如学号）、姓名、学校（或学院/班级）、邮箱、预设密码</span>，例如：<br/>
        <code>202200000000#郭大侠#大数据与互联网学院#csgrandeur@qq.com#123456</code><br/>
        除 User ID 外，其它信息可为空，但“<code>\t</code>”或“<code>#</code>”分隔符必须有，信息是以第几个分隔符来对应的。<br/>
        比如 <code>202200000000##测试#qq@qq.com</code>，即会生成一个类似这样的账号：
        <table class="md_display_div">
            <thead><tr><th>User ID</th><th>Name</th><th>School</th><th>Email</th><th>Password</th></tr></thead>
            <tbody>
            <tr><td>202200000000</td><td>null</td><td>测试</td><td>qq@qq.com</td><td>V395JKQB</td></tr>
            </tbody>
        </table>
    </article>
    <form id="usergen_form" method='post' action="__ADMIN__/usermanager/usergen_ajax">
        <div class="form-group">
            <label for="user_description">User Description: </label>
            <textarea id="user_description" class="form-control" placeholder="Description..." rows="20" cols="50" name="user_description" ></textarea>
        </div>
        <br/>
        <button type="submit" id="submit_button" class="btn btn-primary">Generate!</button>
    </form>

<script type="text/javascript">
    $(document).ready(function()
    {
        $('#usergen_form').validate({
            rules:{
                user_description: {
                    required: true,
                    maxlength: 16384
                }
            },
            submitHandler: function(form)
            {
                $(form).ajaxSubmit(
                    {
                        success: function(ret)
                        {
                            var submit_button = $('#submit_button');
                            if(ret["code"] == 1)
                            {
                                alertify.alert(ret['msg']);
                                button_delay(submit_button, 5, 'Submit');
                                $('#usergen_table').bootstrapTable('load', ret['data']['rows']);
                            }
                            else
                            {
                                alertify.alert(ret['msg']);
                                button_delay(submit_button, 3, 'Submit');
                            }
                            return false;
                        }
                    });
                return false;
            }
        });
    });
</script>

<table
    id="usergen_table"
    data-toggle="table"
    data-buttons-align="left"
    data-sort-name="user_id"
    data-sort-order="asc"
    data-show-export="true"
    data-pagination="false"
    data-method="get"
    data-export-types="['excel', 'csv', 'json', 'png']"
    data-export-options='{"fileName": "User_Generated"}'
>
    <thead>
    <tr>
        <th data-field="idx" data-align="center" data-valign="middle" data-sortable="true" data-width="55" 	data-formatter="AutoId">Idx</th>
        <th data-field="user_id" data-align="center" data-valign="middle" data-sortable="true" data-width="55">User ID</th>
        <th data-field="nick" data-align="left" data-valign="middle" data-width="160" >User Name</th>
        <th data-field="school" data-align="center" data-valign="middle"  data-width="200">School</th>
        <th data-field="email" data-align="center" data-valign="middle" >Email</th>
        <th data-field="password" data-align="center" data-valign="middle"  data-width="60" >Password</th>
    </tr>
    </thead>
</table>
</div>
<script>
function AutoId(value, row, index) {
    return index + 1;
}
</script>
<style type="text/css">
    #usergen_table
    {
        font-family: 'Simsun', 'Microsoft Yahei Mono', 'Lato', "PingFang SC", "Microsoft YaHei", sans-serif;
    }
</style>