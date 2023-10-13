<script type="text/javascript">
    function UrlGet()
    {
        var aQuery = window.location.href.split("?");
        var aGET = new Array();
        if(aQuery.length > 1)
        {
            var aBuf = aQuery[1].split("&");
            for(var i=0, iLoop = aBuf.length; i<iLoop; i++)
            {
                var aTmp = aBuf[i].split("=");
                aGET[aTmp[0]] = aTmp[1];
            }
        }
        return aGET;
    }
</script>