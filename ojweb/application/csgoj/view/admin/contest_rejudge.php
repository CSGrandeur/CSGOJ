{include file="../../admin/view/problem/problem_rejudge_page" /}

<script type="text/javascript">
    $(document).ready(function(){
        $('#problem_rejudge_form').validate({
            rules:{
                problem_id: {
                    maxlength: 256
                },
                solution_id: {
                    maxlength: 256
                }
            },
            submitHandler: function(form)
            {
                var solution_id = $.trim($('#solution_id').val());
                var problem_id = $.trim($('#problem_id').val());

                if(solution_id.length > 0 && problem_id.length > 0)
                {
                    alertify.alert('Please clear one input either solution_id or problem_id');
                }
                else if(solution_id.length > 0)
                {
                    if(/^[0-9,]+$/.test(solution_id)) {
                    	SubmitRejudge(form);
					} else {
						alertify.alert('Solution ID style not valid.');
					}
                }
                else if(problem_id.length > 0)
                {
                    if(/^[A-Za-z,]+$/.test(problem_id)) {
                        alertify.confirm("Rejudge by problem_id may take a long time, sure to rejudge?",
                            function () {
                                SubmitRejudge(form);
                            },
                            function () {
                                return;
                            }
                        );
                    }
                    else
                    {
                        alertify.alert('Problem ID in contest should be in alphabet type.');
                    }
                }
                else
                {
                    alertify.confirm("Rejudge the whole contest may make the players unhappy, sure to rejudge?",
                        function(){
                            SubmitRejudge(form);
                        },
                        function(){
                            return;
                        }
                    );
                }
                return false;
            }
        });
    });
</script>