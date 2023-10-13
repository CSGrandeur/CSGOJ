<h1><span id="cid"></span><span id="ctitle"></span></h1>
<input type="hidden" id="page_info"
    OJ_MODE="{$OJ_MODE}"
    cid="{$contest['contest_id']}"
    ctitle="{$contest['title']}"
>
<script>
    let page_info = csg.getdom("#page_info");
    let sc = {
        OJ_MODE: page_info.getAttribute("OJ_MODE"),
        cid: page_info.getAttribute("cid"),
        ctitle: page_info.getAttribute("ctitle"),
    };

    
    csg.docready(function(){
        // csgn.
    });
</script>