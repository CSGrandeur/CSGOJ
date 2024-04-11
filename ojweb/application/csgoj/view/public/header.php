<header>
    <?php $module = strtolower(request()->module()); $controller = strtolower(request()->controller()); ?>
    {include file="../../csgoj/view/public/csgoj_brand" /}
    <ul id="sidebar_div" class="nav nav-pills nav-stacked sidebar" data-bs-toggle="collapse" data-bs-target="#sidebar_div" aria-expanded="false" aria-controls="sidebar_div">
        <li id="user_panel" {if $OJ_MODE == 'cpcsys'}style="display:none;"{/if}>
            {if(session('?user_id')) }
            {include file="../../csgoj/view/user/logout_div" /}
            {else /}
            {include file="../../csgoj/view/user/login_div" /}
            {/if}
        </li>
        {include file="../../csgoj/view/public/side_menu_cn" /}
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
$('#hidden_user_panel').click(function (event) {
    user_panel.toggle();
});
</script>
{/if}
<script>
const updateZoom = () => {
    if(window.innerWidth < 1900) {
        document.body.style.zoom = window.innerWidth / 1900;
    } else if(window.innerHeight < 1000) {
        document.body.style.zoom = window.innerHeight / 1000;
    } else {
        document.body.style.zoom = '';
    }
}
updateZoom();
</script>
</header>
