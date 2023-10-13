<hr/>
{if $OJ_MODE=='online' || IsAdmin('administrator') }
<li {if ($module == 'index') } class="active" {/if} ><a href="__HOME__/index">Home</a></li>
<li {if ($controller == 'problemset')} class="active" {/if} ><a href="__OJ__/problemset">Problem Set</a></li>
<li {if ($controller == 'status')} class="active" {/if} ><a href="__OJ__/status">Status</a></li>
<li {if ($controller == 'userrank')} class="active" {/if} ><a href="__OJ__/userrank">User Rank</a></li>
<li {if ($module == 'csgoj' && $controller == 'contest')} class="active" {/if} ><a href="__OJ__/contest">Contest</a></li>
{/if}
{if $OJ_STATUS == 'cpc'}
<li  {if $module == 'cpcsys' && $controller == 'contest'} class="active" {/if} ><a href="__CPC__/contest">STD Contest</a></li>
{/if}
<li {if ($controller == 'faqs')} class="active" {/if} ><a href="{if $module == 'cpcsys'}__CPC__{else}__OJ__{/if}/faqs">F.A.Qs</a></li>

<?php
$showadmin = false;
//$ojAdminList在Globalbasecontroller中assign
foreach($ojAdminList as $adminStr => $adminName) {
    if (IsAdmin($adminStr))
    {
        $showadmin = true;
        break;
    }
}
if($showadmin)
{
    ?>
    <li {if ($module == 'admin')} class="active" {/if}><a href="__ADMIN__/index">Admin</a></li>
<?php } ?>

<hr/>
{if $OJ_MODE=='online' || IsAdmin() }
<li><a href="__OJTOOL__">OJ Tools</a></li>
{/if}