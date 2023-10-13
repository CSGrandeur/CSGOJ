{include file="../../csgoj/view/problemset/problem_header" /}
{include file="../../csgoj/view/public/mathjax_js" /}
{include file="../../csgoj/view/public/code_highlight" /}
<link rel="stylesheet" type="text/css" href="__STATIC__/csgoj/oj_problem.css" />
<script type="text/javascript" src="__STATIC__/csgoj/oj_problem.js"></script>
<div>
    <?php
    
    $items = [
        'Description'   => ['article', $problem['description']],
        'Input'         => ['article', $problem['input']],
        'Output'        => ['article', $problem['output']],
        'Sample Input'  => ['pre', $problem['sample_input']],
        'Sample Output' => ['pre', $problem['sample_output']],
        'Hint'          => ['article', $problem['hint']],
    ];
    foreach($items as $key=>$value)
    {
        ?>
        {if $key=='Sample Input'}
        <div name="Sample" class="md_display_div">
            <h2 class="text-primary">Sample</h2>
            <div class="sample_div">
                <div class="sample_row">
                    <div class="sample_col" class="sample_input">
                        <pre class="sampledata sample_input_area">{$value[1]|htmlspecialchars}</pre>
                    </div>
        {elseif $key=='Sample Output'}
                    <div class="sample_col" class="sample_output">
                        <pre class="sampledata sample_output_area">{$value[1]|htmlspecialchars}</pre>
                    </div>
                </div>
            </div>
        </div>
        {else /}
        <div name="{$key}" class="md_display_div">
            <h2 class="text-primary">{$key}</h2>
            {$value[1]}
        </div>
        {/if}
        <?php
    }
    ?>

</div>
<script>
    $(document).ready(function() {
        let sample_div = $('.sample_div');
        let sample_in_str = sample_div.find('.sample_input_area').text();
        let sample_out_str = sample_div.find('.sample_output_area').text();

        sample_div.html(ProblemSampleHtml(sample_in_str, sample_out_str));
    })
</script>

{include file="../../csgoj/view/problemset/problem_footer" /}


<input type="hidden" id="contest_status" value="{$contestStatus}">
<script type="text/javascript">
    $('.disabled_problem_submit_button').on('click', function(){
        var contestStatus = $('#contest_status').val();
        if(contestStatus == -1)
            alertify.error('Contest not started!');
        else if(contestStatus == 2)
            alertify.error('Contest ended!');
        else
            alertify.error($(this).attr('info_str'));
    });
</script>