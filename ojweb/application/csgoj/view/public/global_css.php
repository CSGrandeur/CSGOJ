{if(config('OJ_ENV.OJ_CDN') == 'local') }
    {css href="__STATIC__/bootstrap-3.3.7/css/bootstrap.min.css" /}
    {css href="__STATIC__/bootstrap-table/bootstrap-table.min.css" /}
    {css href='__STATIC__/bootstrap-table/extensions/filter-control/bootstrap-table-filter-control.min.css' /}
    {css href='__STATIC__/bootstrap-table/extensions/reorder-rows/bootstrap-table-reorder-rows.min.css' /}
    {css href='__STATIC__/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css' /}
    {css href="__STATIC__/alertifyjs/css/alertify.min.css" /}
    {css href="__STATIC__/alertifyjs/css/themes/default.min.css" /}
    {css href="__STATIC__/bootstrap-switch-3.3.4/css/bootstrap3/bootstrap-switch.min.css" /}
    {css href="__STATIC__/tooltipster-4.2.8/css/tooltipster.bundle.min.css" /}
    {css href="__STATIC__/tooltipster-4.2.8/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-noir.min.css" /}
{else /}
    {css href="//fastly.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/bootstrap-table.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/filter-control/bootstrap-table-filter-control.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/css/bootstrap3/bootstrap-switch.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/tooltipster@4.2.8/dist/css/tooltipster.bundle.min.css" /}
    {css href="//fastly.jsdelivr.net/npm/tooltipster@4.2.8/dist/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-noir.min.css" /}
{/if}

<link rel="stylesheet" type="text/css" href="__CSS__/basecss.css" />

{if(config('OJ_ENV.OJ_SITE') == 'local')}
    <link rel="stylesheet" type="text/css" href="__CSS__/sidebarlayout_local.css" />
{else/}
    <link rel="stylesheet" type="text/css" href="__CSS__/sidebarlayout.css" />
{/if}

<link rel="stylesheet" type="text/css" href="__CSS__/markdownhtml.css" />
