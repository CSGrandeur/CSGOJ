<div class="login_div">
    <?php 
        if($OJ_SSO == false) {
            if($OJ_MODE == 'online') {
                $login_url = "/csgoj/user/login_ajax";
            } else if($OJ_STATUS == 'cpc') {
                $login_url = "/cpcsys/user/login_ajax";
            } else {
                $login_url = "/expsys/user/login_ajax";
            }
        } else {
            $login_url = "/ojtool/sso/sso_direct_ajax";
        }
    ?>
    
    {if $OJ_SSO != false } 
        <div style="padding-top: 5px; width: 100%;"><a href="/ojtool/sso/sso_start"><button class='btn btn-success' style="width:100%;">登录</button></a></div>
    {else /}
        <form id="login_form" class="form-signin" method="post" action="{$login_url}">
            <input type="text" id="user_id" name="user_id" class="form-control" placeholder="User ID" required {if($controller == 'index' || $controller == 'problemset')}autofocus{/if} style="margin-top: 5px;">
            <input type="password" id="login_password" name="password" class="form-control" placeholder="Password" required style="margin-top: 5px;">
            <div class="btn-group" style="margin-top: 5px;">
                <a href="#" class="a_noline">
                    <button class="btn btn-sm btn-primary" id="login_submit_button" type="submit">登录</button>
                </a>
                &nbsp;
                {if $OJ_MODE == 'online' && $OJ_STATUS=='cpc' && $OJ_SSO == false}
                    <a href="__OJ__/user/register" class="a_noline">
                        <button class="btn btn-sm btn-success" id="forgot_submit_button" type="button">注册</button>
                    </a>
                {/if}
            </div>
            {if $OJ_MODE == 'online' && $OJ_STATUS == 'cpc' && $OJ_SSO == false}
            <a href="__OJ__/user/passback" class="a_noline" style="display: block; padding-top:5px; padding-bottom:5px;">忘记密码</a>
            {/if}
        </form>
    {/if}
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var login_submit_button = $('#login_submit_button');
        $('#login_form').validate({
            rules:{
                user_id: {
                    required: true,
                    minlength: 3,
                    maxlength: 30,
                    user_id_validate: true
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 64
                }
            },
            submitHandler: function(form)
            {
                login_submit_button.attr('disabled', true);
                $(form).ajaxSubmit(function(ret)
                {
                    if(ret["code"] == 1)
                    {
                        alertify.success(ret['msg']);
                        setTimeout(function(){location.reload()}, 500);
                        return true;
                    }
                    else
                    {
                        alertify.alert(ret['msg']);
                        login_submit_button.removeAttr('disabled');
                    }
                    return false;
                });
                return false;
            }
        });
    });
</script>