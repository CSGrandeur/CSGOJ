<script>
	$(document).ready(function(){
		$.get(
			'/csgoj/problemset/problem?pid=1000&ajaxuser=isun_voj&ajaxtoken=c295ae8ba0980d2b951660a728b6071',
			{},
			function(ret){
				console.log(ret);
			}
		)
	})

</script>