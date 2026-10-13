<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
    $footer = get_panel([
		'locate' => 'footer',
		'language' => $language
	]);
?>
<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<div id="article_detail_page" class="page-body">
	<section class="index-section mb-common">
		<div class="uk-container uk-container-center">
			<h1 class="cat_heading_3 mb20"><?php echo $object['title'] ?></h1>
			<div class="entry-description va-content"><?php echo $object['description'] ?></div>
            <div class="list-album">
                <?php if(isset($object['extract_album'])){ ?>
                    <?php
                        function explodeMultiLanguage($string) {
                            if (empty($string)) {
                                return '';
                            }
                            
                            if (strpos($string, ':') !== false && strpos($string, '|') !== false) {
                                $result = [];
                                $parts = explode('|', $string);
                                
                                foreach ($parts as $part) {
                                    $part = trim($part);
                                    $keyValue = explode(':', $part, 2);
                                    
                                    if (count($keyValue) == 2) {
                                        $key = trim($keyValue[0]);
                                        $value = trim($keyValue[1]);
                                        $result[$key] = $value;
                                    }
                                }
                                
                                return $result;
                            }
                            
                            return $string;
                        }
                    ?>
                    <?php
                        $extract_album = json_decode($object['extract_album'], true);
                    ?>
                    <?php
                        
                    foreach($extract_album as $key => $val){ ?>
                        <?php 
                            if(count($val['list']) === 0) continue;
                            $locales = request()->getLocale();
                            $name_album = explodeMultiLanguage($val['name']);
                            $des_album = explodeMultiLanguage($val['description']);
                            $name_album = is_array($name_album) ? $name_album[$locales] : $val['name'];
                            $des_album = is_array($des_album) ? $des_album[$locales] : $val['description'];
                        ?>
                        <div class="extract_album mb20">
                            <div class="panel-head mb10">
                                <h3 class="heading-2">
                                    <span>
                                        <?php echo $name_album ?>
                                    </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="uk-grid uk-grid-small">
                                    <?php foreach($val['list'] as $k => $v){ ?>
                                        <?php 
                                            $name = explodeMultiLanguage($v['name']);
                                            $name = is_array($name) ? $name[$locales] : $v['name'];
                                        ?>
                                        <div class="uk-width-small-1-3 mb10">
                                            <div class="album-item">
                                                <a data-fancybox="images" href="<?php echo $v['image'] ?>" class="image img-cover" data-caption="<?php echo $name ?>">
                                                    <img src="<?php echo $v['image'] ?>" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="description mt10"><?php echo $des_album ?></div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php 
                $locales = request()->getLocale();
                $headings = [
                    'vi' => 'Bài viết liên quan',
                    'en' => 'Related articles',
                    'tq' => '相关文章'
                ];
            ?>
            <?php if(!isset($object['extract_album'])){ ?>
                <section class="relate_art">
                    <div class="cat_heading_3 mb15"><?php echo $headings[$locale]; ?></div>
                    <?php if (isset($article_relate) && check_array($article_relate)): ?>
                        <section class="panel-body">
                            <?php 
                                $_data = [
                                    'articleList' => $article_relate,
                                    'style' => 'grid-4',
                                ];
                                echo view('frontend/homepage/core/articleList', $_data, ['saveData' => true]);
                            ?>

                        </section>
                    <div class="dt_pagination">
                        <?php echo (isset($pagination)) ? $pagination : ''; ?>
                    </div>
                <?php endif ?>
                </section>
            <?php } ?>
		</div>
	</section>
</div>
