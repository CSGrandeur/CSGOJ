<h1 style="overflow: ellipsis;white-space: nowrap;max-width:1200px;">队伍照片：{$contest['title']}</h1>

<strong class="text-danger">批量上传文件名需与队伍ID一一对应，图片建议长:宽=3:2. 如更换图片请务必清理缓存.</strong>
<div class="input-group input-group-lg mb-3">
  <button id="team_image_batch_btn" class="btn btn-primary" disabled>批量上传</button>
  <input class="form-control" type="file" id="team_image_batch" multiple accept="image/jpg,image/png,image/jpeg,image/bmp">
</div>

<table
    id="team_image_table"
    data-toggle="table"
    data-buttons-align="left"
    data-sort-name="team_id"
    data-sort-order="asc"
    data-pagination="false"
    data-method="get"
>
    <thead>
    <tr>
        <th data-field="idx" data-align="center" data-valign="middle" data-sortable="true" data-width="30" data-formatter="IndexFormatter" >Idx</th>
        <th data-field="team_id" data-align="center" data-valign="middle" data-sortable="true" data-width="30">ID</th>
        <th data-field="school" data-align="left" data-valign="middle"  data-width="200">学校</th>
        <th data-field="name" data-align="left" data-valign="middle" data-width="200" >队名</th>
        <th data-field="tmember" data-align="center" data-valign="middle" >成员</th>
        <th data-field="coach" data-align="center" data-valign="middle"  >教练</th>
        <th data-field="room" data-align="center" data-valign="middle"  >房间/区</th>
        <th data-field="tkind" data-align="center" data-valign="middle" data-formatter="FormatterTkind" >类型</th>
        <th data-field="team_photo" data-align="center" data-valign="middle" data-formatter="TeamPhotoFormatter" >照片</th>
    </tr>
    </thead>
</table>
</div>
<input type='hidden' id='page_info' cid="{$contest['contest_id']}" contest_attach="{$contest['attach']}">

<div id="loading_div" class='overlay'>
    <div class="d-flex align-items-center" id="loading_spinner_div">
        <div id="loading_spinner" class="spinner-border ms-auto" aria-hidden="true"></div>
        <strong id="loading_text" role="status">&nbsp;上传中...</strong>
    </div>
</div>

