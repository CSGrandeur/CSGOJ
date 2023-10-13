{if IsAdmin('contest', $contest['contest_id']) || $printManager }
<span class="alert alert-info" style="display: block">打印前需<strong><a href="__IMG__/tutorial/set_default_print.gif" target="_blank">参考链接</a></strong>设置默认打印机为目标打印机。第一次使用时，<strong>页面上方</strong>会提示安装打印控件，用来对接页面打印逻辑和系统打印机，打印前需下载安装。<br/><strong>请使用最新浏览器，建议使用Chrome。</strong><br/>
Chrome94之后禁止了本地网络请求设置，需<strong><a href="__IMG__/tutorial/lodop_chrome_cors.png" target="_blank">关闭该功能</a></strong>：在地址栏输入“chrome://flags”，找到“Block insecure private network requests.”改为“Disabled”。
</span>
{/if}
<div id="print_status_toolbar">
    <div class="form-inline fake-form" role="form">
        <button id="status_refresh" type="submit" class="btn btn-default"><i class="glyphicon glyphicon-refresh icon-refresh"></i></button>
        <button id="print_status_clear" type="submit" class="btn btn-default">Clear</button>
        <button id="print_status_ok" type="submit" class="btn btn-default">Filter</button>
        &nbsp;&nbsp;
        <div class="form-group">
            <span>Team ID:</span>
            <input id="team_id_input" name="team_id" class="form-control print_status_filter" type="text"
                   value="{if !$printManager /} {$contest_user} {/if}"
            style="max-width:180px;">
            &nbsp;&nbsp;
            <span>Room:</span>
            <input id="room_ids" name="room_ids" class="form-control print_status_filter" placeholder="Use ',' to split multiple rooms" type="text" {if isset($room_ids)}value="{$room_ids}" {/if} style="width:220px;">
            &nbsp;&nbsp;
            <span>Status:</span>
            <select name="print_status" class="form-control print_status_filter">
                <option value="-1" selected="true">
                    All
                </option>
                {foreach($printStatus as $key=>$value)}
                <option value="{$key}">
                    {$value}
                </option>
                {/foreach}
            </select>
            &nbsp;&nbsp;
        </div>
        {if $isContestAdmin || $printManager }
        <div class="form-group">
            <label for="color_blind_mode">Auto Print (<strong id="auto_print_interval_span" class="text-info">20</strong>s)：</label>
            <input type="checkbox" id="auto_print_box" data-size="small" name="auto_print_box" >
        </div>
        {/if}
    </div>
</div>
<div class="bootstrap_table_div">
<table
        class="bootstrap_table_table"
        id="print_status_table"
          data-toggle="table"
          data-url="__CPC__/contest/print_status_ajax?cid={$contest['contest_id']}"
          data-pagination="true"
          data-page-list="[20]"
          data-page-size="20"
          data-side-pagination="server"
          data-method="get"
          data-striped="true"
          data-sort-name="print_status_show"
          data-sort-order="asc"
          data-pagination-v-align="bottom"
          data-pagination-h-align="left"
          data-pagination-detail-h-align="right"
          data-toolbar="#print_status_toolbar"
          data-query-params="queryParams"
          data-cookie="true"
          data-cookie-id-table="{$OJ_SESSION_PREFIX}print-status-{$contest['contest_id']}-{$team_id}"
          data-cookie-expire="1m"
>
    <thead>
    <tr>
        <th data-field="print_id" data-align="center" data-valign="middle"  data-sortable="true" data-width="70">PrintID</th>
        <th data-field="team_id_show" data-align="center" data-valign="middle"  data-sortable="false" data-width="100">Team</th>
        <th data-field="code_length" data-align="right" data-valign="middle"  data-sortable="false" data-width="80">Code Length</th>
        <th data-field="room" data-align="center" data-valign="middle"  data-sortable="false"  >Room</th>
        <th data-field="in_date" data-align="center" data-valign="middle"  data-sortable="false"  data-width="160">Submit Time</th>
        <th data-field="print_status_show" data-align="center" data-valign="middle"  data-sortable="true" data-width="120" >Print Status</th>
        {if($printManager && !$isContestAdmin)}
        <th data-field="do_print" data-align="center" data-valign="middle"  data-sortable="false" data-width="70" >Print</th>
        {/if}
        {if $printManager}
        <th data-field="do_deny" data-align="center" data-valign="middle"  data-sortable="false" data-width="70" >Deny</th>
        {/if}
    </tr>
    </thead>
