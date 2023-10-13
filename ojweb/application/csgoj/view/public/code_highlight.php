
{if(config('OJ_ENV.OJ_CDN') == 'local') }
    {css href="__STATIC__/highlight/styles/googlecode.min.css" /}
    {js href="__STATIC__/highlight/highlight.min.js" /}
{else /}
    {css href="//fastly.jsdelivr.net/gh/highlightjs/cdn-release@11.8.0/build/styles/googlecode.min.css" /}
    {js href="//fastly.jsdelivr.net/gh/highlightjs/cdn-release@11.8.0/build/highlight.min.js" /}
{/if}
<script type="text/javascript">hljs.highlightAll();</script>