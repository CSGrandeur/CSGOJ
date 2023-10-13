{if isset($contest) }
    {if $contest_user && $running == 1 && (in_array($contest['private'] % 10, [0, 1]) || !IsAdmin('source_browser') && !$isContestAdmin && (!isset($isContestStaff) || !$isContestStaff)) }
    <a href="/{$module}/{$controller}/submit?cid={$contest['contest_id']}&pid={$apid}" class="a_noline">
        <button type="button" class="btn btn-default">Submit Page</button>
    </a>
    {else /}
    <a href="javascript:void(null)" class='disabled_problem_submit_button a_noline' 
    info_str="{if IsAdmin('source_browser')} You are source browser! {elseif isset($isContestStaff) && $isContestStaff /} You are contest staff! {else /}Please login before submit!{/if}"
    >
        <button type="button" class="btn btn-default" title="Could Not Submit!" >Submit Page</button>
    </a>
    {/if}
    {if ($contestStatus == 2) && ($OJ_MODE == 'online' || IsAdmin('administrator')) }
    &nbsp;&nbsp;
    <a href="__OJ__/problemset/summary?pid={$problem['problem_id']}" class="a_noline">
        <button type="button" class="btn btn-default">Summary</button>
    </a>
    &nbsp;&nbsp;
    <a href="{$GIT_DISCUSSION}?discussions_q={$problem['problem_id']}" class="a_noline" target="_blank">
        <button type="button" class="btn btn-default">Discussion</button>
    </a>
    {/if}
    {if $OJ_STATUS=='exp' && ($ALLOW_TEST_DOWNLOAD || IsAdmin()) }
    &nbsp;&nbsp;
    <a href="/{$module}/{$controller}/testdata?cid={$contest['contest_id']}&pid={$apid}" class="a_noline">
        <button type="button" class="btn btn-default">TestData</button>
    </a>
    {/if}
{else/}
    {if session('?user_id') }
        <a href="__OJ__/problemset/submit?pid={$problem['problem_id']}" class="a_noline">
            <button type="button" class="btn btn-default">Submit Page</button>
        </a>
        {else /}
        <a href="javascript:void(null)" class='disabled_problem_submit_button a_noline'>
            <button type="button" class="btn btn-default" title="Please login before submit!" >Submit Page</button>
        </a>
    {/if}
    &nbsp;&nbsp;
    <a href="__OJ__/problemset/summary?pid={$problem['problem_id']}" class="a_noline">
        <button type="button" class="btn btn-default">Summary</button>
    </a>
    &nbsp;&nbsp;
    <a href="{$GIT_DISCUSSION}?discussions_q={$problem['problem_id']}" class="a_noline" target="_blank">
        <button type="button" class="btn btn-default">Discussion</button>
    </a>
{/if}
