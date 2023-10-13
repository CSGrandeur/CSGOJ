
{include file="../../ojtool/view/public/global_outer_css" /}
<link rel="stylesheet" type="text/css" href="__CSS__/basecss.css" />

{if(config('OJ_ENV.OJ_SITE') == 'local')}
    <link rel="stylesheet" type="text/css" href="__CSS__/sidebarlayout_local.css" />
{else/}
    <link rel="stylesheet" type="text/css" href="__CSS__/sidebarlayout.css" />
{/if}
<link rel="stylesheet" type="text/css" href="__CSS__/markdownhtml.css" />
