{include file="../../csgoj/view/public/code_highlight_base" /}
{include file="../../csgoj/view/public/js_diff" /}
<div id="settings" class="form-inline">
    <label class="control-label">Diff Style: </label> &nbsp;&nbsp;
    <input type="radio" class="form-control diff_type" name="diff_type" id="diffChars" value="diffChars"><label for="diffChars">Chars</label> &nbsp;&nbsp;
    <input type="radio" class="form-control diff_type" name="diff_type" id="diffWords" value="diffWords" ><label for="diffWords">Words</label> &nbsp;&nbsp;
    <input type="radio" class="form-control diff_type" name="diff_type" id="diffLines" value="diffLines"><label for="diffLines">Lines</label> &nbsp;&nbsp;
    <input type="radio" class="form-control diff_type" name="diff_type" id="diffOriginal" value="diffOriginal" checked><label for="diffOriginal">Original</label> &nbsp;&nbsp;
    <label class="control-label text-primary"><span id="diff_num" class="text-danger"></span> Differences</label>

    <label class="alert alert-info" style="position: absolute; left: 50%; top:300px; z-index: 1000;" id="comparing_label">Processing...</label>
</div>

<div class="row compare_code_div">
    {for start="0" end="2"}
    <?php
        if($i == 0)
        {
            $colorType = 'danger';
            $newold = 'New';
        }
        else
        {
            $colorType = 'info';
            $newold = 'Previous';
        }
    ?>
    <div class="col-lg-6 col-md-6 ">
        <div class="panel panel-{$colorType}">
            <div class="panel-heading">
                <h3 class="panel-title">{if isset($code[$i]) && isset($code[$i]['solution_id'])}{$newold} Submission {$code[$i]['solution_id']}&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$userInfoUrl}{$code[$i]['user_id']}" target="_blank">Author: {$code[$i]['user_id']}</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="__OJ__/problemset/problem?pid={$code[$i]['problem_id']}" target="_blank">Problem {$code[$i]['problem_id']}</a>{/if}</h3>
            </div>
            <pre style="display: none;" id="code_compare_hidden_{$i}">{if isset($code[$i]) && isset($code[$i]['source'])}{$code[$i]['source']}{/if}</pre>
            <div class="panel-body code_linenumber_div" id="code_compare_{$i}">
                <pre>{if isset($code[$i]) && isset($code[$i]['source'])}{$code[$i]['source']}{/if}</pre>
            </div>
        </div>
    </div>
    {/for}
</div>

<script type="text/javascript">
    var code_compare_0 = $('#code_compare_0');
    var code_compare_1 = $('#code_compare_1');

    var code_compare_hidden_0 = $('#code_compare_hidden_0');
    var code_compare_hidden_1 = $('#code_compare_hidden_1');

    var code_compare_type_chose = localStorage.getItem('code_compare_type_chose');

    var diff_num = $('#diff_num');

    var code_linenumber_div = $('.code_linenumber_div');

    if(typeof(code_compare_type_chose) != 'undefined')
    {
        var chosedRadio = $('#' + code_compare_type_chose);
        if(chosedRadio.length > 0) {
            chosedRadio.prop('checked', true);
        }
    }
    var comparing_label = $('#comparing_label');
    function changed() {
        var diffnum = 0;
        if(window.diffType == 'diffOriginal')
        {
            fragment0 = code_compare_hidden_0.clone();
            fragment1 = code_compare_hidden_1.clone();
            fragment0 = fragment0.removeAttr('style').removeAttr('id')[0];
            fragment1 = fragment1.removeAttr('style').removeAttr('id')[0];
            hljs.highlightElement(fragment0);
            hljs.highlightElement(fragment1);
            diffnum = '#';
        }
        else
        {
            var diff = Diff[window.diffType](code_compare_hidden_0.text(), code_compare_hidden_1.text());
            var fragment0 = $('<pre></pre>');
            var fragment1 = $('<pre></pre>');
            for (var i=0; i < diff.length; i++) {

                if (diff[i].added && diff[i + 1] && diff[i + 1].removed) {
                    var swap = diff[i];
                    diff[i] = diff[i + 1];
                    diff[i + 1] = swap;
                }

                var node;
                if (diff[i].removed) {
                    node = $('<del></del>');
                    node.text(diff[i].value);
                    fragment0.append(node);

                    node = $('<del></del>');
                    node.text(diff[i].value.replace(/\S/g, ' '));
                    fragment1.append(node);
                    diffnum ++;
                } else if (diff[i].added) {
                    node = $('<ins></ins>');
                    node.text(diff[i].value);
                    fragment1.append(node);

                    node = $('<ins></ins>');
                    node.text(diff[i].value.replace(/\S/g, ' '));
                    fragment0.append(node);
                    diffnum ++;
                } else {
                    node = document.createTextNode(diff[i].value);
                    fragment0.append(node);
                    node = document.createTextNode(diff[i].value);
                    fragment1.append(node);
                }
            }
        }
        code_compare_0.empty();
        code_compare_1.empty();
        code_compare_0.append(fragment0);
        code_compare_1.append(fragment1);

        diff_num.text(diffnum);
        comparing_label.hide();

        AddLineNumber(code_compare_0);
        AddLineNumber(code_compare_1);
    }
    $(document).ready(function() {
        onDiffTypeChange(document.querySelector('#settings [name="diff_type"]:checked'));
        changed();
        AddLineNumber(code_linenumber_div);
    });


    function onDiffTypeChange(radio) {
        localStorage.setItem('code_compare_type_chose', $(radio).attr('id'), {expire: 1});
        window.diffType = radio.value;
//        document.title = "Diff " + radio.value.slice(4);
    }

    $(document).on('change', '.diff_type', function(e){
        comparing_label.show();
        onDiffTypeChange(e.target);
        setTimeout(function(){changed();}, 100);
    });
</script>
{include file="../../csgoj/view/public/code_line_number" /}
<style type="text/css">
    del {
        text-decoration: none;
        color: #b30000;
        background: #fadad7;
    }
    ins {
        background: #eaf2c2;
        color: #406619;
        text-decoration: none;
    }
    .code_linenumber_div
    {
        overflow-x: auto;
    }
    .wrap_pre
    {
        white-space:pre-wrap;
        white-space:-moz-pre-wrap;
        white-space:-pre-wrap;
        white-space:-o-pre-wrap;
        word-wrap:break-word;
        /*word-break;break-all;*/
    }
    .panel-body
    {
        overflow-y: auto;
        display:block;
        padding: 0;
    }
</style>