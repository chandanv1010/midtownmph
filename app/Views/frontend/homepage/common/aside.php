<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
    $article_catalogue =  get_menu([
        'keyword' => 'article_catalogue',
        'language' => $language,
        'output' => 'array'
    ]);
?><?php

	$sidebar = get_menu(['keyword' => 'sidebar','language' => $language, 'output' => 'array']);
?>

<aside class="aside" data-uk-sticky="{top:51,boundary: true}">
	<section class="aside-panel">
			<header class="aside_panel-head"><h3 class="aside-heading"><span>Danh mục thiết kế</span></h3></header>
			<?php if(isset($sidebar['data']) && is_array($sidebar['data']) && count($sidebar['data'])){?>
			<section class="aside_panel-body">
				<ul class="uk-list uk-clearfix aside-list">
					<?php foreach($sidebar['data'] as $key => $val){?>
						<?php
	 						$title = $val['title'];
							$href = write_url($val['canonical'], TRUE, TRUE)
						?>
						<li><a href="<?php echo $href;?>" title="<?php echo $title;?>"><?php echo $title;?></a></li>
					<?php }?>
				</ul>
			</section>
			<?php }?>
		</section> <!-- .aside-panel-->

		<section class="aside-form">
			<div class="aside-heading-1">Điền thông tin tư vấn</div>
			<form action="" method="post" class="uk-form form" id="form-register">
				<div class="error hidden"></div>
				<div class="form-row">
					<input type="text" class="input-text" required id="fullname" name="fullname" placeholder="Họ Tên" />
				</div>
				<div class="form-row">
					<input type="text" class="input-text" id="phone" required name="phone" placeholder="Số điện thoại" />
				</div>
				<div class="form-row">
					<input type="text" class="input-text" id="email" required name="email" placeholder="Email" />
				</div>
				<div class="form-row">
					<input type="submit" value="Gửi" name="create">
				</div>
			</form>
		</section>

</aside>
