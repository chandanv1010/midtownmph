<?php  
    helper('form');
    helper('mydata');
    $baseController = new App\Controllers\BaseController();
    $language = $baseController->currentLanguage();
    $AutoloadModel = new App\Models\AutoloadModel();
    $languageList = get_full_language(['currentLanguage' => $language]);
    $update = '';
    if(isset($website_panel) && is_array($website_panel) && count($website_panel)){
        $update = 'readonly';
    }
?>
<form method="post" action="" class="form-horizontal box">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="box-body">
                <?php echo  (!empty($validate) && isset($validate)) ? '<div class="alert alert-danger">'.$validate.'</div>'  : '' ?>
            </div><!-- /.box-body -->
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="panel-head">
                    <h2 class="panel-title"><?php echo translate('cms_lang.panel.LDP', $language) ?></h2>
                    <div class="panel-description">
                        <p>+ <?php echo translate('cms_lang.panel.Note8', $language) ?></p>
                        <p>+ <?php echo translate('cms_lang.panel.Note9', $language) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="ibox-content uk-clearfix">
                    <div class="row mb15">
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label class="control-label text-left">
                                    <span><?php echo translate('cms_lang.panel.Title4', $language) ?> <b class="text-danger">(Chọn ô sẽ lấy bài viết đầu tiên làm tiêu đề)</b></span>
                                </label>
                                <?php echo form_input('title', validate_input(set_value('title', (isset($website_panel['title'])) ? $website_panel['title'] : '')), 'class="form-control title" style="padding-right: 40px" placeholder="" id="title" autocomplete="off"'); ?>
                                <input type="checkbox" name="default" class="checkbox-panel-default"  value="1" data-language="<?php echo $language ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label class="control-label text-left">
                                    <span><?php echo translate('cms_lang.panel.Keyword5', $language) ?> <b class="text-danger">(*)</b></span>
                                </label>
                                <?php echo form_input('keyword', validate_input(set_value('keyword', (isset($website_panel['keyword'])) ? $website_panel['keyword'] : '')), 'class="form-control keyword" '.$update.' placeholder="" id="keyword" autocomplete="off"'); ?>
                                <?php echo form_hidden('keyword_original', validate_input(set_value('keyword', (isset($website_panel['keyword'])) ? $website_panel['keyword'] : '')), 'class="form-control keyword_original" '.$update.' placeholder="" id="keyword_original" autocomplete="off"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label class="control-label text-left">
                                    <span>Đường dẫn</span>
                                </label>
                                <?php echo form_input('canonical', validate_input(set_value('canonical', (isset($website_panel['canonical'])) ? $website_panel['canonical'] : '')), 'class="form-control url-panel" placeholder="" autocomplete="off"'); ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-row">
                                <label class="control-label text-left uk-flex uk-flex-space-between uk-flex-middle">
                                    <span>Banner ảnh</span>
                                    <a href="" title="" data-target="image" class="uploadIcon">Upload hình ảnh</a>
                                </label>
                                <?php echo form_input('image', set_value('image', (isset($website_panel['image'])) ? $website_panel['image'] : ''), 'class="form-control icon-display" onclick="" placeholder="" autocomplete="off" data-flag="0" ');?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row form-description">
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <label class="control-label text-left">
                                        <span>Mô tả</span>
                                    </label>
                                    <a href="" title="" data-target="description" class="uploadMultiImage">Upload Image</a>
                                </div>
                                <?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description', (isset($website_panel['description'])) ? $website_panel['description'] : ''))), 'class="form-control ck-editor" id="description" placeholder="" autocomplete="off"');?>

                            </div>
                        </div>
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <label class="control-label text-left">
                                    <span><?php echo translate('cms_lang.panel.CAL', $language) ?> </span>
                                </label>
                               <?php echo form_dropdown('locate', $locate, set_value('locate', (isset($website_panel['locate'])) ? $website_panel['locate'] : ''), 'data-module= "'.$module.'" class="form-control m-b select2 "');?>
                            </div>   
                        </div>   
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <label class="control-label text-left">
                                    <span><?php echo translate('cms_lang.panel.STM', $language) ?> </span>
                                </label>
                               <?php echo form_dropdown('module', $dropdown, set_value('module', (isset($website_panel['module'])) ? $website_panel['module'] : ''), 'data-module= "'.$module.'" class="form-control m-b select2 select2_panel"');?>
                            </div>   
                        </div>   
                    </div>
                    <div class="row  select_type_data">
                        <?php if(isset($website_panel) && $website_panel['type_data'] != 'null' && $website_panel['type_data'] != ''){ 
                            $website_panel['type_data'] = json_decode($website_panel['type_data']);
                            ?>
                            <div class="col-lg-12 mb15">
                                <div class="form-row">
                                    <label class="control-label text-left">
                                        <span>Chọn kiểu lấy dữ liệu</span>
                                        <span class="text-danger">(Kiểu dự liệu mặc định là lấy các danh mục đã chọn.)</span>

                                    </label>
                                    <div>
                                       <div class="i-checks clearfix">
                                            <label class="uk-flex uk-flex-middle">
                                                <input style="margin-top:0;margin-right:10px" name="select_type[]" type="checkbox" <?php echo (($website_panel['type_data'] != '')  && in_array('only_post', $website_panel['type_data'])) ? 'checked' : '' ?> value="only_post">
                                                <span>Lấy bài viết</span>
                                            </label>
                                        </div>
                                        <div class="i-checks clearfix">
                                            <label class="uk-flex uk-flex-middle">
                                                <input style="margin-top:0;margin-right:10px" name="select_type[]" type="checkbox" <?php echo (($website_panel['type_data'] != '')  && in_array('only_cat', $website_panel['type_data'])) ? 'checked' : '' ?> value="only_cat">
                                                <span>Lấy danh mục</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>   
                            </div>   
                        <?php } ?>
                    </div>
                    <script>
                        var catalogue = '<?php echo (isset($_POST['catalogue'])) ? json_encode($_POST['catalogue']) : ((isset($website_panel['catalogue']) && $website_panel['catalogue'] != null) ? $website_panel['catalogue'] : '');  ?>'; 
                    </script>
                    <div class="row mb15 ">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <label class="control-label text-left">
                                    <span><?php echo translate('cms_lang.panel.CTRO', $language) ?></span>
                                </label>
                                <?php echo form_dropdown('catalogue[]', '', NULL, 'class="form-control selectMultiplePanel" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm..."  style="width: 100%;" data-join="" data-lang="'.$languageSelect.'" data-module="" data-select="title"'); ?>
                            </div>   
                        </div>   
                    </div>
                    <div class="pull-right"><button type="submit" class="btn btn-primary block m-b pull-right mt30 "><?php echo translate('cms_lang.panel.Save2', $language) ?></button></div>
                </div>
            </div>
        </div>
    </div>
</form>