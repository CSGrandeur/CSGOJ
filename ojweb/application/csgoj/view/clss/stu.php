<h1>班级学生：{$clss['title']}</h1>
<div id="clss_stu_list_toolbar">
    <div class="form-inline">
        <div class="form-group">
            {if $isClssTeacher}
            <button id="clss_stu_batch_manage" class="btn btn-success">批量管理</button>
            {/if}
        </div>
    </div>
</div>
<table
        class="bootstraptable_refresh_local"
        id="stu_list_table"
        data-toggle="table"
        data-url="/{$module}/{$controller}/stu_list_ajax?clss_id={$clss['clss_id']}"
        data-side-pagination="client"
        data-method="get"
        data-search="true"
        data-sort-name="user_id"
        data-sort-order="asc"
        data-toolbar-align="left"
        data-toolbar="#clss_stu_list_toolbar"
        data-search-align="right"
>
    <thead>
    <tr>
        <th data-field="idx"        data-align="right" 	data-valign="middle"  data-width="30" 	data-formatter="FormatterIdx">Idx</th>
        <th data-field="user_id" 	data-align="left" 	data-valign="middle"  data-width="120"	data-sortable="true" data-formatter="FormatterUserId">学号</th>
        <th data-field="nick" 		data-align="left" 	data-valign="middle"  data-width="120"	data-sortable="true" data-formatter="FormatterName">姓名</th>
        <th data-field="school" 	data-align="left" 	data-valign="middle"  data-width="150"  data-sortable="true">行政班级</th>
        <th data-field="defunct" 	data-align="left" 	data-valign="middle"  data-width="150"  data-sortable="true" data-formatter="FormatterDuty" >权限</th>
        {if $isClssTeacher}
        <th data-field="del" 		data-align="center" data-valign="middle"  data-width="80"	data-formatter="FormatterDel">删除</th>
        {/if}
    </tr>
    </thead>
</table>

<!-- Modal -->
<div class="modal fade" id="clss_stu_modal" tabindex="-1" role="dialog" aria-labelledby="clss_stu_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><span id="clss_stu_modal_label_span">班级学生列表修改</span></h3>
                每行一个学号，如果设置助教，则在改行学号后“#”或制表符“\t”隔开填1
                &nbsp;<button type="button" class="close" aria-label="Close" id="stu_modal_close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="clss_stu_modal_content">
                <textarea id="stu_id_list_text" class="form-control" rows=20 placeholder="202000000000#1&#10;202066666666&#10;..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="clss_stu_modal_submit">确认</button>
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" id="clss_stu_modal_cancel">取消</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="page_info" clss_id="{$clss['clss_id']}" >
<script>
let clss_id = parseInt($('#page_info').attr("clss_id"));
let stu_list_table = $('#stu_list_table');
let clss_stu_modal = $('#clss_stu_modal');
let clss_stu_modal_submit = $('#clss_stu_modal_submit'), clss_stu_modal_cancel = $('#clss_stu_modal_cancel');
let clss_stu_modal_content = $('#clss_stu_modal_content');
let stu_id_list_text = $('#stu_id_list_text');



function FormatterUserId(value, row, index) {
    return "<a href='/csgoj/user/userinfo?user_id=" + row['user_id'] + "'>" + value + "</a>";
}
function FormatterName(value, row, index) {
    return value === null ? "[未录入或未登录激活]" : value;
}
function FormatterDuty(value, row, index) {
    let dv = parseInt(value);
    if(isNaN(dv)) {
        return '';
    }
    if(dv & 1) {
        return "<strong class='text-success'>助教</strong>";
    }
}
function FormatterDel(value, row, index) {
    return "<button class='btn btn-danger' user_id='" + row['user_id'] + "'>双击删除</button>";
}
$('#clss_stu_batch_manage').click(function() {
    let stu_id_list = [];
    stu_list_table.bootstrapTable('getData', {includeHiddenRows: true}).forEach((row) => {
        let duty = parseInt(row.defunct);
        stu_id_list.push(`${row.user_id}${isNaN(duty) || !(duty & 1) ? '' : "\t" + row.defunct}`);
    });
    stu_id_list_text.val(stu_id_list.join('\n'));
    clss_stu_modal.modal('show');
});
clss_stu_modal_submit.click(function() {
    let stu_id_str = stu_id_list_text.val().trim();
    let stu_info_list = stu_id_str.split('\n');
    let stu_add_list = [];
    if(stu_id_str == '') {
        stu_add_list = [];
    } else {
        for(let i = 0; i < stu_info_list.length; i ++) {
            stu_info_list[i] = stu_info_list[i].trim();
            let line_info = stu_info_list[i].split(/[#\t]/);
            let line_add = {}
            if(!(/^\w+$/.test(line_info[0])) || line_info[0].length < 3 || line_info[0].length > 32) {
                alertify.error("存在ID不合规范：" + stu_info_list[i]);
                return;
            }
            line_add['user_id'] = line_info[0].trim();
            if(line_info.length > 1) {
                let duty = parseInt(line_info[1])
                if(isNaN(duty) || duty < 1 || duty > 9) {
                    alertify.error("存在权限不合规范：" + stu_info_list[i]);
                    return;
                }
                line_add['defunct'] = duty;
            } else {
                line_add['defunct'] = 0;
            }
            stu_add_list.push(line_add)
        }
    }
    $.post('stu_add_ajax?clss_id=' + clss_id, {'stu_add_list': stu_add_list}, function(ret) {
        if(ret.code == 1) {
            alertify.success("更新成功");
            stu_list_table.bootstrapTable('refresh');
        } else {
            alertify.error(ret.msg);
        }
        clss_stu_modal.modal("hide");
    })
});
$("#stu_modal_close").click(function() {
    alertify.message("什么也没有发生");
    clss_stu_modal.modal("hide");
})
clss_stu_modal_cancel.click(function() {
    alertify.message("什么也没有发生");
    clss_stu_modal.modal("hide");
})
$(document).ready(function(){
    stu_list_table.on('dbl-click-cell.bs.table', function(e, field, value, row, $element){
        if(field == 'del') {
            $.get(
                'stu_del_ajax?clss_id=' + clss_id, 
                {'user_id': row.user_id},
                function(ret) {
                    if(ret.code == 1) {
                        alertify.success(ret['msg']);
                        stu_list_table.bootstrapTable('remove', {field: 'user_id', values: [row.user_id]});
                    }
                    else {
                        alertify.error(ret.msg);
                    }
                }
            );
        }
    });
});
</script>
{include file="../../csgoj/view/public/refresh_in_table" /}
