{if(config('OJ_ENV.OJ_CDN') == 'local') /}
    {js href='__STATIC__/diff2html/diff2html.min.css' /}    
    {js href='__STATIC__/diff2html/diff2html.min.js' /}    
{else /}
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/diff2html@3.4.46/bundles/css/diff2html.min.css">
    <script src="https://fastly.jsdelivr.net/npm/diff2html@3.4.46/bundles/js/diff2html.min.js"></script>
{/if}