</table>
</div>
<input
    type="hidden"
    id="print_status_page_information"
    cid="{if(isset($contest))}{$contest['contest_id']}{else/}x{/if}"
    team_id="{$contest_user}"
    show_code_url="{$show_code_url}"
>
{include file="public/code_highlight_manual" /}
<script type="text/javascript">
    //table related
    var $table = $('#print_status_table');
    var print_status_clear_btn = $('#print_status_clear');
    var print_status_ok_btn = $('#print_status_ok');
    var $refresh = $('#status_refresh');
    var print_status_page_information = $('#print_status_page_information');
    var team_id = print_status_page_information.attr('team_id');
    var print_status_toolbar = $('#print_status_toolbar');


    var auto_print_box = $('#auto_print_box');
    var auto_print_interval = 20;
    var auto_print_time = auto_print_interval;
    var auto_print_timout_id;
    var auto_print_interval_span = $('#auto_print_interval_span');

    var room_ids = $('#room_ids');
    
    $(document).ready(function(){
        auto_print_box.bootstrapSwitch();
        if(auto_print_box.bootstrapSwitch('state') == true)
            AutoPrint();
    });
    auto_print_box.on('switchChange.bootstrapSwitch', function(event, state) {
        if (state == true)
        {
            alertify.confirm('自动打印将会发送页面内所有Waiting的打印任务，并自动刷新列表接收新任务，该状态下Room将禁止修改。<br/><strong>请确认Room已设置好并已执行Filter，且任务按 Print Status 列升序（默认），即表头有朝上的小三角。</strong>',
                function(){
                    room_ids.attr('readonly', 'readonly');
                    DoAutoPrint();
                },
                function(){
                    alertify.message("Canceled");
                    auto_print_box.bootstrapSwitch('state', false, false);
                }
            );
        }
        else
        {
            room_ids.removeAttr('readonly');
            clearTimeout(auto_print_timout_id);
            auto_print_time = auto_print_interval;
            auto_print_interval_span.text(auto_print_time);
        }
    });
    $(window).keydown(function(e) {
        if (e.keyCode == 116 && !e.ctrlKey) {
            if(window.event){
                try{e.keyCode = 0;}catch(e){}
                e.returnValue = false;
            }
            e.preventDefault();
            RefreshTable();
        }
    });
    var lastQuery = [];
    print_status_clear_btn.on('click', function() {
        SetPrintFilter(true);
    });
    print_status_ok_btn.on('click', function () {
        // back to page 1 to prevent blank page after data filtered.
        $table.bootstrapTable('refresh', {pageNumber: 1});
    });
    $refresh.on('click', function(){
        RefreshTable();
    });
    function RefreshTable()
    {
        print_status_toolbar.find('input[name]').each(function () {
            $(this).val(lastQuery[$(this).attr('name')]);
        });
        print_status_toolbar.find('select[name]').each(function () {
            $(this).val(lastQuery[$(this).attr('name')]);
        });
        $table.bootstrapTable('refresh');

    }
    function queryParams(params) {
        print_status_toolbar.find('input[name]').each(function () {
            params[$(this).attr('name')] = $(this).val();
            lastQuery[$(this).attr('name')] = $(this).val();
        });
        print_status_toolbar.find('select[name]').each(function () {
            params[$(this).attr('name')] = $(this).val();
            lastQuery[$(this).attr('name')] = $(this).val();
        });
        return params;
    }
    $('.fake-form').on('keypress', function(e){
        // it'ts not a real form, so overload 'enter' to take effect.
        if(event.keyCode == 13){
            print_status_ok_btn.click();
        }
    });
    $table.on('click-cell.bs.table', function(e, field, val, row, td){
        var tdContent = td.children();
        if(field == 'print_status_show')
        {
            if(tdContent.attr('showcode') == 1)
            {
                $.get(
                    print_status_page_information.attr('show_code_url'),
                    {
                        'print_id': row['print_id'],
                        'cid': print_status_page_information.attr('cid')
                    },
                    function(ret){
                        if(ret['code'] == 1)
                        {
                            var data = ret['data'];
                            var showcode_pre = $("<pre>" + data['auth'] + data['source'] +"</pre>")[0];
                            hljs.highlightElement(showcode_pre);
                            alertify.Showcode(showcode_pre);
                        }
                        else
                        {
                            alertify.error(ret['msg']);
                            return false;
                        }
                    }
                );
            }
        }
        else if(field == 'do_deny')
        {
            if(typeof(tdContent.attr('class')) != 'undefined' && tdContent.attr('class').indexOf('do_deny') >= 0)
            {
                $.get(
                    'print_deny_ajax',
                    {
                        'print_id': row['print_id'],
                        'cid': print_status_page_information.attr('cid')
                    },
                    function(ret){
                        if(ret['code'] == 1)
                        {
                            alertify.success(ret['msg'])
                            $('#do_deny_' + row['print_id']).parent().html("-");
                            var print_status = $('#print_status_' + row['print_id']);
                            print_status.text("Denied").removeClass("btn-info").addClass("btn-danger");
                        }
                        else
                        {
                            alertify.error(ret['msg']);
                        }
                    }
                );
            }
        }
        else if(field == 'do_print')
        {
            if(typeof(tdContent.attr('class')) != 'undefined' && tdContent.attr('class').indexOf('do_print') >= 0 && typeof(tdContent.attr('disabled')) == 'undefined')
            {
                StartSinglePrint(row['print_id']);
            }
        }
    });
    function StartSinglePrint(print_id)
    {
        //正式开始打印一个代码，执行“代码内容请求”和“打印状态更新”两个ajax会话
        $.get(
            'print_code_plain_content_ajax',
            {
                'print_id': print_id,
                'cid': print_status_page_information.attr('cid')
            },
            function(ret){
                if(ret['code'] == 1)
                {
                    PrintCode(ret['data']);
                    UpdatePrintStatus(print_id);
                }
                else
                {
                    alertify.error(ret['msg']);
                }
            }
        );
    }
    function AutoPrint()
    {
        //开始自动打印的倒计时
        auto_print_time --;
        if(auto_print_time <= 0)
        {
            RefreshTable();
        }
        else
        {
            auto_print_timout_id = setTimeout(function(){AutoPrint();}, 1000);
        }
        auto_print_interval_span.text(auto_print_time);
    }
    $table.on('post-body.bs.table', function(){
        //状态表格更新完毕时判断是否需要自动打印代码。AutoPrint()会触发表格刷新。
        if(auto_print_box.bootstrapSwitch('state') == true)
            DoAutoPrint();
    });
    function DoAutoPrint()
    {
        clearTimeout(auto_print_timout_id);
        //执行当前页面Waiting的代码自动打印。
        var print_flag = false;
        $(".print-Waiting").each(function(){
            StartSinglePrint($(this).attr('print_id'));
            print_flag = true;
        });
        auto_print_time = 5;
        if(print_flag != true)
            auto_print_time = auto_print_interval;
        AutoPrint();
    }
    function UpdatePrintStatus(print_id)
    {
        $.get(
            'print_do_ajax',
            {
                'print_id': print_id,
                'cid': print_status_page_information.attr('cid')
            },
            function(ret){
                if(ret['code'] == 1)
                {
                    alertify.success(ret['msg']);
                    $('#do_deny_' + print_id).parent().html("-");
                    var print_status = $('#print_status_' + print_id);
                    print_status.text("Printed").removeClass("btn-info").removeClass("btn-danger").addClass("btn-success");
                    button_delay($('#do_print_' + print_id), 9, 'Print', 'Next')
                }
                else
                {
                    alertify.error(ret['msg']);
                }
            }
        );
    }
    
