<div class="page-header">
    <h1>Staff Generator
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#staff_gen_help_div" aria-expanded="false" aria-controls="navbar">
            Help
        </button>
    </h1>
</div>
<div class="container">
    <article id="staff_gen_help_div" class="md_display_div alert alert-info collapse">
        <p>每行由“#”或“\t”隔开的账号、姓名、密码组成、权限。例如<code>admin01#郭大侠#123456#admin</code>。可用权限如下：</p>
        <li><code>admin</code>: 监考员，可管理部分比赛配置</li>
        <li><code>printer</code>: 打印管理员，负责打印机</li>
        <li><code>balloon_manager</code>: 气球管理员，建议只设置一名</li>
        <li><code>balloon_sender</code>: 气球配送员，可查看气球队列和领取气球任务</li>
    </article>
    <div>
    <div class="form-group">
        <label for="staff_description">Staff Description: </label>
        <textarea id="staff_description" class="form-control" placeholder="Description..." rows="8" cols="50" name="staff_description" ></textarea>
    </div>
    <br/>
    <button type="button" id="staff_submit_button" class="btn btn-primary" post_url="__CPC__/admin/contest_teamgen_ajax?cid={$contest['contest_id']}">Generate!</button>
</div>

<script type="text/javascript">

</script>

<table
    id="staff_gen_table"
    data-toggle="table"
    data-buttons-align="left"
    data-sort-name="team_id"
    data-sort-order="asc"
    data-show-export="true"
    data-unique-id="team_id"
    data-url="__CPC__/admin/teamgen_list_ajax?cid={$contest['contest_id']}&ttype=1"
    data-pagination="false"
    data-method="get"
    data-export-types="['csv', 'json', 'png']"
    data-export-options='{"fileName": "Team_Generated"}'
>
    <thead>
    <tr>
        <th data-field="team_id" data-align="center" data-valign="middle" data-sortable="true" data-width="55">User ID</th>
        <th data-field="name" data-align="left" data-valign="middle" >Name</th>
        <th data-field="password" data-align="center" data-valign="middle"  data-width="60" >Password</th>
        <th data-field="privilege" data-align="center" data-valign="middle"  data-width="60" >Privilege</th>
        <th data-field="delete" data-align="center" data-valign="middle"  data-width="60" data-formatter="FormatterDel">Del(Dbl Click)</th>
    </tr>
    </thead>
</table>
</div>
<input type="hidden" id="page_info" cid="{$contest['contest_id']}">
<script>
let page_info = $('#page_info');
let cid = page_info.attr('cid');
let staff_submit_button = $('#staff_submit_button');
let staff_description = $('#staff_description');
let span_use_system_pass = $('#span_use_system_pass');
let use_system_pass = $('#use_system_pass');
function FormatterDel(value, row, index, field) {
    return "<button class='btn btn-danger'>Delete</button>";
}
let staff_gen_table = $('#staff_gen_table');
let delete_infoed = false;
staff_gen_table.on('click-cell.bs.table', function(e, field, td, row){
    if(field == 'delete') {
        if(!delete_infoed) {
            alertify.message("Double click to delete.")
            delete_infoed = true;
        }
        
    }
});
staff_gen_table.on('dbl-click-cell.bs.table', function(e, field, td, row){
    if(field == 'delete') {
        $.post('team_del_ajax?cid=' + cid, {'team_id': row.team_id}, function(ret) {
            if(ret.code == 1) {
                staff_gen_table.bootstrapTable('removeByUniqueId', row.team_id);
                alertify.success(ret.msg);
            } else {
                alertify.error(ret.msg);
            }
        });
    }
});
const staff_name = new Set(['admin', 'printer', 'balloon_manager', 'balloon_sender']);
$(document).ready(function() { 
    staff_submit_button.click(function(){
        let rvs = staff_description.val();
        let review_list = $.trim(rvs).split('\n');
        let info_submit = "";
        function D(ith, lst) {
            return (ith in lst) && typeof(lst[ith]) == 'string' ? lst[ith].trim() : '';
        }
        for(let i in review_list) {
            let line = review_list[i];
            let info_list = line.split(/[#\t]/);
            if(info_list.length < 4) {
                alertify.error(`${line} 内容不完整.`);
                return;
            }
            if(info_list[0].trim().length < 5) {
                alertify.error(`${line} 账号至少5个字符.`);
                return;
            }
            // if(info_list[2].trim().length < 6) {
            //     alertify.error(`${line} 密码至少6个字符.`);
            //     return;
            // }
            if(!staff_name.has(info_list[3].trim())) {
                alertify.error(`${line} 权限不在可选范围内.`);
                return;
            }
            info_submit += `${D(0, info_list)}#${D(1, info_list)}######${D(2, info_list)}##${D(3, info_list)}\n`;
        }
        $.post(staff_submit_button.attr("post_url"), {'team_description': info_submit, 'staff': 1}, function(ret){
            if(ret.code == 1) {                
                button_delay(staff_submit_button, 5, 'Generate!');
                staff_gen_table.bootstrapTable('load', ret.data.rows);
                alertify.success(ret.msg);
            } else {
                alertify.alert(ret.msg);
            }
        });
    });
});
</script>
<style type="text/css">
    #staff_gen_table {
        font-family: 'Simsun', 'Microsoft Yahei Mono', 'Lato', "PingFang SC", "Microsoft YaHei", sans-serif;
    }
</style>