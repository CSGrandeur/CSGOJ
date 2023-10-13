{if(config('OJ_ENV.OJ_CDN') == 'local') }
    {js href="__STATIC__/MathJax/es5/tex-mml-chtml.js" /}
{else /}
    {js href="https://fastly.jsdelivr.net/npm/mathjax@3.2.2/es5/tex-mml-chtml.min.js" /}
{/if}