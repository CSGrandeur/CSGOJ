
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
			submitHandler: function(form)
			{
				var solution_id = $.trim($('#solution_id').val());
				var contest_id = $.trim($('#contest_id').val());
				var problem_id = $.trim($('#problem_id').val());
				if((solution_id.length > 0) + (problem_id.length > 0) + (contest_id.length > 0) > 1)
				{
					alertify.alert('Please only fill in one input');
				}
				else if(solution_id.length > 0)
				{
					SubmitRejudge(form);
				}
				else if(contest_id.length > 0)
				{
					alertify.confirm("Rejudge by contest_id may make the players unhappy, sure to rejudge?",
						function(){
							SubmitRejudge(form);
						},
						function(){
							return;
						}
					);
				}
				else if(problem_id.length > 0)
				{
					alertify.confirm("Rejudge by problem_id may take a long time, sure to rejudge?",
						function(){
							SubmitRejudge(form);
						},
						function(){
							return;
						}
					);
				}
				else
				{
					alertify.alert('Please give an ID for rejudging.')
				}
				return false;
			}
		});
	});
</script>