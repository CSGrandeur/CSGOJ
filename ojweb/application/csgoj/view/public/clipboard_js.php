{if(config('OJ_ENV.OJ_CDN') == 'local') }
    {js href="__STATIC__/clipboard.js/clipboard.min.js" /}
{else /}
    {js href="//fastly.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js" /}
{/if}