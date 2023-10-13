<h1 class='page-header'>Announcement</h1>
<h2 >{$news['title']}</h2>
<p class="help-block">
	Update Time：{$news['time']}&nbsp;&nbsp;&nbsp;&nbsp;
	Recent Edit：{$news['user_id']}&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<div class="md_display_div">
	{$news['content']}
</div>
{include file="../../csgoj/view/public/code_highlight" /}