<?php echo view('frontend/search/common_head') ?>
    <section class="search_panel search_product_panel">
        <div class="cat_heading_1 text-white bg_heading br6 mb20 db"><?php echo $general['search_result'] ?>
            <span class="number_search">(<?php echo $total_rows_prd ?>)</span>
        </div>
        <?php if (isset($productList) && check_array($productList)): ?>
            <?php echo view('frontend/homepage/common/filter_product'); ?>
            <?php
                $empty = '';
             ?>
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
        <?php else: ?>
            <p class=""><?php echo dt_notice('empty_search') ?></p>
        <?php endif ?>
    </section>
    <section class="search_panel search_article_panel uk-hidden">
        <div class="cat_heading_1 text-white bg_heading br6 mb20 db"><?php echo SEARCH_NEW ?></div>
        <?php
            $empty = '';
         ?>
        <?php if (isset($articleList) && check_array($articleList)): ?>
            <section class="product_list_panel">
                <?php
                    $data = '';
                    $data = array(
                        'articleList' => $articleList,
                        'style' => 'grid-3',
                    );
                 ?>
                <?php
                    echo view('frontend/homepage/core/articleList', $data, ['saveData' => true]);
                ?>
            </section>
        <?php else: ?>
            <p class=""><?php echo dt_notice('empty_search') ?></p>
        <?php endif ?>
    </section>
<?php echo view('frontend/search/common_foot') ?>
