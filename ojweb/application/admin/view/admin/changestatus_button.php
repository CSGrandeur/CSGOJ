{if $defunct == '1'}
<button type="button" field="defunct" itemid="{$item_id}" {if isset($item_name)}item_name="{$item_name}"{/if} class="change_status btn btn-warning" status="1">Reserved</button>
{else /}
<button type="button" field="defunct" itemid="{$item_id}" {if isset($item_name)}item_name="{$item_name}"{/if} class="change_status btn btn-success" status="0">Available</button>
{/if}
{include file="../../admin/view/admin/js_changestatus" /}