<?php

	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = request()->getLocale();

    $__currency = (isset($_COOKIE[AUTH_CURRENCY])) ? $_COOKIE[AUTH_CURRENCY] : '';
	$__currency = json_decode($__currency, TRUE);

	$cart = \Config\Services::cart();
    $mainNav =  get_menu(['language' => $language, 'keyword' => 'main-menu', 'output' => 'html']);

 ?>

<header class="pc-header uk-visible-large ishome" >
 	<div class="hd-menu-search top-search bg-theme" style="display: none;">
		<div class="uk-container uk-container-center">
			<form action="" method="get" class="uk-form form">
				<div class="uk-flex uk-flex-middle">
					<button type="submit" name="" value="" class="btn-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
					<div class="form-row">
						<input type="text" name="keyword" class="uk-width-1-1 input-text suggest_search" placeholder="" />
					</div>
					<a class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></a>
				</div>
			</form>
		</div>
	</div>
 	<div class="uk-container uk-container-center">
		<div class="uk-flex uk-flex-middle uk-flex-space-between">
			<?php if(!empty($general['homepage_logo'])){ ?>
			<?php echo logo(); ?>
			<?php } ?>
			<?php echo $mainNav ?>
			<div class="hd-search-2">
				<div class="button-search">
					<a href="#my-id" data-uk-modal><?php echo esc(lang('app.SEARCH_TITLE')) ?></a>
					<button type="submit" class="btn-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
				</div>
			</div>

			<!-- __language -->
			<div class="uk-flex uk-flex-middle">
				<?php
					$currency_init = get_currency([
						'publish' => 1,
					]);

					$currency = $currency_init;
					if (check_array($currency_init)) {
						$currency = array_column($currency_init, 'title', 'id');
					}

					$currency_id = 0; // k tồn tại cookie
					if (isset($__currency) && check_array($__currency)) {
						$currency_id = $__currency['currency_id'];
					}
			 	?>
			 	<?php if (check_array($currency)): ?>
					<div class="uk-flex uk-flex-middle dt_language js_dt_currency">
						<?php
							foreach ($currency as $key => $val) {
								$_class = ($currency_id == $key)? 'active': '';
								if ($currency_id == 0) {
									$_class = ($val == array_values($currency)[0])? 'active':'';
								}
					 	?>
					        <a class="<?php echo $_class ?>" title="<?php echo $val ?>" data-currency_id="<?php echo $key ?>"><?php echo $val ?></a>
					    <?php } ?>
					</div>
				<?php endif ?>

				<?php
					/* Nut chuyen ngon ngu sinh tu du lieu, khong viet cung nua.
					 * Xem language_switcher() trong app/Helpers/mydata_helper.php. */
					$languageLinks = language_switcher($language);
				?>
				<div class="uk-flex uk-flex-middle dt_language">
					<?php echo $languageLinks ?>
				</div>
			</div>
		</div>
	</div>
</header><!-- .pc-header -->

 <!-- MOBILE HEADER -->
 <header class="mobile-header uk-hidden-large" data-uk-sticky>
 	<section class="upper">
 		<a class="moblie-menu-btn skin-1" href="#offcanvas" class="offcanvas" data-uk-offcanvas="{target:'#offcanvas'}">
 			<span>Menu</span>
 		</a>
		<div class="uk-flex uk-flex-middle uk-flex-center dt_language ">
			<?php echo $languageLinks ?>
		</div>
		<div class="hd-filter">
			<a href="#my-id" data-uk-modal><i class="fa fa-search" aria-hidden="true"></i></a>
		</div>

 	</section><!-- .upper -->
 </header><!-- .mobile-header -->
<div id="my-id" class="uk-modal search-modal-box">
	<div class="uk-modal-dialog uk-modal-dialog-small">

		<a class="uk-modal-close uk-close"> <i class="fa fa-times" aria-hidden="true"></i></a>
		<div class="">
			<section class="artcatalogue mb50">
				<section class="search_panel search_product_panel">
					<div class="cat_heading_1 text-white bg_heading br6 mb20 db"><?php echo $general['search_title'] ?>
					</div>
					<?php echo view('frontend/homepage/common/filter_product'); ?>
				</section>
			</section>
		</div>
	</div>
</div>
<script>
	$(document).on('click', '.open-search', function(event) {
	    event.preventDefault();
	    let _this = $(this);
	  	_this.toggleClass('active');
    	// _this.siblings('.dropdown-search').toggleClass('active');
    	$('.top-search').toggleClass('active');
    	$('.top-search').slideToggle();

	    return false;
	});

	$(document).on('click', '.btn-close', function(event) {
	    $(this).closest('header').find('.open-search').trigger('click');
	});

	 /*body click*/
	$('body').click(function(e) {
	  	var target = $(e.target);
	  	if((!target.is('.hd-menu-search') && $('.hd-menu-search').has(e.target).length === 0))
	  	{
		   	if ( $('.open-search').hasClass('active')){
			    $('.open-search').removeClass('active');
				e.preventDefault();
		  	}
			if ( $('.top-search').hasClass('active')){
			    $('.top-search').removeClass('active');
			    $('.top-search').slideUp();
				e.preventDefault();
		  	}
	  	}

	});

	var actual_link = $(location).attr('pathname');
		actual_link = actual_link.substr(1);

	var parent_url = '<?php echo !empty($breadcrumb[0]['canonical'])? $breadcrumb[0]['canonical'].'.html': 'false' ?>';
	$('.main-menu>li').each(function(){
		let _this = $(this);

		let url = _this.attr('data-url');
		if(actual_link == url){
			_this.addClass('active');
		}else{
			if(parent_url == url){
				_this.addClass('active');
			}
		}
	});



</script>
