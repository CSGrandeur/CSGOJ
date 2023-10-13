<div id="statistic_table_div">
<table
        id="statistic_table"
        data-toggle="table"
        data-striped="true"
>
    <thead>
    <tr>
        <th data-field="problem_id" data-align="center" data-valign="middle"  data-sortable="false" data-width="50">ID</th>
        {foreach($useStatus as $status)}
        <th data-field="{$ojRes[$status]}" data-align="center" data-valign="middle"  data-sortable="false" data-width="50">{$ojRes[$status]}</th>
        {/foreach}
        <th data-field="total" data-align="center" data-valign="middle"  data-sortable="false" data-width="50">Total</th>
        {foreach($useLanguage as $language)}
        <th data-field="{$language}" data-align="center" data-valign="middle"  data-sortable="false" data-width="50">{$language}</th>
        {/foreach}
    </tr>
    </thead>
    <tbody>
    {foreach($problemStatistic as $key=>$ps)}
    <tr>
        <td>{$ps['problem_id_show']}</td>
        {foreach($useStatus as $status)}
        <td>{$ps[$ojRes[$status]]}</td>
        {/foreach}
        <td class="info">{$ps['total']}</td>
        {foreach($useLanguage as $language)}
        <td>{$ps[$language]}</td>
        {/foreach}
    </tr>
    {/foreach}
    </tbody>
</table>
</div>
<script type="text/javascript">
    var statistic_table = $('#statistic_table');
    var statistic_table_div = $('#statistic_table_div');
    statistic_table.on('post-body.bs.table', function(){
        //处理rank宽度
        if(statistic_table[0].scrollWidth > statistic_table_div.width())
            statistic_table_div.width(statistic_table[0].scrollWidth + 20);
    });
</script>