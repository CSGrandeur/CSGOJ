<h2 title="{$news['title']}">{$news['title']}</h2>
<p class="help-block">
    Update Time：{$news['time']}&nbsp;&nbsp;&nbsp;&nbsp;
    Recent Edit：{$news['user_id']}&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<div class="md_display_div news_display_div">
    {$news['content']}
</div>
{include file="../../csgoj/view/public/code_highlight" /}
{include file="../../csgoj/view/public/mathjax_js" /}