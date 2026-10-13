<?php
    helper('form', 'data');
    $baseController = new App\Controllers\BaseController();
	$model = new App\Models\AutoloadModel();
    $language = $baseController->currentLanguage();
    $get_catalogue = check_type_canonical($language);
    if($get_catalogue['content'] == 'silo'){
    	$class = 'get_catalogue';
    }else{
    	$class = '';
    }

    $general = get_general($language);
?>

<form method="post" action="" >
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="box-body">
				<?php echo  (!empty($validate) && isset($validate)) ? '<div class="alert alert-danger">'.$validate.'</div>'  : '' ?>
			</div><!-- /.box-body -->
		</div>

		<div class="row">
			<div class="col-lg-8 clearfix">
				<div class="ibox mb20">
					<div class="ibox-title" style="padding: 9px 15px 0px;">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Thông tin cơ bản <small class="text-danger">Điền đầy đủ các thông tin được mô tả dưới đây</small></h5>
							<div class="ibox-tools">
								<button type="submit" name="create" value="create" class="btn btn-primary block full-width m-b">Lưu</button>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row mb15">
							<div class="col-lg-12">
								<div class="form-row">
									<label class="control-label text-left">
										<span>Tiêu đề bất động sản <b class="text-danger">(*)</b></span>
									</label>
									<?php echo form_input('title', validate_input(set_value('title', (isset($product['title'])) ? $product['title'] : '')), 'class="form-control '.(($method == 'create') ? 'title' : '').'" placeholder="" id="title" autocomplete="off"'); ?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-lg-12">
								<div class="form-row form-description">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<label class="control-label text-left">
											<span>Mô tả ngắn</span>
										</label>
										<a href="" title="" data-target="description" class="uploadMultiImage">Upload hình ảnh</a>
									</div>
									<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description', (isset($product['description'])) ? $product['description'] : ''))), 'class="form-control ck-editor" id="description" placeholder="" autocomplete="off"');?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12	">
								<div class="form-row mb15">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<label class="control-label text-left">
											<span>Nội dung</span>
										</label>
										<a href="" title="" data-target="content" class="uploadMultiImage">Upload hình ảnh</a>
									</div>
									<?php echo form_textarea('content', htmlspecialchars_decode(html_entity_decode(set_value('content', (isset($product['content'])) ? $product['content'] : ''))), 'class="form-control ck-editor" id="content" placeholder="" autocomplete="off"');?>
								</div>
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<label class="control-label text-left ">
										<span>Nội dung mở rộng</span>
									</label>
									<a href="" title="" class="add-attr" onclick="return false;">Thêm nội dung +</a>
								</div>
							</div>

						</div>

					</div>
				</div>
				<div class="ibox">
					<div class="row" id="sortable-view">
					    <div class="col-lg-12 ui-sortable attr-more">
					    	<?php if(isset($product['sub_title']) && is_array($product['sub_title']) && count($product['sub_title'])){ ?>
							<?php foreach ($product['sub_title'] as $key => $value) {?>
								<?php $id = slug($value) ?>
						        <div class="ibox desc-more" style="opacity: 1;">
						            <div class="ibox-title ui-sortable-handle ">
						            	<div class="uk-flex uk-flex-middle row">
							                <div class="col-lg-8">
												<input type="text" name="sub_content[title][]" class="form-control" value="<?php echo $value ?>" placeholder="Tiêu đề">
											</div>
											<div class="col-lg-4">
												<div class="uk-flex uk-flex-middle uk-flex-space-between">
													<a href="" title="" data-target="<?php echo $id ?>" class="uploadMultiImage">Upload hình ảnh</a>
									                <div class="ibox-tools">
									                    <a class="collapse-link ui-sortable">
									                        <i class="fa fa-chevron-up"></i>
									                    </a>
									                    <a class="close-link">
									                        <i class="fa fa-times"></i>
									                    </a>
									                </div>
												</div>
											</div>

						            	</div>
						            </div>
						            <div class="ibox-content" style="">
						            	<div class="row">
							                <div class="col-lg-12" >
							                	<textarea name="sub_content[description][]" class="form-control ck-editor" id="<?php echo $id ?>" placeholder="Mô tả"><?php echo $product['sub_content'][$key] ?></textarea>
											</div>
										</div>
						            </div>
						        </div>
					        <?php }} ?>
					    </div>
					</div>
				</div>
				<div class="ibox mb20 album">
					<div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Album Ảnh </h5>
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<div class="edit">
									<a onclick="BrowseServerAlbum($(this));return false;" href="" title="" class="upload-picture">Chọn hình</a>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<?php
							if(isset($_POST['album'])){
								$album = $_POST['album'];
							}else if(isset($product)){
								$album = json_decode($product['album'], TRUE);
							}

						 ?>
						<div class="row">
							<div class="col-lg-12">
								<div class="click-to-upload" <?php echo (isset($album))?'style="display:none"':'' ?>>
									<div class="icon">
										<a type="button" class="upload-picture" onclick="BrowseServerAlbum($(this));return false;">
											<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
										</a>
									</div>
									<div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
								</div>
								<div class="upload-list" <?php echo (isset($album))?'':'style="display:none"' ?> style="padding:5px;">
									<div class="row">
										<ul id="sortable" class="clearfix data-album sortui">
											<?php if(isset($album) && is_array($album) && count($album)){ ?>
											<?php foreach($album as $key => $val){ ?>
												<li class="ui-state-default">
													<div class="thumb">
														<span class="image img-scaledown">
															<img src="<?php echo $val; ?>" alt="" /> <input type="hidden" value="<?php echo $val; ?>" name="album[]" />
														</span>
														<div class="overlay"></div>
														<div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>
													</div>
												</li>
											<?php }} ?>
										</ul>
									</div>
								</div>
								<hr>
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<label class="control-label text-left ">
										<span><?php echo translate('cms_lang.tour.tour_img_sub', $language) ?></span>
									</label>
									<a href="" title="" class="add-album" onclick="return false;"><?php echo translate('cms_lang.tour.tour_img_add', $language) ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ibox mb20">
					<div class="row" id="sortable-view">
					    <div class="col-lg-12 ui-sortable album-more">
					    	<?php if(isset($sub_album) && is_array($sub_album) && count($sub_album)){
					    		foreach ($sub_album as $key => $value) {
					    	 ?>
						    	<div class="ibox desc-more album" style="opacity: 1;">
							        <div class="ibox-title ui-sortable-handle">
							        	<div class="uk-flex uk-flex-middle">
							        	<div class="col-lg-2">
												Album ảnh
											</div>
							                <div class="col-lg-6">
												<input type="text" name="sub_album_title[<?php echo check_isset(slug($value['title'][0])) ?>][]" class="form-control" value="<?php echo check_isset($value['title'][0]) ?>" placeholder="Tiêu đề">
											</div>
											<div class="col-lg-4">
												<div class="uk-flex uk-flex-middle uk-flex-space-between">
													<a onclick="BrowseServerAlbum($(this),'sub_album',<?php echo check_isset($key) ?>);return false;" href="" title="" class="upload-picture"><?php echo translate('cms_lang.tour.tour_upload', $language) ?></a>
									                <div class="ibox-tools">
									                    <a class="collapse-link ui-sortable">
									                        <i class="fa fa-chevron-up"></i>
									                    </a>
									                    <a class="close-link">
									                        <i class="fa fa-times"></i>
									                    </a>
									                </div>
												</div>
											</div>
							        	</div>
							        </div>
							        <div class="ibox-content" style="">
							        	<div class="row">
											<div class="col-lg-12">
												<div class="click-to-upload" <?php echo (isset($value['album']) && is_array($value['album'])&&count($value['album'])) ? 'style="display:none"': '' ?>>
													<div class="icon">
														<a type="button" class="upload-picture" onclick="BrowseServerAlbum($(this),'sub_album',<?php echo check_isset($key) ?>);return false;">
															<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
														</a>
													</div>
													<div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
												</div>
												<div class="upload-list"  >
													<div class="row">
														<ul  class="clearfix sortui data-album">
															<?php if(isset($value['album']) && is_array($value['album'])&&count($value['album'])){
																foreach ($value['album'] as $keyAlbum => $valAlbum) {
															 ?>
															<li class="ui-state-default">
												                <div class="thumb">
												                    <span class="image img-scaledown">
												                        <img src="<?php echo check_isset($valAlbum) ?>" alt="">
												                        <input type="hidden" value="<?php echo check_isset($valAlbum) ?>" name="sub_album[<?php echo check_isset(slug($value['title'][0])) ?>][]">
												                    </span>
												                    <div class="overlay"></div><div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>
												                </div>
												            </li>
												        <?php }} ?>
														</ul>
													</div>
												</div>
											</div>
										</div>
							        </div>
							    </div>
							<?php }} ?>
					    </div>
					</div>
				</div>

				<div class="ibox">
					<div class="ibox-title">
						<h5>Video (link youtube)</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<?php echo form_textarea('video', set_value('video', (isset($product['video'])) ? $product['video'] : ''), 'class="form-control video" placeholder="" autocomplete="off" style="padding: 5px 10px; min-height:100px; resize:vertical"');?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="ibox">
					<div class="ibox-title">
						<h5>Video 2</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<?php echo form_textarea('vr', set_value('vr', (isset($product['vr'])) ? $product['vr'] : ''), 'class="form-control vr" placeholder="" autocomplete="off" style="padding: 5px 10px; min-height:120px; resize:vertical"');?>
								</div>
							</div>
						</div>
					</div>
				</div>

                <div class="ibox">
					<div class="ibox-title">
						<h5>VR (mã nhúng)</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<?php echo form_textarea('vr_3d', set_value('vr_3d', (isset($product['vr_3d'])) ? $product['vr_3d'] : ''), 'class="form-control vr" placeholder="" autocomplete="off" style="padding: 5px 10px; min-height:120px; resize:vertical"');?>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Thông tin chung của Căn hộ bán -->
				<div class="hidden">
					<?php echo view('backend/product/product/common/general_info') ?>

					<!-- Thông tin giá bán buôn của Căn hộ bán -->
					<?php /*echo view('backend/product/product/common/wholesale')*/ ?>

					<!-- Thông tin phần thuộc tính của Căn hộ bán -->
					<?php /*echo view('backend/product/product/common/attribute_product')*/ ?>

					<?php //echo view('backend/product/product/common/related_product') ?>



				</div>

				<?php echo view('backend/product/product/common/brand_product', ['language' => $language]) ?>
				<div class="ibox ibox-seo mb20">
					<div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Tối ưu SEO <small class="text-danger">Thiết lập các thẻ mô tả giúp khách hàng dễ dàng tìm thấy bạn.</small></h5>

							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<div class="edit">
									<a href="#" class="edit-seo">Chỉnh sửa SEO</a>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<?php
									$metaTitle = (isset($_POST['meta_title'])) ? $_POST['meta_title'] : ((isset($product['meta_title']) && $product['meta_title'] != '') ? $product['meta_title'] : 'Bạn chưa nhập tiêu đề SEO cho Căn hộ bán') ;
									$googleLink = (isset($_POST['canonical'])) ? $_POST['canonical'] : ((isset($product['canonical']) && $product['canonical'] != '') ? BASE_URL.$product['canonical'].HTSUFFIX : BASE_URL.'duong-dan-website'.HTSUFFIX) ;
									$metaDescription = (isset($_POST['meta_description'])) ? $_POST['meta_description'] : ((isset($product['meta_description']) && $product['meta_description'] != '') ? $product['meta_description'] : 'Bạn Chưa nhập mô tả SEO cho Căn hộ bán') ;
								?>
								<div class="google">
									<div class="g-title"><?php echo $metaTitle; ?></div>
									<div class="g-link"><?php echo $googleLink ?></div>
									<div class="g-description" id="metaDescription">
										<?php echo $metaDescription; ?>

									</div>
								</div>
							</div>
						</div>

						<div class="seo-group hidden">
							<hr>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label ">
												<span>Tiêu đề SEO</span>
											</label>
											<span style="color:#9fafba;"><span id="titleCount">0</span> trên 70 ký tự</span>
										</div>
										<?php echo form_input('meta_title', htmlspecialchars_decode(html_entity_decode(set_value('meta_title', (isset($product['meta_title'])) ? $product['meta_title'] : ''))), 'class="form-control meta-title" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label ">
												<span>Mô tả SEO</span>
											</label>
											<span style="color:#9fafba;"><span id="descriptionCount">0</span> trên 320 ký tự</span>
										</div>
										<?php echo form_textarea('meta_description', set_value('meta_description', (isset($product['meta_description'])) ? $product['meta_description'] : ''), 'class="form-control meta-description" id="seoDescription" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label ">
												<span>Đường dẫn <b class="text-danger">(*)</b></span>
											</label>
										</div>
										<div class="outer">
											<div class="uk-flex uk-flex-middle">
												<div class="base-url"><?php echo base_url(); ?></div>
												<?php echo form_input('canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', (isset($product['canonical'])) ? $product['canonical'] : ''))), 'class="form-control canonical" placeholder="" autocomplete="off" data-flag="0" ');?>
												<?php echo form_hidden('original_canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', (isset($product['canonical'])) ? $product['canonical'] : ''))), 'class="form-control canonical" placeholder="" autocomplete="off"');?>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<button type="submit" name="create" value="create" class="btn btn-primary block m-b pull-right">Lưu</button>
			</div>
			<div class="col-lg-4">
				<?php if(isset($attribute_list['status']) && is_array($attribute_list['status']) && count($attribute_list['status'])){
					foreach ($attribute_list['status'] as $key => $value) {
				 ?>
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5><?php echo $value['title'] ?></h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php if(isset($value['data']) && is_array($value['data']) && count($value['data'])){ ?>
											<?php
												$param_sell = convert_array_no_select([
													'data' => $value['data'],
													'field' => 'id',
													'value' => 'title',
												]);
												$status = (isset($product['status'][$value['id']])) ? $product['status'][$value['id']] : '';
									 		?>

											<?php echo form_dropdown('status['.$value['id'].']', $param_sell, set_value('status['.$value['id'].']', $status), 'data-module= "'.$module.'" class="form-control m-b select2 '.($method == 'create' ? $class : '').'"');?>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php }} ?>
				<div class="ibox mb20">
					<div class="ibox-title">
						<h5>Lựa chọn danh mục cha </h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row mb10">
									<small class="text-danger">Chọn [Root] Nếu không có danh mục cha</small>
								</div>
								<div class="form-row mb10">
									<?php echo form_dropdown('catalogueid', $dropdown, set_value('catalogueid', (isset($product['catalogueid'])) ? $product['catalogueid'] : ''), 'data-module= "'.$module.'" class="form-control m-b select2 '.($method == 'create' ? $class : '').'"');?>
								</div>
								<script>
									var catalogue = '<?php echo (isset($_POST['catalogue'])) ? json_encode($_POST['catalogue']) : ((isset($product['catalogue']) && $product['catalogue'] != null) ? $product['catalogue'] : '');  ?>';
									var extra = '<?php echo (isset($_POST['extra'])) ? json_encode($_POST['extra']) : ((isset($product['extra']) && $product['extra'] != null) ? $product['extra'] : '');  ?>';

								</script>
								<div class="form-row mt20">
									<label class="control-label text-left">
										<span>Danh mục phụ</span>
									</label>

									<div class="form-row">
										<?php echo form_dropdown('catalogue[]', '', NULL, 'class="form-control selectMultiple" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm..."  style="width: 100%;" data-join="'.$module.'_translate" data-type="1" data-module="'.$module.'_catalogue" data-select="title"'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<style>
					.select2-container--default .select2-selection--multiple .select2-selection__rendered li{
						white-space: break-spaces  !important;
					}
				</style>
				<div class="ibox mb20 hidden">
					<div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5 class="choose-image" style="cursor: pointer;">Ảnh phụ</h5>
							<div class="edit">
								<a onclick="return false;" title="" class="choose-image">Chọn hình</a>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<div class="avatar" style="cursor: pointer;"><img src="<?php echo (isset($_POST['img_sub'])) ? $_POST['img_sub'] : ((isset($product['img_sub']) && $product['img_sub'] != '') ? $product['img_sub'] : 'public/not-found.png') ?>" class="img-thumbnail" alt=""></div>
									<?php echo form_input('img_sub', htmlspecialchars_decode(html_entity_decode(set_value('img_sub', (isset($product['img_sub'])) ? $product['img_sub'] : ''))), 'class="form-control " placeholder="Đường dẫn của ảnh"  autocomplete="off" style="display:none;" '); ?>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="ibox mb20 hidden">
					<div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5 class="choose-image" style="cursor: pointer;">QR Code</h5>
							<div class="edit">
								<a onclick="return false;" title="" class="choose-image">Chọn hình</a>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<div class="avatar" style="cursor: pointer;"><img src="<?php echo (isset($_POST['qr_code'])) ? $_POST['qr_code'] : ((isset($product['qr_code']) && $product['qr_code'] != '') ? $product['qr_code'] : 'public/not-found.png') ?>" class="img-thumbnail" alt=""></div>
									<?php echo form_input('qr_code', htmlspecialchars_decode(html_entity_decode(set_value('qr_code', (isset($product['qr_code'])) ? $product['qr_code'] : ''))), 'class="form-control " placeholder="Đường dẫn của ảnh"  autocomplete="off" style="display:none;" '); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="ibox mb20 hidden">
					<div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5 class="choose-image" style="cursor: pointer;">Link Căn hộ bán kênh tmđt</h5>
							<div class="edit">
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12 mb10">
								<label class="control-label text-left">
									<span>Tiki</span>
								</label>
								<div class="form-row form-description">
									<?php echo form_textarea('link_tiki', htmlspecialchars_decode(html_entity_decode(set_value('link_tiki', (isset($product['link_tiki'])) ? $product['link_tiki'] : ''))), 'class="form-control" id="link_tiki" placeholder="" autocomplete="off" style="height: 80px; resize:vertical"');?>
								</div>
							</div>
							<div class="col-lg-12 mb10">
								<label class="control-label text-left">
									<span>Shopee</span>
								</label>
								<div class="form-row form-description">
									<?php echo form_textarea('link_shopee', htmlspecialchars_decode(html_entity_decode(set_value('link_shopee', (isset($product['link_shopee'])) ? $product['link_shopee'] : ''))), 'class="form-control" id="link_shopee" placeholder="" autocomplete="off" style="height: 80px; resize:vertical"');?>
								</div>
							</div>
							<div class="col-lg-12 mb10">
								<label class="control-label text-left">
									<span>Lazada</span>
								</label>
								<div class="form-row form-description">
									<?php echo form_textarea('link_laz', htmlspecialchars_decode(html_entity_decode(set_value('link_laz', (isset($product['link_laz'])) ? $product['link_laz'] : ''))), 'class="form-control" id="link_laz" placeholder="" autocomplete="off" style="height: 80px; resize:vertical"');?>
								</div>
							</div>
							<div class="col-lg-12 mb10">
								<label class="control-label text-left">
									<span>Sendo</span>
								</label>
								<div class="form-row form-description">
									<?php echo form_textarea('link_sendo', htmlspecialchars_decode(html_entity_decode(set_value('link_sendo', (isset($product['link_sendo'])) ? $product['link_sendo'] : ''))), 'class="form-control" id="link_sendo" placeholder="" autocomplete="off" style="height: 80px; resize:vertical"');?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php /*
				<div class="ibox mb20">
                    <div class="ibox-title">Link nhúng video</div>
                    <div class="ibox-content">
                        <div class="row">
							<div class="col-lg-12">
								<div class="form-row form-description">
									<?php echo form_textarea('video', htmlspecialchars_decode(html_entity_decode(set_value('video', (isset($product['video'])) ? $product['video'] : ''))), 'class="form-control" id="video" placeholder="" autocomplete="off"');?>
								</div>
							</div>
						</div>
                    </div>
                </div>
                */ ?>

				<div class="ibox mb20">
					<div class="ibox-title">
						<h5>Thông tin cơ bản Căn hộ bán </h5>
					</div>
					<div class="ibox-content">
						<div class="row">
                            <div class="col-md-12">
                                <div class="form-row ">
									<label class="control-label text-left">
										<span>Thẻ dự án</span>
									</label>
									
									<?php  
										$tagList = $model->_get_where([
											'select' => 'tb1.id, tb2.title',
											'table' => 'product_tag as tb1',
											'join' => [
												['product_translate as tb2','tb1.id = tb2.objectid','inner']
											],
											'where' => [
												'tb1.deleted_at' => 0,
												'tb2.language' => 'vi',
												'tb2.module' => 'product_tag'
											]
										], TRUE);
										if(isset($tagList) && is_array($tagList) && count($tagList)){
											foreach($tagList as $key => $val){
												$tag[$val['id']] = $val['title'];
											}
										}

										$tagValue = [];
										if(isset($product) && is_array($product) && count($product)){
											$tagValue = json_decode($product['extra'], TRUE);
										}
										
									?>
									
									<div class="form-row">
										<?php echo form_dropdown('extra[]', $tag, $tagValue, 'class="form-control select2" multiple="multiple"  style="width: 100%;"'); ?>
									</div>
								</div>
                            </div>
                            <div class="col-lg-12 mt15">
								<label class="control-label ">
									<span class="label-title">Mã nội bộ </span>
								</label>
								
								<div class="dd-item">
									<?php echo form_input('code', set_value('code', (isset($product['code'])) ? $product['code'] : $productid), 'class="form-control va-uppercase code"  placeholder="" autocomplete="off"');?>
								</div>
							</div>
							<div class="col-lg-12 mt15">
								<label class="control-label ">
									<span class="label-title">Mã <b class="text-danger">(*)</b></span>
								</label>
								<script>
									var productid = '<?php echo isset($product['productid']) ? $product['productid'] : $productid ?>'
								</script>
								<div class="dd-item">
									<?php echo form_input('productid', set_value('productid', (isset($product['productid'])) ? $product['productid'] : $productid), 'class="form-control va-uppercase productid" readonly placeholder="" autocomplete="off"');?>
									<input type="text" name="productid_original" class="form-control va-uppercase productid_original" value="<?php echo (isset($product['productid_original'])) ? $product['productid_original'] : ((isset($product['productid']) ? $product['productid'] : '')) ?>" style="display: none;">

									<input type="checkbox" id="toogle_readonly" name="toogle_readonly">
								</div>
							</div>
							<div class="col-lg-12 mt15">
								<div class="form-row">
									<label class="control-label text-left">
										<span>Giá Sản phẩm <b class="text-danger">(*)</b></span>
									</label>
									<?php echo form_input('price', validate_input(set_value('price', (isset($product['price'])) ? $product['price'] : '')), 'class="form-control price int" placeholder="" id="price" autocomplete="off"'); ?>
								</div>
							</div>
							<div class="col-lg-12 ">
								<div class="form-row mt15">
									<label class="control-label ">
										<span>Phòng tắm</span>
									</label>
									<?php
										$bath = isset($_POST['bath'])? $_POST['bath'] : (isset($product['bath'])? $product['bath'] : 0);

										// print_r($bath); exit;
									 ?>
									<?php echo form_input('bath', set_value('bath', $bath), 'class="form-control m-b bath" placeholder="Phòng tắm "'); ?>
								</div>
								<div class="form-row mt15">
									<label class="control-label ">
										<span>Phòng ngủ</span>
									</label>
									<?php
										$bed = isset($_POST['bed'])? $_POST['bed'] : (isset($product['bed'])? $product['bed'] : 0);

										// print_r($bed); exit;
									 ?>
									<?php echo form_input('bed', set_value('bed', $bed), 'class="form-control m-b bed" placeholder="Phòng ngủ "'); ?>
								</div>
								<div class="form-row mt15">
									<label class="control-label ">
										<span>Diện tích (m2)</span>
									</label>
									<?php
										$area = isset($_POST['area'])? $_POST['area'] : (isset($product['area'])? $product['area'] : 0);

										// print_r($area); exit;
									 ?>
									<?php echo form_input('area', set_value('area', $area), 'class="form-control m-b area" placeholder="Diện tích "'); ?>
								</div>
								<?php if(isset($attribute_list['product']) && is_array($attribute_list['product']) && count($attribute_list['product'])){
									foreach ($attribute_list['product'] as $key => $value) {
								 ?>
									<div class="form-row mt15">
										<label class="control-label ">
											<span><?php echo $value['title'] ?></span>
										</label>
	                                    <?php if(isset($value['data']) && is_array($value['data']) && count($value['data'])){ ?>
											<?php
												$param_sell = convert_array([
													'data' => $value['data'],
													'field' => 'id',
													'value' => 'title',
													'text' => $value['title']
												]);
												$status = (isset($product['info'][$value['id']])) ? $product['info'][$value['id']] : '';
									 		?>

											<?php echo form_dropdown('info['.$value['id'].']', $param_sell, set_value('info['.$value['id'].']', $status), 'data-module= "'.$module.'" class="form-control m-b select2 '.($method == 'create' ? $class : '').'"');?>
										<?php } ?>
									</div>
								<?php }} ?>
								<div class="form-row mt15">
									<label class="control-label ">
										<span>Địa chỉ (Text)</span>
									</label>
									<?php
										$address = (isset($_POST['address'])) ? $_POST['address'] : ((isset($product['address']) && $product['address'] != null) ? $product['address'] : 'Midtown Phu My Hung, Street number 16, Tan Phu ward, District 7, Ho Chi Minh City, Vietnam');

									 ?>
									<?php echo form_textarea('address', set_value('address', $address), 'class="form-control m-b" style="height: 120px; resize:vertical"'); ?>
								</div>
								<div class="form-row mt15">
									<label class="control-label ">
										<span>Địa chỉ (Google Map)</span>
									</label>
									<?php
										$map = (isset($_POST['map'])) ? $_POST['map'] : ((isset($product['map']) && $product['map'] != null) ? $product['map'] : '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1016.0658355119529!2d106.72732849985206!3d10.722596334576496!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xad44dde48f69cb!2sProHOUSE%20Real%20Estate!5e0!3m2!1sen!2s!4v1653539450219!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
									 ?>
									<?php echo form_textarea('map', htmlspecialchars_decode(html_entity_decode(set_value('map', $map))), 'class="form-control m-b" style="height: 120px; resize:vertical"'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ibox mb20">
					<div class="ibox-title">
						<h5>Chọn TAGS</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<?php echo form_input('tags', validate_input(set_value('tags', (isset($tags)) ? $tags : '')), 'class="form-control   tagsinput"  placeholder="" id="tags" autocomplete="off"'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ibox mb20">
					<div class="ibox-title">
						<h5>Hiển thị </h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<div class="text-warning mb15">Quản lý thiết lập hiển thị cho blog này.</div>
									<div class="block clearfix">
										<div class="i-checks mr30" style="width:100%;">
											<span style="color:#000;" class="uk-flex uk-flex-middle">
												<?php echo form_radio('publish', set_value('publish', 1), ((isset($_POST['publish']) && $_POST['publish'] == 1 || (isset($product['publish']) && $product['publish'] == 1)) ? true : (!isset($_POST['publish'])) ? true : false),'class=""  id="publish"  style="margin-top:0;margin-right:10px;" '); ?>
												<label for="publish" style="margin:0;cursor:pointer;">Cho phép hiển thị trên website</label>
											</span>
										</div>
									</div>
									<div class="block clearfix">
										<div class="i-checks" style="width:100%;">
											<span style="color:#000;" class="uk-flex uk-flex-middle">
												<?php echo form_radio('publish', set_value('publish', 0), ((isset($_POST['publish']) && $_POST['publish'] == 0 || (isset($product['publish']) && $product['publish'] == 0)) ? true : false),'class=""   id="no-publish" style="margin-top:0;margin-right:10px;" '); ?>

												<label for="no-publish" style="margin:0;cursor:pointer;">Không Cho phép hiển thị trên website</label>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>


<div id="product_add_brand" class="modal fade">
      <div class="modal-dialog">
           <div class="modal-content">
                <div class="modal-header">
                    <div class="uk-flex uk-flex-space-between uk-flex-middle" >
                       <h4 class="modal-title">Tạo <?php echo BRAND ?> mới cho Căn hộ bán</h4>
                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form method="post" id="insert_form">
                    	<div class="uk-flex uk-flex-middle ">
                    		<div class="brand-avatar">
                    			<div class="form-row">
									<div class="avatar" style="cursor: pointer;"><img src="public/not-found.png" class="img-thumbnail" alt=""></div>
									<?php echo form_input('brand_img', htmlspecialchars_decode(html_entity_decode(set_value('image'))), 'class="form-control " placeholder="Đường dẫn của ảnh"  id="brand_img"  autocomplete="off" style="display:none;" ');?>
								</div>
                    		</div>
                    		<div class="brand-content">
                    			<label>Tiêu đề <?php echo BRAND ?></label>
		                        <input type="text" name="brand_title" id="brand_title" class="form-control" />
		                        <input type="hidden" name="brand_canonical" id="brand_canonical" class="form-control" />
		                        <br />
		                        <label>Nhãn hiệu</label>
		                        <input type="text" name="keyword" id="keyword" class="form-control" />
		                        <br />
		                        <input type="submit" name="insert" id="insert" value="Thêm mới" class="btn btn-success " />
                    		</div>
                    	</div>
                    </form>
                </div>
           </div>
      </div>
 </div>

 <div id="product_add_attribute" class="modal inmodal fade">
      <div class="modal-dialog modal-xl">
           <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Thêm mới thuộc tính</h4>
					<small class="font-bold text-danger">Cập nhật đầy đủ thông tin người dùng giúp việc quản lý dễ dàng hơn</small>
				</div>
				<div class="modal-body p-md">
					<form method="post" id="attribute_form">
						<div class="row">
							<div class="box-body error hidden">
								<div class="alert alert-danger"></div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
							<label class="col-md-4">
								<div class=" control-label">
									<span class="m-r">Tên thuộc tính <b class="text-danger">(*)</b></span>
								</div>
							</label>
							<div class="col-md-8">
								<input type="text" name="title" value="" id="modal_attribute_title" class="form-control " placeholder="" autocomplete="off">
							</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-4">
									<div class=" control-label">
										<span class="m-r">Nhóm thuộc tính <b class="text-danger">(*)</b></span>
									</div>
								</label>
								<div class="col-md-8">
									<select name="catalogueid_modal" class="form-control input-sm perpage  catalogueid_modal select2" style="width:100%" >
										<?php
											if(isset($attribute_catalogue) && is_array($attribute_catalogue) && count($attribute_catalogue)){
												array_unshift($attribute_catalogue,[
													'title' => '---Chọn nhóm thuộc tính---',
													'objectid' => 'root'
												]);
												foreach ($attribute_catalogue as $key => $value) {
										 ?>
											<option value="<?php echo $value['objectid'] ?>"><?php echo $value['title'] ?></option>
										<?php }} ?>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Thêm mới</button>
						</div>
					</form>
				</div>


			</div>
      </div>
 </div>



<!-- ==================================================== Modal ============================================================ -->

<div class="modal fade modal_version" id="openModalDetail">
  	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-header ">
	      		<div class="uk-flex uk-flex-middle uk-flex-space-between">
		        	<h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa chi tiết phiên bản Căn hộ bán</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
	      		</div>
	      	</div>
	      	<form method="post" action="" id="render_input_version" class="submit_version">
		      	<div class="modal-body">
		      		<div class="row mb15">
		      			<div class="col-lg-6">
		      				<div class="form-row">
		      					<label class="control-label ">
									<span>BarCode</span>
								</label>
								<input type="text" name="barcode_version[]" value="" class="form-control modal_barcode_version" autocomplete="off">
		      				</div>
		      			</div>
		      			<div class="col-lg-6">
		      				<div class="form-row">
		      					<label class="control-label ">
									<span>Model</span>
								</label>
								<input type="text" name="model_version[]" value="" class="form-control modal_model_version" autocomplete="off">
		      				</div>
		      			</div>
		      		</div>
		      		<div class="row">
		      			<div class="col-lg-12">
		      				<div class="form-row">

								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<label class="control-label ">
										<span>Album Căn hộ bán</span>
									</label>
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="edit">
											<a onclick="BrowseServerAlbumModal($(this), '<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>');return false;" href="" title="" class="upload-picture">Chọn hình</a>
										</div>
									</div>
								</div>

								<div class="click-to-upload" >
									<div class="icon">
										<a type="button" class="upload-picture" onclick="BrowseServerAlbumModal($(this), '<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>');return false;">
											<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
										</a>
									</div>
									<div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
								</div>
								<div class="upload-list"  style="padding:5px; display: none;">
									<div class="row">
										<ul class="clearfix sortui sort-modal">

										</ul>
									</div>
								</div>
								<div class="pull-right"><button type="submit" class="btn btn-primary block m-b pull-right mt30 ">Lưu</button></div>
								<?php if(isset($method) && $method == 'update'){ ?>
									<div class="pull-right mr10">
										<button type="button" class="btn btn-open-modal  btn-success block m-b pull-right mt30" data-toggle="modal" onclick="detail_version($(this))" data-title="" data-canonical="" data-target="#openDetailProduct" >Phiên bản nâng cao</button>
									</div>
								<?php } ?>
		      				</div>
		      			</div>
		      		</div>
		      	</div>
	      	</form>
    	</div>
  	</div>
</div>
