<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
    $footer = get_panel([
		'locate' => 'footer',
		'language' => $language
	]);

    $album = $object['album'];
?>
<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<div id="media_detail_page" class="page-body">
	<section class="index-section mb-common">
		<div class="uk-container uk-container-center">
			<h1 class="cat_heading_1 mb30 txt-center"><?php echo $object['title'] ?></h1>

			<section class="album">
				<!-- album here -->
			</section>

			<div class="entry-description"><?php echo $object['content'] ?></div>
		</div>
	</section>
</div>
