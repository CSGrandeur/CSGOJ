{if $controller == 'contest' && $action != 'index' || $controller=='admin' }
    {include file="contest/contest_header" /}
    {if $controller=='admin' && ($isContestAdmin || isset($proctorAdmin) && $proctorAdmin)}
        {include file="admin/admin_header" /}
    {/if}
{/if}