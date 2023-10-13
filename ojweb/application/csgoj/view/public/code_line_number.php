<script type="text/javascript">
    function AddLineNumber(code_linenumber_div) {
        var pre = code_linenumber_div.find('pre');
        if(pre.length != 1) {
            return;
        }
        var oriPreClass = pre.attr('class');
        var oriPreId = pre.attr('id');
        var code = pre.find('code');
        if(code.length == 1) {
            pre = code;
        }
        else if(code.length > 1) {
            return;
        }


        var content = pre.text().split('\n');
        var lines = content.length;
        if(lines > 0 && content[lines - 1].length == 0)
            lines --;

        var number_pre = $('<pre class="ln_number_pre"></pre>');
        var code_pre = $('<pre class="ln_code_pre"></pre>');
        var numberlist = '';
        if(lines > 0)
        {
            for(var i=1; i<=lines;i++) {
                numberlist += i + '\n';
            }
            number_pre.text(numberlist);
            code_pre.html(pre.html());
            code_pre.addClass(oriPreClass);
            code_pre.attr('id', oriPreId);
            code_linenumber_div.empty();
            var table = $('<table class="ln_number_pre_table"></table>');
            var number_pre_td = $('<td class="ln_number_pre_td"></td>').append(number_pre);
            var code_pre_td = $('<td class="ln_code_pre_td"></td>').append(code_pre);
            table.append(number_pre_td).append(code_pre_td);
            code_linenumber_div.append(table);
        }
    }
</script>

<style type="text/css">
    .ln_code_pre_td
    {
        width: 100%;
    }
    .ln_code_pre,
    .ln_number_pre
    {
        margin: 0;
    }
    .ln_code_pre
    {
        background-color: white;
        padding-top: 0;
        padding-bottom: 0;
        border-left: none;
    }
    .ln_number_pre_td {
        background-color: #efefef;
    }
    .ln_number_pre {
        background-color: #efefef;
        padding: 10px;
        text-align: right;
        padding-top: 0;
        padding-bottom: 0;
        border-right: none;
    }
    .ln_number_pre_table {
        border: 0.5px solid #dfdfdf;
        border-radius: 10px;
    }
    
</style>