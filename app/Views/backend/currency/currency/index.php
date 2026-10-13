<?php  
    helper('form');
    $baseController = new App\Controllers\BaseController();
    $language = $baseController->currentLanguage();
    $languageList = get_list_language(['currentLanguage' => $language]);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2><?php echo translate('cms_lang.currency.currency_title', $language) ?></h2>
        <ol class="breadcrumb" style="margin-bottom:10px;">
            <li>
                <a href="<?php echo base_url('backend/dashboard/dashboard/index') ?>"><?php echo translate('cms_lang.currency.currency_home', $language) ?></a>
            </li>
            <li class="active"><strong><?php echo translate('cms_lang.currency.currency_title', $language) ?></strong></li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo translate('cms_lang.currency.currency_title', $language) ?> </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                         <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#" class="delete-all" data-module="<?php echo $module; ?>"><?php echo translate('cms_lang.currency.currency_deleteall', $language) ?></a>
                            </li>
                            <li><a href="#" class="status" data-value="0" data-field="publish" data-module="<?php echo $module; ?>" title="Cập nhật trạng thái bài viết"><?php echo translate('cms_lang.currency.currency_deactive', $language) ?></a>
                            </li> 
                            <li><a href="#" class="status" data-value="1" data-field="publish" data-module="<?php echo $module; ?>" data-title="Cập nhật trạng thái bài viết"><?php echo translate('cms_lang.currency.currency_active', $language) ?></a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form action="" method="">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between mb20">
                            <div class="perpage">
                                <div class="uk-flex uk-flex-middle mb10">
                                    <select name="perpage" class="form-control input-sm perpage filter mr10">
                                        <option value="20">20 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="30">30 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="40">40 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="50">50 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="60">60 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="70">70 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="80">80 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="90">90 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                        <option value="100">100 <?php echo translate('cms_lang.currency.currency_record', $language) ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="toolbox">
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <div class="uk-search uk-flex uk-flex-middle mr10 ml10">
                                        <div class="input-group">
                                            <input type="text" name="keyword" value="<?php echo (isset($_GET['keyword'])) ? $_GET['keyword'] : ''; ?>" placeholder="<?php echo translate('cms_lang.currency.currency_placeholder', $language) ?>" class="form-control"> 
                                            <span class="input-group-btn"> 
                                                <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm"><?php echo translate('cms_lang.currency.currency_search', $language) ?>
                                            </button> 
                                            </span>
                                        </div>
                                    </div>
                                    <div class="uk-button">
                                        <a href="<?php echo base_url('backend/currency/currency/create') ?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i><?php echo translate('cms_lang.currency.currency_add', $language) ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 35px;">
                                <input type="checkbox" id="checkbox-all">
                                <label for="check-all" class="labelCheckAll"></label>
                            </th>
                            <th ><?php echo translate('cms_lang.currency.currency_table_title', $language) ?></th>
                             <?php if(isset($languageList) && is_array($languageList) && count($languageList)){ ?>
                            <?php foreach($languageList as $key => $val){ ?>
                            <th class="text-center" style="width: 100px;">
                                <span class="icon-flag img-cover"><img src="<?php echo getthumb($val['image']); ?>" alt=""></span>
                            </th>
                            <?php }} ?>
                            <th class="text-center" style="width: 120px;">Default</th>
                            <th class="text-center" style="width: 120px;"><?php echo translate('cms_lang.currency.currency_location', $language) ?></th>
                            <th style="width:150px;"><?php echo translate('cms_lang.currency.currency_creator', $language) ?></th>
                            <th style="width:150px;" class="text-center"><?php echo translate('cms_lang.currency.currency_created_at', $language) ?></th>
                            <th class="text-center" style="width:88px;"><?php echo translate('cms_lang.currency.currency_status', $language) ?></th>
                            <th class="text-center" style="width:103px;"><?php echo translate('cms_lang.currency.currency_action', $language) ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // print_r($currencyList); exit;
                             ?>

                            <?php if(isset($currencyList) && is_array($currencyList) && count($currencyList)){ ?>
                            <?php foreach($currencyList as $key => $val){ ?>
                            <?php  
                                $status = ($val['publish'] == 1) ? '<span class="text-success">Active</span>'  : '<span class="text-danger">Deactive</span>';
                            ?>

                            <tr id="post-<?php echo $val['id']; ?>" data-id="<?php echo $val['id']; ?>">
                                <td>
                                    <input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
                                    <div for="" class="label-checkboxitem"></div>
                                </td>
                                <td> 
                                    <div class="uk-flex uk-flex-middle">
                                        <div class="main-info">
                                            <div class="title"><a class="maintitle" href="<?php echo site_url('backend/currency/currency/update/'.$val['id']); ?>" title=""><?php echo $val['currency_title']; ?></a></div>
                                        </div>
                                    </div>
                                </td>
                                <?php if(isset($languageList) && is_array($languageList) && count($languageList)){ ?>
                                <?php foreach($languageList as $keyLanguage => $valLanguage){ ?>
                                <td class="text-center "><a class="text-small <?php echo ($val[$valLanguage['canonical'].'_detect'] > 0 ) ? 'text-success' : 'text-danger' ?> " href="<?php echo base_url('backend/translate/translate/translateObject/'.$val['id'].'/'.$module.'/'.$valLanguage['canonical'].'') ?>">
                                    <?php echo ($val[$valLanguage['canonical'].'_detect'] > 0 ) ? translate('cms_lang.currency.currency_translated', $language) : translate('cms_lang.currency.currency_not_translated', $language) ?>

                                </a></td>
                                <?php }} ?>
                                <td class="text-center">

                                <?php 
                                    // print_r($currencyList); exit;
                                 ?>
                                    <?php 
                                        $checked_1 = (isset($val['default']) && $val['default'] == 1) ? 'checked' : '';
                                    ?>
                                    <div class="i-checks mb0" style="width:100%;"><span style="color:#000;"> <input <?php echo $checked_1 ?> class="popup_gender_1 js_default" data-id="<?php echo $val['id'] ?>" type="radio" value="1"  name="default"> <i></i></span></div>
                                </td>

                                <td class="text-center text-primary">
                                    <?php echo form_input('order['.$val['id'].']', $val['order'], 'data-module="'.$module.'" data-id="'.$val['id'].'"  class="form-control sort-order" placeholder="Vị trí" style="text-align:right;"');?>

                                </td>
                                <td class="text-primary"><?php echo $val['creator']; ?></td>
                                <td class="text-center text-primary"><?php echo gettime($val['created_at'],'Y-d-m') ?></td>
                                <td class="text-center td-status" data-field="publish" data-module="<?php echo $module; ?>" data-where="id"><?php echo $status; ?></td>
                                <td class="text-center">
                                    <a type="button" href="<?php echo base_url('backend/currency/currency/update/'.$val['id']) ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                    <a type="button" href="<?php echo base_url('backend/currency/currency/delete/'.$val['id']) ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php }}else{ ?>
                                <tr>
                                    <td colspan="100%"><span class="text-danger"><?php echo translate('cms_lang.currency.empty', $language) ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                    <div id="pagination">
                        <?php echo (isset($pagination)) ? $pagination : ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>