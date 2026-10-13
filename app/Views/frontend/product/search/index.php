
<section class="page-body" style="padding-top:80px;">
<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
 ?>
<section class="page-body">
	<section class="va-articleCat-panel" >
		<div class="uk-container uk-container-center">
			<div class="dt_bg_grey">
				<div class="productList">
					<h1 class="heading-2"><?php echo $general['search_result'] ?></h1>
					<section class="artcatalogue mb50">
						<?php
							$empty = '';
						 ?>
						<?php if (isset($product) && check_array($product)): ?>
							<section class="product_list_panel">
								<?php
									$data = '';
									$data = array(
										'productList' => $product,
										'style' => 'grid-3',
									);
								 ?>
								<?php
									echo view('frontend/homepage/core/productList', $data, ['saveData' => true]);
								?>
								<div id="pagination" class="va-num-page">
									<?php echo (isset($pagination)) ? $pagination : ''; ?>
								</div>
							</section>
						<?php else: ?>
							<?php $empty = $general['search_result'].'!' ?>
						<?php endif ?>
						<seciton class="product_search_panel">
							<?php echo $empty ?>
						</seciton>
					</section>
				</div>
			</div>

		</div>
	</section>
</section>

<script>
	var moduleid = <?php echo isset($moduleid)? $moduleid : 0 ?>;
</script>
