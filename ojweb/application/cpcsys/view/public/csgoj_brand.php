<style type="text/css">
	.cpc-navbar.cpc-navbar-index,
	.cpc-navbar.cpc-navbar-admin
	{
		background-color: #f0ad4e;
	}
</style>
<?php
$brand_class = request()->module();
if($brand_class == 'index')
	$subtitle = 'Contest System';
else if($brand_class == 'admin')
	$subtitle = 'Admin Panel';
?>
<div class="cpc-navbar cpc-navbar-{$brand_class}">
	<a class="cpc-brand" href="/"><?php echo config('OJ_ENV.OJ_NAME'); ?></a>
	<span class="cpc-brand-subtitle">{$subtitle}</span>
	<button type="button" class="navbar-toggle collapsed cpc-nav-toggle" data-toggle="collapse" data-target=".sidebar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
</div>
