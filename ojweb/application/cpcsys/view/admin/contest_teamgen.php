<div class="page-header">
    <h1>Team Generator
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#teamgen_help_div" aria-expanded="false" aria-controls="navbar">
            Help
        </button>
    </h1>
</div>
<div class="container">
    <article id="teamgen_help_div" class="md_display_div alert alert-info collapse">
        <p>每行一个队伍，该队伍信息由制表符<code>\t</code>或半角字符<code>#</code>隔开。信息从左到右依次为：</p>
        <p>队号（纯数字）、队名、学校、队员、教练、房间（如果有的话）、队伍类型（0普通/1女队/2打星）、预设密码，例如：</p>
        <p><code>001#XX大学一队#XX大学#队员一、队员二、队员三#教练名#机房A#0#123456</code></p>
        <p>队号、密码、房间可为空，由系统自动生成，但“<code>\t</code>”或“<code>#</code>”分隔符必须有，信息是以第几个分隔符来对应的。</p>
        <p>比如 <code>##测试##333#</code>，即会自动生成一个这样的队伍：</p>
        <table class="md_display_div">
            <thead><tr><th>Team ID</th><th>Team Name</th><th>School</th><th>Member</th><th>Coach</th><th>Room</th><th>Team Kind</th><th>Password</th></tr></thead>
            <tbody>
            <tr><td>team0001</td><td>null</td><td>测试</td><td>null</td><td>333</td><td></td><td>0</td><td>V395JKQB</td></tr>
            </tbody>
        </table>
        <p>与报名系统导出的表格对应，可从excel表格复制对应的列直接粘贴在这里生成队伍。</p>
        <p><strong>如果只想预生成 n 个随机密码的“空”队伍，仅输入 <code>n seed(随机种子，可选)</code> 即可，例如 <code>100</code> 或 <code>100 1024</code></strong></p>
    </article>
    
    {if $contestStatus != 2}
    <form id="contest_gen_form" method='post' action="__CPC__/admin/contest_teamgen_ajax?cid={$contest['contest_id']}">
        <div class="form-group">
            <label for="reset_team">Regenerate All Teams：</label>
            <input type="checkbox" id="reset_team" name='reset_team' class="switch_ctr">
        </div>
        <div class="form-group">
            <label for="team_description">Team Description: </label>
            <textarea id="team_description" class="form-control" placeholder="Description..." rows="20" cols="50" name="team_description" ></textarea>
        </div>
        <br/>
        <button type="submit" id="submit_button" class="btn btn-primary">Generate!</button>
    </form>
    {else /}
    <h3 class='text-danger'>Contest ended, team generation not allowed.</h3>
    {/if}

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
					if(switch_name === 'reset_team') {
						alertify.alert('Be careful to clear all teams to regenerate!<br/>"Off" to directly add teams; "On" to clear before add.')
					}
                    switch_ctr.bootstrapSwitch('state', true, true);
                }
                else {
                    switch_ctr.bootstrapSwitch('state', false, true);
                }
            }
            switch_ctr.unbind('switchChange.bootstrapSwitch').on('switchChange.bootstrapSwitch', function(event, state) {
                if(state == true) {
					if($(this).attr('name') === 'reset_team') {
						alertify.alert('Be careful to clear all teams to regenerate!<br/>Off to directly add teams; On to clear all teams before add.')
					}
                    localStorage.setItem(switch_name, 'true');
                }
                else{
                    localStorage.setItem(switch_name, 'false');
                }
            });
        });
        $('#contest_gen_form').validate({
            rules:{
                team_description: {
                    maxlength: 65536
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
                                $('#teamgen_table').bootstrapTable('load', ret['data']['rows']);
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
//    $(window).keydown(function(e) {
//        if (e.keyCode == 83 && e.ctrlKey) {
//            e.preventDefault();
//            var a=document.createEvent("MouseEvents");
//            a.initEvent("click", true, true);
//            $('#submit_button')[0].dispatchEvent(a);
//        }
//    });
</script>

<table
    id="teamgen_table"
    data-toggle="table"
    data-buttons-align="left"
    data-sort-name="team_id"
    data-sort-order="asc"
    data-show-export="true"
    data-unique-id="team_id"
    data-url="__CPC__/admin/teamgen_list_ajax?cid={$contest['contest_id']}&ttype=0"
    data-pagination="false"
    data-method="get"
    data-export-types="['excel', 'xlsx', 'csv', 'json', 'png']"
    data-export-options='{"fileName": "Team_Generated"}'
>
    <thead>
    <tr>
        <th data-field="team_id" data-align="center" data-valign="middle" data-sortable="true" data-width="55">Team ID</th>
        <th data-field="name" data-align="left" data-valign="middle" data-width="160" >Team Name</th>
        <th data-field="school" data-align="center" data-valign="middle"  data-width="200">School</th>
        <th data-field="tmember" data-align="center" data-valign="middle" >Member</th>
        <th data-field="coach" data-align="center" data-valign="middle"  data-width="150">Coach</th>
        <th data-field="room" data-align="center" data-valign="middle"  >Room</th>
        <th data-field="tkind" data-align="center" data-valign="middle"  >Tkind</th>
        <th data-field="password" data-align="center" data-valign="middle"  data-width="60" >Password</th>
        <th data-field="delete" data-align="center" data-valign="middle"  data-width="60" data-formatter="FormatterDel">Del(Dbl Click)</th>
    </tr>
    </thead>
</table>
</div>
<input type="hidden" id="page_info" cid="{$contest['contest_id']}">

<script>
let page_info = $('#page_info');
let cid = page_info.attr('cid');
let teamgen_table = $('#teamgen_table');
let delete_infoed = false;
function FormatterDel(value, row, index, field) {
    return "<button class='btn btn-danger'>Delete</button>";
}
teamgen_table.on('click-cell.bs.table', function(e, field, td, row){
    if(field == 'delete') {
        if(!delete_infoed) {
            alertify.message("Double click to delete.")
            delete_infoed = true;
        }
        
    }
});
teamgen_table.on('dbl-click-cell.bs.table', function(e, field, td, row){
    if(field == 'delete') {
        $.post('team_del_ajax?cid=' + cid, {'team_id': row.team_id}, function(ret) {
            if(ret.code == 1) {
                teamgen_table.bootstrapTable('removeByUniqueId', row.team_id);
                alertify.success(ret.msg);
            } else {
                alertify.error(ret.msg);
            }
        });
    }
});
</script>
<style type="text/css">
    #teamgen_table
    {
        font-family: 'Simsun', 'Microsoft Yahei Mono', 'Lato', "PingFang SC", "Microsoft YaHei", sans-serif;
    }
</style>