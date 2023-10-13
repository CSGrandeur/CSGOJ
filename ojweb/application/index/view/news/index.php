{if $news != null}
<p class="text-right">
    <a href="__HOME__/index/news_list">More...</a>
</p>
{foreach $news as $new }
    <div class="list-group index_news_div">
        <div class="list-group-item limit_header">
            <a href="__HOME__/news/detail?nid={$new['news_id']}" >
                <h2 class="list-group-item-heading">{$new['title']}</h2>
            </a>
            <p class="help-block">
                Update Time：{$new['time']}&nbsp;&nbsp;&nbsp;&nbsp;
                Recent Edit：{$new['user_id']}&nbsp;&nbsp;&nbsp;&nbsp;
            </p>
            <div class="list-group-item-text index_news_content">{$new['content']|htmlspecialchars_decode|stripslashes}</div>
        </div>
    </div>
{/foreach}
{else/}
<h1>No news recently</h1>
{/if}