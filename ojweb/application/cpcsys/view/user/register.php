<div class="container">
<div class="page-header">
	<h1>Registration</h1>
</div>
<div>
	<form id="register_form" class="form-register" method="post" action="__OJ__/user/register_ajax">
		<div class="form-inline">
			<label class="description_label">User ID* ：</label>
			<input type="text" class="form-control" placeholder="5~20 characters" name="user_id" required autofocus>
			<label for="user_id" class="notification_label" class="notification_label"></label>
		</div>
		<div class="form-inline">
			<label class="description_label">Nick ：</label>
			<input type="text" class="form-control" placeholder="no more than 30 characters" name="nick" >
			<label for="nick" class="notification_label"></label>
		</div>
		<div class="form-inline">
			<label class="description_label">School ：</label>
			<input type="text" class="form-control" placeholder="School" name="school" >
			<label for="school" class="notification_label"></label>
		</div>
		<div class="form-inline">
			<label class="description_label">E-Mail* ：</label>
			<input type="text" class="form-control" placeholder="E-Mail" name="email" required>
			<label for="email" class="notification_label"></label>
		</div>
		<div class="form-inline">
			<label class="description_label">Password* ：</label>
			<input type="password" id="register_password" class="form-control" placeholder="at least 6 characters" name="password" required>
			<label for="password" class="notification_label"></label>
		</div>
		<div class="form-inline">
			<label class="description_label">Confirm * ：</label>
			<input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" required>
			<label for="confirm_password" class="notification_label"></label>
		</div>
		<div class="form-inline">
			<label class="description_label">V-Code* ：</label>
			<input type="text" class="form-control" placeholder="Verification Code" name="vcode" required>
			<label for="vcode" class="notification_label"></label>
		</div>
		<button class="btn btn-primary" id="submit_button" type="submit">Submit</button>
		<label id="vcode">{:captcha_img()}</label>
	</form>
</div>
</div>
<style type="text/css">
	#register_form{
		/*width: 500px;*/
	}
	#register_form .description_label{
		width: 100px;
		text-align: right;
	}
	#register_form .notification_label{
		width: 300px;
		text-align: left;
	}
	#register_form input{
		width: 360px;
	}
	#register_form > div{
		margin-top: 10px;
	}
	#register_form > button {
		width: 150px;
		margin-left: 105px;
		margin-top: 20px;
	}
</style>
<script type="text/javascript">
	$('#vcode').on('click', function(){
		var ts = Date.parse(new Date())/1000;
		this.getElementsByTagName('img')[0].src = "/captcha?id="+ts;
	});
	$(document).ready(function(){
		$('#register_form').validate({
			rules:{
				user_id: {
					required: true,
					minlength: 5,
					maxlength: 20,
					user_id_validate: true
				},
				password: {
					required: true,
					minlength: 6,
					maxlength: 64
				},
				confirm_password: {
					required: true,
					minlength: 6,
					maxlength: 255,
					equalTo: "#register_password"
				},
				school: {
					maxlength: 20
				},
				nick: {
					maxlength: 30
				},
				email: {
					required: true,
					email: true
				}
			},
			submitHandler: function(form)
			{
				$('#submit_button').attr('disabled', true);
				$(form).ajaxSubmit(
				{
					success: function(ret)
					{
						if(ret['code'] == 1)
						{
							alertify.alert(ret['msg'], function(){
								location.href = '__OJ__/user/userinfo?user_id=' + ret['data']['user_id'];
							});
						}
						else
						{
							button_delay($('#submit_button'), 3, 'Submit');
							alertify.alert(ret['msg']);
							var ts = Date.parse(new Date())/1000;
							$('#vcode').find('img').attr('src', "/captcha?id="+ts);
						}
						return false;
					}
				});
				return false;
			}
		});

	});
</script>