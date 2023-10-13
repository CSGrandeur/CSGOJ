{if $showCarousel}
    <br/>
    <div id="indexCarousel" class="carousel slide" data-ride="carousel">
        <!-- 轮播（Carousel）指标 -->
        <ol class="carousel-indicators">
            {for start="0" end="3"}
                <li data-target="#indexCarousel" data-slide-to="{$i}" {if($i == 0)} class='active' {/if} ></li>
            {/for}
        </ol>
        <!-- 轮播（Carousel）项目 -->
        <div class="carousel-inner" role="listbox">
            {for start="0" end="3"}
                <div class="item {if($i == 0)} active {/if}">
                    <img src="{if(strlen($carousel['src'][$i]) > 0)} {$carousel['src'][$i]}{else/}__IMG__/carousel_default/carousel{$i}.png{/if}" alt="{$carousel['header'][$i]}">

                    <div class="carousel-caption">
                        <h3>{$carousel['header'][$i]}</h3>
                        <p>{$carousel['content'][$i]}</p>
                    </div>
                </div>
            {/for}
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
{/if}
    <br/>
    <div class="row">
    {foreach($categoryTitles as $ca => $titles)}
    <div class="col-md-12 col-lg-6 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">{$homeCategory[$ca]}<a style="float:right;" href="__HOME__/{$ca}">more...</a></div>
            <ul class="list-group">
                {if $titles != null}
                {foreach $titles as $new }
                <li class="list-group-item">
                    <div class="row">
                        <a class="news-left" href="__HOME__/{$ca}/detail?nid={$new['news_id']}" title="{$new['title']}">{$new['title']}</a>
                        <span class="news-right font-equal-width"><?php echo isset($new['time']) && strlen($new['time']) >= 16 ? substr($new['time'], 0, 16) : '0000-00-00 00:00'; ?></span>
                        {if $now - strtotime($new['time']) < 1296000}
                        <i class="news-new">New</i>
                        {/if}
                    </div>
                </li>
                {/foreach}
                {/if}
            </ul>
        </div>
    </div>
    {/foreach}
    </div>


<style type="text/css">
    .carousel-control.left {
        background-image: none;
    }
    .carousel-control.right {
        background-image: none;
    }
    .news-new
    {
        color: red;
    }
</style>