<div class="md_display_div" id="info_left">
    <table>
        <thead>
        <th>Rank</th><th>Solved</th><th>Submissions</th><th>School</th><th>Email</th>
        </thead>
        <tbody>
        <tr>
            <td width="300px">{$rank}</td>
            <td>{$solved}</td>
            <td>{$submit}</td>
            <td>{$baseinfo['school']|htmlspecialchars}</td>
            <td><?php echo htmlspecialchars(str_replace('@', '#', $baseinfo['email'])); ?></td>
        </tr>
    </table>
</div>