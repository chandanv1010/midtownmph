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
                        <li class="breadcrumb-home"><a href=""> <i class="fa fa-home"></i> Trang chủ</a></li>
                        <li class=""><a href="<?php echo $_SERVER['REDIRECT_URL'] ?>" ><span><?php echo ($_SERVER['REDIRECT_URL'] == '/thuong-hieu.html') ? 'Thương hiệu' : 'Tìm kiếm' ?></span></a></li>
                    </ul>
                <!-- </div> -->
            </section>
            <section class="artcatalogue mb50">
                <section class="search_panel search_product_panel">
                    <div class="cat_heading_1 text-white bg_heading br6 mb20 db"><?php echo SEARCH_PRD ?></div>
                    <?php echo view('frontend/homepage/common/filter_product'); ?>
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
                        </section>
                    <?php endif ?>
                </section>
            </section>
        </div>
    </div>
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

