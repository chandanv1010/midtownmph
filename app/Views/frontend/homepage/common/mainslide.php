<?php
    $slide = get_slide(array(
    	'keyword' => 'sslide',
    	'language' => request()->getLocale(),   // truoc day bo trong nen luon lay slide tieng Viet
    	'type' => '',
    	'output' => 'array',
    ));
    $slide = isset($slide['data'])? $slide['data'] : [];
?>

<?php if(isset($slide) && is_array($slide) && count($slide)){ ?>
<section class="main-slide">
	<!-- <div class="uk-container uk-container-center uk-container-1"> -->
		<div class="uk-slidenav-position slide-show" data-uk-slideshow="{autoplay: true, autoplayInterval: 7500, }">
			<!-- kenburns:'15s' -->
			<ul class="uk-slideshow">
				<?php foreach($slide as $key => $val) { ?>
					<?php
						$image = $val['image'];
						$check_mp4 = process_img_link_mp4($image, true);
						$title = $val['title'];
						$href = $val['url'];
						$description = cutnchar(strip_tags($val['description']), 250);
						$content = cutnchar(strip_tags($val['content']), 250);
					?>
				<li>
					<?php if ($check_mp4): ?>
						<div class="__iframe-video" >
							<video src="<?php echo $image; ?>" autoplay muted loop></video>
						</div>
					<?php else: ?>
						<img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" />
					<?php endif ?>
				</li>
				<?php } ?>
			</ul>
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
