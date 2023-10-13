<form id="passback_form" class="form-signin" method="post" action="__OJ__/user/passback_ajax">
	<h2 class="form-signin-heading">Password Retrieve</h2>
	<label for="passback_user_id">User ID：</label>
	<input type="text" id="passback_user_id" name="passback_user_id" class="form-control" placeholder="User ID" required autofocus>
	<br/>
	<button class="btn btn-lg btn-primary btn-block" id="passback_submit_button" type="Submit">Submit</button>
</form>
<script type="text/javascript">
	$(document).ready(function(){

		$('#passback_form').validate({
			rules:{
				passback_user_id: {
					required: true,
					minlength: 6,
					maxlength: 100,
					user_id: true
				}
			},
			submitHandler: function(form)
			{
				$('#passback_submit_button').text("Submitting...");
				$('#passback_submit_button').attr('disabled', 'disabled');
				$(form).ajaxSubmit(function(data)
				{
					if(data["wrongcode"] == 'totally_right')
					{
						$('#passback_submit_button').attr('disabled', true);
						alertify.confirm("Email sent. Please check your mailbox for password retrieving!", function(e)
						{
							if(e)
							{
								location.href = '__OJ__';
							}
						});
					}
					else
					{
						alertify.alert( data['wrongcode']);
					}
					$('#passback_submit_button').removeAttr('disabled');
					$('#passback_submit_button').text("Submit");
					return false;
				});
				return false;
			}
		});
	});
</script>
<style type="text/css">
	body {
		padding-top:80px;
		padding-bottom:40px;
		background-color:#eee;
	}

	.form-signin {
		max-width:330px;
		margin:0 auto;
		padding:15px;
	}

	.form-signin .form-signin-heading,.form-signin .checkbox {
		margin-bottom:10px;
	}

	.form-signin .checkbox {
		font-weight:400;
	}

	.form-signin .form-control {
		position:relative;
		height:auto;
		-webkit-box-sizing:border-box;
		-moz-box-sizing:border-box;
		box-sizing:border-box;
		font-size:16px;
		padding:10px;
	}

	.form-signin .form-control:focus {
		z-index:2;
	}

</style>

