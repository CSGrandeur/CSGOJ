function TtFormatter(user_volume, explain=false) {
    user_volume = parseInt(user_volume);
    let ret_html = '';
    if(!isNaN(user_volume) && user_volume > 10) {
        let tt_kind = user_volume % 100;
        let tt_state = Math.floor(user_volume / 100 + 0.00001) % 100;
        let tt_level = Math.floor(user_volume / 10000 + 0.00001) % 100;
        let color, content;
        switch(tt_kind) {
            case 10: color='success';   content = 'Trial Member'; break;
            case 20: color='info';      content = 'Div.3'; break;
            case 30: color='warning';   content = 'Div.2'; break;
            case 40: color='danger';    content = 'Div.1'; break;
        }
        ret_html += `<strong class='text-${color}'>${content}</strong>`
        if(tt_level == 10) {
            ret_html += ' - Team A';
        } else if(tt_level == 20) {
            ret_html += ' - Team B';
        }
        if(explain) {
            ret_html += '<strong> in School Training Team</strong>';
        }
        if(tt_state == 10) {
            ret_html += ' (Retired)';
        }
    }
    return ret_html;
}