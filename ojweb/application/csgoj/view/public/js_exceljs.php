{if(config('OJ_ENV.OJ_CDN') == 'local') /}
    {js href='__STATIC__/exceljs/exceljs.min.js' /}    
{else /}
    <script src="https://fasty.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
{/if}
