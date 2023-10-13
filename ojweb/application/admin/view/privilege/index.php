<div class="container">
{include file="admin/select_control" /}
	<br/>
	<form id="privilege_add_form" method='post' action="__ADMIN__/privilege/privilege_add_ajax">
		<div class="form-inline">
			<label for="privilege[]">Add Privilege:</label>
			<select name="privilege[]" class="selectpicker" multiple="multiple">
				{foreach($allowAdmin as $adminStr => $adminName) }
				<option value="{$adminStr}">
					{$adminName}
				</option>
				{/foreach}
			</select>
			<label for="user_id">User ID:</label>     <input type="text" class="form-control" name="user_id"  style="width:200px;">
			<button type="submit" id="submit_button" class="btn btn-primary">Add Privilege</button>
		</div>
	</form>
	<table
			class="bootstraptable_refresh_local"
		id="privilege_edit_table"
		data-toggle="table"
		data-url="__ADMIN__/privilege/privilege_list_ajax"
		data-pagination="true"
		data-page-list="[15,50,100]"
		data-page-size="15"
		data-side-pagination="server"
		data-method="get"
		data-search="true"
		data-pagination-v-align="bottom"
		data-pagination-h-align="left"
		data-pagination-detail-h-align="right"
		data-search-align="center"
		data-sortable="false"
		data-unique-id="serial"
	>
		<thead>
		<tr>
			<th data-field="serial" data-align="center" data-valign="middle"  data-width="55">Serial</th>
			<th data-field="user_id" data-align="left" data-valign="middle"  data-width="80">User ID</th>
			<th data-field="rightstr" data-align="left" data-valign="middle"  data-width="80">Privilege</th>
			<th data-field="delete" data-align="left" data-valign="middle"  data-width="80">Delete</th>
		</tr>
		</thead>
	</table>
</div>
{include file="../../csgoj/view/public/refresh_in_table" /}
<script type="text/javascript">
	var table = $('#privilege_edit_table');
    
	function AddPrivilege(form, enforce=0) {
		$(form).ajaxSubmit({
            data: {
                'enforce': enforce
            },
			success: function(ret) {
                if (ret.code == 1) {
                    alertify.success(ret['msg']);
                    button_delay($('#submit_button'), 3, 'Add Privilege');
                    table.bootstrapTable('refresh');
                }
                else {
                    if(ret.data == 'nouser') {
                        alertify.confirm("No such user. Force to add?", 
                            function() {
                                AddPrivilege(form, 1);
                            },
                            function() {
                                alertify.message("Nothing Happend.");
                        });
                    } else {
                        alertify.error(ret.msg);
                    }                    
                }
                return false;
            }
        });
	}

	$(document).ready(function()
	{
		$('#privilege_add_form').validate({
			rules:{
				user_id:{
					required: true
				}
			},
			submitHandler: function(form) {
				AddPrivilege(form, 0);
				return false;
			}
		});
	});
	table.on('click-cell.bs.table', function(e, field, td, row) {
		if (field == 'delete') {
			if(td != '-')
			{
				$.post(
					'__ADMIN__/privilege/privilege_delete_ajax',
					{
						'user_id': $(row['user_id']).text(),
						'privilege[]': $(row['rightstr']).attr('rightstr'),
                        'enforce': 1
					},
					function (ret) {
						if (ret['code'] == 1) {
							alertify.success(ret['msg']);
							table.bootstrapTable('removeByUniqueId', row['serial']);
						}
						else {
							alertify.error(ret['msg']);
						}
						return false;
					}
				);
			}
		}
	});
</script>