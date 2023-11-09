{include file="../../csgoj/view/contest/index" /}
<script>
function FormatterContestTitle(value, row, index, field) {
    return "<a href='/ojtool/contestlive/ctrl?cid=" + row['contest_id'] + "'>" + value + "</a>";
}
</script>