<header>
    <?php $module = strtolower(request()->module()); $controller = strtolower(request()->controller()); ?>
    {include file="../../csgoj/view/public/csgoj_brand" /}
    <ul id="sidebar_div" class="nav nav-pills flex-column mb-auto sidebar">
        <li id="user_panel" style="display:{if $OJ_MODE == 'cpcsys'}none{/if};">
            {if(session('?user_id')) }
            {include file="../../csgoj/view/user/logout_div" /}
            {else /}
            {include file="../../csgoj/view/user/login_div" /}
            {/if}
        </li>
        {include file="../../ojtool/view/public/side_menu" /}
    </ul>
{if $OJ_MODE == 'cpcsys'}
<script>
let sidebar_div = $('#sidebar_div');
let user_panel = $('#user_panel');
sidebar_div.on('click', function (event) {
    if(event.detail == 3){
        user_panel.toggle();
    }
    
});
</script>
{/if}
</header>
