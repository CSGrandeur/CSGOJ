<?php
$brand_class = $module;
if($OJ_STATUS=='exp' && $module == 'csgoj') {
    $brand_class = 'expsys';
}
if($brand_class == 'expsys') {
    $subtitle = 'Experiment System';
}
else if($brand_class == 'cr') {
    $subtitle = 'Contest Registration';
}
else if($brand_class == 'index') {
    $subtitle = 'Home Page';
}
else if($brand_class == 'admin') {
    $subtitle = 'Admin Panel';
}
else if($brand_class == 'cpcsys') {
    $subtitle = 'Contest System';
}
else if($brand_class == 'tt') {
    $subtitle = 'Training Team';
}
else if($brand_class == 'ojtool') {
    $subtitle = 'Tools';
}
else {
    $subtitle = 'Online Judge';
}
?>
<div class="cpc-navbar cpc-navbar-{$brand_class}">
    <a href="/"><img src="__IMG__/global/badge.png" title="badge" class="cpc-brand-img"></a>
    <a class="cpc-brand" href="/">{$OJ_NAME}</a><br/>
    <span class="cpc-brand-subtitle">{$subtitle}</span>
    <button type="button" class="navbar-toggle collapsed cpc-nav-toggle" data-toggle="collapse" data-target=".sidebar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
</div>
