<script type="text/javascript">
	$(window).keydown(function(e) {
		if (e.keyCode == 116 && !e.ctrlKey) {
			if(window.event){
				try{e.keyCode = 0;}catch(e){}
				e.returnValue = false;
			}
			e.preventDefault();
			$('.bootstraptable_refresh_local').bootstrapTable('refresh');
		}
	});
</script>