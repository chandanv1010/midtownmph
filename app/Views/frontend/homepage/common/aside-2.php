<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
?>

<aside class="aside" data-uk-sticky="{boundary:true}">
	<section class="aside-panel">
		<header class="aside_panel-head"><h3 class="aside-heading"><span>Danh mục sản phẩm</span></h3></header>
		<?php if(isset($category) && is_array($category) && count($category)){?>
		<section class="aside_panel-body">
			<ul class="uk-list uk-clearfix aside-list">
				<?php foreach($category as $key => $val){?>
					<?php
						$title = $val['title'];
						$href = write_url($val['canonical'], true, true);
					?>
					<li><a href="<?php echo $href;?>" title="<?php echo $title;?>"><?php echo $title;?></a></li>
				<?php }?>
			</ul>
		</section>
		<?php }?>
	</section> <!-- .aside-panel-->

	<section class="aside-panel">
		<header class="aside_panel-head"><h3 class="aside-heading"><span>Facebook</span></h3></header>
		<section class="aside_panel-body">
			<div class="fb-page" data-href="https://www.facebook.com/noithattoancau.vn/" data-small-header="false" data-lazyload="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
		</section>
	</section> <!-- .aside-panel-->

</aside>
