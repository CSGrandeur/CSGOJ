<div class="news_detail_div">
<h2 title="{$news['title']}" class="article-title">{$news['title']}</h2>
<p class="help-block">
    <span class="inline_span">Update Time: <span class="inline_span text-danger">{$news['modify_time']}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <span class="inline_span">Recent Editor: <span class="inline_span text-info"><a href="/csgoj/user/userinfo?user_id={$news['modify_user_id']}">{$news['modify_user_id']}</a></span></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <span class="inline_span">Create Time: <span class="inline_span text-default">{$news['time']}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <span class="inline_span">Creator: <span class="inline_span text-default"><a href="/csgoj/user/userinfo?user_id={$news['user_id']}">{$news['user_id']}</a></span></span>&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<hr/>
<article class="md_display_div news_display_div">
    {$news['content']}
</article>
{include file="../../csgoj/view/public/code_highlight" /}
{include file="../../csgoj/view/public/mathjax_js" /}
</div>
<style type="text/css">
    .article-title
    {
        color: #222222;
    }
    .news_detail_div
    {
        max-width: 900px;
    }
</style>