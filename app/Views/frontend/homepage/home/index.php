<?php
    $model = new App\Models\AutoloadModel();
    $baseController = new App\Controllers\FrontendController();
    $language = request()->getLocale();
?>
<div id="homepage" class="homepage">
    <?php if(isset($general['homepage_banner_slide']) && !empty($general['homepage_banner_slide'])){ ?>
        <video style="width: 100%;" autoplay muted controls loop>
            <source src="<?php echo $general['homepage_banner_slide'] ?>" >
        </video>
    <?php }else{ ?>
        <?php echo view('frontend/homepage/common/mainslide') ?>
    <?php } ?>
	<div class="uk-container uk-container-center">
		<div class="dt_bg_grey">
		 	<div class="sologan-1 uk-text-center">
                <div class="txt_center">
                    <?php echo $general['homepage_slogan'] ?>
                </div>
			</div>
			<?php
				$catprd = isset($panel['home-prdcat'])? $panel['home-prdcat'] : [];
                if(isset($catprd['data']) && is_array($catprd['data']) && count($catprd['data'])){
                    foreach($catprd['data'] as $key => $val){
                        $children = $model->_get_where([
                            'select' => 'id',
                            'table' => 'product_catalogue',
                            'where' => ['parentid' => $val['id']]
                        ], TRUE);
                        $id = [];
                        if(isset($children) && is_array($children) && count($children)){
                            foreach($children as $keyChild => $valChild){
                                $id[] = $valChild['id'];
                            }
                        }
                        array_push($id, $val['id']);
                        if(isset($id) && is_array($id) && count($id)){
                            $catprd['data'][$key]['post'] = $model->_get_where([
                                'select' => 'tb1.id, tb2.title, tb2.canonical, tb1.image, tb1.price, tb1.bed, tb1.area, tb2.description, tb1.created_at, tb1.extra',
                                'table' => 'product as tb1',
                                'join' => [
                                    ['product_translate as tb2','tb1.id = tb2.objectid', 'inner'],
                                    ['object_relationship as tb3', 'tb1.id = tb3.objectid','inner']
                                ],
                                'where' => [
                                    'tb1.publish' => 1,
                                    'tb1.deleted_at' => 0,
                                    'tb2.language' => $language,
                                    'tb3.module' => 'product',
                                    'tb2.module' => 'product',
                                ],
                                'where_in' => $id,
                                'where_in_field' => 'tb3.catalogueid',
                                'group_by' => 'tb1.id',
                                'limit' => 9,
                                'order_by' => 'created_at desc'
                            ], TRUE);
                        }
                    }
                }
			?>
			<?php if (isset($catprd['data']) && is_array($catprd['data']) && count($catprd['data'])){ ?>
            <?php foreach($catprd['data'] as $key => $val){ ?>
				<section class="hp_categories_panel dt_panel <?php echo 'style_1_'.$key ?>">
					<div class="uk-container uk-container-center">
			        	<div class="cat_heading_1 before_left mb30"><a href="<?php echo $val['canonical'] ?>" title="<?php echo $val['title'] ?>"><?php echo $val['title'] ?></a></div>
			        	<?php /*<div class="cat_heading_2 mb30">Sản phẩm mới nhất</div>*/ ?>
                        <?php if(isset($val['post']) && is_array($val['post']) && count($val['post'])){ ?>
						<div class="uk-grid ukgrid-large home_list_cat">
							<?php
								foreach ($val['post'] as $keyS => $valS) {
									$title = $valS['title'];
					                $href = write_url($valS['canonical'], true, false);
					                $image = $valS['image'];
                                    $area = $valS['area'];
                                    $bed = $valS['bed'];
                                    $price = $valS['price'];
                                    $valS['extra'] = json_decode($valS['extra'],true);
					                $description = cutnchar(strip_tags(base64_decode($valS['description'])), 250);
					                $created_at = gmdate('M. d. Y / H:i', strtotime($valS['created_at']));
                                    $extra = [];
                                    if(isset($valS['extra']) && is_array($valS['extra']) && count($valS['extra'])){
                                        foreach ($valS['extra'] as $keyPostExtra => $valuePostExtra) {
                                            if(isset($extra_list) && is_array($extra_list) && count($extra_list)){
                                                foreach ($extra_list as $keyExtra => $valueExtra) {
                                                    if($valuePostExtra == $valueExtra['id']){
                                                        $extra[] = $valueExtra;
                                                    }
                                                }
                                            }
                                        }
                                    }
						 	    ?>
								<div class="mb20 uk-width-large-1-3">
									<article class="uk-clearfix article-1 no-shadown dt_product_item br6">
									    <div class="thumb uk-position-relative">
                                            <a href="<?php echo $href; ?>" class="image img-cover img-zoomin"><img src="<?php echo $image ?>" alt="<?php echo $title; ?>"></a>
                                            <div  class="dt_overlay_info_label">
                                                <ul class="uk-list uk-flex uk-flex-wrap dt_grid_responsive">
                                                    <?php if(isset($extra) && is_array($extra) && count($extra)){
                                                        foreach ($extra as $keyExtra => $valueExtra) {
                                                    ?>
                                                    <li class="mb5 mr10">
                                                        <div class="lb_item_1"><a href="<?php echo $valueExtra['canonical'].HTSUFFIX ?>" class="white" title="<?php echo $valueExtra['title'] ?>"><?php echo $valueExtra['title'] ?></a></div>
                                                    </li>
                                                    <?php }} ?>
                                                </ul>
                                            </div>
                                        </div>
									    <div class="info">
									        <?php /*<h3 class="title mb10"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="divne-2 dt_item_title_1"><?php echo $title ?></a></h3>*/ ?>

									        <div class="description line-3"><?php echo $title ?></div>
                                            <div class="utilities_item price">
                                                <div class="uk-flex uk-flex-middle">
                                                    <div class="icon">
                                                        <div class="image img-scaledown">
                                                            <img src="public/frontend/resources/img/icon/dollar.png" alt="dollar">
                                                        </div>
                                                    </div>
                                                    <?php
                                                        $baseController = new App\Controllers\FrontendController();
                                                        $language = request()->getLocale();
                                                    ?>
                                                     <?php $en_string = ($language == 'vi') ? '/ tháng'  : '/ month'  ?>
                                                     <div class="title"><?php echo dt_render_currency($price); ?> <?php echo (isset($val['id']) && $val['id'] == 18 ) ? $en_string : ''; ?> </div>
                                                </div>
                                            </div>
                                            <div class="utilities_item num_bed">
                                                <div class="uk-flex uk-flex-middle">
                                                    <div class="icon">
                                                        <div class="image img-scaledown">
                                                            <img src="public/frontend/resources/img/icon/bed.png" alt="bed">
                                                        </div>
                                                    </div>
                                                    <div class="title"><?php echo $bed ?></div>
                                                </div>
                                            </div>
                                            <div class="utilities_item area">
                                                <div class="uk-flex uk-flex-middle">
                                                    <div class="icon">
                                                        <div class="image img-scaledown">
                                                            <img src="public/frontend/resources/img/icon/icon-2.png" alt="icon">
                                                        </div>
                                                    </div>
                                                    <div class="title"><?php echo $area.' '.lang('app.AREA') ?> </div>
                                                </div>
                                            </div>
									    </div>
									</article>
                                </div>
							<?php } ?>
                                </div>
                        <?php } ?>
						<div class="uk-flex uk-flex-right">
							<a href="<?php echo $val['canonical']; ?>" title="<?php echo $val['title'] ?>" class="dt_btn_viewall"><?php echo $general['home_view'] ?></a>
						</div>
			        </div>
                    <?php echo ($key == 1) ? '<div class="blur"></div>'  : ''; ?>
				</section>
			<!-- dt_cat_prd_1 -->

            <?php }} ?>

			<!-- dt_cat_prd_2 -->
			<div class="">
                <div class="sologan">
    		 		<?php echo $general['homepage_intro'] ?>
    			</div>
            </div>

		</div>
	</div>

</div>
