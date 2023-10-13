<li role="presentation" class="dropdown {if strpos($action, 'topic') === 0 } active {/if}">
    <a href="/{$module}/{$contest_controller}/topic_list?cid={$contest['contest_id']}" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        Clarification <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        {if $running}
        <li>
            <a href="/{$module}/{$contest_controller}/topic_add?cid={$contest['contest_id']}">Add Topic</a>
        </li>
        {/if}
        <li>
            <a href="/{$module}/{$contest_controller}/topic_list?cid={$contest['contest_id']}">Topic List</a>
        </li>
    </ul>
</li>