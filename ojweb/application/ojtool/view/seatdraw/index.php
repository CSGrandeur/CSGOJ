<h1>机位抽签</h1>

<div class="btn-group" role="group">
        <button class="btn btn-func btn-warning button_fullscreen"><i class="bi bi-arrows-fullscreen"></i>&nbsp;全屏</button>
        <button class="btn btn-func btn-outline-dark button_room">录入房间/区域</button>
        <button class="btn btn-func btn-outline-dark button_team">录入队伍</button>
        <button class="btn btn-func btn-outline-danger button_clear">清空缓存</button>
        <button class="btn btn-func btn-success button_draw">按种子设定座位</button>
        <button class="btn btn-func btn-primary button_export" btype="school" disabled>单位序导出</button>
        <button class="btn btn-func btn-primary button_export" btype="team_id" disabled>ID序导出</button>
        {if $Think.session.user_id }
        <button class="btn btn-func btn-danger button_import_contest" disabled title="比赛管理页面预先生成的队伍密码会赋值给抽签结果team_id对应的队伍">导入到比赛</button>
        {/if}
</div>
<div id="seatdraw_div_fullscreen">
<div id="seatdraw_div">
    <div id="seatdraw_table_toolbar">
        <div class="input-group" role="group">
            <button class="btn btn-lg btn-success text-draw-go" id="seatdraw_button">开始！</button>
            <span class="input-group-text text-draw-go">随机种子：</span>
            <input type="text" class="form-control text-draw-go" id="seatdraw_seed" placeholder="随机种子" value=1024>
        </div>
    </div>
    <table
        id="seatdraw_table"
        data-toggle="table"
        data-toolbar="#seatdraw_table_toolbar"
        data-pagination="false"
        data-search="false"
        data-sortable="false"
        data-classes="table table-no-bordered table-dark table-hover"
        data-pagination-h-align="left"
        data-pagination-detail-h-align="right"
        data-search-align="center"
    >
        <thead>
        <tr>
            <th data-field="idx"        data-align="center" data-valign="middle"  data-sortable="false" data-width="55" data-formatter="FormatterIndex" >序</th>
            <th data-field="name"       data-align="center" data-valign="middle"  data-sortable="false"                                                 >队名</th>
            <th data-field="school"     data-align="center" data-valign="middle"  data-sortable="false"                                                 >单位</th>
            <th data-field="tmember"    data-align="center" data-valign="middle"  data-sortable="false" data-width="240"                               >成员</th>
            <th data-field="coach"      data-align="center" data-valign="middle"  data-sortable="false" data-width="100"                                >教练</th>
            <th data-field="tkind"      data-align="center" data-valign="middle"  data-sortable="false" data-width="60" data-formatter="FormatterTkind" >类型</th>
            <th data-field="room"       data-align="center" data-valign="middle"  data-sortable="false" data-width="100" data-formatter="FormatterRoom" >分区</th>
            <th data-field="team_id"    data-align="center" data-valign="middle"  data-sortable="false" data-width="80" data-formatter="FormatterTeamId">队号</th>
        </tr>
        </thead>
    </table>
</div>
</div>

<div class="modal fade" id="room_show_modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"  >
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">房间/区域</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>    
      <div class="modal-body">
        <div class="modal-body" id="room_show_modal_content">
                <div class="mb-3">
                    <label for="room_input" class="form-label">每行一个分区，由“#”或“\\t”隔开的<code>[房间名]#[起始编号]#[结束编号]</code>或<code>[房间名]#[容量]</code>（统一使用其中一种形式）<br/>可直接从Excel表格复制后粘贴于此</label>
                    <textarea class="form-control" id="room_input" rows=6 placeholder="房间A#1#30 或 区域B#35"></textarea>
                </div>
                <h3>房间信息：<span class="text-danger" id="seat_num_span">0</span>机位</h3>
                <table
                    id="room_info_table"
                    data-toggle="table"
                    data-pagination="false"
                    data-method="get"
                    data-search="false"
                    data-sortable="false"
                    data-detail-view="true"
                    data-detail-view-by-click="true"
                    data-detail-view-icon="false"
                    data-classes="table table-no-bordered table-hover"
                    data-pagination-h-align="left"
                    data-pagination-detail-h-align="right"
                    data-search-align="center"
                >
                    <thead>
                    <tr>
                        <th data-field="idx"        data-align="center" data-valign="middle"  data-sortable="false" data-width="55" data-formatter="FormatterIndex">序</th>
                        <th data-field="room_name"  data-align="center" data-valign="middle"  data-sortable="false"  >名称</th>
                        <th data-field="seat_start" data-align="center" data-valign="middle"  data-sortable="false" data-width="100"  >起始编号</th>
                        <th data-field="seat_end"   data-align="center" data-valign="middle"  data-sortable="false" data-width="100" >结束编号</th>
                        <th data-field="seat_num"   data-align="center" data-valign="middle"  data-sortable="false" data-width="100" >容量</th>
                    </tr>
                    </thead>
                </table>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success room_submit">提交</button>
        <button type="button" class="btn btn-primary room_cancel">关闭</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="team_show_modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl"  >
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">队伍信息</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>    
    <div class="modal-body">
        <div class="modal-body" id="team_show_modal_content">
                <div class="mb-3">
                    <label for="team_input" class="form-label">每行一个队伍，由“#”或“\\t”隔开的<code>[队名]#[校名]#[成员]#[教练]#[队伍类型]#[自定义标签]</code><br/>可直接从Excel表格复制后粘贴于此</label>
                    <textarea class="form-control" id="team_input" rows=20 placeholder="一个队名#X大学#姓名A、姓名B、姓名C#教练Y#0#广东省"></textarea>
                </div>
            </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-success team_submit">提交</button>
        <button type="button" class="btn btn-primary team_cancel">关闭</button>
      </div>
    </div>
  </div>
</div>

{include file="public/js_toolbox" /}
{js href="__STATIC__/ojtool/js/seatdraw.js" /}

<style>
    .text-draw-go {
        font-size: 32px;
    }
    #seatdraw_button {
        width: 160px;
    }
    #seatdraw_div {
        max-width: 1280px;
        margin: auto;
    }
    #seatdraw_div_fullscreen {
        overflow-y: auto;
    }
</style>


