{if(config('OJ_ENV.OJ_CDN') == 'local') }
	{css href="__STATIC__/highlight/styles/googlecode.min.css" /}
	{js href="__STATIC__/highlight/highlight.min.js" /}
	{js href="__STATIC__/highlight/languages/cpp.min.js" /}
	{js href="__STATIC__/highlight/languages/java.min.js" /}
	{js href="__STATIC__/highlight/languages/python.min.js" /}
{else /}
    {css href="//fastly.jsdelivr.net/gh/highlightjs/cdn-release@11.4.0/build/styles/googlecode.min.css" /}
    {js href="//fastly.jsdelivr.net/gh/highlightjs/cdn-release@11.4.0/build/highlight.min.js" /}
    {js href="//fastly.jsdelivr.net/gh/highlightjs/cdn-release@11.4.0/build/languages/cpp.min.js" /}
    {js href="//fastly.jsdelivr.net/gh/highlightjs/cdn-release@11.4.0/build/languages/java.min.js" /}
    {js href="//fastly.jsdelivr.net/gh/highlightjs/cdn-release@11.4.0/build/languages/python.min.js" /}
{/if}