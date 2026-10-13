<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<section class="artcatalogue">
	<div class="uk-container uk-container-center">
		<h1 class="cat_heading_1 txt-center mb30"><?php echo $detailCatalogue['title'] ?></h1>
		<?php if (isset($mediaList) && check_array($mediaList)): ?>
			<section class="panel-body">
				<?php 
					$_data = [
						'mediaList' => $mediaList,
						'style' => 'grid-4',
					];
					echo view('frontend/homepage/core/mediaList', $_data, ['saveData' => true]);
			 	?>
				
			</section>
			<div id="pagination" class="va-num-page">
	            <?php echo (isset($pagination)) ? $pagination : ''; ?>
	        </div>
		<?php endif ?>
	</div>
</section>
