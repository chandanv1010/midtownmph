<?php 
    helper('mydatafrontend');
    $widget['data'] = widget_frontend();
    $system = get_system();
 ?>
<!DOCTYPE html>
<html lang="vi-VN">
    <head>
        <!-- CONFIG -->
        <base href="<?php echo BASE_URL ?>" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index,follow" />
        <meta name="author" content="<?php echo (isset($general['homepage_company'])) ? $general['homepage_company'] : ''; ?>" />
        <meta name="copyright" content="<?php echo (isset($general['homepage_company'])) ? $general['homepage_company'] : ''; ?>" />
        <meta http-equiv="refresh" content="1800" />
        <link rel="icon" href="<?php echo $general['homepage_favicon'] ?>" type="image/png" sizes="30x30">
        <!-- GOOGLE -->
        <title><?php echo isset($meta_title)?htmlspecialchars($meta_title):'';?></title>
        <meta name="description"  content="<?php echo isset($meta_description)?htmlspecialchars($meta_description):'';?>" />
        <?php echo isset($canonical)?'<link rel="canonical" href="'.$canonical.'" />':'';?>
        <meta property="og:locale" content="vi_VN" />

        <!-- for Facebook -->
        <meta property="og:title" content="<?php echo (isset($meta_title) && !empty($meta_title))?htmlspecialchars($meta_title):'';?>" />
        <meta property="og:type" content="<?php echo (isset($og_type) && $og_type != '') ? $og_type : 'article'; ?>" />
        <meta property="og:image" content="<?php echo (isset($meta_image) && !empty($meta_image)) ? $meta_image : base_url(isset($general['homepage_logo']) ? $general['homepage_logo'] : ''); ?>" />
        <?php echo isset($canonical)?'<meta property="og:url" content="'.$canonical.'" />':'';?>
        <meta property="og:description" content="<?php echo (isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):'';?>" />
        <meta property="og:site_name" content="<?php echo (isset($general['homepage_company'])) ? $general['homepage_company'] : ''; ?>" />
        <meta property="fb:admins" content=""/>
        <meta property="fb:app_id" content="" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="<?php echo isset($meta_title)?htmlspecialchars($meta_title):'';?>" />
        <meta name="twitter:description" content="<?php echo (isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):'';?>" />
        <meta name="twitter:image" content="<?php echo (isset($meta_image) && !empty($meta_image))?$meta_image:base_url((isset($general['homepage_logo'])) ? $general['homepage_logo']  : '');?>" />

        <?php 
            $check_css = false;
            foreach ($system as $key => $value) {
                if(isset($module) && $value['module'] == $module && $value['keyword'] == $module.'_css'){
                    $check_css = true;
                    echo $system[$value['module'].'_css']['content'];
                }
            } 
            if($check_css == false){
                echo $system['normal_css']['content'];
            }

        ?>
        <link href="public/frontend/resources/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
        <?php echo view('frontend/homepage/common/style', $widget) ?>
        <?php echo view('frontend/homepage/common/head') ?>
        <?php echo $system['general_css']['content'] ?>
        <?php echo $system['general_script_top']['content'] ?>
        <script type="text/javascript">
            var BASE_URL = '<?php echo BASE_URL; ?>';
            var SUFFIX = '<?php echo HTSUFFIX; ?>';
        </script>
        <?php echo $general['analytic_google_analytic'] ?>
        <?php echo $general['facebook_facebook_pixel'] ?>
    </head>
    <body>
        <?php echo view('frontend/homepage/common/schema') ?>
        <div class="wrap-login">
            <div class="header-login">
                <div class="uk-container uk-container-center ">
                    <div class="uk-flex uk-flex-space-between uk-flex-middle" style="padding: 20px 0px 20px 0px">
                        <div class="header-title-panel uk-flex uk-flex-middle">
                                <?php echo logo($general['homepage_logo']) ?>
                                <span class="login-title ml20">Đăng nhập</span>
                        </div>
                    </div>
                   
                </div>
                <div class="login-panel">
                    <div class="uk-container-center uk-container">
                        <div class="uk-grid uk-grid-medium uk-flex-middle uk-grid-width-medium-1-1 uk-grid-width-large-1-2 uk-clearfix">
                            <div class="wrap-login">
                                <div class="login-body">
                                    <div class="img-login mb30">
                                        <?php echo logo($general['homepage_logo']) ?>
                                    </div>
                                    <div class="login-company">
                                        <p><?php echo $general['homepage_company'] ?>p</p>
                                    </div>
                                </div>
                            </div>
                            <div class="wrap-login ">
                                <form action="" class="forgot-form w100">
                                    <div class="login-form-body ">
                                        <div class="heading-login uk-position-relative text-center mb30">
                                            Quên mật khẩu
                                            <a href="login.html" class="btn-back-login" title="Đăng nhập" ><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="login-input">
                                            <div class="input-login mb30 email-check">
                                                <input class=""  type="text" name="email" placeholder="Email...">
                                            </div>
                                            <div class="input-login mb30 email-check-otp-forgot hidden">
                                                <input class=""  type="text" name="otp" placeholder="OTP...">
                                            </div>
                                            <div class="button-forgot mb10">
                                                <button onclick="return false;" class="btn-get-otp-forgot">Nhận mã OTP</button>
                                                <button onclick="return false;" class="btn-submit-forgot hidden">Xác nhận</button>
                                            </div>
                                            <a href="" title="send-back" class="send-back-otp hidden">Gửi lại OTP</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo view('frontend/homepage/common/footer') ?>
        </div>
        <!-- Tao Widget -->
        <?php 
            foreach ($widget['data'] as $key => $value) {
                echo  str_replace("[phone]", isset($general['contact_phone']) ? $general['contact_phone'] : '', $value['html']);
                echo '<script>'.$value['script'].'</script>';
            }
        ?>

        <?php 
            $check_script = false;
            foreach ($system as $key => $value) {
                if(isset($module) && $value['module'] == $module && $value['keyword'] == $module.'_script'){
                    $check_script = true;
                    echo $system[$value['module'].'_script']['content'];
                }
            } 
            if($check_script == false){
                echo $system['normal_script']['content'];
            }

        ?>
        <script src="public/frontend/resources/plugins/jquery.lazyload.min.js" type="text/javascript"></script>
        <script src="public/frontend/resources/plugins/jquery.scrollstop.min.js" type="text/javascript"></script>
        <script src="public/frontend/resources/plugins/select2/dist/js/select2.min.js"></script>
        <script src="public/frontend/resources/plugins.js"></script>
        <script src="public/frontend/resources/login.js"></script>
    </body>
</html>

