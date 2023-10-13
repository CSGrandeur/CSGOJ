{__NOLAYOUT__}
<!DOCTYPE html>
<html>
{include file="../../csgoj/view/public/global_head" /}
<body>
<div class="container">
<h1>Judge Data of {$inputinfo['item']|ucwords}: {$inputinfo['id']}</h1>
<script type="text/javascript">
	var re_checkfile = /^(spj|tpj|([0-9a-zA-Z-_\. \(\)]+\.(zip|in|out|cpp|cc|c|c\+\+)))$/;
</script>
{include file="filemanager/js_upload" /}
</div>
</body>
</html>