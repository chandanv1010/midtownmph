<?php  
    helper('form');
    helper('nestedset');
    $baseController = new App\Controllers\BaseController();
    $language = $baseController->currentLanguage();
    $AutoloadModel = new App\Models\AutoloadModel();
    $languageList = get_full_language(['currentLanguage' => $language]);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div>
                <h2><?php echo translate('cms_lang.panel.ManageDisplay', $language) ?></h2>
                <ol class="breadcrumb mb20">
                    <li>
                        <a href=""><?php echo translate('cms_lang.panel.home_panel', $language) ?></a>
                    </li>
                    <li class="active"><strong><?php echo translate('cms_lang.panel.ManageDisplay', $language) ?></strong></li>
                </ol>
                <div class="uk-flex uk-flex-middle" >
                    <?php if(isset($languageList) && is_array($languageList) && count($languageList)){ ?>
                        <?php foreach($languageList as $key => $val){ ?>
                            <a href="<?php echo base_url('backend/panel/panel/index/'.$val['canonical'].'') ?>" class="mr10" title="<?php echo $val['canonical'] ?>">
                                <span class="icon-flag img-cover"><img src="<?php echo getthumb($val['image']); ?>" alt=""></span>
                            </a>
                    <?php }} ?>
                </div>
            </div>
            <div class="uk-button">
                <a href="<?php echo base_url('backend/panel/panel/create'.'/'.$languageSelect.'') ?>" class="btn btn-success "><i class="fa fa-plus"></i> <?php echo translate('cms_lang.panel.AddaNewlook', $language) ?></a>
            </div>
        </div>
    </div>
</div>
<form method="post" action="" class="form-horizontal box">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="box-body">
        </div><!-- /.box-body -->
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="panel-head">
                    <h2 class="panel-title"><?php echo translate('cms_lang.panel.ListDisplay', $language) ?></h2>
                    <div class="panel-description">
                        <p>+ <?php echo translate('cms_lang.panel.Note7', $language) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th style="width:80px;" class="text-center"><?php echo translate('cms_lang.panel.Keyword4', $language) ?></th>
                                <th><?php echo translate('cms_lang.panel.Title3', $language) ?></th>
                                <th class="text-center"><?php echo translate('cms_lang.panel.Location1', $language) ?></th>
                                <th style="width:100px;" class="text-center"><?php echo translate('cms_lang.panel.Manipulation2', $language) ?></th>
                            </tr>
                        </thead>
                        <tbody id="ajax-content">
                            <?php if(isset($panel) && is_array($panel) && count($panel)){ 
                                foreach ($panel as $key => $value) {
                                    if(isset($locate[$value['locate']]) && $locate[$value['locate']] != ''){
                                        $location = $locate[$value['locate']];
                                    }
                            ?>
                                <tr class="gradeX" id="<?php echo $value['keyword'] ?>">
                                    <td class="text-center"><?php echo $value['keyword'] ?></td>
                                    <td><a class="" href="<?php echo base_url('backend/panel/panel/update/'.$value['id']) ?>" title=""><?php echo $value['title'] ?></a></td>
                                    <td class="text-center"><?php echo $location ?></td>
                                    <td class="text-center" >
                                        <a type="button" href="<?php echo base_url('backend/panel/panel/update/'.$value['id'].'/'.$languageSelect.'') ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                        <a type="button" href="<?php echo base_url('backend/panel/panel/delete/'.$value['id']).'/'.$languageSelect.'' ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