// 处理搜索框动态anchor    
function SetPrintFilter(clear=false) {
    $('.print_status_filter').each(function(index, elem){
        let search_input = $(this);
        let search_name = search_input.attr('name');
        if(clear)
        {
            if(search_input.is('input'))
                search_input.val('');
            else
                search_input.val(-1);
            SetAnchor(null, search_name);
        } else {
            let search_str = GetAnchor(search_name);
            search_input.unbind('input').on('input', function() {
                SetAnchor(search_input.val(), search_name);
            });
            if(search_str !== null) {
                search_input.val(search_str);
            }
        }
    });
    $table.bootstrapTable('refresh', {pageNumber: 1});
}
$(document).ready(function(){
    SetPrintFilter();
});
</script>
<script type="text/javascript">
    if(!alertify.Showcode)
    {
        //init runinfo window.
        alertify.dialog('Showcode', function factory(){
            return {
                main:function(message){
                    this.message = message;
                },
                setup:function(){
                    return {
                        buttons:[{text: "cool !", key:27/*Esc*/}],
                        focus: { element:0 },
                        options: {
                            title: 'Print Request Code',
                            startMaximized: true
                        }
                    };
                },
                prepare:function(){
                    this.setContent(this.message);
                }
            };
        });
    }
</script>
{include file="contest/print_control" /}