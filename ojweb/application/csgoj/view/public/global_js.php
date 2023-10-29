{if(config('OJ_ENV.OJ_CDN') == 'local') }
    {js href="__JS__/jquery-3.1.1.min.js" /}
    {js href="__JS__/jquery.cookie.min.js" /}
    {js href="__STATIC__/bootstrap-3.3.7/js/bootstrap.min.js" /}
    {js href="__STATIC__/bootstrap-table/bootstrap-table.min.js" /}
    {js href='__STATIC__/bootstrap-table/extensions/toolbar/bootstrap-table-toolbar.min.js' /}
    {js href='__STATIC__/tableExport.jquery.plugin/tableExport.min.js' /}
    {js href='__STATIC__/tableExport.jquery.plugin/libs/html2canvas//html2canvas.min.js' /}
    {js href='__STATIC__/bootstrap-table/extensions/export/bootstrap-table-export.min.js' /}
    {js href="__STATIC__/bootstrap-table/extensions/cookie/bootstrap-table-cookie.min.js" /}
    {js href="__JS__/jquery.form.min.js" /}
    {js href="__STATIC__/jquery-validate/jquery.validate.js" /}
    {js href="__STATIC__/alertifyjs/alertify.min.js" /}
    {js href="__STATIC__/bootstrap-switch-3.3.4/js/bootstrap-switch.min.js" /}
    {js href="__STATIC__/tooltipster-4.2.8/js/tooltipster.bundle.js" /}
{else /}
    {js href="//fastly.jsdelivr.net/npm/jquery@3.1.1/dist/jquery.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/jquery.cookie@1.4.1/jquery.cookie.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/bootstrap-table.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/toolbar/bootstrap-table-toolbar.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/tableexport.jquery.plugin@1.28.0/tableExport.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/tableexport.jquery.plugin@1.28.0/libs/html2canvas/html2canvas.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/export/bootstrap-table-export.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/cookie/bootstrap-table-cookie.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/jquery-form@4.3.0/dist/jquery.form.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/js/bootstrap-switch.min.js" /}
    {js href="//fastly.jsdelivr.net/npm/tooltipster@4.2.8/dist/js/tooltipster.bundle.min.js" /}
{/if}


<script type="text/javascript" src="__JS__/global.js"></script>
<script type="text/javascript" src="__JS__/util.js"></script>