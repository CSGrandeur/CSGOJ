<h1>{$teaminfo['team_id']} Information</h1>
<hr/>

<div class="md_display_div" id="info_left">
    <table>
        <tbody>
            <tr><td width="300px">Contest ID:</td><td><a href="__CPC__/contest/contest?cid={$contest['contest_id']}">{$contest['contest_id']}</a></td></tr>
            <tr><td width="300px">Contest:</td><td>{$contest['title']}</td></tr>
            
            <tr><td>Team ID: </td><td>{$teaminfo['team_id']}</td></tr>
            <tr><td>School: </td><td>{$teaminfo['school']}</td></tr>
            <tr><td>Team: </td><td>{$teaminfo['name']}</td></tr>
            <tr><td>Room : </td><td>{$teaminfo['room']}</td></tr>
            <tr><td>Member: </td><td>{$teaminfo['tmember']}</td></tr>
            <tr><td>Coach : </td><td>{$teaminfo['coach']}</td></tr>
        </tr>
    </table>
</div>