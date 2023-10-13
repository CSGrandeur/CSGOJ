<div class="row ipcheck_div">
    {for start="0" end="2"}
    <?php
    if($i == 0)
    {
        $colorType = 'danger';
        $checktype = 'Single user login with different IPs';
    }
    else
    {
        $colorType = 'warning';
        $checktype = 'Multiple users login with same IP';
    }
    ?>
    <div class="col-lg-6 col-md-6 ">
        <div class="panel panel-{$colorType}">
            <div class="panel-heading">
                <h3 class="panel-title">{$checktype}</h3>
            </div>
            <div class="panel-body" id="checktype{$i}">

            </div>
        </div>
    </div>
    {/for}
</div>
<input type="hidden" name="cid" id="contest_id_input" value="{$contest['contest_id']}" >

<script type="text/javascript">
    let checktype0 = $('#checktype0');
    let checktype1 = $('#checktype1');
    let cid = $('#contest_id_input').val();
    function RefreshResults()
    {
        $.get(
            'ipcheck_ajax',
            {
                'cid': cid
            },
            function(ret)
            {
                if(ret['code'] == 1)
                {
                    let data = ret['data'];
                    let userIps = data['userIps'];
                    let ipUsers = data['ipUsers'];
                    checktype0.empty()
                    let appendContent = "<table class='table'>";
                    for(let key in userIps)
                    {
                        appendContent += "<thead><tr><th>" + key + "</th><th>" +
                            userIps[key]['name'] + "</th></tr></thead>" +
                            "<tbody>";
                        for(let ipith in userIps[key]['ips'])
                        {
                            let ip = userIps[key]['ips'][ipith];
                            appendContent += "<tr><td>" + ip['ip'] + "</td><td>" + ip['time'] + "</td></tr>";
                        }
                        appendContent += "</tbody>";
                    }
                    checktype0.append(appendContent);

                    checktype1.empty()
                    appendContent = "<table class='table'>";
                    for(let key in ipUsers)
                    {
                        appendContent += "<thead><tr><th>" + key + "</th><th></th></tr></thead><tbody>";
                        for(let userith in ipUsers[key])
                        {
                            let user = ipUsers[key][userith];
                            appendContent += "<tr><td>" + user['team_id'] + ": " + user['name'] + "</td><td>" + user['time'] + "</td></tr>";
                        }
                        appendContent += "</tbody>";
                    }
                    checktype1.append(appendContent);
                }
                else
                {
                    alertify.error(ret['msg']);
                }
            },
            'json'
        );
    }

    $(document).ready(function() {
        RefreshResults();
    });

</script>

<style type="text/css">
    .panel-body
    {
        overflow-y: auto;
        display:block;
        padding: 0;
    }
</style>