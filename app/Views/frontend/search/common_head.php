<?php
    helper(['mydatafrontend','mydata']);
    $baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
    $footer = get_panel([
        'locate' => 'footer',
        'language' => $language
    ]);
?>
<section class="main-slide dt-mainslide por">
    <div class="uk-slidenav-position slide-show" data-uk-slideshow="{autoplay: true, autoplayInterval: 7500, }">
        <!-- kenburns:'15s' -->
        <ul class="uk-slideshow">
                <?php
                    $image = $general['search_page_image'];
                ?>
            <li>
                <img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" />
            </li>
        </ul>
    </div>
</section><!-- .main-slide -->

<section class="va-articleCat-panel"  data-module="<?php echo check_isset($module); ?>" data-canonical="">
    <div class="uk-container uk-container-center">
        <div class="dt_bg_grey">
            <section class="breadcrumb-panel">
                <!-- <div class="uk-container-center uk-container"> -->
                    <ul class="uk-breadcrumb uk-clearfix ">
                        <li class="breadcrumb-home"><a href=""> <i class="fa fa-home"></i> <?php echo esc(lang('app.HOME_LINK')) ?></a></li>
                        <li class=""><a href="<?php echo $_SERVER['REDIRECT_URL'] ?>" ><span><?php echo ($_SERVER['REDIRECT_URL'] == '/thuong-hieu.html') ? lang('app.BRAND_PAGE') : lang('app.SEARCH_TITLE') ?></span></a></li>
                    </ul>
                <!-- </div> -->
            </section>