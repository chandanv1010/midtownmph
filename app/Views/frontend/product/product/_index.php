<?php 
	// print_r(1); exit;
 ?>
<div id="product" class="page-body">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium">
			<div class="uk-width-large-1-2">
				<section class="detail-panel mb-common">
					<script type="text/javascript">
						$(window).load(function() {
						  $('#carousel').flexslider({
							animation: "slide",
							controlNav: false,
							animationLoop: false,
							slideshow: false,
							itemWidth: 95,
							itemMargin: 0,
							prevText: '',
							nextText: '',
							asNavFor: '#slider'
						  });
						  $('#slider').flexslider({
							animation: "slide",
							controlNav: false,
							animationLoop: false,
							slideshow: false,
							prevText: '',
							nextText: '',
							sync: "#carousel"
						  });
						});
					</script>
					<div class="prd-gallerys">
						<div id="slider" class="flexslider">
							<ul class="slides">
								<?php if(isset($object['album']) && is_array($object['album']) && count($object['album'])){ ?>
								<?php foreach($object['album'] as $key => $val) { ?>
								<li>
									<div class="thumb">
										<a class="image img-scaledown" href="<?php echo $val; ?>" title="Ảnh chi tiết dự án" data-uk-lightbox="{group:'prdGallerys'}"><?php echo render_img(['src' => $val,'alt' => $object['title'].'-'.$key]) ?></a>
									</div>
								</li>
								<?php }} ?>
							</ul>
						</div>
						<div id="carousel" class="flexslider">
							<ul class="slides">
								<?php if(isset($object['album']) && is_array($object['album']) && count($object['album'])){ ?>
								<?php foreach($object['album'] as $key => $val) { ?>
								<li>
									<div class="thumb">
										<span class="image img-scaledown"><?php echo render_img(['src' => $val]) ?></span>
									</div>
								</li>
								<?php }} ?>
							</ul>
						</div>
					</div> <!-- end of product-gallery -->
				</section> <!-- .detail-panel-->
			</div>
			<div class="uk-width-large-1-2">
				<div class="product-information">
					<h1 class="heading-1"><span><?php echo $object['title'] ?></span></h1>
					<div class="price mb20">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<div>
								Giá: <?php echo ($object['price_promotion'] > 0) ? $object['price_promotion'] : $object['price'] ?>
							</div>
							<div class="pull-right">
								<span class="font-20 red">
									<i class="fa fa-star-o "></i>
									<i class="fa fa-star-o "></i>
									<i class="fa fa-star-o "></i>
									<i class="fa fa-star-o "></i>
									<i class="fa fa-star-o "></i>
								</span>
							</div>
						</div>
					</div>
					<div class="description">
						<?php echo $object['description']; ?>
					</div>

					<div class="form-product section uk-clearfix mb20">
						<div class="form-group form_button_details margin-top-15">
							<div class="form_product_content type1 ">
								<?php echo render_quantity_btn('qtym') ?>
								<?php echo render_cart_btn($object['id']) ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="product-detail-content-panel mb50">
				<ul class="uk-list uk-clearfix product-switcher" data-uk-switcher="{connect:'#product-tab'}">
					<li><a href="" title="Thông tin chi tiết">Thông tin chi tiết</a></li>
				</ul>
				<!-- This is the container of the content items -->
				<ul id="product-tab" class="uk-switcher">
					<li class="product-detail-content">
						<?php echo $object['content'] ?>
					</li>

				</ul>
			</div>
		</div>
	</div>
</div>
