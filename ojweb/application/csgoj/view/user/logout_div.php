<div class="logout_div">
    <label>
    Hi! <a href="{if $OJ_MODE == 'cpcsys' }__CPC__{else /}__OJ__{/if}/user/userinfo?user_id={$Think.session.user_id}" class="a_noline">{$Think.session.user_id}</a>
    </label>
    <div>
    {if $OJ_SSO != false}
        <a href="__OJTOOL__/sso/sso_logout"><button class="btn btn-sm btn-primary" type="button" style="margin-top: 5px;">登出</button></a>
        {if null != $Think.session.sso_login }
            <a href="{$OJ_SSO}/sso/changepass/" class="a_noline" target="_blank">修改密码</a>
        {else /}
            <a href="{$OJ_SSO}/sso/changepass/notlogin.html" class="a_noline" target="_blank">修改密码</a>
        {/if}
    {else /}
        <a href="__OJ__/user/modify?user_id={$Think.session.user_id}" class="a_noline">修改信息</a>
        <button class="btn btn-sm btn-primary" id="logout_button" type="button" style="margin-top: 5px;">登出</button>
    {/if}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var logout_button = $('#logout_button');
        $("#logout_button").unbind('click').click(function(){
            $.post("{if $OJ_MODE == 'cpcsys' }__CPC__{else /}__OJ__{/if}/User/logout_ajax", function(ret){
                logout_button.attr('disabled', true);
                if(ret['code'] == 1){
                    alertify.success(ret['msg']);
                    setTimeout(function(){location.reload()}, 500);
                }
                else {
                    alertify.alert(ret['msg']);
                    location.href = '__OJ__';
                }
            });
            return false;
        });
    });
</script>