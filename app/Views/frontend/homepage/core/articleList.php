<?php   
    $locale = request()->getLocale();
    $readmore = [
    'vi' => 'Xem chi tiết',
    'en' => 'See details',
    'tq' => '查看更多'
]
?>

<?php if(isset($articleList) && is_array($articleList) && count($articleList)){ ?>
    <?php if(isset($style) && ($style == 'grid-2' || $style == 'grid-3' || $style == 'grid-4' || $style == 'grid-5'|| $style == 'grid-6')){ ?>
        <?php 
                    if ($style == 'grid-2') $class = 'uk-grid-width-large-1-2';
            else    if ($style == 'grid-3') $class = 'uk-grid-width-large-1-3';
            else    if ($style == 'grid-4') $class = 'uk-grid-width-large-1-4';
            else    if ($style == 'grid-5') $class = 'uk-grid-width-large-1-5';
            else    if ($style == 'grid-6') $class = 'uk-grid-width-large-1-6';
        ?>
        <ul class="uk-grid uk-grid-medium uk-grid-width-1-2 <?php echo $class ?> dt_list-article dt_grid_responsive" data-uk-grid-match="{target: '.ht2109_grid_match'}">
            <?php foreach ($articleList as $keyPost => $valPost) { ?>
                <?php        
                    $title = $valPost['title'];
                    $href = $valPost['canonical'].HTSUFFIX;
                    $image = $valPost['image'];
                    $description = base64_decode($valPost['description']);
                    $description = cutnchar(strip_tags($description), 250);
                    // $content = cutnchar(strip_tags($valPost['content']), 250);
                ?>
            <li class="mb25">
                <div class="dt_article">
                    <div class="thumb_ratio style-43 mb10">
                        <a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-cover img-zoomin"><img src="<?php echo $image ?>" alt="<?php echo $title ?>"></a>
                    </div>
                    <div class="info dt_info_1 ht2109_grid_match">
                        <h3 class="title mb10"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="dt_item_title_2 link line-2"><?php echo $title ?></a></h3>
                        <div class="dt_item_desc_2 line-3 mb10"><?php echo $description ?></div>
                        <a href="<?php echo $href ?>" title="<?php echo $title ?>" class="dt_readmore_2"><?php echo $readmore[$locale] ?></a>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>
    <?php }else if(isset($style) && $style == 'list'){ ?>
        
    <?php }else if(isset($style) && $style == 'scroll'){ ?>
        
    <?php }else{ ?>

    <?php } ?>
<?php } ?>
