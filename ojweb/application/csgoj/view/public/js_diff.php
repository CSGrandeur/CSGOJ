{if(config('OJ_ENV.OJ_CDN') == 'local') }
    {js href="__STATIC__/jsdiff/diff.min.js" /}
{else /}
    {js href="//fastly.jsdelivr.net/npm/diff@5.0.0/dist/diff.min.js" /}
{/if}