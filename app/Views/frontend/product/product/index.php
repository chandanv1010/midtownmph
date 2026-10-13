<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = request()->getLocale();
    $footer = get_panel([
		'locate' => 'footer',
		'language' => $language
	]);

	$album = $object['album'];

	// Truoc day: ternary 'vi' => '/ tháng', con lai '/ month' - nen trang tieng
	// Trung/Han/Nhat deu hien chu tieng Anh. Khoa PER da co du 5 ngon ngu.
	$en_string = '/ ' . lang('app.PER');

	$rating = dt_static_rating([
		'module' => $this->data['module'],
		'objectid' => $object['id'],
		'language' => $language,
	]);

	$average_point = ($rating['detail']['average'])?? 5;
	$num_rate = ($rating['detail']['num_rate'])?? 0;
 	// $param_sell = explode('|', $general['param_sell']);

?>
<?php
	$count_img = 0;
	if (check_array($object['album'])) {
		$count_img = count($object['album']);
	}
 ?>
<?php
	$image = ($object['album'][0])??'';
	// print_r($object['album']); exit;
 ?>
<section class="product-detail-panel">
	<div class="the-big-pic por uk-position-relative ovh <?php echo ($object['vr_3d'] !='') ? '' : '' ?>">
		<a <?php echo ($object['vr_3d'] !='') ? 'class="'.$object['vr_3d'].' thumb_ratio uk-display-block" href="'.$object['vr_3d'].'" target="_blank"' : 'data-src="'.$image.'" data-sub-html="" href="'.$image.'" title="" class="  thumb_ratio light_image uk-display-block"' ?> >
			<div class="image img-cover">
				<img src="<?php echo $object['image']; ?>" alt="<?php echo $object['title']; ?>">
			</div>
			<?php
				$video = '';
				$vr = str_replace('https://www.youtube.com/watch?v=', '', $object['vr']);

				if (isset($object['video'])):
					$video = str_replace('https://www.youtube.com/watch?v=', '', $object['video']); // id ytb link
					?>
					<div class="play-button ">
						<span class="mb20">3D View</span>
						<?php /*
							<img src="<?php echo $image ?>" alt="<?php echo $image; ?>" class="uk-hidden">
						</a>*/ ?>


						<div class="play-btn"><i class="fa fa-play"></i></div>
						<?php if (check_array($object['album'])): ?>
							<div class="uk-hidden">
								<?php foreach ($object['album'] as $key => $val) {

							  	?>
									<a data-src="<?php echo $val ?>" data-sub-html="" href="<?php echo $val ?>" title="" class="image img-cover img-zoomin light_image">
										<img src="<?php echo $val ?>" alt="">
									</a>
								<?php } ?>
							</div>
						<?php endif ?>
					</div>
				<?php endif ?>
		</a>
	</div>
	<section class="product-detail-body mt30">
		<div class="uk-container uk-container-center">
			<div class="dt_bg_grey">
				<div class="uk-grid uk-grid-medium">
					<div class="uk-width-large-2-3 uk-width-medium-1-1">
						<div class="left-side">
							<div class="video-button mb30">
								<ul class="button-list uk-flex uk-flex-wrap">

									<li class="btn lightBoxGallery mb10">
										<?php if ($image != ''): ?>
											<a data-src="<?php echo $image ?>" data-sub-html="" href="<?php echo $image ?>" title="" class="image img-cover img-zoomin light_image">
												<?php echo lang('app.GALLERY').' ( '.$count_img.' )' ?>
												<img src="<?php echo $image ?>" alt="" class="uk-hidden">
											</a>
										<?php else: ?>
											<a title="<?php echo $image; ?>" class="">
												<?php echo lang('app.GALLERY').' ( '.$count_img.' )' ?>
											</a>
										<?php endif ?>
										<?php if (check_array($object['album'])): ?>
										<div class="uk-hidden">
											<?php foreach ($object['album'] as $key => $val) {
												if ($key == 0) {
													continue;
												}
										  	?>
												<a data-src="<?php echo $val ?>" data-sub-html="" href="<?php echo $val ?>" title="" class="image img-cover img-zoomin light_image">
													<img src="<?php echo $val ?>" alt="">
												</a>
											<?php } ?>
										</div>
										<?php endif ?>
									</li>
									<li class="btn">
										<a class="youtube-link" youtubeid="<?php echo $video ?>">
											Video
										</a>
									</li>
									<li class="btn">
										<a class="" title="VR 3D" href="<?php echo $object['vr_3d'] ?>" target="_blank">
											VR
										</a>
									</li>
								</ul>
							</div>

							<div class="heading-2 mb10">
								<?php echo $object['title'] ?>
							</div>
							<div class="page-heading">
								<div id="breadcrumbs">
									<nav role="navigation" aria-label="breadcrumbs">
										<ol class="breadcrumb uk-flex uk-list uk-clearfix" itemscope="" itemtype="http://schema.org/BreadcrumbList">
											<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
												<a href="/" title="<?php echo lang('app.HOME_LINK') ?>" itemprop="item">
													<span itemprop="name"><?php echo lang('app.HOME_LINK') ?></span>
												</a>
											</li>
											<li class="pointer"> » </li>
											<?php foreach($breadcrumb as $key => $val){ ?>
											<li class="active" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
												<a href="<?php echo write_url($val['canonical']); ?>"> <span itemprop="name"><?php echo $val['title'] ?></span></a>
											</li>
											<li class="pointer"> » </li>
											<?php } ?>
										</ol>
									</nav>
								</div>
							</div>
							<div class="uk-flex uk-flex-middle mb10">
								<span class="rating fs20" data-stars="5" data-default-rating="<?php echo $average_point ?>" disabled="disabled"></span>
								<div class="dt_rating_info"><?php echo $average_point ?>/5 - <?php echo $num_rate ?> <?php echo lang('app.PRODUCT_RATE') ?></div>
							</div>
							<div class="basic-infor-panel mb30">
								<div class="heading mb20">
									<?php echo lang('app.PRD_BASIC') ?>
								</div>
								<div class="basic-infor-body">
									<div class="uk-grid uk-grid-medium">
										<?php
											if(isset($object['price']) && !empty($object['price'])){
											?>
											<div class="uk-width-medium-1-2">
												<div class="left-side">
													<div class="panel-item">
														<div class="panel-item-body uk-flex uk-flex-middle">
															<div class="item-pic">
																<div class="image img-scaledown">
																	<img src="public/frontend/resources/img/icon-small/dollar.png" alt="<?php echo $object['title']; ?>">
																</div>
															</div>
															<div class="item-text dt_price_color">
																<?php echo lang('app.PRD_PRICE').dt_render_currency($object['price']).(isset($object['status']) && is_array($object['status']) && count($object['status']) && ((in_array(29, $object['status']) || in_array(30, $object['status']))) ? $en_string : '') ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
										<?php
											if(isset($attrList['product']) && is_array($attrList['product']) && count($attrList['product'])){
												foreach ($attrList['product'] as $key => $value) {
											?>
											<div class="uk-width-medium-1-2">
												<div class="left-side">
													<div class="panel-item">
														<div class="panel-item-body uk-flex uk-flex-middle">
															<div class="item-pic">
																<div class="image img-scaledown">
																	<img src="<?php echo $value['image'] ?>" alt="<?php echo $value['image']; ?>">
																</div>
															</div>
															<div class="item-text ">
																<?php echo $value['title'] ?>: <?php echo $value['value'] ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php }} ?>
										<?php
											if(isset($object['bath']) && !empty($object['bath'])){
											?>
											<div class="uk-width-medium-1-2">
												<div class="left-side">
													<div class="panel-item">
														<div class="panel-item-body uk-flex uk-flex-middle">
															<div class="item-pic">
																<div class="image img-scaledown">
																	<img src="public/frontend/resources/img/icon-small/bath.png" alt="<?php echo $object['bath']; ?>">
																</div>
															</div>
															<div class="item-text ">
																<?php echo lang('app.PRD_BATH') ?>: <?php echo $object['bath'] ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
										<?php
											if(isset($object['bed']) && !empty($object['bed'])){
											?>
											<div class="uk-width-medium-1-2">
												<div class="left-side">
													<div class="panel-item">
														<div class="panel-item-body uk-flex uk-flex-middle">
															<div class="item-pic">
																<div class="image img-scaledown">
																	<img src="public/frontend/resources/img/icon-small/bed.png" alt="<?php echo $object['bed']; ?>">
																</div>
															</div>
															<div class="item-text ">
																<?php echo lang('app.PRD_BED') ?>: <?php echo $object['bed'] ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
										<?php
											if(isset($object['area']) && !empty($object['area'])){
											?>
											<div class="uk-width-medium-1-2">
												<div class="left-side">
													<div class="panel-item">
														<div class="panel-item-body uk-flex uk-flex-middle">
															<div class="item-pic">
																<div class="image img-scaledown">
																	<img src="public/frontend/resources/img/icon-small/icon-2.png" alt="<?php echo $object['area']; ?>">
																</div>
															</div>
															<div class="item-text ">
																<?php echo lang('app.PRD_AREA') ?>: <?php echo $object['area'] ?> <?php echo lang('app.AREA') ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>

									</div>
								</div>

							</div>
							<div class="detai-infor-panel mb50">
								<div class="heading mb20">
									<?php echo lang('app.PRD_INFORMATION') ?>
								</div>
								<div class="description">
									<?php echo $object['description'] ?>
								</div>
								<div class="uk-grid uk-grid-medium">
									<div class="uk-width-large-1-1 uk-width-medium-1-1">
										<div class="mb10 fz15 va-content">
											<?php echo $object['content'] ?>
										</div>
									</div>
									<?php
										$v2 = str_replace('https://www.youtube.com/watch?v=', '', $object['vr']);
									?>
									<?php if(isset($v2) && !empty($v2)){ ?>
										<div class="uk-width-large-1-1 uk-width-medium-1-1">
											<div class="page-pic">
												<div class="thumb_ratio style-11">
													<div class="image img-cover">
														<img src="<?php echo $object['image'] ?>" alt="<?php echo $object['title'] ?>">
													</div>
													<div class="play-button">
														<a  class="youtube-link" title="Youtube" youtubeid="<?php echo $v2 ?>">
															<i class="fa fa-play" aria-hidden="true"></i>
														</a>
													</div>
												</div>
												<div class="info bg_color_main">
											        <div class="title"><span class="line-2 dt_item_title_2 txt_center text-white"><?php echo $detailCatalogue['title'] ?></span></div>
											    </div>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>

							<div class="convenient-panel mb50">
							    <h3 class="heading mb20">
							        <?php echo $general['product_home'] ?>
							    </h3>

								<?php if(isset($brand['indoor']) && is_array($brand['indoor']) && count($brand['indoor'])){ ?>
							    <div class="convenient-body">
							        <div class="uk-grid uk-grid-width-1-2 uk-grid-width-medium-1-5 uk-grid-medium">
							            <?php foreach($brand['indoor'] as $key => $val) {?>
							            <div class="panel-item">
							                <div class="panel-item-body uk-grid uk-grid-small uk-flex uk-flex-middle">
							                    <div class="uk-width-1-5">
							                        <div class="item-pic">
							                            <div class="thumb_ratio">
							                                <span title="<?php echo $val['title'] ?>" class="image img-scaledown">
							                                    <img src="<?php echo $val['image'] ?>" alt="<?php echo $val['title'] ?>" />
							                                </span>
							                            </div>
							                        </div>
							                    </div>
							                    <div class="uk-width-4-5">
							                        <div class="item-text">
							                            <span>
							                                <?php echo $val['title'] ?>
							                            </span>
							                        </div>
							                    </div>
							                </div>
							            </div>
							            <?php } ?>
							        </div>
							    </div>
								<?php } ?>
							</div>

							<div class="convenient-panel mb50">
							    <h3 class="heading mb20">
							        <?php echo $general['product_now'] ?>
							    </h3>


								<?php if(isset($brand['outdoor']) && is_array($brand['outdoor']) && count($brand['outdoor'])){ ?>
							    <div class="convenient-body mb50">
							        <div class="uk-grid uk-grid-width-1-2 uk-grid-width-medium-1-5 uk-grid-medium">
							            <?php foreach($brand['outdoor'] as $key => $val) {?>
							            <div class="panel-item">
							                <div class="panel-item-body uk-grid uk-grid-small uk-flex uk-flex-middle">
							                    <div class="uk-width-1-5 ">
							                        <div class="item-pic">
							                            <div class="thumb_ratio">
															<span title="<?php echo $val['title'] ?>" class="image img-scaledown">
															   <img src="<?php echo $val['image'] ?>" alt="<?php echo $val['title'] ?>" />
														   </span>
							                            </div>
							                        </div>
							                    </div>
							                    <div class="uk-width-4-5">
							                        <div class="item-text">
														<span>
							                                <?php echo $val['title'] ?>
							                            </span>
							                        </div>
							                    </div>
							                </div>
							            </div>
							            <?php } ?>
							        </div>
							    </div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="uk-width-large-1-3 ">
						<div class="right-side ">
							<div class="right-side-header">
								<?php if(isset($attrList['status']) && is_array($attrList['status']) && count($attrList['status'])){
									foreach ($attrList['status'] as $key => $value) {
								?>
									<div class="heading-3 uk-text-left">
										<?php echo $value['title'] ?>
										<span class="status-now highlight">
											<?php echo isset($value['value']) ? $value['value'] : '' ?>
										</span>
									</div>
								<?php }} ?>
								<div class="heading-3 uk-text-left">
									Code:
									<span class="status-now">
										<?php echo $object['productid']; ?>
									</span>
								</div>
								<div class="description mb30">
									<?php echo lang('app.PLS_CONTACT') ?>
								</div>
								<?php if (isset($user_created) && check_array($user_created)): ?>
									<!-- user created -->
									<div class="box-panel">
										<div class="box-title">
											<?php echo lang('app.PRD_CONTACTUS') ?>
										</div>
										<div class="with-us-item">
											<div class="with-us-pic avatar-padding">
												<div class="thumb_ratio">
													<a title="avatar" class="img-cover image">
														<img src="<?php echo $user_created['image'] ?>">
													</a>
												</div>
											</div>
											<div class="with-us-text">
												<div class="secondary-title">
													<?php echo $user_created['fullname'] ?>
												</div>
												<div class="phone">
													<div class="small-text">
														Phone:
														<a style="color:#000" href="tel:<?php echo 	$user_created['phone'] ?>">
															<b><?php echo $user_created['phone'] ?></b>
														</a>
													</div>
												</div>
												<div class="mail mb10">
													<div class="small-text">
														<a style="color:#000;" href="mailto:<?php echo $user_created['email'] ?>">Email: <?php echo $user_created['email'] ?></a>
													</div>
												</div>

											</div>
											<div class="with-us-way">
												<div class="uk-flex uk-flex-middle uk-flex-wrap uk-flex-center">
													<a href="<?php echo $user_created['zalo'] ?>" target="_blank" class="img-scaledown image">
														<img style="max-width:30px;" src="public/frontend/resources/img/contact-2/zalo.png" alt="icon_2">
													</a>
													<a href="<?php echo $user_created['line'] ?>" target="_blank"  class="img-scaledown image">
														<img src="public/frontend/resources/img/whatsapp.png" alt="icon_1">
													</a>

													<a href="<?php echo $user_created['wechat'] ?>" target="_blank"  class="img-scaledown image">
														<img src="public/frontend/resources/img/viber.png" alt="icon_3">
													</a>
												</div>
											</div>
										</div>
									</div>
								<?php endif ?>

								<?php
									$project = get_object_catalogue([
							            'module' => 'project_catalogue',
							            'children' => [
							                'flag' => false,
							            ],
							            'post' => [
							                'flag' => false,
							            ],
							        ]);
								 ?>

								<!-- project -->
								<div class="box-panel">
									<div class="box-title">
										<?php echo $detailCatalogue['title'] ?>
									</div>
									<?php if (check_array($detailCatalogue)): ?>
										<div class="with-us-item">
											<div class="with-us-pic">
												<div class="thumb_ratio">
													<?php $url = write_url($detailCatalogue['canonical']);  ?>
													<a href="<?php echo $url ?>" title="" class="img-cover image">
														<img src="<?php echo $detailCatalogue['image'] ?>" alt="<?php echo $detailCatalogue['title']; ?>">
													</a>
												</div>
											</div>
											<div class="with-us-text">
												<div class="heading-2">
													<?php echo $detailCatalogue['title'] ?>
												</div>
												<div class="description mb20">
													<?php echo strip_tags(base64_decode($detailCatalogue['description'])) ?>
												</div>
												<div class="btn-box">
													<a href="<?php echo $url ?>" title="">
														<?php echo lang('app.BTN_VIEW_DETAIL') ?>
													</a>
												</div>
											</div>
										</div>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="map-panel mb20">
					<div class="uk-container uk-container-center">
				    	<div class="heading mb20">
				    		<?php echo lang('app.MAP') ?>
				    	</div>
						<div class="address mb10"><?php echo $object['address']; ?></div>
				    	<div class="wrap_map">
					    	<?php echo $object['map'] ?>
				    	</div>
				    	<div class="social-panel">
				    		<div class="uk-flex uk-flex-space-between">
				    			 <div class="tag-panel">
				    			 	<div class="fb-like" data-href="<?php echo $canonical ?>" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
							    </div>
				    		</div>
				    	</div>
			    	</div>
			    </div>
			    <?php if(isset($tags) && is_array($tags) && count($tags)){ ?>
				    <div class="tag-panel mb30">
				    	<div class="uk-container uk-container-center">
					    	<ul class="tag-list uk-flex uk-flex-middle uk-flex-wrap">
					    		<?php foreach ($tags as $key => $value) { ?>
						    		<li>
						    			<a href="tag.html?keyword=<?php echo $value['keyword'] ?>" title="<?php echo $value['title'] ?>">
						    				<i class="fa fa-tag" aria-hidden="true"></i>
						    				<?php echo $value['title'] ?>
						    			</a>
						    		</li>
						    	<?php } ?>
					    	</ul>
				    	</div>
				    </div>
				<?php } ?>
		    	<div class="more-infor-panel mb20">
					<div class="heading-2">
						<?php echo lang('app.PRD_DETAIL_FORM') ?>
						( <i class="fa fa-eye"></i> <?php echo $object['viewed']; ?>)
					</div>

					<div class="more-infor-body">
						<form method="POST" action="" class="input-form" id="prd_detail_register">
							<input type="text" name="product" class="uk-width-1-1 product uk-hidden" value="<?php echo $object['title'] ?>" placeholder="">
							<input type="text" name="price" class="uk-width-1-1 price uk-hidden" value="<?php echo dt_render_currency($object['price']) ?>" placeholder="">
							<input type="text" name="bed" class="uk-width-1-1 bed uk-hidden" value="<?php echo lang('app.PRD_BED').$object['bed'] ?>" placeholder="">
							<input type="text" name="area" class="uk-width-1-1 area uk-hidden" value="<?php echo lang('app.PRD_AREA').$object['area'] ?>" placeholder="">


							<div class="form-row">
								<div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
									<div class="uk-width-large-1-5">
										<div class="left-side">
											<span><?php echo lang('app.FORM_FULLNAME') ?>(*)</span>
										</div>
									</div>
									<div class="uk-width-large-4-5">
										<div class="right-side dt_validate" data-validate="<?php echo lang('app.FORM_FULLNAME') ?>">
											<input type="text" name="fullname" class="uk-width-1-1 fullname input-text input-reset" placeholder="">
										</div>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
									<div class="uk-width-large-1-5">
										<div class="left-side">
											<span><?php echo lang('app.FORM_PHONE') ?>(*)</span>
										</div>
									</div>
									<div class="uk-width-large-4-5">
										<div class="right-side dt_validate" data-validate="<?php echo lang('app.FORM_PHONE') ?>">
											<input type="text" name="phone" class="uk-width-1-1 phone input-text input-reset" placeholder="">
										</div>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
									<div class="uk-width-large-1-5">
										<div class="left-side">
											<span><?php echo lang('app.FORM_EMAIL') ?>(*)</span>
										</div>
									</div>
									<div class="uk-width-large-4-5">
										<div class="right-side dt_validate" data-validate="<?php echo lang('app.FORM_EMAIL') ?>">
											<input type="text" name="email" class="uk-width-1-1 email input-text input-reset" placeholder="">
										</div>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
									<div class="uk-width-large-1-5">
										<div class="left-side">
											<span><?php echo lang('app.FORM_MESSAGE_REQUIRE') ?>(*)</span>
										</div>
									</div>
									<div class="uk-width-large-4-5">
										<div class="right-side dt_validate" data-validate="<?php echo lang('app.FORM_MESSAGE_REQUIRE') ?>">
											<textarea type="text" name="message" class="uk-width-1-1 message input-text input-reset" placeholder=""></textarea>
										</div>
									</div>
								</div>
							</div>  
                           

							<div class="form-row">
								<div class="uk-grid uk-grid-medium uk-flex ">
									<div class="uk-width-large-1-5">

									</div>
									<div class="uk-width-large-4-5">
										<div class="right-side">
											<div class="submit">
                                                <div class="loading" style="display: block">
                                                    <div class="bg-loader"></div>
                                                    <button class="btn-submit" type="submit" value="" name=""><?php echo $general['footer_btn'] ?></button>
                                                </div>
                                            </div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="dt_detail_rating">
					<div class="uk-flex uk-flex-middle mb10">
						<div class="title mr10"><?php echo lang('app.RATING_DETAIL') ?></div>
						<span class="rating fs20" data-stars="5" data-default-rating="5"  data-objectid="<?php echo $object['id'] ?>" data-module="<?php echo $this->data['module'] ?>" data-language="<?php echo fe_currentLanguage(); ?>"></span>
					</div>
				</div>

				<?php
				     $a = '<i class="fa fa-caret-left" aria-hidden="true"></i>';
				     $b = '<i class="fa fa-caret-right" aria-hidden="true"></i>';

				    $owlInit = [
				        'items' => 4,
				        'margin' => 20,
				        'nav' => true,
				        'navText'=>[$a ,$b],
				        'dots' =>false,
				        'loop' => false,
						'responsive' => array(
			                0 => array(
			                    'items' => 2,
			                    'nav' => false
			                ),
			                768 => array(
			                    'items' => 2,
			                    'nav' => true
			                ),
			                960 => array(
			                    'items' => 5,
			                    'nav' => true
			                ),

			            )
				    ];

				?>

				<?php
					// print_r($product_general); exit;
				 ?>
				<?php if (isset($product_general) && check_array($product_general)): ?>
					<section class="prd_relate_panel">
						<div class="uk-container uk-container-center">
							<div class="owl-slide ">
								<div class="heading">
									<?php echo lang('app.PRD_RELATE') ?>
								</div>
								<div class="description">
									<?php echo lang('app.PRD_RELATE_DESC') ?>
								</div>


							    <div class="panel-body">
							        <div class="owl-carousel owl-theme division_side" data-owl="<?php echo base64_encode(json_encode($owlInit)); ?>">
							            <?php
							            	foreach ($product_general as $key => $val) {
							            		$bar_code = $val['productid'];
							            		$image = $val['image'];
							            		$title = $val['title'];
							            		$href = write_url($val['canonical'], true, false);
							            		$bed = $val['bed'];
							            		$area = $val['area'].' '.lang('app.AREA');
							            		$price = dt_render_currency($val['price']);
							             ?>
								            <div class="category-item">
								            	<div class="category-pic mb10">
								            		<div class="thumb_ratio thumb_ratio-7-10">
										                <a href="<?php echo $href; ?>" title="<?php echo $title; ?>" class="image img-cover">
										                    <img src="<?php echo $image ?>" alt="<?php echo $val['title'] ?>">
										                </a>
										                <div class="product-infor uk-flex uk-flex-space-between">
										                	<div class="left-side uk-flex ">
										                		<span class="img-scaledown white-pic mr5 ">
										                			<img src="public/frontend/resources/img/icon-small/bed.png" alt="bed" >
										                		</span>
										                		<span><?php echo $bed ?></span>
										                	</div>
										                	<div class="right-side uk-flex">
										                		<span class="img-scaledown white-pic mr5 img ">
										                			<img src="public/frontend/resources/img/icon-small/icon-2.png" alt="icon_2" >
										                		</span>
										                		<span><?php echo $area ?></span>
										                	</div>
										                </div>
									                </div>
								                </div>
								                <div class="product-code">
								                	<strong><?php echo lang('app.PRODUCT_CODE') ?>: <?php echo $bar_code ?></strong>
								                </div>
								                <div class="category-title mb10">
							                        <a href="<?php echo $href ?>" title="<?php echo $title ?>" class="line-2 dt_item_title_2 link-color"><?php echo $title ?></a>
								                </div>
												<?php
													$baseController = new App\Controllers\FrontendController();
													$language = $baseController->currentLanguage();
												?>
							                    <div class="price">
							                        <?php echo lang('app.PRD_PRICE') ?><span class="price-number"><?php echo $price ?> <?php echo (isset($rent_flag) && $rent_flag == 1 ) ? $en_string : ''; ?></span>
							                    </div>
								            </div>
							            <?php } ?>
							        </div>
							    </div>
							</div>
						</div>
					</section>
				<?php endif ?>
			</div>
		</div>
	</section>


</section>

<script type="text/javascript">

	$(document).ready(function() {
		$('#galleryPrd').lightGallery({
			selector: '.light_image',
			thumbnail:true,
			hash:false,
		});
		$('.datetimepicker').datetimepicker({
			timepicker:false,
			format:'d/m/Y',
			minDate: new Date(),
		});
		$('.datetimepicker_time').datetimepicker({
			datepicker:false,
			format:'H:i',
			minTime: 0, // now
		});
	});

	$(document).on('click','.play-btn', function(){
		$('.button-list .lightBoxGallery > a').click();
		return false;
	});

   $(document).on('click','.the-big-pic', function(){
      $('.button-list .lightBoxGallery > a').click();
      return false;
   });

	$(document).on('click', '.other', function(event) {
		event.preventDefault();
		/* Act on the event */
		$(this).siblings('.light_image').click();
	});
</script>
