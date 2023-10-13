<div class="page-header">
    <h1>Update User's Information</h1>
</div>
{if $OJ_SSO != false } 
在SSO主系统修改个人信息，<a href="{$OJ_SSO}" target="_blank">点击访问</a><br/>
Modify your information in the<a href="{$OJ_SSO}" target="_blank"> main system</a> 
{else /}
<div class="container marketing">
    <form id="modify_form" class="form-modify" method="post" action="__OJ__/user/modify_ajax">
        <div class="form-inline">
            <label class="description_label">User ID ：</label>
            <input type="text" class="form-control" placeholder="User ID" name="user_id" value="{$baseinfo['user_id']}" readonly>
            <label for="user_id" class="notification_label"></label>
        </div>
        {if $OJ_STATUS != 'exp'}
        <div class="form-inline">
            <label class="description_label">Nick ：</label>
            <input type="text" class="form-control" name="nick" placeholder="No more than 30 characters" value="{$baseinfo['nick']}">
            <label for="nick" class="notification_label"></label>
        </div>
        <div class="form-inline">
            <label class="description_label">School ：</label>
            <input type="text" class="form-control" name="school" placeholder="School" value="{$baseinfo['school']}">
            <label for="school" class="notification_label"></label>
        </div>
        {/if}
        <div class="form-inline">
            <label class="description_label">*E-Mail ：</label>
            <input type="email" class="form-control" name="email" placeholder="E-Mail" value="{$baseinfo['email']}" required>
            <label for="email" class="notification_label"></label>
        </div>
        {if !$is_password_setter || $is_self }
        <div class="form-inline">
            <label class="description_label">*Verify Password ：</label>
            <input type="password" id="modify_verify_password" class="form-control" placeholder="At least 6 characters" name="password" required>
            <label for="password" class="notification_label"></label>
        </div>
        {/if}
        <div class="form-inline">
            <label class="description_label">New Password ：</label>
            <input type="password" id="modify_new_password" class="form-control" placeholder="Let it blank or at least 6 characters" name="new_password" >
            <label for="password" class="notification_label"></label>
        </div>
        <div class="form-inline">
            <label class="description_label">Confirm  ：</label>
            <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_new_password" >
            <label for="confirm_password" class="notification_label"></label>
        </div>
        {if !$is_password_setter }
        <div class="form-inline">
            <label class="description_label">*V-Code ：</label>
            <input type="text" class="form-control" placeholder="Verification Code" name="vcode" required>
            <label for="vcode" class="notification_label"></label>
        </div>
        {/if}
        <button class="btn btn-primary" id="submit_button" type="submit">Submit</button>
        {if !$is_password_setter }
        <label id="vcode">{:captcha_img()}</label>
        {/if}
    </form>
</div>

<style type="text/css">
    #modify_form{
        /*width: 500px;*/
    }
    #modify_form .description_label{
        width: 150px;
        text-align: right;
    }
    #modify_form .notification_label{
        width: 300px;
        text-align: left;
    }
    #modify_form input{
        width: 360px;
    }
    #modify_form > div{
        margin-top: 10px;
    }
    #modify_form > button {
        margin-left: 155px;
        margin-top: 20px;
    }
</style>
<script type="text/javascript">
    $('#vcode').on('click', function(){
        var ts = Date.parse(new Date())/1000;
        this.getElementsByTagName('img')[0].src = "/captcha?id="+ts;
    });
    $(document).ready(function(){
        $('#modify_form').validate({
            rules:{
                user_id: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 64
                },
                school: {
                    maxlength: 20
                },
                nick: {
                    maxlength: 32
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                new_password:{
                    minlength: 6,
                    maxlength: 64
                },
                confirm_new_password:{
                    equalTo: "#modify_new_password"
                },
                vcode: {
                    required: true
                }
            },
            submitHandler: function(form)
            {
                $('#submit_button').attr('disabled', true);
                $(form).ajaxSubmit(
                {
                    success: function(ret)
                    {
                        if(ret["code"] == 1)
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
{/if}