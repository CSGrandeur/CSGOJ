<div><h4 style="display: inline; ">Problem Tried (<strong class="text-warning"><?php echo count($triedlist); ?></strong>): </h4>(Contests not included)</div>
<div class="md_display_div">
    <table>
        <thead><tr><?php for($i = 0; $i < $problem_oneline; $i ++): ?><th></th><?php endfor; ?></tr></thead>
        <tbody>
        <tr>
            <?php
            $i = 0;
            foreach($triedlist as $solved)
            {
                echo "<td><a href='/".request()->module()."/problemset/problem?pid=".$solved['problem_id']."'>".$solved['problem_id']."</a></td>";
                $i ++;
                if($i == $problem_oneline)
                {
                    $i = 0;
                    echo "</tr><tr>";
                }
            }
            echo "</pre>";
            ?>
        </tr>
        <tr><tr><?php for($i = 0; $i < $problem_oneline; $i ++): ?><th></th><?php endfor; ?></tr>
        </tbody>
    </table>
</div>