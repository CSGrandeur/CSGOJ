<?php $edit_mode = isset($news); ?>
<div class="page-header">
	<h1>
		{if $edit_mode }
		Edit
		{if(isset($special_page))}
		<a href="{$aimurl}" target="_blank">#{$title}</a>
		{else/}
		Article
		<a href="__HOME__/{$news['category']}/detail?nid={$news['news_id']}" target="_blank">#{$news['news_id']}</a>
		{/if}
		<a href="__ADMIN__/filemanager/filemanager?item={$controller}&id={$news['news_id']}" target="_blank"><button class="btn btn-success" id="attachfile">Attach file manager</button></a>

		{if(!isset($special_page))}
		<?php $defunct = $news['defunct']; $item_id = $news['news_id']; ?>
		{include file="admin/changestatus_button" /}
		{/if}
		{else /}
		Add Article
		{/if}
	</h1>
	{if $edit_mode }
	<p class="help-block">
		<span class="inline_span">Creator：<span class="inline_span text-danger">{if $edit_mode}{$news['user_id']}{/if}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
		<span class="inline_span">Time：<span class="inline_span text-danger">{if $edit_mode}{$news['time']}{/if}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
		<span class="inline_span">Last Modify：<span class="inline_span text-danger">{if $edit_mode}{$news['modify_user_id']}{/if}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
		<span class="inline_span">Time：<span class="inline_span text-danger">{if $edit_mode}{$news['modify_time']}{/if}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
	</p>
	{/if}
	{if(!isset($special_page))}
	{include file="news/category_explain" /}
	{/if}
</div>
{include file="admin/select_control" /}
<div>
    {if(!isset($special_page))}
	<form id="news_edit_form" method='post' action="__ADMIN__/news/{$action}_ajax">
    {else /}
    <form id="news_edit_form" method='post' action="__ADMIN__/news/news_edit_ajax">
    {/if}

		<div class="form-group">
			{if !isset($special_page)}
            {include file="admin/co_editor_input" /}
			{/if}
			<label for="title">News Title：</label>
			{if(isset($special_page))}
			<input type="hidden" name="title" value="{if $edit_mode}{$news['title']}{/if}">
			<input type="text" class="form-control" id="title" placeholder="News Title..." value="{if $edit_mode}{$news['title']}{/if}" disabled >
			{else/}
			<input type="text" class="form-control" id="title" placeholder="News Title..." name="title" value="{if $edit_mode}{$news['title']}{/if}">
			{/if}
		</div>
		{if(!isset($special_page))}
		<div class="form-group">
			<label for="title">Category：</label>
			<select name="category" class="selectpicker">
				{foreach($homeCategory as $ckey => $cval) }
				<option value="{$ckey}" {if $edit_mode && $news['category'] == $ckey} selected {/if} >
					{$cval}
				</option>
				{/foreach}
			</select>
		</div>
		<div class="form-group">
			<label for="title">Tags(split by ';' , at most 5 tags, each no more than 32 characters)：</label>
			<input type="text" class="form-control" id="title" placeholder="Contest;Solution;Changsha..." name="tags" value="{if $edit_mode}{$news['tags']}{/if}">
		</div>
		{/if}
		<label for="content">Content (markdown)：</label>
		<textarea id="news_content" class="form-control" placeholder="Content..." rows="15" cols="50" name="content" >{if $edit_mode}{$news['content']|htmlspecialchars}{/if}</textarea>
		<input type="hidden" id='id_input' value="{if $edit_mode}{$news['news_id']}{/if}" name="news_id">
		<button type="submit" id="submit_button" class="btn btn-primary">Modify News</button>
	</form>
</div>
<input type="hidden" id='page_info' edit_mode="{if $edit_mode}1{else/}0{/if}">
<script type="text/javascript">
	var page_info = $('#page_info');
	var edit_mode = page_info.attr('edit_mode');
	var submit_button = $('#submit_button');
	var submit_button_text = submit_button.text();
	$(document).ready(function()
	{
		$('input[type="text"],textarea').tooltipster({
			trigger: 'custom',
			position: 'bottom',
			animation: 'grow',
			theme: 'tooltipster-noir',
			distance: -15
		});
		$('#news_edit_form').validate({
			rules:{
				title:{
					required: true,
					maxlength: 200
				},
				content: {
					required: true,
					maxlength: 65536
				}
			},
			submitHandler: function(form)
			{
				$(form).ajaxSubmit({
					success: function(ret)
					{
						if(ret['code'] == 1)
						{
							if(typeof(ret['data']['alert']) != 'undefined' && ret['data']['alert'] == true){
								alertify.alert(ret['msg']);
							}else{
								alertify.success(ret['msg']);
							}
							button_delay(submit_button, 3, submit_button_text);
							if(edit_mode != '1') {
								setTimeout(function(){location.href='news_edit?id='+ret['data']['id']}, 500);
							}
						}
						else
						{
							alertify.alert(ret['msg']);
							button_delay(submit_button, 3, submit_button_text);
						}
						return false;
					}
				});
				return false;
			}
		});

	});
	$(window).keydown(function(e) {
		if (e.keyCode == 83 && e.ctrlKey) {
			e.preventDefault();
			var a=document.createEvent("MouseEvents");
			a.initEvent("click", true, true);
			$('#submit_button')[0].dispatchEvent(a);
		}
	});
</script>