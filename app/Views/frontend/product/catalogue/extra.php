<?php echo view('frontend/homepage/common/banner_catalogue') ?>
<section class="page-body">
	<?php
		helper(['mydatafrontend','mydata']);
		$baseController = new App\Controllers\FrontendController();
	$language = $baseController->currentLanguage();
	$video_top = get_slide(array(
		'keyword' => 'video-sell',
		'type' => '',
		'output' => 'array',
	));
	$actual_link = "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$base_link = "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]$_SERVER[REDIRECT_URL]";
	// print_r($video_top); exit;<?php echo view('frontend/homepage/common/banner_catalogue') ?>
	<section class="page-body">
		<?php
			helper(['mydatafrontend','mydata']);
			$baseController = new App\Controllers\FrontendController();
			$language = $baseController->currentLanguage();
			// $video_top = get_slide(array(
				// 	'keyword' => 'video-sell',
				// 	'type' => '',
				// 	'output' => 'array',
			// ));
			if(isset($detailCatalogue['video_top_id'])){
				$video_top = get_object_catalogue([
					'module' => 'media_catalogue',
					'where' => [
						'tb1.id' => $detailCatalogue['video_top_id'],
					],
					'children' => [
						'flag' => false,
						// 'limit' => 2,
						// 'post' => true,
						// 'post_limit' => 9,
					],
					'post' => [
						'flag' => true,
						// 'limit' => 0,
					],
				]);
			}
			// print_r($video_top); exit;
		?>
		<?php //echo view('frontend/homepage/common/breadcrumb') ?>
		<?php //echo view('frontend/homepage/common/banner_cat') ?>
		<section class="va-articleCat-panel" data-module="<?php echo isset($module)?? $module; ?>" data-canonical="<?php echo isset($canonical)?? $canonical; ?>">
			<div class="uk-container uk-container-center">
				<section class="dt_bg_grey">
					<?php if(isset($detailCatalogue['description'])){ ?>
						<div class="sologan">
							<div class="uk-width-large-2-3 uk-container-center">
								<div class="txt_center dt_desc_catalogue">
									<?php echo base64_decode($detailCatalogue['description']); ?>
								</div>
							</div>
						</div>
					<?php } ?>
					<section class="midtown-panel">
						<section class="midtown-body">
							<?php if (check_array($video_top)): ?>
							<div class="tag-line-panel">
								<div class="uk-grid lib-grid-20 uk-flex uk-flex-bottom">
									<div class="uk-width-medium-2-5 uk-width-large-1-3">
										<div class="left-side">
											<?php if (isset($video_top['post']) && $video_top['post']): ?>
											<div class="swiper-container mySwiper2">
												<div class="swiper-wrapper">
													<?php
														foreach ($video_top['post'] as $key => $val) {
															$image = $val['image'];
															$video = str_replace('https://www.youtube.com/watch?v=', '', $val['iframe']); // id ytb link
													?>
													<div class="swiper-slide">
														<div class="thumb_ratio thumb_ratio-6-5">
															<div class="img-cover image">
																<img src="<?php echo $image ?>" />
															</div>
															<a title="" class="play-button youtube-link" youtubeid="<?php echo $video ?>">
																<i class="fa fa-play" aria-hidden="true"></i>
															</a>
														</div>
													</div>
													<?php } ?>
												</div>
												<!-- <div class="swiper-button-next"></div>
												<div class="swiper-button-prev"></div>
												-->
											</div>
											<?php endif ?>
										</div>
									</div>
									<div class="uk-width-medium-3-5 uk-width-large-2-3">
										<div class="right-side">
											<?php if(isset($detailCatalogue['content'])){ ?>
												<div class="descrice uk-visible-large">
													<div class="description">
														<?php echo base64_decode($detailCatalogue['content']) ?>
													</div>
												</div>
												<div class="descrice uk-visible-medium">
													<div class="description line-5">
														<?php echo base64_decode($detailCatalogue['content']) ?>
													</div>
												</div>
											<?php } ?>
											<div class="small-side">
												<?php if (isset($video_top['post']) && $video_top['post']): ?>
												<div thumbsSlider="" class="swiper-container mySwiper">
													<div class="swiper-wrapper">
														<?php
																foreach ($video_top['post'] as $key => $val) {
																	$image = $val['image'];
														?>
														<div class="swiper-slide swiper-slide-small">
															<div class="thumb_ratio style-11">
																<div class="image img-cover">
																	<img src="<?php echo $image ?>" />
																</div>
															</div>
														</div>
														<?php } ?>
													</div>
												</div>
												<div class="swiper-button-next">
													<i class="fa fa-caret-right" aria-hidden="true"></i>
												</div>
												<div class="swiper-button-prev">
													<i class="fa fa-caret-left" aria-hidden="true"></i>
												</div>
												<?php endif ?>
											</div>
										</div>
									</div>
								</div>
								<?php if(isset($detailCatalogue['content'])){ ?>
									<div class="mt20 descrice uk-hidden-medium uk-hidden-large">
										<div class="description">
											<?php echo base64_decode($detailCatalogue['content']) ?>
										</div>
									</div>
								<?php } ?>
							</div>
							<?php endif ?>
							<?php if(isset($detailCatalogue['title'])){ ?>
								<div class="midtown-title">
									<?php echo (!isset($_COOKIE['language']) || $_COOKIE['language'] == 'vi') ? 'Danh sách căn hộ '.$detailCatalogue['title'].' cần bán' : '' ?>
									<?php echo (isset($_COOKIE['language']) && $_COOKIE['language'] == 'en') ? 'List of apartments '.$detailCatalogue['title'].' for sale' : '' ?>
									<?php echo (isset($_COOKIE['language']) && $_COOKIE['language'] == 'tq') ? '公寓清单'.$detailCatalogue['title'].'出售' : '' ?>
								</div>
							<?php }else{ ?>
								<div class="midtown-title">
									<?php echo (!isset($_COOKIE['language']) || $_COOKIE['language'] == 'vi') ? 'Danh sách căn hộ'  : '' ?>
									<?php echo (isset($_COOKIE['language']) && $_COOKIE['language'] == 'en') ? 'List of apartments' : '' ?>
									<?php echo (isset($_COOKIE['language']) && $_COOKIE['language'] == 'tq') ? '公寓上市' : '' ?>
									
								</div>
							<?php } ?>
							<div class="midtown-item-list">
								<?php if(isset($detailCatalogue['title'])){ ?>
								<div class="midtown-item-title">
									<?php echo $detailCatalogue['title'] ?>
								</div>
							<?php } ?>
								<div class="small-midtown-title">
									<div class="uk-flex uk-flex-space-between uk-flex-middle">
										<?php /*<div class="left-side">
												Sản phẩm mới nhất
										</div>*/ ?>
										<div class="right-side">
											<a class="sort" title="" >
												<?php echo FILTER_PRODUCT ?> <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
											</a>
											<?php
												$price_asc = 'filter=price|asc';
												$price_desc = 'filter=price|desc';
												$id_asc = 'filter=id|asc';
												$id_desc = 'filter=id|desc';
												$_get_val = '';
												if (isset($_GET) && check_array($_GET)) {
													foreach ($_GET as $key => $val) {
														$_get_val = $key.'='.$val;
													}
												}
												$checked_price_asc = ($_get_val == $price_asc)? 'checked' : '';
												$checked_price_desc = ($_get_val == $price_desc)? 'checked' : '';
												$checked_id_asc = ($_get_val == $id_asc)? 'checked' : '';
												$checked_id_desc = ($_get_val == $id_desc)? 'checked' : '';
											?>
											<ul class="sort-list">
												<li class="uk-flex uk-flex-middle">
													<input type="radio" id="low" name="filter" value="<?php echo $price_asc ?>" <?php echo $checked_price_asc ?>>
													<label for="low" class="sort-item"><?php echo PRICE_ASC ?></label>
												</li>
												<li class="uk-flex uk-flex-middle">
													<input type="radio" id="high" name="filter" value="<?php echo $price_desc ?>" <?php echo $checked_price_desc ?>>
													<label for="high" class="sort-item"><?php echo PRICE_DESC ?></label>
												</li>
												<li class="uk-flex uk-flex-middle">
													<input type="radio" id="new" name="filter" value="<?php echo $id_desc ?>" <?php echo $checked_id_desc ?>>
													<label for="new" class="sort-item"><?php echo STATUS_ASC ?></label>
												</li>
												<li class="uk-flex uk-flex-middle">
													<input type="radio" id="old" name="filter" value="<?php echo $id_asc ?>" <?php echo $checked_id_asc ?>>
													<label for="old" class="sort-item"><?php echo STATUS_DESC ?></label>
												</li>
												<div class="btn-group uk-flex uk-flex-middle uk-flex-space-between">
													<a href="<?php echo $actual_link ?>" title="" class="btn-pick">
														<?php echo BTN_SELECT ?>
													</a>
													<a title="" class="btn-cancel">
														<?php echo BTN_CANCEL ?>
													</a>
												</div>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="productList">
								<section class="artcatalogue mb50">
									<?php
										$empty = '';
									?>
									<?php if (isset($productList) && check_array($productList)): ?>
									<section class="product_list_panel">
										<?php
											$data = '';
											$data = array(
												'productList' => $productList,
												'style' => 'grid-3',
											);
										?>
										<?php
											echo view('frontend/homepage/core/productList', $data, ['saveData' => true]);
										?>
										<div id="pagination" class="va-num-page">
											<?php echo (isset($pagination)) ? $pagination : ''; ?>
										</div>
									</section>
									<?php else: ?>
									<?php $empty = NO_RESULT ?>
									<?php endif ?>
									<seciton class="product_search_panel">
									<?php echo $empty ?>
									</seciton>
								</section>
								<?php /*
									<div class="uk-grid uk-grid-medium uk-clearfix">
										<div class="uk-width-large-1-5 uk-visible-large">
										<?php echo view('frontend/homepage/common/asideproduct') ?>
									</div>
									<div class="uk-width-large-4-5">
									</div>
								</div>
								*/ ?>
							</div>
							<?php
							if(isset($detailCatalogue['video_bottom_id'])){
								$video_bottom = get_object_catalogue([
							'module' => 'media_catalogue',
							'where' => [
							'tb1.id' => $detailCatalogue['video_bottom_id'],
							],
							'children' => [
							'flag' => false,
							// 'limit' => 2,
							// 'post' => true,
							// 'post_limit' => 9,
							],
							'post' => [
							'flag' => true,
							// 'limit' => 0,
							],
							]);
							}
							// print_r($video_bottom); exit;
							?>
							<?php if (isset($video_bottom) && check_array($video_bottom)): ?>
							<div class="demo-midtown-video">
								<?php if (isset($video_bottom['post']) && $video_bottom['post']): ?>
								<div class="video-swiper-container videoSwiper2 mb30">
									<div class="swiper-wrapper">
										<?php
											foreach ($video_bottom['post'] as $key => $val) {
												$image = $val['image'];
												$video = str_replace('https://www.youtube.com/watch?v=', '', $val['iframe']); // id ytb link
										?>
										<div class="swiper-slide">
											<div class="thumb_ratio thumb_ratio-6-10">
												<div class="img-cover image">
													<img src="<?php echo $image ?>" />
												</div>
												<a title="" class="play-button youtube-link" youtubeid="<?php echo $video ?>">
													<i class="fa fa-play" aria-hidden="true"></i>
												</a>
											</div>
										</div>
										<?php } ?>
									</div>
									<!-- <div class="swiper-button-next"></div>
									<div class="swiper-button-prev"></div>
									-->
								</div>
								<?php endif ?>
								<div class="uk-width-large-2-3 uk-container-center">
									<div class="description mb40">
										<?php echo base64_decode($video_bottom['description']); ?>
									</div>
								</div>
								<?php if (isset($video_bottom['post']) && $video_bottom['post']): ?>
								<div class="video-small-side">
									<div thumbsSlider="" class="swiper-container videoSwiper">
										<div class="swiper-wrapper">
											<?php
													foreach ($video_bottom['post'] as $key => $val) {
														$image = $val['image'];
											?>
											<div class="swiper-slide swiper-slide-small">
												<div class="thumb_ratio">
													<div class="image img-cover">
														<img src="<?php echo $image ?>" />
													</div>
												</div>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
								<?php endif ?>
							</div>
							<?php endif ?>
						</section>
					</section>
				</div>
			</section>
			<!-- va-articleCat-panel -->
		</section>
		<div id="modal-product" class="uk-modal">
			<div class="uk-modal-dialog product-modal-panel">
				<a class="uk-modal-close uk-close"></a>
				<div class="wrap-modal-panel">
					<div class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-clearfix">
						<div class="panel-modal-row">
							<div class="slide-img-modal">
							</div>
						</div>
						<div class="panel-modal-row">
							<div class="info-product-modal">
								<div class="product-modal-title">
								</div>
								<div class="product-detail-body product-modal-price">
									<div class="wrap-price mb10">
										<span class="new" style="">
										</span>
										<span class="old line-price"></span>
									</div>
									<div class="form-product section uk-clearfix mb50">
										<div class="form-group form_button_details margin-top-15">
											<div class="form_product_content type1 ">
												<div class="soluong soluong_type_1 show">
													<label class="section margin-bottom-10">Số lượng:</label>
													<div class="custom input_number_product custom-btn-number form-control">
														<button class="btn_num num_1 button button_qty" onclick="var result = document.getElementById('qtym_modal'); var qtypro = result.value; if( !isNaN( qtypro ) &amp;&amp; qtypro > 1 ) result.value--;return false;" type="button">
														<i class="fa fa-minus-circle"></i>
														</button>
														<input type="text" id="qtym_modal" name="quantity" value="1" maxlength="3" class="form-control prd_quantity" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" onchange="if(this.value == 0)this.value=1;">
														<button class="btn_num num_2 button button_qty" onclick="var result = document.getElementById('qtym_modal'); var qtypro = result.value; if( !isNaN( qtypro )) result.value++;return false;" type="button">
														<i class="fa fa-plus-circle"></i>
														</button>
													</div>
												</div>
												<div class="button_actions uk-clearfix">
													<button type="submit" class="btn btn_base normal_button btn_add_cart btn-cart add_to_cart">
													<span class="text_1"><i class="fa fa-shopping-basket"></i> Thêm vào giỏ hàng</span>
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
		var actual_link = '<?php echo $base_link ?>';
		$(document).ready(function(){
		var sort_list = $('.sort-list');
		$('.btn-cancel').on('click', function(){
		sort_list.removeClass('block');
		return false;
		});
		$('.sort-item').on('click', function(){
		let val = $(this).siblings('input').val();
		$('.btn-pick').attr('href', actual_link+'?'+val);
		});
		});
		</script>
