<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<section class="artcatalogue floor">
	<div class="uk-container uk-container-center">
		<h1 class="cat_heading_1 txt-center mb20">
            <?php echo $detailCatalogue['title'] ?>
        </h1>
		<?php if ($detailCatalogue['description'] != ''): ?>
		    <div class="dt_item_desc_1 mb20"><?php echo base64_decode($detailCatalogue['description']) ?></div>
		<?php endif ?>
		<?php if (isset($articleList) && check_array($articleList)): ?>
			<section class="panel-body">
				<?php 
                    $style = 'grid-3';
                    $locale = request()->getLocale();
                    $readmore = [
                        'vi' => 'Xem chi tiết',
                        'en' => 'See details',
                        'tq' => '查看更多'
                    ];
                    $image = [
                        'vi' => 'ảnh',
                        'en' => 'images',
                        'tq' => '图片'
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
                        <?php foreach ($articleList as $k => $valPost) { ?>
                            <?php 
                                $count = 0; 
                                $canonical = request()->getLocale().'/'.$valPost['canonical'].HTSUFFIX;
                            ?>
                            <div class="artile-album">
                                <h3 class="title mb10">
                                    <a href="<?php echo $canonical; ?>" title="" class="dt_item_title_2 link line-2"><?php echo $valPost['title'] ?></a>
                                </h3>
                                <ul class="uk-grid uk-grid-medium uk-grid-width-1-2 <?php echo $class ?> dt_list-article dt_grid_responsive" data-uk-grid-match="{target: '.ht2109_grid_match'}">
                                    
                                        <?php        
                                            $extract_album = json_decode($valPost['extract_album'], true);
                                            $temp = [];
                                            if($extract_album){ 
                                                foreach($extract_album as $key => $val){ 
                                                    foreach($val['list'] as $k => $v){ 
                                                        if($v['image']){ 
                                                            $temp[] = [
                                                                'name' => $v['name'],
                                                                'image' => $v['image'],
                                                            ];
                                                            $count++;
                                                        } 
                                                    } 
                                                } 
                                            }
                                        ?>
                                        <?php foreach ($temp as $k => $v) { ?>
                                            <?php if($k < 3){ ?>
                                                <li class="mb25">
                                                    <div class="dt_article">
                                                        <div class="thumb_ratio style-43 mb10">
                                                            <a href="<?php echo $canonical; ?>" title="" class="image img-cover img-zoomin">
                                                                <img src="<?php echo $v['image'] ?>" alt="<?php echo $v['name'] ?>">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        <?php  } ?>
                                    
                                </ul>
                                <h3 class="title mb20" style="text-align: right;">
                                    <a href="<?php  echo $canonical;  ?>" title="<?php echo $valPost['title'] ?>" class="dt_item_title_2 link line-2"><?php echo $readmore[$locale] ?> (<?php echo $count ?> <?php echo $image[$locale] ?>)</a>
                                </h3>
                            </div>
                        <?php } ?>
                    <?php }else if(isset($style) && $style == 'list'){ ?>
                        
                    <?php }else if(isset($style) && $style == 'scroll'){ ?>
                        
                    <?php }else{ ?>

                    <?php } ?>
                <?php } ?>
			</section>
			<div class="dt_pagination">
	            <?php echo (isset($pagination)) ? $pagination : ''; ?>
	        </div>
		<?php endif ?>
        <?php if ($detailCatalogue['content'] != ''): ?>
            <div class="dt_item_desc_1 mb20"><?php echo base64_decode($detailCatalogue['content']) ?></div>
        <?php endif ?>
	</div>
</section>
