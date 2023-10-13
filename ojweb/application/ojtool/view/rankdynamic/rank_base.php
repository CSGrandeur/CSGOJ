<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <link rel="icon" href="__IMG__/global/favicon.ico" />
    <title><?php echo (isset($pagetitle) ? $pagetitle : "Online Judge"); ?></title>
    {include file="public/global_outer_css" /}
    {include file="public/global_outer_js" /}
    <link  href="__STATIC__/ojtool/css/rankroll.css" rel="stylesheet" >
    <script src="__JS__/global.js" type="text/javascript" ></script>
    <script src="__JS__/util.js" type="text/javascript" ></script>
</head>
{js href='__STATIC__/ojtool/js/rank_tool.js' /}
{include file="public/js_toolbox" /}
{include file="../../csgoj/view/public/js_identicon" /}
<body>
<main>
    <input type="hidden" id="rank_page_info" OJ_MODE={$OJ_MODE} >
    <script>
        let rank_page_info = $('#rank_page_info');
        let OJ_MODE = rank_page_info.attr('OJ_MODE');
        const school_badge_url = '/static/image/school_badge/'
    </script>
    {__CONTENT__}
</main>
</body>
</html>