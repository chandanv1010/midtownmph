<?php echo view('frontend/homepage/common/banner_catalogue') ?>
<section class="page-body">
	<?php
		helper(['mydatafrontend','mydata']);
		$baseController = new App\Controllers\FrontendController();
	$language = request()->getLocale();
	$video_top = get_slide(array(
		'keyword' => 'video-sell',
		'type' => '',
		'output' => 'array',
	));
	$video_top = check_array($video_top['data'])? $video_top['data'] : [];
	?>
	<?php //echo view('frontend/homepage/common/breadcrumb') ?>
	<?php //echo view('frontend/homepage/common/banner_cat') ?>
	<section class="va-articleCat-panel" data-module="<?php echo isset($module)?? $module; ?>" data-canonical="<?php echo isset($canonical)?? $canonical; ?>">
		<div class="uk-container uk-container-center">
			<section class="dt_bg_grey">
				<div class="sologan-1 uk-text-center ">
					<div class="txt_center dt_desc_catalogue">
						 <?php echo base64_decode($detailCatalogue['description']) ?>
					</div>
				</div>
				<section class="midtown-panel">
					<section class="midtown-body">
						<!-- mobile-tag-line -->
						<div class="midtown-title">
							<?php
								if($detailCatalogue['id'] == 10 || $detailCatalogue['id'] == 18){
									if($detailCatalogue['id'] == 10){
										echo (request()->getLocale() == 'vi') ? 'Danh sách căn hộ '.$detailCatalogue['title'] : '';
										echo (request()->getLocale() == 'tq') ? '待售公寓清单 Midtown' : '';
										echo (request()->getLocale() == 'en') ? 'Listing of apartments for sale in Midtown' : '';
									}
									if($detailCatalogue['id'] == 18){
										echo (request()->getLocale() == 'vi') ? 'Danh sách căn hộ '.$detailCatalogue['title'] : '';
										echo (request()->getLocale() == 'tq') ? 'Midtown 出租公寓列表' : '';
										echo (request()->getLocale() == 'en') ? 'Listing of apartments for rent in Midtown' : '';
									}
								}else{
									echo (request()->getLocale() == 'vi') ? 'Danh sách căn hộ '.$detailCatalogue['title'] : '';
									echo (request()->getLocale() == 'tq') ? '公寓清单'.$detailCatalogue['title'] : '';
									echo (request()->getLocale() == 'en') ? 'listing of apartments '.$detailCatalogue['title'] : '';
								}
							?>
						</div>
						<?php if (isset($children) && check_array($children)): ?>
						<?php
							foreach ($children as $key => $val) {
								$title = $val['title'];
								$href = write_url($val['canonical'], true, false);
								$dt_class = ($key > 0)? ' dt_division_catalogue':'';
						?>
						<section class="cat_child_panel <?php echo $dt_class ?>">
							<a href="<?php echo $href ?>" title="<?php echo $title ?>" class="cat_heading_1 before_left mb30"><?php echo $title ?></a>
							<div class="small-midtown-title">
								<div class="uk-flex uk-flex-space-between uk-flex-middle">
									<?php /*<div class="left-side">
											Sản phẩm mới nhất
									</div>*/ ?>
								</div>
							</div>
							<div class="productList">
								<section class="artcatalogue mb50">
									<?php
										$empty = '';
									?>
									<?php if (isset($val['post']) && check_array($val['post'])): ?>
									<section class="product_list_panel">
										<?php
											$data = array(
												'productList' => $val['post'],
												'style' => 'grid-3',
											);
										?>
										<?php
											echo view('frontend/homepage/core/productList', $data, ['saveData' => true]);
										?>
										
									</section>
									<div class="uk-text-right">
										<a href="<?php echo $href ?>" title="" class="dt_btn_viewall"><?php echo $general['home_view'] ?></a>
									</div>
									<?php else: ?>
									<?php $empty = PRODUCT_UPDATE ?>
									<?php endif ?>
									<seciton class="product_search_panel">
									<?php echo $empty ?>
									</seciton>
								</section>
							</div>
						</section>
						<?php } ?>
						<?php endif ?>
					</section>
				</section>
                <div class="blur"></div>
				<div class="sologan-1 uk-text-center">
					<div class="txt_center ">
						<?php echo base64_decode($detailCatalogue['content']) ?>
					</div>
				</div>
			</section>
			<!-- dt_bg_grey -->
		</div>
	</section>
	<!-- va-articleCat-panel -->
</section>
<script>
var moduleid = <?php echo isset($moduleid)? $moduleid : 0 ?>;
</script>
<!-- Initialize Swiper -->
<script>
var swiper = new Swiper(".mySwiper", {
loop: true,
spaceBetween: 0,
slidesPerView: 4,
freeMode: true,
watchSlidesVisibility: true,
watchSlidesProgress: true,
navigation: {
nextEl: ".swiper-button-next",
prevEl: ".swiper-button-prev",
},
});
var swiper2 = new Swiper(".mySwiper2", {
loop: true,
spaceBetween: 10,
// navigation: {
//   nextEl: ".swiper-button-next",
//   prevEl: ".swiper-button-prev",
// },
thumbs: {
swiper: swiper,
},
});
</script>
<script>
var swiper = new Swiper(".videoSwiper", {
loop: true,
spaceBetween: 10,
slidesPerView: 4,
freeMode: true,
watchSlidesVisibility: true,
watchSlidesProgress: true,
// navigation: {
//   nextEl: ".swiper-button-next",
//   prevEl: ".swiper-button-prev",
// },
});
var swiper2 = new Swiper(".videoSwiper2", {
loop: true,
spaceBetween: 10,
navigation: {
nextEl: ".swiper-button-next-2",
prevEl: ".swiper-button-prev-2",
},
thumbs: {
swiper: swiper,
},
});
</script>
<script>
var swiper = new Swiper(".mobile-mySwiper", {
loop: true,
spaceBetween: 10,
slidesPerView: 4,
freeMode: true,
watchSlidesVisibility: true,
watchSlidesProgress: true,
// navigation: {
//   nextEl: ".swiper-button-next",
//   prevEl: ".swiper-button-prev",
// },
});
var swiper2 = new Swiper(".mobile-mySwiper2", {
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
</script>
<script type="text/javascript">
$(document).ready(function(){
	var sort_list = $('.sort-list');
$('.sort').on('click', function(){
if(sort_list.hasClass('block')){
sort_list.removeClass('block');
}
else{
sort_list.addClass('block');
}
// navigation.toggleClass('block');
return false;
});
});
</script>
<script type="text/javascript">
	$(document).ready(function(){
	var sort_list = $('.sort-list');
$('.btn-cancel').on('click', function(){
sort_list.removeClass('block');
return false;
});
$('.sort-item').on('click', function(){
	let val = $(this).siblings('input').val();
	$('.btn-pick').attr('href', val);
});
});
</script>
