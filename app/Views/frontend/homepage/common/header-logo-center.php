<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
	$cart = \Config\Services::cart();
    $mainNav =  get_menu(['language' => $language, 'keyword' => 'main-menu', 'output' => 'html']);
 ?>
<header class="pc-header uk-visible-large ishome">
 	<div class="hd-menu-search top-search bg-theme" style="display: none;">
		<div class="uk-container uk-container-center">
			<form action="" method="get" class="uk-form form">
				<div class="uk-flex uk-flex-middle">
					<button type="submit" name="" value="" class="btn-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
					<div class="form-row">
						<input type="text" name="keyword" class="uk-width-1-1 input-text suggest_search" placeholder="Tìm kiếm ..." />
					</div>
					<a class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></a>
				</div>
			</form>
		</div>
	</div>
 	<div class="uk-container uk-container-center por">
		<div class="uk-flex uk-flex-middle uk-flex-space-between">
			<div id="main-nav">
				<?php echo $mainNav ?>
			</div>
			<?php echo logo(); ?>
			<div class="uk-flex uk-flex-middle">
				<div class="hd-search">
					<form action="<?php echo BASE_URL.'tim-kiem'.HTSUFFIX ?>" method="get" class="uk-form form">
						<div class="form-row">
							<input type="text" name="keyword" value="" placeholder="Search" class="uk-width-1-1 input-text suggest_search">
							<button type="submit" class="btn-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
						</div>
					</form>
				</div>
				<div class="uk-flex uk-flex-bottom">
					<a href="" title="" class="dt_cart_icon">
						<svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-account" viewBox="0 0 17 18"><path fill="#646464" d="M14.51 11.906a8.467 8.467 0 0 0-3.23-2.026 4.912 4.912 0 0 0 2.134-4.05A4.92 4.92 0 0 0 8.5.918 4.92 4.92 0 0 0 3.586 5.83c0 1.678.846 3.162 2.134 4.05a8.467 8.467 0 0 0-3.23 2.025A8.444 8.444 0 0 0 0 17.916h1.328c0-3.954 3.217-7.171 7.172-7.171a7.18 7.18 0 0 1 7.172 7.172H17c0-2.27-.884-4.405-2.49-6.01zM8.5 9.416a3.59 3.59 0 0 1-3.586-3.585A3.59 3.59 0 0 1 8.5 2.245a3.59 3.59 0 0 1 3.586 3.586A3.59 3.59 0 0 1 8.5 9.417z"></path></svg>
					</a>
					<a href="" title="" class="dt_cart_icon icon-cart">
						<svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-cart" viewBox="0 0 13.5 17"><path fill="#646464" d="M12.771 5.236c-.044-.697-.54-1.236-1.135-1.234h-1.504C10.132 1.792 8.606 0 6.723 0 4.841 0 3.315 1.792 3.315 4.002H1.819C1.223 4 .729 4.539.684 5.236l-.68 10.333c-.024.37.085.735.3 1.007.216.271.518.425.834.424h11.18c.315.001.617-.153.833-.424a1.48 1.48 0 0 0 .3-1.007l-.68-10.333zM6.727 1.337c1.252.006 2.265 1.196 2.27 2.665H4.458c.005-1.47 1.018-2.66 2.27-2.665zm-5.59 14.33L1.82 5.335h1.504v.987h1.135v-.987h4.539v.987h1.135v-.987h1.504l.681 10.334H1.137z"></path></svg>
					</a>
				</div>
			</div>
		</div>
	</div>
</header><!-- .pc-header -->
<?php if (!isset($ishome)): ?>
	<div class="hinhbn"></div>
	<style>
		.hinhbn {
		    display: block;
		    width: 100%;
		    min-height: 105px;
		}
		@media (max-width: 959px) {
		   	.hinhbn {
			    display: none;
			}
		}
	</style>
<?php endif ?>
 <!-- MOBILE HEADER -->
 <header class="mobile-header uk-hidden-large">
 	<section class="upper">
 		<a class="moblie-menu-btn skin-1" href="#offcanvas" class="offcanvas" data-uk-offcanvas="{target:'#offcanvas'}">
 			<span>Menu</span>
 		</a>
 		<div class="logo"><a href="" title=""><img src="<?php echo $general['homepage_logo']; ?>" alt="<?php echo $general['seo_meta_title']; ?>"/></a></div>
 	</section><!-- .upper -->
 </header><!-- .mobile-header -->

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