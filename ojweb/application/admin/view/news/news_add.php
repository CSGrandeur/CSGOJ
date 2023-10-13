<div class="page-header">
	<h1>Add news</h1>
	{include file="news/category_explain" /}
</div>
{include file="admin/select_control" /}
<div class="container">
	<form id="news_add_form" method='post' action="__ADMIN__/news/news_add_ajax">
		<div class="form-group">
			<label for="title">News Title：</label>
			<input type="text" class="form-control" id="title" placeholder="News Title..." name="title" >
		</div>
		<div class="form-group">
			<label for="title">Category：</label>
			<select name="category" class="selectpicker">
				{foreach($homeCategory as $ckey => $cval) }
				<option value="{$ckey}">
					{$cval}
				</option>
				{/foreach}
			</select>
		</div>
		<div class="form-group">
			<label for="title">Tags(split by ';' , at most 5 tags, each no more than 32 characters)：</label>
			<input type="text" class="form-control" id="title" placeholder="Contest;Solution;Changsha..." name="tags" >
		</div>
		<label for="content">Content (markdown)：</label>
		<textarea id="news_content" class="form-control" placeholder="Content..." rows="15" cols="50" name="content" ></textarea>
		<br/>
		<button type="submit" id="submit_button" class="btn btn-primary">Add News</button>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#news_add_form').validate({
			rules:{
				title:{
					required: true,
					maxlength: 200
				},
				content: {
					required: true,
					maxlength: 65536
				},
				tags:{
					maxlength: 200
				}
			},
			submitHandler: function(form)
			{
				$(form).ajaxSubmit(
					{
						success: function(ret)
						{
							var submit_button = $('#submit_button');
							if(ret["code"] == 1)
							{
								alertify.success(ret['msg']);
								button_delay(submit_button, 3, 'Add News');
								setTimeout(function(){location.href='news_edit?id='+ret['data']['news_id']}, 1000);
							}
							else
							{
								alertify.alert(ret['msg']);
								button_delay(submit_button, 3, 'Add News');
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