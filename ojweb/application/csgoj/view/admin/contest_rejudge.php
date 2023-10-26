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
                let ac_alertify = "";
                if(document.querySelector('#rejudge_res_check_ac').checked) {
                    ac_alertify = "<strong class='text-danger'>请慎重重判AC的提交，确认？</strong><br/>"
                }

                if(solution_id.length > 0 && problem_id.length > 0) {
                    alertify.alert(`在提交号与题号中只选择其中一项填写<br/>Please clear one input either solution_id or problem_id`);
                } else if(solution_id.length > 0) {
                    if(/^[0-9,]+$/.test(solution_id)) {
                        if(ac_alertify != '') {
                            alertify.confirm(ac_alertify, function() {
                                SubmitRejudge(form);
                            });
                        } else {
                            SubmitRejudge(form);
                        }
					} else {
						alertify.alert(`提交号格式不正确<br/>Solution ID style not valid.`);
					}
                } else if(problem_id.length > 0) {
                    if(/^[A-Za-z,]+$/.test(problem_id)) {
                        alertify.confirm(`${ac_alertify}基于题号评测时间较久，确认？<br/>Rejudge by problem_id may take a long time, sure to rejudge?`,
                            function () {
                                SubmitRejudge(form);
                            },
                            function () {
                                return;
                            }
                        );
                    } else {
                        alertify.alert(`比赛中请使用字母题号<br/>Problem ID in contest should be in alphabet type.`);
                    }
                } else {
                    alertify.confirm(`${ac_alertify}<strong class='text-danger'>未填写题号与提交号，将重判整个比赛所有题目，确认？</strong><br/>Rejudge the whole contest may make the players unhappy, sure to rejudge?`,
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