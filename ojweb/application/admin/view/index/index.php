<div class="container">
<div id="indexCarousel" class="carousel slide">
	<!-- 轮播（Carousel）指标 -->
	<ol class="carousel-indicators">
		<?php for($i = 0; $i < 3; $i ++) { ?>
			<li data-target="#indexCarousel" data-slide-to="{$i}" <?php echo ($i == 0 ? "class='active'" : ""); ?> ></li>
		<?php } ?>
	</ol>
	<!-- 轮播（Carousel）项目 -->
	<div class="carousel-inner">
		<?php for($i = 0; $i < 3; $i ++) { ?>
			<a class="item <?php echo ($i == 0 ? "active" : ""); ?>"
			   href="<?php echo ((isset($carousel[$i]['attribute']) && strlen($carousel[$i]['attribute']) != 0) ? $carousel[$i]['href'] : '#'); ?>"
			   target="_blank">
				<img src="<?php echo ((isset($carousel[$i]['content']) && strlen($carousel[$i]['content']) != 0) ? $carousel[$i]['content'] : ("/static/image/carousel_default/carousel$i.png")); ?>" alt="First slide">
			</a>
		<?php } ?>
	</div>
	<!-- 轮播（Carousel）导航 -->
	<a class="left carousel-control" href="#indexCarousel" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#indexCarousel" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
<br/>
<p class="text-right">
	<a href="__HOME__/index/news_list">More...</a>
</p>
<?php foreach($news as $new){ ?>
	<div class="list-group index_news_div">
		<div class="list-group-item limit_header">
			<a href="__OJ__/index/news_detail?id={$new['news_id']}" >
				<h4 class="list-group-item-heading">{$new['title']}</h4>
			</a>
			<p class="help-block">
				Update Time：{$new['time']}&nbsp;&nbsp;&nbsp;&nbsp;
				Recent Edit：{$new['user_id']}&nbsp;&nbsp;&nbsp;&nbsp;
			</p>
			<div class="list-group-item-text limit_content index_news_content">{$new['content']|htmlspecialchars_decode|stripslashes}</div>
		</div>
	</div>
<?php } ?>
</div>