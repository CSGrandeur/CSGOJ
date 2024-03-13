{__NOLAYOUT__}
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link href="__STATIC__/ojtool/contestlive/favicon.png" rel="icon" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>CSG Live</title>
    {if(config('OJ_ENV.OJ_CDN') == 'local') /}
        <link href="__STATIC__/ojtool/contestlive/css/modern-normalize.min.css" rel="stylesheet" />
        <script src="__STATIC__/ojtool/contestlive/js/vue.global.prod.min.js"></script>
        <script src="__STATIC__/ojtool/contestlive/js/core.index.iife.min.js"></script>
        <script src="__STATIC__/ojtool/contestlive/js/share.index.iife.min.js"></script>
        <script src="__STATIC__/ojtool/contestlive/js/anime.min.js"></script>
        <script src="__STATIC__/ojtool/contestlive/js/base64.min.js"></script>
    {else /}
        <link href="https://fastly.jsdelivr.net/npm/modern-normalize@2.0.0/modern-normalize.min.css" rel="stylesheet" />
        <script src="https://fastly.jsdelivr.net/npm/vue/dist/vue.global.prod.min.js"></script>
        <script src="https://fastly.jsdelivr.net/npm/@vueuse/core@10.5.0/index.iife.min.js"></script>
        <script src="https://fastly.jsdelivr.net/npm/@vueuse/shared@10.5.0/index.iife.min.js"></script>
        <script src="https://fastly.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
        <script src="https://fastly.jsdelivr.net/npm/js-base64@3.7.5/base64.min.js"></script>
    {/if}
    <link href="__STATIC__/ojtool/contestlive/css/live.css" rel="stylesheet" />
    <script src="__STATIC__/js/util.js"></script>
    {js href="__STATIC__/csgoj/rank_common.js" /}
    <script src="__STATIC__/ojtool/js/rank_base.js"></script>
    <script src="__STATIC__/ojtool/js/live_base.js"></script>
  </head>
  <body>
    <div id="app"></div>
    <script>window.staticDirectory = '__STATIC__/ojtool/contestlive/';</script>
    <script src="__STATIC__/ojtool/contestlive/live.esm.js" type="module"></script>
  </body>
</html>
