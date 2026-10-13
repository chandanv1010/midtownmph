<?php if(isset($mediaList) && is_array($mediaList) && count($mediaList)){ ?>
    <?php if(isset($style) && ($style == 'grid-2' || $style == 'grid-3' || $style == 'grid-4' || $style == 'grid-5'|| $style == 'grid-6')){ ?>
        <?php 
                    if ($style == 'grid-2') $class = 'uk-grid-width-large-1-2';
            else    if ($style == 'grid-3') $class = 'uk-grid-width-large-1-3';
            else    if ($style == 'grid-4') $class = 'uk-grid-width-large-1-4';
            else    if ($style == 'grid-5') $class = 'uk-grid-width-large-1-5';
            else    if ($style == 'grid-6') $class = 'uk-grid-width-large-1-6';
        ?>
        <ul class="uk-grid uk-grid-medium uk-grid-width-1-2 <?php echo $class ?> dt_list-media dt_grid_responsive" data-uk-grid-match="{target: '.ht2109_grid_match'}">
            <?php foreach ($mediaList as $keyPost => $valPost) { ?>
                <?php        
                    $title = $valPost['title'];
                    $href = $valPost['canonical'].HTSUFFIX;
                    $image = $valPost['image'];
                    $description = base64_decode($valPost['description']);
                    // $content = cutnchar(strip_tags($valPost['content']), 250);
                ?>
            <li class="mb25">
                <article class="uk-clearfix dt_media article-21 art-bd por">
                    <div class="thumb_ratio style-43"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-cover img-zoomin"><img src="<?php echo $image ?>" alt=""></a></div>
                    <div class="info ht2109_grid_match">
                        <h3 class="title"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="line-2"><?php echo $title ?></a></h3>
                        <div class="description dt_item_desc_2 line-3 mb20"><?php echo $description ?></div>
                        <a href="<?php echo $href ?>" title="<?php echo $title ?>" class="abs-readmore">Xem chi tiết >></a>
                    </div>
                </article>
            </li>
            <?php } ?>
        </ul>
    <?php }else if(isset($style) && $style == 'list'){ ?>
        
    <?php }else if(isset($style) && $style == 'scroll'){ ?>
        
    <?php }else{ ?>

    <?php } ?>
<?php } ?>