<script>
let cid = parseInt($('#page_info').attr('cid'));
let contest_attach = $('#page_info').attr('contest_attach');
let team_image_table = $('#team_image_table');
let team_list, team_map, team_photo_map, flag_ready_cnt;
let loading_div = $('#loading_div'), loading_text = $('#loading_text');
let batch_file_list, batch_error_list, batch_ith, cnt_success;
function FormatterTkind(value, row, index) {
    let v = value === null ? 0 : value;
    let icon = "balloon", title_tip = "常规队", txtcolor="text-success";
    if(v == 1) {
        icon = "balloon-heart", title_tip = "女队", txtcolor="text-danger";
    } else if(v == 2) {
        icon = "star", title_tip = "打星队", txtcolor="text-primary";
    }
    return `<i class="${txtcolor} bi bi-${icon}" title="${title_tip}"></i>`;
}
function IndexFormatter(value, row, index) {
    return index + 1;
};
function TeamImageButtonHtml(team_id) {
    return `
    <button id="team_btn_${team_id}" class="btn btn-success" dclass="team_image_preview" team_id="${team_id}" url="/upload/contest_attach/${contest_attach}/team_photo/${team_id}.jpg">预览</button>
    <button id="team_btn_del_${team_id}" class="btn btn-danger" dclass="team_image_del" team_id="${team_id}" title="双击删除">删除</button>
    `;
}
function TeamUploadButtonHtml(team_id) {
    return `<button id="team_btn_${team_id}" class="btn btn-default">待上传</button>`;
}
function TeamPhotoFormatter(value, row, index) {
    let info_dom = '';
    if(row.team_id in team_photo_map) {
        info_dom = TeamImageButtonHtml(row.team_id);
    } else {
        info_dom = TeamUploadButtonHtml(row.team_id);
    }
    return `<div id="team_image_${row.team_id}">
        <div class="input-group input-group-sm mb-3">
            ${info_dom}
            <input class="form-control team_image_upload_input" type="file" accept="image/jpg,image/png,image/jpeg,image/bmp" dclass="team_image_upload_input" name="${row.team_id}.jpg" id="team_image_${row.team_id}" team_id="${row.team_id}">
        </div>
    </div>`;
}
function TeamPhotoUriOnError(img_obj) {
    $("#img_preview_div").text(`图片加载失败：${img_obj.getAttribute('src')}`);
}
function LoadReady() {
    flag_ready_cnt ++;
    if(flag_ready_cnt >= 2) {
        team_image_table.bootstrapTable('load', team_list);
    }
}
$(document).ready(function() {
    flag_ready_cnt = 0;
    $.get('contest_data_ajax?without_solution=1&cid=' + cid, function(ret) {
        if(ret.code == 1) {
            team_list = ret.data.team.filter((a) => !(a.privilege in {'admin':true, 'balloon':true, 'printer':true}));
            team_map = {};
            for(let i in team_list) {
                team_map[team_list[i].team_id] = team_list[i];
            }
            LoadReady();
        } else {
            alertify.error(ret.msg);
        }
    });
    $.get('team_image_list_ajax?&cid=' + cid, function(ret) {
        if(ret.code == 1) {
            team_photo_map = {};
            for(let i in ret.data) {
                team_photo_map[ret.data[i].file_name.replace('.jpg', '')] = ret.data[i];
            }
            LoadReady();
        } else {
            alertify.error(ret.msg);
        }
    });
});
function FileProcess(file, team_id, loading_show=true) {
    if (file.type.startsWith("image/")) {
        if(loading_show) {
            loading_div.show();
        }
        let reader = new FileReader();
        reader.addEventListener("load", function (e) {
            let data = e.target.result;
            let image = new Image();
            image.addEventListener("load", function (e) {
                let width = e.target.width;
                let height = e.target.height;
                let targetWidth = 1080;
                let ratio = width > targetWidth ? targetWidth / width : 1;
                let targetHeight = parseInt(Math.min(720, height * ratio));
                let mapping_height = targetHeight / ratio;
                let canvas = document.createElement("canvas");
                canvas.width = width * ratio;
                canvas.height = targetHeight;
                let context = canvas.getContext("2d");
                let sy = mapping_height < height ? Math.floor((height - mapping_height) * 0.5) : 0;
                context.drawImage(image, 0, sy, width, targetHeight / ratio, 0, 0, width * ratio, targetHeight);
                let jpgData = canvas.toDataURL("image/jpeg");
                let formData = new FormData();
                $.ajax({
                    url: 'team_image_upload_ajax',
                    type: 'post',
                    data: {
                        'team_photo': jpgData,
                        'cid': cid,
                        'team_id': team_id
                    },
                    success: function(ret) {
                        if(ret.code == 1) {
                            let btn_obj = document.getElementById(`team_btn_${team_id}`);
                            let btn_del = document.getElementById(`team_btn_del_${team_id}`);
                            if(btn_del == null) {
                                btn_obj.outerHTML = TeamImageButtonHtml(team_id);
                            }
                            // btn_obj.classList.remove('btn-default');
                            // btn_obj.classList.add('btn-success');
                            // btn_obj.setAttribute('dclass', 'team_image_preview');
                            // btn_obj.setAttribute('team_id', team_id);
                            // btn_obj.setAttribute('url', ret.data.file_url);
                            // btn_obj.innerText = '预览';
                            cnt_success ++;
                            if(loading_show) {
                                alertify.success("队伍图片已更新");
                            }
                        } else {
                            if(loading_show) {
                                alertify.error(ret.msg);
                            } else {
                                batch_error_list.push(`${file.name}: ${ret.msg}`);
                            }
                        }
                        if(loading_show) {
                            loading_div.hide();
                        }
                        batch_ith ++;
                        BatchProcessIth();
                    },
                    error: function(ret) {
                        alertify.error(ret.status);
                        if(loading_show) {
                            loading_div.hide();
                        } else {
                            batch_error_list.push(`${file.name}: ${ret.msg}`);
                        }
                        batch_ith ++;
                        BatchProcessIth();
                    }
                })
            });
            image.src = data;
        });
        reader.readAsDataURL(file);
    } else {
        alertify.alert("请选择图片文件！");
    }
}
function TeamImagePreview(btn_obj) {
    let team_id = btn_obj.getAttribute('team_id');
    let tparam = '';    // `?t=${new Date().getTime()}` 是否允许缓存图片
    alertify.alert(`队伍图片预览：${team_id}`, `<div id="img_preview_div" style="width:720px;height:480px;overflow:hidden; margin:auto;"><img style="width:100%;height:auto;" src="${btn_obj.getAttribute('url')}${tparam}" onerror="TeamPhotoUriOnError(this)" ></div>`).set('resizable',true).resizeTo(900, 650); 
    //     alertify.set('resizable', true);
    // // 调整窗口的宽度和高度
    // alertify.resizeTo('50%', '50%');
}
function TeamImageDel(btn_obj) {
    let team_id = btn_obj.getAttribute('team_id');
    $.post('team_image_del_ajax', {'cid': cid, 'team_id': team_id}, function(ret) {
        if(ret.code == 1) {
            alertify.success(`${team_id}图片已删除`);
            btn_obj.remove();
            document.getElementById(`team_btn_${team_id}`).outerHTML = TeamUploadButtonHtml(team_id);
        } else {
            alertify.error(ret.msg);
        }
    })
}
function dataURLtoBlob(dataurl) {
  let arr = dataurl.split(",");
  let mime = arr[0].match(/:(.*?);/)[1];
  let bstr = atob(arr[1]);
  let n = bstr.length;
  let u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new Blob([u8arr], { type: mime });
}
function BatchProcessIth() {
    if(batch_ith >= batch_file_list.length) {
        loading_div.hide();
        if(batch_error_list.length > 0) {
            alertify.alert('上传情况', `成功: ${cnt_success}, 失败: ${batch_file_list.length - cnt_success}<br/>${batch_error_list.join('<br/>')}`);
        }
        return;
    }
    let filename = batch_file_list[batch_ith].name, team_id = filename.substring(0, filename.lastIndexOf('.'));
    loading_text.text(` ${parseInt(batch_ith * 100 / batch_file_list.length)}%. 正在处理：${filename}`)
    if(!(team_id in team_map)) {
        batch_error_list.push(`${filename}: 文件名不在队伍ID中`);
        batch_ith ++;
        BatchProcessIth();
    } else {
        FileProcess(batch_file_list[batch_ith], team_id, false);
    }

}
document.addEventListener('change', function(e) {
    if(e.target.getAttribute('dclass') == 'team_image_upload_input') {
        loading_text.text('&nbsp;上传中...');
        batch_file_list = [];
        batch_error_list = [];
        batch_ith = 0;
        FileProcess(e.target.files[0], e.target.getAttribute('team_id'));
        e.target.value = '';
    }
});
document.addEventListener('click', function(e) {
    if(e.target.getAttribute('dclass') == 'team_image_preview') {
        TeamImagePreview(e.target);
    }
});
document.addEventListener('dblclick', function(e) {
    if(e.target.getAttribute('dclass') == 'team_image_del') {
        TeamImageDel(e.target);
    }
});
$('#team_image_batch_btn').click(function() {
    batch_file_list = $('#team_image_batch').prop('files');
    batch_error_list = [];
    batch_ith = 0;
    cnt_success = 0;
    loading_text.text('开始处理...');
    loading_div.show();
    BatchProcessIth();
});
$('#team_image_batch').change(function() {
    $('#team_image_batch_btn').attr('disabled', false);
});
</script>
<style>
#loading_spinner_div {
    position: absolute;
    top: 10vw;
    left: 40%;
    z-index: 21;
}
#loading_div {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(200,200,200,0.7);
    z-index: 20;
}
</style>