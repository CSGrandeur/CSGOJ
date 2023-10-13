<h1>User Information</h1>
<hr/>
<div>
    <h2 style="display: inline-block; line-height: 1px;">{$baseinfo['user_id']}</h2>&nbsp;&nbsp;&nbsp;&nbsp;
    <strong class="text-warning">{$baseinfo['nick']|htmlspecialchars}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
    <div id="volume_info_div"></div>
</div>
<p class="help-block">
    <span class="inline_span">Registration Time: <span class="text-warning">{$baseinfo['reg_time']}</span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <span class="inline_span">Last Login: <span class="text-warning">{$baseinfo['accesstime']}</span></span>
</p>
{include file="../../csgoj/view/user/userinfo_detail" /}
<div id="userinfo_div">
    <div id="userinfo_left">
            <?php $problem_oneline = 10; ?>
            {include file="../../csgoj/view/user/userinfo_solved" /}
    </div>
    <div id="userinfo_right">
        {if isset($loginlog) }
        <div><h4 style="display: inline; ">Login Log: </div>
        <div class="md_display_div">
            <table>
                <thead><tr><th>IP</th><th>Time</th><th>Success</th></tr></thead>
                <tbody>
                {foreach($loginlog as $log)}
                <tr>
                    <td>{$log['ip']}</td>
                    <td>{$log['time']}</td>
                    <td><?php echo $log['password'] == '1' ? 'Yes' : 'No'; ?></td>
                </tr>
                {/foreach}
                <tr>{for start="0" end="3"}<th></th>{/for}</tr>
                </tbody>
            </table>
        </div>
        {/if}
    </div>
</div>
<input id="page_info" type="hidden" user_volume="{$baseinfo['volume']}" >

<script src="__STATIC__/csgoj/tt_formatter.js"></script>
<script>
let page_info;
let user_volume;
function SetTt() {
    let ret_html = TtFormatter(user_volume, true);
    $('#volume_info_div').html(ret_html);
}
$(document).ready(function() {
    page_info = $('#page_info');
    user_volume = parseInt(page_info.attr('user_volume'));
    SetTt();
});
</script>
<style type="text/css">
    #info_left>table th,
    #info_left>table td
    {
        text-align: center;
    }
    #userinfo_div {
        min-width: 1100px;
        overflow:hidden;
    }
    #userinfo_left
    {
        /*display: inline-block;*/
        margin-top: 20px;
        float: left;
        margin-right: 20px;
    }
    #userinfo_right
    {
        overflow:hidden;
        margin-top: 20px;
        /*display: inline-block;*/
        float: left;
    }

    @media (max-width: 1000px)
    {
        #userinfo_div {
            min-width: 600px;
        }
    }
</style>