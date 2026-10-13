<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<section class="artcatalogue">
	<div class="uk-container uk-container-center">
		<h1 class="cat_heading_1 txt-center"><?php echo $breadcrumb[0]['title'] ?></h1>
		<?php 
    		$actual_link = "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$_class = ($actual_link == BASE_URL.$breadcrumb[0]['canonical'].HTSUFFIX)? 'uk-active':'';
		 ?>
		<section class="panel-body">
			<ul class="uk-list uk-clearfix nav-tabs">
				<li class="<?php echo $_class ?>"><a href="<?php echo $breadcrumb[0]['canonical'].HTSUFFIX ?>" title="">Tất cả</a></li>
				<?php if (isset($_catalogue) && check_array($_catalogue)): ?>
					<?php 
						foreach ($_catalogue as $key => $val) {
							$title = $val['title'];
							$href = $val['canonical'].HTSUFFIX;
							$_class = ($actual_link == BASE_URL.$href)? 'uk-active':'';
					 ?>
					<li class="<?php echo $_class ?>"><a href="<?php echo $href ?>" title="<?php echo $title ?>"><?php echo $title ?></a></li>
					<?php } ?>
				<?php endif ?>
			</ul>
			<div class="tab-content">
			<?php if (isset($articleList) && check_array($articleList)): ?>
				<ul class="uk-list uk-clearfix list-file">
					<?php 
						foreach ($articleList as $key => $val) {
							$title = $val['title'];
							$href = $val['files'];
							$download = 'download';
							$created_at = gettime($val['created_at'], 'd/m/Y');
					 ?>
						<li>
							<div class="file-item">
								<div class="uk-flex uk-flex-middle uk-flex-wrap uk-flex-space-between">
								    <div class="info">
								    	<a href="<?php echo $href ?>" class="title"><?php echo $title ?></a>
								    	<div class="time">(<?php echo $created_at ?>)</div>
								    </div>
								    <div class="tttin">
						                <a href="<?php echo $href ?>" class=""><img src="public/pdf.png" class="icon-img" alt=""> Xem</a> |
						                <a href="<?php echo $href ?>" class="tai" <?php echo $download ?>>Tải về</a>
						            </div>
								</div>
							</div>
						</li>
					<?php } ?>
				</ul>
				<div id="" class="dt_pagination">
		            <?php echo (isset($pagination)) ? $pagination : ''; ?>
		        </div>
			<?php else: ?>
				<div class="file-item">
				    <div class="info">
				    	<div class="title">Không có dữ liệu phù hợp</div>
				    </div>
				</div>
			<?php endif ?>
			</div>
		</section>
	</div>
</section>
