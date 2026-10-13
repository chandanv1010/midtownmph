<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
?><?php

	$sidebar = get_panel([
		'language' => $language, 
		'locate' => 'sidebar'
	]);

	// $aside_art = isset($sidebar['aside-art'])? $aside['aside-art'] : [];
?>

<aside id="aside-art" data-uk-sticky="{top:51,boundary: true}">
	<?php if (check_array($sidebar)): ?>
		<?php 
			foreach ($sidebar as $key => $val) {
				$title = $val['title'];
		 ?>
		<section class="aside-panel">
			<header class="aside_panel-head"><h3 class="aside-heading"><span><?php echo $title ?></span></h3></header>
			<?php if(isset($val['data']) && is_array($val['data']) && count($val['data'])){?>
			<section class="aside_panel-body">
				<ul class="uk-list uk-clearfix aside-list">
					<?php foreach($val['data'] as $key => $val){?>
						<?php
	 						$title = $val['title'];
	 						$image = $val['image'];
							$href = write_url($val['canonical'], TRUE, TRUE);
						?>
						<li>
							<div class="aside_art_item">
								<div class="thumb_ratio style-43 mb5"><a href=" <?php echo $href ?>" class="image img-cover img-zoomin"><img src="<?php echo $image ?>" alt=""></a></div>
								<a href="<?php echo $href ?>" title="<?php echo $title ?>" class="dt_item_title_3"><?php echo $title ?></a>
							</div>
						</li>
					<?php }?>
				</ul>
			</section>
			<?php }?>
		</section> <!-- .aside-panel-->
		<?php } ?>
	<?php endif ?>
</aside>
