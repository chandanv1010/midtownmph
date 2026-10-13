<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
	$model = new App\Models\AutoloadModel();
    $language = request()->getLocale();
 	

	$slide['data'] = $model->_get_where([
		'select' => 'tb1.id, tb2.title, tb1.image, tb2.description, tb2.url',
		'table' => 'slide as tb1',
		'join' => [
			['slide_translate as tb2','tb1.id = tb2.objectid','inner']
		],
		'where' => [
			'tb1.deleted_at' => 0,
			'tb1.catalogueid' => 16,
			'tb2.language' => $language,
		]
	], TRUE);


?>



<?php if (isset($slide['data']) && check_array($slide['data'])): ?>
	<section class="project_panel">
		<div class="panel-head">
			<div class="uk-flex uk-flex-center">
				<div class="cat_heading_1 text-white bg_heading br6 style-2"><?php echo $general['footer_title'] ?></div>
			</div>
		</div>
		<?php if (isset($slide['data']) && check_array($slide['data'])): ?>
			<div class="panel-body">
				<?php
					/*slide owl*/
				    $owlInit = array(
				        'margin' => 10,
				        'lazyload' => true,
				        'nav' => true,
				        'navText' => ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
				        'autoplay' => true,
				        'smartSpeed' => 1000,
				        'autoplayTimeout' => 3000,
				        'autoplayHoverPause' => true,
				        'dots' => true,
				        'loop' => false,
				        // 'rewind' => true,
				        'responsive' => array(
				            0 => array(
				                'items' => 2,
				            ),
				            480 => array(
				                'items' => 2,
				            ),
				            768 => array(
				                'items' => 3,
				            ),
				            960 => array(
				                'items' => 3,
				            ),
				        )
				    );
				?>

				<section class="owl-slide mb-common">
					<div class="uk-container uk-container-center">
						<div class="owl-carousel owl-theme" data-owl="<?php echo base64_encode(json_encode($owlInit)); ?>">
							<?php foreach ($slide['data'] as $key => $val) {?>
								<?php
									$title = $val['title'];
									$href = $val['url'];
									$image = $val['image'];
									$description = cutnchar(strip_tags($val['description']), 100);

								?>
							<div class="item">
								<article class="uk-clearfix article-2 dt_shadow_1 br6">
								    <div class="thumb_ratio style-11">
								    	<a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-cover img-zoomin"><img src="<?php echo $image ?>" alt="<?php echo $title; ?>"></a>
							    	 	<div class="info dt_overlay_info_item">
									        <h3 class="title"><a href="<?php echo $href ?>" target="_blank" title="<?php echo $title ?>" class="line-2 dt_item_title_2 txt_center"><?php echo $title ?></a></h3>
									    </div>
								    </div>
								</article>
							</div>
							<?php } ?>
						</div>
					</div>
				</section>
			</div>
		<?php endif ?>
	</section>
 <?php endif ?>
