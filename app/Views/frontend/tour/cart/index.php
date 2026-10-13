<?php 
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
        <div class="layout-cart-body">
            
            <div id="cart-page" class="page-container">
                <div class="uk-container uk-container-center">
                    <div class="uk-flex">
                        <div class="main-cart mb20">
                            <form action="Frontend/Tour/Cart" method="post" class=" form form-cart">
                                <section class="cart-panel delivery delivery-address mb30">
                                    <header class="panel-head mb10">
                                        <div class="breadcrumb-cart mb30">
                                            <div class="main-header">
                                                <a href="." class="logo uk-display-block mb10" title="<?php echo $general['homepage_brand'] ?>">
                                                    <h1 class="logo-text"><?php echo $general['homepage_brand'] ?></h1>
                                                </a>
                                                <ul class="uk-breadcrumb">
                                                    <li><a href="" title=""><i class="fa fa-home"></i> Trang chủ</a></li>
                                                    
                                                    <li><a href="https://kimlientravel.com.vn/dat-mua.html" title="Giỏ hàng">Giỏ hàng</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <h2 class="heading mb10"><span>Thông tin giao hàng</span></h2>
                                        <p class="section-content-text">
                                            Bạn đã có tài khoản?
                                            <a href="login.html" title="Đăng nhập" class="btn-login">
                                                Đăng nhập
                                            </a>
                                        </p>
                                    </header>
                                    <section class="panel-body">
                                        <div class="form-row mb10">
                                            <div class="input-field">
                                                <label class="field-label" for="checkout_user_fullname">Họ và tên</label>
                                                <input type="text" name="fullname" id="checkout_user_fullname" value="<?php echo (isset($customer['fullname']) ? $customer['fullname'] : '') ?>" class="text " placeholder="Họ và tên">
                                            </div>
                                        </div>
                                        <div class="form-row mb10">
                                            <div class="input-field">
                                                <label class="field-label" for="checkout_user_phone">Số điện thoại</label>
                                                <input type="text" name="phone" id="checkout_user_phone"  value="<?php echo (isset($customer['phone']) ? $customer['phone'] : '') ?>" class="text " placeholder="Số điện thoại">
                                            </div>
                                        </div>
                                        <div class="form-row mb10">
                                            <div class="input-field">
                                                <label class="field-label" for="checkout_user_email">Email</label>
                                                <input type="text" id="checkout_user_email" value="<?php echo (isset($customer['email']) ? $customer['email'] : '') ?>" name="email" class="text " placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-row mb10">
                                            <div class="input-field">
                                                <label class="field-label" for="checkout_user_address">Địa chỉ</label>
                                                <input type="text" id="checkout_user_address" value="<?php echo (isset($customer['address']) ? $customer['address'] : '') ?>" name="address" class="text " placeholder="Địa chỉ">
                                            </div>
                                        </div>
                                        <div class="uk-flex mb10">
                                            <div class="form-row  w50 mr10">
                                                <div class="input-field">
                                                    <label class="field-label" for="city">Tỉnh/Thành phố</label>
                                                    <script>
                                                        var cityid = '<?php echo (isset($customer['cityid'])) ? $customer['cityid'] : ''; ?>';
                                                        var districtid = '<?php echo (isset($customer['districtid'])) ? $customer['districtid'] : ''; ?>'
                                                        var wardid = '<?php echo (isset($customer['wardid'])) ? $customer['wardid'] : ''; ?>'
                                                    </script>
                                                   <?php 
                                                        $city = get_data(['select' => 'provinceid, name','table' => 'vn_province','order_by' => 'order desc, name asc']);
                                                        $city = convert_array([
                                                            'data' => $city,
                                                            'field' => 'provinceid',
                                                            'value' => 'name',
                                                            'text' => 'Thành Phố',
                                                        ]);
                                                    ?>
                                                    <?php echo form_dropdown('cityid', $city, set_value('cityid', (isset($user['cityid'])) ? $user['cityid'] : 0), 'class="form-control m-b city select2"  id="city"');?>
                                                </div>
                                            </div>
                                            <div class="form-row  w50 ">
                                                <div class="input-field">
                                                    <label class="field-label" for="district">Quận/Huyện</label>
                                                    <select name="districtid" id="district" class="form-control m-b location select2">
                                                        <option value="0">[Chọn Quận/Huyện]</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row mb10 ">
                                            <div class="input-field">
                                                <label class="field-label" for="checkout_user_message">Lời nhắn</label>
                                                <textarea name="message" id="checkout_user_message" class=" form-textarea" placeholder="Lời nhắn"><?php echo (isset($customer['message']) ? $customer['message'] : '') ?></textarea>
                                            </div>
                                        </div>
                                    </section><!-- .panel-body -->
                                </section><!-- .delivery -->
                                
                                <div  class="section section-payment-method mb30">
                                    <div class="section-header">
                                        <h2 class="section-title">Hình thức thanh toán</h2>
                                    </div>
                                    <div class="section-content">
                                        <div class="content-box">
                                            <div class="radio-wrapper content-box-row">
                                                <label class="radio-label" for="payment_home">
                                                    <div class="radio-input">
                                                        <input id="payment_home" class="input-radio" name="payment_method_id" type="radio" value="home" checked="">
                                                    </div>
                                                    <span class="radio-label-primary">Giao hàng và thu tiền tại nhà (COD)</span>
                                                </label>
                                            </div>
                                            <div class="radio-wrapper content-box-row">
                                                <label class="radio-label" for="payment_store">
                                                    <div class="radio-input">
                                                        <input id="payment_store" class="input-radio" name="payment_method_id" type="radio" value="store">
                                                    </div>
                                                    <span class="radio-label-primary">Nhận hàng và thanh toán tại cửa hàng</span>
                                                </label>
                                            </div>
                                            
                                            <div class="radio-wrapper content-box-row content-box-row-secondary hidden" for="payment_store">
                                                <div class="blank-slate">
                                                    <div class="store-title mb10">
                                                        Quý khách có thể đến một trong các địa chỉ sau để thanh toán và nhận hàng:
                                                    </div>
                                                    <div class="store-company">
                                                        <?php echo $general['contact_address'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="radio-wrapper content-box-row">
                                                <label class="radio-label" for="payment_bank">
                                                    <div class="radio-input">
                                                        <input id="payment_bank" class="input-radio" name="payment_method_id" type="radio" value="bank">
                                                    </div>
                                                    <span class="radio-label-primary">Chuyển khoản qua máy ATM & Ngân hàng</span>
                                                </label>
                                            </div>
                                            <div class="radio-wrapper content-box-row content-box-row-secondary hidden" for="payment_bank">
                                                <div class="blank-slate">
                                                    <div class="store-title mb10">
                                                        Quý khách có thể chuyển khoản qua Internet Banking hoặc truyền thống tới tài khoản ngân hàng của chúng tôi.
                                                    </div>
                                                    <div class="store-company">
                                                        <?php echo $general['contact_bank'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="radio-wrapper content-box-row">
                                                <label class="radio-label" for="payment_method_id_1002194406">
                                                    <div class="radio-input">
                                                        <input id="payment_method_id_1002194406" class="input-radio" name="payment_method_id" type="radio" value="1002194406">
                                                    </div>
                                                    <span class="radio-label-primary">Thanh toán online qua ví MoMo</span>
                                                </label>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                <div  class="section cart-panel section-additional mb20">
                                    <div class="section-header">
                                        <h2 class="section-title">Bổ sung</h2>
                                    </div>
                                    <div class="section-content">
                                        <div class="content-box">
                                            <div class="radio-wrapper content-box-row">
                                                <label class="radio-label" for="vat_form">
                                                    <div class="radio-input">
                                                        <input id="vat_form" class="input-radio" name="form_vat" type="checkbox" value="vat" >
                                                    </div>
                                                    <span class="radio-label-primary">Yêu cầu xuất hóa đơn VAT cho công ty hoặc tổ chức</span>
                                                </label>
                                            </div>
                                            <div class="radio-wrapper content-box-row content-box-row-secondary hidden" for="vat_form">
                                                <div class="blank-slate">
                                                    <div class="form-vat-panel">
                                                        <div class="form-row mb10">
                                                            <div class="uk-flex uk-flex-middle">
                                                                <div class="name-input">
                                                                    Mã số thuế
                                                                </div>
                                                                <div class="input-field">
                                                                    <label class="field-label" for="customer_id_vat">Mã số thuế</label>
                                                                    <input type="text" name="customer_id_vat" id="customer_id_vat" value="" class="text " placeholder="Mã số thuế">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb10">
                                                            <div class="uk-flex uk-flex-middle">
                                                                <div class="name-input">
                                                                    Tên Công ty
                                                                </div>
                                                                <div class="input-field">
                                                                    <label class="field-label" for="customer_company">Tên Công ty</label>
                                                                    <input type="text" name="customer_company" id="customer_company" value="" class="text " placeholder="Tên Công ty">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb10">
                                                            <div class="uk-flex uk-flex-middle">
                                                                <div class="name-input">
                                                                    Địa chỉ
                                                                </div>
                                                                <div class="input-field">
                                                                    <label class="field-label" for="customer_address">Địa chỉ</label>
                                                                    <input type="text" name="customer_address" id="customer_address" value="" class="text " placeholder="Địa chỉ">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="finish-cart-box uk-text-right">
                                    <button type="submit" name="create" value="create" class="btn-finish-cart">Hoàn tất đơn hàng</button>
                                </div>
                            </form><!-- form -->
                        </div><!-- .main-content -->
                        <?php 
                            $sum_price = 0;
                        ?>
                        <aside class="aside-content">
                            <form class="" id="cartForm" method="post" action="">
                                <section class="cart-panel panel-order">
                                    <header class="panel-head uk-flex uk-flex-middle uk-flex-space-between">
                                        <h2 class="heading">Đơn hàng (<span class="count"><?php echo ((isset($cart)) ? count($cart) : 0) ?> Sản phẩm</span>)</h2>
                                        <a class="link" href="">Thay đổi</a>
                                    </header>
                                    <div class="panel-body mb20">
                                        <?php 
                                            if(isset($cart) && is_array($cart) && count($cart)) {
                                        ?>
                                            <ul class="uk-list list-product-cart">
                                                <?php 
                                                    foreach ($cart as $key => $value) {
                                                 ?>
                                                    <li class="mb10">
                                                        <input type="hidden" name="code_tour" class="code_tour_hidden" value="<?php echo $value['code'] ?>">
                                                        <div class="box uk-clearfix">
                                                            <div class="prd-infor uk-clearfix">
                                                                <div class="thumb">
                                                                    <?php echo render_a(BASE_URL.$value['url'].HTSUFFIX,$value['title'],'class="image img-scaledown"',render_img($value['avatar'],$value['title'])) ?>
                                                                </div>
                                                                <div class="desc">
                                                                    <h3 class="title">
                                                                        <?php echo render_a(BASE_URL.$value['url'].HTSUFFIX,$value['title'],'',$value['title']) ?>
                                                                    </h3>
                                                                                                                
                                                                    <button type="button" class="uk-button fc-cart-remove" data-cart-id="" style="border:0;background:0;font-size:11px;padding:0;color:#1d61ae;"><i class="fa fa-trash"></i> Xóa</button>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="prd-price">
                                                                <div class="price price_view"><?php echo number_format(check_isset($value['price']),0,',','.') ?></div>
                                                                <div class="number_quantity"  style="color:red;">x <input min="1"  class="input-quantity" id="" type="number" value="<?php echo $value['quantity'] ?>" name="quantity" style="width:50px;text-align:right;"></div>
                                                                <div class="price new_price"><?php echo number_format(cal_quantity($value['price'], $value['quantity']),0,',','.') ?></div>
                                                            </div>
                                                        </div><!-- .box -->
                                                        <div class="go">
                                                            <?php 
                                                                $input = false;
                                                                if(isset($value['day_start']) && $value['day_start'] != ''){
                                                                    $day = explode(',',$value['day_start']);
                                                                    foreach ($day as $keyDay => $valueDay) {
                                                                        $check  = strtolower(removeutf8($valueDay));
                                                                        if($check == 'hang ngay'){
                                                                            $input = true;
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                            <?php if($input == true){ ?>
                                                                <div class="day_start uk-flex uk-flex-middle">
                                                                    <div class="lablel-day-start" style="width: 100px;">
                                                                        Chọn ngày: 
                                                                    </div>
                                                                    <input type="date" name="day_start['<?php echo $value['code'] ?>']" value="<?php echo gmdate('Y-m-d', time() + 7*3600) ?>" style="width: calc(100% - 100px);padding: 0 10px;">    
                                                                </div>
                                                            <?php }else{ ?>
                                                                <div class="day_start uk-flex uk-flex-middle">
                                                                    <div class="lablel-day-start" style="width: 100px;">
                                                                        Chọn ngày: 
                                                                    </div>
                                                                    <div class="day_start_tag uk-flex uk-flex-middle uk-flex-wrap" style="width: calc(100% - 100px);">
                                                                        <?php 
                                                                            if(isset($day) && is_array($day) && count($day)){
                                                                                foreach ($day as $keyDay => $valueDay) {
                                                                         ?>
                                                                            <div class="select_day_start ">
                                                                                <input type="radio" class="radio_day" <?php echo ($keyDay == 0) ? 'checked="checked"' : ''?> id="<?php echo slug($valueDay).'_in_'.$value['code'] ?>" name="day_start['<?php echo $value['code'] ?>']" value="<?php echo $valueDay ?>" style="display: none">
                                                                                <label for="<?php echo slug($valueDay).'_in_'.$value['code'] ?>" class="element_day"> <?php echo $valueDay ?></label><br>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            <?php }} ?>
                                                        </div>
                                                    </li>
                                                    <?php $sum_price = $sum_price+cal_quantity($value['price'], $value['quantity']); ?>
                                                <?php } ?>
                                            </ul>   
                                        <?php }else{ ?>
                                            <p class="info-cart text-danger">Chưa có đơn hàng nào được chọn</p>
                                        <?php } ?>
                                    </div>
                                    <footer class="panel-foot">
                                        <div class="total-amount">
                                            <div class="uk-flex uk-flex-middle uk-flex-space-between item">
                                                <div class="label">Tổng tiền</div>
                                                <div class="value" id="subtotal"><?php echo number_format($sum_price,0,',','.') ?></div>
                                            </div>
                                        </div>
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between total-purchase">
                                            <div class="label">Số tiền còn lại cần thanh toán</div>
                                            <div class="value" id="total"><?php echo number_format($sum_price,0,',','.') ?></div>
                                        </div>
                                    </footer>
                                </section><!-- .panel-order -->
                            </form>
                        </aside><!-- .aside -->
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('keyup','.input-field input', function(){
                    let _this = $(this);
                    let val = _this.val();
                    if(val.length != 0){
                        _this.parents('.input-field').addClass('field-show-floating-label');
                    }else{
                        _this.parents('.input-field').removeClass('field-show-floating-label');
                    }
                });
                $(document).on('keyup','.input-field textarea', function(){
                    let _this = $(this);
                    let val = _this.val();
                    if(val.length != 0){
                        _this.parents('.input-field').addClass('field-show-floating-label');
                    }else{
                        _this.parents('.input-field').removeClass('field-show-floating-label');
                    }
                });
                $('body').on('change', '.section-payment-method input:radio', function() {
                    $('.section-payment-method .content-box-row.content-box-row-secondary').addClass('hidden');

                    let id = $(this).attr('id');
                    console.log(id);
                    if(id) {
                        let sub = $('body').find('.content-box-row.content-box-row-secondary[for=' + id + ']')
                        
                        if(sub && sub.length > 0) {
                            $(sub).removeClass('hidden');
                        }
                    }
                });
                $('body').on('change', '.section-additional input:checkbox', function() {
                    let id = $(this).attr('id');
                    let sub = $('body').find('.content-box-row.content-box-row-secondary[for=' + id + ']')
                    if($(this).prop("checked") == true){
                        $(sub).removeClass('hidden');
                    }else{
                        $(sub).addClass('hidden');

                    }
                });
            })
        </script>

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

        <?php echo $system['general_script_bottom']['content'] ?>
    </body>
</html>
