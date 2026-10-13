<?php echo view('frontend/search/common_head') ?>
    <section class="artcatalogue mb50">
        <section class="search_panel search_product_panel">
            <div class="cat_heading_1 text-white bg_heading br6 mb20 db"><?php echo $general['search_result'] ?>
                <span class="number_search">(<?php echo $total_rows_prd ?>)</span>
            </div>
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
            <?php else: ?>
                <p class=""><?php echo dt_notice('empty_search') ?></p>
            <?php endif ?>
        </section>
    </section>
<?php echo view('frontend/search/common_foot') ?>
