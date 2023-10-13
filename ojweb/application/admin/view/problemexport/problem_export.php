<div class="page-header">
	<h1>Problem Export
		<a href="__ADMIN__/problemexport/problem_export_filemanager?item=problemexport" target="_blank">
			<button class="btn btn-success" id="attachfile">Exported Problem Files Manager</button>
		</a>
	</h1>
</div>
<div class="container">
	<form id="problem_export_form" method='post' action="__ADMIN__/{$controller}/problem_export_ajax?item=problemexport">
		<div class="form-group">
			<label for="attach_file_check">With Attached Files (Mainly Images)：</label><br/>
			<input type="checkbox" id="attach_file_check" name="attach_file_check">
		</div>
		<div class="form-group">
			<label for="test_data_check">With Test Data：</label><br/>
			<input type="checkbox" id="test_data_check" name="test_data_check">
		</div>
		<div class="form-group">
			<label for="start_pid">Type 1: Start Problem ID：</label>
			<input type="text" class="form-control" id="start_pid" placeholder="Start..." name="start_pid" style="max-width:400px;">
			<br/>
			<label for="end_pid">Type 1: End Problem ID (Leave Empty to Export Single Problem)：</label>
			<input type="text" class="form-control" id="end_pid" placeholder="End Inclusive..." name="end_pid" style="max-width:400px;">
            <br/>
            <label for="pid_list">Type 2: Or Export a List of <span class="text-red">Problem IDs：</span></label>
            <input type="text" class="form-control" id="pid_list" placeholder="Problem IDs Separately" name="pid_list" style="max-width:400px;">
            <br/>
            <label for="ex_cid">Type 3: Or Export Problems <span class="text-red">in a Contest</span>：</label>
            <input type="text" class="form-control" id="ex_cid" placeholder="A Contest ID" name="ex_cid" style="max-width:400px;">
		</div>

		<button type="submit" id="submit_button" class="btn btn-primary">Export</button>
	</form>
</div>
<script type="text/javascript">
	var attach_file_check = $('#attach_file_check');
	var test_data_check = $('#test_data_check');
	var submit_button = $('#submit_button');

	$(document).ready(function()
	{
		attach_file_check.bootstrapSwitch();
		test_data_check.bootstrapSwitch();
		$('#problem_export_form').validate({
			rules:{
				start_pid:{
					number:true,
					min: 1000
				},
				end_pid: {
					number:true
				}
			},
			submitHandler: function(form)
			{
				var start_pid = Number($('#start_pid').val()),  end_pid = Number($('#end_pid').val());
				var pid_list = $('#pid_list').val().trim(), ex_cid = $('#ex_cid').val().trim();
				if(end_pid === 0) end_pid = start_pid;
				if(start_pid !== 0)
                {
                    if(!/^\d{1,10}$/.test(start_pid))
                        alertify.alert("Problem ID should be a limited Positive integer!");
                    else
                    {
                        if(end_pid - start_pid > 30)
                        {
                            //和下面那个条件重复，暂时大于30请求都拦截。
                            alertify.alert("You'd better not export so many problems once (more than 20).");
                        }
                        else if(end_pid - start_pid > 30 && (test_data_check.bootstrapSwitch('state') === true || attach_file_check.bootstrapSwitch('state') === true))
                        {
                            alertify.alert("Together with related files, you cannot export so many problems.");
                        }
                        else if(end_pid < start_pid)
                        {
                            alertify.alert("End problem ID should be bigger than start problem ID.");
                        }
                        else
                        {
                            SubmitExport(form);
                        }
                    }
                }
				else if(pid_list !== '')
                {
                    if(!/^\d{1,10}(,\d{1,10}){0,20}$/.test(pid_list))
                        alertify.alert("Problem IDs should be separated by ',' and be limited Positive Integers.");
                    else
                        SubmitExport(form);
                }
				else if(ex_cid !== '')
                {
                    if(!/^\d{1,10}$/.test(ex_cid))
                        alertify.alert("Should submit only one contest ID and should be a limited Positive Integer.");
                    else
                        SubmitExport(form);
                }
				else
                {
                    alertify.alert("Need to input one of the three types.");
                }
				return false;
			}
		});
	});
	function SubmitExport(form)
	{
		if(attach_file_check.bootstrapSwitch('state') == true || test_data_check.bootstrapSwitch('state') == true)
		{
			alertify.confirm("Problem export may take a long time."+
				"Please make sure that all the data would be not too large (perhaps 200MB around is acceptable)<br/>" +
				"The process would stop if execute time exceed <strong class='text-danger'>180s</strong>.<br/>" +
				"In this case, you can export problem <strong class='text-danger'>WITHOUT test datas</strong> and upload them to the other OJ separately<br/>" +
				"Are you ready?",
				function(){
					DoExport(form);
				},
				function(){
					alertify.message("Canceled");
				}
			);

			return false;
		}
		else
		{
			DoExport(form);
		}
		return false;
	}
	function DoExport(form)
	{
		submit_button.attr('disabled', true);
		var button_text = submit_button.text();
		submit_button.text('Running...');
		$(form).ajaxSubmit({
			success: function(ret)
			{
				if(ret["code"] == 1)
				{
					alertify.alert(ret['msg'], function(){
						var tmpInterval = setInterval(function(){
							$("#attachfile").fadeOut(100).fadeIn(100);
						},200);
						setTimeout(function(){clearInterval(tmpInterval);$("#attachfile").fadeIn(); }, 1000);
					});
				}
				else
				{
					alertify.alert(ret['msg']);
				}
				button_delay(submit_button, 3, 'Export');
				return false;
			}
		});
	}
</script>