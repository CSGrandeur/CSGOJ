{if(config('OJ_ENV.OJ_CDN') == 'local') /}
    {js href='__STATIC__/ojtool/js/jquery.min.js' /}
    {js href='__STATIC__/ojtool/bootstrap/js/bootstrap.bundle.min.js' /}

    {js href='__STATIC__/ojtool/js/jquery.tablednd.min.js' /}
    {js href='__STATIC__/bootstrap-table/bootstrap-table.min.js' /}
    {js href='__STATIC__/bootstrap-table/extensions/toolbar/bootstrap-table-toolbar.min.js' /}
    {js href='__STATIC__/tableExport.jquery.plugin/tableExport.min.js' /}
    {js href='__STATIC__/tableExport.jquery.plugin/libs/html2canvas//html2canvas.min.js' /}
    {js href='__STATIC__/bootstrap-table/extensions/export/bootstrap-table-export.min.js' /}
    {js href='__STATIC__/bootstrap-table/extensions/filter-control/bootstrap-table-filter-control.min.js' /}
    {js href='__STATIC__/bootstrap-table/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js' /}
    {js href='__STATIC__/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js' /}

    {js href='__STATIC__/ojtool/jsoneditor/jsoneditor.min.js' /}
    {js href='__STATIC__/ojtool/jquery-form/jquery.form.min.js' /}
    {js href='__STATIC__/ojtool/jquery-validation/jquery.validate.min.js' /}
    {js href='__STATIC__/ojtool/alertifyjs/alertify.min.js' /}
    {js href='__STATIC__/ojtool/tooltipster/js/tooltipster.bundle.min.js' /}
    {js href='__STATIC__/ojtool/marked/lib/marked.umd.min.js' /}
    {js href='__STATIC__/ojtool/dompurify/purify.min.js' /}
{else /}
<!-- base -->
<script src="https://fastly.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- bootstrap-table related -->
<script src="https://fastly.jsdelivr.net/npm/tablednd@1.0.5/dist/jquery.tablednd.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/tableexport.jquery.plugin@1.27.0/tableExport.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/tableexport.jquery.plugin@1.27.0/libs/html2canvas/html2canvas.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js"></script>
<!-- other tools -->
<script src="https://fastly.jsdelivr.net/npm/jsoneditor@9.10.2/dist/jsoneditor.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/jquery-form@4.3.0/dist/jquery.form.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/tooltipster@4.2.8/dist/js/tooltipster.bundle.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/marked@5.1.0/lib/marked.umd.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/dompurify@3.0.3/dist/purify.min.js"></script>
{/if}
