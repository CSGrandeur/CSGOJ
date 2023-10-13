{if $printManager && !$isContestAdmin }
{js href="__STATIC__/lodop/LodopFuncs.js" /}
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
	<embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<script type="text/javascript">
	$(document).ready(function(){
		setTimeout("InitLodop()", 500);
	});
	var LODOP;
	function InitLodop()
	{
		if (document.readyState!=="complete")
			setTimeout("InitLodop()", 500);
		else
			LODOP = getLodop(document.getElementById('LODOP_OB'), document.getElementById('LODOP_EM'));
	}
	function PrintCode(data)
	{
		CreatePrintPage(data);
//		LODOP.PREVIEW();
		LODOP.PRINT();
	}
	function CreatePrintPage(data) {
		var codes = data['source'].replace(/\t/g, '    ').split('\n');
		var code = '';
		for(var i = 0; i < codes.length; i ++)
		{
			code += pad0left(i + 1, 4) + '  ' + codes[i] + "\n";
		}
//		LODOP=getLodop();
		LODOP.PRINT_INITA(10,10,754,453,"Contest Code " + data['print_id']);
		LODOP.ADD_PRINT_TEXT(20,150,580,30, data['contest_title']);
		LODOP.SET_PRINT_STYLEA(0,"FontName",'Microsoft Yahei');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
		LODOP.SET_PRINT_STYLEA(0,"Horient",0);
		LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
		LODOP.ADD_PRINT_TEXT(25,35,100,22, "TeamID: "+data['team_id']);
		LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
		LODOP.SET_PRINT_STYLEA(0,"Horient",0);
		LODOP.ADD_PRINT_TEXT(63,20,700,310, code);
		LODOP.SET_PRINT_STYLEA(0,"FontName",'Consolas');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
		LODOP.SET_PRINT_STYLEA(0,"ItemType",4);
		LODOP.SET_PRINT_STYLEA(0,"Horient",0);
		LODOP.SET_PRINT_STYLEA(0,"Vorient",3);
		LODOP.SET_PRINT_STYLEA(0,"TextNeatRow", 1);
		LODOP.ADD_PRINT_LINE(53,13,53,725,0,1);
		LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
		LODOP.SET_PRINT_STYLEA(0,"Horient",3);
		LODOP.ADD_PRINT_LINE(404,13,404,725,0,1);
		LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
		LODOP.SET_PRINT_STYLEA(0,"Horient",3);
		LODOP.SET_PRINT_STYLEA(0,"Vorient",1);
//		LODOP.ADD_PRINT_TEXT(421,37,144,22,"Good Luck");
		LODOP.ADD_PRINT_TEXT(421,37,144,22,"         ");
		LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
		LODOP.SET_PRINT_STYLEA(0,"Vorient",1);
		LODOP.ADD_PRINT_TEXT(411,470,250,22,"Print ID: " + pad0left(data['print_id'], 8, '0') + "    Page # of &");
		LODOP.SET_PRINT_STYLEA(0,"ItemType",2);
		LODOP.SET_PRINT_STYLEA(0,"Horient",1);
		LODOP.SET_PRINT_STYLEA(0,"Vorient",1);
	}
</script>
{/if}