<ul class="nav nav-tabs">
    <?php $controller = strtolower(request()->controller()); ?>

    {if(IsAdmin('news_editor')) }
    <li role="presentation" class="dropdown {if $controller == 'news' } active {/if}">
        <a href="__ADMIN__/news/index" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            公告 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="__ADMIN__/news/index">公告列表</a>
            </li>
            <li>
                <a href="__ADMIN__/news/news_add">添加公告</a>
            </li>
            {if(IsAdmin('administrator'))}
            <li>
                <a href="__ADMIN__/news/carousel">轮播</a>
            </li>
            <li>
                <a href="__ADMIN__/news/aboutus">关于...</a>
            </li>
            <li>
                <a href="__ADMIN__/news/oj_faq">常见疑问</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {if(IsAdmin('problem_editor')) }
    <li role="presentation" class="dropdown {if $controller == 'problem' } active {/if}">
        <a href="__ADMIN__/problem/index" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            题目 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="__ADMIN__/problem/index">题目列表</a>
            </li>
            <li>
                <a href="__ADMIN__/problem/problem_add">添加题目</a>
            </li>
            <li>
                <a href="__ADMIN__/problem/problem_rejudge">题目重判</a>
            </li>
            {if(IsAdmin('administrator'))}
            <li>
                <a href="__ADMIN__/problemexport/problem_export?item=problemexport">题目导出</a>
            </li>
            <li>
                <a href="__ADMIN__/problemexport/problem_export_filemanager?item=problemexport" target="_blank">题目导入</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {if(IsAdmin('contest_editor')) }
    <li role="presentation" class="dropdown {if $controller == 'contest' } active {/if}">
        <a href="__ADMIN__/contest/index" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            比赛/练习 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="__ADMIN__/contest/index">比赛列表</a>
            </li>
            <li>
                <a href="__ADMIN__/contest/contest_add">添加比赛</a>
            </li>
            <li>
                <a href="__ADMIN__/contestsummary/contest_summary">统计归档</a>
            </li>
        </ul>
    </li>
    {/if}
    {if IsAdmin('administrator') && $OJ_STATUS == 'exp'}
    <li role="presentation" class="dropdown {if $controller == 'clss' } active {/if}">
        <a href="__ADMIN__/clss/index" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            班级 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="__ADMIN__/clss/index">班级列表</a>
            </li>
            {if IsAdmin('super_admin') && $OJ_STATUS == 'exp'}
            <li>
                <a href="__ADMIN__/clss/clss_add">批量添加/修改班级</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {if(IsAdmin('administrator')) }
    <li class="{if $controller == 'privilege' } active {/if}">
        <a href="__ADMIN__/privilege/index" role="button" aria-haspopup="true" aria-expanded="false">
            权限
        </a>
    </li>
    {/if}
    {if(IsAdmin('password_setter')) }
    {if $OJ_SSO == false}
    <li role="presentation" class="dropdown {if $controller == 'usermanager' } active {/if}">
        <a href="__ADMIN__/usermanager/index" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            用户 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="__ADMIN__/usermanager/index">用户总表</a>
            </li>
            {if !$OJ_SSO && ($OJ_STATUS=='exp' && IsAdmin('administrator') || IsAdmin('super_admin')) }
            <li>
                <a href="__ADMIN__/usermanager/usergen">用户生成</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {/if}
</ul>