{if isset($login_teaminfo) && $login_teaminfo }
<div class="contest_logout_div">
    <span id="cpc_team_id_span" data-tooltip-content="#cpc_teaminfo_div" >{$login_teaminfo['team_id']}</span>
    <a href='#' id="contest_logout_button"><strong>Logout</strong></a>
    
    <div class="tooltip_templates">
        <span id="cpc_teaminfo_div">
            <table style="border: none;">
            <tr><td width=70px>ID:  </td><td>{$login_teaminfo['team_id']}</td></tr>
            <tr><td>School:         </td><td>{$login_teaminfo['school']}</td></tr>
            <tr><td>Team:           </td><td>{$login_teaminfo['name']}</td></tr>
            <tr><td>Member:         </td><td>{$login_teaminfo['tmember']}</td></tr>
            <tr><td>Coach :         </td><td>{$login_teaminfo['coach']}</td></tr>
            <tr><td>Room :          </td><td>{$login_teaminfo['room']}</td></tr>
            </table>
        </span>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#cpc_team_id_span').tooltipster({
            theme: 'tooltipster-noir',
            interactive: true,
            contentCloning: true
        });
        let contest_logout_button = $('#contest_logout_button');
        contest_logout_button.unbind('click').click(function()
        {
            $.post("__CPC__/contest/contest_logout_ajax?cid={$contest['contest_id']}", function(ret){
                if(ret['code'] == 1)
                {
                    alertify.success(ret['msg']);
                    setTimeout(function(){location.reload()}, 500);
                }
                else
                {
                    alertify.alert(ret['msg']);
                    // location.href = '__OJ__';
                }
            });
            return false;
        });
    });
</script>

<style type="text/css">
    .contest_logout_div {
        position: absolute;
        top: 15px;
        right: 30px;
    }
</style>
{/if}
{include file="../../csgoj/view/contest/contest_header" /}