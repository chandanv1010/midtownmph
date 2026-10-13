<?php
	if(isset($detailCatalogue['album']) || isset($breadcrumb[0]['album'])){
		if (isset($detailCatalogue['album']) && $detailCatalogue['album'] != 'null') {
			$banner = json_decode($detailCatalogue['album'], true);
		    if (!check_array($banner)) {
		        $banner = json_decode(base64_decode($detailCatalogue['album']), true);
		    }
		}else if(isset($detailCatalogue['image']) &&$detailCatalogue['image'] != ''){
			$banner[] = $detailCatalogue['image'];
		}else{
			$banner = json_decode($breadcrumb[0]['album'], true);
		    if (!check_array($banner)) {
		        $banner = json_decode(base64_decode($breadcrumb[0]['album']), true);
		    }
		}
	}
?>

<?php if(isset($banner) && is_array($banner) && count($banner)){ ?>
<section class="main-slide cata-slide">
	<!-- <div class="uk-container uk-container-center uk-container-1"> -->
		<div class="uk-slidenav-position slide-show" data-uk-slideshow="{autoplay: true, autoplayInterval: 7500, }">
			<!-- kenburns:'15s' -->
			<ul class="uk-slideshow">
				<?php foreach($banner as $key => $val) { ?>
					<?php
						$image = $val;
						$check_mp4 = process_img_link_mp4($image, true);
					?>
				<li>
					<?php if ($check_mp4): ?>
						<div class="__iframe-video" >
							<video src="<?php echo $image; ?>" autoplay muted loop></video>
						</div>
					<?php else: ?>
						<span class="image img-cover"><img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" /></span>
					<?php endif ?>
				</li>
				<?php } ?>
			</ul>
			<?php if (check_array($banner) && count($banner) > 1): ?>
				<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
				<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
		    	<ul class="uk-dotnav uk-dotnav-contrast uk-position-bottom uk-flex-center">
				<?php for($i = 0; $i<count($banner); $i++){ ?>
			        <li data-uk-slideshow-item="<?php echo $i; ?>"><a href=""></a></li>
				<?php } ?>
			    </ul>
			<?php endif ?>
		</div>
	<!-- </div> -->
</section><!-- .main-slide -->
<?php } ?>

<style>
	.hide{
		opacity: 0 !important;
	}

	/*style iframe*/
	.__iframe-video {
	    position: relative;
	    padding-bottom: calc(100vh - 104px);
	    height: 0;
	    overflow: hidden;
	}

	.__iframe-video >* {
	    position: absolute;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    object-fit: cover;
	}
</style>


<script>

	var target = $('.target');

	var target_title = $('.uk-slideshow .overlay-slide .heading-1>*');
	var target_description = $('.uk-slideshow .overlay-slide .description');
	var target_btn = $('.uk-slideshow .overlay-slide .submit');
	var animate_1 = 'slideInDown';
	var animate_2 = 'slideInLeft';
	var animate_3 = 'slideInUp';

	UIkit.on('beforeshow.uk.slideshow', function(event, next, current){
		target.addClass('hide');
		target_title.removeClass(animate_1);
		target_description.removeClass(animate_2);
		target_btn.removeClass(animate_3);


		if ($(current).find('video').length > 0) {
			$(current).find('video')[0].pause();
		}
		if ($(next).find('video').length > 0) {
			$(next).find('video')[0].play();
		}
	});

	UIkit.on('show.uk.slideshow', function(event, next, current){
		target.removeClass('hide');
		target_title.addClass(animate_1);
		target_description.addClass(animate_2);
		target_btn.addClass(animate_3);
	});

</script>
