<?php 
    // print_r($productList);
if(isset($productList) && is_array($productList) && count($productList)){ ?>
    <?php if(isset($style) && ($style == 'grid-2' || $style == 'grid-3' || $style == 'grid-4' || $style == 'grid-5'|| $style == 'grid-6')){ ?>
        <?php
                    if ($style == 'grid-2') $class = 'uk-grid-width-large-1-2';
            else    if ($style == 'grid-3') $class = 'uk-grid-width-large-1-3';
            else    if ($style == 'grid-4') $class = 'uk-grid-width-large-1-4';
            else    if ($style == 'grid-5') $class = 'uk-grid-width-large-1-5';
            else    if ($style == 'grid-6') $class = 'uk-grid-width-large-1-6';

        ?>
        <ul class="uk-grid uk-grid-medium uk-grid-width-1-1 <?php echo $class ?> dt_list-product dt_grid_responsive" data-uk-grid-match="{target: '.ht2109_grid_match'}">
            <?php foreach ($productList as $keyPost => $valPost) { ?>
                <?php
                    $status = json_decode($valPost['status'], true);

                    $title = $valPost['title'];
                    $sub_title = cutnchar(strip_tags($valPost['title']), 20);

                    $href = write_url($valPost['canonical']);
                    $image = $valPost['image'];
                    $description = strip_tags(base64_decode($valPost['description']));
                    $price = $valPost['price'];
                    $area = $valPost['area'];
                    $bed = $valPost['bed'];
                    $extra = [];
                    if(isset($valPost['extra']) && is_array($valPost['extra']) && count($valPost['extra'])){
                        foreach ($valPost['extra'] as $keyPostExtra => $valuePostExtra) {
                            if(isset($extra_list) && is_array($extra_list) && count($extra_list)){
                                foreach ($extra_list as $keyExtra => $valueExtra) {
                                    if($valuePostExtra == $valueExtra['id']){
                                        $extra[] = $valueExtra;
                                    }
                                }
                            }
                        }
                    }
                    
                    $rent_flag = 0;
                    
                ?>
                <li class="mb20">
                    <div class="uk-clearfix dt_product_item br6">
                    <div class="thumb_ratio style-11">
                        <a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-cover img-zoomin"><img src="<?php echo $image ?>" alt="<?php echo $title; ?>"></a>
                        <div  class="dt_overlay_info_label">
                            <ul class="uk-list uk-flex uk-flex-wrap dt_grid_responsive">
                                <?php if(isset($extra) && is_array($extra) && count($extra)){
                                    foreach ($extra as $keyExtra => $valueExtra) { 
                                ?>
                                <li class="mb5 mr10">
                                    <div class="lb_item_1"><a href="<?php echo write_url($valueExtra['canonical']) ?>" class="white" title="<?php echo $valueExtra['title'] ?>"><?php echo $valueExtra['title'] ?></a></div>
                                </li>
                                <?php }} ?>
                            </ul>
                        </div>
                        <div class="dt_overlay_info_item uk-hidden">
                            <div class="title"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="line-2 dt_item_title_2 txt_center link-color"><?php echo $title ?></a></div>
                        </div>
                    </div>
                    <div class="info">
                        <a href="<?php echo $href ?>" title="<?php echo $title ?>" class="dt_item_desc_2 line-2 mb10"><?php echo $valPost['title'] ?></a>
                        <div class="dt_item_content_2">
                            <ul class="uk-list uk-clearfix list_utilities">
                                <li>
                                    <div class="utilities_item price">
                                        <div class="uk-flex uk-flex-middle">
                                            <div class="icon">
                                                <div class="image img-scaledown">
                                                    <img src="public/frontend/resources/img/icon/dollar.png" alt="dollar">
                                                </div>
                                            </div>
                                            <?php
                                                $baseController = new App\Controllers\FrontendController();
                                                // $language = $baseController->currentLanguage();

                                                $language = request()->getLocale();


                                            ?>
                                            <?php if(isset($status) && is_array($status) && count($status)){
                                                if(in_array(29, $status) || in_array(30, $status)){
                                                    $rent_flag = 1;
                                                }
                                            } ?>
                                            <?php /* Truoc day: ternary 'vi' => '/ tháng', con lai '/ month' - nen tieng
                                                     Trung/Han/Nhat deu ra chu tieng Anh. Khoa PER da co du 5 ngon ngu. */ ?>
                                            <?php $en_string = '/ ' . lang('app.PER') ?>
                                            <?php if(isset($rent_flag)){ ?>
                                                <div class="title">
                                                    <?php echo dt_render_currency($price); ?> <?php echo (isset($rent_flag) && $rent_flag == 1 ) ? $en_string : ''; ?> 
                                                </div>
                                            <?php }else { ?>
                                                <div class="title">
                                                    <?php echo dt_render_currency($price); ?> </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="utilities_item num_bed">
                                        <div class="uk-flex uk-flex-middle">
                                            <div class="icon">
                                                <div class="image img-scaledown">
                                                    <img src="public/frontend/resources/img/icon/bed.png" alt="bed">
                                                </div>
                                            </div>
                                            <div class="title"><?php echo $bed ?></div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="utilities_item area">
                                        <div class="uk-flex uk-flex-middle">
                                            <div class="icon">
                                                <div class="image img-scaledown">
                                                    <img src="public/frontend/resources/img/icon/icon-2.png" alt="icon">
                                                </div>
                                            </div>
                                            <div class="title"><?php echo $area.' '.AREA ?> </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                </li>
            <?php } ?>
        </ul>
    <?php }else if(isset($style) && $style == 'list'){ ?>

    <?php }else if(isset($style) && $style == 'slide'){ ?>
        <?php
            /*slide owl*/
            $owlInit = array(
                'margin' => 10,
                'lazyload' => true,
                'nav' => false,
                'navText' => ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
                'autoplay' => true,
                'smartSpeed' => 1200,
                'autoplayTimeout' => 4000,
                'autoplayHoverPause' => true,
                'dots' => true,
                'loop' => false,
                'rewind' => true,
                'responsive' => array(
                    0 => array(
                        'items' => 2,
                    ),
                    480 => array(
                        'items' => 2,
                    ),
                    768 => array(
                        'items' => 3,
                    ),
                    960 => array(
                        'items' => 4,
                    ),
                )
            );
        ?>

        <section class="owl-slide mb-common">
            <!-- <div class="uk-container uk-container-center"> -->
                <div class="owl-carousel owl-theme" data-owl="<?php echo base64_encode(json_encode($owlInit)); ?>">
                    <?php foreach ($productList as $key => $val) {?>
                        <div class="item">
                            <div class="dt_product">
                                <div class="thumb_ratio style-43">
                                    <a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-cover img-zoomin"><img src="<?php echo $image ?>" alt="<?php echo $title ?>"></a>
                                </div>
                                <div class="info">
                                    <h3 class="title m0"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="ht2109_grid_match link line-3"><?php echo $title ?></a></h3>
                                    <?php if(isset($info) && is_array($info) && count($info)){ ?>
                                        <?php if(isset($info['flag']) && $info['flag'] == 1){ ?>
                                            <div class="product-price uk-text-center">
                                                <!-- <span>Giá: </span> -->
                                                <span class="new-price"><?php echo $info['price_final'] ?></span>
                                            </div>
                                        <?php }else{ ?>
                                            <!-- <div class="dt_label_sale">On <br>Sale</div> -->
                                            <div class="product-price uk-text-center">
                                                <span class="new-price"><?php echo $info['price_final'] ?></span>
                                                <span class="old-price"><?php echo $info['price_old'] ?></span>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <!-- </div> -->
        </section>

        <style>
            /* OWL CAROUSEL*/
            .owl-carousel .owl-prev,
            .owl-carousel .owl-next{
                display: block;
                top: 50%;
                transform: translate(0, -50%);
                -webkit-transform: translate(0, -50%);
                -moz-transform: translate(0, -50%);
                -o-transform: translate(0, -50%);
                left: initial;
                right: -40px;
                /*width: 30px;*/
                /*height: 30px;*/
                /*line-height: 26px !important;*/
                /*font-size: 40px;*/
                position:absolute;
                margin:0 !important;
                outline:0 !important;
                transition:all 0.5s ease;
                -webkit-transition: all 0.5s ease;
                -moz-transition: all 0.5s ease;
                font-family:'Open Sans';

            }
            .owl-carousel .owl-prev{
                left:-40px;
            }
            .owl-carousel .owl-prev > *,
            .owl-carousel .owl-next > *{
                font-size: 40px !important;
                color:#ccc;
            }
            .owl-theme .owl-nav [class*=owl-]:hover{
                background: transparent !important;
            }
            .owl-carousel .owl-prev:hover > *,
            .owl-carousel .owl-next:hover > *{
                color: #666;
            }

            .owl-stage {
                display: flex;
            }

            .owl-theme .owl-nav{
                margin-top: 40px;
            }
            .owl-theme .owl-dots{
                position: absolute;
                left: 50%;
                bottom: -30px;
                transform: translate(-50%, 0);
                display: block;
                height: 20px;
            }

            .owl-theme .owl-dots .owl-dot {
                display: inline-block;
                zoom: 1;
                outline: 0;
            }

            .owl-item {
                display: flex;
                flex: 1 0 auto;
            }

            .item{
                position: relative;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                width: 100%;
            }
            .item>*{
                flex-grow: 1
            }
        </style>
    <?php }else{ ?>

    <?php } ?>
<?php } ?>

<?php /*
<div class="wrap-price mb10">
    <span class="old dt_item_price_1<?php echo (isset($valPost['price_promotion']) && $valPost['price_promotion'] != 0) ? 'line-price' : '' ?>"><?php echo number_format($valPost['price'],0,',','.') ?> <?php echo CURRENCY ?></span>
    <span class="new dt_item_price_1" style="<?php echo (isset($valPost['price_promotion']) && $valPost['price_promotion'] != 0) ? '' : 'display: none;' ?>">
        <?php echo number_format($valPost['price_promotion'],0,',','.') ?> <?php echo CURRENCY ?>
    </span>
</div>
*/ ?>
