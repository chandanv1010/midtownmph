<?php if(isset($listSlide) && is_array($listSlide) && count($listSlide)){ ?>	
<?php foreach($listSlide as $key => $val){ ?>
	<?php $count += $key;  ?>

<li class="tv-block ui-state-default ui-sortable-handle">
	<div class="tv-slide-container">
		<div class="col-sm-4">
			<div class="thumb tv">
				<span class="image img-scaledown">

					<img src="<?php echo isset($val['image_preview'])? $val['image_preview']: '' ?>" alt="" /> 
					<input type="hidden" value="<?php echo isset($val['image'])? $val['image']: '' ?>" name="data[<?php echo $count ?>][image]"/>
				</span>
				<div class="overlay"></div>
				<div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>
				<div class="tv order"><input  value="<?php echo isset($val['order'])? $val['order']: '0' ?>" type="text"  class=" tv-input" name="data[<?php echo $count ?>][order]"></div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="tabs-container tv">
				<ul class="nav nav-tabs tv-nav-tabs">
					<li class=" tab-0 tab-pane active"><a href=".tab-0" aria-expanded="true"> Thông tin chung</a></li>
					<li class="tab-1 tab-pane"><a href=".tab-1" aria-expanded="false">SEO</a></li>
				</ul>
				<div class="tab-content tv-tab-content">
					<div  class="tab-0 tab-pane active">
						<div class="panel-body">
							<div class="row mb5">
								<input  placeholder="URL..." type="text"  class="form-control m-b" name="data[<?php echo $count ?>][url]" value="<?php echo isset($val['url'])? $val['url']: '' ?>">
							</div>
							<div class="row ">
								<input  placeholder="Tiêu đề..." type="text"  class="form-control m-b tv-text" name="data[<?php echo $count ?>][title]" value="<?php echo isset($val['title'])? $val['title']: '' ?>">
							</div>
						</div>
					</div>
					<div  class="tab-1 tab-pane">
						<div class="panel-body">
							<div class="row mb5">
								<div class="form-row">
									<input  placeholder="Mô tả..." type="text"  class="form-control m-b" name="data[<?php echo $count ?>][description]" value="<?php echo isset($val['description'])? $val['description']: ''; ?>">
								</div>
							</div>
							<div class="row mb18">
								<div class="form-row">
									<input  placeholder="Nội dung..." type="text"  class="form-control m-b tv-text" name="data[<?php echo $count ?>][content]" value="<?php echo isset($val['content'])? $val['content']: ''; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</li>
<?php } $count++;}   ?>
<script type="text/javascript">
	var count = <?php echo $count ?>;
</script>