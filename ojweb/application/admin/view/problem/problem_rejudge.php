
{include file="problem/problem_rejudge_page" /}

<script type="text/javascript">
	$(document).ready(function(){
		$('#problem_rejudge_form').validate({
			rules:{
				contest_id: {
					number: true,
					digits: true,
					maxlength: 5
				},
				problem_id: {
					number: true,
					digits: true,
					maxlength: 5
				},
				solution_id: {
					number: true,
					digits: true,
					maxlength: 10
				}
			},
			submitHandler: function(form) {
				var solution_id = $.trim($('#solution_id').val());
				var contest_id = $.trim($('#contest_id').val());
				var problem_id = $.trim($('#problem_id').val());
                let ac_alertify = "";
                if(document.querySelector('#rejudge_res_check_ac').checked) {
                    ac_alertify = "<strong class='text-danger'>请慎重重判AC的提交</strong><br/>"
                }
				if((solution_id.length > 0) + (problem_id.length > 0) + (contest_id.length > 0) > 1) {
					alertify.alert('请在题号和提交号之间选择一项.<br/>Please only fill in one input');
				}
				else if(solution_id.length > 0){
					if(ac_alertify != '') {
						alertify.confirm(ac_alertify, function() {
							SubmitRejudge(form);
						});
					} else {
						SubmitRejudge(form);
					}
				}
				else if(problem_id.length > 0)
				{
					alertify.confirm(`${ac_alertify}基于题号评测时间较久，确认？<br/>Rejudge by problem_id may take a long time, sure to rejudge?`,
						function(){
							SubmitRejudge(form);
						},
						function(){
							return;
						}
					).set('title', 'confirm');
				}
				else
				{
					alertify.alert('请提供题目ID.<br/>Please give an ID for rejudging.')
				}
				return false;
			}
		});
	});
</script>