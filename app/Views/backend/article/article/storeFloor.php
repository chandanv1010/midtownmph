<?php  
    helper('form', 'data');
    $baseController = new App\Controllers\BaseController();
    $language = $baseController->currentLanguage();
    $get_catalogue = check_type_canonical($language);
    if($get_catalogue['content'] == 'silo'){
    	$class = 'get_catalogue';
    }else{
    	$class = '';
    }
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
							<h5><?php echo translate('cms_lang.post.post_info', $language) ?> <small class="text-danger"><?php echo translate('cms_lang.post.post_sub_info', $language) ?></small></h5>
							<div class="ibox-tools">
								<button type="submit" name="create" value="create" class="btn btn-primary block full-width m-b"><?php echo translate('cms_lang.post.post_save', $language) ?></button>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row mb15">
							<div class="col-lg-12">
								<div class="form-row">
									<label class="control-label text-left">
										<span><?php echo translate('cms_lang.post.post_create_title', $language) ?> <b class="text-danger">(*)</b></span>
									</label>
									<?php echo form_input('title', validate_input(set_value('title', (isset($article['title'])) ? $article['title'] : '')), 'class="form-control '.(($method == 'create') ? 'title' : '').'" placeholder="" id="title" autocomplete="off"'); ?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-lg-12">
								<div class="form-row form-description">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<label class="control-label text-left">
											<span><?php echo translate('cms_lang.post.post_description', $language) ?></span>
										</label>
										<a href="" title="" data-target="description" class="uploadMultiImage"><?php echo translate('cms_lang.post.post_upload', $language) ?></a>
									</div>
									<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description', (isset($article['description'])) ? $article['description'] : ''))), 'class="form-control ck-editor" id="description" placeholder="" autocomplete="off"');?>

								</div>
							</div>
						</div>

						<div class="row mb15">
							<div class="col-lg-12">
								<div class="form-row">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<label class="control-label text-left">
											<span><?php echo translate('cms_lang.post.post_content', $language) ?></span>
										</label>
										<a href="" title="" data-target="content" class="uploadMultiImage"><?php echo translate('cms_lang.post.post_upload', $language) ?></a>
									</div>
									<?php echo form_textarea('content', htmlspecialchars_decode(html_entity_decode(set_value('content', (isset($article['content'])) ? $article['content'] : ''))), 'class="form-control ck-editor" id="content" placeholder="" autocomplete="off"');?>
								</div>
							</div>
						</div>
					</div>
				</div>
                <?php if($method == 'create'){ ?>
                    <div class="ibox mb20 album">
                        <div class="ibox-title">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <h5><?php echo translate('cms_lang.post.post_img', $language) ?> </h5>
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <div class="edit mr15">
                                        <a onclick="BrowseServerExtractAlbum($(this), 'extract_album', albumCounter , 'Images');return false;" href="" title="" class="upload-picture"><?php echo translate('cms_lang.post.post_upload', $language) ?></a>
                                    </div>
                                    <div class="add">
                                        <a onclick="BrowseServerAddAlbum($(this), 'extract_album', albumCounter , 'Images');return false;" href="" title="" class="add-album">Thêm Album Mới</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row extract_album">
                                <div class="col-lg-12">
                                    <input type="text" class="mb10 form-control" name="extract_album[0][name]" placeholder="Nhập tên album">
                                    <div class="click-to-upload" >
                                        <div class="icon">
                                            <a type="button" class="upload-picture" onclick="BrowseServerExtractAlbum($(this), 'extract_album', 0 , 'Images'  );return false;">
                                                <svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
                                            </a>
                                        </div>
                                        <div class="small-text">
                                            <?php echo translate('cms_lang.post.post_img_content', $language) ?>
                                        </div>
                                    </div>
                                    <div class="upload-list" style="padding:5px;">
                                        <div class="row">
                                            <ul id="sortable" class="clearfix data-album sortui">
                                            </ul>
                                        </div>
                                    </div>
                                    <textarea type="text" class="form-control" name="extract_album[0][description]" placeholder="Nhập mô tả...."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <? }else{ ?>
                    <?php
                        if(isset($_POST['extract_album'])){
                            $extract_album = $_POST['extract_album'];
                        }else if(isset($article)){
                            $extract_album = json_decode($article['extract_album'], TRUE);
                        }
                    ?>
                    <?php if($extract_album){ ?>
                        <?php foreach($extract_album as $key => $val){ ?>
                            <div class="ibox mb20 album">
                                <div class="ibox-title">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        <h5><?php echo translate('cms_lang.post.post_img', $language) ?> </h5>
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                            <div class="edit mr15">
                                                <a onclick="BrowseServerExtractAlbum($(this), 'extract_album',<?php echo $key  ?>, 'Images');return false;" href="" title="" class="upload-picture"><?php echo translate('cms_lang.post.post_upload', $language) ?></a>
                                            </div>
                                            <div class="add">
                                                <a onclick="BrowseServerAddAlbum($(this), 'extract_album', <?php echo $key  ?> , 'Images');return false;" href="" title="" class="add-album"><?php echo translate('cms_lang.post.post_add_album', $language) ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row extract_album">
                                        <div class="col-lg-12">
                                            <input type="text" class="mb10 form-control" name="extract_album[<?php echo $key ?>][name]" value="<?php echo $val['name'] ?>" placeholder="Nhập tên album">
                                            <div class="click-to-upload" <?php echo (isset($val))?'style="display:none"':'' ?>>
                                                <div class="icon">
                                                    <a type="button" class="upload-picture" onclick="BrowseServerExtractAlbum($(this), 'extract_album',<?php  echo $key ?> , 'Images');return false;">
                                                        <svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
                                                    </a>
                                                </div>
                                                <div class="small-text">
                                                    <?php echo translate('cms_lang.post.post_img_content', $language) ?>
                                                </div>
                                            </div> 
                                            <div class="upload-list" <?php echo (isset($val))?'':'style="display:none"' ?> style="padding:5px;">
                                                <div class="row">
                                                    <ul id="sortable-<?php echo $key ?>" class="clearfix data-album sortui sort-drag">
                                                        <?php foreach($val['list'] as $k => $v){ ?>
                                                            <li class="ui-state-default">
                                                                <div class="thumb">
                                                                    <span class="image img-scaledown">
                                                                        <img src="<?php echo $v['image']; ?>" alt="" /> 
                                                                        <input type="hidden" value="<?php echo $v['image']; ?>" name="extract_album[<?php echo $key ?>][list][image][]" />
                                                                        <input type="text" class="name" value="<?php echo $v['name']; ?>" name="extract_album[<?php echo $key ?>][list][name][]">
                                                                    </span>
                                                                    <div class="overlay"></div>
                                                                    <div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>
                                                                </div>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <textarea name="extract_album[<?php echo htmlspecialchars($key); ?>][description]" id="extract_album_description_<?php echo htmlspecialchars($key); ?>" placeholder="Nhập mô tả..." rows="4" cols="50"><?php echo htmlspecialchars($val['description']); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>                        
                <? } ?>
                <style>
                    textarea {
                        width: 100%;
                        height: 100px !important;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                    }
                    .mb10{
                        margin-bottom: 10px;
                    }
                    .mt20{
                        margin-top: 20px;
                    }
                </style>
				<div class="ibox ibox-seo mb20">
					<div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5><?php echo translate('cms_lang.post.post_seo', $language) ?></h5>
							
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<div class="edit">
									<a href="#" class="edit-seo"><?php echo translate('cms_lang.post.post_seo_edit', $language) ?></a>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<?php  
									$metaTitle = (isset($_POST['meta_title'])) ? $_POST['meta_title'] : ((isset($article['meta_title']) && $article['meta_title'] != '') ? $article['meta_title'] : translate('cms_lang.post.post_seo_validate_title', $language)) ;
									$googleLink = (isset($_POST['canonical'])) ? $_POST['canonical'] : ((isset($article['canonical']) && $article['canonical'] != '') ? BASE_URL.$article['canonical'].HTSUFFIX : BASE_URL.'duong-dan-website'.HTSUFFIX) ;
									$metaDescription = (isset($_POST['meta_description'])) ? $_POST['meta_description'] : ((isset($article['meta_description']) && $article['meta_description'] != '') ? $article['meta_description'] : translate('cms_lang.post.post_seo_validate_description', $language)) ;
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
												<span><?php echo translate('cms_lang.post.post_seo_title', $language) ?></span>
											</label>
											<span style="color:#9fafba;"><span id="titleCount">0</span> <?php echo translate('cms_lang.post.post_seo_number_title', $language) ?></span>
										</div>
										<?php echo form_input('meta_title', htmlspecialchars_decode(html_entity_decode(set_value('meta_title', (isset($article['meta_title'])) ? $article['meta_title'] : ''))), 'class="form-control meta-title" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label ">
												<span><?php echo translate('cms_lang.post.post_seo_description', $language) ?></span>
											</label>
											<span style="color:#9fafba;"><span id="descriptionCount">0</span> <?php echo translate('cms_lang.post.post_seo_number_description', $language) ?></span>
										</div>
										<?php echo form_textarea('meta_description', set_value('meta_description', (isset($article['meta_description'])) ? $article['meta_description'] : ''), 'class="form-control meta-description" id="seoDescription" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label ">
												<span><?php echo translate('cms_lang.post.post_seo_canonical', $language) ?><b class="text-danger">(*)</b></span>
											</label>
										</div>
										<div class="outer">
											<div class="uk-flex uk-flex-middle">
												<div class="base-url"><?php echo base_url(); ?></div>
												<?php echo form_input('canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', (isset($article['canonical'])) ? $article['canonical'] : ''))), 'class="form-control canonical" placeholder="" autocomplete="off" data-flag="0" ');?>
												<?php echo form_hidden('original_canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', (isset($article['canonical'])) ? $article['canonical'] : ''))), 'class="form-control canonical" placeholder="" autocomplete="off"');?>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					
					</div>
					
				</div>
                
				<button type="submit" name="create" value="create" class="btn btn-primary block m-b pull-right"><?php echo translate('cms_lang.post.post_save', $language) ?></button>
			</div>
			<div class="col-lg-4">
				<div class="ibox mb20">
					<div class="ibox-title">
						<h5><?php echo translate('cms_lang.post.post_select_catalogue', $language) ?></h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row mb10">
									<small class="text-danger"><?php echo translate('cms_lang.post.post_select_warning', $language) ?></small>
								</div>
								<div class="form-row">
									<?php echo form_dropdown('catalogueid', $dropdown, set_value('catalogueid', (isset($article['catalogueid'])) ? $article['catalogueid'] : ''), 'class="form-control m-b select2 '.($method == 'create' ? $class : '').'" data-module="'.$module.'"');?>
								</div>

								<script>
									var catalogue = '<?php echo (isset($_POST['catalogue'])) ? json_encode($_POST['catalogue']) : ((isset($article['catalogue']) && $article['catalogue'] != null) ? $article['catalogue'] : '');  ?>';	
								</script>
								<div class="form-row mt20">
										<label class="control-label text-left">
											<span><?php echo translate('cms_lang.post.post_sub_cat', $language) ?></span>
										</label>
										
										<div class="form-row">
											<?php echo form_dropdown('catalogue[]', '', NULL, 'class="form-control selectMultiple" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm..."  style="width: 100%;" data-join="'.$module.'_translate" data-module="'.$module.'_catalogue" data-select="title"'); ?>
										</div>
									</div>
							</div>
						</div>
					</div>
				</div>

				<div class="ibox mb20">
					<div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h5 class="choose-image" style="cursor: pointer;"><?php echo translate('cms_lang.post.post_avatar', $language) ?> </h5>
							<div class="edit">
								<a onclick="return false;" title="" class="choose-image">Chọn hình</a>
							</div>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<div class="avatar" style="cursor: pointer;"><img src="<?php echo (isset($_POST['image'])) ? $_POST['image'] : ((isset($article['image']) && $article['image'] != '') ? $article['image'] : 'public/not-found.png') ?>" class="img-thumbnail" alt=""></div>
									<?php echo form_input('image', htmlspecialchars_decode(html_entity_decode(set_value('image', (isset($article['image'])) ? $article['image'] : ''))), 'class="form-control " placeholder="Đường dẫn của ảnh"  id="imageTxt"  autocomplete="off" style="display:none;" ');?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="ibox mb20">
					<div class="ibox-title">
						<h5>File tài liệu</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<?php echo form_input('files', set_value('files', (isset($article['files'])) ? $article['files'] : ''), 'class="form-control input-text" placeholder="Click to upload file" id="" autocomplete="off" onclick="BrowseServer(this, files)"'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="ibox mb20">
					<div class="ibox-title">
						<h5>Chọn TAGS cho Bài viết </h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<?php echo form_input('tags', validate_input(set_value('tags', (isset($tags)) ? $tags : '')), 'class="form-control tags tagsinput" placeholder="" id="tags" autocomplete="off"'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				
				
				<div class="ibox mb20">
					<div class="ibox-title">
						<h5><?php echo translate('cms_lang.post.post_display', $language) ?></h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-row">
									<div class="text-warning mb15"><?php echo translate('cms_lang.post.post_display_des', $language) ?></div>
									<div class="block clearfix">
										<div class="i-checks mr30" style="width:100%;">
											<span style="color:#000;" class="uk-flex uk-flex-middle"> 
												<?php echo form_radio('publish', set_value('publish', 1), ((isset($_POST['publish']) && $_POST['publish'] == 1 || (isset($article['publish']) && $article['publish'] == 1)) ? true : (!isset($_POST['publish'])) ? true : false),'class=""  id="publish"  style="margin-top:0;margin-right:10px;" '); ?>
												<label for="publish" style="margin:0;cursor:pointer;"><?php echo translate('cms_lang.post.post_display_1', $language) ?></label>
											</span>
										</div>
									</div>
									<div class="block clearfix">
										<div class="i-checks" style="width:100%;">
											<span style="color:#000;" class="uk-flex uk-flex-middle"> 
												<?php echo form_radio('publish', set_value('publish', 0), ((isset($_POST['publish']) && $_POST['publish'] == 0 || (isset($article['publish']) && $article['publish'] == 0)) ? true : false),'class=""   id="no-publish" style="margin-top:0;margin-right:10px;" '); ?>
												
												<label for="no-publish" style="margin:0;cursor:pointer;"><?php echo translate('cms_lang.post.post_display_0', $language) ?></label>
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
