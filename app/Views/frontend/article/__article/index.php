<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
    $footer = get_panel([
		'locate' => 'footer',
		'language' => $language
	]);
?>
<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<div id="article_detail_page" class="page-body">
	<section class="index-section mb-common">
		<div class="uk-container uk-container-center">
			<h1 class="cat_heading_3 mb30"><?php echo $object['title'] ?></h1>
			<div class="entry-description va-content"><?php echo $object['content'] ?></div>

             <?php  
                $locale = request()->getLocale(); 
                 $headings = [
                    'vi' => 'Bài viết liên quan',
                    'en' => 'Related articles',
                    'tq' => '相关文章'
                ];
              
            ?>
			<section class="relate_art">
				<div class="cat_heading_3 mb15"><?php echo $headings[$locale] ?></div>
				<?php if (isset($article_relate) && check_array($article_relate)): ?>
				<section class="panel-body">
					<?php 
						$_data = [
							'articleList' => $article_relate,
							'style' => 'grid-4',
						];
						echo view('frontend/homepage/core/articleList', $_data, ['saveData' => true]);
				 	?>

				</section>
				<div class="dt_pagination">
		            <?php echo (isset($pagination)) ? $pagination : ''; ?>
		        </div>
			<?php endif ?>
			</section>
		</div>
	</section>
</div>
