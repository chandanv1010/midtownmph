<section class="contact-2-panel">
	<div class="the-big-pic mb50">
		<div class="thumb_ratio">
			<div class="image img-cover">
				<img src="<?php echo $general['contact_page_image'] ?>">
			</div>
		</div>
	</div>

	<div class="address-panel mb50">
		<div class="uk-container uk-container-center">
			<div class="map">
				<?php echo $general['contact_page_map'] ?>
			</div>
			<div class="address-text">
				<?php echo $general['contact_page_info'] ?>
			</div>
		</div>
	</div>
	<div class="with-us-panel mb50">
		<div class="uk-container uk-container-center">
			<div class="heading mb20">
				<?php echo lang('app.CONTACT_NOW') ?>
			</div>
			<?php
				$user_sale = dt_get_user_all();
		 	?>
		 	<?php if (check_array($user_sale)): ?>
				<div class="with-us-body">
					<?php
						foreach ($user_sale as $key => $val) {
							$image = $val['image'];
							$fullname = $val['fullname'];
							$email = $val['email'];
							$phone = $val['phone'];
							$zalo = $val['zalo'];
							$wechat = $val['wechat'];
							$messenger = $val['messenger'];
							$intro = $val['intro'];
							$kakao = $val['kakao'];
							$line = $val['line'];
							$qr_kakao = $val['qr_kakao'];
							$qr_line = $val['qr_line'];
							$qr_wechat = $val['qr_wechat'];
					 ?>
						<div class="with-us-item">
							<div class="uk-grid uk-grid-medium">
								<div class="uk-width-large-1-3 uk-width-medium-1-1">
									<div class="left-side">
										<div class="with-us-pic">
											<div class="thumb_ratio">
												<a title="<?php echo $fullname ?>" class="img-cover image">
													<img src="<?php echo $image ?>">
												</a>
											</div>
										</div>
										<div class="with-us-text">
											<div class="secondary-title">
												<?php echo $fullname ?>
											</div>
											<div class="mail mb10">
												<div class="uk-flex uk-flex-middle uk-flex-wrap">
													<div class="small-pic">
														<div class="img-cover">
															<img src="public/frontend/resources/img/contact-2/develop.png">
														</div>
													</div>
													<div class="small-text">
														<a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
													</div>
												</div>
											</div>
											<div class="phone">
												<div class="uk-flex uk-flex-middle uk-flex-wrap">
													<div class="small-pic">
														<div class="img-cover">
															<img src="public/frontend/resources/img/contact-2/circle-phone.png">
														</div>
													</div>
													<div class="small-text">
														<a href="tel:<?php echo $phone ?>"><?php echo $phone ?></a>
													</div>
												</div>
											</div>
										</div>
										<div class="with-us-way">
											<div class="uk-flex uk-flex-middle
											 uk-flex-wrap">
												<a href="<?php echo $zalo ?>" target="_blank" class="img-cover image">
													<img src="public/zalo.png">
												</a>
												<a href="https://facebook.com/<?php echo $val['messenger'] ?>" target="_blank" class="img-cover image">
													<img src="public/frontend/resources/img/mess.png">
												</a>
												<a href="<?php echo $val['line'] ?>" target="_blank" class="img-cover image">
													<img src="public/frontend/resources/img/contact-2/phone-icon.png">
												</a>
											</div>
										</div>
									</div>
								</div>
								<div class="uk-width-large-2-3 uk-width-medium-1-1">
									<div class="right-side">
										<div class="with-us-page">
											<div class="description va-content">
												<?php echo $intro ?>
											</div>
										</div>
										<div class="qr-panel">
											<div class="uk-grid uk-grid-width-1-1 uk-grid-width-medium-1-2 uk-grid-width-large-1-3 uk-grid-medium">
												<div class="panel-item">
													<div class="secondary-title">
														Kakao Talk
													</div>
													<div class="description mb10 uk-text-center">
														<a href="<?php echo $kakao ?>" target="_blank" style="color: #000;"><?php echo $val['kakao_name'] ?></a>
													</div>
													<div class="img-cover image">
														<img src="<?php echo $qr_kakao ?>">
													</div>
												</div>
												<div class="panel-item">
													<div class="secondary-title">
														Line
													</div>
													<div class="description mb10">
														<a href="<?php echo $line ?>" target="_blank" style="color: #000;"><?php echo $val['line_name'] ?></a>
													</div>
													<div class="img-cover image">
														<img src="<?php echo $qr_line ?>">
													</div>
												</div>
												<div class="panel-item">
													<div class="secondary-title">
														Wechat
													</div>
													<div class="description mb10">
														<a href="<?php echo $wechat ?>" target="_blank" style="color: #000;"><?php echo $val['wechat_name'] ?></a>
													</div>
													<div class="img-cover image">
														<img src="<?php echo $qr_wechat ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
		 	<?php endif ?>
		</div>
	</div>

	<?php
		$new = get_object_catalogue([
			'module' => 'article_catalogue',
			'where' => [
				'tb1.id' => 3,
			],
			'post' => [
				'flag' => true,
				'limit' => 8,
			],
		]);

		// pre($new);

		$video = get_object_catalogue([
			'module' => 'media_catalogue',
			'where' => [
				'tb1.id' => 1,
			],
			'post' => [
				'flag' => true,
				'limit' => 8,
			],
		]);
		// print_r($video); exit;
	 ?>

		<div class="news-panel mb50">
			<div class="uk-container uk-container-center">
				<div class="uk-grid uk-grid-medium">
					<div class="uk-width-large-1-2 uk-width-medium-1-1">
					<?php if (check_array($new)): ?>
						<div class="heading">
							<?php echo lang('app.newTitle') ?>
						</div>
						<?php if (isset($new['post']) && check_array($new['post'])): ?>
							<ul class="news-list">
								<?php
									foreach ($new['post'] as $key => $val) {
										$title = $val['title'];
										$href = write_url($val['canonical'], true, false);
								 ?>
									<li class="">
										<div class="dt_description_readmore"><?php echo $val['title'] ?> ...</div>
				        				<a href="<?php echo $href ?>" class="dt_btn_viewmore link">See more</a>
									</li>
								<?php } ?>
							</ul>
						<?php endif ?>
					<?php endif ?>
					</div>

					<div class="uk-width-large-1-2 uk-width-medium-1-1">
					<?php if (check_array($video)): ?>
						<div class="heading">
							<?php echo $video['title'] ?>
						</div>
						<?php if (isset($video['post']) && check_array($video['post'])): ?>
						<ul class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-small-1-2 uk-grid-width-medium-1-2 uk-grid-width-large-1-2" data-uk-grid-match="{target: '.ht2109_grid_match'}">
							<?php
								foreach ($video['post'] as $key => $val) {
									$title = $val['title'];
									$href = write_url($val['canonical'], true, false);
									$iframe = $val['iframe'];
									$image = $val['image'];

								    $video = str_replace('https://www.youtube.com/watch?v=', '', $iframe); // id ytb link
								    // $video = str_replace('https://youtu.be/', '', 'youtube_link'); // id ytb link
							 ?>
								<li class="mb25">
									<article class="uk-clearfix article-2">
									    <div class="thumb_ratio style-43">
									    	<a class="image img-cover img-zoomin youtube-link" youtubeid="<?php echo $video ?>"><img src="<?php echo $image ?>" alt=""></a>
								    	 	<div class="info dt_overlay_info_item">
										        <h3 class="title"><a title="" class="line-2 dt_item_title_2 txt_center"><?php echo $title ?></a></h3>
										    </div>
    										<a class="youtube-link dt_btn_video" youtubeid="<?php echo $video ?>"><i class="fa fa-youtube-play"></i></a>
									    </div>
									</article>
								</li>
							<?php } ?>
						</ul>
						<?php endif ?>
					<?php endif ?>
					</div>
				</div>
			</div>
		</div>
</section>
