const pro_sample_split_reg = /\n##CASE##\n/m;
function ProblemSampleHtml(sample_in_str, sample_out_str, hlevel=4, is_input_dom=false) {
    let sample_in_list = sample_in_str.split(pro_sample_split_reg);
    let sample_out_list = sample_out_str.split(pro_sample_split_reg);
    let sample_num_max = Math.max(sample_in_list.length, sample_out_list.length);
    let sample_html = ``;

    for(let i = 0; i < sample_num_max; i ++) {
        sample_html += OneSample(
            i, 
            i < sample_in_list.length ? DomSantize(sample_in_list[i]) : '',
            i < sample_out_list.length ? DomSantize(sample_out_list[i]) : '',
            hlevel, 
            is_input_dom
        );
        console.log(sample_html);
    }
    if(sample_num_max == 0) {
        sample_html = "<h${hlevel}>No Sample</h${hlevel}>"
    }
    return sample_html;
}
function ResetSampleIdx(problem_sample_div) {
    problem_sample_div.find('.ith_case').each((idx, item) => {
        item.innerText = idx;
    });
}
function OneSample(i, sample_in_item, sample_out_item, hlevel=4, is_input_dom=false) {
    let ith_info = `cs="${i}"`;
    let sample_txt_area_type = is_input_dom ? "textarea rows=6" : "pre";
    let clss = is_input_dom ? "form-control sample_text_input" : "";
    let func_btn = is_input_dom ? `
        <div class="btn-group-vertical sample_operator_btn">
            <button type="button" class="btn btn-xs btn-success up_sample_btn" title="move up">⬆</button>
            <button type="button" class="btn btn-xs btn-danger del_sample_btn" title="double click to delete">Del</button>
            <button type="button" class="btn btn-xs btn-warning down_sample_btn" title="move down">⬇</button>
        </div>
        ` : "";
    return `
    <div class="sample_item_div" ${ith_info}>
        <h${hlevel} class="sample_header">#<span class="ith_case">${i}</span> </h${hlevel}>
        <div class="sample_row" ${ith_info}>
            <div class="sample_col" class="sample_input">
                <div class="sample_title_div"><h${hlevel}><strong>Input</strong></h${hlevel}><button type="button" class="btn btn-xs btn-success sample_copy" >Copy</button></div>
                <${sample_txt_area_type} class="sampledata sample_input_area ${clss}" ${ith_info} stype="input" >${sample_in_item}</${sample_txt_area_type}>
            </div>
            <div class="sample_col" class="sample_output">
                <div class="sample_title_div"><h${hlevel}><strong>Output</strong></h${hlevel}><button type="button" class="btn btn-xs btn-success sample_copy" >Copy</button></div>
                <${sample_txt_area_type} class="sampledata sample_output_area ${clss}" ${ith_info} stype="output" >${sample_out_item}</${sample_txt_area_type}>
            </div>
            ${func_btn}
        </div>
    </div>
    `
}
document.addEventListener('click', (e) => {
    if(e.target.classList.contains("sample_copy")) {
        let target_sample = e.target.parentNode.parentNode.getElementsByClassName('sampledata')[0];
        if(typeof(target_sample) != 'undefined' && target_sample != null) {
            let cs = target_sample.getAttribute('cs');
            let stype = target_sample.getAttribute('stype');
            if(ClipboardWrite(target_sample instanceof HTMLTextAreaElement ? target_sample.value : target_sample.innerText)) {
                alertify.success(`Copied #${cs} <strong>${stype}</strong>`);
            }
        }
    }
});
