{if(config('OJ_ENV.OJ_CDN') == 'local') /}
    {css href='__STATIC__/ojtool/bootstrap/css//bootstrap.min.css' /}
    {css href='__STATIC__/ojtool/bootstrap-icons/font/bootstrap-icons.min.css' /}

    {css href='__STATIC__/bootstrap-table/bootstrap-table.min.css' /}
    {css href='__STATIC__/bootstrap-table/extensions/filter-control/bootstrap-table-filter-control.min.css' /}
    {css href='__STATIC__/bootstrap-table/extensions/reorder-rows/bootstrap-table-reorder-rows.min.css' /}
    {css href='__STATIC__/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css' /}

    {css href='__STATIC__/ojtool/jsoneditor/jsoneditor.min.css' /}
    {css href='__STATIC__/ojtool/alertifyjs/css/alertify.min.css' /}
    {css href='__STATIC__/ojtool/alertifyjs/css/themes//default.min.css' /}
    {css href='__STATIC__/ojtool/tooltipster/css/tooltipster.bundle.min.css' /}
    {css href='__STATIC__/ojtool/tooltipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-noir.min.css' /}
{else /}
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/filter-control/bootstrap-table-filter-control.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css">

    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/jsoneditor@9.10.2/dist/jsoneditor.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/tooltipster@4.2.8/dist/css/tooltipster.bundle.min.css">
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/tooltipster@4.2.8/dist/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-noir.min.css">
{/if}