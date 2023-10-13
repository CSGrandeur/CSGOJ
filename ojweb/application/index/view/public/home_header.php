
<ul class="nav nav-tabs">
    <?php $controller = strtolower(request()->controller()); ?>

    <li class="{if $controller == 'index' } active {/if}">
        <a href="__HOME__/index" role="button" aria-haspopup="true" aria-expanded="false">
            {$OJ_NAME}主页
        </a>
    </li>
    {foreach($homeCategory as $ca=>$categoryName)}
    <li class="{if $controller == $ca } active {/if}">
        <a href="__HOME__/{$ca}" role="button" aria-haspopup="true" aria-expanded="false">
            {$categoryName}
        </a>
    </li>
    {/foreach}
    <li class="{if $controller == 'about' } active {/if}">
        <a href="__HOME__/about" role="button" aria-haspopup="true" aria-expanded="false">
            关于
        </a>
    </li>
</ul>