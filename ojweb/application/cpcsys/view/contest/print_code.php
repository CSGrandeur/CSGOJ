<br/>
<form role="form" id="print_code_form" action="__CPC__/contest/print_code_ajax" method="POST">
	<div class="form-group">
		<textarea class="form-control" rows="25" name="source" spellcheck="false" placeholder="Save energy and protect the environment, don't waste paper." style="max-width:900px;" ></textarea>
	</div>
	<input type="hidden" id="contest_id_input" class="form-control" name="cid" value="{$contest['contest_id']}" >
	<div class="form-group" id="fn-nav">
		<button type="submit" id='submit_button' class="btn btn-primary">Submit Print Query</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$('#print_code_form').validate({
			rules:{
				source: {
					required: true,
					minlength: 6,
					maxlength: 16384
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
						setTimeout(function(){location.href='__CPC__/contest/print_status?cid=' + ret['data']['contest_id'] + '#team_id=' + ret['data']['team_id'];}, 1000);
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