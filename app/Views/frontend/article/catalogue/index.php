<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<section class="artcatalogue">
	<div class="uk-container uk-container-center">
		<h1 class="cat_heading_1 txt-center mb20"><?php echo $detailCatalogue['title'] ?></h1>
		<?php if ($detailCatalogue['content'] != ''): ?>
		<div class="dt_item_desc_1 mb20"><?php echo base64_decode($detailCatalogue['content']) ?></div>
		<?php endif ?>
		<?php if (isset($articleList) && check_array($articleList)): ?>
            <?php pre($articleList); ?>
			<section class="panel-body">
				<?php 
					$_data = [
						'articleList' => $articleList,
						'style' => 'grid-4',
					];
					echo view('frontend/homepage/core/articleList', $_data, ['saveData' => true]);
			 	?>

			</section>
			<div class="dt_pagination">
	            <?php echo (isset($pagination)) ? $pagination : ''; ?>
	        </div>
		<?php endif ?>
	</div>
</section>
