<?php if(isset($general['homepage_banner_slide']) && !empty($general['homepage_banner_slide'])){ ?>
	<video style="width: 100%;" autoplay muted controls loop>
		<source src="<?php echo $general['homepage_banner_overview'] ?>" >
	</video>
<?php }else{ ?>
	<div class="product-detail-panel">
		<div class="the-big-pic por ovh">
			<div class="thumb_ratio">
				<div class="image img-cover">
					<?php echo render_img(['src' => $detailCatalogue['image'], 'alt' => $detailCatalogue['title']]) ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
	$language = request()->getLocale();
	$model = new App\Models\AutoloadModel();
	$children = $model->_get_where([
		'select' => 'tb1.id, tb2.title, tb2.description, tb1.parentid',
		'table' => 'article_catalogue as tb1',
		'join' => [
			['article_translate as tb2', 'tb2.objectid = tb1.id', 'inner'],
		],
		'where' => [
			'tb1.publish' => 1,
			'tb1.deleted_at' => 0,
			'tb2.module' => 'article_catalogue',
			'tb2.language' => $language,
			'tb1.lft >=' => $detailCatalogue['lft'],
			'tb1.rgt <=' =>$detailCatalogue['rgt'],
		],
      'group_by' => 'tb1.id'
	], TRUE);
	$children = recursive($children);
	if(isset($children) && is_array($children) && count($children)){
		foreach($children as $key => $val){
			if(isset($val['children']) && is_array($val['children']) && count($val['children'])){
				foreach($val['children'] as $keyChild => $valChild){
					if(isset($valChild['children']) && is_array($valChild['children']) && count($valChild['children'])){
						foreach($valChild['children'] as $keyS => $valS){
							$children[$key]['children'][$keyChild]['children'][$keyS]['post'] = $model->_get_where([
								'select' => 'tb1.id, tb2.title, tb2.canonical, tb2.description, tb1.album',
								'table' => 'article as tb1',
								'join' => [
									['article_translate as tb2','tb2.objectid = tb1.id','inner']
								],
								'where' => [
									'tb1.publish' => 1,
									'tb1.deleted_at' => 0,
									'tb2.language' => $language,
									'tb2.module' => 'article',
									'tb1.catalogueid' => $valS['id'],
								],
								'order_by' => 'order desc'
							], TRUE);
						}
					}
				}
			}
		}
	}

?>
<section class="overview-panel">
	<div class="uk-container uk-container-center">
		<div class="dt_bg_grey">
			<div class="sologan-1">
				<div class="">
					<div class="txt_center dt_desc_catalogue">
						<?php echo base64_decode($detailCatalogue['description']); ?>
					</div>
				</div>
			</div>
			<section class="overview-body">
				<div class="location mb50">
					<div class="heading">
						<?php echo $general['overview_location'] ?>
					</div>
					<div class="dt_map_overview mb20">
						<?php echo $detailCatalogue['map'] ?>
					</div>
					<div class="description">
						<?php echo base64_decode($detailCatalogue['content']); ?>
					</div>
				</div>

				<?php if(isset($children) && is_array($children) && count($children)){ ?>
				<?php foreach($children as $key => $val){ ?>
				<?php if(isset($val['children']) && is_array($val['children']) && count($val['children'])){ ?>
				<?php foreach($val['children'] as $keyChild => $valChild){ ?>
				<!-- mặt bằng -->
				<div class="ground-panel">
					<div class="heading">
						<?php echo $valChild['title']; ?>
					</div>
					<div class="detail va-detail-select">
						<div class="uk-grid uk-grid-medium">
							<?php if(isset($valChild['children']) && is_array($valChild['children']) && count($valChild['children'])){ ?>
							<div class="uk-width-medium-1-2 uk-width-large-1-2">
								<div class="detail-item uk-flex uk-flex-middle">
									<span><?php echo $general['overview_home'] ?>:</span>
									<select class="dt_nice_select va-select-category">
										<?php foreach($valChild['children'] as $keyS => $valS){ ?>
										<option <?php  echo ($keyS == 0) ? 'selected' : '' ?> value="<?php echo $valS['id'] ?>" ><?php echo $valS['title'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<?php } ?>
							<div class="uk-width-medium-1-2 uk-width-large-1-2">
								<?php if(isset($valChild['children'][0]['post']) && is_array($valChild['children'][0]['post']) && count($valChild['children'][0]['post'])){ ?>
								<div class="detail-item uk-flex uk-flex-middle">
									<span><?php echo $general['overview_now'] ?>:</span>
									<div class="the-select-2">
										<select class="dt_nice_select va-select-article">
											<?php foreach($valChild['children'][0]['post'] as $keyPost => $valPost){ ?>
											<option value="<?php echo $valPost['id'] ?>" <?php  echo ($keyPost == 0) ? 'selected' : '' ?>><?php echo $valPost['title'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php if(isset($valChild['children'][0]['post']) && is_array($valChild['children'][0]['post']) && count($valChild['children'][0]['post'])){ ?>
					<?php foreach($valChild['children'][0]['post'] as $keyPost => $valPost){ ?>
					<?php
						$album = json_decode($valPost['album'], TRUE);
						if($keyPost > 0) break;
					?>
					<div class="add_element">
						<div class="panel-slide mb30">
							<div class="swiper-container mySwiper2 dt_overview_sw_<?php echo $keyChild+1; ?>" data-slide="dt_overview_sw_<?php echo $keyChild+1 ?>">
								<div class="swiper-wrapper">
									<?php if(isset($album) && is_array($album) && count($album)){ ?>
									<?php foreach($album as $keyA => $valA){ ?>
									<div class="swiper-slide">
										<div class="img-cover image">
											<img src="<?php echo $valA ?>" alt="<?php echo $valS['title'] ?>-<?php echo $keyS + 1; ?>" />
										</div>
									</div>
									<?php }} ?>
								</div>
							</div>
							<div class="small-side">
								<div thumbsSlider="" class="swiper-container mySwiper dt_overview_sw_sm_<?php echo $keyChild + 1 ?> " data-slide="dt_overview_sw_sm_<?php echo $keyChild + 1 ?>">
									<div class="swiper-wrapper">
										<?php if(isset($album) && is_array($album) && count($album)){ ?>
										<?php foreach($album as $keyPost => $valA){ ?>
										<div class="swiper-slide img-cover swiper-slide-small">
											<img src="<?php echo $valA; ?>" alt="<?php echo $valS['title'] ?>-<?php echo $keyS + 1; ?>" />
										</div>
										<?php }} ?>
									</div>
								</div>
								<div class="swiper-button-next">
									<i class="fa fa-caret-right" aria-hidden="true"></i>
								</div>
								<div class="swiper-button-prev">
									<i class="fa fa-caret-left" aria-hidden="true"></i>
								</div>
							</div>
						</div>
						<div class="description">
							<?php echo base64_decode($valPost['description']); ?>
						</div>
					</div>
					<?php }} ?>
				</div>
				<?php }}}} ?>
			</section>
		</div>
	</div>
</section>
<!-- Initialize Swiper -->
<script>
var dt_overview_sw_sm_1 = new Swiper(".dt_overview_sw_sm_1", {
loop: true,
spaceBetween: 10,
slidesPerView: 3,
freeMode: true,
watchSlidesVisibility: true,
watchSlidesProgress: true,
// navigation: {
//   nextEl: ".swiper-button-next",
//   prevEl: ".swiper-button-prev",
// },
});
var dt_overview_sw_1 = new Swiper(".dt_overview_sw_1", {
loop: true,
spaceBetween: 10,
navigation: {
nextEl: ".swiper-button-next",
prevEl: ".swiper-button-prev",
},
thumbs: {
swiper: dt_overview_sw_sm_1,
},
});
var swiper_sm_2 = new Swiper(".dt_overview_sw_sm_2", {
loop: true,
spaceBetween: 10,
slidesPerView: 3,
freeMode: true,
watchSlidesVisibility: true,
watchSlidesProgress: true,
// navigation: {
//   nextEl: ".swiper-button-next",
//   prevEl: ".swiper-button-prev",
// },
});
var swiper_2 = new Swiper(".dt_overview_sw_2", {
loop: true,
spaceBetween: 10,
navigation: {
nextEl: ".swiper-button-next",
prevEl: ".swiper-button-prev",
},
thumbs: {
swiper: swiper_sm_2,
},
});
var swiper_sm_3 = new Swiper(".dt_overview_sw_sm_3", {
loop: true,
spaceBetween: 10,
slidesPerView: 3,
freeMode: true,
watchSlidesVisibility: true,
watchSlidesProgress: true,
// navigation: {
//   nextEl: ".swiper-button-next",
//   prevEl: ".swiper-button-prev",
// },
});
var swiper_3 = new Swiper(".dt_overview_sw_3", {
loop: true,
spaceBetween: 10,
navigation: {
nextEl: ".swiper-button-next",
prevEl: ".swiper-button-prev",
},
thumbs: {
swiper: swiper_sm_3,
},
});
</script>

<script>
	$(document).on('change', '.va-select-category', function(){
		let cat = $(this).val();
		let art = $(this).parents('.va-detail-select').find('.va-select-article').val();
		render_data(art, cat, $(this))
	})

	$(document).on('change', '.va-select-article', function(){
		let art = $(this).val();
		console.log(1)
		let cat = $(this).parents('.va-detail-select').find('.select-category').val();
		render_data(art, 0, $(this))
	})

	function render_data(art = 0, cat = 0, _this){
		let ajaxUrl = 'Ajax/Frontend/Filterproduct/detail_item';
		setTimeout(function(){
		    $.ajax({
		        type: "POST",
		        url: ajaxUrl,
				data: {
 				  id: art, catid : cat,
 			   },
		        dataType: "json",
		        cache: false,
		        success: function(json){
		            // do smt
					let parent = _this.closest('.detail')
					let prFriend = parent.siblings('.add_element')
					prFriend.html('');
					prFriend.html(json.html);
					if(cat != 0){
						_this.closest('.detail').find('.nice-select.dt_nice_select.va-select-article').remove()
						_this.closest('.detail').find('.va-select-article').html(json.html_select)
						_this.closest('.detail').find('.va-select-article').niceSelect()
					}
					var swiper = new Swiper('.'+json.class_1, {
					  loop: true,
					  spaceBetween: 10,
					  slidesPerView: 3,
					  freeMode: true,
					  watchSlidesVisibility: true,
					  watchSlidesProgress: true,
					  // navigation: {
					  //   nextEl: ".swiper-button-next",
					  //   prevEl: ".swiper-button-prev",
					  // },
					});
					var swiper2 = new Swiper('.'+json.class_2, {
					  loop: true,
					  spaceBetween: 10,
					  navigation: {
						nextEl: ".swiper-button-next",
						prevEl: ".swiper-button-prev",
					  },
					  thumbs: {
						swiper: swiper,
					  },
					});
		        }
		    });
		}, 300);
	}


</script>
