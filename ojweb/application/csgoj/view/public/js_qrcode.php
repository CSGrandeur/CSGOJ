{if(config('OJ_ENV.OJ_CDN') == 'local') /}
    {js href='__STATIC__/ojtool/js/qr-code-styling.min.js' /}
{else /}
    <script src="https://fastly.jsdelivr.net/npm/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.min.js"></script>
{/if}
