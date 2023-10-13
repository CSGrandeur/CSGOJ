{include file="../../csgoj/view/problemset/problem_header" /}
{include file="../../csgoj/view/public/mathjax_js" /}
{include file="../../csgoj/view/public/code_highlight" /}
<link rel="stylesheet" type="text/css" href="__STATIC__/csgoj/oj_problem.css" />
<script type="text/javascript" src="__STATIC__/csgoj/oj_problem.js"></script>
<div>
    <?php
    if($problem['source'] == "<p>" . strip_tags($problem['source']) . "</p>") {
        $problem['source'] = strip_tags($problem['source']);
        $problem['source'] = "<a href='/csgoj/problemset#search=" . $problem['source'] . "'>" . $problem['source'] . "</a>";
    }
    $items = [
        'Description'   => ['article', $problem['description']],
        'Input'         => ['article', $problem['input']],
        'Output'        => ['article', $problem['output']],
        'Sample Input'  => ['pre', $problem['sample_input']],
        'Sample Output' => ['pre', $problem['sample_output']],
        'Hint'          => ['article', $problem['hint']],
        'Source'        => ['article', $problem['source']],
    ];
    if(isset($problem['author']) && $problem['author'] != null && strlen(trim($problem['author'])) > 0) {
        $items['Author'] = ['article', $problem['author']];
    }
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

<script type="text/javascript">
    $('.disabled_problem_submit_button').on('click', function(){
        alertify.error('Please login before submit!');
    });
</script>
