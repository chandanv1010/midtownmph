<nav class="navbar-default navbar-static-side" role="navigation">
    <?php
        $user = authentication();
        $uri = service('uri');
        $uri = current_url(true);
        $uriModule = $uri->getSegment(2);
        $uriModule_name = $uri->getSegment(3);
        $baseController = new App\Controllers\BaseController();
        $sidebar = new App\Controllers\Api\Sidebar\Sidebar();
        // pre($sidebar);
        $language = $baseController->currentLanguage();
        $count_contact = count_contact();
        $permission_root = (isset($user['email']) && $user['email'] == 'htvietnam@gmail.com' || $user['email'] == 'tudo2109@gmail.com')? true:false;
    ?>
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                    <img alt="image" class="img-circle" src="<?php echo $user['image']; ?>" style="min-width:48px;height:48px;" />
                     </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="<?php echo site_url('profile') ?>">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold" style="color:#fff"><?php echo $user['fullname'] ?></strong>
                     </span> <span class="text-muted text-xs block"><?php echo $user['job'] ?> <b class="caret" style="color: #8095a8"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?php echo base_url('backend/user/profile/profile/'.$user['id']) ?>">Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url('backend/authentication/auth/logout') ?>">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
           <li class="landing_link">
                <a  href="<?php echo base_url('backend/dashboard/dashboard/index') ?>"><i class="fa fa-star"></i> <span class="nav-label">Dashboard</span> <span class="label label-warning pull-right">NEW</span></a>
            </li>

           <li class="<?php echo ( $uriModule == 'project') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-file"></i> <span class="nav-label"><?php echo translate('cms_lang.sidebar.sb_project', $language) ?></span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo ( $uriModule_name == 'catalogue') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/project/catalogue/index') ?>"><?php echo translate('cms_lang.sidebar.sb_project', $language) ?></a></li>
                    <?php /*
                    <li class="<?php echo ( $uriModule_name == 'project') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/project/project/index') ?>"><?php echo translate('cms_lang.sidebar.sb_project', $language) ?></a></li>
                    */ ?>
                </ul>
            </li>

            <li class="<?php echo ( $uriModule == 'product' || $uriModule_name == 'attribute') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-desktop"></i> <span class="nav-label">QL Căn Hộ</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo ( $uriModule_name == 'product') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/product/product/index') ?>">QL Căn hộ</a></li>
                    <li class="<?php echo ( $uriModule_name == 'attribute') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/attribute/attribute/index') ?>"><?php echo translate('cms_lang.sidebar.sb_attr', $language) ?></a></li>
                </ul>
            </li>
            <li class="<?php echo ( $uriModule == 'media') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-file"></i> <span class="nav-label">Tổng quan dự án</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo ( $uriModule_name == 'catalogue') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/media/catalogue/index') ?>"><?php echo translate('cms_lang.sidebar.sb_media_catalogue', $language) ?></a></li>
                    <li class="<?php echo ( $uriModule_name == 'media') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/media/media/index') ?>"><?php echo translate('cms_lang.sidebar.sb_media', $language) ?></a></li>
                </ul>
            </li>



            <li class="<?php echo ( $uriModule_name == 'brand') ? 'active'  : '' ?>">
                <a href="<?php echo base_url('backend/product/brand/brand/index') ?>"><i class="fa fa-folder-open-o" aria-hidden="true"></i><span class="nav-label">QL Tiện nghi</span> </a>
            </li>

            

            <li class="<?php echo ( $uriModule == 'article') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-file"></i> <span class="nav-label"><?php echo translate('cms_lang.sidebar.sb_article', $language) ?></span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo ( $uriModule_name == 'catalogue') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/article/catalogue/index') ?>"><?php echo translate('cms_lang.sidebar.sb_article_catalogue', $language) ?></a></li>
                    <li class="<?php echo ( $uriModule_name == 'article') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/article/article/index') ?>"><?php echo translate('cms_lang.sidebar.sb_article', $language) ?></a></li>
                </ul>
            </li>

            <li class="<?php echo ( $uriModule_name == 'brand') ? 'active'  : '' ?>">
                <a href="<?php echo base_url('backend/article/article/floor') ?>"><i class="fa fa-folder-open-o" aria-hidden="true"></i><span class="nav-label">QL Mặt Bằng</span> </a>
            </li>

            <li class="<?php echo ( $uriModule == 'media') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-file"></i> <span class="nav-label"><?php echo translate('cms_lang.sidebar.sb_media', $language) ?></span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo ( $uriModule_name == 'catalogue') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/media/catalogue/index') ?>"><?php echo translate('cms_lang.sidebar.sb_media_catalogue', $language) ?></a></li>
                    <li class="<?php echo ( $uriModule_name == 'media') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/media/media/index') ?>"><?php echo translate('cms_lang.sidebar.sb_media', $language) ?></a></li>
                </ul>
            </li>

            <li class="<?php echo ( $uriModule == 'contact') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-address-card-o" aria-hidden="true"></i> <span class="nav-label"><?php echo translate('cms_lang.sidebar.sb_contact', $language) ?> <span class="text-warning">(<?php echo $count_contact ?>)</span></span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo ( $uriModule_name == 'catalogue') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/contact/catalogue/index') ?>"><?php echo translate('cms_lang.sidebar.sb_contactCatalogue', $language) ?></a></li>

                    <li class="<?php echo ( $uriModule_name == 'contact') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/contact/contact/index') ?>"><?php echo translate('cms_lang.sidebar.sb_contact', $language) ?> <span class="text-warning">(<?php echo $count_contact ?>)</span></a></li>
                </ul>
            </li>
            <li class="<?php echo ( $uriModule == 'currency') ? 'active'  : '' ?>">
                <a href="<?php echo base_url('backend/currency/currency/index') ?>"><i class="fa fa-folder-open-o" aria-hidden="true"></i><span class="nav-label">QL Tiền tệ</span> </a>
            </li>
            <li class="<?php echo ( $uriModule == 'comment') ? 'active'  : '' ?>">
                <a href="<?php echo base_url('backend/comment/comment/index') ?>"><i class="fa fa-folder-open-o" aria-hidden="true"></i><span class="nav-label">QL Đánh giá</span> </a>
            </li>
            <li class="<?php echo ( $uriModule == 'user') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label"><?php echo translate('cms_lang.sidebar.sb_user', $language) ?></span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo ( $uriModule_name == 'catalogue') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/user/catalogue/index') ?>"><?php echo translate('cms_lang.sidebar.sb_user_catalogue', $language) ?></a></li>
                    <li class="<?php echo ( $uriModule_name == 'user') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/user/user/index') ?>"><?php echo translate('cms_lang.sidebar.sb_user', $language) ?></a></li>
                </ul>
            </li>
            <li class="<?php echo ( $uriModule == 'language' || $uriModule == 'system'  || $uriModule == 'panel' || $uriModule == 'slide' || $uriModule == 'widget' || $uriModule == 'menu') ? 'active'  : '' ?>">
                <a href="index.html"><i class="fa fa-cog"></i> <span class="nav-label"><?php echo translate('cms_lang.sidebar.sb_setting', $language) ?></span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <!-- <li class="<?php echo ( $uriModule_name == 'language') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/language/language/index') ?>"><?php echo translate('cms_lang.sidebar.sb_language', $language) ?></a></li> -->
                    <li class="<?php echo ( $uriModule_name == 'slide') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/slide/slide/index') ?>"><?php echo translate('cms_lang.sidebar.sb_slide', $language) ?></a></li>
                    <li class="<?php echo ( $uriModule_name == 'panel') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/panel/panel/index') ?>"><?php echo translate('cms_lang.sidebar.sb_panel', $language) ?></a></li>
                    <li class="<?php echo ( $uriModule_name == 'general') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/system/general/index') ?>"><?php echo translate('cms_lang.sidebar.sb_general', $language) ?></a></li>
                     <li class="<?php echo ( $uriModule_name == 'widget') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/widget/widget/index') ?>"><?php echo translate('cms_lang.sidebar.sb_widget', $language) ?></a></li>
                    <li class="<?php echo ( $uriModule_name == 'menu') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/menu/menu/listmenu') ?>"><?php echo translate('cms_lang.sidebar.sb_menu', $language) ?></a></li>
                    <?php if ($permission_root): ?>
                        <li class="<?php echo ( $uriModule_name == 'system') ? 'active'  : '' ?>"><a href="<?php echo base_url('backend/system/system/store') ?>">Quản lý Hệ thống</a></li>
                    <?php endif ?>

                </ul>
            </li>
            <li class="landing_link">
                <a  href="<?php echo base_url('backend/dashboard/dashboard/index') ?>"><i class="fa fa-star"></i> <span class="nav-label">Dashboard</span> <span class="label label-warning pull-right">NEW</span></a>
            </li>
        </ul>
    </div>
</nav>
